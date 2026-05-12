<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = $model ?? null;

if (!$model) {
    echo '<p class="text-red-500">Ошибка: модель поиска не определена</p>';
    return;
}
?>

<?php $form = ActiveForm::begin([
    'action' => ['applications'],
    'method' => 'get',
    'options' => ['class' => 'space-y-6'],
    'fieldConfig' => [
        'options' => ['class' => '']
    ]
]); ?>

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <?= $form->field($model, 'work_name')->textInput([
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
        'placeholder' => 'Название работы'
    ])->label('Название работы', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'surname')->textInput([
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200',
        'placeholder' => 'Фамилия участника'
    ])->label('Фамилия', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'user_login')->textInput([
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 transition-all duration-200',
        'placeholder' => 'Логин пользователя'
    ])->label('Логин', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'status')->dropDownList([
        'new' => 'Новая',
        'accepted' => 'Принята', 
        'blocked' => 'Заблокирована',
        'completed' => 'Оценена'
    ], [
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition-all duration-200',
        'prompt' => 'Все статусы'
    ])->label('Статус', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
</div>

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mt-6">
    <?= $form->field($model, 'has_results')->dropDownList([
        '1' => 'С результатами',
        '0' => 'Без результатов',
    ], [
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50 transition-all duration-200',
        'prompt' => 'Наличие результатов'
    ])->label('Результаты', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'has_documents')->dropDownList([
        '1' => 'С документами',
        '0' => 'Без документов',
    ], [
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-500 focus:ring-opacity-50 transition-all duration-200',
        'prompt' => 'Наличие документов'
    ])->label('Документы', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'award_type')->dropDownList([
        'first' => 'Диплом I степени',
        'second' => 'Диплом II степени',
        'third' => 'Диплом III степени',
        'laureate' => 'Диплом лауреата',
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат',
    ], [
        'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-all duration-200',
        'prompt' => 'Все награды'
    ])->label('Награда', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>

    <?= $form->field($model, 'contest_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Contest::find()->all(), 'id', 'name'),
        [
            'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-200',
            'prompt' => 'Все конкурсы'
        ]
    )->label('Конкурс', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
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