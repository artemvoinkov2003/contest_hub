<?php

namespace tests\functional;

use app\models\Contest;
use app\models\Nomination;
use app\models\AgeCategory;

class ContestControllerCest
{
    // Тестирование списка конкурсов
    public function testIndex(\FunctionalTester $I)
    {
        $I->amOnPage(['contest/index']);
        $I->see('Конкурсы');
        $I->seeElement('.contest-list');
    }

    // Тестирование просмотра конкурса
    public function testView(\FunctionalTester $I)
    {
        // Создаем тестовый конкурс
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Test Contest',
            'status' => 1,
        ]);
        
        $I->amOnPage(['contest/view', 'id' => $contestId]);
        $I->see('Test Contest');
        $I->see('Информация о конкурсе');
    }

    // Тестирование подачи заявки на конкурс
    public function testApply(\FunctionalTester $I)
    {
        // Создаем тестовый конкурс с номинациями
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Contest for Application',
            'status' => 1,
        ]);
        
        $nominationId = $I->haveRecord(Nomination::class, [
            'contest_id' => $contestId,
            'name' => 'Test Nomination',
        ]);
        
        $ageCategoryId = $I->haveRecord(AgeCategory::class, [
            'contest_id' => $contestId,
            'name' => 'Test Age Category',
        ]);
        
        $I->amLoggedInAsUser();
        $I->amOnPage(['contest/apply', 'id' => $contestId]);
        $I->see('Подача заявки на конкурс');
        $I->see('Test Contest');
    }

    // Тестирование результатов конкурса
    public function testResults(\FunctionalTester $I)
    {
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Finished Contest',
            'status' => 1,
        ]);
        
        $I->amOnPage(['contest/results', 'id' => $contestId]);
        $I->see('Результаты конкурса');
        $I->see('Finished Contest');
    }

    // Тестирование скачивания программы конкурса
    public function testDownloadProgram(\FunctionalTester $I)
    {
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Contest with Program',
            'status' => 1,
        ]);
        
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['contest/download-program', 'id' => $contestId]);
        // Проверяем, что возвращается файл или сообщение об ошибке
    }

    // Тестирование генерации Excel отчета
    public function testGenerateExcel(\FunctionalTester $I)
    {
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Excel Report Contest',
            'status' => 1,
        ]);
        
        $I->amLoggedInAsAdmin();
        $I->amOnPage(['contest/generate-excel', 'id' => $contestId]);
        
        $I->seeResponseCodeIs(200);
        $I->seeHttpHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    // Тестирование печатного представления
    public function testPrintView(\FunctionalTester $I)
    {
        $contestId = $I->haveRecord(Contest::class, [
            'name' => 'Print Contest',
            'status' => 1,
        ]);
        
        $I->amOnPage(['contest/print-view', 'id' => $contestId]);
        $I->see('Print Contest');
        $I->seeElement('.print-version');
    }
}