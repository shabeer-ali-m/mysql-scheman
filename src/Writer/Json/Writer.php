<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman\Writer\Json;

class Writer implements \MysqlScheman\WriterInterface {

	public static function write($database, $data, $filename)
	{
		$array = [
			'name' => $database,
			'schema'=> $data
		];
		file_put_contents($filename, json_encode($array, JSON_PRETTY_PRINT));
	}
}