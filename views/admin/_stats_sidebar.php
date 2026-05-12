<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $contests app\models\Contest[] */
?>
<div class="bg-white shadow rounded-lg p-4">
    <h3 class="text-lg font-medium text-gray-900 mb-3">Конкурсы</h3>
    <nav class="space-y-1">
        <?php foreach ($contests as $contest): ?>
            <?php
            $total = \app\models\Application::find()
                ->where(['contest_id' => $contest->id, 'status' => ['accepted', 'graded', 'completed']])
                ->count();
            ?>
            <a href="<?= Url::to(['nomination-stats', 'contest_id' => $contest->id]) ?>" 
               class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md 
                      <?= Yii::$app->request->get('contest_id') == $contest->id ? 
                         'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 
                         'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <span class="truncate"><?= Html::encode($contest->name) ?></span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    <?= $total > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                    <?= $total ?>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>
</div>