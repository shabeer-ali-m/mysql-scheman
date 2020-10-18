<?php

namespace MysqlScheman;

interface Driver
{
	public function connect($hostname, $username, $password, $database);

	public function query($sql) : array;
}