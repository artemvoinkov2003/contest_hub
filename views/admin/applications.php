<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->title = 'Управление заявками';
$this->params['breadcrumbs'][] = $this->title;

$searchModel = $searchModel ?? null;
$dataProvider = $dataProvider ?? null;

// Функция для получения класса статуса
function getStatusCssClass($status) {
    $classes = [
        'new' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'accepted' => 'bg-green-100 text-green-800 border-green-300',
        'blocked' => 'bg-red-100 text-red-800 border-red-300',
        'graded' => 'bg-blue-100 text-blue-800 border-blue-300',
        'completed' => 'bg-purple-100 text-purple-800 border-purple-300',
    ];
    return $classes[$status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
}

// Функция для получения текста статуса
function getStatusText($status) {
    $statuses = [
        'new' => 'Новая',
        'accepted' => 'Принята',
        'blocked' => 'Заблокирована',
        'graded' => 'Оценена',
        'completed' => 'Завершена',
    ];
    return $statuses[$status] ?? $status;
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Заявки</h1>
                    <p class="mt-1 text-sm text-blue-100">Управление всеми заявками участников</p>
                </div>
                <a href="<?= Url::to(['application-create']) ?>" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Создать заявку
                </a>
            </div>
        </div>
    </div>

    <!-- Applications Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <?php if ($dataProvider && $dataProvider->getCount() === 0): ?>
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Заявки не найдены</h3>
                <p class="text-gray-500 mb-6">Начните с создания новой заявки</p>
                <a href="<?= Url::to(['application-create']) ?>" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Создать заявку
                </a>
            </div>
        <?php else: ?>
            <!-- Mobile Version (Cards) -->
            <div class="block md:hidden space-y-4">
                <?php foreach ($dataProvider->getModels() as $application): ?>
                <?php
                $result = \app\models\ContestResult::findByApplicationId($application->id);
                $docCount = \app\models\GeneratedDocument::find()
                    ->where(['application_id' => $application->id])
                    ->count();
                ?>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900"><?= Html::encode($application->work_name) ?></h3>
                                <p class="text-sm text-blue-600"><?= Html::encode($application->contest->name ?? '') ?></p>
                            </div>
                            <div class="relative">
                                <button type="button" class="inline-flex items-center p-1 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-sm text-gray-900"><?= Html::encode($application->user->login ?? '') ?></span>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <?= Html::encode($application->nomination->name ?? '') ?>
                                </span>
                            </div>
                            
                            <div class="flex items-center">
                                <?= Html::beginForm(['application-update-status', 'id' => $application->id], 'post', ['class' => 'w-full']) ?>
                                <select name="status" 
                                        onchange="this.form.submit()"
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md <?= getStatusCssClass($application->status) ?>">
                                    <option value="new" <?= $application->status === 'new' ? 'selected' : '' ?>>Новая</option>
                                    <option value="accepted" <?= $application->status === 'accepted' ? 'selected' : '' ?>>Принята</option>
                                    <option value="blocked" <?= $application->status === 'blocked' ? 'selected' : '' ?>>Заблокирована</option>
                                    <option value="graded" <?= $application->status === 'graded' ? 'selected' : '' ?>>Оценена</option>
                                    <option value="completed" <?= $application->status === 'completed' ? 'selected' : '' ?>>Завершена</option>
                                </select>
                                <?= Html::endForm() ?>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <a href="<?= Url::to(['application-view', 'id' => $application->id]) ?>" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                            Просмотр
                                        </a>
                                        <a href="<?= Url::to(['application-update', 'id' => $application->id]) ?>" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200">
                                            Редакт.
                                        </a>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        <?= Yii::$app->formatter->asDate($application->created_at, 'php:d.m.Y') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Version (Table) -->
            <div class="hidden md:block">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Работа</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Участник</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номинация</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($dataProvider->getModels() as $application): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= Html::encode($application->work_name) ?></div>
                                    <div class="text-sm text-gray-500"><?= Html::encode($application->contest->name ?? '') ?></div>                                    
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= Html::encode($application->user->login ?? '') ?></div>
                                    <div class="text-sm text-gray-500"><?= Html::encode($application->surname) ?> <?= Html::encode($application->name) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <?= Html::encode($application->nomination->name ?? '') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= Html::beginForm(['application-update-status', 'id' => $application->id], 'post') ?>
                                    <select name="status" 
                                            onchange="this.form.submit()"
                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md <?= getStatusCssClass($application->status) ?>">
                                        <option value="new" <?= $application->status === 'new' ? 'selected' : '' ?>>Новая</option>
                                        <option value="accepted" <?= $application->status === 'accepted' ? 'selected' : '' ?>>Принята</option>
                                        <option value="blocked" <?= $application->status === 'blocked' ? 'selected' : '' ?>>Заблокирована</option>
                                        <option value="graded" <?= $application->status === 'graded' ? 'selected' : '' ?>>Оценена</option>
                                        <option value="completed" <?= $application->status === 'completed' ? 'selected' : '' ?>>Завершена</option>
                                    </select>
                                    <?= Html::endForm() ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= Yii::$app->formatter->asDate($application->created_at, 'php:d.m.Y H:i') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= Url::to(['application-view', 'id' => $application->id]) ?>" 
                                           class="text-blue-600 hover:text-blue-900">Просмотр</a>
                                        <a href="<?= Url::to(['application-update', 'id' => $application->id]) ?>" 
                                           class="text-green-600 hover:text-green-900">Редактировать</a>
                                        <?php if ($application->status !== 'blocked'): ?>
                                            <?= Html::a('Заблокировать', ['application-block', 'id' => $application->id], [
                                                'class' => 'text-red-600 hover:text-red-900',
                                                'data' => ['confirm' => 'Вы уверены?', 'method' => 'post']
                                            ]) ?>
                                        <?php else: ?>
                                            <?= Html::a('Разблокировать', ['application-unblock', 'id' => $application->id], [
                                                'class' => 'text-green-600 hover:text-green-900',
                                                'data' => ['confirm' => 'Вы уверены?', 'method' => 'post']
                                            ]) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($dataProvider->pagination): ?>
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?= \yii\widgets\LinkPager::widget([
                            'pagination' => $dataProvider->pagination,
                            'options' => ['class' => 'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50'],
                            'prevPageLabel' => 'Назад',
                            'nextPageLabel' => 'Вперед',
                        ]) ?>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Показано <span class="font-bold text-blue-600"><?= $dataProvider->getCount() ?></span> из <span class="font-bold text-blue-600"><?= $dataProvider->getTotalCount() ?></span>
                            </p>
                        </div>
                        <div>
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'relative z-0 inline-flex rounded-md shadow-sm -space-x-px'],
                                'linkContainerOptions' => ['class' => ''],
                                'linkOptions' => ['class' => 'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50'],
                                'pageCssClass' => 'hidden md:inline-flex',
                                'prevPageCssClass' => 'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50',
                                'nextPageCssClass' => 'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50',
                                'activePageCssClass' => 'z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                            ]) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>