<?php

use yii\helpers\Html;

$this->title = 'Личный кабинет - ContestHub';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="relative inline-block">
                <h1 class="text-4xl font-bold text-gray-900 relative z-10">Личный кабинет</h1>
                <div class="absolute -bottom-2 left-0 right-0 h-3 bg-indigo-200 bg-opacity-50 rounded-full -z-0"></div>
            </div>
            <p class="text-xl text-gray-600 mt-4 max-w-2xl mx-auto">
                Управление вашими данными, заявками и настройками профиля
            </p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Profile Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:shadow-2xl transition-all duration-300">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white bg-opacity-10 rounded-full translate-y-12 -translate-x-12"></div>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-between relative z-10">
                            <div class="flex items-center mb-6 sm:mb-0">
                                <div class="w-20 h-20 bg-white bg-opacity-25 rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg border border-white border-opacity-30 transform hover:scale-105 transition duration-300">
                                    <?= strtoupper(mb_substr($user->name, 0, 1) . mb_substr($user->surname, 0, 1)) ?>
                                </div>
                                <div class="ml-6">
                                    <h2 class="text-2xl font-bold text-white mb-1">
                                        <?= Html::encode($user->name . ' ' . $user->surname) ?>
                                    </h2>
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20 text-white">
                                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                            <?= $user->is_admin ? 'Администратор' : 'Участник конкурсов' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center sm:text-right bg-opacity-15 rounded-2xl px-4 py-3 backdrop-blur-sm">
                                <p class="text-indigo-100 text-sm font-medium">Дата регистрации</p>
                                <p class="text-white font-bold text-lg">
                                    <?= Yii::$app->formatter->asDate($user->created_at, 'long') ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information -->
                    <div class="p-8">
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                            <!-- Personal Info -->
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Личная информация</h3>
                                        <p class="text-gray-500 text-sm">Основные данные профиля</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 ml-14">
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-blue-300 transition duration-200">
                                        <label class="text-sm font-medium text-gray-500 block mb-1">Имя</label>
                                        <p class="text-gray-900 font-semibold text-lg"><?= Html::encode($user->name) ?></p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-blue-300 transition duration-200">
                                        <label class="text-sm font-medium text-gray-500 block mb-1">Фамилия</label>
                                        <p class="text-gray-900 font-semibold text-lg"><?= Html::encode($user->surname) ?></p>
                                    </div>
                                    <?php if ($user->patronymic): ?>
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-blue-300 transition duration-200">
                                        <label class="text-sm font-medium text-gray-500 block mb-1">Отчество</label>
                                        <p class="text-gray-900 font-semibold text-lg"><?= Html::encode($user->patronymic) ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Account Info -->
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Учетная запись</h3>
                                        <p class="text-gray-500 text-sm">Данные для входа</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 ml-14">
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-purple-300 transition duration-200">
                                        <label class="text-sm font-medium text-gray-500 block mb-1">Логин</label>
                                        <p class="text-gray-900 font-semibold text-lg"><?= Html::encode($user->login) ?></p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-purple-300 transition duration-200">
                                        <label class="text-sm font-medium text-gray-500 block mb-1">Email</label>
                                        <p class="text-gray-900 font-semibold text-lg"><?= Html::encode($user->email) ?></p>
                                    </div>
                                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-200">
                                        <label class="text-sm font-medium text-gray-500 block mb-1">Статус аккаунта</label>
                                        <p class="text-indigo-700 font-bold text-lg">
                                            <?= $user->is_admin ? '👑 Администратор' : '⭐ Участник' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <?= Html::a('✏️ Редактировать профиль', ['edit'], [
                                    'class' => 'inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-semibold rounded-2xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1'
                                ]) ?>
                                
                                <?= Html::a('🔒 Сменить пароль', ['change-password'], [
                                    'class' => 'inline-flex items-center justify-center px-8 py-4 border border-gray-300 text-lg font-semibold rounded-2xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1'
                                ]) ?>
                                
                                <?= Html::a('📋 Мои заявки', ['/application/index'], [
                                    'class' => 'inline-flex items-center justify-center px-8 py-4 border border-gray-300 text-lg font-semibold rounded-2xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!-- Right Column - Stats -->
<div class="space-y-8">
    <!-- Stats Section -->
    <div class="space-y-6">
        <h3 class="text-2xl font-bold text-gray-900 text-center">Статистика</h3>
        
        <!-- Total Applications Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transform hover:scale-105 transition duration-300">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Всего заявок</p>
                    <p class="text-3xl font-bold text-gray-900">
                        <?= \app\models\Application::find()->where(['user_id' => Yii::$app->user->id])->count() ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Active Applications -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transform hover:scale-105 transition duration-300">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Активные</p>
                    <p class="text-3xl font-bold text-gray-900">
                        <?= \app\models\Application::find()->where([
                            'user_id' => Yii::$app->user->id,
                            'status' => ['new', 'under_review']
                        ])->count() ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Completed Applications -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transform hover:scale-105 transition duration-300">
            <div class="flex items-center">
                <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Завершённые</p>
                    <p class="text-3xl font-bold text-gray-900">
                        <?= \app\models\Application::find()->where([
                            'user_id' => Yii::$app->user->id,
                            'status' => 'graded'
                        ])->count() ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                    <h4 class="text-lg font-bold mb-4">Быстрые действия</h4>
                    <div class="space-y-3">
                        <?= Html::a('🏆 Посмотреть конкурсы', ['/contest/index'], [
                            'class' => 'block w-full text-center bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm rounded-xl px-4 py-3 font-medium transition duration-200 border border-white border-opacity-30'
                        ]) ?>
                        <?= Html::a('📤 Подать заявку', ['/application/create'], [
                            'class' => 'block w-full text-center bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm rounded-xl px-4 py-3 font-medium transition duration-200 border border-white border-opacity-30'
                        ]) ?>
                        <?= Html::a('❓ Помощь', ['/site/contact'], [
                            'class' => 'block w-full text-center bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm rounded-xl px-4 py-3 font-medium transition duration-200 border border-white border-opacity-30'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>