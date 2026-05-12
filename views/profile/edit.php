<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование профиля - ContestHub';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="relative inline-block">
                <h1 class="text-4xl font-bold text-gray-900 relative z-10">Редактирование профиля</h1>
                <div class="absolute -bottom-2 left-0 right-0 h-3 bg-indigo-200 bg-opacity-50 rounded-full -z-0"></div>
            </div>
            <p class="text-xl text-gray-600 mt-4">Обновите ваши личные данные и настройки</p>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:shadow-2xl transition-all duration-300">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Настройки профиля</h2>
                        <p class="text-indigo-100">Изменения сохраняются автоматически</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <?php $form = ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'space-y-8'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'inputOptions' => ['class' => 'mt-2 block w-full rounded-xl border border-gray-300 px-5 py-4 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition duration-200 text-lg shadow-sm hover:border-indigo-300'],
                        'errorOptions' => ['class' => 'text-red-500 text-sm mt-2 font-medium'],
                        'labelOptions' => ['class' => 'block text-lg font-semibold text-gray-700'],
                    ],
                ]); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Введите ваше имя']) ?>
                    
                    <?= $form->field($model, 'surname')->textInput(['placeholder' => 'Введите вашу фамилию']) ?>
                </div>

                <?= $form->field($model, 'patronymic')->textInput(['placeholder' => 'Введите ваше отчество (необязательно)']) ?>

                <?= $form->field($model, 'login')->textInput(['placeholder' => 'Введите ваш логин']) ?>

                <?= $form->field($model, 'email')->textInput(['placeholder' => 'your@email.com']) ?>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800">Информация</h3>
                            <p class="text-blue-700 mt-1">Все изменения сохраняются мгновенно. Для смены пароля используйте специальную форму.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-200">
                    <?= Html::submitButton('💾 Сохранить изменения', [
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