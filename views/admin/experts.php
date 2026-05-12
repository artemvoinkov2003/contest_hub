<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Управление экспертами';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = $dataProvider ?? null;
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Эксперты</h1>
                    <p class="mt-1 text-sm text-blue-100">Управление учетными записями экспертов и их назначениями</p>
                </div>
                <a href="<?= Url::to(['expert-create']) ?>" 
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Добавить эксперта
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Эксперты не найдены</h3>
                    <p class="text-gray-500 mb-6">Начните с добавления нового эксперта</p>
                    <a href="<?= Url::to(['expert-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                        Добавить эксперта
                    </a>
                </div>
            <?php else: ?>

                <div class="block md:hidden">
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($dataProvider->getModels() as $expert): ?>
                            <div class="px-4 py-5 hover:bg-blue-50 transition-all duration-200">
                              
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            <?= Html::encode($expert->surname) ?> <?= Html::encode($expert->name) ?> <?= Html::encode($expert->patronymic) ?>
                                        </h3>
                                        <p class="text-sm text-blue-600 font-medium">@<?= Html::encode($expert->login) ?></p>
                                    </div>
                                    <div class="relative">
                                        <button type="button" class="inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100" id="mobile-menu-button-<?= $expert->id ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>
                                        <div class="absolute right-0 z-10 hidden w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="mobile-menu-<?= $expert->id ?>">
                                            <div class="py-1">
                                                <a href="<?= Url::to(['expert-assignments', 'expert_id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    Назначения
                                                </a>
                                                <a href="<?= Url::to(['expert-assign', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    Назначить
                                                </a>
                                                <a href="<?= Url::to(['expert-update', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    Редактировать
                                                </a>
                                                <?php if (!$expert->is_blocked): ?>
                                                    <a href="<?= Url::to(['expert-block', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" data-confirm="Вы уверены, что хотите заблокировать этого эксперта?">
                                                        Заблокировать
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= Url::to(['expert-unblock', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                        Разблокировать
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 break-all"><?= Html::encode($expert->email) ?></span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm <?= $expert->is_blocked ? 'bg-gradient-to-r from-red-400 to-pink-400 text-white' : 'bg-gradient-to-r from-green-400 to-emerald-400 text-white' ?>">
                                            <?= $expert->is_blocked ? 'Заблокирован' : 'Активен' ?>
                                        </span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">
                                            Зарегистрирован: <?= Yii::$app->formatter->asDate($expert->created_at, 'php:d.m.Y H:i') ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex space-x-2 pt-4">
                                    <a href="<?= Url::to(['expert-assignments', 'expert_id' => $expert->id]) ?>" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Назначения
                                    </a>
                                    <a href="<?= Url::to(['expert-update', 'id' => $expert->id]) ?>" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Редакт.
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            ФИО
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Email
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
                                            Дата регистрации
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($dataProvider->getModels() as $expert): ?>
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">
                                                <?= Html::encode($expert->surname) ?> <?= Html::encode($expert->name) ?> <?= Html::encode($expert->patronymic) ?>
                                            </div>
                                            <div class="text-xs text-blue-600 font-medium"><?= Html::encode($expert->login) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            <?= Html::encode($expert->email) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                                <?= $expert->is_blocked ? 'bg-gradient-to-r from-red-400 to-pink-400 text-white' : 'bg-gradient-to-r from-green-400 to-emerald-400 text-white' ?>">
                                                <?= $expert->is_blocked ? 'Заблокирован' : 'Активен' ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            <?= Yii::$app->formatter->asDate($expert->created_at, 'php:d.m.Y H:i') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end">
                                                <div class="relative inline-block text-left">
                                                    <div>
                                                        <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500" id="menu-button-<?= $expert->id ?>">
                                                            Действия
                                                            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="absolute right-0 z-10 hidden w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="menu-<?= $expert->id ?>">
                                                        <div class="py-1">
                                                            <a href="<?= Url::to(['expert-assignments', 'expert_id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Назначения
                                                            </a>
                                                            <a href="<?= Url::to(['expert-assign', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Назначить
                                                            </a>
                                                            <a href="<?= Url::to(['expert-update', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Редактировать
                                                            </a>
                                                            <?php if (!$expert->is_blocked): ?>
                                                                <a href="<?= Url::to(['expert-block', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" data-confirm="Вы уверены, что хотите заблокировать этого эксперта?">
                                                                    Заблокировать
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="<?= Url::to(['expert-unblock', 'id' => $expert->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                    Разблокировать
                                                                </a>
                                                            <?php endif; ?>
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
                                экспертов
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