-- phpMyAdmin SQL Dump
-- version 4.4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2015 at 04:26 PM
-- Server version: 5.5.44-0+deb8u1
-- PHP Version: 5.6.13-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `maglabs_haldor_test`
--

--
-- Dumping data for table `keyholders`
--

INSERT INTO `keyholders` (`id`, `keycode`, `person`, `start_at`, `end_at`, `updated_at`) VALUES
(1, '777', 'LuckyCharms', '2015-10-01 07:00:00', NULL, '2015-10-12 22:51:25'),
(2, '13131313', 'Friday', '2014-06-13 07:00:00', '2015-03-13 07:00:00', '2015-10-12 22:51:25'),
(3, '909090', 'NOP', '2015-10-01 07:00:00', NULL, '2015-10-13 08:30:13');

--
-- Dumping data for table `space_invaders`
--

INSERT INTO `space_invaders` (`id`, `keyholder_id`, `keycode`, `open_at`, `denied_at`, `created_at`, `updated_at`) VALUES
(1, NULL, '80085', '2015-10-10 07:00:00', NULL, NULL, '2015-10-13 23:25:27'),
(2, NULL, '5376616', NULL, '2015-10-04 07:00:00', NULL, '2015-10-13 23:25:27');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `email`, `pwhash`, `current_session`, `first_name`, `last_name`, `main_phone`, `emergency_phone`, `interests`, `joined_at`, `left_at`, `created_at`, `updated_at`) VALUES
(1, 'Backer', 'maglabs-test-algae@kiafaldorius.net', '$2y$10$I0Bwt8XHnHH7AGW.sXEJ8.ix3VKchJbx3Kw3HSkuWDY4.s6N3nmAC', '36235aaaae33d64e95d60bdb', 'oceanica', 'Gephyrocapsa', '', '', '', NULL, NULL, NULL, '2015-10-12 21:58:09'),
(2, 'Guest', 'test-bennetiana@kiafaldorius.net', '$2y$10$I0Bwt8XHnHH7AGW.sXEJ8.ix3VKchJbx3Kw3HSkuWDY4.s6N3nmAC', 'bennetiana', 'bennetiana', 'Vanvoorstia', '', '', '', NULL, NULL, NULL, '2015-10-12 21:58:15'),
(3, 'Admin', 'test-Prochlorococcus@kiafaldorius.net', '$2y$10$I0Bwt8XHnHH7AGW.sXEJ8.ix3VKchJbx3Kw3HSkuWDY4.s6N3nmAC', 'marinus', 'marinus', 'Prochlorococcus', '', '', '', NULL, NULL, NULL, '2015-10-11 09:56:57'),
(4, 'Guest', 'test-pastorianus@kiafaldorius.net', '$2y$10$I0Bwt8XHnHH7AGW.sXEJ8.ix3VKchJbx3Kw3HSkuWDY4.s6N3nmAC', 'Saccharomyces', 'pastorianus', 'Saccharomyces', '', '', '', NULL, NULL, NULL, '2015-10-11 23:31:15'),
(5, 'Keyholder', 'test-cerevisiae@kiafaldorius.net', '$2y$10$I0Bwt8XHnHH7AGW.sXEJ8.ix3VKchJbx3Kw3HSkuWDY4.s6N3nmAC', 'cerevisiae', 'cerevisiae', 'Saccharomyces', '', '', '', NULL, NULL, NULL, '2015-10-11 23:31:19'),
(6, 'Backer', 'test-Brettanomyces@kiafaldorius.net', 'x', 'bruxellensis', 'bruxellensis', 'Brettanomyces', '', '', '', NULL, NULL, NULL, '2015-10-12 02:48:37'),
(7, 'General', 'test-naardenensis@kiafaldorius.net', 'x', 'naardenensis', 'naardenensis', 'Brettanomyces', '', '', '', NULL, NULL, NULL, '2015-10-12 02:49:48');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
