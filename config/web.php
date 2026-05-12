<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'JurySpace',
            'enableCsrfValidation' => true,
            'csrfParam' => '_csrf',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'site/login',
                'register' => 'site/register',
                'logout' => 'site/logout',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'error' => 'site/error',

                // Конкурсы
                'contests' => 'contest/index',
                'contest/<id:\d+>' => 'contest/view',
                'contest/<id:\d+>/apply' => 'contest/apply',

                'contest/<id:\d+>/download-program' => 'contest/download-program',
                'contest/<id:\d+>/download-evaluation-sheet' => 'contest/download-evaluation-sheet',

                // Заявки
                'my-applications' => 'application/index',
                'application/<id:\d+>' => 'application/view',
                'application/<id:\d+>/cancel' => 'application/cancel',
                'application/download/<id:\d+>' => 'application/download',

                'applications/<id:\d+>/result' => 'application/view-result',
                'applications/documents/<id:\d+>/download' => 'application/download-document',

                // Уведомления
                'notifications' => 'notification/index',
                'notification/<id:\d+>/read' => 'notification/read',

                'notifications/read-all' => 'notification/read-all',
                'notifications/delete-read' => 'notification/delete-read',

                // Личный кабинет
                'profile' => 'profile/index',
                'profile/edit' => 'profile/edit',
                'profile/change-password' => 'profile/change-password',

                // Админ-панель
                'admin' => 'admin/index',
                'admin/applications' => 'admin/applications',
                'admin/application-view/<id:\d+>' => 'admin/application-view',
                'admin/application-block/<id:\d+>' => 'admin/application-block',
                'admin/application-unblock/<id:\d+>' => 'admin/application-unblock',
                'admin/application-delete/<id:\d+>' => 'admin/application-delete',
                
                'admin/contests' => 'admin/contests',
                'admin/contest-create' => 'admin/contest-create',
                'admin/contest-update/<id:\d+>' => 'admin/contest-update',
                'admin/contest-delete/<id:\d+>' => 'admin/contest-delete',
                
                'admin/age-categories' => 'admin/age-categories',
                'admin/age-category-create' => 'admin/age-category-create',
                'admin/age-category-update/<id:\d+>' => 'admin/age-category-update',
                'admin/age-category-delete/<id:\d+>' => 'admin/age-category-delete',
                
                'admin/nominations' => 'admin/nominations',
                'admin/nomination-create' => 'admin/nomination-create',
                'admin/nomination-update/<id:\d+>' => 'admin/nomination-update',
                'admin/nomination-delete/<id:\d+>' => 'admin/nomination-delete',
                
                'admin/experts' => 'admin/experts',
                'admin/expert-create' => 'admin/expert-create',
                'admin/expert-update/<id:\d+>' => 'admin/expert-update',
                'admin/expert-assign/<id:\d+>' => 'admin/expert-assign',
                
                'admin/users' => 'admin/users',
                'admin/user-update/<id:\d+>' => 'admin/user-update',
                'admin/user-block/<id:\d+>' => 'admin/user-block',
                
                'admin/notifications' => 'admin/notifications',
                'admin/notification-create' => 'admin/notification-create',
                'admin/notification-update/<id:\d+>' => 'admin/notification-update',
                'admin/notification-delete/<id:\d+>' => 'admin/notification-delete',
                
                'admin/evaluations' => 'admin/evaluations',
                'admin/evaluation-reset/<id:\d+>' => 'admin/evaluation-reset',

                'admin/templates' => 'admin/templates',
                'admin/templates/create' => 'admin/template-create',
                'admin/templates/<id:\d+>' => 'admin/template-view',
                'admin/templates/<id:\d+>/update' => 'admin/template-update',
                'admin/templates/<id:\d+>/delete' => 'admin/template-delete',
                'admin/templates/<id:\d+>/download' => 'admin/template-download',
                
                'admin/generated-documents' => 'admin/generated-documents',
                'admin/generated-documents/<id:\d+>/delete' => 'admin/generated-document-delete',
                
                'admin/contest-results' => 'admin/contest-results',
                'admin/contest-results/<contest_id:\d+>' => 'admin/contest-result-view',
                'admin/contest-results/<contest_id:\d+>/manage' => 'admin/contest-result-manage',
                'admin/contest-results/<contest_id:\d+>/generate-diplomas' => 'admin/generate-diplomas',
                'admin/contest-results/<contest_id:\d+>/export/<format:[\w-]+>' => 'admin/export-results',
                
                'admin/nomination-stats/<contest_id:\d+>' => 'admin/nomination-stats',
                
                // Маршруты для функционала жюри
                'expert' => 'expert/index',
                'expert/evaluate/<id:\d+>' => 'expert/evaluate',
                'expert/complete/<id:\d+>' => 'expert/complete',

                'expert/ranking' => 'expert/ranking',
                'expert/documents' => 'expert/documents',

                // Маршруты для шаблонов отчетов
                'templates' => 'report-template/index',
                'templates/<id:\d+>' => 'report-template/view',
                'templates/create' => 'report-template/create',
                'templates/update/<id:\d+>' => 'report-template/update',
                'templates/delete/<id:\d+>' => 'report-template/delete',
                'templates/download/<id:\d+>' => 'report-template/download',
                'templates/generate/<id:\d+>' => 'report-template/generate',
                
                // Маршруты для сгенерированных документов
                'documents' => 'generated-document/index',
                'documents/my' => 'generated-document/my-documents',
                'documents/<id:\d+>' => 'generated-document/view',
                'documents/download/<id:\d+>' => 'generated-document/download',
                'documents/delete/<id:\d+>' => 'generated-document/delete',
                'documents/for-application/<application_id:\d+>' => 'generated-document/for-application',
                'documents/for-contest/<contest_id:\d+>' => 'generated-document/for-contest',
                'documents/batch-generate/<contest_id:\d+>' => 'generated-document/batch-generate',
                
                // Маршруты для результатов конкурса
                'contest/<id:\d+>/results' => 'contest/results',
                'contest/<id:\d+>/manage-results' => 'contest/manage-results',
                'contest/<id:\d+>/generate-diplomas' => 'contest/generate-diplomas',
                'contest/<id:\d+>/download-results' => 'contest/download-results',
                'contest/<id:\d+>/documents' => 'contest/documents',

                


            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
