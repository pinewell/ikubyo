<?php 
session_start();
if ((! isset($_SESSION['login'])) || (strcmp($_SESSION['login'],"OK" ) !=0)) {
	header("Location: http://".$_SERVER['HTTP_HOST']."/motion/login.php?lurl=".basename($_SERVER['PHP_SELF']));
	exit();
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3">
<link rel="stylesheet" href="../../../css/reset.css">
<link rel="stylesheet" href="../../../css/base.css">
<title>Webカメラ</title>
<!-- Script -->
<script src="../../../js/mdl.js" type="text/javascript"></script>
<script type="text/javascript">
<!--

//-->
</script>
</head>
<body>
<center>
<?php
$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/motion/cfg/config.ini");
	if (isset($_GET['Cnt'])) { $Cnt=$_GET['Cnt'];}else {$Cnt=0;}
	require($_SERVER['DOCUMENT_ROOT']."/motion/lib/imgClass.php");
	$imgCls = new imgClass(dirname(__FILE__),3,$ini['kirinID'],$ini['kirinHost']);  //一度に3枚表示
	$weekjp_array = array('日','月','火','水','木','金','土');
	$path = explode("/",$_SERVER['SCRIPT_FILENAME']);

	for ($i = 0; $i <=2; $i++) {
		if ($i==0){
			$pyy=$path[count($path)-(4-$i)];
		}
		if ($i==1){
			$pmm=$path[count($path)-(4-$i)];
		}
		if ($i==2){
			$pdd=$path[count($path)-(4-$i)];
		}
	}
	$ptimestamp = mktime(0,0,0,$pmm,$pdd,$pyy);
	$weekno = date('w',$ptimestamp);
	$weekjp = $weekjp_array[$weekno];
	echo $pyy."年".$pmm."月".$pdd."日(".$weekjp.")";
?>
</center>
<hr>
<?php
	echo $imgCls->listImg(0,$Cnt);  //テーブル不使用
?>
<br><a href="../../../index.php" > 戻る </a>
</body>
</html>
