-- ==========================================================================
-- NVHSTUDIO ECOSYSTEM - CRÉATION COMPLÈTE DE L'ÉCOSYSTÈME DATABASE
-- Moteur cible : MariaDB / MySQL (Optimisé Production)
-- ==========================================================================

-- 1. Élimination d'un éventuel conflit et création de la base de données
CREATE DATABASE IF NOT EXISTS `nvhstudio_db`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- 2. Bascule immédiate du contexte vers la nouvelle base de données (CORRIGÉ)
USE `nvhstudio_db`;

-- 3. Création de la table 'contacts' conforme aux normes d'indexation ISO
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  INDEX `idx_contacts_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
