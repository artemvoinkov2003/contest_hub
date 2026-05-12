<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $application app\models\Application */
/* @var $contestResult app\models\ContestResult */
/* @var $contest app\models\Contest */
/* @var $nomination app\models\Nomination */
/* @var $ageCategory app\models\AgeCategory */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Диплом участника конкурса</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background: #f5f5f5;
            padding: 40px;
        }
        
        .diploma-container {
            max-width: 1000px;
            margin: 0 auto;
            background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
            border: 20px solid transparent;
            border-image: linear-gradient(45deg, #8B4513, #DAA520, #8B4513);
            border-image-slice: 1;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
            min-height: 800px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .header h1 {
            font-size: 48px;
            color: #8B0000;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .header .subtitle {
            font-size: 24px;
            color: #333;
            font-style: italic;
        }
        
        .award-info {
            text-align: center;
            margin: 40px 0;
        }
        
        .award-title {
            font-size: 36px;
            color: #DAA520;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .award-description {
            font-size: 20px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .winner-info {
            text-align: center;
            margin: 50px 0;
            padding: 30px;
            background: rgba(255, 255, 240, 0.5);
            border-radius: 10px;
            border: 2px solid #DAA520;
        }
        
        .winner-name {
            font-size: 32px;
            color: #000;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .work-name {
            font-size: 24px;
            color: #333;
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .contest-info {
            text-align: center;
            margin: 30px 0;
        }
        
        .contest-name {
            font-size: 28px;
            color: #8B0000;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .nomination-info {
            font-size: 22px;
            color: #444;
            margin-bottom: 10px;
        }
        
        .age-category {
            font-size: 20px;
            color: #555;
            margin-bottom: 10px;
        }
        
        .results {
            text-align: center;
            margin: 40px 0;
        }
        
        .score {
            font-size: 28px;
            color: #2E8B57;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .place {
            font-size: 32px;
            color: #DAA520;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .signatures {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }
        
        .signature-block {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 2px solid #000;
            margin: 40px auto 10px;
            width: 150px;
        }
        
        .signature-title {
            font-size: 18px;
            color: #333;
        }
        
        .date {
            text-align: center;
            margin-top: 40px;
            font-size: 18px;
            color: #666;
        }
        
        .logo {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 120px;
            height: 120px;
            background: #8B0000;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }
        
        .stamp {
            position: absolute;
            bottom: 100px;
            left: 100px;
            width: 150px;
            height: 150px;
            border: 3px solid #8B0000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #8B0000;
            text-align: center;
            transform: rotate(-15deg);
            opacity: 0.8;
        }
        
        @media print {
            body {
                padding: 0;
                background: white;
            }
            
            .diploma-container {
                border: 15px solid #8B4513;
                box-shadow: none;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="diploma-container">
        <div class="logo">Конкурс</div>
        
        <div class="header">
            <h1>Диплом</h1>
            <div class="subtitle">за участие в творческом конкурсе</div>
        </div>
        
        <div class="award-info">
            <div class="award-title"><?= $contestResult->getAwardText() ?></div>
            <div class="award-description">
                Настоящим дипломом подтверждается, что
            </div>
        </div>
        
        <div class="winner-info">
            <div class="winner-name">
                <?= Html::encode($application->surname . ' ' . $application->name . ($application->patronymic ? ' ' . $application->patronymic : '')) ?>
            </div>
            <div class="work-name">
                "<?= Html::encode($application->work_name) ?>"
            </div>
            <?php if ($application->institution): ?>
                <div style="font-size: 20px; color: #444; margin-top: 10px;">
                    <?= Html::encode($application->institution) ?>
                </div>
            <?php endif; ?>
            <?php if ($application->leader): ?>
                <div style="font-size: 18px; color: #555; margin-top: 5px;">
                    Руководитель: <?= Html::encode($application->leader) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="contest-info">
            <div class="contest-name">
                "<?= Html::encode($contest->name) ?>"
            </div>
            <div class="nomination-info">
                Номинация: <?= Html::encode($nomination->name) ?>
            </div>
            <div class="age-category">
                Возрастная категория: <?= Html::encode($ageCategory->name) ?>
            </div>
        </div>
        
        <div class="results">
            <?php if ($contestResult->place): ?>
                <div class="place">
                    <?= $contestResult->getPlaceText() ?> место
                </div>
            <?php endif; ?>
            <?php if ($contestResult->final_score): ?>
                <div class="score">
                    Общий балл: <?= number_format($contestResult->final_score, 2) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="date">
            Дата проведения конкурса: <?= date('d.m.Y', strtotime($contest->start_date)) ?> - <?= date('d.m.Y', strtotime($contest->end_date)) ?>
        </div>
        
        <div class="signatures">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">Председатель жюри</div>
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">Организатор конкурса</div>
            </div>
        </div>
        
        <div class="stamp">
            Официальный<br>документ
        </div>
    </div>
    
    <script>
        // Печать при загрузке (опционально)
        document.addEventListener('DOMContentLoaded', function() {
            // window.print(); // Раскомментируйте, если нужно автоматически печатать
        });
    </script>
</body>
</html>