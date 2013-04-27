<?php

class tempCls{
	private $dMode;        //動作モード,'d' or 'm' or 'y'  1日の動き、ひと月の動き１年の動き //未実装
    private $cTime;   //作成するデータの参照時間（int）。
	private $senseCnt;     //センサーの数 (1~4)
	private $db;           //データベースクラス
	// $timx[]     時間軸
	// $temper[][]  センサーナンバー、時間軸に応じた温度
	// $tempHi[][][]  センサーナンバー、最高温度、時間
	// $tempLo[][][]  センサーナンバー、最低温度、時間
	function __construct($_dMode,$_createTime,$_senseCnt,$_db) {
		$this->dMode      =$_dMode;
		$this->cTime =$_createTime;
		$this->senseCnt   =$_senseCnt;
		$this->db         =$_db;
		$this->init();
	}
	function init(){
		//コンストラクタから呼ばれ、元となるデータを作成
		global $tempHi;
		global $tempLo;
		global $temper;
		global $timx;
		
		switch($this->dMode) {
			case 'y':
				break;
			case 'm':
				break;
			default:
				$tbName = "HIGHLOW";
				$tbMsi  = "MSI";

				$stint = gmp_strval(gmp_div_q(($this->cTime+32400) , 86400)) * 86400 -32400; //指定日にちのタイムスタンプから 0時のタイムスタンプ生成
				$enint = $stint + 86399;                                                     //                     翌日    0時のタイムスタンプ生成
				
		}
			
			//unset($timx);
			//unset($temper);
			

		for ($i=0;$i<$this->senseCnt;$i++) {
			//センサー毎に
			//最高温度の取得
			$tb="";
			unset($tb);
			$tb=$this->db->getDataTable("select gettemp,UPTIM from ".$tbName." where kbn=0 and SensorNo=".$i." and TIM=".$stint);
			if(count($tb)>0){
				$tempHi[$i] =array($tb[0]['gettemp'],getint2time($tb[0]['UPTIM']));
			}else{
				$tempHi[$i] =array('_','_');
			}
			//最低温度の取得
			unset($tb);
			$tb=$this->db->getDataTable("select gettemp,UPTIM from ".$tbName." where kbn=1 and SensorNo=".$i." and TIM=".$stint);
			if(count($tb)>0){
				$tempLo[$i] =array($tb[0]['gettemp'],getint2time($tb[0]['UPTIM']));
			}else{
				$tempLo[$i] =array('_','_');
			}
		}
   	 		//１日の動きをarrayへ。
   	 		// $temper[センサー毎][0~23]=温度
   	 		// 24個の配列に一つずつ入れている。
   	 		//      データには1時間に1行しかデータが無いことが前提。と、いうか書き込み時にそうしている。
   	 		/*
			unset($tb);
			$tb=$this->db->getDataTable("select UPTIM,gettemp".$i." as gettemp from ".$tbMsi." where UPTIM >=".$stint." and UPTIM < ".$enint." order by UPTIM ");
			$r=0;	
			for($ct=0;$ct<24;$ct++){
				if (isset($tb[$r]['UPTIM'])) {
					//$ctは０～23時   データから取得したタイムスタンプの値から時間を読み取り、
					if($ct == intval(substr(getint2time($tb[$r]['UPTIM']),0,2))) {
						if ($i==0){  //センサーが0の時だけタイムスタンプを配列に。
							$timx[]=getint2time($tb[$r]['UPTIM']);
						}
						//
						$temper[$i][]=$tb[$r]['gettemp'];
						if($r<count($tb)) {$r++; }
					} else {
						if ($i==0){
							$timx[]= substr("00".$ct,-2,2).":00";
						}

					$temper[$i][]="_";
					}
				}else{
					if ($i==0){
							$timx[]= substr("00".$ct,-2,2).":00";
					}
					$temper[$i][]="_";

				}
			}
			*/
		/*	
			//$i はセンサー番号
			//$ct は 時間帯ごとの枠
			//   cronで5分ごとに温度は記録されている。
			//   最大で12だが、それ以上にも対応。
			//   時間帯ごとに1つもレコードが無い= "_"
			//   時間帯ごとに1つのレコード      = それ。
			//              2つのレコード      = 2つの平均。
			//              3つ               = 一番高い/一番低いを除外。
			//              4つ               = 一番高い/一番低いを除外して2つの平均
			//              5つ               = 一番高い/一番低いを除外して3つの平均
			//              6つ               = 一番高い/一番低いを除外して4つの平均
			//              7つ               = 一番高い/一番低いを除外して5つの平均
			//              8つ以上           = 一番高い、次に高い/一番低い、次に低いを除外して4つの平均。
			//  タイムスタンプはxx:00:00の時のものを使用。

		for($j=0;$j<$this->senseCnt;$j++){
			for($i=0;$i<24;$i++){
				$temper[$j][]="_";
			}
		}
			for($ct=0;$ct<24;$ct++){	
				$sthint= $stint+($ct*3600);  //今日の00:00:00から時間帯を作成  
				$enhint= $sthint + 3599;     //上記時間の60秒x60分 -1 = xx:59:59。
				$timx[]=getint2time($sthint);
				$p = array("@st","@en");
				$v = array($sthint,$enhint);
				$cnt=$this->db->getRecordcount("SELECT count(*) as cnt FROM MSI WHERE UPTIM between @st and @en",$p,$v);

				unset($tb);		
				switch($cnt){
					case 0:						
						break;
					case 1:
						$sqlst ="";
						for($i=0;$i<$this->senseCnt;$i++){
							$sqlst .="gettemp".$i.",";
						}
						$sqlst .="0";
				
						$tb=$this->db->getDatatable("SELECT ".$sqlst." from MSI WHERE UPTIM between @st and @en",$p,$v);
						for($i=0;$i<$this->senseCnt;$i++){
							$temper[$i][$ct]=number_format($tb[0]['gettemp'.$i],2);
						}						
						break;
					case 2:
						$sqlst ="";
						for($i=0;$i<$this->senseCnt;$i++){
							$sqlst .="avg(gettemp".$i.") as gettemp".$i.",";
						}				
						$sqlst .="0";
						
						$tb=$this->db->getDatatable("SELECT ".$sqlst." from MSI WHERE UPTIM between @st and @en",$p,$v);
						for($i=0;$i<$this->senseCnt;$i++){
							$temper[$i][$ct]=number_format($tb[0]['gettemp'.$i],2);
						}
						break;
					case 3: case 4: case 5:  case 6:  case 7:
						//最大の時のIDを取得
						//最小の時のIDを取得
						//それ以外で平均を取得
						$mxid=$this->db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT max(gettemp0+gettemp1+gettemp2) FROM MSI) and UPTIM between @st and @en",$p,$v);
						$mnid=$this->db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT min(gettemp0+gettemp1+gettemp2) FROM MSI) and UPTIM between @st and @en",$p,$v);
						$whestr = " and id<> ".$mxid." and id<>".$mnid." ";
						$sqlst ="";
						for($i=0;$i<$this->senseCnt;$i++){
							$sqlst .="avg(gettemp".$i.") as gettemp".$i.",";
						}
										
						$sqlst .="0";
						
						$tb=$this->db->getDatatable("SELECT ".$sqlst." from MSI WHERE UPTIM between @st and @en ".$whestr,$p,$v);
						for($i=0;$i<$this->senseCnt;$i++){
							$temper[$i][$ct]=number_format($tb[0]['gettemp'.$i],2);
						}
						break;
					default:
						//最大の時のIDを取得
						//最小の時のIDを取得
						//それ以外で平均を取得
						$pp = array("@st1","@en1","@st","@en");
						$vv = array($sthint,$enhint,$sthint,$enhint);
						$mxid=$this->db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT max(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1) and UPTIM between @st and @en",$pp,$vv);
						$mnid=$this->db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT min(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1) and UPTIM between @st and @en",$pp,$vv);
						$whestr = " and id<> ".$mxid." and id<>".$mnid." ";
						$whemaxstr = " and id <> ".$mxid." ";
						$wheminstr = " and id <> ".$mnid." ";
						$mmxid=$this->db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT max(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1 ".$whemaxstr.") and UPTIM between @st and @en ".$whemaxstr,$pp,$vv);
						$mmnid=$this->db->dLookup("SELECT id FROM MSI WHERE gettemp0+gettemp1+gettemp2 = (SELECT min(gettemp0+gettemp1+gettemp2) FROM MSI WHERE UPTIM between @st1 and @en1 ".$wheminstr.") and UPTIM between @st and @en ".$wheminstr,$pp,$vv);					
						$whestr .= " and id<> ".$mmxid." and id<>".$mmnid." ";
						$sqlst ="";
						for($i=0;$i<$this->senseCnt;$i++){
							$sqlst .="avg(gettemp".$i.") as gettemp".$i.",";
						}										
						$sqlst .="0";  //単にダミー。
						$tb=$this->db->getDatatable("SELECT ".$sqlst." from MSI WHERE UPTIM between @st and @en ".$whestr,$p,$v);
					
						for($i=0;$i<$this->senseCnt;$i++){
							$temper[$i][$ct]=number_format($tb[0]['gettemp'.$i],2);
						}
						break;
					}
			}
		*/
		//
		//  AVGTEMPから取得。
		//
		for ($i=0;$i<24;$i++){
			$timx[]=str_pad($i, 2, '0', STR_PAD_LEFT).":00";
		}
		for($j=0;$j<$this->senseCnt;$j++){
			for($i=0;$i<24;$i++){
				$temper[$j][]="_";
				
			}
		}
		$p = array("@st","en");
		$v = array($stint,$enint);
		$cnt=$this->db->getRecordCount("SELECT COUNT(*) as cnt FROM AVGTEMP WHERE UPTIM >= @st AND UPTIM <= @en",$p,$v);
		if ($cnt > 0){
			$tb = $this->db->getDataTable("SELECT UPTIM,gettemp0,gettemp1,gettemp2,gettemp3 FROM AVGTEMP WHERE UPTIM >= @st AND UPTIM <= @en ORDER BY UPTIM",$p,$v);
			for($r=0;$r<count($tb);$r++){
				$h = intval(substr(getint2time($tb[$r]['UPTIM']),0,2));
				for ($i=0;$i<$this->senseCnt;$i++){
					$temper[$i][$h]=number_format($tb[$r]['gettemp'.$i],2);
				}
/*				switch ($this->senseCnt){
					case 0:
						break;
					case 1:
						$temper[0][$h]=number_format($tb[$r]['gettemp0'],2);
						break;
					case 2:
						$temper[0][$h]=number_format($tb[$r]['gettemp0'],2);
						$temper[1][$h]=number_format($tb[$r]['gettemp1'],2);
						break;
					case 3:
						$temper[0][$h]=number_format($tb[$r]['gettemp0'],2);
						$temper[1][$h]=number_format($tb[$r]['gettemp1'],2);
						$temper[2][$h]=number_format($tb[$r]['gettemp2'],2);
						break;
					default:
						$temper[0][$h]=number_format($tb[$r]['gettemp0'],2);
						$temper[1][$h]=number_format($tb[$r]['gettemp1'],2);
						$temper[2][$h]=number_format($tb[$r]['gettemp2'],2);
						$temper[3][$h]=number_format($tb[$r]['gettemp3'],2);
						break;
				}*/
				
			}
			
		}
		
	}
	
	function getHiLo(){
		global $tempHi;
		global $tempLo;
		//最低・最高温度の表示
		echo '<table id="table-02">'."\n";
		echo "<tr>\n";
		echo "<th></th>\n";
		for($c=0;$c<$this->senseCnt;$c++){
			echo '<th>'.($c+1)."</th>\n";
			echo "<th>時間</th>\n";
		}
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>最高</td>\n";
		for($c=0;$c<$this->senseCnt;$c++){
			echo '<td align="right">'.$tempHi[$c][0]."</td>\n";
			echo '<td align="right">'.$tempHi[$c][1]."</td>\n";
		}
		echo "</tr>\n";
		echo "<td>最低</td>\n";
		for($c=0;$c<$this->senseCnt;$c++){
			echo '<td align="right">'.$tempLo[$c][0]."</td>\n";
			echo '<td align="right">'.$tempLo[$c][1]."</td>\n";
		}
		echo "</tr>\n";
		echo "</table>\n";
	}
	function getMsi() {
		global $temper;
		global $timx;
		//テーブルの作成
		echo '<table  id="table-01">'."\n";
		echo "<tr>\n";
		echo "<td></td>\n";
		for($c=0;$c<$this->senseCnt;$c++){
			echo '<th>センサー'.($c+1)."</th>\n";
		}
		echo "</tr>\n";
		//for ($j=0;$j<count($timx);$j++) {
		for ($j=0;$j<24;$j++){
			echo '<tr>'."\n";
			echo '<td>'.$timx[$j]."</td>\n";
			for($c=0;$c<$this->senseCnt;$c++){
				echo '<td align="right">'.$temper[$c][$j]."</td>\n";
			}
			echo '</tr>'."\n";	
		}
		echo '</table>'."\n";

	}
	function getChart() {
		//負の値はデータなしとみなされる！！！実際の数に＋30して、レンジであわせて表示している・・・・
		global $temper;
		global $timx;
		$t="";
		unset($t);
		for($j=0;$j<$this->senseCnt;$j++) {
			for($i=0;$i<count($temper[$j]);$i++){
				if(is_numeric($temper[$j][$i])) {
					$t[$j][] = $temper[$j][$i]+30.0;
				} else {
					$t[$j][] = -1;
				}
			}
		}

		//グラフの作成
		$lineChart = new gLineChart(740,300);
		for($c=0;$c<$this->senseCnt;$c++) {
			$lineChart->addDataSet($t[$c]);
		}

		$lineChart->setLegend(array("センサー1", "センサー2" , "センサー3", "センサー4"));
		$lineChart->setColors(array("ff3344", "11ff11", "22aacc", "3333aa" ));
		$lineChart->setVisibleAxes(array('x','y'));
		$lineChart->setDataRange(0,70);
		$lineChart->addAxisLabel(0, $timx);
		$lineChart->addAxisLabel(1, array("-30","-20","-10","0","10","20","30","40"));
		$lineChart->setStripFill('c',0,array('CCCCCC',0.04,'FFFFFF',0.04));
		echo '<p class="resizeimage"><img src="'.$lineChart->getUrl().'" ></p><br>'."\n";
	}
}
?>
