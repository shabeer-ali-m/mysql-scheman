<?php

/**
 * MysqlSchemanCli.php
 *
 * @copyright      MIT
 * @since          0.0.1
 *
 */

namespace MysqlScheman;


/**
 * MysqlSchemanCli Class
 */
class MysqlSchemanCli extends MysqlScheman
{
	private $instance = null;

	private $config = [];

	function __construct() {}

	public function getInstance()
	{
	    if ($this->instance == null) {
	    	$this->instance = new MysqlScheman(
	    		(string) $this->config['hostname'],
	    		(string) $this->config['username'],
	    		(string) $this->config['password'],
	    		(string) $this->config['database'],
	    		(string) $this->config['driver'],
	    	);
	    }
	    return $this->instance;
	}

	public function setConfig($file)
	{
		$ext = $this->getFileExt($file);
		$this->config = self::getClass('Reader\\'.$ext.'\\Reader')::readFile($file);
		array_walk($this->config, function(&$conf) {
			if (empty($conf)) $conf = null;
		});
	}

	public function export($filename)
	{
		if (empty($this->config)) {
			throw new \Exception("Database configaration is missing. (use --config <configfile>) ". strtolower($ext) . PHP_EOL);
			return;
		}
		$this->getInstance()->export($filename);
	}

	public function sync2db($filename)
	{
		if (empty($this->config)) {
			throw new \Exception("Database configaration is missing. (use --config <configfile>) ". strtolower($ext) . PHP_EOL);
			return;
		}
		$this->getInstance()->sync2db($filename);
	}
}
