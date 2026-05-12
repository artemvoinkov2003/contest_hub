<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;

// Получаем параметры фильтра
$typeFilter = Yii::$app->request->get('type', 'all');
$statusFilter = Yii::$app->request->get('status', 'all');

// Статистика
$totalCount = \app\models\Notification::find()->where(['user_id' => Yii::$app->user->id])->count();
$unreadCount = \app\models\Notification::getUnreadCount(Yii::$app->user->id);
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок и статистика -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Уведомления</h1>
                    <p class="text-lg text-gray-600">Все важные уведомления и обновления по вашим заявкам</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-4 text-center border border-gray-200">
                        <div class="text-2xl font-bold text-gray-900"><?= $totalCount ?></div>
                        <div class="text-sm text-gray-600">Всего</div>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600"><?= $unreadCount ?></div>
                        <div class="text-sm text-blue-600">Новых</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Фильтры и массовые действия -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 mb-6 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Фильтры -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Фильтры</h3>
                        <?php $form = ActiveForm::begin([
                            'method' => 'get',
                            'action' => ['index'],
                            'options' => ['class' => 'flex flex-wrap gap-4 items-end']
                        ]); ?>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Тип уведомления</label>
                                <select name="type" 
                                        class="block w-48 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="all" <?= $typeFilter === 'all' ? 'selected' : '' ?>>Все типы</option>
                                    <?php foreach (\app\models\Notification::getTypeOptions() as $value => $label): ?>
                                        <option value="<?= $value ?>" <?= $typeFilter === $value ? 'selected' : '' ?>>
                                            <?= Html::encode($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                                <select name="status" 
                                        class="block w-48 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>Все статусы</option>
                                    <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>Новые</option>
                                    <option value="read" <?= $statusFilter === 'read' ? 'selected' : '' ?>>Прочитанные</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end space-x-2">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                                    Применить
                                </button>
                                <a href="<?= \yii\helpers\Url::to(['index']) ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Сбросить
                                </a>
                            </div>
                        </div>
                        
                        <?php ActiveForm::end(); ?>
                    </div>
                    
                    <!-- Массовые действия -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Массовые действия</h3>
                        <div class="flex flex-wrap gap-2">
                            <?= Html::a('Отметить все как прочитанные', ['read-all'], [
                                'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700',
                                'data-method' => 'post',
                                'data-confirm' => 'Отметить все уведомления как прочитанные?'
                            ]) ?>
                            
                            <?= Html::a('Удалить прочитанные', ['delete-read'], [
                                'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700',
                                'data-method' => 'post',
                                'data-confirm' => 'Удалить все прочитанные уведомления? Это действие нельзя отменить.'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Список уведомлений -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
            <?php if ($dataProvider->getTotalCount() > 0): ?>
                <div class="divide-y divide-gray-200">
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_notification_item',
                        'layout' => "{items}",
                        'itemOptions' => ['class' => 'p-6 hover:bg-gray-50 transition-colors duration-150'],
                        'emptyText' => '<div class="text-center py-16">
                            <div class="text-4xl text-gray-300 mb-4">📭</div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Уведомлений не найдено</h3>
                            <p class="text-gray-600">Попробуйте изменить параметры фильтрации</p>
                        </div>',
                    ]) ?>
                </div>
                
                <!-- Пагинация -->
                <?php if ($dataProvider->pagination->pageCount > 1): ?>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Показано <span class="font-medium"><?= $dataProvider->getCount() ?></span> из <span class="font-medium"><?= $dataProvider->getTotalCount() ?></span>
                        </div>
                        <div class="flex space-x-2">
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'flex space-x-2'],
                                'linkOptions' => ['class' => 'px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-700 bg-white hover:bg-gray-50'],
                                'activePageCssClass' => 'bg-blue-600 text-white border-blue-600',
                                'disabledPageCssClass' => 'opacity-50 cursor-not-allowed hover:bg-white',
                            ]) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
            <?php else: ?>
                <!-- Пустой список -->
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-full mb-6">
                        <div class="text-3xl">📭</div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Уведомлений пока нет</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        Здесь будут появляться уведомления о смене статуса заявок, генерации дипломов и других важных событиях
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <?= Html::a('Подать заявку', ['/application/create'], [
                            'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700'
                        ]) ?>
                        <?= Html::a('Мои заявки', ['/application/index'], [
                            'class' => 'inline-flex items-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50'
                        ]) ?>
                        <?= Html::a('На главную', ['/site/index'], [
                            'class' => 'inline-flex items-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50'
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
