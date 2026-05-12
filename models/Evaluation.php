<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evaluation".
 *
 * @property int $id
 * @property int $application_id
 * @property int $expert_id
 * @property string $status
 * @property string $total_score
 * @property string|null $notes
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Application $application
 * @property User $expert
 * @property EvaluationScore[] $evaluationScores
 */
class Evaluation extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_COMPLETED = 'completed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evaluation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'expert_id'], 'required'],
            [['application_id', 'expert_id'], 'integer'],
            [['notes'], 'string'],
            [['total_score'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['expert_id' => 'id']],
            [['application_id', 'expert_id'], 'unique', 'targetAttribute' => ['application_id', 'expert_id'], 
         'message' => 'Этот эксперт уже оценил данную заявку'],
        ];
    }

    public function afterSave($insert, $changedAttributes)
{
    parent::afterSave($insert, $changedAttributes);
    
    $contestResult = ContestResult::findOne(['application_id' => $this->application_id]);
    
    if (!$contestResult) {
        $contestResult = new ContestResult();
        $contestResult->application_id = $this->application_id;
        $contestResult->contest_id = $this->application->contest_id;
    }
    
    $averageScore = Evaluation::find()
        ->where(['application_id' => $this->application_id])
        ->average('score');
    
    $contestResult->final_score = $averageScore;
    $contestResult->save();
}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_id' => 'Заявка',
            'expert_id' => 'Эксперт',
            'status' => 'Статус',
            'total_score' => 'Общий балл',
            'notes' => 'Примечания',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::class, ['id' => 'application_id']);
    }

    /**
     * Gets query for [[Expert]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(User::class, ['id' => 'expert_id']);
    }

    /**
     * Gets query for [[EvaluationScores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluationScores()
    {
        return $this->hasMany(EvaluationScore::class, ['evaluation_id' => 'id']);
    }

    /**
     * Calculate total score
     */
    public function calculateTotalScore()
    {
        $total = 0;
        foreach ($this->evaluationScores as $score) {
            $total += $score->score;
        }
        $this->total_score = $total;
        return $total;
    }

    /**
     * Complete evaluation
     */
    public function complete()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->calculateTotalScore();
        return $this->save();
    }

    /**
     * Get evaluation status label
     */
    public function getStatusLabel()
    {
        $statuses = [
            'draft' => 'Черновик',
            'completed' => 'Завершена'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

}