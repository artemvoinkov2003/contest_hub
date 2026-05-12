<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заявки для оценки';
$this->params['breadcrumbs'][] = $this->title;

$statusFilter = Yii::$app->request->get('status', 'all');
?>
<div class="expert-index">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-2 text-base md:text-lg text-gray-600">Список заявок, назначенных вам для оценки</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <div class="inline-flex items-center px-4 md:px-6 py-3 rounded-xl text-sm md:text-base font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-lg hover:shadow-xl transition-shadow duration-300 w-full justify-center md:w-auto">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Панель жюри
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтры -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg mb-8 p-4 md:p-6 border border-gray-100">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Фильтры заявок</h3>
                        <p class="text-sm text-gray-500">Отфильтруйте заявки по статусу оценки</p>
                    </div>
                </div>
                
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['index'],
                    'options' => ['class' => 'w-full']
                ]); ?>
                
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <label class="text-sm font-medium text-gray-700">Статус:</label>
                        <select name="status" onchange="this.form.submit()" 
                                class="block w-full sm:w-56 rounded-lg md:rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 bg-gray-50 py-2.5">
                            <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>Все заявки</option>
                            <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Ожидают оценки</option>
                            <option value="draft" <?= $statusFilter === 'draft' ? 'selected' : '' ?>>Черновики</option>
                            <option value="evaluated" <?= $statusFilter === 'evaluated' ? 'selected' : '' ?>>Оцененные</option>
                            <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>Все эксперты завершили</option>
                        </select>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-4 md:px-5 py-2.5 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Применить
                        </button>
                        <a href="<?= Url::to(['index']) ?>" 
                           class="inline-flex items-center justify-center px-4 md:px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 shadow-sm hover:shadow transition-all w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Сбросить
                        </a>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Заголовок таблицы -->
            <div class="px-4 md:px-8 py-4 md:py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <div class="flex flex-col gap-4">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Список заявок</h2>
                        <p class="text-sm text-gray-500 mt-1">Всего заявок: <span class="font-semibold text-gray-700"><?= $dataProvider->getTotalCount() ?></span></p>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Нажмите на строку для просмотра деталей
                    </div>
                </div>
            </div>

            <!-- Десктопная версия (GridView) -->
            <div class="hidden md:block overflow-x-auto">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                    'layout' => "{items}\n<div class='px-8 py-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0'>{pager}\n<div class='text-sm text-gray-600'>{summary}</div></div>",
                    'emptyText' => '
                        <div class="text-center py-20 px-4">
                            <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-2xl font-bold text-gray-900">Нет заявок для оценки</h3>
                            <p class="mt-3 text-gray-500 max-w-md mx-auto">В данный момент вам не назначены заявки для оценки.</p>
                        </div>
                    ',
                    'summary' => 'Показано <b class="text-gray-800">{begin}-{end}</b> из <b class="text-gray-800">{totalCount}</b> заявок',
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        return [
                            'class' => 'hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition duration-150 cursor-pointer group',
                            'onclick' => 'window.location="' . Url::to(['evaluate', 'id' => $model->id]) . '"'
                        ];
                    },
                    'columns' => [
                        [
                            'attribute' => 'work_name',
                            'label' => 'НАЗВАНИЕ РАБОТЫ',
                            'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-base font-semibold text-gray-900 group-hover:text-purple-700'],
                            'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'attribute' => 'contest.name',
                            'label' => 'КОНКУРС',
                            'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm text-gray-700'],
                            'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'attribute' => 'nomination.name',
                            'label' => 'НОМИНАЦИЯ',
                            'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm text-gray-700'],
                            'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'ИТОГОВЫЙ БАЛЛ',
                            'value' => function ($model) {
                                $totalScore = 0;
                                $completedCount = 0;
                                $evaluations = \app\models\Evaluation::find()
                                    ->where(['application_id' => $model->id, 'status' => 'completed'])
                                    ->all();
                                
                                foreach ($evaluations as $evaluation) {
                                    $totalScore += $evaluation->total_score;
                                    $completedCount++;
                                }
                                
                                if ($completedCount > 0) {
                                    $average = round($totalScore / $completedCount, 2);
                                    return '
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center">
                                                <span class="font-bold text-green-700">' . $average . '</span>
                                            </div>
                                        </div>';
                                } else {
                                    return '<span class="text-gray-400">—</span>';
                                }
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm'],
                            'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'СТАТУС ОЦЕНКИ',
                            'value' => function ($model) {
                                $evaluation = \app\models\Evaluation::find()
                                    ->where(['application_id' => $model->id, 'expert_id' => Yii::$app->user->id])
                                    ->one();
                                
                                $assignedExperts = \app\models\ExpertAssignment::find()
                                    ->where([
                                        'contest_id' => $model->contest_id,
                                        'nomination_id' => $model->nomination_id,
                                        'age_category_id' => $model->age_category_id
                                    ])
                                    ->count();
                                
                                $completedEvaluations = \app\models\Evaluation::find()
                                    ->where(['application_id' => $model->id, 'status' => 'completed'])
                                    ->count();
                                
                                $allExpertsCompleted = ($assignedExperts > 0 && $completedEvaluations >= $assignedExperts);
                                
                                $hasContestResult = \app\models\ContestResult::find()
                                    ->where(['application_id' => $model->id])
                                    ->exists();
                                
                                if (!$evaluation) {
                                    return '
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                Ожидает оценки
                                            </span>
                                        </div>';
                                } elseif ($evaluation->status === \app\models\Evaluation::STATUS_DRAFT) {
                                    return '
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                Черновик
                                            </span>
                                        </div>';
                                } elseif ($allExpertsCompleted) {
                                    $badge = '
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                Все эксперты завершили
                                            </span>';
                                    if ($hasContestResult) {
                                        $badge .= ' 
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-500 text-white text-xs" title="Результат определен">
                                                ✓
                                            </span>';
                                    }
                                    $badge .= '</div>';
                                    return $badge;
                                } else {
                                    return '
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                Оценена
                                            </span>
                                        </div>';
                                }
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm'],
                            'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'ДАТА ПОДАЧИ',
                            'format' => ['date', 'php:d.m.Y'],
                            'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-sm text-gray-600'],
                            'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-left text-sm font-bold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{evaluate}',
                            'buttons' => [
                                'evaluate' => function ($url, $model, $key) {
                                    $evaluation = \app\models\Evaluation::find()
                                        ->where(['application_id' => $model->id, 'expert_id' => Yii::$app->user->id])
                                        ->one();
                                    
                                    $buttonText = $evaluation ? 'Продолжить' : 'Начать';
                                    $buttonClass = $evaluation && $evaluation->status === \app\models\Evaluation::STATUS_DRAFT 
                                        ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800'
                                        : 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700';
                                    
                                    return Html::a($buttonText, $url, [
                                        'class' => "inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg transition-all {$buttonClass}",
                                    ]);
                                },
                            ],
                            'contentOptions' => ['class' => 'px-8 py-6 whitespace-nowrap text-right text-sm font-medium'],
                            'headerOptions' => ['class' => 'px-8 py-4 bg-gray-50 text-right text-sm font-bold text-gray-700 uppercase tracking-wider'],
                        ],
                    ],
                    'pager' => [
                        'options' => ['class' => 'flex flex-wrap gap-2'],
                        'linkOptions' => ['class' => 'px-4 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:shadow transition-all'],
                        'pageCssClass' => 'inline-flex',
                        'prevPageCssClass' => 'inline-flex',
                        'nextPageCssClass' => 'inline-flex',
                        'activePageCssClass' => 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white border-purple-600',
                        'disabledPageCssClass' => 'opacity-50 cursor-not-allowed hover:bg-white',
                    ],
                ]); ?>
            </div>

            <!-- Мобильная версия (карточки) -->
            <div class="md:hidden">
                <?php if ($dataProvider->getTotalCount() > 0): ?>
                    <div class="space-y-4 p-4">
                        <?php foreach ($dataProvider->getModels() as $model): ?>
                            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-150 cursor-pointer" onclick="window.location='<?= Url::to(['evaluate', 'id' => $model->id]) ?>'">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900 truncate"><?= Html::encode($model->work_name) ?></h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <?= Html::encode($model->contest->name) ?>
                                            </span>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <?= Html::encode($model->nomination->name) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <?php
                                        $evaluation = \app\models\Evaluation::find()
                                            ->where(['application_id' => $model->id, 'expert_id' => Yii::$app->user->id])
                                            ->one();
                                        
                                        $assignedExperts = \app\models\ExpertAssignment::find()
                                            ->where([
                                                'contest_id' => $model->contest_id,
                                                'nomination_id' => $model->nomination_id,
                                                'age_category_id' => $model->age_category_id
                                            ])
                                            ->count();
                                        
                                        $completedEvaluations = \app\models\Evaluation::find()
                                            ->where(['application_id' => $model->id, 'status' => 'completed'])
                                            ->count();
                                        
                                        $allExpertsCompleted = ($assignedExperts > 0 && $completedEvaluations >= $assignedExperts);
                                        
                                        $hasContestResult = \app\models\ContestResult::find()
                                            ->where(['application_id' => $model->id])
                                            ->exists();
                                    ?>
                                    
                                    <?php if (!$evaluation): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 ml-2">
                                            Ожидает
                                        </span>
                                    <?php elseif ($evaluation->status === \app\models\Evaluation::STATUS_DRAFT): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 ml-2">
                                            Черновик
                                        </span>
                                    <?php elseif ($allExpertsCompleted): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 ml-2">
                                            Завершено
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 ml-2">
                                            Оценена
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-3">
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 mb-1">Дата подачи</div>
                                        <div class="text-sm font-medium text-gray-700"><?= date('d.m.Y', strtotime($model->created_at)) ?></div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 mb-1">Итоговый балл</div>
                                        <div class="text-sm font-medium text-gray-700">
                                            <?php
                                                $totalScore = 0;
                                                $completedCount = 0;
                                                $evaluations = \app\models\Evaluation::find()
                                                    ->where(['application_id' => $model->id, 'status' => 'completed'])
                                                    ->all();
                                                
                                                foreach ($evaluations as $evaluation) {
                                                    $totalScore += $evaluation->total_score;
                                                    $completedCount++;
                                                }
                                                
                                                if ($completedCount > 0) {
                                                    echo round($totalScore / $completedCount, 2);
                                                } else {
                                                    echo '—';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="pt-3 border-t border-gray-100">
                                    <?php
                                        $evaluation = \app\models\Evaluation::find()
                                            ->where(['application_id' => $model->id, 'expert_id' => Yii::$app->user->id])
                                            ->one();
                                        
                                        $buttonText = $evaluation ? 'Продолжить' : 'Начать';
                                        $buttonClass = $evaluation && $evaluation->status === \app\models\Evaluation::STATUS_DRAFT 
                                            ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800'
                                            : 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700';
                                    ?>
                                    <button onclick="window.location='<?= Url::to(['evaluate', 'id' => $model->id]) ?>'" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg transition-all <?= $buttonClass ?>">
                                        <?= $buttonText ?>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Пагинация для мобильных -->
                    <?php if ($dataProvider->pagination->pageCount > 1): ?>
                        <div class="px-4 py-6 bg-gray-50 border-t border-gray-200">
                            <div class="text-sm text-gray-600 text-center mb-4">
                                Показано <b class="text-gray-800"><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + 1 ?>-<?= min(($dataProvider->pagination->page + 1) * $dataProvider->pagination->pageSize, $dataProvider->totalCount) ?></b> из <b class="text-gray-800"><?= $dataProvider->totalCount ?></b> заявок
                            </div>
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'flex flex-wrap justify-center gap-2'],
                                'linkOptions' => ['class' => 'px-3 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:shadow transition-all'],
                                'activePageCssClass' => 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white border-purple-600',
                                'disabledPageCssClass' => 'opacity-50 cursor-not-allowed hover:bg-white',
                            ]); ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-12 px-4">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Нет заявок для оценки</h3>
                        <p class="text-gray-500">В данный момент вам не назначены заявки для оценки.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>