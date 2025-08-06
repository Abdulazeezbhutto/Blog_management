/*
SQLyog Trial v13.1.8 (64 bit)
MySQL - 10.4.32-MariaDB : Database - blog_system
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`blog_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `blog_system`;

/*Table structure for table `post` */

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_title` varchar(250) DEFAULT NULL,
  `post_summary` varchar(500) DEFAULT NULL,
  `post_description` text DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `post_status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` time DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `cat_id` (`cat_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `post_ibfk_3` FOREIGN KEY (`cat_id`) REFERENCES `post_category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post` */

insert  into `post`(`post_id`,`cat_id`,`user_id`,`post_title`,`post_summary`,`post_description`,`featured_image`,`post_status`,`created_at`,`updated_at`) values 
(1,1,1,'learn java in 30 days','okay','hey there how are you','uploads/1754393764_logo.png','active','2025-08-05 04:38:56',NULL),
(2,2,1,'Icreativez','Softaware Company','asjhd asjfd sfjdsaf sdj cjhsdf adjfs djavsc sdnvgadisfds csdayudsbj vdsjiyadsv','uploads/1754394234_OIP.jpeg','active','2025-08-05 04:44:22',NULL),
(3,2,1,'The Silent Language of Art: Expressing the Inexpressible','Art is a powerful form of expression that goes beyond words.','Art is more than brushstrokes on canvas or shapes molded from clay — it\'s a universal language that speaks where words fall short. It captures human emotion, history, identity, and imagination in ways that transcend time and culture. From abstract expressionism to digital design, every piece of art tells a story, provokes thought, and invites personal interpretation. In a world driven by logic and reason, art offers a space to feel, reflect, and connect with our deeper selves.\r\n','uploads/1754395629_art.jpg','active','2025-08-05 05:10:06',NULL);

/*Table structure for table `post_category` */

DROP TABLE IF EXISTS `post_category`;

CREATE TABLE `post_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_category` */

insert  into `post_category`(`cat_id`,`cate_name`,`created_at`,`updated_at`) values 
(1,'Technology',NULL,NULL),
(2,'Art',NULL,NULL),
(3,'Science',NULL,NULL),
(4,'Sports',NULL,NULL);

/*Table structure for table `post_tags` */

DROP TABLE IF EXISTS `post_tags`;

CREATE TABLE `post_tags` (
  `post_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`post_tag_id`),
  KEY `tag_id` (`tag_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_tags` */

insert  into `post_tags`(`post_tag_id`,`post_id`,`tag_id`) values 
(3,1,1),
(4,1,2),
(5,2,1),
(6,2,2);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(250) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `roles` */

insert  into `roles`(`role_id`,`role_name`) values 
(1,'Admin'),
(2,'user');

/*Table structure for table `tags` */

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tags` */

insert  into `tags`(`tag_id`,`tag_name`) values 
(1,'PHP'),
(2,'JS'),
(3,'JAVA'),
(4,'python'),
(5,'HTML'),
(6,'MERN'),
(7,'LARAVEL'),
(8,'SEO');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(250) DEFAULT NULL,
  `middle_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `date_of_birth` varchar(250) DEFAULT NULL,
  `image_path` varchar(250) DEFAULT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `contact_no` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` varchar(250) DEFAULT NULL,
  `role_id` int(11) DEFAULT 2,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`first_name`,`middle_name`,`last_name`,`email`,`password`,`gender`,`date_of_birth`,`image_path`,`address`,`contact_no`,`created_at`,`updated_at`,`role_id`) values 
(1,'Abdul','Azeez','Bhutto','abdulazeezbhutto085@gmail.com','$2y$10$rPJjmIIR3wrQaSddZr3ihOBnXBib8S.GYiLt0Kh.cI3uaDOlgaWFu','male','2003-03-10','uploads/1754392234OIP.jpeg','Baharia Town Karachi','03239265024','2025-08-05 04:18:26',NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
