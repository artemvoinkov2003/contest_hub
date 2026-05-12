<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Создание уведомления' : 'Редактирование уведомления';
$this->params['breadcrumbs'][] = ['label' => 'Уведомления', 'url' => ['notifications']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white"><?= $this->title ?></h1>
                        <p class="mt-1 text-blue-100"><?= $model->isNewRecord ? 'Создание нового системного уведомления' : 'Редактирование уведомления' ?></p>
                    </div>
                    <a href="<?= Url::to(['notifications']) ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Назад к списку
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="p-6">
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'space-y-6'],
                ]); ?>

                <div class="grid grid-cols-1 gap-6">
                    <?= $form->field($model, 'user_id', [
                        'options' => ['class' => ''],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700 mb-2']
                    ])->dropDownList(
                        \yii\helpers\ArrayHelper::map($users, 'id', 'login'),
                        [
                            'class' => 'mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg',
                            'prompt' => 'Все пользователи'
                        ]
                    ) ?>

                    <?= $form->field($model, 'title', [
                        'options' => ['class' => ''],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700 mb-2']
                    ])->textInput([
                        'class' => 'mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500',
                        'placeholder' => 'Введите заголовок уведомления'
                    ]) ?>

                    <?= $form->field($model, 'message', [
                        'options' => ['class' => ''],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700 mb-2']
                    ])->textarea([
                        'class' => 'mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500',
                        'rows' => 6,
                        'placeholder' => 'Введите текст уведомления'
                    ]) ?>

                    <?= $form->field($model, 'status', [
                        'options' => ['class' => ''],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700 mb-2']
                    ])->dropDownList(
                        [
                            'new' => 'Новое',
                            'read' => 'Прочитано'
                        ],
                        [
                            'class' => 'mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg'
                        ]
                    ) ?>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="<?= Url::to(['notifications']) ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Отмена
                    </a>
                    <?= Html::submitButton($model->isNewRecord ? 'Создать уведомление' : 'Сохранить изменения', [
                        'class' => 'inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>