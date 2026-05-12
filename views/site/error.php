<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

// Определяем код ошибки
$statusCode = Yii::$app->response->statusCode;
if (isset($exception) && method_exists($exception, 'getStatusCode')) {
    $statusCode = $exception->getStatusCode();
}

// Конфигурация для разных типов ошибок
$errorConfig = [
    400 => [
        'title' => 'Неверный запрос',
        'description' => 'Сервер не может обработать ваш запрос из-за некорректного синтаксиса.',
        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z',
        'color' => 'from-yellow-500 to-amber-500',
        'bgColor' => 'bg-gradient-to-r from-yellow-500 to-amber-500'
    ],
    401 => [
        'title' => 'Не авторизован',
        'description' => 'Для доступа к этой странице требуется авторизация.',
        'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
        'color' => 'from-orange-500 to-red-500',
        'bgColor' => 'bg-gradient-to-r from-orange-500 to-red-500'
    ],
    403 => [
        'title' => 'Доступ запрещен',
        'description' => 'У вас недостаточно прав для доступа к этой странице.',
        'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
        'color' => 'from-red-500 to-pink-600',
        'bgColor' => 'bg-gradient-to-r from-red-500 to-pink-600'
    ],
    404 => [
        'title' => 'Страница не найдена',
        'description' => 'Запрошенная страница не существует или была перемещена.',
        'icon' => 'M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'color' => 'from-blue-500 to-indigo-600',
        'bgColor' => 'bg-gradient-to-r from-blue-500 to-indigo-600'
    ],
    500 => [
        'title' => 'Внутренняя ошибка сервера',
        'description' => 'На сервере произошла непредвиденная ошибка. Мы уже работаем над ее устранением.',
        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z',
        'color' => 'from-purple-500 to-pink-600',
        'bgColor' => 'bg-gradient-to-r from-purple-500 to-pink-600'
    ],
    503 => [
        'title' => 'Сервис недоступен',
        'description' => 'Сервер временно недоступен из-за технического обслуживания.',
        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        'color' => 'from-gray-500 to-blue-500',
        'bgColor' => 'bg-gradient-to-r from-gray-500 to-blue-500'
    ]
];

// Получаем конфигурацию для текущей ошибки или используем значения по умолчанию
$error = $errorConfig[$statusCode] ?? [
    'title' => $name,
    'description' => $message,
    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z',
    'color' => 'from-gray-500 to-gray-700',
    'bgColor' => 'bg-gradient-to-r from-gray-500 to-gray-700'
];

$this->title = $error['title'];
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 px-4 py-8">
    <div class="max-w-2xl w-full">
        <!-- Анимированный логотип -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 <?= $error['bgColor'] ?> rounded-2xl shadow-lg mb-6 transform hover:scale-110 transition-transform duration-300">
                <span class="text-white font-bold text-2xl">CH</span>
            </div>
        </div>

        <!-- Основной контент ошибки -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <!-- Заголовок ошибки -->
            <div class="<?= $error['bgColor'] ?> px-8 py-6 text-center">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-white/20 rounded-xl p-3 mr-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $error['icon'] ?>"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h1 class="text-4xl font-bold text-white mb-1">Ошибка <?= $statusCode ?></h1>
                        <p class="text-white/90 text-lg font-medium"><?= $error['title'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Детали ошибки -->
            <div class="p-8">
                <div class="text-center mb-8">
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        <?= $error['description'] ?>
                    </p>
                    
                    <?php if (YII_DEBUG && $statusCode >= 500): ?>
                        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-6 text-left">
                            <h3 class="text-red-800 font-semibold mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Детали ошибки (только в режиме отладки)
                            </h3>
                            <div class="text-red-700 text-sm font-mono bg-white rounded-lg p-4 overflow-x-auto">
                                <?= nl2br(Html::encode($message)) ?>
                                <?php if (isset($exception) && $exception instanceof \Exception): ?>
                                    <div class="mt-2 text-xs opacity-75">
                                        Файл: <?= $exception->getFile() ?><br>
                                        Строка: <?= $exception->getLine() ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-blue-50 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600 mb-1"><?= $statusCode ?></div>
                            <div class="text-sm text-blue-500">Код ошибки</div>
                        </div>
                        <div class="bg-green-50 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600 mb-1">
                                <?= date('H:i') ?>
                            </div>
                            <div class="text-sm text-green-500">Время возникновения</div>
                        </div>
                        <div class="bg-purple-50 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600 mb-1">
                                <?= Yii::$app->request->method ?>
                            </div>
                            <div class="text-sm text-purple-500">Метод запроса</div>
                        </div>
                    </div>
                </div>

                <!-- Рекомендации -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 mb-8 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Что можно сделать?
                    </h3>
                    <ul class="text-gray-600 space-y-2">
                        <?php if ($statusCode === 404): ?>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Проверьте правильность введенного URL-адреса
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Вернитесь на предыдущую страницу
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Воспользуйтесь поиском по сайту
                            </li>
                        <?php elseif ($statusCode === 403): ?>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Войдите в систему с соответствующими правами доступа
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Обратитесь к администратору для получения доступа
                            </li>
                        <?php else: ?>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Обновите страницу через некоторое время
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Проверьте свое интернет-соединение
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Очистите кэш браузера
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?= Yii::$app->homeUrl ?>" 
                       class="inline-flex items-center justify-center px-8 py-4 <?= $error['bgColor'] ?> text-white font-semibold rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        На главную
                    </a>
                    
                    <button onclick="history.back()" 
                            class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-2xl border border-gray-300 hover:bg-gray-50 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg group">
                        <svg class="w-5 h-5 mr-3 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Назад
                    </button>
                    
                    <a href="<?= \yii\helpers\Url::to(['/site/contact']) ?>" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Связаться с поддержкой
                    </a>
                </div>

                <!-- Дополнительная информация -->
                <div class="text-center mt-8">
                    <p class="text-gray-500 text-sm">
                        Если проблема повторяется, пожалуйста, сообщите нам об этом.<br>
                        Включите код ошибки <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?= $statusCode ?></span> в вашем сообщении.
                    </p>
                </div>

                <!-- Декоративные элементы -->
                <div class="fixed inset-0 -z-10 overflow-hidden">
                    <div class="absolute -top-40 -right-32 w-80 h-80 bg-gradient-to-r from-blue-200 to-purple-200 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                    <div class="absolute -bottom-40 -left-32 w-80 h-80 bg-gradient-to-r from-green-200 to-blue-200 rounded-full blur-3xl opacity-30 animate-pulse delay-1000"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-purple-200 to-pink-200 rounded-full blur-3xl opacity-20 animate-pulse delay-500"></div>
                </div>
            </div>
        </div>
    </div>
</div>