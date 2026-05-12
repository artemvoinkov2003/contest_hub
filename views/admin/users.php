<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Управление пользователями';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = $dataProvider ?? null;

function getRoleCssClass($isAdmin) {
    return $isAdmin ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800';
}
function getRoleText($isAdmin) {
    return $isAdmin ? 'Администратор' : 'Пользователь';
}
function getStatusCssClass($isBlocked) {
    return $isBlocked ? 'bg-gradient-to-r from-red-400 to-pink-400 text-white' : 'bg-gradient-to-r from-green-400 to-emerald-400 text-white';
}
function getStatusText($isBlocked) {
    return $isBlocked ? 'Заблокирован' : 'Активен';
}
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Пользователи</h1>
                    <p class="mt-1 text-sm text-blue-100">Управление учетными записями всех пользователей системы</p>
                </div>
                    <a href="<?= Url::to(['user-create']) ?>" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Создать пользователя
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Пользователи не найдены</h3>
                    <p class="text-gray-500 mb-6">Начните с создания нового пользователя</p>
                    <a href="<?= Url::to(['user-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                        Создать пользователя
                    </a>
                </div>
            <?php else: ?>
     
                <div class="block md:hidden">
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($dataProvider->getModels() as $user): ?>
                            <div class="px-4 py-5 hover:bg-blue-50 transition-all duration-200">
                              
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            <?= Html::encode($user->surname) ?> <?= Html::encode($user->name) ?> <?= Html::encode($user->patronymic) ?>
                                        </h3>
                                        <p class="text-sm text-blue-600 font-medium">@<?= Html::encode($user->login) ?></p>
                                    </div>
                                    <div class="relative">
                                        <button type="button" class="inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100" id="mobile-menu-button-<?= $user->id ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>
                                        <div class="absolute right-0 z-10 hidden w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="mobile-menu-<?= $user->id ?>">
                                            <div class="py-1">
                                                <a href="<?= Url::to(['user-update', 'id' => $user->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    Редактировать
                                                </a>
                                                <?php if (!$user->is_blocked): ?>
                                                    <form action="<?= Url::to(['user-block', 'id' => $user->id]) ?>" method="post" style="display: inline;">
                                                        <?= Html::csrfMetaTags() ?>
                                                        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" onclick="return confirm('Вы уверены, что хотите заблокировать этого пользователя?')">
                                                            Заблокировать
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form action="<?= Url::to(['user-unblock', 'id' => $user->id]) ?>" method="post" style="display: inline;">
                                                        <?= Html::csrfMetaTags() ?>
                                                        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                            Разблокировать
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?= Url::to(['user-delete', 'id' => $user->id]) ?>" method="post" style="display: inline;">
                                                    <?= Html::csrfMetaTags() ?>
                                                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                             
                                <div class="space-y-3">
          
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 break-all"><?= Html::encode($user->email) ?></span>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= getRoleCssClass($user->is_admin) ?>">
                                            <?= getRoleText($user->is_admin) ?>
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm <?= getStatusCssClass($user->is_blocked) ?>">
                                            <?= getStatusText($user->is_blocked) ?>
                                        </span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">
                                            Зарегистрирован: <?= Yii::$app->formatter->asDate($user->created_at, 'php:d.m.Y H:i') ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex space-x-2 pt-4">
                                    <a href="<?= Url::to(['user-update', 'id' => $user->id]) ?>" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Редактировать
                                    </a>
                                    <?php if (!$user->is_blocked): ?>
                                        <form action="<?= Url::to(['user-block', 'id' => $user->id]) ?>" method="post" class="flex-1">
                                            <?= Html::csrfMetaTags() ?>
                                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition-colors duration-200" onclick="return confirm('Вы уверены, что хотите заблокировать этого пользователя?')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                Заблокировать
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?= Url::to(['user-unblock', 'id' => $user->id]) ?>" method="post" class="flex-1">
                                            <?= Html::csrfMetaTags() ?>
                                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                Разблокировать
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
                                        Роль
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
                                <?php foreach ($dataProvider->getModels() as $user): ?>
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">
                                                <?= Html::encode($user->surname) ?> <?= Html::encode($user->name) ?> <?= Html::encode($user->patronymic) ?>
                                            </div>
                                            <div class="text-xs text-blue-600 font-medium"><?= Html::encode($user->login) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            <?= Html::encode($user->email) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= getRoleCssClass($user->is_admin) ?>">
                                                <?= getRoleText($user->is_admin) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm <?= getStatusCssClass($user->is_blocked) ?>">
                                                <?= getStatusText($user->is_blocked) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            <?= Yii::$app->formatter->asDate($user->created_at, 'php:d.m.Y H:i') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end">
                                                <div class="relative inline-block text-left">
                                                    <div>
                                                        <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500" id="menu-button-<?= $user->id ?>">
                                                            Действия
                                                            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="absolute right-0 z-10 hidden w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="menu-<?= $user->id ?>">
                                                        <div class="py-1">
                                                            <a href="<?= Url::to(['user-update', 'id' => $user->id]) ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Редактировать
                                                            </a>
                                                            <?php if (!$user->is_blocked): ?>
                                                                <form action="<?= Url::to(['user-block', 'id' => $user->id]) ?>" method="post" style="display: inline;">
                                                                    <?= Html::csrfMetaTags() ?>
                                                                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" onclick="return confirm('Вы уверены, что хотите заблокировать этого пользователя?')">
                                                                        Заблокировать
                                                                    </button>
                                                                </form>
                                                            <?php else: ?>
                                                                <form action="<?= Url::to(['user-unblock', 'id' => $user->id]) ?>" method="post" style="display: inline;">
                                                                    <?= Html::csrfMetaTags() ?>
                                                                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                        Разблокировать
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                            <form action="<?= Url::to(['user-delete', 'id' => $user->id]) ?>" method="post" style="display: inline;">
                                                                <?= Html::csrfMetaTags() ?>
                                                                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                                                                    Удалить
                                                                </button>
                                                            </form>
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
                                пользователей
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