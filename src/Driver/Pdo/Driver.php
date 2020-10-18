<?php

namespace MysqlScheman\Driver\Pdo;

class Driver implements \MysqlScheman\Driver {

	private $conn;

	public function connect($hostname, $username, $password, $database)
	{
		$this->conn = new \PDO("mysql:host=$hostname;dbname=$database", $username, $password);
	}

	public function query($sql) : array
	{
		$stmt = $this->conn->prepare($sql);
  		$stmt->execute();
  		$stmt->setFetchMode(\PDO::FETCH_ASSOC);
  		return $stmt->fetchAll();
	}
}