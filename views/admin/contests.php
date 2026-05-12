<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Управление конкурсами';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = $dataProvider ?? null;

function getTemplateStatus($contestId) {
    $types = ['program', 'scoresheet', 'diploma'];
    $hasTemplates = false;
    $missingTypes = [];
    
    foreach ($types as $type) {
        $template = \app\models\ReportTemplate::find()
            ->where(['contest_id' => $contestId, 'type' => $type])
            ->orWhere(['contest_id' => null, 'type' => $type])
            ->one();
            
        if (!$template || !$template->fileExists()) {
            $missingTypes[] = $type;
        } else {
            $hasTemplates = true;
        }
    }
    
    return [
        'hasTemplates' => $hasTemplates,
        'missingTypes' => $missingTypes,
        'allExist' => empty($missingTypes)
    ];
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
 
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Конкурсы</h1>
                    <p class="mt-1 text-sm text-blue-100">Управление конкурсами и их материалами</p>
                </div>
                <a href="<?= Url::to(['contest-create']) ?>" 
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Добавить конкурс
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php if (!$dataProvider || $dataProvider->getCount() === 0): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Конкурсы не найдены</h3>
                    <p class="text-gray-500 mb-6">Начните с создания нового конкурса</p>
                    <a href="<?= Url::to(['contest-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                        Создать конкурс
                    </a>
                </div>
            <?php else: ?>

                <div class="block md:hidden">
                    <div class="space-y-4 p-4">
                        <?php foreach ($dataProvider->getModels() as $contest): ?>
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md border border-blue-100 p-4">
                                <div class="space-y-4">
                           
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900"><?= Html::encode($contest->name) ?></h3>
                                            <p class="text-sm text-gray-600 mt-1"><?= Html::encode(mb_substr($contest->description, 0, 120)) ?>...</p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ml-2 <?= $contest->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                            <?= $contest->status ? 'Активен' : 'Неактивен' ?>
                                        </span>
                                    </div>

                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-sm">
                                                <?= Yii::$app->formatter->asDate($contest->start_date, 'php:d.m.Y') ?> - 
                                                <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?>
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 ml-7">
                                            <?php
                                            $today = new DateTime();
                                            $endDate = new DateTime($contest->end_date);
                                            if ($endDate < $today) {
                                                echo '<span class="text-red-500">Завершен</span>';
                                            } elseif ($contest->start_date > date('Y-m-d')) {
                                                echo '<span class="text-blue-500">Не начат</span>';
                                            } else {
                                                echo '<span class="text-green-500">Активен</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <?php
                                    $nominationsCount = \app\models\Nomination::find()->where(['contest_id' => $contest->id])->count();
                                    $ageCategoriesCount = \app\models\AgeCategory::find()->where(['contest_id' => $contest->id])->count();
                                    $applicationsCount = \app\models\Application::find()->where(['contest_id' => $contest->id])->count();
                                    ?>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="text-center p-2 bg-purple-50 rounded-lg">
                                            <div class="font-bold text-purple-700 text-lg"><?= $nominationsCount ?></div>
                                            <div class="text-xs text-purple-600">Номинаций</div>
                                        </div>
                                        <div class="text-center p-2 bg-green-50 rounded-lg">
                                            <div class="font-bold text-green-700 text-lg"><?= $ageCategoriesCount ?></div>
                                            <div class="text-xs text-green-600">Возр. кат.</div>
                                        </div>
                                        <div class="text-center p-2 bg-blue-50 rounded-lg">
                                            <div class="font-bold text-blue-700 text-lg"><?= $applicationsCount ?></div>
                                            <div class="text-xs text-blue-600">Заявок</div>
                                        </div>
                                    </div>

                                    <?php
                                    $resultsCount = \app\models\ContestResult::find()
                                        ->joinWith('application')
                                        ->where(['application.contest_id' => $contest->id])
                                        ->count();
                                    $applicationsCount = \app\models\Application::find()
                                        ->where(['contest_id' => $contest->id, 'status' => ['accepted', 'completed']])
                                        ->count();
                                    $completionPercent = $applicationsCount > 0 ? round(($resultsCount / $applicationsCount) * 100) : 0;
                                    ?>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="font-medium">Результаты:</span>
                                            <span><?= $resultsCount ?>/<?= $applicationsCount ?></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full <?= $completionPercent == 100 ? 'bg-teal-600' : ($completionPercent > 50 ? 'bg-yellow-500' : 'bg-red-500') ?>" 
                                                  style="width: <?= $completionPercent ?>%"></div>
                                        </div>
                                        <div class="text-xs <?= $completionPercent == 100 ? 'text-teal-600' : 'text-gray-500' ?> mt-1">
                                            <?= $completionPercent ?>% завершено
                                        </div>
                                    </div>

                                    <?php
                                    $templateStatus = getTemplateStatus($contest->id);
                                    $templateLabels = [
                                        'program' => 'Программа',
                                        'scoresheet' => 'Оц.лист',
                                        'diploma' => 'Диплом'
                                    ];
                                    ?>
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span class="font-medium">Шаблоны:</span>
                                        </div>
                                        <div class="flex flex-wrap gap-1 ml-7">
                                            <?php foreach (['program', 'scoresheet', 'diploma'] as $type): ?>
                                                <?php $hasTemplate = !in_array($type, $templateStatus['missingTypes']); ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $hasTemplate ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= $hasTemplate ? '✓' : '✗' ?> <?= substr($templateLabels[$type], 0, 3) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-blue-100">
                                        <div class="grid grid-cols-2 gap-2">
                                            <a href="<?= Url::to(['contest-update', 'id' => $contest->id]) ?>" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Редактировать
                                            </a>
                                            <a href="<?= Url::to(['nomination-stats', 'contest_id' => $contest->id]) ?>" 
                                               class="inline-flex items-center justify-center px-3 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                                Статистика
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            Название
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Период
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Статистика
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Результаты
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Документы
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Шаблоны
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        Статус
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($dataProvider->getModels() as $contest): ?>
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900"><?= Html::encode($contest->name) ?></div>
                                            <div class="text-xs text-gray-500 mt-1"><?= Html::encode(mb_substr($contest->description, 0, 100)) ?>...</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?= Yii::$app->formatter->asDate($contest->start_date, 'php:d.m.Y') ?> - 
                                                <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <?php
                                                $today = new DateTime();
                                                $endDate = new DateTime($contest->end_date);
                                                if ($endDate < $today) {
                                                    echo '<span class="text-red-500">Завершен</span>';
                                                } elseif ($contest->start_date > date('Y-m-d')) {
                                                    echo '<span class="text-blue-500">Не начат</span>';
                                                } else {
                                                    echo '<span class="text-green-500">Активен</span>';
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $nominationsCount = \app\models\Nomination::find()->where(['contest_id' => $contest->id])->count();
                                            $ageCategoriesCount = \app\models\AgeCategory::find()->where(['contest_id' => $contest->id])->count();
                                            $applicationsCount = \app\models\Application::find()->where(['contest_id' => $contest->id])->count();
                                            ?>
                                            <div class="flex flex-wrap gap-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <?= $nominationsCount ?> ном.
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <?= $ageCategoriesCount ?> возр. кат.
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <?= $applicationsCount ?> заявок
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $resultsCount = \app\models\ContestResult::find()
                                                ->joinWith('application')
                                                ->where(['application.contest_id' => $contest->id])
                                                ->count();
                                            $applicationsCount = \app\models\Application::find()
                                                ->where(['contest_id' => $contest->id, 'status' => ['accepted', 'completed']])
                                                ->count();
                                            $completionPercent = $applicationsCount > 0 ? round(($resultsCount / $applicationsCount) * 100) : 0;
                                            ?>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= $resultsCount ?> из <?= $applicationsCount ?>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                                                <div class="h-2.5 rounded-full <?= $completionPercent == 100 ? 'bg-teal-600' : ($completionPercent > 50 ? 'bg-yellow-500' : 'bg-red-500') ?>" 
                                                      style="width: <?= $completionPercent ?>%"></div>
                                            </div>
                                            <div class="text-xs <?= $completionPercent == 100 ? 'text-teal-600' : 'text-gray-500' ?> mt-1">
                                                <?= $completionPercent ?>% завершено
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $docsCount = \app\models\GeneratedDocument::find()
                                                ->joinWith('application')
                                                ->where(['application.contest_id' => $contest->id])
                                                ->count();
                                            ?>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= $docsCount ?> документов
                                            </div>
                                            <?php if ($docsCount > 0): ?>
                                                <?php
                                                $diplomasCount = \app\models\GeneratedDocument::find()
                                                    ->joinWith('application')
                                                    ->where(['application.contest_id' => $contest->id, 'document_type' => 'diploma'])
                                                    ->count();
                                                $certificatesCount = $docsCount - $diplomasCount;
                                                ?>
                                                <div class="text-xs text-gray-500">
                                                    <?= $diplomasCount ?> дипломов, <?= $certificatesCount ?> сертификатов
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $templateStatus = getTemplateStatus($contest->id);
                                            ?>
                                            <div class="flex flex-wrap gap-1">
                                                <?php
                                                $templateLabels = [
                                                    'program' => 'Программа',
                                                    'scoresheet' => 'Оц.лист',
                                                    'diploma' => 'Диплом'
                                                ];
                                                
                                                foreach (['program', 'scoresheet', 'diploma'] as $type) {
                                                    $hasTemplate = !in_array($type, $templateStatus['missingTypes']);
                                                    $colorClass = $hasTemplate ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                                    $icon = $hasTemplate ? '✓' : '✗';
                                                    
                                                    echo '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' . $colorClass . '" title="' . $templateLabels[$type] . '">';
                                                    echo $icon . ' ' . substr($templateLabels[$type], 0, 3);
                                                    echo '</span>';
                                                }
                                                ?>
                                            </div>
                                            <?php if (!$templateStatus['allExist']): ?>
                                                <div class="text-xs text-red-600 mt-1">
                                                    Отсутствуют: 
                                                    <?= implode(', ', array_map(function($type) use ($templateLabels) {
                                                        return $templateLabels[$type] ?? $type;
                                                    }, $templateStatus['missingTypes'])) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                                <?= $contest->status ? 'bg-gradient-to-r from-green-400 to-emerald-400 text-white' : 'bg-gradient-to-r from-gray-400 to-gray-500 text-white' ?>">
                                                <?= $contest->status ? 'Активен' : 'Неактивен' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end">
                                                <div class="relative inline-block text-left">
                                                    <div>
                                                        <button type="button" 
                                                                onclick="document.getElementById('menu-<?= $contest->id ?>').classList.toggle('hidden')"
                                                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                                            Действия
                                                            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="absolute right-0 z-10 hidden w-64 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="menu-<?= $contest->id ?>">
                                                        <div class="py-1">
                                                            <a href="<?= Url::to(['contest-update', 'id' => $contest->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Редактировать
                                                            </a>
                                                            <a href="<?= Url::to(['contest-result-manage', 'contest_id' => $contest->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Управление результатами
                                                            </a>
                                                            <a href="<?= Url::to(['generate-diplomas', 'contest_id' => $contest->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Сгенерировать документы
                                                            </a>
                                                            <a href="<?= Url::to(['nomination-stats', 'contest_id' => $contest->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Статистика номинаций
                                                            </a>
                                                            <a href="<?= Url::to(['export-results', 'contest_id' => $contest->id, 'format' => 'excel']) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Экспорт результатов (Excel)
                                                            </a>
                                                            <?php
                                                            $today = new DateTime();
                                                            $endDate = new DateTime($contest->end_date);
                                                            if ($endDate < $today): ?>
                                                                <a href="<?= Url::to(['contest-download-program', 'id' => $contest->id, 'format' => 'excel']) ?>" 
                                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                    Скачать программу (Excel)
                                                                </a>
                                                                <a href="<?= Url::to(['contest-download-program', 'id' => $contest->id, 'format' => 'word']) ?>" 
                                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                    Скачать программу (Word)
                                                                </a>
                                                                <a href="<?= Url::to(['contest-download-scores', 'id' => $contest->id, 'format' => 'excel']) ?>" 
                                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                    Скачать оценочный лист (Excel)
                                                                </a>
                                                                <a href="<?= Url::to(['contest-download-scores', 'id' => $contest->id, 'format' => 'word']) ?>" 
                                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                    Скачать оценочный лист (Word)
                                                                </a>
                                                            <?php endif; ?>
                                                            <a href="<?= Url::to(['contest-delete-works', 'id' => $contest->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" 
                                                               onclick="return confirm('Вы уверены, что хотите удалить все файлы работ этого конкурса? Заявки останутся в системе.');">
                                                                Удалить работы
                                                            </a>
                                                            <?= Html::a('Удалить конкурс', ['contest-delete', 'id' => $contest->id], [
                                                                'class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                                                                'data' => [
                                                                    'confirm' => 'Вы уверены, что хотите удалить этот конкурс?',
                                                                    'method' => 'post',
                                                                ]
                                                            ]) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 flex items-center justify-between border-t border-blue-200">
                    <div class="flex-1 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Показано
                                <span class="font-bold text-blue-600"><?= $dataProvider->getCount() ?></span>
                                из
                                <span class="font-bold text-blue-600"><?= $dataProvider->getTotalCount() ?></span>
                                конкурсов
                            </p>
                        </div>
                        <div>
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'inline-flex rounded-lg shadow-sm'],
                                'linkContainerOptions' => ['class' => ''],
                                'linkOptions' => ['class' => 'relative inline-flex items-center px-4 py-2 border border-blue-200 bg-white text-sm font-medium text-blue-600 hover:bg-blue-50'],
                                'pageCssClass' => '',
                                'prevPageCssClass' => 'px-4 py-2 border border-blue-200 bg-white text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-l-lg',
                                'nextPageCssClass' => 'px-4 py-2 border border-blue-200 bg-white text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-r-lg',
                                'activePageCssClass' => 'z-10 bg-gradient-to-r from-blue-500 to-purple-500 border-blue-500 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button[onclick*="menu-"]') && !e.target.closest('div[id^="menu-"]')) {
            document.querySelectorAll('div[id^="menu-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
    document.querySelectorAll('a[onclick*="confirm"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('onclick').match(/confirm\('([^']+)'/)[1])) {
                e.preventDefault();
            }
        });
    });
});
</script>