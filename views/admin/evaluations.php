<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Модерация оценок';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = $dataProvider ?? null;

function getStatusCssClass($status) {
    switch ($status) {
        case \app\models\Evaluation::STATUS_DRAFT: 
            return 'bg-gradient-to-r from-blue-400 to-blue-500 text-white';
        case 'completed': 
            return 'bg-gradient-to-r from-green-400 to-emerald-400 text-white';
        default: 
            return 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
    }
}

function getStatusText($status) {
    switch ($status) {
        case \app\models\Evaluation::STATUS_DRAFT: 
            return 'Черновик';
        case 'completed': 
            return 'Завершено';
        default: 
            return ucfirst($status);
    }
}

function getStatusIcon($status) {
    switch ($status) {
        case \app\models\Evaluation::STATUS_DRAFT: 
            return '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>';
        case 'completed': 
            return '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>';
        default: 
            return '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>';
    }
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
   
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-1 text-sm text-blue-100">Управление оценками экспертов и контроль качества</p>
                </div>
            </div>
        </div>
    </div>

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
                            <h3 class="text-lg font-semibold text-gray-900">Фильтры оценок</h3>
                            <p class="text-sm text-gray-500">Отфильтруйте оценки по статусу</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= Url::to(['evaluations', 'status' => 'all']) ?>" 
                           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium <?= Yii::$app->request->get('status', 'all') === 'all' ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                            Все оценки
                        </a>
                        <a href="<?= Url::to(['evaluations', 'status' => 'draft']) ?>" 
                           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium <?= Yii::$app->request->get('status') === 'draft' ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                            Черновики
                        </a>
                        <a href="<?= Url::to(['evaluations', 'status' => 'completed']) ?>" 
                           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium <?= Yii::$app->request->get('status') === 'completed' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                            Завершенные
                        </a>
                    </div>
                </div>
            </div>
            
            <?php if ($dataProvider): ?>
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white">
                    <p class="text-sm text-gray-600">
                        Всего оценок: <span class="font-semibold text-gray-900"><?= $dataProvider->getTotalCount() ?></span>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <?php Pjax::begin(['id' => 'evaluations-pjax']); ?>
            
            <?php if (!$dataProvider || $dataProvider->getCount() === 0): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Оценки не найдены</h3>
                    <p class="text-gray-500 mb-6">Нет оценок, соответствующих выбранным критериям.</p>
                </div>
            <?php else: ?>
         
                <div class="block md:hidden">
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($dataProvider->getModels() as $evaluation): ?>
                            <div class="px-4 py-5 hover:bg-blue-50 transition-all duration-200">
                                
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900"><?= Html::encode($evaluation->application->work_name ?? 'Не указана') ?></h3>
                                        <p class="text-sm text-blue-600 font-medium">ID: <?= $evaluation->id ?></p>
                                    </div>
                                    <div class="relative">
                                        <button type="button" class="inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100" id="mobile-eval-menu-button-<?= $evaluation->id ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>
                                        <div class="absolute right-0 z-10 hidden w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="mobile-eval-menu-<?= $evaluation->id ?>">
                                            <div class="py-1">
                                                <a href="<?= Url::to(['evaluation-view', 'id' => $evaluation->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Просмотр
                                                </a>
                                                <?php if ($evaluation->status === \app\models\Evaluation::STATUS_DRAFT): ?>
                                                    <?= Html::a('Завершить', ['evaluation-complete', 'id' => $evaluation->id], [
                                                        'class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100',
                                                        'data' => [
                                                            'confirm' => 'Вы уверены, что хотите завершить эту оценку?',
                                                            'method' => 'post',
                                                        ]
                                                    ]) ?>
                                                <?php endif; ?>
                                                <?php if ($evaluation->status === 'completed'): ?>
                                                    <?= Html::a('Сбросить', ['evaluation-reset', 'id' => $evaluation->id], [
                                                        'class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100',
                                                        'data' => [
                                                            'confirm' => 'Вы уверены, что хотите сбросить статус этой оценки?',
                                                            'method' => 'post',
                                                        ]
                                                    ]) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                      
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900"><?= Html::encode($evaluation->expert->surname ?? '') ?> <?= Html::encode($evaluation->expert->name ?? '') ?></p>
                                            <p class="text-xs text-gray-500">Эксперт</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <div>
                                            <p class="text-lg font-bold text-gray-900"><?= Html::encode($evaluation->total_score) ?> баллов</p>
                                            <p class="text-xs text-gray-500">Итоговый балл</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= getStatusCssClass($evaluation->status) ?>">
                                            <?= getStatusIcon($evaluation->status) ?>
                                            <?= getStatusText($evaluation->status) ?>
                                        </span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm text-gray-900"><?= Yii::$app->formatter->asDate($evaluation->updated_at, 'php:d.m.Y H:i') ?></p>
                                            <p class="text-xs text-gray-500">Последнее обновление</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex space-x-2 pt-4">
                                    <a href="<?= Url::to(['evaluation-view', 'id' => $evaluation->id]) ?>" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Просмотр
                                    </a>
                                    
                                    <?php if ($evaluation->status === \app\models\Evaluation::STATUS_DRAFT): ?>
                                        <?= Html::a('Завершить', ['evaluation-complete', 'id' => $evaluation->id], [
                                            'class' => 'flex-1 inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors duration-200',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите завершить эту оценку?',
                                                'method' => 'post',
                                            ]
                                        ]) ?>
                                    <?php elseif ($evaluation->status === 'completed'): ?>
                                        <?= Html::a('Сбросить', ['evaluation-reset', 'id' => $evaluation->id], [
                                            'class' => 'flex-1 inline-flex justify-center items-center px-3 py-2 bg-yellow-50 text-yellow-700 font-medium rounded-lg hover:bg-yellow-100 transition-colors duration-200',
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите сбросить статус этой оценки?',
                                                'method' => 'post',
                                            ]
                                        ]) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="hidden md:block">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                        'layout' => "{items}\n<div class='px-8 py-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0'>{pager}\n<div class='text-sm text-gray-600'>{summary}</div></div>",
                        'emptyText' => '',
                        'summary' => 'Показано <b class="text-gray-800">{begin}-{end}</b> из <b class="text-gray-800">{totalCount}</b> оценок',
                        'rowOptions' => function ($model, $key, $index, $grid) {
                            return [
                                'class' => 'hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition duration-150',
                            ];
                        },
                        'columns' => [
                            [
                                'attribute' => 'application.work_name',
                                'label' => 'РАБОТА',
                                'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-base font-semibold text-gray-900'],
                                'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'attribute' => 'expert.fullName',
                                'label' => 'ЭКСПЕРТ',
                                'value' => function ($model) {
                                    return $model->expert->surname . ' ' . $model->expert->name;
                                },
                                'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm text-gray-700'],
                                'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'attribute' => 'total_score',
                                'label' => 'БАЛЛ',
                                'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm font-bold text-gray-900'],
                                'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'СТАТУС',
                                'value' => function ($model) {
                                    if ($model->status === \app\models\Evaluation::STATUS_DRAFT) {
                                        return '
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                    Черновик
                                                </span>
                                            </div>';
                                    } elseif ($model->status === 'completed') {
                                        return '
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                    Завершено
                                                </span>
                                            </div>';
                                    } else {
                                        return '
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-gray-500"></div>
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                                    ' . $model->status . '
                                                </span>
                                            </div>';
                                    }
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'attribute' => 'updated_at',
                                'label' => 'ОБНОВЛЕНО',
                                'format' => ['date', 'php:d.m.Y H:i'],
                                'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm text-gray-600'],
                                'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {complete} {reset}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('Просмотр', ['evaluation-view', 'id' => $model->id], [
                                            'class' => 'inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all',
                                            'title' => 'Просмотр деталей оценки'
                                        ]);
                                    },
                                    'complete' => function ($url, $model, $key) {
                                        if ($model->status === \app\models\Evaluation::STATUS_DRAFT) {
                                            return Html::a('Завершить', ['evaluation-complete', 'id' => $model->id], [
                                                'class' => 'inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-green-500 to-emerald-500 text-white hover:from-green-600 hover:to-emerald-600 transition-all ml-2',
                                                'title' => 'Перевести в статус "Завершено"',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите завершить эту оценку? Это действие изменит статус с "Черновик" на "Завершено".',
                                                    'method' => 'post',
                                                ]
                                            ]);
                                        }
                                        return '';
                                    },
                                    'reset' => function ($url, $model, $key) {
                                        if ($model->status === 'completed') {
                                            return Html::a('Сбросить', ['evaluation-reset', 'id' => $model->id], [
                                                'class' => 'inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-yellow-500 to-amber-500 text-white hover:from-yellow-600 hover:to-amber-600 transition-all ml-2',
                                                'title' => 'Сбросить статус на "Черновик"',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите сбросить статус этой оценки на "Черновик"?',
                                                    'method' => 'post',
                                                ]
                                            ]);
                                        }
                                        return '';
                                    },
                                ],
                                'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-right text-sm font-medium space-x-2'],
                                'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-right text-sm font-bold text-gray-700 uppercase tracking-wider'],
                            ],
                        ],
                        'pager' => [
                            'options' => ['class' => 'flex space-x-2'],
                            'linkOptions' => ['class' => 'px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:shadow transition-all'],
                            'pageCssClass' => 'inline-flex',
                            'prevPageCssClass' => 'inline-flex',
                            'nextPageCssClass' => 'inline-flex',
                            'activePageCssClass' => 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white border-purple-600',
                            'disabledPageCssClass' => 'opacity-50 cursor-not-allowed hover:bg-white',
                        ],
                    ]); ?>
                </div>
                
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 flex items-center justify-between border-t border-blue-200">
                    <div class="flex-1 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Показано
                                <span class="font-bold text-blue-600"><?= $dataProvider->getCount() ?></span>
                                из
                                <span class="font-bold text-blue-600"><?= $dataProvider->getTotalCount() ?></span>
                                оценок
                            </p>
                        </div>
                        <div>
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'inline-flex rounded-lg shadow-sm'],
                                'linkContainerOptions' => ['class' => ''],
                                'linkOptions' => ['class' => 'relative inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:shadow transition-all'],
                                'pageCssClass' => '',
                                'prevPageCssClass' => 'px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-l-lg',
                                'nextPageCssClass' => 'px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-r-lg',
                                'activePageCssClass' => 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white border-purple-600',
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    document.querySelectorAll('button[id^="mobile-eval-menu-button-"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menuId = this.id.replace('mobile-eval-menu-button-', 'mobile-eval-menu-');
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            
            document.querySelectorAll('div[id^="mobile-eval-menu-"]').forEach(function(m) {
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
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button[id^="mobile-eval-menu-button-"]') && !e.target.closest('div[id^="mobile-eval-menu-"]')) {
            document.querySelectorAll('div[id^="mobile-eval-menu-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
});

document.addEventListener('pjax:success', function() {

    document.querySelectorAll('button[id^="mobile-eval-menu-button-"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menuId = this.id.replace('mobile-eval-menu-button-', 'mobile-eval-menu-');
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            
            document.querySelectorAll('div[id^="mobile-eval-menu-"]').forEach(function(m) {
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