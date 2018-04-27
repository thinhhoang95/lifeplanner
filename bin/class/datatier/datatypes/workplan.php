<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/7/2016
 * Time: 7:49 AM
 */

namespace datatier\datatypes;


class workplan
{
    public $id;
    public $workload_id;
    public $time_units;
    public $registration_date;
    public $completion_status;
    public $completion_date;
    public $work_name;
    public $mission;

    public function initWithObj($obj)
    {

        $this->id=$obj["id"];
        $this->workload_id=$obj["workload_id"];
        $this->time_units=$obj["time_units"];
        $this->registration_date=$obj["registration_date"];
        $this->completion_status=$obj["completion_status"]=="1"?true:false;
        $this->completion_date=$obj["completion_date"];
        if(isset($obj["work_name"])) $this->work_name=$obj["work_name"];
        $this->mission=$obj["mission"];
    }

    public function getRegistrationDate()
    {
        return strtotime($this->registration_date);
    }

}