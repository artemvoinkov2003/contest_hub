<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $contests app\models\Contest[] */

$this->title = 'Итоговые результаты конкурсов';
$this->params['breadcrumbs'][] = $this->title;

// Статусы конкурсов
function getContestStatus($contest) {
    $today = date('Y-m-d');
    if ($contest->status == 0) return ['status' => 'inactive', 'text' => 'Неактивен', 'class' => 'bg-gradient-to-r from-gray-400 to-gray-500 text-white'];
    if ($today < $contest->start_date) return ['status' => 'upcoming', 'text' => 'Скоро начнется', 'class' => 'bg-gradient-to-r from-yellow-400 to-amber-400 text-white'];
    if ($today > $contest->end_date) return ['status' => 'completed', 'text' => 'Завершен', 'class' => 'bg-gradient-to-r from-green-400 to-emerald-400 text-white'];
    return ['status' => 'active', 'text' => 'Активен', 'class' => 'bg-gradient-to-r from-blue-400 to-cyan-400 text-white'];
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Заголовок с градиентом -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Итоговые результаты конкурсов</h1>
                    <p class="mt-1 text-sm text-blue-100">Управление результатами конкурсов и генерация отчетов</p>
                </div>
                <div class="flex space-x-3">
                    <?= Html::a('Генерация документов', ['admin/generated-documents'], [
                        'class' => 'inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 transition-all duration-200'
                    ]) ?>
                    <?= Html::a('Экспорт всех результатов', ['admin/export-results'], [
                        'class' => 'inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-xl shadow-lg text-white bg-transparent hover:bg-white/10 transition-all duration-200'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Всего конкурсов</p>
                        <p class="text-2xl font-bold text-white"><?= count($contests) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-white">Завершенных</p>
                        <?php 
                            $completed = 0;
                            foreach ($contests as $contest) {
                                $status = getContestStatus($contest);
                                if ($status['status'] == 'completed') $completed++;
                            }
                        ?>
                        <p class="text-2xl font-bold text-white"><?= $completed ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-white">Активных</p>
                        <?php 
                            $active = 0;
                            foreach ($contests as $contest) {
                                $status = getContestStatus($contest);
                                if ($status['status'] == 'active') $active++;
                            }
                        ?>
                        <p class="text-2xl font-bold text-white"><?= $active ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl shadow-xl p-6 transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-white">Заявок всего</p>
                        <?php 
                            $totalApps = \app\models\Application::find()->count();
                        ?>
                        <p class="text-2xl font-bold text-white"><?= $totalApps ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Список конкурсов -->
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-blue-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Список конкурсов</h2>
                        <p class="text-sm text-gray-600">Выберите конкурс для управления результатами</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="<?= Url::to(['admin/contest-create']) ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Новый конкурс
                        </a>
                    </div>
                </div>
            </div>

            <?php if (empty($contests)): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Конкурсы не найдены</h3>
                    <p class="text-gray-500 mb-6">Создайте первый конкурс для начала работы</p>
                    <a href="<?= Url::to(['admin/contest-create']) ?>" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200">
                        Создать конкурс
                    </a>
                </div>
            <?php else: ?>
                <!-- Мобильный вид -->
                <div class="block md:hidden divide-y divide-gray-200">
                    <?php foreach ($contests as $contest): ?>
                        <?php
                            $totalApplications = \app\models\Application::find()->where(['contest_id' => $contest->id])->count();
                            $completedApplications = \app\models\Application::find()->where(['contest_id' => $contest->id, 'status' => 'completed'])->count();
                            $totalResults = \app\models\ContestResult::find()->joinWith('application')->where(['application.contest_id' => $contest->id])->count();
                            $statusInfo = getContestStatus($contest);
                        ?>
                        <div class="px-4 py-5 hover:bg-blue-50 transition-all duration-200">
                            <!-- Заголовок конкурса -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900"><?= Html::encode($contest->name) ?></h3>
                                    <div class="flex items-center mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold shadow-sm <?= $statusInfo['class'] ?>">
                                            <?= $statusInfo['text'] ?>
                                        </span>
                                        <span class="ml-2 text-sm text-blue-600 font-medium">ID: <?= $contest->id ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Статистика -->
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div class="text-center bg-blue-50 rounded-lg p-2">
                                    <p class="text-2xl font-bold text-blue-600"><?= $totalApplications ?></p>
                                    <p class="text-xs text-gray-500">Заявок</p>
                                </div>
                                <div class="text-center bg-green-50 rounded-lg p-2">
                                    <p class="text-2xl font-bold text-green-600"><?= $completedApplications ?></p>
                                    <p class="text-xs text-gray-500">Завершено</p>
                                </div>
                                <div class="text-center bg-purple-50 rounded-lg p-2">
                                    <p class="text-2xl font-bold text-purple-600"><?= $totalResults ?></p>
                                    <p class="text-xs text-gray-500">Результатов</p>
                                </div>
                            </div>

                            <!-- Даты -->
                            <div class="flex items-center mb-3">
                                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-900">
                                        <?= Yii::$app->formatter->asDate($contest->start_date, 'php:d.m.Y') ?> - 
                                        <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?>
                                    </p>
                                    <p class="text-xs text-gray-500">Даты проведения</p>
                                </div>
                            </div>

                            <!-- Кнопки действий -->
                            <div class="grid grid-cols-2 gap-2 mt-4">
                                <a href="<?= Url::to(['admin/contest-result-view', 'contest_id' => $contest->id]) ?>" 
                                   class="inline-flex justify-center items-center px-3 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Просмотр
                                </a>
                                <a href="<?= Url::to(['admin/contest-result-manage', 'contest_id' => $contest->id]) ?>" 
                                   class="inline-flex justify-center items-center px-3 py-2 bg-emerald-50 text-emerald-700 font-medium rounded-lg hover:bg-emerald-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Управление
                                </a>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <a href="<?= Url::to(['admin/export-results', 'contest_id' => $contest->id, 'format' => 'excel']) ?>" 
                                   target="_blank"
                                   class="inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Excel
                                </a>
                                <a href="<?= Url::to(['admin/generate-diplomas', 'contest_id' => $contest->id]) ?>" 
                                   class="inline-flex justify-center items-center px-3 py-2 bg-amber-50 text-amber-700 font-medium rounded-lg hover:bg-amber-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Дипломы
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Десктоп вид -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        Конкурс
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                    Статус
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Статистика
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Даты
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                    Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($contests as $contest): ?>
                                <?php
                                    $totalApplications = \app\models\Application::find()->where(['contest_id' => $contest->id])->count();
                                    $completedApplications = \app\models\Application::find()->where(['contest_id' => $contest->id, 'status' => 'completed'])->count();
                                    $totalResults = \app\models\ContestResult::find()->joinWith('application')->where(['application.contest_id' => $contest->id])->count();
                                    $statusInfo = getContestStatus($contest);
                                ?>
                                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                    <!-- Название конкурса -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900"><?= Html::encode($contest->name) ?></div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-2"><?= Html::encode(mb_substr($contest->description, 0, 100)) ?>...</div>
                                    </td>
                                    
                                    <!-- Статус -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-green-400 to-green-500 text-white<?= $statusInfo['class'] ?>">
                                            <?= $statusInfo['text'] ?>
                                        </span>
                                    </td>
                                    
                                    <!-- Статистика -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="grid grid-cols-3 gap-2">
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-blue-600"><?= $totalApplications ?></div>
                                                <div class="text-xs text-gray-500">Заявок</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-green-600"><?= $completedApplications ?></div>
                                                <div class="text-xs text-gray-500">Завершено</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-purple-600"><?= $totalResults ?></div>
                                                <div class="text-xs text-gray-500">Результатов</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Даты -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?= Yii::$app->formatter->asDate($contest->start_date, 'php:d.m.Y') ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Действия -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button" class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="menu-button-<?= $contest->id ?>">
                                                Действия
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <div class="absolute right-0 z-10 hidden w-64 mt-2 origin-top-right bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="menu-<?= $contest->id ?>">
                                                <div class="py-1 grid grid-cols-1 gap-1">
                                                    <a href="<?= Url::to(['admin/contest-result-view', 'contest_id' => $contest->id]) ?>" 
                                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Просмотр результатов
                                                    </a>
                                                    <a href="<?= Url::to(['admin/contest-result-manage', 'contest_id' => $contest->id]) ?>" 
                                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Управление результатами
                                                    </a>
                                                    <a href="<?= Url::to(['admin/export-results', 'contest_id' => $contest->id, 'format' => 'excel']) ?>" 
                                                       target="_blank"
                                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Экспорт в Excel
                                                    </a>
                                                    <a href="<?= Url::to(['admin/generate-diplomas', 'contest_id' => $contest->id]) ?>" 
                                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Генерация дипломов
                                                    </a>
                                                    <a href="<?= Url::to(['admin/contest-program', 'contest_id' => $contest->id]) ?>" 
                                                       target="_blank"
                                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Программа конкурса
                                                    </a>
                                                    <div class="border-t border-gray-200 pt-1 mt-1">
                                                        <a href="<?= Url::to(['admin/contest-update', 'id' => $contest->id]) ?>" 
                                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            Редактировать конкурс
                                                        </a>
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
            <?php endif; ?>
        </div>

        <!-- Подсказки -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="bg-blue-100 p-3 rounded-full mr-4 flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Информация о работе с результатами</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Просмотр результатов</span>
                            </div>
                            <p class="text-sm text-gray-600">Просмотр итоговых результатов конкурса с распределением мест и наград</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Управление результатами</span>
                            </div>
                            <p class="text-sm text-gray-600">Ручное распределение мест и назначение наград участникам</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Экспорт в Excel</span>
                            </div>
                            <p class="text-sm text-gray-600">Выгрузка результатов в формате Excel для отчетности</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-2 bg-amber-500 rounded-full mr-2"></div>
                                <span class="font-medium text-gray-900">Генерация дипломов</span>
                            </div>
                            <p class="text-sm text-gray-600">Автоматическая генерация дипломов и сертификатов для участников</p>
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
    document.querySelectorAll('button[id^="menu-button-"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menuId = this.id.replace('menu-button-', 'menu-');
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            
            // Закрываем все остальные меню
            document.querySelectorAll('div[id^="menu-"]').forEach(function(m) {
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
        if (!e.target.closest('button[id^="menu-button-"]') && !e.target.closest('div[id^="menu-"]')) {
            document.querySelectorAll('div[id^="menu-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
    
    // Анимация статистики
    const statCards = document.querySelectorAll('.transform.hover\\:scale-105');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transition = 'all 0.3s ease';
        });
    });
});
</script>