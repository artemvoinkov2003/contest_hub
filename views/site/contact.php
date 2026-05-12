<?php

use yii\helpers\Html;

$this->title = 'Контакты и информация';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Контакты и информация</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Свяжитесь с нами и узнайте больше о нашей платформе</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Контактная информация
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email</h3>
                            <p class="text-gray-600">support@contesthub.ru</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Телефон</h3>
                            <p class="text-gray-600">+7 (999) 123-45-67</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Адрес</h3>
                            <p class="text-gray-600">г. Москва, ул. Примерная, д. 123</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Время работы</h3>
                            <p class="text-gray-600">Пн-Пт: 9:00 - 18:00</p>
                            <p class="text-gray-600">Сб-Вс: выходной</p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4">Мы в социальных сетях</h3>
                    <div class="flex space-x-4">
                        <a href="https://vk.com/artem_voinkov" target="_blank" class="text-gray-400 hover:text-blue-600 transition duration-150">
                            <span class="sr-only">VK</span>
                            <img src="/img/vk-icon.png" alt="VK" class="h-8 w-8 rounded-full object-cover">
                        </a>
                        <a href="https://t.me/Art3moon_444" target="_blank" class="text-gray-400 hover:text-blue-400 transition duration-150">
                            <span class="sr-only">Telegram</span>
                            <img src="/img/tg-icon.png" alt="Telegram" class="h-8 w-8 rounded-full object-cover">
                        </a>
                    </div>
                </div>
            </div>

            <!-- About Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    О платформе ContestHub
                </h2>
                
                <div class="space-y-4 text-gray-600">
                    <p>
                        <strong>ContestHub</strong> — это современная платформа для организации и участия в творческих конкурсах, 
                        которая объединяет талантливых людей со всей страны.
                    </p>
                    
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <h3 class="font-semibold text-blue-900 mb-2">Наша миссия</h3>
                        <p class="text-blue-800">
                            Мы создаем пространство, где каждый творческий человек может показать свои способности, 
                            получить профессиональную оценку и найти признание.
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Что мы предлагаем:</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Широкий выбор творческих конкурсов</li>
                            <li>Профессиональное жюри и экспертизу</li>
                            <li>Прозрачную систему оценки работ</li>
                            <li>Удобный личный кабинет для участников</li>
                            <li>Быструю техническую поддержку</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <h3 class="font-semibold text-green-900 mb-2">Для кого наш сервис?</h3>
                        <p class="text-green-800">
                            Наша платформа подходит для художников, фотографов, писателей, музыкантов 
                            и всех творческих людей, которые хотят развиваться и участвовать в конкурсах.
                        </p>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4">Наша статистика</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">50+</div>
                            <div class="text-sm text-gray-600">Конкурсов</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">1000+</div>
                            <div class="text-sm text-gray-600">Участников</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">2000+</div>
                            <div class="text-sm text-gray-600">Работ</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">30+</div>
                            <div class="text-sm text-gray-600">Экспертов</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Card -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-8 mt-8 text-white text-center">
            <h3 class="text-2xl font-bold mb-4">Нужна помощь?</h3>
            <p class="text-indigo-100 text-lg mb-6 max-w-2xl mx-auto">
                Наша служба поддержки всегда готова помочь вам с любыми вопросами, 
                связанными с использованием платформы или участием в конкурсах.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:support@contesthub.ru" class="bg-white text-indigo-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    Написать на почту
                </a>
                <a href="tel:+79991234567" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 backdrop-blur-sm">
                    Позвонить нам
                </a>
            </div>
        </div>
    </div>
</div>
