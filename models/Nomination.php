<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nomination".
 *
 * @property int $id
 * @property int $contest_id
 * @property string $name
 * @property string|null $description
 * @property int|null $max_participants
 * @property int|null $order
 *
 * @property Application[] $applications
 * @property Contest $contest
 * @property Criteria[] $criteria
 * @property ExpertAssignment[] $expertAssignments
 */
class Nomination extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%nomination}}';
    }

    public function rules()
    {
        return [
            [['contest_id', 'name'], 'required'],
            [['contest_id', 'max_participants', 'order'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['contest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contest::class, 'targetAttribute' => ['contest_id' => 'id']],
            ['max_participants', 'default', 'value' => 0],
            ['max_participants', 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contest_id' => 'Конкурс',
            'name' => 'Название',
            'description' => 'Описание',
            'max_participants' => 'Макс. участников',
            'order' => 'Порядок',
        ];
    }

    /**
     * Gets query for [[Applications]].
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['nomination_id' => 'id']);
    }

    /**
     * Gets query for [[Contest]].
     */
    public function getContest()
    {
        return $this->hasOne(Contest::class, ['id' => 'contest_id']);
    }

    /**
     * Gets query for [[Criteria]].
     */
    public function getCriteria()
    {
        return $this->hasMany(Criteria::class, ['nomination_id' => 'id']);
    }

    /**
     * Gets query for [[ExpertAssignments]].
     */
    public function getExpertAssignments()
    {
        return $this->hasMany(ExpertAssignment::class, ['nomination_id' => 'id']);
    }

    /**
     * Check if nomination has reached participant limit
     */
    public function hasReachedLimit()
    {
        if ($this->max_participants <= 0) {
            return false;
        }
        
        $currentCount = Application::find()
            ->where(['nomination_id' => $this->id])
            ->andWhere(['!=', 'status', 'blocked'])
            ->count();
        
        return $currentCount >= $this->max_participants;
    }

    /**
     * Get current participant count
     */
    public function getCurrentParticipantCount()
    {
        return Application::find()
            ->where(['nomination_id' => $this->id])
            ->andWhere(['!=', 'status', 'blocked'])
            ->count();
    }

    /**
     * Get available spots
     */
    public function getAvailableSpots()
    {
        if ($this->max_participants <= 0) {
            return 'Не ограничено';
        }
        
        $current = $this->getCurrentParticipantCount();
        return max(0, $this->max_participants - $current);
    }

}