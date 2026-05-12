<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Смена пароля - ContestHub';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="relative inline-block">
                <h1 class="text-4xl font-bold text-gray-900 relative z-10">Смена пароля</h1>
                <div class="absolute -bottom-2 left-0 right-0 h-3 bg-indigo-200 bg-opacity-50 rounded-full -z-0"></div>
            </div>
            <p class="text-xl text-gray-600 mt-4">Обновите ваш пароль для безопасности аккаунта</p>
        </div>

        <!-- Change Password Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:shadow-2xl transition-all duration-300">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Безопасность аккаунта</h2>
                        <p class="text-indigo-100">Создайте надежный пароль для защиты</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <?php $form = ActiveForm::begin([
                    'id' => 'change-password-form',
                    'options' => ['class' => 'space-y-8'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'inputOptions' => ['class' => 'mt-2 block w-full rounded-xl border border-gray-300 px-5 py-4 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition duration-200 text-lg shadow-sm hover:border-indigo-300'],
                        'errorOptions' => ['class' => 'text-red-500 text-sm mt-2 font-medium'],
                        'labelOptions' => ['class' => 'block text-lg font-semibold text-gray-700'],
                    ],
                ]); ?>

                <!-- Current Password -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <?= $form->field($model, 'currentPassword')->passwordInput(['placeholder' => 'Введите текущий пароль']) ?>
                        </div>
                    </div>
                </div>

                <!-- New Password -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <?= $form->field($model, 'newPassword')->passwordInput(['placeholder' => 'Не менее 6 символов']) ?>
                        </div>
                    </div>
                </div>

                <!-- Repeat New Password -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['placeholder' => 'Повторите новый пароль']) ?>
                        </div>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border-l-4 border-yellow-500 p-6 rounded-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-3">Требования к паролю</h3>
                            <ul class="text-yellow-700 space-y-2 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Минимум 6 символов
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Содержит буквы и цифры
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Рекомендуются специальные символы
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Советы по безопасности</h3>
                            <p class="text-blue-700 text-sm">
                                Используйте уникальный пароль, который вы не используете на других сайтах. 
                                Регулярно обновляйте пароль для повышения безопасности вашего аккаунта.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-200">
                    <?= Html::submitButton('🔒 Сменить пароль', [
                        'class' => 'inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-semibold rounded-2xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex-1'
                    ]) ?>
                    
                    <?= Html::a('↩️ Отмена', ['index'], [
                        'class' => 'inline-flex items-center justify-center px-8 py-4 border border-gray-300 text-lg font-semibold rounded-2xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex-1 text-center'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>