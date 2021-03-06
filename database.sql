CREATE DATABASE gettemp;
USE gettemp;
CREATE TABLE `MSI` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `gettemp0` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp1` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp2` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp3` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Weather` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=35411 DEFAULT CHARSET=utf8;

CREATE TABLE `HIGHLOW_Y` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIM` int(11) NOT NULL DEFAULT '0',
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `SensorNo` tinyint(4) NOT NULL DEFAULT '0',
  `gettemp` decimal(5,2) NOT NULL DEFAULT '0.00',
  `kbn` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:High 1:Low',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `HIGHLOW_M` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIM` int(11) NOT NULL DEFAULT '0',
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `SensorNo` tinyint(4) NOT NULL DEFAULT '0',
  `gettemp` decimal(5,2) NOT NULL DEFAULT '0.00',
  `kbn` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:High 1:Low',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `HIGHLOW` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TIM` int(11) NOT NULL DEFAULT '0',
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `SensorNo` tinyint(4) NOT NULL DEFAULT '0',
  `gettemp` decimal(5,2) NOT NULL DEFAULT '0.00',
  `kbn` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:High 1:Low',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3580 DEFAULT CHARSET=utf8;

CREATE TABLE `AVGTEMP` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `gettemp0` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp1` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp2` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp3` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2145 DEFAULT CHARSET=utf8;

CREATE TABLE `MSI_Y` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `gettemp0` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp1` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp2` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp3` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Weather` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `MSI_M` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `gettemp0` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp1` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp2` decimal(5,2) NOT NULL DEFAULT '0.00',
  `gettemp3` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Weather` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `forecast` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UPTIM` int(11) NOT NULL DEFAULT '0',
  `w_title` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `w_publictime` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `w_telop` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `w_description` varchar(5000) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `w_temp_max` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `w_temp_min` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `w_img` char(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=448 DEFAULT CHARSET=utf8;

GRANT ALL PRIVILEGES ON *.* TO 'gettemp'@'localhost' IDENTIFIED
BY '%パスワード%' WITH GRANT OPTION;

