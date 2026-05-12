<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="container mt-4">
    <h1>Назначение экспертов</h1>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'contest_id')->dropDownList(
                ArrayHelper::map($contests, 'id', 'name'),
                ['prompt' => 'Выберите конкурс', 'id' => 'contest-id']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nomination_id')->dropDownList(
                [],
                ['prompt' => 'Выберите номинацию', 'id' => 'nomination-id']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'age_category_id')->dropDownList(
                [],
                ['prompt' => 'Выберите возрастную категорию', 'id' => 'age-category-id']
            ) ?>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <?= $form->field($model, 'expert_ids')->checkboxList(
                ArrayHelper::map($experts, 'id', 'login'),
                ['multiple' => true, 'class' => 'expert-checkboxes']
            )->label('Выберите экспертов (можно несколько)') ?>
        </div>
    </div>
    
    <div class="form-group mt-3">
        <?= Html::submitButton('Назначить экспертов', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
    
    <hr>
    
    <h3>Текущие назначения</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Конкурс</th>
                <th>Номинация</th>
                <th>Возрастная категория</th>
                <th>Эксперты</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?= $assignment['contest_name'] ?></td>
                <td><?= $assignment['nomination_name'] ?></td>
                <td><?= $assignment['age_category_name'] ?></td>
                <td>
                    <?php foreach ($assignment['experts'] as $expert): ?>
                        <span class="badge bg-primary"><?= $expert ?></span>
                    <?php endforeach; ?>
                </td>
                <td>
                    <?= Html::a('Удалить', ['admin/delete-assignment', 'id' => $assignment['id']], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => ['confirm' => 'Удалить назначение?']
                    ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$this->registerJs(<<<JS
$(document).ready(function() {
    // Загрузка номинаций при выборе конкурса
    $('#contest-id').change(function() {
        var contestId = $(this).val();
        $.get('/admin/get-nominations', {contest_id: contestId}, function(data) {
            $('#nomination-id').html('<option value="">Выберите номинацию</option>');
            $.each(data, function(id, name) {
                $('#nomination-id').append('<option value="' + id + '">' + name + '</option>');
            });
        });
    });
    
    // Загрузка возрастных категорий при выборе конкурса
    $('#contest-id').change(function() {
        var contestId = $(this).val();
        $.get('/admin/get-age-categories', {contest_id: contestId}, function(data) {
            $('#age-category-id').html('<option value="">Выберите возрастную категорию</option>');
            $.each(data, function(id, name) {
                $('#age-category-id').append('<option value="' + id + '">' + name + '</option>');
            });
        });
    });
});
JS
);
?>