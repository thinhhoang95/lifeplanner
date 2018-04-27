<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/6/2016
 * Time: 7:59 AM
 */

namespace datatier;
require_once "db.php";

class Dbhelper
{
    private static $Db;

    public static function connect()
    {
        self::$Db=new \DbConnection();
    }

    public static function fetchSQL($sql)
    {
        if(!isset(self::$Db)) self::connect();
        $result = self::$Db->query($sql);
        $rows = [];
        while($row=$result->fetch_array(MYSQLI_ASSOC))
        {
            $rows[]=$row;
        }
        return $rows;
    }

    public static function query($sql)
    {
        if(!isset(self::$Db)) self::connect();
        $result=self::$Db->query($sql);
        return $result;
    }

    function __destruct()
    {
        self::$Db->closeDb();
    }
}