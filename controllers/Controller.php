<?php

namespace app\controllers;

use Yii;
use yii\web\Controller as BaseController;

class Controller extends BaseController
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Проверяем только авторизованных пользователей
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            if ($user && $user->isBlocked()) {
                // Разлогиниваем пользователя
                Yii::$app->user->logout();
                
                // Сохраняем сообщение о блокировке в сессии
                Yii::$app->session->setFlash('error', 'Ваш аккаунт был заблокирован администратором.');
                
                // Перенаправляем на страницу входа
                return $this->redirect(['site/login'])->send();
            }
        }

        return true;
    }
}
