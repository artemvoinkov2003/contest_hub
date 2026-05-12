<?php
use yii\helpers\Html;

$this->title = 'Аккаунт заблокирован';
?>

<div class="site-blocked">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card mt-5">
                    <div class="card-header bg-danger text-white">
                        <h1 class="card-title">Аккаунт заблокирован</h1>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <h4 class="alert-heading"><i class="fas fa-ban"></i> Доступ запрещен</h4>
                            <p>Ваш аккаунт был заблокирован администратором системы.</p>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Причина блокировки:</h5>
                            <ul>
                                <li>Нарушение правил платформы</li>
                                <li>Подозрительная активность</li>
                                <li>Нарушение сроков подачи заявок</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Что делать:</h5>
                            <p>Если вы считаете, что блокировка произошла по ошибке, свяжитесь с администратором по email:</p>
                            <p class="text-center">
                                <strong>admin@competition-platform.ru</strong>
                            </p>
                        </div>
                        
                        <div class="text-center mt-4">
                            <?= Html::a('На главную', ['site/index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Войти в другой аккаунт', ['site/login'], ['class' => 'btn btn-secondary']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
