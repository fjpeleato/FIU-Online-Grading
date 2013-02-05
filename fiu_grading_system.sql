-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 26, 2012 at 06:56 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fiu_grading_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `Activities`
--

CREATE TABLE IF NOT EXISTS `Activities` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `course_ID` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `max_points` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`,`course_ID`),
  KEY `course_ID` (`course_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `Activities`
--

INSERT INTO `Activities` (`ID`, `course_ID`, `name`, `max_points`) VALUES
(1, 1, 'QUIZ', 20),
(2, 1, 'TEST', 50),
(3, 1, 'HW1', 20),
(4, 2, 'QUIZ', 20),
(5, 2, 'TEST', 50),
(6, 2, 'HW1', 20),
(7, 3, 'QUIZ', 50),
(8, 3, 'TEST', 60),
(9, 3, 'HW1', 20);

-- --------------------------------------------------------

--
-- Table structure for table `CourseEnrollment`
--

CREATE TABLE IF NOT EXISTS `CourseEnrollment` (
  `student_ID` int(11) NOT NULL DEFAULT '0',
  `course_ID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`student_ID`,`course_ID`),
  KEY `course_ID` (`course_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CourseEnrollment`
--

INSERT INTO `CourseEnrollment` (`student_ID`, `course_ID`) VALUES
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `CourseRequests`
--

CREATE TABLE IF NOT EXISTS `CourseRequests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `professor_ID` int(11) DEFAULT NULL,
  `course_number` varchar(16) DEFAULT NULL,
  `section` varchar(3) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `max_enrollment` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `professor_ID` (`professor_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `CourseRequests`
--

INSERT INTO `CourseRequests` (`ID`, `professor_ID`, `course_number`, `section`, `name`, `max_enrollment`) VALUES
(1, 8, 'CDA3103', 'U01', 'Fundamentals of Computer Sys.', 30),
(2, 9, 'CDA4101', 'U01', 'Structured Computer Org', 30);

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE IF NOT EXISTS `Courses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `professor_ID` int(11) DEFAULT NULL,
  `course_number` varchar(16) DEFAULT NULL,
  `section` varchar(3) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `current_enrollment` int(11) DEFAULT NULL,
  `max_enrollment` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `professor_ID` (`professor_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `Courses`
--

INSERT INTO `Courses` (`ID`, `professor_ID`, `course_number`, `section`, `name`, `current_enrollment`, `max_enrollment`) VALUES
(1, 8, 'COP4338', 'U01', 'Computer Programming III', 20, 25),
(2, 7, 'CEN4010', 'U01', 'Software Engineering I', 20, 25),
(3, 9, 'COP3337', 'U01', 'Computer Programming II', 20, 25),
(4, 9, 'COT3420', 'U01', 'Logic for Computer Science', 20, 25);

-- --------------------------------------------------------

--
-- Stand-in structure for view `courses_avg`
--
CREATE TABLE IF NOT EXISTS `courses_avg` (
`course_ID` int(11)
,`name` varchar(50)
,`classMax` decimal(32,0)
,`classPoints` decimal(32,0)
);
-- --------------------------------------------------------

--
-- Table structure for table `Grades`
--

CREATE TABLE IF NOT EXISTS `Grades` (
  `student_ID` int(11) NOT NULL DEFAULT '0',
  `activity_ID` int(11) NOT NULL DEFAULT '0',
  `points` int(11) DEFAULT NULL,
  PRIMARY KEY (`student_ID`,`activity_ID`),
  KEY `activity_ID` (`activity_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Grades`
--

INSERT INTO `Grades` (`student_ID`, `activity_ID`, `points`) VALUES
(2, 1, 13),
(2, 2, 0),
(2, 3, 20),
(2, 4, 13),
(2, 5, 0),
(2, 6, 20),
(2, 7, 13),
(2, 8, 0),
(2, 9, 20),
(3, 1, 20),
(3, 2, 20),
(3, 3, 20),
(3, 4, 20),
(3, 5, 20),
(3, 6, 20),
(3, 7, 20),
(3, 8, 20),
(3, 9, 20),
(4, 1, 20),
(4, 2, 50),
(4, 3, 20),
(4, 4, 20),
(4, 5, 50),
(4, 6, 20),
(4, 7, 20),
(4, 8, 50),
(4, 9, 20),
(5, 1, 20),
(5, 2, 20),
(5, 3, 20),
(5, 4, 20),
(5, 5, 20),
(5, 6, 20),
(5, 7, 20),
(5, 8, 20),
(5, 9, 20),
(6, 1, 20),
(6, 2, 50),
(6, 3, 20),
(6, 4, 20),
(6, 5, 50),
(6, 6, 20),
(6, 7, 20),
(6, 8, 50),
(6, 9, 20);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `password_hash` varchar(128) DEFAULT NULL,
  `user_name` varchar(40) DEFAULT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`ID`, `password_hash`, `user_name`, `first_name`, `last_name`, `type`) VALUES
(1, 'admin', 'admin', 'admin', 'admin', 0),
(2, 'student', 'ccorv', 'Carlos', 'Corvaia', 2),
(3, 'student', 'mluff', 'Monkey', 'Luffy', 2),
(4, 'student', 'rzoro', 'Roronoa', 'Zoro', 2),
(5, 'student', 'nrobi', 'Nico', 'Robin', 2),
(6, 'student', 'fpele', 'Frank', 'Peleato', 2),
(7, 'teach', 'milani', 'Masoud', 'Milani', 1),
(8, 'teach', 'pelin', 'Alex', 'Pelin', 1),
(9, 'teach', 'pestaina', 'Norman', 'Pestaina', 1);

-- --------------------------------------------------------

--
-- Structure for view `courses_avg`
--
DROP TABLE IF EXISTS `courses_avg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `courses_avg` AS select `Activities`.`course_ID` AS `course_ID`,`Activities`.`name` AS `name`,sum(`Activities`.`max_points`) AS `classMax`,sum(`Grades`.`points`) AS `classPoints` from (`Activities` join `Grades`) where (`Activities`.`ID` = `Grades`.`activity_ID`) group by `Activities`.`name`,`Activities`.`course_ID` order by `Activities`.`course_ID`;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Activities`
--
ALTER TABLE `Activities`
  ADD CONSTRAINT `Activities_ibfk_3` FOREIGN KEY (`course_ID`) REFERENCES `Courses` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `CourseEnrollment`
--
ALTER TABLE `CourseEnrollment`
  ADD CONSTRAINT `CourseEnrollment_ibfk_6` FOREIGN KEY (`course_ID`) REFERENCES `Courses` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `CourseEnrollment_ibfk_5` FOREIGN KEY (`student_ID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `CourseRequests`
--
ALTER TABLE `CourseRequests`
  ADD CONSTRAINT `CourseRequests_ibfk_3` FOREIGN KEY (`professor_ID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Courses`
--
ALTER TABLE `Courses`
  ADD CONSTRAINT `Courses_ibfk_3` FOREIGN KEY (`professor_ID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Grades`
--
ALTER TABLE `Grades`
  ADD CONSTRAINT `Grades_ibfk_6` FOREIGN KEY (`activity_ID`) REFERENCES `Activities` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Grades_ibfk_5` FOREIGN KEY (`student_ID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
