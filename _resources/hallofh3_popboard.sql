-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 07, 2013 at 12:25 PM
-- Server version: 5.5.30
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hallofh3_popboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usrname` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_post` text CHARACTER SET utf8,
  `comment_date` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `usrname`, `post_id`, `user_id`, `comment_post`, `comment_date`) VALUES
(1, 'Alfonse', 1, 1, 'That''s awesome!', 'May 30, 2012'),
(2, 'guest', 2, 2, 'nyan cat is awesome!', 'May 31, 2012'),
(3, 'guest', 1, 2, 'awesome!', 'May 31, 2012'),
(4, 'Kosta', 2, 5, 'WTH is that?', 'May 31, 2012'),
(5, 'guest', 4, 2, 'Who is 123?', 'May 31, 2012'),
(6, 'alfonse', 7, 1, 'Thanks bro! Definitely want to add more features to it later on.', 'May 31, 2012'),
(7, 'Kosta', 5, 5, 'ian is cool', 'May 31, 2012'),
(8, 'iamalfonse', 8, 23, 'It sure does', 'Jun 05, 2012'),
(9, 'iamalfonse', 2, 23, 'It''s a nyan cat!', 'Jun 13, 2012'),
(10, 'iamalfonse', 9, 23, 'Super sexy!', 'Jun 14, 2012'),
(11, 'iamalfonse', 1, 23, 'Finally :)', 'Jun 14, 2012'),
(12, 'MaxKelly', 6, 26, 'What is this?', 'Jun 14, 2012'),
(13, 'alfonse', 6, 1, 'Cool story bro!', 'Oct 01, 2012'),
(14, 'alfonse', 13, 1, 'bhu', 'Dec 13, 2012'),
(15, 'test', 12, 31, 'Awesome!', 'Jan 22, 2013');

-- --------------------------------------------------------

--
-- Table structure for table `confirm`
--

DROP TABLE IF EXISTS `confirm`;
CREATE TABLE IF NOT EXISTS `confirm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(128) NOT NULL DEFAULT '',
  `key` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `confirm`
--

INSERT INTO `confirm` (`id`, `userid`, `key`, `email`) VALUES
(46, '', '2e72c8163b25856338a365f051348ef1', 'a@hotmail.com'),
(47, '', '54cbce7670f7c1ea5b23021e5293c7ec', 'hello@me.com');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(158, 1, 9),
(140, 1, 10),
(3, 2, 1),
(142, 1, 8),
(156, 26, 10),
(154, 23, 9),
(155, 23, 6),
(130, 23, 2),
(136, 23, 7),
(160, 1, 2),
(99, 1, 0),
(81, 25, 10),
(152, 23, 10),
(157, 26, 8),
(161, 23, 12),
(162, 1, 12),
(163, 1, 7),
(171, 31, 3),
(172, 31, 8),
(173, 31, 13),
(174, 31, 10),
(175, 31, 9),
(176, 31, 2),
(177, 31, 1);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `blog_title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `blog_message` mediumtext CHARACTER SET utf8,
  `post_date` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `user_id`, `blog_title`, `blog_message`, `post_date`, `views`) VALUES
(1, 1, 'Popboard Is Now Live!', '<p><strong>Popboard is now live!</strong> Thanks for signing up!</p>\r\n<p><img src="http://hallofhavoc.com/images/popboard.jpg" alt="Popboard is now Live!" width="740" height="393" /></p>', 'May 30, 2012', 6),
(2, 2, 'Nyan Cat', '<p>cool story bro!</p>\r\n<p><img src="http://laughingsquid.com/wp-content/uploads/nyan.jpg" alt="nyan cat" width="431" height="282" /></p>', 'May 31, 2012', 7),
(3, 5, 'DIS IS YOU BOY!', '<p>Kosta Was Here</p>', 'May 31, 2012', 1),
(4, 4, 'Hellloooooo World', '<p>whats up?</p>', 'May 31, 2012', NULL),
(5, 6, 'What up. ', '<p>Hey everybody!</p>', 'May 31, 2012', NULL),
(6, 3, 'My first post... ', '<p><img src="http://d24w6bsrhbeh9d.cloudfront.net/photo/4333728_460s_v1.jpg" alt="" width="460" height="490" /></p>', 'May 31, 2012', 6),
(7, 3, 'Popboard is the shit', '<p>really dude. it''s looking good.</p>', 'May 31, 2012', 5),
(8, 23, 'Whoa! Awesomesauce. ', '<p>Testing this on an iPad.. Seems to work.</p>', 'Jun 03, 2012', NULL),
(9, 1, 'Pure sexy!', '<p><img src="http://image.modified.com/f/25518102/modp_0908_03_o+hks_cz200s_mitsubishi_lancer_evo_x+front_view.jpg" alt="" width="838" height="628" /></p>', 'Jun 06, 2012', 18),
(10, 1, 'Bboys represent', '<p><img src="http://1.bp.blogspot.com/-0NqhKOsKiEU/TnQniUc4g7I/AAAAAAAAALc/QdUthJUvDkM/s1600/283270_255461581149671_236417166387446_902126_2836040_n.jpg" alt="" width="720" height="484" /></p>', 'Jun 08, 2012', 15),
(12, 26, 'The Last Day of School!', '<p><em>Finally here, ready for summer break! I finally can get away from Oscar!<br /></em></p>', 'Jun 14, 2012', 8),
(13, 1, 'gyiubghukb', '<p>hyilhih</p>', 'Dec 13, 2012', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `email` varchar(70) CHARACTER SET utf8 NOT NULL,
  `passwd` char(40) CHARACTER SET utf8 NOT NULL,
  `displayname` varchar(30) CHARACTER SET utf8 NOT NULL,
  `gravatar` tinyint(4) DEFAULT NULL,
  `active` binary(1) NOT NULL DEFAULT '0',
  `joindate` date DEFAULT NULL,
  `firstname` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `bio` text CHARACTER SET utf8,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `passwd`, `displayname`, `gravatar`, `active`, `joindate`, `firstname`, `lastname`, `bio`) VALUES
(1, 'alfonse', 'bboyhavoc7@yahoo.com', 'ca058b136319a9664d38737989dde13ce5563f9d', 'Alfonse', 1, '1', '2012-05-30', 'Alfonse', 'Surigao', 'Web UI/UX designer located in San Francisco, Ca'),
(2, 'guest', 'guest@guest.com', '35675e68f4b5af7b995d9205ad0fc43842f16450', 'guest', 1, '1', '2012-05-31', NULL, NULL, NULL),
(3, 'roman', 'roman@romandiaz.me', '238a1843d81dd7fbe80b5c1b99515c4ba8c94d0d', 'Roman', 1, '1', '2012-05-31', NULL, NULL, NULL),
(4, '123', 'hello', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123', 0, '1', '2012-05-31', NULL, NULL, NULL),
(5, 'kosta', 'konstandinosgoumenidis@gmail.com', 'e0bc2557be8fc085e6699d6585e6991cbea5e6ca', 'Kosta', 1, '1', '2012-05-31', NULL, NULL, NULL),
(6, 'iansayre', 'isayre020888@gmail.com', '4ed1c2b242d618bc70b56fea528b558e31334937', 'iansayre', NULL, '1', '2012-05-31', NULL, NULL, NULL),
(23, 'iamalfonse', 'alfonse.surigao@yahoo.com', '7598f9ec7694a3f477f39f5e8daeaa23c3bbb471', 'iamalfonse', 1, '1', '2012-05-31', 'Alfonse', 'Surigao', 'Web UI/UX Designer located in San Francisco bay area.'),
(25, 'havoc', 'me@alfonsewebdesign.com', 'ca058b136319a9664d38737989dde13ce5563f9d', 'Havoc', NULL, '1', '2012-06-07', NULL, NULL, NULL),
(26, 'maxkelly', 'makelly@aii.edu', '89e495e7941cf9e40e6980d14a16bf023ccd4c91', 'MaxKelly', NULL, '1', '2012-06-14', 'Max', 'Kelly', 'too old'),
(27, 'rookhaven', 'rookhaven@gmail.com', '312c7d157f8cf74a481e9047518d8ba83d186923', 'rookhaven', NULL, '1', '2012-08-08', NULL, NULL, NULL),
(28, 'errolv', 'errolveloso@gmail.com', 'beed00fb58c9adde49e61ac474cbc33643e8b958', 'errolv', NULL, '1', '2012-08-30', NULL, NULL, NULL),
(29, 'hello', 'a@hotmail.com', 'aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d', 'Hello', NULL, '0', '2012-09-13', NULL, NULL, NULL),
(30, 'try', 'hello@me.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'try', NULL, '0', '2012-10-09', NULL, NULL, NULL),
(31, 'test', 'testuser@popboard.hallofhavoc.com', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test', NULL, '1', '2012-10-11', '', '', ''),
(32, 'popboard', 'popboard.user@gmail.com', '4292c45f46982a794a4e59b0f68604938f418316', 'Popboard', NULL, '1', '2013-01-23', 'Popboard', 'User', 'Hi, I am a Popboard user.');

-- --------------------------------------------------------

--
-- Table structure for table `user_files`
--

DROP TABLE IF EXISTS `user_files`;
CREATE TABLE IF NOT EXISTS `user_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `filename` varchar(40) CHARACTER SET utf8 NOT NULL,
  `dt_added` datetime DEFAULT NULL,
  `mime_type` varchar(20) CHARACTER SET utf8 NOT NULL,
  `file_content` blob,
  `file_size` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
