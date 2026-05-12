<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_ADMIN_CREATE = 'admin_create';
    const SCENARIO_ADMIN_UPDATE = 'admin_update';

    public $password_input;
    public $password_repeat;
    public $rules_agreement;

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            
            [['name', 'surname', 'login', 'email', 'password_input', 'password_repeat', 'rules_agreement'], 'required', 'on' => self::SCENARIO_REGISTER],
            
            
            [['name', 'surname', 'login', 'email', 'password_input', 'password_repeat'], 'required', 'on' => self::SCENARIO_CREATE],
            [['name', 'surname', 'login', 'email'], 'required', 'on' => self::SCENARIO_ADMIN_CREATE],
                        
            [['name', 'surname', 'login', 'email'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_ADMIN_UPDATE]],
                        
            [['name', 'surname', 'patronymic'], 'match', 'pattern' => '/^[а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Разрешены только кириллица, пробел и тире'],
            ['login', 'match', 'pattern' => '/^[a-zA-Z0-9\-]+$/', 'message' => 'Разрешены только латиница, цифры и тире'],
            ['login', 'unique', 'message' => 'Этот логин уже занят'],
            ['email', 'email', 'message' => 'Некорректный формат email'],
            ['email', 'unique', 'message' => 'Этот email уже занят'],
            ['password_input', 'string', 'min' => 6, 'message' => 'Пароль должен содержать минимум 6 символов', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_CREATE]],
            ['password_repeat', 'compare', 'compareAttribute' => 'password_input', 'message' => 'Пароли не совпадают', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_CREATE]],
            ['rules_agreement', 'compare', 'compareValue' => true, 'message' => 'Вы должны согласиться с правилами', 'on' => self::SCENARIO_REGISTER],
            
            [['name', 'surname', 'patronymic', 'login', 'email'], 'string', 'max' => 100],
            [['password_input', 'password_repeat', 'rules_agreement'], 'safe'],
            [['is_admin', 'is_blocked', 'rules'], 'integer'],

            ['is_blocked', 'boolean'],
            ['is_blocked', 'default', 'value' => 0],
            ['rules', 'boolean'],
            ['rules', 'default', 'value' => 0],  
            
            [['is_expert'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'login' => 'Логин',
            'email' => 'Email',
            'password_input' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
            'rules_agreement' => 'Согласие с правилами',
            'rules' => 'Согласие с правилами',
            'is_admin' => 'Администратор',
            'is_blocked' => 'Заблокирован',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['name', 'surname', 'patronymic', 'login', 'email', 'password_input', 'password_repeat', 'rules_agreement', 'rules'];
        $scenarios[self::SCENARIO_CREATE] = ['name', 'surname', 'patronymic', 'login', 'email', 'password_input', 'password_repeat', 'is_admin', 'is_blocked', 'rules'];
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'surname', 'patronymic', 'login', 'email', 'password_input', 'password_repeat'];
        $scenarios[self::SCENARIO_ADMIN_CREATE] = ['name', 'surname', 'patronymic', 'login', 'email', 'password_input', 'password_repeat', 'is_admin', 'is_blocked', 'rules'];
        $scenarios[self::SCENARIO_ADMIN_UPDATE] = ['name', 'surname', 'patronymic', 'login', 'email', 'password_input', 'password_repeat', 'is_admin', 'is_blocked', 'rules'];
        return $scenarios;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
            }
            if (!empty($this->password_input)) {
                $this->setPassword($this->password_input);
            }
            
            if ($this->scenario === self::SCENARIO_REGISTER && $this->rules_agreement) {
                $this->rules = 1;
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();        
        $this->password_input = '';
        $this->password_repeat = '';        
    }

    public function getRole()
    {
        if ($this->is_admin) return 'admin';
        if ($this->is_expert) return 'expert';
        return 'participant';
    }

    /**
     * Проверяет, заблокирован ли пользователь
     */
    public function isBlocked()
    {
        return $this->is_blocked == 1;
    }

    public function isExpert()
    {
        return $this->is_expert == 1; 
    }

    public function isParticipant()
    {
        return !$this->is_admin && !$this->is_expert;
    }

    public function getFullName()
    {
        return $this->surname . ' ' . $this->name . ($this->patronymic ? ' ' . $this->patronymic : '');
    }

    // IdentityInterface methods
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Находит пользователя по логину или email с проверкой на блокировку
     */
    public static function findByLogin($login)
    {
        return static::find()
            ->where(['login' => $login])
            ->orWhere(['email' => $login])
            ->andWhere(['is_blocked' => 0]) // Только незаблокированные пользователи
            ->one();
    }

    public static function findByUsername($username)
    {
        return static::find()->where(['login' => $username])->orWhere(['email' => $username])->one();
    }

    /**
     * Находит пользователя по логину или email без проверки на блокировку
     * (для использования в админ-панели)
     */
    public static function findByLoginForAdmin($login)
    {
        return static::find()
            ->where(['login' => $login])
            ->orWhere(['email' => $login])
            ->one();
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
