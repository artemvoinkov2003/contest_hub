<?php

return [
    'admin' => [
        'email' => 'admin@example.com',
        'password_hash' => Yii::$app->security->generatePasswordHash('admin123'),
        'name' => 'Admin User',
        'is_admin' => 1,
        'is_expert' => 0,
        'is_blocked' => 0,
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'user' => [
        'email' => 'user@example.com',
        'password_hash' => Yii::$app->security->generatePasswordHash('user123'),
        'name' => 'Regular User',
        'is_admin' => 0,
        'is_expert' => 0,
        'is_blocked' => 0,
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'expert' => [
        'email' => 'expert@example.com',
        'password_hash' => Yii::$app->security->generatePasswordHash('expert123'),
        'name' => 'Expert User',
        'is_admin' => 0,
        'is_expert' => 1,
        'is_blocked' => 0,
        'created_at' => time(),
        'updated_at' => time(),
    ],
];