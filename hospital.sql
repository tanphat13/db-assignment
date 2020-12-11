-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2020 at 05:58 PM
-- Server version: 8.0.19
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital`
--
DROP DATABASE IF EXISTS `hospital`;
CREATE DATABASE IF NOT EXISTS `hospital` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `hospital`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `check_expired_medication`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_expired_medication` ()  BEGIN
    DECLARE id INT DEFAULT 0;
    DECLARE finished INT DEFAULT 0;
    DECLARE expired_medication CURSOR FOR SELECT medication_id FROM medication WHERE expiration_date <= CURRENT_DATE() AND out_of_date='no';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    OPEN expired_medication;
    check_loop: LOOP
    	FETCH expired_medication INTO id;
        IF finished = 1 THEN
        	LEAVE check_loop;
        END IF;
        UPDATE medication SET out_of_date='yes' WHERE medication_id = id;
    END LOOP;
    CLOSE expired_medication;
END$$

DROP PROCEDURE IF EXISTS `sorted_doctor_list`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sorted_doctor_list` (IN `start_date` DATE, IN `end_date` DATE)  BEGIN
	SELECT CONCAT(D.Fname, ' ', D.Lname) as fullname, num_of_patients FROM doctor D JOIN (SELECT doctor_id, COUNT(patient_id) as num_of_patients FROM (SELECT treats.inpatient_id as patient_id, treats.doctor_id FROM treatment JOIN treats ON treatment.treatment_id = treats.treatment_id WHERE treatment.start_date > start_date AND treatment.end_date < end_date UNION SELECT exams.outpatient_id as patient_id, exams.doctor_id FROM exams JOIN examination ON exams.examination_id = examination.examination_id WHERE examination.examination_date BETWEEN start_date AND end_date ) AS u GROUP BY doctor_id ORDER BY num_of_patients) U ON D.employee_id = U.doctor_id;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `total_price_medication`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `total_price_medication` (`in_patient_id` VARCHAR(7)) RETURNS JSON READS SQL DATA
BEGIN
	DECLARE result JSON DEFAULT JSON_ARRAY();
    DECLARE record JSON;
    DECLARE service VARCHAR(20) DEFAULT '';
    DECLARE price INT DEFAULT 0;
    DECLARE finished INT DEFAULT 0;
	DECLARE medication_price CURSOR FOR SELECT service_name, total_price FROM (SELECT CONCAT('treatment-',CONVERT(T.treatment_id, CHAR)) as service_name, SUM(M.price) as total_price, T.inpatient_id as patient_id FROM treats T JOIN treatment_medication TM ON T.treatment_id = TM.treatment_id JOIN medication M ON TM.medication_id = M.medication_id GROUP BY TM.treatment_id UNION SELECT CONCAT('examination-', CONVERT(E.examination_id, CHAR)) as service_name, SUM(M.price) as total_price, E.outpatient_id as patient_id FROM exams E JOIN examination_medication EM ON E.examination_id = EM.examination_id JOIN medication M ON EM.medication_id = M.medication_id GROUP BY EM.examination_id) as U WHERE patient_id = in_patient_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    OPEN medication_price;
    cal_price: LOOP
    	FETCH medication_price INTO service, price;
        IF finished = 1 THEN
        	LEAVE cal_price;
        END IF;
        SET record := JSON_OBJECT('service', service, 'price', price);
        SET result := JSON_ARRAY_INSERT(result, '$[0]', record);
    END LOOP;
    CLOSE medication_price;
    RETURN result;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `degree`
--

DROP TABLE IF EXISTS `degree`;
CREATE TABLE `degree` (
  `employee_id` int NOT NULL,
  `degree_name` text NOT NULL,
  `degree_speciality` text NOT NULL,
  `degree_year` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `degree`
--

INSERT INTO `degree` (`employee_id`, `degree_name`, `degree_speciality`, `degree_year`) VALUES
(101000, 'Doctor of Clinical Medicine ', 'Infectious disease', '1999'),
(101001, 'Master of Medical Science ', 'Pediatricians', '1996'),
(101002, 'Master of Clinical Medicine ', 'General surgeons', '2002'),
(101003, 'Doctor of Surgery ', 'Orthopedic surgeons', '1995'),
(101004, 'Doctor of Clinical Surgery ', 'Pulmonologists', '2010'),
(101005, 'Master of Medical Science ', 'Cardiac surgeons', '1994'),
(102000, 'Doctoral Degrees in Nursing', 'Cardiac Nurse', '2000'),
(102001, 'Bachelor of Science in Nursing', 'Critical Care Nurse', '2006'),
(102002, 'Doctoral Degrees in Nursing', 'Mental Health Nurse', '2015'),
(102003, 'Associate Degree in Nursing', 'Orthopedic Nurse', '2013'),
(102004, 'Master of Science in Nursing', 'Cardiac Nurse', '2007'),
(102005, 'Associate Degree in Nursing ', 'Mental Health Nurse', '2007'),
(102006, 'Master of Science in Nursing', 'Perioperative Nurse', '2009'),
(102007, 'Bachelor of Science in Nursing', 'Critical Care Nurse', '2005'),
(102008, 'Master of Science in Nursing', 'Geriatric Nursing', '2003'),
(201000, 'Doctor of Clinical Medicine ', 'Infectious disease', '2003'),
(201001, 'Master of Medical Science ', 'Pediatricians', '2010'),
(201002, 'Master of Medical Science ', 'Cardiac surgeons', '2015'),
(201003, 'Doctor of Surgery ', 'Orthopedic surgeons', '2009'),
(201004, 'Doctor of Surgery ', 'Orthopedic surgeons', '2016'),
(202000, 'Associate Degree in Nursing', 'Geriatric Nursing', '2005'),
(202001, 'Doctoral Degrees in Nursing', 'Mental Health Nurse', '2006'),
(202002, 'Master of Science in Nursing', 'Cardiac Nurse', '2014'),
(202003, 'Associate Degree in Nursing', 'Geriatric Nursing', '2013'),
(202004, 'Doctoral Degrees in Nursing', 'Mental Health Nurse', '2010'),
(202005, 'Bachelor of Science in Nursing', 'Perioperative Nurse', '2015'),
(202006, 'Master of Science in Nursing', 'Critical Care Nurse', '2019');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `department_id` int NOT NULL,
  `title` text NOT NULL,
  `dean_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `title`, `dean_id`) VALUES
(1, 'Department A', NULL),
(2, 'Department B', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `doctor`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `doctor`;
CREATE TABLE `doctor` (
`employee_id` int
,`Address` text
,`Fname` text
,`Lname` text
,`Date_of_birth` date
,`Gender` set('Male','Female','Others')
,`Job_Type` set('Doctor','Nurse')
,`Department_code` int
,`Start_date` date
);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `employee_id` int NOT NULL,
  `Address` text NOT NULL,
  `Fname` text NOT NULL,
  `Lname` text NOT NULL,
  `Date_of_birth` date NOT NULL,
  `Gender` set('Male','Female','Others') NOT NULL,
  `Job_Type` set('Doctor','Nurse') NOT NULL,
  `Department_code` int NOT NULL,
  `Start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `Address`, `Fname`, `Lname`, `Date_of_birth`, `Gender`, `Job_Type`, `Department_code`, `Start_date`) VALUES
(101000, '1204 London Street', 'Thanh', 'Lam', '1970-12-22', 'Male', 'Doctor', 1, '2010-10-06'),
(101001, '123 Golden Street', 'Long', 'Nguyen', '1965-02-23', 'Male', 'Doctor', 1, '2010-02-12'),
(101002, '96 Alberta Street', 'Uyen', 'Nguyen', '1975-02-23', 'Female', 'Doctor', 1, '2015-12-01'),
(101003, '99 Southern Street', 'Tien', 'Nguyen', '1963-01-21', 'Male', 'Doctor', 1, '2012-07-13'),
(101004, '1134 NorthEast Street', 'Tue', 'Ngo', '1979-03-14', 'Female', 'Doctor', 1, '2010-01-10'),
(101005, '969 Middle Street', 'Nguyen Van', 'A', '1969-05-27', 'Male', 'Doctor', 1, '2015-03-14'),
(102000, '157/48 Buffalo Street', 'Lisa', 'Nelson', '1977-12-02', 'Female', 'Nurse', 1, '2010-01-30'),
(102001, '34 Buffalo Street', 'Rose', 'Alizabeth', '1980-11-11', 'Female', 'Nurse', 1, '2010-02-22'),
(102002, '25 Middle Street', 'Linh', 'Nguyen', '1988-08-18', 'Female', 'Nurse', 1, '2015-12-13'),
(102003, '2048 Eastern Street', 'Nga', 'Do', '1985-07-07', 'Female', 'Nurse', 1, '2016-12-17'),
(102004, '232 Mountain Street', 'Linh', 'Ha', '1982-09-10', 'Female', 'Nurse', 1, '2014-10-27'),
(102005, '13 Babylon Street', 'Hexi', 'Nhu', '1982-10-30', 'Female', 'Nurse', 1, '2013-10-10'),
(102006, '208 Babylon Street', 'Jackie', 'Chen', '1984-03-31', 'Male', 'Nurse', 1, '2013-10-10'),
(102007, '1234 Bolean Street', 'Nam', 'Ngo', '1984-06-05', 'Male', 'Nurse', 1, '2009-04-03'),
(102008, '299 Long Street', 'Le', 'Katherine', '1985-08-12', 'Female', 'Nurse', 1, '2014-02-06'),
(201000, '69 England Street', 'Rang', 'Nguyen', '1977-08-12', 'Male', 'Doctor', 2, '2010-10-16'),
(201001, '269 HighLand Street', 'Kevin', 'Nguyen', '1987-03-24', 'Male', 'Doctor', 2, '2016-12-05'),
(201002, '145 Nation Street', 'Luck', 'Do', '1990-02-12', 'Male', 'Doctor', 2, '2019-02-04'),
(201003, '402 Lake Street', 'My', 'Hoang', '1986-06-24', 'Female', 'Doctor', 2, '2015-03-23'),
(201004, '626 Lake Street', 'Linh', 'Tong', '1990-04-26', 'Female', 'Doctor', 2, '2018-05-31'),
(202000, '3223 Haaland Street', 'Linh', 'Trang', '1982-07-16', 'Female', 'Nurse', 2, '2014-10-08'),
(202001, '36 Sharon Street', 'Quyen', 'Do', '1984-06-24', 'Female', 'Nurse', 2, '2010-11-28'),
(202002, '58 Pharaoh Street', 'Nhi', 'Hoang', '1989-03-19', 'Female', 'Nurse', 2, '2016-05-21'),
(202003, '126/4 Clinton Street', 'Phuong', 'Cara', '1992-11-20', 'Female', 'Nurse', 2, '2013-09-08'),
(202004, '76 Maximax Street', 'Thanh', 'Phuong', '1989-03-13', 'Female', 'Nurse', 2, '2017-06-21'),
(202005, '3241 Wordinton Street', 'Karen', 'Nguyen', '1993-05-21', 'Female', 'Nurse', 2, '2016-01-06'),
(202006, '56 Halminton Street', 'Rosie', 'Tran', '1994-05-21', 'Female', 'Nurse', 2, '2020-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

DROP TABLE IF EXISTS `examination`;
CREATE TABLE `examination` (
  `examination_id` int NOT NULL,
  `examination_date` date NOT NULL,
  `second_exam_date` date NOT NULL,
  `diagnosis` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fee` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `examination`
--

INSERT INTO `examination` (`examination_id`, `examination_date`, `second_exam_date`, `diagnosis`, `fee`) VALUES
(1, '2010-01-03', '2010-02-15', 'Malaise and fatigue', 50),
(2, '2017-06-18', '2017-09-24', 'Back pain', 120),
(3, '2014-06-27', '2014-07-06', 'Hypertension', 40),
(4, '2016-03-23', '2016-04-23', 'Pain in joint, Back pain', 260),
(5, '2015-03-07', '2015-03-31', 'Respiratory problems', 130),
(6, '2011-10-10', '2011-10-17', 'Acute bronchitis', 350),
(7, '2016-05-24', '2016-06-20', 'Acute laryngopharyngitis', 260),
(8, '2011-08-03', '2011-08-13', 'Asthma, Respiratory problems', 200),
(9, '2016-07-15', '2016-07-31', 'Visual refractive errors', 35),
(10, '2009-08-03', '2009-08-07', 'Diabetes', 95),
(11, '2004-12-29', '2005-01-05', 'Diabetes, Hyperlipidemia', 195),
(12, '2013-08-26', '2013-08-26', 'Allergic rhinitis', 25),
(13, '2014-02-23', '2014-03-01', 'Osteoarthritis', 340),
(14, '2013-08-01', '2013-09-16', 'Pain in joint', 560),
(15, '2008-10-06', '2008-11-06', 'Acute bronchitis', 320),
(16, '2016-04-08', '2016-04-28', 'Urinary tract infection', 225),
(17, '2012-04-06', '2012-04-26', 'Osteoarthritis', 260),
(18, '2020-12-08', '2020-12-24', 'Hangover', 200),
(19, '2020-12-08', '2020-12-24', 'Hangover', 200);

-- --------------------------------------------------------

--
-- Table structure for table `examination_medication`
--

DROP TABLE IF EXISTS `examination_medication`;
CREATE TABLE `examination_medication` (
  `medication_id` int NOT NULL,
  `examination_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `examination_medication`
--

INSERT INTO `examination_medication` (`medication_id`, `examination_id`) VALUES
(13, 1),
(5, 2),
(1, 3),
(11, 4),
(33, 4),
(8, 5),
(14, 6),
(18, 7),
(15, 8),
(34, 8),
(22, 9),
(3, 10),
(2, 11),
(35, 11),
(6, 12),
(21, 13),
(16, 14),
(36, 14),
(20, 15),
(37, 15),
(19, 16),
(9, 17);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams` (
  `outpatient_id` varchar(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `examination_id` int NOT NULL,
  `doctor_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`outpatient_id`, `examination_id`, `doctor_id`) VALUES
('OP00010', 1, 101003),
('OP00002', 4, 101005),
('OP00007', 5, 201001),
('OP00008', 7, 101004),
('OP00003', 8, 201002),
('OP00004', 10, 201002),
('OP00001', 11, 101000),
('OP00005', 15, 201004),
('OP00002', 16, 101005),
('OP00009', 17, 201002),
('OP00012', 19, 101004);

-- --------------------------------------------------------

--
-- Table structure for table `inpatient`
--

DROP TABLE IF EXISTS `inpatient`;
CREATE TABLE `inpatient` (
  `patient_id` varchar(7) NOT NULL,
  `date_of_discharge` date DEFAULT NULL,
  `diagnosis` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `fee` int DEFAULT NULL,
  `date_of_admission` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sickroom` varchar(5) DEFAULT NULL,
  `nurse_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inpatient`
--

INSERT INTO `inpatient` (`patient_id`, `date_of_discharge`, `diagnosis`, `fee`, `date_of_admission`, `sickroom`, `nurse_id`) VALUES
('IP00001', '2019-10-10', 'Hypertension, Pain in joint', 200, '2019-09-28 13:39:14', 'A201', 102002),
('IP00002', '2018-10-28', 'Diabetes, Malaise and fatigue, Asthma', 540, '2018-10-23 19:55:35', 'B304', 202005),
('IP00003', '2017-05-05', 'Back pain, Pain in joint', 1050, '2017-04-26 13:33:14', 'B205', 202002),
('IP00004', '2017-02-16', 'Respiratory problems', 165, '2017-02-14 04:26:18', 'A211', 102002),
('IP00005', '2009-06-07', 'Urinary tract infection', 470, '2009-06-04 20:49:27', 'A306', 102001),
('IP00006', '2020-10-28', 'Acute maxillary sinusitis', 2050, '2020-10-14 05:35:48', 'B307', 202006),
('IP00007', '2016-07-26', 'Malaise and fatigue, Respiratory problems', 600, '2016-07-24 17:38:17', 'A207', 102008),
('IP00008', '2018-01-23', 'Allergic rhinitis, Respiratory problems', 955, '2018-01-19 23:40:09', 'B413', 202004),
('IP00009', '2011-09-26', 'Acute laryngopharyngitis, Malaise and fatigue', 680, '2011-09-24 23:59:10', 'A403', 102004),
('IP00010', '2015-05-24', 'Reflux esophagitis', 400, '2015-05-22 19:20:26', 'B211', 202003),
('IP00011', NULL, NULL, NULL, '2020-12-10 23:13:41', NULL, NULL),
('IP00012', NULL, NULL, NULL, '2020-12-10 23:15:56', NULL, NULL),
('IP00013', NULL, NULL, NULL, '2020-12-10 23:17:12', NULL, NULL),
('IP00014', NULL, NULL, NULL, '2020-12-10 23:18:20', NULL, NULL),
('IP00015', NULL, 'Back Pain', NULL, '2020-12-19 02:17:00', 'A201', 102000),
('IP00016', NULL, 'Back Pain', NULL, '2020-12-14 05:22:00', 'A201', 102000);

-- --------------------------------------------------------

--
-- Stand-in structure for view `inpatient_of_doctor`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `inpatient_of_doctor`;
CREATE TABLE `inpatient_of_doctor` (
`inpatient_id` varchar(7)
);

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

DROP TABLE IF EXISTS `medication`;
CREATE TABLE `medication` (
  `medication_id` int NOT NULL,
  `name` text NOT NULL,
  `expiration_date` date NOT NULL,
  `effect` text NOT NULL,
  `price` int NOT NULL,
  `out_of_date` set('yes','no') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`medication_id`, `name`, `expiration_date`, `effect`, `price`, `out_of_date`) VALUES
(1, 'Hypertension Medicine', '2022-01-31', 'Reduce Hypertension', 380, 'no'),
(2, 'Hyperlipidemia Medicine', '2020-12-31', 'Reduce Hyperlipidemia', 205, 'no'),
(3, 'Diabetes Medicine', '2010-10-31', 'Reduce Diabetes diagnose', 310, 'yes'),
(4, 'Diabetes Medicine', '2019-10-20', 'Reduce Diabetes diagnoses', 225, 'yes'),
(5, 'Bond pain medicine', '2020-08-24', 'Reduce pain ', 450, 'yes'),
(6, 'Allergic rhinitis medicine', '2024-10-31', 'Reduce Allergic rhinitis diagnoses', 190, 'no'),
(7, 'Reflux esophagitis medicine', '2021-10-12', 'Reduce Reflux esophagitis diagnoses', 235, 'no'),
(8, 'Respiratory problems medicine', '2021-10-31', 'Reduce Respiratory problems diagnoses\r\n', 380, 'no'),
(9, 'Osteoarthritis medicine', '2022-12-31', 'Reduce Osteoarthritis diagnoses', 580, 'no'),
(10, 'Respiratory medicine', '2020-12-30', 'Reduce  Respiratory problems diagnoses', 460, 'no'),
(11, 'Pain in joint medicine', '2019-12-31', 'Reduce Pain in joint diagnoses', 130, 'yes'),
(12, 'Pain in joint medicine', '2021-01-31', 'Reduce Pain in joint diagnoses', 130, 'no'),
(13, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no'),
(14, 'antibiotics medicine', '2021-01-31', 'Provide antibiotic', 250, 'no'),
(15, 'Asthma medicine', '2022-05-31', 'Reduce Asthma diagnose ', 95, 'no'),
(16, 'Painless Medicine', '2025-12-31', 'Reduce pain', 50, 'no'),
(17, 'Painless Medicine', '2020-09-30', 'Reduce Pain', 40, 'yes'),
(18, 'laryngopharyngitis medicine', '2022-10-31', 'Reduce laryngopharyngitis diagnose', 650, 'no'),
(19, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no'),
(20, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no'),
(21, 'Osteoarthritis medicine', '2022-12-31', 'Reduce Osteoarthritis diagnoses', 580, 'no'),
(22, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no'),
(23, 'Respiratory problems medicine', '2021-10-31', 'Reduce Respiratory problems diagnoses\r\n', 380, 'no'),
(24, 'infection medicine', '2021-04-21', 'cure infection ', 45, 'no'),
(25, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no'),
(26, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no'),
(27, 'Respiratory medicine', '2020-12-30', 'Reduce  Respiratory problems diagnoses', 460, 'no'),
(28, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no'),
(29, 'laryngopharyngitis medicine', '2022-10-31', 'Reduce laryngopharyngitis diagnose', 650, 'no'),
(30, 'Asthma medicine', '2022-05-31', 'Reduce Asthma diagnose ', 95, 'no'),
(31, 'antibiotics medicine', '2022-06-15', 'Provide antibiotic', 300, 'no'),
(32, 'antibiotics medicine', '2022-06-15', 'Provide antibiotic', 300, 'no'),
(33, 'antibiotics medicine', '2022-06-15', 'Provide antibiotic', 300, 'no'),
(34, 'Respiratory problems medicine', '2021-01-22', 'Reduce Respiratory problems diagnoses\r\n', 330, 'no'),
(35, 'Diabetes Medicine', '2021-12-30', 'Reduce Diabetes diagnoses', 230, 'no'),
(36, 'antibiotics medicine', '2022-06-15', 'Provide antibiotic', 300, 'no'),
(37, 'antibiotics medicine', '2021-10-31', 'Provide antibiotic', 300, 'no');

-- --------------------------------------------------------

--
-- Stand-in structure for view `nurse`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `nurse`;
CREATE TABLE `nurse` (
`employee_id` int
,`Address` text
,`Fname` text
,`Lname` text
,`Date_of_birth` date
,`Gender` set('Male','Female','Others')
,`Job_Type` set('Doctor','Nurse')
,`Department_code` int
,`Start_date` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `outpatient_of_doctor`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `outpatient_of_doctor`;
CREATE TABLE `outpatient_of_doctor` (
`outpatient_id` varchar(7)
);

-- --------------------------------------------------------

--
-- Table structure for table `out_patient`
--

DROP TABLE IF EXISTS `out_patient`;
CREATE TABLE `out_patient` (
  `patient_id` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `out_patient`
--

INSERT INTO `out_patient` (`patient_id`) VALUES
('OP00001'),
('OP00002'),
('OP00003'),
('OP00004'),
('OP00005'),
('OP00006'),
('OP00007'),
('OP00008'),
('OP00009'),
('OP00010'),
('OP00011'),
('OP00012');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE `patient` (
  `patient_id` varchar(7) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `gender` set('Male','Female','Other') NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `fname`, `lname`, `date_of_birth`, `phone_number`, `gender`, `address`) VALUES
('IP00001', 'Valencia', 'Antonio', '1990-06-09', '0112254781', 'Male', '962 Hamsburg Street'),
('IP00002', 'Rooney', 'Wayne', '1984-08-22', '033562144', 'Male', '02 Capital Street'),
('IP00003', 'Mary', 'Nguyen', '1996-08-31', '0995544887', 'Female', '1992 Capital Street'),
('IP00004', 'Hilary', 'Clinton', '1986-07-02', '0557369154', 'Female', '63 Ausburg Street'),
('IP00005', 'German', 'Nguyen', '1993-08-25', '0315598997', 'Male', '694 Peripheral Street'),
('IP00006', 'Dung', 'Ngo', '1998-06-23', '0998547788', 'Male', '652 Citizen Street'),
('IP00007', 'Harry', 'Ngo', '2005-10-10', '0215199844', 'Male', '88 Global Street'),
('IP00008', 'Trista', 'Lummy', '2001-12-24', '0686845745', 'Female', '124 Binary Street'),
('IP00009', 'Violet', 'Crystal', '2006-07-08', '0252544698', 'Female', '374 Catalog Street'),
('IP00010', 'Melisa', 'Lucy', '1994-08-19', '0554897612', 'Female', '897 Millston Street'),
('IP00011', 'Nguyen', 'Long', '1998-07-29', '0909306071', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward'),
('IP00012', 'Nguyen', 'Long', '1998-07-29', '0909306071', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward'),
('IP00013', 'Nguyen', 'Long', '1998-07-29', '0909306071', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward'),
('IP00014', 'Nguyen', 'Long', '1998-07-29', '0909306071', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward'),
('IP00015', 'Nguyen', 'Long', '1998-07-29', '0909306071', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward'),
('IP00016', 'Nguyen', 'Long', '1998-10-22', '0909306071', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward'),
('OP00001', 'Dang', 'Nguyen', '1988-10-11', '0215194946', 'Male', '2123 London Street'),
('OP00002', 'My', 'Le', '1978-05-16', '0244994946', 'Female', '213 Hall Street'),
('OP00003', 'Tung', 'Haru', '1992-04-11', '0335594946', 'Male', '99 Hillston Street'),
('OP00004', 'Nguyen', 'Nguyen', '1989-01-20', '0337786245', 'Male', '101 Einstein Street'),
('OP00005', 'Lisa', 'Tran', '1999-09-09', '0689857851', 'Female', '1234 Bill Street'),
('OP00006', 'Trang', 'Tran', '2005-11-30', '0256458989', 'Female', '23 Local Street'),
('OP00007', 'Phung', 'Do', '1988-10-12', '0124536987', 'Male', '34 Lenglet Street'),
('OP00008', 'Long', 'Nguyen', '1999-08-10', '0265487913', 'Male', '13 Angeles Street'),
('OP00009', 'Rose', 'Halminton', '1966-02-09', '0668754129', 'Female', '329 Clinton Street'),
('OP00010', 'Viera', 'Patric', '1994-06-23', '0788945612', 'Male', '123 Parellet Street'),
('OP00011', 'Phat', 'Cu', '1999-12-13', '0934023414', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward'),
('OP00012', 'Phat', 'Cu', '1999-12-13', '0934023414', 'Male', '199/25 De Tham St, Pham Ngu Lao Ward');

--
-- Triggers `patient`
--
DROP TRIGGER IF EXISTS `classify`;
DELIMITER $$
CREATE TRIGGER `classify` AFTER INSERT ON `patient` FOR EACH ROW if NEW.patient_id LIKE 'OP%' THEN
	INSERT INTO out_patient(patient_id) VALUES (NEW.patient_id);
ELSEIF NEW.patient_id LIKE 'IP%' THEN
	INSERT INTO inpatient(patient_id) VALUES (NEW.patient_id);
    END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `patients_of_doctor`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `patients_of_doctor`;
CREATE TABLE `patients_of_doctor` (
`patient_id` varchar(7)
,`fname` mediumtext
,`lname` mediumtext
,`date_of_birth` date
,`phone_number` varchar(11)
,`gender` varchar(17)
,`address` mediumtext
);

-- --------------------------------------------------------

--
-- Table structure for table `phone_number`
--

DROP TABLE IF EXISTS `phone_number`;
CREATE TABLE `phone_number` (
  `employee_id` int NOT NULL,
  `Phone_number` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phone_number`
--

INSERT INTO `phone_number` (`employee_id`, `Phone_number`) VALUES
(101000, '0125466548'),
(201002, '0128261627'),
(102008, '0172638462'),
(101005, '0183477563'),
(101001, '0239446371'),
(201001, '0281628361'),
(102004, '0284627361'),
(102005, '0292735166'),
(101002, '0293847637'),
(102006, '0298461628'),
(102001, '0356678354'),
(101004, '0364582475'),
(202002, '0382637484'),
(102002, '0382736472'),
(202000, '0397263534'),
(201000, '0398273938'),
(201004, '0398372632'),
(202005, '0492736382'),
(202003, '0492928283'),
(202004, '0497263633'),
(102003, '0498876251'),
(201003, '0594736527'),
(102007, '0927451728'),
(202001, '0937263747'),
(102000, '0937481155'),
(202006, '0938274425'),
(101003, '0957462847');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

DROP TABLE IF EXISTS `treatment`;
CREATE TABLE `treatment` (
  `treatment_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `result` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`treatment_id`, `start_date`, `end_date`, `result`) VALUES
(1, '2019-09-28', '2019-10-12', 'better, no more diagnose'),
(2, '2018-10-24', '2018-10-27', 'diagnose decrease, patient gets better'),
(3, '2017-04-26', '2017-05-01', 'Pain is decrease, patient still being watch, maybe have some bad diagnose'),
(4, '2017-02-14', '2017-02-15', 'Normal'),
(5, '2009-06-04', '2009-06-06', 'No more infection, become better'),
(6, '2020-10-14', '2020-10-26', 'extremely dangerous, patient is confused,  need watching 24/7 '),
(7, '2016-07-25', '2016-07-25', 'normal diagnose, need rest'),
(8, '2018-01-20', '2018-01-22', 'Need more rest, bad diagnose, need watching 24/7'),
(9, '2011-09-25', '2011-09-26', 'No longer dangerous, diagnose is low, need more rest'),
(10, '2015-05-23', '2015-05-24', 'Normal, need more rest');

-- --------------------------------------------------------

--
-- Table structure for table `treatment_medication`
--

DROP TABLE IF EXISTS `treatment_medication`;
CREATE TABLE `treatment_medication` (
  `medication_id` int NOT NULL,
  `treatment_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `treatment_medication`
--

INSERT INTO `treatment_medication` (`medication_id`, `treatment_id`) VALUES
(12, 1),
(4, 2),
(30, 2),
(31, 2),
(17, 3),
(32, 3),
(23, 4),
(22, 5),
(24, 5),
(25, 6),
(26, 7),
(27, 7),
(10, 8),
(28, 9),
(29, 9),
(7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `treats`
--

DROP TABLE IF EXISTS `treats`;
CREATE TABLE `treats` (
  `inpatient_id` varchar(7) NOT NULL,
  `treatment_id` int NOT NULL,
  `doctor_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `treats`
--

INSERT INTO `treats` (`inpatient_id`, `treatment_id`, `doctor_id`) VALUES
('IP00001', 1, 101002),
('IP00002', 2, 101003),
('IP00003', 3, 201002),
('IP00004', 4, 201004),
('IP00005', 5, 101005),
('IP00006', 6, 101003),
('IP00007', 7, 201000),
('IP00008', 8, 101002),
('IP00009', 9, 201003),
('IP00010', 10, 201003);

-- --------------------------------------------------------

--
-- Structure for view `doctor`
--
DROP TABLE IF EXISTS `doctor`;

DROP VIEW IF EXISTS `doctor`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `doctor`  AS  select `employee`.`employee_id` AS `employee_id`,`employee`.`Address` AS `Address`,`employee`.`Fname` AS `Fname`,`employee`.`Lname` AS `Lname`,`employee`.`Date_of_birth` AS `Date_of_birth`,`employee`.`Gender` AS `Gender`,`employee`.`Job_Type` AS `Job_Type`,`employee`.`Department_code` AS `Department_code`,`employee`.`Start_date` AS `Start_date` from `employee` where (`employee`.`Job_Type` = 'Doctor') ;

-- --------------------------------------------------------

--
-- Structure for view `inpatient_of_doctor`
--
DROP TABLE IF EXISTS `inpatient_of_doctor`;

DROP VIEW IF EXISTS `inpatient_of_doctor`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `inpatient_of_doctor`  AS  select `T`.`inpatient_id` AS `inpatient_id` from (`treats` `T` join `doctor` `D` on((`T`.`doctor_id` = `d`.`employee_id`))) where (concat(`d`.`Fname`,' ',`d`.`Lname`) = 'Nguyen Van A') ;

-- --------------------------------------------------------

--
-- Structure for view `nurse`
--
DROP TABLE IF EXISTS `nurse`;

DROP VIEW IF EXISTS `nurse`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `nurse`  AS  select `employee`.`employee_id` AS `employee_id`,`employee`.`Address` AS `Address`,`employee`.`Fname` AS `Fname`,`employee`.`Lname` AS `Lname`,`employee`.`Date_of_birth` AS `Date_of_birth`,`employee`.`Gender` AS `Gender`,`employee`.`Job_Type` AS `Job_Type`,`employee`.`Department_code` AS `Department_code`,`employee`.`Start_date` AS `Start_date` from `employee` where (`employee`.`Job_Type` = 'Nurse') ;

-- --------------------------------------------------------

--
-- Structure for view `outpatient_of_doctor`
--
DROP TABLE IF EXISTS `outpatient_of_doctor`;

DROP VIEW IF EXISTS `outpatient_of_doctor`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `outpatient_of_doctor`  AS  select `E`.`outpatient_id` AS `outpatient_id` from (`exams` `E` join `doctor` `D` on((`E`.`doctor_id` = `d`.`employee_id`))) where (concat(`d`.`Fname`,' ',`d`.`Lname`) = 'Nguyen Van A') ;

-- --------------------------------------------------------

--
-- Structure for view `patients_of_doctor`
--
DROP TABLE IF EXISTS `patients_of_doctor`;

DROP VIEW IF EXISTS `patients_of_doctor`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `patients_of_doctor`  AS  select `P`.`patient_id` AS `patient_id`,`P`.`fname` AS `fname`,`P`.`lname` AS `lname`,`P`.`date_of_birth` AS `date_of_birth`,`P`.`phone_number` AS `phone_number`,`P`.`gender` AS `gender`,`P`.`address` AS `address` from (`inpatient_of_doctor` `IP` join `patient` `P` on((`ip`.`inpatient_id` = `P`.`patient_id`))) union select `P`.`patient_id` AS `patient_id`,`P`.`fname` AS `fname`,`P`.`lname` AS `lname`,`P`.`date_of_birth` AS `date_of_birth`,`P`.`phone_number` AS `phone_number`,`P`.`gender` AS `gender`,`P`.`address` AS `address` from (`outpatient_of_doctor` `OP` join `patient` `P` on((`op`.`outpatient_id` = `P`.`patient_id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `degree`
--
ALTER TABLE `degree`
  ADD PRIMARY KEY (`employee_id`,`degree_name`(20),`degree_speciality`(30),`degree_year`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `dean_id` (`dean_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `Department_code` (`Department_code`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`examination_id`);

--
-- Indexes for table `examination_medication`
--
ALTER TABLE `examination_medication`
  ADD PRIMARY KEY (`medication_id`,`examination_id`),
  ADD KEY `examination_id` (`examination_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`examination_id`),
  ADD KEY `examination_id` (`examination_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `exams_ibfk_1` (`outpatient_id`);

--
-- Indexes for table `inpatient`
--
ALTER TABLE `inpatient`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `nurse_id` (`nurse_id`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`medication_id`);

--
-- Indexes for table `out_patient`
--
ALTER TABLE `out_patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `phone_number`
--
ALTER TABLE `phone_number`
  ADD PRIMARY KEY (`employee_id`,`Phone_number`),
  ADD UNIQUE KEY `unique_phone` (`Phone_number`);

--
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`treatment_id`);

--
-- Indexes for table `treatment_medication`
--
ALTER TABLE `treatment_medication`
  ADD PRIMARY KEY (`medication_id`,`treatment_id`),
  ADD KEY `treatment_id` (`treatment_id`);

--
-- Indexes for table `treats`
--
ALTER TABLE `treats`
  ADD PRIMARY KEY (`treatment_id`),
  ADD KEY `treatment_id` (`treatment_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `treats_ibfk_1` (`inpatient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202007;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `examination_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `medication_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `treatment`
--
ALTER TABLE `treatment`
  MODIFY `treatment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `degree`
--
ALTER TABLE `degree`
  ADD CONSTRAINT `employee_degree` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`dean_id`) REFERENCES `employee` (`employee_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_department` FOREIGN KEY (`Department_code`) REFERENCES `department` (`department_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `examination_medication`
--
ALTER TABLE `examination_medication`
  ADD CONSTRAINT `examination_medication_ibfk_2` FOREIGN KEY (`examination_id`) REFERENCES `examination` (`examination_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `examination_medication_ibfk_3` FOREIGN KEY (`medication_id`) REFERENCES `medication` (`medication_id`) ON UPDATE CASCADE;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`outpatient_id`) REFERENCES `out_patient` (`patient_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`examination_id`) REFERENCES `examination` (`examination_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `exams_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `employee` (`employee_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inpatient`
--
ALTER TABLE `inpatient`
  ADD CONSTRAINT `inpatient_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inpatient_ibfk_2` FOREIGN KEY (`nurse_id`) REFERENCES `employee` (`employee_id`) ON UPDATE CASCADE;

--
-- Constraints for table `out_patient`
--
ALTER TABLE `out_patient`
  ADD CONSTRAINT `out_patient_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `phone_number`
--
ALTER TABLE `phone_number`
  ADD CONSTRAINT `employee_phone` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `treatment_medication`
--
ALTER TABLE `treatment_medication`
  ADD CONSTRAINT `treatment_medication_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medication` (`medication_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treatment_medication_ibfk_2` FOREIGN KEY (`treatment_id`) REFERENCES `treatment` (`treatment_id`) ON UPDATE CASCADE;

--
-- Constraints for table `treats`
--
ALTER TABLE `treats`
  ADD CONSTRAINT `treats_ibfk_1` FOREIGN KEY (`inpatient_id`) REFERENCES `inpatient` (`patient_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treats_ibfk_2` FOREIGN KEY (`treatment_id`) REFERENCES `treatment` (`treatment_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treats_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `employee` (`employee_id`) ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
DROP EVENT `check_medication`$$
CREATE DEFINER=`root`@`localhost` EVENT `check_medication` ON SCHEDULE EVERY 1 MINUTE STARTS '2002-12-11 00:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Check expired medication every day' DO CALL check_expired_medication()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
