<?php 
session_start();
$ini = parse_ini_file("./cfg/config.ini");


if ((isset($ini['loginpass'])) && (strlen($ini['loginpass']) >0)) {
	if ((! isset($_SESSION['login'])) || (strcmp($_SESSION['login'],"OK" ) !=0)) {
		header("Location: http://".$_SERVER['HTTP_HOST']."/motion/login.php?lurl=".basename($_SERVER['PHP_SELF']));
		exit();
	}
}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3">
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/base.css">
<title>システム設定</title>
<!-- Script -->
<script src="js/mdl.js" type="text/javascript"></script>
<script type="text/javascript">
<!--

//-->
</script>
</head>
<body>

<?php

if (file_exists("./cfg/config.ini")) {
	$ini = parse_ini_file("./cfg/config.ini");
	extract($ini);
}
if ( isset($_POST['save'])) {
        extract($_POST);
	if (isset($passclear)) { 
		$ini['loginpass'] = ""; 
		$_SESSION['login']='NG'; 
	}
	if (isset($sencnt))    {$ini['sencnt']=$sencnt;       }
	if (isset($dbsrv))     {$ini['dbsrv'] =$dbsrv;        }
	if (isset($dbname))    {$ini['dbname']=$dbname;       }
	if (isset($dbuser))    {$ini['dbuser']=$dbuser;       } 
	if (isset($dbpass))    {$ini['dbpass']=$dbpass;       }
	if (isset($serial))    {$ini['serial']=$serial;       }
//	if (isset($kirinID))   {$ini['kirinID']=$kirinID;     }
//	if (isset($kirinHost)) {$ini['kirinHost']=$kirinHost; }
	$fp = fopen('./cfg/config.ini', 'w');
	foreach ($ini as $k => $i) fputs($fp, "$k=$i\n");
	fclose($fp);
	echo '保存しました。';
} else {

if (! isset($sencnt)) {$sencnt="0";          }
if (! isset($dbsrv))  {$dbsrv="localhost";   }
if (! isset($dbname)) {$dbname="gettemp";    }
if (! isset($dbuser)) {$dbuser="gettemp";    } 
if (! isset($dbpass)) {$dbpass="gettemp";    }
if (! isset($serial)) {$serial="/dev/ttyS0"; }
//if (! isset($kirinID)) {$kirinID="dummy";  }
//if (! isset($kirinHost)) {$kirinHost="dummy"; }

echo '<form action="./config.php" method="post">';
echo '<p>';
echo 'センサー(DS18B20)の数:<br><input type="text" name="sencnt" size="2" istyle="4" value="'.$sencnt.'">';
echo '</p>';
echo '<p>';
echo 'データベースサーバー:<br><input type="text" name="dbsrv" size="40" istyle="3" value="'.$dbsrv.'">';
echo '</p>';
echo '<p>';
echo 'データベース名:<br><input type="text" name="dbname" size="40" istyle="3" value="'.$dbname.'">';
echo '</p>';
echo '<p>';
echo 'username:<br><input type="text" name="dbuser" size="40" istyle="3" value="'.$dbuser.'">';
echo '</p>';
echo '<p>';
echo 'password:<br><input type="password" name="dbpass" size="40" istyle="3" value="'.$dbpass.'">';
echo '</p>';
echo '<p>';
echo '<input type="checkbox" name="passclear" value="passclear" > ログインパスワードをクリアする';
echo '</p>';
echo '<p>';
echo 'シリアルポート:<br><input type="text" name="serial" size="40" istyle="3" value="'.$serial.'">';
echo '</p>';
/*
echo '<p>';
echo 'kirinID:<br><input type="text" name="kirinID" size="60" istyle="3" value="'.$kirinID.'">';
echo '</p>';
echo '<p>';
echo 'kirinHost:<br><input type="text" name="kirinHost" size="60" istyle="3" value="'.$kirinHost.'">';
echo '</p>';
*/
echo '<p><input type="submit" name="save" value="保存" style="width:100px;height:40px"></p>';
echo '</form>';
echo '<br><input type="button" NAME="btnupsencnt" VALUE="Arduinoセンサー数の更新" ONCLICK="upsencnt();" style="width:180px;height:40px"><br>';
echo 'DS18B20を付けたり外したりした時に押します';
}
?>
<br><a href="./index.php">TOPへ</a>
</body>
</html>
