<?php

use yii\helpers\Url;

$this->title = 'Админ-панель';
// Получаем дополнительные статистики
$stats['totalTemplates'] = \app\models\ReportTemplate::find()->count();
$stats['totalGeneratedDocs'] = \app\models\GeneratedDocument::find()->count();
$stats['totalResults'] = \app\models\ContestResult::find()->count();

// Номинации с достигнутым лимитом
$nominationsAtLimit = \app\models\Nomination::find()
    ->where(['>', 'max_participants', 0])
    ->all();
$atLimitCount = 0;
foreach ($nominationsAtLimit as $nomination) {
    $currentCount = \app\models\Application::find()
        ->where(['nomination_id' => $nomination->id])
        ->andWhere(['status' => ['accepted', 'completed']])
        ->count();
    if ($currentCount >= $nomination->max_participants) {
        $atLimitCount++;
    }
}
$stats['nominationsAtLimit'] = $atLimitCount;
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <h1 class="text-3xl font-bold text-gray-900">Админ-панель</h1>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Applications -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Всего заявок</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['totalApplications'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Contests -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Конкурсов</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['totalContests'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Пользователей</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['totalUsers'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Applications -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Ожидают рассмотрения</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['pendingApplications'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Новые блоки статистики -->
            <!-- Шаблоны отчетов -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Шаблонов отчетов</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['totalTemplates'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Сгенерированных документов -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-pink-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Сгенерированных документов</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['totalGeneratedDocs'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Определенных результатов -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-teal-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Определенных результатов</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['totalResults'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Номинации с достигнутым лимитом -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Номинаций с лимитом</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['nominationsAtLimit'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Quick Actions -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Быстрые действия</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="<?= Url::to(['/admin/applications']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-indigo-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-500 transition duration-150">
                            <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition duration-150">Заявки</h4>
                            <p class="text-sm text-gray-500">Управление заявками</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/contests']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-green-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-500 transition duration-150">
                            <svg class="w-6 h-6 text-green-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-green-600 transition duration-150">Конкурсы</h4>
                            <p class="text-sm text-gray-500">Управление конкурсами</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/users']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-500 transition duration-150">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition duration-150">Пользователи</h4>
                            <p class="text-sm text-gray-500">Управление пользователями</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/experts']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-purple-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-500 transition duration-150">
                            <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-purple-600 transition duration-150">Эксперты</h4>
                            <p class="text-sm text-gray-500">Управление экспертами</p>
                        </div>
                    </div>
                </a>

                <!-- Новые карточки -->
                <a href="<?= Url::to(['/admin/notifications']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-yellow-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-500 transition duration-150">
                            <svg class="w-6 h-6 text-yellow-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM8.5 14.5A2.5 2.5 0 0011 12V5.5a4 4 0 10-4 0V12a2.5 2.5 0 002.5 2.5z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-yellow-600 transition duration-150">Уведомления</h4>
                            <p class="text-sm text-gray-500">Системные уведомления</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/nominations']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-pink-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center group-hover:bg-pink-500 transition duration-150">
                            <svg class="w-6 h-6 text-pink-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-pink-600 transition duration-150">Номинации</h4>
                            <p class="text-sm text-gray-500">Управление номинациями</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/age-categories']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-teal-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-500 transition duration-150">
                            <svg class="w-6 h-6 text-teal-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-teal-600 transition duration-150">Возрастные категории</h4>
                            <p class="text-sm text-gray-500">Управление категориями</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/evaluations']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-orange-500 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-500 transition duration-150">
                            <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-orange-600 transition duration-150">Модерация оценок</h4>
                            <p class="text-sm text-gray-500">Просмотр и управление оценками</p>
                        </div>
                    </div>
                </a>

                <!-- Дополнительные быстрые ссылки на новые разделы -->
                <a href="<?= Url::to(['/admin/templates']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-indigo-600 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition duration-150">
                            <svg class="w-6 h-6 text-indigo-500 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition duration-150">Шаблоны</h4>
                            <p class="text-sm text-gray-500">Управление шаблонами</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/generated-documents']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-pink-600 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-pink-50 rounded-lg flex items-center justify-center group-hover:bg-pink-600 transition duration-150">
                            <svg class="w-6 h-6 text-pink-500 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-pink-600 transition duration-150">Документы</h4>
                            <p class="text-sm text-gray-500">Сгенерированные документы</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/contest-results']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-teal-600 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center group-hover:bg-teal-600 transition duration-150">
                            <svg class="w-6 h-6 text-teal-500 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-teal-600 transition duration-150">Итоги</h4>
                            <p class="text-sm text-gray-500">Итоговые результаты</p>
                        </div>
                    </div>
                </a>

                <a href="<?= Url::to(['/admin/nomination-stats']) ?>" class="group bg-white p-6 rounded-lg border border-gray-200 hover:border-red-600 hover:shadow-md transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center group-hover:bg-red-600 transition duration-150">
                            <svg class="w-6 h-6 text-red-500 group-hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition duration-150">Статистика</h4>
                            <p class="text-sm text-gray-500">Статистика номинаций</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

</div>