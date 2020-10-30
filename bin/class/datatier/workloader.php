<?php
/**
 * Created by IntelliJ IDEA.
 * User: thinhhoang
 * Date: 66//16
 * Time: 11:30 AM
 */

namespace datatier;
use datatier\datatypes\workload;
use datatier\datatypes\workloadStats;

require_once "dbhelper.php";
require_once "datatypes/workload.php";
require_once "datatypes/workload_stats.php";

class workloader
{
    public function workLoadAllBuild()
    {
        $sql="SELECT * FROM workload WHERE workload.active='1'";
        $obj = Dbhelper::fetchSQL($sql);
        $arr = [];
        foreach ($obj as $item)
        {
            $wl = new workload();
            $wl->initWithObj($item);
            $arr[]=$wl;
        }
        return $arr;
    }

    public function workLoadAllUnactivated()
    {
        $sql="SELECT * FROM workload WHERE workload.active='0'";
        $obj = Dbhelper::fetchSQL($sql);
        $arr = [];
        foreach ($obj as $item)
        {
            $wl = new workload();
            $wl->initWithObj($item);
            $arr[]=$wl;
        }
        return $arr;
    }

    public function workLoadWeekBuild()
    {
        $sql="SELECT workload.id,workload.work_name,workload.work_description,(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id)) AS remaining_units, IF((workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))>=0,LEAST((workload.units_per_week-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE WEEKOFYEAR(workplan.completion_date)=WEEKOFYEAR(NOW()) AND workplan.completion_status=1 AND workload_id=workload.id)),(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))),0) AS remaining_units_in_week FROM workload WHERE workload.active='1'";
        $obj=Dbhelper::fetchSQL($sql);
        $arr = [];
        foreach($obj as $item)
        {
            $wl=new workloadStats();
            $wl->initWithObj($item);
            $arr[]=$wl;
        }
        return $arr;
    }

    public function workLoadDailyBuild()
    {
        $sql="SELECT workload.id,workload.work_name,workload.work_description,(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id)) AS remaining_units, IF((workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))>=0,LEAST((workload.units_per_week-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE WEEKOFYEAR(workplan.completion_date)=WEEKOFYEAR(NOW()) AND workplan.completion_status=1 AND workload_id=workload.id)),(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))),0) AS remaining_units_in_week FROM workload WHERE workload.active='1'";
        $obj=Dbhelper::fetchSQL($sql);
        $arr = [];
        foreach($obj as $item)
        {
            $wl=new workloadStats();
            $wl->initWithObj($item);
            $arr[]=$wl;
        }
        return $arr;
    }

    public function registerWorkload($workload_id, $time_units, $mission) // Workplan
    {
        $date = date("Y-m-d H:i:s");
		Dbhelper::query("INSERT INTO inbox (workload_id, time_units, registration_date, completion_status, completion_date, mission) VALUES ('$workload_id','$time_units','$date','0','','$mission')");
    }

    public function getWorkloadById($id)
    {
        $obj=Dbhelper::query("SELECT * FROM workload WHERE id='$id'");
        if($obj->num_rows==1)
        {
            $dbw=$obj->fetch_array(MYSQLI_ASSOC);
            $wl = new workload();
            $wl->id=$id;
            $wl->work_name=$dbw["work_name"];
            $wl->work_description=$dbw["work_description"];
            $wl->total_time_units=$dbw["total_time_units"];
            $wl->units_per_week=$dbw["units_per_week"];
            $wl->active=$dbw["active"];
        } else {
            die("Database violation detected in WorkLoader! ID is ".$id);
        }
        return $wl;
    }

    public function updateWorkload($id,$work_name,$work_description,$total_time_units,$units_per_week,$active)
    {
        $sql="UPDATE workload SET work_name='$work_name', work_description='$work_description', total_time_units='$total_time_units', units_per_week='$units_per_week', active='$active' WHERE id='$id'";
        Dbhelper::query($sql);
    }

    public function newWorkload($work_name,$work_description,$total_time_units,$units_per_week,$active)
    {
        $sql="INSERT INTO workload (work_name, work_description, total_time_units, units_per_week, active) VALUES ('$work_name','$work_description','$total_time_units','$units_per_week','$active')";
        Dbhelper::query($sql);
    }

    public function deleteWorkload($id)
    {
        $sql="DELETE FROM workload WHERE id='$id'";
        Dbhelper::query($sql);
    }

}