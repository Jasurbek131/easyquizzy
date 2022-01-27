<?php

//return [
//    'class' => 'yii\db\Connection',
//    'dsn' => 'mysql:host=213.230.127.153:3307;dbname=prizma',
//    'username' => 'prizma',
//    'password' => 's@m02020',
//    'charset' => 'utf8',
//    'attributes' => [
//        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));",
//    ],

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=db_name',
    'username' => 'postgres',
    'password' => 'postgres',
    'charset' => 'utf8',
    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));",
    ],

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

//return [
//    'class' => 'yii\db\Connection',
//    'dsn' => 'pgsql:host=localhost;port=5432;dbname=test',
//    'username' => 'user',
//    'password' => '1997'
//
//    // Schema cache options (for production environment)
//    //'enableSchemaCache' => true,
//    //'schemaCacheDuration' => 60,
//    //'schemaCache' => 'cache',
//];