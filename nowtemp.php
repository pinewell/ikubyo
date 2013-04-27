<?php
//
// ajax mdl.js の中のgetnowtemp()で呼ばれる。現在温度をjsonで返す。
//

	$hd="取得できません。";
	$t0="取得できません。";
	$t1="取得できません。";
	$t2="取得できません。";
	$ini = parse_ini_file("./cfg/config.ini");
	exec ('./bin/arduino-serial -b 9600 -p '.$ini['serial'].' -s g -d 1200 -r',$retstr);
	$temp =explode("\t",$retstr[0]);
	switch(count($temp)) {
		case 0:
			break;
		case 1:
			break;
		case 2:
			if($temp[1]==="g") {
			} else {
				$hd=substr($temp[1],2)."\n";
			}
			break;
		case 3:
			$hd=substr($temp[1],2)."\n";
			$t0=substr($temp[2],3)."℃\n";
			break;
		case 4:
			$hd=substr($temp[1],2)."\n";
			$t0=substr($temp[2],3)."℃\n";
			$t1=substr($temp[3],3)."℃\n";
			break;
		default:
			$hd=substr($temp[1],2)."\n";
			$t0=substr($temp[2],3)."℃\n";
			$t1=substr($temp[3],3)."℃\n";
			$t2=substr($temp[4],3)."℃\n";
			break;
	}
$arr = array(array("hd" => $hd,"t0" => $t0,"t1" => $t1,"t2" => $t2 ));
$encode = json_encode($arr);
header("Content-Type: text/javascript; charset=utf-8");
echo $encode;

?>
