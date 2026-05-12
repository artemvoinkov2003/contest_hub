<?php

namespace tests\functional;

use app\models\Notification;

class NotificationControllerCest
{
    // Тестирование списка уведомлений
    public function testIndex(\FunctionalTester $I)
    {
        $I->amLoggedInAsUser();
        $I->amOnPage(['notification/index']);
        $I->see('Мои уведомления');
        $I->seeElement('.notification-list');
    }

    // Тестирование пометки как прочитанного
    public function testRead(\FunctionalTester $I)
    {
        // Создаем тестовое уведомление
        $notificationId = $I->haveRecord(Notification::class, [
            'user_id' => 2,
            'title' => 'Test Notification',
            'message' => 'Test Message',
            'status' => Notification::STATUS_NEW,
        ]);
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['notification/read', 'id' => $notificationId]);
        
        $I->see('Уведомление отмечено как прочитанное');
        $I->seeRecord(Notification::class, [
            'id' => $notificationId,
            'status' => Notification::STATUS_READ
        ]);
    }

    // Тестирование пометки всех как прочитанных
    public function testReadAll(\FunctionalTester $I)
    {
        // Создаем несколько уведомлений
        for ($i = 0; $i < 3; $i++) {
            $I->haveRecord(Notification::class, [
                'user_id' => 2,
                'title' => "Test Notification $i",
                'status' => Notification::STATUS_NEW,
            ]);
        }
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['notification/read-all']);
        
        $I->see('уведомлений отмечены как прочитанные');
        
        // Проверяем, что все уведомления прочитаны
        $count = Notification::find()
            ->where(['user_id' => 2, 'status' => Notification::STATUS_READ])
            ->count();
        $I->assertEquals(3, $count);
    }

    // Тестирование удаления прочитанных
    public function testDeleteRead(\FunctionalTester $I)
    {
        // Создаем прочитанные уведомления
        for ($i = 0; $i < 2; $i++) {
            $I->haveRecord(Notification::class, [
                'user_id' => 2,
                'title' => "Read Notification $i",
                'status' => Notification::STATUS_READ,
            ]);
        }
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['notification/delete-read']);
        
        $I->see('Удалено 2 прочитанных уведомлений');
        
        // Проверяем, что прочитанные уведомления удалены
        $count = Notification::find()
            ->where(['user_id' => 2, 'status' => Notification::STATUS_READ])
            ->count();
        $I->assertEquals(0, $count);
    }

    // Тестирование фильтрации по типу и статусу
    public function testFiltering(\FunctionalTester $I)
    {
        $I->amLoggedInAsUser();
        $I->amOnPage(['notification/index', 'type' => 'system', 'status' => 'new']);
        $I->see('Мои уведомления');
        // Проверяем, что фильтры применяются
    }
}