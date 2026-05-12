<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class ApplicationSearch extends Application
{
    public $user_login;
    public $has_results;
    public $has_documents;
    public $award_type;
    
    public function rules()
    {
        return [
            [['id', 'user_id', 'contest_id', 'nomination_id', 'age_category_id'], 'integer'],
            [['work_name', 'surname', 'name', 'patronymic', 'user_login', 'status', 'award_type', 'institution', 'leader'], 'safe'],
            [['has_results', 'has_documents'], 'safe'], // Изменено с integer на safe
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    public function search($params, $isAdmin = false, $userId = null)
    {
        $query = Application::find()
            ->alias('app')
            ->joinWith(['user u', 'contest c', 'nomination n', 'ageCategory ac']);
        
        if (!$isAdmin && $userId !== null) {
            $query->andWhere(['app.user_id' => $userId]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['app.id' => SORT_ASC],
                        'desc' => ['app.id' => SORT_DESC],
                    ],
                    'work_name' => [
                        'asc' => ['app.work_name' => SORT_ASC],
                        'desc' => ['app.work_name' => SORT_DESC],
                    ],
                    'surname' => [
                        'asc' => ['app.surname' => SORT_ASC],
                        'desc' => ['app.surname' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['app.name' => SORT_ASC],
                        'desc' => ['app.name' => SORT_DESC],
                    ],
                    'status' => [
                        'asc' => ['app.status' => SORT_ASC],
                        'desc' => ['app.status' => SORT_DESC],
                    ],
                    'created_at' => [
                        'asc' => ['app.created_at' => SORT_ASC],
                        'desc' => ['app.created_at' => SORT_DESC],
                    ],
                    'contest.name' => [
                        'asc' => ['c.name' => SORT_ASC],
                        'desc' => ['c.name' => SORT_DESC],
                        'label' => 'Конкурс',
                    ],
                    'nomination.name' => [
                        'asc' => ['n.name' => SORT_ASC],
                        'desc' => ['n.name' => SORT_DESC],
                        'label' => 'Номинация',
                    ],
                    'ageCategory.name' => [
                        'asc' => ['ac.name' => SORT_ASC],
                        'desc' => ['ac.name' => SORT_DESC],
                        'label' => 'Возрастная категория',
                    ],
                    'user.login' => [
                        'asc' => ['u.login' => SORT_ASC],
                        'desc' => ['u.login' => SORT_DESC],
                        'label' => 'Логин пользователя',
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $this->load($params);

        if (!$this->validate()) {

        }
        $query->andFilterWhere(['app.id' => $this->id])
              ->andFilterWhere(['app.user_id' => $this->user_id])
              ->andFilterWhere(['app.contest_id' => $this->contest_id])
              ->andFilterWhere(['app.nomination_id' => $this->nomination_id])
              ->andFilterWhere(['app.age_category_id' => $this->age_category_id])
              ->andFilterWhere(['app.status' => $this->status])
              ->andFilterWhere(['like', 'app.work_name', $this->work_name])
              ->andFilterWhere(['like', 'app.surname', $this->surname])
              ->andFilterWhere(['like', 'app.name', $this->name])
              ->andFilterWhere(['like', 'app.patronymic', $this->patronymic])
              ->andFilterWhere(['like', 'app.institution', $this->institution])
              ->andFilterWhere(['like', 'app.leader', $this->leader]);
        
        if (!empty($this->user_login)) {
            $query->andFilterWhere(['like', 'u.login', $this->user_login]);
        }
        
        if ($this->has_results !== null && $this->has_results !== '') {
            if ($this->has_results == '1') {
                $query->andWhere(['exists', 
                    (new \yii\db\Query())
                        ->select('*')
                        ->from('contest_result cr')
                        ->where('cr.application_id = app.id')
                ]);
            } elseif ($this->has_results == '0') {
                $query->andWhere(['not exists', 
                    (new \yii\db\Query())
                        ->select('*')
                        ->from('contest_result cr')
                        ->where('cr.application_id = app.id')
                ]);
            }
        }
        
        if ($this->has_documents !== null && $this->has_documents !== '') {
            if ($this->has_documents == '1') {
                $query->andWhere(['exists', 
                    (new \yii\db\Query())
                        ->select('*')
                        ->from('generated_document gd')
                        ->where('gd.application_id = app.id')
                ]);
            } elseif ($this->has_documents == '0') {
                $query->andWhere(['not exists', 
                    (new \yii\db\Query())
                        ->select('*')
                        ->from('generated_document gd')
                        ->where('gd.application_id = app.id')
                ]);
            }
        }
        
        if (!empty($this->award_type)) {
            $query->andWhere(['exists', 
                (new \yii\db\Query())
                    ->select('*')
                    ->from('contest_result cr')
                    ->where('cr.application_id = app.id')
                    ->andWhere(['like', 'cr.award_type', $this->award_type])
            ]);
        }
        
        return $dataProvider;
    }

    /**
 * Поиск заявок для эксперта
 * 
 * @param array $params параметры поиска
 * @param int $expertId ID эксперта
 * @return ActiveDataProvider
 */
public function searchForExpert($params, $expertId)
{
    $query = Application::find()
        ->alias('a')
        ->joinWith(['contest c', 'nomination n', 'ageCategory ac'])
        ->where(['!=', 'a.status', Application::STATUS_BLOCKED])
        ->orderBy(['a.created_at' => SORT_DESC]);

    // Проверяем, является ли пользователь администратором
    $user = User::findOne($expertId);
    
    if (!$user->is_admin) {
        // Получаем ID конкурсов, номинаций и возрастных категорий, назначенных эксперту
        $assignments = ExpertAssignment::find()
            ->where(['expert_id' => $expertId])
            ->all();
        
        if (!empty($assignments)) {
            $orConditions = ['or'];
            
            foreach ($assignments as $assignment) {
                $orConditions[] = [
                    'a.contest_id' => $assignment->contest_id,
                    'a.nomination_id' => $assignment->nomination_id,
                    'a.age_category_id' => $assignment->age_category_id,
                ];
            }
            
            $query->andWhere($orConditions);
        } else {
            // Если нет назначений, не показываем ничего
            $query->andWhere('1=0');
        }
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    // Фильтрация
    $query->andFilterWhere([
        'a.id' => $this->id,
        'a.contest_id' => $this->contest_id,
        'a.nomination_id' => $this->nomination_id,
        'a.age_category_id' => $this->age_category_id,
    ]);

    $query->andFilterWhere(['like', 'a.work_name', $this->work_name])
        ->andFilterWhere(['like', 'a.surname', $this->surname])
        ->andFilterWhere(['like', 'a.name', $this->name])
        ->andFilterWhere(['like', 'a.status', $this->status]);

    return $dataProvider;
}

}