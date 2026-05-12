<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Управление номинациями';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = $dataProvider ?? null;
?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white">Номинации</h1>
                    <p class="mt-1 text-sm text-blue-100">Управление номинациями для конкурсов</p>
                </div>
             <a href="<?= Url::to(['nomination-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                        Создать номинацию
                    </a>
            </div>
        </div>
    </div>

    <!-- Nominations Content -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php if (!$dataProvider || $dataProvider->getCount() === 0): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Номинации не найдены</h3>
                    <p class="text-gray-500 mb-6">Начните с создания новой номинации</p>
                    <a href="<?= Url::to(['nomination-create']) ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transition-all duration-200">
                        Создать номинацию
                    </a>
                </div>
            <?php else: ?>
                <!-- Mobile View -->
                <div class="block md:hidden">
                    <div class="space-y-4 p-4">
                        <?php foreach ($dataProvider->getModels() as $nomination): ?>
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md border border-blue-100 p-4">
                                <div class="space-y-4">
                                    <!-- Заголовок -->
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900"><?= Html::encode($nomination->name) ?></h3>
                                        <?php if ($nomination->description): ?>
                                            <p class="text-sm text-gray-600 mt-1"><?= Html::encode(mb_substr($nomination->description, 0, 100)) ?>...</p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Информация -->
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <span class="text-sm font-medium">Конкурс: <?= Html::encode($nomination->contest->name ?? '') ?></span>
                                        </div>

                                        <?php $criteriaCount = \app\models\Criteria::find()->where(['nomination_id' => $nomination->id])->count(); ?>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            <span class="text-sm font-medium">Критерии: <?= $criteriaCount ?></span>
                                        </div>

                                        <?php
                                        $currentCount = \app\models\Application::find()
                                            ->where(['nomination_id' => $nomination->id])
                                            ->andWhere(['status' => ['accepted', 'completed']])
                                            ->count();
                                        
                                        $pendingCount = \app\models\Application::find()
                                            ->where(['nomination_id' => $nomination->id])
                                            ->andWhere(['status' => 'new'])
                                            ->count();
                                            
                                        $maxParticipants = $nomination->max_participants;
                                        
                                        if ($maxParticipants > 0) {
                                            $percentage = $currentCount > 0 ? round(($currentCount / $maxParticipants) * 100, 1) : 0;
                                            $statusClass = $currentCount >= $maxParticipants ? 'text-red-600' : 
                                                          ($percentage > 80 ? 'text-yellow-600' : 'text-green-600');
                                        }
                                        ?>
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-teal-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm font-medium">Участники:</span>
                                                        <span class="font-bold <?= $statusClass ?? '' ?>">
                                                            <?= $currentCount ?>
                                                            <?php if ($pendingCount > 0): ?>
                                                                <span class="text-yellow-600">(+<?= $pendingCount ?>)</span>
                                                            <?php endif; ?>
                                                            <?php if ($maxParticipants > 0): ?>
                                                                /<?= $maxParticipants ?>
                                                            <?php else: ?>
                                                                /∞
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <?php if ($maxParticipants > 0): ?>
                                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                                            <div class="h-2 rounded-full <?= $currentCount >= $maxParticipants ? 'bg-red-600' : ($percentage > 80 ? 'bg-yellow-500' : 'bg-green-600') ?>" 
                                                                 style="width: <?= min($percentage, 100) ?>%"></div>
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            <?= $percentage ?>% заполнено
                                                            <?php if ($currentCount < $maxParticipants): ?>
                                                                • Свободно: <?= $maxParticipants - $currentCount ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Действия -->
                                    <div class="pt-4 border-t border-blue-100">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="<?= Url::to(['nomination-update', 'id' => $nomination->id]) ?>" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Редактировать
                                            </a>
                                            <a href="<?= Url::to(['/admin/criteria-index', 'nomination_id' => $nomination->id]) ?>" 
                                               class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                Критерии
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Desktop View -->
                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Название
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            Конкурс
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Критерии
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Участники
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-blue-200">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($dataProvider->getModels() as $nomination): ?>
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900"><?= Html::encode($nomination->name) ?></div>
                                            <?php if ($nomination->description): ?>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <?= Html::encode(mb_substr($nomination->description, 0, 60)) ?>...
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= Html::encode($nomination->contest->name ?? '') ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $criteriaCount = \app\models\Criteria::find()->where(['nomination_id' => $nomination->id])->count();
                                            ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <?= $criteriaCount ?> критериев
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $currentCount = \app\models\Application::find()
                                                ->where(['nomination_id' => $nomination->id])
                                                ->andWhere(['status' => ['accepted', 'completed']])
                                                ->count();
                                            
                                            $pendingCount = \app\models\Application::find()
                                                ->where(['nomination_id' => $nomination->id])
                                                ->andWhere(['status' => 'new'])
                                                ->count();
                                                
                                            $maxParticipants = $nomination->max_participants;
                                            
                                            if ($maxParticipants > 0) {
                                                $percentage = $currentCount > 0 ? round(($currentCount / $maxParticipants) * 100, 1) : 0;
                                                $progressColor = $currentCount >= $maxParticipants ? 'bg-red-600' : 
                                                               ($percentage > 80 ? 'bg-yellow-500' : 'bg-green-600');
                                            }
                                            ?>
                                            <div class="space-y-2">
                                                <!-- Основная статистика -->
                                                <div class="flex items-center justify-between">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?= $currentCount ?>
                                                        <?php if ($pendingCount > 0): ?>
                                                            <span class="text-yellow-600" title="Ожидают рассмотрения">(+<?= $pendingCount ?>)</span>
                                                        <?php endif; ?>
                                                        <?php if ($maxParticipants > 0): ?>
                                                            <span class="text-gray-500">/<?= $maxParticipants ?></span>
                                                        <?php else: ?>
                                                            <span class="text-gray-400">/∞</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if ($maxParticipants > 0): ?>
                                                        <span class="text-xs font-medium <?= $currentCount >= $maxParticipants ? 'text-red-600' : 'text-gray-500' ?>">
                                                            <?= $percentage ?>%
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Прогресс-бар -->
                                                <?php if ($maxParticipants > 0): ?>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="h-2 rounded-full <?= $progressColor ?>" 
                                                             style="width: <?= min($percentage, 100) ?>%"></div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Статус -->
                                                <div class="flex items-center justify-between">
                                                    <div class="text-xs">
                                                        <?php if ($maxParticipants > 0): ?>
                                                            <?php if ($currentCount >= $maxParticipants): ?>
                                                                <span class="font-bold text-red-600 flex items-center">
                                                                    Лимит достигнут
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-gray-600">
                                                                    Свободно: <span class="font-medium"><?= $maxParticipants - $currentCount ?></span>
                                                                </span>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="text-blue-600">Без ограничений</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <?php if ($pendingCount > 0): ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800"
                                                              title="Заявок на рассмотрении">
                                                            <?= $pendingCount ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end">
                                                <div class="relative inline-block text-left">
                                                    <div>
                                                        <button type="button" 
                                                                onclick="document.getElementById('menu-<?= $nomination->id ?>').classList.toggle('hidden')"
                                                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Действия
                                                            <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="absolute right-0 z-10 hidden w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="menu-<?= $nomination->id ?>">
                                                        <div class="py-1">
                                                            <a href="<?= Url::to(['nomination-update', 'id' => $nomination->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Редактировать
                                                            </a>
                                                            <a href="<?= Url::to(['/admin/criteria-index', 'nomination_id' => $nomination->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Критерии оценки
                                                            </a>
                                                            <a href="<?= Url::to(['applications', 'NominationSearch[nomination_id]' => $nomination->id]) ?>" 
                                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                                Просмотр заявок
                                                            </a>
                                                            <?= Html::a('Удалить', ['nomination-delete', 'id' => $nomination->id], [
                                                                'class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                                                                'data' => [
                                                                    'confirm' => 'Вы уверены, что хотите удалить эту номинацию?',
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
                
                <!-- Pagination -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 flex items-center justify-between border-t border-blue-200">
                    <div class="flex-1 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-700">
                                Показано
                                <span class="font-bold text-blue-600"><?= $dataProvider->getCount() ?></span>
                                из
                                <span class="font-bold text-blue-600"><?= $dataProvider->getTotalCount() ?></span>
                                номинаций
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

<!-- JavaScript для закрытия меню при клике вне его -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Закрытие меню при клике вне его
    document.addEventListener('click', function(e) {
        if (!e.target.closest('button[onclick*="menu-"]') && !e.target.closest('div[id^="menu-"]')) {
            document.querySelectorAll('div[id^="menu-"]').forEach(function(menu) {
                menu.classList.add('hidden');
            });
        }
    });
});
</script>