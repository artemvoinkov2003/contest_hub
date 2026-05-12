<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование оценки #' . $evaluation->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['/application/index']];
$this->params['breadcrumbs'][] = ['label' => 'Заявка #' . $application->id, 'url' => ['/application/view', 'id' => $application->id]];
$this->params['breadcrumbs'][] = ['label' => 'Оценка #' . $evaluation->id, 'url' => ['view', 'id' => $evaluation->id]];
$this->params['breadcrumbs'][] = 'Редактирование';

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6"><?= Html::encode($this->title) ?></h1>
            
            <?php $form = ActiveForm::begin([
                'id' => 'evaluation-update-form',
                'options' => ['class' => 'space-y-6'],
            ]); ?>
            
            <?= $form->field($evaluation, 'notes')->textarea([
                'rows' => 4,
                'class' => 'mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150',
                'placeholder' => 'Ваши комментарии и замечания...'
            ]) ?>
            
            <div class="flex justify-between pt-6 border-t border-gray-200">
                <?= Html::a('Отмена', ['view', 'id' => $evaluation->id], [
                    'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                ]) ?>
                
                <?= Html::submitButton('Сохранить изменения', [
                    'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                ]) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>