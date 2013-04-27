<?php 
session_start();
if ((! isset($_SESSION['login'])) || (strcmp($_SESSION['login'],"OK" ) !=0)) {
	header("Location: http://".$_SERVER['HTTP_HOST']."/motion/login.php?lurl=".basename($_SERVER['PHP_SELF']));
	exit();
}
?>
<?php
exec ('/usr/bin/lwp-request http://localhost:8070/0/action/snapshot > /dev/null 2>&1');

?>
<html lang="ja">
<head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/base.css">
<title>現在の様子</title>
<script src="js/mdl.js" type="text/javascript"></script>
</head>
<body>
<p class="resizeimage"><img src="./lastsnap.jpg" alt="lastsnap.jpg"></p><br>
<?php
if (file_exists("./lastsnap.jpg")) {
	$time=date("Y/m/d H:i:s.", filemtime("./lastsnap.jpg")); 
	echo $time;
}
echo '<br><input type="button" NAME="nowtemp" VALUE="現在温度の取得" ONCLICK="getnowtemp();" style="width:180px;height:40px"><br>'."\n";
echo '湿度 <span id="hd"></span><br>'."\n";
echo '１： <span id="t0"></span><br>'."\n";
echo '２： <span id="t1"></span><br>'."\n";
echo '３： <span id="t2"></span><br>'."\n";

?>
</body>
</html>
