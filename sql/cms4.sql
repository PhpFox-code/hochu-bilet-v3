-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Фев 27 2015 г., 08:34
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `cms4`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `text` text,
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `image` varchar(255) DEFAULT NULL,
  `show_image` tinyint(1) NOT NULL DEFAULT '1',
  `views` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `created_at`, `updated_at`, `status`, `name`, `alias`, `text`, `h1`, `title`, `description`, `keywords`, `image`, `show_image`, `views`) VALUES
(2, 1421690767, 1424962252, 1, 'Мужская ASICS ® GEL-STRATUS ® 2.1 : легкая модель  с исключительной амортизацию и гибкость.', 'muzhskaja_asics_gel-stratus___legkaja_model__s_iskljuchitelnoj_amortizaciju_i_gibkost.', '<p>&nbsp;Мужская ASICS &reg; GEL-STRATUS &reg; 2.1 :&nbsp;легкая модель &nbsp;с исключительной амортизацию и гибкость.&nbsp;Два улучшение вы заметите в мужском ASICS &reg; GEL-Stratus &reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&nbsp;11.3 унцийМужская ASICS &reg; GEL-STRATUS &reg; 2.1 :&nbsp;легкая модель &nbsp;с исключительной амортизацию и гибкость.&nbsp;Два улучшение вы заметите в мужском ASICS &reg; GEL-Stratus &reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&nbsp;11.3 унцийМужская ASICS &reg; GEL-STRATUS &reg; 2.1 :&nbsp;легкая модель &nbsp;с исключительной амортизацию и гибкость.&nbsp;Два улучшение вы заметите в мужском ASICS &reg; GEL-Stratus &reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&nbsp;11.3 унций</p>', 'BAIT X ASICS GEL SAGA', 'BAIT X ASICS GEL SAGA', 'BAIT X ASICS GEL SAGA\r\n\r\n', 'BAIT X ASICS GEL SAGA\r\n\r\n', 'f6a9e5e410696f40921085b9e9ff0029.jpg', 1, 37),
(3, 1424962272, NULL, 1, 'dfgdfgdfgdf', 'muzhskaja_asics_gel-stratus___legkaja_model__s_iskljuchitelnoj_amortizaciju_i_gibkost.9984', '', '', '', '', '', '8b12fe1176452639dd04b23b4170a2a7.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `big` varchar(255) DEFAULT NULL,
  `small` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `banners`
--

INSERT INTO `banners` (`id`, `created_at`, `updated_at`, `status`, `big`, `small`, `url`, `image`) VALUES
(2, 1421678716, 1423055055, 1, 'прогулочную коллекцию', 'скидки на', 'faq', '4a740516998604d3dd161169b5038086.jpg'),
(3, 1421689827, 1423226820, 1, 'прогулочную коллекцию', 'скидки на', '', 'c861efedb86b74ef04e60e64f61491e5.jpg'),
(4, 1421689846, 1423471841, 1, 'на мужскую коллекцию - обувь, одежда, акксесуары', 'Самые низкие цены только сегодня', 'http://airpac.php5.ks.ua/articles/bait_x_asics_gel_saga2', '4085771ce3cf8a63515aa2b99578fbdc.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(10) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `text` text,
  `alias` varchar(255) NOT NULL,
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `views` int(10) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id`, `created_at`, `updated_at`, `status`, `sort`, `name`, `text`, `alias`, `h1`, `title`, `description`, `keywords`, `views`, `image`) VALUES
(1, 1422362960, 1422430276, 1, 1, 'Nike', '&lt;p&gt;&lt;strong&gt;Nike, Inc.&lt;/strong&gt; (&lt;small&gt;МФА&lt;/small&gt; &lt;span class=&quot;IPA&quot;&gt;&lt;a title=&quot;Международный фонетический алфавит&quot; href=&quot;https://ru.wikipedia.org/wiki/%D0%9C%D0%B5%D0%B6%D0%B4%D1%83%D0%BD%D0%B0%D1%80%D0%BE%D0%B4%D0%BD%D1%8B%D0%B9_%D1%84%D0%BE%D0%BD%D0%B5%D1%82%D0%B8%D1%87%D0%B5%D1%81%D0%BA%D0%B8%D0%B9_%D0%B0%D0%BB%D1%84%D0%B0%D0%B2%D0%B8%D1%82&quot;&gt;[''naɪkɪ]&lt;/a&gt;&lt;/span&gt;&lt;sup id=&quot;cite_ref-ReferenceA_3-0&quot; class=&quot;reference&quot;&gt;&lt;a href=&quot;https://ru.wikipedia.org/wiki/Nike#cite_note-ReferenceA-3&quot;&gt;[3]&lt;/a&gt;&lt;/sup&gt;, &lt;a title=&quot;Русский язык&quot; href=&quot;https://ru.wikipedia.org/wiki/%D0%A0%D1%83%D1%81%D1%81%D0%BA%D0%B8%D0%B9_%D1%8F%D0%B7%D1%8B%D0%BA&quot;&gt;рус.&lt;/a&gt; &lt;em&gt;&lt;span lang=&quot;ru&quot;&gt;/на́йки/&lt;/span&gt;&lt;/em&gt;, написание товарного знака по-русски&amp;nbsp;&amp;mdash; &lt;em&gt;Найк&lt;/em&gt;&lt;sup id=&quot;cite_ref-4&quot; class=&quot;reference&quot;&gt;&lt;a href=&quot;https://ru.wikipedia.org/wiki/Nike#cite_note-4&quot;&gt;[4]&lt;/a&gt;&lt;/sup&gt;)&amp;nbsp;&amp;mdash; &lt;a class=&quot;mw-redirect&quot; title=&quot;США&quot; href=&quot;https://ru.wikipedia.org/wiki/%D0%A1%D0%A8%D0%90&quot;&gt;американская&lt;/a&gt; компания, всемирно известный производитель спортивной одежды и обуви. Штаб-квартира&amp;nbsp;&amp;mdash; в городе &lt;a title=&quot;Бивертон (Орегон)&quot; href=&quot;https://ru.wikipedia.org/wiki/%D0%91%D0%B8%D0%B2%D0%B5%D1%80%D1%82%D0%BE%D0%BD_%28%D0%9E%D1%80%D0%B5%D0%B3%D0%BE%D0%BD%29&quot;&gt;Бивертон&lt;/a&gt; (штат &lt;a title=&quot;Орегон&quot; href=&quot;https://ru.wikipedia.org/wiki/%D0%9E%D1%80%D0%B5%D0%B3%D0%BE%D0%BD&quot;&gt;Орегон&lt;/a&gt;). По мнению аналитиков, на долю компании Nike приходится почти 95&amp;nbsp;% рынка баскетбольной обуви в США&lt;sup id=&quot;cite_ref-ReferenceE_5-0&quot; class=&quot;reference&quot;&gt;&lt;a href=&quot;https://ru.wikipedia.org/wiki/Nike#cite_note-ReferenceE-5&quot;&gt;[5]&lt;/a&gt;&lt;/sup&gt;. В 2008 году в компании было занято более 30 000 человек по всему миру. Бренд оценивается в $ 10,7&amp;nbsp;млрд и является самой ценной торговой маркой в спортивной индустрии&lt;sup id=&quot;cite_ref-ReferenceK_6-0&quot; class=&quot;reference&quot;&gt;&lt;a href=&quot;https://ru.wikipedia.org/wiki/Nike#cite_note-ReferenceK-6&quot;&gt;[6]&lt;/a&gt;&lt;/sup&gt;&lt;sup id=&quot;cite_ref-ReferenceH_7-0&quot; class=&quot;reference&quot;&gt;&lt;a href=&quot;https://ru.wikipedia.org/wiki/Nike#cite_note-ReferenceH-7&quot;&gt;[7]&lt;/a&gt;&lt;/sup&gt;. С 20 сентября 2013&amp;nbsp;г. входит в Промышленный индекс Доу-Джонса.&lt;/p&gt;\r\n&lt;p&gt;Компания была основана 25 января 1964 года под названием &lt;em&gt;Blue Ribbon Sports&lt;/em&gt;, и официально стала &lt;em&gt;Nike, Inc.&lt;/em&gt; 30 мая 1978 года. Nike продает свою продукцию под собственной торговой маркой, а также Nike Golf, Nike Pro, Nike+, Air Jordan, Nike Skateboarding и в том числе под дочерними брендами Cole Haan, Hurley International и Converse. Nike также принадлежала Bauer Hockey (позже переименована Nike Bauer) в период между 1995 и 2008 годами&lt;sup id=&quot;cite_ref-ReferenceG_8-0&quot; class=&quot;reference&quot;&gt;&lt;a href=&quot;https://ru.wikipedia.org/wiki/Nike#cite_note-ReferenceG-8&quot;&gt;[8]&lt;/a&gt;&lt;/sup&gt;. В дополнение к производству одежды и оборудования, компания управляет розничными магазинами под названием &lt;a class=&quot;new&quot; title=&quot;Niketown (страница отсутствует)&quot; href=&quot;https://ru.wikipedia.org/w/index.php?title=Niketown&amp;amp;action=edit&amp;amp;redlink=1&quot;&gt;Niketown&lt;/a&gt;. Nike является спонсором многих спортсменов и спортивных команд по всему миру.&lt;/p&gt;', 'nike', 'Nike h1', 'Nike title', 'Nike description', 'Nike keywords', 0, '3209d00453b8d2000953cfd1e8a09d79.jpg'),
(7, 1423570350, NULL, 1, 0, 'New Balance', '', 'new_balance', '', '', '', '', 0, NULL),
(8, 1423570550, NULL, 1, 0, 'Reebok', '', 'reebok', '', '', '', '', 0, NULL),
(9, 1423570569, NULL, 1, 0, 'Puma', '', 'puma', '', '', '', '', 0, NULL),
(10, 1423570585, NULL, 1, 0, 'Timberland', '', 'timberland', '', '', '', '', 0, NULL),
(11, 1423570612, NULL, 1, 0, 'Asics', '', 'asics', '', '', '', '', 0, NULL),
(12, 1423570622, NULL, 1, 0, 'Converse', '', 'converse', '', '', '', '', 0, NULL),
(13, 1423570640, NULL, 1, 0, 'Vans', '', 'vans', '', '', '', '', 0, NULL),
(14, 1423570648, NULL, 1, 0, 'Lacoste', '', 'lacoste', '', '', '', '', 0, NULL),
(15, 1423570678, NULL, 1, 0, 'Le coq sportif', '', 'le_coq_sportif', '', '', '', '', 0, NULL),
(16, 1423570701, NULL, 1, 0, 'Isabel Marant', '', 'isabel_marant', '', '', '', '', 0, NULL),
(17, 1423570790, NULL, 1, 0, 'Diamond', '', 'diamond', '', '', '', '', 0, NULL),
(18, 1423570804, NULL, 1, 0, 'Obey', '', 'obey', '', '', '', '', 0, NULL),
(19, 1423570875, NULL, 1, 0, 'Young Money Cash Money Billionaires', '', 'young_money_cash_money_billionaires', '', '', '', '', 0, NULL),
(20, 1423570907, NULL, 1, 0, 'Supreme', '', 'supreme', '', '', '', '', 0, NULL),
(21, 1423570929, NULL, 1, 0, 'Yums', '', 'yums', '', '', '', '', 0, NULL),
(22, 1423570945, NULL, 1, 0, 'Hater', '', 'hater', '', '', '', '', 0, NULL),
(23, 1423571051, NULL, 1, 0, 'Fred Perry', '', 'fred_perry', '', '', '', '', 0, NULL),
(24, 1423571123, NULL, 1, 0, 'Ralph Lauren', '', 'ralph_lauren', '', '', '', '', 0, NULL),
(25, 1423571149, NULL, 1, 0, 'Franklin Marshall', '', 'franklin_marshall', '', '', '', '', 0, NULL),
(26, 1423571212, NULL, 1, 0, 'Mi-pac', '', 'mi-pac', '', '', '', '', 0, NULL),
(27, 1423571251, NULL, 1, 0, 'Carhartt', '', 'carhartt', '', '', '', '', 0, NULL),
(28, 1423571269, NULL, 1, 0, 'The North Face', '', 'the_north_face', '', '', '', '', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `callback`
--

CREATE TABLE IF NOT EXISTS `callback` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(64) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `callback`
--

INSERT INTO `callback` (`id`, `created_at`, `updated_at`, `status`, `name`, `phone`, `ip`) VALUES
(3, 1422039897, NULL, 0, 'dfdfgdf', '+38 (334) 345-44-33', '127.0.0.1'),
(4, 1422296573, NULL, 0, 'Виталий', '+38 (099) 274-03-48', '127.0.0.1'),
(7, 1423087178, 1423087200, 1, 'ьм', '+38 (654) 654-54-64', '93.79.159.189'),
(8, 1423087327, NULL, 0, 'ававав', '+38 (454) 545-45-45', '93.79.159.189'),
(9, 1423229266, NULL, 0, 'nnn', '+38 (111) 111-11-11', '178.136.229.251'),
(10, 1423468939, NULL, 0, '123', '+38 (111) 111-11-11', '178.136.229.251');

-- --------------------------------------------------------

--
-- Структура таблицы `carts`
--

CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Дамп данных таблицы `carts`
--

INSERT INTO `carts` (`id`, `created_at`, `updated_at`, `hash`) VALUES
(1, 1424355464, NULL, '1b434125a1f1a856b7c544f876fef20243dd17dd'),
(2, 1424355589, NULL, '91c54471aa41485f40d568b14d15648dbd96c928'),
(3, 1424356431, NULL, '59a62780c5b4127ea681bed21b2a737c984ace5f'),
(4, 1424356502, NULL, 'e37d66a0f9326eb8115c70a8d49b82fed3a1e4a9'),
(5, 1424357138, NULL, 'b184c8af15b457f9395db83c4d361ff85ae9b832'),
(6, 1424357255, NULL, '6eebf4c70f80081026e3a35ba6d0dc6d116f9971'),
(7, 1424442993, NULL, 'a02d75db85c1b56570d0da4c1b00cd328c9423b8'),
(8, 1424443170, NULL, '4d2b4919004ce39dc3d67ae30753d4ac38d70aa2'),
(9, 1424443177, NULL, '9c258d12166c92718fa016fa65df2672f8b3e71e'),
(10, 1424443343, NULL, '1dcd65fb78f3e7e892a1417b7bc40c3c52169494'),
(11, 1424443346, NULL, 'a9e514bd0e01e874a4d89356a54cdb9e4434a032'),
(12, 1424443623, NULL, '7cc36e28ce60666b684b9b573159c0d0ef4603a8'),
(13, 1424443634, NULL, '76f086e3e1f97e026fb301a94953888468b993b2'),
(14, 1424443640, NULL, 'deecccf12f8e40a934f7b8b2f7593a6f83931163'),
(15, 1424443665, NULL, 'badac536d827dabf7e767c283227add9ce2db556'),
(16, 1424443866, NULL, '949429c5ba8156ab723cdf5ccd05c4d3b45e56b5'),
(17, 1424673504, NULL, 'a1fb06a1ad52d83bcce6a4b783dfecc1d67f2f1d'),
(18, 1424673557, NULL, '42dfc35e2d95c39407ac2fa8af4085c5d5cc80d0'),
(19, 1424673637, NULL, '527778c24ba24d50af1b31f39033cd1cb93878a8'),
(20, 1424673643, NULL, '3f0b0f80f34235d0015d0b4edf6c3b9f28acc87e'),
(21, 1424673654, NULL, '8f49c097745389548b8bce785d74008846c12e94'),
(22, 1424673660, NULL, '1c7b6d5e5653e87c545d4dc721fa062fdabd59b2'),
(23, 1424673674, NULL, '11fc26b97a173b068100e0fedcdfeb738a99b0b6'),
(24, 1424673680, NULL, '68fe110ecbdf9c45678f6f486115f5556859bbea'),
(25, 1424673696, NULL, '5c75b3637d05148a39edb0e6bd2abc11c756412b'),
(26, 1424673702, NULL, 'de286394a3ea2db87f04c7bab7626f199eede5a8'),
(27, 1424673708, NULL, 'fa3946dd047d3a2b9d0165df4579f24d9c135086'),
(28, 1424673709, NULL, 'decf413b83dbe8a2b317aca15f0624e6c134687c'),
(29, 1424673710, NULL, 'c8107db55fa2eace9d7b4bf4bec3fb36bab78f19'),
(30, 1424673711, NULL, '48c5fd6c21bab8972e7a170831da6eecb0bf44ae'),
(31, 1424673711, NULL, '3882f9690d305a3debacc2c60d9fa816e8a5e9c0'),
(32, 1424673711, NULL, '7259a801c15e304049c681a0f4239eceef5be502'),
(33, 1424673712, NULL, '41e5cefd92b3995ac67197001e4791ba7964b6cf'),
(34, 1424673712, NULL, '67309b133fd64f2569440af85423d4028fce10ba'),
(35, 1424673712, NULL, '6f440e9493ff453ccce0b9b89a7860cbb4271a5f'),
(36, 1424673712, NULL, '77961dc50b440f04bf71233456ff07710c2f0c9d'),
(37, 1424673725, NULL, 'b65d2faa42ccbb9b70648ce048ccb3eff16a9abe'),
(38, 1424673807, NULL, 'f34bf5df2cc436a2a3aca2bc3de05e8f519a1ff5'),
(39, 1424673868, NULL, '5f118f08ecf5cf110d646e37cd26b2a6d539bfc8'),
(40, 1424673938, NULL, '966a51dd4d4fe18d09d1c46c6af3a3c4ed5fb9cc'),
(41, 1424674003, NULL, '8baf8cc14302620466829a2a880eb5a6b98b0a77'),
(42, 1424933657, NULL, 'a129b408f5090f2b9ce5133318c34773316990cd');

-- --------------------------------------------------------

--
-- Структура таблицы `carts_items`
--

CREATE TABLE IF NOT EXISTS `carts_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) NOT NULL,
  `catalog_id` int(10) NOT NULL,
  `count` int(6) DEFAULT NULL,
  `size_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cart` (`cart_id`),
  KEY `id_goods` (`catalog_id`),
  KEY `cart_id` (`cart_id`),
  KEY `catalog_id` (`catalog_id`),
  KEY `size_id` (`size_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `carts_items`
--

INSERT INTO `carts_items` (`id`, `cart_id`, `catalog_id`, `count`, `size_id`) VALUES
(1, 41, 34, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog`
--

CREATE TABLE IF NOT EXISTS `catalog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `new` tinyint(1) NOT NULL DEFAULT '0',
  `new_from` int(10) NOT NULL DEFAULT '0',
  `sale` tinyint(1) NOT NULL DEFAULT '0',
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` text,
  `description` text,
  `cost` int(6) NOT NULL DEFAULT '0',
  `cost_old` int(6) NOT NULL DEFAULT '0',
  `artikul` varchar(128) DEFAULT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - мужское, 1- женское, 2 - унисекс',
  `views` int(10) NOT NULL DEFAULT '0',
  `brand_id` int(10) DEFAULT NULL,
  `model_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `parent_id` (`parent_id`),
  KEY `brand_id` (`brand_id`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `catalog`
--

INSERT INTO `catalog` (`id`, `created_at`, `updated_at`, `status`, `sort`, `name`, `alias`, `parent_id`, `new`, `new_from`, `sale`, `top`, `available`, `h1`, `title`, `keywords`, `description`, `cost`, `cost_old`, `artikul`, `sex`, `views`, `brand_id`, `model_id`) VALUES
(25, 1423498015, 1423608119, 1, 24, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse', 3, 1, 1423498015, 0, 1, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000001', 2, 28, 1, 8),
(26, 1423498469, 1423608155, 1, 25, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse2018', 3, 1, 1423498469, 0, 1, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000002', 2, 9, 1, 8),
(27, 1423498635, 1423581241, 1, 26, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse6927', 3, 1, 1423498635, 0, 1, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000003', 2, 24, 1, 8),
(28, 1423498765, 1423583961, 1, 27, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse9495', 3, 1, 1423498765, 0, 1, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000004', 2, 38, 1, 8),
(29, 1423498938, 1423825548, 1, 28, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse6869', 3, 1, 1423498938, 1, 1, 2, NULL, NULL, NULL, NULL, 1029, 1200, '0000005', 2, 69, 1, 8),
(30, 1423499272, 1423608133, 1, 29, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse6556', 3, 1, 1423499272, 0, 0, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000006', 2, 8, 1, 8),
(31, 1423499561, 1423829119, 1, 30, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse6873', 3, 1, 1423499561, 1, 0, 2, NULL, NULL, NULL, NULL, 1029, 1500, '0000007', 2, 18, 1, 8),
(32, 1423500053, 1423608168, 1, 31, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse1911', 3, 1, 1423500053, 0, 0, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000008', 2, 10, 1, 8),
(33, 1423500226, 1423608122, 1, 32, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse1178', 3, 1, 1423500226, 0, 0, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000009', 2, 9, 1, 8),
(34, 1423500353, 1423570851, 1, 33, 'Nike Air Max 90 Hyperfuse', 'nike_air_max_90_hyperfuse3973', 3, 1, 1423500353, 0, 0, 2, NULL, NULL, NULL, NULL, 1029, 0, '0000010', 2, 100, 1, 8),
(35, 1424941997, 0, 1, 10, 'sdfsdfsdfsd', 'sdfsdfsdfsd', 3, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 32423, 0, '', 0, 0, 1, 52);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_comments`
--

CREATE TABLE IF NOT EXISTS `catalog_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` int(10) DEFAULT NULL,
  `catalog_id` int(10) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `text` text,
  `ip` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `catalog_comments`
--

INSERT INTO `catalog_comments` (`id`, `created_at`, `updated_at`, `status`, `date`, `catalog_id`, `name`, `city`, `text`, `ip`) VALUES
(19, 1423498197, NULL, 0, 1423498197, 25, 'Евгений', 'Киев', 'Заказал эти кроссовки. Качество супер!', '178.136.229.251'),
(20, 1423663886, 1424949164, 0, 1423663886, 31, 'dfsdfsdf', 'sfdsfsd', 'fsdfsdf', '127.0.0.1');

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_images`
--

CREATE TABLE IF NOT EXISTS `catalog_images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `catalog_id` int(10) NOT NULL DEFAULT '0',
  `main` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

--
-- Дамп данных таблицы `catalog_images`
--

INSERT INTO `catalog_images` (`id`, `created_at`, `updated_at`, `sort`, `catalog_id`, `main`, `image`) VALUES
(34, 1423498054, 1423498068, 0, 25, 0, 'fd8ebbc018dd38f4410ea9787b46a0f0.jpg'),
(35, 1423498055, 1423498068, 0, 25, 0, '745deb9e9ef43f1a2b3bb48c64cbbf5d.jpg'),
(36, 1423498055, 1423498068, 0, 25, 0, 'c6a1cc7664dc15562bb4b75912fc3f14.jpg'),
(37, 1423498055, 1423498068, 0, 25, 1, 'ca9ad1f51a811747b5dfbaf07a5f2485.jpg'),
(38, 1423498056, 1423498068, 0, 25, 0, '23739d889100b7f35f786019e1a49abe.jpg'),
(39, 1423498056, 1423498068, 0, 25, 0, 'cd35fe51af4edfb6aa273e95d271e315.jpg'),
(40, 1423498503, 1423498517, 0, 26, 0, '0bf66991f796cd07ddbf3c5ebb42cc2c.jpg'),
(41, 1423498504, 1423498517, 0, 26, 0, '580ca8abfc9253d537476c271c217910.jpg'),
(42, 1423498505, 1423498517, 0, 26, 0, '37cc04cef2bbae7483a1ab8c1d7af769.jpg'),
(43, 1423498505, 1423498517, 0, 26, 0, '7eb8bfe38491a546bb8afcfca3d11a92.jpg'),
(44, 1423498505, 1423498517, 0, 26, 0, 'd5bc7a89536388b2af331bc0369804f6.jpg'),
(45, 1423498505, 1423498517, 0, 26, 1, 'e3e7447dc1ec33d844e561ba9866f79e.jpg'),
(46, 1423498660, 1423498666, 0, 27, 0, 'fdeb1e925d668c6b7cabc489f8069e74.jpg'),
(47, 1423498661, 1423498666, 0, 27, 0, 'c8e5c80f366a2a101a55cdefcfe0b61a.jpg'),
(48, 1423498661, 1423498666, 0, 27, 1, 'c20c04f5eb4020a4ac1c72b0e06791a4.jpg'),
(49, 1423498662, 1423498666, 0, 27, 0, '1b53dde46f18f52b526a74f1096bfe7f.jpg'),
(50, 1423498662, 1423498666, 0, 27, 0, '74a1a35dd8bb98be23dbc5fe88b4e182.jpg'),
(51, 1423498781, 1423561636, 1, 28, 0, '9d86b8c431b16d31386dce4439f6f166.jpg'),
(52, 1423498781, 1423561636, 2, 28, 1, '1153289278e2f9afb47f35a7fc0a5fa1.jpg'),
(53, 1423498782, 1423561636, 3, 28, 0, '946ceb91a51a6c68763fae75e43f484d.jpg'),
(54, 1423498783, 1423561636, 4, 28, 0, '28943c1d42f4b2283eb042802d81c569.jpg'),
(55, 1423498783, 1423561636, 5, 28, 0, 'c5db458a86767df04ebbe36a6ffd8df4.jpg'),
(56, 1423498956, 1423498961, 0, 29, 0, 'daab18bf820cdf3860ae659b1d4f7043.jpg'),
(57, 1423498957, 1423498961, 0, 29, 1, '21bfe2990f68118f081de8ecd501e86b.jpg'),
(58, 1423498958, 1423498961, 0, 29, 0, '99267454bd297b2d633ee017c6b35296.jpg'),
(59, 1423498958, 1423498961, 0, 29, 0, '8a627c32d137d285fdcb008437d8ec63.jpg'),
(60, 1423498958, 1423498961, 0, 29, 0, '5af58e23c7743d71a4ab9a74a107091a.jpg'),
(61, 1423499297, 1423499302, 0, 30, 0, '285a1a68f4ec5113bc94a08f7f8a27ed.jpg'),
(62, 1423499298, 1423499302, 0, 30, 0, '78951df56f432d1b2801e48e7aed27de.jpg'),
(63, 1423499298, 1423499302, 0, 30, 1, '795b2fa0335f0a018e71120a6a59376c.jpg'),
(64, 1423499299, 1423499302, 0, 30, 0, 'afb26061b27d31f825f1421bb9f9acfd.jpg'),
(65, 1423499299, 1423499302, 0, 30, 0, '607bf7397fbf2d6f1e41f3a5ff77a240.jpg'),
(66, 1423499299, 1423499302, 0, 30, 0, 'dadbc69095e96af11116a39e00f3b84a.jpg'),
(67, 1423499575, 1423499582, 1, 31, 0, '5442258e374c28713ed1568cefe5d08d.jpg'),
(68, 1423499576, 1423499582, 2, 31, 0, '1020d283864c8c6e93fe3c3ce0c8a257.jpg'),
(69, 1423499576, 1423499582, 3, 31, 0, 'ef679376e15fc66195b19c0f5ee5b752.jpg'),
(70, 1423499576, 1423499582, 4, 31, 0, '4b400c6a50e33f6e074c6929abd82acd.jpg'),
(71, 1423499577, 1423499582, 5, 31, 0, '24b5d315896ca19477e9641cba1330b4.jpg'),
(72, 1423499577, 1423499582, 6, 31, 1, '50025dd6d4541dc9035232867386049c.jpg'),
(73, 1423500070, 1423500078, 0, 32, 0, '12c24a5e0dfa553af42e227deadc0486.jpg'),
(74, 1423500071, 1423500078, 0, 32, 0, 'b48f779322f8b8e64a235330847aa63e.jpg'),
(75, 1423500072, 1423500078, 0, 32, 0, '5c448ed582efea69503dd71d30b7d983.jpg'),
(76, 1423500072, 1423500078, 0, 32, 0, 'cc2d9bb22aea5fdea782f72a3f254724.jpg'),
(77, 1423500072, 1423500078, 0, 32, 1, '5a969f9f1945ecc2c0fdb252e245574d.jpg'),
(78, 1423500073, 1423500078, 0, 32, 0, 'e502c2a764f8eb89d520faa7d3f39a55.jpg'),
(79, 1423500240, 1423500244, 0, 33, 0, 'ce4868796ac45395140daf038adf1bc3.jpg'),
(80, 1423500241, 1423500244, 0, 33, 0, '0b725a4eed7304046faaad9fbe919a6b.jpg'),
(81, 1423500241, 1423500244, 0, 33, 1, '18e8533dd8e5a49f886e0fc7b65cf866.jpg'),
(82, 1423500241, 1423500244, 0, 33, 0, '345d9a03d11c85f9ae863801351aea92.jpg'),
(83, 1423500365, 1423553844, 3, 34, 1, '2e9102770dcc54681dc492d86981537b.jpg'),
(84, 1423500367, 1423553844, 4, 34, 0, 'b9c1b7f592a74c5871cdd974b9e3ae28.jpg'),
(85, 1423500367, 1423553844, 6, 34, 0, 'ccb99ac50b298d34499ae314192c519c.jpg'),
(86, 1423500367, 1423553844, 5, 34, 0, 'baa5bd37335ad6880d9951e62ee9665d.jpg'),
(87, 1423500367, 1423553844, 7, 34, 0, 'fb395a7b19e2164332eddffb165a61cd.jpg'),
(96, 1423561171, 1423561636, 7, 28, 0, 'cacc67383d9c64c5a1b06297951a8dd3.jpg'),
(97, 1423561172, 1423561636, 9, 28, 0, 'b54dc4ae44efdd23190bc5fbb0f3ad5a.jpg'),
(98, 1423561172, 1423561636, 8, 28, 0, '2c6568f141871cb644a1bd721cc7424a.jpg'),
(102, 1423561576, 1423561636, 6, 28, 0, '2666a4559ad1639351bbe51fc9723b66.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_questions`
--

CREATE TABLE IF NOT EXISTS `catalog_questions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `text` text,
  `catalog_id` int(10) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `catalog_questions`
--

INSERT INTO `catalog_questions` (`id`, `created_at`, `updated_at`, `status`, `name`, `email`, `text`, `catalog_id`, `ip`) VALUES
(1, 1422268402, 1422359280, 1, 'Виталий', 'vitaliy.demyanenko.1991@gmail.com', 'sdfsdf', NULL, '127.0.0.1'),
(2, 1422359003, 1422359315, 1, 'Виталий Андреевич', 'vitaliy.demyanenko.1991@gmail.com', 'вапавпвап', NULL, '127.0.0.1'),
(3, 1423087292, NULL, 0, '', 'asd@asd.asd', 'мсчмсмсчмчс', NULL, '93.79.159.189'),
(4, 1423087572, NULL, 0, '', 'asd@asd.asd', 'мсчмсмчсчс', NULL, '93.79.159.189'),
(6, 1423224911, NULL, 0, '', 'fdsdds@dssd.sds', 'dfdfdfdf', NULL, '178.136.229.251'),
(7, 1423225240, NULL, 0, '', 'Mai@mail.ru', '12356', NULL, '178.136.229.251'),
(8, 1423225740, NULL, 0, '', 'mai@mail.ru', '123356', NULL, '178.136.229.251'),
(9, 1423226287, NULL, 0, '', 'Mail@mail.ru', '12356', NULL, '178.136.229.251'),
(10, 1423663897, 1424951528, 1, '', 'demyanenko.v.wezom@gmail.com', 'asdasd', 31, '127.0.0.1');

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_sizes`
--

CREATE TABLE IF NOT EXISTS `catalog_sizes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `catalog_id` int(10) NOT NULL,
  `size_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`),
  KEY `size_id` (`size_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=449 ;

--
-- Дамп данных таблицы `catalog_sizes`
--

INSERT INTO `catalog_sizes` (`id`, `catalog_id`, `size_id`) VALUES
(331, 33, 10),
(332, 33, 11),
(333, 33, 12),
(334, 33, 13),
(335, 33, 14),
(336, 33, 15),
(337, 33, 7),
(338, 33, 16),
(342, 32, 10),
(343, 32, 11),
(344, 32, 12),
(345, 32, 13),
(346, 32, 14),
(347, 32, 15),
(348, 32, 7),
(349, 32, 16),
(353, 31, 10),
(354, 31, 11),
(355, 31, 12),
(356, 31, 13),
(357, 31, 14),
(358, 31, 15),
(359, 31, 7),
(360, 31, 16),
(364, 30, 10),
(365, 30, 11),
(366, 30, 12),
(367, 30, 13),
(368, 30, 14),
(369, 30, 15),
(370, 30, 7),
(371, 30, 16),
(375, 29, 10),
(376, 29, 11),
(377, 29, 12),
(378, 29, 13),
(379, 29, 14),
(380, 29, 15),
(381, 29, 7),
(382, 29, 16),
(386, 28, 10),
(387, 28, 11),
(388, 28, 12),
(389, 28, 13),
(390, 28, 14),
(391, 28, 15),
(392, 28, 7),
(393, 28, 16),
(397, 26, 10),
(398, 26, 11),
(399, 26, 12),
(400, 26, 13),
(401, 26, 14),
(402, 26, 15),
(403, 26, 7),
(404, 26, 16),
(408, 25, 10),
(409, 25, 11),
(410, 25, 12),
(411, 25, 13),
(412, 25, 14),
(413, 25, 15),
(414, 25, 7),
(415, 25, 16),
(419, 27, 10),
(420, 27, 11),
(421, 27, 12),
(422, 27, 13),
(423, 27, 14),
(424, 27, 15),
(425, 27, 7),
(426, 27, 16),
(441, 34, 10),
(442, 34, 11),
(443, 34, 12),
(444, 34, 13),
(445, 34, 14),
(446, 34, 15),
(447, 34, 7),
(448, 34, 16);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_specifications_values`
--

CREATE TABLE IF NOT EXISTS `catalog_specifications_values` (
  `id` int(11) NOT NULL,
  `catalog_id` int(10) NOT NULL,
  `specification_value_id` int(10) NOT NULL,
  `specification_id` int(10) NOT NULL,
  KEY `catalog_id` (`catalog_id`),
  KEY `specification_value_id` (`specification_value_id`),
  KEY `specification_id` (`specification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `catalog_specifications_values`
--

INSERT INTO `catalog_specifications_values` (`id`, `catalog_id`, `specification_value_id`, `specification_id`) VALUES
(0, 33, 32, 2),
(0, 33, 18, 4),
(0, 33, 34, 9),
(0, 33, 35, 9),
(0, 33, 36, 9),
(0, 33, 42, 1),
(0, 32, 32, 2),
(0, 32, 18, 4),
(0, 32, 34, 9),
(0, 32, 35, 9),
(0, 32, 36, 9),
(0, 32, 43, 1),
(0, 31, 32, 2),
(0, 31, 18, 4),
(0, 31, 34, 9),
(0, 31, 35, 9),
(0, 31, 36, 9),
(0, 31, 41, 1),
(0, 30, 32, 2),
(0, 30, 18, 4),
(0, 30, 34, 9),
(0, 30, 35, 9),
(0, 30, 36, 9),
(0, 30, 40, 1),
(0, 29, 32, 2),
(0, 29, 18, 4),
(0, 29, 34, 9),
(0, 29, 35, 9),
(0, 29, 36, 9),
(0, 29, 39, 1),
(0, 28, 32, 2),
(0, 28, 18, 4),
(0, 28, 34, 9),
(0, 28, 35, 9),
(0, 28, 36, 9),
(0, 28, 38, 1),
(0, 26, 32, 2),
(0, 26, 18, 4),
(0, 26, 34, 9),
(0, 26, 35, 9),
(0, 26, 36, 9),
(0, 26, 28, 1),
(0, 25, 32, 2),
(0, 25, 18, 4),
(0, 25, 34, 9),
(0, 25, 35, 9),
(0, 25, 36, 9),
(0, 25, 25, 1),
(0, 27, 32, 2),
(0, 27, 18, 4),
(0, 27, 34, 9),
(0, 27, 35, 9),
(0, 27, 36, 9),
(0, 27, 37, 1),
(0, 34, 32, 2),
(0, 34, 18, 4),
(0, 34, 34, 9),
(0, 34, 35, 9),
(0, 34, 36, 9),
(0, 34, 27, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_tree`
--

CREATE TABLE IF NOT EXISTS `catalog_tree` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `image` varchar(64) DEFAULT NULL,
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` text,
  `description` text,
  `text` text,
  `views` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Дамп данных таблицы `catalog_tree`
--

INSERT INTO `catalog_tree` (`id`, `created_at`, `updated_at`, `status`, `sort`, `name`, `alias`, `parent_id`, `image`, `h1`, `title`, `keywords`, `description`, `text`, `views`) VALUES
(1, NULL, 1424940544, 1, 0, 'Мужское', 'for_men', 0, 'e5e0861c65c520f43e195407fe648862.jpg', 'Мужское', 'Мужское', 'Мужское', 'Мужское', '<p>dfgdfgdfg</p>', 594),
(2, NULL, 1423569602, 1, 1, 'Женское', 'for_women', 0, '3b613f5aeecc9d29c6daa872e65ee802.jpg', 'Женское', 'Женское', 'Женское', 'Женское', '&lt;h4&gt;спортивная обувь и одежда в наличии и под заказ в нашем интернет магазине&lt;/h4&gt;\r\n&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum enim tenetur itaque voluptates suscipit corporis necessitatibus aperiam, esse quos praesentium maiores ratione aliquid consectetur totam. Provident, molestias, illum! Dolor aliquid expedita itaque, laudantium voluptatibus alias sequi labore asperiores deleniti excepturi vitae iste harum facilis, error amet vero. Officia ad voluptatem sit deserunt, quibusdam ipsum a voluptatum harum reiciendis repellat. Iure, officiis officia cupiditate quisquam odit excepturi voluptas nemo praesentium facere reprehenderit totam, repellat repellendus optio. Voluptates velit reprehenderit odit tenetur illum distinctio cupiditate illo est saepe minus ad, culpa sed. Amet nostrum, mollitia impedit temporibus ratione numquam blanditiis sunt veritatis qui excepturi necessitatibus autem dolor in dolores, placeat cumque veniam reprehenderit consectetur! Quas maiores veniam incidunt enim, libero omnis dignissimos dicta, commodi ratione in expedita voluptate error. Harum voluptates sapiente libero modi veniam? Accusamus minima laboriosam laborum magni. Id, dolores, ut. Facilis aliquam vitae accusamus molestias repudiandae, hic, expedita modi et, ipsum mollitia assumenda laudantium reiciendis! Quis qui eius inventore placeat distinctio sed dolores soluta iste laborum quasi, facilis suscipit corporis minima magni laudantium impedit cumque magnam quo veniam eligendi facere. Magni nihil repellat impedit ipsa dolorem dolore error placeat aliquam at, optio iusto obcaecati doloremque, voluptatum suscipit fuga accusantium nostrum vitae. Consectetur commodi asperiores neque, dolor et. Esse earum asperiores officiis nostrum, quod, eligendi accusantium veritatis voluptatibus rem distinctio aperiam hic nemo, optio velit! Ea fuga minima nostrum, veritatis reiciendis sit sint nam blanditiis a error aut suscipit eos quos quaerat voluptates illo, nulla est dignissimos.&lt;/p&gt;\r\n&lt;p&gt;Consectetur nesciunt deleniti recusandae debitis, atque animi dicta architecto quaerat praesentium provident incidunt, molestiae libero earum doloremque nisi autem. Vel maxime labore nisi accusamus ullam quia harum blanditiis, inventore minus modi dolores minima, pariatur tenetur consequuntur, corporis nostrum ex, quisquam quo? Eligendi quasi hic unde doloremque alias dolorum ipsa nobis error vero quia eius modi similique quaerat molestias architecto laudantium, vitae nam tempora sunt commodi placeat impedit explicabo corporis delectus! Dolores repudiandae fugiat, suscipit libero fuga dolore minus tempore modi mollitia neque, illo maiores sint voluptas sequi culpa pariatur quia voluptatem obcaecati laudantium. Pariatur nostrum facilis, sint corrupti iste doloribus reprehenderit doloremque! Itaque nostrum repellat rem ratione quam libero aliquam, eos, tempore perspiciatis vero inventore aspernatur in quasi voluptas esse voluptatum. Incidunt, voluptas voluptate sed officia rem soluta nisi iusto nihil cupiditate porro suscipit perferendis ab, sit, eius nemo ullam deleniti. Accusantium odit cumque totam recusandae, debitis velit sit, corporis, id neque est voluptas natus nemo fugiat reprehenderit dicta ipsam modi officiis. Itaque aliquam et repellat, minus pariatur vitae totam nulla? Debitis, culpa quaerat vel modi sint accusantium? Tempore est quis eligendi ipsam ut, id velit! Laborum quasi est mollitia reiciendis quia, iste quidem quam aperiam ut assumenda aliquam obcaecati ipsa inventore nihil quod, illo voluptatum eligendi accusamus error, excepturi earum sunt? Laboriosam corporis minima nihil explicabo repellat eligendi quibusdam ipsa unde, voluptatum veniam voluptates quos, non voluptatibus! Consequatur non facere id quidem, eos sapiente asperiores aliquid eligendi quasi omnis alias ratione iusto dolore cupiditate minima natus accusamus repellat placeat inventore vel ea.&lt;/p&gt;', 27),
(3, 1422130515, 1424079055, 1, 0, 'Кроссовки для бега', 'krossovki_dlja_bega', 1, 'ee9eb4691f951faaec518b0b71117a45.jpg', 'Кроссовки для бега', 'Кроссовки для бега', 'Кроссовки для бега', 'Кроссовки для бега', '', 91291),
(4, 1422130532, 1423581204, 1, 1, 'Повседневные кроссовки', 'povsednevnye_krossovki', 1, NULL, 'Повседневные кроссовки', 'Повседневные кроссовки', 'Повседневные кроссовки', 'Повседневные кроссовки', '', 335),
(5, 1422130541, 1423574065, 1, 2, 'Кеды', 'kedy', 1, '360dbfe825d92a913ecae44ea65289db.jpg', 'Кеды', 'Кеды', 'Кеды', 'Кеды', '&lt;p&gt;fdfgdfg&lt;/p&gt;', 917),
(6, 1422130558, 1423569602, 1, 4, 'Сникерсы', 'snikersy', 1, NULL, 'Сникерсы', 'Сникерсы', 'Сникерсы', 'Сникерсы', '', 683),
(7, 1422130568, 1423569602, 1, 3, 'Мокасины', 'mokasiny', 1, NULL, 'Мокасины', 'Мокасины', 'Мокасины', 'Мокасины', '', 691),
(8, 1422134524, 1423569602, 1, 0, 'Кроссовки', 'krossovki', 2, NULL, 'Кроссовки', 'Кроссовки', 'Кроссовки', 'Кроссовки', '', 688),
(9, 1422134536, 1424077462, 1, 1, 'Футболки', 'futbolki', 2, NULL, 'Футболки', 'Футболки', 'Футболки', 'Футболки', '', 684),
(23, 1423569554, 1423664965, 1, 2, 'Аксессуары', 'aksessuary', 0, 'cd9956f00ad0cf65609a99e46014501f.jpg', NULL, NULL, NULL, NULL, '', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_tree_brands`
--

CREATE TABLE IF NOT EXISTS `catalog_tree_brands` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) NOT NULL,
  `catalog_tree_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`),
  KEY `catalog_tree_id` (`catalog_tree_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Дамп данных таблицы `catalog_tree_brands`
--

INSERT INTO `catalog_tree_brands` (`id`, `brand_id`, `catalog_tree_id`) VALUES
(57, 1, 3),
(59, 1, 23),
(64, 11, 1),
(65, 27, 1),
(66, 12, 1),
(67, 17, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_tree_sizes`
--

CREATE TABLE IF NOT EXISTS `catalog_tree_sizes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `catalog_tree_id` int(10) NOT NULL,
  `size_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_tree_id` (`catalog_tree_id`),
  KEY `size_id` (`size_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=151 ;

--
-- Дамп данных таблицы `catalog_tree_sizes`
--

INSERT INTO `catalog_tree_sizes` (`id`, `catalog_tree_id`, `size_id`) VALUES
(128, 3, 10),
(129, 3, 11),
(130, 3, 12),
(131, 3, 13),
(132, 3, 14),
(133, 3, 15),
(134, 3, 7),
(135, 3, 16),
(143, 23, 10),
(144, 23, 11),
(145, 23, 12),
(146, 23, 13),
(147, 23, 14),
(148, 23, 15),
(149, 23, 7),
(150, 23, 16);

-- --------------------------------------------------------

--
-- Структура таблицы `catalog_tree_specifications`
--

CREATE TABLE IF NOT EXISTS `catalog_tree_specifications` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `catalog_tree_id` int(10) NOT NULL,
  `specification_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_tree_id` (`catalog_tree_id`),
  KEY `specification_id` (`specification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

--
-- Дамп данных таблицы `catalog_tree_specifications`
--

INSERT INTO `catalog_tree_specifications` (`id`, `catalog_tree_id`, `specification_id`) VALUES
(96, 3, 2),
(97, 3, 4),
(98, 3, 9),
(99, 3, 1),
(100, 23, 2),
(101, 23, 4),
(102, 23, 9),
(103, 23, 1),
(104, 1, 4),
(105, 1, 9),
(106, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `zna` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `key` varchar(255) CHARACTER SET cp1251 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`id`, `name`, `zna`, `updated_at`, `status`, `sort`, `type`, `key`) VALUES
(1, 'E-mail администратора сайта (на него приходят уведомления с контактной формы)', 'markova.t.wezom@gmail.com', 1423495242, 1, 3, 0, 'admin_email'),
(2, 'Название сайта', 'Airpac', 1423495242, 1, 1, 0, 'name_site'),
(3, 'Наименование "Владельца сайта" для Title на каждой странице', 'Airpac', 1423495242, 1, 2, 0, 'url_site'),
(4, 'Copyright', '2014 © Интернет магазин спортивной обуви и одежды', 1423495242, 1, 4, 0, 'copyright'),
(5, 'Отображение всплывающих сообщений', 'top', 1419349591, 0, 2000, 3, 'message_output'),
(6, 'Номер телефона в шапке сайта', '+380 (67) 855-85-77', 1423495242, 1, 6, 0, 'phone'),
(7, 'Количество товаров на странице', '30', 1423495242, 1, 7, 0, 'limit'),
(8, 'Количество статей на главной странице', '2', 1423495242, 1, 8, 0, 'limit_articles_main_page'),
(9, 'Количество строк в админ-панели', '20', 1423495242, 1, 9, 0, 'limit_backend'),
(10, 'VK.com', 'http://vk.com/airpacgroup', 1423495242, 1, 10, 0, 'vk'),
(11, 'FB.com', 'http://fb.com', 1423495242, 1, 11, 0, 'fb'),
(12, 'Instagram', 'http://instagram.com', 1423495242, 1, 12, 0, 'instagram'),
(13, 'Надпись в подвале сайта для подписчиков', 'Хочешь быть в числе первых, кому мы сообщим об акциях и новинках?!', 1423495242, 1, 5, 0, 'subscribe_text'),
(14, 'Количество дней, которое товар ( отмеченный галочкой "Новинка" ) будет новым', '20', 1423495242, 1, 13, 0, 'new_days'),
(15, 'Время доставки ( Есть в наличии )', 'доставка 1-3 дня', 1423495242, 1, 14, 0, 'dostavka_est'),
(16, 'Время доставки ( Под заказ )', 'доставка 25-30 дней', 1423495242, 1, 15, 0, 'dostavka_bron'),
(17, 'Количество новостей / статей на странице', '10', 1423495242, 1, 7, 0, 'limit_articles'),
(18, 'Количество групп товаров на странице', '15', 1423495242, 1, 7, 0, 'limit_groups');

-- --------------------------------------------------------

--
-- Структура таблицы `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `text` text,
  `ip` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `contacts`
--

INSERT INTO `contacts` (`id`, `created_at`, `updated_at`, `status`, `name`, `email`, `text`, `ip`) VALUES
(1, 1422296735, 1422973284, 1, 'Виталий', 'demyanenko.v.wezom@gmail.com', 'Ну шотам?', '127.0.0.1'),
(2, 1422296795, 1422973284, 1, 'Виталий', 'demyanenko.v.wezom@gmail.com', 'Ну шотам?', '127.0.0.1'),
(3, 1422296862, 1422300811, 1, 'Виталий', 'demyanenko.v.wezom@gmail.com', 'Ну шотам?', '127.0.0.1'),
(12, 1423469857, 1424951313, 1, '123', 'palenaya.v.wezom@gmail.com', '123', '178.136.229.251'),
(13, 1424953894, NULL, 0, 'авап', 'demyanenko.v.wezom@gmail.com', '“[|]’~<!--@/*$%^&#*/()?>,.*/\\ ', '127.0.0.1');

-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `alias` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `title` text CHARACTER SET cp1251,
  `description` text CHARACTER SET cp1251,
  `keywords` text CHARACTER SET cp1251,
  `text` text CHARACTER SET cp1251,
  `status` int(1) DEFAULT '1',
  `created_at` int(10) DEFAULT NULL,
  `h1` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `class` varchar(64) DEFAULT NULL,
  `views` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `action` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `content`
--

INSERT INTO `content` (`id`, `name`, `alias`, `title`, `description`, `keywords`, `text`, `status`, `created_at`, `h1`, `updated_at`, `sort`, `parent_id`, `class`, `views`) VALUES
(2, 'FAQ', 'faq', 'FAQ title', 'FAQ description', 'FAQ keywords', '&lt;p&gt;Помощь&lt;br /&gt;Гарантия и возврат.&lt;br /&gt;&lt;br /&gt;Есть ли гарантия на товар?&lt;br /&gt;- В нашем интернет магазине предоставляется гарантия 2 месяца на весь ассортимент женской и мужской обуви. &lt;br /&gt;&lt;br /&gt;Что делать если обувь пришла бракованная?&lt;br /&gt;&lt;br /&gt;- В случае если обувь, которую вы получили, имеет брак, обязательно свяжитесь с нами по одному из этих номеров +380(63)530-99-11; +380(67)855-85-75 или напишите нам на e-mail: my.airpac@ya.ru. Мы постараемся в кротчайшие сроки сделать возврат или обмен товара. &lt;br /&gt;&lt;br /&gt;Что делать если в период гарантийного срока на обуви стали проявляться скрытые дефекты?&lt;br /&gt;- В таком случае обязательно свяжитесь с нами по одному из этих номеров +380(63)530-99-11; +380(67)855-85-75 или напишите нам на e-mail: my.airpac@ya.ru. Мы постараемся в кротчайшие сроки сделать возврат или обмен товара. &lt;br /&gt;&lt;br /&gt;Есть ли гарантия на одежду?&lt;br /&gt;- Наш интернет магазин не предоставляет гарантию на одежду и головные уборы. &lt;br /&gt;&lt;br /&gt;Что делать если обувь или одежда не подошли по размеру?&lt;br /&gt;Если случилось так, что придя домой, вы поняли, что обувь или одежда не подходит вам по размеру, свяжитесь обязательно с нами, и мы осуществим возврат или обмен товара.&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;Как вернуть товар?&lt;br /&gt;Вы идете в отделение новой почты и высылаете товар на указанные реквизиты. При этом все затраты связанные с доставкой товара вы берете на себя. Дабы минимизировать эти затраты, мы предлагаем отправлять товар без наложенного платежа, и при получении его нами, деньги в полном объеме будут перечислены на указанный вами номер банковской карточки. &lt;br /&gt;&lt;br /&gt;&lt;br /&gt;В какой период я могу вернуть или обменять товар?&lt;br /&gt;Если вещь, которую вы хотите вернуть сохранила товарный вид, а так же тару (коробка, пакет) в которой она поставлялась, вы можете вернуть ее в течение 14 дней с момента получения на почте. Если же это обмен, после получения нами товара, мы в этот же день высылаем вам другой надлежащего качества и размера. &lt;br /&gt;&lt;br /&gt;Оплата.&lt;br /&gt;&lt;br /&gt;Можно ли оплатить товар при получении?&lt;br /&gt;Так как мы высылаем товар наложенным платежом, вы оплачиваете его при получении на почте. Если же это курьерская доставка, оплата производится при личной встрече.&lt;br /&gt;Могу ли я оплатить полностью всю стоимость товара на карточку?&lt;br /&gt;Вы можете оплатить полностью всю стоимость товара на карточку до того как он будет отправлен вам. Просим заранее сообщать об этом.&lt;br /&gt;Какие электронные способы оплаты вы поддерживаете?&lt;br /&gt;В данный момент доступен лишь один способ электронной оплаты &amp;ndash;пополнение карточки Приватбанк.&lt;br /&gt;Могу ли я примерять товар перед оплатой?&lt;br /&gt;Да, вы можете примерять товар перед оплатой на почте. При этом вы оставляете задаток оператору в размере полной стоимости товара, после чего идете делать осмотр и примерку. Если товар вам подходит, вы оплачиваете доставку и забираете его. Если же нет, пишите заявление на возврат и возвращаете товар обратно нам.&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;Доставка &lt;br /&gt;&lt;br /&gt;Как происходит доставка товара?&lt;br /&gt;Доставка товара осуществляется службой экспресс доставки Новая почта.&lt;br /&gt;Если я с Днепропетровска, могу ли я забрать товар лично при встрече?&lt;br /&gt;Да, вы можете забрать товар лично при встрече, заранее оговорив с оператором место и время.&lt;br /&gt;Сколько по времени занимает доставка товара?&lt;br /&gt;Срок доставки товара в наличии составляет 1-3 дня по территории Украины. Если же товара в наличии нет, срок доставки увеличивается до 14-28 дней.&lt;br /&gt;Какими курьерскими службами вы отправляете товар?&lt;br /&gt;В данный момент доставка осуществляется только компанией Новая почта.&lt;br /&gt;Как я узнаю о том, что товар пришел?&lt;br /&gt;Мы обязательно связываемся с клиентом за 1-2 дня до того как товар поступит на наш склад в Днепропетровск. Когда товар будет доставлен на отделение Новой почты в вашем городе, на ваш номер придет sms оповещение о том, что товар прибыл.&lt;br /&gt;Кто оплачивает доставку?&lt;br /&gt;Доставку оплачивает клиент. В стоимость товара не входит сума доставки.&lt;br /&gt;Cколько стоит доставка?&lt;br /&gt;Стоимость доставки рассчитывается индивидуально, и зависит от объема и стоимости товара, а так же от отдаленности между городом отправителя и городом получателя. В среднем стоимость доставки одной пары кроссовок или одной футболки составляет от 20 до 30 грн. Так же клиент оплачивает обратную доставку денег 25-35 грн. В общем, средняя стоимость доставки составляет 50-55 грн.&lt;br /&gt;Нужны ли документы для получения товара?&lt;br /&gt;При получении товара вы обязаны предъявить документы оператору, подтверждающие вашу личность. Это может быть как паспорт, так и водительские права.&lt;br /&gt;Если я несовершеннолетний?&lt;br /&gt;Если вы еще не достигли совершеннолетия, ваши родители могут получить товар за вас, при этом в заказе следует указывать Фамилию и Имя одного из родителей.&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;Качество товара и производство. &lt;br /&gt;&lt;br /&gt;Товар оригинальный?&lt;br /&gt;Мы занимаемся исключительно качественными копиями, товар не оригинальный. На всю обувь предоставляется гарантия 2 месяца, а так же право возврата и обмена товара.&lt;br /&gt;Качество хорошее?&lt;br /&gt;Так как у каждого человека свое субъективное понимание качественного товара, мы не вправе внушать ему свою точку зрения и свое мнение. Поэтому, мы предоставляем клиенту возможность перед оплатой тщательно осмотреть товар и примерять его. Но для копий качество довольно таки хорошее.&lt;br /&gt;Кто производитель?&lt;br /&gt;Весь товар производится в Китае.&lt;br /&gt;Чем отличается оригинальные модели от копий (реплик)?&lt;br /&gt;В отличие от оригинальных моделей в копиях используются искусственные материалы, искусственная кожа и искусственная замша. По всем же остальным параметрам они полностью соответствуют оригинальным моделям.&lt;br /&gt;Есть ли у вас оригинал?&lt;br /&gt;Мы занимаемся исключительно качественными копиями, совмещая доступную цену с хорошим качеством товара. Возможно, в ближайшем будущем у нас появятся и оригинальные модели.&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;Размеры &lt;br /&gt;&lt;br /&gt;Как правильно подобрать размер обуви?&lt;br /&gt;Приоритетом при выборе размера является длина стопы в сантиметрах. Но так как при замере часто возникают погрешности, мы ориентируемся на обозначения с размерами, которые указаны на ваших повседневных кроссовках с внутренней стороны язычка. На рисунке вы можете наглядно увидеть, как выглядят эти обозначения.&lt;br /&gt;У вас обувь маломерная?&lt;br /&gt;Да, все наша обувь маломерная, поэтому советуем вам, выбирать из двух размеров которые вы носите, самый большой. Это и будет европейский размер. Например, если вам подходит и 37-й и 38-й размеры, значит заказывать нужно 38-й.&lt;br /&gt;Как подобрать размер куртки или футболки?&lt;br /&gt;Так как у нас все куртки и футболки маломерные, и буквенные обозначения не совпадают с повседневными, размер подбирать следует исходя из роста и веса. Зная два этих параметра, мы можем более точно выбрать вам подходящий размер.&lt;/p&gt;', 1, 1421594834, 'FAQ h1', 1423559293, 4, 0, NULL, 3),
(5, 'Доставка', 'dostavka', 'Доставка', 'Доставка', 'Доставка', '&lt;p&gt;Одним из важных моментов в работе интернет магазина является доставка товара. Поэтому, выбирая способ доставки, мы всегда ориентируемся на скорость, качество и надежность.&lt;br /&gt;&lt;br /&gt;Сроки доставки.&lt;br /&gt;&lt;br /&gt;Доставка товара под заказ &amp;ndash; подразумевает под сбой то, что товара в данный момент нет в наличии, он находится за границей, и срок его доставки составит от 28 до 35 дней. При этом время, затрачиваемое на доставку товара по территории Украины не учитывается.&lt;br /&gt;Доставка товара в наличии &amp;ndash; самый быстрый способ, так как товар находится на складе в Днепропетровске, и срок его доставки составит 1-3 дня.&lt;/p&gt;\r\n&lt;p&gt;&lt;img style=&quot;float: left; border-width: 5px; margin: 5px;&quot; src=&quot;../../../../files/filemanager/4.png&quot; alt=&quot;&quot; width=&quot;246&quot; height=&quot;319&quot; /&gt;&lt;br /&gt;Способы доставки.&lt;br /&gt;&lt;br /&gt;Курьерская доставка &amp;ndash; осуществляется курьером интернет магазина, и подразумевает под собой передачу товара непосредственно в руки клиенту при личной встрече. Время и место оговариваются после того, как товар поступил на склад в город Днепропетровск.&lt;br /&gt;Доставка Новой почтой - осуществляется лидером экспресс доставки в Украине, компанией &amp;laquo;Нова пошта&amp;raquo; и составляет 1-3 дня с момента поступления товара на склад в город Днепропетровск. Все адреса и номера отделений вы можете посмотреть непосредственно на сайте компании отправителя: http://novaposhta.ua/&lt;br /&gt;&lt;br /&gt;Стоимость доставки.&lt;br /&gt;&lt;br /&gt;Курьерская доставка по городу Днепропетровск бесплатная.&lt;br /&gt;&lt;br /&gt;Доставка Новой почтой рассчитывается индивидуально, и зависит от объема и стоимости товара, а так же от отдаленности между городом отправителя и городом получателя. В среднем стоимость доставки одной пары кроссовок или одной футболки составляет от 20 до 30 грн. Так же клиент оплачивает обратную доставку денег 25-35 грн. В общем, средняя стоимость доставки составляет 50-55 грн.&lt;br /&gt;&lt;br /&gt;Важно!&lt;br /&gt;&lt;br /&gt;- В связи с непредвиденными обстоятельствами, такими как пожары, наводнения, или другие стихийные бедствия на территории страны производителя, а так же в связи с праздниками или затрудненной работой таможенной службы, возможны задержки товара на незначительный срок.&amp;nbsp; - Доставка осуществляется только по территории Украины и не распространяется на территорию России, Белоруссии, а так же территории других стран пост советского пространства.&lt;/p&gt;', 1, 1421738919, 'Доставка', 1423559451, 0, 0, 'dostavka', 0),
(6, 'Оплата', 'oplata', 'Оплата', 'Оплата', 'Оплата', '&lt;p&gt;Ни для кого не секрет, что при покупке в интернет магазине, человек подвергается определенным рискам, одним из которых является оплата товара. Во многих интернет магазинах она осуществляется еще до того как клиент получает свой заказ. При этом он может ожидать от продавца чего угодно: получения некачественного товара, несоответствие размеров или цветов, а в худшем случае продавец вообще пропадет сразу же после получении 100%-й оплаты товара.&lt;br /&gt;Дабы минимизировать эти риски и сделать покупку товара в интернет магазине более безопасной, мы предоставляем несколько способов оплаты товара.&lt;/p&gt;\r\n&lt;p&gt;&lt;img style=&quot;border-width: 10px; margin: 5px auto; display: block;&quot; src=&quot;../../../../files/filemanager/ca8e504bb973b5e1ca6a0dffff63f961.png&quot; alt=&quot;&quot; width=&quot;300&quot; height=&quot;240&quot; /&gt;&lt;/p&gt;\r\n&lt;p&gt;&lt;br /&gt;Наложенным платежом (более безопасный) &amp;ndash; осуществляется при встрече с курьером интернет магазина или же в отделении Новой почты при получении товара, при этом, клиент оплачивает обратную доставку денег, величина которой зависит от стоимости товара и в среднем составляет 25-35 грн.&lt;br /&gt;Электронный перевод (менее затратный) &amp;ndash; осуществляется на карточку Приватбанка, и оговаривается заранее, до момента отправки товара. Реквизиты для оплаты высылаются интернет магазином на e-mail клиента или же на мобильный номер. После перевода средств, клиент обязуется, сообщит продавцу точное время и суму зачисления. Выбрав этот способ, покупатель экономит примерно 25-35 грн. на обратной доставке денег.&lt;/p&gt;', 1, 1421739002, 'Оплата', 1423559614, 0, 5, 'oplata', 1),
(7, 'Гарантия', 'garantija', 'Гарантия', 'Гарантия', 'Гарантия', '&lt;p&gt;По статистике, при выборе интернет магазина, 70% потенциальных покупателей уделяют особое внимание наличию сервисов гарантии, обмена и возврата товара. Это очень важные нюансы и мы им уделяем особое внимание. В данный момент существует огромное количество размерных сеток и таблиц, и среди всего этого разнообразия человеку не мудрено ошибиться при выборе своего размера. Поэтому мы предоставляем сервисы обмена и возврата товара, с условиями, которых вы можете ознакомиться ниже. Так же нашим интернет магазином предоставляется гарантия на товар, но обо всем по порядку.&lt;br /&gt;&lt;img style=&quot;border-width: 5px; margin: 5px auto; display: block;&quot; src=&quot;../../../../files/filemanager/garantia_bolsh.png&quot; alt=&quot;&quot; width=&quot;300&quot; height=&quot;221&quot; /&gt;&lt;br /&gt;Гарантия.&lt;br /&gt;&lt;br /&gt;Гарантия предоставляется на весь сегмент обуви в нашем интернет магазине и составляет 2 месяца с момента получения товара клиентом.&lt;br /&gt;Если в период гарантийного срока, в процессе носки появились непредвиденные дефекты (отрывы по шву или отклеивание), вы имеете право на обмен товара или возврат денег. В случае возникновения такой ситуации, вы должны как можно быстрее сообщить об этом продавцу, предоставив несколько фотографий места дефекта, а так же общего вида обуви.&lt;br /&gt;После проведенной оценки и просмотра фотографий, мы принимаем решение о возврате или же обмене товара.&lt;br /&gt;&lt;br /&gt;&lt;br /&gt;&lt;/p&gt;', 1, 1421739016, 'Гарантия', 1423559680, 1, 0, 'garanty', 0),
(8, 'Возврат', 'vozvrat', 'Возврат', 'Возврат', 'Возврат', '&lt;p&gt;Возврат и обмен.&lt;br /&gt;&lt;br /&gt;В случае если вам не подошел размер, не понравилась сама модель или же имеют место быть причины, о которых вы не считаете нужным уведомить продавца, вы имеете право вернуть товар в течение 14-ти дней с момента его получения. При этом вещь должна быть неношеной, иметь товарный вид и тару (коробку, пакет) в котором изначально поставлялась вам.&lt;br /&gt;&lt;img style=&quot;border-width: 10px; margin: 5px auto; display: block;&quot; src=&quot;../../../../files/filemanager/0_c0bf5_3953ba58_L.png&quot; alt=&quot;&quot; width=&quot;300&quot; height=&quot;300&quot; /&gt;&lt;br /&gt;Процесс возврата и обмена товара.&lt;br /&gt;&lt;br /&gt;Уведомив продавца о возврате и согласовав с ним все моменты, клиент возвращает товар на полученные им реквизиты, новой почтой. При этом все затраты связанные с доставкой он берет на себя. Рекомендуем высылать товар без наложенного платежа, дабы минимизировать затраты связанные с обратной доставкой денег, предварительно указав продавцу ваши реквизиты (Приватбанк). После получения товара, в течение двух дней продавец обязуется перечислить деньги в полном объеме на номер карты, указанной покупателем.&lt;br /&gt;&lt;br /&gt;Возврату и обмену не подлежит товар, если: - имеет царапины и порезы, связанные с неправильной его ноской; - бывший в ремонте; - имеет потертый или грязный вид; - не имеет тары (коробка, пакет), в которой изначально поставлялся покупателю.&lt;/p&gt;', 1, 1421739031, 'Возврат', 1423559712, 2, 0, 'return', 2),
(11, 'Вопрос 2', 'vopros_2', 'Вопрос 2', 'Вопрос 2', 'Вопрос 2', '&lt;h2&gt;Вопрос 2&lt;/h2&gt;', 0, 1421740431, 'Вопрос 2', 1423559254, 0, 2, NULL, 0),
(12, 'Вопрос 3', 'vopros_3', 'Вопрос 3', 'Вопрос 3', 'Вопрос 3', '&lt;h2&gt;Вопрос 3&lt;/h2&gt;', 0, 1421740442, 'Вопрос 3', 1423559256, 2, 2, NULL, 0),
(13, 'О нас', 'o_nas', 'О нас', 'О нас', 'О нас', '&lt;p&gt;Дорогие посетители и клиенты нашего сайта!&lt;br /&gt;Интернет магазин Airpac является на данный момент одним из самых перспективных и быстроразвивающихся магазинов молодежной одежды и обуви на территории Украины. В нашем ассортименте представлено более 4000 пар обуви, а так же множество курток, пуховиков, футболок, толстовок, кепок и прочих аксессуаров. С каждым днем этот ассортимент увеличивается, в продажу входят все новые и новые модели, а вместе с ним растет и наш уровень обслуживания клиентов.&lt;br /&gt;Начиная с 2012-го года мы успели добиться ошеломительных результатов, расширив ассортимент до максимума и сделав его одним из самых крупных в Украине. За это время мы показали себя с лучшей стороны и накопили добротную базу постоянных клиентов.&lt;br /&gt;Изо дня в день мы стараемся стать лучше, наш сервис и обслуживание клиентов становится качественнее, риски связанные с покупкой товара в интернете становятся меньше, а цены и обилие моделей склоняют на свою сторону ежедневно десятки новых клиентов.&lt;br /&gt;Мы уверены, что вместе с вами, с вашей обоснованной критикой и заслуженной нами похвалой, мы достигнем огромных результатов, и с каждым днем будем баловать вас еще большим количеством качественной обуви и одежды!&lt;br /&gt;С Ув. персонал интернет магазина Airpac!&lt;/p&gt;', 1, 1421740458, 'О нас', 1423557006, 3, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `control`
--

CREATE TABLE IF NOT EXISTS `control` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` text,
  `description` text,
  `text` text,
  `alias` varchar(32) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `control`
--

INSERT INTO `control` (`id`, `name`, `h1`, `title`, `keywords`, `description`, `text`, `alias`, `status`) VALUES
(1, 'Главная страница', 'Главная страница АШ114', 'Главная страница ТИТУЛ', 'Главная страница КЕЙВОРДС', 'Главная страница ДЕСКРИПТИОН', '<p>Это текст для главной страницы сайта</p>', 'index', 0),
(2, 'Контакты', 'Контакты1222', 'Контакты', 'Контакты', 'Контакты', '<p><strong>Контактная информация компании Интернет-магазин "Airpac"</strong></p>\r\n<p><br />Название:&nbsp;&nbsp; &nbsp;Интернет-магазин "Airpac"<br />Контактное лицо:&nbsp;&nbsp; &nbsp;Игорь<br />Адрес:&nbsp;&nbsp; &nbsp;Только online-магазин, Днепропетровск, Днепропетровская область, Украина<br />Телефон:&nbsp;&nbsp; &nbsp;<br />+380 (67) 855-85-75<br />+380 (63) 530-99-11<br />Email:&nbsp;&nbsp; &nbsp;<a href="mailto:my.airpac@ya.ru">my.airpac@ya.ru</a></p>\r\n<p>Страница в контатке: <a href="http://vk.com/airpac">http://vk.com/airpac</a> <br />Сообщество в контакте: <a href="http://vk.com/airpacgroup">http://vk.com/airpacgroup</a></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><iframe src="http://prom.ua/map/frame_map/2149604?mode=big" width="100%" height="420" frameborder="0" scrolling="no"></iframe></p>', 'contact', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

--
-- Дамп данных таблицы `log`
--

INSERT INTO `log` (`id`, `created_at`, `updated_at`, `name`, `link`, `ip`, `deleted`, `type`, `status`) VALUES
(1, 1422296573, NULL, 'Заказан звонок', '/backend/callback/new/id/4', '127.0.0.1', 0, 3, 0),
(2, 1422296593, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/3', '127.0.0.1', 0, 7, 0),
(3, 1422296623, NULL, 'Отзыв к товару', '/backend/comments/new/id/3', '127.0.0.1', 0, 6, 0),
(4, 1422296735, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/1', '127.0.0.1', 0, 2, 0),
(5, 1422296795, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/2', '127.0.0.1', 0, 2, 0),
(6, 1422296862, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/3', '127.0.0.1', 0, 2, 0),
(7, 1422359003, NULL, 'Вопрос о товаре', '/backend/questions/new/id/2', '127.0.0.1', 0, 5, 0),
(8, 1422525318, NULL, 'Новый заказ', '/backend/orders/new/id/1', '127.0.0.1', 0, 8, 0),
(9, 1422948000, NULL, 'Новый заказ', '/backend/orders/new/id/3', '178.136.229.251', 0, 8, 0),
(10, 1422962036, NULL, 'Заказ звонка', '/backend/callback/new/id/5', '178.136.229.251', 0, 3, 0),
(11, 1422963413, NULL, 'Заказ звонка', '/backend/callback/new/id/6', '178.136.229.251', 0, 3, 0),
(12, 1422964201, NULL, 'Регистрация пользователя', '/backend/users/new/id/4', '178.136.229.251', 0, 1, 0),
(13, 1422965778, NULL, 'Регистрация пользователя', '/backend/users/new/id/5', '178.136.229.251', 0, 1, 0),
(14, 1422965930, NULL, 'Регистрация пользователя', '/backend/users/new/id/6', '178.136.229.251', 0, 1, 0),
(15, 1422967172, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/4', '178.136.229.251', 0, 7, 0),
(16, 1422970305, NULL, 'Новый заказ', '/backend/orders/new/id/4', '178.136.229.251', 0, 8, 0),
(17, 1422971757, NULL, 'Отзыв к товару', '/backend/comments/new/id/4', '178.136.229.251', 0, 6, 0),
(18, 1422973272, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/4', '178.136.229.251', 0, 2, 0),
(19, 1422974161, NULL, 'Подписчик', '/backend/subscribers/new/id/1', '178.136.229.251', 0, 4, 0),
(20, 1422974954, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/5', '178.136.229.251', 0, 7, 0),
(21, 1423036703, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/6', '178.136.229.251', 0, 7, 0),
(22, 1423037499, NULL, 'Подписчик', '/backend/subscribers/new/id/2', '178.136.229.251', 0, 4, 0),
(23, 1423039261, NULL, 'Регистрация пользователя', '/backend/users/new/id/7', '178.136.229.251', 0, 1, 0),
(24, 1423043808, NULL, 'Подписчик', '/backend/subscribers/new/id/3', '178.136.229.251', 0, 4, 0),
(25, 1423049871, NULL, 'Новый заказ', '/backend/orders/new/id/6', '178.136.229.251', 0, 8, 0),
(26, 1423050169, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/7', '178.136.229.251', 0, 7, 0),
(27, 1423059933, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/8', '178.136.229.251', 0, 7, 0),
(28, 1423062636, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/9', '178.136.229.251', 0, 7, 0),
(29, 1423063892, NULL, 'Отзыв к товару', '/backend/comments/new/id/5', '178.136.229.251', 0, 6, 0),
(30, 1423064384, NULL, 'Отзыв к товару', '/backend/comments/new/id/6', '178.136.229.251', 0, 6, 0),
(31, 1423083936, NULL, 'Отзыв к товару', '/backend/comments/new/id/7', '93.79.159.189', 0, 6, 0),
(32, 1423084098, NULL, 'Отзыв к товару', '/backend/comments/new/id/8', '93.79.159.189', 0, 6, 0),
(33, 1423084202, NULL, 'Новый заказ', '/backend/orders/new/id/8', '93.79.159.189', 0, 8, 0),
(34, 1423084696, NULL, 'Регистрация пользователя, требующая подтверждения', '/backend/users/new/id/8', '93.79.159.189', 0, 1, 0),
(35, 1423084954, NULL, 'Подписчик', '/backend/subscribers/new/id/5', '93.79.159.189', 0, 4, 0),
(36, 1423085019, NULL, 'Новый заказ', '/backend/orders/new/id/9', '93.79.159.189', 0, 8, 0),
(37, 1423085854, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/10', '93.79.159.189', 0, 7, 0),
(38, 1423087071, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/5', '93.79.159.189', 0, 2, 0),
(39, 1423087178, NULL, 'Заказ звонка', '/backend/callback/new/id/7', '93.79.159.189', 0, 3, 0),
(40, 1423087292, NULL, 'Вопрос о товаре', '/backend/questions/new/id/3', '93.79.159.189', 0, 5, 0),
(41, 1423087327, NULL, 'Заказ звонка', '/backend/callback/new/id/8', '93.79.159.189', 0, 3, 0),
(42, 1423087565, NULL, 'Отзыв к товару', '/backend/comments/new/id/9', '93.79.159.189', 0, 6, 0),
(43, 1423087572, NULL, 'Вопрос о товаре', '/backend/questions/new/id/4', '93.79.159.189', 0, 5, 0),
(44, 1423125454, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/11', '178.136.229.251', 0, 7, 0),
(45, 1423129392, NULL, 'Новый заказ', '/backend/orders/new/id/10', '178.136.229.251', 0, 8, 0),
(46, 1423129435, NULL, 'Новый заказ', '/backend/orders/new/id/11', '178.136.229.251', 0, 8, 0),
(47, 1423129828, NULL, 'Новый заказ', '/backend/orders/new/id/12', '178.136.229.251', 0, 8, 0),
(48, 1423129952, NULL, 'Новый заказ', '/backend/orders/new/id/13', '178.136.229.251', 0, 8, 0),
(49, 1423130291, NULL, 'Отзыв к товару', '/backend/comments/new/id/10', '178.136.229.251', 0, 6, 0),
(50, 1423130378, NULL, 'Вопрос о товаре', '/backend/questions/new/id/5', '178.136.229.251', 0, 5, 0),
(51, 1423131702, NULL, 'Регистрация пользователя, требующая подтверждения', '/backend/users/new/id/9', '178.136.229.251', 0, 1, 0),
(52, 1423140463, NULL, 'Новый заказ', '/backend/orders/new/id/14', '178.136.229.251', 0, 8, 0),
(53, 1423140568, NULL, 'Новый заказ', '/backend/orders/new/id/15', '178.136.229.251', 0, 8, 0),
(54, 1423140872, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/6', '178.136.229.251', 0, 2, 0),
(55, 1423141168, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/7', '178.136.229.251', 0, 2, 0),
(56, 1423141359, NULL, 'Новый заказ', '/backend/orders/new/id/16', '178.136.229.251', 0, 8, 0),
(57, 1423148616, NULL, 'Регистрация пользователя, требующая подтверждения', '/backend/users/new/id/10', '178.136.229.251', 0, 1, 0),
(58, 1423152161, NULL, 'Регистрация пользователя, требующая подтверждения', '/backend/users/new/id/11', '178.136.229.251', 0, 1, 0),
(59, 1423207953, NULL, 'Отзыв к товару', '/backend/comments/new/id/11', '178.136.229.251', 0, 6, 0),
(60, 1423208155, NULL, 'Новый заказ', '/backend/orders/new/id/17', '178.136.229.251', 0, 8, 0),
(61, 1423208200, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/8', '178.136.229.251', 0, 2, 0),
(62, 1423209150, NULL, 'Отзыв к товару', '/backend/comments/new/id/12', '178.136.229.251', 0, 6, 0),
(63, 1423218135, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/9', '178.136.229.251', 0, 2, 0),
(64, 1423218320, NULL, 'Отзыв к товару', '/backend/comments/new/id/13', '178.136.229.251', 0, 6, 0),
(65, 1423223279, NULL, 'Отзыв к товару', '/backend/comments/new/id/14', '178.136.229.251', 0, 6, 0),
(66, 1423224885, NULL, 'Отзыв к товару', '/backend/comments/new/id/15', '178.136.229.251', 0, 6, 0),
(67, 1423224911, NULL, 'Вопрос о товаре', '/backend/questions/new/id/6', '178.136.229.251', 0, 5, 0),
(68, 1423225240, NULL, 'Вопрос о товаре', '/backend/questions/new/id/7', '178.136.229.251', 0, 5, 0),
(69, 1423225740, NULL, 'Вопрос о товаре', '/backend/questions/new/id/8', '178.136.229.251', 0, 5, 0),
(70, 1423226287, NULL, 'Вопрос о товаре', '/backend/questions/new/id/9', '178.136.229.251', 0, 5, 0),
(71, 1423229266, NULL, 'Заказ звонка', '/backend/callback/new/id/9', '178.136.229.251', 0, 3, 0),
(72, 1423229294, NULL, 'Регистрация пользователя, требующая подтверждения', '/backend/users/new/id/12', '178.136.229.251', 0, 1, 0),
(73, 1423229352, NULL, 'Новый заказ', '/backend/orders/new/id/18', '178.136.229.251', 0, 8, 0),
(74, 1423230799, NULL, 'Отзыв к товару', '/backend/comments/new/id/16', '178.136.229.251', 0, 6, 0),
(75, 1423232751, NULL, 'Новый заказ', '/backend/orders/new/id/19', '178.136.229.251', 0, 8, 0),
(76, 1423233771, NULL, 'Новый заказ', '/backend/orders/new/id/20', '178.136.229.251', 0, 8, 0),
(77, 1423234131, NULL, 'Новый заказ', '/backend/orders/new/id/21', '178.136.229.251', 0, 8, 0),
(78, 1423235876, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/10', '178.136.229.251', 0, 2, 0),
(79, 1423238783, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/11', '178.136.229.251', 0, 2, 0),
(80, 1423239587, NULL, 'Новый заказ', '/backend/orders/new/id/22', '178.136.229.251', 0, 8, 0),
(81, 1423317054, NULL, 'Регистрация пользователя, требующая подтверждения', '/backend/users/new/id/13', '95.134.141.85', 0, 1, 0),
(82, 1423467998, NULL, 'Регистрация пользователя, требующая подтверждения', '/backend/users/new/id/14', '178.136.229.251', 0, 1, 0),
(83, 1423468939, NULL, 'Заказ звонка', '/backend/callback/new/id/10', '178.136.229.251', 0, 3, 0),
(84, 1423469857, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/12', '178.136.229.251', 0, 2, 0),
(85, 1423473487, NULL, 'Новый заказ', '/backend/orders/new/id/23', '178.136.229.251', 0, 8, 0),
(86, 1423477696, NULL, 'Отзыв к товару', '/backend/comments/new/id/17', '178.136.229.251', 0, 6, 0),
(87, 1423477874, NULL, 'Отзыв к товару', '/backend/comments/new/id/18', '178.136.229.251', 0, 6, 0),
(88, 1423483019, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/12', '178.136.229.251', 0, 7, 0),
(89, 1423483079, NULL, 'Новый заказ', '/backend/orders/new/id/24', '178.136.229.251', 0, 8, 0),
(90, 1423498197, NULL, 'Отзыв к товару', '/backend/comments/new/id/19', '178.136.229.251', 0, 6, 0),
(91, 1423663833, NULL, 'Заказ в один клик', '/backend/orders_simple/new/id/13', '127.0.0.1', 0, 7, 0),
(92, 1423663886, NULL, 'Отзыв к товару', '/backend/comments/new/id/20', '127.0.0.1', 0, 6, 0),
(93, 1423663897, NULL, 'Вопрос о товаре', '/backend/questions/new/id/10', '127.0.0.1', 0, 5, 0),
(94, 1423664650, NULL, 'Подписчик', '/backend/subscribers/new/id/6', '127.0.0.1', 0, 4, 0),
(95, 1423664685, NULL, 'Заказ звонка', '/backend/callback/new/id/11', '127.0.0.1', 0, 3, 0),
(96, 1424248645, NULL, 'Подписчик', '/backend/subscribers/new/id/Array', '127.0.0.1', 0, 4, 0),
(97, 1424249058, NULL, 'Подписчик', '/backend/subscribers/new/id/Array', '127.0.0.1', 0, 4, 0),
(98, 1424334596, NULL, 'Новый заказ', '/backend/orders/new/id/Array', '127.0.0.1', 0, 8, 0),
(99, 1424334627, NULL, 'Новый заказ', '/backend/orders/new/id/Array', '127.0.0.1', 0, 8, 0),
(100, 1424334660, NULL, 'Новый заказ', '/backend/orders/new/id/Array', '127.0.0.1', 0, 8, 0),
(101, 1424334784, NULL, 'Подписчик', '/backend/subscribers/new/id/Array', '127.0.0.1', 0, 4, 0),
(102, 1424334792, NULL, 'Подписчик', '/backend/subscribers/new/id/Array', '127.0.0.1', 0, 4, 0),
(103, 1424334834, NULL, 'Новый заказ', '/backend/orders/new/id/Array', '127.0.0.1', 0, 8, 0),
(104, 1424355286, NULL, 'Вопрос о товаре', '/backend/questions/new/id/', '127.0.0.1', 0, 5, 0),
(105, 1424355295, NULL, 'Отзыв к товару', '/backend/comments/new/id/', '127.0.0.1', 0, 6, 0),
(106, 1424953894, NULL, 'Сообщение из контактной формы', '/backend/contacts/new/id/13', '127.0.0.1', 0, 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `mail_templates`
--

CREATE TABLE IF NOT EXISTS `mail_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `name`, `subject`, `text`, `updated_at`, `status`) VALUES
(1, 'Контактная форма ( Администратору )', 'Новое сообщение из контактной формы - сайт {{site}}', '<p>Вам пришло новое письмо из контактной формы с сайта {{site}}!</p>\r\n<p>Имя отправителя: {{name}} ( {{ip}} ).</p>\r\n<p>E-Mail отправителя: {{email}}.</p>\r\n<p>IP отправителя: {{ip}}.</p>\r\n<p>Дата сообщения: {{date}}.</p>\r\n<p>Текст сообщения: {{text}}.</p>\r\n<p>&nbsp;</p>\r\n<p>Письмо сгенерировано автоматически. Пожалуйста не отвечайте на него!</p>', 1424951595, 1),
(2, 'Письмо после подписки ( Пользователю )', 'Спасибо за подписку! Сайт {{site}}', '&lt;p&gt;Спасибо за подписку на новости и акции на сайте {{site}}.&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\r\n&lt;h5&gt;&lt;span style=&quot;color: #999999;&quot;&gt;Для того, что бы отписаться, перейдите по ссылке {{link}}.&lt;/span&gt;&lt;/h5&gt;\r\n&lt;h5&gt;&lt;span style=&quot;color: #999999;&quot;&gt;Письмо создано автоматически. Пожалуйста не отвечайте на него.&lt;/span&gt;&lt;/h5&gt;', 1423057992, 1),
(3, 'Заказ звонка ( Администратору )', 'Заказ звонка', '&lt;p&gt;Новый заказ звонка!&lt;/p&gt;\r\n&lt;p&gt;Зовут: {{name}}.&lt;/p&gt;\r\n&lt;p&gt;Номер телефона: {{phone}}.&lt;/p&gt;', 1423057992, 1),
(4, 'Подтверждение регистрации ( Пользователю )', 'Пожалуйста, подтвердите свой почтовый адрес, сайт {{site}}', '&lt;p&gt;Для подтверждения регистрации на сайте {{site}}, перейдите по ссылке:&lt;/p&gt;\r\n&lt;p&gt;{{link}}&lt;/p&gt;', 1423057993, 1),
(5, 'Восстановление пароля ( Пользователю )', 'Восстановление пароля', '&lt;p&gt;Ваш новый пароль для входа на сайт {{site}}:&lt;/p&gt;\r\n&lt;p&gt;{{password}}&lt;/p&gt;', 1423057994, 1),
(6, 'Изменение пароля ( Пользователю )', 'Изменение пароля - {{site}}', '&lt;p&gt;Ваш новый пароль:&lt;/p&gt;\r\n&lt;p&gt;{{password}}&lt;/p&gt;', 1423057995, 1),
(7, 'Новый коментарий ( Администратору )', 'Новый коментарий', '&lt;p&gt;Новый коментарий&lt;/p&gt;', 1423057996, 1),
(8, 'Новый быстрый заказ ( Администратору )', 'Новый быстрый заказ', 'Новый быстрый заказ', 1423057996, 1),
(9, 'Вопрос о товаре ( Администратору )', 'Вопрос о товаре', 'Вопрос о товаре', 1423057997, 1),
(10, 'Вопрос о товаре ( Пользователю )', 'Вопрос о товаре', 'Вопрос о товаре', 1423057998, 1),
(11, 'Новый заказ ( Администратору )', 'Новый заказ', '&lt;p&gt;Для уточнения деталей перейдите по ссылке:&lt;/p&gt;\r\n&lt;p&gt;{{link_admin}}&lt;/p&gt;', 1422525231, 1),
(12, 'Новый заказ ( Пользователю )', 'Новый заказ', '&lt;p&gt;Спасибо что купили у нас:&lt;/p&gt;\r\n&lt;p&gt;{{items}}&lt;/p&gt;\r\n&lt;p&gt;Подробнее по ссылке: {{link_user}}&lt;/p&gt;', 1422525285, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) DEFAULT '0',
  `name` varchar(255) CHARACTER SET cp1251 DEFAULT NULL,
  `link` varchar(255) CHARACTER SET cp1251 DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `id_content` int(11) DEFAULT '0',
  `created_at` int(10) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `image` varchar(255) CHARACTER SET cp1251 DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `adm` int(1) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  `ctrls` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `id_parent`, `name`, `link`, `sort`, `id_content`, `created_at`, `status`, `image`, `updated_at`, `adm`, `icon`, `ctrls`) VALUES
(1, 0, 'Панель управления', 'index/index', 0, 0, NULL, 1, NULL, NULL, 0, 'fa-dashboard', 'index'),
(2, 0, 'Модуль SEO', NULL, 16, 0, NULL, 1, '', NULL, 0, 'fa-tags', 'templates;links;metrika;counters'),
(3, 0, 'Пользователи', 'users/index', 10, 0, NULL, 1, NULL, NULL, 0, 'fa-users', 'users'),
(4, 0, 'Настройки сайта', 'config/edit', 15, 0, NULL, 1, NULL, NULL, 0, 'fa-cogs', 'config'),
(5, 0, 'Управление страницами', NULL, 1, 0, NULL, 1, NULL, NULL, 0, 'fa-folder-open-o', 'content;control'),
(6, 5, 'Список текстовых страниц', 'content/index', 1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(7, 5, 'Добавить текстовую страницу', 'content/add', 2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(8, 2, 'Метрика', 'seo/metrika/index', 1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(9, 2, 'Счётчики', 'seo/counters/index', 3, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(10, 2, 'Добавить метрику', 'seo/metrika/add', 2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(11, 2, 'Добавить счётчик', 'seo/counter/add', 4, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(12, 2, 'Список тегов для конкретных ссылок', 'seo/links/index', 0, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(13, 2, 'Добавить теги для ссылки', 'seo/links/add', 0, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(14, 0, 'Шаблоны писем', 'mailTemplates/index', 14, 0, NULL, 1, NULL, NULL, 0, 'fa-file-image-o', 'mailTemplates'),
(15, 0, 'Меню', NULL, 4, 0, NULL, 1, NULL, NULL, 0, 'fa-list-ul', 'menu'),
(16, 15, 'Список пунктов меню', 'menu/index', 1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(17, 15, 'Добавить пункт в меню', 'menu/add', 2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(18, 0, 'Новости', NULL, 2, 0, NULL, 1, NULL, NULL, 0, 'fa-bullhorn', 'news'),
(19, 18, 'Список новостей', 'news/index', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, ''),
(20, 18, 'Добавить новость', 'news/add', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(21, 0, 'Статьи', NULL, 3, 0, NULL, 1, NULL, NULL, 0, 'fa-language', 'articles'),
(22, 21, 'Список статей', 'articles/index', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(23, 21, 'Добавить статью', 'articles/add', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(24, 0, 'Слайдшоу', NULL, 5, 0, NULL, 1, NULL, NULL, 0, 'fa-picture-o', 'slider'),
(25, 24, 'Список слайдов', 'slider/index', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(26, 24, 'Добавть слайд', 'slider/add', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(27, 0, 'Банерная система', NULL, 6, 0, NULL, 1, NULL, NULL, 0, 'fa-puzzle-piece', 'banners'),
(28, 27, 'Список банеров', 'banners/index', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(29, 27, 'Добавить банер', 'banners/add', NULL, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(30, 0, 'Рассылка', NULL, 11, 0, NULL, 1, NULL, NULL, 0, 'fa-rss', 'subscribe;subscribers'),
(31, 30, 'Список подписчиков', 'subscribers/index', 1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(32, 30, 'Добавить подписчика', 'subscribers/add', 2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(33, 30, 'Список разосланных писем', 'subscribe/index', 3, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(34, 30, 'Разослать письмо', 'subscribe/send', 4, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(35, 0, 'Связь', NULL, 12, 0, NULL, 1, NULL, NULL, 0, 'fa-envelope-o', 'contacts;callback;questions'),
(36, 35, 'Сообщения из контактной формы', 'contacts/index', 1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(37, 35, 'Заказы звонка', 'callback/index', 2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(38, 0, 'Каталог', NULL, 7, 0, NULL, 1, NULL, NULL, 0, 'fa-inbox', 'groups;items;brands;models;specifications;sizes'),
(39, 38, 'Группы товаров', 'groups/index', 1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(40, 38, 'Добавить группу товаров', 'groups/add', 2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(41, 38, 'Товары', 'items/index', 3, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(42, 38, 'Добавить товар', 'items/add', 4, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(43, 38, 'Производители', 'brands/index', 5, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(44, 38, 'Добавить производителя', 'brands/add', 6, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(45, 38, 'Модели', 'models/index', 7, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(46, 38, 'Добавить модель', 'models/add', 8, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(47, 38, 'Характеристики', 'specifications/index', 11, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(48, 38, 'Добавить характеристику', 'specifications/add', 12, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(49, 35, 'Вопрос по товару', 'questions/index', 3, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(50, 0, 'Заказы', NULL, 9, 0, NULL, 1, NULL, NULL, 0, 'fa-shopping-cart', 'orders;simple'),
(51, 50, 'Обычные', 'orders/index', 1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(52, 50, 'В один клик', 'simple/index', 2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(53, 0, 'Отзывы к товарам', 'items_comments/index', 8, 0, NULL, 1, NULL, NULL, 0, 'fa-weixin', 'itemsComments'),
(54, 0, 'Лента событий', 'log/index', 13, 0, NULL, 1, NULL, NULL, 0, 'fa-tasks', 'log'),
(55, 38, 'Размеры', 'sizes/index', 9, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(56, 38, 'Добавить размер', 'sizes/add', 10, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(57, 2, 'Шаблон группы товаров', 'seo/templates/1', -2, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(58, 2, 'Шаблон товаров', 'seo/templates/2', -1, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(59, 5, 'Главная страница', 'control/index', 3, 0, NULL, 1, NULL, NULL, 0, NULL, NULL),
(60, 5, 'Контакты', 'control/contact', 4, 0, NULL, 1, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `message_output`
--

CREATE TABLE IF NOT EXISTS `message_output` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `zna` varchar(255) DEFAULT NULL,
  `id_config` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `message_output`
--

INSERT INTO `message_output` (`id`, `name`, `zna`, `id_config`) VALUES
(1, 'Отображать вверху', 'top', 2000),
(2, 'Отображать вверху слева', 'topLeft', 2000),
(3, 'Отображать вверху по центру', 'topCenter', 2000),
(4, 'Отображать вверху справа', 'topRight', 2000),
(5, 'Отображать по центру слева', 'centerLeft', 2000),
(6, 'Отображать по центру', 'center', 2000),
(7, 'Отображать по центру справа', 'centerRight', 2000),
(8, 'Отображать внизу слева', 'bottomLeft', 2000),
(9, 'Отображать внизу по центру', 'bottomCenter', 2000),
(10, 'Отображать внизу справа', 'bottomRight', 2000),
(11, 'Отображать внизу', 'bottom', 2000);

-- --------------------------------------------------------

--
-- Структура таблицы `models`
--

CREATE TABLE IF NOT EXISTS `models` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `brand_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=88 ;

--
-- Дамп данных таблицы `models`
--

INSERT INTO `models` (`id`, `created_at`, `updated_at`, `status`, `name`, `alias`, `brand_id`) VALUES
(26, 1423571699, NULL, 1, 'Gel Saga', 'gel_saga', 11),
(27, 1423571724, NULL, 1, 'Gel Lyte', 'gel_lyte', 11),
(28, 1423571758, NULL, 1, 'Gel Kinsei', 'gel_kinsei', 11),
(29, 1423571776, 1423573515, 1, 'Gel Noosa TRI 8', 'gel_noosa_tri_8', 11),
(30, 1423571792, 1423573502, 1, 'Gel Noosa TRI 7', 'gel_noosa_tri_7', 11),
(31, 1423571813, NULL, 1, 'Gel Stratus', 'gel_stratus', 11),
(32, 1423572204, NULL, 1, 'Gel Volt', 'gel_volt', 11),
(33, 1423572259, NULL, 1, '574', '574', 7),
(34, 1423572420, NULL, 1, '996', '996', 7),
(35, 1423572432, NULL, 1, '999', '999', 7),
(36, 1423572443, NULL, 1, '998', '998', 7),
(37, 1423572460, NULL, 1, '580', '580', 7),
(38, 1423572471, NULL, 1, '1300', '1300', 7),
(39, 1423572481, NULL, 1, '1400', '1400', 7),
(40, 1423572496, NULL, 1, '1600', '1600', 7),
(41, 1423572508, NULL, 1, '420', '420', 7),
(42, 1423572516, NULL, 1, '680', '680', 7),
(43, 1423572531, NULL, 1, 'WH574', 'wh574', 7),
(44, 1423572565, NULL, 1, 'Air Max 90 Hyperfuse', 'air_max_90_hyperfuse', 1),
(45, 1423572618, NULL, 1, 'Air Max 90 Tape Premium', 'air_max_90_tape_premium', 1),
(46, 1423572650, NULL, 1, 'Air Max 90 EM', 'air_max_90_em', 1),
(47, 1423572662, NULL, 1, 'Air Max 90 VT', 'air_max_90_vt', 1),
(48, 1423572678, NULL, 1, 'Air Max 90', 'air_max_90', 1),
(49, 1423572698, NULL, 1, 'Air Max 90 Excellerate', 'air_max_90_excellerate', 1),
(50, 1423572716, NULL, 1, 'Air Max 90 Lunare', 'air_max_90_lunare', 1),
(51, 1423572733, NULL, 1, 'Air Max 90 Sneakerboot', 'air_max_90_sneakerboot', 1),
(52, 1423572754, NULL, 1, 'Air Max 87', 'air_max_87', 1),
(53, 1423572772, NULL, 1, 'Air Max 87 EM', 'air_max_87_em', 1),
(54, 1423572799, NULL, 1, 'Air Max 87 Tape Premium', 'air_max_87_tape_premium', 1),
(55, 1423572818, NULL, 1, 'Air Force', 'air_force', 1),
(56, 1423572836, NULL, 1, 'Pepper Low', 'pepper_low', 1),
(57, 1423572850, NULL, 1, 'Roshe Run', 'roshe_run', 1),
(58, 1423572873, NULL, 1, 'Free Run', 'free_run', 1),
(59, 1423572890, NULL, 1, 'Air Max 2013', 'air_max_2013', 1),
(60, 1423572898, 1423573435, 1, 'Air Max 2014', 'air_max_2014', 1),
(61, 1423572915, NULL, 1, 'Air Max Thea Print', 'air_max_thea_print', 1),
(62, 1423572928, NULL, 1, 'Lunar Elite Sky', 'lunar_elite_sky', 1),
(63, 1423572940, NULL, 1, 'Air Pegasus', 'air_pegasus', 1),
(64, 1423572967, NULL, 1, 'Cortez', 'cortez', 1),
(65, 1423572981, NULL, 1, 'Stefan Janoski Max', 'stefan_janoski_max', 1),
(66, 1423573033, NULL, 1, 'Air Jordan', 'air_jordan', 1),
(67, 1423573042, NULL, 1, 'Air Yezzy', 'air_yezzy', 1),
(68, 1423573053, NULL, 1, 'Lebron', 'lebron', 1),
(69, 1423573063, NULL, 1, 'Kobe', 'kobe', 1),
(70, 1423573079, NULL, 1, 'Air Max 2011', 'air_max_2011', 1),
(71, 1423573101, NULL, 1, 'Air Max Tailwind', 'air_max_tailwind', 1),
(72, 1423573142, 1423573164, 1, 'GL6000', 'gl6000', 8),
(73, 1423573191, 1423573571, 1, 'Classic', 'classic', 8),
(74, 1423573202, NULL, 1, 'ATV19 Plus', 'atv19_plus', 8),
(75, 1423573217, NULL, 1, 'Zigwild TR 2', 'zigwild_tr_2', 8),
(76, 1423573232, NULL, 1, 'Zig-Tech', 'zig-tech', 8),
(77, 1423573244, NULL, 1, '685', '685', 8),
(78, 1423573254, NULL, 1, '683', '683', 8),
(79, 1423573268, NULL, 1, 'Easytone', 'easytone', 8),
(80, 1423573286, NULL, 1, 'Realflex', 'realflex', 8),
(81, 1423573298, NULL, 1, 'Hello Kitty', 'hello_kitty', 8),
(82, 1423573321, NULL, 1, 'Suede', 'suede', 9),
(83, 1423573335, NULL, 1, 'Gility', 'gility', 9),
(84, 1423573345, NULL, 1, 'Cell', 'cell', 9),
(85, 1423573362, NULL, 1, 'Speeder', 'speeder', 9),
(86, 1423573375, NULL, 1, 'Cabana Racer', 'cabana_racer', 9),
(87, 1423573387, NULL, 1, 'Slip on', 'slip_on', 9);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `date` int(10) DEFAULT NULL,
  `text` text,
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `image` varchar(255) DEFAULT NULL,
  `show_image` tinyint(1) NOT NULL DEFAULT '1',
  `views` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `created_at`, `updated_at`, `status`, `name`, `alias`, `date`, `text`, `h1`, `title`, `description`, `keywords`, `image`, `show_image`, `views`) VALUES
(1, 1421599719, 1423471279, 1, 'Новые кроссовки NIKE AIR FORCE 1 LOW PURPLE VENOM', 'novye_krossovki_nike_air_force_1_low_purple_venom', 1421532000, '&lt;p&gt;How does the iconic silhouette of the Nike Air Force 1 stay fresh? Answer: by having various fresh colorways for days. This AF1 pair is dominantly turbo green, with atomic mango accents and a hint of purple venom. To keep its cleanliness, the midsole is clean and white to stay true to it&amp;rsquo;s all-white original counterpart. What are your thoughts on the colorways that can be conjured up with this sneaker? Let&amp;rsquo;s hear it in the comments below.&lt;/p&gt;\r\n&lt;p&gt;Available in stores and online Saturday.&lt;img src=&quot;../../../../files/filemanager/krossovki-adidas-springblade.jpg&quot; alt=&quot;&quot; width=&quot;800&quot; height=&quot;533&quot; /&gt;&lt;/p&gt;', 'NIKE AIR FORCE 1 LOW PURPLE VENOM. H1', 'NIKE AIR FORCE 1 LOW PURPLE VENOM title', 'NIKE AIR FORCE 1 LOW PURPLE VENOM. description', 'NIKE AIR FORCE 1 LOW PURPLE VENOM. keywords ', '45cb12a20b01293b78ada78b4076e369.jpg', 1, 24),
(22, 1423466990, 1424938017, 1, 'test', 'test', 1423432800, '<ul>\r\n<li><a class="title_li" href="../../catalog/for_men">МУЖСКОЕ</a>\r\n<ul>\r\n<li><a href="../../catalog/krossovki_dlja_bega">КРОССОВКИ ДЛЯ БЕГА</a></li>\r\n<li><a href="../../catalog/povsednevnye_krossovki">ПОВСЕДНЕВНЫЕ КРОССОВКИ</a></li>\r\n<li><a href="../../catalog/kedy">КЕДЫ</a></li>\r\n<li><a href="../../catalog/snikersy">СНИКЕРСЫ</a></li>\r\n<li><a href="../../catalog/mokasiny">МОКАСИНЫ</a>\r\n<ul>\r\n<li><a class="title_li" href="../../catalog/for_men">МУЖСКОЕ</a>\r\n<ul>\r\n<li><a href="../../catalog/krossovki_dlja_bega">КРОССОВКИ ДЛЯ БЕГА</a></li>\r\n<li><a href="../../catalog/povsednevnye_krossovki">ПОВСЕДНЕВНЫЕ КРОССОВКИ</a></li>\r\n<li><a href="../../catalog/kedy">КЕДЫ</a></li>\r\n<li><a href="../../catalog/snikersy">СНИКЕРСЫ</a></li>\r\n<li><a href="../../catalog/mokasiny">МОКАСИНЫ</a>\r\n<ul>\r\n<li><a class="title_li" href="../../catalog/for_men">МУЖСКОЕ</a>\r\n<ul>\r\n<li><a href="../../catalog/krossovki_dlja_bega">КРОССОВКИ ДЛЯ БЕГА</a></li>\r\n<li><a href="../../catalog/povsednevnye_krossovki">ПОВСЕДНЕВНЫЕ КРОССОВКИ</a></li>\r\n<li><a href="../../catalog/kedy">КЕДЫ</a></li>\r\n<li><a href="../../catalog/snikersy">СНИКЕРСЫ</a></li>\r\n<li><a href="../../catalog/mokasiny">МОКАСИНЫ</a>\r\n<ul>\r\n<li><a class="title_li" href="../../catalog/for_men">МУЖСКОЕ</a>\r\n<ul>\r\n<li><a href="../../catalog/krossovki_dlja_bega">КРОССОВКИ ДЛЯ БЕГА</a></li>\r\n<li><a href="../../catalog/povsednevnye_krossovki">ПОВСЕДНЕВНЫЕ КРОССОВКИ</a></li>\r\n<li><a href="../../catalog/kedy">КЕДЫ</a></li>\r\n<li><a href="../../catalog/snikersy">СНИКЕРСЫ</a></li>\r\n<li><a href="../../catalog/mokasiny">МОКАСИНЫ</a>\r\n<ul>\r\n<li><a class="title_li" href="../../catalog/for_men">МУЖСКОЕ</a>\r\n<ul>\r\n<li><a href="../../catalog/krossovki_dlja_bega">КРОССОВКИ ДЛЯ БЕГА</a></li>\r\n<li><a href="../../catalog/povsednevnye_krossovki">ПОВСЕДНЕВНЫЕ КРОССОВКИ</a></li>\r\n<li><a href="../../catalog/kedy">КЕДЫ</a></li>\r\n<li><a href="../../catalog/snikersy">СНИКЕРСЫ</a></li>\r\n<li><a href="../../catalog/mokasiny">МОКАСИНЫ</a></li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n</li>\r\n</ul>', '', '', '', '', '4a266677f77c0a401f679f6dacdb35fa.jpg', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `payment` int(2) DEFAULT NULL,
  `delivery` int(2) DEFAULT NULL,
  `number` varchar(128) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `user_id` int(10) DEFAULT '0',
  `ip` varchar(16) DEFAULT NULL,
  `delivery_date` int(10) DEFAULT NULL,
  `delivery_number` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `created_at`, `updated_at`, `status`, `payment`, `delivery`, `number`, `name`, `phone`, `user_id`, `ip`, `delivery_date`, `delivery_number`) VALUES
(1, 1422525317, 1422605741, 1, 1, 2, 'Херсон, №8', 'Демяненко Виталий Андреевич', '+38 (099) 274-03-48', 3, '127.0.0.1', NULL, NULL),
(2, 1422607449, NULL, 0, 1, 2, 'Херсон, отделение №9', 'Вася', '+38 (099) 274-03-48', 0, NULL, NULL, NULL),
(3, 1422948000, NULL, 0, 2, 2, '6', 'Евгений', '+38 (096) 816-17-51', 0, '178.136.229.251', NULL, NULL),
(4, 1422970305, 1422971541, 3, 1, 2, 'ав', 'павп', '+38 (514) 564-65-46', 1, '178.136.229.251', NULL, NULL),
(5, 1422971671, 1422974121, 1, 2, 2, '1', 'ававваав', '+38 (000) 000-00-00', 0, NULL, NULL, NULL),
(6, 1423049871, NULL, 0, 1, 2, '1', '123', '+38 (066) 303-39-34', 0, '178.136.229.251', NULL, NULL),
(7, 1423065489, NULL, 0, 1, 1, '', '123', '+38 (011) 111-11-11', 0, NULL, NULL, NULL),
(8, 1423084202, 1423084613, 0, 1, 1, '', 'папамса', '+38 (322) 323-32-32', 0, '93.79.159.189', NULL, NULL),
(9, 1423085019, 1423085349, 1, 1, 1, '', 'петров', '+38 (343) 434-34-34', 8, '93.79.159.189', NULL, NULL),
(10, 1423129392, NULL, 0, 1, 2, 'савчяа', 'авааы', '+38 (434) 343-43-43', 1, '178.136.229.251', NULL, NULL),
(11, 1423129435, NULL, 0, 1, 2, 'rfew', 'rewewrw', '+38 (323) 231-23-23', 0, '178.136.229.251', NULL, NULL),
(12, 1423129828, NULL, 0, 1, 1, '', '43', '+38 (344) 334-43-43', 0, '178.136.229.251', NULL, NULL),
(13, 1423129952, NULL, 0, 1, 1, '', '4324frdg', '+38 (432) 432-32-32', 0, '178.136.229.251', NULL, NULL),
(14, 1423140463, NULL, 0, 1, 1, '', 'fsdfsdf', '+38 (332) 232-32-32', 9, '178.136.229.251', NULL, NULL),
(15, 1423140568, NULL, 0, 2, 1, '', 'fdsfdsf', '+38 (434) 343-43-43', 9, '178.136.229.251', NULL, NULL),
(16, 1423141359, NULL, 0, 1, 2, 'trtrrt', 'rtgr', '+38 (455) 454-54-54', 9, '178.136.229.251', NULL, NULL),
(17, 1423208154, NULL, 0, 1, 2, 'ывфв', 'авав', '+38 (323) 232-32-32', 1, '178.136.229.251', NULL, NULL),
(18, 1423229352, NULL, 0, 2, 2, '12', 'nnn', '+38 (111) 111-11-11', 0, '178.136.229.251', NULL, NULL),
(19, 1423232751, NULL, 0, 1, 1, '', 'Херсон', '+38 (111) 111-11-11', 1, '178.136.229.251', NULL, NULL),
(20, 1423233771, NULL, 0, 1, 2, '21', 'тест тест', '+38 (111) 111-11-11', 9, '178.136.229.251', NULL, NULL),
(21, 1423234131, NULL, 0, 1, 1, '', 'тест', '+38 (222) 222-22-22', 9, '178.136.229.251', NULL, NULL),
(22, 1423239587, NULL, 0, 1, 1, '', 'имя', '+38 (111) 111-11-11', 0, '178.136.229.251', NULL, NULL),
(23, 1423473487, NULL, 0, 2, 2, '1', 'Имя', '+38 (111) 111-11-11', 0, '178.136.229.251', NULL, NULL),
(24, 1423483079, NULL, 0, 1, 1, '', 'Имя', '+38 (111) 111-11-11', 1, '178.136.229.251', NULL, NULL),
(25, 1424334596, NULL, 0, 1, 1, '', 'dfgdfgdf', '+38 (446) 546-54-64', 3, '127.0.0.1', NULL, NULL),
(26, 1424334627, NULL, 0, 2, 1, '', 'fdgdfgdfgdf', '+38 (453) 453-45-35', 3, '127.0.0.1', NULL, NULL),
(27, 1424334660, NULL, 0, 2, 1, '', 'fdgdfgdfgdf', '+38 (453) 453-45-35', 3, '127.0.0.1', NULL, NULL),
(28, 1424334834, NULL, 1, 2, 2, 'fgh', 'asdsadghgfhfghfghfgh', '+38 (456) 454-65-65', 3, '127.0.0.1', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_items`
--

CREATE TABLE IF NOT EXISTS `orders_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `catalog_id` int(10) DEFAULT NULL,
  `size_id` int(10) NOT NULL DEFAULT '0',
  `cost` int(10) NOT NULL DEFAULT '0',
  `count` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `catalog_id` (`catalog_id`),
  KEY `size_id` (`size_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Дамп данных таблицы `orders_items`
--

INSERT INTO `orders_items` (`id`, `order_id`, `catalog_id`, `size_id`, `cost`, `count`) VALUES
(1, 2, NULL, 4, 1200, 3),
(2, 1, NULL, 0, 400, 3),
(3, 1, NULL, 5, 1200, 2),
(4, 1, NULL, 3, 1200, 1),
(5, 1, NULL, 3, 959, 2),
(6, 3, NULL, 3, 959, 1),
(7, 3, NULL, 0, 400, 4),
(8, 4, NULL, 3, 959, 1),
(9, 4, NULL, 5, 1200, 2),
(11, 6, NULL, 0, 400, 2),
(14, 7, NULL, 4, 959, 1),
(15, 7, NULL, 4, 959, 1),
(16, 8, NULL, 0, 2360, 1),
(17, 9, NULL, 0, 112, 3),
(18, 10, NULL, 4, 959, 1),
(19, 11, NULL, 2, 959, 1),
(20, 11, NULL, 4, 959, 43),
(21, 11, NULL, 0, 12123, 2),
(22, 12, NULL, 5, 100, 1),
(23, 13, NULL, 0, 112, 2),
(24, 14, NULL, 2, 959, 3),
(25, 14, NULL, 4, 959, 3),
(26, 15, NULL, 2, 959, 1),
(27, 15, NULL, 4, 959, 2),
(28, 16, NULL, 0, 12123, 1),
(29, 16, NULL, 0, 1000, 2),
(30, 16, NULL, 0, 2360, 3),
(31, 16, NULL, 5, 100, 1),
(32, 17, NULL, 2, 959, 2),
(33, 17, NULL, 4, 959, 2),
(34, 18, NULL, 0, 112, 1),
(35, 19, NULL, 4, 959, 1),
(36, 19, NULL, 0, 112, 1),
(37, 20, NULL, 0, 400, 2),
(38, 20, NULL, 3, 959, 4),
(39, 20, NULL, 2, 959, 3),
(40, 20, NULL, 4, 959, 1),
(41, 21, NULL, 0, 400, 2),
(42, 22, NULL, 0, 112, 1),
(43, 23, NULL, 0, 112, 1),
(44, 24, NULL, 0, 123, 1),
(45, 24, NULL, 0, 1200, 1),
(46, 24, NULL, 4, 959, 1),
(47, 28, 33, 14, 1029, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `orders_simple`
--

CREATE TABLE IF NOT EXISTS `orders_simple` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `phone` varchar(64) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `catalog_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `orders_simple`
--

INSERT INTO `orders_simple` (`id`, `created_at`, `updated_at`, `status`, `phone`, `ip`, `catalog_id`, `user_id`) VALUES
(1, 1422267455, NULL, 0, '+38 (546) 446-56-46', '127.0.0.1', NULL, NULL),
(2, 1422267526, NULL, 0, '+38 (456) 456-46-45', '127.0.0.1', NULL, NULL),
(3, 1422296593, NULL, 0, '+38 (099) 274-03-48', '127.0.0.1', NULL, NULL),
(4, 1422967172, 1422967353, 1, '+38 (545) 545-55-55', '178.136.229.251', NULL, 1),
(5, 1422974954, NULL, 0, '+38 (111) 111-11-11', '178.136.229.251', NULL, 1),
(6, 1423036703, NULL, 0, '+38 (486) 565-65-65', '178.136.229.251', NULL, 1),
(7, 1423050169, NULL, 0, '+38 (111) 111-11-11', '178.136.229.251', NULL, 1),
(8, 1423059933, NULL, 0, '+38 (111) 111-11-11', '178.136.229.251', NULL, 1),
(9, 1423062636, 1423064620, 1, '+38 (111) 111-11-11', '178.136.229.251', NULL, 1),
(10, 1423085854, NULL, 0, '+38 (256) 656-56-55', '93.79.159.189', NULL, 8),
(11, 1423125454, NULL, 0, '+38 (334) 343-43-43', '178.136.229.251', NULL, 0),
(12, 1423483019, NULL, 0, '+38 (111) 111-11-11', '178.136.229.251', NULL, 1),
(13, 1423663833, 1424949891, 1, '+38 (099) 274-03-48', '127.0.0.1', 31, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `popup_messages`
--

CREATE TABLE IF NOT EXISTS `popup_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `ru` text,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Дамп данных таблицы `popup_messages`
--

INSERT INTO `popup_messages` (`id`, `name`, `ru`, `updated_at`) VALUES
(1, 'message_after_oformleniya_basket', '<p>Благодарим вас за заказ! <br /> Менеджер свяжется с вами в ближайшее время.</p>', 2013),
(2, 'message_error_captcha', 'Вы неправильно ввели код безопасности.<br> Повторите, пожалуйста, отправку данных, внимательно указав код.', NULL),
(3, 'message_after_q_about_good', 'Ваш вопрос принят. Менеджер ответит вам в ближайшее время.', NULL),
(4, 'message_add_contact', 'Благодарим вас за сообщение!', NULL),
(5, 'message_add_comment_to_guestbook', 'Благодарим вас за оставленный отзыв. <br>Администрация сайта обязательно рассмотрит ваши материалы и опубликует их в ближайшее время.', NULL),
(6, 'message_add_comment_to_news', 'Благодарим вас за оставленный комментарий. <br>С вашей помощью наш сайт становится интереснее и полезнее. <br>Администрация сайта обязательно рассмотрит ваши материалы и опубликует их в ближайшее время.', NULL),
(7, 'message_error_login', 'Неправильно введен логин', NULL),
(8, 'message_after_registration', 'Благодарим вас за регистрацию на нашем сайте! Приятной работы!', NULL),
(9, 'message_text_after_registration_user_at_site', 'Благодарим вас за регистрацию на нашем сайте! <br /> На ваш email, указанный при регистрации, отправлено уведомление с данными для входа в ваш личный кабинет на сайте. <br /> Приятной работы!', NULL),
(10, 'message_after_autorisation', 'Данные введены правильно! Приятной работы!', NULL),
(11, 'message_text_after_autorisation_user_at_site', 'Добро пожаловать на наш сайт! <br /> Воспользуйтесь личным кабинетом для: редактирования своих данных и просмотра истории покупок. <br /> Приятной работы!', NULL),
(12, 'message_error_autorisation', 'Данные введены неправильно! Будьте внимательны!', NULL),
(13, 'message_after_exit', 'Возвращайтесь еще!', NULL),
(14, 'message_text_after_exit', 'Администрация сайта благодарит вас за время, потраченное на нашем сайте. До скорых встреч!', NULL),
(16, 'message_after_edit_data', 'Выши данные изменены. Приятной работы.', NULL),
(17, 'message_text_after_edit_data', 'Благодарим вас внимание к нашему сайту. <br /> На ваш email, указанный при регистрации, отправлено уведомление с измененными данными. <br /> Приятной работы!', NULL),
(18, 'subscribe_refuse', 'Вы отказались от рассылки на сайте!', NULL),
(19, 'subscribe_refuse_error', 'Вы не подписывались на рассылку на сайте!', NULL),
(20, 'subscribe_already01', 'уже является подписчиком на сайте!', NULL),
(21, 'subscribe_already02', 'введите другую почту для подписки.', NULL),
(22, 'subscribe_done', 'Вы подписались на рассылку на сайте', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `seo`
--

CREATE TABLE IF NOT EXISTS `seo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `h1` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `title` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `description` text CHARACTER SET cp1251,
  `keywords` text CHARACTER SET cp1251,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `seo`
--

INSERT INTO `seo` (`id`, `name`, `h1`, `title`, `description`, `keywords`, `updated_at`) VALUES
(1, 'Шаблон для групп товаров', '{{name}}', '{{name}} в интернет магазине Airpac. Купить обувь в Украине, Киеве', '{{content:20}}', '{{name}}, Купить обувь в Украине, Киеве', 1424952511),
(2, 'Шаблон для товаров', '{{name}}', '{{name}} в интернет магазине Airpac. Купить лучшую обувь {{group}} в Украине, Киеве', 'Купить {{name}} в Украине. Огромный ассортимент обуви, современные {{group}} от компании {{brand}}. Гарантия качества, своевременная доставка, любые способы оплаты', 'Купить, {{name}}, {{group}}, {{brand}}, обувь', 1422817102);

-- --------------------------------------------------------

--
-- Структура таблицы `seo_counters`
--

CREATE TABLE IF NOT EXISTS `seo_counters` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `script` text,
  `status` int(1) DEFAULT '0',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `seo_counters`
--

INSERT INTO `seo_counters` (`id`, `name`, `script`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Liveinternet', '&lt;!--LiveInternet counter--&gt;&lt;script type=&quot;text/javascript&quot;&gt;&lt;!--\r\ndocument.write(&quot;&lt;a href=''//www.liveinternet.ru/click'' &quot;+\r\n&quot;target=_blank&gt;&lt;img src=''//counter.yadro.ru/hit?t21.6;r&quot;+\r\nescape(document.referrer)+((typeof(screen)==&quot;undefined&quot;)?&quot;&quot;:\r\n&quot;;s&quot;+screen.width+&quot;*&quot;+screen.height+&quot;*&quot;+(screen.colorDepth?\r\nscreen.colorDepth:screen.pixelDepth))+&quot;;u&quot;+escape(document.URL)+\r\n&quot;;&quot;+Math.random()+\r\n&quot;'' alt='''' title=''LiveInternet: показано число просмотров за 24&quot;+\r\n&quot; часа, посетителей за 24 часа и за сегодня'' &quot;+\r\n&quot;border=''0'' width=''88'' height=''31''&gt;&lt;\\/a&gt;&quot;)\r\n//--&gt;&lt;/script&gt;&lt;!--/LiveInternet--&gt;\r\n', 0, 1404925196, 1421775732);

-- --------------------------------------------------------

--
-- Структура таблицы `seo_links`
--

CREATE TABLE IF NOT EXISTS `seo_links` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `text` text,
  PRIMARY KEY (`id`),
  KEY `link` (`link`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `seo_links`
--

INSERT INTO `seo_links` (`id`, `name`, `status`, `created_at`, `updated_at`, `link`, `h1`, `title`, `description`, `keywords`, `text`) VALUES
(3, 'Главная страница', 1, 1413746597, 1425018715, '/backend/seo/links/edit/3', 'Этим можно перебить стандартные мета теги H1', 'Этим можно перебить стандартные мета теги', 'Этим можно перебить стандартные мета теги Description', 'Этим можно перебить стандартные мета теги Keywords', '<p>Выбирайте бренд Kludi! Лучшие смесители для Вас!</p>'),
(6, 'Страница списка новостей', 1, 1421952502, 1425018689, '/news', 'Новости', 'Новости', 'Новости', 'Новости', ''),
(7, 'Новинки', 0, 1422295097, 1425018679, '/new', 'Новинки', 'Новинки title', 'Новинки', 'Новинки', '<p>Новинки</p>');

-- --------------------------------------------------------

--
-- Структура таблицы `seo_metrika`
--

CREATE TABLE IF NOT EXISTS `seo_metrika` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `script` text,
  `status` int(1) DEFAULT '0',
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `place` varchar(31) DEFAULT 'head',
  PRIMARY KEY (`id`),
  KEY `place` (`place`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `seo_metrika`
--

INSERT INTO `seo_metrika` (`id`, `name`, `script`, `status`, `created_at`, `updated_at`, `place`) VALUES
(1, 'Yandex метрика', '<!-- Code YandexMetrika -->', 1, 1404924906, 1424953313, 'body'),
(2, 'Google', '<!-- Code GoogleAnalytics -->', 1, 1412689169, 1419515708, 'head');

-- --------------------------------------------------------

--
-- Структура таблицы `sitemenu`
--

CREATE TABLE IF NOT EXISTS `sitemenu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(10) NOT NULL DEFAULT '0',
  `name` varchar(64) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `sitemenu`
--

INSERT INTO `sitemenu` (`id`, `created_at`, `updated_at`, `status`, `sort`, `name`, `url`) VALUES
(1, 1421597167, 1423469623, 1, 0, 'О нас', 'o_nas'),
(2, 1421597182, 1423469623, 1, 1, 'Новости', 'news'),
(3, 1421597188, 1423469623, 1, 2, 'Статьи', 'articles'),
(4, 1421597204, 1423469623, 1, 3, 'FAQ', 'faq'),
(5, 1421597213, 1424073277, 1, 4, 'Контакты', 'contact');

-- --------------------------------------------------------

--
-- Структура таблицы `sizes`
--

CREATE TABLE IF NOT EXISTS `sizes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `sizes`
--

INSERT INTO `sizes` (`id`, `created_at`, `updated_at`, `name`, `alias`, `status`) VALUES
(7, 1423495004, NULL, '45 EUR', '45_eur', 1),
(10, 1423495051, 1423495118, '39 EUR', '39_eur', 1),
(11, 1423495065, 1423495119, '40 EUR', '40_eur', 1),
(12, 1423495074, 1423495119, '41 EUR', '41_eur', 1),
(13, 1423495083, 1423499136, '42 EUR', '42_eur', 1),
(14, 1423495090, 1423499136, '43 EUR', '43_eur', 1),
(15, 1423495099, 1423499139, '44 EUR', '44_eur', 1),
(16, 1423495107, 1423499138, '46 EUR', '46_eur', 1),
(17, 1423573648, NULL, '47 EUR', '47_eur', 1),
(18, 1423573671, NULL, 'S', 's', 1),
(19, 1423573676, NULL, 'M', 'm', 1),
(20, 1423573683, NULL, 'L', 'l', 1),
(21, 1423573688, 1423573726, 'XL', 'xl', 1),
(23, 1423573700, NULL, '3XL', '3xl', 1),
(24, 1423573706, 1423573722, '4XL', '4xl', 1),
(25, 1423573712, NULL, '5XL', '5xl', 1),
(26, 1424947893, NULL, '45433334343', '45433334343', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `slider`
--

CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `slider`
--

INSERT INTO `slider` (`id`, `created_at`, `updated_at`, `status`, `sort`, `name`, `url`, `description`, `image`) VALUES
(2, 1421652465, 1423210985, 1, 0, 'NIKE SNEAKERBOOTS NIKE', '', 'NIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTSNIKE SNEAKERBOOTS', '8c7a5c5dc9aed85162c6b8eb9f232f55.jpg'),
(3, 1421686591, 1423051197, 1, 1, 'SNEAKERS', '', '', '2331500bc4f24c7ebf514b433c96d6ff.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `specifications`
--

CREATE TABLE IF NOT EXISTS `specifications` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `type_id` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `specifications`
--

INSERT INTO `specifications` (`id`, `created_at`, `updated_at`, `status`, `name`, `alias`, `type_id`) VALUES
(1, 1422695395, NULL, 1, 'Цвет', 'color', 1),
(2, 1422695522, NULL, 1, 'Материал', 'material', 3),
(4, 1422695571, NULL, 1, 'Производство', 'country', 2),
(9, 1423497060, NULL, 1, 'Сезон', 'sezon', 3),
(10, 1424948026, NULL, 1, 'fgfgfghffff', 'fgfgfghffff', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `specifications_types`
--

CREATE TABLE IF NOT EXISTS `specifications_types` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `specifications_types`
--

INSERT INTO `specifications_types` (`id`, `name`) VALUES
(1, 'Цвет'),
(2, 'Обычная'),
(3, 'Мультивыбор');

-- --------------------------------------------------------

--
-- Структура таблицы `specifications_values`
--

CREATE TABLE IF NOT EXISTS `specifications_values` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) NOT NULL,
  `specification_id` int(10) NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `specification_id` (`specification_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `specifications_values`
--

INSERT INTO `specifications_values` (`id`, `created_at`, `updated_at`, `status`, `name`, `alias`, `specification_id`, `color`) VALUES
(2, 1422729570, NULL, 1, 'Кожа', 'skin', 2, NULL),
(18, 1422731866, NULL, 1, 'Китай', 'china', 4, NULL),
(20, 1422731882, NULL, 1, 'Украина', 'ukraine', 4, NULL),
(25, 1422734316, 1423497535, 1, 'Белый', 'white', 1, '#ffffff'),
(27, 1422734363, 1423497534, 1, 'Синий', 'blue', 1, '#4000ff'),
(28, 1422734426, 1423499918, 1, 'Коричневый ', 'gray', 1, '#4d3939'),
(29, 1422861689, NULL, 1, 'Полиестр', 'poliestr', 2, NULL),
(32, 1423496980, NULL, 1, 'Синтетика', 'sintetik', 2, NULL),
(33, 1423497107, NULL, 1, 'Зима', 'winter', 9, NULL),
(34, 1423497153, NULL, 1, 'Весна', 'spring', 9, NULL),
(35, 1423497194, NULL, 1, 'Лето', 'summer', 9, NULL),
(36, 1423497213, NULL, 1, 'Осень', 'autumn', 9, NULL),
(37, 1423497556, NULL, 1, 'Зеленый', 'green', 1, '#035c12'),
(38, 1423497582, NULL, 1, 'Красный', 'red', 1, '#f20534'),
(39, 1423497679, 1423497748, 1, 'Темно-синий', 'dark_blue', 1, '#061759'),
(40, 1423497800, NULL, 1, 'Голубой', 'light_blue', 1, '#00bfff'),
(41, 1423497848, NULL, 1, 'Бирюзовый', 'turquoise', 1, '#03edca'),
(42, 1423499820, NULL, 1, 'Бежевый', 'beige', 1, '#c29032'),
(43, 1423499896, NULL, 1, 'Бордовый', 'claret', 1, '#7a0b21'),
(44, 1423573875, NULL, 1, 'Замша', 'Zamsha', 2, NULL),
(45, 1423573926, NULL, 1, 'Искусственная замша', 'iskustvennayazamsha', 2, NULL),
(46, 1423573947, NULL, 1, 'Искусственная кожа', 'iskustvennayakozha', 2, NULL),
(47, 1423573961, NULL, 1, 'Нейлон', 'nylon', 2, NULL),
(48, 1423573992, NULL, 1, 'Текстиль', 'tekstil', 2, NULL),
(49, 1423574025, NULL, 1, 'Хлопок ', 'Hlopok', 2, NULL),
(51, NULL, NULL, 1, 'dfgdfg', 'dfg', 10, NULL),
(52, 1424948152, NULL, 1, 'dfgd', '65', 10, NULL),
(54, NULL, NULL, 1, 'dsfsdfsdf', 'sdfsdfsdf', 1, '#c93232');

-- --------------------------------------------------------

--
-- Структура таблицы `subscribers`
--

CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `hash` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `subscribers`
--

INSERT INTO `subscribers` (`id`, `created_at`, `updated_at`, `email`, `ip`, `status`, `hash`) VALUES
(1, 1422974161, NULL, 'gupeyixok@flurred.com', '178.136.229.251', 1, '2aa7c76b58e86020036d2451d04aa0b9f4bd3dde'),
(2, 1423037499, NULL, 'zolota_ira@mail.ru', '178.136.229.251', 1, '3c2669e5a7e530cf78585dd28a98ce80e8611799'),
(3, 1423043808, NULL, 'palenaya.v.wezom@gmail.com', '178.136.229.251', 1, '17d818e355bc39e0d022aa76e7ed07f54602d9ad'),
(5, 1423084954, NULL, 'nokeyeve@flurred.com', '93.79.159.189', 1, '8a6769318c2deebd0fcb9cc664db16afa328a514'),
(6, 1423664650, 1423664668, 'alyohina.i.wezom@gmail.com', '127.0.0.1', 0, '5484d3ac6c180bd4d84b548b7f25a96c0e988c6d'),
(7, 1424248369, NULL, 'demyanenko.v.wezom@gmail.com', '127.0.0.1', 1, '304003ffedc5899e1d16cbe5cfcd4eab844dd8ff'),
(8, 1424248645, NULL, 'demyanenko.v.wezgom@gmail.com', '127.0.0.1', 1, 'e30e0d5e8465bdcbc9e19e1b142873e48105ef63'),
(9, 1424249058, NULL, 'alyohina.i.wezo4m@gmail.com', '127.0.0.1', 1, '09fac4dd7240aa34473193a5f0d95e7ecf708d43'),
(10, 1424334784, NULL, 'demyanenko.v.wezom@gmailf.com', '127.0.0.1', 1, '4c2c15a998205e728f33dca938bce607d9502fec'),
(11, 1424334792, NULL, 'admin@ds.sds', '127.0.0.1', 0, 'af9157aa20e2397207146cbc52317f5738a0ae8a');

-- --------------------------------------------------------

--
-- Структура таблицы `subscribe_mails`
--

CREATE TABLE IF NOT EXISTS `subscribe_mails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `text` mediumtext,
  `emails` longtext,
  `count_emails` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `subscribe_mails`
--

INSERT INTO `subscribe_mails` (`id`, `created_at`, `updated_at`, `subject`, `text`, `emails`, `count_emails`) VALUES
(1, 1423067023, NULL, 'Письмо', '&lt;p&gt;12356&lt;/p&gt;', 'gupeyixok@flurred.com;zolota_ira@mail.ru;palenaya.v.wezom@gmail.com', 3),
(2, 1423086761, NULL, 'Новинка', '&lt;p&gt;Мужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более&lt;/p&gt;\r\n&lt;p&gt;&lt;img src=&quot;../../files/filemanager/Krossovki_Adidas_GORE-TEX_Originals_03_b10673.jpg&quot; alt=&quot;&quot; width=&quot;800&quot; height=&quot;531&quot; /&gt; гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унцийМужская ASICS &amp;reg; GEL-STRATUS &amp;reg; 2.1 :&amp;nbsp;легкая модель &amp;nbsp;с исключительной амортизацию и гибкость.&amp;nbsp;Два улучшение вы заметите в мужском ASICS &amp;reg; GEL-Stratus &amp;reg; 2.1: новая развязка пятки для лучшего поглощения ударов и даже-более гибкая верхняя!&amp;nbsp;11.3 унций&lt;/p&gt;', 'gupeyixok@flurred.com;zolota_ira@mail.ru;palenaya.v.wezom@gmail.com;nokeyeve@flurred.com', 4),
(3, NULL, NULL, 'dfgdfgdfgdfgfddf', '<p>dfgdf gdf gdf gdf gdf</p>', 'gupeyixok@flurred.com;zolota_ira@mail.ru;palenaya.v.wezom@gmail.com;nokeyeve@flurred.com;demyanenko.v.wezom@gmail.com;demyanenko.v.wezgom@gmail.com;alyohina.i.wezo4m@gmail.com;demyanenko.v.wezom@gmailf.com', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `login` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `password` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `created_at` int(10) DEFAULT NULL,
  `updated_at` int(10) DEFAULT NULL,
  `hash` varchar(250) CHARACTER SET cp1251 DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` int(2) NOT NULL DEFAULT '1',
  `ip` varchar(16) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `last_login` int(10) DEFAULT NULL,
  `logins` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `hash` (`hash`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `password`, `created_at`, `updated_at`, `hash`, `email`, `status`, `role_id`, `ip`, `phone`, `last_login`, `logins`) VALUES
(1, 'Администратор', 'admin', 'c2bcb46de4d99a0ce346ee0a6530b85bb6f0fb80656406a23d3e60c1158a22e8', 1418300546, 1423472600, '48e00180ccc77b94d73ec413b015a4cfb9aa58ba10d8c1b63aad5c8317847d9f', 'palenaya.v.wezom111@gmail.com', 1, 2, NULL, '+38 (111) 111-11-11', NULL, 0),
(2, 'weZom', 'wezom', '4958070fab7cebd8b1000c6c8cb1bca4aa23b509ee9bc4b70570b5c0e3dfe64a', NULL, 1419838641, 'c2bcb46de4d99a0ce346ee0a6530b85bb6f0fb80656406a23d3e60c1158a22e8', '', 1, 2, NULL, NULL, NULL, 0),
(3, 'Демяненко Виталий Андреевич', NULL, '52f3c00b62ec3f50526bc7968597f1ab49bd2b73a2d7d8ac20e446a50f9bde3f', 1422043498, 1424674083, '47addb85d6080d63728b40383e83385bc868d3008580d5a913295288941b02c4', 'demyanenko.v.wezom@gmail.com', 1, 1, '127.0.0.1', '+38 (099) 274-03-48', 1424674083, 4),
(8, 'Петров Петр Петрович', NULL, '141a8dd2257eed1526665fd0cfaec9cd219790eee3de2dd3c74d01393388b723', 1423084696, 1423085945, '66e4741bb6679d17b08f4f8d166d3d69579e682c998899b66378b65b8f4c919d', 'nokeyeve@flurred.com', 1, 1, '93.79.159.189', '+38 (323) 232-32-32', NULL, 0),
(9, 'fgdg', NULL, 'd7957677dc4ec0e9e425c8b99e477aa70244ae512fc2583af6a1ab2ee3b01fbc', 1423131702, 1423473897, 'fa4eb48cead29aa32e4aab3d16c6bb6db28782e3082190932ce4094f59f72c7b', 'zoto@flurred.com', 0, 1, '178.136.229.251', '+38 (344) 343-67-65', 1423234086, 10),
(10, NULL, NULL, '6ea4bdebd0555269de2c62670f6a402470133814398693162050a6bea45a27ba', 1423148616, NULL, '373e597e3c060199b9f6497595b6b3f33a67d33047ef8d6c7f871dcfed49620b', 'palenaya.v.wezom@gmail.com', 0, 1, '178.136.229.251', NULL, NULL, 0),
(11, NULL, NULL, '99bbed544406cf05b82dd1e9f2affaebaf189fca8c2cfef0ee3ffa2f5250611f', 1423152161, 1423152230, 'a0ec2fb1c39419e91243f6e76f03cb8bf2d75a557a5028eeaa0e07333e999fc6', 'T1ne1geR@mail.ru', 1, 1, '178.136.229.251', NULL, 1423152230, 1),
(12, NULL, NULL, '6ea4bdebd0555269de2c62670f6a402470133814398693162050a6bea45a27ba', 1423229294, NULL, '6f35f78fa1243b16e90bd98f4b43319444401313413e037e6c07705853c699b3', 'palenaya.v.wezom@gmail.com111111111', 0, 1, '178.136.229.251', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users_roles`
--

CREATE TABLE IF NOT EXISTS `users_roles` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users_roles`
--

INSERT INTO `users_roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Client of the site'),
(2, 'admin', 'Admin of the site');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `carts_items`
--
ALTER TABLE `carts_items`
  ADD CONSTRAINT `carts_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carts_items_ibfk_2` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog`
--
ALTER TABLE `catalog`
  ADD CONSTRAINT `catalog_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `catalog_tree` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `catalog_comments`
--
ALTER TABLE `catalog_comments`
  ADD CONSTRAINT `catalog_comments_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog_images`
--
ALTER TABLE `catalog_images`
  ADD CONSTRAINT `catalog_images_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog_questions`
--
ALTER TABLE `catalog_questions`
  ADD CONSTRAINT `catalog_questions_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog_sizes`
--
ALTER TABLE `catalog_sizes`
  ADD CONSTRAINT `catalog_sizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalog_sizes_ibfk_3` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog_specifications_values`
--
ALTER TABLE `catalog_specifications_values`
  ADD CONSTRAINT `catalog_specifications_values_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalog_specifications_values_ibfk_2` FOREIGN KEY (`specification_value_id`) REFERENCES `specifications_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalog_specifications_values_ibfk_3` FOREIGN KEY (`specification_id`) REFERENCES `specifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog_tree_brands`
--
ALTER TABLE `catalog_tree_brands`
  ADD CONSTRAINT `catalog_tree_brands_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalog_tree_brands_ibfk_2` FOREIGN KEY (`catalog_tree_id`) REFERENCES `catalog_tree` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog_tree_sizes`
--
ALTER TABLE `catalog_tree_sizes`
  ADD CONSTRAINT `catalog_tree_sizes_ibfk_1` FOREIGN KEY (`catalog_tree_id`) REFERENCES `catalog_tree` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalog_tree_sizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `catalog_tree_specifications`
--
ALTER TABLE `catalog_tree_specifications`
  ADD CONSTRAINT `catalog_tree_specifications_ibfk_1` FOREIGN KEY (`catalog_tree_id`) REFERENCES `catalog_tree` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `catalog_tree_specifications_ibfk_2` FOREIGN KEY (`specification_id`) REFERENCES `specifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders_items`
--
ALTER TABLE `orders_items`
  ADD CONSTRAINT `orders_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_items_ibfk_2` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders_simple`
--
ALTER TABLE `orders_simple`
  ADD CONSTRAINT `orders_simple_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `specifications`
--
ALTER TABLE `specifications`
  ADD CONSTRAINT `specifications_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `specifications_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `specifications_values`
--
ALTER TABLE `specifications_values`
  ADD CONSTRAINT `specifications_values_ibfk_1` FOREIGN KEY (`specification_id`) REFERENCES `specifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `users_roles` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
