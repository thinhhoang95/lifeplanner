<?php

/**
 * Created by IntelliJ IDEA.
 * User: Thinh Hoang
 * Date: 6/7/2016
 * Time: 2:51 PM
 */
namespace queries;

class worksuggestion
{
    public $id;
    public $work_name;
    public $rand_ratio;
    public $rand_lot; // rand_lot + rand_ratio
}

class worksuggestor
{
    private $listOfWorkloads;

    public function __construct($workloadList)
    {
        $this->listOfWorkloads = $workloadList;
    }

    public function suggest($num_of_units)
    {
        $attempt = 0;
        $arr = [];
        $total_work_units = 0;
        $workload_data = [];
        while ($attempt < $num_of_units) {
            $attempt++;
            // Number of participating workload
            foreach ($this->listOfWorkloads as $item) {
                $total_work_units += $item->remaining_units_in_week;
//                echo "<br>ATTEMPT ".$attempt.": ".$item->remaining_units_in_week;
            }
            // Build a formal lottery
            $lot = 0;
            foreach ($this->listOfWorkloads as $item) {
                $ws = new worksuggestion();
                $ws->id=$item->id;
                $ws->work_name = $item->work_name;
                $ws->rand_ratio = ($item->remaining_units_in_week) / $total_work_units;
                $ws->rand_lot = $lot;
                $lot += $ws->rand_ratio;
                $workload_data[] = $ws;
            }
            // Start a lottery
            mt_srand($this->make_seed());
            $rand = mt_rand(0, 1000000);
            $rand = $rand / 1000000;
            // Check for lottery winner
            foreach ($workload_data as $item) {
                if (($item->rand_lot < $rand) && ($item->rand_lot + $item->rand_ratio > $rand)) {
                    // Lottery winner!
                    $arr[] = $item;
                    // Find the corresponding record in listOfWorkloads and make corrections
                    foreach ($this->listOfWorkloads as $val) {
                        if ($val->id == $item->id) {
                            $val->remaining_units_in_week--;
                            break;
                        }
                    }
                    break;
                }
            }
        }
        return $arr;
    }

    private function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float)$sec + ((float)$usec * 100000);
    }


}