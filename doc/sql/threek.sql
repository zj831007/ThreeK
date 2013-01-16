-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 01 月 16 日 13:24
-- 服务器版本: 5.1.44
-- PHP 版本: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `threek`
--

-- --------------------------------------------------------

--
-- 表的结构 `goods00`
--

CREATE TABLE IF NOT EXISTS `goods00` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods00`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods01`
--

CREATE TABLE IF NOT EXISTS `goods01` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods01`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods02`
--

CREATE TABLE IF NOT EXISTS `goods02` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods02`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods03`
--

CREATE TABLE IF NOT EXISTS `goods03` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods03`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods04`
--

CREATE TABLE IF NOT EXISTS `goods04` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods04`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods05`
--

CREATE TABLE IF NOT EXISTS `goods05` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods05`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods06`
--

CREATE TABLE IF NOT EXISTS `goods06` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods06`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods07`
--

CREATE TABLE IF NOT EXISTS `goods07` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods07`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods08`
--

CREATE TABLE IF NOT EXISTS `goods08` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods08`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods09`
--

CREATE TABLE IF NOT EXISTS `goods09` (
  `goodsid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `userid` int(11) NOT NULL,
  `price` varchar(20) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifytime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lon` varchar(20) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `status` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `goods09`
--


-- --------------------------------------------------------

--
-- 表的结构 `goods_pic`
--

CREATE TABLE IF NOT EXISTS `goods_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `smallpic` varchar(50) NOT NULL,
  `bigpic` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `goods_pic`
--


-- --------------------------------------------------------

--
-- 表的结构 `user00`
--

CREATE TABLE IF NOT EXISTS `user00` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user00`
--


-- --------------------------------------------------------

--
-- 表的结构 `user01`
--

CREATE TABLE IF NOT EXISTS `user01` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user01`
--


-- --------------------------------------------------------

--
-- 表的结构 `user02`
--

CREATE TABLE IF NOT EXISTS `user02` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user02`
--


-- --------------------------------------------------------

--
-- 表的结构 `user03`
--

CREATE TABLE IF NOT EXISTS `user03` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user03`
--


-- --------------------------------------------------------

--
-- 表的结构 `user04`
--

CREATE TABLE IF NOT EXISTS `user04` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user04`
--


-- --------------------------------------------------------

--
-- 表的结构 `user05`
--

CREATE TABLE IF NOT EXISTS `user05` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user05`
--


-- --------------------------------------------------------

--
-- 表的结构 `user06`
--

CREATE TABLE IF NOT EXISTS `user06` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user06`
--


-- --------------------------------------------------------

--
-- 表的结构 `user07`
--

CREATE TABLE IF NOT EXISTS `user07` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user07`
--


-- --------------------------------------------------------

--
-- 表的结构 `user08`
--

CREATE TABLE IF NOT EXISTS `user08` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user08`
--


-- --------------------------------------------------------

--
-- 表的结构 `user09`
--

CREATE TABLE IF NOT EXISTS `user09` (
  `userid` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `gender` tinyint(3) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) NOT NULL,
  `mark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user09`
--


-- --------------------------------------------------------

--
-- 表的结构 `uuid`
--

CREATE TABLE IF NOT EXISTS `uuid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `uuid`
--

INSERT INTO `uuid` (`id`, `username`) VALUES
(1, '1'),
(10, '2'),
(11, '3');
