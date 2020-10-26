<?php

/**
 * QueryBuilder.php
 *
 * @copyright      MIT
 * @since          0.0.1
 *
 */

namespace MysqlScheman;

/**
 * QueryBuilder Class
 */
class QueryBuilder
{
    /**
     * Query Result Array
     * @var array
     */
    private static $query = [];

    private static function structure($c)
    {
        $Query = [
            'Null'    => ' NOT NULL ',
            'Default' => '',
            'Extra' => '',
            'Comment' => ''
        ];
        if (isset($c['Null'])) {
            if ($c['Null'] == "YES") {
                $Query['Null'] = " NULL ";
            } elseif ($c['Null'] == "NO") {
                $Query['Null'] = " NOT NULL ";
            }
        }
        if (isset($c['Default']) && $c['Default'] != null) {
            if ($c['Default'] == 'CURRENT_TIMESTAMP') {
                $Query['Default'] = " default " . $c['Default'] . " ";
            } else {
                $Query['Default'] = " default '" . $c['Default'] . "' ";
            }
        }
        if (isset($c['Extra'])) {
            $Query['Extra'] = $c['Extra'];
        }
        if (isset($c['Comment'])) {
            $Query['Comment'] = " COMMENT '" . $c['Comment'] . "'";
        }
        return ($c['Type'] ?? null) . $Query['Null'] . $Query['Default'] . $Query['Extra'] . $Query['Comment'];
    }

    /**
     * Create Table Query
     * @param  [array] $table
     * @return [string] SQL Query
     */
    private static function tableQuery($table, $column, $action = "")
    {
        $keys = "";
        $sql = "";
        foreach ($column as $c) {
            if (isset($c['Key']) && $c['Key'] != "") {
                if ($c['Key'] == "PRI") {
                    $keys .= " PRIMARY KEY  (`" . $c['Field'] . "`) ";
                } elseif ($c['Key'] == "UNI") {
                    if ($keys != "") {
                        $keys .= ", ";
                    }
                    $keys .= " UNIQUE (`" . $c['Field'] . "`) ";
                } elseif ($c['Key'] == "MUL") {
                    if ($keys != "") {
                        $keys .= ", ";
                    }
                    $keys .= " INDEX (`" . $c['Field'] . "`) ";
                }
            }
            $sql .= $action
                 . ($action == 'CHANGE' ? " `".$c['Field']."`" : '')
                 . " `".$c['Field']."` " . self::structure($c).",";
        }
        return rtrim($sql, ',') . ($keys ? ','.$keys: '');
    }

    /**
     * Insert Table Array
     * @param  [array] $data
     */
    private static function create($data)
    {
        foreach ($data as $table => $column) {
            self::$query[] = 'CREATE TABLE IF NOT EXISTS `' . $table . '` (' .self::tableQuery($table, $column). ')';
        }
    }

    private static function update($data)
    {
        foreach ($data['ADD'] ?? [] as $table => $column) {
            self::$query[] = 'ALTER TABLE `' . $table . '` ' . self::tableQuery($table, $column, 'ADD');
        }
        foreach ($data['CHANGE'] ?? [] as $table => $column) {
            self::$query[] = 'ALTER TABLE `' . $table . '` ' . self::tableQuery($table, $column, 'CHANGE');
        }
    }

    /**
     * Build SQL Query From Array
     * @param  array  $data Data array
     * @return [array] Query Array
     */
    public static function build(array $data)
    {
        self::create($data['CREATE_TABLE'] ?? []);
        self::update($data['ALTER_TABLE'] ?? []);
        return self::$query;
    }
}
