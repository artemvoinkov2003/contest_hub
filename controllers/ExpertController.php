<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Contest;
use app\models\Nomination;
use app\models\Application;
use app\models\Evaluation;
use app\models\EvaluationScore;
use app\models\Criteria;
use app\models\ExpertAssignment;
use app\models\Notification;
use app\models\ContestResult;
use app\models\GeneratedDocument;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ExpertController implements the CRUD actions for expert evaluation.
 */
class ExpertController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return $this->isExpert();
                            }
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'complete' => ['POST'],
                        'export-evaluations' => ['POST'],
                        'request-diploma' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Check if user is expert
     */
    private function isExpert()
    {
        $user = Yii::$app->user->identity;
        // Проверяем, является ли пользователь экспертом по флагу в таблице user
        return $user && $user->is_expert == 1;
    }

    /**
     * Lists all applications for evaluation.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $statusFilter = Yii::$app->request->get('status', 'all');
        
        $query = ExpertAssignment::getAssignedApplications($userId);
        
        // Применяем фильтр по статусу
        if ($statusFilter !== 'all') {
            switch ($statusFilter) {
                case 'pending':
                    // Ожидают оценки (нет оценки или только черновик)
                    $query->andWhere(['not in', 'a.id', 
                        Evaluation::find()
                            ->select('application_id')
                            ->where(['expert_id' => $userId])
                            ->andWhere(['in', 'status', ['draft', 'completed']])
                    ]);
                    break;
                    
                case 'draft':
                    // Только черновики
                    $query->innerJoin(['e' => Evaluation::tableName()], 
                        'e.application_id = a.id AND e.expert_id = :userId AND e.status = :status', 
                        [':userId' => $userId, ':status' => 'draft']
                    );
                    break;
                    
                case 'evaluated':
                    // Оцененные текущим экспертом
                    $query->innerJoin(['e' => Evaluation::tableName()], 
                        'e.application_id = a.id AND e.expert_id = :userId AND e.status = :status', 
                        [':userId' => $userId, ':status' => 'completed']
                    );
                    break;
                    
                case 'completed':
                    // Все эксперты завершили
                    $subQuery = (new \yii\db\Query())
                        ->select('a.id')
                        ->from(['a' => Application::tableName()])
                        ->innerJoin(['ea' => ExpertAssignment::tableName()], 
                            'ea.contest_id = a.contest_id AND ea.nomination_id = a.nomination_id AND ea.age_category_id = a.age_category_id')
                        ->innerJoin(['e' => Evaluation::tableName()], 'e.application_id = a.id')
                        ->groupBy('a.id')
                        ->having('COUNT(DISTINCT ea.expert_id) = COUNT(DISTINCT CASE WHEN e.status = "completed" THEN e.expert_id END)');
                    
                    $query->andWhere(['in', 'a.id', $subQuery]);
                    break;
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Evaluates a specific application.
     * @param int $id application ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEvaluate($id)
    {
        $application = $this->findApplication($id);
        
        // Check if expert is assigned to this application
        if (!ExpertAssignment::find()
            ->where([
                'expert_id' => Yii::$app->user->id,
                'contest_id' => $application->contest_id,
                'nomination_id' => $application->nomination_id,
                'age_category_id' => $application->age_category_id
            ])
            ->exists()) {
            throw new NotFoundHttpException('У вас нет доступа к оценке этой заявки.');
        }

        // КРИТИЧЕСКАЯ ПРОВЕРКА: Наличие критериев для номинации
        $criteria = Criteria::getForNomination($application->nomination_id);
        if (empty($criteria)) {
            Yii::$app->session->setFlash('error', 
                'Для данной номинации не заданы критерии оценки. ' .
                'Пожалуйста, свяжитесь с администратором для настройки критериев оценки.'
            );
            return $this->redirect(['index']);
        }

        // Проверяем, все ли критерии активны
        $inactiveCriteria = [];
        foreach ($criteria as $criterion) {
            if (!$criterion->is_active) {
                $inactiveCriteria[] = $criterion->name;
            }
        }
        
        if (!empty($inactiveCriteria)) {
            Yii::$app->session->setFlash('warning', 
                'Некоторые критерии оценки неактивны: ' . implode(', ', $inactiveCriteria) .
                '. Оценка по этим критериям не будет учитываться.'
            );
        }

        $evaluation = Evaluation::find()
            ->where(['application_id' => $id, 'expert_id' => Yii::$app->user->id])
            ->one();

        if (!$evaluation) {
            $evaluation = new Evaluation();
            $evaluation->application_id = $id;
            $evaluation->expert_id = Yii::$app->user->id;
            $evaluation->status = Evaluation::STATUS_DRAFT;
            
            // Инициализируем оценку
            if (!$evaluation->save(false)) {
                Yii::$app->session->setFlash('error', 'Ошибка при создании оценки.');
                return $this->redirect(['index']);
            }
        }

        $scores = [];
        foreach ($criteria as $criterion) {
            $score = EvaluationScore::find()
                ->where(['evaluation_id' => $evaluation->id, 'criteria_id' => $criterion->id])
                ->one();

            if (!$score) {
                $score = new EvaluationScore();
                $score->evaluation_id = $evaluation->id;
                $score->criteria_id = $criterion->id;
                $score->score = 0;
                if (!$score->save(false)) {
                    Yii::$app->session->setFlash('error', 'Ошибка при инициализации оценки критерия.');
                    return $this->redirect(['index']);
                }
            }
            $scores[] = $score;
        }

        if ($this->request->isPost) {
            $post = $this->request->post();

            // Валидация оценок
            $validationErrors = [];
            foreach ($scores as $score) {
                $scoreKey = 'score_' . $score->criteria_id;
                if (isset($post[$scoreKey])) {
                    $newScore = (int)$post[$scoreKey];
                    
                    // Проверка диапазона
                    if ($newScore < 0 || $newScore > $score->criteria->max_score) {
                        $validationErrors[] = "Критерий '{$score->criteria->name}': оценка должна быть от 0 до {$score->criteria->max_score}";
                    } else {
                        $score->score = $newScore;
                    }
                }
            }

            if (!empty($validationErrors)) {
                Yii::$app->session->setFlash('error', implode('<br>', $validationErrors));
                return $this->render('evaluate', [
                    'application' => $application,
                    'evaluation' => $evaluation,
                    'criteria' => $criteria,
                    'scores' => $scores,
                ]);
            }

            // Update scores
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($scores as $score) {
                    $scoreKey = 'score_' . $score->criteria_id;
                    if (isset($post[$scoreKey])) {
                        $score->score = (int)$post[$scoreKey];
                        if (!$score->save(false)) {
                            throw new \Exception('Ошибка сохранения оценки по критерию: ' . $score->criteria->name);
                        }
                    }
                }

                // Update notes
                if (isset($post['notes'])) {
                    $evaluation->notes = htmlspecialchars($post['notes'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }

                // Recalculate total score
                $evaluation->calculateTotalScore();
                
                // Save evaluation
                if (!$evaluation->save(false)) {
                    throw new \Exception('Ошибка сохранения оценки');
                }
                
                $transaction->commit();
                
                if (isset($post['complete'])) {
                    if ($evaluation->complete()) {
                        // Проверяем, все ли эксперты завершили оценку
                        $this->checkAllEvaluationsComplete($application);
                        
                        // Уведомление пользователю о завершении оценки
                        Notification::create(
                            $application->user_id,
                            'Заявка оценена экспертом',
                            "Ваша заявка '{$application->work_name}' была оценена экспертом. " .
                            "Общий балл: {$evaluation->total_score}"
                        );
                        
                        // Уведомление администратору о завершении оценки
                        $admins = User::find()->where(['is_admin' => 1])->all();
                        foreach ($admins as $admin) {
                            Notification::create(
                                $admin->id,
                                'Эксперт завершил оценку',
                                "Эксперт " . Yii::$app->user->identity->name . 
                                " завершил оценку заявки '{$application->work_name}' (ID: {$application->id})"
                            );
                        }
                        
                        Yii::$app->session->setFlash('success', 
                            'Оценка завершена и отправлена. Результаты сохранены.'
                        );
                        return $this->redirect(['index']);
                    } else {
                        throw new \Exception('Ошибка при завершении оценки');
                    }
                } else {
                    Yii::$app->session->setFlash('success', 'Оценка сохранена как черновик.');
                }
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 
                    'Ошибка при сохранении оценки: ' . $e->getMessage()
                );
            }
        }

        return $this->render('evaluate', [
            'application' => $application,
            'evaluation' => $evaluation,
            'criteria' => $criteria,
            'scores' => $scores,
        ]);
    }

    /**
     * Completes an evaluation.
     * @param int $id evaluation ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionComplete($id)
    {
        $evaluation = $this->findEvaluation($id);
        
        if ($evaluation->expert_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('У вас нет доступа к этой оценке.');
        }

        // Проверяем, что все критерии оценены
        $criteriaCount = Criteria::find()
            ->where(['nomination_id' => $evaluation->application->nomination_id, 'is_active' => 1])
            ->count();
        
        $scoresCount = EvaluationScore::find()
            ->where(['evaluation_id' => $id])
            ->andWhere(['>', 'score', 0])
            ->count();
        
        if ($scoresCount < $criteriaCount) {
            Yii::$app->session->setFlash('error', 
                'Не все критерии оценены. Пожалуйста, оцените все критерии перед завершением.'
            );
            return $this->redirect(['evaluate', 'id' => $evaluation->application_id]);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$evaluation->complete()) {
                throw new \Exception('Ошибка при завершении оценки.');
            }
            
            $application = $evaluation->application;
            
            // Проверяем, все ли эксперты завершили оценку
            $this->checkAllEvaluationsComplete($application);
            
            // Уведомление пользователю о завершении оценки
            Notification::create(
                $application->user_id,
                'Заявка оценена экспертом',
                "Ваша заявка '{$application->work_name}' была оценена экспертом. " .
                "Общий балл: {$evaluation->total_score}"
            );
            
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Оценка завершена и отправлена.');
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Ошибка при завершении оценки: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * View application ranking.
     */
    public function actionRanking($contest_id = null, $nomination_id = null)
    {
        $userId = Yii::$app->user->id;
        
        // Находим все заявки, которые оценивал эксперт (завершенные оценки)
        $query = Application::find()
            ->alias('a')
            ->innerJoin(['e' => Evaluation::tableName()], 
                'e.application_id = a.id AND e.expert_id = :expertId', 
                [':expertId' => $userId])
            ->where(['e.status' => 'completed']);
        
        // Проверяем, что у номинации есть критерии
        if ($nomination_id) {
            $criteriaCount = Criteria::find()
                ->where(['nomination_id' => $nomination_id, 'is_active' => 1])
                ->count();
            
            if ($criteriaCount === 0) {
                Yii::$app->session->setFlash('warning', 
                    'Для выбранной номинации не заданы критерии оценки.'
                );
            }
        }
        
        // Применяем фильтры
        if ($contest_id) {
            $query->andWhere(['a.contest_id' => $contest_id]);
        }
        
        if ($nomination_id) {
            $query->andWhere(['a.nomination_id' => $nomination_id]);
        }
        
        // Сортируем по итоговому баллу (среднему) или по нашему баллу, если итогового нет
        $query->orderBy([
            new \yii\db\Expression('COALESCE((SELECT final_score FROM contest_result WHERE application_id = a.id), 0) DESC'),
            'e.total_score' => SORT_DESC
        ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        $contests = Contest::find()->where(['status' => 1])->all();
        $nominations = Nomination::find()->all();

        return $this->render('ranking', [
            'dataProvider' => $dataProvider,
            'contests' => $contests,
            'nominations' => $nominations,
            'selectedContestId' => $contest_id,
            'selectedNominationId' => $nomination_id,
        ]);
    }

    /**
     * Экспорт оценок эксперта в CSV
     */
    public function actionExportEvaluations()
    {
        $userId = Yii::$app->user->id;
        
        // Получаем все завершенные оценки эксперта
        $evaluations = Evaluation::find()
            ->with(['application', 'application.contest', 'application.nomination'])
            ->where(['expert_id' => $userId, 'status' => 'completed'])
            ->all();
        
        if (empty($evaluations)) {
            Yii::$app->session->setFlash('error', 'Нет завершенных оценок для экспорта.');
            return $this->redirect(['index']);
        }
        
        // Создаем CSV файл
        $filename = 'evaluations_expert_' . $userId . '_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $output = fopen('php://output', 'w');
        
        // Добавляем BOM для корректного отображения кириллицы в Excel
        fwrite($output, "\xEF\xBB\xBF");
        
        // Заголовки CSV
        fputcsv($output, [
            'ID заявки',
            'Конкурс',
            'Номинация',
            'Участник',
            'Название работы',
            'Ваша оценка',
            'Итоговый балл',
            'Место',
            'Награда',
            'Дата оценки'
        ], ';');
        
        // Данные
        foreach ($evaluations as $evaluation) {
            $application = $evaluation->application;
            $contestResult = ContestResult::findOne(['application_id' => $application->id]);
            
            fputcsv($output, [
                $application->id,
                $application->contest->name,
                $application->nomination->name,
                $application->surname . ' ' . $application->name . 
                    ($application->patronymic ? ' ' . $application->patronymic : ''),
                $application->work_name,
                $evaluation->total_score,
                $contestResult ? $contestResult->final_score : '—',
                $contestResult ? $contestResult->place : '—',
                $contestResult ? $contestResult->award_type : '—',
                Yii::$app->formatter->asDate($evaluation->updated_at)
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    /**
     * Метод для запроса генерации диплома
     */
    public function actionRequestDiploma($id)
    {
        $application = $this->findApplication($id);
        
        // Проверяем, что эксперт оценивал эту заявку
        $evaluation = Evaluation::find()
            ->where(['application_id' => $id, 'expert_id' => Yii::$app->user->id])
            ->one();
        
        if (!$evaluation) {
            Yii::$app->session->setFlash('error', 'Вы не оценивали эту заявку.');
            return $this->redirect(['ranking']);
        }
        
        // Проверяем, есть ли уже диплом
        $existingDiplomas = GeneratedDocument::findDiplomasByApplicationId($id);
        if (!empty($existingDiplomas)) {
            Yii::$app->session->setFlash('info', 'Для этой заявки уже есть диплом.');
            return $this->redirect(['ranking']);
        }
        
        // Проверяем, есть ли результаты
        $contestResult = ContestResult::findOne(['application_id' => $id]);
        if (!$contestResult || !$contestResult->award_type) {
            Yii::$app->session->setFlash('error', 
                'Для этой заявки еще не определены результаты конкурса. ' .
                'Диплом можно сгенерировать только после определения итоговых результатов.'
            );
            return $this->redirect(['ranking']);
        }
        
        // Отправляем уведомление администратору о запросе диплома
        $admins = User::find()->where(['is_admin' => 1])->all();
        foreach ($admins as $admin) {
            Notification::create(
                $admin->id,
                'Запрос на генерацию диплома',
                "Эксперт " . Yii::$app->user->identity->name . 
                " запросил генерацию диплома для заявки '" . 
                $application->work_name . "' (ID: " . $application->id . ")."
            );
        }
        
        Yii::$app->session->setFlash('success', 
            'Запрос на генерацию диплома отправлен администратору. ' .
            'Вы получите уведомление, когда диплом будет сгенерирован.'
        );
        return $this->redirect(['ranking']);
    }

    /**
     * View generated documents for applications evaluated by expert.
     */
    public function actionDocuments($application_id = null, $type = 'all')
    {
        $userId = Yii::$app->user->id;
        
        // Находим все заявки, которые оценивал эксперт
        $evaluatedApplicationIds = Evaluation::find()
            ->select('application_id')
            ->where(['expert_id' => $userId, 'status' => 'completed'])
            ->column();
        
        if (empty($evaluatedApplicationIds)) {
            Yii::$app->session->setFlash('info', 'Вы еще не завершили ни одной оценки.');
            $evaluatedApplicationIds = [0]; // Пустой массив для запроса
        }
        
        $query = GeneratedDocument::find()
            ->with(['application', 'application.contest'])
            ->where(['application_id' => $evaluatedApplicationIds]);
        
        // Фильтр по конкретной заявке
        if ($application_id) {
            $query->andWhere(['application_id' => $application_id]);
        }
        
        // Фильтр по типу документа
        if ($type !== 'all') {
            $query->andWhere(['document_type' => $type]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => ['generated_at' => SORT_DESC],
            ],
        ]);

        return $this->render('documents', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Проверяет, завершили ли все эксперты оценку заявки.
     * Если да - обновляет статус заявки и создает ContestResult.
     */
    private function checkAllEvaluationsComplete($application)
    {
        try {
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
                $application->status = Application::STATUS_GRADED;
                if (!$application->save(false)) {
                    throw new \Exception('Ошибка обновления статуса заявки');
                }
                
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
                
                // Уведомление пользователю
                Notification::create(
                    $application->user_id,
                    'Все эксперты завершили оценку',
                    "Все эксперты завершили оценку вашей заявки '{$application->work_name}'. " .
                    "Результаты будут обработаны администратором."
                );
            }
        } catch (\Exception $e) {
            Yii::error('Ошибка в checkAllEvaluationsComplete: ' . $e->getMessage());
        }
    }

    /**
     * Создает или обновляет ContestResult для заявки.
     */
    private function createOrUpdateContestResult($application)
    {
        try {
            // Вычисляем средний балл по всем оценкам
            $evaluations = Evaluation::find()
                ->where(['application_id' => $application->id, 'status' => 'completed'])
                ->all();
            
            if (empty($evaluations)) {
                return;
            }
            
            $totalScore = 0;
            $expertCount = 0;
            
            foreach ($evaluations as $evaluation) {
                if ($evaluation->total_score > 0) {
                    $totalScore += $evaluation->total_score;
                    $expertCount++;
                }
            }
            
            if ($expertCount === 0) {
                return;
            }
            
            $averageScore = round($totalScore / $expertCount, 2);
           
            $contestResult = ContestResult::findByApplicationId($application->id);
            
            if (!$contestResult) {
                $contestResult = new ContestResult();
                $contestResult->application_id = $application->id;
            }
            
            $contestResult->final_score = $averageScore;
            
            if (!$contestResult->save(false)) {
                throw new \Exception('Ошибка сохранения результатов конкурса');
            }
            
        } catch (\Exception $e) {
            Yii::error('Ошибка в createOrUpdateContestResult: ' . $e->getMessage());
        }
    }

    /**
     * Finds the Application model based on its primary key value.
     * @param int $id ID
     * @return Application the loaded model
     * @throws NotFoundHttpException if the model cannot be found
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
     * @param int $id ID
     * @return Evaluation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findEvaluation($id)
    {
        if (($model = Evaluation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Оценка не найдена.');
    }
}