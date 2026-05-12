<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Создание заявки' : 'Редактирование заявки';
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['applications']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= $this->title ?></h1>
                    <p class="mt-1 text-sm text-blue-100"><?= $model->isNewRecord ? 'Создание новой заявки' : 'Редактирование существующей заявки' ?></p>
                </div>
                <a href="<?= Url::to(['applications']) ?>" class="inline-flex items-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-xl text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                    Назад к списку
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'space-y-8 divide-y divide-gray-200', 'enctype' => 'multipart/form-data']
            ]); ?>

            <div class="space-y-8 divide-y divide-gray-200">
                <!-- Основная информация -->
                <div class="pt-8">
                    <div class="px-8">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Основная информация</h3>
                        <p class="mt-1 text-sm text-gray-500">Данные о конкурсной работе</p>
                    </div>

                    <div class="mt-6 px-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'work_name', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Название работы'
                            ])->label('Название работы', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'contest_id', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->dropDownList(
                                \yii\helpers\ArrayHelper::map($contests, 'id', 'name'),
                                [
                                    'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                    'prompt' => 'Выберите конкурс'
                                ]
                            )->label('Конкурс', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-3">
                            <?= $form->field($model, 'nomination_id', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->dropDownList(
                                [],
                                [
                                    'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                    'prompt' => 'Сначала выберите конкурс'
                                ]
                            )->label('Номинация', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-3">
                            <?= $form->field($model, 'age_category_id', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->dropDownList(
                                [],
                                [
                                    'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                    'prompt' => 'Сначала выберите конкурс'
                                ]
                            )->label('Возрастная категория', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>
                    </div>
                </div>

                <!-- Информация об участнике -->
                <div class="pt-8">
                    <div class="px-8">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Информация об участнике</h3>
                        <p class="mt-1 text-sm text-gray-500">Данные об участнике конкурса</p>
                    </div>

                    <div class="mt-6 px-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'user_id', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->dropDownList(
                                \yii\helpers\ArrayHelper::map($users, 'id', 'login'),
                                [
                                    'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                    'prompt' => 'Выберите пользователя'
                                ]
                            )->label('Пользователь', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-2">
                            <?= $form->field($model, 'surname', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Фамилия'
                            ])->label('Фамилия', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-2">
                            <?= $form->field($model, 'name', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Имя'
                            ])->label('Имя', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-2">
                            <?= $form->field($model, 'patronymic', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Отчество'
                            ])->label('Отчество', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'institution', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Учреждение'
                            ])->label('Учреждение', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'leader', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->textInput([
                                'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200',
                                'placeholder' => 'Руководитель'
                            ])->label('Руководитель', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>
                    </div>
                </div>

                <!-- Файл работы -->
                <div class="pt-8">
                    <div class="px-8">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Файл работы</h3>
                        <p class="mt-1 text-sm text-gray-500">Загрузите файл с конкурсной работой</p>
                    </div>

                    <div class="mt-6 px-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <?= $form->field($model, 'file', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->fileInput([
                                'class' => 'mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-gradient-to-r file:from-blue-50 file:to-blue-100 file:text-blue-700 hover:file:from-blue-100 hover:file:to-blue-200 transition-all duration-200'
                            ])->label('Файл работы', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>

                        <?php if (!$model->isNewRecord && $model->file_path): ?>
                        <div class="sm:col-span-6">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-200 text-blue-600">
                                        <?= $model->getFileTypeIcon() ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Текущий файл</div>
                                        <div class="text-sm text-gray-600">
                                            <?= basename($model->file_path) ?> (<?= $model->getFileExtension() ?> • <?= $model->getFileSize() ?>)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Статус -->
                <?php if (!$model->isNewRecord): ?>
                <div class="pt-8">
                    <div class="px-8">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Статус заявки</h3>
                        <p class="mt-1 text-sm text-gray-500">Текущий статус заявки в системе</p>
                    </div>

                    <div class="mt-6 px-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <?= $form->field($model, 'status', [
                                'template' => '{label}{input}{error}',
                                'options' => ['class' => '']
                            ])->dropDownList(
                                \app\models\Application::getStatuses(),
                                [
                                    'class' => 'mt-1 block w-full rounded-xl border border-blue-200 bg-white py-3 px-4 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 transition-all duration-200'
                                ]
                            )->label('Статус', ['class' => 'block text-sm font-bold text-gray-700 mb-2']) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Кнопки действий -->
            <div class="pt-8 pb-6">
                <div class="flex justify-end px-8 space-x-4">
                    <a href="<?= Url::to(['applications']) ?>" class="bg-white py-3 px-6 border-2 border-blue-200 rounded-xl shadow-sm text-sm font-bold text-blue-600 hover:bg-blue-50 hover:border-blue-300 transform hover:scale-105 transition-all duration-200">
                        Отмена
                    </a>
                    <?= Html::submitButton($model->isNewRecord ? 'Создать заявку' : 'Сохранить изменения', [
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
                Советы по созданию заявки
            </h4>
            <ul class="text-sm text-gray-600 space-y-2">
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Убедитесь, что выбранный конкурс активен и принимает заявки
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Поддерживаемые форматы файлов: MP4, AVI, MKV, PNG, JPG, JPEG, PDF
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Максимальный размер файла: 100 МБ
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Статистика пользователя -->
        <?php if (!$model->isNewRecord && $model->user): ?>
        <div class="mt-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
            <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Статистика пользователя
            </h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-4 border border-green-100">
                    <div class="text-sm font-medium text-gray-500">Всего заявок</div>
                    <div class="text-2xl font-bold text-green-600">
                        <?= \app\models\Application::find()->where(['user_id' => $model->user_id])->count() ?>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 border border-green-100">
                    <div class="text-sm font-medium text-gray-500">Активных</div>
                    <div class="text-2xl font-bold text-blue-600">
                        <?= \app\models\Application::find()->where(['user_id' => $model->user_id, 'status' => ['new', 'accepted']])->count() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function() {
    // Загрузка номинаций и возрастных категорий при выборе конкурса
    $('#application-contest_id').change(function() {
        var contestId = $(this).val();
        if (contestId) {
            // Показываем индикатор загрузки
            var nominationSelect = $('#application-nomination_id');
            var ageCategorySelect = $('#application-age_category_id');
            
            nominationSelect.html('<option value="">Загрузка...</option>');
            ageCategorySelect.html('<option value="">Загрузка...</option>');
            
            // Загрузка номинаций
            $.get('/admin/nominations-by-contest', {contest_id: contestId}, function(data) {
                nominationSelect.empty();
                nominationSelect.append('<option value="">Выберите номинацию</option>');
                $.each(data, function(key, value) {
                    nominationSelect.append('<option value="' + key + '">' + value + '</option>');
                });
            });

            // Загрузка возрастных категорий
            $.get('/admin/age-categories-by-contest', {contest_id: contestId}, function(data) {
                ageCategorySelect.empty();
                ageCategorySelect.append('<option value="">Выберите возрастную категорию</option>');
                $.each(data, function(key, value) {
                    ageCategorySelect.append('<option value="' + key + '">' + value + '</option>');
                });
            });
        } else {
            $('#application-nomination_id').empty().append('<option value="">Сначала выберите конкурс</option>');
            $('#application-age_category_id').empty().append('<option value="">Сначала выберите конкурс</option>');
        }
    });

    // Загрузка данных при редактировании
    if ($('#application-contest_id').val()) {
        var contestId = $('#application-contest_id').val();
        var nominationId = '$model->nomination_id';
        var ageCategoryId = '$model->age_category_id';
        
        $.get('/admin/nominations-by-contest', {contest_id: contestId}, function(data) {
            var select = $('#application-nomination_id');
            select.empty();
            select.append('<option value="">Выберите номинацию</option>');
            $.each(data, function(key, value) {
                var selected = (key == nominationId) ? 'selected' : '';
                select.append('<option value="' + key + '" ' + selected + '>' + value + '</option>');
            });
        });

        $.get('/admin/age-categories-by-contest', {contest_id: contestId}, function(data) {
            var select = $('#application-age_category_id');
            select.empty();
            select.append('<option value="">Выберите возрастную категорию</option>');
            $.each(data, function(key, value) {
                var selected = (key == ageCategoryId) ? 'selected' : '';
                select.append('<option value="' + key + '" ' + selected + '>' + value + '</option>');
            });
        });
    }
});
JS;
$this->registerJs($js);
?>