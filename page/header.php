<div class="header">
    <div class="wrapper">
        <div>
            <div class="logo" style="flex: 1 0 auto"><span style="font-weight: bold">Divergent</span> Life Planner</div>
            <div class="account_name">Hoàng Đình Thịnh</div>
        </div>
        <div>
            <div class="navigation_tabs">
                <div class="tab <?php echo ((basename($_SERVER['REQUEST_URI'])=='index.php')||basename($_SERVER['REQUEST_URI'])=='lifeplanner')?"active":""?>" id="nav_home_btn">CÔNG VIỆC</div>
                <div class="tab <?php echo basename($_SERVER['REQUEST_URI'])=='execute.php'?"active":""?>" id="nav_exec_btn">THỰC THI</div>

                <script language="javascript">
                    $("#nav_home_btn").on("click",function(){
                        window.location="index.php";
                    });
                    $("#nav_exec_btn").on("click",function(){
                        window.location="execute.php";
                    });
                </script>

            </div>
        </div>
    </div>
</div>