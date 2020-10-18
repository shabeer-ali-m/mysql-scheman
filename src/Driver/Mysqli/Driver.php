<?php

namespace MysqlScheman\Driver\Mysqli;

class Driver implements \MysqlScheman\Driver {

	private $conn;

	public function connect($hostname, $username, $password, $database)
	{
		$this->conn = new mysqli($hostname, $username, $password, $database);
	}

	public function query($sql)
	{
		
	}

}