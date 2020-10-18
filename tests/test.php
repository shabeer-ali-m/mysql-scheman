<?php


require __DIR__.'/../vendor/autoload.php';

use MysqlScheman\Driver\Pdo\Driver as Driver;

$scheman = new MysqlScheman\MysqlScheman(new Driver, 'localhost','root','','test');

$scheman->export('db.xml');
