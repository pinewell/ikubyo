<?php
//
// ajax mdl.js の中のセンサー数の更新 upsencnt() で呼ばれる。
// 特に値は返さない。
//
$ini = parse_ini_file("./cfg/config.ini");
exec ('./bin/arduino-serial -b 9600 -p '.$ini['serial'].' -s i ',$retstr);
?>
