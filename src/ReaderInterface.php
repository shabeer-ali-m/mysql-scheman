<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman;

/**
 * The generic interface of File Reader
 */

interface ReaderInterface
{
    public static function readConfig(string $file) : array;

    public static function read(string $file) : array;
}
