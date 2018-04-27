<?php
/**
 * Created by IntelliJ IDEA.
 * User: thinhhoang
 * Date: 66//16
 * Time: 11:43 AM
 */

namespace datatier\datatypes;


class workloadStats
{
    public $id;
    public $work_name;
    public $work_description;
    public $remaining_units;
    public $remaining_units_in_week;

    public function initWithObj($rowobj)
    {
        $this->id=$rowobj["id"];
        $this->work_name=$rowobj["work_name"];
        $this->work_description=$rowobj["work_description"];
        $this->remaining_units=$rowobj["remaining_units"];
        $this->remaining_units_in_week=$rowobj["remaining_units_in_week"];
    }

}