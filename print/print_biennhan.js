const escpos = require('escpos');
const mysql = require('mysql');
const promise = require('promise');
const express = require('express');
const app = express(),
      bodyParser = require('body-parser');

app.use(bodyParser.urlencoded({ extended: true })); 

app.get('/', async (req, res) => {
    var inboxId = req.query.id;
    console.log('Request print inbox id ' + inboxId);
    await queryAllPromise(inboxId);
    await printWorkload();
    console.log('Print completed!');
    res.status(200).send("{'stat': 'OK'}");
});

app.listen(3001, () => console.log(`Print Biennhan App is listening on port 3001!`))
 
// Select the adapter based on your printer type
const device  = new escpos.USB();
// const device  = new escpos.Network('localhost');
// const device  = new escpos.Serial('/dev/usb/lp0');
 
const options = { encoding: "GB18030" /* default */ }
// encoding is optional
 
const printer = new escpos.Printer(device, options);

var workload;
  
var queryAllPromise = function(inboxId){
    return new Promise((resolve, reject)=>{
        /* Query data from database */
        var con = mysql.createConnection({
            host: "localhost",
            user: "root",
            password: "",
            database: "lifeplanner"
        });
        con.on('error', function() {});
        con.connect(function(err) {
            if (err) throw err;
            con.query("SELECT inbox.*, workload.work_name FROM inbox, workload WHERE inbox.workload_id = workload.id AND inbox.id='" + inboxId + "'", function (err, result, fields) {
                if (err) throw err;
                workload = result;
                console.log(workload);
                con.end();
                resolve();
            });
          });
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
    .text('WORKLOAD INFORMATION RECEIPT')
    .control('lf')
    .control('lf')
    .align('lt')
    .text('STUDENT NAME: Hoang Dinh Thinh      ID: 41303880')
    .text('BIRTHDATE: 24/05/1995               SEX: M')
    .text('SOCIAL CREDIT NUMBER: ******478     RCRD PAGE: 1')
    .text('DATE ISSUED: ' + today.toLocaleDateString())
    .control('lf')
    .control('lf')
    .text('THE SYSTEM CERTIFIES THAT YOU HAVE COMPLETED:')
    .text('------------------------------------------------')
    workload.forEach((wl)=>{
        printer.text(padding(wl['work_name'], 48))
        .text(wordwrap(wl['mission'], 48, 0))
        .text(padding('REG: ' + wl['registration_date'].toLocaleDateString('en-US'), 48))
        .control('lf')
    });
    var sheetId = makeid(6);
    printer.text('------------------------------------------------')
    .align('ct')
    .barcode(sheetId, 'CODE39')
    .control('lf');
    
    printer.control('lf').control('lf').cut().close();
    
    /*.barcode('1234567', 'EAN8')
    .qrimage('https://github.com/song940/node-escpos', function(err){
        this.cut();
        this.close();
    });*/
    });
}