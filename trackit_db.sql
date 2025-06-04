-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 09:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trackit_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accum_points`
--

CREATE TABLE `accum_points` (
  `Record_id` int(11) NOT NULL,
  `Emp_id` varchar(10) DEFAULT NULL,
  `Week_Start` date DEFAULT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `Dept_Id` varchar(10) NOT NULL,
  `Dept_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`Dept_Id`, `Dept_Name`) VALUES
('D001', 'Paul Kaldi Coffee Roastery & Shop - BINHI/Elem. Coop');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contacts`
--

CREATE TABLE `emergency_contacts` (
  `contact_id` int(11) NOT NULL,
  `emp_id` varchar(10) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_contacts`
--

INSERT INTO `emergency_contacts` (`contact_id`, `emp_id`, `contact_name`, `relationship`, `contact_number`) VALUES
(7, 'MPKM001', 'James Palermo', 'Partner', '9070606090'),
(8, 'PKM002', 'Vima Torremucha', 'Mother', '9812950681'),
(9, 'PKE002', 'Febelle Ice Salave', NULL, '9456712633'),
(10, 'PKE003', 'Rona Mie Arroz', 'Cousin', '9955406820'),
(11, 'PKE004', 'Leovic Velarde', 'Brother', '9812950631'),
(12, 'PKE005', 'Merry Joy Ramos', 'Mother', '9850275710');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `Emp_Id` varchar(10) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `role` enum('Employee','Manager','Admin') DEFAULT 'Employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Dept_Id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`Emp_Id`, `password`, `first_name`, `last_name`, `middle_name`, `date_of_birth`, `phone_number`, `email`, `job_title`, `role`, `created_at`, `Dept_Id`) VALUES
('PKM001', '', 'Glenda', 'Baquiriza', NULL, '1998-07-08', '09108133090', 'glenda.baquiriza@gmail.com', 'Barista/POS/Manager', 'Employee', '2025-05-24 11:09:57', 'D001'),
('PKA001', '', 'Peter Paul', 'Dayanan', NULL, '1998-07-08', NULL, NULL, 'Administrator', 'Admin', '2025-05-24 11:09:57', 'D001'),
('PKE001', '', 'Jayhson', 'Bibit', NULL, '0000-09-05', '09692439884', 'jayhson.bibit@gmail.com', 'Barista', 'Employee', '2025-05-24 11:09:57', 'D001'),
('PKE002', '', 'Stewart Sean', 'Dayco', NULL, '2000-09-24', '09666800825', 'stewartsean.dayco@gmail.com', 'Barista/POS', 'Employee', '2025-05-24 11:09:57', 'D001'),
('PKE003', '', 'Jeric', 'Palermo', NULL, '1998-03-23', '09817215444', 'jeric.palermo@gmail.com', 'Barista', 'Employee', '2025-05-24 11:09:57', 'D001'),
('PKE004', '', 'Nikko', 'Velarde', NULL, '2000-02-13', '09939780149', 'nikko.velarde@gmail.com', 'Barista', 'Employee', '2025-05-24 11:09:57', 'D001'),
('PKE005', '', 'Marvin John', 'Ramos', NULL, '2005-05-18', '09388777885', 'ramosmarvinjohn27@gmail.com', 'Barista/POS', 'Employee', '2025-05-24 11:09:57', 'D001'),
('PKM002', '', 'Maricel', 'Torremucha', NULL, '1999-05-10', '09930690299', 'maricel.torremucha@gmail.com', 'Barista/POS/Manager', 'Employee', '2025-05-24 11:09:57', 'D001');

-- --------------------------------------------------------

--
-- Table structure for table `employee_employment_information`
--

CREATE TABLE `employee_employment_information` (
  `Employment_Id` int(11) NOT NULL,
  `Emp_Id` varchar(10) DEFAULT NULL,
  `Hired_Date` date NOT NULL,
  `Role` enum('POS Manager','Barista','Staff','Rider') DEFAULT NULL,
  `Job_Title` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_employment_information`
--

INSERT INTO `employee_employment_information` (`Employment_Id`, `Emp_Id`, `Hired_Date`, `Role`, `Job_Title`) VALUES
(9, 'PKA001', '2025-05-24', 'POS Manager', NULL),
(10, 'MPKM001', '2025-05-24', 'Barista', NULL),
(11, 'PKM002', '2025-05-24', 'Barista', NULL),
(12, 'PKE001', '2025-05-24', 'Barista', NULL),
(13, 'PKE002', '2025-05-24', 'Barista', NULL),
(14, 'PKE003', '2025-05-24', 'Barista', NULL),
(15, 'PKE004', '2025-05-24', 'Barista', NULL),
(16, 'PKE005', '2025-05-24', 'Barista', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_profile`
--

CREATE TABLE `employee_profile` (
  `Emp_Id` varchar(10) NOT NULL,
  `First_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) DEFAULT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Place_of_Birth` varchar(100) DEFAULT NULL,
  `Date_of_Birth` date DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT 'Other',
  `Civil_Status` enum('Single','Married','Widowed') DEFAULT NULL,
  `Nationality` varchar(50) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_profile`
--

INSERT INTO `employee_profile` (`Emp_Id`, `First_Name`, `Last_Name`, `Middle_Name`, `Place_of_Birth`, `Date_of_Birth`, `Gender`, `Civil_Status`, `Nationality`, `Phone`, `Email`, `Address`, `profile_picture`) VALUES
('MPKM001', 'Glenda', 'Baquiriza', NULL, 'Carles, Iloilo', '1998-07-08', 'Female', 'Single', 'Filipino', '09108133090', 'glenda.baquiriza@gmail.com', '12 Rizal Street, Molo, Iloilo City', NULL),
('PKA001', 'Peter Paul', 'Dayanan', NULL, 'Carles, Iloilo', '1998-07-08', 'Male', 'Single', 'Filipino', NULL, NULL, NULL, NULL),
('PKE001', 'Jayhson', 'Bibit', NULL, 'Cavite', '0000-09-05', 'Male', 'Single', 'Filipino', '09692439884', 'jayhson.bibit@gmail.com', '8 Bonifacio Street, Lapaz, Iloilo City', NULL),
('PKE002', 'Stewart Sean', 'Dayco', NULL, 'Iloilo', '2000-09-24', 'Male', 'Single', 'Filipino', '09666800825', 'stewartsean.dayco@gmail.com', '25 Mabini Street, City Proper, Iloilo City', NULL),
('PKE003', 'Jeric', 'Palermo', NULL, 'Bolo, Carles, Iloilo', '1998-03-23', 'Male', 'Single', 'Filipino', '09817215444', 'jeric.palermo@gmail.com', 'Sitio Bato, Brgy. Cagay, Oton, Iloilo', NULL),
('PKE004', 'Nikko', 'Velarde', NULL, 'Roxas, Capiz', '2000-02-13', 'Male', 'Single', 'Filipino', '09939780149', 'nikko.velarde@gmail.com', 'Purok 5, Brgy. San Jose, San Miguel, Iloilo', NULL),
('PKE005', 'Marvin John', 'Ramos', NULL, 'Iloilo', '2005-05-18', 'Male', 'Single', 'Filipino', '09388777885', 'ramosmarvinjohn27@gmail.com', 'Brgy. Buhang, Taftnorth, Mandurriao, Iloilo City', NULL),
('PKM002', 'Maricel', 'Torremucha', NULL, 'Quezon City', '1999-05-10', 'Female', 'Single', 'Filipino', '09930690299', 'maricel.torremucha@gmail.com', 'Lot 7, Phase 2, Megaworld Blvd., Mandurriao, Iloil', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `assigned_to` varchar(10) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('not_started','in_progress','done','overdue') NOT NULL DEFAULT 'not_started',
  `assigned_by` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accum_points`
--
ALTER TABLE `accum_points`
  ADD PRIMARY KEY (`Record_id`),
  ADD KEY `fk_accum_emp` (`Emp_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`Dept_Id`);

--
-- Indexes for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `fk_emergency_contacts_emp` (`emp_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`Emp_Id`);

--
-- Indexes for table `employee_employment_information`
--
ALTER TABLE `employee_employment_information`
  ADD PRIMARY KEY (`Employment_Id`),
  ADD KEY `fk_employment_emp` (`Emp_Id`);

--
-- Indexes for table `employee_profile`
--
ALTER TABLE `employee_profile`
  ADD PRIMARY KEY (`Emp_Id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `fk_assigned_to` (`assigned_to`),
  ADD KEY `fk_assigned_by` (`assigned_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accum_points`
--
ALTER TABLE `accum_points`
  MODIFY `Record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employee_employment_information`
--
ALTER TABLE `employee_employment_information`
  MODIFY `Employment_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accum_points`
--
ALTER TABLE `accum_points`
  ADD CONSTRAINT `fk_accum_emp` FOREIGN KEY (`Emp_id`) REFERENCES `employees` (`Emp_Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD CONSTRAINT `fk_emergency_contacts_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`Emp_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_employment_information`
--
ALTER TABLE `employee_employment_information`
  ADD CONSTRAINT `fk_employment_emp` FOREIGN KEY (`Emp_Id`) REFERENCES `employees` (`Emp_Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee_profile`
--
ALTER TABLE `employee_profile`
  ADD CONSTRAINT `fk_profile_emp` FOREIGN KEY (`Emp_Id`) REFERENCES `employees` (`Emp_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `employees` (`Emp_Id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_assigned_to` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`Emp_Id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
