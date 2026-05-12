<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Авторизация';
?>

<div id="login-page" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8 sm:px-8 sm:py-10">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">
                        Авторизация
                    </h2>
                    <p class="mt-2 text-indigo-100">
                        Войдите в свой аккаунт
                    </p>
                </div>
            </div>
            
            <!-- Form -->
            <div class="px-6 py-8 sm:px-8 sm:py-10">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'space-y-6'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'inputOptions' => ['class' => 'mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150'],
                        'errorOptions' => ['class' => 'text-red-500 text-sm mt-1'],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                    ],
                ]); ?>

                <div class="space-y-5">
                    <?= $form->field($model, 'login', [
                        'template' => "<div class='relative'>{label}\n<div class='relative'>{input}\n<div class='absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none'><svg class='h-5 w-5 text-gray-400' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' </div></div>\n{error}</div>"
                    ])->textInput(['placeholder' => 'Введите логин или email']) ?>
                    
                    <?= $form->field($model, 'password', [
                        'template' => "<div class='relative'>{label}\n<div class='relative'>{input}\n<div class='absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none'><svg class='h-5 w-5 text-gray-400' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' </div></div>\n{error}</div>"
                    ])->passwordInput(['placeholder' => 'Введите пароль']) ?>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <?= $form->field($model, 'rememberMe', [
                                'template' => '<div class="flex items-center">{input} {label}</div>',
                                'inputOptions' => ['class' => 'h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded'],
                                'labelOptions' => ['class' => 'ml-2 block text-sm text-gray-700'],
                            ])->checkbox() ?>
                        </div>
                        
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150">
                                Забыли пароль?
                            </a>
                        </div>
                    </div>
                    
                    <?= Html::submitButton('Авторизоваться', [
                        'class' => 'w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-medium transition duration-150 shadow-md'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Нет аккаунта?</span>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <?= Html::a('Создать новый аккаунт', ['register'], [
                            'class' => 'inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium transition duration-150'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>