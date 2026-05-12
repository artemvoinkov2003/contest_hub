<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Создать пользователя' : 'Редактировать пользователя: ' . $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['users']];
$this->params['breadcrumbs'][] = $model->isNewRecord ? 'Создать' : 'Редактировать';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h1 class="text-3xl font-bold text-white"><?= $this->title ?></h1>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 p-6">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'space-y-6']
            ]); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?= $form->field($model, 'name', [
                    'template' => '
                        <label class="block text-sm font-medium text-gray-700">{label}</label>
                        <div class="mt-1">{input}</div>
                        {error}
                        <div class="mt-1 text-sm text-gray-500">{hint}</div>
                    '
                ])->textInput([
                    'class' => 'block w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200',
                    'placeholder' => 'Имя'
                ]) ?>

                <?= $form->field($model, 'surname', [
                    'template' => '
                        <label class="block text-sm font-medium text-gray-700">{label}</label>
                        <div class="mt-1">{input}</div>
                        {error}
                        <div class="mt-1 text-sm text-gray-500">{hint}</div>
                    '
                ])->textInput([
                    'class' => 'block w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200',
                    'placeholder' => 'Фамилия'
                ]) ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?= $form->field($model, 'patronymic', [
                    'template' => '
                        <label class="block text-sm font-medium text-gray-700">{label}</label>
                        <div class="mt-1">{input}</div>
                        {error}
                        <div class="mt-1 text-sm text-gray-500">{hint}</div>
                    '
                ])->textInput([
                    'class' => 'block w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200',
                    'placeholder' => 'Отчество'
                ]) ?>

                <?= $form->field($model, 'login', [
                    'template' => '
                        <label class="block text-sm font-medium text-gray-700">{label}</label>
                        <div class="mt-1">{input}</div>
                        {error}
                        <div class="mt-1 text-sm text-gray-500">{hint}</div>
                    '
                ])->textInput([
                    'class' => 'block w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200',
                    'placeholder' => 'Логин'
                ]) ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?= $form->field($model, 'email', [
                    'template' => '
                        <label class="block text-sm font-medium text-gray-700">{label}</label>
                        <div class="mt-1">{input}</div>
                        {error}
                        <div class="mt-1 text-sm text-gray-500">{hint}</div>
                    '
                ])->textInput([
                    'class' => 'block w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200',
                    'placeholder' => 'Email',
                    'type' => 'email'
                ]) ?>

                <?php if ($model->isNewRecord): ?>
                    <?= $form->field($model, 'password_input', [
                        'template' => '
                            <label class="block text-sm font-medium text-gray-700">{label}</label>
                            <div class="mt-1">{input}</div>
                            {error}
                            <div class="mt-1 text-sm text-gray-500">{hint}</div>
                        '
                    ])->passwordInput([
                        'class' => 'block w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200',
                        'placeholder' => 'Пароль'
                    ]) ?>
                <?php endif; ?>
            </div>

            <?php if ($model->isNewRecord): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?= $form->field($model, 'password_repeat', [
                        'template' => '
                            <label class="block text-sm font-medium text-gray-700">{label}</label>
                            <div class="mt-1">{input}</div>
                            {error}
                            <div class="mt-1 text-sm text-gray-500">{hint}</div>
                        '
                    ])->passwordInput([
                        'class' => 'block w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-200',
                        'placeholder' => 'Повторите пароль'
                    ]) ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?= $form->field($model, 'is_admin', [
                    'template' => '
                        <label class="flex items-center">
                            {input}
                            <span class="ml-2 text-sm text-gray-700">{label}</span>
                        </label>
                        {error}
                        <div class="mt-1 text-sm text-gray-500">{hint}</div>
                    '
                ])->checkbox([
                    'class' => 'rounded border-blue-300 text-blue-600 focus:ring-blue-500'
                ]) ?>

                <?= $form->field($model, 'is_blocked', [
                    'template' => '
                        <label class="flex items-center">
                            {input}
                            <span class="ml-2 text-sm text-gray-700">{label}</span>
                        </label>
                        {error}
                        <div class="mt-1 text-sm text-gray-500">{hint}</div>
                    '
                ])->checkbox([
                    'class' => 'rounded border-blue-300 text-blue-600 focus:ring-blue-500'
                ]) ?>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="<?= \yii\helpers\Url::to(['users']) ?>" class="px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                    Отмена
                </a>
                <?= Html::submitButton($model->isNewRecord ? 'Создать пользователя' : 'Обновить данные', [
                    'class' => 'px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>