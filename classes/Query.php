<?php

class Query
{

    public static function verifyExists($table, $query, $arr)
    {
        $verify = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $query");
        $verify->execute($arr);
        if ($verify->rowCount() > 0) {
            $verify = $verify->fetch()['conta_id'];
        } else {
            $verify = false;
        }
        return $verify;
    }

    public static function insert($table, $values, $arr)
    {
        $sql = MySql::conectar()->prepare("INSERT INTO `$table` VALUES($values) ");
        $sql->execute($arr);
    }

    public static function selectAll($table, $where = false, $order = false)
    {
        if ($order) {
            if ($where) {
                $sql = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $where ORDER BY $order");
            } else {
                $sql = MySql::conectar()->prepare("SELECT * FROM `$table` ORDER BY $order");
            }
        } elseif ($where) {
            $sql = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $where");
        } else {
            $sql = MySql::conectar()->prepare("SELECT * FROM `$table`");
        }
        $sql->execute();
        return $sql->fetchAll();
    }

    public static function selectAllWhere($table, $where, $arr, $order = false)
    {
        if ($order) {
            $sql = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $where ORDER BY $order");
        } else {
            $sql = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $where");
        }
        $sql->execute($arr);
        return $sql->fetchAll();
    }

    /*Seleciona apenas 1 registro*/
    public static function selectWhere($table, $where, $arr)
    {
        $sql = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $where");

        $sql->execute($arr);
        return $sql->fetch();
    }

    public static function update($table, $setters, $where, $arr)
    {
        $sql = MySql::conectar()->prepare("UPDATE `$table` SET $setters WHERE $where");
        $sql->execute($arr);
    }

    public static function deleteAll($table)
    {
        MySql::conectar()->exec("DELETE FROM `$table`");
        return true;
    }

    public static function delete($table, $where)
    {
        MySql::conectar()->exec("DELETE FROM `$table` WHERE $where");
        return true;
    }

    public static function selectSum($parm, $table, $where, $arr)
    {
        $sql = MySql::conectar()->prepare("SELECT sum($parm) FROM `$table` WHERE $where");

        $sql->execute($arr);
        return $sql->fetch();
    }
}
