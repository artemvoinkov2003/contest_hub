<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Создание конкурса' : 'Редактирование конкурса';
$this->params['breadcrumbs'][] = ['label' => 'Конкурсы', 'url' => ['contests']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= $this->title ?></h1>
                    <p class="mt-1 text-sm text-blue-100"><?= $model->isNewRecord ? 'Создание нового конкурса' : 'Редактирование существующего конкурса' ?></p>
                </div>
                <a href="<?= Url::to(['contests']) ?>" class="inline-flex items-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-xl text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                    Назад к списку
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'space-y-8 divide-y divide-gray-200']
            ]); ?>

            <div class="space-y-8 divide-y divide-gray-200">
                <!-- Основная информация -->
                <div class="pt-8">
                    <div class="px-8">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Основная информация</h3>
                        <p class="mt-1 text-sm text-gray-500">Основные данные конкурса</p>
                    </div>

                    <div class="mt-6 px-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'name', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Название конкурса'
                            ])->label('Название конкурса', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'description', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textarea([
                                'rows' => 4,
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Описание конкурса'
                            ])->label('Описание', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-3">
                            <?= $form->field($model, 'start_date', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'type' => 'date',
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200',
                            ])->label('Дата начала', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-3">
                            <?= $form->field($model, 'end_date', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'type' => 'date',
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200',
                            ])->label('Дата окончания', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-3">
                            <?= $form->field($model, 'status', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->dropDownList([
                                1 => 'Активен',
                                0 => 'Неактивен'
                            ], [
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 transition-all duration-200'
                            ])->label('Статус', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="pt-8 pb-6">
                <div class="flex justify-end px-8 space-x-4">
                    <a href="<?= Url::to(['contests']) ?>" class="bg-white py-3 px-6 border-2 border-blue-200 rounded-xl shadow-sm text-sm font-bold text-blue-600 hover:bg-blue-50 hover:border-blue-300 transform hover:scale-105 transition-all duration-200">
                        Отмена
                    </a>
                    <?= Html::submitButton($model->isNewRecord ? 'Создать конкурс' : 'Сохранить изменения', [
                        'class' => 'inline-flex justify-center py-3 px-8 border border-transparent shadow-lg text-sm font-bold rounded-xl text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <!-- Подсказки -->
        <?php if ($model->isNewRecord): ?>
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <h4 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Советы по созданию конкурса
            </h4>
            <ul class="text-sm text-gray-600 space-y-2">
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    После создания конкурса добавьте номинации и возрастные категории
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Убедитесь, что даты проведения конкурса корректны
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    После завершения конкурса можно скачать программу и оценочные листы
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Статистика конкурса -->
        <?php if (!$model->isNewRecord): ?>
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Номинации -->
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Номинации
                    </h4>
                    <a href="<?= Url::to(['nominations']) ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg text-white bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 transform hover:scale-105 transition-all duration-200">
                        Управление
                    </a>
                </div>
                <?php
                $nominations = \app\models\Nomination::find()->where(['contest_id' => $model->id])->all();
                if ($nominations): ?>
                    <div class="space-y-2">
                        <?php foreach ($nominations as $nomination): ?>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-purple-100">
                                <span class="text-sm font-medium text-gray-900"><?= Html::encode($nomination->name) ?></span>
                                <span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                    <?= \app\models\Application::find()->where(['nomination_id' => $nomination->id])->count() ?> заявок
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-purple-600 bg-purple-50 p-3 rounded-lg border border-purple-200">
                        Номинации еще не добавлены к этому конкурсу.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Возрастные категории -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Возрастные категории
                    </h4>
                    <a href="<?= Url::to(['age-categories']) ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 transform hover:scale-105 transition-all duration-200">
                        Управление
                    </a>
                </div>
                <?php
                $ageCategories = \app\models\AgeCategory::find()->where(['contest_id' => $model->id])->all();
                if ($ageCategories): ?>
                    <div class="space-y-2">
                        <?php foreach ($ageCategories as $category): ?>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-100">
                                <span class="text-sm font-medium text-gray-900"><?= Html::encode($category->name) ?></span>
                                <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    <?= \app\models\Application::find()->where(['age_category_id' => $category->id])->count() ?> заявок
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                        Возрастные категории еще не добавлены к этому конкурсу.
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
// JavaScript для улучшения работы с датами
$js = <<<JS
$(document).ready(function() {
    // Добавляем минимальную дату для окончания конкурса
    $('#contest-start_date').change(function() {
        var startDate = $(this).val();
        if (startDate) {
            $('#contest-end_date').attr('min', startDate);
        }
    });
    
    // Проверяем, что дата окончания не раньше даты начала
    $('#contest-end_date').change(function() {
        var startDate = $('#contest-start_date').val();
        var endDate = $(this).val();
        
        if (startDate && endDate && endDate < startDate) {
            alert('Дата окончания не может быть раньше даты начала!');
            $(this).val(startDate);
        }
    });
});
JS;

$this->registerJs($js);
?>