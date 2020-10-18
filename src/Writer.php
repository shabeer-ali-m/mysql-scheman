<?php

namespace MysqlScheman;

interface Writer
{
	public static function write($database, $data=[], $file);
}