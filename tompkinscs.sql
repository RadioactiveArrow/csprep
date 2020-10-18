-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 18, 2020 at 04:03 AM
-- Server version: 5.7.31-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tompkinscs`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `ansID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `ansText` tinytext NOT NULL,
  `ansLetter` char(1) NOT NULL,
  `ansCorrect` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `student_id` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `grade` tinyint(4) NOT NULL,
  `email` tinytext NOT NULL,
  `parent_email` tinytext NOT NULL,
  `kva` bit(1) NOT NULL,
  `admin` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `questionID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `questionNum` int(11) NOT NULL,
  `questionBlockEncoded` text NOT NULL,
  `questionText` tinytext NOT NULL,
  `questionExplanation` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `testID` int(11) NOT NULL,
  `testName` tinytext NOT NULL,
  `testDiff` tinytext NOT NULL,
  `testQNum` int(11) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `useranswers`
--

CREATE TABLE `useranswers` (
  `userID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `questionNum` int(11) DEFAULT NULL,
  `answerID` int(11) NOT NULL,
  `userCorrect` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `userrank`
--

CREATE TABLE `userrank` (
  `userID` int(11) NOT NULL,
  `rankCorrect` int(11) NOT NULL,
  `rankTotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `userUID` tinytext NOT NULL,
  `userMail` tinytext NOT NULL,
  `userName` tinytext NOT NULL,
  `admin` bit(1) NOT NULL,
  `userPass` text NOT NULL,
  `verified` bit(1) NOT NULL,
  `vkey` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `usertests`
--

CREATE TABLE `usertests` (
  `userID` int(11) NOT NULL,
  `testID` int(11) NOT NULL,
  `score` double NOT NULL,
  `datesub` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`ansID`,`questionID`,`testID`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD UNIQUE KEY `student_id` (`student_id`(8));

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questionID`,`testID`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`testID`);

--
-- Indexes for table `useranswers`
--
ALTER TABLE `useranswers`
  ADD PRIMARY KEY (`userID`,`testID`,`questionID`,`answerID`);

--
-- Indexes for table `userrank`
--
ALTER TABLE `userrank`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `usertests`
--
ALTER TABLE `usertests`
  ADD PRIMARY KEY (`userID`,`testID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `ansID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `testID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
