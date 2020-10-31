<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LifePlanner - Execute</title>
    <link rel="stylesheet" href="style/reset.css"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,vietnamese' rel='stylesheet'
          type='text/css'/>
    <link href='https://fonts.googleapis.com/css?family=Product+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/default.css"/>
    <script language="javascript" src="js/jquery-2.2.4.min.js"></script>
    <style>
        .actbtn
        {
            height: 25px;
            font-size: 8pt;
            font-weight: 300;
            padding-left: 6px;
            padding-right: 6px;
            display: inline-block;
            color: #FFF;
            cursor: pointer;
            border-radius: 3px;
        }
        .actbtn.green
        {
            margin-right: 4px;
            background: #8BC34A;
        }
        .actbtn.red{
            background: #F44336;
        }
    </style>
</head>
<body>

<?php include("page/header.php") ?>

<div class="main_page">

    <h1>HOÀN THÀNH CÔNG VIỆC</h1>
    <table>
        <tr class="title">
            <td class="abypass" style="width: 5%">
                ID
            </td>
            <td style="width: 50%">
                TÊN CÔNG VIỆC
            </td>
            <td style="width: 10%">
                SỐ TIẾT ĐK
            </td>
            <td style="width:20%">
                THỜI GIAN ĐK
            </td>
            <td style="width:15%">
                HÀNH ĐỘNG
            </td>
        </tr>

        <?php

        function time_elapsed_string($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'năm',
                'm' => 'tháng',
                'w' => 'tuần',
                'd' => 'ngày',
                'h' => 'giờ',
                'i' => 'phút',
                's' => 'giây',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' trước' : 'Tức thời';
        }

        include_once "bin/class/datatier/workplaner.php";
        $workplaner = new \datatier\workplaner();
        $listOfPlans = $workplaner->workplanUnfinishedBuild();
        foreach ($listOfPlans as $item) {
            echo "<tr>";
            echo "<td id='id' class='abypass'>" . $item->id . "</td><td id='work_name' style='font-weight: bold; color: #E91E63'>" . $item->work_name . "</td><td id='time_units'><input type='text' id='input_time_units' data-id='".$item->id."' value='". $item->time_units ."'/></td><td id='reg_date'>" . time_elapsed_string($item->registration_date,false) . "</td><td>" . "<div class='actbtn green' data-id='".$item->id."'>DONE</div><div class='actbtn red' data-id='".$item->id."'>DELETE</div>" . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='4'><input type='text' style='width: 100%' id='input_mission' data-id='".$item->id."' value='".$item->mission."'/></td>";
            echo "</tr>";
        }
        ?>

    </table>

    <h1>ĐĂNG KÝ GẦN ĐÂY</h1>
    <table id="recently_registered">
        <tr class="title">
            <td class="abypass" style="width: 5%">
                ID
            </td>
            <td style="width: 55%">
                TÊN CÔNG VIỆC
            </td>
            <td style="width: 10%">
                SỐ TIẾT ĐK
            </td>
            <td style="width:20%">
                HOÀN THÀNH
            </td>
        </tr>

        <?php
        $listOfPlans = $workplaner->workPlanFinishedBuild();
        foreach ($listOfPlans as $item) {
            echo "<tr>";
            echo "<td class='abypass'>" . $item->id . "</td><td><p style='font-weight: bold; color: #F44336'>" . $item->work_name . "</p><p>".$item->mission."</p></td><td>" . $item->time_units . "</td><td>" . time_elapsed_string($item->completion_date) . "</td>";
            echo "</tr>";
        }
        ?>

    </table>

    <h1>Kích hoạt công việc</h1>
    <table id="activate_workload">
        <tr class="title">
            <td class="abypass" style="width: 5%">
                ID
            </td>
            <td style="width: 55%">
                TÊN CÔNG VIỆC
            </td>
            <td style="width: 10%">
                TỔNG SỐ TIẾT
            </td>
            <td style="width:20%">
                SỐ TIẾT/TUẦN
            </td>
        </tr>

        <?php
        include_once "bin/class/datatier/workloader.php";
        $workloader = new \datatier\workloader();
        
        $listOfWorkloads = $workloader->workLoadAllUnactivated();
        foreach ($listOfWorkloads as $item) {
            echo "<tr>";
            echo "<td class='abypass'>" . $item->id . "</td><td><a href='edit_workload.php?id=".$item->id."'>" . $item->work_name . "</a></td><td>" . $item->total_time_units . "</td><td>" . $item->units_per_week . "</td>";
            echo "</tr>";
        }
        ?>

    </table>

</div>

<div class='toast' style='display:none'>Event Created</div>

<script language="javascript">
    function showToast(message)
    {
        $('.toast').html(message);
        $('.toast').stop();
        $('.toast').fadeIn(400).delay(1000).fadeOut(400); //fade out after 3 seconds
    }
    $(".red").on("click",function(e){
        var wp_id=$(this).attr("data-id");
        deleteWorkplan(wp_id,$(this));
    });
    $(".green").on("click",function(e)
    {
        var wp_id=$(this).attr("data-id");
        doneWorkplan(wp_id,$(this));
    });
    function deleteWorkplan(id,element)
    {
        $.ajax("bin/action/delete_workplan.php?id="+id).done(function(data){
            if(data=="OK")
            {
                showToast("Thành công!");
                // element.parent().parent().remove();
                location.reload();
            } else {
                alert("Lỗi xảy ra: "+data+". Vui lòng thử lại sau!");
            }
        });
    }
    function doneWorkplan(id,element)
    {
        var hosttr = element.parent().parent();
        var tu = hosttr.find("#input_time_units").val();
        console.log('Time units are: ' + tu);
        var mission = hosttr.next().find("#input_mission").val();
        $.ajax("bin/action/finish_workplan.php?id="+id+"&timeunits="+tu+"&mission="+encodeURIComponent(mission)).done(function(data){
            if(data=="OK") {
                var idx = hosttr.find("#id").html();
                var work_name = hosttr.find("#work_name").html();
                var reg_date = hosttr.find("#reg_date").html();
                var time_units = tu;
                var insert_tag = "<tr><td class='abypass'>" + idx + "</td><td>" + work_name + "</td><td>" + time_units + "</td><td>" + reg_date + "</td></tr>";
                $("#recently_registered").append(insert_tag);
                // hosttr.remove();
                location.reload();
                showToast("Thành công!");
            } else {
                alert("Lỗi xảy ra: "+data+". Vui lòng thử lại sau!");
            }
        });
    }
</script>

</body>
</html>