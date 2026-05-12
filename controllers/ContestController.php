<?php

namespace app\controllers;

use Yii;
use app\models\Contest;
use app\models\Application;
use app\models\AgeCategory;
use app\models\Nomination;
use app\models\Notification;
use app\models\ContestResult;
use app\models\GeneratedDocument;
use app\models\ReportTemplate;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use yii\web\Response;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

/**
 * ContestController implements the CRUD actions for Contest model.
 */
class ContestController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'generate-results' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Contest models.
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Contest::find()->where(['status' => 1]),
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contest model.
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $ageCategories = AgeCategory::find()->where(['contest_id' => $id])->all();
        $nominations = Nomination::find()->where(['contest_id' => $id])->all();
        $results = ContestResult::findByContestId($id);
        
        // Статистика по заполненности номинаций
        $nominationStats = [];
        foreach ($nominations as $nomination) {
            $total = Application::find()
                ->where(['nomination_id' => $nomination->id, 'status' => ['accepted', 'completed']])
                ->count();
            
            $nominationStats[] = [
                'nomination' => $nomination,
                'total' => $total,
                'max_participants' => $nomination->max_participants,
                'percentage' => $nomination->max_participants > 0 ? 
                    round(($total / $nomination->max_participants) * 100, 1) : 0,
            ];
        }

        return $this->render('view', [
            'model' => $model,
            'ageCategories' => $ageCategories,
            'nominations' => $nominations,
            'results' => $results,
            'nominationStats' => $nominationStats,
        ]);
    }

    /**
     * Creates a new Application for contest.
     */

public function actionApply($id)
{
    $contest = $this->findModel($id);
    $model = new Application();
    $model->contest_id = $id;
    $model->user_id = Yii::$app->user->id;
    $model->status = Application::STATUS_NEW;

    if ($this->request->isPost) {
        if ($model->load($this->request->post())) {
            // Получаем загруженный файл
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            
            // ПРОВЕРКА ЛИМИТА УЧАСТНИКОВ В НОМИНАЦИИ
            $nominationId = $model->nomination_id;
            if ($nominationId) {
                $nomination = Nomination::findOne($nominationId);
                if ($nomination && $nomination->max_participants > 0) {
                    $currentCount = Application::find()
                        ->where(['nomination_id' => $nominationId, 'contest_id' => $id])
                        ->andWhere(['!=', 'status', Application::STATUS_BLOCKED])
                        ->count();
                    
                    if ($currentCount >= $nomination->max_participants) {
                        Yii::$app->session->setFlash('error', 
                            "В номинации '{$nomination->name}' достигнут лимит участников ({$nomination->max_participants}). " .
                            "Выберите другую номинацию."
                        );
                        
                        return $this->render('apply', [
                            'contest' => $contest,
                            'model' => $model,
                            'ageCategories' => AgeCategory::find()->where(['contest_id' => $id])->all(),
                            'nominations' => Nomination::find()->where(['contest_id' => $id])->all(),
                        ]);
                    }
                }
            }
            
            // Проверяем наличие файла
            if (!$model->file) {
                Yii::$app->session->setFlash('error', 'Необходимо загрузить файл работы.');
                return $this->render('apply', [
                    'contest' => $contest,
                    'model' => $model,
                    'ageCategories' => AgeCategory::find()->where(['contest_id' => $id])->all(),
                    'nominations' => Nomination::find()->where(['contest_id' => $id])->all(),
                ]);
            }
            
            // Валидация модели (включая файл)
            if ($model->validate()) {
                // Загружаем файл
                if ($model->upload()) {
                    // Сохраняем модель, отключаем валидацию так как уже проверили
                    if ($model->save(false)) {
                        // Уведомление
                        Notification::create(
                            $model->user_id,
                            'Заявка подана',
                            "Ваша заявка '{$model->work_name}' успешно подана на конкурс '{$contest->name}'. Статус: Новая"
                        );
                        
                        Yii::$app->session->setFlash('success', 'Заявка успешно отправлена!');
                        return $this->redirect(['contest/view', 'id' => $id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'Ошибка при сохранении заявки в базу данных.');
                        // Логируем ошибки
                        Yii::error('Ошибка сохранения заявки: ' . print_r($model->errors, true));
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла на сервер.');
                }
            } else {
                // Показываем ошибки валидации
                $errorMessages = [];
                foreach ($model->errors as $attribute => $errors) {
                    foreach ($errors as $error) {
                        $errorMessages[] = $error;
                    }
                }
                
                if (!empty($errorMessages)) {
                    Yii::$app->session->setFlash('error', implode('<br>', $errorMessages));
                } else {
                    Yii::$app->session->setFlash('error', 'Произошла ошибка при отправке заявки.');
                }
                
                Yii::error('Ошибки валидации заявки: ' . print_r($model->errors, true));
            }
        }
    } else {
        $model->loadDefaultValues();
    }

    $ageCategories = AgeCategory::find()->where(['contest_id' => $id])->all();
    $nominations = Nomination::find()->where(['contest_id' => $id])->all();

    return $this->render('apply', [
        'contest' => $contest,
        'model' => $model,
        'ageCategories' => $ageCategories,
        'nominations' => $nominations,
    ]);
}

    /**
     * Displays contest results.
     */
    public function actionResults($id)
    {
        $contest = $this->findModel($id);
        $results = ContestResult::findByContestId($id);
        
        // Группируем результаты по номинациям и возрастным категориям
        $groupedResults = [];
        foreach ($results as $result) {
            $app = $result->application;
            $key = $app->nomination_id . '_' . $app->age_category_id;
            if (!isset($groupedResults[$key])) {
                $groupedResults[$key] = [
                    'nomination' => $app->nomination->name ?? 'Не указано',
                    'ageCategory' => $app->ageCategory->name ?? 'Не указано',
                    'results' => [],
                ];
            }
            $groupedResults[$key]['results'][] = $result;
        }

        return $this->render('results', [
            'contest' => $contest,
            'groupedResults' => $groupedResults,
        ]);
    }

    /**
     * Download program for contest.
     */
    public function actionDownloadProgram($id)
    {
        $contest = $this->findModel($id);
        
        $template = ReportTemplate::find()
            ->where(['contest_id' => $id, 'type' => 'program'])
            ->one();
        
        if (!$template || !file_exists($template->template_file)) {
            Yii::$app->session->setFlash('error', 'Шаблон программы не найден.');
            return $this->redirect(['view', 'id' => $id]);
        }
        
        return Yii::$app->response->sendFile($template->template_file, 
            "Программа_{$contest->name}.pdf");
    }

    /**
     * Download evaluation sheet for contest.
     */
    public function actionDownloadEvaluationSheet($id)
    {
        $contest = $this->findModel($id);
        
        $template = ReportTemplate::find()
            ->where(['contest_id' => $id, 'type' => 'evaluation_sheet'])
            ->one();
        
        if (!$template || !file_exists($template->template_file)) {
            Yii::$app->session->setFlash('error', 'Шаблон оценочного листа не найден.');
            return $this->redirect(['view', 'id' => $id]);
        }
        
        return Yii::$app->response->sendFile($template->template_file, 
            "Оценочный_лист_{$contest->name}.pdf");
    }

    /**
     * Generate Excel report for contest results
     */
    public function actionGenerateExcel($id)
    {
        return $this->generateExcelReport($id);
    }

    /**
     * Generate PDF report for contest results
     */
    public function actionGeneratePdf($id)
    {
        return $this->generateHtmlPdfReport($id);
    }

    /**
     * Generate Excel report (XLSX format)
     */
    private function generateExcelReport($id)
    {
        $contest = $this->findModel($id);
        
        // Получаем данные для отчета
        $applications = Application::find()
            ->where(['contest_id' => $id])
            ->andWhere(['status' => ['accepted', 'graded', 'completed']])
            ->orderBy(['nomination_id' => SORT_ASC, 'age_category_id' => SORT_ASC])
            ->all();
        
        // Если нет данных
        if (empty($applications)) {
            Yii::$app->session->setFlash('warning', 'Нет данных для формирования отчета.');
            return $this->redirect(['results', 'id' => $id]);
        }
        
        // Создаем новый Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Устанавливаем свойства документа
        $spreadsheet->getProperties()
            ->setCreator("Конкурсная система")
            ->setLastModifiedBy("Конкурсная система")
            ->setTitle("Результаты конкурса: {$contest->name}")
            ->setSubject("Результаты конкурса")
            ->setDescription("Результаты конкурса {$contest->name}, сгенерированные системой");
        
        // Заголовок
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'ИТОГОВЫЕ РЕЗУЛЬТАТЫ КОНКУРСА');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A2', $contest->name);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->mergeCells('A3:M3');
        $sheet->setCellValue('A3', 'Дата выгрузки: ' . date('d.m.Y H:i:s'));
        $sheet->getStyle('A3')->getFont()->setSize(11);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Пустая строка
        $sheet->setCellValue('A4', '');
        
        // Заголовки таблицы
        $headers = [
            '№ п/п',
            'Фамилия',
            'Имя',
            'Отчество',
            'Номинация',
            'Возрастная категория',
            'Название работы',
            'Учебное заведение',
            'Руководитель',
            'Статус',
            'Итоговый балл',
            'Место',
            'Награда'
        ];
        
        $row = 5;
        $col = 1;
        foreach ($headers as $header) {
            $columnLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->setCellValue($columnLetter . $row, $header);
            $col++;
        }
        
        // Стили для заголовков
        $sheet->getStyle('A5:M5')->getFont()->setBold(true);
        $sheet->getStyle('A5:M5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:M5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A5:M5')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A5:M5')->getFill()->getStartColor()->setARGB('FFE0E0E0');
        $sheet->getStyle('A5:M5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Данные
        $row = 6;
        $counter = 1;
        
        foreach ($applications as $application) {
            // Получаем результат конкурса
            $result = ContestResult::findOne(['application_id' => $application->id]);
            
            $sheet->setCellValue('A' . $row, $counter);
            $sheet->setCellValue('B' . $row, $application->surname ?? '');
            $sheet->setCellValue('C' . $row, $application->name ?? '');
            $sheet->setCellValue('D' . $row, $application->patronymic ?? '');
            $sheet->setCellValue('E' . $row, $application->nomination->name ?? '');
            $sheet->setCellValue('F' . $row, $application->ageCategory->name ?? '');
            $sheet->setCellValue('G' . $row, $application->work_name ?? '');
            $sheet->setCellValue('H' . $row, $application->institution ?? '');
            $sheet->setCellValue('I' . $row, $application->leader ?? '');
            $sheet->setCellValue('J' . $row, $application->getStatusLabel() ?? '');
            
            if ($result) {
                $sheet->setCellValue('K' . $row, $result->final_score ?? '');
                $sheet->setCellValue('L' . $row, $result->place ?? '');
                $sheet->setCellValue('M' . $row, $result->getAwardText() ?? '');
            } else {
                $sheet->setCellValue('K' . $row, '-');
                $sheet->setCellValue('L' . $row, '-');
                $sheet->setCellValue('M' . $row, '-');
            }
            
            $row++;
            $counter++;
        }
        
        // Устанавливаем границы для всех ячеек с данными
        $lastRow = $row - 1;
        if ($lastRow >= 6) {
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle('A6:M' . $lastRow)->applyFromArray($styleArray);
        }
        
        // Авторазмер колонок
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Выравнивание текста
        $sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L6:L' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K6:K' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Перенос текста для длинных полей
        $sheet->getStyle('G6:G' . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('H6:H' . $lastRow)->getAlignment()->setWrapText(true);
        
        // Имя файла (латиницей для совместимости)
        $filename = 'results_contest_' . $contest->id . '_' . date('Y_m_d_H_i') . '.xlsx';
        
        // Очищаем буфер вывода
        if (ob_get_length()) {
            ob_end_clean();
        }
        
        // Создаем временный файл
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        // Отправляем файл пользователю
        return Yii::$app->response->sendFile($tempFile, $filename, [
            'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'inline' => false
        ])->on(Response::EVENT_AFTER_SEND, function($event) use ($tempFile) {
            // Удаляем временный файл после отправки
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        });
    }

    /**
     * Generate PDF report using HTML to PDF conversion via wkhtmltopdf or alternative
     */
    private function generateHtmlPdfReport($id)
    {
        $contest = $this->findModel($id);
        
        // Получаем данные для отчета
        $applications = Application::find()
            ->where(['contest_id' => $id])
            ->andWhere(['status' => ['accepted', 'graded', 'completed']])
            ->orderBy(['nomination_id' => SORT_ASC, 'age_category_id' => SORT_ASC])
            ->all();
        
        // Если нет данных
        if (empty($applications)) {
            Yii::$app->session->setFlash('warning', 'Нет данных для формирования отчета.');
            return $this->redirect(['results', 'id' => $id]);
        }
        
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
        
        // Создаем HTML для PDF
        $html = $this->renderPartial('pdf-template', [
            'contest' => $contest,
            'groupedResults' => $groupedResults,
            'applications' => $applications,
        ]);
        
        // Имя файла
        $filename = 'results_contest_' . $contest->id . '_' . date('Y_m_d_H_i') . '.html';
        
        // Создаем временный HTML файл
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_') . '.html';
        file_put_contents($tempFile, $html);
        
        // Пытаемся использовать wkhtmltopdf если установлен
        $pdfFile = str_replace('.html', '.pdf', $tempFile);
        
        // Команда для конвертации HTML в PDF через wkhtmltopdf
        $command = "wkhtmltopdf --encoding 'UTF-8' --page-size A4 --orientation Landscape {$tempFile} {$pdfFile} 2>&1";
        
        $output = [];
        $returnVar = 0;
        exec($command, $output, $returnVar);
        
        // Если wkhtmltopdf не установлен, отдаем HTML файл
        if ($returnVar !== 0 || !file_exists($pdfFile)) {
            Yii::$app->session->setFlash('info', 'Для генерации PDF установите wkhtmltopdf. Сейчас предлагается HTML версия.');
            
            // Отправляем HTML файл
            return Yii::$app->response->sendFile($tempFile, $filename, [
                'mimeType' => 'text/html',
                'inline' => false
            ])->on(Response::EVENT_AFTER_SEND, function($event) use ($tempFile) {
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            });
        }
        
        // Отправляем PDF файл
        $pdfFilename = str_replace('.html', '.pdf', $filename);
        return Yii::$app->response->sendFile($pdfFile, $pdfFilename, [
            'mimeType' => 'application/pdf',
            'inline' => false
        ])->on(Response::EVENT_AFTER_SEND, function($event) use ($tempFile, $pdfFile) {
            // Удаляем временные файлы
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            if (file_exists($pdfFile)) {
                unlink($pdfFile);
            }
        });
    }

    /**
     * Альтернативный метод: создаем HTML страницу с кнопкой печати
     */
    public function actionPrintView($id)
    {
        $contest = $this->findModel($id);
        $applications = Application::find()
            ->where(['contest_id' => $id])
            ->andWhere(['status' => ['accepted', 'graded', 'completed']])
            ->orderBy(['nomination_id' => SORT_ASC, 'age_category_id' => SORT_ASC])
            ->all();
        
        return $this->render('print-view', [
            'contest' => $contest,
            'applications' => $applications,
        ]);
    }

    /**
     * Finds the Contest model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Contest::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }

    public function actionDocuments($id)
    {
        $contest = $this->findModel($id);
        
        $documents = GeneratedDocument::find()
            ->joinWith('application')
            ->where(['application.contest_id' => $id])
            ->all();
        
        return $this->render('documents', [
            'contest' => $contest,
            'documents' => $documents,
        ]);
    }

    public function actionGenerateWord($id)
{
    $contest = $this->findModel($id);
    $applications = Application::find()
        ->where(['contest_id' => $id])
        ->andWhere(['status' => ['accepted', 'graded', 'completed']])
        ->orderBy(['nomination_id' => SORT_ASC, 'age_category_id' => SORT_ASC])
        ->all();
    
    if (empty($applications)) {
        Yii::$app->session->setFlash('warning', 'Нет данных для формирования отчета.');
        return $this->redirect(['results', 'id' => $id]);
    }
    
    // Получаем результаты
    $results = [];
    foreach ($applications as $application) {
        $result = ContestResult::findOne(['application_id' => $application->id]);
        $results[] = [
            'application' => $application,
            'result' => $result
        ];
    }
    
    // Генерируем HTML для Word
    $html = $this->generateWordHtml($contest, $results);
    
    // Устанавливаем заголовки для скачивания как Word-документ
    $filename = 'report_' . $contest->id . '_' . date('Y_m_d_H_i') . '.doc';
    
    Yii::$app->response->format = Response::FORMAT_RAW;
    Yii::$app->response->headers->add('Content-Type', 'application/msword');
    Yii::$app->response->headers->add('Content-Disposition', 'attachment; filename="' . $filename . '"');
    Yii::$app->response->headers->add('Cache-Control', 'max-age=0');
    
    return $html;
}

private function generateWordHtml($contest, $results)
{
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Отчет конкурса: ' . htmlspecialchars($contest->name) . '</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 30px; text-align: right; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Итоговый отчет конкурса</h1>
        <h2>' . htmlspecialchars($contest->name) . '</h2>
        <p>Дата выгрузки: ' . date('d.m.Y H:i:s') . '</p>
    </div>';
    
    // Группируем по номинациям
    $grouped = [];
    foreach ($results as $item) {
        $nominationName = $item['application']->nomination->name ?? 'Без номинации';
        if (!isset($grouped[$nominationName])) {
            $grouped[$nominationName] = [];
        }
        $grouped[$nominationName][] = $item;
    }
    
    foreach ($grouped as $nominationName => $items) {
        $html .= '<h3>Номинация: ' . htmlspecialchars($nominationName) . '</h3>';
        $html .= '<table>
            <tr>
                <th>№</th>
                <th>ФИО участника</th>
                <th>Возрастная категория</th>
                <th>Название работы</th>
                <th>Учебное заведение</th>
                <th>Руководитель</th>
                <th>Итоговый балл</th>
                <th>Место</th>
                <th>Награда</th>
            </tr>';
        
        $counter = 1;
        foreach ($items as $item) {
            $application = $item['application'];
            $result = $item['result'];
            
            $html .= '<tr>
                <td>' . $counter . '</td>
                <td>' . htmlspecialchars($application->surname . ' ' . $application->name . 
                      ($application->patronymic ? ' ' . $application->patronymic : '')) . '</td>
                <td>' . htmlspecialchars($application->ageCategory->name ?? '') . '</td>
                <td>' . htmlspecialchars($application->work_name) . '</td>
                <td>' . htmlspecialchars($application->institution ?? '') . '</td>
                <td>' . htmlspecialchars($application->leader ?? '') . '</td>
                <td>' . ($result ? $result->final_score : '-') . '</td>
                <td>' . ($result ? $result->place : '-') . '</td>
                <td>' . ($result ? $this->getAwardText($result->award_type) : '-') . '</td>
            </tr>';
            $counter++;
        }
        
        $html .= '</table><br>';
    }
    
    $html .= '
    <div class="footer">
        <p>Документ сгенерирован автоматически системой ContestHub</p>
    </div>
</body>
</html>';
    
    return $html;
}

private function getAwardText($awardType)
{
    $awards = [
        'first' => 'Диплом I степени',
        'second' => 'Диплом II степени',
        'third' => 'Диплом III степени',
        'laureate' => 'Диплом лауреата',
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат участника',
    ];
    
    return $awards[$awardType] ?? $awardType ?? 'Не указано';
}
    
}