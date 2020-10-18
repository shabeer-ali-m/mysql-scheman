<?php

/**
 * MysqlScheman.php
 *
 * @copyright      MIT
 * @since          0.0.1
 *
 */

namespace MysqlScheman;

use MysqlScheman\Driver as Driver;
use MysqlScheman\Writer as Writer;

/**
 * MysqlScheman Class
 */
class MysqlScheman
{
	/**
	 * MysqlScheman\Driver
	 */	
	private $driver;

	private $database;

	private $schema;

	function __construct(Driver $driver, $hostname, $username, $password, $database)
	{
		$this->database = $database;
		$this->driver = $driver;
		$this->driver->connect($hostname, $username, $password, $database);
	}

	public function export($filename)
	{
		$this->getSchema();
		Writer\Xml\Writer::write($this->database, $this->schema, $filename);
	}

	private function getSchema()
	{
		$tables = $this->driver->query("SHOW TABLES");
		foreach ($tables as $key => $table) {
			$tablename = $table['Tables_in_'.$this->database];
			$columns = $this->driver->query("show full columns from $tablename");
			$this->schema[$tablename] = $columns;
		}
	}
}
