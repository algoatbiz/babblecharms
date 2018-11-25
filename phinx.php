<?php
require_once(__DIR__ . '/bootstrap.php');
return [
    'paths' =>
        [
            'migrations' => __DIR__ . '/' . EnvHelper::env('PHINX_MIGRATIONS', 'migrations'),
            'seeds' => __DIR__ . '/' . EnvHelper::env('PHINX_SEEDS', 'seeds'),
        ],
    'environments' =>
        [
            'default_migration_table' => 'phinxlog',
            'default_database' => 'default',
            'default' =>
                [
                    'adapter' => EnvHelper::env('DB_ADAPTER', 'mysql'),
                    'host'    => EnvHelper::env('DB_HOST', 'localhost'),
                    'name'    => EnvHelper::env('DB_NAME', 'dbname'),
                    'user'    => EnvHelper::env('DB_USER', 'user'),
                    'pass'    => EnvHelper::env('DB_PASS', 'password'),
                    'port'    => 3306,
                    'charset' => 'utf8',
                    'mysql_attr_init_command' => 'SET time_zone = \'-09:00\',@@session.sql_mode = \'\''
                ],
        ],
    ];
