<?php

/**
 * MysqlScheman.php
 *
 * @copyright      MIT
 * @since          0.0.1
 *
 */

namespace MysqlScheman;

/**
 * MysqlScheman Class
 */
class SchemaDiffer
{
	/**
	 * $return
	 * @var array
	 */
	private static $return = [];

	protected static function tableDiff($a1, $a2)
	{
		$a1_keys = array_keys($a1);
		$a2_keys = array_keys($a2);
		foreach (array_diff($a2_keys, $a1_keys) as $table_name) {
		 	self::$return['CREATE_TABLE'][$table_name] = $a2[$table_name];
		}
	}

	/**
	 * arrayWithId
	 * @param  array  $input
	 * @return array
	 */
	private static function arrayWithId(array $input) : array
	{
		$output = []; 
		foreach ($input as $key => $value) {
			$output[$value['Field']] = $value;
		}
		return $output;
	}

	protected static function columnDiff($a1, $a2)
	{
		$return = [];
		$tables = array_diff(
			array_keys($a2), 
			array_keys(self::$return['CREATE_TABLE'] ?? [])
		);
		foreach ($tables as $table_name) {
			$col1 = self::arrayWithId($a1[$table_name]);
			$col2 = self::arrayWithId($a2[$table_name]);
			foreach ($col2 as $name => $attr) {
				if (isset($col1[$name])) {
					if ($change = array_diff($attr, $col1[$name])) {
						$change['Field'] = $name;
						self::$return['ALTER_TABLE']['CHANGE'][$table_name][] =  $change;
					}
				} else {
					self::$return['ALTER_TABLE']['ADD'][$table_name][] =  $attr;
				}
			}
		}
	}

	/**
	 * Get diff between db & file schema
	 * @param  array  $a1
	 * @param  array  $a2
	 * @return array
	 */
	public static function diff(array $a1, array $a2) : array
	{
		self::tableDiff($a1, $a2);
		self::columnDiff($a1, $a2);
		return self::$return;
	}
}
