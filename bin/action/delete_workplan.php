<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/7/2016
 * Time: 9:30 AM
 */

include_once "../class/datatier/workplaner.php";
$id=$_GET["id"];

$workplaner = new \datatier\workplaner();
$workplaner->deleteWorkplan($id);
echo "OK";

?>