<?php

use yii\helpers\Html;
use app\models\Application;
use app\models\ExpertAssignment;

$this->title = 'Заявка #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$contestResult = $contestResult ?? null;
$documents = $documents ?? [];

$statusColors = [
    Application::STATUS_NEW => 'bg-blue-100 text-blue-800 border-blue-200',
    Application::STATUS_UNDER_REVIEW => 'bg-yellow-100 text-yellow-800 border-yellow-200', 
    Application::STATUS_BLOCKED => 'bg-red-100 text-red-800 border-red-200',
    Application::STATUS_GRADED => 'bg-green-200 text-green-900 border-green-300 shadow-sm',
];

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Заявка - <?= $model->contest->name ?>
            </h1>
            <p class="text-lg text-gray-600">Детальная информация о заявке</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Status Bar -->
            <div class="bg-blue-600 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-white">
                    <div class="flex items-center mb-2 sm:mb-0">
                        <span class="text-blue-100 mr-2">Статус:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-blue-600">
                            <?= $model->getStatusLabel() ?>
                        </span>
                    </div>
                    <div class="text-sm text-blue-100">
                        Подана: <?= Yii::$app->formatter->asDate($model->created_at, 'dd.MM.Y') ?>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Contest Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Информация о конкурсе
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Конкурс</label>
                            <p class="text-gray-900 font-medium"><?= Html::encode($model->contest->name) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Номинация</label>
                            <p class="text-gray-900 font-medium"><?= Html::encode($model->nomination->name) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Возрастная категория</label>
                            <p class="text-gray-900 font-medium"><?= Html::encode($model->ageCategory->name) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Даты конкурса</label>
                            <p class="text-gray-900 font-medium">
                                <?= Yii::$app->formatter->asDate($model->contest->start_date) ?> - 
                                <?= Yii::$app->formatter->asDate($model->contest->end_date) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Applicant Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Информация об участнике
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">ФИО</label>
                            <p class="text-gray-900 font-medium">
                                <?= Html::encode($model->surname . ' ' . $model->name . ' ' . $model->patronymic) ?>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Название работы</label>
                            <p class="text-gray-900 font-medium"><?= Html::encode($model->work_name) ?></p>
                        </div>
                        <?php if ($model->institution): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Учреждение</label>
                            <p class="text-gray-900 font-medium"><?= Html::encode($model->institution) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($model->leader): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Руководитель</label>
                            <p class="text-gray-900 font-medium"><?= Html::encode($model->leader) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contest Results (if exists) -->
                <?php if ($contestResult): ?>
                    <div class="mb-8" id="results">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Итоговые результаты
                        </h3>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Финальный балл</label>
                                    <p class="text-2xl font-bold text-green-600"><?= $contestResult->final_score !== null ? number_format($contestResult->final_score, 2) : '—' ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Место</label>
                                    <p class="text-2xl font-bold text-blue-600"><?= $contestResult->getPlaceText() ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Тип награды</label>
                                    <p class="text-lg font-medium text-purple-600"><?= $contestResult->getAwardText() ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Дата определения</label>
                                    <p class="text-gray-900 font-medium"><?= Yii::$app->formatter->asDate($contestResult->created_at) ?></p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <?= Html::a('Посмотреть результаты конкурса', ['contest/results', 'id' => $model->contest_id], [
                                    'class' => 'inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Generated Documents -->
                <?php if (!empty($documents)): ?>
                <div class="mb-8" id="documents">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Сгенерированные документы
                    </h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($documents as $document): ?>
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-white">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 w-10 h-10 <?= $document->document_type == 'diploma' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' ?> rounded-lg flex items-center justify-center">
                                        <span class="text-lg">
                                            <?= $document->document_type == 'diploma' ? '📜' : '📄' ?>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= $document->getDocumentTypeName() ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= $document->fileExists() ? $document->getFileExtension() . ' • ' . $document->getFileSize() : 'Файл не найден' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-500">
                                        <?= Yii::$app->formatter->asDate($document->generated_at) ?>
                                    </span>
                                    <?php if ($document->fileExists()): ?>
                                        <?= Html::a('Скачать', ['download-document', 'id' => $document->id], [
                                            'class' => 'inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700'
                                        ]) ?>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                            Недоступно
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- File Upload -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Файл работы
                    </h3>
                    
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <?php
                        $fileUrl = $model->getFileUrl();
                        $filePath = $model->getFilePath();
                        if ($model->fileExists()): ?>
                            <?php if ($model->isImage()): ?>
                                <div class="text-center">
                                    <div class="inline-block bg-white rounded-lg shadow-sm p-4 mb-4">
                                        <img src="<?= $fileUrl ?>" alt="Работа" class="max-w-full h-auto rounded-lg mx-auto max-h-64">
                                    </div>
                                    <div class="mt-4">
                                        <a href="<?= $fileUrl ?>" download class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            Скачать изображение
                                        </a>
                                    </div>
                                </div>
                            <?php elseif ($model->isVideo()): ?>
                                <div class="text-center">
                                    <div class="inline-block bg-white rounded-lg shadow-sm p-4 mb-4">
                                        <video controls class="max-w-full h-auto rounded-lg mx-auto max-h-64">
                                            <source src="<?= $fileUrl ?>" type="video/<?= $model->getFileExtension() ?>">
                                            Ваш браузер не поддерживает видео тег.
                                        </video>
                                    </div>
                                    <div class="mt-4">
                                        <a href="<?= $fileUrl ?>" download class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            Скачать видео
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl text-blue-600">📄</span>
                                    </div>
                                    <p class="text-gray-600 mb-2">
                                        Формат файла: <span class="font-medium"><?= strtoupper($model->getFileExtension()) ?></span>
                                    </p>
                                    <p class="text-sm text-gray-500 mb-4">Вы можете скачать файл для просмотра</p>
                                    <a href="<?= $fileUrl ?>" download class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Скачать файл работы
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-2xl text-gray-400">📄</span>
                                </div>
                                <p class="text-gray-600 mb-2">Файл не найден</p>
                                <p class="text-sm text-gray-500">Файл работы недоступен для скачивания</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500 mr-2">Действия:</span>
                            <div class="inline-flex space-x-2">
                                <?= Html::a('Вернуться к списку', ['index'], [
                                    'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50'
                                ]) ?>
                                
                                <?php if ($contestResult): ?>
                                    <?= Html::a('Просмотр результатов', ['view-result', 'id' => $model->id], [
                                        'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 ml-2'
                                    ]) ?>
                                <?php endif; ?>
                                
                                <!-- Expert evaluation link (if user is expert) -->
                                <?php if (Yii::$app->user->identity->is_admin || ExpertAssignment::find()->where(['expert_id' => Yii::$app->user->id, 'contest_id' => $model->contest_id, 'nomination_id' => $model->nomination_id, 'age_category_id' => $model->age_category_id])->exists()): ?>
                                    <?= Html::a('Оценить', ['evaluation/create', 'application_id' => $model->id], [
                                        'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 ml-2'
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <?php if ($model->canBeCancelled()): ?>
                                <?= Html::a('Отменить заявку', ['cancel', 'id' => $model->id], [
                                    'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите отменить заявку?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>