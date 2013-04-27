#!/usr/bin/php -q
<?php
//  arduinoより湿度、温度を取得。
//  cronにより定期実行されることを想定。
//  オプションにより、mysqlのテーブルに書き込みを行う。（最低・最高・定時の温度。）
//  
//  シリアルポートに 'g' を送ることで、arduinoは以下の書式でデータを出力する。
//H:27.50%\tT0:22.40\tT1:-8.00\tT2:22.40\n
//
//
// CREATE TABLE AVGTEMP(ID int NOT NULL AUTO_INCREMENT,UPTIM int NOT NULL default 0,gettemp0 decimal(5,2) NOT NULL default 0.0
//                      gettemp1 decimal(5,2) NOT NULL default 0.0,gettemp2 decimal(5,2) NOT NULL default 0.0,gettemp3 decimal(5,2) NOT NULL default 0.0
//                      PRIMARY KEY(ID));

$version = "0.9.1"; // 2012/12/24

require (dirname(__FILE__).'/../lib/mydbcls.php');
require (dirname(__FILE__).'/../lib/mdl.php');

//メール送信ファンクション
function send_alert($sbj,$alt,$lmad){
	writeLog("メール送信 ".$lmad." ".$sbj." ".$alt);
	mb_language("Japanese");
	mb_internal_encoding("UTF-8");

	if (mb_send_mail($lmad,$sbj, $alt , "From: root@localhost")) { }

	//送信時間を更新
	$fp = fopen(dirname(__FILE__).'/lastsend',"w");
		fputs($fp,time());
	fclose($fp);
	return true;
}
//ログ書き込み
function writeLog($logmsg) {
	$logmsg =getint2date(time())." ".getint2time(time())." ".$logmsg; 
	$logmsg = str_replace("\n","",$logmsg);
	$fp = fopen(dirname(__FILE__).'/alert.log',"a");
		fputs($fp,$logmsg."\n");
	fclose($fp);
}

function getstr2tmp($ttystr,$t_kind,&$defval){
	$ret = false;
	$retval = 0.0;
	switch($t_kind) {
		case "H:":
			$stpos=strpos($ttystr,"H:");
			$enpos = strpos($ttystr,"\t",$stpos);
			$retval = substr($ttystr,$stpos,$enpos-$stpos);  //H:26.20%となる。
			$retval = mb_ereg_replace('[^0-9\.-]', '', $retval); //0~9と.-以外を削除
			if(is_numeric($retval)) {
				$ret =true;
				$defval = floatval($retval);				
			}
			break;
		case "T2:":
			$stpos=strpos($ttystr,"T2:");
			$enpos = $stpos+10;
			$retval = substr($ttystr,$stpos+2,$enpos-$stpos);  //:-7.0となる。
			$retval = mb_ereg_replace('[^0-9\.-]', '', $retval); //0~9と.-以外を削除
			if(is_numeric($retval)) {
				$ret =true;
				$defval = floatval($retval);				
			}
			break;
		case "T1:":
			$stpos=strpos($ttystr,"T1:");
			$enpos = strpos($ttystr,"\t",$stpos);
			if( $enpos ===false ) { 
				$enpos=$stpos+10;
			}
			$retval = substr($ttystr,$stpos+2,$enpos-$stpos);  //:-7.0となる。
			$retval = mb_ereg_replace('[^0-9\.-]', '', $retval); //0~9と.-以外を削除
			if(is_numeric($retval)) {
				$ret =true;
				$defval = floatval($retval);				
			}
			break;


		default:
			$stpos=strpos($ttystr,$t_kind);
			$enpos = strpos($ttystr,"\t",$stpos);
			$retval = substr($ttystr,$stpos+2,$enpos-$stpos);  //:-7.0となる。
			$retval = mb_ereg_replace('[^0-9\.-]', '', $retval); //0~9と.-以外を削除
			if(is_numeric($retval)) {
				$ret =true;
				$defval = floatval($retval);				
			}
			break;
	}			
	return $ret;
}


$ini = parse_ini_file(dirname(__FILE__)."/../cfg/config.ini");
//db情報を使用
extract($ini);

$retstr  = "";
$lastline= "";

$hd = 100.0; //湿度
$t0 = 99.0;  //センサー0 
$t1 = 99.0;  //センサー1
$t2 = 99.0;  //センサー2

$cnt = 0;  //ループカウント
$chk_h = false; //値が取得できたか？
$chk_t0 = false; //

switch ($ini['sencnt']) {
	case 0:
		$chk_t1 = true; 
		$chk_t2 = true; 
		break;
	case 1:
		$chk_t1 = false; 
		$chk_t2 = true; 
		break;
	default:
		$chk_t1 = false; 
		$chk_t2 = false; 
}

$upmysqlchk = false;
$timestampprintchk = false;
$sendalertmailchk  = true;
$outputchk = false;
$outputflname = "";


$opt = getopt("hvmtso:");
foreach($opt as $key => $value) {
	switch($key){
		case "h":
			echo "usage:\n";
			echo " -h print help.\n";
			echo " -v print verseion.\n";
			echo " -m update mysql data.\n";
			echo " -t print now timestamp( timestamp hd t0 t1 t2).\n";
			echo " -s send alert mail.\n";
			echo " -o value output file.\n";
			exit();
			break;
		case "v":
			echo $version;
			exit();
			break;
		case "m":
			$upmysqlchk = true;
			break;
		case "t":
			$timestampprintchk = true;
			break;
		case "s":
			$sendalertmailchk = true;
			break;
		case "o":
			$outputchk=true;
			$outputflname=$value;
			break;							
	}
	
	
}


// arduino-serial を使ってコマンド'g'を送信。（しばしttyS0に温度が書き込まれる）

while(1){
	exec (dirname(__FILE__).'/arduino-serial -b 9600 -p '.$ini['serial'].' -s g ',$retstr);
	exec ('head -n3 '.$ini['serial'],$retstr);

	if (count($retstr) > 0) {
		if(! $chk_h) {
			for($i=0;$i<count($retstr);$i++){
				$chk_h = getstr2tmp($retstr[$i],"H:",$hd);
				if($chk_h){ break;}
			}
		}
		
		if(! $chk_t0){
			for($i=0;$i<count($retstr);$i++){
				$chk_t0 = getstr2tmp($retstr[$i],"T0:",$t0);
				if($chk_t0){ break;}
			}			
		}	
		if(! $chk_t1){
			for($i=0;$i<count($retstr);$i++){
				$chk_t1 = getstr2tmp($retstr[$i],"T1:",$t1);
				if($chk_t1){ break;}
			}			
		}
		if(! $chk_t2){
			for($i=0;$i<count($retstr);$i++){
				$chk_t2 = getstr2tmp($retstr[$i],"T2:",$t2);
				if($chk_t2){ break;}
			}			
		}
		
	}
	
	if ($chk_h && $chk_t0 && $chk_t1 && $chk_t2) { break; }
	$cnt++;
	if ($cnt > 5 ) {
		break;
	}
}
/////  温度取得 終了
if (!($chk_h && $chk_t0 && $chk_t1 && $chk_t2 )) {
	//取得できなければ 1を返して終了 
	exit(1);
}

$tm="";  //タイムスタンプ
if($timestampprintchk) {
	$tm=time()."\t";
}
if (! $outputchk) {
	echo $tm.$hd."\t".$t0."\t".$t1."\t".$t2."\n";
} else {
	file_put_contents($outputflname,$tm.$hd."\t".$t0."\t".$t1."\t".$t2."\n");
}

//温度を配列に格納
$tp = array($t0,$t1,$t2);

if ( $upmysqlchk) {
	//mysqlに書き込み。
	$db = new mydbcls($ini['dbsrv'],$ini['dbname'],$ini['dbuser'],$ini['dbpass']);
	
	$nowtimes = getdate2int();  //現在時刻タイムスタンプを取得
	$dayinit = getdate2int(date("Y/m/d"). " 00:00:00" );   //今日１日の最初のタイムスタンプを取得
	$daysttime = getdate2int(date("Y/m/d H"). ":00:00" );   //現在の時間帯の最初
	$dayentime = getdate2int(date("Y/m/d H"). ":59:59" );   //             最後
	
	//現在の時間帯で既に記録があるか？ <-- old
	/*
	$p = array("@st","@en");
	$v = array($daysttime,$dayentime);
	$cnt = $db->getRecordCount("SELECT COUNT(*) as cnt FROM MSI WHERE UPTIM between @st and @en",$p,$v);
	echo $cnt."\n".$daysttime."\t".$dayentime."\n";
	echo getint2datelong($daysttime)."\t".getint2datelong($dayentime)."\n";
	
	if($cnt ==0 ){ //無ければ記録。
	*/
	//あろうがなかろうが、現在の時間でデータベースに記録。(cronで5分ごとに記録される)
		$sqlstr = "INSERT INTO MSI (UPTIM,gettemp0,gettemp1,gettemp2,gettemp3) VALUES (@UPTIM,@T0,@T1,@T2,@HD)";
		$p = array("@UPTIM","@T0","@T1","@T2","@HD");
		$v = array($nowtimes,$tp[0],$tp[1],$tp[2],$hd);
		$cnt = $db->executeSql($sqlstr,$p,$v);
	//}
	
	//センサーナンバー毎に最低・最高温度の更新。
	$j=0;
	for($j=0;$j<3;$j++){
		//最高気温
		$p = array("@day","@sno");
		$v = array($dayinit,$j);
		$cnt =$db->getRecordCount("SELECT COUNT(*) as cnt FROM HIGHLOW WHERE TIM=@day and SensorNo=@sno and kbn=0",$p,$v); //区分0で最高気温
		if($cnt == 0) { 
			$p = array("@TIM","@UPTIM","@sno","@tp");
			$v = array($dayinit,$nowtimes,$j,$tp[$j]);
			$cnt= $db->executeSql("INSERT INTO HIGHLOW(TIM,UPTIM,SensorNo,gettemp,kbn) VALUES (@TIM,@UPTIM,@sno,@tp,0)",$p,$v);
		} else {
			//今日の保存されている値がいまよりも低いので更新される。
			$p = array("@TIM","@UPTIM","@sno","@tp","@uptp");
			$v = array($dayinit,$nowtimes,$j,$tp[$j],$tp[$j]);
			$cnt = $db->executeSql("UPDATE HIGHLOW SET gettemp=@uptp,UPTIM=@UPTIM WHERE TIM=@TIM and SensorNo=@sno and kbn=0 and gettemp < @tp",$p,$v );
		}
		
		//最低気温
		$p = array("@day","@sno");
		$v = array($dayinit,$j);
		$cnt =$db->getRecordCount("SELECT COUNT(*) as cnt FROM HIGHLOW WHERE TIM=@day and SensorNo=@sno and kbn=1",$p,$v); //区分0で最高気温
		if($cnt == 0) { 
			$p = array("@TIM","@UPTIM","@sno","@tp");
			$v = array($dayinit,$nowtimes,$j,$tp[$j]);
			$cnt= $db->executeSql("INSERT INTO HIGHLOW(TIM,UPTIM,SensorNo,gettemp,kbn) VALUES (@TIM,@UPTIM,@sno,@tp,1)",$p,$v);
		} else {
			//今日の保存されている値がいまよりも低いので更新される。
			$p = array("@TIM","@UPTIM","@sno","@tp","@uptp");
			$v = array($dayinit,$nowtimes,$j,$tp[$j],$tp[$j]);
			$cnt = $db->executeSql("UPDATE HIGHLOW SET gettemp=@uptp,UPTIM=@UPTIM WHERE TIM=@TIM and SensorNo=@sno and kbn=1 and gettemp > @tp",$p,$v );
		}
		
	}
	
	//前時間までの平均を更新
	$daybeftime = $daysttime - 3600;  //１時間前を取得
	$daybefentime = $daybeftime + 3599; //範囲の終わり。
	
	$cnt = $db->getRecordcount("SELECT count(*)as cnt FROM AVGTEMP WHERE UPTIM=".$daybeftime);
if ($cnt ==0) {
	$p = array("@UPTIM","@st","@en");
	$v = array($daybeftime,$daybeftime,$daybefentime);
	$cnt=$db->getRecordcount("SELECT count(*) as cnt FROM MSI WHERE UPTIM between @st and @en",$p,$v);
	unset($tb);		
	switch($cnt){
		case 0:		
		  	echo "avg update0(0)  ".getint2datelong($daybeftime)."\n";
			break;
		case 1:
			$ret=$db->executeSql("INSERT INTO AVGTEMP(UPTIM,gettemp0,gettemp1,gettemp2,gettemp3)" .
					"(SELECT @UPTIM ,gettemp0,gettemp1,gettemp2,gettemp3 FROM MSI WHERE UPTIM between @st and @en)",$p,$v);
			echo "avg update1(".$ret.")  ".getint2datelong($daybeftime)."\n";
			break;
		case 2:
			$ret=$db->executeSql("INSERT INTO AVGTEMP(UPTIM,gettemp0,gettemp1,gettemp2,gettemp3)" .
					"(SELECT @UPTIM ,avg(gettemp0),avg(gettemp1),avg(gettemp2),avg(gettemp3) FROM MSI WHERE UPTIM between @st and @en)",$p,$v);
			echo "avg update2(".$ret.")  ".getint2datelong($daybeftime)."\n";
			break;
		case 3: case 4: case 5:  case 6:  case 7:
			//最大の時のIDを取得
			//最小の時のIDを取得
			//それ以外で平均を取得
			$mxid=$db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT max(gettemp0+gettemp1+gettemp2) FROM MSI) and UPTIM between @st and @en",$p,$v);
			$mnid=$db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT min(gettemp0+gettemp1+gettemp2) FROM MSI) and UPTIM between @st and @en",$p,$v);
			$whestr = " and id<> ".$mxid." and id<>".$mnid." ";
			$ret=$db->executeSql("INSERT INTO AVGTEMP(UPTIM,gettemp0,gettemp1,gettemp2,gettemp3)" .
					"(SELECT @UPTIM ,avg(gettemp0),avg(gettemp1),avg(gettemp2),avg(gettemp3) FROM MSI WHERE UPTIM between @st and @en ".$whestr.")",$p,$v);
			echo "avg update3(".$ret.")  ".getint2datelong($daybeftime)."\n";
			break;
		default:
			//最大の時のIDを取得
			//最小の時のIDを取得
			//それ以外で平均を取得
			$pp = array("@UPTIM","@st1","@en1","@st","@en");
			$vv = array($daybeftime,$daybeftime,$daybefentime,$daybeftime,$daybefentime);
			$mxid=$db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT max(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1) and UPTIM between @st and @en",$pp,$vv);
			$mnid=$db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT min(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1) and UPTIM between @st and @en",$pp,$vv);
			$whestr = " and id<> ".$mxid." and id<>".$mnid." ";
			$whemaxstr = " and id <> ".$mxid." ";
			$wheminstr = " and id <> ".$mnid." ";
			$mmxid=$db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT max(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1 ".$whemaxstr.") and UPTIM between @st and @en ".$whemaxstr,$pp,$vv);
			$mmnid=$db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT min(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1 ".$wheminstr.") and UPTIM between @st and @en ".$wheminstr,$pp,$vv);					
			$whestr .= " and id<> ".$mmxid." and id<>".$mmnid." ";
			$ret=$db->executeSql("INSERT INTO AVGTEMP(UPTIM,gettemp0,gettemp1,gettemp2,gettemp3)" .
					"(SELECT @UPTIM ,avg(gettemp0),avg(gettemp1),avg(gettemp2),avg(gettemp3) FROM MSI WHERE UPTIM between @st and @en ".$whestr.")",$p,$v);
			echo "avg update d(".$ret.")  ".getint2datelong($daybeftime)."\n";
			break;
		}
	} else {
		echo "avg update exist  ".getint2datelong($daybeftime)."\n";
	}
	$db->disconnect();
}


if ($sendalertmailchk){
	
	$mailini = parse_ini_file(dirname(__FILE__)."/../cfg/mailconfig.ini");
	extract($mailini); //メール設定情報iniファイルを展開

	if (! isset($sendintval)) { $sendintval=999999;}
	if (! isset($mad)) { $mad="root@localhost";}
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
	
	/*
	
	//現在温度の取得時間の妥当性をチェック
	if (file_exists(dirname(__FILE__).'/../nowtemp')) {
		$lines = file(dirname(__FILE__).'/../nowtemp');
	} else {
		writeLog("現在温度が取得できない");
		exit();
	}
 
	//	取得時間が現在時間+-15分以上だったら終了
	$nowtemp = explode("\t",$lines[0]);
	//echo $nowtemp[0];  //取得時間
	//echo $nowtemp[1];  //センサー１～４
	//echo $nowtemp[2];
	//echo $nowtemp[3];
	//echo $nowtemp[4];

	//時間の妥当性
	if (! is_numeric($nowtemp[0])) { 
		writeLog("取得時間妥当性エラー");
		exit(); 
	}

	if (($nowtemp[0] > time()+(15*60)) | ($nowtemp[0] < (time() - (15*60)))) {
		writeLog( "取得時間が+-15分以上\n");
		writeLog( "現在の日時 ".getint2date(time())." ".getint2time(time())."\n");
		writeLog( "  取得日時 ".getint2date($nowtemp[0])." ".getint2time($nowtemp[0])."\n");
		exit();
	}
*/
	//	前回送信時間を取得
	$lastsend = 0;
	if (file_exists(dirname(__FILE__).'/lastsend')) {
		$fp = fopen(dirname(__FILE__).'/lastsend','r');
		$lastsend = fgets($fp);
		fclose($fp);
	}
	$lastsend=str_replace("\n","",$lastsend);

	//前回送ってから設定時間以上たっているか？
	//(10分置きにメールがきたらたまらん )
	if ( ($lastsend+($sendintval * 60)) > time()) { 
		writeLog( " 送信済み (前回送信日時 ".getint2date($lastsend)." ".getint2time($lastsend)).")";
 		exit(); 
	}

	
	//  todo;    アラートの元となる温度のセンサーを設定で変えられるようにする！！
	//          今は$tp[1]を固定$senceno 
	$nowtemp[0]=0;
	$nowtemp[1]=$tp[$senceno -1 ];
	
	//温度の妥当性
	if (! is_numeric($nowtemp[1])) { 
		writeLog("温度妥当性エラー");
		exit(); 
	}

	//現在時刻から時間帯を取得
	$i = intval(substr(getint2time(time()),0,2));   // "05:00" -> 5
	$tm=time();
	
	//高温アラート
	if(! $hc[$i] == 0) {
		if ($nowtemp[1] >= $h[$i] ) {
			$bun  = "  取得日時: ".getint2datelong($tm)."\n";
			$bun .= "  設定温度:".$h[$i]."\n";
			$bun .= "現在の温度:".$nowtemp[1]."\n";
			send_alert("High!!",$bun,$mad);	
		}
	}

	//低温アラート
	if(! $lc[$i] == 0) {
		if ($nowtemp[1] <= $l[$i] ) {
			$bun  = "  取得日時: ".getint2datelong($tm)."\n";
			$bun .= "  設定温度:".$l[$i]."\n";
			$bun .= "現在の温度:".$nowtemp[1]."\n";
			send_alert("Low!!",$bun,$mad);	
		}
	}
}

exit();

?>
