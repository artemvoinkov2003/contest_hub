<?php

use yii\helpers\Html;

$this->title = 'Результаты заявки #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Заявка #' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Результаты';

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Результаты заявки</h1>
            <p class="text-lg text-gray-600">
                Конкурс: <span class="font-medium text-blue-600"><?= Html::encode($model->contest->name) ?></span>
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Result Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h2 class="text-xl font-bold">Итоговые результаты</h2>
                        <p class="text-green-100">Работа: <?= Html::encode($model->work_name) ?></p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-green-100">Номинация: <?= Html::encode($model->nomination->name) ?></div>
                        <div class="text-sm text-green-100"><?= Html::encode($model->ageCategory->name) ?></div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Main Result Card -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-lg border border-green-200 p-8 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-500 mb-2">Финальный балл</div>
                            <div class="text-4xl font-bold text-green-600"><?= $contestResult->final_score !== null ? number_format($contestResult->final_score, 2) : '—' ?></div>
                            <div class="text-sm text-gray-600 mt-2">из 100 возможных</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-500 mb-2">Место</div>
                            <div class="text-4xl font-bold text-blue-600"><?= $contestResult->getPlaceText() ?></div>
                            <div class="text-sm text-gray-600 mt-2">в номинации</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-500 mb-2">Награда</div>
                            <div class="text-2xl font-bold text-purple-600"><?= $contestResult->getAwardText() ?></div>
                            <div class="text-sm text-gray-600 mt-2">тип награды</div>
                        </div>
                    </div>
                </div>

                <!-- Result Details -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Детали результатов
                    </h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Дата определения результатов:</span>
                                <span class="text-gray-900"><?= Yii::$app->formatter->asDate($contestResult->created_at, 'long') ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Конкурс:</span>
                                <span class="text-gray-900"><?= Html::encode($model->contest->name) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Номинация:</span>
                                <span class="text-gray-900"><?= Html::encode($model->nomination->name) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Участник:</span>
                                <span class="text-gray-900">
                                    <?= Html::encode($model->surname . ' ' . $model->name . ' ' . $model->patronymic) ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Название работы:</span>
                                <span class="text-gray-900"><?= Html::encode($model->work_name) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-6 border-t border-gray-200">
                    <div class="space-x-3">
                        <?= Html::a('Вернуться к заявке', ['view', 'id' => $model->id], [
                            'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50'
                        ]) ?>
                        
                        <?= Html::a('Посмотреть все результаты конкурса', ['contest/results', 'id' => $model->contest_id], [
                            'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 ml-2'
                        ]) ?>
                    </div>
                    
                    <div class="space-x-3">
                        <?= Html::a('К списку заявок', ['index'], [
                            'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>