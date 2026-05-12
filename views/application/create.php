<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Nomination;
use app\models\AgeCategory;

$this->title = 'Подача заявки';
$this->params['breadcrumbs'][] = ['label' => 'Мои заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$contest_id = $model->contest_id ?? 1;

// Получаем данные для выпадающих списков
$nominations = Nomination::find()->where(['contest_id' => $contest_id])->all();
$ageCategories = AgeCategory::find()->where(['contest_id' => $contest_id])->all();

$nominationList = ArrayHelper::map($nominations, 'id', 'name');
$ageCategoryList = ArrayHelper::map($ageCategories, 'id', 'name');

?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Подача заявки на конкурс</h1>
            <p class="text-lg text-gray-600">Заполните форму для участия в конкурсе</p>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Форма заявки</h2>
                        <p class="text-blue-100 text-sm">Заполните все необходимые поля</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <?php $form = ActiveForm::begin([
                    'id' => 'application-form',
                    'options' => ['class' => 'space-y-6', 'enctype' => 'multipart/form-data'],
                    'fieldConfig' => [
                        'template' => "<div class='space-y-2'>{label}\n{input}\n{error}</div>",
                        'inputOptions' => ['class' => 'w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white placeholder-gray-400'],
                        'errorOptions' => ['class' => 'text-red-500 text-sm mt-1 font-medium'],
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                    ],
                ]); ?>

                <!-- Competition Selection - Hidden -->
                <?= $form->field($model, 'contest_id')->hiddenInput(['value' => $contest_id])->label(false) ?>

                <!-- Nomination and Age Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?= $form->field($model, 'nomination_id')->dropDownList(
                        $nominationList,
                        [
                            'prompt' => 'Выберите номинацию',
                            'class' => 'w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white'
                        ]
                    ) ?>

                    <?= $form->field($model, 'age_category_id')->dropDownList(
                        $ageCategoryList,
                        [
                            'prompt' => 'Выберите возрастную категорию',
                            'class' => 'w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white'
                        ]
                    ) ?>
                </div>

                <!-- Personal Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Личная информация</span>
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?= $form->field($model, 'name')->textInput([
                                'placeholder' => 'Ваше имя'
                            ]) ?>
                            <?= $form->field($model, 'surname')->textInput([
                                'placeholder' => 'Ваша фамилия'
                            ]) ?>
                        </div>
                        <?= $form->field($model, 'patronymic')->textInput([
                            'placeholder' => 'Ваше отчество (необязательно)'
                        ]) ?>
                    </div>
                </div>

                <!-- Work Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Информация о работе</span>
                    </h3>
                    <div class="space-y-4">
                        <?= $form->field($model, 'work_name')->textInput([
                            'placeholder' => 'Название конкурсной работы'
                        ]) ?>
                        <?= $form->field($model, 'institution')->textInput([
                            'placeholder' => 'Название учреждения (необязательно)'
                        ]) ?>
                        <?= $form->field($model, 'leader')->textInput([
                            'placeholder' => 'Руководитель (необязательно)'
                        ]) ?>
                    </div>
                </div>

<!-- File Upload -->
<div class="border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <span>Файл работы</span>
    </h3>
    
    <div class="file-upload-container">
        <?= $form->field($model, 'file', [
            'template' => "<div class='space-y-2'><div class='file-input-wrapper'>{input}<div class='file-display'><div class='flex flex-col items-center justify-center py-6'><svg class='w-12 h-12 text-gray-400 mb-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'></path></svg><span class='text-gray-500'>Файл не выбран</span></div></div></div>{error}</div>"
        ])->fileInput([
            'class' => 'hidden-file-input'
        ]) ?>
    </div>
</div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <?= Html::submitButton('Отправить заявку', [
                        'class' => 'inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-semibold rounded-2xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1'
                    ]) ?>
                    
                    <?= Html::a('Отмена', ['index'], [
                        'class' => 'inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-semibold rounded-2xl text-white bg-gradient-to-r from-gray-600 to-gray-600 hover:from-gray-700 hover:to-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<style>
.file-input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.hidden-file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}

.file-display {
    width: 100%;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    background-color: #f9fafb;
    text-align: center;
    color: #6b7280;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-display:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.file-display.has-file {
    border-color: #10b981;
    background-color: #ecfdf5;
    color: #065f46;
    border-style: solid;
}

.file-upload-container:hover .file-display {
    border-color: #3b82f6;
}
</style>

<script>
// Простой скрипт только для отображения имени файла
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('.hidden-file-input');
    const fileDisplay = document.querySelector('.file-display');
    
    if (fileInput && fileDisplay) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileDisplay.innerHTML = '<div class="flex flex-col items-center justify-center py-4"><svg class="w-8 h-8 text-green-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span class="text-green-600 font-medium">' + this.files[0].name + '</span></div>';
                fileDisplay.classList.add('has-file');
            } else {
                fileDisplay.innerHTML = '<div class="flex flex-col items-center justify-center py-6"><svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg><span class="text-gray-500">Файл не выбран</span></div>';
                fileDisplay.classList.remove('has-file');
            }
        });
    }
});8
</script>