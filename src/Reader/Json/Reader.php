<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman\Reader\Json;

class Reader implements \MysqlScheman\ReaderInterface
{

    public static function readConfig($filename) : array
    {
        $json = file_get_contents($filename);
        return json_decode($json, true);
    }


    public static function read($filename) : array
    {
        $return = file_get_contents($filename);
        $return = json_decode($return, true);
        return $return['schema'];
    }
}
