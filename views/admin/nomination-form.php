<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = $model->isNewRecord ? 'Создание номинации' : 'Редактирование номинации';
$this->params['breadcrumbs'][] = ['label' => 'Номинации', 'url' => ['nominations']];
$this->params['breadcrumbs'][] = $this->title;

$contests = \app\models\Contest::find()->where(['status' => 1])->all();
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h1 class="text-3xl font-bold text-white"><?= $this->title ?></h1>
                <p class="mt-1 text-sm text-blue-100">Заполните информацию о номинации</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-blue-50 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-bold text-gray-900">Основная информация</h3>
            </div>
            
            <div class="p-6">
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'space-y-6'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'block text-sm font-medium text-gray-700 mb-1'],
                        'inputOptions' => ['class' => 'mt-1 block w-full rounded-lg border border-gray-300 py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-blue-500'],
                        'errorOptions' => ['class' => 'mt-1 text-sm text-red-600']
                    ]
                ]); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Название номинации -->
                    <div class="md:col-span-2">
                        <?= $form->field($model, 'name')->textInput([
                            'placeholder' => 'Введите название номинации',
                            'maxlength' => true
                        ]) ?>
                    </div>
                    
                    <!-- Конкурс -->
                    <div>
                        <?= $form->field($model, 'contest_id')->dropDownList(
                            ArrayHelper::map($contests, 'id', 'name'),
                            [
                                'prompt' => 'Выберите конкурс',
                                'class' => 'mt-1 block w-full rounded-lg border border-gray-300 py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                            ]
                        ) ?>
                    </div>
                    
                    <!-- Максимальное количество участников -->
                    <div>
                        <?= $form->field($model, 'max_participants')->textInput([
                            'type' => 'number',
                            'min' => '0',
                            'placeholder' => '0 - без ограничений',
                            'class' => 'mt-1 block w-full rounded-lg border border-gray-300 py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                        ]) ?>
                        <p class="mt-1 text-sm text-gray-500">
                            Укажите 0 для неограниченного количества участников
                        </p>
                    </div>
                    
                    <!-- Описание -->
                    <div class="md:col-span-2">
                        <?= $form->field($model, 'description')->textarea([
                            'rows' => 4,
                            'placeholder' => 'Описание номинации (необязательно)',
                            'class' => 'mt-1 block w-full rounded-lg border border-gray-300 py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-blue-500'
                        ]) ?>
                    </div>
                </div>
                
                <!-- Информация о текущей загрузке если редактирование -->
                <?php if (!$model->isNewRecord): ?>
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Текущая статистика:</h4>
                        <?php
                        $currentCount = \app\models\Application::find()
                            ->where(['nomination_id' => $model->id])
                            ->andWhere(['status' => ['accepted', 'completed']])
                            ->count();
                        $pendingCount = \app\models\Application::find()
                            ->where(['nomination_id' => $model->id])
                            ->andWhere(['status' => 'new'])
                            ->count();
                        ?>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Принято заявок:</span>
                                <span class="ml-2 font-medium <?= $model->max_participants > 0 && $currentCount >= $model->max_participants ? 'text-red-600' : 'text-green-600' ?>">
                                    <?= $currentCount ?>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Ожидают рассмотрения:</span>
                                <span class="ml-2 font-medium text-yellow-600"><?= $pendingCount ?></span>
                            </div>
                            <?php if ($model->max_participants > 0): ?>
                                <div class="col-span-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full <?= $currentCount >= $model->max_participants ? 'bg-red-600' : 'bg-green-600' ?>" 
                                              style="width: <?= min(($currentCount / $model->max_participants) * 100, 100) ?>%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Заполнено: <?= round(($currentCount / $model->max_participants) * 100, 1) ?>%
                                        <?php if ($currentCount >= $model->max_participants): ?>
                                            <span class="text-red-600 font-medium ml-2">Лимит достигнут!</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Кнопки -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="<?= Url::to(['nominations']) ?>" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Отмена
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-medium rounded-lg shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <?= $model->isNewRecord ? 'Создать номинацию' : 'Сохранить изменения' ?>
                    </button>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>