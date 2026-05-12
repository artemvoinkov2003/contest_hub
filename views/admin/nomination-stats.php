<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $contest app\models\Contest */
/* @var $stats array */

$this->title = 'Статистика по номинациям: ' . $contest->name;
$this->params['breadcrumbs'][] = ['label' => 'Статистика', 'url' => ['nomination-stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nomination-stats">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?= Html::encode($this->title) ?></h1>
            <p class="mt-2 text-gray-600">Статистика заполненности номинаций участниками</p>
        </div>
        <?= Html::a('Назад к конкурсам', ['contest-results'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Общая информация</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Конкурс:</dt>
                    <dd class="text-sm text-gray-900"><?= Html::encode($contest->name) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Период:</dt>
                    <dd class="text-sm text-gray-900">
                        <?= Yii::$app->formatter->asDate($contest->start_date) ?> - <?= Yii::$app->formatter->asDate($contest->end_date) ?>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Номинаций:</dt>
                    <dd class="text-sm text-gray-900"><?= count($stats) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Всего участников:</dt>
                    <?php
                    $totalParticipants = array_sum(array_column($stats, 'total'));
                    $totalCapacity = array_sum(array_column($stats, 'max_participants'));
                    ?>
                    <dd class="text-sm text-gray-900"><?= $totalParticipants ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Общая заполненность:</dt>
                    <dd class="text-sm text-gray-900">
                        <?= $totalCapacity > 0 ? round(($totalParticipants / $totalCapacity) * 100, 1) : '0' ?>%
                    </dd>
                </div>
            </dl>
        </div>

        <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Детальная статистика</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номинация</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Участников</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Лимит</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Заполненность</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Прогресс</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($stats as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= Html::encode($item['nomination']->name) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= Html::encode($item['nomination']->description) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        <?= $item['total'] > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= $item['total'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($item['max_participants']): ?>
                                        <span class="text-sm text-gray-900"><?= $item['max_participants'] ?></span>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-400">Без ограничений</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($item['max_participants'] > 0): ?>
                                        <span class="text-sm text-gray-900"><?= $item['percentage'] ?>%</span>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($item['max_participants'] > 0): ?>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full 
                                                <?= $item['percentage'] >= 100 ? 'bg-red-600' : 
                                                   ($item['percentage'] >= 80 ? 'bg-yellow-500' : 'bg-green-600') ?>" 
                                                style="width: <?= min($item['percentage'], 100) ?>%">
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($item['max_participants'] > 0): ?>
                                        <?php if ($item['percentage'] >= 100): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                Переполнена
                                            </span>
                                        <?php elseif ($item['percentage'] >= 80): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                Почти заполнена
                                            </span>
                                        <?php elseif ($item['percentage'] >= 50): ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                Средняя заполненность
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                Свободна
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Без ограничений
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Визуализация статистики -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Визуализация заполненности номинаций</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($stats as $item): ?>
                <?php if ($item['max_participants'] > 0): ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-sm font-medium text-gray-900 truncate"><?= Html::encode($item['nomination']->name) ?></h4>
                            <span class="text-sm text-gray-500"><?= $item['total'] ?>/<?= $item['max_participants'] ?></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-1">
                            <div class="h-3 rounded-full 
                                <?= $item['percentage'] >= 100 ? 'bg-red-600' : 
                                   ($item['percentage'] >= 80 ? 'bg-yellow-500' : 'bg-green-600') ?>" 
                                style="width: <?= min($item['percentage'], 100) ?>%">
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>0%</span>
                            <span class="<?= $item['percentage'] >= 80 ? 'font-medium text-yellow-600' : '' ?>">
                                <?= $item['percentage'] ?>%
                            </span>
                            <span>100%</span>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>