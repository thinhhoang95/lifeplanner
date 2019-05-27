const escpos = require('escpos');
const mysql = require('mysql');
const promise = require('promise');
 
// Select the adapter based on your printer type
const device  = new escpos.USB();
// const device  = new escpos.Network('localhost');
// const device  = new escpos.Serial('/dev/usb/lp0');
 
const options = { encoding: "GB18030" /* default */ }
// encoding is optional
 
const printer = new escpos.Printer(device, options);

/* Query data from database */
var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "lifeplanner"
  });

var workload, stats;
  
var queryAllPromise = function(){
    return new Promise((resolve, reject)=>{
        var flag = 0;
        con.connect(function(err) {
            if (err) throw err;
            con.query("SELECT inbox.*,workload.work_name FROM inbox,workload WHERE workload.id=inbox.workload_id AND inbox.completion_status='0' ORDER BY inbox.id DESC LIMIT 20", function (err, result, fields) {
                if (err) throw err;
                workload = result;
                flag++;
                if (flag==2) resolve();
            });
            con.query("SELECT workload.id,workload.work_name,workload.work_description,workload.units_per_week,(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id)) AS remaining_units, IF((workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))>=0,LEAST((workload.units_per_week-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE WEEKOFYEAR(workplan.completion_date)=WEEKOFYEAR(NOW()) AND workplan.completion_status=1 AND workload_id=workload.id)),(workload.total_time_units-(SELECT COALESCE(SUM(workplan.time_units),0) FROM workplan WHERE workplan.completion_status=1 AND workplan.workload_id=workload.id))),0) AS remaining_units_in_week FROM workload WHERE workload.active='1'", function(err, result, field){
                if (err) throw err;
                stats = result;
                flag++;
                if (flag==2) resolve();
            });
          });
    });
}

var printPromise = function(){
    return new Promise((resolve, reject)=>{
        if (workload && stats)
        {
            printWorkload();
        }
    });
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

var today = new Date();

var printWorkload = ()=>{
    device.open(function(){
    printer
    .font('a')
    .align('ct')
    .style('bu')
    .size(1, 1)
    .text('NEURALMETRICS LIFEPLANNER')
    .text('WORKLOAD INFORMATION SHEET')
    .control('lf')
    .control('lf')
    .align('lt')
    .text('STUDENT NAME: Hoang Dinh Thinh      ID: 41303880')
    .text('BIRTHDATE: 24/05/1995               SEX: M')
    .text('SOCIAL CREDIT NUMBER: ******478     RCRD PAGE: 1')
    .text('DATE ISSUED: ' + today.toLocaleDateString())
    .control('lf')
    .control('lf')
    .text('REGISTERED TASKS')
    .text('------------------------------------------------')
    workload.forEach((wl)=>{
        printer.text(padding(wl['work_name'], 48))
        .text(wordwrap(wl['mission'], 48, 0))
        .control('lf')
    });
    printer.text('------------------------------------------------')
    .text('STATISTICS')
    .text('------------------------------------------------')
    .text('No    Task name       UPW     RMT     RMW');
    var j = 0;
    var sheetId = makeid(10);
    stats.forEach((el)=>{
        j++;
        printer.text(formatWithMargin(padding(j,6) + padding(el["work_name"],16) + padding(el["units_per_week"], 8) + padding(el["remaining_units"],8) + padding(el["remaining_units_in_week"],8)))
    });
    printer.text('------------------------------------------------').control('lf').control('lf')
    .text('Workload code: ' + sheetId)
    .align('ct')
    .barcode(sheetId, 'CODE39')
    .control('lf')
    .text('Signature of participant');
    
    printer.control('lf').control('lf').cut().close();
    /*.barcode('1234567', 'EAN8')
    .qrimage('https://github.com/song940/node-escpos', function(err){
        this.cut();
        this.close();
    });*/
    });
}

async function init()
{
    await queryAllPromise();
    await printPromise();
    console.log('Print completed');
}

init();