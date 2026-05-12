<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\Application;
use app\models\Contest;
use app\models\AgeCategory;
use app\models\Nomination;
use app\models\User;
use app\models\Notification;
use app\models\ExpertAssignment;
use app\models\Criteria;
use app\models\Evaluation;
use app\models\EvaluationSearch;
use app\models\ApplicationSearch;
use app\models\ReportTemplate;
use app\models\GeneratedDocument;
use app\models\EvaluationScore;
use app\models\ContestResult;
use app\models\ExpertAssignmentForm;
use DateTime; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\web\Response;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->is_admin == 1;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $stats = [
            'totalApplications' => Application::find()->count(),
            'totalContests' => Contest::find()->count(),
            'totalUsers' => User::find()->count(),
            'pendingApplications' => Application::find()->where(['status' => 'new'])->count(),
            'totalTemplates' => ReportTemplate::find()->count(),
            'totalGeneratedDocs' => GeneratedDocument::find()->count(),
            'totalResults' => ContestResult::find()->count(),
        ];

        return $this->render('index', [
            'stats' => $stats,
        ]);
    }

    // ==================== ЗАЯВКИ ====================
    public function actionApplications()
    {
        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('applications', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionApplicationView($id)
    {
        $model = $this->findApplicationModel($id);

        return $this->render('application-view', [
            'model' => $model,
        ]);
    }

    public function actionGenerateCertificate($application_id)
{
    $application = $this->findApplicationModel($application_id);
    if (!$application) {
        throw new NotFoundHttpException('Заявка не найдена.');
    }
    
    // Ищем шаблон сертификата
    $template = ReportTemplate::find()
        ->where(['type' => 'certificate'])
        ->andWhere(['or', ['contest_id' => $application->contest_id], ['contest_id' => null]])
        ->orderBy(['contest_id' => SORT_DESC]) // сначала ищем для конкретного конкурса
        ->one();
    
    if (!$template || !$template->fileExists()) {
        Yii::$app->session->setFlash('error', 'Шаблон сертификата не найден. Создайте шаблон в разделе "Шаблоны отчетов".');
        return $this->redirect(['application-view', 'id' => $application_id]);
    }
    
    // Получаем результат конкурса
    $contestResult = ContestResult::findByApplicationId($application_id);
    
    // Подготавливаем данные
    $data = [
        'participant_name' => $application->getFullName(),
        'work_name' => $application->work_name,
        'contest_name' => $application->contest->name,
        'contest_date' => Yii::$app->formatter->asDate($application->contest->end_date, 'php:d.m.Y'),
        'nomination' => $application->nomination->name ?? '',
        'age_category' => $application->ageCategory->name ?? '',
        'institution' => $application->institution ?? '',
        'leader' => $application->leader ?? '',
        'final_score' => $contestResult->final_score ?? '',
        'current_date' => date('d.m.Y'),
    ];
    
    // Генерируем документ
    if ($this->generateDocumentFromTemplate($template, $data, 'certificate', $application->id)) {
        Yii::$app->session->setFlash('success', 'Сертификат успешно сгенерирован');
    } else {
        Yii::$app->session->setFlash('error', 'Ошибка при генерации сертификата.');
    }
    
    return $this->redirect(['application-view', 'id' => $application_id]);
}

public function actionGenerateAlbum($application_id)
{
    $application = $this->findApplicationModel($application_id);
    if (!$application) {
        throw new NotFoundHttpException('Заявка не найдена.');
    }
    
    // Ищем шаблон альбома
    $template = ReportTemplate::find()
        ->where(['type' => 'album'])
        ->andWhere(['or', ['contest_id' => $application->contest_id], ['contest_id' => null]])
        ->orderBy(['contest_id' => SORT_DESC])
        ->one();
    
    Yii::info('Найден шаблон: ' . ($template ? 'да' : 'нет'), 'album');
    if ($template) {
        Yii::info('Путь к файлу: ' . $template->getAbsolutePath(), 'album');
        Yii::info('Файл существует: ' . ($template->fileExists() ? 'да' : 'нет'), 'album');
    }
    
    // Получаем результат конкурса
    $contestResult = ContestResult::findByApplicationId($application_id);
    
    // Подготавливаем данные для альбома
    $data = [
        'participant_name' => $application->getFullName(),
        'work_name' => $application->work_name,
        'contest_name' => $application->contest->name,
        'contest_date' => Yii::$app->formatter->asDate($application->contest->end_date, 'php:d.m.Y'),
        'nomination' => $application->nomination->name ?? '',
        'age_category' => $application->ageCategory->name ?? '',
        'institution' => $application->institution ?? '',
        'leader' => $application->leader ?? '',
        'final_score' => $contestResult->final_score ?? '',
        'place' => $contestResult->place ?? '',
        'award_type' => $this->getAwardTypeText($contestResult->award_type ?? ''),
        'current_date' => date('d.m.Y'),
    ];
    
    // Генерируем документ
    if ($this->generateDocumentFromTemplate($template, $data, 'album', $application->id)) {
        Yii::$app->session->setFlash('success', 'Альбом успешно сгенерирован');
    } else {
        Yii::$app->session->setFlash('error', 'Ошибка при генерации альбома.');
    }
    
    return $this->redirect(['application-view', 'id' => $application_id]);
}


private function generateDocumentFromTemplate($template, $data, $documentType, $applicationId)
{
    try {
        // Читаем шаблон
        $templatePath = $template->getAbsolutePath();
        Yii::info("Путь к шаблону: $templatePath", 'document');
        
        if (!file_exists($templatePath)) {
            Yii::error("Файл шаблона не найден: $templatePath", 'document');
            return false;
        }
        
        $content = file_get_contents($templatePath);
        if ($content === false) {
            Yii::error("Не удалось прочитать файл шаблона: $templatePath", 'document');
            return false;
        }
        
        // Заменяем плейсхолдеры
        foreach ($data as $key => $value) {
            $placeholder = "{{" . $key . "}}";
            $content = str_replace($placeholder, $value, $content);
        }
        
        // Сохраняем файл
        $folder = $documentType == 'certificate' ? 'certificates' : 
                  ($documentType == 'album' ? 'albums' : 'diplomas');
        
        $dir = Yii::getAlias("@webroot/uploads/{$folder}/");
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $filename = "{$folder}_{$applicationId}_" . time() . '.html';
        $filepath = $dir . $filename;
        $relativePath = "uploads/{$folder}/{$filename}";
        
        Yii::info("Сохранение в: $filepath", 'document');
        
        if (file_put_contents($filepath, $content) === false) {
            Yii::error("Не удалось сохранить файл: $filepath", 'document');
            return false;
        }
        
        // Сохраняем запись в generated_document
        $generatedDoc = new GeneratedDocument();
        $generatedDoc->application_id = $applicationId;
        $generatedDoc->document_type = $documentType;
        $generatedDoc->file_path = $relativePath;
        
        if (!$generatedDoc->save()) {
            Yii::error("Ошибка сохранения GeneratedDocument: " . 
                       print_r($generatedDoc->errors, true), 'document');
            return false;
        }
        
        // Уведомление участнику
        $application = Application::findOne($applicationId);
        if ($application) {
            Notification::create(
                $application->user_id,
                'Документ сгенерирован',
                "Ваш " . ($documentType == 'certificate' ? 'сертификат' : 
                         ($documentType == 'album' ? 'альбом' : 'диплом')) . 
                " по заявке '{$data['work_name']}' готов к скачиванию."
            );
        }
        
        Yii::info("Документ успешно создан: $relativePath", 'document');
        return true;
        
    } catch (\Exception $e) {
        Yii::error("Ошибка при генерации документа: " . $e->getMessage(), 'document');
        return false;
    }
}

private function getAwardTypeText($awardType)
{
    $awardTypes = [
        'first' => 'Диплом I степени',
        'second' => 'Диплом II степени',
        'third' => 'Диплом III степени',
        'laureate' => 'Диплом лауреата',
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат',
    ];
    
    return $awardTypes[$awardType] ?? $awardType;
}

    public function actionApplicationCreate()
    {
        $model = new Application();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            
            if ($model->file && $model->uploadFile($model->file) && $model->save()) {
                // Уведомление пользователю о создании заявки администратором
                Notification::create(
                    $model->user_id,
                    'Заявка создана администратором',
                    "Администратор создал заявку '{$model->work_name}' для вас на конкурс '{$model->contest->name}'"
                );
                
                Yii::$app->session->setFlash('success', 'Заявка успешно создана');
                return $this->redirect(['application-view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при создании заявки');
            }
        }

        $contests = Contest::find()->where(['status' => 1])->all();
        $users = User::find()->all();

        return $this->render('application-form', [
            'model' => $model,
            'contests' => $contests,
            'users' => $users,
        ]);
    }

    public function actionApplicationUpdate($id)
    {
        $model = $this->findApplicationModel($id);
        $oldStatus = $model->status;

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            
            if ($model->file) {
                $model->uploadFile($model->file);
            }
            
            if ($model->save()) {
                // Уведомление при изменении статуса
                if ($oldStatus !== $model->status) {
                    Notification::create(
                        $model->user_id,
                        'Статус заявки изменен администратором',
                        "Статус вашей заявки '{$model->work_name}' изменен на '{$model->getStatusLabel()}' администратором"
                    );
                }
                
                Yii::$app->session->setFlash('success', 'Заявка успешно обновлена');
                return $this->redirect(['application-view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при обновлении заявки');
            }
        }

        $contests = Contest::find()->where(['status' => 1])->all();
        $users = User::find()->all();

        return $this->render('application-form', [
            'model' => $model,
            'contests' => $contests,
            'users' => $users,
        ]);
    }

   public function actionApplicationUpdateStatus($id)
{
    Yii::$app->response->format = Response::FORMAT_JSON;
    
    $model = $this->findApplicationModel($id);
    
    if (Yii::$app->request->isPost) {
        $newStatus = Yii::$app->request->post('status');
        
        $validStatuses = [
            Application::STATUS_NEW, 
            Application::STATUS_UNDER_REVIEW, 
            Application::STATUS_BLOCKED, 
            Application::STATUS_GRADED,
            'completed' 
        ];
        
        if (!in_array($newStatus, $validStatuses)) {
            return ['success' => false, 'error' => 'Недопустимый статус'];
        }
        
        if ($newStatus === Application::STATUS_GRADED) {
            $evaluations = Evaluation::find()
                ->where(['application_id' => $id, 'status' => 'completed'])
                ->count();
            
            if ($evaluations === 0) {
                return ['success' => false, 'error' => 'Нельзя установить статус "Оценена" без завершенных оценок'];
            }
        }
        
        $oldStatus = $model->status;
        $model->status = $newStatus;
        
        if ($model->save(false)) { 
            if ($oldStatus !== $newStatus) {
                
                Notification::create(
                    $model->user_id,
                    'Статус заявки изменен',
                    "Статус вашей заявки '{$model->work_name}' изменен с '" . 
                    Application::getStatusLabels()[$oldStatus] . "' на '" . 
                    Application::getStatusLabels()[$newStatus] . "'"
                );
            }
            
            return ['success' => true, 'message' => 'Статус успешно обновлен'];
        } else {
            $errors = $model->getFirstErrors();
            return ['success' => false, 'error' => $errors ? reset($errors) : 'Ошибка сохранения'];
        }
    }
    
    return ['success' => false, 'error' => 'Некорректный запрос'];
}

public function actionApplicationUnblock($id)
{
    $model = $this->findApplicationModel($id);
    $model->status = Application::STATUS_NEW; // Используем константу
    
    if ($model->save()) {
        // Уведомление о разблокировке заявки
        Notification::create(
            $model->user_id,
            'Заявка разблокирована',
            "Ваша заявка '{$model->work_name}' была разблокирована администратором"
        );
        
        Yii::$app->session->setFlash('success', 'Заявка разблокирована');
    } else {
        Yii::$app->session->setFlash('error', 'Ошибка при разблокировке заявки');
    }

    return $this->redirect(['applications']);
}

public function actionApplicationDelete($id)
{
    $model = $this->findApplicationModel($id);
    
    if ($model->delete()) {
        // Уведомление об удалении заявки
        Notification::create(
            $model->user_id,
            'Заявка удалена администратором',
            "Ваша заявка '{$model->work_name}' была удалена администратором"
        );
        
        Yii::$app->session->setFlash('success', 'Заявка удалена');
    } else {
        Yii::$app->session->setFlash('error', 'Ошибка при удалении заявки');
    }

    return $this->redirect(['applications']);
}
    public function actionNominationsByContest($contest_id)
    {
        $nominations = Nomination::find()
            ->where(['contest_id' => $contest_id])
            ->select(['id', 'name'])
            ->asArray()
            ->all();
        
        $result = [];
        foreach ($nominations as $nomination) {
            $result[$nomination['id']] = $nomination['name'];
        }
        
        return $this->asJson($result);
    }

    public function actionAgeCategoriesByContest($contest_id)
    {
        $ageCategories = AgeCategory::find()
            ->where(['contest_id' => $contest_id])
            ->select(['id', 'name'])
            ->asArray()
            ->all();
        
        $result = [];
        foreach ($ageCategories as $category) {
            $result[$category['id']] = $category['name'];
        }
        
        return $this->asJson($result);
    }

    public function actionApplicationPublish($id)
    {
        $model = $this->findApplicationModel($id);
        $model->status = 'accepted';
        
        if ($model->save()) {
            // Уведомление о публикации заявки
            Notification::create(
                $model->user_id,
                'Заявка опубликована',
                "Ваша заявка '{$model->work_name}' была опубликована администратором"
            );
            
            Yii::$app->session->setFlash('success', 'Заявка опубликована');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при публикации заявки');
        }

        return $this->redirect(['applications']);
    }

    /**
     * Finds the Application model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Application the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findApplicationModel($id)
    {
        if (($model = Application::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Заявка не найдена.');
    }

    public function actionViewFile($id)
{
    $model = $this->findApplicationModel($id);
    if (!$model->file_path) {
        throw new NotFoundHttpException('Файл не найден.');
    }

    $filePath = Yii::getAlias('@webroot/' . $model->file_path);
    if (!file_exists($filePath)) {
        throw new NotFoundHttpException('Файл не найден.');
    }

    return Yii::$app->response->sendFile($filePath, $model->work_name . '.' . pathinfo($filePath, PATHINFO_EXTENSION), [
        'inline' => true
    ]);
}

public function actionDownload($id)
{
    $model = $this->findApplicationModel($id);
    if (!$model->file_path) {
        throw new NotFoundHttpException('Файл не найден.');
    }

    $filePath = Yii::getAlias('@webroot/' . $model->file_path);
    if (!file_exists($filePath)) {
        throw new NotFoundHttpException('Файл не найден.');
    }

    return Yii::$app->response->sendFile($filePath, $model->work_name . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
}

    // ==================== КОНКУРСЫ ====================
    public function actionContests()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Contest::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('contests', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContestCreate()
    {
        $model = new Contest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Уведомление всем пользователям о новом конкурсе
            $users = User::find()->where(['is_admin' => 0])->all();
            foreach ($users as $user) {
                Notification::create(
                    $user->id,
                    'Новый конкурс',
                    "Доступен новый конкурс: '{$model->name}'. Успейте подать заявку!"
                );
            }
            
            Yii::$app->session->setFlash('success', 'Конкурс создан');
            return $this->redirect(['contests']);
        }

        return $this->render('contest-form', [
            'model' => $model,
        ]);
    }

    public function actionContestUpdate($id)
{
    $model = Contest::findOne($id);
    if (!$model) {
        throw new NotFoundHttpException('Конкурс не найден.');
    }

    if ($model->load(Yii::$app->request->post())) {
        // Валидация дат
        $startDate = strtotime($model->start_date);
        $endDate = strtotime($model->end_date);
        
        if ($startDate === false || $endDate === false) {
            Yii::$app->session->setFlash('error', 'Неверный формат даты.');
            return $this->render('contest-form', ['model' => $model]);
        }
        
        if ($startDate > $endDate) {
            Yii::$app->session->setFlash('error', 'Дата начала не может быть позже даты окончания.');
            return $this->render('contest-form', ['model' => $model]);
        }
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Конкурс обновлен');
            return $this->redirect(['contests']);
        }
    }

    return $this->render('contest-form', [
        'model' => $model,
    ]);
}

    public function actionContestDelete($id)
    {
        $model = Contest::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Конкурс удален');
        }

        return $this->redirect(['contests']);
    }

    // ==================== ДОПОЛНИТЕЛЬНЫЕ МЕТОДЫ ДЛЯ КОНКУРСОВ ====================

    public function actionContestDownloadProgram($id, $format)
    {
        $contest = $this->findContestModel($id);
        
        // Проверяем, что конкурс завершен
        $today = new DateTime();
        $endDate = new DateTime($contest->end_date);
        if ($endDate >= $today) {
            Yii::$app->session->setFlash('error', 'Программа доступна только для завершенных конкурсов');
            return $this->redirect(['contests']);
        }
        
        // Логика генерации программы конкурса
        // Заголовки: Номер Номинация ФИО Название номера
        
        if ($format === 'excel') {
            // Генерация Excel
            return $this->generateExcelProgram($contest);
        } else {
            // Генерация Word
            return $this->generateWordProgram($contest);
        }
    }

    public function actionContestDownloadScores($id, $format)
    {
        $contest = $this->findContestModel($id);
        
        // Проверяем, что конкурс завершен
        $today = new DateTime();
        $endDate = new DateTime($contest->end_date);
        if ($endDate >= $today) {
            Yii::$app->session->setFlash('error', 'Оценочный лист доступен только для завершенных конкурсов');
            return $this->redirect(['contests']);
        }
        
        // Логика генерации оценочного листа
        // Заголовки: Номер ФИО / Название коллектива, руководитель Название номера и автор 
        // Мастерство по направлению Артистизм / Раскрытие худ. образа Сцен.культура Общий балл Примечания
        
        if ($format === 'excel') {
            // Генерация Excel
            return $this->generateExcelScores($contest);
        } else {
            // Генерация Word
            return $this->generateWordScores($contest);
        }
    }

    public function actionContestDeleteWorks($id)
    {
        $contest = $this->findContestModel($id);
        
        // Находим все заявки конкурса
        $applications = Application::find()->where(['contest_id' => $id])->all();
        
        $deletedFiles = 0;
        foreach ($applications as $application) {
            if ($application->file_path && file_exists(Yii::getAlias('@webroot/' . $application->file_path))) {
                unlink(Yii::getAlias('@webroot/' . $application->file_path));
                $application->file_path = null;
                $application->save(false); // Сохраняем без валидации
                $deletedFiles++;
            }
        }
        
        Yii::$app->session->setFlash('success', "Удалено {$deletedFiles} файлов работ. Заявки сохранены в системе.");
        return $this->redirect(['contests']);
    }

    /**
     * Находит модель конкурса по ID
     */
    protected function findContestModel($id)
    {
        if (($model = Contest::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Конкурс не найден.');
    }

    // Вспомогательные методы для генерации файлов (заглушки)
    private function generateExcelProgram($contest)
    {
        // Заглушка для генерации Excel
        Yii::$app->session->setFlash('info', 'Генерация Excel программы конкурса');
        return $this->redirect(['contests']);
    }

    private function generateWordProgram($contest)
    {
        // Заглушка для генерации Word
        Yii::$app->session->setFlash('info', 'Генерация Word программы конкурса');
        return $this->redirect(['contests']);
    }

    private function generateExcelScores($contest)
    {
        // Заглушка для генерации Excel оценочного листа
        Yii::$app->session->setFlash('info', 'Генерация Excel оценочного листа');
        return $this->redirect(['contests']);
    }

    private function generateWordScores($contest)
    {
        // Заглушка для генерации Word оценочного листа
        Yii::$app->session->setFlash('info', 'Генерация Word оценочного листа');
        return $this->redirect(['contests']);
    }

    // В AdminController добавьте эти методы:

public function actionCriteriaCreate($nomination_id = null)
{
    $model = new Criteria();
    
    if ($nomination_id) {
        $model->nomination_id = $nomination_id;
    }
    
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::$app->session->setFlash('success', 'Критерий создан');
        return $this->redirect(['criteria-index', 'nomination_id' => $model->nomination_id]);
    }
    
    $nominations = Nomination::find()->all();
    
    return $this->render('criteria-form', [
        'model' => $model,
        'nominations' => $nominations,
    ]);
}

public function actionCriteriaUpdate($id)
{
    $model = Criteria::findOne($id);
    if (!$model) {
        throw new NotFoundHttpException('Критерий не найден.');
    }
    
    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::$app->session->setFlash('success', 'Критерий обновлен');
        return $this->redirect(['criteria-index', 'nomination_id' => $model->nomination_id]);
    }
    
    $nominations = Nomination::find()->all();
    
    return $this->render('criteria-form', [
        'model' => $model,
        'nominations' => $nominations,
    ]);
}

public function actionCriteriaDelete($id)
{
    $model = Criteria::findOne($id);
    if (!$model) {
        throw new NotFoundHttpException('Критерий не найден.');
    }
    
    $nomination_id = $model->nomination_id;
    if ($model->delete()) {
        Yii::$app->session->setFlash('success', 'Критерий удален');
    }
    
    return $this->redirect(['criteria-index', 'nomination_id' => $nomination_id]);
}

    // ==================== ВОЗРАСТНЫЕ КАТЕГОРИИ ====================
    public function actionAgeCategories()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AgeCategory::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('age-categories', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAgeCategoryCreate()
    {
        $model = new AgeCategory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Возрастная категория создана');
            return $this->redirect(['age-categories']);
        }

        $contests = Contest::find()->where(['status' => 1])->all();

        return $this->render('age-category-form', [
            'model' => $model,
            'contests' => $contests,
        ]);
    }

    public function actionAgeCategoryUpdate($id)
    {
        $model = AgeCategory::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Возрастная категория не найдена.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Возрастная категория обновлена');
            return $this->redirect(['age-categories']);
        }

        $contests = Contest::find()->where(['status' => 1])->all();

        return $this->render('age-category-form', [
            'model' => $model,
            'contests' => $contests,
        ]);
    }

    public function actionAgeCategoryDelete($id)
    {
        $model = AgeCategory::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Возрастная категория удалена');
        }

        return $this->redirect(['age-categories']);
    }

    // ==================== НОМИНАЦИИ ====================
    public function actionNominations()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Nomination::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('nominations', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCriteriaIndex($nomination_id)
{
    $nomination = Nomination::findOne($nomination_id);
    if (!$nomination) {
        throw new NotFoundHttpException('Номинация не найдена.');
    }

    $dataProvider = new ActiveDataProvider([
        'query' => Criteria::find()->where(['nomination_id' => $nomination_id]),
        'pagination' => [
            'pageSize' => 20,
        ],
        'sort' => [
            'defaultOrder' => ['order' => SORT_ASC],
        ],
    ]);

    return $this->render('criteria-index', [
        'dataProvider' => $dataProvider,
        'nomination' => $nomination,
    ]);
}

    public function actionNominationCreate()
    {
        $model = new Nomination();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Номинация создана');
            return $this->redirect(['nominations']);
        }

        $contests = Contest::find()->where(['status' => 1])->all();

        return $this->render('nomination-form', [
            'model' => $model,
            'contests' => $contests,
        ]);
    }

    public function actionNominationUpdate($id)
    {
        $model = Nomination::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Номинация не найдена.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Номинация обновлена');
            return $this->redirect(['nominations']);
        }

        $contests = Contest::find()->where(['status' => 1])->all();

        return $this->render('nomination-form', [
            'model' => $model,
            'contests' => $contests,
        ]);
    }

    public function actionNominationDelete($id)
    {
        $model = Nomination::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Номинация удалена');
        }

        return $this->redirect(['nominations']);
    }

    // ==================== ЭКСПЕРТЫ ====================
    public function actionExpertCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_ADMIN_CREATE;

        if ($model->load(Yii::$app->request->post())) {
            // Пароль будет установлен автоматически в beforeSave через password_input
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Эксперт создан');
                return $this->redirect(['experts']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при создании эксперта: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        return $this->render('expert-form', [
            'model' => $model,
        ]);
    }

    public function actionExpertAssignment()
{
    $model = new ExpertAssignmentForm();
    $experts = User::find()->where(['is_expert' => 1])->all();
    $contests = Contest::find()->all();
    
    if ($model->load(Yii::$app->request->post())) {
        // Удаляем старые назначения для этой комбинации
        ExpertAssignment::deleteAll([
            'contest_id' => $model->contest_id,
            'nomination_id' => $model->nomination_id,
            'age_category_id' => $model->age_category_id
        ]);
        
        // Создаем новые назначения для каждого выбранного эксперта
        foreach ($model->expert_ids as $expertId) {
            $assignment = new ExpertAssignment();
            $assignment->expert_id = $expertId;
            $assignment->contest_id = $model->contest_id;
            $assignment->nomination_id = $model->nomination_id;
            $assignment->age_category_id = $model->age_category_id;
            $assignment->save();
        }
        
        Yii::$app->session->setFlash('success', 'Эксперты назначены');
        return $this->refresh();
    }
    
    // Получаем текущие назначения с группировкой
    $assignments = $this->getGroupedAssignments();
    
    return $this->render('expert-assignment', [
        'model' => $model,
        'experts' => $experts,
        'contests' => $contests,
        'assignments' => $assignments,
    ]);
}

private function getGroupedAssignments()
{
    $assignments = ExpertAssignment::find()
        ->joinWith(['expert', 'contest', 'nomination', 'ageCategory'])
        ->all();
    
    $grouped = [];
    foreach ($assignments as $assignment) {
        $key = $assignment->contest_id . '_' . $assignment->nomination_id . '_' . $assignment->age_category_id;
        
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'id' => $assignment->id,
                'contest_name' => $assignment->contest->name,
                'nomination_name' => $assignment->nomination->name,
                'age_category_name' => $assignment->ageCategory->name,
                'experts' => []
            ];
        }
        
        $grouped[$key]['experts'][] = $assignment->expert->login;
    }
    
    return array_values($grouped);
}


public function actionGetNominations($contest_id)
{
    $nominations = Nomination::find()
        ->where(['contest_id' => $contest_id])
        ->all();
    
    $result = [];
    foreach ($nominations as $nomination) {
        $result[$nomination->id] = $nomination->name;
    }
    
    return $this->asJson($result);
}

public function actionGetAgeCategories($contest_id)
{
    $categories = AgeCategory::find()
        ->where(['contest_id' => $contest_id])
        ->all();
    
    $result = [];
    foreach ($categories as $category) {
        $result[$category->id] = $category->name;
    }
    
    return $this->asJson($result);
}

    public function actionExpertUpdate($id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Эксперт не найден.');
        }

        $model->scenario = User::SCENARIO_ADMIN_UPDATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Эксперт обновлен');
            return $this->redirect(['experts']);
        }

        return $this->render('expert-form', [
            'model' => $model,
        ]);
    }

    public function actionExperts()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['is_admin' => 0]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('experts', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExpertAssignments($expert_id)
    {
        $expert = User::findOne($expert_id);
        if (!$expert) {
            throw new NotFoundHttpException('Эксперт не найден.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => ExpertAssignment::find()->where(['expert_id' => $expert_id]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('expert-assignments', [
            'expert' => $expert,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExpertAssign($id)
    {
        $expert = User::findOne($id);
        if (!$expert) {
            throw new NotFoundHttpException('Эксперт не найден.');
        }

        $model = new ExpertAssignment();
        $model->expert_id = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Эксперт назначен');
            return $this->redirect(['expert-assignments', 'expert_id' => $id]);
        }

        $contests = Contest::find()->where(['status' => 1])->all();
        $nominations = Nomination::find()->all();
        $ageCategories = AgeCategory::find()->all();

        return $this->render('expert-assign', [
            'model' => $model,
            'expert' => $expert,
            'contests' => $contests,
            'nominations' => $nominations,
            'ageCategories' => $ageCategories,
        ]);
    }

    public function actionExpertBlock($id)
    {
        $model = User::findOne($id);
        if ($model) {
            $model->is_blocked = 1;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Эксперт заблокирован');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при блокировке эксперта');
            }
        }

        return $this->redirect(['experts']);
    }

    public function actionExpertUnblock($id)
    {
        $model = User::findOne($id);
        if ($model) {
            $model->is_blocked = 0;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Эксперт разблокирован');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при разблокировке эксперта');
            }
        }

        return $this->redirect(['experts']);
    }

    // ==================== ПОЛЬЗОВАТЕЛИ ====================
    public function actionUserCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_ADMIN_CREATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Уведомление новому пользователю
            Notification::create(
                $model->id,
                'Добро пожаловать!',
                "Ваш аккаунт был создан администратором. Добро пожаловать в систему конкурсов!"
            );
            
            Yii::$app->session->setFlash('success', 'Пользователь успешно создан');
            return $this->redirect(['users']);
        }

        return $this->render('user-form', [
            'model' => $model,
        ]);
    }

    public function actionUserUpdate($id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        $model->scenario = User::SCENARIO_ADMIN_UPDATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Пользователь обновлен');
            return $this->redirect(['users']);
        }

        return $this->render('user-form', [
            'model' => $model,
        ]);
    }
    public function actionUsers()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('users', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUserBlock($id)
{
    $model = User::findOne($id);
    if (!$model) {
        throw new NotFoundHttpException('Пользователь не найден.');
    }
    
    if ($model->id === Yii::$app->user->id) {
        Yii::$app->session->setFlash('error', 'Вы не можете заблокировать свой собственный аккаунт.');
        return $this->redirect(['users']);
    }
    
    $model->is_blocked = 1;
    if ($model->save()) { // Изменено с save(false) на save()
        Notification::create(
            $model->id,
            'Аккаунт заблокирован',
            'Ваш аккаунт был заблокирован администратором. Вы не можете войти в систему.'
        );
        Yii::$app->session->setFlash('success', 'Пользователь успешно заблокирован');
    } else {
        Yii::$app->session->setFlash('error', 'Ошибка при блокировке пользователя: ' . implode(', ', $model->getFirstErrors()));
    }
    
    return $this->redirect(['users']);
}

    public function actionUserUnblock($id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        $model->is_blocked = 0;
        if ($model->save(false)) {
            Notification::create(
                $model->id,
                'Аккаунт разблокирован',
                'Ваш аккаунт был разблокирован администратором. Теперь вы можете войти в систему.'
            );
            
            Yii::$app->session->setFlash('success', 'Пользователь успешно разблокирован');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при разблокировке пользователя');
        }

        return $this->redirect(['users']);
    }  

    public function actionUserDelete($id)
    {
        $model = User::findOne($id);
        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', 'Пользователь удален');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении пользователя');
        }

        return $this->redirect(['users']);
    }


    // ==================== УВЕДОМЛЕНИЯ ====================
    public function actionNotifications()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Notification::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('notifications', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNotificationCreate()
    {
        $model = new Notification();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Уведомление успешно создано');
            return $this->redirect(['notifications']);
        }

        $users = User::find()->all();

        return $this->render('notification-form', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    public function actionNotificationUpdate($id)
    {
        $model = Notification::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Уведомление не найдено.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Уведомление успешно обновлено');
            return $this->redirect(['notifications']);
        }

        $users = User::find()->all();

        return $this->render('notification-form', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    public function actionNotificationDelete($id)
    {
        $model = Notification::findOne($id);
        if ($model) {
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'Уведомление удалено');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при удалении уведомления');
            }
        }

        return $this->redirect(['notifications']);
    }

    // ==================== МОДЕРАЦИЯ ОЦЕНОК ====================
    public function actionEvaluations()
{
    $statusFilter = Yii::$app->request->get('status', 'all');
    
    $query = Evaluation::find()
        ->with(['application', 'expert', 'application.contest'])
        ->orderBy(['updated_at' => SORT_DESC]);
    
    // Применяем фильтр по статусу
    if ($statusFilter !== 'all') {
        if ($statusFilter === 'draft') {
            $query->andWhere(['status' => Evaluation::STATUS_DRAFT]);
        } elseif ($statusFilter === 'completed') {
            $query->andWhere(['status' => 'completed']);
        }
    }
    
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);
    
    return $this->render('evaluations', [
        'dataProvider' => $dataProvider,
    ]);
}



    public function actionEvaluationReset($id)
    {
        $model = Evaluation::findOne($id);
        if ($model) {
            $model->status = 'draft';
            $model->save();
            
            // Уведомление эксперту о сбросе оценки
            Notification::create(
                $model->expert_id,
                'Оценка сброшена',
                "Ваша оценка заявки '{$model->application->work_name}' была сброшена администратором"
            );
            
            Yii::$app->session->setFlash('success', 'Статус оценки сброшен');
        }

        return $this->redirect(['evaluations']);
    }

    public function actionEvaluationView($id)
    {
        $model = Evaluation::findOne($id); // ЗАМЕНА НА ПРЯМОЙ ПОИСК
        if (!$model) {
            throw new NotFoundHttpException('Оценка не найдена.');
        }
        
        return $this->render('evaluation-view', [
            'model' => $model,
        ]);
    }

    // ==================== ШАБЛОНЫ ОТЧЕТОВ ====================
public function actionTemplates()
{
    $dataProvider = new ActiveDataProvider([
        'query' => ReportTemplate::find(),
        'sort' => [
            'defaultOrder' => ['created_at' => SORT_DESC],
        ],
    ]);

    return $this->render('templates', [
        'dataProvider' => $dataProvider,
    ]);
}

public function actionTemplateView($id)
{
    $model = $this->findTemplateModel($id);

    return $this->render('template-view', [
        'model' => $model,
    ]);
}

public function actionTemplateCreate()
{
    $model = new ReportTemplate();

    if ($model->load(Yii::$app->request->post())) {
        $model->templateFile = UploadedFile::getInstance($model, 'templateFile');
        
        if ($model->templateFile && $model->upload()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Шаблон успешно создан.');
                return $this->redirect(['template-view', 'id' => $model->id]);
            }
        } else {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Шаблон успешно создан (без файла).');
                return $this->redirect(['template-view', 'id' => $model->id]);
            }
        }
        
        Yii::$app->session->setFlash('error', 'Ошибка при создании шаблона: ' . implode(', ', $model->getFirstErrors()));
    }

    $contests = Contest::find()->all();
    $types = ReportTemplate::getAllTypes();

    return $this->render('template-form', [
        'model' => $model,
        'contests' => $contests,
        'types' => $types,
    ]);
}

public function actionTemplateUpdate($id)
{
    $model = $this->findTemplateModel($id);
    $oldFile = $model->template_file;

    if ($model->load(Yii::$app->request->post())) {
        $model->templateFile = UploadedFile::getInstance($model, 'templateFile');
        
        if ($model->templateFile) {
            if ($model->upload()) {
                // Удаляем старый файл
                if ($oldFile && file_exists($model->getAbsolutePath())) {
                    unlink(Yii::getAlias('@webroot/uploads/templates/' . $oldFile));
                }
            }
        }
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Шаблон успешно обновлен.');
            return $this->redirect(['template-view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при обновлении шаблона: ' . implode(', ', $model->getFirstErrors()));
        }
    }

    $contests = Contest::find()->all();
    $types = ReportTemplate::getAllTypes();

    return $this->render('template-form', [
        'model' => $model,
        'contests' => $contests,
        'types' => $types,
    ]);
}

public function actionTemplateDelete($id)
{
    $model = $this->findTemplateModel($id);
    $filePath = $model->getAbsolutePath();
    
    if ($model->delete()) {
        // Удаляем файл
        if ($filePath && file_exists($filePath)) {
            unlink($filePath);
        }
        Yii::$app->session->setFlash('success', 'Шаблон успешно удален.');
    }

    return $this->redirect(['templates']);
}

public function actionTemplateDownload($id)
{
    $model = $this->findTemplateModel($id);
    
    if (!$model->fileExists()) {
        throw new NotFoundHttpException('Файл шаблона не найден.');
    }
    
    $filePath = $model->getAbsolutePath();
    $fileName = $model->template_file;
    
    return Yii::$app->response->sendFile($filePath, $fileName, [
        'inline' => false,
        'mimeType' => 'text/html'
    ]);
}

protected function findTemplateModel($id)
{
    if (($model = ReportTemplate::findOne($id)) !== null) {
        return $model;
    }
    throw new NotFoundHttpException('Шаблон не найден.');
}

    // ==================== СГЕНЕРИРОВАННЫЕ ДОКУМЕНТЫ ====================
    public function actionGeneratedDocuments($contest_id = null)
    {
        $query = GeneratedDocument::find()->joinWith('application');
        
        if ($contest_id) {
            $query->where(['application.contest_id' => $contest_id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['generated_at' => SORT_DESC],
            ],
        ]);

        $contests = Contest::find()->all();

        return $this->render('generated-documents', [
            'dataProvider' => $dataProvider,
            'contests' => $contests,
            'selectedContestId' => $contest_id,
        ]);
    }

    public function actionGeneratedDocumentDelete($id)
    {
        $model = GeneratedDocument::findOne($id);
        
        if (!$model) {
            throw new NotFoundHttpException('Документ не найден.');
        }
        
        $filePath = $model->getAbsolutePath();
        
        if ($model->delete()) {
            // Удаляем файл
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            Yii::$app->session->setFlash('success', 'Документ успешно удален.');
        }

        return $this->redirect(['generated-documents']);
    }

    // ==================== ИТОГОВЫЕ РЕЗУЛЬТАТЫ ====================
    public function actionContestResults()
    {
        $contests = Contest::find()->all();
        
        return $this->render('contest-results', [
            'contests' => $contests,
        ]);
    }

    public function actionContestResultView($contest_id)
    {
        $contest = $this->findContestModel($contest_id);
        $results = ContestResult::findByContestId($contest_id);
        
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

        return $this->render('contest-result-view', [
            'contest' => $contest,
            'groupedResults' => $groupedResults,
        ]);
    }

    public function actionContestResultManage($contest_id)
    {
        $contest = $this->findContestModel($contest_id);
        
        // Получаем все заявки конкурса
        $applications = Application::find()
            ->where(['contest_id' => $contest_id, 'status' => 'accepted'])
            ->all();
        
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $resultsData = Yii::$app->request->post('results', []);
                
                foreach ($resultsData as $applicationId => $data) {
                    $result = ContestResult::findByApplicationId($applicationId);
                    
                    if (!$result) {
                        $result = new ContestResult();
                        $result->application_id = $applicationId;
                    }
                    
                    $result->final_score = $data['final_score'] ?? null;
                    $result->place = $data['place'] ?? null;
                    $result->award_type = $data['award_type'] ?? null;
                    
                    if (!$result->save()) {
                        throw new \Exception('Ошибка сохранения результата для заявки ' . $applicationId);
                    }
                    
                    // Обновляем статус заявки
                    $application = Application::findOne($applicationId);
                    if ($application) {
                        $application->status = 'completed';
                        $application->save(false);
                    }
                }
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Результаты успешно сохранены.');
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Ошибка сохранения результатов: ' . $e->getMessage());
            }
            
            return $this->refresh();
        }

        return $this->render('contest-result-manage', [
            'contest' => $contest,
            'applications' => $applications,
            'awardTypes' => [
                'first' => 'Диплом I степени',
                'second' => 'Диплом II степени',
                'third' => 'Диплом III степени',
                'laureate' => 'Диплом лауреата',
                'diploma' => 'Диплом',
                'certificate' => 'Сертификат участника',
            ],
        ]);
    }

    public function actionGenerateDiploma($application_id)
{
    $application = $this->findApplicationModel($application_id);
    if (!$application) {
        throw new NotFoundHttpException('Заявка не найдена.');
    }
    
    // Ищем шаблон диплома
    $template = ReportTemplate::find()
        ->where(['type' => 'diploma'])
        ->andWhere(['or', ['contest_id' => $application->contest_id], ['contest_id' => null]])
        ->orderBy(['contest_id' => SORT_DESC])
        ->one();
    
    if (!$template || !$template->fileExists()) {
        Yii::$app->session->setFlash('error', 'Шаблон диплома не найден. Создайте шаблон в разделе "Шаблоны отчетов".');
        return $this->redirect(['application-view', 'id' => $application_id]);
    }
    
    // Получаем или создаем результат конкурса
    $contestResult = ContestResult::findByApplicationId($application_id);
    if (!$contestResult) {
        $contestResult = new ContestResult();
        $contestResult->application_id = $application_id;
        
        // Рассчитываем средний балл
        $evaluations = Evaluation::find()
            ->where(['application_id' => $application_id, 'status' => 'completed'])
            ->all();
        
        if (!empty($evaluations)) {
            $totalScore = 0;
            foreach ($evaluations as $evaluation) {
                $totalScore += $evaluation->total_score;
            }
            $averageScore = round($totalScore / count($evaluations), 2);
            $contestResult->final_score = $averageScore;
        }
        
        $contestResult->award_type = 'diploma';
        $contestResult->save(false);
    }
    
    // Подготавливаем данные
    $data = [
        'participant_name' => $application->getFullName(),
        'work_name' => $application->work_name,
        'contest_name' => $application->contest->name,
        'contest_date' => Yii::$app->formatter->asDate($application->contest->end_date, 'php:d.m.Y'),
        'nomination' => $application->nomination->name ?? '',
        'age_category' => $application->ageCategory->name ?? '',
        'institution' => $application->institution ?? '',
        'leader' => $application->leader ?? '',
        'final_score' => $contestResult->final_score ?? '',
        'place' => $contestResult->place ?? '',
        'award_type' => $this->getAwardTypeText($contestResult->award_type ?? 'diploma'),
        'current_date' => date('d.m.Y'),
    ];
    
    // Генерируем документ
    if ($this->generateDocumentFromTemplate($template, $data, 'diploma', $application->id)) {
        Yii::$app->session->setFlash('success', 'Диплом успешно сгенерирован');
    } else {
        Yii::$app->session->setFlash('error', 'Ошибка при генерации диплома.');
    }
    
    return $this->redirect(['application-view', 'id' => $application_id]);
}

    private function generateDocument($contestResult)
{
    $app = $contestResult->application;
    $contest = $app->contest;
    
    // Определяем тип документа
    $isCertificate = in_array($contestResult->award_type, ['certificate']);
    $templateType = $isCertificate ? 'certificate' : 'diploma';
    $documentType = $isCertificate ? 'certificate' : 'diploma';
    
    // Ищем шаблон
    $template = ReportTemplate::findByTypeAndContest($templateType, $contest->id);
    if (!$template) {
        $template = ReportTemplate::findByTypeAndContest($templateType, null);
    }
    
    if (!$template || !$template->fileExists()) {
        // Создаем простой HTML если шаблона нет
        return $this->createSimpleDocument($contestResult, $documentType);
    }
    
    // Подготавливаем данные
    $awardTypes = [
        'first' => 'Диплом I степени',
        'second' => 'Диплом II степени', 
        'third' => 'Диплом III степени',
        'laureate' => 'Диплом лауреата',
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат участника',
    ];
    
    $data = [
        'participant_name' => $app->getFullName(),
        'work_name' => $app->work_name,
        'contest_name' => $contest->name,
        'contest_date' => Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y'),
        'nomination' => $app->nomination->name ?? '',
        'age_category' => $app->ageCategory->name ?? '',
        'institution' => $app->institution ?? '',
        'leader' => $app->leader ?? '',
        'final_score' => $contestResult->final_score ?? '',
        'place' => $contestResult->place ? $this->getPlaceText($contestResult->place) : '',
        'award_type' => $awardTypes[$contestResult->award_type] ?? $contestResult->award_type ?? '',
        'current_date' => date('d.m.Y'),
    ];
    
    // Генерируем документ из шаблона
    return $this->generateFromTemplate($template, $data, $documentType, $app->id);
}

private function generateFromTemplate($template, $data, $documentType, $applicationId)
{
    // Читаем шаблон
    $content = file_get_contents($template->getAbsolutePath());
    
    // Заменяем плейсхолдеры
    foreach ($data as $key => $value) {
        $content = str_replace("{{{$key}}}", $value, $content);
        // Также поддерживаем формат без фигурных скобок
        $content = str_replace("{{" . $key . "}}", $value, $content);
    }
    
    // Сохраняем файл
    $folder = $documentType == 'certificate' ? 'certificates' : 'diplomas';
    $dir = Yii::getAlias("@webroot/uploads/{$folder}/");
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $filename = "{$folder}_{$applicationId}_" . time() . '.html';
    $filepath = $dir . $filename;
    $relativePath = "uploads/{$folder}/{$filename}";
    
    file_put_contents($filepath, $content);
    
    // Сохраняем запись в generated_document
    $generatedDoc = new GeneratedDocument();
    $generatedDoc->application_id = $applicationId;
    $generatedDoc->document_type = $documentType;
    $generatedDoc->file_path = $relativePath;
    
    if ($generatedDoc->save()) {
        // Уведомление участнику
        Notification::create(
            Application::findOne($applicationId)->user_id,
            'Документ сгенерирован',
            "Ваш {$data['award_type']} по заявке '{$data['work_name']}' готов к скачиванию."
        );
        
        return true;
    }
    
    return false;
}

private function createSimpleDocument($contestResult, $documentType)
{
    $app = $contestResult->application;
    
    // Простой HTML документ
    $html = '<!DOCTYPE html><html><body>';
    $html .= '<h1>' . ($documentType == 'certificate' ? 'Сертификат' : 'Диплом') . '</h1>';
    $html .= '<p>Участник: ' . $app->getFullName() . '</p>';
    $html .= '<p>Работа: ' . $app->work_name . '</p>';
    $html .= '<p>Конкурс: ' . $app->contest->name . '</p>';
    $html .= '</body></html>';
    
    // Сохраняем файл
    $folder = $documentType == 'certificate' ? 'certificates' : 'diplomas';
    $dir = Yii::getAlias("@webroot/uploads/{$folder}/");
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $filename = "{$folder}_{$app->id}_" . time() . '.html';
    $filepath = $dir . $filename;
    $relativePath = "uploads/{$folder}/{$filename}";
    
    file_put_contents($filepath, $html);
    
    // Сохраняем запись
    $generatedDoc = new GeneratedDocument();
    $generatedDoc->application_id = $app->id;
    $generatedDoc->document_type = $documentType;
    $generatedDoc->file_path = $relativePath;
    
    return $generatedDoc->save();
}

private function getPlaceText($place)
{
    $endings = ['', 'первое', 'второе', 'третье'];
    if (isset($endings[$place])) {
        return $endings[$place] . ' место';
    }
    return $place . '-е место';
}

    // Заглушки для других методов генерации
    private function generateWordDiploma($template, $contestResult) { return false; }
    
    private function generateHtmlDocument($template, $data, $documentType, $applicationId)
{
    // Создаем директорию для документов
    $folder = $documentType == 'certificate' ? 'certificates' : 'diplomas';
    $dir = Yii::getAlias("@webroot/uploads/{$folder}/");
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $filename = "{$folder}_{$applicationId}_" . time() . '.html';
    $filepath = $dir . $filename;
    $relativePath = "uploads/{$folder}/{$filename}";
    
    // Создаем простой HTML документ
    $html = '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($data['award_type']) . ' - ' . htmlspecialchars($data['participant_name']) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .document-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border: 3px solid #4a6fa5;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4a6fa5;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header h2 {
            color: #333;
            font-size: 24px;
            margin-top: 0;
        }
        .separator {
            border: 1px solid #4a6fa5;
            margin: 30px 0;
        }
        .participant-info {
            text-align: center;
            margin: 30px 0;
        }
        .participant-name {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            color: #2c3e50;
        }
        .work-details {
            font-size: 18px;
            margin: 20px 0;
        }
        .award-info {
            font-size: 20px;
            font-weight: bold;
            color: #e74c3c;
            margin: 30px 0;
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature {
            text-align: left;
        }
        .date {
            text-align: right;
        }
        .print-button {
            text-align: center;
            margin-top: 30px;
        }
        .print-button button {
            background-color: #4a6fa5;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-button button:hover {
            background-color: #3a5a80;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="document-container">
        <div class="header">
            <h1>' . htmlspecialchars($data['award_type']) . '</h1>
            <h2>' . htmlspecialchars($data['contest_name']) . '</h2>
        </div>
        
        <div class="separator"></div>
        
        <div class="participant-info">
            <p style="font-size: 18px;">Награждается</p>
            <div class="participant-name">' . htmlspecialchars($data['participant_name']) . '</div>
            
            <div class="work-details">
                <p>за участие в номинации<br>
                <strong>' . htmlspecialchars($data['nomination']) . '</strong></p>
                
                <p>с работой<br>
                <strong>"' . htmlspecialchars($data['work_name']) . '"</strong></p>';
                
    if (!empty($data['age_category'])) {
        $html .= '<p>Возрастная категория: <strong>' . htmlspecialchars($data['age_category']) . '</strong></p>';
    }
    
    if (!empty($data['institution'])) {
        $html .= '<p>Учреждение: <strong>' . htmlspecialchars($data['institution']) . '</strong></p>';
    }
    
    if (!empty($data['leader'])) {
        $html .= '<p>Руководитель: <strong>' . htmlspecialchars($data['leader']) . '</strong></p>';
    }
    
    $html .= '</div>';
    
    if (!empty($data['place'])) {
        $html .= '<div class="award-info">' . htmlspecialchars($data['place']) . '</div>';
    }
    
    if (!empty($data['final_score']) && $data['final_score'] != 'Нет оценки') {
        $html .= '<p style="text-align: center; font-size: 16px;">Итоговый балл: <strong>' . htmlspecialchars($data['final_score']) . '</strong></p>';
    }
    
    $html .= '</div>
        
        <div class="separator"></div>
        
        <div class="footer">
            <div class="signature">
                <p>Директор конкурса</p>
                <p style="margin-top: 50px;">_________________</p>
            </div>
            <div class="date">
                <p>' . htmlspecialchars($data['current_date']) . '</p>
            </div>
        </div>
        
        <div class="print-button">
            <button onclick="window.print()">Распечатать документ</button>
        </div>
    </div>
    
    <script>
        // Автоматическая печать при открытии
        window.onload = function() {
            // Можно раскомментировать для автоматической печати
            // window.print();
        };
    </script>
</body>
</html>';
    
    // Сохраняем HTML файл
    file_put_contents($filepath, $html);
    
    return $relativePath;
}

    // ==================== ЭКСПОРТ РЕЗУЛЬТАТОВ ====================
    public function actionExportResults($contest_id, $format = 'excel')
{
    $contest = $this->findContestModel($contest_id);
    $results = ContestResult::find()
        ->joinWith(['application'])
        ->where(['application.contest_id' => $contest_id])
        ->orderBy(['place' => SORT_ASC])
        ->all();
    
    if ($format === 'excel') {
        return $this->exportResultsToExcel($contest, $results);
    } else if ($format === 'word') {
        return $this->exportResultsToWord($contest, $results);
    } else if ($format === 'pdf') {
        return $this->exportResultsToPdf($contest, $results);
    }
}

// Новый метод exportResultsToExcel
private function exportResultsToExcel($contest, $results)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Заголовки
    $sheet->setCellValue('A1', 'Итоговые результаты конкурса: ' . $contest->name);
    $sheet->setCellValue('A2', 'Дата выгрузки: ' . date('d.m.Y H:i'));
    $sheet->setCellValue('A4', '№');
    $sheet->setCellValue('B4', 'ФИО участника');
    $sheet->setCellValue('C4', 'Номинация');
    $sheet->setCellValue('D4', 'Возрастная категория');
    $sheet->setCellValue('E4', 'Название работы');
    $sheet->setCellValue('F4', 'Итоговый балл');
    $sheet->setCellValue('G4', 'Место');
    $sheet->setCellValue('H4', 'Награда');
    
    // Данные
    $row = 5;
    foreach ($results as $index => $result) {
        $app = $result->application;
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $app->getFullName());
        $sheet->setCellValue('C' . $row, $app->nomination->name ?? '');
        $sheet->setCellValue('D' . $row, $app->ageCategory->name ?? '');
        $sheet->setCellValue('E' . $row, $app->work_name);
        $sheet->setCellValue('F' . $row, $result->final_score);
        $sheet->setCellValue('G' . $row, $result->place);
        
        $awardLabels = [
            'first' => 'Диплом I степени',
            'second' => 'Диплом II степени',
            'third' => 'Диплом III степени',
            'laureate' => 'Диплом лауреата',
            'diploma' => 'Диплом',
            'certificate' => 'Сертификат участника',
        ];
        $sheet->setCellValue('H' . $row, $awardLabels[$result->award_type] ?? $result->award_type ?? '');
        
        $row++;
    }
    
    // Авторазмер колонок
    foreach (range('A', 'H') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // Стили
    $sheet->getStyle('A1:H1')->getFont()->setBold(true);
    $sheet->getStyle('A4:H4')->getFont()->setBold(true);
    $sheet->getStyle('A4:H4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0E0E0');
    
    $filename = 'results_' . $contest->id . '_' . date('Ymd_His') . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Добавьте эти методы для Word и PDF (заглушки)
private function exportResultsToWord($contest, $results)
{
    Yii::$app->session->setFlash('info', 'Экспорт в Word в разработке.');
    return $this->redirect(['contest-result-view', 'contest_id' => $contest->id]);
}

private function exportResultsToPdf($contest, $results)
{
    Yii::$app->session->setFlash('info', 'Экспорт в PDF в разработке.');
    return $this->redirect(['contest-result-view', 'contest_id' => $contest->id]);
}

    // ==================== СТАТИСТИКА ПО НОМИНАЦИЯМ ====================
   public function actionNominationStats($contest_id)
{
    $contest = $this->findContestModel($contest_id);
    $nominations = Nomination::find()->where(['contest_id' => $contest_id])->all();
    
    $stats = [];
    foreach ($nominations as $nomination) {
        $total = Application::find()
            ->where(['nomination_id' => $nomination->id, 'status' => ['accepted', 'graded', 'completed']])
            ->count();
        
        $stats[] = [
            'nomination' => $nomination,
            'total' => $total,
            'max_participants' => $nomination->max_participants,
            'percentage' => $nomination->max_participants > 0 ? 
                round(($total / $nomination->max_participants) * 100, 1) : 0,
        ];
    }

    return $this->render('nomination-stats', [
        'contest' => $contest,
        'stats' => $stats,
    ]);
}

    public function actionBatchCreateResults()
{
    if (Yii::$app->request->isPost) {
        $applicationIds = explode(',', Yii::$app->request->post('application_ids'));
        $count = 0;
        
        foreach ($applicationIds as $id) {
            $application = Application::findOne($id);
            if ($application) {
                $result = ContestResult::findByApplicationId($application->id);
                if (!$result) {
                    $result = new ContestResult();
                    $result->application_id = $application->id;
                    $result->save(false);
                    $count++;
                }
            }
        }
        
        Yii::$app->session->setFlash('success', "Создано {$count} результатов");
    }
    
    return $this->redirect(['applications']);
}

    public function actionBatchGenerateDiplomas()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!Yii::$app->request->isPost) {
            return ['success' => false, 'error' => 'Некорректный запрос'];
        }
        
        $applicationIds = explode(',', Yii::$app->request->post('application_ids', ''));
        $successCount = 0;
        
        foreach ($applicationIds as $id) {
            $id = (int)trim($id);
            if ($id > 0) {
                try {
                    $application = Application::findOne($id);
                    if ($application) {
                        $result = ContestResult::findByApplicationId($application->id);
                        if ($result) {
                            if ($this->generateDocument($result)) {
                                $successCount++;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Yii::error("Ошибка генерации диплома для заявки {$id}: " . $e->getMessage());
                }
            }
        }
        
        return ['success' => true, 'count' => $successCount];
    }

    public function actionEvaluationComplete($id)
{
    $model = Evaluation::findOne($id);
    if (!$model) {
        throw new NotFoundHttpException('Оценка не найдена.');
    }
    
    // Проверяем, что есть все необходимые оценки по критериям
    $criteriaScores = EvaluationScore::find()
        ->where(['evaluation_id' => $id])
        ->count();
    
    $totalCriteria = Criteria::find()
        ->where(['nomination_id' => $model->application->nomination_id])
        ->count();
    
    if ($criteriaScores < $totalCriteria) {
        Yii::$app->session->setFlash('error', 'Не все критерии оценены.');
        return $this->redirect(['evaluations']);
    }
    
    if ($model->complete()) {
        $this->checkAllEvaluationsComplete($model->application);
        
        Notification::create(
            $model->application->user_id,
            'Заявка оценена экспертом',
            "Ваша заявка '{$model->application->work_name}' была оценена экспертом. Общий балл: {$model->total_score}"
        );
        
        Yii::$app->session->setFlash('success', 'Оценка завершена');
    }
    
    return $this->redirect(['evaluations']);
}

/**
 * Проверяет, завершили ли все эксперты оценку заявки
 */
private function checkAllEvaluationsComplete($application)
{
    // Получаем всех экспертов, назначенных на эту заявку
    $assignedExperts = ExpertAssignment::find()
        ->where([
            'contest_id' => $application->contest_id,
            'nomination_id' => $application->nomination_id,
            'age_category_id' => $application->age_category_id
        ])
        ->count();
    
    // Получаем количество завершенных оценок
    $completedEvaluations = Evaluation::find()
        ->where([
            'application_id' => $application->id,
            'status' => 'completed'
        ])
        ->count();
    
    // Если все эксперты завершили оценку
    if ($assignedExperts > 0 && $completedEvaluations >= $assignedExperts) {
        // Обновляем статус заявки
        $application->status = 'graded';
        $application->save(false);
        
        // Создаем или обновляем ContestResult
        $this->createOrUpdateContestResult($application);
        
        // Уведомление администратору о завершении всех оценок
        $admins = User::find()->where(['is_admin' => 1])->all();
        foreach ($admins as $admin) {
            Notification::create(
                $admin->id,
                'Все эксперты завершили оценку',
                "Все эксперты завершили оценку заявки '{$application->work_name}'. " .
                "Заявка переведена в статус 'Оценена'."
            );
        }
    }
}

/**
 * Создает или обновляет ContestResult для заявки
 */
private function createOrUpdateContestResult($application)
{
    // Вычисляем средний балл по всем оценкам
    $evaluations = Evaluation::find()
        ->where(['application_id' => $application->id, 'status' => 'completed'])
        ->all();
    
    if (empty($evaluations)) {
        return;
    }
    
    $totalScore = 0;
    foreach ($evaluations as $evaluation) {
        $totalScore += $evaluation->total_score;
    }
    
    $averageScore = $totalScore / count($evaluations);
   
    $contestResult = ContestResult::findByApplicationId($application->id);
    
    if (!$contestResult) {
        $contestResult = new ContestResult();
        $contestResult->application_id = $application->id;
    }
    
    $contestResult->final_score = $averageScore;
    $contestResult->save(false);
}

    public function actionGeneratedDocumentDownload($id)
    {
        $model = GeneratedDocument::findOne($id);
        
        if (!$model || !$model->file_path) {
            throw new NotFoundHttpException('Документ не найден.');
        }

        $filePath = Yii::getAlias('@webroot/' . $model->file_path);
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('Файл не найден.');
        }

        return Yii::$app->response->sendFile($filePath, basename($model->file_path));
    }

    public function actionExportResultsGenerate()
{
    $contestId = Yii::$app->request->get('contest_id');
    $format = Yii::$app->request->get('format', 'excel');
    
    if (!$contestId) {
        Yii::$app->session->setFlash('error', 'Не выбран конкурс для экспорта.');
        return $this->redirect(['export-results']);
    }
    
    if ($contestId === 'all') {
        // Экспорт всех конкурсов
        $contests = Contest::find()->all();
        $allResults = [];
        
        foreach ($contests as $contest) {
            $results = ContestResult::find()
                ->joinWith(['application'])
                ->where(['application.contest_id' => $contest->id])
                ->orderBy(['place' => SORT_ASC, 'final_score' => SORT_DESC])
                ->all();
            
            $allResults[$contest->name] = $results;
        }
        
        return $this->exportAllResultsToExcel($allResults, $format);
    } else {
        // Экспорт конкретного конкурса
        $contest = $this->findContestModel($contestId);
        $results = ContestResult::find()
            ->joinWith(['application'])
            ->where(['application.contest_id' => $contestId])
            ->orderBy(['place' => SORT_ASC, 'final_score' => SORT_DESC])
            ->all();
        
        return $this->exportResultsToExcel($contest, $results, $format);
    }
}

private function exportAllResultsToExcel($allResults, $format)
{
    $spreadsheet = new Spreadsheet();
    $sheetIndex = 0;
    
    foreach ($allResults as $contestName => $results) {
        if ($sheetIndex > 0) {
            $spreadsheet->createSheet();
        }
        
        $sheet = $spreadsheet->setActiveSheetIndex($sheetIndex);
        $sheet->setTitle(substr($contestName, 0, 31)); // Ограничение Excel
        
        // Заголовки
        $sheet->setCellValue('A1', 'Общий отчет по всем конкурсам');
        $sheet->setCellValue('A2', 'Дата выгрузки: ' . date('d.m.Y H:i'));
        $sheet->setCellValue('A4', 'Конкурс: ' . $contestName);
        $sheet->setCellValue('A6', '№');
        $sheet->setCellValue('B6', 'ФИО участника');
        $sheet->setCellValue('C6', 'Номинация');
        $sheet->setCellValue('D6', 'Возрастная категория');
        $sheet->setCellValue('E6', 'Название работы');
        $sheet->setCellValue('F6', 'Итоговый балл');
        $sheet->setCellValue('G6', 'Место');
        $sheet->setCellValue('H6', 'Награда');
        $sheet->setCellValue('I6', 'Учреждение');
        $sheet->setCellValue('J6', 'Руководитель');
        
        // Данные
        $row = 7;
        foreach ($results as $index => $result) {
            $app = $result->application;
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $app->getFullName());
            $sheet->setCellValue('C' . $row, $app->nomination->name ?? '');
            $sheet->setCellValue('D' . $row, $app->ageCategory->name ?? '');
            $sheet->setCellValue('E' . $row, $app->work_name);
            $sheet->setCellValue('F' . $row, $result->final_score);
            $sheet->setCellValue('G' . $row, $result->place);
            
            $awardLabels = [
                'first' => 'Диплом I степени',
                'second' => 'Диплом II степени',
                'third' => 'Диплом III степени',
                'laureate' => 'Диплом лауреата',
                'diploma' => 'Диплом',
                'certificate' => 'Сертификат участника',
            ];
            $sheet->setCellValue('H' . $row, $awardLabels[$result->award_type] ?? $result->award_type ?? '');
            $sheet->setCellValue('I' . $row, $app->institution ?? '');
            $sheet->setCellValue('J' . $row, $app->leader ?? '');
            
            $row++;
        }
        
        // Авторазмер колонок
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $sheetIndex++;
    }
    
    $filename = 'all_results_' . date('Ymd_His') . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

}