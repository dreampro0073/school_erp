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
