<?php
	include 'database.php';

	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		// set the PDO error mode to exception
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE DATABASE IF NOT EXISTS camagru_pkerckho";
		// use exec() because no results are returned
		$db->exec($sql);
		$sql = "USE camagru_pkerckho;
				CREATE TABLE `users` (`id` int(32) NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`),`username` varchar(255) NOT NULL,`password` varchar(255) NOT NULL, `avatar` VARCHAR(255) NULL, `email` varchar(255) NOT NULL, status tinyint(3)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `user_option` (`id` int(32) NOT NULL AUTO_INCREMENT , `username` varchar(255) NOT NULL, `question` varchar(255) NULL, `answer` varchar(255) NULL, PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `photos` (`id` int(32) NOT NULL AUTO_INCREMENT ,`photo_path` varchar(32) NOT NULL,`username` varchar(255) NOT NULL, `gallery` VARCHAR(16) NOT NULL, `filename` VARCHAR(255) NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `comments` (`id` int(32) NOT NULL AUTO_INCREMENT,`photo_id` int(11) NOT NULL,`users_id` int(11) NOT NULL,`comment` text, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `likes` (`photo_id` int(11) NOT NULL,`users_id` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				CREATE TABLE `status` (id INT(11) NOT NULL AUTO_INCREMENT, `osid` INT(11) NOT NULL, `account_name` VARCHAR(16) NOT NULL, `author` VARCHAR(16) NOT NULL, `type` ENUM('a','b','c') NOT NULL, `data` TEXT NOT NULL, `postdate` DATETIME NOT NULL, PRIMARY KEY (id))";
		$db->exec($sql);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
		die();
	}
?>
