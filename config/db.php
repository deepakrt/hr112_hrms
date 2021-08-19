<?php

// Staging
/*return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=10.228.11.117;dbname=erss112',
    'username' => 'sa',
    'password' => 'mysql',
    'charset' => 'utf8',
];
*/

// Local system
/*return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=scrumcyb_ehrms',
    'username' => 'scrumcyb_ehrms',
    'password' => '1228@deepak',
    'charset' => 'utf8',
];
*/

// Production

return [
    'class' => 'yii\db\Connection',
    //'dsn' => 'mysql:host=localhost;dbname=erss112_hry',
    'dsn' => 'mysql:host=localhost;dbname=staging_erss_112',
    'username' => 'root',
    'password' => 'root@123',
    'charset' => 'utf8',
    // 'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
];

