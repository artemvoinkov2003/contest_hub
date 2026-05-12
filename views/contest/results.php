<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $contest app\models\Contest */
/* @var $groupedResults array */

$this->title = 'Результаты конкурса: ' . $contest->name;
$this->params['breadcrumbs'][] = ['label' => 'Конкурсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Функция для получения текста места
function getPlaceText($place) {
    if (!$place) return '—';
    
    $endings = [
        1 => '1 место',
        2 => '2 место',
        3 => '3 место',
    ];
    
    return isset($endings[$place]) ? $endings[$place] : $place . ' место';
}

// Функция для получения текста награды
function getAwardText($award_type) {
    $awards = [
        'first' => 'Диплом I степени',
        'second' => 'Диплом II степени',
        'third' => 'Диплом III степени',
        'laureate' => 'Диплом лауреата',
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат участника',
    ];
    
    return isset($awards[$award_type]) ? $awards[$award_type] : ($award_type ?: '—');
}

// Функция для безопасного форматирования числа
function safeNumberFormat($value, $decimals = 2) {
    return $value !== null ? number_format($value, $decimals) : '0.00';
}
?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
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
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="<?= Url::to(['contest/view', 'id' => $contest->id]) ?>" 
                       class="inline-flex items-center px-5 py-2.5 border border-white text-sm font-medium rounded-xl shadow-lg text-white bg-transparent hover:bg-white/10 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Назад к конкурсу
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Всего участников</p>
                        <?php 
                            $totalParticipants = 0;
                            foreach ($groupedResults as $group) {
                                $totalParticipants += count($group['results']);
                            }
                        ?>
                        <p class="text-2xl font-bold text-white"><?= $totalParticipants ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Номинаций</p>
                        <?php
                        $nominationCount = count(array_unique(array_column(array_column($groupedResults, 'nomination'), 'id')));
                        ?>
                        <p class="text-2xl font-bold text-white"><?= $nominationCount ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Возрастных категорий</p>
                        <?php
                        $ageCategoryCount = count(array_unique(array_column(array_column($groupedResults, 'ageCategory'), 'id')));
                        ?>
                        <p class="text-2xl font-bold text-white"><?= $ageCategoryCount ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Призеров</p>
                        <?php 
                            $winners = 0;
                            foreach ($groupedResults as $group) {
                                foreach ($group['results'] as $result) {
                                    if ($result->place && $result->place <= 3) {
                                        $winners++;
                                    }
                                }
                            }
                        ?>
                        <p class="text-2xl font-bold text-white"><?= $winners ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Результаты -->
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-blue-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Итоговые результаты</h2>
                        <p class="text-sm text-gray-600">Поздравляем всех участников конкурса!</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Печать
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
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Результатов пока нет</h3>
                    <p class="text-gray-500 mb-6">Результаты этого конкурса еще не опубликованы организаторами</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach ($groupedResults as $key => $group): ?>
                        <?php 
                        // Сортируем результаты по месту, затем по баллу
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
                                                        <?= Html::encode($application->work_name) ?>
                                                    </div>
                                                </td>
                                                
                                                <!-- Балл -->
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php if ($result->final_score !== null): ?>
                                                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-green-400 to-green-500 text-white shadow-sm">
                                                            <?= safeNumberFormat($result->final_score, 2) ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-gray-400">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <!-- Награда -->
                                                <td class="px-4 py-4">
                                                    <?php if ($result->award_type): ?>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-purple-400 to-pink-400 text-white">
                                                            <?= getAwardText($result->award_type) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-gray-400">—</span>
                                                    <?php endif; ?>
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

        <!-- Информация о конкурсе -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="bg-blue-100 p-3 rounded-full mr-4 flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">О конкурсе</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Критерии оценки</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Мастерство по направлению</li>
                                <li>• Артистизм / Раскрытие художественного образа</li>
                                <li>• Сценическая культура</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Награждение</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Дипломы I, II, III степени</li>
                                <li>• Дипломы лауреатов</li>
                                <li>• Сертификаты участников</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
