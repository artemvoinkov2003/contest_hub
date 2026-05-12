<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contest".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property string $start_date
 * @property string $end_date
 * @property int $status
 * @property string $created_at
 *
 * @property AgeCategory[] $ageCategories
 * @property Application[] $applications
 * @property Nomination[] $nominations
 */
class Contest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'start_date', 'end_date'], 'required'],
            [['description'], 'string'],
            [['start_date', 'end_date'], 'safe'], // Изменено на 'safe'
            [['status'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 255],
            [['end_date'], 'compare', 'compareAttribute' => 'start_date', 'operator' => '>', 'message' => 'Дата окончания должна быть позже даты начала'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'image' => 'Изображение',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'status' => 'Статус',
            'created_at' => 'Создан',
        ];
    }

    /**
     * Gets query for [[AgeCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgeCategories()
    {
        return $this->hasMany(AgeCategory::class, ['contest_id' => 'id']);
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['contest_id' => 'id']);
    }
    
    public function getContestResults()
    {
        return $this->hasMany(ContestResult::class, ['contest_id' => 'id']);
    }

    public function getExpertAssignments()
    {
        return $this->hasMany(ExpertAssignment::class, ['contest_id' => 'id']);
    }

    public function getReportTemplates()
    {
        return $this->hasMany(ReportTemplate::class, ['contest_id' => 'id']);
    }

    /**
     * Gets query for [[Nominations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNominations()
    {
        return $this->hasMany(Nomination::class, ['contest_id' => 'id']);
    }

    /**
     * Получает текстовое представление статуса
     * @return string
     */
    public function getStatusLabel()
    {
        return $this->status == 1 ? 'Активен' : 'Неактивен';
    }

    /**
     * Проверяет, активен ли конкурс
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * Проверяет, завершен ли конкурс
     * @return bool
     */
    public function isEnded()
    {
        $today = date('Y-m-d');
        return strtotime($today) > strtotime($this->end_date);
    }

    /**
     * Проверяет, начался ли конкурс
     * @return bool
     */
    public function isStarted()
    {
        $today = date('Y-m-d');
        return strtotime($today) >= strtotime($this->start_date);
    }

    /**
     * Проверяет, идет ли конкурс сейчас
     * @return bool
     */
    public function isInProgress()
    {
        $today = date('Y-m-d');
        return strtotime($today) >= strtotime($this->start_date) && 
               strtotime($today) <= strtotime($this->end_date);
    }
}