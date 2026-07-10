-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: zakat
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `attribute_changes` json DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agents`
--

DROP TABLE IF EXISTS `agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `area_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coverage_district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coverage_upazila` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `onboarding_status` enum('pending','trained','active','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `capacity_score` int NOT NULL DEFAULT '100',
  `active_cases_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agents_user_id_foreign` (`user_id`),
  KEY `agents_branch_id_foreign` (`branch_id`),
  CONSTRAINT `agents_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `agents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agents`
--

LOCK TABLES `agents` WRITE;
/*!40000 ALTER TABLE `agents` DISABLE KEYS */;
/*!40000 ALTER TABLE `agents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_requests`
--

DROP TABLE IF EXISTS `ai_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prompt_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `token_usage` int NOT NULL DEFAULT '0',
  `cost_estimate` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `status` enum('pending','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `response_json` json DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `review_decision` enum('accepted','modified','rejected') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_requests_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `ai_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_requests`
--

LOCK TABLES `ai_requests` WRITE;
/*!40000 ALTER TABLE `ai_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ai_risk_scores`
--

DROP TABLE IF EXISTS `ai_risk_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ai_risk_scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_id` bigint unsigned NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `factors_json` json DEFAULT NULL,
  `explanation` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_risk_scores_case_id_foreign` (`case_id`),
  KEY `ai_risk_scores_approved_by_foreign` (`approved_by`),
  CONSTRAINT `ai_risk_scores_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ai_risk_scores_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_risk_scores`
--

LOCK TABLES `ai_risk_scores` WRITE;
/*!40000 ALTER TABLE `ai_risk_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_risk_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approvals`
--

DROP TABLE IF EXISTS `approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint unsigned NOT NULL,
  `step_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver_id` bigint unsigned DEFAULT NULL,
  `decision` enum('pending','approved','rejected','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `decided_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approvals_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  KEY `approvals_approver_id_decision_index` (`approver_id`,`decision`),
  CONSTRAINT `approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvals`
--

LOCK TABLES `approvals` WRITE;
/*!40000 ALTER TABLE `approvals` DISABLE KEYS */;
/*!40000 ALTER TABLE `approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `before_json` json DEFAULT NULL,
  `after_json` json DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_actor_id_created_at_index` (`actor_id`,`created_at`),
  KEY `audit_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  CONSTRAINT `audit_logs_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beneficiaries`
--

DROP TABLE IF EXISTS `beneficiaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beneficiaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `application_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_person_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_person_name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `identity_type` enum('nid','birth_cert','passport','other','none') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `identity_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_income` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mobile_banking_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_banking_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_specific_data_json` json DEFAULT NULL,
  `total_assets_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_liabilities` decimal(15,2) NOT NULL DEFAULT '0.00',
  `medical_status` text COLLATE utf8mb4_unicode_ci,
  `disability_flag` tinyint(1) NOT NULL DEFAULT '0',
  `disability_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vulnerability_score` int NOT NULL DEFAULT '0',
  `ai_score` int DEFAULT NULL,
  `ai_verification_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ai_notes` text COLLATE utf8mb4_unicode_ci,
  `zakat_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','under_review','verified','approved','rejected','blacklisted','graduated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `blacklist_flag` tinyint(1) NOT NULL DEFAULT '0',
  `watchlist_flag` tinyint(1) NOT NULL DEFAULT '0',
  `duplicate_confidence_score` int NOT NULL DEFAULT '0',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `beneficiaries_application_no_unique` (`application_no`),
  KEY `beneficiaries_user_id_foreign` (`user_id`),
  KEY `beneficiaries_branch_id_foreign` (`branch_id`),
  KEY `beneficiaries_status_branch_id_index` (`status`,`branch_id`),
  KEY `beneficiaries_identity_type_identity_no_index` (`identity_type`,`identity_no`),
  KEY `beneficiaries_mobile_index` (`mobile`),
  KEY `beneficiaries_zakat_category_index` (`zakat_category`),
  CONSTRAINT `beneficiaries_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beneficiaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficiaries`
--

LOCK TABLES `beneficiaries` WRITE;
/*!40000 ALTER TABLE `beneficiaries` DISABLE KEYS */;
INSERT INTO `beneficiaries` VALUES (1,7,'BEN-2026-000002','Rahima Begum','রহিমা বেগম','female','1988-06-15','nid','8923481239','01700000007',4500.00,'bKash','01700000007','{\"daily_income\": 150, \"family_members\": 3}',2000.00,5000.00,NULL,0,NULL,NULL,NULL,0,NULL,NULL,NULL,'faqir','approved',0,0,0,NULL,1,'2026-06-15 12:27:59','2026-06-15 12:27:59',NULL);
/*!40000 ALTER TABLE `beneficiaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beneficiary_category_data`
--

DROP TABLE IF EXISTS `beneficiary_category_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beneficiary_category_data` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `beneficiary_id` bigint unsigned NOT NULL,
  `zakat_category_id` bigint unsigned NOT NULL,
  `form_data` json NOT NULL,
  `verification_status` enum('unverified','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unverified',
  `verification_notes` text COLLATE utf8mb4_unicode_ci,
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `beneficiary_category_data_zakat_category_id_foreign` (`zakat_category_id`),
  KEY `beneficiary_category_data_verified_by_foreign` (`verified_by`),
  KEY `beneficiary_category_data_beneficiary_id_zakat_category_id_index` (`beneficiary_id`,`zakat_category_id`),
  CONSTRAINT `beneficiary_category_data_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `beneficiary_category_data_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beneficiary_category_data_zakat_category_id_foreign` FOREIGN KEY (`zakat_category_id`) REFERENCES `zakat_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficiary_category_data`
--

LOCK TABLES `beneficiary_category_data` WRITE;
/*!40000 ALTER TABLE `beneficiary_category_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `beneficiary_category_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beneficiary_documents`
--

DROP TABLE IF EXISTS `beneficiary_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beneficiary_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `beneficiary_id` bigint unsigned NOT NULL,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int NOT NULL DEFAULT '0',
  `verification_status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verification_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `beneficiary_documents_beneficiary_id_foreign` (`beneficiary_id`),
  KEY `beneficiary_documents_verified_by_foreign` (`verified_by`),
  CONSTRAINT `beneficiary_documents_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `beneficiary_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficiary_documents`
--

LOCK TABLES `beneficiary_documents` WRITE;
/*!40000 ALTER TABLE `beneficiary_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `beneficiary_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beneficiary_households`
--

DROP TABLE IF EXISTS `beneficiary_households`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beneficiary_households` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `beneficiary_id` bigint unsigned NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `address_bn` text COLLATE utf8mb4_unicode_ci,
  `geo_lat` decimal(10,7) DEFAULT NULL,
  `geo_lng` decimal(10,7) DEFAULT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upazila` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `union_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `village` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `housing_type` enum('own','rented','government','shelter','homeless','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `housing_condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `beneficiary_households_beneficiary_id_foreign` (`beneficiary_id`),
  CONSTRAINT `beneficiary_households_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficiary_households`
--

LOCK TABLES `beneficiary_households` WRITE;
/*!40000 ALTER TABLE `beneficiary_households` DISABLE KEYS */;
INSERT INTO `beneficiary_households` VALUES (1,1,'Kailash Slum, Gulshan',NULL,NULL,NULL,'Dhaka','Dhaka','Gulshan',NULL,NULL,NULL,NULL,NULL,'2026-06-15 12:27:59','2026-06-15 12:27:59');
/*!40000 ALTER TABLE `beneficiary_households` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beneficiary_otps`
--

DROP TABLE IF EXISTS `beneficiary_otps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beneficiary_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficiary_otps`
--

LOCK TABLES `beneficiary_otps` WRITE;
/*!40000 ALTER TABLE `beneficiary_otps` DISABLE KEYS */;
/*!40000 ALTER TABLE `beneficiary_otps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beneficiary_support_history`
--

DROP TABLE IF EXISTS `beneficiary_support_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `beneficiary_support_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `beneficiary_id` bigint unsigned NOT NULL,
  `distribution_id` bigint unsigned DEFAULT NULL,
  `disbursement_id` bigint unsigned DEFAULT NULL,
  `policy_id` bigint unsigned DEFAULT NULL,
  `zakat_category_id` bigint unsigned DEFAULT NULL,
  `zakat_category_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_received` decimal(15,2) NOT NULL DEFAULT '0.00',
  `distribution_date` date NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_eligible_date` date DEFAULT NULL,
  `followup_required` tinyint(1) NOT NULL DEFAULT '1',
  `followup_due_date` date DEFAULT NULL,
  `followup_status` enum('pending','completed','overdue','skipped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_restricted` tinyint(1) NOT NULL DEFAULT '0',
  `is_permanently_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `restriction_reason` text COLLATE utf8mb4_unicode_ci,
  `restriction_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `beneficiary_support_history_distribution_id_foreign` (`distribution_id`),
  KEY `beneficiary_support_history_disbursement_id_foreign` (`disbursement_id`),
  KEY `beneficiary_support_history_policy_id_foreign` (`policy_id`),
  KEY `beneficiary_support_history_zakat_category_id_foreign` (`zakat_category_id`),
  KEY `bsh_beneficiary_dist_date_index` (`beneficiary_id`,`distribution_date`),
  KEY `bsh_beneficiary_next_elig_index` (`beneficiary_id`,`next_eligible_date`),
  KEY `bsh_followup_due_status_index` (`followup_due_date`,`followup_status`),
  CONSTRAINT `beneficiary_support_history_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `beneficiary_support_history_disbursement_id_foreign` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beneficiary_support_history_distribution_id_foreign` FOREIGN KEY (`distribution_id`) REFERENCES `distributions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beneficiary_support_history_policy_id_foreign` FOREIGN KEY (`policy_id`) REFERENCES `support_policies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `beneficiary_support_history_zakat_category_id_foreign` FOREIGN KEY (`zakat_category_id`) REFERENCES `zakat_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beneficiary_support_history`
--

LOCK TABLES `beneficiary_support_history` WRITE;
/*!40000 ALTER TABLE `beneficiary_support_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `beneficiary_support_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklists`
--

DROP TABLE IF EXISTS `blacklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blacklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `identity_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` enum('temporary','permanent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'permanent',
  `added_by` bigint unsigned DEFAULT NULL,
  `fraud_case_id` bigint unsigned DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blacklists_added_by_foreign` (`added_by`),
  KEY `blacklists_fraud_case_id_foreign` (`fraud_case_id`),
  KEY `blacklists_identity_type_identity_no_index` (`identity_type`,`identity_no`),
  KEY `blacklists_mobile_index` (`mobile`),
  KEY `blacklists_is_active_severity_index` (`is_active`,`severity`),
  CONSTRAINT `blacklists_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blacklists_fraud_case_id_foreign` FOREIGN KEY (`fraud_case_id`) REFERENCES `fraud_cases` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklists`
--

LOCK TABLES `blacklists` WRITE;
/*!40000 ALTER TABLE `blacklists` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blockchain_records`
--

DROP TABLE IF EXISTS `blockchain_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blockchain_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint unsigned NOT NULL,
  `hash` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chain` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ethereum',
  `network` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sepolia',
  `tx_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anchor_status` enum('pending','anchored','failed','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blockchain_records_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  KEY `blockchain_records_hash_index` (`hash`),
  KEY `blockchain_records_tx_hash_index` (`tx_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blockchain_records`
--

LOCK TABLES `blockchain_records` WRITE;
/*!40000 ALTER TABLE `blockchain_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `blockchain_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upazila` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `geo_lat` decimal(10,7) DEFAULT NULL,
  `geo_lng` decimal(10,7) DEFAULT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,'DHK-001','Dhaka Central','ঢাকা কেন্দ্রীয়','Dhaka','Dhaka','Dhaka',NULL,NULL,NULL,NULL,NULL,NULL,'active','2026-06-15 12:27:57','2026-06-15 12:27:57',NULL),(2,'CTG-001','Chittagong Branch','চট্টগ্রাম শাখা','Chittagong','Chittagong','Chittagong',NULL,NULL,NULL,NULL,NULL,NULL,'active','2026-06-15 12:27:57','2026-06-15 12:27:57',NULL),(3,'RAJ-001','Rajshahi Branch','রাজশাহী শাখা','Rajshahi','Rajshahi','Rajshahi',NULL,NULL,NULL,NULL,NULL,NULL,'active','2026-06-15 12:27:57','2026-06-15 12:27:57',NULL);
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('zakat-management-platform-cache-1daf24ae68ca3fcb31eaaee07b6a59e3','i:1;',1781527730),('zakat-management-platform-cache-1daf24ae68ca3fcb31eaaee07b6a59e3:timer','i:1781527730;',1781527730),('zakat-management-platform-cache-92315f3bb891045bc12d683e38e0eda6','i:1;',1781527707),('zakat-management-platform-cache-92315f3bb891045bc12d683e38e0eda6:timer','i:1781527707;',1781527707),('zakat-management-platform-cache-dea5aa1b10ce89b353b302aae7cc4ecc','i:1;',1781527371),('zakat-management-platform-cache-dea5aa1b10ce89b353b302aae7cc4ecc:timer','i:1781527371;',1781527371),('zakat-management-platform-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:65:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"dashboard.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:18:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;i:11;i:12;i:12;i:13;i:13;i:14;i:14;i:15;i:15;i:16;i:16;i:17;i:17;i:18;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"users.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"users.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"users.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"users.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:10:\"roles.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"roles.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:10:\"roles.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:12:\"roles.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:13:\"branches.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"branches.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"branches.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:15:\"branches.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:11:\"donors.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:7;i:2;i:8;i:3;i:16;i:4;i:17;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:13:\"donors.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:17;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:11:\"donors.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:13:\"donors.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:10:\"donors.kyc\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:18;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:18:\"beneficiaries.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:9:{i:0;i:1;i:1;i:4;i:2;i:7;i:3;i:9;i:4;i:10;i:5;i:11;i:6;i:16;i:7;i:17;i:8;i:18;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:20:\"beneficiaries.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:17;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:18:\"beneficiaries.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:20:\"beneficiaries.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:20:\"beneficiaries.verify\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:21:\"beneficiaries.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:20:\"beneficiaries.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:10:\"cases.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:4;i:2;i:5;i:3;i:7;i:4;i:9;i:5;i:10;i:6;i:11;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:12:\"cases.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:10:\"cases.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:12:\"cases.assign\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:7;i:2;i:11;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:13:\"cases.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:11:\"cases.close\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:17:\"verification.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:10;i:2;i:11;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:19:\"verification.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:10;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:19:\"verification.review\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:11;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:16:\"collections.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:3;i:2;i:6;i:3;i:7;i:4;i:8;i:5;i:17;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"collections.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:17;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:21:\"collections.reconcile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:14:\"campaigns.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:8;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"campaigns.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:14:\"campaigns.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:16:\"campaigns.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:10:\"funds.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:12:\"funds.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:12:\"funds.ledger\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:18:\"distributions.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;i:5;i:7;i:6;i:9;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:20:\"distributions.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:4;i:2;i:9;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:21:\"distributions.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:21:\"distributions.release\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:12:\"reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;i:5;i:7;i:6;i:18;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:14:\"reports.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:6;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:17:\"reports.financial\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:13:\"settings.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:13:\"settings.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"complaints.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:7;i:2;i:16;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:17:\"complaints.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:17:\"complaints.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:16;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:10:\"audit.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;i:4;i:18;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:12:\"audit.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:6;i:2;i:18;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:7:\"ai.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:9:\"ai.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:15:\"blockchain.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:17:\"blockchain.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:14:\"shariah.review\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:15:\"shariah.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:5;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:20:\"notifications.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:18:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"system_admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:13:\"finance_admin\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:13:\"zakat_officer\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:13:\"shariah_board\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:7:\"auditor\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"branch_manager\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:18:\"collection_officer\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:20:\"distribution_officer\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:11:\"field_agent\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:10:\"supervisor\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:12;s:1:\"b\";s:5:\"donor\";s:1:\"c\";s:3:\"web\";}i:12;a:3:{s:1:\"a\";i:13;s:1:\"b\";s:11:\"beneficiary\";s:1:\"c\";s:3:\"web\";}i:13;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:9:\"volunteer\";s:1:\"c\";s:3:\"web\";}i:14;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"organization\";s:1:\"c\";s:3:\"web\";}i:15;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:16:\"customer_support\";s:1:\"c\";s:3:\"web\";}i:16;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:10:\"data_entry\";s:1:\"c\";s:3:\"web\";}i:17;a:3:{s:1:\"a\";i:18;s:1:\"b\";s:18:\"compliance_officer\";s:1:\"c\";s:3:\"web\";}}}',1781613218);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaigns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fund_type` enum('zakat','sadaqah','fitrah','waqf','emergency','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zakat',
  `branch_id` bigint unsigned DEFAULT NULL,
  `target_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `collected_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','active','paused','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaigns_slug_unique` (`slug`),
  KEY `campaigns_branch_id_foreign` (`branch_id`),
  KEY `campaigns_fund_type_status_index` (`fund_type`,`status`),
  CONSTRAINT `campaigns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
INSERT INTO `campaigns` VALUES (1,'Ramadan Zakat Drive 2026','রমজান যাকাত অভিযান ২০২৬','ramadan-zakat-2026','Annual Ramadan zakat collection campaign for poverty alleviation.','দারিদ্র্য বিমোচনের জন্য বার্ষিক রমজান যাকাত সংগ্রহ অভিযান।',NULL,'zakat',NULL,5000000.00,0.00,'2026-06-15 12:27:58','2026-08-15 12:27:58','active',1,'2026-06-15 12:27:58','2026-06-15 12:27:58',NULL),(2,'Education Support','শিক্ষা সহায়তা','education-support','Supporting underprivileged students with school supplies and tuition fees.',NULL,NULL,'sadaqah',NULL,2000000.00,0.00,'2026-06-15 12:27:58','2026-12-15 12:27:58','active',0,'2026-06-15 12:27:58','2026-06-15 12:27:58',NULL);
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_notes`
--

DROP TABLE IF EXISTS `case_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `case_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `note_type` enum('general','assessment','visit','decision','follow_up','escalation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_notes_case_id_foreign` (`case_id`),
  KEY `case_notes_author_id_foreign` (`author_id`),
  CONSTRAINT `case_notes_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `case_notes_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_notes`
--

LOCK TABLES `case_notes` WRITE;
/*!40000 ALTER TABLE `case_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `case_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `beneficiary_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `case_type` enum('food','medical','education','debt_relief','livelihood','housing','emergency','rehabilitation','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `stage` enum('assessment','field_verification','supervisor_review','shariah_review','finance_review','approved','disbursement','follow_up','closed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assessment',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requested_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `approved_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `outcome_status` enum('pending','successful','partial','failed','graduated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `follow_up_date` date DEFAULT NULL,
  `assigned_agent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cases_case_no_unique` (`case_no`),
  KEY `cases_branch_id_foreign` (`branch_id`),
  KEY `cases_assigned_agent_id_foreign` (`assigned_agent_id`),
  KEY `cases_stage_priority_index` (`stage`,`priority`),
  KEY `cases_beneficiary_id_stage_index` (`beneficiary_id`,`stage`),
  CONSTRAINT `cases_assigned_agent_id_foreign` FOREIGN KEY (`assigned_agent_id`) REFERENCES `agents` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cases_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cases_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cases`
--

LOCK TABLES `cases` WRITE;
/*!40000 ALTER TABLE `cases` DISABLE KEYS */;
INSERT INTO `cases` VALUES (1,'CASE-2026-000002',1,1,'livelihood','high','disbursement','online',25000.00,20000.00,'Sadaqah/Zakat livelihood support application for stitching machine.',NULL,'pending',NULL,NULL,'2026-06-15 12:27:59','2026-06-15 12:27:59',NULL);
/*!40000 ALTER TABLE `cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `collections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `receipt_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_id` bigint unsigned DEFAULT NULL,
  `campaign_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `fund_type` enum('zakat','sadaqah','fitrah','waqf','emergency','admin','restricted','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zakat',
  `source_channel` enum('online','cash','bank_transfer','cheque','mfs','card','pos','payment_link','qr') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'online',
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BDT',
  `donor_preference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','validated','failed','refunded','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `collected_by` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `referral_code` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_gateway` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collections_receipt_no_unique` (`receipt_no`),
  KEY `collections_campaign_id_foreign` (`campaign_id`),
  KEY `collections_branch_id_foreign` (`branch_id`),
  KEY `collections_collected_by_foreign` (`collected_by`),
  KEY `collections_fund_type_status_created_at_index` (`fund_type`,`status`,`created_at`),
  KEY `collections_donor_id_created_at_index` (`donor_id`,`created_at`),
  KEY `collections_referral_code_index` (`referral_code`),
  CONSTRAINT `collections_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collections_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collections_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `collections_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` VALUES (1,'REC-2026-00001',1,1,1,'zakat','online',75000.00,'BDT',NULL,0,'validated',NULL,'General Zakat donation',NULL,NULL,'bkash','TRX-BK9823471','paid','2026-06-15 12:27:58','2026-06-15 12:27:58',NULL),(2,'REC-2026-00002',1,1,1,'sadaqah','online',15000.00,'BDT',NULL,0,'validated',NULL,'Education Sadaqah donation',NULL,NULL,'sslcommerz','TRX-SSL348912','paid','2026-06-15 12:27:58','2026-06-15 12:27:58',NULL);
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `complaints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `complainant_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complainant_id` bigint unsigned DEFAULT NULL,
  `complainant_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complainant_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` enum('web','phone','email','sms','in_person','anonymous') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `severity` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sla_due_at` timestamp NULL DEFAULT NULL,
  `status` enum('open','assigned','investigating','resolved','closed','escalated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `assigned_to` bigint unsigned DEFAULT NULL,
  `resolution` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaints_ticket_no_unique` (`ticket_no`),
  KEY `complaints_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `complaints_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `complaints`
--

LOCK TABLES `complaints` WRITE;
/*!40000 ALTER TABLE `complaints` DISABLE KEYS */;
/*!40000 ALTER TABLE `complaints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disbursements`
--

DROP TABLE IF EXISTS `disbursements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disbursements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint unsigned DEFAULT NULL,
  `distribution_id` bigint unsigned NOT NULL,
  `payout_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payout_channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_status` enum('initiated','submitted','accepted','settled','failed','reversed','manually_resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'initiated',
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disbursements_batch_id_foreign` (`batch_id`),
  KEY `disbursements_distribution_id_foreign` (`distribution_id`),
  CONSTRAINT `disbursements_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `distribution_batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `disbursements_distribution_id_foreign` FOREIGN KEY (`distribution_id`) REFERENCES `distributions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disbursements`
--

LOCK TABLES `disbursements` WRITE;
/*!40000 ALTER TABLE `disbursements` DISABLE KEYS */;
INSERT INTO `disbursements` VALUES (1,NULL,1,'PAY-BK-892348',20000.00,'bKash','settled','2026-06-15 12:27:59','2026-06-15 12:27:59','2026-06-15 12:27:59');
/*!40000 ALTER TABLE `disbursements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribution_batches`
--

DROP TABLE IF EXISTS `distribution_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distribution_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `fund_id` bigint unsigned NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_beneficiaries` int NOT NULL DEFAULT '0',
  `approval_status` enum('draft','pending','approved','rejected','processing','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `payout_channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prepared_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `distribution_batches_batch_no_unique` (`batch_no`),
  KEY `distribution_batches_branch_id_foreign` (`branch_id`),
  KEY `distribution_batches_fund_id_foreign` (`fund_id`),
  KEY `distribution_batches_prepared_by_foreign` (`prepared_by`),
  KEY `distribution_batches_approved_by_foreign` (`approved_by`),
  CONSTRAINT `distribution_batches_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distribution_batches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distribution_batches_fund_id_foreign` FOREIGN KEY (`fund_id`) REFERENCES `funds` (`id`),
  CONSTRAINT `distribution_batches_prepared_by_foreign` FOREIGN KEY (`prepared_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribution_batches`
--

LOCK TABLES `distribution_batches` WRITE;
/*!40000 ALTER TABLE `distribution_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `distribution_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribution_queues`
--

DROP TABLE IF EXISTS `distribution_queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distribution_queues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `beneficiary_id` bigint unsigned NOT NULL,
  `case_id` bigint unsigned DEFAULT NULL,
  `zakat_category_id` bigint unsigned DEFAULT NULL,
  `distribution_round_id` bigint unsigned DEFAULT NULL,
  `requested_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `minimum_acceptable_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `approved_amount` decimal(15,2) DEFAULT NULL,
  `priority_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `priority_factors` json DEFAULT NULL,
  `vulnerability_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `category_weight` decimal(5,2) NOT NULL DEFAULT '0.00',
  `waiting_time_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `urgency_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `recommendation_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `repeat_receipt_penalty` decimal(5,2) NOT NULL DEFAULT '0.00',
  `misuse_history_penalty` decimal(5,2) NOT NULL DEFAULT '0.00',
  `previously_received_count` int unsigned NOT NULL DEFAULT '0',
  `last_received_date` date DEFAULT NULL,
  `queue_position` int unsigned DEFAULT NULL,
  `queue_status` enum('waiting','priority_review','processing','distributed','skipped','expired','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `added_to_queue_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `distributed_at` timestamp NULL DEFAULT NULL,
  `skip_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distribution_queues_case_id_foreign` (`case_id`),
  KEY `distribution_queues_zakat_category_id_foreign` (`zakat_category_id`),
  KEY `dist_queues_round_status_score_index` (`distribution_round_id`,`queue_status`,`priority_score`),
  KEY `distribution_queues_beneficiary_id_queue_status_index` (`beneficiary_id`,`queue_status`),
  KEY `dist_queues_score_prev_count_index` (`priority_score`,`previously_received_count`),
  CONSTRAINT `distribution_queues_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `distribution_queues_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distribution_queues_distribution_round_id_foreign` FOREIGN KEY (`distribution_round_id`) REFERENCES `distribution_rounds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distribution_queues_zakat_category_id_foreign` FOREIGN KEY (`zakat_category_id`) REFERENCES `zakat_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribution_queues`
--

LOCK TABLES `distribution_queues` WRITE;
/*!40000 ALTER TABLE `distribution_queues` DISABLE KEYS */;
/*!40000 ALTER TABLE `distribution_queues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distribution_rounds`
--

DROP TABLE IF EXISTS `distribution_rounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distribution_rounds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `round_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fund_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `organization_id` bigint unsigned DEFAULT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `round_type` enum('regular','ramadan','eid','flood_emergency','special') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `total_fund_available` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_distributed` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reserved_for_emergency` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_beneficiaries_planned` int unsigned NOT NULL DEFAULT '0',
  `total_beneficiaries_served` int unsigned NOT NULL DEFAULT '0',
  `round_start_date` date DEFAULT NULL,
  `round_end_date` date DEFAULT NULL,
  `status` enum('draft','open','processing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `prioritize_never_received` tinyint(1) NOT NULL DEFAULT '1',
  `allow_repeat_recipients` tinyint(1) NOT NULL DEFAULT '1',
  `max_per_beneficiary` int NOT NULL DEFAULT '1',
  `category_allocation_percent` json DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `distribution_rounds_round_no_unique` (`round_no`),
  KEY `distribution_rounds_fund_id_foreign` (`fund_id`),
  KEY `distribution_rounds_branch_id_foreign` (`branch_id`),
  KEY `distribution_rounds_organization_id_foreign` (`organization_id`),
  KEY `distribution_rounds_created_by_foreign` (`created_by`),
  CONSTRAINT `distribution_rounds_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distribution_rounds_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distribution_rounds_fund_id_foreign` FOREIGN KEY (`fund_id`) REFERENCES `funds` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distribution_rounds_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distribution_rounds`
--

LOCK TABLES `distribution_rounds` WRITE;
/*!40000 ALTER TABLE `distribution_rounds` DISABLE KEYS */;
/*!40000 ALTER TABLE `distribution_rounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distributions`
--

DROP TABLE IF EXISTS `distributions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distributions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `beneficiary_id` bigint unsigned NOT NULL,
  `case_id` bigint unsigned DEFAULT NULL,
  `fund_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned DEFAULT NULL,
  `category_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `distribution_type` enum('cash','bank_transfer','mfs','voucher','food_pack','medical','education','livelihood','housing','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('pending','approved','disbursed','acknowledged','failed','reversed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distributions_beneficiary_id_foreign` (`beneficiary_id`),
  KEY `distributions_case_id_foreign` (`case_id`),
  KEY `distributions_fund_id_foreign` (`fund_id`),
  KEY `distributions_batch_id_foreign` (`batch_id`),
  CONSTRAINT `distributions_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `distribution_batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distributions_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`),
  CONSTRAINT `distributions_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distributions_fund_id_foreign` FOREIGN KEY (`fund_id`) REFERENCES `funds` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distributions`
--

LOCK TABLES `distributions` WRITE;
/*!40000 ALTER TABLE `distributions` DISABLE KEYS */;
INSERT INTO `distributions` VALUES (1,1,1,1,NULL,'faqir',20000.00,'cash','approved','2026-06-15 12:27:59','2026-06-15 12:27:59',NULL);
/*!40000 ALTER TABLE `distributions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donor_addresses`
--

DROP TABLE IF EXISTS `donor_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `donor_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `donor_id` bigint unsigned NOT NULL,
  `country` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BD',
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upazila` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line` text COLLATE utf8mb4_unicode_ci,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donor_addresses_donor_id_foreign` (`donor_id`),
  CONSTRAINT `donor_addresses_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donor_addresses`
--

LOCK TABLES `donor_addresses` WRITE;
/*!40000 ALTER TABLE `donor_addresses` DISABLE KEYS */;
INSERT INTO `donor_addresses` VALUES (1,1,'BD','Dhaka','Dhaka','Gulshan','House 12, Road 4, Baridhara',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58');
/*!40000 ALTER TABLE `donor_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donors`
--

DROP TABLE IF EXISTS `donors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `donors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `donor_type` enum('individual','corporate','mosque','branch','institutional') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'individual',
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anonymous_default` tinyint(1) NOT NULL DEFAULT '0',
  `kyc_status` enum('none','pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `kyc_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donors_user_id_foreign` (`user_id`),
  KEY `donors_donor_type_kyc_status_index` (`donor_type`,`kyc_status`),
  CONSTRAINT `donors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donors`
--

LOCK TABLES `donors` WRITE;
/*!40000 ALTER TABLE `donors` DISABLE KEYS */;
INSERT INTO `donors` VALUES (1,4,'individual','Al-Karim Trust','Abdul Karim',NULL,0,'verified',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58',NULL);
/*!40000 ALTER TABLE `donors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `duplicate_candidates`
--

DROP TABLE IF EXISTS `duplicate_candidates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `duplicate_candidates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `primary_beneficiary_id` bigint unsigned NOT NULL,
  `duplicate_beneficiary_id` bigint unsigned NOT NULL,
  `match_types` json NOT NULL,
  `confidence_score` tinyint unsigned NOT NULL,
  `match_details` json DEFAULT NULL,
  `review_status` enum('pending','confirmed_duplicate','false_positive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dup_candidates_primary_dup_unique` (`primary_beneficiary_id`,`duplicate_beneficiary_id`),
  KEY `duplicate_candidates_reviewed_by_foreign` (`reviewed_by`),
  KEY `duplicate_candidates_duplicate_beneficiary_id_foreign` (`duplicate_beneficiary_id`),
  KEY `duplicate_candidates_review_status_confidence_score_index` (`review_status`,`confidence_score`),
  CONSTRAINT `duplicate_candidates_duplicate_beneficiary_id_foreign` FOREIGN KEY (`duplicate_beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `duplicate_candidates_primary_beneficiary_id_foreign` FOREIGN KEY (`primary_beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `duplicate_candidates_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duplicate_candidates`
--

LOCK TABLES `duplicate_candidates` WRITE;
/*!40000 ALTER TABLE `duplicate_candidates` DISABLE KEYS */;
/*!40000 ALTER TABLE `duplicate_candidates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow_ups`
--

DROP TABLE IF EXISTS `follow_ups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follow_ups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_id` bigint unsigned NOT NULL,
  `agent_id` bigint unsigned NOT NULL,
  `follow_up_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `impact_rating` tinyint DEFAULT NULL,
  `funds_utilized_properly` tinyint(1) DEFAULT NULL,
  `next_follow_up_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `follow_ups_case_id_foreign` (`case_id`),
  KEY `follow_ups_agent_id_foreign` (`agent_id`),
  CONSTRAINT `follow_ups_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`),
  CONSTRAINT `follow_ups_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow_ups`
--

LOCK TABLES `follow_ups` WRITE;
/*!40000 ALTER TABLE `follow_ups` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow_ups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fraud_alerts`
--

DROP TABLE IF EXISTS `fraud_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fraud_alerts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `alert_code` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` enum('info','warning','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'warning',
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `evidence` json DEFAULT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT '0',
  `resolved_by` bigint unsigned DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolution_note` text COLLATE utf8mb4_unicode_ci,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fraud_alerts_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `fraud_alerts_resolved_by_foreign` (`resolved_by`),
  KEY `fraud_alerts_assigned_to_foreign` (`assigned_to`),
  KEY `fraud_alerts_alert_code_severity_index` (`alert_code`,`severity`),
  KEY `fraud_alerts_is_resolved_severity_index` (`is_resolved`,`severity`),
  CONSTRAINT `fraud_alerts_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fraud_alerts_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fraud_alerts`
--

LOCK TABLES `fraud_alerts` WRITE;
/*!40000 ALTER TABLE `fraud_alerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `fraud_alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fraud_cases`
--

DROP TABLE IF EXISTS `fraud_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fraud_cases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `reported_by` bigint unsigned DEFAULT NULL,
  `fraud_type` enum('duplicate_identity','false_information','agent_collusion','document_forgery','geo_fraud','identity_theft','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidence` json DEFAULT NULL,
  `status` enum('open','investigating','confirmed','dismissed','referred') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `investigator_id` bigint unsigned DEFAULT NULL,
  `resolution` text COLLATE utf8mb4_unicode_ci,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fraud_cases_case_no_unique` (`case_no`),
  KEY `fraud_cases_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `fraud_cases_reported_by_foreign` (`reported_by`),
  KEY `fraud_cases_investigator_id_foreign` (`investigator_id`),
  KEY `fraud_cases_status_fraud_type_index` (`status`,`fraud_type`),
  CONSTRAINT `fraud_cases_investigator_id_foreign` FOREIGN KEY (`investigator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fraud_cases_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fraud_cases`
--

LOCK TABLES `fraud_cases` WRITE;
/*!40000 ALTER TABLE `fraud_cases` DISABLE KEYS */;
/*!40000 ALTER TABLE `fraud_cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fraud_risk_scores`
--

DROP TABLE IF EXISTS `fraud_risk_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fraud_risk_scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `score` tinyint unsigned NOT NULL,
  `risk_level` enum('low','medium','high','critical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'low',
  `factors` json NOT NULL,
  `explanation` text COLLATE utf8mb4_unicode_ci,
  `flagged_by` enum('system','agent','admin','supervisor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `review_status` enum('pending','cleared','confirmed_fraud') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewer_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fraud_risk_scores_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `fraud_risk_scores_reviewed_by_foreign` (`reviewed_by`),
  KEY `fraud_risk_scores_risk_level_review_status_index` (`risk_level`,`review_status`),
  KEY `fraud_risk_scores_score_index` (`score`),
  CONSTRAINT `fraud_risk_scores_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fraud_risk_scores`
--

LOCK TABLES `fraud_risk_scores` WRITE;
/*!40000 ALTER TABLE `fraud_risk_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `fraud_risk_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fund_ledgers`
--

DROP TABLE IF EXISTS `fund_ledgers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fund_ledgers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fund_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `entry_type` enum('collection','distribution','transfer','adjustment','refund','fee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `ref_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` bigint unsigned DEFAULT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci,
  `effective_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fund_ledgers_branch_id_foreign` (`branch_id`),
  KEY `fund_ledgers_created_by_foreign` (`created_by`),
  KEY `fund_ledgers_fund_id_effective_at_index` (`fund_id`,`effective_at`),
  CONSTRAINT `fund_ledgers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fund_ledgers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fund_ledgers_fund_id_foreign` FOREIGN KEY (`fund_id`) REFERENCES `funds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fund_ledgers`
--

LOCK TABLES `fund_ledgers` WRITE;
/*!40000 ALTER TABLE `fund_ledgers` DISABLE KEYS */;
/*!40000 ALTER TABLE `fund_ledgers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funds`
--

DROP TABLE IF EXISTS `funds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('zakat','sadaqah','fitrah','waqf','emergency','admin','restricted','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zakat',
  `restricted_flag` tinyint(1) NOT NULL DEFAULT '0',
  `branch_scoped_flag` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `funds_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funds`
--

LOCK TABLES `funds` WRITE;
/*!40000 ALTER TABLE `funds` DISABLE KEYS */;
INSERT INTO `funds` VALUES (1,'ZAKAT','Zakat Fund','যাকাত তহবিল','zakat',1,0,NULL,'active','2026-06-15 12:27:58','2026-06-15 12:27:58'),(2,'SADAQAH','Sadaqah Fund','সদকা তহবিল','sadaqah',0,0,NULL,'active','2026-06-15 12:27:58','2026-06-15 12:27:58'),(3,'FITRAH','Fitrah Fund','ফিতরা তহবিল','fitrah',1,0,NULL,'active','2026-06-15 12:27:58','2026-06-15 12:27:58'),(4,'WAQF','Waqf Fund','ওয়াক্‌ফ তহবিল','waqf',1,0,NULL,'active','2026-06-15 12:27:58','2026-06-15 12:27:58'),(5,'EMERGENCY','Emergency Relief','জরুরি সাহায্য','emergency',0,0,NULL,'active','2026-06-15 12:27:58','2026-06-15 12:27:58'),(6,'GENERAL','General Donation','সাধারণ দান','general',0,0,NULL,'active','2026-06-15 12:27:58','2026-06-15 12:27:58');
/*!40000 ALTER TABLE `funds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geographic_areas`
--

DROP TABLE IF EXISTS `geographic_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `geographic_areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `level` enum('division','district','upazila','union','ward','village') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'village',
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bbs_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_type` enum('rural','semi_urban','urban') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rural',
  `poverty_index` decimal(5,2) NOT NULL DEFAULT '0.00',
  `population_estimate` int unsigned NOT NULL DEFAULT '0',
  `geo_lat` decimal(10,7) DEFAULT NULL,
  `geo_lng` decimal(10,7) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `geographic_areas_parent_id_level_index` (`parent_id`,`level`),
  KEY `geographic_areas_level_area_type_index` (`level`,`area_type`),
  KEY `geographic_areas_bbs_code_index` (`bbs_code`),
  CONSTRAINT `geographic_areas_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `geographic_areas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geographic_areas`
--

LOCK TABLES `geographic_areas` WRITE;
/*!40000 ALTER TABLE `geographic_areas` DISABLE KEYS */;
INSERT INTO `geographic_areas` VALUES (1,NULL,'division','ঢাকা বিভাগ','Dhaka Division',NULL,'urban',0.00,0,NULL,NULL,1,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(2,1,'district','ঢাকা জেলা','Dhaka District',NULL,'urban',0.00,0,NULL,NULL,1,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(3,2,'village','বারিধারা গ্রাম','Baridhara Village',NULL,'urban',0.00,0,NULL,NULL,1,'2026-06-15 12:27:58','2026-06-15 12:27:58');
/*!40000 ALTER TABLE `geographic_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `household_members`
--

DROP TABLE IF EXISTS `household_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `household_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `household_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disability_flag` tinyint(1) NOT NULL DEFAULT '0',
  `education_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `income` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `household_members_household_id_foreign` (`household_id`),
  CONSTRAINT `household_members_household_id_foreign` FOREIGN KEY (`household_id`) REFERENCES `beneficiary_households` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `household_members`
--

LOCK TABLES `household_members` WRITE;
/*!40000 ALTER TABLE `household_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `household_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imam_muezzins`
--

DROP TABLE IF EXISTS `imam_muezzins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imam_muezzins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `imam_code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `mosque_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned DEFAULT NULL,
  `role` enum('imam','muezzin','khatib','general_validator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imam',
  `nid_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qualification_bn` text COLLATE utf8mb4_unicode_ci,
  `years_of_service` int unsigned NOT NULL DEFAULT '0',
  `address_bn` text COLLATE utf8mb4_unicode_ci,
  `coverage_area_id` bigint unsigned NOT NULL,
  `coverage_level` enum('village','ward','union') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ward',
  `coverage_village` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','active','suspended','removed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `validated_by` bigint unsigned DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `total_verifications` int unsigned NOT NULL DEFAULT '0',
  `total_followups` int unsigned NOT NULL DEFAULT '0',
  `total_misuse_reports` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `imam_muezzins_imam_code_unique` (`imam_code`),
  UNIQUE KEY `imam_muezzins_nid_no_unique` (`nid_no`),
  KEY `imam_muezzins_user_id_foreign` (`user_id`),
  KEY `imam_muezzins_organization_id_foreign` (`organization_id`),
  KEY `imam_muezzins_validated_by_foreign` (`validated_by`),
  KEY `imam_muezzins_mosque_id_status_index` (`mosque_id`,`status`),
  KEY `imam_muezzins_coverage_area_id_index` (`coverage_area_id`),
  CONSTRAINT `imam_muezzins_coverage_area_id_foreign` FOREIGN KEY (`coverage_area_id`) REFERENCES `geographic_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `imam_muezzins_mosque_id_foreign` FOREIGN KEY (`mosque_id`) REFERENCES `mosques` (`id`) ON DELETE CASCADE,
  CONSTRAINT `imam_muezzins_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `imam_muezzins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `imam_muezzins_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imam_muezzins`
--

LOCK TABLES `imam_muezzins` WRITE;
/*!40000 ALTER TABLE `imam_muezzins` DISABLE KEYS */;
/*!40000 ALTER TABLE `imam_muezzins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

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

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_21_110128_add_two_factor_columns_to_users_table',1),(5,'2026_05_21_110128_create_permission_tables',1),(6,'2026_05_21_110129_create_activity_log_table',1),(7,'2026_05_21_110129_create_passkeys_table',1),(8,'2026_05_21_120001_create_branches_table',1),(9,'2026_05_21_120002_create_mosques_table',1),(10,'2026_05_21_120003_extend_users_table',1),(11,'2026_05_21_120004_create_donor_and_collection_tables',1),(12,'2026_05_21_120005_create_beneficiary_and_case_tables',1),(13,'2026_05_21_120006_create_fund_and_distribution_tables',1),(14,'2026_05_21_120007_create_intelligence_and_support_tables',1),(15,'2026_05_21_122724_add_islamic_and_verification_fields_to_beneficiaries',1),(16,'2026_05_21_122726_create_follow_ups_table',1),(17,'2026_05_25_062038_create_module_settings_table',1),(18,'2026_05_25_062038_create_zakat_categories_table',1),(19,'2026_05_25_062105_create_fraud_guard_tables',1),(20,'2026_05_25_062147_create_geographic_areas_table',1),(21,'2026_05_25_081011_create_organization_ecosystem_tables',1),(22,'2026_05_25_081042_create_imam_volunteer_verification_tables',1),(23,'2026_05_25_081120_create_smart_distribution_tables',1),(24,'2026_05_31_200000_add_referral_and_payment_fields',1),(25,'2026_05_31_210000_update_user_type_enum',1),(26,'2026_06_15_120000_create_beneficiary_otps_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `misuse_reports`
--

DROP TABLE IF EXISTS `misuse_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `misuse_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `beneficiary_id` bigint unsigned NOT NULL,
  `disbursement_id` bigint unsigned DEFAULT NULL,
  `distribution_id` bigint unsigned DEFAULT NULL,
  `reported_by` bigint unsigned NOT NULL,
  `reporter_type` enum('volunteer','imam','muezzin','org_admin','field_agent','community_member') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imam',
  `organization_id` bigint unsigned DEFAULT NULL,
  `mosque_id` bigint unsigned DEFAULT NULL,
  `imam_muezzin_id` bigint unsigned DEFAULT NULL,
  `misuse_type` enum('gambling','luxury_spending','wasted','false_claim','wrong_category','sold_aid','gave_to_others','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `description_bn` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidence_paths` json DEFAULT NULL,
  `status` enum('pending','investigating','confirmed','dismissed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_decision` enum('warning','temporary_restrict','permanent_block','cleared','referred_to_authority') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restriction_days` int DEFAULT NULL,
  `admin_notes_bn` text COLLATE utf8mb4_unicode_ci,
  `decided_by` bigint unsigned DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `notify_beneficiary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `misuse_reports_report_no_unique` (`report_no`),
  KEY `misuse_reports_disbursement_id_foreign` (`disbursement_id`),
  KEY `misuse_reports_distribution_id_foreign` (`distribution_id`),
  KEY `misuse_reports_organization_id_foreign` (`organization_id`),
  KEY `misuse_reports_mosque_id_foreign` (`mosque_id`),
  KEY `misuse_reports_imam_muezzin_id_foreign` (`imam_muezzin_id`),
  KEY `misuse_reports_decided_by_foreign` (`decided_by`),
  KEY `misuse_reports_beneficiary_id_status_index` (`beneficiary_id`,`status`),
  KEY `misuse_reports_reported_by_reporter_type_index` (`reported_by`,`reporter_type`),
  KEY `misuse_reports_status_admin_decision_index` (`status`,`admin_decision`),
  CONSTRAINT `misuse_reports_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `misuse_reports_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `misuse_reports_disbursement_id_foreign` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements` (`id`) ON DELETE SET NULL,
  CONSTRAINT `misuse_reports_distribution_id_foreign` FOREIGN KEY (`distribution_id`) REFERENCES `distributions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `misuse_reports_imam_muezzin_id_foreign` FOREIGN KEY (`imam_muezzin_id`) REFERENCES `imam_muezzins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `misuse_reports_mosque_id_foreign` FOREIGN KEY (`mosque_id`) REFERENCES `mosques` (`id`) ON DELETE SET NULL,
  CONSTRAINT `misuse_reports_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `misuse_reports_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `misuse_reports`
--

LOCK TABLES `misuse_reports` WRITE;
/*!40000 ALTER TABLE `misuse_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `misuse_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(3,'App\\Models\\User',2),(4,'App\\Models\\User',3),(12,'App\\Models\\User',4),(15,'App\\Models\\User',5),(14,'App\\Models\\User',6),(13,'App\\Models\\User',7);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_settings`
--

DROP TABLE IF EXISTS `module_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `module_code` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` json NOT NULL,
  `data_type` enum('boolean','integer','string','json','enum','float') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `label_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `group_label_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_sensitive` tinyint(1) NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_settings_module_code_setting_key_unique` (`module_code`,`setting_key`),
  KEY `module_settings_updated_by_foreign` (`updated_by`),
  KEY `module_settings_module_code_index` (`module_code`),
  CONSTRAINT `module_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_settings`
--

LOCK TABLES `module_settings` WRITE;
/*!40000 ALTER TABLE `module_settings` DISABLE KEYS */;
INSERT INTO `module_settings` VALUES (1,'general','institution_name_bn','\"কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম\"','string','প্রতিষ্ঠানের নাম (বাংলা)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(2,'general','institution_name_en','\"Central Zakat Management Platform\"','string','প্রতিষ্ঠানের নাম (ইংরেজি)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(3,'general','portal_url','\"http://localhost:8080\"','string','পোর্টালের URL',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(4,'general','default_locale','\"bn\"','string','ডিফল্ট ভাষা',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(5,'general','fiscal_year_month','1','integer','অর্থবছরের শুরুর মাস',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(6,'general','ramadan_mode_enabled','false','boolean','রমজান মোড',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(7,'fraud_guard','enabled','true','boolean','ফ্রড সুরক্ষা মডিউল চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(8,'fraud_guard','dedup_nid_check','true','boolean','NID ডুপ্লিকেট চেক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(9,'fraud_guard','dedup_mobile_check','true','boolean','মোবাইল ডুপ্লিকেট চেক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(10,'fraud_guard','dedup_fuzzy_name_check','true','boolean','নামের ফাজি মিল চেক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(11,'fraud_guard','geo_cluster_check','true','boolean','GPS ক্লাস্টার চেক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(12,'fraud_guard','blacklist_check_enabled','true','boolean','কালো তালিকা চেক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(13,'fraud_guard','dedup_geo_radius_meters','50','integer','GPS ডুপ্লিকেট রেডিয়াস (মিটার)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(14,'fraud_guard','dedup_geo_max_applications','3','integer','একই এলাকায় সর্বোচ্চ আবেদন',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(15,'fraud_guard','risk_score_review_threshold','60','integer','ম্যানুয়াল রিভিউ থ্রেশহোল্ড (০-১০০)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(16,'fraud_guard','risk_score_auto_block_threshold','85','integer','অটো-হোল্ড থ্রেশহোল্ড (০-১০০)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(17,'fraud_guard','auto_hold_on_high_risk','true','boolean','হাই রিস্কে অটো-হোল্ড',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(18,'fraud_guard','agent_collusion_detection','true','boolean','এজেন্ট কলিউশন সনাক্তকরণ',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(19,'porichoy','enabled','false','boolean','পরিচয় NID যাচাই মডিউল চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(20,'porichoy','api_key','\"\"','string','API Key',NULL,NULL,NULL,1,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(21,'porichoy','verify_on_register','false','boolean','নিবন্ধনে স্বয়ংক্রিয় যাচাই',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(22,'porichoy','block_on_mismatch','false','boolean','মিলে না গেলে আবেদন আটকে দাও',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(23,'sms_gateway','enabled','true','boolean','SMS গেটওয়ে চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(24,'sms_gateway','provider','\"sms_net_bd\"','string','প্রোভাইডার',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(25,'sms_gateway','api_key','\"\"','string','API Key',NULL,NULL,NULL,1,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(26,'sms_gateway','sender_id','\"ZAKAT\"','string','Sender ID',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(27,'sms_gateway','status_check_keyword','\"STATUS\"','string','SMS স্ট্যাটাস কীওয়ার্ড',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(28,'payment','bkash_enabled','true','boolean','বিকাশ গেটওয়ে চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(29,'payment','nagad_enabled','false','boolean','নগদ গেটওয়ে চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(30,'payment','sslcommerz_enabled','true','boolean','SSLCommerz গেটওয়ে চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(31,'payment','rocket_enabled','false','boolean','রকেট গেটওয়ে চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(32,'payment','surjopay_enabled','false','boolean','শুর্জোপে গেটওয়ে চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(33,'payment','sandbox_mode','true','boolean','স্যান্ডবক্স মোড (টেস্ট)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(34,'ai','enabled','false','boolean','এআই মডিউল চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(35,'ai','provider','\"gemini\"','string','এআই প্রোভাইডার',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(36,'ai','ocr_enabled','false','boolean','OCR চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(37,'ai','risk_scoring','false','boolean','রিস্ক স্কোরিং চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(38,'ai','translation','false','boolean','বাংলা-ইংরেজি অনুবাদ চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(39,'ai','monthly_budget_bdt','0','integer','মাসিক এআই বাজেট (টাকা)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(40,'blockchain','enabled','false','boolean','ব্লকচেইন মডিউল চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(41,'blockchain','network','\"sepolia\"','string','নেটওয়ার্ক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(42,'blockchain','auto_anchor','false','boolean','অটো হ্যাশ অ্যাংকর',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(43,'field_agent','enabled','true','boolean','ফিল্ড এজেন্ট অ্যাপ চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(44,'field_agent','gps_required','true','boolean','GPS বাধ্যতামূলক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(45,'field_agent','photo_required','true','boolean','ছবি তোলা বাধ্যতামূলক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:55','2026-06-15 12:27:55'),(46,'field_agent','offline_sync','true','boolean','অফলাইন সিঙ্ক চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(47,'field_agent','supervisor_review_threshold','60','integer','সুপারভাইজার রিভিউ থ্রেশহোল্ড',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(48,'mosque_collection','enabled','true','boolean','মসজিদ কালেকশন মডিউল চালু',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(49,'mosque_collection','require_receipt_photo','true','boolean','রশিদের ছবি বাধ্যতামূলক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(50,'security','mfa_admin_required','true','boolean','এডমিনের জন্য MFA বাধ্যতামূলক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(51,'security','mfa_finance_required','true','boolean','ফিনান্স টিমের জন্য MFA বাধ্যতামূলক',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(52,'security','login_max_attempts','5','integer','সর্বোচ্চ লগইন প্রচেষ্টা',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(53,'security','session_timeout_minutes','60','integer','সেশন মেয়াদ (মিনিট)',NULL,NULL,NULL,0,0,0,NULL,'2026-06-15 12:27:56','2026-06-15 12:27:56');
/*!40000 ALTER TABLE `module_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mosque_collections`
--

DROP TABLE IF EXISTS `mosque_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mosque_collections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `collection_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mosque_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `collected_by_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `collected_by_mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zakat',
  `cash_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `collection_date` date NOT NULL,
  `prayer_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `receipt_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_status` enum('pending','deposited','reconciled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `deposited_by` bigint unsigned DEFAULT NULL,
  `deposited_at` timestamp NULL DEFAULT NULL,
  `fund_ledger_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mosque_collections_collection_no_unique` (`collection_no`),
  KEY `mosque_collections_branch_id_foreign` (`branch_id`),
  KEY `mosque_collections_deposited_by_foreign` (`deposited_by`),
  KEY `mosque_collections_created_by_foreign` (`created_by`),
  KEY `mosque_collections_mosque_id_deposit_status_index` (`mosque_id`,`deposit_status`),
  KEY `mosque_collections_collection_date_index` (`collection_date`),
  CONSTRAINT `mosque_collections_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mosque_collections_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mosque_collections_deposited_by_foreign` FOREIGN KEY (`deposited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mosque_collections_mosque_id_foreign` FOREIGN KEY (`mosque_id`) REFERENCES `mosques` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mosque_collections`
--

LOCK TABLES `mosque_collections` WRITE;
/*!40000 ALTER TABLE `mosque_collections` DISABLE KEYS */;
/*!40000 ALTER TABLE `mosque_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mosques`
--

DROP TABLE IF EXISTS `mosques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mosques` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `committee_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `geo_lat` decimal(10,7) DEFAULT NULL,
  `geo_lng` decimal(10,7) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mosques_branch_id_foreign` (`branch_id`),
  CONSTRAINT `mosques_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mosques`
--

LOCK TABLES `mosques` WRITE;
/*!40000 ALTER TABLE `mosques` DISABLE KEYS */;
/*!40000 ALTER TABLE `mosques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications_log`
--

DROP TABLE IF EXISTS `notifications_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `recipient_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_id` bigint unsigned NOT NULL,
  `channel` enum('email','sms','whatsapp','push') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
  `template_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload_json` json DEFAULT NULL,
  `send_status` enum('queued','sent','delivered','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'queued',
  `failure_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications_log`
--

LOCK TABLES `notifications_log` WRITE;
/*!40000 ALTER TABLE `notifications_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_validators`
--

DROP TABLE IF EXISTS `organization_validators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organization_validators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `scope` enum('all','district','type') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `scope_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `assigned_by` bigint unsigned DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organization_validators_user_id_unique` (`user_id`),
  KEY `organization_validators_assigned_by_foreign` (`assigned_by`),
  CONSTRAINT `organization_validators_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `organization_validators_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_validators`
--

LOCK TABLES `organization_validators` WRITE;
/*!40000 ALTER TABLE `organization_validators` DISABLE KEYS */;
/*!40000 ALTER TABLE `organization_validators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organizations`
--

DROP TABLE IF EXISTS `organizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `org_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referral_code` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_collected_via_referral` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_donors_via_referral` int unsigned NOT NULL DEFAULT '0',
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('national','regional','district','mosque_based','local_welfare') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'district',
  `registration_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_license_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngo_registration_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upazila` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `coverage_area_ids` json DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `status` enum('pending','under_review','verified','suspended','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `suspension_reason` text COLLATE utf8mb4_unicode_ci,
  `can_manage_own_fund` tinyint(1) NOT NULL DEFAULT '0',
  `field_visit_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organizations_org_code_unique` (`org_code`),
  UNIQUE KEY `organizations_referral_code_unique` (`referral_code`),
  KEY `organizations_branch_id_foreign` (`branch_id`),
  KEY `organizations_verified_by_foreign` (`verified_by`),
  KEY `organizations_created_by_foreign` (`created_by`),
  KEY `organizations_status_district_index` (`status`,`district`),
  KEY `organizations_type_status_index` (`type`,`status`),
  CONSTRAINT `organizations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `organizations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `organizations_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organizations`
--

LOCK TABLES `organizations` WRITE;
/*!40000 ALTER TABLE `organizations` DISABLE KEYS */;
INSERT INTO `organizations` VALUES (1,'ORG-2026-00001','O047A4D8',125000.00,3,'ঢাকা সমাজকল্যাণ সংস্থা','Dhaka Welfare Society','local_welfare','REG-NGO-98234',NULL,NULL,'Mustafa Rahman','01811111111','org@czm.bd',NULL,NULL,NULL,'Dhaka','Dhaka','Gulshan','Welfare Mansion, Gulshan 2',NULL,1,'verified',1,'2026-06-15 12:27:59',NULL,NULL,0,0,NULL,'2026-06-15 12:27:59','2026-06-15 12:27:59',NULL);
/*!40000 ALTER TABLE `organizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passkeys`
--

DROP TABLE IF EXISTS `passkeys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `passkeys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credential_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credential` json NOT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `passkeys_credential_id_unique` (`credential_id`),
  KEY `passkeys_user_id_index` (`user_id`),
  CONSTRAINT `passkeys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passkeys`
--

LOCK TABLES `passkeys` WRITE;
/*!40000 ALTER TABLE `passkeys` DISABLE KEYS */;
/*!40000 ALTER TABLE `passkeys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

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

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_gateways`
--

DROP TABLE IF EXISTS `payment_gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_gateways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` enum('sandbox','live') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sandbox',
  `config_json` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_gateways_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_gateways`
--

LOCK TABLES `payment_gateways` WRITE;
/*!40000 ALTER TABLE `payment_gateways` DISABLE KEYS */;
INSERT INTO `payment_gateways` VALUES (1,'sslcommerz','SSLCOMMERZ','sandbox','{\"sandbox\": true, \"store_id\": \"\", \"store_passwd\": \"\"}',1,1,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(2,'bkash','bKash','sandbox','{\"app_key\": \"\", \"sandbox\": true, \"app_secret\": \"\"}',0,2,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(3,'manual','Manual/Cash','live','[]',1,10,'2026-06-15 12:27:58','2026-06-15 12:27:58');
/*!40000 ALTER TABLE `payment_gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `collection_id` bigint unsigned NOT NULL,
  `gateway_id` bigint unsigned DEFAULT NULL,
  `provider_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tran_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `callback_status` enum('pending','success','failed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `validated_status` enum('pending','valid','invalid','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `risk_level` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'low',
  `gateway_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_collection_id_foreign` (`collection_id`),
  KEY `payments_tran_id_provider_ref_index` (`tran_id`,`provider_ref`),
  KEY `payments_gateway_id_validated_status_index` (`gateway_id`,`validated_status`),
  CONSTRAINT `payments_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_gateway_id_foreign` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'dashboard.view','web','2026-06-15 12:27:53','2026-06-15 12:27:53'),(2,'users.view','web','2026-06-15 12:27:53','2026-06-15 12:27:53'),(3,'users.create','web','2026-06-15 12:27:53','2026-06-15 12:27:53'),(4,'users.edit','web','2026-06-15 12:27:53','2026-06-15 12:27:53'),(5,'users.delete','web','2026-06-15 12:27:53','2026-06-15 12:27:53'),(6,'roles.view','web','2026-06-15 12:27:53','2026-06-15 12:27:53'),(7,'roles.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(8,'roles.edit','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(9,'roles.delete','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(10,'branches.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(11,'branches.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(12,'branches.edit','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(13,'branches.delete','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(14,'donors.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(15,'donors.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(16,'donors.edit','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(17,'donors.delete','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(18,'donors.kyc','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(19,'beneficiaries.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(20,'beneficiaries.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(21,'beneficiaries.edit','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(22,'beneficiaries.delete','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(23,'beneficiaries.verify','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(24,'beneficiaries.approve','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(25,'beneficiaries.reject','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(26,'cases.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(27,'cases.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(28,'cases.edit','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(29,'cases.assign','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(30,'cases.approve','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(31,'cases.close','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(32,'verification.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(33,'verification.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(34,'verification.review','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(35,'collections.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(36,'collections.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(37,'collections.reconcile','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(38,'campaigns.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(39,'campaigns.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(40,'campaigns.edit','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(41,'campaigns.delete','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(42,'funds.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(43,'funds.manage','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(44,'funds.ledger','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(45,'distributions.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(46,'distributions.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(47,'distributions.approve','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(48,'distributions.release','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(49,'reports.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(50,'reports.export','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(51,'reports.financial','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(52,'settings.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(53,'settings.edit','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(54,'complaints.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(55,'complaints.create','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(56,'complaints.manage','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(57,'audit.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(58,'audit.export','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(59,'ai.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(60,'ai.manage','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(61,'blockchain.view','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(62,'blockchain.manage','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(63,'shariah.review','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(64,'shariah.approve','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(65,'notifications.manage','web','2026-06-15 12:27:54','2026-06-15 12:27:54');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `porichoy_verifications`
--

DROP TABLE IF EXISTS `porichoy_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `porichoy_verifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `verifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verifiable_id` bigint unsigned NOT NULL,
  `nid_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','matched','not_found','mismatch','error','skipped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `api_response` json DEFAULT NULL,
  `matched_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matched_dob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_matched` tinyint(1) DEFAULT NULL,
  `api_request_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `porichoy_verifications_verifiable_type_verifiable_id_index` (`verifiable_type`,`verifiable_id`),
  KEY `porichoy_verifications_nid_no_status_index` (`nid_no`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `porichoy_verifications`
--

LOCK TABLES `porichoy_verifications` WRITE;
/*!40000 ALTER TABLE `porichoy_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `porichoy_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(10,2),(11,2),(12,2),(52,2),(53,2),(57,2),(65,2),(1,3),(35,3),(37,3),(42,3),(43,3),(44,3),(45,3),(47,3),(48,3),(49,3),(50,3),(51,3),(57,3),(1,4),(19,4),(23,4),(24,4),(25,4),(26,4),(28,4),(30,4),(42,4),(45,4),(46,4),(49,4),(1,5),(26,5),(42,5),(45,5),(49,5),(63,5),(64,5),(1,6),(35,6),(42,6),(45,6),(49,6),(50,6),(57,6),(58,6),(61,6),(1,7),(2,7),(14,7),(19,7),(26,7),(29,7),(35,7),(45,7),(49,7),(54,7),(1,8),(14,8),(15,8),(35,8),(36,8),(38,8),(1,9),(19,9),(26,9),(45,9),(46,9),(1,10),(19,10),(26,10),(32,10),(33,10),(1,11),(19,11),(26,11),(29,11),(32,11),(34,11),(1,12),(1,13),(1,14),(1,15),(1,16),(14,16),(19,16),(54,16),(56,16),(1,17),(14,17),(15,17),(19,17),(20,17),(35,17),(36,17),(1,18),(18,18),(19,18),(49,18),(57,18),(58,18);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super_admin','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(2,'system_admin','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(3,'finance_admin','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(4,'zakat_officer','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(5,'shariah_board','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(6,'auditor','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(7,'branch_manager','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(8,'collection_officer','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(9,'distribution_officer','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(10,'field_agent','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(11,'supervisor','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(12,'donor','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(13,'beneficiary','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(14,'volunteer','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(15,'organization','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(16,'customer_support','web','2026-06-15 12:27:54','2026-06-15 12:27:54'),(17,'data_entry','web','2026-06-15 12:27:55','2026-06-15 12:27:55'),(18,'compliance_officer','web','2026-06-15 12:27:55','2026-06-15 12:27:55');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seasonal_modes`
--

DROP TABLE IF EXISTS `seasonal_modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seasonal_modes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mode_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `starts_at` date DEFAULT NULL,
  `ends_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `auto_activate` tinyint(1) NOT NULL DEFAULT '0',
  `overrides` json DEFAULT NULL,
  `activated_by` bigint unsigned DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seasonal_modes_mode_code_unique` (`mode_code`),
  KEY `seasonal_modes_activated_by_foreign` (`activated_by`),
  CONSTRAINT `seasonal_modes_activated_by_foreign` FOREIGN KEY (`activated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seasonal_modes`
--

LOCK TABLES `seasonal_modes` WRITE;
/*!40000 ALTER TABLE `seasonal_modes` DISABLE KEYS */;
/*!40000 ALTER TABLE `seasonal_modes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('YG7wUBOjneHi9PJ6GfT3T29N6NM9vswyq2jBVwcQ',5,'172.19.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJWWE93TmM0dU1ZOTVyS0xNcm1KSVlDUk5WZEtBa2t0QVRJNjNkR1JLIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwODBcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo1fQ==',1781527707);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_json` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_group_code_key_unique` (`group_code`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'general','site_name','\"Central Zakat Management Platform\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(2,'general','site_name_bn','\"কেন্দ্রীয় যাকাত ব্যবস্থাপনা প্ল্যাটফর্ম\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(3,'general','default_currency','\"BDT\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(4,'general','default_locale','\"bn\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(5,'zakat','nisab_gold_grams','87.48',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(6,'zakat','nisab_silver_grams','612.36',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(7,'zakat','default_nisab_basis','\"silver\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(8,'zakat','default_rate','0.025',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(9,'zakat','zakat_categories','{\"amil\": {\"bn\": \"আমিল\", \"en\": \"Zakat Administrators (Amil)\"}, \"faqir\": {\"bn\": \"ফকির\", \"en\": \"The Poor (Faqir)\"}, \"riqab\": {\"bn\": \"রিকাব\", \"en\": \"Freeing Captives (Riqab)\"}, \"miskin\": {\"bn\": \"মিসকিন\", \"en\": \"The Needy (Miskin)\"}, \"muallaf\": {\"bn\": \"মুয়াল্লাফ\", \"en\": \"New Muslims (Muallaf)\"}, \"gharimin\": {\"bn\": \"গারিমীন\", \"en\": \"Debtors (Gharimin)\"}, \"ibnussabil\": {\"bn\": \"ইবনুস সাবিল\", \"en\": \"Wayfarers (Ibnus Sabil)\"}, \"fisabilillah\": {\"bn\": \"ফী সাবিলিল্লাহ\", \"en\": \"In the Way of Allah (Fi Sabilillah)\"}}',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(10,'ai','default_provider','\"ollama\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(11,'ai','ollama_endpoint','\"http://localhost:11434\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(12,'blockchain','enabled','false',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58'),(13,'blockchain','network','\"sepolia\"',NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_logs`
--

DROP TABLE IF EXISTS `sms_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `recipient_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_id` bigint unsigned NOT NULL,
  `to_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_code` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','sent','delivered','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gateway_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `cost_unit` int NOT NULL DEFAULT '1',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sms_logs_recipient_type_recipient_id_index` (`recipient_type`,`recipient_id`),
  KEY `sms_logs_to_number_status_index` (`to_number`,`status`),
  KEY `sms_logs_template_code_created_at_index` (`template_code`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_logs`
--

LOCK TABLES `sms_logs` WRITE;
/*!40000 ALTER TABLE `sms_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_status_queries`
--

DROP TABLE IF EXISTS `sms_status_queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_status_queries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `query_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `was_resolved` tinyint(1) NOT NULL DEFAULT '0',
  `sms_log_id` bigint unsigned DEFAULT NULL,
  `received_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sms_status_queries_from_number_index` (`from_number`),
  KEY `sms_status_queries_received_at_index` (`received_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_status_queries`
--

LOCK TABLES `sms_status_queries` WRITE;
/*!40000 ALTER TABLE `sms_status_queries` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_status_queries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_policies`
--

DROP TABLE IF EXISTS `support_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_policies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `policy_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `support_type` enum('one_time','annual','biannual','quarterly','long_term','emergency') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'one_time',
  `min_interval_days` int unsigned NOT NULL DEFAULT '365',
  `max_times_per_year` int unsigned NOT NULL DEFAULT '1',
  `max_consecutive_years` int unsigned DEFAULT NULL,
  `requires_followup_before_reapply` tinyint(1) NOT NULL DEFAULT '1',
  `requires_imam_recommendation` tinyint(1) NOT NULL DEFAULT '0',
  `requires_new_verification` tinyint(1) NOT NULL DEFAULT '1',
  `auto_schedule_followup` tinyint(1) NOT NULL DEFAULT '1',
  `followup_after_days` int unsigned NOT NULL DEFAULT '30',
  `auto_review_interval_days` int unsigned NOT NULL DEFAULT '180',
  `applicable_zakat_categories` json DEFAULT NULL,
  `priority_penalty_per_receipt` int NOT NULL DEFAULT '-10',
  `priority_penalty_max` int NOT NULL DEFAULT '-30',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `support_policies_policy_code_unique` (`policy_code`),
  KEY `support_policies_created_by_foreign` (`created_by`),
  CONSTRAINT `support_policies_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_policies`
--

LOCK TABLES `support_policies` WRITE;
/*!40000 ALTER TABLE `support_policies` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_policies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('staff','donor','beneficiary','agent','volunteer','org_admin') COLLATE utf8mb4_unicode_ci DEFAULT 'staff',
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bn',
  `status` enum('active','inactive','suspended','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_mobile_unique` (`mobile`),
  KEY `users_branch_id_foreign` (`branch_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'System Administrator','সিস্টেম প্রশাসক','admin@czm.bd','01700000001',NULL,'staff','en','active',NULL,NULL,'2026-06-15 12:27:57','$2y$12$7fy/utIGDV9XmIZ7rZOJQORqIBjw.hMISdxJ37IHKmTdwiRFD.GDG',NULL,NULL,NULL,NULL,'2026-06-15 12:27:57','2026-06-15 12:27:57',NULL),(2,1,'Finance Manager','অর্থ ব্যবস্থাপক','finance@czm.bd','01700000002',NULL,'staff','bn','active',NULL,NULL,'2026-06-15 12:27:58','$2y$12$dBUDZHXMFHED5M9X9qswu.E/FKgGAXLOfQlMwvwDSPwknd.soxIru',NULL,NULL,NULL,NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58',NULL),(3,1,'Zakat Officer','যাকাত কর্মকর্তা','officer@czm.bd','01700000003',NULL,'staff','bn','active',NULL,NULL,'2026-06-15 12:27:58','$2y$12$RWp682sf6bBq0X0oNCDFa.Kc7fRwTg9U2dtlMt4Q.WtPDR2MpWDA2',NULL,NULL,NULL,NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58',NULL),(4,1,'Demo Donor','ডেমো যাকাতদাতা','donor@czm.bd','01700000004',NULL,'donor','bn','active',NULL,NULL,'2026-06-15 12:27:58','$2y$12$dbN0iruO5S9u5WvSpqZX9Oik9tvhzsWUN0Pi1VFdOq6Bd5dwwnDZe',NULL,NULL,NULL,NULL,'2026-06-15 12:27:58','2026-06-15 12:27:58',NULL),(5,1,'Dhaka Welfare Admin','ঢাকা সমাজকল্যাণ প্রশাসক','org@czm.bd','01700000005',NULL,'org_admin','bn','active',NULL,NULL,'2026-06-15 12:27:59','$2y$12$SkQQbgGLZfrjVyyYb93qY.S.4FuvsSWyR1nPcotIKDSHji7nOr6wu',NULL,NULL,NULL,NULL,'2026-06-15 12:27:59','2026-06-15 12:27:59',NULL),(6,1,'Kamrul Hasan','কামরুল হাসান','volunteer@czm.bd','01700000006',NULL,'volunteer','bn','active',NULL,NULL,'2026-06-15 12:27:59','$2y$12$MYZ76kp97CAEuFUh0UiKne0UZm9/x/.qv3.oYp5rZwE1Ni7d0d2Mi',NULL,NULL,NULL,NULL,'2026-06-15 12:27:59','2026-06-15 12:27:59',NULL),(7,1,'Rahima Begum','রহিমা বেগম','beneficiary@czm.bd','01700000007',NULL,'beneficiary','bn','active',NULL,NULL,'2026-06-15 12:27:59','$2y$12$Zzba5VYl82K16NQYSVPLmecFoOnSvEHv7y0ESSpDuSsrGyqK9MbiO',NULL,NULL,NULL,NULL,'2026-06-15 12:27:59','2026-06-15 12:27:59',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_visits`
--

DROP TABLE IF EXISTS `verification_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verification_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `case_id` bigint unsigned NOT NULL,
  `agent_id` bigint unsigned NOT NULL,
  `visit_at` timestamp NULL DEFAULT NULL,
  `gps_lat` decimal(10,7) DEFAULT NULL,
  `gps_lng` decimal(10,7) DEFAULT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `summary_bn` text COLLATE utf8mb4_unicode_ci,
  `interview_data_json` json DEFAULT NULL,
  `risk_flag` tinyint(1) NOT NULL DEFAULT '0',
  `risk_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_paths` json DEFAULT NULL,
  `document_paths` json DEFAULT NULL,
  `supervisor_status` enum('pending','approved','returned','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `supervisor_notes` text COLLATE utf8mb4_unicode_ci,
  `supervisor_reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verification_visits_agent_id_foreign` (`agent_id`),
  KEY `verification_visits_supervisor_id_foreign` (`supervisor_id`),
  KEY `verification_visits_case_id_supervisor_status_index` (`case_id`,`supervisor_status`),
  CONSTRAINT `verification_visits_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `verification_visits_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `verification_visits_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_visits`
--

LOCK TABLES `verification_visits` WRITE;
/*!40000 ALTER TABLE `verification_visits` DISABLE KEYS */;
/*!40000 ALTER TABLE `verification_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer_area_restrictions`
--

DROP TABLE IF EXISTS `volunteer_area_restrictions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `volunteer_area_restrictions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `volunteer_id` bigint unsigned NOT NULL,
  `geographic_area_id` bigint unsigned NOT NULL,
  `level` enum('village','ward','union','upazila') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'village',
  `can_verify` tinyint(1) NOT NULL DEFAULT '1',
  `can_followup` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vol_area_restrict_unique` (`volunteer_id`,`geographic_area_id`),
  KEY `volunteer_area_restrictions_geographic_area_id_foreign` (`geographic_area_id`),
  CONSTRAINT `volunteer_area_restrictions_geographic_area_id_foreign` FOREIGN KEY (`geographic_area_id`) REFERENCES `geographic_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `volunteer_area_restrictions_volunteer_id_foreign` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_area_restrictions`
--

LOCK TABLES `volunteer_area_restrictions` WRITE;
/*!40000 ALTER TABLE `volunteer_area_restrictions` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer_area_restrictions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteers`
--

DROP TABLE IF EXISTS `volunteers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `volunteers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `volunteer_code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referral_code` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_collected_via_referral` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_donors_via_referral` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `nid_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_bn` text COLLATE utf8mb4_unicode_ci,
  `primary_area_id` bigint unsigned NOT NULL,
  `coverage_level` enum('village','ward','union','upazila') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'village',
  `status` enum('pending','active','suspended','removed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `validated_by` bigint unsigned DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `total_verifications` int unsigned NOT NULL DEFAULT '0',
  `total_followups` int unsigned NOT NULL DEFAULT '0',
  `last_active_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `volunteers_volunteer_code_unique` (`volunteer_code`),
  UNIQUE KEY `volunteers_nid_no_unique` (`nid_no`),
  UNIQUE KEY `volunteers_referral_code_unique` (`referral_code`),
  KEY `volunteers_user_id_foreign` (`user_id`),
  KEY `volunteers_validated_by_foreign` (`validated_by`),
  KEY `volunteers_organization_id_status_index` (`organization_id`,`status`),
  KEY `volunteers_primary_area_id_coverage_level_index` (`primary_area_id`,`coverage_level`),
  CONSTRAINT `volunteers_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `volunteers_primary_area_id_foreign` FOREIGN KEY (`primary_area_id`) REFERENCES `geographic_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `volunteers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `volunteers_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteers`
--

LOCK TABLES `volunteers` WRITE;
/*!40000 ALTER TABLE `volunteers` DISABLE KEYS */;
INSERT INTO `volunteers` VALUES (1,'VOL-2026-00001','VE4D3259',80000.00,2,6,1,'3928102391','কামরুল হাসান','Kamrul Hasan','01700000006','Student','ঢাকা, বারিধারা',3,'village','active',1,'2026-06-15 12:27:59',NULL,8,2,NULL,'2026-06-15 12:27:59','2026-06-15 12:27:59',NULL);
/*!40000 ALTER TABLE `volunteers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zakat_calculations`
--

DROP TABLE IF EXISTS `zakat_calculations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zakat_calculations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `donor_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `rule_pack` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `nisab_basis` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'silver',
  `nisab_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `asset_snapshot_json` json NOT NULL,
  `liability_snapshot_json` json DEFAULT NULL,
  `total_assets` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_liabilities` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_zakatable` decimal(15,2) NOT NULL DEFAULT '0.00',
  `zakat_due` decimal(15,2) NOT NULL DEFAULT '0.00',
  `zakat_rate` decimal(5,4) NOT NULL DEFAULT '0.0250',
  `is_eligible` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zakat_calculations_donor_id_foreign` (`donor_id`),
  KEY `zakat_calculations_user_id_foreign` (`user_id`),
  CONSTRAINT `zakat_calculations_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_calculations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zakat_calculations`
--

LOCK TABLES `zakat_calculations` WRITE;
/*!40000 ALTER TABLE `zakat_calculations` DISABLE KEYS */;
/*!40000 ALTER TABLE `zakat_calculations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zakat_categories`
--

DROP TABLE IF EXISTS `zakat_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zakat_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arabic_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `eligibility_criteria_bn` text COLLATE utf8mb4_unicode_ci,
  `icon_class` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_hex` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#10B981',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `requires_field_visit` tinyint(1) NOT NULL DEFAULT '1',
  `requires_shariah_review` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zakat_categories_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zakat_categories`
--

LOCK TABLES `zakat_categories` WRITE;
/*!40000 ALTER TABLE `zakat_categories` DISABLE KEYS */;
INSERT INTO `zakat_categories` VALUES (1,'fuqara','الفقراء','ফকির (সম্পূর্ণ নিঃস্ব)','Al-Fuqara (The Poor)','যাদের জীবন ধারণের জন্য কোনো সম্পদ বা উপার্জন নেই বা নিসাবের অর্ধেকের কম সম্পদ রয়েছে।',NULL,'মাসিক আয় নেই বা ন্যূনতম জীবনযাপনের জন্য অপ্রতুল। স্থায়ী বাসস্থান নেই বা নিজের সম্পদ নেই।','fas fa-hand-holding-heart','#EF4444',1,1,1,0,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(2,'masakin','المساكين','মিসকিন (অভাবগ্রস্ত)','Al-Masakin (The Needy)','যাদের আয় আছে কিন্তু মৌলিক চাহিদা মেটানোর জন্য অপ্রতুল।',NULL,'মাসিক আয় পরিবারের মৌলিক খরচের চেয়ে কম। নিসাবের কম সম্পদ আছে।','fas fa-people-arrows','#F97316',2,1,1,0,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(3,'muallafa','المؤلفة قلوبهم','মন আকর্ষণ (নওমুসলিম)','Al-Mu\'allafatu Qulubuhum','নতুন মুসলিম যাদের ঈমানী দৃঢ়তা ও পুনর্বাসনে সহায়তা প্রয়োজন।',NULL,'ইসলাম গ্রহণের প্রমাণ থাকতে হবে। ইমাম বা স্থানীয় আলেমের সুপারিশপত্র প্রয়োজন।','fas fa-heart','#8B5CF6',3,1,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(4,'gharimin','الغارمين','ঋণগ্রস্ত (আল-গারিমীন)','Al-Gharimin (The Indebted)','যারা প্রয়োজনীয় কারণে ঋণগ্রস্ত হয়েছেন এবং নিজে পরিশোধে অক্ষম।',NULL,'ঋণ নিজের বা পরিবারের বৈধ প্রয়োজনে নেওয়া হয়েছে। ব্যবসায় লোকসান বা দুর্যোগজনিত ঋণ। ঋণের পরিমাণ সম্পদের চেয়ে বেশি।','fas fa-file-invoice-dollar','#DC2626',4,1,1,0,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(5,'fi_sabilillah','في سبيل الله','আল্লাহর রাস্তায় (ফি সাবিলিল্লাহ)','Fi-Sabilillah (In the Way of Allah)','ধর্মীয় শিক্ষা, দাওয়াহ বা ইসলামের কল্যাণে নিয়োজিত অভাবগ্রস্ত ব্যক্তি।',NULL,'মাদ্রাসা ছাত্র যার ভরণপোষণ নেই। দাওয়াহ কাজে নিয়োজিত কিন্তু আয় নেই। শরিয়াহ বোর্ড অনুমোদিত কার্যক্রমে অংশগ্রহণকারী।','fas fa-mosque','#059669',5,1,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(6,'ibn_sabil','ابن السبيل','মুসাফির/বিপদগ্রস্ত পথচারী (ইবনুস সাবিল)','Ibn-as-Sabil (Traveler in Need)','সফরে এসে অর্থাভাবে বিপদে পড়া ব্যক্তি, যদিও স্বদেশে সচ্ছল।',NULL,'সফর বৈধ উদ্দেশ্যে (চিকিৎসা, ব্যবসা, শিক্ষা)। সাময়িক অভাবে পড়েছেন। স্বদেশে ফেরার পথ বা চিকিৎসার প্রয়োজন।','fas fa-route','#0EA5E9',6,1,0,0,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(7,'riqab','في الرقاب','মুক্তি (আর-রিকাব)','Ar-Riqab (Emancipation)','আধুনিক প্রেক্ষাপটে: দাসত্বের মতো পরিস্থিতি, বন্ড-লেবার বা জোরপূর্বক ঋণ থেকে মুক্তি।',NULL,'শরিয়াহ বোর্ডের অনুমোদন প্রয়োজন। এই খাতে বিতরণ সর্বদা শরিয়াহ রিভিউ সাপেক্ষ।','fas fa-unlock','#7C3AED',7,1,1,1,'2026-06-15 12:27:57','2026-06-15 12:27:57');
/*!40000 ALTER TABLE `zakat_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zakat_category_documents`
--

DROP TABLE IF EXISTS `zakat_category_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zakat_category_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `zakat_category_id` bigint unsigned NOT NULL,
  `doc_key` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_bn` text COLLATE utf8mb4_unicode_ci,
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `accepted_mime_types` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image/jpeg,image/png,application/pdf',
  `max_size_kb` int NOT NULL DEFAULT '2048',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zakat_category_documents_zakat_category_id_doc_key_unique` (`zakat_category_id`,`doc_key`),
  CONSTRAINT `zakat_category_documents_zakat_category_id_foreign` FOREIGN KEY (`zakat_category_id`) REFERENCES `zakat_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zakat_category_documents`
--

LOCK TABLES `zakat_category_documents` WRITE;
/*!40000 ALTER TABLE `zakat_category_documents` DISABLE KEYS */;
INSERT INTO `zakat_category_documents` VALUES (1,1,'nid_or_birth_cert','NID বা জন্ম নিবন্ধন সনদ',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(2,1,'chairman_certificate','ইউপি চেয়ারম্যান/মেম্বারের প্রত্যয়নপত্র',NULL,NULL,0,'image/jpeg,image/png,application/pdf',2048,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(3,2,'nid_or_birth_cert','NID বা জন্ম নিবন্ধন',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(4,2,'income_proof','আয়ের প্রমাণপত্র (বেতন স্লিপ / নিয়োগকর্তার পত্র)',NULL,NULL,0,'image/jpeg,image/png,application/pdf',2048,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(5,3,'nid_or_birth_cert','NID বা জন্ম নিবন্ধন',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(6,3,'imam_recommendation','ইমামের সুপারিশপত্র',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(7,4,'nid_or_birth_cert','NID বা জন্ম নিবন্ধন',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(8,4,'debt_document','ঋণের চুক্তিপত্র / দলিল / স্বীকারোক্তি',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(9,4,'creditor_statement','ঋণদাতার বিবৃতি (যদি পাওয়া যায়)',NULL,NULL,0,'image/jpeg,image/png,application/pdf',2048,3,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(10,5,'nid_or_birth_cert','NID বা জন্ম নিবন্ধন',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(11,5,'enrollment_certificate','ভর্তির সনদপত্র',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(12,5,'institution_letter','প্রতিষ্ঠান প্রধানের সুপারিশপত্র',NULL,NULL,0,'image/jpeg,image/png,application/pdf',2048,3,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(13,6,'nid_or_birth_cert','NID বা পরিচয়পত্র',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,1,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(14,6,'travel_proof','সফরের প্রমাণ (টিকিট, মেডিকেল পেপার ইত্যাদি)',NULL,NULL,0,'image/jpeg,image/png,application/pdf',2048,2,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(15,7,'nid_or_birth_cert','NID বা পরিচয়পত্র',NULL,NULL,1,'image/jpeg,image/png,application/pdf',2048,1,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(16,7,'supporting_evidence','সহায়ক প্রমাণপত্র',NULL,NULL,0,'image/jpeg,image/png,application/pdf',2048,2,1,'2026-06-15 12:27:57','2026-06-15 12:27:57');
/*!40000 ALTER TABLE `zakat_category_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zakat_category_forms`
--

DROP TABLE IF EXISTS `zakat_category_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zakat_category_forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `zakat_category_id` bigint unsigned NOT NULL,
  `field_key` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_bn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder_bn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field_type` enum('text','textarea','number','decimal','date','select','radio','checkbox','file','phone','nid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `field_options` json DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `validation_rules` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `help_text_bn` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zakat_category_forms_zakat_category_id_field_key_unique` (`zakat_category_id`,`field_key`),
  KEY `zakat_category_forms_zakat_category_id_sort_order_index` (`zakat_category_id`,`sort_order`),
  CONSTRAINT `zakat_category_forms_zakat_category_id_foreign` FOREIGN KEY (`zakat_category_id`) REFERENCES `zakat_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zakat_category_forms`
--

LOCK TABLES `zakat_category_forms` WRITE;
/*!40000 ALTER TABLE `zakat_category_forms` DISABLE KEYS */;
INSERT INTO `zakat_category_forms` VALUES (1,1,'income_source','আয়ের উৎস (যদি থাকে)',NULL,NULL,'select','[{\"value\": \"none\", \"label_bn\": \"নেই\"}, {\"value\": \"day_labor\", \"label_bn\": \"দিনমজুর\"}, {\"value\": \"begging\", \"label_bn\": \"ভিক্ষা\"}, {\"value\": \"other\", \"label_bn\": \"অন্যান্য\"}]',0,NULL,NULL,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(2,1,'monthly_income','মাসিক আয় (টাকা)',NULL,NULL,'decimal',NULL,1,'required|numeric|min:0',NULL,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(3,1,'has_any_asset','কোনো সম্পদ আছে কিনা',NULL,NULL,'radio','[{\"value\": \"no\", \"label_bn\": \"না\"}, {\"value\": \"yes\", \"label_bn\": \"হ্যাঁ (বিস্তারিত দিন)\"}]',1,NULL,NULL,3,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(4,1,'asset_details','সম্পদের বিবরণ (থাকলে)',NULL,NULL,'textarea',NULL,0,NULL,NULL,4,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(5,1,'housing_ownership','বাসস্থানের ধরন',NULL,NULL,'select','[{\"value\": \"homeless\", \"label_bn\": \"গৃহহীন\"}, {\"value\": \"rented\", \"label_bn\": \"ভাড়া\"}, {\"value\": \"relative\", \"label_bn\": \"আত্মীয়ের কাছে\"}, {\"value\": \"govt_shelter\", \"label_bn\": \"সরকারি আশ্রয়\"}]',1,NULL,NULL,5,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(6,1,'local_reference','স্থানীয় চেয়ারম্যান/মেম্বারের নাম ও মোবাইল',NULL,NULL,'text',NULL,0,NULL,NULL,6,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(7,2,'occupation','পেশা / কাজের ধরন',NULL,NULL,'text',NULL,1,'required|string|max:100',NULL,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(8,2,'monthly_income','মাসিক আয় (টাকা)',NULL,NULL,'decimal',NULL,1,NULL,NULL,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(9,2,'monthly_expense','আনুমানিক মাসিক খরচ (টাকা)',NULL,NULL,'decimal',NULL,1,NULL,NULL,3,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(10,2,'income_sources','সকল আয়ের উৎস',NULL,NULL,'textarea',NULL,0,NULL,NULL,4,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(11,2,'assistance_needed','কী ধরনের সহায়তা দরকার',NULL,NULL,'select','[{\"value\": \"food\", \"label_bn\": \"খাদ্য\"}, {\"value\": \"medical\", \"label_bn\": \"চিকিৎসা\"}, {\"value\": \"education\", \"label_bn\": \"শিক্ষা\"}, {\"value\": \"general\", \"label_bn\": \"সাধারণ\"}]',1,NULL,NULL,5,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(12,3,'conversion_date','ইসলাম গ্রহণের তারিখ',NULL,NULL,'date',NULL,1,NULL,NULL,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(13,3,'imam_name','সুপারিশকারী ইমাম/আলেমের নাম',NULL,NULL,'text',NULL,1,NULL,NULL,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(14,3,'imam_mobile','ইমাম/আলেমের মোবাইল',NULL,NULL,'phone',NULL,1,NULL,NULL,3,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(15,3,'mosque_name','সংশ্লিষ্ট মসজিদের নাম',NULL,NULL,'text',NULL,0,NULL,NULL,4,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(16,3,'current_needs','বর্তমান প্রয়োজনের বিবরণ',NULL,NULL,'textarea',NULL,1,NULL,NULL,5,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(17,4,'total_debt','মোট ঋণের পরিমাণ (টাকা)',NULL,NULL,'decimal',NULL,1,'required|numeric|min:1',NULL,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(18,4,'debt_reason','ঋণ নেওয়ার কারণ',NULL,NULL,'select','[{\"value\": \"medical\", \"label_bn\": \"চিকিৎসা\"}, {\"value\": \"business_loss\", \"label_bn\": \"ব্যবসায় লোকসান\"}, {\"value\": \"disaster\", \"label_bn\": \"দুর্যোগ/বন্যা\"}, {\"value\": \"food\", \"label_bn\": \"খাদ্য সংকট\"}, {\"value\": \"education\", \"label_bn\": \"সন্তানের শিক্ষা\"}, {\"value\": \"other\", \"label_bn\": \"অন্যান্য\"}]',1,NULL,NULL,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(19,4,'creditor_name','ঋণদাতার নাম',NULL,NULL,'text',NULL,1,NULL,NULL,3,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(20,4,'creditor_mobile','ঋণদাতার মোবাইল',NULL,NULL,'phone',NULL,0,NULL,NULL,4,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(21,4,'debt_due_date','ঋণ পরিশোধের শেষ তারিখ',NULL,NULL,'date',NULL,0,NULL,NULL,5,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(22,4,'repayment_ability','নিজে পরিশোধের সক্ষমতা আছে কিনা',NULL,NULL,'radio','[{\"value\": \"no\", \"label_bn\": \"না, সম্পূর্ণ অক্ষম\"}, {\"value\": \"partial\", \"label_bn\": \"আংশিক সক্ষম\"}]',1,NULL,NULL,6,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(23,5,'institution_name','মাদ্রাসা/প্রতিষ্ঠানের নাম',NULL,NULL,'text',NULL,1,NULL,NULL,1,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(24,5,'institution_address','প্রতিষ্ঠানের ঠিকানা',NULL,NULL,'text',NULL,1,NULL,NULL,2,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(25,5,'enrollment_class','শ্রেণী/বর্ষ',NULL,NULL,'text',NULL,0,NULL,NULL,3,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(26,5,'guardian_income','অভিভাবকের মাসিক আয় (টাকা)',NULL,NULL,'decimal',NULL,1,NULL,NULL,4,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(27,5,'scholarship_received','অন্য কোনো বৃত্তি পাচ্ছেন কিনা',NULL,NULL,'radio','[{\"value\": \"no\", \"label_bn\": \"না\"}, {\"value\": \"yes\", \"label_bn\": \"হ্যাঁ (বিস্তারিত)\"}]',1,NULL,NULL,5,1,'2026-06-15 12:27:56','2026-06-15 12:27:56'),(28,6,'travel_purpose','সফরের উদ্দেশ্য',NULL,NULL,'select','[{\"value\": \"medical\", \"label_bn\": \"চিকিৎসা\"}, {\"value\": \"education\", \"label_bn\": \"শিক্ষা\"}, {\"value\": \"business\", \"label_bn\": \"ব্যবসা\"}, {\"value\": \"family\", \"label_bn\": \"পারিবারিক\"}, {\"value\": \"disaster_affected\", \"label_bn\": \"দুর্যোগে ক্ষতিগ্রস্ত\"}]',1,NULL,NULL,1,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(29,6,'origin_district','স্থায়ী বাড়ির জেলা',NULL,NULL,'text',NULL,1,NULL,NULL,2,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(30,6,'needed_amount','প্রয়োজনীয় আনুমানিক পরিমাণ (টাকা)',NULL,NULL,'decimal',NULL,1,NULL,NULL,3,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(31,6,'emergency_contact','জরুরি যোগাযোগ (পরিবার/আত্মীয়)',NULL,NULL,'phone',NULL,1,NULL,NULL,4,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(32,6,'situation_details','বিস্তারিত পরিস্থিতি',NULL,NULL,'textarea',NULL,1,NULL,NULL,5,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(33,7,'situation_description','পরিস্থিতির বিস্তারিত বিবরণ',NULL,NULL,'textarea',NULL,1,NULL,NULL,1,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(34,7,'bond_amount','বন্ডের পরিমাণ (যদি প্রযোজ্য)',NULL,NULL,'decimal',NULL,0,NULL,NULL,2,1,'2026-06-15 12:27:57','2026-06-15 12:27:57'),(35,7,'witness_name','সাক্ষীর নাম ও মোবাইল',NULL,NULL,'text',NULL,0,NULL,NULL,3,1,'2026-06-15 12:27:57','2026-06-15 12:27:57');
/*!40000 ALTER TABLE `zakat_category_forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zakat_followups`
--

DROP TABLE IF EXISTS `zakat_followups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zakat_followups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `followup_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disbursement_id` bigint unsigned NOT NULL,
  `beneficiary_id` bigint unsigned NOT NULL,
  `distribution_id` bigint unsigned NOT NULL,
  `reporter_id` bigint unsigned NOT NULL,
  `reporter_type` enum('volunteer','imam','muezzin','org_admin','field_agent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imam',
  `organization_id` bigint unsigned DEFAULT NULL,
  `imam_muezzin_id` bigint unsigned DEFAULT NULL,
  `followup_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `next_followup_date` date DEFAULT NULL,
  `fund_usage_type` enum('medicine','food','debt_repaid','education','livelihood','housing','clothing','saved','misused','partially_misused','unknown') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `fund_usage_description_bn` text COLLATE utf8mb4_unicode_ci,
  `current_condition` enum('improved','same','worse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'same',
  `condition_notes_bn` text COLLATE utf8mb4_unicode_ci,
  `needs_further_support` tinyint(1) NOT NULL DEFAULT '0',
  `further_support_reason_bn` text COLLATE utf8mb4_unicode_ci,
  `misuse_suspected` tinyint(1) NOT NULL DEFAULT '0',
  `misuse_description_bn` text COLLATE utf8mb4_unicode_ci,
  `photo_evidence_paths` json DEFAULT NULL,
  `status` enum('draft','submitted','reviewed','action_taken') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `review_notes_bn` text COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zakat_followups_followup_no_unique` (`followup_no`),
  KEY `zakat_followups_disbursement_id_foreign` (`disbursement_id`),
  KEY `zakat_followups_distribution_id_foreign` (`distribution_id`),
  KEY `zakat_followups_organization_id_foreign` (`organization_id`),
  KEY `zakat_followups_imam_muezzin_id_foreign` (`imam_muezzin_id`),
  KEY `zakat_followups_reviewed_by_foreign` (`reviewed_by`),
  KEY `zakat_followups_beneficiary_id_status_index` (`beneficiary_id`,`status`),
  KEY `zakat_followups_reporter_id_reporter_type_index` (`reporter_id`,`reporter_type`),
  KEY `zakat_followups_due_date_status_index` (`due_date`,`status`),
  CONSTRAINT `zakat_followups_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `zakat_followups_disbursement_id_foreign` FOREIGN KEY (`disbursement_id`) REFERENCES `disbursements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `zakat_followups_distribution_id_foreign` FOREIGN KEY (`distribution_id`) REFERENCES `distributions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `zakat_followups_imam_muezzin_id_foreign` FOREIGN KEY (`imam_muezzin_id`) REFERENCES `imam_muezzins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_followups_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_followups_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `zakat_followups_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zakat_followups`
--

LOCK TABLES `zakat_followups` WRITE;
/*!40000 ALTER TABLE `zakat_followups` DISABLE KEYS */;
/*!40000 ALTER TABLE `zakat_followups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zakat_verifications`
--

DROP TABLE IF EXISTS `zakat_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zakat_verifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `beneficiary_id` bigint unsigned NOT NULL,
  `case_id` bigint unsigned DEFAULT NULL,
  `verifier_id` bigint unsigned NOT NULL,
  `verifier_type` enum('volunteer','imam','muezzin','general_validator','org_admin','branch_admin','system_admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'volunteer',
  `organization_id` bigint unsigned DEFAULT NULL,
  `volunteer_id` bigint unsigned DEFAULT NULL,
  `imam_muezzin_id` bigint unsigned DEFAULT NULL,
  `verified_area_id` bigint unsigned NOT NULL,
  `is_within_authority` tinyint(1) NOT NULL DEFAULT '0',
  `authority_override` tinyint(1) NOT NULL DEFAULT '0',
  `override_by` bigint unsigned DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `gps_lat` decimal(10,7) DEFAULT NULL,
  `gps_lng` decimal(10,7) DEFAULT NULL,
  `photo_paths` json DEFAULT NULL,
  `household_condition_bn` text COLLATE utf8mb4_unicode_ci,
  `income_verified` tinyint(1) DEFAULT NULL,
  `identity_verified` tinyint(1) DEFAULT NULL,
  `category_appropriate` tinyint(1) DEFAULT NULL,
  `recommendation` enum('approve','reject','needs_more_info','reduce_amount') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'needs_more_info',
  `recommended_amount` decimal(15,2) DEFAULT NULL,
  `notes_bn` text COLLATE utf8mb4_unicode_ci,
  `up_reference_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `up_reference_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imam_reference_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neighbor_reference_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neighbor_reference_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('submitted','reviewed','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zakat_verifications_case_id_foreign` (`case_id`),
  KEY `zakat_verifications_organization_id_foreign` (`organization_id`),
  KEY `zakat_verifications_volunteer_id_foreign` (`volunteer_id`),
  KEY `zakat_verifications_imam_muezzin_id_foreign` (`imam_muezzin_id`),
  KEY `zakat_verifications_override_by_foreign` (`override_by`),
  KEY `zakat_verifications_reviewed_by_foreign` (`reviewed_by`),
  KEY `zakat_verifications_beneficiary_id_status_index` (`beneficiary_id`,`status`),
  KEY `zakat_verifications_verifier_id_verifier_type_index` (`verifier_id`,`verifier_type`),
  KEY `zakat_verifications_verified_area_id_index` (`verified_area_id`),
  CONSTRAINT `zakat_verifications_beneficiary_id_foreign` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `zakat_verifications_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_verifications_imam_muezzin_id_foreign` FOREIGN KEY (`imam_muezzin_id`) REFERENCES `imam_muezzins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_verifications_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_verifications_override_by_foreign` FOREIGN KEY (`override_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_verifications_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zakat_verifications_verified_area_id_foreign` FOREIGN KEY (`verified_area_id`) REFERENCES `geographic_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `zakat_verifications_verifier_id_foreign` FOREIGN KEY (`verifier_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `zakat_verifications_volunteer_id_foreign` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zakat_verifications`
--

LOCK TABLES `zakat_verifications` WRITE;
/*!40000 ALTER TABLE `zakat_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `zakat_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'zakat'
--

--
-- Dumping routines for database 'zakat'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-27  3:30:59
