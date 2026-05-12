<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Contest $contest */
/** @var app\models\GeneratedDocument[] $documents */

$this->title = 'Сгенерированные документы: ' . $contest->name;
$this->params['breadcrumbs'][] = ['label' => 'Конкурсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $contest->name, 'url' => ['view', 'id' => $contest->id]];
$this->params['breadcrumbs'][] = 'Документы';
?>
<div class="contest-documents min-h-screen bg-gray-50 py-8">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Сгенерированные документы</h1>
            <p class="text-lg text-gray-600">
                Конкурс: <span class="font-semibold text-blue-600"><?= Html::encode($contest->name) ?></span>
            </p>
        </div>

        <!-- Documents List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Список документов</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                <?php if (empty($documents)): ?>
                    <div class="px-6 py-8 text-center">
                        <p class="text-gray-500">Нет сгенерированных документов</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($documents as $document): ?>
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <div class="flex items-center">
                                <span class="text-blue-600 font-medium"><?= $document->application->work_name ?? 'Не указано' ?></span>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $document->document_type == 'diploma' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= $document->document_type == 'diploma' ? 'Диплом' : 'Сертификат' ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Участник: <?= $document->application->surname ?? '' ?> <?= $document->application->name ?? '' ?>
                                <?php if ($document->application->patronymic): ?>
                                    <?= $document->application->patronymic ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-500">
                                <?= Yii::$app->formatter->asDate($document->generated_at) ?>
                            </span>
                            <?= Html::a('Скачать', ['download-document', 'id' => $document->id], [
                                'class' => 'inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200'
                            ]) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6">
            <?= Html::a('Вернуться к конкурсу', ['view', 'id' => $contest->id], [
                'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50'
            ]) ?>
        </div>
    </div>

</div>