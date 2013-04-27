<?php 
session_start();
if ((! isset($_SESSION['login'])) || (strcmp($_SESSION['login'],"OK" ) !=0)) {
	header("Location: http://".$_SERVER['HTTP_HOST']."/motion/login.php?lurl=".basename($_SERVER['PHP_SELF']));
	exit();
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/base.css">
<title>現在の様子</title>
</head>
<!-- Script -->
<script src="js/mdl.js" type="text/javascript"></script>
<script type="text/javascript">
<!--

//-->
</script>
<body>
<applet code=com.charliemouse.cambozola.Viewer
archive=cambozola.jar width=640 height=480>
<?php

echo "<param name=url value=http://".$_GET['haddr'].":8081>";

?>
</applet>
<br>
<!-- <input type="button" NAME="on_light" VALUE="ライト点灯" ONCLICK="onlight();" style="width:110px;height:40px"><br> -->
<input type="button" NAME="nowtemp" VALUE="現在温度の取得" ONCLICK="getnowtemp();" style="width:180px;height:40px"><br>
湿度 <span id="hd"></span><br>
１： <span id="t0"></span><br>
２： <span id="t1"></span><br>
３： <span id="t2"></span><br>

<br><a href="./index.php">戻る</a>
</body>
</html>
