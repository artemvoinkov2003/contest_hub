<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Evaluation;

$this->title = 'Заявка: ' . $model->work_name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['applications']];
$this->params['breadcrumbs'][] = $this->title;

// Получаем все оценки для этой заявки
$evaluations = Evaluation::find()
    ->where(['application_id' => $model->id])
    ->with('expert')
    ->all();

$result = \app\models\ContestResult::findByApplicationId($model->id);
$documents = \app\models\GeneratedDocument::find()
    ->where(['application_id' => $model->id])
    ->all();

// Рассчитываем среднее арифметическое
$totalScore = 0;
$evaluationCount = count($evaluations);
foreach ($evaluations as $evaluation) {
    $totalScore += $evaluation->total_score;
}
$averageScore = $evaluationCount > 0 ? $totalScore / $evaluationCount : 0;

// Функция для получения класса статуса
function getStatusCssClass($status) {
    $classes = [
        'new' => 'bg-yellow-100 text-yellow-800',
        'accepted' => 'bg-green-100 text-green-800',
        'blocked' => 'bg-red-100 text-red-800',
        'graded' => 'bg-blue-100 text-blue-800',
        'completed' => 'bg-purple-100 text-purple-800',
    ];
    return $classes[$status] ?? 'bg-gray-100 text-gray-800';
}
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Просмотр заявки</h1>
                    <p class="mt-1 text-sm text-gray-500"><?= Html::encode($model->work_name) ?></p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?= Url::to(['applications']) ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Назад
                    </a>
                    <a href="<?= Url::to(['application-update', 'id' => $model->id]) ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Редактировать
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Основная информация -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Информация о заявке -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Информация о заявке</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Основные данные и детали</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= getStatusCssClass($model->status) ?>">
                            <?= $model->getStatusLabel() ?>
                        </span>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Название работы</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= Html::encode($model->work_name) ?></dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Конкурс</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= Html::encode($model->contest->name ?? '') ?></dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Номинация</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <?= Html::encode($model->nomination->name ?? '') ?>
                                    </span>
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Возрастная категория</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= Html::encode($model->ageCategory->name ?? '') ?></dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">ФИО участника</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <?= Html::encode($model->surname) ?> <?= Html::encode($model->name) ?> <?= Html::encode($model->patronymic) ?>
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Учреждение</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= Html::encode($model->institution) ?></dd>
                            </div>
                            <?php if ($model->leader): ?>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Руководитель</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= Html::encode($model->leader) ?></dd>
                            </div>
                            <?php endif; ?>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <?= Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i') ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Файл работы -->
                <?php if ($model->file_path): ?>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Файл работы</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                    <?= $model->getFileTypeIcon() ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= basename($model->file_path) ?></div>
                                    <div class="text-sm text-gray-500"><?= $model->getFileExtension() ?> • <?= $model->getFileSize() ?></div>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="<?= Url::to(['view-file', 'id' => $model->id]) ?>" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Просмотреть
                                </a>
                                <a href="<?= Url::to(['download', 'id' => $model->id]) ?>" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Скачать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Оценки экспертов -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Оценки экспертов</h3>
                        <p class="mt-1 text-sm text-gray-500">Индивидуальные оценки и средний балл</p>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                        <?php if ($evaluations): ?>
                            <div class="space-y-6">
                                <!-- Индивидуальные оценки -->
                                <div>
                                    <h4 class="text-md font-medium text-gray-900 mb-4">Индивидуальные оценки:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-<?= $evaluationCount >= 2 ? '2' : '1' ?> gap-4">
                                        <?php foreach ($evaluations as $index => $evaluation): ?>
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-700">Эксперт <?= $index + 1 ?></div>
                                                        <div class="text-sm text-gray-500">
                                                            <?= Html::encode($evaluation->expert->login ?? 'Неизвестный эксперт') ?>
                                                        </div>
                                                    </div>
                                                    <div class="text-lg font-bold text-blue-600">
                                                        <?= number_format($evaluation->total_score, 2) ?>
                                                    </div>
                                                </div>
                                                <?php if ($evaluation->notes): ?>
                                                    <div class="mt-2 text-sm text-gray-600 border-t border-gray-200 pt-2">
                                                        <span class="font-medium">Примечание:</span>
                                                        <?= Html::encode($evaluation->notes) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Средний балл -->
                                <div class="border-t border-gray-200 pt-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-4">Средний балл:</h4>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm text-gray-500">На основе <?= $evaluationCount ?> оценк<?= $evaluationCount == 1 ? 'и' : 'ок' ?></div>
                                            <div class="text-3xl font-bold text-gray-900 mt-1">
                                                <?= number_format($averageScore, 2) ?>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php if ($evaluationCount > 1): ?>
                                                (<?= implode(' + ', array_map(function($e) { 
                                                    return number_format($e->total_score, 2); 
                                                }, $evaluations)) ?>) / <?= $evaluationCount ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Итоговые результаты -->
                                <?php if ($result): ?>
                                <div class="border-t border-gray-200 pt-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-4">Итоговые результаты конкурса:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Итоговый балл</label>
                                            <div class="mt-1 text-2xl font-semibold text-gray-900">
                                                <?= $result->final_score ?? '—' ?>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Место</label>
                                            <div class="mt-1 text-2xl font-semibold text-gray-900">
                                                <?php if ($result->place): ?>
                                                    <span class="<?= $result->place == 1 ? 'text-yellow-600' : ($result->place == 2 ? 'text-gray-600' : ($result->place == 3 ? 'text-orange-600' : 'text-blue-600')) ?>">
                                                        <?= $result->place ?>
                                                    </span>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Награда</label>
                                            <div class="mt-1 text-lg font-medium text-gray-900">
                                                <?php if ($result->award_type): ?>
                                                    <?= [
                                                        'first' => 'Диплом I степени',
                                                        'second' => 'Диплом II степени',
                                                        'third' => 'Диплом III степени',
                                                        'laureate' => 'Диплом лауреата',
                                                        'diploma' => 'Диплом',
                                                        'certificate' => 'Сертификат',
                                                    ][$result->award_type] ?? $result->award_type ?>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">Оценки еще не выставлялись</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Боковая панель -->
            <div class="space-y-6">
                <!-- Действия -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Действия</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-6 space-y-4">
                        <?= Html::beginForm(['application-update-status', 'id' => $model->id], 'post') ?>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Статус заявки</label>
                            <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="new" <?= $model->status === 'new' ? 'selected' : '' ?>>Новая</option>
                                <option value="accepted" <?= $model->status === 'accepted' ? 'selected' : '' ?>>Принята</option>
                                <option value="blocked" <?= $model->status === 'blocked' ? 'selected' : '' ?>>Заблокирована</option>
                                <option value="graded" <?= $model->status === 'graded' ? 'selected' : '' ?>>Оценена</option>
                                <option value="completed" <?= $model->status === 'completed' ? 'selected' : '' ?>>Завершена</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Обновить статус
                        </button>
                        <?= Html::endForm() ?>
                        
                        <div class="pt-4 space-y-2">
                            <?php if ($model->status !== 'blocked'): ?>
                                <?= Html::a('Заблокировать', ['application-block', 'id' => $model->id], [
                                    'class' => 'w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
                                    'data' => ['confirm' => 'Вы уверены?', 'method' => 'post']
                                ]) ?>
                            <?php else: ?>
                                <?= Html::a('Разблокировать', ['application-unblock', 'id' => $model->id], [
                                    'class' => 'w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
                                    'data' => ['confirm' => 'Вы уверены?', 'method' => 'post']
                                ]) ?>
                            <?php endif; ?>
                            
                            <?= Html::a('Удалить', ['application-delete', 'id' => $model->id], [
                                'class' => 'w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
                                'data' => ['confirm' => 'Вы уверены?', 'method' => 'post']
                            ]) ?>
                        </div>
                    </div>
                </div>

                <!-- Генерация документов -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Генерация документов</h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-6 space-y-4">
                        <?php if ($evaluationCount > 0): ?>
                            <?= Html::a('Сгенерировать диплом', ['generate-diploma', 'application_id' => $model->id], [
                                'class' => 'w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                            ]) ?>
                            
                            <?= Html::a('Сгенерировать сертификат', ['generate-certificate', 'application_id' => $model->id], [
                                'class' => 'w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500'
                            ]) ?>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 text-center">Для генерации документов нужны оценки экспертов</p>
                        <?php endif; ?>
                        
                        <?= Html::a('Сгенерировать альбом', ['generate-album', 'application_id' => $model->id], [
                            'class' => 'w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500'
                        ]) ?>
                    </div>
                </div>

                <!-- Сгенерированные документы -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Документы</h3>
                        <p class="mt-1 text-sm text-gray-500">Сгенерированные файлы</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <?php if ($documents): ?>
                            <ul class="divide-y divide-gray-200">
                                <?php foreach ($documents as $doc): ?>
                                <li class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                <?php if ($doc->document_type == 'diploma'): ?>
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= $doc->document_type == 'diploma' ? 'Диплом' : ($doc->document_type == 'certificate' ? 'Сертификат' : 'Альбом') ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?= Yii::$app->formatter->asDate($doc->generated_at, 'php:d.m.Y H:i') ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="<?= Url::to(['generated-document-download', 'id' => $doc->id]) ?>" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="px-4 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Нет документов</h3>
                                <p class="mt-1 text-sm text-gray-500">Сгенерируйте документы для этой заявки</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>