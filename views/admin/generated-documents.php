<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $contests app\models\Contest[] */
/* @var $selectedContestId int|null */

$this->title = 'Сгенерированные документы';
$this->params['breadcrumbs'][] = $this->title;

// Проверяем существование dataProvider
$dataProvider = $dataProvider ?? null;
$contests = $contests ?? [];

// Функция для получения текста типа документа
function getDocumentTypeText($type) {
    $types = [
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат',
        'program' => 'Программа',
        'evaluation_sheet' => 'Оценочный лист',
        'result' => 'Результаты',
    ];
    return $types[$type] ?? $type;
}

// Функция для получения CSS класса типа документа
function getDocumentTypeCssClass($type) {
    $classes = [
        'diploma' => 'bg-gradient-to-r from-yellow-400 to-amber-400 text-white',
        'certificate' => 'bg-gradient-to-r from-green-400 to-emerald-400 text-white',
        'program' => 'bg-gradient-to-r from-blue-400 to-blue-500 text-white',
        'evaluation_sheet' => 'bg-gradient-to-r from-purple-400 to-violet-400 text-white',
        'result' => 'bg-gradient-to-r from-indigo-400 to-purple-400 text-white',
    ];
    return $classes[$type] ?? 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
}

// Функция для получения размера файла в удобном формате
function getFileSize($filePath) {
    if ($filePath && file_exists(Yii::getAlias('@webroot/' . $filePath))) {
        $size = filesize(Yii::getAlias('@webroot/' . $filePath));
        return Yii::$app->formatter->asShortSize($size);
    }
    return '-';
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-1 text-sm text-blue-100">Управление сгенерированными документами участников</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Фильтр документов</h3>
                            <p class="text-sm text-gray-500">Отфильтруйте документы по конкурсу</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        <?= Html::a(
                            'Все документы',
                            ['generated-documents'],
                            ['class' => 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium ' . (!$selectedContestId ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')]
                        ) ?>
                        <?php foreach ($contests as $contest): ?>
                            <?= Html::a(
                                $contest->name,
                                ['generated-documents', 'contest_id' => $contest->id],
                                ['class' => 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium ' . ($selectedContestId == $contest->id ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200')]
                            ) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Статистика -->
            <?php if ($dataProvider): ?>
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white">
                    <p class="text-sm text-gray-600">
                        Всего документов: <span class="font-semibold text-gray-900"><?= $dataProvider->getTotalCount() ?></span>
                        <?php if ($selectedContestId && $selectedContest = \app\models\Contest::findOne($selectedContestId)): ?>
                            по конкурсу: <span class="font-semibold text-gray-900"><?= Html::encode($selectedContest->name) ?></span>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Generated Documents Content -->
    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php Pjax::begin(['id' => 'documents-pjax']); ?>
            
            <?php if (!$dataProvider || $dataProvider->getCount() === 0): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Документы не найдены</h3>
                    <p class="text-gray-500 mb-6">
                        <?php if ($selectedContestId): ?>
                            Для выбранного конкурса документы не найдены
                        <?php else: ?>
                            Сгенерированные документы отсутствуют
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <!-- Mobile Version (Cards) -->
                <div class="block md:hidden">
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($dataProvider->getModels() as $document): ?>
                            <?php
                            $fileExists = $document->file_path && file_exists(Yii::getAlias('@webroot/' . $document->file_path));
                            $fileSize = getFileSize($document->file_path);
                            ?>
                            <div class="px-4 py-5 hover:bg-blue-50 transition-all duration-200">
                                <!-- Заголовок карточки -->
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            <?= Html::encode($document->application ? $document->application->work_name : 'Работа удалена') ?>
                                        </h3>
                                        <p class="text-sm text-blue-600 font-medium">ID: <?= $document->id ?></p>
                                    </div>
                                    <div class="relative">
                                        <button type="button" class="inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100" id="mobile-menu-button-<?= $document->id ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>
                                        <div class="absolute right-0 z-10 hidden w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="mobile-menu-<?= $document->id ?>">
                                            <div class="py-1">
                                                <?php if ($fileExists): ?>
                                                    <a href="<?= Url::to(['/admin/generated-document-download', 'id' => $document->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" target="_blank">
                                                        Скачать
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?= Url::to(['generated-document-delete', 'id' => $document->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" data-confirm="Вы уверены, что хотите удалить этот документ?" data-method="post">
                                                    Удалить
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Информация о документе -->
                                <div class="space-y-3">
                                    <!-- Конкурс -->
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                <?= Html::encode($document->application ? $document->application->contest->name : 'Конкурс не указан') ?>
                                            </p>
                                            <p class="text-xs text-gray-500">Конкурс</p>
                                        </div>
                                    </div>

                                    <!-- Участник -->
                                    <?php if ($document->application && $document->application->user): ?>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">
                                                    <?= Html::encode($document->application->user->getFullName()) ?>
                                                </p>
                                                <p class="text-xs text-gray-500">Участник</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Тип документа -->
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= getDocumentTypeCssClass($document->document_type) ?>">
                                            <?= getDocumentTypeText($document->document_type) ?>
                                        </span>
                                    </div>

                                    <!-- Файл и размер -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <!-- Файл -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-center mb-1">
                                                <svg class="w-4 h-4 mr-1 <?= $fileExists ? 'text-green-500' : 'text-red-500' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <?php if ($fileExists): ?>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    <?php else: ?>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    <?php endif; ?>
                                                </svg>
                                                <span class="text-xs font-medium text-gray-500">Файл</span>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                <?= $fileExists ? basename($document->file_path) : 'Отсутствует' ?>
                                            </p>
                                        </div>

                                        <!-- Размер -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-center mb-1">
                                                <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                                </svg>
                                                <span class="text-xs font-medium text-gray-500">Размер</span>
                                            </div>
                                            <p class="text-sm font-bold text-gray-900">
                                                <?= $fileSize ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Дата генерации -->
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm text-gray-900">
                                                <?= Yii::$app->formatter->asDate($document->generated_at, 'php:d.m.Y H:i') ?>
                                            </p>
                                            <p class="text-xs text-gray-500">Дата генерации</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Основные действия -->
                                <div class="flex space-x-2 pt-4">
                                    <?php if ($fileExists): ?>
                                        <a href="<?= Url::to(['/admin/generated-document-download', 'id' => $document->id]) ?>" 
                                           class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors duration-200"
                                           target="_blank">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Скачать
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= Url::to(['generated-document-delete', 'id' => $document->id]) ?>" 
                                       class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition-colors duration-200"
                                       data-confirm="Вы уверены, что хотите удалить этот документ?" 
                                       data-method="post">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Удалить
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Desktop Version (Grid) -->
                <div class="hidden md:block overflow-x-auto">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                        'layout' => "{items}\n<div class='px-8 py-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0'>{pager}\n<div class='text-sm text-gray-600'>{summary}</div></div>",
                        'emptyText' => '',
                        'summary' => 'Показано <b class="text-gray-800">{begin}-{end}</b> из <b class="text-gray-800">{totalCount}</b> документов',
                        'rowOptions' => function ($model, $key, $index, $grid) {
                            return [
                                'class' => 'hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition duration-150',
                            ];
                        },
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header' => '#',
                                'contentOptions' => ['class' => 'px-6 py-4 text-sm text-gray-500 text-center'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-b border-blue-200'],
                            ],
                            [
                                'attribute' => 'application.work_name',
                                'label' => 'РАБОТА',
                                'format' => 'raw',
                                'value' => function($model) {
                                    if ($model->application) {
                                        return Html::a(
                                            Html::encode($model->application->work_name),
                                            ['application-view', 'id' => $model->application->id],
                                            ['class' => 'text-blue-600 hover:text-blue-800 hover:underline font-medium']
                                        );
                                    }
                                    return '<span class="text-gray-400">Работа удалена</span>';
                                },
                                'contentOptions' => ['class' => 'px-6 py-4 text-sm text-gray-900 min-w-[200px] max-w-[250px] break-words'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[200px]'],
                            ],
                            [
                                'attribute' => 'application.contest.name',
                                'label' => 'КОНКУРС',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 min-w-[180px]'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[180px]'],
                            ],
                            [
                                'attribute' => 'application.user.fullName',
                                'label' => 'УЧАСТНИК',
                                'value' => function($model) {
                                    return $model->application && $model->application->user ? 
                                        $model->application->user->getFullName() : '-';
                                },
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 min-w-[180px]'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[180px]'],
                            ],
                            [
                                'attribute' => 'document_type',
                                'label' => 'ТИП',
                                'value' => function($model) {
                                    return getDocumentTypeText($model->document_type);
                                },
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 min-w-[120px]'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[120px]'],
                            ],
                            [
                                'attribute' => 'file_path',
                                'label' => 'ФАЙЛ',
                                'format' => 'raw',
                                'value' => function($model) {
                                    if ($model->file_path && file_exists(Yii::getAlias('@webroot/' . $model->file_path))) {
                                        $fileName = basename($model->file_path);
                                        $url = Url::to(['/admin/generated-document-download', 'id' => $model->id]);
                                        return Html::a(
                                            '<span class="text-blue-600 hover:text-blue-800 hover:underline font-medium">' . Html::encode($fileName) . '</span>',
                                            $url,
                                            ['class' => 'inline-flex items-center', 'target' => '_blank']
                                        );
                                    }
                                    return '<span class="text-gray-400">Файл отсутствует</span>';
                                },
                                'contentOptions' => ['class' => 'px-6 py-4 text-sm text-gray-900 min-w-[200px] max-w-[250px] break-words'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[200px]'],
                            ],
                            [
                                'label' => 'РАЗМЕР',
                                'value' => function($model) {
                                    return getFileSize($model->file_path);
                                },
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium min-w-[100px]'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[100px]'],
                            ],
                            [
                                'attribute' => 'generated_at',
                                'label' => 'СГЕНЕРИРОВАН',
                                'format' => ['date', 'php:d.m.Y H:i'],
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-600 min-w-[140px]'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[140px]'],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{download} {delete}',
                                'header' => 'ДЕЙСТВИЯ',
                                'buttons' => [
                                    'download' => function ($url, $model) {
                                        if ($model->file_path && file_exists(Yii::getAlias('@webroot/' . $model->file_path))) {
                                            $url = Url::to(['/admin/generated-document-download', 'id' => $model->id]);
                                            return Html::a(
                                                'Скачать',
                                                $url,
                                                [
                                                    'class' => 'inline-flex items-center px-4 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg hover:bg-green-200 transition-colors duration-200 mr-2',
                                                    'title' => 'Скачать документ',
                                                    'target' => '_blank'
                                                ]
                                            );
                                        }
                                        return Html::tag('span', 'Файл отсутствует', [
                                            'class' => 'inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-lg'
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            'Удалить',
                                            ['generated-document-delete', 'id' => $model->id],
                                            [
                                                'class' => 'inline-flex items-center px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors duration-200',
                                                'title' => 'Удалить документ',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить этот документ?',
                                                    'method' => 'post',
                                                ],
                                            ]
                                        );
                                    },
                                ],
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-left text-sm font-medium min-w-[220px]'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200 min-w-[220px]'],
                            ],
                        ],
                        'pager' => [
                            'options' => ['class' => 'flex space-x-2'],
                            'linkOptions' => ['class' => 'px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:shadow transition-all'],
                            'pageCssClass' => 'inline-flex',
                            'prevPageCssClass' => 'inline-flex',
                            'nextPageCssClass' => 'inline-flex',
                            'activePageCssClass' => 'bg-gradient-to-r from-blue-500 to-purple-500 text-white border-blue-500',
                            'disabledPageCssClass' => 'opacity-50 cursor-not-allowed hover:bg-white',
                        ],
                    ]); ?>
                </div>
            <?php endif; ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<script>
// Обработка выпадающих меню для мобильной версии
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('button[id^="mobile-menu-button-"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menuId = this.id.replace('mobile-menu-button-', 'mobile-menu-');
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            
            // Скрываем все меню
            document.querySelectorAll('div[id^="mobile-menu-"]').forEach(function(m) {
                if (m.id !== menuId) {
                    m.classList.add('hidden');
                }
            });
            
            // Показываем/скрываем текущее меню
            if (isHidden) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    });
    
    // Закрытие меню при клике вне его
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button[id^="mobile-menu-button-"]') && !e.target.closest('div[id^="mobile-menu-"]')) {
            document.querySelectorAll('div[id^="mobile-menu-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
});

// Поддержка Pjax
document.addEventListener('pjax:success', function() {
    // Переинициализируем обработчики после Pjax-обновления
    document.querySelectorAll('button[id^="mobile-menu-button-"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menuId = this.id.replace('mobile-menu-button-', 'mobile-menu-');
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            
            document.querySelectorAll('div[id^="mobile-menu-"]').forEach(function(m) {
                if (m.id !== menuId) {
                    m.classList.add('hidden');
                }
            });
            
            if (isHidden) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    });
});
</script>