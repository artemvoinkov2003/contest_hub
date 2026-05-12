<?php

namespace tests\functional;

use app\models\Contest;
use app\models\Application;
use app\models\User;
use Yii;

class AdminControllerCest
{
    // Доступ к админке без авторизации
    public function testAccessDeniedForGuests(\FunctionalTester $I)
    {
        $I->amOnPage(['admin/index']);
        $I->see('Вход в систему');
    }

    // Доступ к админке для обычного пользователя
    public function testAccessDeniedForRegularUser(\FunctionalTester $I)
    {
        $I->amLoggedInAsUser();
        $I->amOnPage(['admin/index']);
        $I->seeResponseCodeIs(403); // Forbidden
    }

    // Доступ к админке для администратора
    public function testAccessAllowedForAdmin(\FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/index']);
        $I->seeResponseCodeIs(200);
        $I->see('Административная панель');
        $I->see('Статистика');
    }

    // Тестирование управления заявками
    public function testApplicationsManagement(\FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/applications']);
        $I->see('Управление заявками');
        $I->seeElement('table');
    }

    // Тестирование просмотра заявки
    public function testViewApplication(\FunctionalTester $I)
    {
        // Создаем тестовую заявку
        $applicationId = $I->haveRecord(Application::class, [
            'user_id' => 2,
            'contest_id' => 1,
            'nomination_id' => 1,
            'age_category_id' => 1,
            'work_name' => 'Test Work',
            'status' => Application::STATUS_NEW,
            'created_at' => time(),
        ]);
        
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/application-view', 'id' => $applicationId]);
        $I->see('Test Work');
        $I->see('Информация о заявке');
    }

    // Тестирование создания конкурса
    public function testCreateContest(\FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/contest-create']);
        $I->see('Создание конкурса');
        
        $I->fillField('input[name="Contest[name]"]', 'New Test Contest');
        $I->fillField('textarea[name="Contest[description]"]', 'Test Description');
        $I->fillField('input[name="Contest[start_date]"]', date('Y-m-d'));
        $I->fillField('input[name="Contest[end_date]"]', date('Y-m-d', strtotime('+1 month')));
        $I->click('Сохранить');
        
        $I->see('Конкурс создан');
        $I->seeRecord(Contest::class, ['name' => 'New Test Contest']);
    }

    // Тестирование управления пользователями
    public function testUsersManagement(\FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/users']);
        $I->see('Пользователи');
        $I->seeElement('table');
    }

    // Тестирование блокировки пользователя
    public function testBlockUser(\FunctionalTester $I)
    {
        // Создаем тестового пользователя
        $userId = $I->haveRecord(User::class, [
            'email' => 'testblock@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('test123'),
            'name' => 'Test User',
            'is_admin' => 0,
            'is_blocked' => 0,
        ]);
        
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/user-block', 'id' => $userId]);
        $I->see('Пользователь успешно заблокирован');
        $I->seeRecord(User::class, ['id' => $userId, 'is_blocked' => 1]);
    }

    // Тестирование управления экспертами
    public function testExpertsManagement(\FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/experts']);
        $I->see('Эксперты');
        $I->seeElement('table');
    }

    // Тестирование управления шаблонами
    public function testTemplatesManagement(\FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/templates']);
        $I->see('Шаблоны отчетов');
        $I->seeElement('table');
    }

    // Тестирование управления результатами конкурсов
    public function testContestResultsManagement(\FunctionalTester $I)
    {
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/contest-results']);
        $I->see('Итоговые результаты конкурсов');
        $I->seeElement('table');
    }

    // Тестирование экспорта результатов
    public function testExportResults(\FunctionalTester $I)
    {
        // Создаем тестовый конкурс
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Export Test Contest',
            'status' => 1,
        ]);
        
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['admin/export-results', 'contest_id' => $contestId, 'format' => 'excel']);
        
        // Проверяем заголовки для скачивания файла
        $I->seeResponseCodeIs(200);
        $I->seeHttpHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}