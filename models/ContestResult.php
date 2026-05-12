<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contest_result".
 *
 * @property int $id
 * @property int|null $application_id
 * @property float|null $final_score
 * @property int|null $place
 * @property string|null $award_type
 * @property string $created_at
 *
 * @property Application $application
 */
class ContestResult extends \yii\db\ActiveRecord
{
    const AWARD_FIRST = 'first';
    const AWARD_SECOND = 'second';
    const AWARD_THIRD = 'third';
    const AWARD_LAUREATE = 'laureate';
    const AWARD_DIPLOMA = 'diploma';
    const AWARD_CERTIFICATE = 'certificate';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contest_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'place'], 'integer'],
            [['final_score'], 'number'],
            [['created_at'], 'safe'],
            [['award_type'], 'string', 'max' => 50],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_id' => 'Заявка',
            'final_score' => 'Итоговый балл',
            'place' => 'Место',
            'award_type' => 'Тип награды',
            'created_at' => 'Дата создания',
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
     * Получить текстовое описание награды
     * @return string
     */
    public function getAwardText()
    {
        $awardTypes = [
            self::AWARD_FIRST => 'Диплом I степени',
            self::AWARD_SECOND => 'Диплом II степени',
            self::AWARD_THIRD => 'Диплом III степени',
            self::AWARD_LAUREATE => 'Диплом лауреата',
            self::AWARD_DIPLOMA => 'Диплом',
            self::AWARD_CERTIFICATE => 'Сертификат участника',
        ];
        
        return isset($awardTypes[$this->award_type]) ? $awardTypes[$this->award_type] : $this->award_type;
    }
    
    /**
     * Получить текстовое описание места
     * @return string
     */
    public function getPlaceText()
    {
        if (!$this->place) {
            return 'Не указано';
        }
        
        $placeEndings = [
            1 => '1 место',
            2 => '2 место',
            3 => '3 место',
        ];
        
        return isset($placeEndings[$this->place]) ? $placeEndings[$this->place] : $this->place . ' место';
    }
    
    /**
     * Найти результат по ID заявки
     * @param int $applicationId
     * @return ContestResult|null
     */
    public static function findByApplicationId($applicationId)
    {
        return static::findOne(['application_id' => $applicationId]);
    }
    
    /**
     * Найти все результаты по ID конкурса
     * @param int $contestId
     * @return array
     */
    public static function findByContestId($contestId)
    {
        return static::find()
            ->joinWith('application')
            ->where(['application.contest_id' => $contestId])
            ->orderBy(['place' => SORT_ASC, 'final_score' => SORT_DESC])
            ->all();
    }
    
    /**
     * Получить список типов наград
     * @return array
     */
    public static function getAwardTypesList()
    {
        return [
            self::AWARD_FIRST => 'Диплом I степени',
            self::AWARD_SECOND => 'Диплом II степени',
            self::AWARD_THIRD => 'Диплом III степени',
            self::AWARD_LAUREATE => 'Диплом лауреата',
            self::AWARD_DIPLOMA => 'Диплом',
            self::AWARD_CERTIFICATE => 'Сертификат участника',
        ];
    }
}