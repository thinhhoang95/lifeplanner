<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/7/2016
 * Time: 8:27 PM
 */

include_once "../class/datatier/workloader.php";
include_once "../class/notifier.php";

$work_name=$_POST["work_name"];
$work_description=$_POST["work_description"];
$total_time_units=$_POST["total_time_units"];
$units_per_week=$_POST["units_per_week"];
$active=$_POST["active"];
$workloader = new \datatier\workloader();
$workloader->newWorkload($work_name, $work_description, $total_time_units, $units_per_week,$active);
//notifier::send("Workload: $work_name has been created");
echo "OK";

?>