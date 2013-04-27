<?php
//数値から時刻だけを返す HH:MM
function getint2time($inTime) {
	return strftime("%H:%M",$inTime);
}
//数値から日付だけを返す YYYY/MM/DD
function getint2date($inTime) {
	return strftime("%Y/%m/%d",$inTime);
}

//数値から日付と時刻を返す YYYY/MM/DD hh:mm:ss
function getint2datelong($inTime) {
	return strftime("%Y/%m/%d %H:%M:%S",$inTime);
}

//日付から整数値を返す（現在時刻ならtime()だけでよいのだ！！）
// 引数なしは現在時刻からの、引数を渡される場合は yyyy/mm/dd hh:mm:ss の形式のみ。
function getdate2int() {
	if (func_num_args() ==0) {
		return time();	
	}
	$dateStr = func_get_arg(0);
	$yy = intval(substr($dateStr,0,4));
	$mm = intval(substr($dateStr,5,2));
	$dd = intval(substr($dateStr,8,2));
	$h =  intval(substr($dateStr,11,2));
	$m =  intval(substr($dateStr,14,2));
	$s =  intval(substr($dateStr,17,2));
	return mktime($h,$m,$s,$mm,$dd,$yy);
}
function is_mobile(){
		// 切り替え用URLです。falseにすれば対象を除外できます。
	$docomo = 'docomo';  // ドコモ
	$au     = 'au'; // au
	$sb     = 'sb'; // SoftBank
	$sp     = 'sp'; // スマートフォン
	$mobile = 'mobile';  // モバイル端末
    $willcom= 'willcom';
    $em     = 'em';
    
    $ua = $_SERVER['HTTP_USER_AGENT'];
	// ドコモ
	if (preg_match('/^DoCoMo/', $ua)) {
		$mobileredirect = $docomo;
	// au
	} elseif (preg_match('/^KDDI-|^UP\.Browser/',$ua)) {
		$mobileredirect = $au;
	// SoftBank
	} elseif (preg_match('#^J-(PHONE|EMULATOR)/|^(Vodafone/|MOT(EMULATOR)?-[CV]|SoftBank/|[VS]emulator/)#', $ua)) {
		$mobileredirect = $sb;
	// Willcom
	} elseif (preg_match('/(DDIPOCKET|WILLCOM);/', $ua)) {
		$mobileredirect = $willcom;
	// e-mobile
	} elseif (preg_match('#^(emobile|Huawei|IAC)/#', $ua)) {
		$mobileredirect = $em;
	// スマートフォン
	} elseif (preg_match('#\b(iP(hone|od);|Android )|Android|dream|blackberry9500|blackberry9530|blackberry9520|blackberry9550|blackberry9800|CUPCAKE|webOS|incognito|webmate#', $ua)) {
		$mobileredirect = $sp;
	// モバイル端末
	} elseif (preg_match('#(^Nokia\w+|^BlackBerry[0-9a-z]+/|^SAMSUNG\b|Opera Mini|Opera Mobi|PalmOS\b|Windows CE\b)#', $ua)) {
		$mobileredirect = $mobile;
	// PC	
	} else {
		$mobileredirect = 'pc';
	}
 
	return $mobileredirect;
}

function get_mobile_id() {
    $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
    $strHostName = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $strMobileId  ="";
    if ( preg_match("/.docomo.ne.jp/", $strHostName) ) {
        /**
         * DoCoMo
         */
        preg_match('/ser([a-zA-Z0-9]+)/',$strUserAgent, $dprg);
        if (strlen($dprg[1]) === 11) {
            $strMobileId = $dprg[1];
        } elseif (strlen($dprg[1]) === 15) {
            $strMobileId = $dprg[1];
            preg_match('/icc([a-zA-Z0-9]+)/',$strUserAgent, $dpeg);
            if (strlen($dpeg[1]) === 20) {
                $strMobileId = $dpeg[1];
            } else {
                $strMobileId = false;
            }
        } else {
            $strMobileId = false;
        }
    } elseif (preg_match('/.[dhtckrnsq].vodafone.ne.jp/', $strHostName)
           || preg_match('/.softbank.ne.jp/', $strHostName)) {
        /**
         * SoftBank
         */
        if ( preg_match('//SN([a-zA-Z0-9]+)//', $strUserAgent, $vprg)) {
            $strMobileId = $vprg[1];
        } else {
            $strMobileId = false;
        }
    } elseif ( preg_match("/.ezweb.ne.jp/", $strHostName) ) {
        /**
         * EzWeb
         */
        $strMobileId = $_SERVER['HTTP_X_UP_SUBNO'];
    }
    return $strMobileId;
}

//有効な日付かチェックする関数
function is_date($input_date){
	if ($input_date != "") {
		if(ereg("^([0-9]{4})[-/]+([0-9]+)[-/]+([0-9]+)$", $input_date, $date_parts)){
			if(checkdate($date_parts[2], $date_parts[3], $date_parts[1])){
				return true;
			}
		}
	}
return false;
}

//再帰的にパスを取得する
function array_dirlist($path, $level=30) {
  $dirlist = array();
  if ($level) {
    $dh = opendir($path);
    while (($filename = readdir($dh))) {
      if ( $filename == '..')
        continue;
      else {
        $realpath = $path.'/'.$filename;
       if (is_link($realpath))
          continue;
       else if (is_file($realpath))
       { 
		 // $dirlist[] = $filename;
		//ディレクトリだけでよいのでコメント
	}
       else if (is_dir($realpath))
	if ($filename == '.' ) {
	    $dirlist[$filename] = $filename;
        }else {
            $dirlist[$filename] = array_dirlist($realpath, $level-1);
        }
      } 
    }
    closedir($dh);
  }
 return $dirlist;
}

//上記関数は結果が多次元のarrayになるので1次元の配列に入れ直し
function array_fullpath($path, $dirlist) {
  $fullpath = array();
  foreach ($dirlist as $id=>$filename) {
    if (is_array($filename)) {
      $fullpath = array_merge($fullpath,  array_fullpath($path.'/'.$id, $filename));
    }else{
	if (strlen($path.'/'.$filename) > 12) {
                    if (is_date(substr($path.'/'.$filename,2,10))) {
      			$fullpath[] = $path.'/'.$filename;   //有効な日付フォルダのみ返すことにした。
                    }
        }
   }
  }
  return $fullpath;
}


?>
