<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "report_template".
 *
 * @property int $id
 * @property int|null $contest_id
 * @property string|null $type
 * @property string|null $template_file
 * @property string $created_at
 *
 * @property Contest $contest
 * @property string $templateFile Виртуальное свойство для загрузки файла
 */
class ReportTemplate extends \yii\db\ActiveRecord
{
    const TYPE_DIPLOMA = 'diploma';
    const TYPE_CERTIFICATE = 'certificate';
    const TYPE_PROGRAM = 'program';
    const TYPE_EVALUATION_SHEET = 'evaluation_sheet';
    const TYPE_ALBUM = 'album';
    
    public $templateFile; // Добавлено виртуальное свойство для загрузки файла
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contest_id'], 'integer'],
            [['created_at'], 'safe'],
            [['type', 'template_file'], 'string', 'max' => 255],
            [['contest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contest::class, 'targetAttribute' => ['contest_id' => 'id']],
            [['templateFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'html, htm'],
            [['type'], 'required', 'message' => 'Тип шаблона обязателен'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contest_id' => 'Конкурс',
            'type' => 'Тип шаблона',
            'template_file' => 'Файл шаблона',
            'created_at' => 'Дата создания',
            'templateFile' => 'Файл шаблона',
        ];
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
     * Gets all available types
     */
    public static function getAllTypes()
    {
        return [
            self::TYPE_DIPLOMA => 'Диплом',
            self::TYPE_CERTIFICATE => 'Сертификат',
            self::TYPE_PROGRAM => 'Программа конкурса',
            self::TYPE_EVALUATION_SHEET => 'Оценочный лист',
            self::TYPE_ALBUM => 'Альбом работ',
        ];
    }
    
    /**
     * Finds template by type and contest
     */
    public static function findByTypeAndContest($type, $contest_id = null)
    {
        return self::find()
            ->where(['type' => $type])
            ->andWhere(['or', ['contest_id' => $contest_id], ['contest_id' => null]])
            ->orderBy(['contest_id' => SORT_DESC])
            ->one();
    }
    
    /**
     * Проверяет существование файла
     */
    public function fileExists()
    {
        return $this->template_file && file_exists($this->getAbsolutePath());
    }
    
    /**
     * Получает абсолютный путь к файлу
     */
    public function getAbsolutePath()
    {
        return Yii::getAlias('@webroot/uploads/templates/' . $this->template_file);
    }
    
    /**
     * Загружает файл шаблона
     */
    public function upload()
    {
        if ($this->templateFile) {
            $fileName = time() . '_' . $this->templateFile->baseName . '.' . $this->templateFile->extension;
            $uploadPath = Yii::getAlias('@webroot/uploads/templates/');
            
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if ($this->templateFile->saveAs($uploadPath . $fileName)) {
                $this->template_file = $fileName;
                return true;
            }
        }
        return false;
    }
    
    /**
     * Получает имя шаблона для отображения
     */
    public function getDisplayName()
    {
        return $this->template_file ? basename($this->template_file) : 'Без имени';
    }
}