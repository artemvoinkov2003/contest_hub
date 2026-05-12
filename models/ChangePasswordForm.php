<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;

    private $_user;

    public function __construct($user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['currentPassword', 'validateCurrentPassword'],
            ['newPassword', 'string', 'min' => 6, 'message' => 'Пароль должен содержать минимум 6 символов'],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли не совпадают'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'currentPassword' => 'Текущий пароль',
            'newPassword' => 'Новый пароль',
            'newPasswordRepeat' => 'Подтвердите пароль',
        ];
    }

    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->currentPassword)) {
                $this->addError($attribute, 'Неверный текущий пароль');
            }
        }
    }

    public function changePassword()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        $user->password_input = $this->newPassword;
        $user->password_repeat = $this->newPasswordRepeat;

        return $user->save();
    }
}