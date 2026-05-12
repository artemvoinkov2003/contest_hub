<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Заявки для оценки';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Заявки для оценки</h1>
            <p class="text-gray-600">Список заявок, доступных для оценки</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Filters -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>

            <!-- Applications List -->
            <div class="overflow-x-auto">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                    'layout' => "{items}\n{pager}",
                    'pager' => [
                        'options' => ['class' => 'px-6 py-3 bg-gray-50 border-t border-gray-200'],
                    ],
                    'columns' => [
                        [
                            'attribute' => 'work_name',
                            'label' => 'Работа',
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'],
                        ],
                        [
                            'attribute' => 'contest.name',
                            'label' => 'Конкурс',
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-500'],
                        ],
                        [
                            'attribute' => 'nomination.name',
                            'label' => 'Номинация',
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-500'],
                        ],
                        [
                            'attribute' => 'ageCategory.name',
                            'label' => 'Возрастная категория',
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-500'],
                        ],
                        [
                            'attribute' => 'status',
                            'label' => 'Статус заявки',
                            'value' => function($model) {
                                return $model->getStatusLabel();
                            },
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-500'],
                        ],
                        [
                            'label' => 'Оценка',
                            'value' => function($model) use ($user) {
                                $evaluation = \app\models\Evaluation::findOne([
                                    'application_id' => $model->id,
                                    'expert_id' => $user->id,
                                ]);
                                
                                if ($evaluation) {
                                    if ($evaluation->status == 'completed') {
                                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Оценено: ' . $evaluation->total_score . '
                                        </span>';
                                    } else {
                                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Черновик
                                        </span>';
                                    }
                                } else {
                                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Не оценено
                                    </span>';
                                }
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-500'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {evaluate}',
                            'buttons' => [
                                'view' => function($url, $model) {
                                    return Html::a('Просмотр заявки', ['application/view', 'id' => $model->id], [
                                        'class' => 'inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50'
                                    ]);
                                },
                                'evaluate' => function($url, $model) use ($user) {
                                    $evaluation = \app\models\Evaluation::findOne([
                                        'application_id' => $model->id,
                                        'expert_id' => $user->id,
                                    ]);
                                    
                                    if ($evaluation) {
                                        $label = $evaluation->status == 'completed' ? 'Просмотр оценки' : 'Продолжить оценку';
                                    } else {
                                        $label = 'Оценить';
                                    }
                                    
                                    return Html::a($label, ['evaluation/create', 'application_id' => $model->id], [
                                        'class' => 'inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 ml-2'
                                    ]);
                                }
                            ],
                            'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm font-medium text-right'],
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
        
        <!-- Additional links -->
        <div class="mt-6 flex space-x-4">
            <?= Html::a('Мои оценки', ['evaluation/my-evaluations'], [
                'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50'
            ]) ?>
            
            <?= Html::a('На главную', ['site/index'], [
                'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700'
            ]) ?>
        </div>
    </div>
</div>