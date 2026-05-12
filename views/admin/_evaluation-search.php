<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="evaluation-search">
    <?php $form = ActiveForm::begin([
        'action' => ['evaluations'],
        'method' => 'get',
        'options' => [
            'class' => 'grid grid-cols-1 md:grid-cols-4 gap-4'
        ],
    ]); ?>

    <?= $form->field($model, 'workName')->textInput(['placeholder' => 'Название работы'])->label(false) ?>

    <?= $form->field($model, 'expertName')->textInput(['placeholder' => 'Имя эксперта'])->label(false) ?>

    <?= $form->field($model, 'contestName')->textInput(['placeholder' => 'Название конкурса'])->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList([
        '' => 'Все статусы',
        'draft' => 'Черновик',
        'completed' => 'Завершена'
    ], ['placeholder' => 'Статус'])->label(false) ?>

    <div class="form-group flex items-end">
        <?= Html::submitButton('Поиск', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']) ?>
        <?= Html::a('Сбросить', ['evaluations'], ['class' => 'ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>