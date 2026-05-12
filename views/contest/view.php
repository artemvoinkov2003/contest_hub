<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Contest $model */
/** @var app\models\AgeCategory[] $ageCategories */
/** @var app\models\Nomination[] $nominations */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Конкурсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contest-view min-h-screen bg-gray-50">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white mb-4">
                            Активный конкурс
                        </span>
                        <h1 class="text-3xl font-bold mb-2"><?= Html::encode($model->name) ?></h1>
                        <p class="text-blue-100"><?= Html::encode($model->description) ?></p>
                    </div>
                    <?= Html::a('Подать заявку', ['apply', 'id' => $model->id], [
                        'class' => 'inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-blue-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Timeline Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Сроки проведения</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 rounded-lg p-5 border border-blue-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Начало</h3>
                            <p class="text-xl font-bold text-blue-600"><?= Yii::$app->formatter->asDate($model->start_date) ?></p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-5 border border-purple-100">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Окончание</h3>
                            <p class="text-xl font-bold text-purple-600"><?= Yii::$app->formatter->asDate($model->end_date) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Статистика заполненности номинаций</h2>
                    <div class="space-y-4">
                        <?php foreach ($nominationStats as $stat): ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-900"><?= Html::encode($stat['nomination']->name) ?></span>
                                <span class="text-sm font-medium <?= $stat['percentage'] >= 100 ? 'text-red-600' : ($stat['percentage'] >= 80 ? 'text-yellow-600' : 'text-green-600') ?>">
                                    <?= $stat['total'] ?>/<?= $stat['max_participants'] ?: '∞' ?>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full <?= $stat['percentage'] >= 100 ? 'bg-red-600' : ($stat['percentage'] >= 80 ? 'bg-yellow-500' : 'bg-green-500') ?>" 
                                     style="width: <?= min($stat['percentage'], 100) ?>%"></div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                Заполнено на <?= $stat['percentage'] ?>%
                                <?php if ($stat['percentage'] >= 100): ?>
                                    <span class="ml-2 text-red-600 font-medium">Лимит достигнут</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">Документы конкурса</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if ($programTemplate = \app\models\ReportTemplate::findByTypeAndContest('program', $model->id)): ?>
                            <?= Html::a('Скачать программу конкурса', ['download-program', 'id' => $model->id], [
                                'class' => 'inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700'
                            ]) ?>
                        <?php endif; ?>
                        
                        <?php if ($evaluationTemplate = \app\models\ReportTemplate::findByTypeAndContest('evaluation_sheet', $model->id)): ?>
                            <?= Html::a('Скачать оценочный лист', ['download-evaluation-sheet', 'id' => $model->id], [
                                'class' => 'inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700'
                            ]) ?>
                        <?php endif; ?>
                        
                        <?php if (strtotime($model->end_date) < time()): ?>
                            <?= Html::a('Итоговые результаты', ['results', 'id' => $model->id], [
                                'class' => 'inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700'
                            ]) ?>
                        <?php endif; ?>
                        
                        <?php if ($documents = \app\models\GeneratedDocument::find()->where(['application.contest_id' => $model->id])->joinWith('application')->exists()): ?>
                            <?= Html::a('Сгенерированные документы', ['documents', 'id' => $model->id], [
                                'class' => 'inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700'
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-8">
                <!-- Age Categories -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Возрастные категории</h3>
                    <div class="space-y-3">
                        <?php foreach ($ageCategories as $category): ?>
                            <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <span class="text-gray-700 font-medium"><?= Html::encode($category->name) ?></span>
                                <?php if ($category->min_age && $category->max_age): ?>
                                    <span class="text-sm text-gray-500 ml-2">(<?= $category->min_age ?>-<?= $category->max_age ?> лет)</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Nominations -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">Номинации</h3>
                    <div class="space-y-3">
                        <?php foreach ($nominations as $nomination): ?>
                            <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <span class="text-gray-700 font-medium"><?= Html::encode($nomination->name) ?></span>
                                <?php if ($nomination->max_participants): ?>
                                    <span class="text-sm text-gray-500 ml-2">(макс. <?= $nomination->max_participants ?> участников)</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>