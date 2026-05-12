<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class EvaluationSearch extends Evaluation
{
    public function rules()
    {
        return [
            [['id', 'application_id', 'expert_id'], 'integer'],
            [['status', 'notes'], 'safe'],
            [['total_score'], 'number'],
        ];
    }
    
    public function search($params, $expertId = null)
    {
        $query = Evaluation::find()
            ->joinWith(['application', 'application.contest', 'application.nomination']);
        
        if ($expertId !== null) {
            $query->andWhere(['expert_id' => $expertId]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'application_id' => $this->application_id,
            'expert_id' => $this->expert_id,
            'status' => $this->status,
            'total_score' => $this->total_score,
        ]);
        
        $query->andFilterWhere(['like', 'notes', $this->notes]);
        
        return $dataProvider;
    }
}