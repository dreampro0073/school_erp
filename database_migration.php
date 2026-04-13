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

//Dipanshu 16th mrach 2026
ALTER TABLE `users` CHANGE `parent_user_id` `parent_user_id` INT(11) NOT NULL DEFAULT '0';

// Devendra 16Mar2026
ALTER TABLE `worklog` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

// Devendra 18Mar2026
ALTER TABLE `years` ADD `start_date` DATE NULL DEFAULT NULL AFTER `period`, ADD `end_date` DATE NULL DEFAULT NULL AFTER `start_date`;
ALTER TABLE `days` ADD `name1l` VARCHAR(10) NULL DEFAULT NULL AFTER `name`, ADD `name3l` VARCHAR(10) NULL DEFAULT NULL AFTER `name1l`;
ALTER TABLE `client_standards` CHANGE `client_id` `client_id` INT(11) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `client_standards` ADD `status` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>active, 1=>Inactive' AFTER `section_id`;
ALTER TABLE `client_standards` ADD `is_verified` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>pedding, 1=>verified, -1=>blocked' AFTER `status`;
ALTER TABLE `users` CHANGE `erp_id` `erp_id` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `students` ADD `client_id` INT NULL DEFAULT NULL AFTER `parent_user_id`;
ALTER TABLE `students` CHANGE `admission_no` `admission_no` VARCHAR(50) NULL DEFAULT NULL;

ALTER TABLE `students` ADD `parent_user_id` INT NOT NULL DEFAULT '0' AFTER `user_id`;


CREATE TABLE `master_data` ( `master_id` INT NOT NULL , `name` VARCHAR(255) NOT NULL , `type` INT NOT NULL DEFAULT '0' COMMENT '1-->Category ,2--> Blood Group , 3-->gender-->' , `status` TINYINT NOT NULL DEFAULT '0' COMMENT '0-->active, 1-->inactive' ) ENGINE = InnoDB;


ALTER TABLE `master_data` ADD PRIMARY KEY( `master_id`);
ALTER TABLE `master_data` CHANGE `master_id` `master_id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `master_data` ADD `parent_master_id` INT NOT NULL DEFAULT '0' AFTER `master_id`;

ALTER TABLE `master_data` CHANGE `type` `type` INT(11) NOT NULL DEFAULT '0' COMMENT '1-->Religion ,2-->Caste,3--->gender, 4-->Blood Group';


ALTER TABLE `students` ADD `blood_group_id` INT NOT NULL DEFAULT '0' AFTER `permanent_address`;

ALTER TABLE `students` ADD `religion_id` INT NOT NULL DEFAULT '0' AFTER `blood_group_id`, ADD `cast_id` INT NOT NULL DEFAULT '0' AFTER `religion_id`;

ALTER TABLE `students` ADD `standard_id` INT NOT NULL DEFAULT '0' AFTER `erp_id`, ADD `section_id` INT NOT NULL DEFAULT '0' AFTER `standard_id`;


ALTER TABLE `students` ADD `height` VARCHAR(20) NULL DEFAULT NULL AFTER `blood_group_id`, ADD `weight` VARCHAR(20) NULL DEFAULT NULL AFTER `height`;

ALTER TABLE `students` ADD `previous_school` VARCHAR(255) NULL DEFAULT NULL AFTER `approved`, ADD `previous_school_address` VARCHAR(255) NULL DEFAULT NULL AFTER `previous_school`;

// Devendra 19Mar2026
ALTER TABLE `students` CHANGE `client_id` `school_id` INT(11) NULL DEFAULT NULL;

//Dipanshu 20th Mar 2026

ALTER TABLE `students` ADD `added_by` INT NULL DEFAULT NULL AFTER `section_id`;


ALTER TABLE `users` ADD `added_by` INT NOT NULL DEFAULT '0' AFTER `last_login`;

ALTER TABLE `students` ADD `added_by` INT NOT NULL DEFAULT '0' AFTER `cast_id`;
ALTER TABLE `parents` ADD `added_by` INT NOT NULL DEFAULT '0' AFTER `mother_aadhar_no`;

ALTER TABLE `students` CHANGE `parent_user_id` `parent_id` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `parents` CHANGE `client_id` `school_id` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `fees` ADD `school_id` INT NOT NULL DEFAULT '0' AFTER `id`, ADD `student_id` INT NOT NULL DEFAULT '0' AFTER `school_id`;


ALTER TABLE `fees` ADD INDEX( `school_id`, `student_id`, `standard_id`, `section_id`, `fee_type_id`,`added_by`);


ALTER TABLE `students` ADD INDEX( `school_id`, `user_id`, `parent_id`, `erp_id`, `standard_id`, `section_id`, `blood_group_id`, `religion_id`, `cast_id`, `added_by`);

ALTER TABLE `parents` ADD INDEX( `school_id`, `user_id`, `erp_id`, `added_by`);

// Devendra 22Mar2026

ALTER TABLE `client_standards` ADD `session_id` INT NULL DEFAULT NULL AFTER `section_id`;
ALTER TABLE `client_standards` CHANGE `is_verified` `is_verified` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0=>pedding, 1=>verified, -1=>blocked, -2 block after verified';
ALTER TABLE `client_standards` ADD `added_by` INT NULL DEFAULT NULL AFTER `is_verified`;

// Devendra 24Mar2026

CREATE TABLE `class_students` ( `id` INT NOT NULL AUTO_INCREMENT , `class_id` INT NULL DEFAULT NULL , `student_id` INT NULL DEFAULT NULL , `added_by` INT NULL DEFAULT NULL , `school_id` INT NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `class_subjects` ( `id` INT NOT NULL AUTO_INCREMENT , `class_id` INT NULL DEFAULT NULL , `subject_id` INT NULL DEFAULT NULL , `added_by` INT NULL DEFAULT NULL , `school_id` INT NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


ALTER TABLE `sections` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `students` ADD `student_photo` VARCHAR(255) NULL DEFAULT NULL AFTER `cast_id`, ADD `aadhar_card` VARCHAR(255) NULL DEFAULT NULL AFTER `student_photo`;


// Devendra 31Mar2026
ALTER TABLE `teachers` ADD `unique_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `teachers` ADD `added_by` INT NULL DEFAULT NULL AFTER `email`, ADD `first_name` VARCHAR(255) NULL DEFAULT NULL AFTER `added_by`, ADD `last_name` VARCHAR(255) NULL DEFAULT NULL AFTER `first_name`, ADD `aadhar_no` VARCHAR(20) NULL DEFAULT NULL AFTER `last_name`, ADD `gender` TINYINT(2) NULL DEFAULT NULL AFTER `aadhar_no`, ADD `mobile` VARCHAR(20) NULL DEFAULT NULL AFTER `gender`, ADD `pravious_school` VARCHAR(255) NULL DEFAULT NULL AFTER `mobile`, ADD `pravios_school_address` VARCHAR(255) NULL DEFAULT NULL AFTER `pravious_school`, ADD `residential_address` VARCHAR(255) NULL DEFAULT NULL AFTER `pravios_school_address`, ADD `permanent_address` VARCHAR(255) NULL DEFAULT NULL AFTER `residential_address`, ADD `blood_group_id` INT(4) NULL DEFAULT NULL AFTER `permanent_address`, ADD `height` VARCHAR(20) NULL DEFAULT NULL AFTER `blood_group_id`, ADD `weight` VARCHAR(20) NULL DEFAULT NULL AFTER `height`, ADD `religion_id` INT NULL DEFAULT NULL AFTER `weight`, ADD `cast_id` INT NULL DEFAULT NULL AFTER `religion_id`;

ALTER TABLE `teachers` ADD INDEX(`unique_id`);
ALTER TABLE `teachers` ADD INDEX(`user_id`);
ALTER TABLE `teachers` ADD INDEX(`school_id`);
ALTER TABLE `teachers` ADD INDEX(`added_by`);
ALTER TABLE `teachers` ADD INDEX(`blood_group_id`);
ALTER TABLE `teachers` ADD INDEX(`religion_id`);
ALTER TABLE `teachers` ADD INDEX(`cast_id`);

//Dipanshu Chauhan Apr 2026

CREATE TABLE fee_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    school_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(100) NOT NULL,
    is_recurring TINYINT(1) DEFAULT 0,
    description TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE fee_frequencies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    school_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(50) NOT NULL,
    months_count INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE fee_structures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    school_id BIGINT UNSIGNED NOT NULL,

    class_id BIGINT UNSIGNED NOT NULL,
    fee_type_id BIGINT UNSIGNED NOT NULL,
    frequency_id BIGINT UNSIGNED NULL,

    amount DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);




CREATE TABLE student_fee_assignments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    school_id BIGINT UNSIGNED NOT NULL,

    student_id BIGINT UNSIGNED NOT NULL,
    fee_structure_id BIGINT UNSIGNED NOT NULL,

    custom_amount DECIMAL(10,2) NULL,
    discount DECIMAL(10,2) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `fee_structures` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `fee_types` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE `fee_frequencies` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `student_fee_assignments` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE `fee_payments` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE `fee_structures` CHANGE `class_id` `standard_id` BIGINT(20) UNSIGNED NOT NULL;

CREATE TABLE payment_modes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,          -- Cash, UPI, Card, etc.
    code VARCHAR(50) UNIQUE NULL,        -- CASH, UPI, CARD (for internal use)
    description TEXT NULL,               -- optional details
    is_online TINYINT(1) DEFAULT 0,      -- 1 = online (UPI, NetBanking)
    status TINYINT(1) DEFAULT 1,         -- 1 = active, 0 = inactive
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO payment_modes (name, code, is_online) VALUES
('Cash', 'CASH', 0),
('UPI', 'UPI', 1),
('Debit Card', 'DEBIT_CARD', 1),
('Credit Card', 'CREDIT_CARD', 1),
('Net Banking', 'NET_BANKING', 1),
('Cheque', 'CHEQUE', 0),
('DD', 'DEMAND_DRAFT', 0),
('Wallet', 'WALLET', 1);
ALTER TABLE `payment_modes` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE `fee_payments` ADD `payment_mode` INT NOT NULL DEFAULT '0' AFTER `status`;

ALTER TABLE `fee_payments` ADD `transction_id` VARCHAR(255) NULL DEFAULT NULL AFTER `payment_mode`, ADD `cheque_no` VARCHAR(50) NULL DEFAULT NULL AFTER `transction_id`;

ALTER TABLE `fee_payments` ADD `remarks` TEXT NULL DEFAULT NULL AFTER `cheque_no`;

ALTER TABLE `fee_payments` ADD `added_by` INT NOT NULL DEFAULT '0' AFTER `transction_id`;

ALTER TABLE `fee_payments` ADD `standard_id` INT NOT NULL DEFAULT '0' AFTER `school_id`;


ALTER TABLE `teachers` ADD `father_name` VARCHAR(255) NULL DEFAULT NULL AFTER `resign_date`, ADD `father_mobile` VARCHAR(20) NULL DEFAULT NULL AFTER `father_name`, ADD `father_email` VARCHAR(255) NULL DEFAULT NULL AFTER `father_mobile`, ADD `father_aadhar_no` VARCHAR(20) NULL DEFAULT NULL AFTER `father_email`, ADD `mother_name` VARCHAR(255) NULL DEFAULT NULL AFTER `father_aadhar_no`, ADD `mother_mobile` VARCHAR(20) NULL DEFAULT NULL AFTER `mother_name`, ADD `mother_email` VARCHAR(100) NULL DEFAULT NULL AFTER `mother_mobile`, ADD `mother_aadhar_no` VARCHAR(20) NULL DEFAULT NULL AFTER 'mother_email';

ALTER TABLE `teachers` ADD `active` TINYINT(2) NOT NULL DEFAULT '0' AFTER `mother_aadhar_no`;
ALTER TABLE `teachers` CHANGE `gender` `gender` VARCHAR(22) NULL DEFAULT NULL;


// Divandra

INSERT INTO days (name, `name3l`, `name1l`) VALUES
('Monday', 'Mon', 'M'),
('Tuesday', 'Tue', 'T'),
('Wednesday', 'Wed', 'W'),
('Thursday', 'Thu', 'T'),
('Friday', 'Fri', 'F'),
('Saturday', 'Sat', 'S'),
('Sunday', 'Sun', 'S');
ALTER TABLE `class_schedule` ADD `client_id` INT NULL DEFAULT NULL AFTER `remarks`;
ALTER TABLE `subjects` ADD `client_id` INT NULL DEFAULT NULL AFTER `status`;

ALTER TABLE `standards` ADD `is_verified` TINYINT NOT NULL DEFAULT '0' COMMENT ' 0=>pedding, 1=>verified, -1=>blocked, -2 block after verified' AFTER `status`;

// Devenrda
ALTER TABLE `fee_structures` ADD `status` TINYINT NOT NULL DEFAULT '0' COMMENT '0-active, 1-inactive' AFTER `amount`;

ALTER TABLE `fee_structures` ADD `fin_year` INT NULL DEFAULT NULL AFTER `amount`;

CREATE TABLE `class_subjects` ( `id` INT NOT NULL AUTO_INCREMENT , `subject_id` INT NULL DEFAULT NULL , `class_id` INT NULL DEFAULT NULL , `school_id` INT NULL DEFAULT NULL , `added_by` INT NULL DEFAULT NULL , `status` TINYINT NOT NULL DEFAULT '0' , `created_at` TIMESTAMP NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `class_subjects` ADD `book_name` VARCHAR(255) NULL DEFAULT NULL AFTER `school_id`, ADD `published_by` VARCHAR(255) NULL DEFAULT NULL AFTER `book_name`;
ALTER TABLE `fee_structures` ADD COLUMN `class_id` BIGINT UNSIGNED NULL AFTER `school_id`;
ALTER TABLE `fee_structures`
  MODIFY `standard_id` BIGINT UNSIGNED NULL;




UPDATE `fee_structures`
SET `class_id` = `standard_id`
WHERE `class_id` IS NULL OR `class_id` = 0;
ALTER TABLE `fee_structures`
  MODIFY `class_id` BIGINT UNSIGNED NOT NULL;

ALTER TABLE `fee_structures`
  DROP COLUMN `standard_id`;


// Devendra
RENAME TABLE `clients` TO `r_school_erp`.`schools`;
ALTER TABLE `schools` CHANGE `client_name` `school_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `schools` ADD `user_id` INT NULL DEFAULT NULL AFTER `id`;

//Dipanshu Chauhan 12th April 2026

ALTER TABLE `fee_payments` CHANGE `year` `fin_year` YEAR(4) NULL DEFAULT NULL;

//Uppper code deployed to PROD 

//Dipanshu Chauhan 13th April 2026
CREATE TABLE transport_routes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    school_id BIGINT UNSIGNED NOT NULL,

    route_name VARCHAR(100),
    amount DECIMAL(10,2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE `transport_routes` ADD `description` TEXT NULL DEFAULT NULL AFTER `amount`;

ALTER TABLE `transport_routes` ADD `status` TINYINT NOT NULL DEFAULT '0' AFTER `description`;

ALTER TABLE `transport_routes` ADD `frequency_id` INT NOT NULL DEFAULT '0' AFTER `school_id`;

// Devendra
ALTER TABLE `teachers`
  DROP `pravious_school`,
  DROP `pravios_school_address`;

ALTER TABLE `teachers` ADD `previous_school` VARCHAR(255) NULL DEFAULT NULL AFTER `mobile`, ADD `previous_school_address` VARCHAR(255) NULL DEFAULT NULL AFTER `previous_school`;