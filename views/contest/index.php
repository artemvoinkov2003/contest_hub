<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Конкурсы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contest-index min-h-screen bg-gray-50 py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Текущие конкурсы
            </h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Примите участие в наших творческих конкурсах и раскройте свой талант
            </p>
        </div>

        <!-- Competitions Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($dataProvider->getModels() as $contest): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                <!-- Image Section -->
                <div class="relative h-48 overflow-hidden bg-gradient-to-r from-blue-500 to-indigo-600">
                    <?php if ($contest->image): ?>
                        <img class="w-full h-full object-cover" 
                             src="<?= Html::encode($contest->image) ?>" 
                             alt="<?= Html::encode($contest->name) ?>">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center text-white p-4">
                                <span class="text-xl font-semibold"><?= Html::encode($contest->name) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            Активный
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6">
                    <!-- Date -->
                    <div class="text-sm text-blue-600 font-medium mb-3">
                        <?= Yii::$app->formatter->asDate($contest->start_date) ?> - <?= Yii::$app->formatter->asDate($contest->end_date) ?>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        <?= Html::encode($contest->name) ?>
                    </h3>

                    <!-- Description -->
                    <p class="text-gray-600 leading-relaxed mb-4 line-clamp-3">
                        <?= Html::encode(mb_strimwidth($contest->description, 0, 120, '...')) ?>
                    </p>

                    <!-- Action Button -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <?= Html::a('Участвовать', ['view', 'id' => $contest->id], [
                            'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition'
                        ]) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <?php if ($dataProvider->getCount() == 0): ?>
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-lg flex items-center justify-center">
                        <span class="text-3xl">🏆</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Нет активных конкурсов</h3>
                    <p class="text-gray-600 mb-6">В данный момент нет активных конкурсов. Следите за обновлениями!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>