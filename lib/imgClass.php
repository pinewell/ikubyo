<?php
class imgClass {
	var $pPath;
	var $Total;
	var $imgFile;
	var $dspCnt;   //一度に表示するイメージの数
        var $pkirinID;
        var $pkirinHost;

	//コンストラクタ
	Function imgClass($path,$c=10,$kId,$kHost) {
		$this->pPath = $path;
		$this->dspCnt = $c;
		$this->pkirinID=$kId;
		$this->pkirinHost=$kHost;
		$this->SetTotal();
	}
	Function SetTotal() {
		//$dir = dir('./');
		$dir = dir($this->pPath);
		$path = $dir->path."/";
		$row = 0;
		$file = array();
		while (($temp = $dir->read()) !== false ) {
			if ($temp != "." && $temp != "..") {
				switch (filetype($path.$temp)) {
					case "dir":
						break;
					case "file":
						if (pathinfo($path.$temp,PATHINFO_EXTENSION) =='jpg') {
							$file[$row] = $temp;
							$row++;
						}
						break;
					default:
						break;
				}
			}
		}
		rsort($file);
		$dir->close();
		$this->Total = $row;	
		$this->imgFile = $file;
	}
	Function total() {
		return $this->Total;
	}
	Function getimgPath($i) { 
		return $this->imgFile[$i];
	}
	Function listImg($tflg=0,$stcnt=0) {
		$kirinID=$this->pkirinID;
		$bdir = $_SERVER['DOCUMENT_ROOT']."/motion/";
                $kirinHost =$this->pkirinHost;
//イメージファイルをリスト表示
//$tflgでテーブル表示させるかどうか。
//$stcntはスタートさせるイメージの位置。0で最新のイメージとなる。
//$dspCnt  一度に表示するイメージの数。

		if ($tflg==0){
			//テーブル不使用
			$cnt=0;
			if ($stcnt > $this->Total-1) { $stcnt=$this->Total-1;}

			for ($j=$stcnt ; $j < $this->Total;$j++){
				
				echo '<a href = "'.$this->imgFile[$j].'"><p2><img src="'.$this->imgFile[$j].'" width="160" height="120"></p2></a><br>'."\n";
				
				
				$t=substr($this->imgFile[$j],0,2).":".substr($this->imgFile[$j],2,2).":".substr($this->imgFile[$j],4,2);
				echo $t.'<br>';	
				$cnt++;
				if ($cnt>=$this->dspCnt) {break;}
			}
			$j++;
			if ($j<=$this->Total) { echo $j."/".$this->Total."<br>"; }else {
				echo $this->Total."/".$this->Total."<br>";
			}
			echo "<hr>";
			if ($j-$this->dspCnt > 0 ){ 
				//echo '<a href =index.php?Cnt='.($j-1)-($this->dspCnt).'>前へ</a>';
				$cc=$stcnt-$this->dspCnt;
				if ($cc<0){$cc=0;}
				echo "<a href =index.php?Cnt=".$cc.">前へ</a>";
			}
			if ($j < $this->Total) {
				echo "<a href =index.php?Cnt=".$j.">次へ</a>";
			}


		} else {
			//テーブル使用
			$j=0;
			$r=0;
			$row=count($this->imgFile);
			$colcnt = 5;
			$j=($row-1) % $colcnt;
			$rowmax  = (int)(($row-1) / $colcnt);
			if ($j >1) { $rowmax++; }

			echo '<table border=1><tbody>';

			for ($j=0 ; $j < $rowmax;$j++){
				echo '<tr alien=center>';
				for ($i=0;$i < $colcnt ;$i++) {
					if ($r+$i < $row ) {
						echo '<td><a href = "'.$file[$r+$i].'"><img src="'.$file[$r+$i].'" width=80></a></td>';	
						//echo '<td><a href = "'.$this->imgFile[$r+$i].'"><img src="thumb.php?path='.$this->imgFile[$r+$i].'" ></a></td>';	
					} else {
						echo '<td></td>';
					}
				}
				echo '</tr>';
				echo '<tr align=center bgcolor="#cccccc">';
				for ($i=0;$i < $colcnt ;$i++) {
					if ($r+$i < $row ) {
						$t=substr($this->imgFile[$r+$i],0,2).":".substr($this->imgFile[$r+$i],2,2).":".substr($this->imgFile[$r+$i],4,2);
						echo '<td>'.$t.'</td>';	
					} else {
						echo '<td></td>';
					}
				}
				echo '</tr>';
				$r=$r+$colcnt;
			}
			echo '</tbody></table>';
		}
		echo "<br>\n";
		if ($this->Total > 0 ) {
			//イベント毎の表示
			echo "イベント別<br>";
			$buf ="";
			$cnt =1; 
			for ($i=0;$i<$this->Total;$i++) {
				//$t=substr($this->imgFile[$i],7,2);
				$t=substr($this->imgFile[$i],7,2+(strrpos($this->imgFile[$i],"-")-9));
				$tt = substr($this->imgFile[$i],0,2).":".substr($this->imgFile[$i],2,2).":".substr($this->imgFile[$i],4,2);

				if (!strcmp($buf,$t)==0){
					$buf=$t;
					if(!$i==0){
						echo " ".$cnt."枚</a><br>";
						$cnt=1;
					} 
					echo "<a href=index.php?Cnt=".$i.">". $tt."～ ";
				} else { $cnt++;}
		
			}
			echo " ".$cnt."枚</a><br>";
			$cnt=0;
/*			//時間帯毎の表示
			echo "時間帯別<br>";
			$buf ="";
			$cnt =1; 
			for ($i=0;$i<$this->Total;$i++) {
				$t=substr($this->imgFile[$i],0,2);
				if (!strcmp($buf,$t)==0){
					$buf=$t;
					if(!$i==0){
						echo " ".$cnt."枚</a><br>";
						$cnt=1;
					} 
					echo "<a href=index.php?Cnt=".$i.">". $buf.":00～ ";
				} else { $cnt++;}
		
			}
			echo " ".$cnt."枚</a><br>";
*/		
		}


	}
}

?>
