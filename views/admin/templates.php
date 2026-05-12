<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Шаблоны отчетов';
$this->params['breadcrumbs'][] = $this->title;

function getTemplateTypeText($type) {
    $types = [
        'program' => 'Программа',
        'scoresheet' => 'Оценочный лист',
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат',
        'album' => 'Альбом',
    ];
    return $types[$type] ?? $type;
}

function getTemplateTypeCssClass($type) {
    $classes = [
        'program' => 'bg-gradient-to-r from-blue-400 to-blue-500 text-white',
        'scoresheet' => 'bg-gradient-to-r from-green-400 to-emerald-400 text-white',
        'diploma' => 'bg-gradient-to-r from-yellow-400 to-amber-400 text-white',
        'certificate' => 'bg-gradient-to-r from-purple-400 to-violet-400 text-white',
        'album' => 'bg-gradient-to-r from-indigo-400 to-purple-400 text-white',
    ];
    return $classes[$type] ?? 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-1 text-sm text-blue-100">Управление шаблонами документов и отчетов</p>
                </div>
                <a href="<?= Url::to(['template-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                    Создать шаблон
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php if ($dataProvider->getCount() === 0): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Шаблоны не найдены</h3>
                    <p class="text-gray-500 mb-6">Начните с добавления нового шаблона</p>
                    <a href="<?= Url::to(['template-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                        Добавить шаблон
                    </a>
                </div>
            <?php else: ?>
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                    'layout' => "{items}\n<div class='px-8 py-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0'>{pager}\n<div class='text-sm text-gray-600'>{summary}</div></div>",
                    'emptyText' => '',
                    'summary' => 'Показано <b class="text-gray-800">{begin}-{end}</b> из <b class="text-gray-800">{totalCount}</b> шаблонов',
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        return [
                            'class' => 'hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition duration-150',
                        ];
                    },
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => '#',
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center'],
                            'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-b border-blue-200'],
                        ],
                        [
                            'attribute' => 'template_file',
                            'label' => 'ФАЙЛ ШАБЛОНА',
                            'format' => 'raw',
                            'value' => function($model) {
                                return Html::a(
                                    Html::encode($model->displayName),
                                    ['template-view', 'id' => $model->id],
                                    ['class' => 'text-blue-600 hover:text-blue-800 hover:underline font-medium']
                                );
                            },
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900'],
                            'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200'],
                        ],
                        [
                            'attribute' => 'type',
                            'label' => 'ТИП',
                            'value' => function($model) {
                                return getTemplateTypeText($model->type);
                            },
                            'contentOptions' => function($model) {
                                return ['class' => 'px-6 py-4 whitespace-nowrap'];
                            },
                            'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200'],
                        ],
                        [
                            'attribute' => 'contest_id',
                            'label' => 'КОНКУРС',
                            'value' => function($model) {
                                return $model->contest ? $model->contest->name : 'Общий шаблон';
                            },
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900'],
                            'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'СОЗДАН',
                            'format' => ['date', 'php:d.m.Y H:i'],
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-600'],
                            'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200'],
                        ],
                        [
                            'label' => 'ДЕЙСТВИЯ',
                            'format' => 'raw',
                            'value' => function($model) {
                                $buttons = [];
                                
                                if ($model->fileExists()) {
                                    $buttons[] = Html::a('Скачать', ['template-download', 'id' => $model->id], [
                                        'class' => 'inline-flex justify-center items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded hover:bg-green-200 transition-colors duration-200 mr-1',
                                        'title' => 'Скачать шаблон'
                                    ]);
                                }
                                
                                $buttons[] = Html::a('Просмотр', ['template-view', 'id' => $model->id], [
                                    'class' => 'inline-flex justify-center items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded hover:bg-blue-200 transition-colors duration-200 mr-1',
                                    'title' => 'Просмотр шаблона'
                                ]);
                                
                                $buttons[] = Html::a('Редакт.', ['template-update', 'id' => $model->id], [
                                    'class' => 'inline-flex justify-center items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded hover:bg-yellow-200 transition-colors duration-200 mr-1',
                                    'title' => 'Редактировать шаблон'
                                ]);
                                
                                $buttons[] = Html::a('Удалить', ['template-delete', 'id' => $model->id], [
                                    'class' => 'inline-flex justify-center items-center px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded hover:bg-red-200 transition-colors duration-200',
                                    'title' => 'Удалить шаблон',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этот шаблон?',
                                        'method' => 'post',
                                    ],
                                ]);
                                
                                return '<div class="flex justify-end space-x-1">' . implode('', $buttons) . '</div>';
                            },
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-right text-sm font-medium'],
                            'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200'],
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
                <?php Pjax::end(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>