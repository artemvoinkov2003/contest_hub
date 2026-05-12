<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $contest app\models\Contest */
/* @var $groupedResults array */

$this->title = 'Итоги конкурса: ' . $contest->name;
$this->params['breadcrumbs'][] = ['label' => 'Итоги', 'url' => ['contest-results']];
$this->params['breadcrumbs'][] = $this->title;

// Типы наград
$awardLabels = [
    'first' => 'Диплом I степени',
    'second' => 'Диплом II степени',
    'third' => 'Диплом III степени',
    'laureate' => 'Диплом лауреата',
    'diploma' => 'Диплом',
    'certificate' => 'Сертификат участника',
];

$awardColors = [
    'first' => 'bg-gradient-to-r from-red-400 to-rose-400 text-white',
    'second' => 'bg-gradient-to-r from-orange-400 to-amber-400 text-white',
    'third' => 'bg-gradient-to-r from-yellow-400 to-lime-400 text-white',
    'laureate' => 'bg-gradient-to-r from-purple-400 to-pink-400 text-white',
    'diploma' => 'bg-gradient-to-r from-blue-400 to-cyan-400 text-white',
    'certificate' => 'bg-gradient-to-r from-gray-400 to-gray-500 text-white',
];

// Статистика
$totalResults = 0;
$totalParticipants = 0;
foreach ($groupedResults as $group) {
    $totalResults++;
    $totalParticipants += count($group['results']);
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Заголовок с градиентом -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-6">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-white"><?= Html::encode($this->title) ?></h1>
                    <div class="mt-2 flex flex-wrap gap-2 items-center">
                        <p class="text-sm text-blue-100">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?= Yii::$app->formatter->asDate($contest->start_date) ?> - <?= Yii::$app->formatter->asDate($contest->end_date) ?>
                        </p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white">
                            ID: <?= $contest->id ?>
                        </span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <?= Html::a(
                        'Управление результатами',
                        ['admin/contest-result-manage', 'contest_id' => $contest->id],
                        [
                            'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 transition-all duration-200'
                        ]
                    ) ?>
                    <?= Html::a(
                        'Экспорт в Excel',
                        ['admin/export-results', 'contest_id' => $contest->id, 'format' => 'excel'],
                        [
                            'class' => 'inline-flex items-center px-5 py-2.5 border border-white text-sm font-medium rounded-xl shadow-lg text-white bg-transparent hover:bg-white/10 transition-all duration-200',
                            'target' => '_blank'
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <!-- Быстрые действия -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Быстрые действия</h3>
                            <p class="text-emerald-100">Сгенерируйте дипломы для всех участников одним кликом</p>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="batchGenerateDiplomas()"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-rose-500 to-pink-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Сгенерировать все дипломы
                    </button>
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Всего групп</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $totalResults ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg border border-green-100 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-10.672a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Участников</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $totalParticipants ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg border border-purple-100 p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Номинаций</p>
                        <?php
                        $nominationCount = count(array_unique(array_column(array_column($groupedResults, 'nomination'), 'id')));
                        ?>
                        <p class="text-2xl font-bold text-gray-900"><?= $nominationCount ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg border border-amber-100 p-6">
                <div class="flex items-center">
                    <div class="bg-amber-100 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Возрастных категорий</p>
                        <?php
                        $ageCategoryCount = count(array_unique(array_column(array_column($groupedResults, 'ageCategory'), 'id')));
                        ?>
                        <p class="text-2xl font-bold text-gray-900"><?= $ageCategoryCount ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-blue-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Результаты конкурса</h2>
                        <p class="text-sm text-gray-600">Сгруппировано по номинациям и возрастным категориям</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="printResults()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Печать
                        </button>
                        <button onclick="exportToPDF()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            PDF
                        </button>
                    </div>
                </div>
            </div>

            <?php if (empty($groupedResults)): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Результатов нет</h3>
                    <p class="text-gray-500 mb-6">Для этого конкурса еще не сформированы результаты</p>
                    <?= Html::a(
                        'Создать результаты',
                        ['admin/contest-result-manage', 'contest_id' => $contest->id],
                        [
                            'class' => 'inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200'
                        ]
                    ) ?>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($groupedResults as $key => $group): ?>
                        <?php 
                        usort($group['results'], function($a, $b) {
                            if ($a->place && $b->place) {
                                return $a->place - $b->place;
                            }
                            if ($a->place) return -1;
                            if ($b->place) return 1;
                            return $b->final_score <=> $a->final_score;
                        });
                        ?>
                        
                        <div class="p-6 hover:bg-blue-50/50 transition-all duration-200">
                            <!-- Заголовок группы -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-bold text-gray-900 mr-3">
                                            <?= Html::encode($group['nomination']) ?>
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-500 to-purple-500 text-white">
                                            <?= count($group['results']) ?> участников
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/>
                                        </svg>
                                        <?= Html::encode($group['ageCategory']) ?>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" 
                                            onclick="generateGroupDiplomas('<?= $key ?>')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Дипломы группы
                                    </button>
                                </div>
                            </div>

                            <!-- Таблица результатов -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-gray-50 to-blue-50">
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Место</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Участник</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Работа</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Балл</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Награда</th>
                                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php foreach ($group['results'] as $result): ?>
                                            <?php $application = $result->application; ?>
                                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                                <!-- Место -->
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php if ($result->place): ?>
                                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-yellow-400 to-amber-400 text-white font-bold shadow-lg">
                                                            <?= $result->place ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-gray-400">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <!-- Участник -->
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        <?= Html::encode($application->getFullName()) ?>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <?= Html::encode($application->institution) ?>
                                                        <?php if ($application->leader): ?>
                                                            <br>Рук.: <?= Html::encode($application->leader) ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                
                                                <!-- Работа -->
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= Html::a(
                                                            Html::encode($application->work_name),
                                                            ['admin/application-view', 'id' => $application->id],
                                                            ['class' => 'text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200']
                                                        ) ?>
                                                    </div>
                                                </td>
                                                
                                                <!-- Балл -->
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-green-400 to-green-500 text-white shadow-sm">
                                                        <?= number_format($result->final_score, 1) ?>
                                                    </div>
                                                </td>
                                                
                                                <!-- Награда -->
                                                <td class="px-4 py-4">
                                                    <?php if ($result->award_type && isset($awardLabels[$result->award_type])): ?>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm <?= $awardColors[$result->award_type] ?>">
                                                            <?= $awardLabels[$result->award_type] ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-gray-400">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <!-- Действия -->
                                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="relative inline-block text-left">
                                                        <button type="button" 
                                                                class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                                id="result-menu-<?= $result->id ?>">
                                                            Действия
                                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                            </svg>
                                                        </button>
                                                        <div class="absolute right-0 z-10 hidden w-56 mt-2 origin-top-right bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                                             id="result-menu-dropdown-<?= $result->id ?>">
                                                            <div class="py-1 grid grid-cols-1 gap-1">
                                                                <a href="<?= Url::to(['admin/generate-diploma', 'application_id' => $application->id]) ?>" 
                                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    Сгенерировать диплом
                                                                </a>
                                                                <a href="<?= Url::to(['admin/application-view', 'id' => $application->id]) ?>" 
                                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                    </svg>
                                                                    Просмотр заявки
                                                                </a>
                                                                <a href="<?= Url::to(['admin/contest-result-manage', 'contest_id' => $contest->id]) ?>" 
                                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                    </svg>
                                                                    Изменить результат
                                                                </a>
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
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Подсказки -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="bg-blue-100 p-3 rounded-full mr-4 flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Управление результатами конкурса</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Генерация дипломов</span>
                            </div>
                            <p class="text-sm text-gray-600">Можно генерировать дипломы для всей группы или отдельных участников. Дипломы создаются в формате HTML для печати.</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Экспорт в Excel</span>
                            </div>
                            <p class="text-sm text-gray-600">Выгрузка всех результатов в формате Excel с распределением мест и наград для отчетности и архивирования.</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-amber-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Управление местами</span>
                            </div>
                            <p class="text-sm text-gray-600">Для изменения мест и наград перейдите в раздел "Управление результатами" конкурса.</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Печать результатов</span>
                            </div>
                            <p class="text-sm text-gray-600">Используйте кнопку "Печать" для получения бумажной версии результатов конкурса.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка выпадающих меню
    document.querySelectorAll('button[id^="result-menu-"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const resultId = this.id.replace('result-menu-', '');
            const menuId = 'result-menu-dropdown-' + resultId;
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            
            // Закрываем все остальные меню
            document.querySelectorAll('div[id^="result-menu-dropdown-"]').forEach(function(m) {
                if (m.id !== menuId) {
                    m.classList.add('hidden');
                }
            });
            
            // Открываем/закрываем текущее меню
            if (isHidden) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    });
    
    // Закрытие меню при клике вне его
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button[id^="result-menu-"]') && !e.target.closest('div[id^="result-menu-dropdown-"]')) {
            document.querySelectorAll('div[id^="result-menu-dropdown-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
});

// Функции для кнопок
function batchGenerateDiplomas() {
    if (confirm('Сгенерировать дипломы для всех участников конкурса? Это может занять некоторое время.')) {
        // Собираем все ID заявок
        const applicationIds = [];
        document.querySelectorAll('a[href*="generate-diploma"]').forEach(function(link) {
            const href = link.getAttribute('href');
            const match = href.match(/application_id=(\d+)/);
            if (match) {
                applicationIds.push(match[1]);
            }
        });
        
        if (applicationIds.length > 0) {
            // Отправляем запрос на генерацию всех дипломов
            fetch('<?= Url::to(["admin/batch-generate-diplomas"]) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: 'application_ids=' + applicationIds.join(',')
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Дипломы успешно сгенерированы: ' + data.count + ' документов');
                    location.reload();
                } else {
                    alert('Ошибка: ' + (data.error || 'Неизвестная ошибка'));
                }
            })
            .catch(error => {
                alert('Ошибка сети: ' + error);
            });
        } else {
            alert('Нет заявок для генерации дипломов');
        }
    }
}

function generateGroupDiplomas(groupKey) {
    if (confirm('Сгенерировать дипломы для всех участников этой группы?')) {
        // Находим все заявки в этой группе
        const groupElement = document.querySelector(`div[id*="${groupKey}"]`) || 
                            document.querySelector(`[data-group="${groupKey}"]`);
        if (groupElement) {
            const applicationIds = [];
            groupElement.querySelectorAll('a[href*="generate-diploma"]').forEach(function(link) {
                const href = link.getAttribute('href');
                const match = href.match(/application_id=(\d+)/);
                if (match) {
                    applicationIds.push(match[1]);
                }
            });
            
            if (applicationIds.length > 0) {
                // Отправляем запрос
                fetch('<?= Url::to(["admin/batch-generate-diplomas"]) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: 'application_ids=' + applicationIds.join(',')
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Дипломы для группы успешно сгенерированы: ' + data.count + ' документов');
                        location.reload();
                    } else {
                        alert('Ошибка: ' + (data.error || 'Неизвестная ошибка'));
                    }
                })
                .catch(error => {
                    alert('Ошибка сети: ' + error);
                });
            }
        }
    }
}

function printResults() {
    window.print();
}

function exportToPDF() {
    alert('Функция экспорта в PDF в разработке. Используйте кнопку "Экспорт в Excel" для выгрузки результатов.');
}
</script>