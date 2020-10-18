<?php

namespace MysqlScheman\Writer\Xml;

use Spatie\ArrayToXml\ArrayToXml;

class Writer implements \MysqlScheman\Writer {

	public static function write($database, $data, $filename)
	{
		$array = [];
		foreach ($data as $tablename => $columns) {
			$columns_array = [];
			foreach ($columns as $key => $value) {
				unset($value['Privileges']);
				$columns_array[] = ['_attributes' => $value];
			}
			$array['table'][] = [
				'_attributes' => [
					'name' => $tablename
				],
				'columns' => $columns_array
			];
		}
		
		$xml = ArrayToXml::convert($array, [
    			'rootElementName' => 'database',
			    '_attributes' => [
			        'name' => $database,
			    ]
			]);

		file_put_contents($filename, $xml);
	}
}