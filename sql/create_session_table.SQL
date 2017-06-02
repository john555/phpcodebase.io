-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";

CREATE TABLE `session` (
  `id` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `expiry` int(10) UNSIGNED NOT NULL,
  primary key (id)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

-- ALTER TABLE `session`
--   ADD PRIMARY KEY (`id`);
