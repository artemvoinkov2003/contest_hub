<?php

namespace app\models;

use Yii;

class AgeCategory extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%age_category}}';
    }

    public function rules()
    {
        return [
            [['contest_id', 'name'], 'required'],
            [['contest_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contest_id' => 'Конкурс',
            'name' => 'Название',
        ];
    }

    public function getContest()
    {
        return $this->hasOne(Contest::class, ['id' => 'contest_id']);
    }
}