/*
SQLyog Community v11.52 (64 bit)
MySQL - 5.6.14-log : Database - db_progmob_kepanitian
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_progmob_kepanitian` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_progmob_kepanitian`;

/*Table structure for table `tbl_admin` */

DROP TABLE IF EXISTS `tbl_admin`;

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_admin` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_admin` */

/*Table structure for table `tbl_kegiatan` */

DROP TABLE IF EXISTS `tbl_kegiatan`;

CREATE TABLE `tbl_kegiatan` (
  `id_kegiatan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kegiatan` varchar(50) DEFAULT NULL,
  `tgl_kegiatan` date DEFAULT NULL,
  `tgl_rapat_perdana` date DEFAULT NULL,
  `deskripsi` text,
  `foto_kegiatan` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_kegiatan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_kegiatan` */

insert  into `tbl_kegiatan`(`id_kegiatan`,`nama_kegiatan`,`tgl_kegiatan`,`tgl_rapat_perdana`,`deskripsi`,`foto_kegiatan`,`status`) values (1,'IT-ESEGA 2018','2018-09-16','2018-06-01','kepanitian seru cok',NULL,1),(2,'SPORTI 2018','2018-12-01','2018-11-10','sport competion is TI university of udayana',NULL,1),(3,'ITVERSERY','2018-07-01','2018-04-01','HUT TI',NULL,1),(6,'ITCC 2018','2018-11-11','2018-05-01','wkwkkwkwkwk',NULL,0),(7,'HEHEHE','2018-11-11','2018-05-01','wkwkkwkwkwk',NULL,0),(8,'zzzzzzzzzz','2018-11-11','2018-05-01','wkwkkwkwkwk',NULL,0),(9,'zzzzzzzzzz','2018-11-11','2018-05-01','zzzzzzzzzzzzzzzzzzzzzz','testqtegtstetstte.jpg',0);

/*Table structure for table `tbl_kepanitiaan` */

DROP TABLE IF EXISTS `tbl_kepanitiaan`;

CREATE TABLE `tbl_kepanitiaan` (
  `id_kepanitiaan` int(11) NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `id_sie_kegiatan` int(11) DEFAULT NULL,
  `tgl_daftar` date DEFAULT NULL,
  `alasan` text,
  `status_panitia` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_kepanitiaan`),
  KEY `id_mahasiswa` (`id_mahasiswa`),
  KEY `id_sie_kegiatan` (`id_sie_kegiatan`),
  CONSTRAINT `tbl_kepanitiaan_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tbl_member` (`id_mahasiswa`),
  CONSTRAINT `tbl_kepanitiaan_ibfk_2` FOREIGN KEY (`id_sie_kegiatan`) REFERENCES `tbl_sie_kegiatan` (`id_sie_kegiatan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_kepanitiaan` */

insert  into `tbl_kepanitiaan`(`id_kepanitiaan`,`id_mahasiswa`,`id_sie_kegiatan`,`tgl_daftar`,`alasan`,`status_panitia`) values (1,1,1,'2018-11-10','pingin ikut',1),(2,1,3,'2018-11-12','pengen aja jje. serius nee',1);

/*Table structure for table `tbl_member` */

DROP TABLE IF EXISTS `tbl_member`;

CREATE TABLE `tbl_member` (
  `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT,
  `nim` int(11) DEFAULT NULL,
  `nama_mahasiswa` varchar(50) DEFAULT NULL,
  `angkatan` int(11) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_mahasiswa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_member` */

insert  into `tbl_member`(`id_mahasiswa`,`nim`,`nama_mahasiswa`,`angkatan`,`username`,`password`,`status_active`) values (1,1605551110,'Krisna',2016,'krisna','krisna',1),(2,1605551123,'rika hari',16,'rika','123',0);

/*Table structure for table `tbl_sie_kegiatan` */

DROP TABLE IF EXISTS `tbl_sie_kegiatan`;

CREATE TABLE `tbl_sie_kegiatan` (
  `id_sie_kegiatan` int(11) NOT NULL AUTO_INCREMENT,
  `id_kegiatan` int(11) DEFAULT NULL,
  `sie` varchar(30) DEFAULT NULL,
  `job_desc` text,
  `kuota` int(11) DEFAULT NULL,
  `nama_koor` varchar(30) DEFAULT NULL,
  `id_line_koor` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_sie_kegiatan`),
  KEY `id_kegiatan` (`id_kegiatan`),
  KEY `id_sie` (`sie`),
  CONSTRAINT `tbl_sie_kegiatan_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `tbl_kegiatan` (`id_kegiatan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_sie_kegiatan` */

insert  into `tbl_sie_kegiatan`(`id_sie_kegiatan`,`id_kegiatan`,`sie`,`job_desc`,`kuota`,`nama_koor`,`id_line_koor`) values (1,1,'lomba','banyak',8,'Boy','@boytod'),(2,1,'kamper','satpam sama pegawai gudang',10,'Made','@mademade'),(3,2,'kesehatan','mengobati',5,'Putu','@Pututut'),(4,2,'Kesekretariatan','nyatet nyatet dah pokoknya',3,'Muslimah','@muslimah');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
