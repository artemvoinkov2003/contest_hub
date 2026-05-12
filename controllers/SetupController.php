<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Nomination;
use app\models\Criteria;

class SetupController extends Controller
{
    public function actionAddDefaultCriteria()
    {
        $nominations = Nomination::find()->all();
        $defaultCriteria = [
            'Мастерство по направлению',
            'Артистизм / Раскрытие художественного образа',
            'Сценическая культура'
        ];
        
        $count = 0;
        
        foreach ($nominations as $nomination) {
            // Проверяем, есть ли уже критерии для этой номинации
            $existing = Criteria::find()->where(['nomination_id' => $nomination->id])->count();
            
            if ($existing == 0) {
                $order = 1;
                foreach ($defaultCriteria as $criterionName) {
                    $criteria = new Criteria();
                    $criteria->nomination_id = $nomination->id;
                    $criteria->name = $criterionName;
                    $criteria->max_score = 10;
                    $criteria->is_active = 1;
                    $criteria->order = $order++;
                    if ($criteria->save()) {
                        $count++;
                    }
                }
            }
        }
        
        Yii::$app->session->setFlash('success', "Добавлено $count критериев для всех номинаций");
        return $this->redirect(['admin/index']);
    }
}