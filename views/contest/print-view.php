<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Печать результатов: ' . $contest->name;
?>
<div class="min-h-screen bg-gray-100 p-4 md:p-6">
    <!-- Control Panel -->
    <div class="no-print bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Подготовка к печати</h3>
                <p class="text-gray-600 text-sm mt-1">Настройте параметры страницы перед печатью</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Печать
                </button>
                <a href="<?= Url::to(['generate-excel', 'id' => $contest->id]) ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Скачать Excel
                </a>
                <button onclick="window.close()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Закрыть
                </button>
            </div>
        </div>
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-700">
                💡 <strong>Совет:</strong> В диалоге печати выберите "Сохранить как PDF" для создания PDF файла. 
                Убедитесь, что в настройках печати выбрана "Ландшафтная" ориентация.
            </p>
        </div>
    </div>
    
    <!-- Printable Content -->
    <div class="print-area bg-white rounded-lg shadow-sm p-4 md:p-6">
        <!-- Header -->
        <div class="text-center border-b pb-4 mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">ИТОГОВЫЕ РЕЗУЛЬТАТЫ КОНКУРСА</h1>
            <h2 class="text-xl md:text-2xl font-semibold text-gray-700 mt-2"><?= Html::encode($contest->name) ?></h2>
            <div class="text-sm text-gray-500 mt-1">Дата формирования: <?= date('d.m.Y H:i:s') ?></div>
        </div>
        
        <?php 
        // Группируем по номинациям
        $grouped = [];
        foreach ($applications as $app) {
            $nomination = $app->nomination->name ?? 'Без номинации';
            if (!isset($grouped[$nomination])) {
                $grouped[$nomination] = [];
            }
            $grouped[$nomination][] = $app;
        }
        ?>
        
        <?php foreach ($grouped as $nominationName => $nominationApps): ?>
            <!-- Nomination Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 px-4 py-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        Номинация: <?= Html::encode($nominationName) ?>
                        <span class="float-right text-sm font-normal text-gray-600">
                            Участников: <?= count($nominationApps) ?>
                        </span>
                    </h3>
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">№</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">ФИО участника</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Возрастная категория</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Название работы</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Учебное заведение</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Руководитель</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Итоговый балл</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Место</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Награда</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $counter = 1; ?>
                            <?php foreach ($nominationApps as $app): ?>
                                <?php $result = \app\models\ContestResult::findOne(['application_id' => $app->id]); ?>
                                <?php 
                                $rowClass = '';
                                if ($result) {
                                    if ($result->place == 1) $rowClass = 'bg-green-50';
                                    elseif ($result->place == 2) $rowClass = 'bg-yellow-50';
                                    elseif ($result->place == 3) $rowClass = 'bg-red-50';
                                }
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td class="px-3 py-2 text-sm text-center border border-gray-200"><?= $counter++ ?></td>
                                    <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($app->getFullName()) ?></td>
                                    <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($app->ageCategory->name ?? '') ?></td>
                                    <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($app->work_name) ?></td>
                                    <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($app->institution) ?></td>
                                    <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($app->leader) ?></td>
                                    <td class="px-3 py-2 text-sm text-center border border-gray-200"><?= $result ? $result->final_score : '-' ?></td>
                                    <td class="px-3 py-2 text-sm text-center border border-gray-200"><?= $result ? $result->place : '-' ?></td>
                                    <td class="px-3 py-2 text-sm border border-gray-200"><?= $result ? Html::encode($result->getAwardText()) : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Summary -->
        <div class="mt-8 pt-4 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-800">Всего номинаций</div>
                    <div class="mt-1 text-2xl font-bold text-blue-900"><?= count($grouped) ?></div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-800">Всего участников</div>
                    <div class="mt-1 text-2xl font-bold text-green-900"><?= count($applications) ?></div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-purple-800">Дата формирования</div>
                    <div class="mt-1 text-lg font-bold text-purple-900"><?= date('d.m.Y H:i') ?></div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>Документ сгенерирован автоматически конкурсной системой</p>
            <p class="mt-1">Страница <span class="page-number">1</span></p>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .print-area {
        width: 100%;
        padding: 0;
        margin: 0;
        box-shadow: none;
    }
    body {
        background: white !important;
        font-size: 11pt;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
    }
    th, td {
        border: 1px solid #000;
        padding: 3pt;
    }
    h1, h2, h3 {
        page-break-after: avoid;
    }
    .page-break {
        page-break-after: always;
    }
}
</style>

<script>
// Автоматическая печать (опционально)
// window.addEventListener('load', function() {
//     setTimeout(function() {
//         window.print();
//     }, 1000);
// });

// Обновляем номер страницы
window.addEventListener('beforeprint', function() {
    document.querySelectorAll('.page-number').forEach((el, index) => {
        el.textContent = (index + 1);
    });
});
</script>