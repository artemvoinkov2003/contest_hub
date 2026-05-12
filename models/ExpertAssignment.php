<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expert_assignment".
 *
 * @property int $id
 * @property int $expert_id
 * @property int $contest_id
 * @property int $nomination_id
 * @property int $age_category_id
 * @property string $created_at
 *
 * @property User $expert
 * @property Contest $contest
 * @property Nomination $nomination
 * @property AgeCategory $ageCategory
 */
class ExpertAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expert_id', 'contest_id', 'nomination_id', 'age_category_id'], 'required'],
            [['expert_id', 'contest_id', 'nomination_id', 'age_category_id'], 'integer'],
            [['created_at'], 'safe'],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['expert_id' => 'id']],
            [['contest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contest::class, 'targetAttribute' => ['contest_id' => 'id']],
            [['nomination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nomination::class, 'targetAttribute' => ['nomination_id' => 'id']],
            ['expert_id', 'unique', 'targetAttribute' => 
                ['expert_id', 'contest_id', 'nomination_id', 'age_category_id'],
                'message' => 'Этот эксперт уже назначен на эту комбинацию конкурса, номинации и возрастной категории.'
            ],
            [['age_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgeCategory::class, 'targetAttribute' => ['age_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expert_id' => 'Эксперт',
            'contest_id' => 'Конкурс',
            'nomination_id' => 'Номинация',
            'age_category_id' => 'Возрастная категория',
            'created_at' => 'Дата назначения',
        ];
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
     * Gets query for [[Contest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContest()
    {
        return $this->hasOne(Contest::class, ['id' => 'contest_id']);
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
     * Gets query for [[AgeCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgeCategory()
    {
        return $this->hasOne(AgeCategory::class, ['id' => 'age_category_id']);
    }

    /**
     * Get applications assigned to expert
     */
    public static function getAssignedApplications($expertId)
    {
        return Application::find()
            ->alias('a')
            ->innerJoin(['ea' => self::tableName()], 'ea.contest_id = a.contest_id AND ea.nomination_id = a.nomination_id AND ea.age_category_id = a.age_category_id')
            ->where(['ea.expert_id' => $expertId])
            ->andWhere(['a.status' => 'new'])
            ->orderBy(['a.created_at' => SORT_DESC]);
    }
}