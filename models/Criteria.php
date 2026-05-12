<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "criteria".
 *
 * @property int $id
 * @property int $nomination_id
 * @property string $name
 * @property int $max_score
 * @property int $order
 * @property string $created_at
 *
 * @property Nomination $nomination
 * @property EvaluationScore[] $evaluationScores
 */
class Criteria extends \yii\db\ActiveRecord
{

    const CRITERIA_MASTERY = 'mastery';
    const CRITERIA_ARTISTRY = 'artistry';
    const CRITERIA_STAGE_CULTURE = 'stage_culture';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'criteria';
    }

    public static function getDefaultCriteria()
    {
        return [
            ['name' => 'Мастерство по направлению', 'max_score' => 10],
            ['name' => 'Артистизм / Раскрытие художественного образа', 'max_score' => 10],
            ['name' => 'Сценическая культура', 'max_score' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomination_id', 'name'], 'required'],
            [['nomination_id', 'max_score', 'order'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['nomination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nomination::class, 'targetAttribute' => ['nomination_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomination_id' => 'Номинация',
            'name' => 'Название критерия',
            'max_score' => 'Максимальный балл',
            'order' => 'Порядок',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[Nomination]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNomination()
    {
        return $this->hasOne(Nomination::class, ['id' => 'nomination_id']);
    }

    /**
     * Gets query for [[EvaluationScores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluationScores()
    {
        return $this->hasMany(EvaluationScore::class, ['criteria_id' => 'id']);
    }

    /**
     * Get criteria for nomination
     */
    public static function getForNomination($nominationId)
    {
        return self::find()
            ->where(['nomination_id' => $nominationId])
            ->orderBy(['order' => SORT_ASC])
            ->all();
    }
}