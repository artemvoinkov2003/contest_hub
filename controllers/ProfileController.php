<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\ProfileForm;
use app\models\ChangePasswordForm;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        return $this->render('index', [
            'user' => $user,
        ]);
    }

    public function actionEdit()
    {
        $user = Yii::$app->user->identity;
        $model = new ProfileForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->updateProfile()) {
            Yii::$app->session->setFlash('success', 'Профиль успешно обновлен.');
            return $this->redirect(['index']);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword()
    {
        $user = Yii::$app->user->identity;
        $model = new ChangePasswordForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Пароль успешно изменен.');
            return $this->redirect(['index']);
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }
}