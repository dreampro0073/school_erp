<?php 
ALTER TABLE `students` ADD `first_name` VARCHAR(255) NULL DEFAULT NULL AFTER `erp_id`, ADD `last_name` VARCHAR(255) NULL DEFAULT NULL AFTER `first_name`;

CREATE TABLE `worklog` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `client_id` BIGINT UNSIGNED NULL,
  `date` DATE NOT NULL,
  `remark` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `worklog_user_id_index` (`user_id`),
  KEY `worklog_client_id_index` (`client_id`),
  KEY `worklog_date_index` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance_statuses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `label` VARCHAR(60) NOT NULL,
  `badge_class` VARCHAR(50) NULL DEFAULT NULL,
  `bar_class` VARCHAR(50) NULL DEFAULT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_statuses_code_unique` (`code`),
  KEY `attendance_statuses_active_index` (`active`),
  KEY `attendance_statuses_sort_order_index` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `attendance_statuses`
(`code`, `label`, `badge_class`, `bar_class`, `sort_order`, `is_default`, `active`, `created_at`, `updated_at`)
VALUES
('present', 'Present', 'text-bg-success', 'bg-primary-600', 1, 1, 1, NOW(), NOW()),
('absent', 'Absent', 'text-bg-danger', 'bg-warning-600', 2, 0, 1, NOW(), NOW()),
('late', 'Late', 'text-bg-warning', 'bg-purple-600', 3, 0, 1, NOW(), NOW()),
('half_day', 'Half Day', 'text-bg-info', 'bg-success-600', 4, 0, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
`label` = VALUES(`label`),
`badge_class` = VALUES(`badge_class`),
`bar_class` = VALUES(`bar_class`),
`sort_order` = VALUES(`sort_order`),
`is_default` = VALUES(`is_default`),
`active` = VALUES(`active`),
`updated_at` = NOW();
