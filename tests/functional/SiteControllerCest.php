<?php

namespace tests\functional;

use app\models\User;
use Yii;

class SiteControllerCest
{
    // Этот метод выполняется перед каждым тестом
    public function _before(\FunctionalTester $I)
    {
        // Очищаем базу данных перед каждым тестом
        $this->cleanDatabase();
    }

    // Тестирование главной страницы
    public function testIndex(\FunctionalTester $I)
    {
        $I->amOnPage(['site/index']);
        $I->see('Конкурсы');
        $I->seeLink('Войти');
        $I->seeLink('Регистрация');
    }

    // Тестирование страницы входа
    public function testLoginPage(\FunctionalTester $I)
    {
        $I->amOnPage(['site/login']);
        $I->see('Вход в систему');
        $I->seeElement('input[name="LoginForm[email]"]');
        $I->seeElement('input[name="LoginForm[password]"]');
        $I->seeElement('button[name="login-button"]');
    }

    // Тестирование успешного входа
    public function testSuccessfulLogin(\FunctionalTester $I)
    {
        // Создаем тестового пользователя напрямую
        $this->createTestUser('admin@example.com', 'admin123', 1, 0);
        
        $I->amOnPage(['site/login']);
        $I->fillField('input[name="LoginForm[email]"]', 'admin@example.com');
        $I->fillField('input[name="LoginForm[password]"]', 'admin123');
        $I->click('login-button');
        
        // Проверяем успешный вход по URL
        $I->seeCurrentUrlEquals('/site/index');
        $I->see('Вы успешно вошли в систему');
    }

    // Тестирование неудачного входа
    public function testFailedLogin(\FunctionalTester $I)
    {
        $I->amOnPage(['site/login']);
        $I->fillField('input[name="LoginForm[email]"]', 'wrong@example.com');
        $I->fillField('input[name="LoginForm[password]"]', 'wrongpassword');
        $I->click('login-button');
        
        $I->see('Ошибка входа');
    }

    // Тестирование страницы регистрации
    public function testRegisterPage(\FunctionalTester $I)
    {
        $I->amOnPage(['site/register']);
        $I->see('Регистрация');
        $I->seeElement('input[name="RegisterForm[email]"]');
        $I->seeElement('input[name="RegisterForm[password]"]');
        $I->seeElement('input[name="RegisterForm[name]"]');
    }

    // Тестирование успешной регистрации
    public function testSuccessfulRegistration(\FunctionalTester $I)
    {
        $I->amOnPage(['site/register']);
        $I->fillField('input[name="RegisterForm[email]"]', 'newuser@example.com');
        $I->fillField('input[name="RegisterForm[password]"]', 'newpassword123');
        $I->fillField('input[name="RegisterForm[name]"]', 'New User');
        $I->click('register-button');
        
        $I->see('Регистрация прошла успешно');
        $I->seeCurrentUrlEquals('/site/login');
    }

    // Тестирование выхода из системы
    public function testLogout(\FunctionalTester $I)
    {
        // Создаем и логиним пользователя
        $this->createTestUser('test@example.com', 'test123');
        
        $I->amOnPage(['site/login']);
        $I->fillField('input[name="LoginForm[email]"]', 'test@example.com');
        $I->fillField('input[name="LoginForm[password]"]', 'test123');
        $I->click('login-button');
        
        // Выходим
        $I->amOnPage(['site/logout']);
        $I->see('Вы успешно вышли из системы');
    }

    // Тестирование страницы контактов
    public function testContactPage(\FunctionalTester $I)
    {
        // Создаем и логиним пользователя
        $this->createTestUser('user@example.com', 'user123');
        
        $I->amOnPage(['site/login']);
        $I->fillField('input[name="LoginForm[email]"]', 'user@example.com');
        $I->fillField('input[name="LoginForm[password]"]', 'user123');
        $I->click('login-button');
        
        $I->amOnPage(['site/contact']);
        $I->see('Контакты');
    }

    /**
     * Создает тестового пользователя
     */
    private function createTestUser($email, $password, $isAdmin = 0, $isExpert = 0)
    {
        $user = new User();
        $user->email = $email;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->name = 'Test User';
        $user->is_admin = $isAdmin;
        $user->is_expert = $isExpert;
        $user->is_blocked = 0;
        $user->created_at = time();
        $user->updated_at = time();
        
        if (!$user->save()) {
            throw new \Exception('Не удалось создать тестового пользователя: ' . print_r($user->errors, true));
        }
        
        return $user;
    }

    /**
     * Очищает базу данных перед тестом
     */
    private function cleanDatabase()
    {
        // Отключаем проверку внешних ключей
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
        
        // Очищаем таблицы в правильном порядке
        $tables = ['notification', 'evaluation_score', 'evaluation', 'expert_assignment', 
                  'criteria', 'contest_result', 'generated_document', 'application', 
                  'age_category', 'nomination', 'report_template', 'contest', 'user'];
        
        foreach ($tables as $table) {
            try {
                Yii::$app->db->createCommand()->truncateTable($table)->execute();
            } catch (\Exception $e) {
                // Игнорируем ошибки если таблицы не существует
            }
        }
        
        // Включаем проверку внешних ключей обратно
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}