<?php

namespace tests\functional;

use app\models\Application;
use app\models\Contest;

class ApplicationControllerCest
{
    // Тестирование просмотра списка заявок
    public function testIndex(\FunctionalTester $I)
    {
        $I->amLoggedInAsUser();
        $I->amOnPage(['application/index']);
        $I->see('Мои заявки');
        $I->seeElement('table');
    }

    // Тестирование создания заявки
    public function testCreate(\FunctionalTester $I)
    {
        // Создаем тестовый конкурс
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Test Contest for Application',
            'status' => 1,
        ]);
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['application/create', 'contest_id' => $contestId]);
        $I->see('Подача заявки');
        
        // Заполняем форму
        $I->fillField('input[name="Application[surname]"]', 'Иванов');
        $I->fillField('input[name="Application[name]"]', 'Иван');
        $I->fillField('input[name="Application[work_name]"]', 'Тестовая работа');
        $I->fillField('input[name="Application[institution]"]', 'Тестовое учреждение');
        $I->fillField('input[name="Application[leader]"]', 'Тестовый руководитель');
        
        // Здесь нужно будет добавить логику загрузки файла
        // $I->attachFile('input[name="Application[file]"]', 'test.pdf');
        
        $I->click('Сохранить');
        $I->see('Заявка успешно подана');
    }

    // Тестирование просмотра заявки
    public function testView(\FunctionalTester $I)
    {
        // Создаем тестовую заявку
        $applicationId = $I->haveRecord(Application::class, [
            'user_id' => 2,
            'contest_id' => 1,
            'work_name' => 'Test Application',
            'status' => Application::STATUS_NEW,
        ]);
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['application/view', 'id' => $applicationId]);
        $I->see('Test Application');
        $I->see('Информация о заявке');
    }

    // Тестирование обновления заявки
    public function testUpdate(\FunctionalTester $I)
    {
        // Создаем тестовую заявку
        $applicationId = $I->haveRecord(Application::class, [
            'user_id' => 2,
            'contest_id' => 1,
            'work_name' => 'Old Work Name',
            'status' => Application::STATUS_NEW,
        ]);
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['application/update', 'id' => $applicationId]);
        $I->see('Редактирование заявки');
        
        $I->fillField('input[name="Application[work_name]"]', 'Updated Work Name');
        $I->click('Сохранить');
        
        $I->see('Заявка успешно обновлена');
        $I->seeRecord(Application::class, ['id' => $applicationId, 'work_name' => 'Updated Work Name']);
    }

    // Тестирование отмены заявки
    public function testCancel(\FunctionalTester $I)
    {
        $applicationId = $I->haveRecord(Application::class, [
            'user_id' => 2,
            'contest_id' => 1,
            'status' => Application::STATUS_NEW,
        ]);
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['application/cancel', 'id' => $applicationId]);
        
        $I->see('Заявка успешно отменена');
        $I->seeRecord(Application::class, ['id' => $applicationId, 'status' => Application::STATUS_BLOCKED]);
    }

    // Тестирование удаления заявки
    public function testDelete(\FunctionalTester $I)
    {
        $applicationId = $I->haveRecord(Application::class, [
            'user_id' => 2,
            'contest_id' => 1,
        ]);
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['application/delete', 'id' => $applicationId]);
        
        $I->see('Заявка успешно удалена');
        $I->dontSeeRecord(Application::class, ['id' => $applicationId]);
    }

    // Тестирование AJAX запросов для получения номинаций
    public function testGetNominations(\FunctionalTester $I)
    {
        $I->amLoggedInAsUser();
        $I->sendAjaxGetRequest(['application/get-nominations', 'contest_id' => 1]);
        $I->seeResponseContains('<option value="">Выберите номинацию</option>');
    }
}