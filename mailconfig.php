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
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/base.css">
<title>メール設定</title>
<!-- Script -->
<script src="js/mdl.js" type="text/javascript"></script>
<script type="text/javascript">
<!--

//-->
</script>
</head>

<body>
<?php
if ( isset($_POST['save'])) {
        extract($_POST);
	if (isset($mad)) {$ini['mad']=$mad; }
	if (isset($sendintval)) {$ini['sendintval']=$sendintval; }
	if (isset($senceno)){$ini['senceno']=$senceno; }
	if(isset($l00)) { $ini['l00']=$l00; }
	if(isset($l01)) { $ini['l01']=$l01; }
	if(isset($l02)) { $ini['l02']=$l02; }
	if(isset($l03)) { $ini['l03']=$l03; }
	if(isset($l04)) { $ini['l04']=$l04; }
	if(isset($l05)) { $ini['l05']=$l05; }
	if(isset($l06)) { $ini['l06']=$l06; }
	if(isset($l07)) { $ini['l07']=$l07; }
	if(isset($l08)) { $ini['l08']=$l08; }
	if(isset($l09)) { $ini['l09']=$l09; }
	if(isset($l10)) { $ini['l10']=$l10; }
	if(isset($l11)) { $ini['l11']=$l11; }
	if(isset($l12)) { $ini['l12']=$l12; }
	if(isset($l13)) { $ini['l13']=$l13; }
	if(isset($l14)) { $ini['l14']=$l14; }
	if(isset($l15)) { $ini['l15']=$l15; }
	if(isset($l16)) { $ini['l16']=$l16; }
	if(isset($l17)) { $ini['l17']=$l17; }
	if(isset($l18)) { $ini['l18']=$l18; }
	if(isset($l19)) { $ini['l19']=$l19; }
	if(isset($l20)) { $ini['l20']=$l20; }
	if(isset($l21)) { $ini['l21']=$l21; }
	if(isset($l22)) { $ini['l22']=$l22; }
	if(isset($l23)) { $ini['l23']=$l23; }
	
	if(isset($h00)) { $ini['h00']=$h00; }
	if(isset($h01)) { $ini['h01']=$h01; }
	if(isset($h02)) { $ini['h02']=$h02; }
	if(isset($h03)) { $ini['h03']=$h03; }
	if(isset($h04)) { $ini['h04']=$h04; }
	if(isset($h05)) { $ini['h05']=$h05; }
	if(isset($h06)) { $ini['h06']=$h06; }
	if(isset($h07)) { $ini['h07']=$h07; }
	if(isset($h08)) { $ini['h08']=$h08; }
	if(isset($h09)) { $ini['h09']=$h09; }
	if(isset($h10)) { $ini['h10']=$h10; }
	if(isset($h11)) { $ini['h11']=$h11; }
	if(isset($h12)) { $ini['h12']=$h12; }
	if(isset($h13)) { $ini['h13']=$h13; }
	if(isset($h14)) { $ini['h14']=$h14; }
	if(isset($h15)) { $ini['h15']=$h15; }
	if(isset($h16)) { $ini['h16']=$h16; }
	if(isset($h17)) { $ini['h17']=$h17; }
	if(isset($h18)) { $ini['h18']=$h18; }
	if(isset($h19)) { $ini['h19']=$h19; }
	if(isset($h20)) { $ini['h20']=$h20; }
	if(isset($h21)) { $ini['h21']=$h21; }
	if(isset($h22)) { $ini['h22']=$h22; }
	if(isset($h23)) { $ini['h23']=$h23; }

	if (isset($lc00)) {if ($lc00==="on") {$ini['lc00']=1;}else {$ini['lc00']=0;}} else { $ini['lc00']=0;}
	if (isset($lc01)) {if ($lc01==="on") {$ini['lc01']=1;}else {$ini['lc01']=0;}} else { $ini['lc01']=0;}
	if (isset($lc02)) {if ($lc02==="on") {$ini['lc02']=1;}else {$ini['lc02']=0;}} else { $ini['lc02']=0;}
	if (isset($lc03)) {if ($lc03==="on") {$ini['lc03']=1;}else {$ini['lc03']=0;}} else { $ini['lc03']=0;}
	if (isset($lc04)) {if ($lc04==="on") {$ini['lc04']=1;}else {$ini['lc04']=0;}} else { $ini['lc04']=0;}
	if (isset($lc05)) {if ($lc05==="on") {$ini['lc05']=1;}else {$ini['lc05']=0;}} else { $ini['lc05']=0;}
	if (isset($lc06)) {if ($lc06==="on") {$ini['lc06']=1;}else {$ini['lc06']=0;}} else { $ini['lc06']=0;}
	if (isset($lc07)) {if ($lc07==="on") {$ini['lc07']=1;}else {$ini['lc07']=0;}} else { $ini['lc07']=0;}
	if (isset($lc08)) {if ($lc08==="on") {$ini['lc08']=1;}else {$ini['lc08']=0;}} else { $ini['lc08']=0;}
	if (isset($lc09)) {if ($lc09==="on") {$ini['lc09']=1;}else {$ini['lc09']=0;}} else { $ini['lc09']=0;}
	if (isset($lc10)) {if ($lc10==="on") {$ini['lc10']=1;}else {$ini['lc10']=0;}} else { $ini['lc10']=0;}
	if (isset($lc11)) {if ($lc11==="on") {$ini['lc11']=1;}else {$ini['lc11']=0;}} else { $ini['lc11']=0;}
	if (isset($lc12)) {if ($lc12==="on") {$ini['lc12']=1;}else {$ini['lc12']=0;}} else { $ini['lc12']=0;}
	if (isset($lc13)) {if ($lc13==="on") {$ini['lc13']=1;}else {$ini['lc13']=0;}} else { $ini['lc13']=0;}
	if (isset($lc14)) {if ($lc14==="on") {$ini['lc14']=1;}else {$ini['lc14']=0;}} else { $ini['lc14']=0;}
	if (isset($lc15)) {if ($lc15==="on") {$ini['lc15']=1;}else {$ini['lc15']=0;}} else { $ini['lc15']=0;}
	if (isset($lc16)) {if ($lc16==="on") {$ini['lc16']=1;}else {$ini['lc16']=0;}} else { $ini['lc16']=0;}
	if (isset($lc17)) {if ($lc17==="on") {$ini['lc17']=1;}else {$ini['lc17']=0;}} else { $ini['lc17']=0;}
	if (isset($lc18)) {if ($lc18==="on") {$ini['lc18']=1;}else {$ini['lc18']=0;}} else { $ini['lc18']=0;}
	if (isset($lc19)) {if ($lc19==="on") {$ini['lc19']=1;}else {$ini['lc19']=0;}} else { $ini['lc19']=0;}
	if (isset($lc20)) {if ($lc20==="on") {$ini['lc20']=1;}else {$ini['lc20']=0;}} else { $ini['lc20']=0;}
	if (isset($lc21)) {if ($lc21==="on") {$ini['lc21']=1;}else {$ini['lc21']=0;}} else { $ini['lc21']=0;}
	if (isset($lc22)) {if ($lc22==="on") {$ini['lc22']=1;}else {$ini['lc22']=0;}} else { $ini['lc22']=0;}
	if (isset($lc23)) {if ($lc23==="on") {$ini['lc23']=1;}else {$ini['lc23']=0;}} else { $ini['lc23']=0;}

	if (isset($hc00)) {if ($hc00==="on") {$ini['hc00']=1;}else {$ini['hc00']=0;}} else { $ini['hc00']=0;}
	if (isset($hc01)) {if ($hc01==="on") {$ini['hc01']=1;}else {$ini['hc01']=0;}} else { $ini['hc01']=0;}
	if (isset($hc02)) {if ($hc02==="on") {$ini['hc02']=1;}else {$ini['hc02']=0;}} else { $ini['hc02']=0;}
	if (isset($hc03)) {if ($hc03==="on") {$ini['hc03']=1;}else {$ini['hc03']=0;}} else { $ini['hc03']=0;}
	if (isset($hc04)) {if ($hc04==="on") {$ini['hc04']=1;}else {$ini['hc04']=0;}} else { $ini['hc04']=0;}
	if (isset($hc05)) {if ($hc05==="on") {$ini['hc05']=1;}else {$ini['hc05']=0;}} else { $ini['hc05']=0;}
	if (isset($hc06)) {if ($hc06==="on") {$ini['hc06']=1;}else {$ini['hc06']=0;}} else { $ini['hc06']=0;}
	if (isset($hc07)) {if ($hc07==="on") {$ini['hc07']=1;}else {$ini['hc07']=0;}} else { $ini['hc07']=0;}
	if (isset($hc08)) {if ($hc08==="on") {$ini['hc08']=1;}else {$ini['hc08']=0;}} else { $ini['hc08']=0;}
	if (isset($hc09)) {if ($hc09==="on") {$ini['hc09']=1;}else {$ini['hc09']=0;}} else { $ini['hc09']=0;}
	if (isset($hc10)) {if ($hc10==="on") {$ini['hc10']=1;}else {$ini['hc10']=0;}} else { $ini['hc10']=0;}
	if (isset($hc11)) {if ($hc11==="on") {$ini['hc11']=1;}else {$ini['hc11']=0;}} else { $ini['hc11']=0;}
	if (isset($hc12)) {if ($hc12==="on") {$ini['hc12']=1;}else {$ini['hc12']=0;}} else { $ini['hc12']=0;}
	if (isset($hc13)) {if ($hc13==="on") {$ini['hc13']=1;}else {$ini['hc13']=0;}} else { $ini['hc13']=0;}
	if (isset($hc14)) {if ($hc14==="on") {$ini['hc14']=1;}else {$ini['hc14']=0;}} else { $ini['hc14']=0;}
	if (isset($hc15)) {if ($hc15==="on") {$ini['hc15']=1;}else {$ini['hc15']=0;}} else { $ini['hc15']=0;}
	if (isset($hc16)) {if ($hc16==="on") {$ini['hc16']=1;}else {$ini['hc16']=0;}} else { $ini['hc16']=0;}
	if (isset($hc17)) {if ($hc17==="on") {$ini['hc17']=1;}else {$ini['hc17']=0;}} else { $ini['hc17']=0;}
	if (isset($hc18)) {if ($hc18==="on") {$ini['hc18']=1;}else {$ini['hc18']=0;}} else { $ini['hc18']=0;}
	if (isset($hc19)) {if ($hc19==="on") {$ini['hc19']=1;}else {$ini['hc19']=0;}} else { $ini['hc19']=0;}
	if (isset($hc20)) {if ($hc20==="on") {$ini['hc20']=1;}else {$ini['hc20']=0;}} else { $ini['hc20']=0;}
	if (isset($hc21)) {if ($hc21==="on") {$ini['hc21']=1;}else {$ini['hc21']=0;}} else { $ini['hc21']=0;}
	if (isset($hc22)) {if ($hc22==="on") {$ini['hc22']=1;}else {$ini['hc22']=0;}} else { $ini['hc22']=0;}
	if (isset($hc23)) {if ($hc23==="on") {$ini['hc23']=1;}else {$ini['hc23']=0;}} else { $ini['hc23']=0;}

	$fp = fopen('./cfg/mailconfig.ini', 'w');
	foreach ($ini as $k => $i) fputs($fp, "$k=$i\n");
	fclose($fp);
	echo '保存しました。';
} else {
	if (file_exists("./cfg/mailconfig.ini")) {
		$ini = parse_ini_file("./cfg/mailconfig.ini");
		extract($ini);
	}
	if (! isset($mad)) {$mad="root@localhost"; }
	if (! isset($sendintval)) {$sendintval=60; }
	if (! isset($senceno)) { $senceno = 1;}
	if(! isset($l00)) { $l00=-99; }
	if(! isset($l01)) { $l01=-99; }
	if(! isset($l02)) { $l02=-99; }
	if(! isset($l03)) { $l03=-99; }
	if(! isset($l04)) { $l04=-99; }
	if(! isset($l05)) { $l05=-99; }
	if(! isset($l06)) { $l06=-99; }
	if(! isset($l07)) { $l07=-99; }
	if(! isset($l08)) { $l08=-99; }
	if(! isset($l09)) { $l09=-99; }
	if(! isset($l10)) { $l10=-99; }
	if(! isset($l11)) { $l11=-99; }
	if(! isset($l12)) { $l12=-99; }
	if(! isset($l13)) { $l13=-99; }
	if(! isset($l14)) { $l14=-99; }
	if(! isset($l15)) { $l15=-99; }
	if(! isset($l16)) { $l16=-99; }
	if(! isset($l17)) { $l17=-99; }
	if(! isset($l18)) { $l18=-99; }
	if(! isset($l19)) { $l19=-99; }
	if(! isset($l20)) { $l20=-99; }
	if(! isset($l21)) { $l21=-99; }
	if(! isset($l22)) { $l22=-99; }
	if(! isset($l23)) { $l23=-99; }

	$l=array ( $l00,$l01,$l02,$l03,$l04,$l05,$l06,$l07,$l08,$l09,$l10,$l11,$l12,$l13,$l14,$l15,$l16,$l17,$l18,$l19,$l20,$l21,$l22,$l23 );
	
	if(! isset($h00)) { $h00=99; }
	if(! isset($h01)) { $h01=99; }
	if(! isset($h02)) { $h02=99; }
	if(! isset($h03)) { $h03=99; }
	if(! isset($h04)) { $h04=99; }
	if(! isset($h05)) { $h05=99; }
	if(! isset($h06)) { $h06=99; }
	if(! isset($h07)) { $h07=99; }
	if(! isset($h08)) { $h08=99; }
	if(! isset($h09)) { $h09=99; }
	if(! isset($h10)) { $h10=99; }
	if(! isset($h11)) { $h11=99; }
	if(! isset($h12)) { $h12=99; }
	if(! isset($h13)) { $h13=99; }
	if(! isset($h14)) { $h14=99; }
	if(! isset($h15)) { $h15=99; }
	if(! isset($h16)) { $h16=99; }
	if(! isset($h17)) { $h17=99; }
	if(! isset($h18)) { $h18=99; }
	if(! isset($h19)) { $h19=99; }
	if(! isset($h20)) { $h20=99; }
	if(! isset($h21)) { $h21=99; }
	if(! isset($h22)) { $h22=99; }
	if(! isset($h23)) { $h23=99; }
    
	$h=array ( $h00,$h01,$h02,$h03,$h04,$h05,$h06,$h07,$h08,$h09,$h10,$h11,$h12,$h13,$h14,$h15,$h16,$h17,$h18,$h19,$h20,$h21,$h22,$h23 );
	if(! isset($hc00)) { $hc00=0; }
	if(! isset($hc01)) { $hc01=0; }
	if(! isset($hc02)) { $hc02=0; }
	if(! isset($hc03)) { $hc03=0; }
	if(! isset($hc04)) { $hc04=0; }
	if(! isset($hc05)) { $hc05=0; }
	if(! isset($hc06)) { $hc06=0; }
	if(! isset($hc07)) { $hc07=0; }
	if(! isset($hc08)) { $hc08=0; }
	if(! isset($hc09)) { $hc09=0; }
	if(! isset($hc10)) { $hc10=0; }
	if(! isset($hc11)) { $hc11=0; }
	if(! isset($hc12)) { $hc12=0; }
	if(! isset($hc13)) { $hc13=0; }
	if(! isset($hc14)) { $hc14=0; }
	if(! isset($hc15)) { $hc15=0; }
	if(! isset($hc16)) { $hc16=0; }
	if(! isset($hc17)) { $hc17=0; }
	if(! isset($hc18)) { $hc18=0; }
	if(! isset($hc19)) { $hc19=0; }
	if(! isset($hc20)) { $hc20=0; }
	if(! isset($hc21)) { $hc21=0; }
	if(! isset($hc22)) { $hc22=0; }
	if(! isset($hc23)) { $hc23=0; }

	$hc=array ( $hc00,$hc01,$hc02,$hc03,$hc04,$hc05,$hc06,$hc07,$hc08,$hc09,$hc10,$hc11,$hc12,$hc13,$hc14,$hc15,$hc16,$hc17,$hc18,$hc19,$hc20,$hc21,$hc22,$hc23 );
	
	if(! isset($lc00)) { $lc00=0; }
	if(! isset($lc01)) { $lc01=0; }
	if(! isset($lc02)) { $lc02=0; }
	if(! isset($lc03)) { $lc03=0; }
	if(! isset($lc04)) { $lc04=0; }
	if(! isset($lc05)) { $lc05=0; }
	if(! isset($lc06)) { $lc06=0; }
	if(! isset($lc07)) { $lc07=0; }
	if(! isset($lc08)) { $lc08=0; }
	if(! isset($lc09)) { $lc09=0; }
	if(! isset($lc10)) { $lc10=0; }
	if(! isset($lc11)) { $lc11=0; }
	if(! isset($lc12)) { $lc12=0; }
	if(! isset($lc13)) { $lc13=0; }
	if(! isset($lc14)) { $lc14=0; }
	if(! isset($lc15)) { $lc15=0; }
	if(! isset($lc16)) { $lc16=0; }
	if(! isset($lc17)) { $lc17=0; }
	if(! isset($lc18)) { $lc18=0; }
	if(! isset($lc19)) { $lc19=0; }
	if(! isset($lc20)) { $lc20=0; }
	if(! isset($lc21)) { $lc21=0; }
	if(! isset($lc22)) { $lc22=0; }
	if(! isset($lc23)) { $lc23=0; }

	$lc=array ( $lc00,$lc01,$lc02,$lc03,$lc04,$lc05,$lc06,$lc07,$lc08,$lc09,$lc10,$lc11,$lc12,$lc13,$lc14,$lc15,$lc16,$lc17,$lc18,$lc19,$lc20,$lc21,$lc22,$lc23 );
	
	echo '<form action="./mailconfig.php" method="post">';
	echo '<p>';
	echo '送信先メールアドレス:<br><input type="text" name="mad" size="30" istyle="3" value="'.$mad.'">';
	echo '</p>';
	echo '<p>';
	echo '送信後の待機時間(分):<br><input type="text" name="sendintval" size="4" istyle="4" value="'.$sendintval.'">';
	echo '</p>';
	echo '<p>';
	echo '監視するセンサー番号:<br><input type="text" name="senceno" size="2" istyle="2" value="'.$senceno.'">';
	echo '</p>';
	echo '<table>';
	echo '<tr>';
	echo '<td>時間帯</td>';
	echo '<td>低温度</td>';
	echo '<td>送信する</td>';
	echo '<td>高温度</td>';
	echo '<td>送信する</td>';
	echo '</tr>';
	for ($i=0;$i<24;$i++) {
		echo '<tr>';
		echo '<td>'.substr('00'.$i,-2).':00~</td>';
		echo '<td><input type="text" name="l'.substr('00'.$i,-2).'" size="4" istyle="4" value="'.$l[$i].'"></td>';
		if ($lc[$i] == 0 ) { $chk=""; } else { $chk="checked"; }
		echo '<td><input type="checkbox" name="lc'.substr('00'.$i,-2).'" value="on" '.$chk.'></td>';
		echo '<td><input type="text" name="h'.substr('00'.$i,-2).'" size="4" istyle="4" value="'.$h[$i].'"></td>';
		if ($hc[$i] == 0 ) { $chk=""; } else { $chk="checked"; }
		echo '<td><input type="checkbox" name="hc'.substr('00'.$i,-2).'" value="on" '.$chk.'></td>';
		echo '</tr>';
	}
	echo '</table>';
	echo '<p><input type="submit" name="save" value="保存"></p>';
	echo '</form>';

}
?>
<br><a href="./index.php">TOPへ</a>
</body>
</html>
