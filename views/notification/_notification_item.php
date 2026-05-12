<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Notification $model */

// Декодируем метаданные если они есть
$metadata = [];
if ($model->metadata) {
    $metadata = json_decode($model->metadata, true) ?: [];
}
?>

<div class="flex items-start space-x-4">
    <!-- Иконка -->
    <div class="flex-shrink-0">
        <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl <?= $model->isNew() ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' ?>">
            <?= $model->getTypeIcon() ?>
        </div>
    </div>
    
    <!-- Основной контент -->
    <div class="flex-1 min-w-0">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 <?= $model->isNew() ? 'font-bold' : '' ?>">
                        <?= Html::encode($model->title) ?>
                    </h3>
                    
                    <!-- Тип уведомления -->
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $model->getTypeBadgeColor() ?>">
                        <?= $model->getTypeLabel($model->notification_type) ?>
                    </span>
                    
                    <!-- Статус -->
                    <?php if ($model->isNew()): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Новое
                        </span>
                    <?php endif; ?>
                </div>
                
                <p class="text-gray-600 mb-3">
                    <?= nl2br(Html::encode($model->message)) ?>
                </p>
                
                <!-- Дополнительные действия для специфичных типов -->
                <?php if ($model->notification_type === \app\models\Notification::TYPE_DIPLOMA_GENERATED && !empty($metadata['file_path'])): ?>
                    <div class="mt-3">
                        <?= Html::a('Скачать диплом', ['/application/download-diploma', 'id' => $metadata['application_id'] ?? null], [
                            'class' => 'inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700',
                            'target' => '_blank'
                        ]) ?>
                    </div>
                <?php elseif ($model->notification_type === \app\models\Notification::TYPE_RESULTS_PUBLISHED): ?>
                    <div class="mt-3">
                        <?= Html::a('Посмотреть результаты', ['/contest/results'], [
                            'class' => 'inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700'
                        ]) ?>
                    </div>
                <?php elseif ($model->notification_type === \app\models\Notification::TYPE_APPLICATION_SUBMITTED): ?>
                    <div class="mt-3">
                        <?= Html::a('Просмотреть заявку', ['/application/view', 'id' => $metadata['application_id'] ?? null], [
                            'class' => 'inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700'
                        ]) ?>
                    </div>
                <?php elseif ($model->notification_type === \app\models\Notification::TYPE_EXPERT_EVALUATION): ?>
                    <div class="mt-3">
                        <?= Html::a('Просмотреть оценку', ['/application/evaluation', 'id' => $metadata['application_id'] ?? null], [
                            'class' => 'inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700'
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Действия -->
            <div class="mt-3 sm:mt-0 flex items-center space-x-2">
                <div class="text-sm text-gray-500 whitespace-nowrap">
                    <?= Yii::$app->formatter->asRelativeTime($model->created_at) ?>
                </div>
                
                <div class="flex space-x-1">
                    <?php if ($model->isNew()): ?>
                        <?= Html::a('Прочитать', ['read', 'id' => $model->id], [
                            'class' => 'inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800',
                            'title' => 'Отметить как прочитанное',
                            'data-method' => 'post'
                        ]) ?>
                    <?php endif; ?>
                    
                    <?= Html::a('✕', ['delete', 'id' => $model->id], [
                        'class' => 'inline-flex items-center p-1.5 text-gray-400 hover:text-red-600',
                        'title' => 'Удалить уведомление',
                        'data-method' => 'post',
                        'data-confirm' => 'Удалить это уведомление?'
                    ]) ?>
                </div>
            </div>
        </div>
        
        <!-- Метаданные -->
        <?php if (!empty($metadata) && isset($metadata['application_id'])): ?>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                    <?php if (isset($metadata['application_id'])): ?>
                        <span class="inline-flex items-center">
                            <span class="mr-1">ID заявки:</span>
                            <span class="font-medium"><?= $metadata['application_id'] ?></span>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (isset($metadata['contest_id'])): ?>
                        <?php $contest = \app\models\Contest::findOne($metadata['contest_id']); ?>
                        <?php if ($contest): ?>
                            <span class="inline-flex items-center">
                                <span class="mr-1">Конкурс:</span>
                                <span class="font-medium"><?= Html::encode($contest->name) ?></span>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
