<?php
session_start();
require ('lib/mdl.php');
//serial を使用

///////////////// ここからメイン ////
//ログアウト
if (isset($_GET['logout'])) { $_SESSION['login']='NG'; }


if ((! isset($_SESSION['login'])) || (strcmp($_SESSION['login'],"OK" ) !=0)) {
	$_SESSION['login']='NG';
	header("Location: http://".$_SERVER['HTTP_HOST']."/motion/login.php?lurl=index.php");	
	exit();	
}

if (isset($_GET['page'])) { $page=$_GET['page'];}else {$page=0;}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3">
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/base.css">
<title>Webカメラ</title>
<!-- Script -->
<script src="js/mdl.js" type="text/javascript"></script>
<script type="text/javascript">
<!--

//-->
</script>
</head>
<body>

<?php
if (file_exists("./now.jpg")) {
	echo '<div align="center"><h2>最終保存画像</h2><br></div>'."\n";
	echo '<a href="./now.jpg"><p class="resizeimage"><img src="./now.jpg" alt="最終画像" ></p></a><br>'."\n";
	$m = filemtime("./now.jpg");
	echo '<div align="right">'.date("Y/m/d H:i",$m)."</div><br>"."\n";
}

echo '<input type="button" NAME="nowtemp" VALUE="現在温度の取得" ONCLICK="getnowtemp();" style="width:180px;height:40px"><br>'."\n";
echo '湿度 <span id="hd"></span><br>'."\n";
echo '１： <span id="t0"></span><br>'."\n";
echo '２： <span id="t1"></span><br>'."\n";
echo '３： <span id="t2"></span><br>'."\n";

//有効な日付フォルダのみハイパーリンクを張る。
//$a = array_dirlist('.');
//$fp = array_fullpath('.',$a);
$fp = file('./diarydir.txt');

$cnt=0;
$dspCnt=10;
$tCnt=count($fp);
rsort($fp);
echo '<aside><h4>';
for ($j=$page;$j<$tCnt;$j++){
       	//echo '<a href="'.substr($fp[$j],2,10).'/">'.substr($fp[$j],2,10).'</a><br>'."\n";
       	echo '<a href="'.ereg_replace("\r|\n","",$fp[$j]).'/">'.ereg_replace("\r|\n","",$fp[$j]).'</a><br>'."\n";
	$cnt++;
	if ($cnt>=$dspCnt){break;} 
}
$j++;
echo "</aside></h4><hr><aside><h4>";
	if ($j-$dspCnt> 0 ){ 
		$cc=$page-$dspCnt;
		if ($cc<0){$cc=0;}
		echo '<a href ="index.php?page='.$cc.'">前へ</a>'."\n";
	}
	if ($j < $tCnt) {
		echo '<a href ="index.php?page='.$j.'">次へ</a>'."\n";
	}

echo "</aside></h4><hr>\n";

//ローカル環境とWeb環境で現在の様子のアプレットのアドレスを変える必要がある。
$svm=$_SERVER['SERVER_ADDR'];
$c=strrpos($svm,".");
if($c !== false) {
	//192.168.1.  を取得	(とりあえずローカルは/24のネットワークと決めつける)
	$svm=substr($svm,0,($c+1));
}

$haddr="";
if (strpos(getenv("REMOTE_ADDR"),$svm)===false)  {  //192.168.1.がリモートアドレスに含まれていたらローカルと判断
	$c=strpos($_SERVER['HTTP_HOST'],":8083");
	if ($c !==false) {
		$haddr=substr($_SERVER['HTTP_HOST'],0,$c);
	} else {
		$haddr=$_SERVER['HTTP_HOST'];
	}
} else {
	$haddr=$_SERVER['SERVER_ADDR'];
}

echo '<aside><h5><a href="./temper.php">温度管理</a><br>'."\n";
$agent = is_mobile();
switch($agent){
	case "sp":
		echo '<a href="./nowmobile.php">現在の様子</a>'."\n";
		break;
	case "docomo":
		echo '<a href="./nowmobile.php">現在の様子</a>'."\n";
		break;
	default:
	echo '<a href="./cam.php?haddr='.$haddr.'">現在の様子</a>'."\n";
}

?>

<br><a href ="./mailconfig.php">アラートメール送信設定</a><br>
<a href ="./config.php">システム設定</a><br>
<a href ="./index.php?logout=1">ログアウト</a><br>
</aside></h5>
</body>
</html>
