  -- --------------------------------------------------------
  -- Host:                         127.0.0.1
  -- Server version:               8.4.3 - MySQL Community Server - GPL
  -- Server OS:                    Win64
  -- HeidiSQL Version:             12.8.0.6908
  -- --------------------------------------------------------

  /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
  /*!40101 SET NAMES utf8 */;
  /*!50503 SET NAMES utf8mb4 */;
  /*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
  /*!40103 SET TIME_ZONE='+00:00' */;
  /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
  /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
  /*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


  -- Dumping database structure for sipadi
  CREATE DATABASE IF NOT EXISTS `sipadi` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
  USE `sipadi`;

  -- Dumping structure for table sipadi.alokasi_bibit
  CREATE TABLE IF NOT EXISTS `alokasi_bibit` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `id_petani` int unsigned NOT NULL,
    `musim_tanam` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contoh: 2024-Ganjil',
    `jumlah_bibit` decimal(10,2) NOT NULL COMMENT 'Satuan: kg',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_bibit_petani` (`id_petani`),
    CONSTRAINT `fk_bibit_petani` FOREIGN KEY (`id_petani`) REFERENCES `anggota_petani` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.alokasi_bibits
  CREATE TABLE IF NOT EXISTS `alokasi_bibits` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `id_petani` bigint unsigned NOT NULL,
    `musim_tanam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `jumlah_bibit` double NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `alokasi_bibits_id_petani_foreign` (`id_petani`),
    CONSTRAINT `alokasi_bibits_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `anggota_petanis` (`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.anggota_petani
  CREATE TABLE IF NOT EXISTS `anggota_petani` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `nama_petani` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `luas_lahan` decimal(10,2) NOT NULL COMMENT 'Satuan: tumbak',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.anggota_petanis
  CREATE TABLE IF NOT EXISTS `anggota_petanis` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `nama_petani` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `luas_lahan` double NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.arus_kas
  CREATE TABLE IF NOT EXISTS `arus_kas` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `tanggal` date NOT NULL,
    `musim_tanam` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `jenis_transaksi` enum('pemasukan','pengeluaran') COLLATE utf8mb4_unicode_ci NOT NULL,
    `nominal` double NOT NULL,
    `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.cache
  CREATE TABLE IF NOT EXISTS `cache` (
    `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_expiration_index` (`expiration`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.cache_locks
  CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `expiration` int NOT NULL,
    PRIMARY KEY (`key`),
    KEY `cache_locks_expiration_index` (`expiration`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.data_panen
  CREATE TABLE IF NOT EXISTS `data_panen` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `id_petani` int unsigned NOT NULL,
    `musim_tanam` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `total_panen` decimal(10,2) NOT NULL COMMENT 'Satuan: kg',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_panen_petani` (`id_petani`),
    CONSTRAINT `fk_panen_petani` FOREIGN KEY (`id_petani`) REFERENCES `anggota_petani` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.data_panens
  CREATE TABLE IF NOT EXISTS `data_panens` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `id_petani` bigint unsigned NOT NULL,
    `musim_tanam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `total_panen` double NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `data_panens_id_petani_foreign` (`id_petani`),
    CONSTRAINT `data_panens_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `anggota_petanis` (`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.failed_jobs
  CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.jobs
  CREATE TABLE IF NOT EXISTS `jobs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `attempts` tinyint unsigned NOT NULL,
    `reserved_at` int unsigned DEFAULT NULL,
    `available_at` int unsigned NOT NULL,
    `created_at` int unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.job_batches
  CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `total_jobs` int NOT NULL,
    `pending_jobs` int NOT NULL,
    `failed_jobs` int NOT NULL,
    `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `options` mediumtext COLLATE utf8mb4_unicode_ci,
    `cancelled_at` int DEFAULT NULL,
    `created_at` int NOT NULL,
    `finished_at` int DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.laporan_operasional
  CREATE TABLE IF NOT EXISTS `laporan_operasional` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `musim_tanam` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `status_finalisasi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = draft, 1 = final',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `musim_tanam` (`musim_tanam`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.laporan_operasionals
  CREATE TABLE IF NOT EXISTS `laporan_operasionals` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `musim_tanam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `status_finalisasi` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.migrations
  CREATE TABLE IF NOT EXISTS `migrations` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `batch` int NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.password_reset_tokens
  CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.pelanggan
  CREATE TABLE IF NOT EXISTS `pelanggan` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `kontak` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.pelanggans
  CREATE TABLE IF NOT EXISTS `pelanggans` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `kontak` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.pembagian_hasil
  CREATE TABLE IF NOT EXISTS `pembagian_hasil` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `id_petani` int unsigned NOT NULL,
    `musim_tanam` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `proporsi_panen` decimal(8,4) NOT NULL COMMENT 'Persentase kontribusi panen petani (0-100)',
    `alokasi_keuntungan` decimal(15,2) NOT NULL COMMENT 'Nominal rupiah yang diterima petani',
    `total_keuntungan` decimal(15,2) NOT NULL COMMENT 'Total keuntungan kelompok periode tersebut',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_petani_musim` (`id_petani`,`musim_tanam`),
    CONSTRAINT `fk_bagihsil_petani` FOREIGN KEY (`id_petani`) REFERENCES `anggota_petani` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.pembagian_hasils
  CREATE TABLE IF NOT EXISTS `pembagian_hasils` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `id_petani` bigint unsigned NOT NULL,
    `musim_tanam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `proporsi_panen` double NOT NULL,
    `alokasi_keuntungan` double NOT NULL,
    `total_keuntungan` double NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `pembagian_hasils_id_petani_foreign` (`id_petani`),
    CONSTRAINT `pembagian_hasils_id_petani_foreign` FOREIGN KEY (`id_petani`) REFERENCES `anggota_petanis` (`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.pengguna
  CREATE TABLE IF NOT EXISTS `pengguna` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hashed dengan bcrypt',
    `role` enum('ketua','staf_produksi','staf_penjualan','staf_keuangan') COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.penggunas
  CREATE TABLE IF NOT EXISTS `penggunas` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `role` enum('ketua','staf_produksi','staf_penjualan','staf_keuangan') COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `penggunas_username_unique` (`username`)
  ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.sessions
  CREATE TABLE IF NOT EXISTS `sessions` (
    `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_id` bigint unsigned DEFAULT NULL,
    `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `user_agent` text COLLATE utf8mb4_unicode_ci,
    `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `last_activity` int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.stok_beras
  CREATE TABLE IF NOT EXISTS `stok_beras` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `ketersediaan_stok` double NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.transaksi
  CREATE TABLE IF NOT EXISTS `transaksi` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `id_pelanggan` int unsigned NOT NULL,
    `tanggal_pesanan` date NOT NULL,
    `jumlah_pesanan` int unsigned NOT NULL COMMENT 'Satuan: kg',
    `status_pesanan` enum('Menunggu','Selesai','Dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_transaksi_pelanggan` (`id_pelanggan`),
    CONSTRAINT `fk_transaksi_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.transaksis
  CREATE TABLE IF NOT EXISTS `transaksis` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `id_pelanggan` bigint unsigned NOT NULL,
    `tanggal_pesanan` date NOT NULL,
    `jumlah_pesanan` int NOT NULL,
    `status_pesanan` enum('Menunggu','Selesai','Dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `transaksis_id_pelanggan_foreign` (`id_pelanggan`),
    CONSTRAINT `transaksis_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggans` (`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  -- Dumping structure for table sipadi.users
  CREATE TABLE IF NOT EXISTS `users` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Data exporting was unselected.

  /*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
  /*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
  /*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
