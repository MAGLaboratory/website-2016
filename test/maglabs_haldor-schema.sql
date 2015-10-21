-- phpMyAdmin SQL Dump
-- version 4.4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 20, 2015 at 10:35 PM
-- Server version: 5.5.44-0+deb8u1
-- PHP Version: 5.6.13-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `maglabs_haldor`
--

-- --------------------------------------------------------

--
-- Table structure for table `haldor`
--

DROP TABLE IF EXISTS `haldor`;
CREATE TABLE IF NOT EXISTS `haldor` (
  `id` int(10) unsigned NOT NULL,
  `sensor` enum('Front_Door','Main_Door','Office_Motion','Shop_Motion','Open_Switch','Boot','Space_Invader','Temperature','Halley') DEFAULT NULL,
  `start_at` timestamp NULL DEFAULT NULL,
  `progress_at` timestamp NULL DEFAULT NULL,
  `progress_count` int(11) NOT NULL DEFAULT '0',
  `end_at` timestamp NULL DEFAULT NULL,
  `mark_at` timestamp NULL DEFAULT NULL COMMENT 'this is for when the system had to manually mark an end (eg, when another session started after too long has passed)',
  `last_value` varchar(2560) NOT NULL DEFAULT '',
  `session` varchar(50) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=ascii COMMENT='Haldor - Norse name meaning Thor''s Stone';

-- --------------------------------------------------------

--
-- Table structure for table `haldor_payloads`
--

DROP TABLE IF EXISTS `haldor_payloads`;
CREATE TABLE IF NOT EXISTS `haldor_payloads` (
  `id` int(10) unsigned NOT NULL,
  `payload` text,
  `session` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `keyholders`
--

DROP TABLE IF EXISTS `keyholders`;
CREATE TABLE IF NOT EXISTS `keyholders` (
  `id` int(10) unsigned NOT NULL,
  `keycode` varchar(200) CHARACTER SET ascii NOT NULL,
  `person` varchar(500) NOT NULL DEFAULT 'n00b',
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `procurement`
--

DROP TABLE IF EXISTS `procurement`;
CREATE TABLE IF NOT EXISTS `procurement` (
  `id` int(10) unsigned NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(1500) NOT NULL,
  `description` text NOT NULL,
  `need_amount` int(11) NOT NULL,
  `have_amount` int(11) NOT NULL DEFAULT '0',
  `cost` varchar(500) NOT NULL DEFAULT '',
  `history` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archived_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `RFID_keys`
--

DROP TABLE IF EXISTS `RFID_keys`;
CREATE TABLE IF NOT EXISTS `RFID_keys` (
  `keycode` varchar(200) CHARACTER SET ascii NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `space_invaders`
--

DROP TABLE IF EXISTS `space_invaders`;
CREATE TABLE IF NOT EXISTS `space_invaders` (
  `id` int(10) unsigned NOT NULL,
  `keyholder_id` int(10) unsigned DEFAULT NULL,
  `keycode` varchar(200) NOT NULL,
  `open_at` timestamp NULL DEFAULT NULL,
  `denied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `role` set('Admin','General','Keyholder','Backer','Guest','Invite','Verify','Reset','Disabled') CHARACTER SET ascii NOT NULL DEFAULT 'Guest,Invite',
  `email` varchar(255) NOT NULL,
  `pwhash` varchar(255) NOT NULL,
  `current_session` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `main_phone` varchar(30) NOT NULL DEFAULT '',
  `emergency_phone` varchar(30) NOT NULL DEFAULT '',
  `interests` text NOT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `left_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `haldor`
--
ALTER TABLE `haldor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `haldor_payloads`
--
ALTER TABLE `haldor_payloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyholders`
--
ALTER TABLE `keyholders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `keycode` (`keycode`,`end_at`);

--
-- Indexes for table `procurement`
--
ALTER TABLE `procurement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `space_invaders`
--
ALTER TABLE `space_invaders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `haldor`
--
ALTER TABLE `haldor`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `haldor_payloads`
--
ALTER TABLE `haldor_payloads`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `keyholders`
--
ALTER TABLE `keyholders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `procurement`
--
ALTER TABLE `procurement`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `space_invaders`
--
ALTER TABLE `space_invaders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
