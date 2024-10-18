SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

SET GLOBAL time_zone = '+08:00';
SET @@session.time_zone = '+08:00';

SET time_zone = "+08:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

-- Table structure for table `admin`
CREATE TABLE `admin` (
  `admin_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `user_type` TEXT NOT NULL,
  `status` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Initial data for table `admin`
INSERT INTO `admin` (`admin_id`, `username`, `password`, `user_type`, `status`) VALUES
(1, 'admin', '$2y$10$m62rGFcy.z6I0xvDBp4qSu88SQatgUSTGr6UpuD9wxrOefLoMcRQ.', 'ADMIN', 1);

-- --------------------------------------------------------

-- Table structure for table `scholars`
CREATE TABLE `scholars` (
  `student_no` VARCHAR(15) NOT NULL PRIMARY KEY,
  `name` TEXT NOT NULL,
  `semester` INT(11) NOT NULL,
  `church` TEXT NOT NULL,
  `roll_no` INT(11) NOT NULL,
  `year_level` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `pic` TEXT DEFAULT NULL,
  `admin_id` INT(11) NOT NULL,
  FOREIGN KEY (`admin_id`) REFERENCES `admin`(`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for table `leaders`
CREATE TABLE `leaders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` TEXT NOT NULL,
  `church_role` TEXT NOT NULL,
  `designation` TEXT NOT NULL,
  `church` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `admin_id` INT(11) NOT NULL,
  FOREIGN KEY (`admin_id`) REFERENCES `admin`(`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for table `events`
CREATE TABLE `events` (
  `event_code` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` TEXT NOT NULL,
  `semester` INT(11) NOT NULL,
  `admin_id` INT(11) NOT NULL,
  FOREIGN KEY (`admin_id`) REFERENCES `admin`(`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for table `attendance`
CREATE TABLE `attendance` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `student_no` VARCHAR(15) NOT NULL,
  `date` DATE NOT NULL,
  `day` TEXT NOT NULL,
  `event_code` INT(11) NOT NULL,
  `slot` INT(11) NOT NULL,
  `year_level` TEXT NOT NULL,
  `church` TEXT NOT NULL,
  `semester` INT(11) DEFAULT NULL,
  `time` TEXT NOT NULL,
  `ip_address` LONGTEXT DEFAULT NULL,
  FOREIGN KEY (`student_no`) REFERENCES `scholars`(`student_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`event_code`) REFERENCES `events`(`event_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for table `attendance_noti`
CREATE TABLE `attendance_noti` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `student_no` VARCHAR(15) NOT NULL,
  `date` TEXT NOT NULL,
  `day` TEXT NOT NULL,
  `event_code` INT(11) NOT NULL,
  `slot` INT(11) NOT NULL,
  `year_level` TEXT NOT NULL,
  `church` TEXT NOT NULL,
  `semester` INT(11) DEFAULT NULL,
  `time` TEXT NOT NULL,
  `ip_address` LONGTEXT DEFAULT NULL,
  `is_read` INT(11) DEFAULT 0,
  FOREIGN KEY (`student_no`) REFERENCES `scholars`(`student_no`),
  FOREIGN KEY (`event_code`) REFERENCES `events`(`event_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for table `settings`
CREATE TABLE `settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `location` TEXT DEFAULT NULL,
  `lat` TEXT DEFAULT NULL,
  `lon` TEXT DEFAULT NULL,
  `coverage` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Initial data for table `settings`
INSERT INTO `settings` (`id`, `location`, `lat`, `lon`, `coverage`) VALUES
(1, 'Alta Vista 2 Street', '14.678521', '121.005937', '0.5');

-- --------------------------------------------------------

-- Table structure for table `timetable`
CREATE TABLE `timetable` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `semester` INT(11) NOT NULL,
  `year_level` TEXT NOT NULL,
  `church` TEXT DEFAULT NULL,
  `day` TEXT NOT NULL,
  `slot` INT(11) NOT NULL,
  `slotlabel` TEXT DEFAULT NULL,
  `event_code` INT(11) NOT NULL,
  `academic_year` TEXT NOT NULL,
  `admin_id` INT(11) NOT NULL,
  FOREIGN KEY (`event_code`) REFERENCES `events`(`event_code`),
  FOREIGN KEY (`admin_id`) REFERENCES `admin`(`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Auto-increment for tables
ALTER TABLE `attendance`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

ALTER TABLE `attendance_noti`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

ALTER TABLE `settings`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `leaders`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

ALTER TABLE `timetable`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

ALTER TABLE `admin`
  MODIFY `admin_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
