<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman\Reader\Xml;

class Reader {

	public static function read($filename)
	{
		$return = [];
		$xmlfile = file_get_contents($filename); 
		$data = simplexml_load_string($xmlfile); 
		$data = json_decode(json_encode($data), true);
		foreach($data['table'] as $table) {
			foreach ($table['columns'] as $key => $attr) {
				$return[$table['@attributes']['name']][] = $attr['@attributes'];
			}
		}
		return $return;
	}
}