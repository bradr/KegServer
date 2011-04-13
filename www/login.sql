-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
-- 
-- Host: localhost:3306
-- Generation Time: Jul 07, 2009 at 01:48 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6
-- 
-- Database: `jigowatt_login`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `login_levels`
-- 

CREATE TABLE `login_levels` (
  `id` int(11) NOT NULL auto_increment,
  `level_name` varchar(255) NOT NULL,
  `level_level` int(1) NOT NULL,
  `level_disabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `login_levels`
-- 

INSERT INTO `login_levels` (`id`, `level_name`, `level_level`, `level_disabled`) VALUES 
(1, 'Admin', 1, 0),
(2, 'Special', 2, 0),
(3, 'User', 3, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `login_users`
-- 

CREATE TABLE `login_users` (
  `user_id` int(8) NOT NULL auto_increment,
  `user_level` int(1) NOT NULL default '2',
  `restricted` int(1) NOT NULL default '0',
  `username` varchar(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `login_users`
-- 

INSERT INTO `login_users` (`user_id`, `user_level`, `restricted`, `username`, `fname`, `lname`, `email`, `password`, `timestamp`) VALUES 
(1, 1, 0, 'admin', 'Test', 'Admin', 'test.admin@themeforest.net', '21232f297a57a5a743894a0e4a801fc3', '0000-00-00 00:00:00');