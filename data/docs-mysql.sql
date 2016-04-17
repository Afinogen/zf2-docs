-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 17 2016 г., 21:45
-- Версия сервера: 5.6.25
-- Версия PHP: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `zf2-doc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `doc`
--

CREATE TABLE IF NOT EXISTS `doc` (
  `doc_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `author_id` int(10) unsigned NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_register` timestamp NULL DEFAULT NULL,
  `register_number` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `is_agreed` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `is_executed` tinyint(1) NOT NULL DEFAULT '0',
  `responsible_id` int(11) DEFAULT NULL,
  `resolution` text,
  `period_execution` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `doc`
--

INSERT INTO `doc` (`doc_id`, `type`, `author_id`, `date_create`, `date_register`, `register_number`, `title`, `description`, `is_agreed`, `is_approved`, `is_executed`, `responsible_id`, `resolution`, `period_execution`, `keywords`) VALUES
(1, 1, 1, '2016-04-17 12:45:04', NULL, NULL, 'sdfsdfsdf', '', 0, 1, 0, 1, '', '', NULL),
(2, 1, 1, '2016-04-17 12:46:24', NULL, NULL, 'sdfsdfsdf', '', 0, 1, 0, 1, '', '', NULL),
(3, 1, 1, '2016-04-17 12:46:46', NULL, NULL, 'sdfsdfsdf', '', 0, 1, 0, 1, '', '', NULL),
(4, 1, 1, '2016-04-17 12:46:57', NULL, NULL, 'sdfsdfsdf', '', 0, 1, 0, 1, '', '', NULL),
(5, 1, 1, '2016-04-17 12:55:00', NULL, NULL, 'sdfasff', '', 0, 0, 0, NULL, '', '', NULL),
(6, 1, 1, '2016-04-17 12:56:02', NULL, NULL, 'sdfasff', '', 0, 1, 0, NULL, '', '', NULL),
(7, 1, 1, '2016-04-17 15:33:22', '2016-04-17 17:42:19', 'Ð¢Ð”000000007-Ð’', 'dfsfsdfsdf', '', 0, 0, 0, NULL, '', '', 'sdf,sd,dfgsd,gfsdg,sdgs,d');

-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `file_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `doc_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `file`
--

INSERT INTO `file` (`file_id`, `filename`, `date`, `doc_id`) VALUES
(4, 'wallpaper_480x800_14.jpg', '2016-04-17 17:11:20', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`) VALUES
(1, NULL, 'admin@mail.ru', 'Admin', '$2y$14$EQI.03Hk2Wk103FcA8gYV.WtLgcK0QNwYnGWil4wDFYIt7Vjs0Dt6', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_has_role`
--

CREATE TABLE IF NOT EXISTS `user_has_role` (
  `user_id` int(11) unsigned NOT NULL,
  `user_role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_has_role`
--

INSERT INTO `user_has_role` (`user_id`, `user_role_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `user_role_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`user_role_id`, `name`, `is_default`, `parent_id`) VALUES
(1, 'guest', 1, NULL),
(2, 'user', 0, 1),
(3, 'admin', 0, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `doc`
--
ALTER TABLE `doc`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `doc_id` (`doc_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD PRIMARY KEY (`user_id`,`user_role_id`),
  ADD KEY `role_id` (`user_role_id`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `doc`
--
ALTER TABLE `doc`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `file`
--
ALTER TABLE `file`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user_role`
--
ALTER TABLE `user_role`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `doc`
--
ALTER TABLE `doc`
  ADD CONSTRAINT `doc_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doc` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD CONSTRAINT `user_has_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_has_role_ibfk_2` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`user_role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
