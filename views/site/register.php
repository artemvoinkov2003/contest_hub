<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
?>

<div id="register-page" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8 sm:px-8 sm:py-10">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white">
                        Регистрация
                    </h2>
                    <p class="mt-2 text-indigo-100">
                        Присоединяйтесь к нашему сообществу
                    </p>
                </div>
            </div>
            
            <!-- Form -->
            <div class="px-6 py-8 sm:px-8 sm:py-10">
                <?php $form = ActiveForm::begin([
                    'id' => 'register-form',
                    'options' => ['class' => 'space-y-6'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'inputOptions' => ['class' => 'mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150'],
                        'errorOptions' => ['class' => 'text-red-500 text-sm mt-1'],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                    ],
                ]); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Ваше имя']) ?>
                    
                    <?= $form->field($model, 'surname')->textInput(['placeholder' => 'Ваша фамилия']) ?>
                </div>

                <?= $form->field($model, 'patronymic')->textInput(['placeholder' => 'Ваше отчество (необязательно)']) ?>

                <?= $form->field($model, 'login')->textInput(['placeholder' => 'Придумайте логин']) ?>

                <?= $form->field($model, 'email')->textInput(['placeholder' => 'your@email.com']) ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Не менее 6 символов']) ?>
                    
                    <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Повторите пароль']) ?>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <?= $form->field($model, 'rules', [
                            'template' => '{input}',
                            'inputOptions' => ['class' => 'h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded']
                        ])->checkbox([], false) ?>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="registerform-rules" class="text-gray-700">
                            Я согласен с <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">правилами регистрации</a> и <a href="#" class="text-indigo-600 hover:text-indigo-500 font-medium">политикой конфиденциальности</a>
                        </label>
                    </div>
                </div>

                <?= Html::submitButton('Зарегистрироваться', [
                    'class' => 'w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-medium transition duration-150 shadow-md'
                ]) ?>

                <?php ActiveForm::end(); ?>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Уже есть аккаунт? 
                        <?= Html::a('Войти', ['login'], [
                            'class' => 'font-medium text-indigo-600 hover:text-indigo-500 transition duration-150'
                        ]) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>