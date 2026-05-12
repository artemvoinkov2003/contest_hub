<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Contest[] $contests */
/** @var app\models\Nomination[] $nominations */
/** @var int|null $selectedContestId */
/** @var int|null $selectedNominationId */

$this->title = 'Рейтинг оцененных заявок';
$this->params['breadcrumbs'][] = $this->title;

// Статистика эксперта
$expertId = Yii::$app->user->id;
$evaluations = \app\models\Evaluation::find()
    ->where(['expert_id' => $expertId, 'status' => 'completed'])
    ->all();

$evaluatedCount = count($evaluations);
$totalScore = 0;
foreach ($evaluations as $evaluation) {
    $totalScore += $evaluation->total_score;
}
$averageScore = $evaluatedCount > 0 ? round($totalScore / $evaluatedCount, 2) : 0;
?>
<div class="expert-ranking">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок страницы -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-2 text-lg text-gray-600">Рейтинг заявок, которые вы оценили</p>
                </div>
            </div>
        </div>

        <!-- Статистика эксперта -->
        <div class="bg-white rounded-xl shadow-md mb-6 p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Статистика эксперта</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center">
                        <div class="text-3xl text-blue-600 mr-4">📊</div>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Оценено работ</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $evaluatedCount ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                    <div class="flex items-center">
                        <div class="text-3xl text-green-600 mr-4">⭐</div>
                        <div>
                            <p class="text-sm font-medium text-green-800">Средняя ваша оценка</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $averageScore ?>/10</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                    <div class="flex items-center">
                        <div class="text-3xl text-purple-600 mr-4">📤</div>
                        <div>
                            <p class="text-sm font-medium text-purple-800">Экспорт данных</p>
                            <?= Html::a('Скачать Excel', ['export-evaluations'], [
                                'class' => 'mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700',
                                'data-method' => 'post'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтры -->
        <div class="bg-white rounded-xl shadow-md mb-6 p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Фильтры</h3>
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['ranking'],
                'options' => ['class' => 'space-y-4']
            ]); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Конкурс</label>
                    <select name="contest_id" 
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Все конкурсы</option>
                        <?php foreach ($contests as $contest): ?>
                            <option value="<?= $contest->id ?>" <?= $selectedContestId == $contest->id ? 'selected' : '' ?>>
                                <?= Html::encode($contest->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Номинация</label>
                    <select name="nomination_id" 
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Все номинации</option>
                        <?php foreach ($nominations as $nomination): ?>
                            <option value="<?= $nomination->id ?>" <?= $selectedNominationId == $nomination->id ? 'selected' : '' ?>>
                                <?= Html::encode($nomination->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex items-end space-x-4">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                        Применить
                    </button>
                    <a href="<?= Url::to(['ranking']) ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Сбросить
                    </a>
                </div>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>

        <!-- Основной контент -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Рейтинг заявок</h2>
                        <p class="text-sm text-gray-500">Заявки отсортированы по итоговому баллу</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'min-w-full divide-y divide-gray-200'],
                    'layout' => "{items}\n<div class='mt-6 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0'>{pager}\n<div class='text-sm text-gray-500'>{summary}</div></div>",
                    'emptyText' => '
                        <div class="text-center py-16">
                            <div class="bg-gray-50 rounded-xl p-8 inline-block">
                                <div class="text-4xl text-gray-400 mb-4">📊</div>
                            </div>
                            <h3 class="mt-6 text-2xl font-bold text-gray-900">Нет оцененных заявок</h3>
                            <p class="mt-3 text-lg text-gray-500 max-w-md mx-auto">У вас нет завершенных оценок для отображения рейтинга.</p>
                        </div>
                    ',
                    'summary' => 'Показано <b>{begin}-{end}</b> из <b>{totalCount}</b> заявок',
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        $contestResult = \app\models\ContestResult::findOne(['application_id' => $model->id]);
                        $rowClass = 'hover:bg-gray-50';
                        if ($contestResult && $contestResult->place == 1) {
                            $rowClass .= ' bg-yellow-50';
                        } elseif ($contestResult && $contestResult->place == 2) {
                            $rowClass .= ' bg-gray-50';
                        } elseif ($contestResult && $contestResult->place == 3) {
                            $rowClass .= ' bg-orange-50';
                        }
                        return ['class' => $rowClass];
                    },
                    'columns' => [
                        [
                            'attribute' => 'contest.name',
                            'label' => 'КОНКУРС',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm text-gray-900'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'attribute' => 'nomination.name',
                            'label' => 'НОМИНАЦИЯ',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm text-gray-900'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'УЧАСТНИК',
                            'value' => function ($model) {
                                return Html::encode($model->surname . ' ' . $model->name);
                            },
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm text-gray-900'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'attribute' => 'work_name',
                            'label' => 'НАЗВАНИЕ РАБОТЫ',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-base font-medium text-gray-900'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'ВАШ БАЛЛ',
                            'value' => function ($model) {
                                $evaluation = \app\models\Evaluation::find()
                                    ->where(['application_id' => $model->id, 'expert_id' => Yii::$app->user->id])
                                    ->one();
                                if ($evaluation) {
                                    return '<div class="text-center">
                                                <span class="font-bold text-gray-900 text-lg">' . $evaluation->total_score . '</span>
                                                <div class="text-xs text-gray-500">из 10</div>
                                            </div>';
                                }
                                return '<span class="text-gray-400">—</span>';
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'СРЕДНИЙ БАЛЛ',
                            'value' => function ($model) {
                                $contestResult = \app\models\ContestResult::findOne(['application_id' => $model->id]);
                                if ($contestResult && $contestResult->final_score) {
                                    return '<div class="text-center">
                                                <span class="font-bold text-gray-900 text-lg">' . $contestResult->final_score . '</span>
                                                <div class="text-xs text-gray-500">итоговый</div>
                                            </div>';
                                }
                                
                                // Если нет итогового, считаем средний по всем экспертам
                                $evaluations = \app\models\Evaluation::find()
                                    ->where(['application_id' => $model->id, 'status' => 'completed'])
                                    ->all();
                                
                                if (count($evaluations) > 0) {
                                    $total = 0;
                                    foreach ($evaluations as $eval) {
                                        $total += $eval->total_score;
                                    }
                                    $average = round($total / count($evaluations), 2);
                                    return '<div class="text-center">
                                                <span class="font-bold text-gray-900 text-lg">' . $average . '</span>
                                                <div class="text-xs text-gray-500">из ' . count($evaluations) . ' экспертов</div>
                                            </div>';
                                }
                                
                                return '<span class="text-gray-400">—</span>';
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'ИТОГОВОЕ МЕСТО',
                            'value' => function ($model) {
                                $contestResult = \app\models\ContestResult::findOne(['application_id' => $model->id]);
                                if ($contestResult && $contestResult->place) {
                                    $badgeClass = '';
                                    $icon = '';
                                    
                                    switch ($contestResult->place) {
                                        case 1:
                                            $badgeClass = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                            $icon = '🥇';
                                            break;
                                        case 2:
                                            $badgeClass = 'bg-gray-100 text-gray-800 border-gray-300';
                                            $icon = '🥈';
                                            break;
                                        case 3:
                                            $badgeClass = 'bg-orange-100 text-orange-800 border-orange-300';
                                            $icon = '🥉';
                                            break;
                                        default:
                                            $badgeClass = 'bg-blue-100 text-blue-800 border-blue-300';
                                            $icon = '🏅';
                                    }
                                    
                                    return '<span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border ' . $badgeClass . '">
                                                ' . $icon . ' <span class="ml-1">' . $contestResult->place . ' место</span>
                                            </span>';
                                }
                                return '<span class="text-gray-400">—</span>';
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'ТИП НАГРАДЫ',
                            'value' => function ($model) {
                                $contestResult = \app\models\ContestResult::findOne(['application_id' => $model->id]);
                                if ($contestResult && $contestResult->award_type) {
                                    $awardTypes = [
                                        'first' => 'Диплом I степени',
                                        'second' => 'Диплом II степени',
                                        'third' => 'Диплом III степени',
                                        'participant' => 'Сертификат участника',
                                        'winner' => 'Диплом победителя',
                                        'laureate' => 'Диплом лауреата',
                                        'diploma' => 'Диплом',
                                        'certificate' => 'Сертификат'
                                    ];
                                    
                                    $awardName = $awardTypes[$contestResult->award_type] ?? $contestResult->award_type;
                                    return '<span class="font-medium text-gray-900">' . $awardName . '</span>';
                                }
                                return '<span class="text-gray-400">—</span>';
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
                        ],
                        [
                            'label' => 'ДИПЛОМ',
                            'value' => function ($model) {
                                $documents = \app\models\GeneratedDocument::findDiplomasByApplicationId($model->id);
                                if (!empty($documents)) {
                                    $document = $documents[0];
                                    return '<div class="space-y-2">
                                                <a href="' . $document->getFileUrl() . '" 
                                                   target="_blank"
                                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-green-300 text-green-700 bg-green-50 hover:bg-green-100">
                                                    Просмотреть
                                                </a>
                                                <a href="' . $document->getFileUrl() . '" 
                                                   download
                                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700">
                                                    Скачать
                                                </a>
                                            </div>';
                                }
                                
                                // Проверяем, можно ли запросить диплом
                                $contestResult = \app\models\ContestResult::findOne(['application_id' => $model->id]);
                                if ($contestResult && $contestResult->place && $contestResult->award_type) {
                                    return Html::a('Запросить диплом', ['request-diploma', 'id' => $model->id], [
                                        'class' => 'inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Отправить запрос на генерацию диплома?'
                                    ]);
                                }
                                
                                return '<span class="text-gray-400">Не доступен</span>';
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'px-6 py-5 whitespace-nowrap text-sm'],
                            'headerOptions' => ['class' => 'px-6 py-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider'],
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
            </div>
        </div>
    </div>

</div>
