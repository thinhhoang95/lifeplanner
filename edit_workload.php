<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workplanner - Sửa đổi công việc</title>
    <link rel="stylesheet" href="style/reset.css"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,vietnamese' rel='stylesheet'
          type='text/css'/>
    <link href='https://fonts.googleapis.com/css?family=Product+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/default.css"/>
    <script language="javascript" src="js/jquery-2.2.4.min.js"></script>
</head>
<body>

<?php include("page/header.php") ?>
<?php
include_once "bin/class/datatier/workloader.php";
if(!isset($_GET["id"])) die("Invalid parameter specified!  No ID found in the URL!");
$workloader = new \datatier\workloader();
$workload=$workloader->getWorkloadById($_GET["id"]);
?>
<div class="main_page">

    <h1>Chỉnh sửa nội dung</h1>
    <form id="reg_form">
        <div class="form_entry">
            <div>Tên công việc</div>
            <div>
                <input type="text" name="work_name" value="<?php echo $workload->work_name?>" />
            </div>
        </div>
        <div class="form_entry">
            <div>Mô tả công việc</div>
            <div>
                <input type="text" name="work_description" value="<?php echo $workload->work_description?>"/>
            </div>
        </div>
        <div class="form_entry">
            <div>Tổng số tiết</div>
            <div>
                <input type="number" name="total_time_units" value="<?php echo $workload->total_time_units?>" />
            </div>
        </div>
        <div class="form_entry">
            <div>Số tiết trên tuần</div>
            <div>
                <input type="number" name="units_per_week" value="<?php echo $workload->units_per_week?>" />
            </div>
        </div>
        <div class="form_entry">
            <div>Tình trạng kích hoạt (0 hoặc 1)</div>
            <div>
                <input type="number" name="active" value="<?php echo $workload->active ?>" />
            </div>
        </div>
        <div class="form_entry">
            <div class="submit_button" id="submit_button">
                LƯU THAY ĐỔI
            </div>
            <div class="submit_button" id="delete_button" style="background: #E91E63">
                XÓA CÔNG VIỆC NÀY
            </div>
        </div>
    </form>

</div>

<div class='toast' style='display:none'>Event Created</div>

<script language="javascript">
    $("#submit_button").on("click", function (e) {
        var serializedData=$("#reg_form").serialize();
        console.log(serializedData);
        $.ajax({
            url: 'bin/action/update_workload.php?id=<?php echo $_GET["id"]?>',
            data: serializedData,
            type: 'POST'
        }).success(function(data){
            if(data=="OK")
            {
                showToast("Thành công!");
                $("#time_units").val("");
                $("#id").focus();
            } else {
                alert("Lỗi xảy ra: "+data+". Vui lòng thử lại sau!");
            }
        });
    });
    $("#delete_button").on("click",function(e){
        var serializedData=$("#reg_form").serialize();
        console.log(serializedData);
        $.ajax({
            url: 'bin/action/delete_workload.php?id=<?php echo $_GET["id"]?>',
            data: serializedData,
            type: 'POST'
        }).success(function(data){
            if(data=="OK")
            {
                window.location="index.php";
            } else {
                alert("Lỗi xảy ra: "+data+". Vui lòng thử lại sau!");
            }
        });
    });

    function showToast(message)
    {
        $('.toast').html(message);
        $('.toast').stop();
        $('.toast').fadeIn(400).delay(1000).fadeOut(400); //fade out after 3 seconds
    }

</script>

</body>
</html>