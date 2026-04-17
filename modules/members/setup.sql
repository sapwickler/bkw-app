CREATE TABLE `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(65) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `trongate_user_id` int(11) DEFAULT NULL,
  `date_created` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add 'member' level to trongate_user_levels if it doesn't exist
-- Level 1 is usually 'admin', so we use Level 2 for 'member'
INSERT INTO `trongate_user_levels` (`id`, `level_title`) VALUES (2, 'member');
