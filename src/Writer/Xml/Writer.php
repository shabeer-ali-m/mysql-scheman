<?php

/**
 * MIT License. This file is part of the Scheman package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MysqlScheman\Writer\Xml;

use Spatie\ArrayToXml\ArrayToXml;

class Writer implements \MysqlScheman\WriterInterface
{

    public static function write($database, $data, $filename)
    {
        $array = [];
        foreach ($data as $tablename => $columns) {
            $columns_array = [];
            foreach ($columns as $key => $value) {
                $columns_array[] = ['_attributes' => $value];
            }
            $array['table'][] = [
                '_attributes' => [
                    'name' => $tablename
                ],
                'columns' => $columns_array
            ];
        }
        
        $arrayToXml = new ArrayToXml($array, [
                'rootElementName' => 'database',
                '_attributes' => [
                    'name' => $database,
                ],
                false,
                '',
                '',
                ['formatOutput' => true]
            ]);
        $arrayToXml->setDomProperties(['formatOutput' => true]);
        file_put_contents($filename, $arrayToXml->toXml());
    }
}
