<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ProfileForm extends Model
{
    public $name;
    public $surname;
    public $patronymic;
    public $login;
    public $email;

    private $_user;

    public function __construct($user, $config = [])
    {
        $this->_user = $user;
        $this->name = $user->name;
        $this->surname = $user->surname;
        $this->patronymic = $user->patronymic;
        $this->login = $user->login;
        $this->email = $user->email;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'surname', 'login', 'email'], 'required'],
            [['name', 'surname', 'patronymic'], 'match', 'pattern' => '/^[а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Разрешены только кириллица, пробел и тире'],
            ['login', 'match', 'pattern' => '/^[a-zA-Z0-9\-]+$/', 'message' => 'Разрешены только латиница, цифры и тире'],
            ['login', 'unique', 'targetClass' => User::class, 'filter' => ['!=', 'id', $this->_user->id], 'message' => 'Этот логин уже занят'],
            ['email', 'email', 'message' => 'Некорректный формат email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => ['!=', 'id', $this->_user->id], 'message' => 'Этот email уже занят'],
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
        ];
    }

    public function updateProfile()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->patronymic = $this->patronymic;
        $user->login = $this->login;
        $user->email = $this->email;

        return $user->save();
    }
}