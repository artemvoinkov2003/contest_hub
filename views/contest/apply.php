<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Contest $contest */
/** @var app\models\Application $model */
/** @var app\models\AgeCategory[] $ageCategories */
/** @var app\models\Nomination[] $nominations */

$this->title = 'Подача заявки на конкурс: ' . $contest->name;
$this->params['breadcrumbs'][] = ['label' => 'Конкурсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $contest->name, 'url' => ['view', 'id' => $contest->id]];
$this->params['breadcrumbs'][] = 'Подача заявки';
?>
<div class="contest-apply min-h-screen bg-gray-50 py-8">

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Подача заявки</h1>
            <p class="text-lg text-gray-600">
                Конкурс: <span class="font-semibold text-blue-600"><?= Html::encode($contest->name) ?></span>
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-5">
                <div class="flex items-center">
                    <div class="text-white">
                        <h2 class="text-xl font-bold">Форма заявки</h2>
                        <p class="text-blue-100">Заполните все необходимые поля</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'space-y-6', 'enctype' => 'multipart/form-data'],
                    'fieldConfig' => [
                        'template' => "
                            <div class='space-y-2'>
                                {label}
                                <div>
                                    {input}
                                </div>
                                {error}
                            </div>
                        ",
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                        'inputOptions' => ['class' => 'block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500'],
                        'errorOptions' => ['class' => 'text-red-500 text-sm mt-1'],
                    ],
                ]); ?>

                <!-- Nomination and Age Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <?php
                        $nominationItems = [];
                        $nominationOptions = [];
                        foreach ($nominations as $nomination) {
                            $currentCount = \app\models\Application::find()
                                ->where(['nomination_id' => $nomination->id, 'contest_id' => $contest->id])
                                ->andWhere(['!=', 'status', 'blocked'])
                                ->count();
                            $available = $nomination->max_participants ? ($nomination->max_participants - $currentCount) : PHP_INT_MAX;
                            
                            $label = $nomination->name;
                            if ($nomination->max_participants) {
                                $label .= " (осталось мест: " . max(0, $available) . ")";
                            }
                            
                            $nominationItems[$nomination->id] = $label;
                            if ($available <= 0) {
                                $nominationOptions[$nomination->id] = ['disabled' => true];
                            }
                        }
                        ?>
                        
                        <?= $form->field($model, 'nomination_id')->dropDownList(
                            $nominationItems,
                            [
                                'prompt' => 'Выберите номинацию',
                                'class' => 'block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                                'options' => $nominationOptions,
                            ]
                        )->label('Номинация') ?>
                        
                        <?php if (Yii::$app->session->hasFlash('error')): ?>
                            <div class="text-red-600 text-sm bg-red-50 p-3 rounded-lg border border-red-200">
                                <?= Yii::$app->session->getFlash('error') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-2">
                        <?= $form->field($model, 'age_category_id')->dropDownList(
                            \yii\helpers\ArrayHelper::map($ageCategories, 'id', 'name'),
                            [
                                'prompt' => 'Выберите возрастную категорию',
                                'class' => 'block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500'
                            ]
                        )->label('Возрастная категория') ?>
                    </div>
                </div>

                <!-- Work Name -->
                <div class="space-y-2">
                    <?= $form->field($model, 'work_name')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'Введите название вашей конкурсной работы'
                    ])->label('Название работы') ?>
                </div>

                <!-- Personal Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?= $form->field($model, 'surname')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'Иванов'
                    ])->label('Фамилия') ?>

                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'Иван'
                    ])->label('Имя') ?>

                    <?= $form->field($model, 'patronymic')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'Иванович'
                    ])->label('Отчество') ?>
                </div>

                <!-- File Upload -->
                <div class="space-y-2">
                    <?= $form->field($model, 'file')->fileInput([
                        'class' => 'block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100',
                        'accept' => '.mp4,.mkv,.png,.avi,.jpg,.jpeg,.pdf'
                    ])->label('Файл работы') ?>
                    <p class="text-sm text-gray-500 mt-1">
                        Поддерживаемые форматы: MP4, MKV, PNG, AVI, JPG, JPEG, PDF. Максимальный размер: 50MB
                    </p>
                </div>

                <!-- Additional Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?= $form->field($model, 'institution')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'Название учебного заведения или организации'
                    ])->label('Учреждение') ?>

                    <?= $form->field($model, 'leader')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'ФИО руководителя'
                    ])->label('Руководитель') ?>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-gray-200">
                    <?= Html::a('Отмена', ['view', 'id' => $contest->id], [
                        'class' => 'inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                    ]) ?>
                    <?= Html::submitButton('Отправить заявку', [
                        'class' => 'inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>