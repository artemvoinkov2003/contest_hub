<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $user_id
 * @property int $contest_id
 * @property int $nomination_id
 * @property int $age_category_id
 * @property string $name
 * @property string $surname
 * @property string|null $patronymic
 * @property string $work_name
 * @property string $file_path
 * @property string|null $institution
 * @property string|null $leader
 * @property string $status
 * @property string $created_at
 *
 * @property AgeCategory $ageCategory
 * @property Contest $contest
 * @property Nomination $nomination
 * @property User $user
 */
class Application extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';               
    const STATUS_UNDER_REVIEW = 'accepted'; 
    const STATUS_BLOCKED = 'blocked';       
    const STATUS_GRADED = 'graded';        
    const STATUS_COMPLETED = 'Завершена';

    public $file; 
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'contest_id', 'nomination_id', 'age_category_id', 'name', 'surname', 'work_name'], 'required'],
            [['user_id', 'contest_id', 'nomination_id', 'age_category_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 100],
            [['work_name', 'file_path', 'institution', 'leader'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'mp4, mkv, png, avi, jpg, jpeg, pdf', 
             'maxSize' => 100 * 1024 * 1024], // 100MB
            [['age_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgeCategory::class, 'targetAttribute' => ['age_category_id' => 'id']],
            [['contest_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contest::class, 'targetAttribute' => ['contest_id' => 'id']],
            [['nomination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Nomination::class, 'targetAttribute' => ['nomination_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function getEvaluations()
    {
        return $this->hasMany(Evaluation::class, ['application_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'contest_id' => 'Конкурс',
            'nomination_id' => 'Номинация',
            'age_category_id' => 'Возрастная категория',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'work_name' => 'Название работы',
            'file_path' => 'Файл',
            'institution' => 'Учреждение',
            'leader' => 'Руководитель',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
        ];
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
     * Gets query for [[Contest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContest()
    {
        return $this->hasOne(Contest::class, ['id' => 'contest_id']);
    }

    public function getFullName()
    {
        return trim($this->surname . ' ' . $this->name . ' ' . $this->patronymic);
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    
    /**
     * Получает текстовое представление статуса
     * @return string
     */
     public static function getStatusLabels()
    {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_UNDER_REVIEW => 'На проверке',
            self::STATUS_BLOCKED => 'Заблокирована',
            self::STATUS_GRADED => 'Оценена',
            self::STATUS_COMPLETED => 'Завершена',
        ];
    }

    public function getContestResult()
    {
        return $this->hasOne(ContestResult::class, ['application_id' => 'id']);
    }

    public function getResult()
    {
        return $this->contestResult;
    }

    public function getStatusLabel()
    {
        $labels = self::getStatusLabels();
        return isset($labels[$this->status]) ? $labels[$this->status] : $this->status;
    }
    
    /**
     * Получает все доступные статусы
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_NEW => 'Новая',            
            self::STATUS_BLOCKED => 'Заблокирована',
            self::STATUS_GRADED => 'Оценена',            
            self::STATUS_UNDER_REVIEW => 'На рассмотрении',
            self::STATUS_COMPLETED => 'Завершена',
        ];
    }

    public function getCreatedDate()
    {
        return $this->created_at ? Yii::$app->formatter->asDate($this->created_at, 'php:Y-m-d') : null;
    }

    public function getCreatedDateTime()
    {
        return $this->created_at ? Yii::$app->formatter->asDatetime($this->created_at) : null;
    }

    /**
     * Проверяет, может ли заявка быть отменена
     * @return bool
     */
    public function canBeCancelled()
    {
        return $this->status === self::STATUS_NEW;
    }

    public function canBeEdited()
    {
        return in_array($this->status, [self::STATUS_NEW, self::STATUS_UNDER_REVIEW]);
    }

    public function isBlocked()
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function fileExists()
    {
        $path = $this->getFilePath();
        return $path && file_exists($path);
    }

    public function getFilePath()
    {
        if (!$this->file_path) {
            return null;
        }
        
        // Проверяем, является ли путь уже абсолютным
        if (strpos($this->file_path, '/') === 0 || strpos($this->file_path, ':') === 1) {
            return $this->file_path;
        }
        
        // Возвращаем путь относительно webroot
        return Yii::getAlias('@webroot') . '/' . $this->file_path;
    }

    public function getFileUrl()
    {
        if (!$this->file_path) {
            return null;
        }
        
        // Проверяем, является ли путь уже абсолютным URL
        if (strpos($this->file_path, 'http') === 0) {
            return $this->file_path;
        }
        
        // Возвращаем URL относительно webroot
        return Yii::getAlias('@web') . '/' . $this->file_path;
    }

    public function isImage()
    {
        if (!$this->file_path) {
            return false;
        }
        
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        
        return in_array($extension, $imageExtensions);
    }

    public function isVideo()
    {
        if (!$this->file_path) {
            return false;
        }
        
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
        
        return in_array($extension, $videoExtensions);
    }

    public function getFileExtension()
    {
        if (!$this->file_path) {
            return '';
        }
        
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    public function getFileSize()
    {
        $path = $this->getFilePath();
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

    public function upload()
    {
        if ($this->file) {
            $uploadPath = Yii::getAlias('@webroot/uploads/applications/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Генерируем уникальное имя файла
            $fileName = time() . '_' . Yii::$app->security->generateRandomString(10) . '.' . $this->file->extension;
            $filePath = $uploadPath . $fileName;
            
            // Сохраняем файл
            if ($this->file->saveAs($filePath)) {
                // Устанавливаем путь для сохранения в БД
                $this->file_path = 'uploads/applications/' . $fileName;
                return true;
            }
        }
        return false;
    }

    /**
     * Переопределяем валидацию для правильной обработки файла
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        // Сначала валидируем все остальные поля
        $valid = parent::validate($attributeNames, $clearErrors);
        
        // Затем отдельно валидируем файл, если он есть
        if ($this->file) {
            // Создаем отдельный валидатор для файла
            $fileValidator = new \yii\validators\FileValidator([
                'extensions' => ['mp4', 'mkv', 'png', 'avi', 'jpg', 'jpeg', 'pdf'],
                'maxSize' => 100 * 1024 * 1024, // 100MB
                'skipOnEmpty' => false,
            ]);
            
            if (!$fileValidator->validate($this->file, $error)) {
                $this->addError('file', $error);
                $valid = false;
            }
        } else {
            $this->addError('file', 'Необходимо загрузить файл работы.');
            $valid = false;
        }
        
        return $valid;
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        
        // Если это новая запись, устанавливаем дату создания
        if ($insert) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->file = null;
    }

    public function getFileTypeIcon()
    {
        $extension = $this->getFileExtension();
        
        $icons = [
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'pdf' => '📄',
            'mp4' => '🎥',
            'avi' => '🎥',
            'mov' => '🎥',
            'mkv' => '🎥',
        ];
        
        return $icons[$extension] ?? '📎';
}

}
