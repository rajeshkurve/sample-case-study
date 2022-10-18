SET NAMES utf8;

DROP TABLE IF EXISTS `policy`;
CREATE TABLE `policy` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `policy_number` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `premium` double(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `policy` (`id`, `first_name`, `last_name`, `policy_number`, `start_date`, `end_date`, `premium`, `created_at`, `updated_at`) VALUES
(1,	'Rajesh',	'Kurve',	'A3123239090',	'2020-10-12',	'2032-10-11',	8000.00,	'2022-10-16 14:56:48',	'2022-10-16 14:56:48'),
(2,	'Aarya',	'Soni',	'43443',	'2022-10-17',	'2022-10-11',	24242.00,	'2022-10-17 09:11:32',	'2022-10-17 18:46:06'),
(3,	'Bhaawan',	'Surname',	'24242',	'2022-10-17',	'2022-10-29',	2323.00,	'2022-10-17 09:34:42',	'2022-10-17 09:34:42')