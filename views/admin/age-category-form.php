<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Создание возрастной категории' : 'Редактирование возрастной категории';
$this->params['breadcrumbs'][] = ['label' => 'Возрастные категории', 'url' => ['age-categories']];
$this->params['breadcrumbs'][] = $this->title;

$contests = $contests ?? [];
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= $this->title ?></h1>
                    <p class="mt-1 text-sm text-blue-100"><?= $model->isNewRecord ? 'Создание новой возрастной категории' : 'Редактирование существующей категории' ?></p>
                </div>
                <a href="<?= Url::to(['age-categories']) ?>" class="inline-flex items-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-xl text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                    Назад к списку
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'space-y-8 divide-y divide-gray-200']
            ]); ?>

            <div class="space-y-8 divide-y divide-gray-200">
                <!-- Основная информация -->
                <div class="pt-8">
                    <div class="px-8">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Основная информация</h3>
                        <p class="mt-1 text-sm text-gray-500">Основные данные возрастной категории</p>
                    </div>

                    <div class="mt-6 px-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <?= $form->field($model, 'name', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Например: Дети 6-9 лет'
                            ])->label('Название категории', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-4">
                            <?= $form->field($model, 'contest_id', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->dropDownList(
                                \yii\helpers\ArrayHelper::map($contests, 'id', 'name'),
                                [
                                    'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200',
                                    'prompt' => 'Выберите конкурс'
                                ]
                            )->label('Конкурс', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="pt-8 pb-6">
                <div class="flex justify-end px-8 space-x-4">
                    <a href="<?= Url::to(['age-categories']) ?>" class="bg-white py-3 px-6 border-2 border-blue-200 rounded-xl shadow-sm text-sm font-bold text-blue-600 hover:bg-blue-50 hover:border-blue-300 transform hover:scale-105 transition-all duration-200">
                        Отмена
                    </a>
                    <?= Html::submitButton($model->isNewRecord ? 'Создать категорию' : 'Сохранить изменения', [
                        'class' => 'inline-flex justify-center py-3 px-8 border border-transparent shadow-lg text-sm font-bold rounded-xl text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <!-- Подсказки -->
        <?php if ($model->isNewRecord): ?>
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <h4 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Советы по созданию возрастных категорий
            </h4>
            <ul class="text-sm text-gray-600 space-y-2">
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Используйте понятные и описательные названия
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Указывайте возрастные диапазоны (например: "Дети 6-9 лет")
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Одна возрастная категория может быть привязана только к одному конкурсу
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>