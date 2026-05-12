<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Критерии оценки для номинации: ' . $nomination->name;
$this->params['breadcrumbs'][] = ['label' => 'Номинации', 'url' => ['/admin/nominations']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="criteria-index">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-1 text-sm text-blue-100">Управление критериями оценки</p>
                </div>
                <a href="<?= Url::to(['criteria-create', 'nomination_id' => $nomination->id]) ?>" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 transition-all duration-200">
                    <svg class="-ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Добавить критерий
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                'layout' => "{items}\n{pager}",
                'pager' => [
                    'options' => ['class' => 'pagination justify-content-center'],
                    'linkContainerOptions' => ['class' => 'page-item'],
                    'linkOptions' => ['class' => 'page-link'],
                    'prevPageCssClass' => 'page-item',
                    'nextPageCssClass' => 'page-item',
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'description:ntext',
                    'max_score',
                    'order',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a(
                                    'Редактировать',
                                    ['criteria-update', 'id' => $model->id],
                                    ['class' => 'text-blue-600 hover:text-blue-900']
                                );
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(
                                    'Удалить',
                                    ['criteria-delete', 'id' => $model->id],
                                    [
                                        'class' => 'text-red-600 hover:text-red-900',
                                        'data' => [
                                            'confirm' => 'Вы уверены, что хотите удалить этот критерий?',
                                            'method' => 'post',
                                        ],
                                    ]
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>