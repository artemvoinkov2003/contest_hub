<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Оценка заявки: ' . $application->work_name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['/application/index']];
$this->params['breadcrumbs'][] = ['label' => 'Заявка #' . $application->id, 'url' => ['/application/view', 'id' => $application->id]];
$this->params['breadcrumbs'][] = 'Оценка';

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= Html::encode($this->title) ?></h1>
                    <div class="text-gray-600">
                        <p><strong>Участник:</strong> <?= Html::encode($application->surname . ' ' . $application->name) ?></p>
                        <p><strong>Конкурс:</strong> <?= Html::encode($application->contest->name) ?></p>
                        <p><strong>Номинация:</strong> <?= Html::encode($application->nomination->name) ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $evaluation->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= $evaluation->status === 'completed' ? 'Завершена' : 'Черновик' ?>
                    </span>
                </div>
            </div>
            
            <!-- File Preview -->
            <div class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Работа участника</h3>
                <?php if ($application->fileExists()): ?>
                    <?php if ($application->isImage()): ?>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <img src="<?= $application->getFileUrl() ?>" alt="Работа" class="max-w-full h-auto max-h-96 mx-auto rounded-lg">
                        </div>
                    <?php elseif ($application->isVideo()): ?>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <video controls class="max-w-full h-auto max-h-96 mx-auto rounded-lg">
                                <source src="<?= $application->getFileUrl() ?>" type="video/<?= $application->getFileExtension() ?>">
                            </video>
                        </div>
                    <?php else: ?>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl text-blue-600">📄</span>
                            </div>
                            <p class="text-gray-600 mb-2">Формат файла: <?= strtoupper($application->getFileExtension()) ?></p>
                            <a href="<?= $application->getFileUrl() ?>" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                Открыть файл
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-gray-500 italic">Файл работы не найден</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Evaluation Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <?php $form = ActiveForm::begin([
                'id' => 'evaluation-form',
                'options' => ['class' => 'space-y-6'],
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'inputOptions' => ['class' => 'mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150'],
                    'errorOptions' => ['class' => 'text-red-500 text-sm mt-1'],
                    'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700'],
                ],
            ]); ?>

            <!-- Criteria Scores -->
            <div class="space-y-6">
                <h2 class="text-xl font-bold text-gray-900 border-b border-gray-200 pb-3">Оценка по критериям</h2>
                
                <?php foreach ($criteriaList as $criteria): ?>
                    <div class="bg-gray-50 rounded-lg p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900"><?= Html::encode($criteria->name) ?></h3>
                                <?php if ($criteria->description): ?>
                                    <p class="text-gray-600 text-sm mt-1"><?= Html::encode($criteria->description) ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-sm font-medium text-gray-700">
                                Максимум: <?= $criteria->max_score ?> баллов
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Оценка (от 0 до <?= $criteria->max_score ?>)
                            </label>
                            <input type="range" 
                                   name="criteria_scores[<?= $criteria->id ?>]" 
                                   min="0" 
                                   max="<?= $criteria->max_score ?>" 
                                   value="<?= isset($savedScores[$criteria->id]) ? $savedScores[$criteria->id] : 0 ?>"
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                   oninput="updateScoreValue(this, 'score-value-<?= $criteria->id ?>')">
                            <div class="flex justify-between mt-2">
                                <span class="text-sm text-gray-500">0</span>
                                <span class="text-sm font-medium text-gray-900">
                                    Выбрано: <span id="score-value-<?= $criteria->id ?>" class="text-indigo-600">
                                        <?= isset($savedScores[$criteria->id]) ? $savedScores[$criteria->id] : 0 ?>
                                    </span> баллов
                                </span>
                                <span class="text-sm text-gray-500"><?= $criteria->max_score ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Notes -->
            <div class="border-t border-gray-200 pt-6">
                <?= $form->field($evaluation, 'notes')->textarea([
                    'rows' => 4,
                    'placeholder' => 'Ваши комментарии и замечания по работе...'
                ]) ?>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <p><strong>Общий балл:</strong> <span id="total-score" class="text-xl font-bold text-green-600">
                        <?= $evaluation->total_score ?: '0' ?>
                    </span></p>
                </div>
                
                <div class="flex space-x-3">
                    <?= Html::a('Отмена', ['/application/view', 'id' => $application->id], [
                        'class' => 'inline-flex items-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150'
                    ]) ?>
                    
                    <?= Html::submitButton('Сохранить черновик', [
                        'name' => 'save',
                        'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150'
                    ]) ?>
                    
                    <?= Html::submitButton('Завершить оценку', [
                        'name' => 'complete',
                        'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите завершить оценку? После завершения вы не сможете изменить оценки.',
                        ]
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
function updateScoreValue(slider, elementId) {
    document.getElementById(elementId).textContent = slider.value;
    calculateTotalScore();
}

function calculateTotalScore() {
    let total = 0;
    const sliders = document.querySelectorAll('input[type="range"]');
    sliders.forEach(slider => {
        total += parseInt(slider.value);
    });
    document.getElementById('total-score').textContent = total;
}

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', function() {
    calculateTotalScore();
    const sliders = document.querySelectorAll('input[type="range"]');
    sliders.forEach(slider => {
        const criteriaId = slider.name.match(/\[(\d+)\]/)[1];
        slider.addEventListener('input', function() {
            updateScoreValue(this, 'score-value-' + criteriaId);
        });
    });
});
</script>