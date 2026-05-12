<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Contest;

$this->title = 'ContestHub - Платформа для творческих конкурсов';

// Получаем разные типы конкурсов
$activeContests = Contest::find()
    ->where(['status' => 1])
    ->andWhere(['<=', 'start_date', date('Y-m-d')])
    ->andWhere(['>=', 'end_date', date('Y-m-d')])
    ->orderBy(['start_date' => SORT_ASC])
    ->limit(6)
    ->all();

$upcomingContests = Contest::find()
    ->where(['status' => 1])
    ->andWhere(['>', 'start_date', date('Y-m-d')])
    ->orderBy(['start_date' => SORT_ASC])
    ->limit(3)
    ->all();

$recentFinishedContests = Contest::find()
    ->where(['status' => 1])
    ->andWhere(['<', 'end_date', date('Y-m-d')])
    ->orderBy(['end_date' => SORT_DESC])
    ->limit(3)
    ->all();

// Статистика
$totalContests = Contest::find()->where(['status' => 1])->count();
$totalApplications = \app\models\Application::find()->count();
$totalUsers = \app\models\User::find()->count();
$activeContestsCount = count($activeContests);
?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 py-16 relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
                <span class="text-white text-sm font-medium">🚀 Новая платформа для творчества</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Раскрой свой
                <span class="block text-yellow-300 mt-2 animate-gradient bg-gradient-to-r from-yellow-300 to-pink-400 bg-clip-text text-transparent">
                    творческий потенциал
                </span>
            </h1>
            
            <p class="text-xl text-blue-100 mb-10 max-w-3xl mx-auto leading-relaxed">
                Участвуйте в конкурсах, получайте оценку профессионального жюри, 
                завоевывайте награды и развивайте свои навыки на нашей платформе
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['/site/signup']) ?>" 
                       class="group bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <span>Начать участие</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="<?= Url::to(['/site/login']) ?>" 
                       class="group border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105">
                        Войти в систему
                    </a>
                <?php else: ?>
                    <a href="<?= Url::to(['/contest/index']) ?>" 
                       class="group bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Найти конкурсы</span>
                    </a>
                    <a href="<?= Url::to(['/application/create']) ?>" 
                       class="group bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Подать заявку</span>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $totalContests ?></div>
                    <div class="text-blue-200 text-sm">Конкурсов</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $activeContestsCount ?></div>
                    <div class="text-blue-200 text-sm">Активных</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $totalApplications ?></div>
                    <div class="text-blue-200 text-sm">Заявок</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1"><?= $totalUsers ?></div>
                    <div class="text-blue-200 text-sm">Участников</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Как это работает?
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Всего 4 простых шага до вашей победы в конкурсе
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-6">
                    <span class="text-2xl font-bold">1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Регистрация</h3>
                <p class="text-gray-600">Создайте аккаунт и заполните профиль</p>
            </div>
            
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-100">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 text-purple-600 rounded-full mb-6">
                    <span class="text-2xl font-bold">2</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Выбор конкурса</h3>
                <p class="text-gray-600">Выберите конкурс и номинацию для участия</p>
            </div>
            
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-green-50 to-teal-50 border border-green-100">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-6">
                    <span class="text-2xl font-bold">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Подача заявки</h3>
                <p class="text-gray-600">Загрузите работу и отправьте заявку</p>
            </div>
            
            <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-100">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 text-orange-600 rounded-full mb-6">
                    <span class="text-2xl font-bold">4</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Оценка и результат</h3>
                <p class="text-gray-600">Получите оценку жюри и диплом</p>
            </div>
        </div>
    </div>
</section>

<!-- Active Contests Section -->
<section class="bg-gradient-to-br from-gray-50 to-blue-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">
                    Активные конкурсы
                </h2>
                <p class="text-gray-600 mt-2">
                    Примите участие в текущих конкурсах
                </p>
            </div>
            <?php if (!empty($activeContests) || !empty($upcomingContests)): ?>
            <a href="<?= Url::to(['/contest/index']) ?>" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                Все конкурсы
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($activeContests)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <?php foreach ($activeContests as $contest): ?>
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 relative">
                            <?php if ($contest->image): ?>
                                <img src="<?= Html::encode($contest->image) ?>" alt="<?= Html::encode($contest->name) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    Активен
                                </span>
                            </div>
                            <div class="absolute bottom-3 left-3 right-3">
                                <div class="flex items-center text-xs text-white bg-black/40 backdrop-blur-sm rounded-lg px-3 py-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>До <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1"><?= Html::encode($contest->name) ?></h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?= Html::encode($contest->description) ?>
                            </p>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-6">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span><?= Yii::$app->formatter->asDate($contest->start_date, 'php:d.m.Y') ?> - <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?></span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="<?= Url::to(['/contest/view', 'id' => $contest->id]) ?>" 
                                   class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center group">
                                    Подробнее
                                    <svg class="ml-1 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                <?php if (!Yii::$app->user->isGuest): ?>
                                    <a href="<?= Url::to(['/application/create', 'contest_id' => $contest->id]) ?>" 
                                       class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        Участвовать
                                    </a>
                                <?php else: ?>
                                    <a href="<?= Url::to(['/site/login']) ?>" 
                                       class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-900 transition-colors duration-200">
                                        Войти для участия
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Upcoming Contests -->
        <?php if (!empty($upcomingContests)): ?>
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Скоро начнутся</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($upcomingContests as $contest): ?>
                        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 transition-colors duration-200">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="font-bold text-gray-900 line-clamp-1"><?= Html::encode($contest->name) ?></h4>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                    Скоро
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?= Html::encode($contest->description) ?></p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Старт: <?= Yii::$app->formatter->asDate($contest->start_date, 'php:d.m.Y') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (empty($activeContests) && empty($upcomingContests)): ?>
            <div class="text-center py-12 bg-gradient-to-br from-white to-blue-50 rounded-2xl border border-blue-100">
                <div class="mx-auto w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Новых конкурсов пока нет</h3>
                <p class="text-gray-600 max-w-md mx-auto mb-6">
                    Мы активно работаем над подготовкой новых конкурсов. Следите за обновлениями!
                </p>
                
                <?php if (!empty($recentFinishedContests)): ?>
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h4 class="font-bold text-gray-900 mb-4">Недавние конкурсы</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-2xl mx-auto">
                            <?php foreach ($recentFinishedContests as $contest): ?>
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-sm font-medium text-gray-900"><?= Html::encode($contest->name) ?></div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Завершен <?= Yii::$app->formatter->asDate($contest->end_date, 'php:d.m.Y') ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Benefits Section -->
<section class="bg-gradient-to-br from-purple-50 to-pink-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Преимущества платформы
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Почему участники выбирают нашу платформу
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Профессиональное жюри</h3>
                <p class="text-gray-600">Опытные эксперты в различных творческих направлениях</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 text-white rounded-lg mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Честная оценка</h3>
                <p class="text-gray-600">Прозрачная система оценок и подробная обратная связь</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-lg mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Электронные дипломы</h3>
                <p class="text-gray-600">Автоматическая генерация дипломов и сертификатов</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-lg mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Безопасность данных</h3>
                <p class="text-gray-600">Защита персональных данных и авторских прав</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-lg mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Круглосуточная поддержка</h3>
                <p class="text-gray-600">Техническая поддержка и помощь в решении вопросов</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 text-white rounded-lg mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Портфолио работ</h3>
                <p class="text-gray-600">Создайте свое портфолио из конкурсных работ</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-br from-gray-900 to-black py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-6">
            Готовы показать свои таланты?
        </h2>
        <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
            Присоединяйтесь к сообществу творческих людей и участвуйте в конкурсах уже сегодня
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <?php if (Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to(['/site/signup']) ?>" 
                   class="bg-white text-gray-900 px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                    Зарегистрироваться бесплатно
                </a>
                <a href="<?= Url::to(['/site/login']) ?>" 
                   class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-gray-900 transition-all duration-300">
                    Уже есть аккаунт? Войти
                </a>
            <?php else: ?>
                <a href="<?= Url::to(['/contest/index']) ?>" 
                   class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-xl font-bold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                    Посмотреть все конкурсы
                </a>
                <a href="<?= Url::to(['/application/index']) ?>" 
                   class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-gray-900 transition-all duration-300">
                    Мои заявки
                </a>
            <?php endif; ?>
        </div>
        
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-left">
                <div>
                    <div class="text-2xl font-bold text-white mb-2"><?= $totalContests ?></div>
                    <div class="text-gray-400">Проведено конкурсов</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white mb-2"><?= $totalApplications ?></div>
                    <div class="text-gray-400">Подано заявок</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white mb-2">24/7</div>
                    <div class="text-gray-400">Поддержка участников</div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

.line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

.line-clamp-3 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}
</style>