/*
SQLyog Community- MySQL GUI v8.22 
MySQL - 5.5.8-log : Database - epub_publish
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`epub_publish` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `epub_publish`;

/*Table structure for table `activitylibrary` */

DROP TABLE IF EXISTS `activitylibrary`;

CREATE TABLE `activitylibrary` (
  `activityId` int(11) NOT NULL AUTO_INCREMENT,
  `activityName` varchar(250) DEFAULT NULL,
  `activityType` varchar(250) DEFAULT NULL,
  `activityDesc` varchar(250) DEFAULT NULL,
  `activityFile` varchar(250) DEFAULT NULL,
  `activityFolder` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`activityId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `activitylibrary` */

insert  into `activitylibrary`(`activityId`,`activityName`,`activityType`,`activityDesc`,`activityFile`,`activityFolder`) values (1,'act1','animatype','act1','assets_1340033035_1340254681_23062012__23062012__23062012__23062012__23062012.zip','act1340297881'),(2,'Surface1','dragdrop','Surface1','surface_a_1340624332.zip','act1340624332'),(3,'Activity2','animatype','Activity2','activity_20042012_1340587384.zip','act1340630584'),(4,'Sactivity','dragdrop','forgsake','forcesofnature_1334399167_1340689007.zip','act1340689007');

/*Table structure for table `epubsetting` */

DROP TABLE IF EXISTS `epubsetting`;

CREATE TABLE `epubsetting` (
  `settingId` int(11) NOT NULL AUTO_INCREMENT,
  `presetName` varchar(200) DEFAULT NULL,
  `presetSet` int(11) DEFAULT NULL,
  `epubVersion` varchar(50) DEFAULT NULL,
  `outputType` varchar(50) DEFAULT NULL,
  `bookTitle` varchar(200) DEFAULT NULL,
  `coverImage` varchar(200) DEFAULT NULL,
  `resol` int(11) DEFAULT NULL,
  `supportDevice` varchar(50) DEFAULT NULL,
  `fixedLay` varchar(50) DEFAULT NULL,
  `openSpread` varchar(50) DEFAULT NULL,
  `interActive` varchar(50) DEFAULT NULL,
  `specificFont` varchar(50) DEFAULT NULL,
  `fontName` varchar(200) DEFAULT NULL,
  `oriLock` varchar(50) DEFAULT NULL,
  `epubFolder` varchar(250) DEFAULT NULL,
  `pdfFile` varchar(200) DEFAULT NULL,
  `pdfPages` int(11) DEFAULT NULL,
  `activityFolder` longblob,
  `decimalValue` decimal(5,2) DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  PRIMARY KEY (`settingId`),
  KEY `epub1` (`epubFolder`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `epubsetting` */

insert  into `epubsetting`(`settingId`,`presetName`,`presetSet`,`epubVersion`,`outputType`,`bookTitle`,`coverImage`,`resol`,`supportDevice`,`fixedLay`,`openSpread`,`interActive`,`specificFont`,`fontName`,`oriLock`,`epubFolder`,`pdfFile`,`pdfPages`,`activityFolder`,`decimalValue`,`createdDate`) values (1,'',1,'version2','2','Scorm','',120,'ipad','fixtrue','opetrue','inttrue','spefal',NULL,'portrait-only','thegoldenstate_1340702691','the golden state.pdf',32,'',NULL,'2012-06-26 09:29:03'),(2,'',1,'version2','2','wewerewr','',120,'ipad','fixtrue','opetrue','inttrue','spefal',NULL,'portrait-only','thegoldenstate_1340672592','the golden state.pdf',0,NULL,NULL,NULL),(3,'',1,'version2','2','ewrwerwer','',120,'ipad','fixtrue','opetrue','inttrue','spefal',NULL,'portrait-only','thegoldenstate_1340673234','the golden state.pdf',32,NULL,NULL,NULL),(4,'',1,'version2','2','E-sample1','Water lilies.jpg',120,'ipad','fixtrue','opetrue','inttrue','spefal',NULL,'portrait-only','5_1340793581','5.3_Benny Goes for a Walk.pdf',24,NULL,NULL,NULL);

/*Table structure for table `preset_settings` */

DROP TABLE IF EXISTS `preset_settings`;

CREATE TABLE `preset_settings` (
  `presetId` int(11) NOT NULL AUTO_INCREMENT,
  `presetname` varchar(200) DEFAULT NULL,
  `versiontype` varchar(200) DEFAULT NULL,
  `outputtype` varchar(200) DEFAULT NULL,
  `dpires` varchar(200) DEFAULT NULL,
  `supdev` varchar(200) DEFAULT NULL,
  `fixlay` varchar(200) DEFAULT NULL,
  `openspread` varchar(200) DEFAULT NULL,
  `interlay` varchar(200) DEFAULT NULL,
  `fontset` varchar(200) DEFAULT NULL,
  `oriloc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`presetId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `preset_settings` */

insert  into `preset_settings`(`presetId`,`presetname`,`versiontype`,`outputtype`,`dpires`,`supdev`,`fixlay`,`openspread`,`interlay`,`fontset`,`oriloc`) values (1,'Preset 1','version2','2','120','ipad','fixtrue','opetrue','inttrue','spefal','portrait-only'),(2,'Preset 2','version3','2','100','ipad','fixtrue','opetrue','','spefal','portrait-only');

/* Trigger structure for table `activitylibrary` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `activity_desc` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `activity_desc` BEFORE INSERT ON `activitylibrary` FOR EACH ROW begin
if (new.activityDesc = '') then
set new.activityDesc= '-';
end if;
end */$$


DELIMITER ;

/* Procedure structure for procedure `getSettingId` */

/*!50003 DROP PROCEDURE IF EXISTS  `getSettingId` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `getSettingId`(
in setids int,
out setid int,OUT prename varchar(200),OUT preset INT,
OUT epubver varchar(50),OUT output VARCHAR(50),
OUT booktit VARCHAR(200),OUT coverim VARCHAR(200),
OUT reso int,OUT supportd VARCHAR(50),OUT fixedlay VARCHAR(50),
OUT openspr VARCHAR(50),OUT interac VARCHAR(50),
OUT specifi VARCHAR(50),OUT fontname VARCHAR(50),
OUT orilock VARCHAR(50),OUT epubfol VARCHAR(250),
OUT pdffile VARCHAR(200), OUT pdfpage int,
OUT activity longblob, OUT credate datetime
)
begin
select settingId,presetName,presetSet,epubVersion,outputType,
bookTitle,coverImage,resol,supportDevice,fixedLay,
openSpread,interActive,specificFont,fontName,oriLock,epubFolder,
pdfFile,pdfPages,activityFolder,createdDate into setid,prename,preset,
epubver,output,booktit,coverim,reso,supportd,fixedlay,
openspr,interac,specifi,fontname,orilock,epubfol,
pdffile,pdfpage,activity,credate from epubsetting where settingId = 
setids;
end */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
