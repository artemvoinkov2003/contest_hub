<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Application $application */
/** @var app\models\Evaluation $evaluation */
/** @var app\models\Criteria[] $criteria */
/** @var app\models\EvaluationScore[] $scores */

$this->title = 'Оценка заявки';
$this->params['breadcrumbs'][] = ['label' => 'Заявки для оценки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Получаем статистику по заявке
$allEvaluations = \app\models\Evaluation::find()
    ->where(['application_id' => $application->id, 'status' => 'completed'])
    ->all();

$completedCount = count($allEvaluations);
$assignedExperts = \app\models\ExpertAssignment::find()
    ->where([
        'contest_id' => $application->contest_id,
        'nomination_id' => $application->nomination_id,
        'age_category_id' => $application->age_category_id
    ])
    ->count();

$allExpertsCompleted = ($assignedExperts > 0 && $completedCount >= $assignedExperts);

// Средний балл по другим экспертам (исключая текущего)
$otherExpertsScores = [];
foreach ($allEvaluations as $eval) {
    if ($eval->expert_id != Yii::$app->user->id) {
        $otherExpertsScores[] = $eval->total_score;
    }
}

$averageOtherScore = count($otherExpertsScores) > 0 
    ? round(array_sum($otherExpertsScores) / count($otherExpertsScores), 2)
    : null;

// Проверяем есть ли сгенерированные документы
$generatedDocuments = \app\models\GeneratedDocument::findByApplicationId($application->id);
$hasDocuments = !empty($generatedDocuments);
?>
<div class="expert-evaluate">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок страницы -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Оценка конкурсной работы</h1>
            <p class="mt-2 text-lg text-gray-600">Заполните оценочный лист для заявки участника</p>
        </div>

        <!-- Информация о заявке -->
        <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden border border-gray-200">
            <div class="bg-blue-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Информация о работе</h2>
                        <p class="mt-1 text-blue-100">Детали конкурсной работы и участника</p>
                    </div>
                    <div class="bg-white/20 rounded-lg px-4 py-2">
                        <span class="text-white font-semibold"><?= Html::encode($application->contest->name) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Основная информация -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Название работы</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900"><?= Html::encode($application->work_name) ?></p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Участник</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                <?= Html::encode($application->surname) ?> 
                                <?= Html::encode($application->name) ?>
                                <?= Html::encode($application->patronymic) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Дополнительная информация -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-500">Номинация</h3>
                                <p class="mt-1 font-semibold text-gray-900"><?= Html::encode($application->nomination->name) ?></p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-500">Возрастная категория</h3>
                                <p class="mt-1 font-semibold text-gray-900"><?= Html::encode($application->ageCategory->name) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Файл работы -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Файл работы</h3>
                    <?php if ($application->file_path): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="text-2xl">📎</div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">Работа участника</p>
                                    <p class="text-sm text-gray-500"><?= basename($application->file_path) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <?= Html::a('Просмотреть', ['/application/view-file', 'id' => $application->id], [
                                    'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50',
                                    'target' => '_blank'
                                ]) ?>
                                <?= Html::a('Скачать', ['/application/download', 'id' => $application->id], [
                                    'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700',
                                    'download' => true
                                ]) ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <div class="text-4xl text-gray-400 mb-4">📄</div>
                            <p class="text-lg font-medium text-gray-900">Файл не загружен</p>
                            <p class="mt-2 text-gray-500">Участник не прикрепил файл работы</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Итоговая статистика -->
        <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden border border-gray-200">
            <div class="bg-green-600 px-6 py-5">
                <h2 class="text-2xl font-bold text-white">Итоговая статистика</h2>
                <p class="mt-1 text-green-100">Статус оценки заявки другими экспертами</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500">Завершено оценок</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900"><?= $completedCount ?>/<?= $assignedExperts ?></p>
                        <p class="mt-1 text-sm text-gray-500">из назначенных экспертов</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500">Средний балл других экспертов</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            <?= $averageOtherScore !== null ? $averageOtherScore : '—' ?>
                        </p>
                        <p class="mt-1 text-sm text-gray-500">из 10 баллов</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500">Статус заявки</h3>
                        <p class="mt-2">
                            <?php if ($allExpertsCompleted): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Все эксперты завершили
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Оценка в процессе
                                </span>
                            <?php endif; ?>
                        </p>
                        <p class="mt-1 text-sm text-gray-500">
                            <?= $allExpertsCompleted ? 'Заявка переведена в статус "Оценена"' : 'Ожидаются оценки других экспертов' ?>
                        </p>
                    </div>
                </div>
                
                <!-- Сгенерированные документы -->
                <?php if ($hasDocuments): ?>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Сгенерированные документы</h3>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-2xl">🏆</div>
                                <div>
                                    <p class="font-medium text-gray-900">Для этой заявки уже сгенерированы документы</p>
                                    <p class="text-sm text-gray-600">Дипломы и сертификаты доступны для скачивания</p>
                                </div>
                            </div>
                            <?= Html::a('Просмотреть документы', ['documents', 'application_id' => $application->id], [
                                'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700'
                            ]) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Форма оценки -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-600 to-green-600 px-6 py-5">
                <h2 class="text-2xl font-bold text-white">Оценочный лист</h2>
                <p class="mt-1 text-blue-100">Выставьте баллы по каждому критерию оценки</p>
            </div>

            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'p-6 space-y-6'],
            ]); ?>

            <!-- Критерии оценки -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Критерии оценки</h3>
                </div>
                
                <div class="space-y-6">
                    <?php foreach ($criteria as $index => $criterion): ?>
                    <?php $score = $scores[$index]; ?>
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2"><?= Html::encode($criterion->name) ?></h4>
                                <p class="text-sm text-gray-600">Максимальный балл: <span class="font-semibold"><?= $criterion->max_score ?></span></p>
                            </div>
                            <div class="mt-4 lg:mt-0 lg:ml-6">
                                <div class="text-2xl font-bold text-blue-600">
                                    <span id="score_display_<?= $criterion->id ?>"><?= $score->score ?></span>/<?= $criterion->max_score ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>0 баллов</span>
                                <span><?= $criterion->max_score ?> баллов</span>
                            </div>
                            <input 
                                type="range" 
                                name="score_<?= $criterion->id ?>" 
                                value="<?= $score->score ?>" 
                                min="0" 
                                max="<?= $criterion->max_score ?>" 
                                step="1"
                                class="w-full h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer slider"
                                data-criteria="<?= $criterion->id ?>"
                                oninput="updateScoreDisplay(<?= $criterion->id ?>, this.value)"
                            >
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Общий балл -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-white">Общий балл</h4>
                            <p class="text-indigo-100">Сумма всех оценок по критериям</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <div class="text-4xl font-bold text-white">
                                <span id="total_score_display"><?= $evaluation->total_score ?></span>
                                <span class="text-2xl text-indigo-100">/<?= array_sum(array_map(function($c) { return $c->max_score; }, $criteria)) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Примечания -->
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-900">Примечания и рекомендации</h3>
                <textarea 
                    name="notes" 
                    rows="6" 
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none py-3 px-4 border"
                    placeholder="Введите ваши комментарии, замечания и рекомендации для участника..."
                ><?= Html::encode($evaluation->notes) ?></textarea>
                <p class="text-sm text-gray-500">Эти комментарии будут видны участнику после завершения оценки</p>
            </div>

            <!-- Кнопки действий -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2 text-sm <?= $evaluation->status === \app\models\Evaluation::STATUS_COMPLETED ? 'text-green-600' : 'text-blue-600' ?>">
                            <div class="bg-<?= $evaluation->status === \app\models\Evaluation::STATUS_COMPLETED ? 'green' : 'blue' ?>-100 rounded-full p-2">
                                <?= $evaluation->status === \app\models\Evaluation::STATUS_COMPLETED ? '✓' : '✎' ?>
                            </div>
                            <span class="font-medium">Статус: <?= $evaluation->getStatusLabel() ?></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <?= Html::a('Вернуться к списку', ['index'], [
                            'class' => 'inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50'
                        ]) ?>
                        
                        <button 
                            type="submit" 
                            name="save" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700'
                        >
                            Сохранить черновик
                        </button>
                        
                        <button 
                            type="submit" 
                            name="complete" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700'
                            onclick="return confirm('Вы уверены, что хотите завершить оценку? После завершения изменить оценку будет невозможно.')"
                        >
                            Завершить оценку
                        </button>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<script>
function updateScoreDisplay(criteriaId, value) {
    // Обновляем отображение балла для конкретного критерия
    document.getElementById('score_display_' + criteriaId).textContent = value;
    
    // Пересчитываем общий балл
    let totalScore = 0;
    document.querySelectorAll('input[type="range"].slider').forEach(slider => {
        totalScore += parseInt(slider.value);
    });
    
    document.getElementById('total_score_display').textContent = totalScore;
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="range"].slider').forEach(slider => {
        const criteriaId = slider.getAttribute('data-criteria');
        updateScoreDisplay(criteriaId, slider.value);
    });
});
</script>
