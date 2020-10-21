<?php

require __DIR__.'/../vendor/autoload.php';

$scheman = new MysqlScheman\MysqlScheman('127.0.0.1','root','','test');

//$scheman->export('db.xml');
$scheman->sync2db('db.xml');
