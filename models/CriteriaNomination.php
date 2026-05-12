<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "criteria_nomination".
 *
 * @property int $id
 * @property int $criteria_id
 * @property int $nomination_id
 * @property int $weight
 * @property int $order
 * @property string $created_at
 *
 * @property Criteria $criteria
 * @property Nomination $nomination
 */
class CriteriaNomination extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'criteria_nomination';
    }

    public function rules()
    {
        return [
            [['criteria_id', 'nomination_id'], 'required'],
            [['criteria_id', 'nomination_id', 'weight', 'order'], 'integer'],
            [['created_at'], 'safe'],
            [['criteria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Criteria::class, 'targetAttribute' => ['criteria_id' => 'id']],
            [['nomination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nomination::class, 'targetAttribute' => ['nomination_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'criteria_id' => 'Критерий',
            'nomination_id' => 'Номинация',
            'weight' => 'Вес',
            'order' => 'Порядок',
            'created_at' => 'Дата создания',
        ];
    }

    public function getCriteria()
    {
        return $this->hasOne(Criteria::class, ['id' => 'criteria_id']);
    }

    public function getNomination()
    {
        return $this->hasOne(Nomination::class, ['id' => 'nomination_id']);
    }
}