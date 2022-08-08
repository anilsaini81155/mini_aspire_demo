#database name
CREATE DATABASE `mini_proj`;

CREATE TABLE `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `password` varchar(256) DEFAULT '',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `user_type` enum('User','Admin') NOT NULL DEFAULT 'User',
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `mobile_no` (`mobile_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='List of all system users';


CREATE TABLE `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `token` varchar(250) NOT NULL DEFAULT '',
  `expires_at` timestamp NOT NULL , 
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `token_key1` FOREIGN KEY (`user_id`) REFERENCES `sys_user` (`id`) ON UPDATE CASCADE
  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='User Token Table';


CREATE TABLE `sys_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `config` json NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1  COMMENT='List of system config';




CREATE TABLE `loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_amount` float(12,4) DEFAULT NULL,
  `loan_tenure` int(10) DEFAULT NULL,
  `loan_status` enum('Pending','Approved','Closed') NOT NULL DEFAULT 'Pending',
   `emi_amount` float(12,4) DEFAULT NULL,
   `user_id` int(11) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`),
  CONSTRAINT `loan_key1` FOREIGN KEY (`user_id`) REFERENCES `sys_user` (`id`) ON UPDATE CASCADE   
)ENGINE=InnoDB  DEFAULT CHARSET=latin1;



CREATE TABLE `loan_repayment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emi_amount` float(12,4) DEFAULT NULL,
  `amount_received` float(12,4) DEFAULT NULL,
  `emi_date` date NOT NULL,
  `emi_status` enum('Paid','UnPaid') NOT NULL DEFAULT 'UnPaid',
   `loan_id` int(11) NOT NULL,
   `principal_outstanding` float(12,4) DEFAULT NULL,
   `post_payment_principal_outstanding` float(12,4) DEFAULT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `loan_id` (`loan_id`),
  CONSTRAINT `loan_repayment_key1` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`id`) ON UPDATE CASCADE   
)ENGINE=InnoDB  DEFAULT CHARSET=latin1;

#default entry for the admin user

INSERT INTO `sys_user` (`id`, `email`, `name`, `mobile_no`, `password`, `status`, `user_type`,  `is_deleted`, `created_at`, `updated_at`)
VALUES
	(1, 'admin@demoapp.com', 'admin', '7897891234', 'eyJpdiI6IlBuT0lqQ1BSZUhlcVVHcThJSXpCa2c9PSIsInZhbHVlIjoiT2FFSXZFalZxM1k1SjJQOGZFaFBhUT09IiwibWFjIjoiMzZmODc1MTQzNWE5M2Y3MTdhNjMwZGM2NDQ2ZWZiZGU2OWUwM2I5OTA0ZGFiNjRjYWQwYjVhNjQ2MTAwNjgzOCJ9', 'Active', 'Admin',  'False', '2022-08-08 14:53:06', '2022-08-08 14:53:26');


#default entry for the sys config 

INSERT INTO `sys_config` (`id`, `name`, `config`, `status`, `is_deleted`, `created_at`, `updated_at`)
VALUES
	(1, 'token_hash', '"qbTQB2F1Fzp"', 'Active', 'False', '2022-08-08 15:01:49', '2022-08-08 15:02:41');


