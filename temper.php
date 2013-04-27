<?php 
session_start();
if ((! isset($_SESSION['login'])) || (strcmp($_SESSION['login'],"OK" ) !=0)) {
	header("Location: http://".$_SERVER['HTTP_HOST']."/motion/login.php?lurl=".basename($_SERVER['PHP_SELF']));
	exit();
}
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/base.css">
<script type="text/javascript" src="js/calendar.js" charset="UTF-8"></script>
<title>温度変化</title>
</head>
<body>
<?php


$ini = parse_ini_file("./cfg/config.ini");
//db情報を使用

//define("SENSECNT","3");  //監視するセンサーの数


require ('GChartPhp/gChart.php');
require ('lib/mydbcls.php');
require ('lib/mdl.php');
require ('lib/tempCls.php');
require ('lib/forecastCls.php');

if (isset($_POST['cal'])) {
	$cTime=	mktime(0,0,0,substr($_POST['cal'],4,2),substr($_POST['cal'],6,2),substr($_POST['cal'],0,4));
	if (isset($_POST['dMode'])) {
		$dMode=$_POST['dMode'];
	} else {
		$dMode = 'd';
	} 

} else {
	if (isset($_GET['cTime'])) {
		$cTime=$_GET['cTime'];
	} else {
		$cTime = time();  //今現在の値	
	}
	if (isset($_GET['dMode'])) {
		$dMode=$_GET['dMode'];
	} else {
		$dMode = 'd';
	} 
}
echo "<center>".getint2date($cTime)."の温度変化<br></center>\n";

$db = new mydbcls($ini['dbsrv'],$ini['dbname'],$ini['dbuser'],$ini['dbpass']);
$tp = new tempCls($dMode,$cTime,$ini['sencnt']+1,$db);
$fc = new forecastCls($cTime,$db);
$tp->getHiLo();
$tp->getMsi();
$tp->getChart();
echo '<a href="./temper.php?dMode=t&cTime='.($cTime-86400).'">前日</a> <a href="./temper.php?dMode=t&cTime='.($cTime+86400).'">翌日</a><br>'."\n";


?>
<form name="calendar" method="post" action="./temper.php">
  日付入力(YYYYMMDD)
  <input type="text" name="cal" id="p1" size="10" maxlength="8" onClick="wrtCalendar(event,this.form.p1,'yyyymmdd')">
  <input type="hidden" name="dMode" value="d" >
  <input type="submit" value="送信">
</form>
<?php
$fc->getForecast();
?>
<a href="./index.php">戻る</a>

<hr>
</body></html>
