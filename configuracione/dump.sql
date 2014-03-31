-- MySQL dump 10.13  Distrib 5.5.9, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: cslatam
-- ------------------------------------------------------
-- Server version	5.0.51b-community-nt-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Not dumping tablespaces as no INFORMATION_SCHEMA.FILES table on this server
--

--
-- Current Database: `cslatam`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `cslatam` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cslatam`;

--
-- Table structure for table `audittrail`
--

DROP TABLE IF EXISTS `audittrail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audittrail` (
  `id` int(11) NOT NULL auto_increment,
  `datetime` datetime NOT NULL,
  `script` varchar(255) default NULL,
  `user` varchar(255) default NULL,
  `action` varchar(255) default NULL,
  `table` varchar(255) default NULL,
  `field` varchar(255) default NULL,
  `keyvalue` longtext,
  `oldvalue` longtext,
  `newvalue` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audittrail`
--

LOCK TABLES `audittrail` WRITE;
/*!40000 ALTER TABLE `audittrail` DISABLE KEYS */;
INSERT INTO `audittrail` VALUES (1,'2013-02-27 15:18:34','/cssociety/csociety_source/login.php','ad','login','127.0.0.1','','','',''),(2,'2013-03-31 06:12:51','/cssociety/cs_source/login.php','ad','login','127.0.0.1','','','',''),(3,'2013-03-31 19:58:52','/cssociety/cs_source/login.php','ad','login','127.0.0.1','','','','');
/*!40000 ALTER TABLE `audittrail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogs` (
  `idforos_fb` int(11) NOT NULL auto_increment,
  `nombre` varchar(45) default NULL,
  `pais` int(11) default NULL,
  `tipo` int(11) default NULL,
  `user` varchar(245) default NULL,
  `pass` varchar(245) default NULL,
  `owner` int(11) default NULL,
  PRIMARY KEY  (`idforos_fb`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'blog personal',1,NULL,'jamesjara','password',NULL);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_grupos`
--

DROP TABLE IF EXISTS `fb_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_grupos` (
  `idfb_grupos` int(11) NOT NULL auto_increment,
  `nombre` varchar(45) default NULL,
  `pais` int(11) default NULL,
  `url` varchar(245) default NULL,
  `super_id` varchar(245) default NULL,
  PRIMARY KEY  (`idfb_grupos`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_grupos`
--

LOCK TABLES `fb_grupos` WRITE;
/*!40000 ALTER TABLE `fb_grupos` DISABLE KEYS */;
INSERT INTO `fb_grupos` VALUES (1,'CS CR FB',1,'https://www.facebook.com/groups/ComputerSocietyCR/?ref=ts&fref=ts','214267468618834'),(2,'CS PERU FB',2,'https://www.facebook.com/groups/ComputerSocietyPE/?ref=ts&fref=ts','342877735801525');
/*!40000 ALTER TABLE `fb_grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_posts`
--

DROP TABLE IF EXISTS `fb_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_posts` (
  `idfb_posts` int(11) NOT NULL auto_increment,
  `id` varchar(245) default NULL,
  `created_time` varchar(245) default NULL,
  `actions` text,
  `icon` varchar(245) default NULL,
  `is_published` varchar(245) default NULL,
  `link` varchar(245) default NULL,
  `message` text,
  `object_id` varchar(245) default NULL,
  `picture` varchar(245) default NULL,
  `privacy` text,
  `promotion_status` varchar(245) default NULL,
  `timeline_visibility` varchar(245) default NULL,
  `type` varchar(245) default NULL,
  `updated_time` varchar(245) default NULL,
  `caption` text,
  `description` text,
  `name` text,
  `source` text,
  `from` text,
  `to` text,
  `comments` text,
  `id_grupo` varchar(245) default NULL,
  PRIMARY KEY  (`idfb_posts`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_posts`
--

LOCK TABLES `fb_posts` WRITE;
/*!40000 ALTER TABLE `fb_posts` DISABLE KEYS */;
INSERT INTO `fb_posts` VALUES (98,'214267468618834_515965828448995','2013-03-31T18:36:10+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515965828448995\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515965828448995\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yz/r/StEh3RhPvjk.gif','1','http://www.facebook.com/photo.php?fbid=548525475187256&set=o.214267468618834&type=1&relevant_count=1','Hola gente necesito de asu apoyo estamos en primer lugar con el proyecto DYNAMICS MOVES de la Universidad-privada Ada A Byron, conoce el proyecto en este link http://icperu.azurewebsites.net/video/improvement-students-treatment-motive-disability nos encontramos en el concurso de IMAGINE CUP 2013 TE ADJUNTAMOS LOS PASO PARA QUE REALIZAR LA VOTACION TE NECESITAMOS CHINCHA Y El PERU..!!','548525475187256','https://fbcdn-photos-f-a.akamaihd.net/hphotos-ak-ash3/9097_548525475187256_1813071204_s.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','photo','2013-03-31T20:07:35+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}','a:2:{s:4:\"data\";a:1:{i:0;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-31T20:07:35+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:10:\"James Jara\";s:2:\"id\";s:10:\"1483154807\";}s:2:\"id\";s:47:\"214267468618834_515965828448995_516017331777178\";s:10:\"like_count\";i:0;s:7:\"message\";s:11:\"testing api\";s:10:\"user_likes\";b:0;}}s:6:\"paging\";a:1:{s:4:\"next\";s:244:\"https://graph.facebook.com/214267468618834_515965828448995/comments?limit=500&fields=can_remove,created_time,from,id,like_count,message,message_tags,user_likes,likes,comments&offset=500&__after_id=214267468618834_515965828448995_516017331777178\";}}','214267468618834'),(99,'214267468618834_515700511808860','2013-03-31T01:45:43+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515700511808860\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515700511808860\";}}',NULL,'1',NULL,'¿Estás terminando tu carrera y querés hacer tu pasantía laboral en el exterior, y no solo querés crecimiento profesional sino también querés conocer otra cultura totalmente diferente?\nAIESEC te da la oportunidad, únicos requisitos: tener mas del 80% de la carrera, máximo 2 años de egresado y menor de 30 años\nPara más información: fabiola.carmona@aiesec.net',NULL,NULL,'a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','status','2013-03-31T18:35:47+0000','Attachment UnavailableThis attachment may have been removed or the person who shared it may not have permission to share it with you.',NULL,NULL,NULL,'a:2:{s:4:\"name\";s:14:\"Thomas Bertsch\";s:2:\"id\";s:9:\"669961571\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}','a:2:{s:4:\"data\";a:1:{i:0;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-31T18:35:47+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}s:2:\"id\";s:47:\"214267468618834_515700511808860_515965691782342\";s:10:\"like_count\";i:0;s:7:\"message\";s:25:\"yo..!! Ing. Sistemas Peru\";s:10:\"user_likes\";b:0;}}s:6:\"paging\";a:1:{s:4:\"next\";s:244:\"https://graph.facebook.com/214267468618834_515700511808860/comments?limit=500&fields=can_remove,created_time,from,id,like_count,message,message_tags,user_likes,likes,comments&offset=500&__after_id=214267468618834_515700511808860_515965691782342\";}}','214267468618834'),(100,'214267468618834_515529201825991','2013-03-30T16:27:21+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515529201825991\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515529201825991\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yz/r/StEh3RhPvjk.gif','1','http://www.facebook.com/photo.php?fbid=548036628569474&set=o.214267468618834&type=1&relevant_count=1','TE ENSEÑAMOS COMO VOTAR POR EL PROYECTO BETTER WORLD - CONCURSO DE MICROSOFT NACIONAL E INTERNACIONAL TE NECESITAMOS ...!!','548036628569474','https://fbcdn-photos-a.akamaihd.net/hphotos-ak-ash4/299199_548036628569474_1495505807_s.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','photo','2013-03-30T16:27:21+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834'),(101,'214267468618834_515529071826004','2013-03-30T16:26:47+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515529071826004\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/515529071826004\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yj/r/v2OnaTyTQZE.gif','1','http://www.youtube.com/watch?feature=player_embedded&v=ZRTCEuFsb7I','Necesito su apoyo para un concurso de Microsoft si le interesa la solución brindada entra al link --> http://icperu.azurewebsites.net/video/project-minimize-bullying y vota por Proyecto Better World - Proyecto para minimizar el acoso escorlar TU VOTO CUENTA TE NECESITAMOS .....!! HELP HELP \n\nhttp://www.youtube.com/watch?feature=player_embedded&v=ZRTCEuFsb7I',NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQBuP5xVk3qSsI7m&w=130&h=130&url=http%3A%2F%2Fi3.ytimg.com%2Fvi%2FZRTCEuFsb7I%2Fmqdefault.jpg%3Ffeature%3Dog','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','video','2013-03-30T16:26:47+0000',NULL,'Imagine Cup 2013','Better World - Proyecto para minimizar el acoso escorlar','http://www.youtube.com/v/ZRTCEuFsb7I?version=3&autohide=1&autoplay=1','a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834'),(102,'214267468618834_514079278637650','2013-03-27T05:14:36+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514079278637650\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514079278637650\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://www.technochatnews.com/wp/?p=3529',NULL,NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQD6_Q68PoNM3PfB&w=154&h=154&url=http%3A%2F%2Fwww.technochatnews.com%2Fwp%2Fwp-content%2Fuploads%2F2013%2F03%2Flo-que-te-gusta-300x314.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-30T15:32:41+0000','www.technochatnews.com','Ha cuantos de ustedes les ha sucedido, que a la hora de escoger alguna carrera profesional lo hacemos por lo que dicte el mercado, por las recomendaciones de amigos, familiares y/o nuestro padres.','¿Has pensado si de verdad, estas haciendo lo que realmente te gusta?  - Technochat Costa Rica',NULL,'a:2:{s:4:\"name\";s:21:\"TechnoChat Costa Rica\";s:2:\"id\";s:15:\"100001534325556\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}','a:2:{s:4:\"data\";a:1:{i:0;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-30T15:32:41+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:16:\"Cristiam Herrera\";s:2:\"id\";s:9:\"776533899\";}s:2:\"id\";s:47:\"214267468618834_514079278637650_515514695160775\";s:10:\"like_count\";i:0;s:7:\"message\";s:15:\"Esta excelente.\";s:10:\"user_likes\";b:0;}}s:6:\"paging\";a:1:{s:4:\"next\";s:244:\"https://graph.facebook.com/214267468618834_514079278637650/comments?limit=500&fields=can_remove,created_time,from,id,like_count,message,message_tags,user_likes,likes,comments&offset=500&__after_id=214267468618834_514079278637650_515514695160775\";}}','214267468618834'),(103,'214267468618834_514976328547945','2013-03-29T01:00:18+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514976328547945\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514976328547945\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://www.theinquirer.es/2013/03/26/richard-stallman-pide-eliminar-ubuntu-del-flisol.html','... Don Richard ataca de nuevo :( !!!',NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQA9HyLKqOKaziMJ&w=154&h=154&url=http%3A%2F%2Fwww.theinquirer.es%2Fwp-content%2Fthemes%2Ftwentyten-theinquirer%2Fimages%2FpicInquirerLogo-es.gif','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-29T01:00:18+0000','www.theinquirer.es','Corporate spending on enterprise mobility is growing exponentially with no apparent ceiling. In a recent iPass survey of enterprises 42 percent believed mobile connectivity costs would likely outpace inflation with an increase of at least five percent over the coming year.','Richard Stallman pide eliminar Ubuntu del FLISOL - The Inquirer ES',NULL,'a:2:{s:4:\"name\";s:12:\"Juan Matías\";s:2:\"id\";s:9:\"659896371\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834'),(104,'214267468618834_514906155221629','2013-03-28T23:26:03+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514906155221629\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514906155221629\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://www.facebook.com/groups/GiruCode/','URGENTE: El próximo 18/04/2013, Computer Society Colombia, cumple 1 año de existencia, y justo hasta esa fecha mi ciclo como Líder de CSCO finaliza, ya que he tomado la decisión de emprender una camino propio con una idea en la cual he estado trabajando desde hace algún tiempo, están todo invitados a acompañarme a este nuevo ciclo en #GiruCode\nhttps://www.facebook.com/groups/GiruCode/',NULL,'https://fbstatic-a.akamaihd.net/rsrc.php/v2/y3/r/0PnClccPFXs.png','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-28T23:26:03+0000',NULL,'Bienvenido al mundo Geek #GiruCode\n\nSitio para discutir todos los aspectos de Desarrollo y diseño de aplicaciones multi-plataforma.: bolsa de empleo, recursos disponibles, eventos tecnológicos, grupos...106 members','#GiruCode',NULL,'a:2:{s:4:\"name\";s:13:\"Angel Kürten\";s:2:\"id\";s:15:\"100001409273153\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834'),(105,'214267468618834_514872995224945','2013-03-28T22:23:40+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514872995224945\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514872995224945\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://msdn.microsoft.com/en-us/library/bb397687.aspx','muy buena guía así \"for Dummies\" como yo sobre expresiones lambda en C#\nhttp://msdn.microsoft.com/en-us/library/bb397687.aspx',NULL,NULL,'a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-28T22:23:40+0000','msdn.microsoft.com','A lambda expression is an anonymous function that you can use to create delegates or expression tree types. By using lambda expressions, you can write local functions that can be passed as arguments or returned as the value of function calls. Lambda expressions are particularly helpful for writing L...','Lambda Expressions (C# Programming Guide)',NULL,'a:2:{s:4:\"name\";s:18:\"Christian Sanabria\";s:2:\"id\";s:10:\"1617739258\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834'),(106,'214267468618834_514222628623315','2013-03-27T14:46:54+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514222628623315\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514222628623315\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://www.redseguridad.com/opinion/articulos/nace-el-primer-cert-para-proteger-las-infraestructuras-criticas','http://www.redseguridad.com/opinion/articulos/nace-el-primer-cert-para-proteger-las-infraestructuras-criticas',NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQBx0jdvUhOpq34A&w=154&h=154&url=http%3A%2F%2Fwww.redseguridad.com%2Fvar%2Fseguridad%2Fstorage%2Fimages%2Fmedia%2Fimagenes%2Frevistas%2F060-red%2F060_control_mandos_gi%2F92248-1-esl-ES%2F060_con','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-27T14:46:54+0000','www.redseguridad.com','Durante estos últimos años, en España ha sido algo habitual que las actuaciones en materia de protección de Infraestructuras Críticas (IC) se hayan encontrado siempre en un plano algo más avanzado respecto al resto de Europa. Iniciativas tales como la aprobación por la Secretaría de Estado de Seguri...','Nace el primer CERT para proteger las infraestructuras críticas',NULL,'a:2:{s:4:\"name\";s:15:\"Randall Barnett\";s:2:\"id\";s:9:\"787936413\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834'),(107,'214267468618834_514221145290130','2013-03-27T14:44:39+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514221145290130\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/514221145290130\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://websecuritydesign.com/el-fbi-espia-a-los-usuarios-de-gmail-y-dropbox/','http://websecuritydesign.com/el-fbi-espia-a-los-usuarios-de-gmail-y-dropbox/',NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQADE4SNRcbqOR51&w=154&h=154&url=http%3A%2F%2Fwebsecuritydesign.com%2Fwp-content%2Fuploads%2F2013%2F03%2F69beffdaa73ef53cc0f9a184b48fd02d_article.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-27T14:44:39+0000','websecuritydesign.com','El FBI quiere aumentar su capacidad de monitorizar en tiempo real, servicios como Gmail, Google Voice y Dropbox, según informaciones de Slate recogidas por El Mundo. El consejero general de la agen...','El FBI espía a los usuarios de Gmail y Dropbox.',NULL,'a:2:{s:4:\"name\";s:15:\"Randall Barnett\";s:2:\"id\";s:9:\"787936413\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834'),(108,'342877735801525_437636379658993','2013-03-31T18:34:39+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/437636379658993\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/437636379658993\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yz/r/StEh3RhPvjk.gif','1','http://www.facebook.com/photo.php?fbid=548525058520631&set=o.342877735801525&type=1&relevant_count=1','Hola gente necesito de asu apoyo estamos en primer lugar con el proyecto DYNAMICS MOVES de la Universidad-privada Ada A Byron, conoce el proyecto en este link http://icperu.azurewebsites.net/video/improvement-students-treatment-motive-disability nos encontramos en el concurso de IMAGINE CUP 2013 TE ADJUNTAMOS LOS PASO PARA QUE REALIZAR LA VOTACION TE NECESITAMOS CHINCHA Y LE PERU..!!','548525058520631','https://fbcdn-photos-e-a.akamaihd.net/hphotos-ak-ash3/58891_548525058520631_1363595373_s.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','photo','2013-03-31T18:34:39+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}',NULL,'342877735801525'),(109,'342877735801525_436986763057288','2013-03-30T16:25:06+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436986763057288\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436986763057288\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yz/r/StEh3RhPvjk.gif','1','http://www.facebook.com/photo.php?fbid=548036145236189&set=o.342877735801525&type=1&relevant_count=1','TE ENSEÑAMOS COMO VOTAR POR EL PROYECTO BETTER WORLD - CONCURSO DE MICROSOFT NACIONAL E INTERNACIONAL TE NECESITAMOS ...!!','548036145236189','https://fbcdn-photos-d-a.akamaihd.net/hphotos-ak-prn1/163503_548036145236189_532653199_s.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','photo','2013-03-30T16:25:06+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}',NULL,'342877735801525'),(110,'342877735801525_436986673057297','2013-03-30T16:24:36+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436986673057297\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436986673057297\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yj/r/v2OnaTyTQZE.gif','1','http://www.youtube.com/watch?feature=player_embedded&v=ZRTCEuFsb7I','Necesito su apoyo para un concurso de Microsoft si le interesa la solución brindada entra al link --> http://icperu.azurewebsites.net/video/project-minimize-bullying y vota por Proyecto Better World - Proyecto para minimizar el acoso escorlar TU VOTO CUENTA TE NECESITAMOS .....!! HELP HELP \n\nhttp://www.youtube.com/watch?feature=player_embedded&v=ZRTCEuFsb7I',NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQBuP5xVk3qSsI7m&w=130&h=130&url=http%3A%2F%2Fi3.ytimg.com%2Fvi%2FZRTCEuFsb7I%2Fmqdefault.jpg%3Ffeature%3Dog','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','video','2013-03-30T16:24:36+0000',NULL,'Imagine Cup 2013','Better World - Proyecto para minimizar el acoso escorlar','http://www.youtube.com/v/ZRTCEuFsb7I?version=3&autohide=1&autoplay=1','a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}',NULL,'342877735801525'),(111,'342877735801525_436956823060282','2013-03-30T15:09:07+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436956823060282\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436956823060282\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://goo.gl/c0Qps','CENCOSUD solicita un Analista Web. Mira la convocatoria, aquí --> http://goo.gl/c0Qps',NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQA0SCbbJbgLbUHn&w=154&h=154&url=http%3A%2F%2Fwww.laborum.pe%2Fofertas%2F521041%2F..%2F..%2Fimg%2Flogo_laborum.png','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-30T15:09:07+0000','www.laborum.pe','Oferta de trabajo para analista web en comercio mayorista (intermediac. , dist.). Laborum Perú.','Laborum Perú – Oferta de Trabajo – Analista Web',NULL,'a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}',NULL,'342877735801525'),(112,'342877735801525_436636893092275','2013-03-29T16:44:12+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436636893092275\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436636893092275\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yz/r/StEh3RhPvjk.gif','1','http://www.facebook.com/photo.php?fbid=469303456473149&set=o.342877735801525&type=1&relevant_count=1','Seguridad Informática 2013\r\n\r\n“Conferencia: ¿Como Averiguar el IP de quien te acosa?”\r\n\r\nFecha: Viernes 29 de Marzo\r\nHora: 20:00 horas\r\n\r\nLugar:\r\nEdiciones Forenses\r\nAv. Agustin de la Rosa Toro #883 Oficina 201\r\nSan Luis\r\n(Altura. Cdra 34 de la Avenida Canada)\r\n\r\nWhatssapp: 991435643\r\nUnete a nosotros en www.facebook.com/inifopenday\r\nTelefonos: (051) 723-0924 (951) 435-6824\r\n\r\nCONSTANCIA:\r\nS/.10.00 Nuevos Soles\r\n\r\nEntidad Bancaria: BANCO DE LA NACIÓN\r\nNro. de Cuenta: 00-015-009950\r\nResponsable / Titular: Instituto Nacional de Investigación Forense.\r\n\r\nINGRESO LIBRE / PREVIO REGISTRO\r\n\r\nReserve su vacante \r\nSolicitando su numero de registro remitiendo su nombre completo y DNI a los correos electrónicos asignados: \r\nsedeforense@hotmail.com, eventos@sedeforense.edu.pe','469303456473149','https://fbcdn-photos-f-a.akamaihd.net/hphotos-ak-frc1/733969_469303456473149_1730529683_s.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','photo','2013-03-29T16:44:12+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:16:\"Analista Forense\";s:2:\"id\";s:15:\"100001804978401\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}',NULL,'342877735801525'),(113,'342877735801525_436603493095615','2013-03-29T15:15:32+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436603493095615\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436603493095615\";}}',NULL,'1',NULL,'## A FULL PREPARANDO TESIS --> HELP DESK (.......) ¿QUE OPINAN?',NULL,NULL,'a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','status','2013-03-29T16:08:27+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}','a:2:{s:4:\"data\";a:1:{i:0;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-29T16:08:27+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:26:\"Brunotec Melgar Sayritupac\";s:2:\"id\";s:15:\"100002730495005\";}s:2:\"id\";s:47:\"342877735801525_436603493095615_436628129759818\";s:10:\"like_count\";i:0;s:7:\"message\";s:39:\"como y donde lo vas a implementar......\";s:10:\"user_likes\";b:0;}}s:6:\"paging\";a:1:{s:4:\"next\";s:244:\"https://graph.facebook.com/342877735801525_436603493095615/comments?limit=500&fields=can_remove,created_time,from,id,like_count,message,message_tags,user_likes,likes,comments&offset=500&__after_id=342877735801525_436603493095615_436628129759818\";}}','342877735801525'),(114,'342877735801525_436332833122681','2013-03-28T23:25:22+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436332833122681\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436332833122681\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://www.facebook.com/groups/GiruCode/','URGENTE: El próximo 18/04/2013, Computer Society Colombia, cumple 1 año de existencia, y justo hasta esa fecha mi ciclo como Líder de CSCO finaliza, ya que he tomado la decisión de emprender una camino propio con una idea en la cual he estado trabajando desde hace algún tiempo, están todo invitados a acompañarme a este nuevo ciclo en #GiruCode\nhttps://www.facebook.com/groups/GiruCode/',NULL,'https://fbstatic-a.akamaihd.net/rsrc.php/v2/y3/r/0PnClccPFXs.png','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-28T23:25:22+0000',NULL,'Bienvenido al mundo Geek #GiruCode\n\nSitio para discutir todos los aspectos de Desarrollo y diseño de aplicaciones multi-plataforma.: bolsa de empleo, recursos disponibles, eventos tecnológicos, grupos...106 members','#GiruCode',NULL,'a:2:{s:4:\"name\";s:13:\"Angel Kürten\";s:2:\"id\";s:15:\"100001409273153\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}',NULL,'342877735801525'),(115,'342877735801525_435534389869192','2013-03-26T22:21:01+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/435534389869192\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/435534389869192\";}}',NULL,'1',NULL,'alguien sabe algun programa para cortar videos \npor fa pasenme el link para descargarlo gracias \n:D',NULL,NULL,'a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','status','2013-03-28T21:06:32+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:22:\"Carlos Blacido Muñico\";s:2:\"id\";s:10:\"1412108323\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}','a:2:{s:4:\"data\";a:34:{i:0;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:22:10+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435534703202494\";s:10:\"like_count\";i:0;s:7:\"message\";s:32:\"que tan profesional lo necesitas\";s:10:\"user_likes\";b:0;}i:1;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:23:07+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435535166535781\";s:10:\"like_count\";i:0;s:7:\"message\";s:26:\"ya que puedes probar desde\";s:10:\"user_likes\";b:0;}i:2;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:23:11+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435535196535778\";s:10:\"like_count\";i:0;s:7:\"message\";s:14:\"adobe premiere\";s:10:\"user_likes\";b:0;}i:3;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:23:40+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435535346535763\";s:10:\"like_count\";i:0;s:7:\"message\";s:10:\"sony vegas\";s:10:\"user_likes\";b:0;}i:4;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:24:02+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435535503202414\";s:10:\"like_count\";i:0;s:7:\"message\";s:8:\"camtasia\";s:10:\"user_likes\";b:0;}i:5;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:24:09+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435535529869078\";s:10:\"like_count\";i:0;s:7:\"message\";s:13:\"o movie maker\";s:10:\"user_likes\";b:0;}i:6;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:26:06+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536139869017\";s:10:\"like_count\";i:0;s:7:\"message\";s:36:\"en lo personal he utilizado camtasia\";s:10:\"user_likes\";b:0;}i:7;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:26:17+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536189869012\";s:10:\"like_count\";i:0;s:7:\"message\";s:32:\"y actualmente está la versió 8\";s:10:\"user_likes\";b:0;}i:8;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:26:42+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:22:\"Carlos Blacido Muñico\";s:2:\"id\";s:10:\"1412108323\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536346535663\";s:10:\"like_count\";i:0;s:7:\"message\";s:52:\"uno simple para unos videos nomas no muy profesional\";s:10:\"user_likes\";b:0;}i:9;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:26:52+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:22:\"Carlos Blacido Muñico\";s:2:\"id\";s:10:\"1412108323\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536389868992\";s:10:\"like_count\";i:0;s:7:\"message\";s:51:\"pero gracias los tendre en cuenta para mas adelante\";s:10:\"user_likes\";b:0;}i:10;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:27:10+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536459868985\";s:10:\"like_count\";i:0;s:7:\"message\";s:45:\"y para activarlo está un tutorial  en youtbu\";s:10:\"user_likes\";b:0;}i:11;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:27:22+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536553202309\";s:10:\"like_count\";i:0;s:7:\"message\";s:32:\"siendo así te ayudará camtasia\";s:10:\"user_likes\";b:0;}i:12;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:27:55+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536776535620\";s:10:\"like_count\";i:0;s:7:\"message\";s:46:\"y te exportará en varios foratos a diferencia\";s:10:\"user_likes\";b:0;}i:13;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:28:02+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536819868949\";s:10:\"like_count\";i:0;s:7:\"message\";s:14:\"de movie maker\";s:10:\"user_likes\";b:0;}i:14;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:28:49+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435536993202265\";s:10:\"like_count\";i:0;s:7:\"message\";s:23:\"lo descargas desde aqui\";s:10:\"user_likes\";b:0;}i:15;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:30:21+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537399868891\";s:10:\"like_count\";i:0;s:7:\"message\";s:54:\"http://www.techsmith.com/download/camtasia/default.asp\";s:10:\"user_likes\";b:0;}i:16;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:30:32+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537473202217\";s:10:\"like_count\";i:0;s:7:\"message\";s:28:\"solo asigna cualquier correo\";s:10:\"user_likes\";b:0;}i:17;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:30:36+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537513202213\";s:10:\"like_count\";i:0;s:7:\"message\";s:7:\"y listo\";s:10:\"user_likes\";b:0;}i:18;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:31:23+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537733202191\";s:10:\"like_count\";i:0;s:7:\"message\";s:105:\"para activarlo hay un video en youtube que te dará paso a paso la activación utilizando un localhost...\";s:10:\"user_likes\";b:0;}i:19;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:31:36+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537783202186\";s:10:\"like_count\";i:0;s:7:\"message\";s:42:\"que es el que estoy utilizando actualmente\";s:10:\"user_likes\";b:0;}i:20;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:31:44+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537823202182\";s:10:\"like_count\";i:0;s:7:\"message\";s:13:\"y para usarlo\";s:10:\"user_likes\";b:0;}i:21;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:31:55+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537866535511\";s:10:\"like_count\";i:0;s:7:\"message\";s:11:\"es sencillo\";s:10:\"user_likes\";b:0;}i:22;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:32:01+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:22:\"Carlos Blacido Muñico\";s:2:\"id\";s:10:\"1412108323\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537893202175\";s:10:\"like_count\";i:0;s:7:\"message\";s:3:\"aya\";s:10:\"user_likes\";b:0;}i:23;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:32:11+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:22:\"Carlos Blacido Muñico\";s:2:\"id\";s:10:\"1412108323\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435537929868838\";s:10:\"like_count\";i:0;s:7:\"message\";s:22:\"si eso si lo se man :D\";s:10:\"user_likes\";b:0;}i:24;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:32:23+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435538003202164\";s:10:\"like_count\";i:0;s:7:\"message\";s:20:\"al abrir el programa\";s:10:\"user_likes\";b:0;}i:25;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:32:32+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435538043202160\";s:10:\"like_count\";i:0;s:7:\"message\";s:17:\"está el tutorial\";s:10:\"user_likes\";b:0;}i:26;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:32:35+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435538063202158\";s:10:\"like_count\";i:0;s:7:\"message\";s:13:\"te encantará\";s:10:\"user_likes\";b:0;}i:27;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:32:46+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435538186535479\";s:10:\"like_count\";i:0;s:7:\"message\";s:34:\"con el he hecho mis primeros pasos\";s:10:\"user_likes\";b:0;}i:28;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:32:56+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435538316535466\";s:10:\"like_count\";i:0;s:7:\"message\";s:10:\"en youtube\";s:10:\"user_likes\";b:0;}i:29;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:38:58+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435539986535299\";s:10:\"like_count\";i:0;s:7:\"message\";s:50:\"ejemplo http://www.youtube.com/watch?v=m3TozXmdCgE\";s:10:\"user_likes\";b:0;}i:30;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:39:28+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435540116535286\";s:10:\"like_count\";i:0;s:7:\"message\";s:48:\"aqui utilizo prezí y la edición en camtasia...\";s:10:\"user_likes\";b:0;}i:31;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:40:23+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435540249868606\";s:10:\"like_count\";i:0;s:7:\"message\";s:49:\"checa y ahi nos cuentas tu experiencia utilizando\";s:10:\"user_likes\";b:0;}i:32;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-26T22:40:26+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:14:\"Vic Man Ur Mar\";s:2:\"id\";s:15:\"100000644480947\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_435540266535271\";s:10:\"like_count\";i:0;s:7:\"message\";s:8:\"camtasia\";s:10:\"user_likes\";b:0;}i:33;a:7:{s:10:\"can_remove\";b:1;s:12:\"created_time\";s:24:\"2013-03-28T21:06:32+0000\";s:4:\"from\";a:2:{s:4:\"name\";s:19:\"Vazagho Programador\";s:2:\"id\";s:15:\"100000263386058\";}s:2:\"id\";s:47:\"342877735801525_435534389869192_436280783127886\";s:10:\"like_count\";i:0;s:7:\"message\";s:180:\"si solo vas a dividir un video o cortarlo usa el bolisoft vide splitter y el joiner para unirlo, es demasiado sencillo si solo vas a dividir ciertas partes, ojo bajalo con su crack\";s:10:\"user_likes\";b:0;}}s:6:\"paging\";a:1:{s:4:\"next\";s:244:\"https://graph.facebook.com/342877735801525_435534389869192/comments?limit=500&fields=can_remove,created_time,from,id,like_count,message,message_tags,user_likes,likes,comments&offset=500&__after_id=342877735801525_435534389869192_436280783127886\";}}','342877735801525'),(116,'342877735801525_436271576462140','2013-03-28T20:27:55+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436271576462140\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/342877735801525/posts/436271576462140\";}}','https://fbstatic-a.akamaihd.net/rsrc.php/v2/yD/r/aS8ecmYRys0.gif','1','http://bit.ly/14vVQ7f','La nube: las empresas están aumentando su confianza en el uso de sistemas híbridos http://bit.ly/14vVQ7f',NULL,'https://fbexternal-a.akamaihd.net/safe_image.php?d=AQA5Jc7j-_P6IGpQ&w=154&h=154&url=http%3A%2F%2Fblogs.sap.com%2Flatinamerica%2Ffiles%2F2012%2F07%2F273910_l_srgb_s_gl-300x232.jpg','a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','link','2013-03-28T20:27:55+0000','blogs.sap.com','El resultado de una encuesta realizada por North Bridge Venture Partners afirma que el 50% de las empresas confían en las soluciones basadas en la nube. Entre los 785 expertos consultados en el son...','Cloud Computing: Aumenta la confianza de las empresas en el uso de sistemas híbridos.',NULL,'a:2:{s:4:\"name\";s:18:\"Jairzinho Gonzales\";s:2:\"id\";s:15:\"100000894258993\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:68:\"Ingenieros Computacion y Sistemas de Perú :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"342877735801525\";}}}',NULL,'342877735801525'),(117,'214267468618834_516152481763663','2013-04-01T05:52:35+0000','a:2:{i:0;a:2:{s:4:\"name\";s:7:\"Comment\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/516152481763663\";}i:1;a:2:{s:4:\"name\";s:4:\"Like\";s:4:\"link\";s:61:\"http://www.facebook.com/214267468618834/posts/516152481763663\";}}',NULL,'1',NULL,'testing sdk api',NULL,NULL,'a:1:{s:5:\"value\";s:0:\"\";}','ineligible','no timeline unit for this post','status','2013-04-01T05:52:35+0000',NULL,NULL,NULL,NULL,'a:2:{s:4:\"name\";s:10:\"James Jara\";s:2:\"id\";s:10:\"1483154807\";}','a:1:{s:4:\"data\";a:1:{i:0;a:3:{s:4:\"name\";s:73:\"Ingenieros Computacion y Sistemas de Costa Rica :: Computer Society Group\";s:7:\"version\";i:1;s:2:\"id\";s:15:\"214267468618834\";}}}',NULL,'214267468618834');
/*!40000 ALTER TABLE `fb_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `idfeedback` int(11) NOT NULL auto_increment,
  `Titulo` varchar(245) default NULL,
  `Descripcion` text,
  `Url` varchar(245) default NULL,
  `autor` int(11) default NULL,
  `paises_target_blogs` varchar(245) default NULL,
  `paises_target_fbg` varchar(245) default NULL,
  `fecha` timestamp NULL default NULL,
  `ejecutado` int(11) default '0',
  PRIMARY KEY  (`idfeedback`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `noticias` (
  `idnoticias` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`idnoticias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticias`
--

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
/*!40000 ALTER TABLE `noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owners`
--

DROP TABLE IF EXISTS `owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `owners` (
  `id_owners` int(11) NOT NULL auto_increment,
  `Correo Electronico` varchar(245) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `activated` varchar(245) default NULL,
  `profile` text,
  `role` int(11) default NULL,
  `tipo` int(11) default NULL,
  PRIMARY KEY  (`id_owners`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owners`
--

LOCK TABLES `owners` WRITE;
/*!40000 ALTER TABLE `owners` DISABLE KEYS */;
INSERT INTO `owners` VALUES (49,'jamesjara@gmail.com','ad','Y',NULL,NULL,NULL),(50,'jgonzales.chincha@gmail.com','test','Y',NULL,NULL,NULL);
/*!40000 ALTER TABLE `owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paises` (
  `idpaises` int(11) NOT NULL auto_increment,
  `nombre` varchar(245) NOT NULL,
  `admin` int(11) default NULL,
  PRIMARY KEY  (`idpaises`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paises`
--

LOCK TABLES `paises` WRITE;
/*!40000 ALTER TABLE `paises` DISABLE KEYS */;
INSERT INTO `paises` VALUES (1,'CS CR',49),(2,'CS PERU',50);
/*!40000 ALTER TABLE `paises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'cslatam'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-01  8:59:08
