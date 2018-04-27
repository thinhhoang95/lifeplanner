<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/6/2016
 * Time: 10:57 PM
 */

include_once "../class/datatier/workloader.php";
include_once "../class/notifier.php";

$wl_id=$_POST["id"];
$wl_time_units=$_POST["time_units"];
$wl_mission=$_POST["mission"];

$workloader = new \datatier\workloader();
//for ($i=0;$i<$wl_time_units;$i++)
//{
$workloader->registerWorkload($wl_id, $wl_time_units, $wl_mission);
$workload = $workloader->getWorkloadById($wl_id);
$work_name = $workload->work_name;
//}
notifier::send("Workload $work_name ($wl_mission) has been registered for $wl_time_units time units.");

echo "OK";

?>