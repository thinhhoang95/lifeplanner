<html>
    <head>
        <title>Printing test</title>
        <script src="zip.js"></script>
        <script src="zip-ext.js"></script>
        <script src="deflate.js"></script>
        <script src="JSPrintManager.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    </head>
    <div style="text-align:center">
        <h1>Print current workplan</h1>
        <hr />
        <label class="checkbox">
            <input type="checkbox" id="useDefaultPrinter" /> <strong>Print to Default printer</strong>
        </label>
        <p>or...</p>
        <div id="installedPrinters">
            <label for="installedPrinterName">Select an installed Printer:</label>
            <select name="installedPrinterName" id="installedPrinterName"></select>
        </div>
        <br /><br />
        <button onclick="print();">Print Now...</button>
    </div>

    <script>
 
        //WebSocket settings
        JSPM.JSPrintManager.auto_reconnect = true;
        JSPM.JSPrintManager.start();
        JSPM.JSPrintManager.WS.onStatusChanged = function () {
            if (jspmWSStatus()) {
                //get client installed printers
                JSPM.JSPrintManager.getPrinters().then(function (myPrinters) {
                    var options = '';
                    for (var i = 0; i < myPrinters.length; i++) {
                        options += '<option>' + myPrinters[i] + '</option>';
                    }
                    $('#installedPrinterName').html(options);
                });
            }
        };
     
        //Check JSPM WebSocket status
        function jspmWSStatus() {
            if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
                return true;
            else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
                alert('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
                return false;
            }
            else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.BlackListed) {
                alert('JSPM has blacklisted this website!');
                return false;
            }
        }

        function formatWithMargin(text)
        {
            return '' + text;
        }
     
        function formatWithMarginDoubleWidth(text)
        {
            return '' + text;
        }

        function wordwrap(str, width, margin) {
			prefix = '';
            postfix = '\x0A';
            postfix += formatWithMargin('');
            postfix += prefix.padEnd(margin, ' ');
            spaceReplacer = postfix;
            if (str.length>width) {
                var p=width
                for (;p>0 && str[p]!=' ';p--) {
                }
                if (p>0) {
                    var left = str.substring(0, p);
                    var right = str.substring(p+1);
                    return left + spaceReplacer + wordwrap(right, width, margin);
                }
            }
        return str;
    }

        function padding(str, width)
        {
            resStr = String(str);
            if(resStr.length<width) {
                return resStr.padEnd(width, ' ');
            } else {
                return resStr.substr(0, width);
            }
        }       

        function makeid(length) {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            for (var i = 0; i < length; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }

        //Do printing...
        function print(o) {
            if (jspmWSStatus()) {
                //Create a ClientPrintJob
                var cpj = new JSPM.ClientPrintJob();
                //Set Printer type (Refer to the help, there many of them!)
                if ($('#useDefaultPrinter').prop('checked')) {
                    cpj.clientPrinter = new JSPM.DefaultPrinter();
                } else {
                    cpj.clientPrinter = new JSPM.InstalledPrinter($('#installedPrinterName').val());
                }
                //Set content to print...
                //Create ESP/POS commands for sample label
                var esc = '\x1B'; //ESC byte in hex notation
                var newLine = '\x0A'; //LF byte in hex notation

                var cmds = esc + "@"; //Initializes the printer (ESC @)

                cmds += esc + 'x' + '\x00'; // Draft font
                
                cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
                cmds += esc + 'a' + '\x01'; // Center alignment 

                cmds += formatWithMargin('NEURALMETRICS LIFEPLANNER 2.0'); 
                cmds += newLine;
                cmds += formatWithMargin('WORKPLAN INFORMATION SHEET')
                cmds += newLine + newLine;
                cmds += newLine + newLine;

                cmds += esc + 'a' + '\x00'; // Left alignment
                cmds += formatWithMargin('STUDENT NAME: Hoang Dinh Thinh      ID: 41303880');
                cmds += newLine;
                cmds += formatWithMargin('BIRTHDATE: 24/05/1995               SEX: M');
                cmds += newLine;
                cmds += formatWithMargin('SOCIAL CREDIT NUMBER: ******478     RCRD PAGE: 1');
                cmds += newLine;
                var today = new Date();
                cmds += formatWithMargin('DATE ISSUED: ' + today.toLocaleDateString());

                cmds += newLine + newLine;
                cmds += newLine + newLine;

                cmds += formatWithMargin('REGISTERED TASKS');
                cmds += newLine;
                cmds += formatWithMargin('------------------------------------------------');
                cmds += newLine;
                cmds += formatWithMargin('No    Task name       Note');
                cmds += newLine;
             // cmds += formatWithMargin('1     Manifold Opt    Manifold theory review');
                // cmds += newLine;

                <?php
                include_once "../bin/class/datatier/workplaner.php";
                $workplaner = new \datatier\workplaner();
                $listOfPlans = $workplaner->workplanUnfinishedBuild();
                $i = 0;
                foreach ($listOfPlans as $item) {
                    $i++;
                    echo "cmds += formatWithMargin(padding('$item->work_name',48));"; 
                    echo "cmds += newLine;";
                    echo "cmds += wordwrap('$item->mission',48,0);";
                    echo "cmds += newLine;";
                }
                ?>

                cmds += formatWithMargin('------------------------------------------------');
                cmds += newLine;
                cmds += esc + '!' + '\x18'; //Emphasized + Double-height mode selected (ESC ! (16 + 8)) 24 dec => 18 hex
                <?php
                echo "cmds += formatWithMargin('TOTAL WORKLOAD: $i');";
                ?>
                // cmds += formatWithMargin('TOTAL WORKLOAD: 2, TOTAL TIME UNITS: 2');
                cmds += newLine + newLine + newLine;

                cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)                

                cmds += formatWithMargin('WEEK SUMMARY');
                cmds += newLine;
                cmds += formatWithMargin('------------------------------------------------');
                cmds += newLine;
                cmds += formatWithMargin('No    Task name       UPW     RMT     RMW');
                cmds += newLine;

                <?php
                $stats = $workplaner->statsThisWeekBuild();
                $j = 0;
                foreach($stats as $stat)
                {
                    $j++;
                    $work_name = $stat["work_name"];
                    $upw = $stat["units_per_week"];
                    $rmt = $stat["remaining_units"];
                    $rmw = $stat["remaining_units_in_week"];
                    echo "cmds += formatWithMargin(padding('$j',6) + padding('$work_name',16) + padding('$upw', 8) + padding('$rmt',8) + padding('$rmw',8));";
                    echo "cmds += newLine;";
                }
                ?>

                cmds += formatWithMargin('------------------------------------------------');
                cmds += newLine + newLine + newLine;

                var sheetCode = makeid(6);
                cmds += formatWithMargin('SHEET CODE: ' + sheetCode);
                cmds += newLine;

                cmds += '\x1d\x6b\x04' + sheetCode + '\x00';

                cmds += esc + 'a' + '\x02'; // Right alignment

                cmds += newLine;
                cmds += newLine;
                cmds += newLine;

                cmds += formatWithMargin('Signature of participant.')

                

                cmds += '\x1d\x56\x00';
     
                cpj.printerCommands = cmds;
                //Send print job to printer!
                cpj.sendToClient();
                console.log(cmds);
            }
        }
     
    </script>
</html>
