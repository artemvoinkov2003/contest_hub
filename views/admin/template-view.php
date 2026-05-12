<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Просмотр шаблона: ' . $model->displayName;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны отчетов', 'url' => ['templates']];
$this->params['breadcrumbs'][] = $this->title;

function getTemplateTypeText($type) {
    $types = [
        'program' => 'Программа',
        'scoresheet' => 'Оценочный лист',
        'diploma' => 'Диплом',
        'certificate' => 'Сертификат',
        'album' => 'Альбом',
    ];
    return $types[$type] ?? $type;
}
?>

<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Просмотр шаблона</h1>
                    <p class="mt-1 text-sm text-gray-500"><?= Html::encode($model->displayName) ?></p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?= Url::to(['templates']) ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Назад
                    </a>
                    <a href="<?= Url::to(['template-update', 'id' => $model->id]) ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Редактировать
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Информация о шаблоне</h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Название файла</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= Html::encode($model->displayName) ?></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Тип шаблона</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= getTemplateTypeText($model->type) ?></dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Конкурс</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= $model->contest ? Html::encode($model->contest->name) : 'Общий шаблон' ?></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y H:i') ?></dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Статус файла</dt>
                        <dd class="mt-1 text-sm font-gray-900 sm:mt-0 sm:col-span-2">
                            <?php if ($model->fileExists()): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Файл доступен
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Файл отсутствует
                                </span>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Действия</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex space-x-2">
                                <?php if ($model->fileExists()): ?>
                                    <a href="<?= Url::to(['template-download', 'id' => $model->id]) ?>" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Скачать шаблон
                                    </a>
                                <?php endif; ?>
                                <a href="<?= Url::to(['template-update', 'id' => $model->id]) ?>" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Редактировать
                                </a>
                                <a href="<?= Url::to(['template-delete', 'id' => $model->id]) ?>" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" data-confirm="Вы уверены, что хотите удалить этот шаблон?" data-method="post">
                                    Удалить
                                </a>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <?php if ($model->fileExists()): ?>
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Предпросмотр шаблона</h3>
                <p class="mt-1 text-sm text-gray-500">Содержимое файла шаблона</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <div class="bg-gray-50 rounded-lg p-4 overflow-auto max-h-96">
                    <pre class="text-sm text-gray-800 whitespace-pre-wrap"><?= Html::encode(file_get_contents($model->getAbsolutePath())) ?></pre>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>