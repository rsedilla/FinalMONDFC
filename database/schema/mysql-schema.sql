/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cell_group_church_attender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cell_group_church_attender` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `cell_group_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cell_group_church_attender_church_attender_id_foreign` (`church_attender_id`),
  KEY `cell_group_church_attender_cell_group_id_foreign` (`cell_group_id`),
  CONSTRAINT `cell_group_church_attender_cell_group_id_foreign` FOREIGN KEY (`cell_group_id`) REFERENCES `cell_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cell_group_church_attender_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cell_group_lesson_completions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cell_group_lesson_completions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `lesson_number` int NOT NULL,
  `completion_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cell_group_lesson_completions_church_attender_id_foreign` (`church_attender_id`),
  CONSTRAINT `cell_group_lesson_completions_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cell_group_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cell_group_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cell_group_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cell_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cell_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cell_group_type_id` bigint unsigned NOT NULL,
  `meeting_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meeting_day` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cell_groups_cell_group_type_id_foreign` (`cell_group_type_id`),
  CONSTRAINT `cell_groups_cell_group_type_id_foreign` FOREIGN KEY (`cell_group_type_id`) REFERENCES `cell_group_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cell_leaders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cell_leaders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `cell_group_id` bigint unsigned NOT NULL,
  `training_progress_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cell_leaders_church_attender_id_foreign` (`church_attender_id`),
  KEY `cell_leaders_cell_group_id_foreign` (`cell_group_id`),
  KEY `cell_leaders_training_progress_id_foreign` (`training_progress_id`),
  CONSTRAINT `cell_leaders_cell_group_id_foreign` FOREIGN KEY (`cell_group_id`) REFERENCES `cell_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cell_leaders_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cell_leaders_training_progress_id_foreign` FOREIGN KEY (`training_progress_id`) REFERENCES `training_progress` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cell_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cell_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `cell_group_id` bigint unsigned NOT NULL,
  `training_progress_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cell_members_church_attender_id_unique` (`church_attender_id`),
  KEY `cell_members_cell_group_id_foreign` (`cell_group_id`),
  KEY `cell_members_training_progress_id_foreign` (`training_progress_id`),
  CONSTRAINT `cell_members_cell_group_id_foreign` FOREIGN KEY (`cell_group_id`) REFERENCES `cell_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cell_members_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cell_members_training_progress_id_foreign` FOREIGN KEY (`training_progress_id`) REFERENCES `training_progress` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cgs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cell_group_id` bigint unsigned NOT NULL,
  `session_date` datetime NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cgs_cell_group_id_foreign` (`cell_group_id`),
  CONSTRAINT `cgs_cell_group_id_foreign` FOREIGN KEY (`cell_group_id`) REFERENCES `cell_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cgsa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cgsa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cgs_id` bigint unsigned NOT NULL,
  `church_attender_id` bigint unsigned NOT NULL,
  `attended_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cgsa_cgs_id_fk` (`cgs_id`),
  KEY `cgsa_attender_id_fk` (`church_attender_id`),
  CONSTRAINT `cgsa_attender_id_fk` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cgsa_cgs_id_fk` FOREIGN KEY (`cgs_id`) REFERENCES `cgs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `church_attenders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `church_attenders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_media_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `network` enum('mens','womens') COLLATE utf8mb4_unicode_ci NOT NULL,
  `present_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permanent_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civil_status_id` bigint unsigned DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `church_attenders_email_unique` (`email`),
  KEY `church_attenders_civil_status_id_foreign` (`civil_status_id`),
  CONSTRAINT `church_attenders_civil_status_id_foreign` FOREIGN KEY (`civil_status_id`) REFERENCES `civil_statuses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `civil_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `civil_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `civil_statuses_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `g12_leader_cell_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `g12_leader_cell_leader` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `g12_leader_id` bigint unsigned NOT NULL,
  `cell_leader_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `g12_leader_cell_leader_g12_leader_id_foreign` (`g12_leader_id`),
  KEY `g12_leader_cell_leader_cell_leader_id_foreign` (`cell_leader_id`),
  CONSTRAINT `g12_leader_cell_leader_cell_leader_id_foreign` FOREIGN KEY (`cell_leader_id`) REFERENCES `cell_leaders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `g12_leader_cell_leader_g12_leader_id_foreign` FOREIGN KEY (`g12_leader_id`) REFERENCES `g12_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `g12_leader_cell_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `g12_leader_cell_member` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `g12_leader_id` bigint unsigned NOT NULL,
  `cell_member_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `g12_leader_cell_member_g12_leader_id_foreign` (`g12_leader_id`),
  KEY `g12_leader_cell_member_cell_member_id_foreign` (`cell_member_id`),
  CONSTRAINT `g12_leader_cell_member_cell_member_id_foreign` FOREIGN KEY (`cell_member_id`) REFERENCES `cell_members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `g12_leader_cell_member_g12_leader_id_foreign` FOREIGN KEY (`g12_leader_id`) REFERENCES `g12_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `g12_leader_church_attender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `g12_leader_church_attender` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `g12_leader_id` bigint unsigned NOT NULL,
  `church_attender_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `g12_leader_church_attender_g12_leader_id_foreign` (`g12_leader_id`),
  KEY `g12_leader_church_attender_church_attender_id_foreign` (`church_attender_id`),
  CONSTRAINT `g12_leader_church_attender_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `g12_leader_church_attender_g12_leader_id_foreign` FOREIGN KEY (`g12_leader_id`) REFERENCES `g12_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `g12_leaders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `g12_leaders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `cell_group_id` bigint unsigned NOT NULL,
  `training_progress_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `g12_leaders_church_attender_id_unique` (`church_attender_id`),
  KEY `g12_leaders_cell_group_id_foreign` (`cell_group_id`),
  KEY `g12_leaders_training_progress_id_foreign` (`training_progress_id`),
  CONSTRAINT `g12_leaders_cell_group_id_foreign` FOREIGN KEY (`cell_group_id`) REFERENCES `cell_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `g12_leaders_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `g12_leaders_training_progress_id_foreign` FOREIGN KEY (`training_progress_id`) REFERENCES `training_progress` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `network_leader_cell_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `network_leader_cell_leader` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `network_leader_id` bigint unsigned NOT NULL,
  `cell_leader_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `network_leader_cell_leader_cell_leader_id_unique` (`cell_leader_id`),
  KEY `network_leader_cell_leader_network_leader_id_foreign` (`network_leader_id`),
  CONSTRAINT `network_leader_cell_leader_cell_leader_id_foreign` FOREIGN KEY (`cell_leader_id`) REFERENCES `cell_leaders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `network_leader_cell_leader_network_leader_id_foreign` FOREIGN KEY (`network_leader_id`) REFERENCES `network_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `network_leader_cell_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `network_leader_cell_member` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `network_leader_id` bigint unsigned NOT NULL,
  `cell_member_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `network_leader_cell_member_network_leader_id_foreign` (`network_leader_id`),
  KEY `network_leader_cell_member_cell_member_id_foreign` (`cell_member_id`),
  CONSTRAINT `network_leader_cell_member_cell_member_id_foreign` FOREIGN KEY (`cell_member_id`) REFERENCES `cell_members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `network_leader_cell_member_network_leader_id_foreign` FOREIGN KEY (`network_leader_id`) REFERENCES `network_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `network_leader_church_attender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `network_leader_church_attender` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `network_leader_id` bigint unsigned NOT NULL,
  `church_attender_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `network_leader_church_attender_network_leader_id_foreign` (`network_leader_id`),
  KEY `network_leader_church_attender_church_attender_id_foreign` (`church_attender_id`),
  CONSTRAINT `network_leader_church_attender_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `network_leader_church_attender_network_leader_id_foreign` FOREIGN KEY (`network_leader_id`) REFERENCES `network_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `network_leader_emerging_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `network_leader_emerging_leader` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `network_leader_id` bigint unsigned NOT NULL,
  `church_attender_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `network_leader_emerging_leader_church_attender_id_unique` (`church_attender_id`),
  KEY `network_leader_emerging_leader_network_leader_id_foreign` (`network_leader_id`),
  CONSTRAINT `network_leader_emerging_leader_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `network_leader_emerging_leader_network_leader_id_foreign` FOREIGN KEY (`network_leader_id`) REFERENCES `network_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `network_leader_g12_leader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `network_leader_g12_leader` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `network_leader_id` bigint unsigned NOT NULL,
  `g12_leader_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `network_leader_g12_leader_network_leader_id_foreign` (`network_leader_id`),
  KEY `network_leader_g12_leader_g12_leader_id_foreign` (`g12_leader_id`),
  CONSTRAINT `network_leader_g12_leader_g12_leader_id_foreign` FOREIGN KEY (`g12_leader_id`) REFERENCES `g12_leaders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `network_leader_g12_leader_network_leader_id_foreign` FOREIGN KEY (`network_leader_id`) REFERENCES `network_leaders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `network_leaders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `network_leaders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `network` enum('mens','womens') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `network_leaders_church_attender_id_foreign` (`church_attender_id`),
  CONSTRAINT `network_leaders_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ssas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ssas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_date` datetime NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ssasa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ssasa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ssas_id` bigint unsigned NOT NULL,
  `church_attender_id` bigint unsigned NOT NULL,
  `attended_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ssasa_ssas_id_fk` (`ssas_id`),
  KEY `ssasa_attender_id_fk` (`church_attender_id`),
  CONSTRAINT `ssasa_attender_id_fk` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ssasa_ssas_id_fk` FOREIGN KEY (`ssas_id`) REFERENCES `ssas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sunday_service_completions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sunday_service_completions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `service_number` int NOT NULL,
  `attendance_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sunday_service_completions_church_attender_id_foreign` (`church_attender_id`),
  CONSTRAINT `sunday_service_completions_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `suynl_lesson_completions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suynl_lesson_completions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `lesson_number` tinyint unsigned NOT NULL,
  `completion_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suynl_lesson_completions_church_attender_id_lesson_number_unique` (`church_attender_id`,`lesson_number`),
  CONSTRAINT `suynl_lesson_completions_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `training_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `church_attender_id` bigint unsigned NOT NULL,
  `training_progress_type_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `training_progress_church_attender_id_foreign` (`church_attender_id`),
  KEY `training_progress_training_progress_type_id_foreign` (`training_progress_type_id`),
  CONSTRAINT `training_progress_church_attender_id_foreign` FOREIGN KEY (`church_attender_id`) REFERENCES `church_attenders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `training_progress_training_progress_type_id_foreign` FOREIGN KEY (`training_progress_type_id`) REFERENCES `training_progress_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `training_progress_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_progress_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `training_progress_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_08_30_082204_create_church_attender_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_08_31_000000_create_training_progress_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_08_31_092418_create_cell_group_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_08_31_093507_create_cell_groups_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_08_31_094201_create_cell_group_church_attender_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_08_31_094726_create_cell_leaders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_08_31_100027_create_cell_members_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_08_31_110000_create_g12_leaders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_08_31_110100_create_g12_leader_cell_leader_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_08_31_120000_create_g12_leader_cell_member_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_08_31_123000_create_g12_leader_church_attender_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_09_01_140000_create_network_leaders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_09_01_141000_create_network_leader_g12_leader_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_09_01_142000_create_network_leader_cell_leader_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_09_01_143000_create_network_leader_emerging_leader_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_09_01_144000_create_network_leader_cell_member_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_09_01_145000_create_network_leader_church_attender_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_09_01_150000_create_cell_group_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_09_01_151000_create_cell_group_session_attendance_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_09_01_160000_create_sunday_service_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_09_01_161000_create_sunday_service_session_attendance_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_09_01_180000_create_training_progress_types_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_09_01_190000_create_suynl_lesson_completions_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_09_01_200000_create_roles_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_09_01_200100_create_role_user_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_09_01_210000_add_birthday_to_church_attenders_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_09_01_220000_create_civil_statuses_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_09_02_053621_create_sunday_service_completions_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_09_02_060000_create_cell_group_lesson_completions_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_09_02_100000_populate_training_progress_from_existing_completions',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_09_02_120000_normalize_training_progress_table',10);
