<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $contest app\models\Contest */
/* @var $applications app\models\Application[] */
/* @var $awardTypes array */

$this->title = 'Управление результатами: ' . $contest->name;
$this->params['breadcrumbs'][] = ['label' => 'Итоги', 'url' => ['contest-results']];
$this->params['breadcrumbs'][] = ['label' => $contest->name, 'url' => ['contest-result-view', 'contest_id' => $contest->id]];
$this->params['breadcrumbs'][] = 'Управление';
?>
<div class="contest-result-manage">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><?= Html::encode($this->title) ?></h1>
        <p class="mt-2 text-gray-600">Назначение мест и наград участникам конкурса</p>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'results-form']); ?>
    
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Участник</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Работа / Номинация</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Балл</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Место</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Награда</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($applications as $index => $application): ?>
                        <?php
                        $result = \app\models\ContestResult::findByApplicationId($application->id);
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $index + 1 ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= Html::encode($application->getFullName()) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= Html::encode($application->institution) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= Html::a(
                                        Html::encode($application->work_name),
                                        ['application-view', 'id' => $application->id],
                                        ['class' => 'text-blue-600 hover:underline']
                                    ) ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= $application->nomination->name ?? 'Не указано' ?> / 
                                    <?= $application->ageCategory->name ?? 'Не указано' ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($result && $result->final_score): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <?= $result->final_score ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" 
                                       name="results[<?= $application->id ?>][place]" 
                                       value="<?= $result ? $result->place : '' ?>"
                                       min="1"
                                       class="form-input w-20 text-center"
                                       placeholder="—">
                            </td>
                            <td class="px-6 py-4">
                                <select name="results[<?= $application->id ?>][award_type]" 
                                        class="form-select w-full">
                                    <option value="">— Выберите награду —</option>
                                    <?php foreach ($awardTypes as $value => $label): ?>
                                        <option value="<?= $value ?>" 
                                                <?= ($result && $result->award_type == $value) ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($result && $result->final_score): ?>
                                    <input type="hidden" 
                                           name="results[<?= $application->id ?>][final_score]" 
                                           value="<?= $result->final_score ?>">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Есть результат
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Нет результата
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Сохранение результатов</h3>
                <p class="mt-1 text-sm text-gray-500">
                    После сохранения участники получат уведомления, а заявки будут переведены в статус "Завершено"
                </p>
            </div>
            <div class="flex space-x-3">
                <?= Html::a('Отмена', ['contest-result-view', 'contest_id' => $contest->id], ['class' => 'btn btn-secondary']) ?>
                <?= Html::submitButton('Сохранить результаты', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>