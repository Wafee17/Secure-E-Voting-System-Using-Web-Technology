-- Online Voting System Database Schema
-- This file contains only the table structure without any sample data

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `candidate_details`
--

CREATE TABLE `candidate_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `election_id` int(11) DEFAULT NULL,
  `candidate_name` varchar(255) DEFAULT NULL,
  `party_details` text DEFAULT NULL,
  `candidate_photo` text DEFAULT NULL,
  `inserted_by` varchar(255) DEFAULT NULL,
  `inserted_on` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `election_topic` varchar(255) DEFAULT NULL,
  `no_of_candidates` int(11) DEFAULT NULL,
  `starting_date` date DEFAULT NULL,
  `ending_date` date DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `inserted_by` varchar(255) DEFAULT NULL,
  `inserted_on` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `mobile_number` varchar(45) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `user_role` varchar(45) DEFAULT 'Voter',
  `otp` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votings`
--

CREATE TABLE `votings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `election_id` int(11) DEFAULT NULL,
  `voters_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) NOT NULL,
  `vote_date` date DEFAULT NULL,
  `vote_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
