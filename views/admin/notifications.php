<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Управление уведомлениями';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = $dataProvider ?? null;

function getStatusCssClass($status) {
    switch ($status) {
        case 'new': return 'bg-gradient-to-r from-yellow-400 to-amber-400 text-white';
        case 'read': return 'bg-gradient-to-r from-green-400 to-emerald-400 text-white';
        default: return 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
    }
}

function getStatusText($status) {
    switch ($status) {
        case 'new': return 'Новое';
        case 'read': return 'Прочитано';
        default: return ucfirst($status);
    }
}

function getStatusIcon($status) {
    switch ($status) {
        case 'new': 
            return '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM8.5 14.5A2.5 2.5 0 0011 12V5.5a4 4 0 10-4 0V12a2.5 2.5 0 002.5 2.5z"/>
            </svg>';
        case 'read': 
            return '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>';
        default: 
            return '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>';
    }
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">

    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Уведомления</h1>
                    <p class="mt-1 text-sm text-blue-100">Управление системными уведомлениями для пользователей</p>
                </div>
                    <a href="<?= Url::to(['notification-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                        Создать уведомление
                    </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php if (!$dataProvider || $dataProvider->getCount() === 0): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM8.5 14.5A2.5 2.5 0 0011 12V5.5a4 4 0 10-4 0V12a2.5 2.5 0 002.5 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Уведомления не найдены</h3>
                    <p class="text-gray-500 mb-6">Начните с создания нового уведомления</p>
                    <a href="<?= Url::to(['notification-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200">
                        Создать уведомление
                    </a>
                </div>
            <?php else: ?>
                <div class="block md:hidden">
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($dataProvider->getModels() as $notification): ?>
                            <div class="px-4 py-5 hover:bg-blue-50 transition-all duration-200">
                     
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900"><?= Html::encode($notification->title) ?></h3>
                                        <p class="text-sm text-blue-600 font-medium">ID: <?= $notification->id ?></p>
                                    </div>
                                    <div class="relative">
                                        <button type="button" class="inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100" id="mobile-menu-button-<?= $notification->id ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>
                                        <div class="absolute right-0 z-10 hidden w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="mobile-menu-<?= $notification->id ?>">
                                            <div class="py-1">
                                                <a href="<?= Url::to(['notification-update', 'id' => $notification->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    Редактировать
                                                </a>
                                                <a href="<?= Url::to(['notification-delete', 'id' => $notification->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" data-confirm="Вы уверены, что хотите удалить это уведомление?" data-method="post">
                                                    Удалить
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 line-clamp-3">
                                        <?= Html::encode(mb_substr($notification->message, 0, 150)) ?>
                                        <?php if (mb_strlen($notification->message) > 150): ?>...<?php endif; ?>
                                    </p>
                                </div>

                                <div class="space-y-3">
                               
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                <?= Html::encode($notification->user->login ?? 'Все пользователи') ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?= Html::encode($notification->user->surname ?? '') ?> <?= Html::encode($notification->user->name ?? '') ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= getStatusCssClass($notification->status) ?>">
                                            <?= getStatusIcon($notification->status) ?>
                                            <?= getStatusText($notification->status) ?>
                                        </span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm text-gray-900">
                                                <?= Yii::$app->formatter->asDate($notification->created_at, 'php:d.m.Y H:i') ?>
                                            </p>
                                            <p class="text-xs text-gray-500">Дата создания</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex space-x-2 pt-4">
                                    <a href="<?= Url::to(['notification-update', 'id' => $notification->id]) ?>" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Редактировать
                                    </a>
                                    <a href="<?= Url::to(['notification-delete', 'id' => $notification->id]) ?>" 
                                       class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition-colors duration-200"
                                       data-confirm="Вы уверены, что хотите удалить это уведомление?" 
                                       data-method="post">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Удалить
                                    </a>
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                            </svg>
                                            Заголовок
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Пользователь
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        Статус
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Дата создания
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($dataProvider->getModels() as $notification): ?>
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900"><?= Html::encode($notification->title) ?></div>
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-2"><?= Html::encode(mb_substr($notification->message, 0, 100)) ?>...</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900"><?= Html::encode($notification->user->login ?? 'Все пользователи') ?></div>
                                            <div class="text-xs text-green-600">
                                                <?= Html::encode($notification->user->surname ?? '') ?> <?= Html::encode($notification->user->name ?? '') ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm <?= getStatusCssClass($notification->status) ?>">
                                                <?= getStatusText($notification->status) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            <?= Yii::$app->formatter->asDate($notification->created_at, 'php:d.m.Y H:i') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end">
                                                <div class="relative inline-block text-left">
                                                    <div>
                                                        <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500" id="menu-button-<?= $notification->id ?>">
                                                            Действия
                                                            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="absolute right-0 z-10 hidden w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="menu-<?= $notification->id ?>">
                                                        <div class="py-1">
                                                            <a href="<?= Url::to(['notification-update', 'id' => $notification->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Редактировать
                                                            </a>
                                                            <a href="<?= Url::to(['notification-delete', 'id' => $notification->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" data-confirm="Вы уверены, что хотите удалить это уведомление?" data-method="post">
                                                                Удалить
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
                </div>
                
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 flex items-center justify-between border-t border-blue-200">
                    <div class="flex-1 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Показано
                                <span class="font-bold text-blue-600"><?= $dataProvider->getCount() ?></span>
                                из
                                <span class="font-bold text-blue-600"><?= $dataProvider->getTotalCount() ?></span>
                                уведомлений
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

    document.querySelectorAll('button[id^="menu-button-"]').forEach(function(button) {
        button.addEventListener('click', function() {
            const menuId = this.id.replace('menu-button-', 'menu-');
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            

            document.querySelectorAll('div[id^="menu-"]').forEach(function(m) {
                m.classList.add('hidden');
            });
            

            if (isHidden) {
                menu.classList.remove('hidden');
            }
        });
    });
    

    document.querySelectorAll('button[id^="mobile-menu-button-"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menuId = this.id.replace('mobile-menu-button-', 'mobile-menu-');
            const menu = document.getElementById(menuId);
            const isHidden = menu.classList.contains('hidden');
            
    
            document.querySelectorAll('div[id^="mobile-menu-"]').forEach(function(m) {
                if (m.id !== menuId) {
                    m.classList.add('hidden');
                }
            });
            

            if (isHidden) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button[id^="menu-button-"]') && !e.target.closest('div[id^="menu-"]')) {
            document.querySelectorAll('div[id^="menu-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button[id^="mobile-menu-button-"]') && !e.target.closest('div[id^="mobile-menu-"]')) {
            document.querySelectorAll('div[id^="mobile-menu-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
});
</script>