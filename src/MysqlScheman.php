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
    const VERSION = '0.0.1';
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
    private $db_schema = null;

    /**
     * Schema Array from File
     * @var array
     */
    private $file_schema = null;

    /**
     * __construct description
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $driver
     */
    public function __construct($hostname, $username, $password, $database, $driver = 'pdo')
    {
        $this->database = $database;
        $this->db = self::getClass("Driver\\".ucfirst($driver)."\\Driver");
        $this->db->connect($hostname, $username, $password, $database);
    }

    protected static function getClass($class)
    {
        $class = 'MysqlScheman\\' . $class;
        return new $class;
    }

    /**
     * export
     * @param  string $filename
     */
    public function export($filename)
    {
        if (!class_exists("MysqlScheman\Writer\\".($ext=$this->getFileExt($filename))."\Writer")) {
            throw new \Exception("Unsupported File Format : .". strtolower($ext) . PHP_EOL);
        }

        self::getClass('Writer\\'.$ext.'\\Writer')::write(
            $this->database,
            $this->getDatabaseSchema(),
            $filename
        );
    }

    /**
     * sync2db
     * @param  string $filename
     */
    public function sync2db($filename)
    {
        $diff = SchemaDiffer::diff(
            $this->getDatabaseSchema(),
            $this->getFileSchema($filename)
        );
        $query = QueryBuilder::build($diff);
        foreach ($query as $q) {
            $this->db->query($q);
        }
    }

    /**
     * getFileExt extension
     * @param  string $filename
     * @return string
     */
    protected function getFileExt($filename)
    {
        return ucfirst(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * getFileSchema
     * @param  string $filename [description]
     */
    private function getFileSchema($filename)
    {
        if ($this->file_schema == null) {
            $ext = ucfirst($this->getFileExt($filename));
            $this->file_schema = self::getClass('Reader\\'.$ext.'\\Reader')::read($filename);
        }
        return $this->file_schema;
    }

    /**
     * getDatabaseSchema from db
     * * @param  array
     */

    private function getDatabaseSchema()
    {
        if ($this->db_schema == null) {
            $this->db_schema = [];
            $tables = $this->db->query("SHOW TABLES");
            foreach ($tables as $key => $table) {
                $tablename = $table['Tables_in_'.$this->database];
                $columns = $this->db->query("show full columns from $tablename");
                foreach ($columns as &$col) {
                    unset($col['Privileges']);
                    unset($col['Collation']);
                }
                $this->db_schema[$tablename] = $columns;
            }
        }
        return $this->db_schema;
    }
}
