<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman;

/**
 * The generic interface to data to file
 */

interface WriterInterface
{
	public static function write($database, $data=[], $file);
}