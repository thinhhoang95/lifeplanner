<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LifePlanner - Welcome</title>
    <link rel="stylesheet" href="style/reset.css" />
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,vietnamese' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css?family=Product+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/default.css" />
    <script language="javascript" src="js/jquery-2.2.4.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(["init", {
            appId: "d756e9e2-2987-4b9b-870c-8a36afe3868d",
            autoRegister: false,
            notifyButton: {
                enable: true /* Set to false to hide */
            }
        }]);
    </script>
</head>

<body>

    <?php include("page/header.php") ?>

    <div class="main_page">

    <?php
    include_once "bin/class/datatier/workloader.php";
    $workloader = new \datatier\workloader();
    // $result = $workloader->workLoadWeekBuild();
    ?>

        <h1>Đăng ký kế hoạch</h1>

        <div id="registration_form">
            <form name="registration_form" id="reg_form">
                <div class="form_entry">
                    <div>Tên công việc cần thực hiện</div>
                    <div>
                        <select name="id" id="id">
                            <?php
                            $result = $workloader->workLoadAllBuild();
                            foreach ($result as $item) {
                                echo "<option value=\"" . $item->id . "\">" . $item->work_name . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form_entry">
                    <div>Số tiết đăng ký</div>
                    <div>
                        <input type="number" id="time_units" name="time_units">
                    </div>
                </div>
                <div class="form_entry">
                    <div>Kế hoạch làm việc</div>
                    <div>
                        <textarea id="mission" name="mission"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <div class="form_entry">
            <div class="submit_button" id="submit_button">
                ĐĂNG KÝ LỊCH LÀM VIỆC
            </div>
        </div>

        <h1>Thống kê</h1>
        <table>
            <tr class="title">
                <td class="abypass" style="width: 5%">
                    ID
                </td>
                <td style="width: 25%">
                    TÊN CÔNG VIỆC
                </td>
                <td class='bypass' style="width: 40%">
                    MÔ TẢ
                </td>
                <td style="width:15%">
                    TỔNG TIẾT
                </td>
                <td style="width:15%">
                    TIẾT CÒN
                </td>
            </tr>

            <?php
            $result = $workloader->workLoadWeekBuild();
            foreach ($result as $item) {
                echo "<tr>";
                if ($item->remaining_units > 1000) {
                    $remaining_units_display = '-';
                } else {
                    $remaining_units_display = $item->remaining_units;
                }
                echo "<td class='abypass'>" . $item->id . "</td><td><a href='edit_workload.php?id=" . $item->id . "'>" . $item->work_name . "</a></td><td class='bypass'>" . $item->work_description . "</td><td>" . $remaining_units_display . "</td><td>" . $item->remaining_units_in_week . "</td>";
                echo "</tr>";
            }
            ?>

        </table>

        <a href="create_workload.php">Thêm công việc</a>

        <h1>Gợi ý</h1>

        <ul>

            <?php
            include_once "bin/class/queries/worksuggestor.php";
            $worksuggestor = new \queries\worksuggestor($result);
            $suggestions = $worksuggestor->suggest(5);
            $i = 0;
            foreach ($suggestions as $item) {
                $i++;
                echo "<li><span style='font-weight: bold'>" . $i . ". </span>" . $item->work_name;
            }
            ?>

        </ul>


    </div>

    <div class='toast' style='display:none'>Event Created</div>

    <script language="javascript">
        $("#submit_button").on("click", function(e) {
            var serializedData = $("#reg_form").serialize();
            console.log(serializedData);
            $.ajax({
                url: 'bin/action/register_workload.php',
                data: serializedData,
                type: 'POST'
            }).success(function(data) {
                if (data == "OK") {
                    showToast("Thành công!");
                    $("#time_units").val("");
                    $("#id").focus();
                    $("#mission").val("");
                } else {
                    alert("Lỗi xảy ra: " + data + ". Vui lòng thử lại sau!");
                }
            });
        });

        function showToast(message) {
            $('.toast').html(message);
            $('.toast').stop();
            $('.toast').fadeIn(400).delay(1000).fadeOut(400); //fade out after 3 seconds
        }
    </script>

</body>

</html>