<?php
/**
 * Created by IntelliJ IDEA.
 * User: thinhhoang
 * Date: 56//16
 * Time: 7:39 PM
 */

class DbConnection
{
    public $Db;

    function __construct()
    {
        $this->Db=new mysqli("localhost","root","","lifeplanner") or die ("Can not connect to database!");
        $this->Db->set_charset("utf8");
    }

    function query($sql)
    {
        return $this->Db->query($sql);
    }

    function closeDb()
    {
        $this->Db->close();
    }
}

?>