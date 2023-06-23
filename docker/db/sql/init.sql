/* table for Image*/
DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `image_id` VARCHAR(23) NOT NULL,
  `image_name` varchar(256) NOT NULL,
  `image_type` varchar(64) NOT NULL,
  `image_content` MEDIUMBLOB,
  `image_size` int DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`)
) DEFAULT CHARSET=utf8mb4; 

/* table for user*/
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` VARCHAR(23) NOT NULL,
  `user_name` VARCHAR(30) NOT NULL,
  `password` VARCHAR(90) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) DEFAULT CHARSET=utf8mb4;

/* table for category*/
DROP TABLE IF EXISTS `category_list`;

CREATE TABLE `category_list` (
  `c_id` int NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(100) NOT NULL,
  UNIQUE KEY `c_id` (`c_id`),
  PRIMARY KEY (`c_id`)
) DEFAULT CHARSET=utf8mb4;

/* table for blog*/
DROP TABLE IF EXISTS `blogs`;

CREATE TABLE `blogs` (
  `blog_id` VARCHAR(23) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `thumnail_id` VARCHAR(23) NOT NULL,
  `c_id` int NOT NULL,
  UNIQUE KEY `blog_id` (`blog_id`),
  PRIMARY KEY (`blog_id`)
) DEFAULT CHARSET=utf8mb4;

/* table for image owner*/
DROP TABLE IF EXISTS `image_owner`;

CREATE TABLE `image_owner` (
  `album_id` VARCHAR(23) NOT NULL,
  `author_id` VARCHAR(23) NOT NULL
) DEFAULT CHARSET=utf8mb4;

/* table for blog owner*/
DROP TABLE IF EXISTS `blog_owner`;

CREATE TABLE `blog_owner` (
  `b_id` VARCHAR(23) NOT NULL,
  `author_id` VARCHAR(23) NOT NULL
) DEFAULT CHARSET=utf8mb4;

/* table for user thumnail */
DROP TABLE IF EXISTS `user_thumnail`;

CREATE TABLE `user_thumnail` (
  `u_id` VARCHAR(23) NOT NULL,
  `image_id` VARCHAR(23) NOT NULL,
  `image_name` varchar(256) NOT NULL,
  `image_type` varchar(64) NOT NULL,
  `image_content` MEDIUMBLOB,
  `image_size` int DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`, `u_id`)
) DEFAULT CHARSET=utf8mb4;