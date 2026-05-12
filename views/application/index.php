<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use app\models\Application;
use app\models\Contest;
use app\models\ContestResult;
use app\models\GeneratedDocument;

$this->title = 'Мои заявки';
$this->params['breadcrumbs'][] = $this->title;

$statuses = Application::getStatuses();

$statusColors = [
    Application::STATUS_NEW => 'bg-blue-100 text-blue-800 border-blue-200',
    Application::STATUS_UNDER_REVIEW => 'bg-yellow-100 text-yellow-800 border-yellow-200', 
    Application::STATUS_BLOCKED => 'bg-red-100 text-red-800 border-red-200',
    Application::STATUS_GRADED => 'bg-green-200 text-green-900 border-green-300 shadow-sm',
];

$statusColors['completed'] = 'bg-purple-100 text-purple-800 border-purple-200';

$userId = Yii::$app->user->id;

// Определяем типы наград для отображения
$awardTypes = [
    'first' => 'Диплом I степени',
    'second' => 'Диплом II степени',
    'third' => 'Диплом III степени',
    'laureate' => 'Диплом лауреата',
    'diploma' => 'Диплом',
    'certificate' => 'Сертификат участника',
];

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600 mb-1">
                    <?= Application::find()->where(['user_id' => $userId])->count() ?>
                </div>
                <div class="text-gray-600 text-sm">Всего заявок</div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-green-600 mb-1">
                    <?= Application::find()->where(['user_id' => $userId, 'status' => Application::STATUS_GRADED])->count() ?>
                </div>
                <div class="text-gray-600 text-sm">Оценено</div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600 mb-1">
                    <?= Application::find()->where(['user_id' => $userId, 'status' => Application::STATUS_NEW])->count() ?>
                </div>
                <div class="text-gray-600 text-sm">Новые</div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-1">
                    <?= Application::find()->where(['user_id' => $userId, 'status' => Application::STATUS_UNDER_REVIEW])->count() ?>
                </div>
                <div class="text-gray-600 text-sm">На проверке</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 md:p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Фильтры и поиск</h3>
            
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['index'],
                'options' => [
                    'class' => 'space-y-4',
                    'id' => 'filter-form'
                ],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'inputOptions' => ['class' => 'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500'],
                    'errorOptions' => ['class' => 'text-red-500 text-sm mt-1'],
                    'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                ],
            ]); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <?= $form->field($searchModel, 'contest_id')->dropDownList(
                    ArrayHelper::map($contests, 'id', 'name'),
                    [
                        'prompt' => 'Все конкурсы',
                        'class' => 'w-full',
                        'value' => Yii::$app->request->get('ApplicationSearch')['contest_id'] ?? ''
                    ]
                ) ?>

                <?= $form->field($searchModel, 'nomination_id')->dropDownList(
                    ArrayHelper::map($nominations, 'id', 'name'),
                    [
                        'prompt' => 'Все номинации',
                        'class' => 'w-full',
                        'value' => Yii::$app->request->get('ApplicationSearch')['nomination_id'] ?? ''
                    ]
                ) ?>

                <?= $form->field($searchModel, 'age_category_id')->dropDownList(
                    ArrayHelper::map($ageCategories, 'id', 'name'),
                    [
                        'prompt' => 'Все возрастные категории',
                        'class' => 'w-full',
                        'value' => Yii::$app->request->get('ApplicationSearch')['age_category_id'] ?? ''
                    ]
                ) ?>

                <?= $form->field($searchModel, 'status')->dropDownList(
                    $statuses,
                    [
                        'prompt' => 'Все статусы',
                        'class' => 'w-full',
                        'value' => Yii::$app->request->get('ApplicationSearch')['status'] ?? ''
                    ]
                ) ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                <?= $form->field($searchModel, 'work_name', [
                    'options' => ['class' => '']
                ])->textInput([
                    'placeholder' => 'Поиск по названию работы',
                    'value' => Yii::$app->request->get('ApplicationSearch')['work_name'] ?? ''
                ]) ?>

                <?= $form->field($searchModel, 'surname', [
                    'options' => ['class' => '']
                ])->textInput([
                    'placeholder' => 'Поиск по фамилии',
                    'value' => Yii::$app->request->get('ApplicationSearch')['surname'] ?? ''
                ]) ?>
            </div>

            <?php if (Yii::$app->user->identity->is_admin): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                    <?= $form->field($searchModel, 'user_login', [
                        'options' => ['class' => '']
                    ])->textInput([
                        'placeholder' => 'Логин пользователя',
                        'value' => Yii::$app->request->get('ApplicationSearch')['user_login'] ?? ''
                    ]) ?>

                    <?= $form->field($searchModel, 'has_results', [
                        'options' => ['class' => '']
                    ])->dropDownList([
                        '' => 'Все',
                        '1' => 'С результатами',
                        '0' => 'Без результатов'
                    ], [
                        'class' => 'w-full',
                        'value' => Yii::$app->request->get('ApplicationSearch')['has_results'] ?? ''
                    ]) ?>

                    <?= $form->field($searchModel, 'has_documents', [
                        'options' => ['class' => '']
                    ])->dropDownList([
                        '' => 'Все',
                        '1' => 'С документами',
                        '0' => 'Без документов'
                    ], [
                        'class' => 'w-full',
                        'value' => Yii::$app->request->get('ApplicationSearch')['has_documents'] ?? ''
                    ]) ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-200">
                <div class="flex gap-2 w-full sm:w-auto">
                    <?= Html::submitButton('Применить фильтры', [
                        'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex-1 sm:flex-none justify-center'
                    ]) ?>
                    
                    <?= Html::a('Сбросить', ['index'], [
                        'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex-1 sm:flex-none justify-center'
                    ]) ?>
                </div>
                
                <div class="text-sm text-gray-500">
                    Найдено: <span class="font-bold"><?= $dataProvider->getTotalCount() ?></span> заявок
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Все заявки</h2>
                <p class="text-gray-600">Просмотр и управление вашими заявки на конкурсы</p>
            </div>
            <?= Html::a('Подать новую заявку', ['contest/index'], [
                'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full lg:w-auto justify-center'
            ]) ?>
        </div>

        <?php if ($dataProvider->getTotalCount() > 0): ?>
          
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php foreach ($dataProvider->getModels() as $model): ?>
                    <?php 
                        $contestResult = ContestResult::findByApplicationId($model->id);
                        $documents = GeneratedDocument::findByApplicationId($model->id);
                        $documentsCount = count($documents);
                    ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
     
                        <div class="p-5 border-b border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 pr-2">
                                    <h3 class="font-bold text-gray-900 text-lg truncate">
                                        <?= Html::encode($model->work_name) ?>
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <?= Html::encode($model->nomination->name) ?>
                                    </p>
                                </div>
                                <?php 
                                    $statusClass = isset($statusColors[$model->status]) 
                                        ? $statusColors[$model->status] 
                                        : 'bg-gray-100 text-gray-800 border-gray-200';
                                ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $statusClass ?> whitespace-nowrap">
                                    <?= $model->getStatusLabel() ?>
                                </span>
                            </div>
                        
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="truncate"><?= Html::encode($model->contest->name) ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    <span><?= Html::encode($model->ageCategory->name) ?></span>
                                </div>
                                <?php if ($model->institution): ?>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="truncate"><?= Html::encode($model->institution) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                     
                        <div class="p-5 border-b border-gray-200">
                            <?php if ($contestResult): ?>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 font-medium mb-1">БАЛЛ</div>
                                        <div class="text-2xl font-bold text-green-600">
                                            <?= $contestResult->final_score !== null ? number_format($contestResult->final_score, 1) : '0.0' ?>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 font-medium mb-1">МЕСТО</div>
                                        <div class="text-2xl font-bold text-blue-600"><?= $contestResult->place ?? '—' ?></div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 font-medium mb-1">НАГРАДА</div>
                                        <div class="text-lg font-semibold text-purple-600">
                                            <?= isset($awardTypes[$contestResult->award_type]) ? $awardTypes[$contestResult->award_type] : ($contestResult->award_type ?: 'Не указано') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="text-gray-400 mb-2">
                                        <svg class="w-12 h-12 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">Результаты еще не определены</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-5">
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php if ($documentsCount > 0): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                        Документы: <?= $documentsCount ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($contestResult && $documentsCount == 0): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Диплом не сгенерирован
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <?= Html::a('Просмотр', ['view', 'id' => $model->id], [
                                    'class' => 'inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                                ]) ?>
                                
                                <?php if ($contestResult): ?>
                                    <?= Html::a('Результаты', ['view-result', 'id' => $model->id], [
                                        'class' => 'inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500'
                                    ]) ?>
                                <?php else: ?>
                                    <?= Html::a('Результаты', ['view', 'id' => $model->id], [
                                        'class' => 'inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-50 cursor-not-allowed opacity-60',
                                        'disabled' => true
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($dataProvider->pagination->pageCount > 1): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-600">
                            Показано <?= $dataProvider->getCount() ?> из <?= $dataProvider->getTotalCount() ?> заявок
                        </div>
                        
                        <?= LinkPager::widget([
                            'pagination' => $dataProvider->pagination,
                            'options' => ['class' => 'flex flex-wrap justify-center gap-1'],
                            'linkContainerOptions' => ['class' => ''],
                            'linkOptions' => ['class' => 'px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50'],
                            'activePageCssClass' => 'bg-blue-50 border-blue-500 text-blue-600',
                            'disabledPageCssClass' => 'opacity-50 cursor-not-allowed hover:bg-transparent hover:text-gray-700 hover:border-gray-300',
                        ]); ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Пустой список -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 md:p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Заявок не найдено</h3>
                    <p class="text-gray-600 mb-6">
                        <?php if (Yii::$app->request->get()): ?>
                            По вашему запросу заявок не найдено. Попробуйте изменить параметры фильтрации.
                        <?php else: ?>
                            У вас еще нет поданых заявок на конкурсы. Создайте первую заявку и примите участие в конкурсе!
                        <?php endif; ?>
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <?php if (Yii::$app->request->get()): ?>
                            <?= Html::a('Сбросить фильтры', ['index'], [
                                'class' => 'inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                            ]) ?>
                        <?php endif; ?>
                        <?= Html::a('Подать первую заявку', ['contest/index'], [
                            'class' => 'inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>