<?php 

 // Devendra 02Nov2023

ALTER TABLE `sitting_entries` ADD `checkin_date` TIMESTAMP NULL DEFAULT NULL AFTER `check_in`;
ALTER TABLE `sitting_entries` ADD `show_amount` INT NULL DEFAULT '0' AFTER `paid_amount`;

CREATE TABLE `railway_master`.`checks` (`id` INT NOT NULL AUTO_INCREMENT , `entry_id` INT NOT NULL DEFAULT '0' , `slip_id` INT NOT NULL DEFAULT '0' , `time` TIMESTAMP NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `sitting_rate_list` ADD `adult_rate_sec` INT NOT NULL DEFAULT '0' AFTER `child_rate`;
ALTER TABLE `sitting_rate_list` ADD `child_rate_sec` INT NOT NULL DEFAULT '0' AFTER `adult_rate_sec`;

ALTER TABLE `massage_entries_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;

ALTER TABLE `locker_entries_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;

ALTER TABLE `massage_entries` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_entries` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_penalty` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_penalty_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `locker_penalty` ADD `is_backup` INT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `sitting_entries_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `sitting_entries` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `sitting_rate_list` ADD `adult_rate_sec` INT NOT NULL DEFAULT '0' AFTER `child_rate`, ADD `child_rate_sec` INT NOT NULL DEFAULT '0' AFTER `adult_rate_sec`;
ALTER TABLE `client_services` CHANGE `services_id` `services_id` INT(11) NOT NULL DEFAULT '0' COMMENT '1=sittinng,2=cloakroom, 3=canteen,4= Massage, 5=Locker';
CREATE TABLE `railway_master`.`check_status` (`id` INT NOT NULL AUTO_INCREMENT , `check_date_time` TIMESTAMP NULL DEFAULT NULL , `checked_by` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `massage_entries` ADD `slip_id` INT NOT NULL DEFAULT '0' AFTER `client_id`;




CREATE TABLE `locker_rate_list` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `first_rate` int(11) NOT NULL COMMENT 'perDay',
  `second_rate` int(11) NOT NULL COMMENT 'perDay'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `locker_rate_list`
--
ALTER TABLE `locker_rate_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `locker_rate_list`
--
ALTER TABLE `locker_rate_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


ALTER TABLE `daily_entries` CHANGE `check_in` `check_in` TIME NULL DEFAULT NULL;

CREATE TABLE `change_pay_type_log` ( `id` INT NOT NULL AUTO_INCREMENT , `sitting_id` INT NULL DEFAULT NULL , `old_pay_type` TINYINT(1) NOT NULL DEFAULT '0' , `new_pay_type` TINYINT(1) NOT NULL DEFAULT '0' , `changed_by` INT NULL DEFAULT NULL , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `change_pay_type_log` ADD `date` DATE NULL DEFAULT NULL AFTER `new_pay_type`;

ALTER TABLE `change_pay_type_log` ADD `e_entry_id` INT NULL DEFAULT NULL AFTER `sitting_id`;

ALTER TABLE `sitting_entries` ADD `alert_count` TINYINT(1) NOT NULL DEFAULT '0' AFTER `m_slip`;

ALTER TABLE `users` ADD `is_auto_alert_access` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=>false, 1=>true' AFTER `last_login`;

ALTER TABLE `users` ADD `org_id` INT NULL DEFAULT NULL AFTER `last_login`;
ALTER TABLE `clients` ADD `org_id` INT NULL DEFAULT NULL AFTER `address`;

ALTER TABLE `clients` ADD `client_name` VARCHAR(255) NULL DEFAULT NULL AFTER `name`;
ALTER TABLE `daily_entry_items` ADD `client_id` INT NULL DEFAULT NULL AFTER `entry_id`;
ALTER TABLE `lockers` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `locker_no`;
UPDATE lockers SET client_id = 1;

ALTER TABLE `canteen_items` ADD `is_manual` TINYINT NOT NULL DEFAULT '0' AFTER `added_by`;
ALTER TABLE `canteen_items` ADD `barvalue_avail` TINYINT NOT NULL DEFAULT '1' AFTER `is_manual`;
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CREAM BUN', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'BROWNI', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CREAM ROLL', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG PATIES', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER PATIES', 40,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CUP CAKE', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PESTRY', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'HOTDOG', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PIZZA BASKET', 70,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SWEET PUFF', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER PAW ROLL', 70,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'ONION RINGS', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG WRAP', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'DONUT', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'BAKED SAMOSA', 25,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER WRAP', 70, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG SANDWICH', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHEESE SANDWICH', 70,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG TIKKA SANDWICH', 60, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PANEER TIKKA SANDWICH',0, 80,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SLICE CAKE', 120,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'PIZZA', 100,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG BURGER', 50,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHEESE BURGER', 60,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHEESE COOKIE', 130,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SEB COOKIE', 110,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHOCO CHIPS COOKIES',0, 130,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'JEERA COOKIE', 120,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'VEG MOMO', 60, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHICKEN MOM', 70, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'NORTH INDIAN VEG THALI',0, 140,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'NORTH INDIA NON-VEG THALI',0, 180,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'EGG CURRY WITH RICE', 100,0, 1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'CHICKEN CURRY WITH RICE',0, 150, 1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'RED TEA', 30, 1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SPECIAL TEA', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'SPECIAL TEA -SUGAR FREE',0, 30,1);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'BLACK TEA', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'GREEN TEA', 30,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'COFFEE', 20,1,0);
INSERT INTO canteen_items(canteen_id,item_name,price,is_manual,barvalue_avail) VALUES (2,'COFFEE CAPACHINO', 30,1,0);

CREATE TABLE `total_incomes` ( `id` INT NOT NULL , `date` DATE NULL DEFAULT NULL , `total_amount` INT NOT NULL , `advance_amount` INT NOT NULL , `back_balance` INT NOT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `total_incomes` ADD `added_by` INT NOT NULL DEFAULT '0' AFTER `back_balance`;
ALTER TABLE `total_incomes` ADD `all_total` INT NOT NULL DEFAULT '0' AFTER `back_balance`;
ALTER TABLE `total_incomes` CHANGE `total_amount` `total_amount` INT(11) NOT NULL DEFAULT '0', CHANGE `back_balance` `back_balance` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `incomes` ADD `income_id` INT NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `client_services` CHANGE `services_id` `services_id` INT(11) NOT NULL DEFAULT '0' COMMENT '1=sittinng,2=cloakroom, 3=canteen,4= Massage, 5=Locker,6=Ledger account';


CREATE TABLE `incomes` ( `id` INT NOT NULL AUTO_INCREMENT , `client_id` INT NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `cash_amount` DOUBLE NOT NULL DEFAULT '0' , `upi_amount` DOUBLE NOT NULL DEFAULT '0' , `total_amount` DOUBLE NOT NULL DEFAULT '0' , `back_balance` DOUBLE NOT NULL DEFAULT '0' , `day_total` DOUBLE NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `income_entries` ( `id` INT NOT NULL AUTO_INCREMENT , `income_id` INT NOT NULL DEFAULT '0' , `client_id` INT NOT NULL DEFAULT '0' , `service_id` INT NOT NULL DEFAULT '0' , `cash_amount` DOUBLE NOT NULL DEFAULT '0' , `upi_amount` DOUBLE NOT NULL DEFAULT '0' , `total_amount` DOUBLE NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `income_entries` ADD `remarks` VARCHAR(255) NULL DEFAULT NULL AFTER `date`;

ALTER TABLE `income_entries` ADD `source` VARCHAR(255) NULL DEFAULT NULL AFTER `service_id`;

//DIpanshu 29th Sep 2024

CREATE TABLE `nnhp`.`godowns` ( `id` INT NOT NULL , `org_id` INT NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `client_item_id` INT NOT NULL DEFAULT '0' , `stock` INT NOT NULL , `status` TINYINT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL ) ENGINE = InnoDB;

CREATE TABLE `nnhp`.`transfer_godown_stocks` ( `id` INT NOT NULL , `org_id` INT NOT NULL DEFAULT '0' , `client_id` INT NOT NULL DEFAULT '0' , `canteen_item_id` INT NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `stock` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT 
  
ALTER TABLE `users` ADD `godown_id` INT NOT NULL DEFAULT '0' AFTER `org_id`;
ALTER TABLE `canteen_items` ADD `godown_id` INT NOT NULL DEFAULT '0' AFTER `client_id`;

ALTER TABLE `cloakroom_rate_list` ADD `type` FLOAT NOT NULL DEFAULT '1' AFTER `second_rate`;
ALTER TABLE `clients` ADD `rate_type` INT NOT NULL DEFAULT '1' AFTER `org_id`;

// Devendra 15Nov2024

ALTER TABLE `clients` ADD `rate_type` TINYINT NOT NULL DEFAULT '1' AFTER `org_id`;
ALTER TABLE `cloakroom_entries` CHANGE `total_day` `total_day` FLOAT NOT NULL DEFAULT '0';

// Devendra 17Dec2024

CREATE TABLE `aadhar_details` ( `id` INT NOT NULL AUTO_INCREMENT , `aadhar_no` INT NULL , `name` VARCHAR(255) NULL DEFAULT NULL , `mobile` VARCHAR(20) NULL DEFAULT NULL , `front` VARCHAR(255) NULL DEFAULT NULL , `back` VARCHAR(255) NULL DEFAULT NULL , `father_name` VARCHAR(255) NULL DEFAULT NULL , `address` VARCHAR(255) NULL DEFAULT NULL , `created_at` TIMESTAMP NULL DEFAULT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `aadhar_details` CHANGE `aadhar_no` `aadhar_no` BIGINT NULL DEFAULT NULL;
ALTER TABLE `cloakroom_entries` ADD `aadhar_no` BIGINT NULL DEFAULT NULL AFTER `pnr_uid`;
ALTER TABLE `aadhar_details` ADD `upload_status` TINYINT NOT NULL DEFAULT '0' AFTER `address`;

ALTER TABLE `cloakroom_entries_backup` ADD `is_backup` TINYINT NOT NULL DEFAULT '0' AFTER `checkout_by`;
ALTER TABLE `cloakroom_penalities_backup` ADD `old_cloakroom_id` INT NOT NULL DEFAULT '0' AFTER `cloakroom_id`;

ALTER TABLE `aadhar_details` CHANGE `aadhar_no` `aadhar_no` VARCHAR(50) NULL DEFAULT NULL;

ALTER TABLE `cloakroom_entries` CHANGE `aadhar_no` `aadhar_no` VARCHAR(50) NULL DEFAULT NULL;

// Devendra 26Dec2024
ALTER TABLE `users` CHANGE `active` `active` TINYINT(2) NULL DEFAULT '1';
UPDATE users SET users.active = 1
ALTER TABLE `aadhar_details` CHANGE `aadhar_no` `aadhar_no` VARCHAR(50) NULL DEFAULT NULL;



ALTER TABLE `users_backup` ADD `lounge_id` INT NOT NULL DEFAULT '0' AFTER `enc_id`, ADD `old_id` INT NOT NULL DEFAULT '0' AFTER `lounge_id`, ADD `canteen_id` INT NOT NULL DEFAULT '0' AFTER `old_id`;

ALTER TABLE `users_backup` CHANGE `parent_user_id` `perent_user_id` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `users_backup` ADD `org_id` INT NOT NULL DEFAULT '0' AFTER `perent_user_id`, ADD `is_auto_alert_access` TINYINT(1) NOT NULL DEFAULT '0' AFTER `org_id`;



ALTER TABLE `client_services` CHANGE `services_id` `services_id` INT(11) NOT NULL DEFAULT '0' COMMENT '1=sittinng,2=cloakroom, 3=canteen,4= Massage, 5=Locker,6=Ledger account, 7 = Recliners, 8 = Rooms';

ALTER TABLE `room_entries` DROP `deleted`, DROP `delete_by`, DROP `delete_time`;

ALTER TABLE `room_entries` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `room_e_entries` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `pods` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `single_cabins` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `double_beds` ADD `client_id` INT NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `client_services` ADD `capacity` INT NOT NULL DEFAULT '0' AFTER `status`;

CREATE TABLE `client_setting` ( `id` INT NOT NULL AUTO_INCREMENT , `client_id` INT NOT NULL DEFAULT '0' , `amount` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `client_setting` ADD `org_id` INT NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `clients` ADD `hide_amount` INT NOT NULL AFTER `rate_type`;

ALTER TABLE `users` ADD `is_super` TINYINT NOT NULL DEFAULT '0' AFTER `perent_user_id`;


CREATE TABLE `scanning_entries` ( `id` INT NOT NULL , `client_id` INT NOT NULL DEFAULT '0' , `unique_id` INT NULL DEFAULT NULL , `barcodevalue` VARCHAR(255) NULL DEFAULT NULL , `name` VARCHAR(255) NULL DEFAULT NULL , `no_of_item` INT NOT NULL DEFAULT '0' , `item_type` INT NOT NULL DEFAULT '0' , `incoming_type` TINYINT NOT NULL DEFAULT '0' , `date_time` DATETIME NULL DEFAULT NULL , `pay_type` TINYINT NOT NULL DEFAULT '0' , `added_by` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL ) ENGINE = InnoDB;
ALTER TABLE `scanning_entries` ADD `paid_amount` VARCHAR(20) NULL DEFAULT NULL AFTER `incoming_type`;
ALTER TABLE `scanning_entries` ADD PRIMARY KEY( `id`);
ALTER TABLE `scanning_entries` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `scanning_entries` ADD `date` DATE NULL DEFAULT NULL AFTER `paid_amount`;

CREATE TABLE `scanning_rate_list` ( `id` INT NOT NULL AUTO_INCREMENT , `client_id` INT NOT NULL DEFAULT '0' , `item_type_id` TINYINT NOT NULL DEFAULT '0' , `incoming_type_id` TINYINT NOT NULL DEFAULT '0' , `rate` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `scanning_item_types` ( `id` INT NOT NULL AUTO_INCREMENT , `item_type_name` VARCHAR(255) NULL DEFAULT NULL , `status` TINYINT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `scanning_rate_list` CHANGE `incoming_type` `incoming_type` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1-> Outword, 2-> Inword';

ALTER TABLE `scanning_rate_list` CHANGE `rate` `rate` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `scanning_entries` CHANGE `item_type` `item_type_id` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `scanning_entries` CHANGE `incoming_type` `incoming_type_id` TINYINT(4) NOT NULL DEFAULT '0';

ALTER TABLE `scanning_entries` ADD `print_count` INT NOT NULL DEFAULT '0' AFTER `added_by`, ADD `max_print` INT NOT NULL DEFAULT '2' AFTER `print_count`;

ALTER TABLE `scanning_entries` ADD `qr_print_count` INT NOT NULL DEFAULT '0' AFTER `max_print`, ADD `max_qr_count` INT NOT NULL DEFAULT '2' AFTER `qr_print_count`;


ALTER TABLE `clients` CHANGE `hide_amount` `hide_amount` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `clients` ADD `create_date` DATE NULL DEFAULT NULL AFTER `hide_amount`;
//Dipanshu Chauhan 11th Apr 2025
ALTER TABLE `scanning_entries` ADD `train_no` INT NULL DEFAULT NULL AFTER `name`;


CREATE TABLE `rest_entries` ( `id` INT NOT NULL , `client_id` INT NOT NULL DEFAULT '0' , `no_of_hours` INT NOT NULL DEFAULT '0' , `date` DATE NULL DEFAULT NULL , `paid_amount` INT NULL DEFAULT NULL , `pay_type` INT NOT NULL DEFAULT '0' , `added_by` INT NULL DEFAULT '0' , `updated_at` DATETIME NOT NULL , `created_at` DATETIME NOT NULL , `time` TIME NULL DEFAULT NULL ) ENGINE = InnoDB;


//Dipanshu Chauhan 20th May 2025

ALTER TABLE `rest_entries` ADD `no_of_people` INT NOT NULL DEFAULT '0' AFTER `no_of_hours`;

CREATE TABLE `login_logs` (`id` INT NOT NULL AUTO_INCREMENT , `client_id` INT NOT NULL DEFAULT '0' , `user_id` INT NOT NULL DEFAULT '0' , `login_time` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `login_logs` ADD `ip` VARCHAR(255) NULL DEFAULT NULL AFTER `login_time`;

// Devendra 26May2025
ALTER TABLE `clients` ADD `max_users` INT NULL DEFAULT '0' AFTER `org_id`, ADD `max_logins` INT NULL DEFAULT '0' AFTER `max_users`;

CREATE TABLE `login_token` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `client_id` INT NOT NULL , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `clients` ADD `org_id` INT NOT NULL DEFAULT '0' AFTER `address`;

ALTER TABLE `rest_entries` ADD `checkout_date` TIMESTAMP NULL DEFAULT NULL AFTER `date_time`;

//Dipanshu 13th June

ALTER TABLE `sitting_entries` CHANGE `unique_id` `unique_id` VARCHAR(55) NULL DEFAULT NULL;

ALTER TABLE `sitting_entries` CHANGE `unique_id` `unique_id` BIGINT(55) NULL DEFAULT NULL;

ALTER TABLE `sitting_entries` CHANGE `unique_id` `unique_id` VARCHAR(255) NULL DEFAULT NULL;

//Dipanshu Chauhan

ALTER TABLE `room_entries` ADD `checkout_by` INT NOT NULL DEFAULT '0' AFTER `added_by`;

CREATE TABLE `nnhp`.`web_at` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL DEFAULT '0' , `file_path` VARCHAR(255) NULL DEFAULT NULL , `status` INT NOT NULL DEFAULT '0' , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_at` TIMESTAMP NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `clients` ADD `print_name` TINYINT NOT NULL DEFAULT '0' COMMENT '0->Yes,1->No' AFTER `create_date`;

?>