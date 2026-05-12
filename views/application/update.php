<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Редактирование заявки #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
// Инициализация при загрузке страницы
$(document).ready(function() {
    var contestId = $('#application-contest_id').val();
    if (contestId) {
        loadNominations(contestId);
        loadAgeCategories(contestId);
    }
});

// Обработчик изменения конкурса
$('#application-contest_id').change(function() {
    var contestId = $(this).val();
    if (contestId) {
        loadNominations(contestId);
        loadAgeCategories(contestId);
    } else {
        $('#application-nomination_id').html('<option value="">Выберите номинацию</option>').prop('disabled', true);
        $('#application-age_category_id').html('<option value="">Выберите возрастную категорию</option>').prop('disabled', true);
    }
});

function loadNominations(contestId) {
    $.get('/application/get-nominations', { contest_id: contestId }, function(data) {
        $('#application-nomination_id').html(data).prop('disabled', false);
        $('#application-nomination_id').val('{$model->nomination_id}');
    });
}

function loadAgeCategories(contestId) {
    $.get('/application/get-age-categories', { contest_id: contestId }, function(data) {
        $('#application-age_category_id').html(data).prop('disabled', false);
        $('#application-age_category_id').val('{$model->age_category_id}');
    });
}
JS;
$this->registerJs($js);

?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Редактирование заявки #<?= $model->id ?></h1>
            <p class="text-lg text-gray-600 mt-2">Обновите информацию о вашей заявке</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <?php $form = ActiveForm::begin([
                'id' => 'application-form',
                'options' => ['class' => 'space-y-6', 'enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'inputOptions' => ['class' => 'mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150'],
                    'errorOptions' => ['class' => 'text-red-500 text-sm mt-1'],
                    'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                ],
            ]); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?= $form->field($model, 'contest_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map($contests, 'id', 'name'),
                    ['prompt' => 'Выберите конкурс']
                ) ?>

                <?= $form->field($model, 'nomination_id')->dropDownList(
                    [],
                    ['disabled' => true, 'prompt' => 'Выберите номинацию']
                ) ?>

                <?= $form->field($model, 'age_category_id')->dropDownList(
                    [],
                    ['disabled' => true, 'prompt' => 'Выберите возрастную категорию']
                ) ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?= $form->field($model, 'name')->textInput(['placeholder' => 'Ваше имя']) ?>
                <?= $form->field($model, 'surname')->textInput(['placeholder' => 'Ваша фамилия']) ?>
                <?= $form->field($model, 'patronymic')->textInput(['placeholder' => 'Ваше отчество (необязательно)']) ?>
            </div>

            <?= $form->field($model, 'work_name')->textInput(['placeholder' => 'Название конкурсной работы']) ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?= $form->field($model, 'institution')->textInput(['placeholder' => 'Название учреждения (необязательно)']) ?>
                <?= $form->field($model, 'leader')->textInput(['placeholder' => 'Руководитель (необязательно)']) ?>
            </div>

            <?= $form->field($model, 'file')->fileInput([
                'class' => 'block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100'
            ]) ?>

            <?php if ($model->file_path): ?>
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            <strong>Текущий файл:</strong> <?= basename($model->file_path) ?><br>
                            <small>Оставьте поле пустым, если не хотите менять файл.</small>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Поддерживаемые форматы:</strong> MP4, MKV, PNG, AVI, JPG, PDF<br>
                            <strong>Максимальный размер:</strong> 100MB
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <?= Html::submitButton('Сохранить изменения', [
                    'class' => 'inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 shadow-sm flex-1'
                ]) ?>
                
                <?= Html::a('Отмена', ['view', 'id' => $model->id], [
                    'class' => 'inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 flex-1 text-center'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>