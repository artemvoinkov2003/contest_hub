<?php

namespace app\controllers;

use Yii;
use app\models\ReportTemplate;
use app\models\Contest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * ReportController implements the CRUD actions for ReportTemplate model.
 */
class ReportController extends Controller
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
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->identity->is_admin == 1;
                            }
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ReportTemplate models grouped by contest.
     */
    public function actionIndex()
    {
        $groupedTemplates = ReportTemplate::getAllGrouped();
        
        return $this->render('index', [
            'groupedTemplates' => $groupedTemplates,
        ]);
    }

    /**
     * Displays a single ReportTemplate model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ReportTemplate model.
     */
    public function actionCreate()
    {
        $model = new ReportTemplate();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->templateFile = UploadedFile::getInstance($model, 'templateFile');
                
                if ($model->templateFile) {
                    $fileName = time() . '_' . $model->templateFile->baseName . '.' . $model->templateFile->extension;
                    $uploadPath = Yii::getAlias('@webroot/uploads/templates/');
                    
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    
                    if ($model->templateFile->saveAs($uploadPath . $fileName)) {
                        $model->template_file = $fileName;
                        if ($model->save()) {
                            Yii::$app->session->setFlash('success', 'Шаблон успешно создан.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'contests' => Contest::find()->all(),
            'types' => ReportTemplate::getAllTypes(),
        ]);
    }

    /**
     * Updates an existing ReportTemplate model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldFile = $model->template_file;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $uploadedFile = UploadedFile::getInstance($model, 'templateFile');
            
            if ($uploadedFile) {
                $fileName = time() . '_' . $uploadedFile->baseName . '.' . $uploadedFile->extension;
                $uploadPath = Yii::getAlias('@webroot/uploads/templates/');
                
                if ($uploadedFile->saveAs($uploadPath . $fileName)) {
                    
                    if ($oldFile && file_exists($uploadPath . $oldFile)) {
                        unlink($uploadPath . $oldFile);
                    }
                    $model->template_file = $fileName;
                }
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Шаблон успешно обновлен.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'contests' => Contest::find()->all(),
            'types' => ReportTemplate::getAllTypes(),
        ]);
    }

    /**
     * Deletes an existing ReportTemplate model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $filePath = $model->getAbsolutePath();
        
        if ($model->delete()) {
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            Yii::$app->session->setFlash('success', 'Шаблон успешно удален.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Downloads the template file.
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        
        if (!$model->fileExists()) {
            throw new NotFoundHttpException('Файл не найден.');
        }
        
        return Yii::$app->response->sendFile($model->getAbsolutePath(), $model->template_file);
    }

    /**
     * Generates a document based on template.
     */
    public function actionGenerate($id)
    {
        $model = $this->findModel($id);
        
        // Здесь будет логика генерации документа
        // Это зависит от типа шаблона и используемой библиотеки (TCPDF, PhpWord и т.д.)
        
        Yii::$app->session->setFlash('info', 'Функция генерации в разработке.');
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the ReportTemplate model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = ReportTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }
}