<?php

namespace app\models;

use Yii;

/**
 * 
 * @property int $id
 * @property int $application_id
 * @property string $document_type
 * @property string $file_path
 * @property string $generated_at
 * 
 * @property Application $application
 */
class GeneratedDocument extends \yii\db\ActiveRecord
{
    const TYPE_DIPLOMA = 'diploma';
    const TYPE_CERTIFICATE = 'certificate';

    const TYPE_PROGRAM = 'program';
    const TYPE_EVALUATION_SHEET = 'evaluation_sheet';
    const TYPE_ALBUM = 'album'; 

    public static function tableName()
    {
        return '{{%generated_document}}';
    }

    public function rules()
    {
        return [
            [['application_id', 'document_type', 'file_path'], 'required'],
            [['application_id'], 'integer'],
            [['document_type'], 'string', 'max' => 20],
            [['file_path'], 'string', 'max' => 255],
            [['document_type'], 'in', 'range' => [
                self::TYPE_DIPLOMA, 
                self::TYPE_CERTIFICATE,
                self::TYPE_PROGRAM,
                self::TYPE_EVALUATION_SHEET,
                self::TYPE_ALBUM
            ]],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::class, 'targetAttribute' => ['application_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_id' => 'Заявка',
            'document_type' => 'Тип документа',
            'file_path' => 'Путь к файлу',
            'generated_at' => 'Дата генерации',
        ];
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_DIPLOMA => 'Диплом',
            self::TYPE_CERTIFICATE => 'Сертификат',
            self::TYPE_PROGRAM => 'Программа конкурса',
            self::TYPE_EVALUATION_SHEET => 'Сводный оценочный лист',
            self::TYPE_ALBUM => 'Альбом работ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::class, ['id' => 'application_id']);
    }


    public function getFileUrl()
    {
        if (!$this->file_path) {
            return null;
        }
        return Yii::$app->request->baseUrl . '/' . $this->file_path;
    }



    public function getAbsolutePath()
    {
        if ($this->file_path) {
            return Yii::getAlias('@webroot/' . $this->file_path);
        }
        return null;
    }



    public function fileExists()
    {
        $path = $this->getAbsolutePath();
        return $path && file_exists($path);
    }


    public function getDocumentTypeName()
    {
        $types = [
            'diploma' => 'Диплом',
            'certificate' => 'Сертификат',
        ];
        
        return $types[$this->document_type] ?? $this->document_type;
    }


    public function getFileExtension()
    {
        if (!$this->file_path) {
            return '';
        }
        return strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }


    public function getFileSize()
{
    $path = $this->getAbsolutePath();
    if (!$path || !file_exists($path)) {
        return 'N/A';
    }
    
    $size = filesize($path);
    $units = ['B', 'KB', 'MB', 'GB'];
    $unitIndex = 0;
    
    while ($size >= 1024 && $unitIndex < count($units) - 1) {
        $size /= 1024;
        $unitIndex++;
    }
    
    return round($size, 2) . ' ' . $units[$unitIndex];
}

    public function getUrl()
    {
        if ($this->file_path) {
            return Yii::getAlias('@web/' . $this->file_path);
        }
        return null;
    }




    public static function findByApplicationId($applicationId)
    {
        return self::find()->where(['application_id' => $applicationId])->all();
    }


    public static function findByContestId($contestId)
    {
        return self::find()
            ->joinWith('application')
            ->where(['application.contest_id' => $contestId])
            ->all();
    }


    public static function findDiplomasByApplicationId($applicationId)
    {
        return self::find()
            ->where(['application_id' => $applicationId, 'document_type' => self::TYPE_DIPLOMA])
            ->all();
    }


    public static function findCertificatesByApplicationId($applicationId)
    {
        return self::find()
            ->where(['application_id' => $applicationId, 'document_type' => self::TYPE_CERTIFICATE])
            ->all();
    }
}
