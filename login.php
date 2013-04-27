<?php
session_start();

require ('lib/mdl.php');
if (! isset($_SESSION['login'])) {$_SESSION['login']='NG';}
$ini = parse_ini_file("./cfg/config.ini");


//ログインパスワードが設定されていない
if ((! isset($ini['loginpass'])) || (strlen($ini['loginpass']) ==0)) {

	if (isset($_POST['setpass'])) {
		$ini['loginpass']=sha1($_POST['loginpass']);
		$fp = fopen('./cfg/config.ini', 'w');
		foreach ($ini as $k => $i) fputs($fp, "$k=$i\n");
		fclose($fp);
       		header("Location: http://".$_SERVER['HTTP_HOST'].'/motion/');
	 	exit();  //これ以下を実行しない
	} else {

		echo '<html lang="ja"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">';
		echo '<link rel="stylesheet" href="css/reset.css">';
		echo '<link rel="stylesheet" href="css/base.css">';
		echo '<title>Webカメラ_ログイン</title></head>';
		echo '<body onLoad="document.form1.loginword.focus()">';
		echo '<form action ="./login.php" method="post" name="form1" >';
		echo '<h3>パスワードの設定<br></h3>';
		echo '<h4><input type="password" name="loginpass" value="" size="10" istyle="4" style="width:110px;height:40px" ></h4><br>';
		echo '<input type="submit" name="setpass" value="保存"  style="width:110px;height:40px" >';
		echo '</form>';
		echo '</body>';
		exit();	
	}

} else {


		if (isset($_GET['lurl'])){ 
			$lurl=$_GET['lurl'];
		} else {
			$lurl="index.php";
		}

		if (isset($_POST['login'])) {
 		   $id = get_mobile_id();   //携帯固有シリアルを取得
			if (! isset($_POST['loginword'])) { $_POST['loginword'] = ""; }
			if (strcmp(sha1($_POST['loginword']),$ini['loginpass']) ==0) { $_SESSION['login'] ='OK'; } //パスワードがあっていたらログインOK
			if (strcmp($_POST['loginword'],"guest") ==0) { $_SESSION['login'] ='guest';}
	
			if ((strcmp($_SESSION['login'],"OK") ==0) || (strcmp($_SESSION['login'],"guest") ==0))  {
       		 		header("Location: http://".$_SERVER['HTTP_HOST'].'/motion/'.$lurl);
	 			exit();  //これ以下を実行しない
      	 	 	 }
 		}

 	if ($_SESSION['login'] === 'NG' ) {

 		echo '<html lang="ja"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';
 		echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">';
 		echo '<link rel="stylesheet" href="css/reset.css">';
 		echo '<link rel="stylesheet" href="css/base.css">';
 		echo '<title>Webカメラ_ログイン</title></head>';
 		echo '<body onLoad="document.form1.loginword.focus()">';
 		echo '<form action ="./login.php?lurl='.$lurl.'" method="post" name="form1" >';
 		echo '<h3>パスワード<br></h3>';
 		echo '<h4><input type="password" name="loginword" value="" size="10" istyle="4" style="width:110px;height:40px" ></h4><br>';
 		echo '<input type="submit" name="login" value="login"  style="width:110px;height:40px" >';
 		echo '</form>';
 		echo '</body>';
 		exit();	
 	}
}
?>
