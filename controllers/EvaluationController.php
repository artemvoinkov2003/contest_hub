<?php

namespace app\controllers;

use Yii;
use app\models\Application;
use app\models\Evaluation;
use app\models\EvaluationScore;
use app\models\Criteria;
use app\models\ExpertAssignment;
use app\models\Notification;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * EvaluationController implements the CRUD actions for Evaluation model.
 */
class EvaluationController extends Controller
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
     * Creates a new Evaluation model for an application.
     * @param int $application_id
     * @return mixed
     * @throws NotFoundHttpException if the application cannot be found
     * @throws ForbiddenHttpException if the user cannot evaluate this application
     */
    public function actionCreate($application_id)
    {
        $application = $this->findApplication($application_id);
        $user = Yii::$app->user->identity;
        
        // Проверяем, может ли пользователь оценивать эту заявку
        if (!$this->canEvaluate($application, $user)) {
            throw new ForbiddenHttpException('Вы не можете оценить эту заявку.');
        }
        
        // Проверяем, существует ли уже оценка от этого эксперта
        $evaluation = Evaluation::findOne([
            'application_id' => $application_id,
            'expert_id' => $user->id,
        ]);
        
        if (!$evaluation) {
            $evaluation = new Evaluation();
            $evaluation->application_id = $application_id;
            $evaluation->expert_id = $user->id;
            $evaluation->status = 'draft';
        }
        
        // Получаем критерии для номинации
        $criteriaList = Criteria::find()
            ->where(['nomination_id' => $application->nomination_id, 'is_active' => 1])
            ->orderBy(['order' => SORT_ASC])
            ->all();
        
        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                if ($evaluation->load($this->request->post())) {
                    // Устанавливаем статус "completed" если оценка завершена
                    if (isset($this->request->post()['complete'])) {
                        $evaluation->status = 'completed';
                    }
                    
                    // Сохраняем оценку
                    if ($evaluation->save()) {
                        // Удаляем старые оценки по критериям
                        EvaluationScore::deleteAll(['evaluation_id' => $evaluation->id]);
                        
                        // Сохраняем оценки по критериям
                        $totalScore = 0;
                        $criteriaScores = $this->request->post('criteria_scores', []);
                        
                        foreach ($criteriaList as $criteria) {
                            $score = isset($criteriaScores[$criteria->id]) ? (int)$criteriaScores[$criteria->id] : 0;
                            
                            // Ограничиваем оценку максимальным баллом
                            $score = min($score, $criteria->max_score);
                            
                            $evaluationScore = new EvaluationScore();
                            $evaluationScore->evaluation_id = $evaluation->id;
                            $evaluationScore->criteria_id = $criteria->id;
                            $evaluationScore->score = $score;
                            
                            if (!$evaluationScore->save()) {
                                throw new \Exception('Ошибка сохранения оценки по критерию');
                            }
                            
                            $totalScore += $score;
                        }
                        
                        // Обновляем общий балл
                        $evaluation->total_score = $totalScore;
                        $evaluation->save(false);
                        
                        $transaction->commit();
                        
                        // Создаем уведомление для участника
                        if ($evaluation->status === 'completed') {
                            Notification::create(
                                $application->user_id,
                                'Заявка оценена экспертом',
                                "Ваша заявка '{$application->work_name}' была оценена экспертом. Общий балл: {$totalScore}"
                            );
                        }
                        
                        Yii::$app->session->setFlash('success', 
                            $evaluation->status === 'completed' 
                                ? 'Оценка успешно сохранена и завершена!' 
                                : 'Оценка сохранена как черновик.'
                        );
                        
                        return $this->redirect(['view', 'id' => $evaluation->id]);
                    }
                }
                
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Ошибка при сохранении оценки: ' . $e->getMessage());
            }
        }
        
        // Получаем сохраненные оценки для отображения в форме
        $savedScores = [];
        if (!$evaluation->isNewRecord) {
            $scores = EvaluationScore::find()
                ->where(['evaluation_id' => $evaluation->id])
                ->indexBy('criteria_id')
                ->all();
            
            foreach ($scores as $score) {
                $savedScores[$score->criteria_id] = $score->score;
            }
        }
        
        return $this->render('create', [
            'application' => $application,
            'evaluation' => $evaluation,
            'criteriaList' => $criteriaList,
            'savedScores' => $savedScores,
        ]);
    }

    public function actionIndex()
{
    $user = Yii::$app->user->identity;
    
    // Если пользователь не эксперт и не админ, то запрещаем доступ
    if (!$user->is_admin && !$user->is_expert) {
        throw new ForbiddenHttpException('Доступ только для экспертов и администраторов.');
    }
    
    $searchModel = new \app\models\ApplicationSearch();
    $dataProvider = $searchModel->searchForExpert(Yii::$app->request->queryParams, $user->id);
    
    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'user' => $user,
    ]);
}

/**
 * Lists all evaluations for the current expert.
 * @return mixed
 */
public function actionMyEvaluations()
{
    $user = Yii::$app->user->identity;
    
    $searchModel = new \app\models\EvaluationSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user->id);
    
    return $this->render('my-evaluations', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}
    
    /**
     * Displays a single Evaluation model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $evaluation = $this->findEvaluation($id);
        $user = Yii::$app->user->identity;
        
        // Проверяем доступ к просмотру оценки
        if (!$this->canViewEvaluation($evaluation, $user)) {
            throw new ForbiddenHttpException('У вас нет прав для просмотра этой оценки.');
        }
        
        $criteriaScores = EvaluationScore::find()
            ->where(['evaluation_id' => $id])
            ->joinWith('criteria')
            ->orderBy('criteria.order')
            ->all();
        
        return $this->render('view', [
            'evaluation' => $evaluation,
            'criteriaScores' => $criteriaScores,
        ]);
    }
    
    /**
     * Updates an existing Evaluation model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $evaluation = $this->findEvaluation($id);
        $user = Yii::$app->user->identity;
        
        // Проверяем, может ли пользователь редактировать эту оценку
        if (!$this->canEditEvaluation($evaluation, $user)) {
            throw new ForbiddenHttpException('Вы не можете редактировать эту оценку.');
        }
        
        $application = $evaluation->application;
        $criteriaList = Criteria::find()
            ->where(['nomination_id' => $application->nomination_id, 'is_active' => 1])
            ->orderBy(['order' => SORT_ASC])
            ->all();
        
        if ($this->request->isPost && $evaluation->load($this->request->post())) {
            if ($evaluation->save()) {
                Yii::$app->session->setFlash('success', 'Оценка успешно обновлена!');
                return $this->redirect(['view', 'id' => $evaluation->id]);
            }
        }
        
        return $this->render('update', [
            'evaluation' => $evaluation,
            'application' => $application,
            'criteriaList' => $criteriaList,
        ]);
    }
    
    /**
     * Finds the Application model based on its primary key value.
     * @param int $id
     * @return Application
     * @throws NotFoundHttpException
     */
    protected function findApplication($id)
    {
        if (($model = Application::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('Заявка не найдена.');
    }
    
    /**
     * Finds the Evaluation model based on its primary key value.
     * @param int $id
     * @return Evaluation
     * @throws NotFoundHttpException
     */
    protected function findEvaluation($id)
    {
        if (($model = Evaluation::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('Оценка не найдена.');
    }
    
    /**
     * Checks if user can evaluate the application
     * @param Application $application
     * @param \app\models\User $user
     * @return bool
     */
    protected function canEvaluate(Application $application, $user)
    {
        // Администратор может оценивать любые заявки
        if ($user->is_admin) {
            return true;
        }
        
        // Эксперт может оценивать заявки, назначенные ему
        return ExpertAssignment::find()
            ->where([
                'expert_id' => $user->id,
                'contest_id' => $application->contest_id,
                'nomination_id' => $application->nomination_id,
                'age_category_id' => $application->age_category_id,
            ])
            ->exists();
    }
    
    /**
     * Checks if user can view the evaluation
     * @param Evaluation $evaluation
     * @param \app\models\User $user
     * @return bool
     */
    protected function canViewEvaluation(Evaluation $evaluation, $user)
    {
        // Администратор может просматривать все оценки
        if ($user->is_admin) {
            return true;
        }
        
        // Эксперт может просматривать свои оценки
        if ($evaluation->expert_id == $user->id) {
            return true;
        }
        
        // Участник может просматривать оценки своей заявки
        if ($evaluation->application->user_id == $user->id) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Checks if user can edit the evaluation
     * @param Evaluation $evaluation
     * @param \app\models\User $user
     * @return bool
     */
    protected function canEditEvaluation(Evaluation $evaluation, $user)
    {
        // Администратор может редактировать любые оценки
        if ($user->is_admin) {
            return true;
        }
        
        // Эксперт может редактировать свои оценки только в статусе "черновик"
        if ($evaluation->expert_id == $user->id && $evaluation->status === 'draft') {
            return true;
        }
        
        return false;
    }
}