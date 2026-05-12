<?php

namespace app\controllers;

use Yii;
use app\models\Notification;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'read' => ['POST'],
                        'read-all' => ['POST'],
                        'delete-read' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Notification models for the current user.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $typeFilter = Yii::$app->request->get('type', 'all');
        $statusFilter = Yii::$app->request->get('status', 'all');
        
        $query = Notification::find()->where(['user_id' => $userId]);
        
        // Фильтр по типу
        if ($typeFilter !== 'all') {
            $query->andWhere(['notification_type' => $typeFilter]);
        }
        
        // Фильтр по статусу
        if ($statusFilter !== 'all') {
            $query->andWhere(['status' => $statusFilter]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Notification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('У вас нет прав на удаление этого уведомления.');
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Уведомление успешно удалено.');
        return $this->redirect(['index']);
    }

    /**
     * Marks a notification as read.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRead($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('У вас нет прав для изменения этого уведомления.');
        }

        $model->markAsRead();

        Yii::$app->session->setFlash('success', 'Уведомление отмечено как прочитанное.');
        return $this->redirect(['index']);
    }

    /**
     * Marks all notifications as read.
     * @return \yii\web\Response
     */
    public function actionReadAll()
    {
        $userId = Yii::$app->user->id;
        $updated = Notification::updateAll(
            ['status' => Notification::STATUS_READ],
            ['user_id' => $userId, 'status' => Notification::STATUS_NEW]
        );

        if ($updated > 0) {
            Yii::$app->session->setFlash('success', "{$updated} уведомлений отмечены как прочитанные.");
        } else {
            Yii::$app->session->setFlash('info', 'Нет непрочитанных уведомлений.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Deletes all read notifications.
     * @return \yii\web\Response
     */
    public function actionDeleteRead()
    {
        $userId = Yii::$app->user->id;
        $deleted = Notification::deleteAll([
            'user_id' => $userId,
            'status' => Notification::STATUS_READ
        ]);

        if ($deleted > 0) {
            Yii::$app->session->setFlash('success', "Удалено {$deleted} прочитанных уведомлений.");
        } else {
            Yii::$app->session->setFlash('info', 'Нет прочитанных уведомлений для удаления.');
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notification::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Уведомление не найдено.');
    }
}
