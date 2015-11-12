CREATE TABLE IF NOT EXISTS `article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_category` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `small_text` text,
  `text` text,
  `date_create` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_fk1` (`id_category`),
  CONSTRAINT `article_fk1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


INSERT INTO `article` (`id`, `id_category`, `title`, `small_text`, `text`, `date_create`, `is_active`) VALUES
	(1, 1, 'Заголовок статьи 1', 'Короткое описание статьи', 'Полный текст статьи', '2014-03-30', 1),
	(4, 1, 'Заголовок статьи 2', 'Короткое описание статьи', 'Полный текст статьи', '2014-03-30', 1),
	(5, 1, 'Заголовок статьи 3', 'Короткое описание статьи', 'Полный текст статьи', '2014-03-30', 1),
	(6, 2, 'Заголовок статьи 4', 'Короткое описание статьи', 'Полный текст статьи', '2014-03-30', 1),
	(7, 2, 'Заголовок статьи 5', 'Короткое описание статьи', 'Полный текст статьи', '2014-03-30', 1),
	(8, 2, 'Заголовок статьи 6', 'Короткое описание статьи', 'Полный текст статьи', '2014-03-30', 1),
	(9, 2, 'Заголовок статьи 7', 'Короткое описание статьи', 'Полный текст статьи', '2014-03-30', 1);

	
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


INSERT INTO `category` (`id`, `name`, `is_active`) VALUES
	(1, 'Категория 1', 1),
	(2, 'Категория 2', 1),
	(3, 'Категория 3', 1);
	
	
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) DEFAULT NULL,
  `text` text,
  `date_create` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
