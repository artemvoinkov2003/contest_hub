<?php
$this->title = 'Генерация Excel-отчета';
$this->params['breadcrumbs'][] = ['label' => 'Конкурсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $contest->name, 'url' => ['view', 'id' => $contest->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info">
    <h4>Файл Excel генерируется...</h4>
    <p>Если загрузка не началась автоматически, <a href="#" onclick="window.location.reload()">обновите страницу</a>.</p>
</div>

<script>
// Автоматическая загрузка файла
setTimeout(function() {
    window.location.href = '<?= \yii\helpers\Url::to(['generate-excel', 'id' => $contest->id]) ?>';
}, 1000);
</script>