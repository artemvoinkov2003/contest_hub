<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Contest;
?>

<div class="application-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'space-y-4'],
    ]); ?>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <?= $form->field($model, 'work_name', [
                'options' => ['class' => ''],
                'inputOptions' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm'],
            ])->textInput(['placeholder' => 'Название работы'])->label(false) ?>
        </div>
        
        <div>
            <?= $form->field($model, 'contest_id', [
                'options' => ['class' => ''],
                'inputOptions' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm'],
            ])->dropDownList(
                ArrayHelper::map(Contest::find()->all(), 'id', 'name'),
                ['prompt' => 'Все конкурсы']
            )->label(false) ?>
        </div>
        
        <div>
            <?= $form->field($model, 'status', [
                'options' => ['class' => ''],
                'inputOptions' => ['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm'],
            ])->dropDownList([
                'new' => 'Новая',
                'under_review' => 'На проверке',
                'graded' => 'Оценена',
            ], ['prompt' => 'Все статусы'])->label(false) ?>
        </div>
        
        <div class="flex items-end">
            <?= Html::submitButton('Поиск', ['class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) ?>
            <?= Html::a('Сбросить', ['index'], ['class' => 'ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) ?>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>