<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var string $fileName */
/** @var string $filePath */
/** @var string $fileType */

$this->title = 'Просмотр файла';
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Заголовок -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?= Html::encode($this->title) ?></h1>
                    <p class="mt-2 text-lg text-gray-600">Просмотр и управление файлом заявки</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <a href="<?= Url::to(['index']) ?>" 
                       class="inline-flex items-center px-6 py-3 rounded-xl text-base font-semibold bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 shadow-sm hover:shadow transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Назад к списку
                    </a>
                </div>
            </div>
        </div>

        <!-- Информация о файле -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 mb-8">
            <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Информация о файле</h2>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Имя файла</h3>
                                <p class="text-lg font-semibold text-gray-900 truncate"><?= Html::encode($fileName) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Тип файла</h3>
                                <p class="text-lg font-semibold text-gray-900"><?= Html::encode($fileType) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Дата загрузки</h3>
                                <p class="text-lg font-semibold text-gray-900"><?= date('d.m.Y H:i') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Превью файла -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Предварительный просмотр</h3>
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 bg-gray-50">
                        <?php if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif'])): ?>
                            <div class="text-center">
                                <img src="<?= $filePath ?>" alt="<?= $fileName ?>" 
                                     class="max-w-full max-h-96 rounded-lg shadow-md mx-auto">
                                <p class="mt-4 text-sm text-gray-500">Изображение • <?= $fileType ?></p>
                            </div>
                        <?php elseif ($fileType === 'application/pdf'): ?>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">PDF документ</p>
                                <p class="text-sm text-gray-500 mt-1">Для просмотра скачайте файл</p>
                            </div>
                        <?php elseif (strpos($fileType, 'video/') === 0): ?>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">Видео файл</p>
                                <p class="text-sm text-gray-500 mt-1">Для просмотра скачайте файл</p>
                            </div>
                        <?php else: ?>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">Файл • <?= $fileType ?></p>
                                <p class="text-sm text-gray-500 mt-1">Для просмотра скачайте файл</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Действия -->
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="<?= $filePath ?>" download="<?= $fileName ?>" 
                       class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Скачать файл
                    </a>
                    
                    <button onclick="window.print()" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Распечатать
                    </button>
                    
                    <a href="<?= Url::to(['index']) ?>" 
                       class="inline-flex items-center px-8 py-3 bg-white text-gray-700 font-semibold rounded-xl border border-gray-300 hover:bg-gray-50 hover:shadow transition-all duration-300 hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Назад к списку
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>