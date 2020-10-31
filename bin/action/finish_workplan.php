<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/7/2016
 * Time: 9:35 AM
 */

include_once "../class/datatier/workplaner.php";
include_once "../class/datatier/workloader.php";
include_once "../class/notifier.php";

$id=$_GET["id"];
$tu=$_GET["timeunits"];
$mission = $_GET["mission"];

$workplaner = new \datatier\workplaner();
$workloader = new \datatier\workloader();

$workplaner->completeWorkplan($id,$tu,$mission); // Add user-specified time units to the registry

/*$wp = $workplaner->findWorkPlanById($id);
$wl = $workloader->getWorkloadById($wp->workload_id);
$wpmission = $wp->mission;
$wptime = $wp->time_units;
$wlname = $wl->work_name;
notifier::send("Workload $wlname ($wpmission - $wptime time units) has been marked as completed.");*/
echo "OK";

?>