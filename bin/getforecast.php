#!/usr/bin/php -q
<?php
//
//  天気予報情報の取得 
//    Ver2.0
//       2013/04/06 livedoor側の仕様変更によりjsonからの取り込みとなった。
require ('/var/www/motion/lib/mydbcls.php');
$base_url ="http://weather.livedoor.com/forecast/webservice/json/v1?city=013010";

$json = file_get_contents($base_url,true);

if ($json == false ) {

	echo 'falut';
	return;
} 
$w_temp_max="";
$w_temp_min="";
$obj = json_decode($json);
foreach ($obj as $key => $value) {
	switch ($key) {
		case 'location':
			foreach ($value as $key2 => $value2)
			{
				//echo $key2.":".$value2."\n";
			}
			break;
		
		case 'title':
			//echo $key.":".$value."\n";
			$w_title = $value;
			break;

		case 'link':
			//echo $key.":".$value."\n";
			break;

		case 'publicTime':
			//echo $key.":".$value."\n";
			$w_publictime = $value;
			break;
	
		case 'description':
			foreach ($value as $key2 => $value2)
			{
				if($key2 === "text") {
					$w_description = $value2;
				}
				//echo $key2.":".$value2."\n";
			}
			break;

		case 'forecasts':
			foreach ($value as $key2 => $value2)
			{
				//echo "-->".$key2."<--"."\n";
				foreach ($value2 as $key3 => $value3)
				{

					if (is_object($value3)) {
						foreach ($value3 as $key4 => $value4)
						{	
							if (is_object($value4)) {
							foreach ($value4 as $key5 => $value5)
								{
									if ($key2 == 0 && $key4==="max" && $key5==="celsius" ) {
										$w_temp_max =$value5; 
									}
									if ($key2 == 0 && $key4==="min" && $key5==="celsius" ) {
										$w_temp_min  = $value5;
									}
									//echo $key5.":<-5-".$value5."\n";
								}
							} else {
								
								if ($key2 ==0 && $key4==="url") {
									$w_img = basename($value4);
								}
								//echo $key4.":<-4-".$value4."\n";
							}
						}
					} else {	
						if ($key2 == 0 && $key3==="telop" ) {
							$w_telop= $value3;
						}

						//echo $key3.":<-3-".$value3."\n";
					}

				
				}
			}
			break;

		case 'pinpointLocation':
			foreach ($value as $key2 => $value2)
			{
				//echo $key2.":".$value2."\n";
			}
			break;
		
		case 'copyright':
			foreach ($value as $key2 => $value2)
			{
				if (is_object($value2)) {
					foreach ($value2 as $key3 => $value3)
					{
					//	echo $key3.":".$value3."\n";
					}
				} else {
					//echo $key2.":".$value2."\n";
				}
	
			}
			break;

			
		default:
			
	}
	
}	 

	$db = new mydbcls("localhost","gettemp","gettemp","gettemp"); 
	$nowint = gmp_strval(gmp_div_q((time()+32400) , 86400)) * 86400 -32400; 

	$ret=$db->getrecordCount("SELECT COUNT(*) as cnt FROM forecast WHERE UPTIM=".$nowint);

	if ($ret==0){
		$ret=$db->executeSql("INSERT INTO forecast(UPTIM,w_title,w_publictime,w_telop
                       ,w_description,w_temp_max,w_temp_min,w_img) VALUES (".$nowint.",'".$w_title."','".$w_publictime."','".$w_telop."',
			'".$w_description."','".$w_temp_max."','".$w_temp_min."','".$w_img."')");
		if($ret==0){
			$ret= "Insert fault.";
		}else{
			$ret="Insert ok.";
		}
	}else{
		$ret=$db->executeSql("UPDATE forecast SET
                           w_title='".$w_title."',
                           w_publictime='".$w_publictime."',
                           w_telop='".$w_telop."',
                           w_description='".$w_description."',
                           w_temp_max='".$w_temp_max."',
                           w_temp_min='".$w_temp_min."',
                           w_img='".$w_img."'
                            WHERE UPTIM=".$nowint);
		if($ret==0){
			$ret= "Update fault.";
		}else{
			$ret="Uupdate ok.";
		}
	}


?>

