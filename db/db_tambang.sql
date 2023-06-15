-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_tambang
CREATE DATABASE IF NOT EXISTS `db_tambang` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_tambang`;

-- Dumping structure for table db_tambang.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned NOT NULL,
  `no_telp` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__users` (`id_user`),
  CONSTRAINT `FK__users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.kendaraan
CREATE TABLE IF NOT EXISTS `kendaraan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `no_polisi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe_angkut` enum('orang','barang') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kepemilikan` enum('perusahaan','sewa') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_group_id` int DEFAULT NULL,
  `is_active` enum('1','0') COLLATE utf8mb4_general_ci DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.pegawai
CREATE TABLE IF NOT EXISTS `pegawai` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int unsigned DEFAULT NULL,
  `no_tlp` int DEFAULT NULL,
  `unit_kerja` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pegawai_users` (`id_user`),
  KEY `FK_pegawai_ref_unit` (`unit_kerja`),
  CONSTRAINT `FK_pegawai_ref_unit` FOREIGN KEY (`unit_kerja`) REFERENCES `ref_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pegawai_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.pemesanan
CREATE TABLE IF NOT EXISTS `pemesanan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_kendaraan` int unsigned NOT NULL,
  `id_admin` int unsigned NOT NULL,
  `id_pegawai` int unsigned NOT NULL,
  `id_atasan` int unsigned NOT NULL,
  `tgl_order` timestamp NULL DEFAULT NULL,
  `mulai` timestamp NULL DEFAULT NULL,
  `selesai` timestamp NULL DEFAULT NULL,
  `kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tujuan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_order` set('1','0','404') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '404',
  PRIMARY KEY (`id`),
  KEY `FK_pemesanan_admin` (`id_admin`),
  KEY `FK_pemesanan_kendaraan` (`id_kendaraan`),
  KEY `FK_pemesanan_pegawai` (`id_pegawai`),
  KEY `FK_pemesanan_pool` (`id_atasan`),
  CONSTRAINT `FK_pemesanan_admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pemesanan_kendaraan` FOREIGN KEY (`id_kendaraan`) REFERENCES `kendaraan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pemesanan_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pemesanan_pool` FOREIGN KEY (`id_atasan`) REFERENCES `pool` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.pool
CREATE TABLE IF NOT EXISTS `pool` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_users` int unsigned NOT NULL,
  `region` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__users__atasan` (`id_users`),
  KEY `FK__ref_unit_unit` (`region`),
  CONSTRAINT `FK__ref_unit_unit` FOREIGN KEY (`region`) REFERENCES `ref_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__users__atasan` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.ref_unit
CREATE TABLE IF NOT EXISTS `ref_unit` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `region` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `region` int unsigned NOT NULL,
  `user_group_id` int DEFAULT NULL,
  `is_active` enum('1','0') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_users_ref_unit` (`region`),
  CONSTRAINT `FK_users_ref_unit` FOREIGN KEY (`region`) REFERENCES `ref_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table db_tambang.users_group
CREATE TABLE IF NOT EXISTS `users_group` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `desc` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_general_ci DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
