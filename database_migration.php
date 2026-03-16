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

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` BIGINT UNSIGNED NOT NULL,
  `receiver_id` BIGINT UNSIGNED NOT NULL,
  `client_id` BIGINT UNSIGNED NULL,
  `message` TEXT NOT NULL,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_sender_id_index` (`sender_id`),
  KEY `chat_messages_receiver_id_index` (`receiver_id`),
  KEY `chat_messages_client_id_index` (`client_id`),
  KEY `chat_messages_pair_created_idx` (`sender_id`, `receiver_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exam_marks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` BIGINT UNSIGNED NULL,
  `student_id` BIGINT UNSIGNED NOT NULL,
  `subject_id` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `marked_by` BIGINT UNSIGNED NULL,
  `exam_name` VARCHAR(120) NOT NULL,
  `exam_date` DATE NOT NULL,
  `total_marks` DECIMAL(8,2) NOT NULL DEFAULT 100.00,
  `obtained_marks` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  `remark` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_marks_client_id_index` (`client_id`),
  KEY `exam_marks_student_id_index` (`student_id`),
  KEY `exam_marks_subject_id_index` (`subject_id`),
  KEY `exam_marks_marked_by_index` (`marked_by`),
  KEY `exam_marks_exam_date_index` (`exam_date`),
  KEY `exam_marks_lookup_idx` (`client_id`, `student_id`, `subject_id`, `exam_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `teacher_salary_structures` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` BIGINT UNSIGNED NULL,
  `teacher_id` BIGINT UNSIGNED NOT NULL,
  `component_name` VARCHAR(120) NOT NULL,
  `component_type` ENUM('earning','deduction') NOT NULL DEFAULT 'earning',
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_salary_structures_client_id_index` (`client_id`),
  KEY `teacher_salary_structures_teacher_id_index` (`teacher_id`),
  KEY `teacher_salary_structures_client_teacher_index` (`client_id`, `teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `teacher_salary_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` BIGINT UNSIGNED NULL,
  `teacher_id` BIGINT UNSIGNED NOT NULL,
  `salary_month` DATE NOT NULL,
  `gross_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `deduction_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_date` DATE NULL,
  `payment_mode` VARCHAR(50) NULL,
  `transaction_ref` VARCHAR(120) NULL,
  `remark` TEXT NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_salary_logs_client_id_index` (`client_id`),
  KEY `teacher_salary_logs_teacher_id_index` (`teacher_id`),
  KEY `teacher_salary_logs_salary_month_index` (`salary_month`),
  KEY `teacher_salary_logs_lookup_index` (`client_id`, `teacher_id`, `salary_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `teacher_bank_details` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` BIGINT UNSIGNED NULL,
  `teacher_id` BIGINT UNSIGNED NOT NULL,
  `account_holder_name` VARCHAR(150) NOT NULL,
  `bank_name` VARCHAR(150) NOT NULL,
  `account_number` VARCHAR(50) NOT NULL,
  `ifsc_code` VARCHAR(20) NOT NULL,
  `branch_name` VARCHAR(150) NULL,
  `upi_id` VARCHAR(120) NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_bank_details_client_teacher_unique` (`client_id`, `teacher_id`),
  KEY `teacher_bank_details_client_id_index` (`client_id`),
  KEY `teacher_bank_details_teacher_id_index` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `expenses` (`name`, `active`, `created_at`, `updated_at`)
SELECT 'Salary', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `expenses` WHERE LOWER(`name`) = 'salary'
);

ALTER TABLE `users` ADD `end_date` DATE NULL DEFAULT NULL AFTER `active`;


// Devendra 09MAR2026

ALTER TABLE `users` CHANGE `perent_user_id` `parent_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `teacher_bank_details` CHANGE `teacher_id` `user_id` INT(11) NOT NULL;
RENAME TABLE `teacher_bank_details` TO `bank_details`;
RENAME TABLE `teacher_salary_structures` TO `salary_structures`;
ALTER TABLE `salary_structures` CHANGE `teacher_id` `user_id` INT NOT NULL;


//Dipanshu 11 mrach 2026
ALTER TABLE `students` ADD `gender` VARCHAR(50) NOT NULL DEFAULT '' AFTER `aadhar_no`;

ALTER TABLE `students` ADD `email` VARCHAR(255) NULL DEFAULT NULL AFTER `mobile`;
ALTER TABLE `students` ADD `admission_no` INT NOT NULL DEFAULT '0' AFTER `erp_id`;

ALTER TABLE `users` ADD `start_date` DATE NULL DEFAULT NULL AFTER `last_login`;

// Devendra 11MAR2026
RENAME TABLE `teacher_salary_logs` TO `salary_logs`;
ALTER TABLE `salary_logs` CHANGE `teacher_id` `user_id` BIGINT(20) UNSIGNED NOT NULL;
ALTER TABLE `privileges` ADD `plural_name` VARCHAR(50) NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `standards` ADD `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=>active, 1=>inactive' AFTER `name`;
ALTER TABLE `services` ADD `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`;
ALTER TABLE `services` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=>Active, 1=>Inactive';
ALTER TABLE `sections` ADD `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=>Active, 1=>Inactive' AFTER `name`;
ALTER TABLE `subjects` ADD `status` TINYINT(1) NULL DEFAULT '0' COMMENT '0=>active, 1=>Inactive' AFTER `name`;
ALTER TABLE `fee_types` ADD `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=>Active, 1=>Inactive' AFTER `name`;

ALTER TABLE `worklog` CHANGE `client_id` `hours` DOUBLE UNSIGNED NULL DEFAULT NULL;


ALTER TABLE `standards` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;

//Dipanshu 15 mrach 2026
ALTER TABLE `students` ADD `residential_address` TEXT NULL DEFAULT NULL AFTER `approved`, ADD `permanent_address` TEXT NULL DEFAULT NULL AFTER `residential_address`;

ALTER TABLE `parents` CHANGE `name` `father_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `mobile` `father_mobile` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `email` `father_email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `parents` ADD `father_aadhar_no` VARCHAR(50) NULL DEFAULT NULL AFTER `updated_at`, ADD `mother_name` VARCHAR(255) NULL DEFAULT NULL AFTER `father_aadhar_no`, ADD `mother_mobile` VARCHAR(50) NULL DEFAULT NULL AFTER `mother_name`, ADD `mother_email` VARCHAR(255) NULL DEFAULT NULL AFTER `mother_mobile`, ADD `mother_aadhar_no` VARCHAR(50) NULL DEFAULT NULL AFTER `mother_email`;

ALTER TABLE `students` ADD `dob` DATE NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `students` CHANGE `admission_no` `admission_no` INT(11) NULL DEFAULT NULL;

ALTER TABLE `parents` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `students` ADD `unique_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `parents` ADD `unique_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;

// Devendra 16Mar2026
ALTER TABLE `worklog` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;


