<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = 'Просмотр оценки';
$this->params['breadcrumbs'][] = ['label' => 'Модерация оценок', 'url' => ['evaluations']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Просмотр оценки</h1>
                        <p class="mt-1 text-blue-100">Детальная информация о выставленной оценке</p>
                    </div>
                    <a href="<?= Url::to(['evaluations']) ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Назад к списку
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Информация о заявке -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Информация о заявке</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Работа</dt>
                                <dd class="text-sm text-gray-900 font-semibold"><?= Html::encode($model->application->work_name ?? '') ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Конкурс</dt>
                                <dd class="text-sm text-gray-900"><?= Html::encode($model->application->contest->name ?? '') ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Участник</dt>
                                <dd class="text-sm text-gray-900">
                                    <?= Html::encode($model->application->surname) ?> 
                                    <?= Html::encode($model->application->name) ?> 
                                    <?= Html::encode($model->application->patronymic) ?>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Информация об эксперте -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Информация об эксперте</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Эксперт</dt>
                                <dd class="text-sm text-gray-900 font-semibold">
                                    <?= Html::encode($model->expert->surname) ?> 
                                    <?= Html::encode($model->expert->name) ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Логин</dt>
                                <dd class="text-sm text-gray-900"><?= Html::encode($model->expert->login ?? '') ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900"><?= Html::encode($model->expert->email ?? '') ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Оценки по критериям -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Оценки по критериям</h3>
                    <div class="space-y-3">
                        <?php 
                        $scores = $model->evaluationScores;
                        if (!empty($scores)): 
                            foreach ($scores as $score): ?>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900"><?= Html::encode($score->criteria->name ?? '') ?></span>
                                        <span class="text-xs text-gray-500 ml-2">(макс. <?= $score->criteria->max_score ?? 0 ?> баллов)</span>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <?= $score->score ?> баллов
                                    </span>
                                </div>
                            <?php endforeach; 
                        else: ?>
                            <p class="text-sm text-gray-500">Оценки по критериям не найдены</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Общая информация -->
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg p-4 text-center">
                        <div class="text-white text-sm font-medium">Общий балл</div>
                        <div class="text-white text-2xl font-bold mt-1"><?= $model->total_score ?></div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg p-4 text-center">
                        <div class="text-white text-sm font-medium">Статус</div>
                        <div class="text-white text-lg font-bold mt-1"><?= $model->getStatusLabel() ?></div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg p-4 text-center">
                        <div class="text-white text-sm font-medium">Дата оценки</div>
                        <div class="text-white text-sm font-medium mt-1"><?= Yii::$app->formatter->asDate($model->updated_at, 'php:d.m.Y H:i') ?></div>
                    </div>
                </div>

                <!-- Примечания -->
                <?php if (!empty($model->notes)): ?>
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Примечания эксперта</h3>
                    <div class="prose prose-sm max-w-none">
                        <p class="text-gray-700"><?= nl2br(Html::encode($model->notes)) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Действия -->
                <div class="mt-6 flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="<?= Url::to(['evaluations']) ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Назад к списку
                    </a>
                    
                    <?php if ($model->status === \app\models\Evaluation::STATUS_DRAFT): ?>
                        <a href="<?= Url::to(['evaluation-complete', 'id' => $model->id]) ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-lg text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" 
                           data-confirm="Вы уверены, что хотите завершить эту оценку? Это действие изменит статус с 'Черновик' на 'Завершено'." 
                           data-method="post">
                            Завершить оценку
                        </a>
                    <?php elseif ($model->status === 'completed'): ?>
                        <a href="<?= Url::to(['evaluation-reset', 'id' => $model->id]) ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-lg text-white bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" 
                           data-confirm="Вы уверены, что хотите сбросить статус этой оценки на 'Черновик'?" 
                           data-method="post">
                            Сбросить статус
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>