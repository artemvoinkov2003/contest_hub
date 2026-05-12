<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$fontsCssPath = Yii::getAlias('@webroot/css/style.css');
if (file_exists($fontsCssPath)) {
    $this->registerCssFile(Url::to(['/css/style.css']));
}

$this->registerJsFile('@web/js/applications.js', ['depends' => [yii\web\JqueryAsset::class]]);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-full">
<head>
    <title><?= Html::encode($this->title) ?></title>    
    <?php $this->head() ?>
</head>
<body class="h-full bg-gray-50">
<?php $this->beginBody() ?>

<div class="min-h-full flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?= Yii::$app->user->isGuest ? Url::to(['/site/login']) : Url::home() ?>" class="text-xl font-bold text-indigo-600 flex items-center">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-2">
                            <span class="text-white font-bold text-sm">CH</span>
                        </div>
                        ContestHub
                    </a>
                </div>

                <!-- Menu button -->
                <button type="button" 
                        id="mobile-menu-button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition duration-150">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar -->
    <div id="mobile-sidebar" 
         class="fixed inset-0 z-50 transform transition-transform duration-300 ease-in-out"
         style="transform: translateX(-100%);">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50" id="sidebar-overlay"></div>
        
        <!-- Sidebar Content -->
        <div class="relative w-80 max-w-full bg-white h-full shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-2">
                        <span class="text-white font-bold text-sm">CH</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-900">Меню</span>
                </div>
                <button id="close-sidebar" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition duration-150">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <nav class="p-4 space-y-2">
                <?php if (Yii::$app->user->isGuest): ?>
                    <!-- Menu for guests -->
                    <div class="space-y-2">
                        <?= Html::a('Авторизация', ['/site/login'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 group w-full'
                        ]) ?>
                        <?= Html::a('Регистрация', ['/site/register'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-indigo-600 border-2 border-indigo-600 hover:bg-indigo-50 transition duration-150 group w-full'
                        ]) ?>
                    </div>
                <?php else: ?>
                    <!-- Basic pages for authorized users -->
                    <div class="space-y-2">
                        <?= Html::a('Главная', ['/site/index'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition duration-150 group'
                        ]) ?>
                        <?= Html::a('Контакты', ['/site/contact'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition duration-150 group'
                        ]) ?>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-3"></div>

                    <!-- Application sections -->
                    <div class="space-y-2">
                        <?= Html::a('Конкурсы', ['/contest/index'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition duration-150 group'
                        ]) ?>
                        <?= Html::a('Мои заявки', ['/application/index'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition duration-150 group'
                        ]) ?>
                        <?= Html::a('Уведомления', ['/notification/index'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition duration-150 group'
                        ]) ?>
                        <?= Html::a('Личный кабинет', ['/profile/index'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition duration-150 group'
                        ]) ?>
                        <?= Html::a('Эксперт', ['/expert/index'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition duration-150 group'
                        ]) ?>
                        
                        <!-- Admin panel for admins -->
                        <?php if (Yii::$app->user->identity->is_admin): ?>
                            <?= Html::a('Админ-панель', ['/admin/index'], [
                                'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-white bg-purple-600 hover:bg-purple-700 transition duration-150 group'
                            ]) ?>
                        <?php endif; ?>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-3"></div>

                    <!-- User info and logout -->
                    <div class="space-y-3">
                        <div class="px-4 py-3 bg-gray-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-900">
                                <?= Html::encode(Yii::$app->user->identity->name) ?>
                            </div>
                            <div class="text-sm text-gray-500 truncate">
                                <?= Html::encode(Yii::$app->user->identity->email) ?>
                            </div>
                        </div>
                        <?= Html::a('Выйти', ['/site/logout'], [
                            'class' => 'flex items-center px-4 py-3 rounded-lg text-base font-medium text-white bg-red-600 hover:bg-red-700 transition duration-150 group w-full justify-center',
                            'data' => ['method' => 'post']
                        ]) ?>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Flash messages -->
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <?= Yii::$app->session->getFlash('success') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <?= Yii::$app->session->getFlash('error') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-8 right-8 w-12 h-12 bg-indigo-600 text-white rounded-full shadow-lg hover:bg-indigo-700 transform hover:scale-110 transition-all duration-300 opacity-0 invisible flex items-center justify-center z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>