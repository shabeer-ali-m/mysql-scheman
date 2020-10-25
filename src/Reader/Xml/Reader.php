<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman\Reader\Xml;

class Reader implements \MysqlScheman\ReaderInterface {

	public static function readFile($filename) : array
	{
		$xmlfile = file_get_contents($filename); 
		$data = simplexml_load_string($xmlfile); 
		return json_decode(json_encode($data), true);
	}


	public static function read($filename) : array
	{
		$xmlfile = file_get_contents($filename); 
		$data = simplexml_load_string($xmlfile); 
		$return = [];
		foreach($data->table as $table) {
			$tbl_name = (string) $table->attributes()['name'];
			foreach ($table->columns as $key => $attr) {
				$return[$tbl_name][] = ((array) $attr)['@attributes'];
			}
		}
		return $return;
	}
}