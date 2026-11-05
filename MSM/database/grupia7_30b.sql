-- Corrected and Normalized SQL Dump for Movie Management System (`mms`)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `movie_id` INT(11) NOT NULL,
  `nr_tickets` INT(11) NOT NULL,
  `date` DATE NOT NULL,
  `time` VARCHAR(10) NOT NULL,
  `is_approved` ENUM('true', 'false') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `movie_id` (`movie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `movie_id`, `nr_tickets`, `date`, `time`, `is_approved`) VALUES
(1, 2, 1, 2, '2022-12-16', '19:00', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE IF NOT EXISTS `movies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `movie_name` VARCHAR(255) NOT NULL,
  `movie_desc` TEXT NOT NULL,
  `movie_quality` VARCHAR(50) NOT NULL,
  `movie_rating` DECIMAL(3,1) NOT NULL,
  `movie_image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `movie_name`, `movie_desc`, `movie_quality`, `movie_rating`, `movie_image`) VALUES
(1, 'Zgjoi', 'Hoping to provide for their families, struggling widows start a business to sell a local food product.', '3D', 10.0, 'zgjoi.jpg'),
(2, 'Fast and Furious', 'Fast & Furious is a media franchise centered on a series of action films.', '2D', 7.0, 'fastandfurious.jpg'),
(3, 'VENOM', 'Journalist Eddie Brock is trying to take down Carlton Drake, the founder of Life Foundation.', '6D', 7.0, 'venom.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `emri` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `is_admin` ENUM('true', 'false') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `emri`, `username`, `email`, `password`, `is_admin`) VALUES
(1, 'Arlinda', 'arlinda', 'arlindaosmani00@gmail.com', '$2y$10$0VBVvKNyxIl9OA00/FRmrep7atIIE/SoLXGEoXcjNmF.4ZV6b3NIG', 'true'),
(2, 'Iljas', 'iljas', 'iljas@iljas.com', '$2y$10$erk7uFn0RpqhC6g3VX.XcuyU3CVkOanelghahzqjmdtxeFwfEgQQO', 'false'),
(3, 'Linda', 'linda', 'linda@linda.com', '$2y$10$pzATPYrl/LDDmFp8DIxalOrvUNT2RL4.I3pFbRjGrsUE8L/xH9O32', 'true');

COMMIT;
