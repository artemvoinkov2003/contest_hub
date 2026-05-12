<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evaluation_score".
 *
 * @property int $id
 * @property int $evaluation_id
 * @property int $criteria_id
 * @property int $score
 *
 * @property Evaluation $evaluation
 * @property Criteria $criteria
 */
class EvaluationScore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation_score';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evaluation_id', 'criteria_id', 'score'], 'required'],
            [['evaluation_id', 'criteria_id', 'score'], 'integer'],
            [['evaluation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Evaluation::class, 'targetAttribute' => ['evaluation_id' => 'id']],
            [['criteria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Criteria::class, 'targetAttribute' => ['criteria_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'evaluation_id' => 'Оценка',
            'criteria_id' => 'Критерий',
            'score' => 'Балл',
        ];
    }

    /**
     * Gets query for [[Evaluation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluation()
    {
        return $this->hasOne(Evaluation::class, ['id' => 'evaluation_id']);
    }

    /**
     * Gets query for [[Criteria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCriteria()
    {
        return $this->hasOne(Criteria::class, ['id' => 'criteria_id']);
    }
}