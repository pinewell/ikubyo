<?php

class forecastCls{
	private $db;
	private $cTime;
	
	function __construct($_cTime,$_db){
		$this->db =$_db;
		$this->cTime =gmp_strval(gmp_div_q(($_cTime+32400) , 86400)) * 86400 -32400; 
	}

	function getForecast() {
		$ret = $this->db->getRecordCount("SELECT count(*) as cnt FROM forecast WHERE UPTIM=".$this->cTime);
		if($ret!=0) {
			$tb=$this->db->getDataTable("SELECT w_title,w_publictime,w_telop,w_description,w_temp_max,w_temp_min,w_img from forecast WHERE UPTIM=".$this->cTime);
			echo  '<hr>'.$tb[0]['w_title']."<br>\n";
			echo  '発表時間'.$tb[0]['w_publictime']."<br>\n";
			echo  '<img src ="image/'.$tb[0]['w_img'].'">'. $tb[0]['w_telop']."<br>\n";
			echo  $tb[0]['w_description']."<br>\n";
			echo  '予想最高 '.$tb[0]['w_temp_max']."℃<br>\n";
			echo  '予想最低 '.$tb[0]['w_temp_min']."℃<br><hr>\n";
		} else {
			echo "天気予報情報は取得できていません。<br>\n";
		}
	}
}
