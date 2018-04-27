<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/8/2016
 * Time: 5:04 PM
 */

include_once "../class/datatier/workloader.php";

$id=$_GET["id"];
$workloader=new \datatier\workloader();
$workloader->deleteWorkload($id);

echo "OK";
?>