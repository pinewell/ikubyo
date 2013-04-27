#!/usr/bin/php
<?php
	//var_dump($argv);
	if (file_exists(dirname(__FILE__)."/../now.jpg")) {
		unlink(dirname(__FILE__)."/../now.jpg");
	}
	symlink($argv[1],dirname(__FILE__)."/../now.jpg");

	$d=dirname($argv[1])."/";
	$p=$d."index.php";
	if (file_exists($p)) {
	} else {
		copy(dirname(__FILE__)."/../imgdir_index.php", $p);
		// copy(dirname(__FILE__)."/thumb.php", $d."thumb.php");
		if (preg_match("/snapshot/",$argv[1])) {
		} else {
                if ($fn = fopen(dirname(__FILE__)."/../diarydir.txt",'a')) {
		//	fwrite($fn,str_replace("/var/www/motion/","",dirname($argv[1])."\n"));
			fwrite($fn,str_replace(dirname(dirname(__FILE__))."/","",dirname($argv[1])."\n"));
                	fclose($fn);
		} else { 
			echo "fopen err";
		}
		}
	}
?>
