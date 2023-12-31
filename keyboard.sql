CREATE TABLE `keyboard` (
    `user` TINYINT(1) NOT NULL UNIQUE,
    `is_active` TINYINT(1) DEFAULT 0,
    `key_1` CHAR(6) DEFAULT NULL,
    `key_2` CHAR(6) DEFAULT NULL,
    `key_3` CHAR(6) DEFAULT NULL,
    `key_4` CHAR(6) DEFAULT NULL,
    `key_5` CHAR(6) DEFAULT NULL,
    `key_6` CHAR(6) DEFAULT NULL,
    `key_7` CHAR(6) DEFAULT NULL,
    `key_8` CHAR(6) DEFAULT NULL,
    `key_9` CHAR(6) DEFAULT NULL,
    `key_10` CHAR(6) DEFAULT NULL,
    `control` TINYINT(1) DEFAULT 0,
    `color` CHAR(6) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)