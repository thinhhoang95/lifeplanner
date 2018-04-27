<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/7/2016
 * Time: 8:00 AM
 */

namespace datatier;
use datatier\datatypes\workplan;

include_once "dbhelper.php";
include_once "datatypes/workplan.php";

class workplaner
{
    private $Db;

    public function __construct()
    {
        $this->Db=new Dbhelper();
    }

    public function findWorkPlanById($id)
    {
        $result=$this->Db->query("SELECT * FROM inbox WHERE id='$id'");
        if($result->num_rows==1)
        {
            $dbw=$result->fetch_array(MYSQLI_ASSOC);
            $wp = new workplan();
            $wp->initWithObj($dbw);
        } else {
            die("An error occurred. Database integrity violated!");
        }
        return $wp;
    }

    private function workplanBuilder($sql)
    {
        $obj = $this->Db->fetchSQL($sql);
        $arr = [];
        foreach($obj as $item)
        {
            $wp = new workplan();
            $wp->initWithObj($item);
            $arr[]=$wp;
        }
        return $arr;
    }

    public function workPlanFinishedBuild()
    {
        return $this->workplanBuilder("SELECT workplan.*,workload.work_name FROM workplan,workload WHERE workload.id=workplan.workload_id AND workplan.completion_status='1' ORDER BY workplan.id DESC LIMIT 20");
    }

    public function workplanUnfinishedBuild()
    {
        return $this->workplanBuilder("SELECT inbox.*,workload.work_name FROM inbox,workload WHERE workload.id=inbox.workload_id AND inbox.completion_status='0' ORDER BY inbox.id DESC LIMIT 20");
    }
    
    public function completeWorkplan($wp_id)
    {
        $date = date("Y-m-d H:i:s");
		$now = $date;
        //$this->Db->query("UPDATE inbox SET completion_status='1', completion_date='$now' WHERE id='$wp_id'");
		$wp = $this->findWorkPlanById($wp_id);
		$workload_id = $wp->workload_id;
		$mission = $wp->mission;
		$registration_date = $wp->registration_date;
		$this->Db->query("INSERT INTO workplan (workload_id, time_units, registration_date, completion_status, completion_date, mission) VALUES ('$workload_id','1','$registration_date','1','$date','$mission')");
    }

    public function lastWeekWorkplanBuild()
    {
        return $this->workplanBuilder("SELECT workplan.*,workload.work_name FROM workplan,workload WHERE workload.id=workplan.workload_id AND workplan.completion_status='1' AND WEEKOFYEAR(workplan.completion_date)=WEEKOFYEAR(NOW())-1 ORDER BY workplan.id DESC LIMIT 150");
    }

    public function thisWeekWorkplanBuild()
    {
        return $this->workplanBuilder("SELECT workplan.*,workload.work_name FROM workplan,workload WHERE workload.id=workplan.workload_id AND workplan.completion_status='1' AND WEEKOFYEAR(workplan.completion_date)=WEEKOFYEAR(NOW()) ORDER BY workplan.id DESC LIMIT 150");
    }

    public function statsLastWeekBuild()
    {
        $sql="SELECT workload.id,workload.work_name,workload.work_description,workload.units_per_week,(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id)) AS remaining_units, IF((workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))>=0,GREATEST((workload.units_per_week-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE WEEKOFYEAR(workplan.completion_date)=WEEKOFYEAR(NOW())-1 AND workplan.completion_status=1 AND workload_id=workload.id)),(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))),0) AS remaining_units_in_week FROM workload WHERE workload.active='1'";
        return $this->Db->fetchSQL($sql);
    }

    public function statsThisWeekBuild()
    {
        $sql="SELECT workload.id,workload.work_name,workload.work_description,workload.units_per_week,(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id)) AS remaining_units, IF((workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))>=0,LEAST((workload.units_per_week-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE WEEKOFYEAR(workplan.completion_date)=WEEKOFYEAR(NOW()) AND workplan.completion_status=1 AND workload_id=workload.id)),(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))),0) AS remaining_units_in_week FROM workload WHERE workload.active='1'";
        return $this->Db->fetchSQL($sql);
    }

    public function deleteWorkplan($wp_id)
    {
        $this->Db->query("DELETE FROM inbox WHERE id='$wp_id'");
    }

}