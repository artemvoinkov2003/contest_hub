<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Документы оцененных заявок';
$this->params['breadcrumbs'][] = $this->title;

$applicationId = Yii::$app->request->get('application_id');
$documentType = Yii::$app->request->get('type', 'all');
?>
<div class="expert-documents">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок страницы -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-2 text-lg text-gray-600">Дипломы и сертификаты для заявок, которые вы оценили</p>
                </div>
            </div>
        </div>

        <!-- Фильтры -->
        <div class="bg-white rounded-xl shadow-md mb-6 p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Фильтры</h3>
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['documents'],
                'options' => ['class' => 'flex flex-wrap gap-4 items-center']
            ]); ?>
            
            <input type="hidden" name="application_id" value="<?= $applicationId ?>">
            
            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700">Тип документа:</label>
                <select name="type" onchange="this.form.submit()" 
                        class="block w-48 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" <?= $documentType === 'all' ? 'selected' : '' ?>>Все документы</option>
                    <option value="diploma" <?= $documentType === 'diploma' ? 'selected' : '' ?>>Только дипломы</option>
                    <option value="certificate" <?= $documentType === 'certificate' ? 'selected' : '' ?>>Только сертификаты</option>
                </select>
            </div>
            
            <div>
                <a href="<?= Url::to(['documents']) ?>" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Сбросить фильтры
                </a>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>

        <!-- Основной контент -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Документы</h2>
                        <p class="text-sm text-gray-500">Всего документов: <?= $dataProvider->getTotalCount() ?></p>
                    </div>
                    <?php if ($applicationId): ?>
                        <div>
                            <?= Html::a('Все документы', ['documents'], [
                                'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50'
                            ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-6">
                <?php if ($dataProvider->getTotalCount() == 0): ?>
                    <div class="text-center py-16">
                        <div class="bg-gray-50 rounded-xl p-8 inline-block">
                            <div class="text-4xl text-gray-400 mb-4">📑</div>
                        </div>
                        <h3 class="mt-6 text-2xl font-bold text-gray-900">Нет документов</h3>
                        <p class="mt-3 text-lg text-gray-500 max-w-md mx-auto">
                            Для ваших оцененных заявок еще не сгенерированы документы.
                        </p>
                        <div class="mt-6">
                            <?= Html::a('Перейти к рейтингу', ['ranking'], [
                                'class' => 'inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700'
                            ]) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                        'layout' => "{items}\n<div class='mt-6 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0'>{pager}\n<div class='text-sm text-gray-500'>{summary}</div></div>",
                        'emptyText' => 'Нет документов',
                        'summary' => 'Показано <b>{begin}-{end}</b> из <b>{totalCount}</b> документов',
                        'columns' => [
                            [
                                'attribute' => 'application.work_name',
                                'label' => 'НАЗВАНИЕ РАБОТЫ',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'attribute' => 'application.contest.name',
                                'label' => 'КОНКУРС',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-500'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'ТИП ДОКУМЕНТА',
                                'value' => function ($model) {
                                    $badgeClass = $model->document_type === 'diploma' 
                                        ? 'bg-yellow-100 text-yellow-800' 
                                        : 'bg-blue-100 text-blue-800';
                                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badgeClass . '">
                                                ' . $model->getDocumentTypeName() . '
                                            </span>';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'ВАША ОЦЕНКА',
                                'value' => function ($model) {
                                    $evaluation = \app\models\Evaluation::find()
                                        ->where(['application_id' => $model->application_id, 'expert_id' => Yii::$app->user->id])
                                        ->one();
                                    if ($evaluation) {
                                        return '<div class="text-center">
                                                    <span class="font-bold text-gray-900">' . $evaluation->total_score . '</span>
                                                    <div class="text-xs text-gray-500">ваш балл</div>
                                                </div>';
                                    }
                                    return '<span class="text-gray-400">—</span>';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'ИТОГОВЫЙ БАЛЛ',
                                'value' => function ($model) {
                                    $contestResult = \app\models\ContestResult::findOne(['application_id' => $model->application_id]);
                                    if ($contestResult && $contestResult->final_score) {
                                        return '<div class="text-center">
                                                    <span class="font-bold text-gray-900">' . $contestResult->final_score . '</span>
                                                    <div class="text-xs text-gray-500">средний</div>
                                                </div>';
                                    }
                                    return '<span class="text-gray-400">—</span>';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'РАЗНИЦА',
                                'value' => function ($model) {
                                    $evaluation = \app\models\Evaluation::find()
                                        ->where(['application_id' => $model->application_id, 'expert_id' => Yii::$app->user->id])
                                        ->one();
                                    $contestResult = \app\models\ContestResult::findOne(['application_id' => $model->application_id]);
                                    
                                    if ($evaluation && $contestResult && $contestResult->final_score) {
                                        $diff = $evaluation->total_score - $contestResult->final_score;
                                        $color = $diff > 0 ? 'text-green-600' : ($diff < 0 ? 'text-red-600' : 'text-gray-600');
                                        $sign = $diff > 0 ? '+' : '';
                                        
                                        return '<div class="text-center ' . $color . '">
                                                    <span class="font-bold">' . $sign . round($diff, 2) . '</span>
                                                    <div class="text-xs">разница</div>
                                                </div>';
                                    }
                                    return '<span class="text-gray-400">—</span>';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'СТАТУС ЗАЯВКИ',
                                'value' => function ($model) {
                                    $application = $model->application;
                                    $badgeClass = '';
                                    $text = '';
                                    
                                    switch ($application->status) {
                                        case 'new':
                                            $badgeClass = 'bg-yellow-100 text-yellow-800';
                                            $text = 'Новая';
                                            break;
                                        case 'accepted':
                                            $badgeClass = 'bg-blue-100 text-blue-800';
                                            $text = 'Принята';
                                            break;
                                        case 'graded':
                                            $badgeClass = 'bg-green-100 text-green-800';
                                            $text = 'Оценена';
                                            break;
                                        default:
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            $text = $application->status;
                                    }
                                    
                                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badgeClass . '">
                                                ' . $text . '
                                            </span>';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'ФАЙЛ',
                                'value' => function ($model) {
                                    if ($model->fileExists()) {
                                        return '<div class="flex space-x-2">
                                                    <a href="' . $model->getFileUrl() . '" 
                                                       target="_blank"
                                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                                                        Просмотреть
                                                    </a>
                                                    <a href="' . $model->getFileUrl() . '" 
                                                       download
                                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                                                        Скачать
                                                    </a>
                                                </div>';
                                    }
                                    return '<span class="text-red-600 text-sm">Файл не найден</span>';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                            [
                                'label' => 'ДЕЙСТВИЯ',
                                'value' => function ($model) {
                                    // Проверяем, можно ли запросить диплом, если его нет
                                    $diplomas = \app\models\GeneratedDocument::findDiplomasByApplicationId($model->application_id);
                                    if (empty($diplomas) && $model->application->status === 'graded') {
                                        return Html::a('Запросить диплом', ['request-diploma', 'id' => $model->application_id], [
                                            'class' => 'inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700',
                                            'data-method' => 'post',
                                            'data-confirm' => 'Отправить запрос на генерацию диплома для этой заявки?'
                                        ]);
                                    }
                                    return '<span class="text-gray-400">—</span>';
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'px-6 py-4 whitespace-nowrap text-sm'],
                                'headerOptions' => ['class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider'],
                            ],
                        ],
                        'pager' => [
                            'options' => ['class' => 'flex space-x-2'],
                            'linkOptions' => ['class' => 'px-3 py-2 rounded border border-gray-300 text-gray-700 bg-white hover:bg-gray-50'],
                            'pageCssClass' => 'inline-flex',
                            'prevPageCssClass' => 'inline-flex',
                            'nextPageCssClass' => 'inline-flex',
                            'activePageCssClass' => 'bg-blue-600 text-white border-blue-600',
                            'disabledPageCssClass' => 'opacity-50 cursor-not-allowed hover:bg-white',
                        ],
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
