<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// Проверяем существование модели
$model = $model ?? null;

if (!$model) {
    echo '<p class="text-red-500">Ошибка: модель поиска не определена</p>';
    return;
}
?>

<?php $form = ActiveForm::begin([
    'action' => ['applications'],
    'method' => 'get',
    'options' => ['class' => 'space-y-6']
]); ?>

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <?= $form->field($model, 'work_name', [
        'options' => ['class' => ''],
        'template' => '{label}{input}{error}'
    ])->textInput([
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
        'placeholder' => 'Название работы'
    ])->label('Название работы', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'surname', [
        'options' => ['class' => ''],
        'template' => '{label}{input}{error}'
    ])->textInput([
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200',
        'placeholder' => 'Фамилия участника'
    ])->label('Фамилия', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'user_login', [
        'options' => ['class' => ''],
        'template' => '{label}{input}{error}'
    ])->textInput([
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 transition-all duration-200',
        'placeholder' => 'Логин пользователя'
    ])->label('Логин', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'status', [
        'options' => ['class' => ''],
        'template' => '{label}{input}{error}'
    ])->dropDownList([
        'new' => 'Новая',
        'accepted' => 'Принята', 
        'blocked' => 'Заблокирована',
        'completed' => 'Оценена'
    ], [
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition-all duration-200',
        'prompt' => 'Все статусы'
    ])->label('Статус', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
</div>

<div class="flex justify-end space-x-4 pt-6">
    <?= Html::a('Сбросить', ['applications'], [
        'class' => 'inline-flex items-center px-6 py-3 border-2 border-blue-200 text-sm font-bold rounded-xl text-blue-600 bg-white hover:bg-blue-50 hover:border-blue-300 transform hover:scale-105 transition-all duration-200 shadow-sm'
    ]) ?>
    <?= Html::submitButton('Поиск', [
        'class' => 'inline-flex items-center px-8 py-3 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
    ]) ?>
</div>

<?php ActiveForm::end(); ?>