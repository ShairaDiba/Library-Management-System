-- SQL Dump for Library Management System
-- Modified based on given requirements

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `Student`
--

CREATE TABLE `Student` (
  `student_id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `issue_date` DATE NOT NULL,
  `borrower_id` INT(11) NOT NULL,
  `return_date` DATE NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(15) NOT NULL,
  `fine_amount` DECIMAL(10, 2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `Faculty`
--

CREATE TABLE `Faculty` (
  `faculty_id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `issue_date` DATE NOT NULL,
  `borrower_id` INT(11) NOT NULL,
  `return_date` DATE NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(15) NOT NULL,
  `department` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `Books`
--

CREATE TABLE `Books` (
  `book_id` INT(11) NOT NULL,
  `title` VARCHAR(250) NOT NULL,
  `author` VARCHAR(250) NOT NULL,
  `publisher` VARCHAR(250) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `year` YEAR NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `Library_staff`
--

CREATE TABLE `Library_staff` (
  `staff_id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `Borrowing_log`
--

CREATE TABLE `Borrowing_log` (
  `transaction_id` INT(11) NOT NULL,
  `borrower_id` INT(11) NOT NULL,
  `book_id` INT(11) NOT NULL,
  `issue_date` DATE NOT NULL,
  `return_id` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

-- Indexes for table `Student`
ALTER TABLE `Student`
  ADD PRIMARY KEY (`student_id`);

-- Indexes for table `Faculty`
ALTER TABLE `Faculty`
  ADD PRIMARY KEY (`faculty_id`);

-- Indexes for table `Books`
ALTER TABLE `Books`
  ADD PRIMARY KEY (`book_id`);

-- Indexes for table `Library_staff`
ALTER TABLE `Library_staff`
  ADD PRIMARY KEY (`staff_id`);

-- Indexes for table `Borrowing_log`
ALTER TABLE `Borrowing_log`
  ADD PRIMARY KEY (`transaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

-- AUTO_INCREMENT for table `Student`
ALTER TABLE `Student`
  MODIFY `student_id` INT(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `Faculty`
ALTER TABLE `Faculty`
  MODIFY `faculty_id` INT(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `Books`
ALTER TABLE `Books`
  MODIFY `book_id` INT(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `Library_staff`
ALTER TABLE `Library_staff`
  MODIFY `staff_id` INT(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `Borrowing_log`
ALTER TABLE `Borrowing_log`
  MODIFY `transaction_id` INT(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
