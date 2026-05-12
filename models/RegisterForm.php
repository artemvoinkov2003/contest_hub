<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $name;
    public $surname;
    public $patronymic;
    public $login;
    public $email;
    public $password;
    public $password_repeat;
    public $rules;

    public function rules()
    {
        return [
            [['name', 'surname', 'login', 'email', 'password', 'password_repeat', 'rules'], 'required'],
            [['name', 'surname', 'patronymic'], 'match', 'pattern' => '/^[а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Разрешены только кириллица, пробел и тире'],
            ['login', 'match', 'pattern' => '/^[a-zA-Z0-9\-]+$/', 'message' => 'Разрешены только латиница, цифры и тире'],
            ['login', 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят'],
            ['email', 'email', 'message' => 'Некорректный формат email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже занят'],
            ['password', 'string', 'min' => 6, 'message' => 'Пароль должен содержать минимум 6 символов'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['rules', 'compare', 'compareValue' => true, 'message' => 'Вы должны согласиться с правилами'],
            [['name', 'surname', 'patronymic', 'login', 'email'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'login' => 'Логин',
            'email' => 'Почта',
            'password' => 'Пароль',
            'password_repeat' => 'Повторите пароль',
            'rules' => 'Согласие с правилами',
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->scenario = User::SCENARIO_REGISTER;
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->patronymic = $this->patronymic;
        $user->login = $this->login;
        $user->email = $this->email;
        $user->password_input = $this->password; 
        $user->password_repeat = $this->password_repeat; 
        $user->rules_agreement = $this->rules;

        if ($user->save()) {
            return $user;
        }

        if ($user->hasErrors()) {
            foreach ($user->getErrors() as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->addError($attribute, $error);
                }
            }
        }

        return false;
    }
}