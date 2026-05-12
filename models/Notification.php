<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property string $status
 * @property string $notification_type
 * @property string $metadata
 * @property string $created_at
 *
 * @property User $user
 */
class Notification extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    
    // Типы уведомлений
    const TYPE_DIPLOMA_GENERATED = 'diploma_generated';
    const TYPE_RESULTS_PUBLISHED = 'results_published';
    const TYPE_NOMINATION_LIMIT_REACHED = 'nomination_limit_reached';
    const TYPE_ALL_EVALUATIONS_COMPLETE = 'all_evaluations_complete';
    const TYPE_APPLICATION_STATUS_CHANGED = 'application_status_changed';
    const TYPE_APPLICATION_SUBMITTED = 'application_submitted';
    const TYPE_APPLICATION_DELETED = 'application_deleted';
    const TYPE_EXPERT_EVALUATION = 'expert_evaluation';
    const TYPE_DIPLOMA_REQUEST = 'diploma_request';
    
    // Типы для фильтрации
    public static function getTypeOptions()
    {
        return [
            self::TYPE_DIPLOMA_GENERATED => 'Диплом сгенерирован',
            self::TYPE_RESULTS_PUBLISHED => 'Результаты опубликованы',
            self::TYPE_NOMINATION_LIMIT_REACHED => 'Лимит номинации достигнут',
            self::TYPE_ALL_EVALUATIONS_COMPLETE => 'Все эксперты завершили',
            self::TYPE_APPLICATION_STATUS_CHANGED => 'Статус заявки изменен',
            self::TYPE_APPLICATION_SUBMITTED => 'Заявка подана',
            self::TYPE_APPLICATION_DELETED => 'Заявка удалена',
            self::TYPE_EXPERT_EVALUATION => 'Оценка эксперта',
            self::TYPE_DIPLOMA_REQUEST => 'Запрос диплома',
        ];
    }
    
    public static function getTypeLabel($type)
    {
        $options = self::getTypeOptions();
        return $options[$type] ?? $type;
    }

    public static function tableName()
    {
        return '{{%notification}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'title', 'message'], 'required'],
            [['user_id'], 'integer'],
            [['message', 'metadata'], 'string'],
            [['title', 'notification_type'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['notification_type'], 'in', 'range' => array_keys(self::getTypeOptions())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'title' => 'Заголовок',
            'message' => 'Сообщение',
            'status' => 'Статус',
            'notification_type' => 'Тип уведомления',
            'metadata' => 'Дополнительные данные',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->status = self::STATUS_READ;
        return $this->save(false);
    }

    /**
     * Check if notification is new
     */
    public function isNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Get icon for notification type
     */
    public function getTypeIcon()
    {
        $icons = [
            self::TYPE_DIPLOMA_GENERATED => '🏆',
            self::TYPE_RESULTS_PUBLISHED => '📊',
            self::TYPE_NOMINATION_LIMIT_REACHED => '⚠️',
            self::TYPE_ALL_EVALUATIONS_COMPLETE => '✅',
            self::TYPE_APPLICATION_STATUS_CHANGED => '📝',
            self::TYPE_APPLICATION_SUBMITTED => '📨',
            self::TYPE_APPLICATION_DELETED => '🗑️',
            self::TYPE_EXPERT_EVALUATION => '⭐',
            self::TYPE_DIPLOMA_REQUEST => '📄',
        ];
        return $icons[$this->notification_type] ?? '📢';
    }

    /**
     * Get badge color for notification type
     */
    public function getTypeBadgeColor()
    {
        $colors = [
            self::TYPE_DIPLOMA_GENERATED => 'bg-yellow-100 text-yellow-800',
            self::TYPE_RESULTS_PUBLISHED => 'bg-blue-100 text-blue-800',
            self::TYPE_NOMINATION_LIMIT_REACHED => 'bg-red-100 text-red-800',
            self::TYPE_ALL_EVALUATIONS_COMPLETE => 'bg-green-100 text-green-800',
            self::TYPE_APPLICATION_STATUS_CHANGED => 'bg-purple-100 text-purple-800',
            self::TYPE_APPLICATION_SUBMITTED => 'bg-indigo-100 text-indigo-800',
            self::TYPE_APPLICATION_DELETED => 'bg-gray-100 text-gray-800',
            self::TYPE_EXPERT_EVALUATION => 'bg-orange-100 text-orange-800',
            self::TYPE_DIPLOMA_REQUEST => 'bg-cyan-100 text-cyan-800',
        ];
        return $colors[$this->notification_type] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Create a new notification
     */
    public static function create($userId, $title, $message, $type = null, $metadata = null)
    {
        $notification = new self();
        $notification->user_id = $userId;
        $notification->title = $title;
        $notification->message = $message;
        $notification->notification_type = $type;
        
        if ($metadata !== null) {
            $notification->metadata = is_string($metadata) ? $metadata : json_encode($metadata);
        }
        
        return $notification->save();
    }

    /**
     * Create diploma generated notification
     */
    public static function createDiplomaGenerated($userId, $applicationId, $workName, $filePath = null)
    {
        $metadata = [
            'application_id' => $applicationId,
            'file_path' => $filePath,
        ];
        
        return self::create(
            $userId,
            'Диплом сгенерирован',
            "Для вашей заявки '{$workName}' сгенерирован диплом. Теперь вы можете скачать его в личном кабинете.",
            self::TYPE_DIPLOMA_GENERATED,
            $metadata
        );
    }

    /**
     * Create results published notification
     */
    public static function createResultsPublished($userId, $contestName)
    {
        return self::create(
            $userId,
            'Результаты конкурса опубликованы',
            "Результаты конкурса '{$contestName}' были опубликованы. Вы можете ознакомиться с ними в личном кабинете.",
            self::TYPE_RESULTS_PUBLISHED
        );
    }

    /**
     * Get unread notifications count for user
     */
    public static function getUnreadCount($userId)
    {
        return self::find()
            ->where(['user_id' => $userId, 'status' => self::STATUS_NEW])
            ->count();
    }

    /**
     * Get notifications for user with pagination
     */
    public static function getForUser($userId, $type = null, $limit = 10)
    {
        $query = self::find()->where(['user_id' => $userId]);
        
        if ($type && $type !== 'all') {
            $query->andWhere(['notification_type' => $type]);
        }
        
        return $query
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
}
