<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\User;
use app\models\Contest;
use app\models\Application;
use app\models\Notification;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        // Если пользователь не авторизован, перенаправляем на страницу входа
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        // Получаем активные конкурсы
        $activeContests = Contest::find()
            ->where(['status' => 1])
            ->andWhere(['>=', 'end_date', date('Y-m-d')])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        // Статистика для главной страницы
        $stats = [
            'totalContests' => Contest::find()->where(['status' => 1])->count(),
            'totalApplications' => Application::find()->count(),
            'totalUsers' => User::find()->count(),
            'activeContests' => count($activeContests),
        ];

        return $this->render('index', [
            'activeContests' => $activeContests,
            'stats' => $stats,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            
            if ($model->login()) {
                Yii::$app->session->setFlash('success', 'Вы успешно вошли в систему');
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка входа. Проверьте введенные данные.');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            
            if ($user = $model->register()) {
                // Уведомление о успешной регистрации
                Notification::create(
                    $user->id,
                    'Добро пожаловать!',
                    "Вы успешно зарегистрировались в системе конкурсов. Теперь вы можете подавать заявки на участие."
                );
                
                Yii::$app->session->setFlash('success', 'Регистрация прошла успешно! Теперь вы можете войти.');
                return $this->redirect(['login']);
            } else {
                Yii::$app->session->setFlash('error', 'Исправьте ошибки в форме');
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'Вы успешно вышли из системы');
        return $this->goHome();
    }

    public function actionContact()
    {
        return $this->render('contact');
    }

    public function actionBlocked()
    {
        return $this->render('blocked');
    }

}