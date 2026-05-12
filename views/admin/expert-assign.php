<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Назначение эксперта: ' . $expert->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Эксперты', 'url' => ['experts']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <h1 class="text-3xl font-bold text-white">Назначение эксперта</h1>
                <p class="mt-1 text-sm text-blue-100"><?= Html::encode($expert->getFullName()) ?></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 p-6">
            <?php $form = ActiveForm::begin(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?= $form->field($model, 'contest_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map($contests, 'id', 'name'),
                    ['prompt' => 'Выберите конкурс', 'class' => 'form-input mt-1 block w-full rounded-lg border-blue-200']
                ) ?>
                
                <?= $form->field($model, 'nomination_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map($nominations, 'id', 'name'),
                    ['prompt' => 'Выберите номинацию', 'class' => 'form-input mt-1 block w-full rounded-lg border-blue-200']
                ) ?>
                
                <?= $form->field($model, 'age_category_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map($ageCategories, 'id', 'name'),
                    ['prompt' => 'Выберите возрастную категорию', 'class' => 'form-input mt-1 block w-full rounded-lg border-blue-200']
                ) ?>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="<?= Url::to(['expert-assignments', 'expert_id' => $expert->id]) ?>" class="px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                    Отмена
                </a>
                <button type="submit" class="px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200">
                    Назначить
                </button>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>