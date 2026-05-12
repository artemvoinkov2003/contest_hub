<?php
use yii\helpers\Html;
use app\models\ContestResult;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты конкурса: <?= Html::encode($contest->name) ?></title>
    <!-- Tailwind CDN для PDF -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'print': {'raw': 'print'},
                    }
                }
            }
        }
    </script>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        @media print {
            body {
                margin: 0;
                padding: 10mm;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .print-only {
            display: none;
        }
        @media print {
            .print-only {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-white text-gray-900">
    <!-- Header -->
    <div class="print-only mb-8">
        <div class="text-center border-b pb-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">ИТОГОВЫЕ РЕЗУЛЬТАТЫ КОНКУРСА</h1>
            <h2 class="text-xl font-semibold text-gray-700 mt-2"><?= Html::encode($contest->name) ?></h2>
            <div class="text-sm text-gray-500 mt-1">Дата формирования: <?= date('d.m.Y H:i:s') ?></div>
        </div>
    </div>

    <?php 
    // Группируем результаты по номинациям
    $groupedResults = [];
    foreach ($applications as $application) {
        $result = ContestResult::findOne(['application_id' => $application->id]);
        if ($result) {
            $nominationName = $application->nomination->name ?? 'Без номинации';
            if (!isset($groupedResults[$nominationName])) {
                $groupedResults[$nominationName] = [];
            }
            $groupedResults[$nominationName][] = [
                'application' => $application,
                'result' => $result
            ];
        }
    }
    
    $nominationCount = 0;
    $totalNominations = count($groupedResults);
    ?>
    
    <?php foreach ($groupedResults as $nominationName => $nominationResults): ?>
        <?php $nominationCount++; ?>
        <!-- Nomination Header -->
        <div class="mb-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-3 mb-4">
                <h3 class="text-lg font-bold text-gray-900">
                    Номинация: <?= Html::encode($nominationName) ?>
                    <span class="float-right text-sm font-normal text-gray-600">
                        Участников: <?= count($nominationResults) ?>
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
                            <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Балл</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Место</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-700 uppercase tracking-wider border border-gray-200">Награда</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $counter = 1; ?>
                        <?php foreach ($nominationResults as $item): ?>
                            <?php 
                            $application = $item['application'];
                            $result = $item['result'];
                            
                            $rowClass = '';
                            if ($result->place == 1) $rowClass = 'bg-green-50';
                            elseif ($result->place == 2) $rowClass = 'bg-yellow-50';
                            elseif ($result->place == 3) $rowClass = 'bg-red-50';
                            ?>
                            <tr class="<?= $rowClass ?>">
                                <td class="px-3 py-2 text-sm text-center border border-gray-200"><?= $counter++ ?></td>
                                <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($application->getFullName()) ?></td>
                                <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($application->ageCategory->name ?? '') ?></td>
                                <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($application->work_name) ?></td>
                                <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($application->institution) ?></td>
                                <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($application->leader) ?></td>
                                <td class="px-3 py-2 text-sm text-center border border-gray-200"><?= $result->final_score ?></td>
                                <td class="px-3 py-2 text-sm text-center border border-gray-200"><?= $result->place ?></td>
                                <td class="px-3 py-2 text-sm border border-gray-200"><?= Html::encode($result->getAwardText()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if ($nominationCount < $totalNominations): ?>
            <div class="page-break"></div>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <!-- Footer -->
    <div class="mt-8 pt-4 border-t border-gray-200 print-only">
        <div class="text-center text-sm text-gray-500">
            <p>Документ сгенерирован автоматически конкурсной системой</p>
            <p class="mt-1">Всего участников: <?= count($applications) ?> | Всего номинаций: <?= $totalNominations ?></p>
            <p class="mt-1">Страница <span class="page-number"></span></p>
        </div>
    </div>
    
    <script>
        // Добавляем номера страниц
        document.addEventListener('DOMContentLoaded', function() {
            const pageNumbers = document.querySelectorAll('.page-number');
            pageNumbers.forEach((el, index) => {
                el.textContent = (index + 1);
            });
        });
    </script>
</body>
</html>