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

use MysqlScheman\SchemaDiffer as SchemaDiffer;

use MysqlScheman\QueryBuilder as QueryBuilder;

/**
 * MysqlScheman Class
 */
class MysqlScheman
{
	/**
	 * MysqlScheman\DriverInterface
	 */	
	private $db;

	/**
	 * Database name
	 * @var string
	 */
	private $database;

	/**
	 * Schema Array from DB
	 * @var array
	 */
	private $db_schema = [];

	/**
	 * Schema Array from File
	 * @var array
	 */
	private $file_schema = [];

	/**
	 * __construct description
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param string $driver  
	 */
	function __construct($hostname, $username, $password, $database, $driver='pdo')
	{
		$driver = "MysqlScheman\\Driver\\".ucfirst($driver)."\\Driver";
		$this->database = $database;
		$this->db = new $driver;
		$this->db->connect($hostname, $username, $password, $database);
	}

	/**
	 * export
	 * @param  string $filename
	 */
	public function export($filename)
	{
		$this->getDbSchema();
		Writer::write($this->database, $this->db_schema, $filename);
	}

	/**
	 * sync2db
	 * @param  string $filename
	 */
	public function sync2db($filename)
	{
		$this->getDbSchema();
		$this->getFileSchema($filename);
		$diff = SchemaDiffer::diff($this->db_schema, $this->file_schema);
		$query = QueryBuilder::build($diff);
		foreach ($query as $q) {
			echo $q."\n";
			$this->db->query($q);
		}
	}

	/**
	 * getFileSchema
	 * @param  string $filename [description]
	 */
	private function getFileSchema($filename)
	{
		$this->file_schema = Reader\Xml\Reader::read($filename);
	}

	/**
	 * getDbSchema from db
	 */
	private function getDbSchema()
	{
		$tables = $this->db->query("SHOW TABLES");
		foreach ($tables as $key => $table) {
			$tablename = $table['Tables_in_'.$this->database];
			$columns = $this->db->query("show full columns from $tablename");
			$this->db_schema[$tablename] = $columns;
		}
	}
}
