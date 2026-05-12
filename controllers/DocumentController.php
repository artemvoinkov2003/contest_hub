<?php

namespace app\controllers;

use Yii;
use app\models\GeneratedDocument;
use app\models\Application;
use app\models\Contest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
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
     * Lists all GeneratedDocument models.
     */
    public function actionIndex($contest_id = null)
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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'contests' => $contests,
            'selectedContestId' => $contest_id,
        ]);
    }

    /**
     * Displays a single GeneratedDocument model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Downloads a document.
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        
        if (!$model->fileExists()) {
            throw new NotFoundHttpException('Файл не найден.');
        }
        
        $fileName = $this->generateDownloadFileName($model);
        
        return Yii::$app->response->sendFile(
            $model->getAbsolutePath(), 
            $fileName
        );
    }

    /**
     * Deletes an existing GeneratedDocument model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $filePath = $model->getAbsolutePath();
        
        if ($model->delete()) {
            // Удаляем файл
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            Yii::$app->session->setFlash('success', 'Документ успешно удален.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Lists documents for a specific application.
     */
    public function actionForApplication($application_id)
    {
        $application = Application::findOne($application_id);
        
        if (!$application) {
            throw new NotFoundHttpException('Заявка не найдена.');
        }
        
        $documents = GeneratedDocument::findByApplicationId($application_id);

        return $this->render('for-application', [
            'application' => $application,
            'documents' => $documents,
        ]);
    }

    /**
     * Lists documents for a specific contest.
     */
    public function actionForContest($contest_id)
    {
        $contest = Contest::findOne($contest_id);
        
        if (!$contest) {
            throw new NotFoundHttpException('Конкурс не найден.');
        }
        
        $documents = GeneratedDocument::findByContestId($contest_id);

        return $this->render('for-contest', [
            'contest' => $contest,
            'documents' => $documents,
        ]);
    }

    /**
     * User's documents (for regular users).
     */
    public function actionMyDocuments()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;
        
        $query = GeneratedDocument::find()
            ->joinWith('application')
            ->where(['application.user_id' => $userId]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['generated_at' => SORT_DESC],
            ],
        ]);

        return $this->render('my-documents', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Batch generate documents for a contest.
     */
    public function actionBatchGenerate($contest_id)
    {
        $contest = Contest::findOne($contest_id);
        
        if (!$contest) {
            throw new NotFoundHttpException('Конкурс не найден.');
        }

        // Здесь будет логика пакетной генерации документов
        // В реальном проекте это может быть фоновая задача
        
        Yii::$app->session->setFlash('info', 'Пакетная генерация документов в разработке.');
        return $this->redirect(['for-contest', 'contest_id' => $contest_id]);
    }

    /**
     * Finds the GeneratedDocument model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = GeneratedDocument::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }

    /**
     * Generates a filename for download.
     */
    private function generateDownloadFileName($model)
    {
        $app = $model->application;
        $ext = pathinfo($model->file_path, PATHINFO_EXTENSION);
        
        $name = sprintf(
            '%s_%s_%s.%s',
            $model->document_type,
            $app ? $app->surname : 'document',
            $app ? $app->work_name : 'work',
            $ext
        );
        
        return preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
    }
}