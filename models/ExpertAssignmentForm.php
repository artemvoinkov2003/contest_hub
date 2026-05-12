<?php
namespace app\models;

use yii\base\Model;

class ExpertAssignmentForm extends Model
{
    public $contest_id;
    public $nomination_id;
    public $age_category_id;
    public $expert_ids = [];
    
    public function rules()
    {
        return [
            [['contest_id', 'nomination_id', 'age_category_id'], 'required'],
            ['expert_ids', 'each', 'rule' => ['integer']],
            ['expert_ids', 'required', 'message' => 'Выберите хотя бы одного эксперта'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'contest_id' => 'Конкурс',
            'nomination_id' => 'Номинация',
            'age_category_id' => 'Возрастная категория',
            'expert_ids' => 'Эксперты',
        ];
    }
}