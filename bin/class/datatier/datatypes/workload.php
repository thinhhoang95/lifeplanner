<?php
/**
 * Created by IntelliJ IDEA.
 * User: thinhhoang
 * Date: 66//16
 * Time: 11:43 AM
 */

namespace datatier\datatypes;


class workload
{
    public $id;
    public $work_name;
    public $work_description;
    public $total_time_units;
    public $units_per_week;
    public $active;

    public function initWithParams($id,$work_name,$work_description,$total_time_units,$units_per_week,$active)
    {
        $this->id=$id;
        $this->work_name=$work_name;
        $this->work_description=$work_description;
        $this->total_time_units=$total_time_units;
        $this->units_per_week=$units_per_week;
        $this->active=$active;
    }

    public function initWithObj($rowobj)
    {
        $this->id=$rowobj["id"];
        $this->work_name=$rowobj["work_name"];
        $this->work_description=$rowobj["work_description"];
        $this->total_time_units=$rowobj["total_time_units"];
        $this->units_per_week=$rowobj["units_per_week"];
        $this->active=$rowobj["active"];
    }

}