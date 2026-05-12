<?php

use yii\helpers\Html;

$this->title = 'Оценка #' . $evaluation->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['/application/index']];
$this->params['breadcrumbs'][] = ['label' => 'Заявка #' . $evaluation->application_id, 'url' => ['/application/view', 'id' => $evaluation->application_id]];
$this->params['breadcrumbs'][] = 'Просмотр оценки';

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Оценка #<?= $evaluation->id ?></h1>
                    <p class="text-gray-600">Заявка: <?= Html::encode($evaluation->application->work_name) ?></p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $evaluation->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                    <?= $evaluation->status === 'completed' ? 'Завершена' : 'Черновик' ?>
                </span>
            </div>

            <!-- Application Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Информация о заявке</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Участник</p>
                        <p class="font-medium"><?= Html::encode($evaluation->application->surname . ' ' . $evaluation->application->name) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Конкурс</p>
                        <p class="font-medium"><?= Html::encode($evaluation->application->contest->name) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Номинация</p>
                        <p class="font-medium"><?= Html::encode($evaluation->application->nomination->name) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Эксперт</p>
                        <p class="font-medium"><?= Html::encode($evaluation->expert->surname . ' ' . $evaluation->expert->name) ?></p>
                    </div>
                </div>
            </div>

            <!-- Scores -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Оценки по критериям</h3>
                <div class="space-y-4">
                    <?php foreach ($criteriaScores as $score): ?>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <div>
                                <h4 class="font-medium text-gray-900"><?= Html::encode($score->criteria->name) ?></h4>
                                <?php if ($score->criteria->description): ?>
                                    <p class="text-sm text-gray-500"><?= Html::encode($score->criteria->description) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900"><?= $score->score ?></span>
                                <span class="text-sm text-gray-500">/<?= $score->criteria->max_score ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Total Score -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-5 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Общий балл</p>
                        <p class="text-3xl font-bold text-green-600"><?= $evaluation->total_score ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Дата оценки</p>
                        <p class="font-medium"><?= Yii::$app->formatter->asDate($evaluation->updated_at) ?></p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if ($evaluation->notes): ?>
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Комментарии эксперта</h3>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <p class="text-gray-700"><?= nl2br(Html::encode($evaluation->notes)) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex justify-between pt-6 border-t border-gray-200">
                <?= Html::a('Назад к заявке', ['/application/view', 'id' => $evaluation->application_id], [
                    'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                ]) ?>
                
                <?php if (Yii::$app->user->id == $evaluation->expert_id && $evaluation->status === 'draft'): ?>
                    <?= Html::a('Редактировать', ['update', 'id' => $evaluation->id], [
                        'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>