<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Создание шаблона' : 'Редактирование шаблона';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны отчетов', 'url' => ['templates']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h1 class="text-2xl font-bold text-gray-900"><?= $this->title ?></h1>
            </div>
            
            <?php $form = ActiveForm::begin(['options' => ['class' => 'px-4 py-5 sm:p-6', 'enctype' => 'multipart/form-data']]); ?>
            
            <div class="space-y-6">
                <?= $form->field($model, 'type')->dropDownList(
                    \app\models\ReportTemplate::getAllTypes(),
                    ['prompt' => 'Выберите тип шаблона', 'class' => 'mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md']
                ) ?>
                
                <?= $form->field($model, 'contest_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\app\models\Contest::find()->all(), 'id', 'name'),
                    ['prompt' => 'Общий шаблон (для всех конкурсов)', 'class' => 'mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md']
                ) ?>
                
                <?= $form->field($model, 'templateFile')->fileInput(['class' => 'mt-1']) ?>
                
                <?php if (!$model->isNewRecord && $model->template_file): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Текущий файл</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p><?= Html::encode($model->displayName) ?></p>
                                <p class="mt-1 text-xs text-blue-600">Загружен: <?= Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Информация о шаблонах</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Шаблоны должны быть в формате HTML. Поддерживаются следующие переменные:</p>
                                <ul class="list-disc pl-5 mt-1 space-y-1">
                                    <li><code>{{participant_name}}</code> - ФИО участника</li>
                                    <li><code>{{work_name}}</code> - Название работы</li>
                                    <li><code>{{contest_name}}</code> - Название конкурса</li>
                                    <li><code>{{contest_date}}</code> - Дата конкурса</li>
                                    <li><code>{{nomination}}</code> - Номинация</li>
                                    <li><code>{{age_category}}</code> - Возрастная категория</li>
                                    <li><code>{{institution}}</code> - Учреждение</li>
                                    <li><code>{{leader}}</code> - Руководитель</li>
                                    <li><code>{{final_score}}</code> - Итоговый балл</li>
                                    <li><code>{{place}}</code> - Место</li>
                                    <li><code>{{award_type}}</code> - Тип награды</li>
                                    <li><code>{{current_date}}</code> - Текущая дата</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="<?= \yii\helpers\Url::to(['templates']) ?>" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Отмена
                </a>
                <?= Html::submitButton($model->isNewRecord ? 'Создать шаблон' : 'Обновить шаблон', ['class' => 'inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>