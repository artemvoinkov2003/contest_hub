<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Application;
use app\models\ApplicationSearch;
use app\models\ExpertAssignment;
use app\models\Contest;
use app\models\AgeCategory;
use app\models\Nomination;
use app\models\Notification;
use app\models\ContestResult;
use app\models\GeneratedDocument;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\Html;


/**
 * ApplicationController implements the actions for Application model.
 */
class ApplicationController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Application models.
     * @return mixed
     */
public function actionIndex()
{
    $searchModel = new ApplicationSearch();
    
    $isAdmin = Yii::$app->user->identity->is_admin;
    $userId = Yii::$app->user->id;
    
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $isAdmin, $userId);
    
    $contests = Contest::find()->all();
    $nominations = Nomination::find()->all();
    $ageCategories = AgeCategory::find()->all();
    
    $statuses = Application::getStatuses();

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'contests' => $contests,
        'nominations' => $nominations,
        'ageCategories' => $ageCategories,
        'statuses' => $statuses,
    ]);
}


    /**
     * Displays a single Application model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        if (!$this->checkAccess($model)) {
            throw new ForbiddenHttpException('У вас нет прав для просмотра этой заявки.');
        }

        $contestResult = ContestResult::findByApplicationId($id);
        
        $documents = GeneratedDocument::findByApplicationId($id);

        return $this->render('view', [
            'model' => $model,
            'contestResult' => $contestResult,
            'documents' => $documents,
        ]);
    }

    /**
     * Creates a new Application model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($contest_id = null)
    {
        $model = new Application();
        $model->user_id = Yii::$app->user->id;
        
        if (!$contest_id) {
            $contest = Contest::find()->where(['status' => 1])->one();
            if ($contest) {
                return $this->redirect(['contest/apply', 'id' => $contest->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Нет активных конкурсов для подачи заявки.');
                return $this->redirect(['contest/index']);
            }
        }
        
        $model->contest_id = $contest_id;
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $nominationId = $model->nomination_id;
                if ($nominationId) {
                    $nomination = Nomination::findOne($nominationId);
                    if ($nomination && $nomination->max_participants > 0) {
                        $currentCount = Application::find()
                            ->where([
                                'nomination_id' => $nominationId, 
                                'contest_id' => $contest_id
                            ])
                            ->andWhere(['!=', 'status', Application::STATUS_BLOCKED])
                            ->count();
                        
                        if ($currentCount >= $nomination->max_participants) {
                            Yii::$app->session->setFlash('error', 
                                "В номинации '{$nomination->name}' достигнут лимит участников ({$nomination->max_participants}). " .
                                "Выберите другую номинацию или попробуйте позже."
                            );
                            
                            $ageCategories = AgeCategory::find()->where(['contest_id' => $contest_id])->all();
                            $nominations = Nomination::find()->where(['contest_id' => $contest_id])->all();
                            
                            return $this->render('create', [
                                'model' => $model,
                                'ageCategories' => $ageCategories,
                                'nominations' => $nominations,
                            ]);
                        }
                    }
                }
                
                $model->file = UploadedFile::getInstance($model, 'file');
                
                if ($model->file) {
                    $allowedExtensions = ['mp4', 'mkv', 'png', 'avi', 'jpg', 'jpeg', 'pdf'];
                    $fileExtension = strtolower($model->file->extension);
                    
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        Yii::$app->session->setFlash('error', 
                            "Недопустимый формат файла. Допустимые форматы: " . implode(', ', $allowedExtensions)
                        );
                        
                        $ageCategories = AgeCategory::find()->where(['contest_id' => $contest_id])->all();
                        $nominations = Nomination::find()->where(['contest_id' => $contest_id])->all();
                        
                        return $this->render('create', [
                            'model' => $model,
                            'ageCategories' => $ageCategories,
                            'nominations' => $nominations,
                        ]);
                    }
                }
                
                if ($model->validate()) {
                
                    if ($model->file) {
                        if (!$model->upload()) {
                            Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла.');
                            $ageCategories = AgeCategory::find()->where(['contest_id' => $contest_id])->all();
                            $nominations = Nomination::find()->where(['contest_id' => $contest_id])->all();
                            return $this->render('create', [
                                'model' => $model,
                                'ageCategories' => $ageCategories,
                                'nominations' => $nominations,
                            ]);
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Необходимо загрузить файл работы.');
                        $ageCategories = AgeCategory::find()->where(['contest_id' => $contest_id])->all();
                        $nominations = Nomination::find()->where(['contest_id' => $contest_id])->all();
                        return $this->render('create', [
                            'model' => $model,
                            'ageCategories' => $ageCategories,
                            'nominations' => $nominations,
                        ]);
                    }
                    
                    if ($model->save()) {
                        
                        if ($nomination && $nomination->max_participants > 0) {
                            $newCount = Application::find()
                                ->where(['nomination_id' => $nominationId, 'contest_id' => $contest_id])
                                ->andWhere(['!=', 'status', Application::STATUS_BLOCKED])
                                ->count();
                            
                            if ($newCount > $nomination->max_participants) {
                               
                                $model->status = Application::STATUS_BLOCKED;
                                $model->save();
                                
                                Yii::$app->session->setFlash('error', 
                                    "Извините, лимит участников в номинации '{$nomination->name}' был достигнут " .
                                    "одновременно с вашей заявкой. Пожалуйста, выберите другую номинацию."
                                );
                                
                                Notification::create(
                                    $model->user_id,
                                    'Заявка автоматически отменена',
                                    "Ваша заявка '{$model->work_name}' была автоматически отменена, " .
                                    "так как лимит участников в номинации '{$nomination->name}' был достигнут."
                                );
                                
                                return $this->redirect(['contest/index']);
                            }
                            
                            if ($newCount >= $nomination->max_participants) {
                                
                                $admins = User::find()->where(['is_admin' => 1])->all();
                                foreach ($admins as $admin) {
                                    Notification::create(
                                        $admin->id,
                                        'Лимит участников достигнут',
                                        "В номинации '{$nomination->name}' конкурса '{$model->contest->name}' " .
                                        "достигнут лимит участников ({$nomination->max_participants})."
                                    );
                                }
                            }
                        }
                        
                        Notification::create(
                            $model->user_id,
                            'Заявка подана',
                            "Ваша заявка '{$model->work_name}' успешно подана на конкурс '{$model->contest->name}'. Статус: Новая"
                        );
                        
                        Yii::$app->session->setFlash('success', 'Заявка успешно подана!');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $ageCategories = AgeCategory::find()->where(['contest_id' => $contest_id])->all();
        $nominations = Nomination::find()->where(['contest_id' => $contest_id])->all();

        return $this->render('create', [
            'model' => $model,
            'ageCategories' => $ageCategories,
            'nominations' => $nominations,
        ]);
    }

    /**
     * Updates an existing Application model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldStatus = $model->status;
        $user = Yii::$app->user->identity;
        
        if (!$this->checkAccess($model) || (!$user->is_admin && !$model->canBeCancelled())) {
            throw new ForbiddenHttpException('Вы не можете редактировать эту заявку.');
        }

        $originalNominationId = $model->nomination_id;

        $contests = Contest::find()->where(['status' => 1])->all();

        if ($this->request->isPost && $model->load($this->request->post())) {
           
            if ($model->nomination_id != $originalNominationId) {
                $nomination = Nomination::findOne($model->nomination_id);
                if ($nomination && $nomination->max_participants > 0) {
                    $currentCount = Application::find()
                        ->where(['nomination_id' => $model->nomination_id, 'contest_id' => $model->contest_id])
                        ->andWhere(['!=', 'status', Application::STATUS_BLOCKED])
                        ->andWhere(['!=', 'id', $model->id]) 
                        ->count();
                    
                    if ($currentCount >= $nomination->max_participants) {
                        Yii::$app->session->setFlash('error', 
                            "В номинации '{$nomination->name}' достигнут лимит участников ({$nomination->max_participants})."
                        );
                        
                        return $this->render('update', [
                            'model' => $model,
                            'contests' => $contests,
                        ]);
                    }
                }
            }
            
            $model->file = UploadedFile::getInstance($model, 'file');
            
            if ($model->file) {
                $allowedExtensions = ['mp4', 'mkv', 'png', 'avi', 'jpg', 'jpeg', 'pdf'];
                $fileExtension = strtolower($model->file->extension);
                
                if (!in_array($fileExtension, $allowedExtensions)) {
                    Yii::$app->session->setFlash('error', 
                        "Недопустимый формат файла. Допустимые форматы: " . implode(', ', $allowedExtensions)
                    );
                    
                    return $this->render('update', [
                        'model' => $model,
                        'contests' => $contests,
                    ]);
                }
                
                if (!$model->upload()) {
                    Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла.');
                    return $this->render('update', [
                        'model' => $model,
                        'contests' => $contests,
                    ]);
                }
            }
            
            if ($model->save()) {
                
                if ($oldStatus !== $model->status) {
                    Notification::create(
                        $model->user_id,
                        'Статус заявки изменен',
                        "Статус вашей заявки '{$model->work_name}' изменен на '{$model->getStatusLabel()}'"
                    );
                }
                
                Yii::$app->session->setFlash('success', 'Заявка успешно обновлена!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'contests' => $contests,
        ]);
    }

    /**
     * Получает номинации для конкурса (AJAX)
     */
    public function actionGetNominations($contest_id)
    {
        $nominations = Nomination::find()
            ->where(['contest_id' => $contest_id])
            ->orderBy(['order' => SORT_ASC])
            ->all();
        
        $html = '<option value="">Выберите номинацию</option>';
        foreach ($nominations as $nomination) {
            $count = Application::find()
                ->where(['nomination_id' => $nomination->id, 'contest_id' => $contest_id])
                ->andWhere(['!=', 'status', Application::STATUS_BLOCKED])
                ->count();
            
            $maxLabel = $nomination->max_participants > 0 ? 
                " ({$count}/{$nomination->max_participants})" : 
                " ({$count})";
            
            $disabled = ($nomination->max_participants > 0 && $count >= $nomination->max_participants) ? 
                'disabled' : '';
            
            $html .= '<option value="' . $nomination->id . '" ' . $disabled . '>' . 
                     Html::encode($nomination->name . $maxLabel) . '</option>';
        }
        
        return $html;
    }

    /**
     * Получает возрастные категории для конкурса (AJAX)
     */
    public function actionGetAgeCategories($contest_id)
    {
        $ageCategories = AgeCategory::find()
            ->where(['contest_id' => $contest_id])
            ->orderBy(['order' => SORT_ASC])
            ->all();
        
        $html = '<option value="">Выберите возрастную категорию</option>';
        foreach ($ageCategories as $category) {
            $html .= '<option value="' . $category->id . '">' . 
                     Html::encode($category->name) . '</option>';
        }
        
        return $html;
    }

    /**
     * Deletes an existing Application model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $user = Yii::$app->user->identity;
        
        if (!$this->checkAccess($model) || (!$user->is_admin && !$model->canBeCancelled())) {
            throw new ForbiddenHttpException('Вы не можете удалить эту заявку.');
        }

        $nomination = $model->nomination;
        
        Notification::create(
            $model->user_id,
            'Заявка удалена',
            "Ваша заявка '{$model->work_name}' на конкурс '{$model->contest->name}' была удалена"
        );

        if ($model->delete()) {
            
            if ($nomination && $nomination->max_participants > 0) {
                $currentCount = Application::find()
                    ->where(['nomination_id' => $nomination->id, 'contest_id' => $model->contest_id])
                    ->andWhere(['!=', 'status', Application::STATUS_BLOCKED])
                    ->count();
                
                if ($currentCount < $nomination->max_participants) {
                    $admins = User::find()->where(['is_admin' => 1])->all();
                    foreach ($admins as $admin) {
                        Notification::create(
                            $admin->id,
                            'Место освободилось в номинации',
                            "В номинации '{$nomination->name}' конкурса '{$model->contest->name}' " .
                            "освободилось место. Текущее количество: {$currentCount}/{$nomination->max_participants}"
                        );
                    }
                }
            }
            
            Yii::$app->session->setFlash('success', 'Заявка успешно удалена.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении заявки.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Cancel an existing Application model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        $user = Yii::$app->user->identity;
        
        if (!$this->checkAccess($model) || (!$user->is_admin && !$model->canBeCancelled())) {
            throw new ForbiddenHttpException('Вы не можете отменить эту заявку.');
        }

        $model->status = Application::STATUS_BLOCKED;
        if ($model->save()) {
     
            $nomination = $model->nomination;
            if ($nomination && $nomination->max_participants > 0) {
                $currentCount = Application::find()
                    ->where(['nomination_id' => $nomination->id, 'contest_id' => $model->contest_id])
                    ->andWhere(['!=', 'status', Application::STATUS_BLOCKED])
                    ->count();
                
                if ($currentCount < $nomination->max_participants) {
                    $admins = User::find()->where(['is_admin' => 1])->all();
                    foreach ($admins as $admin) {
                        Notification::create(
                            $admin->id,
                            'Место освободилось в номинации',
                            "В номинации '{$nomination->name}' конкурса '{$model->contest->name}' " .
                            "освободилось место. Текущее количество: {$currentCount}/{$nomination->max_participants}"
                        );
                    }
                }
            }
            
            Notification::create(
                $model->user_id,
                'Заявка отменена',
                "Ваша заявка '{$model->work_name}' на конкурс '{$model->contest->name}' была отменена"
            );
            
            Yii::$app->session->setFlash('success', 'Заявка успешно отменена.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при отмене заявки.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Downloads a generated document.
     */
    public function actionDownloadDocument($id)
    {
        $document = GeneratedDocument::findOne($id);
        
        if (!$document) {
            throw new NotFoundHttpException('Документ не найден.');
        }
        
        $model = $document->application;
        if (!$this->checkAccess($model)) {
            throw new ForbiddenHttpException('У вас нет прав для скачивания этого документа.');
        }
        
        if (!$document->fileExists()) {
            Yii::$app->session->setFlash('error', 'Файл не найден.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        $fileName = sprintf(
            '%s_%s_%s.%s',
            $document->getDocumentTypeName(),
            $model->surname,
            $model->work_name,
            pathinfo($document->file_path, PATHINFO_EXTENSION)
        );
        
        $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
        
        return Yii::$app->response->sendFile($document->getAbsolutePath(), $fileName);
    }

    /**
     * View contest result details.
     */
    public function actionViewResult($id)
    {
        $model = $this->findModel($id);
        $contestResult = ContestResult::findByApplicationId($id);
        
        if (!$contestResult) {
            Yii::$app->session->setFlash('error', 'Результаты для этой заявки еще не определены.');
            return $this->redirect(['view', 'id' => $id]);
        }
        
        if (!$this->checkAccess($model)) {
            throw new ForbiddenHttpException('У вас нет прав для просмотра этого результата.');
        }

        return $this->render('view-result', [
            'model' => $model,
            'contestResult' => $contestResult,
        ]);
    }

    /**
     * Finds the Application model based on its primary key value.
     * @param int $id
     * @return Application
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Application::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Заявка не найдена.');
    }

    /**
     * Check if user has access to the application
     * @param Application $application
     * @return bool
     */
    protected function checkAccess(Application $application)
    {
        $user = Yii::$app->user->identity;
        
        // Admin has access to all applications
        if ($user->is_admin) {
            return true;
        }

        // User can access their own applications
        if ($application->user_id == $user->id) {
            return true;
        }

        // Expert can access applications they are assigned to
        $expertAssignment = ExpertAssignment::find()
            ->where([
                'expert_id' => $user->id,
                'contest_id' => $application->contest_id,
                'nomination_id' => $application->nomination_id,
                'age_category_id' => $application->age_category_id,
            ])
            ->exists();

        return $expertAssignment;
    }

    /**
     * Download diploma for application.
     */
    public function actionDownloadDiploma($id)
    {
        $model = $this->findModel($id);
        
        if (!$this->checkAccess($model)) {
            throw new ForbiddenHttpException('У вас нет прав для скачивания диплома этой заявки.');
        }
        
        $contestResult = ContestResult::findByApplicationId($id);
        if (!$contestResult) {
            Yii::$app->session->setFlash('error', 'Для этой заявки еще нет результатов и диплом не может быть сгенерирован.');
            return $this->redirect(['view', 'id' => $id]);
        }
        
        $document = GeneratedDocument::find()
            ->where(['application_id' => $id, 'document_type' => 'diploma'])
            ->one();
        
        if (!$document) {
            $document = $this->generateDiploma($model, $contestResult);
        }
        
        if (!$document->fileExists()) {
            $this->createDiplomaFile($document, $model, $contestResult);
        }
        
        $fileName = $this->generateDiplomaFilename($model, $contestResult);
        
        return Yii::$app->response->sendFile($document->getAbsolutePath(), $fileName, [
            'mimeType' => $this->getMimeType($document->file_path),
            'inline' => false
        ]);
    }

    /**
     * Generate diploma record in database.
     */
    protected function generateDiploma($application, $contestResult)
    {
        $document = new GeneratedDocument();
        $document->application_id = $application->id;
        $document->document_type = 'diploma';
        
        $basePath = Yii::getAlias('@webroot/uploads/diplomas/');
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
        }
        
        $fileName = 'diploma_' . $application->id . '_' . time() . '.html';
        $document->file_path = 'uploads/diplomas/' . $fileName;
        
        if ($document->save()) {
            return $document;
        } else {
            throw new \Exception('Не удалось сохранить документ: ' . print_r($document->errors, true));
        }
    }

    /**
     * Create diploma HTML file.
     */
    protected function createDiplomaFile($document, $application, $contestResult)
    {
        $filePath = Yii::getAlias('@webroot/') . $document->file_path;
        
        $contest = $application->contest;
        $nomination = $application->nomination;
        $ageCategory = $application->ageCategory;
        
        $html = $this->renderPartial('diploma-template', [
            'application' => $application,
            'contestResult' => $contestResult,
            'contest' => $contest,
            'nomination' => $nomination,
            'ageCategory' => $ageCategory,
        ]);
        
        file_put_contents($filePath, $html);
    }

    /**
     * Generate filename for diploma download.
     */
    protected function generateDiplomaFilename($application, $contestResult)
    {
        $placeText = $contestResult->place ? $contestResult->getPlaceText() : 'participant';
        $awardText = $contestResult->award_type ? $contestResult->getAwardText() : 'certificate';
        
        $filename = sprintf(
            'Диплом_%s_%s_%s.html',
            str_replace(' ', '_', $awardText),
            $application->surname,
            $application->work_name
        );
        
        $filename = preg_replace('/[^a-zA-Zа-яА-Я0-9._-]/u', '_', $filename);
        
        return $filename;
    }

    /**
     * Get MIME type by file extension.
     */
    protected function getMimeType($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'html' => 'text/html',
            'htm' => 'text/html',
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}