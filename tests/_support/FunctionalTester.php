<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;
    
    /**
     * Логин пользователя
     */
    public function amLoggedInAs($email, $password)
    {
        $this->amOnPage('/site/login');
        $this->fillField('input[name="LoginForm[email]"]', $email);
        $this->fillField('input[name="LoginForm[password]"]', $password);
        $this->click('login-button');
    }
    
    /**
     * Логин как администратор
     */
    public function amLoggedInAsAdmin()
    {
        $this->amLoggedInAs('admin@example.com', 'admin123');
    }
    
    /**
     * Логин как обычный пользователь
     */
    public function amLoggedInAsUser()
    {
        $this->amLoggedInAs('user@example.com', 'user123');
    }
    
    /**
     * Логин как эксперт
     */
    public function amLoggedInAsExpert()
    {
        $this->amLoggedInAs('expert@example.com', 'expert123');
    }
    
    /**
     * Проверка успешного флеш-сообщения
     */
    public function seeSuccessFlash($message = null)
    {
        $this->seeElement('.alert-success');
        if ($message) {
            $this->see($message, '.alert-success');
        }
    }
    
    /**
     * Проверка ошибки флеш-сообщения
     */
    public function seeErrorFlash($message = null)
    {
        $this->seeElement('.alert-danger');
        if ($message) {
            $this->see($message, '.alert-danger');
        }
    }
    
    /**
     * Проверка предупреждения флеш-сообщения
     */
    public function seeWarningFlash($message = null)
    {
        $this->seeElement('.alert-warning');
        if ($message) {
            $this->see($message, '.alert-warning');
        }
    }
    
    /**
     * Проверка информационного флеш-сообщения
     */
    public function seeInfoFlash($message = null)
    {
        $this->seeElement('.alert-info');
        if ($message) {
            $this->see($message, '.alert-info');
        }
    }
    
    /**
     * Создание пользователя
     */
    public function createUser($email, $password, $isAdmin = 0, $isExpert = 0)
    {
        $user = new \app\models\User();
        $user->email = $email;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->name = 'Test User';
        $user->is_admin = $isAdmin;
        $user->is_expert = $isExpert;
        $user->is_blocked = 0;
        $user->created_at = time();
        $user->updated_at = time();
        
        if (!$user->save()) {
            throw new \Exception('Не удалось создать пользователя: ' . print_r($user->errors, true));
        }
        
        return $user;
    }
}