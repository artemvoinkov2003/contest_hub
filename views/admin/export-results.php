<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Contest;

/* @var $this yii\web\View */
/* @var $contests app\models\Contest[] */

$this->title = 'Экспорт результатов конкурсов';
$this->params['breadcrumbs'][] = $this->title;

// Получаем все конкурсы
$contests = Contest::find()->orderBy(['start_date' => SORT_DESC])->all();
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Заголовок с градиентом -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-6">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-white">Экспорт результатов конкурсов</h1>
                    <p class="mt-1 text-sm text-blue-100">Выберите конкурс и формат для экспорта результатов</p>
                </div>
                <div class="flex space-x-3">
                    <?= Html::a('Назад к результатам', ['admin/contest-results'], [
                        'class' => 'inline-flex items-center px-5 py-2.5 border border-white text-sm font-medium rounded-xl shadow-lg text-white bg-transparent hover:bg-white/10 transition-all duration-200'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
        <!-- Форма выбора -->
        <div class="bg-white rounded-2xl shadow-xl border border-blue-100 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-blue-50 border-b border-blue-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Выбор параметров экспорта</h2>
                        <p class="text-sm text-gray-600">Укажите конкурс и формат для выгрузки данных</p>
                    </div>
                    <div class="flex items-center text-sm text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Всего конкурсов: <?= count($contests) ?>
                    </div>
                </div>
            </div>

            <?php if (empty($contests)): ?>
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Конкурсы не найдены</h3>
                    <p class="text-gray-500 mb-6">Сначала создайте конкурсы для экспорта результатов</p>
                    <a href="<?= Url::to(['admin/contest-create']) ?>" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-200">
                        Создать конкурс
                    </a>
                </div>
            <?php else: ?>
                <div class="p-6">
                    <?php $form = ActiveForm::begin([
                        'id' => 'export-form',
                        'action' => ['admin/export-results-generate'],
                        'options' => [
                            'class' => 'space-y-6',
                            'target' => '_blank'
                        ]
                    ]); ?>

                    <!-- Выбор конкурса -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Выберите конкурс
                                </div>
                            </label>
                            <select id="contest_id" name="contest_id" required
                                    class="mt-1 block w-full pl-4 pr-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">-- Выберите конкурс --</option>
                                <option value="all">Все конкурсы (общий отчет)</option>
                                <?php foreach ($contests as $contest): ?>
                                    <option value="<?= $contest->id ?>">
                                        <?= Html::encode($contest->name) ?> 
                                        (<?= Yii::$app->formatter->asDate($contest->start_date, 'php:d.m.Y') ?> - <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Для выбора всех конкурсов используйте опцию "Все конкурсы"</p>
                        </div>

                        <!-- Выбор формата -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Формат экспорта
                                </div>
                            </label>
                            <select id="format" name="format" required
                                    class="mt-1 block w-full pl-4 pr-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">-- Выберите формат --</option>
                                <option value="excel">Excel (.xlsx) - Рекомендуется</option>
                                <option value="pdf">PDF (.pdf) - Для печати</option>
                                <option value="word">Word (.docx) - Для редактирования</option>
                                <option value="csv">CSV (.csv) - Для импорта в другие системы</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Выберите наиболее подходящий формат для ваших целей</p>
                        </div>
                    </div>

                    <!-- Дополнительные опции -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Дополнительные опции</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="include_scores" name="include_scores" checked
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="include_scores" class="ml-2 block text-sm text-gray-700">
                                    Включить детальные баллы по критериям
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="include_contacts" name="include_contacts"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="include_contacts" class="ml-2 block text-sm text-gray-700">
                                    Включить контактную информацию участников
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="include_expert_comments" name="include_expert_comments"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="include_expert_comments" class="ml-2 block text-sm text-gray-700">
                                    Включить комментарии экспертов
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="group_by_nomination" name="group_by_nomination" checked
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="group_by_nomination" class="ml-2 block text-sm text-gray-700">
                                    Группировать по номинациям
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button type="submit" 
                                    id="export-button"
                                    class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Экспортировать результаты
                            </button>
                            <button type="button" 
                                    onclick="previewExport()"
                                    class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Предварительный просмотр
                            </button>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Информация о форматах -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Информация о форматах экспорта</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-4 border border-blue-200 transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900">Excel (.xlsx)</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Табличный формат</li>
                        <li>• Подходит для анализа</li>
                        <li>• Поддерживает формулы</li>
                        <li>• Автофильтры и сортировка</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-xl p-4 border border-red-200 transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900">PDF (.pdf)</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Для печати и отчетов</li>
                        <li>• Сохраняет форматирование</li>
                        <li>• Защита от редактирования</li>
                        <li>• Универсальный формат</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-xl p-4 border border-blue-200 transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900">Word (.docx)</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Для редактирования</li>
                        <li>• Подходит для документов</li>
                        <li>• Сохраняет стили</li>
                        <li>• Легко форматировать</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-xl p-4 border border-gray-200 transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center mb-3">
                        <div class="bg-gray-100 p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900">CSV (.csv)</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Простой текстовый формат</li>
                        <li>• Импорт в другие системы</li>
                        <li>• Маленький размер</li>
                        <li>• Универсальная совместимость</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="bg-white rounded-2xl shadow-lg border border-blue-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Статистика для экспорта</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">
                        <?= \app\models\ContestResult::find()->count() ?>
                    </div>
                    <p class="text-sm text-gray-600">Всего результатов в системе</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">
                        <?= \app\models\Contest::find()->count() ?>
                    </div>
                    <p class="text-sm text-gray-600">Конкурсов доступно</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600 mb-2">
                        <?= \app\models\Application::find()->where(['status' => 'completed'])->count() ?>
                    </div>
                    <p class="text-sm text-gray-600">Завершенных заявок</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportForm = document.getElementById('export-form');
    const exportButton = document.getElementById('export-button');
    
    if (exportForm) {
        exportForm.addEventListener('submit', function(e) {
            const contestId = document.getElementById('contest_id').value;
            const format = document.getElementById('format').value;
            
            if (!contestId || !format) {
                e.preventDefault();
                alert('Пожалуйста, выберите конкурс и формат экспорта.');
                return false;
            }
            
            // Показываем индикатор загрузки
            const originalText = exportButton.innerHTML;
            exportButton.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Идет подготовка файла...
            `;
            exportButton.disabled = true;
            
            // Через 5 секунд возвращаем кнопку в исходное состояние
            setTimeout(function() {
                exportButton.innerHTML = originalText;
                exportButton.disabled = false;
            }, 5000);
        });
    }
});

function previewExport() {
    const contestId = document.getElementById('contest_id').value;
    const format = document.getElementById('format').value;
    
    if (!contestId || !format) {
        alert('Пожалуйста, выберите конкурс и формат для предварительного просмотра.');
        return;
    }
    
    // Здесь можно было бы сделать AJAX запрос для предварительного просмотра
    // Но пока просто покажем сообщение
    alert('Функция предварительного просмотра в разработке. Для просмотра результатов перейдите в раздел "Итоговые результаты" и выберите нужный конкурс.');
    
    // Перенаправляем на страницу результатов конкурса, если выбран конкретный конкурс
    if (contestId !== 'all') {
        window.open(`<?= Url::to(['admin/contest-result-view']) ?>?contest_id=${contestId}`, '_blank');
    }
}
</script>