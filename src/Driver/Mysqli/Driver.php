<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman\Driver\Mysqli;

class Driver implements \MysqlScheman\DriverInterface
{

    private $conn;

    public function connect($hostname, $username, $password, $database)
    {
        $this->conn = new mysqli($hostname, $username, $password, $database);
    }

    public function query($sql)
    {
    }
}
