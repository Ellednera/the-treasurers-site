--CREATE DATABASE `tts`;
--USE `tts`;

CREATE TABLE IF NOT EXISTS`students` (
`stu_id` VARCHAR(15) NOT NULL,
`actual_name` VARCHAR(255) NOT NULL,
`username` VARCHAR(255) NOT NULL,
`pswd` VARCHAR(255) NOT NULL COMMENT 'encrypted password',
`pswd_bu` VARCHAR(255) NOT NULL COMMENT 'backup original password',
`role` INT(1) NOT NULL,
`c_name` VARCHAR(3) NOT NULL,
`stu_deleted` INT(1) NOT NULL,
PRIMARY KEY (stu_id)
);

CREATE TABLE IF NOT EXISTS `payments` (
`pmt_id` INT(255) NOT NULL AUTO_INCREMENT,
`pmt_name` VARCHAR(255) NOT NULL,
`pmt_price` VARCHAR(10) NOT NULL,
`pmt_status` VARCHAR(10) NOT NULL COMMENT 'money still with the treasurer or not',
`c_name` VARCHAR(3) NOT NULL,
`pmt_deleted` INT(1) NOT NULL,
PRIMARY KEY (pmt_id)
);

CREATE TABLE IF NOT EXISTS `sp_record` (
`spr_id` INT(255) NOT NULL AUTO_INCREMENT,
`stu_id` VARCHAR(15) NOT NULL,
`pmt_id` INT(255) NOT NULL,
`spr_status` VARCHAR(9) NOT NULL COMMENT 'paid or not',
`spr_deleted` INT(1) NOT NULL,
PRIMARY KEY (spr_id)
);

CREATE TABLE IF NOT EXISTS `class` (
`c_name` VARCHAR(3) NOT NULL,
`c_exists` int(1) NOT NULL COMMENT 'class exists or not',
PRIMARY KEY (c_name)
);

INSERT INTO `class` (`c_name`, `c_exists`) VALUES
('1A',	1),
('1B',	1),
('1C',	1),
('1D',	1),
('1E',	1),
('1F',	1),
('1G',	1),
('1H',	1),
('1I',	1),
('1J',	1),
('2A',	1),
('2B',	1),
('2C',	1),
('2D',	1),
('2E',	1),
('2F',	1),
('2G',	1),
('2H',	1),
('2I',	1),
('2J',	0),
('3A',	1),
('3B',	1),
('3C',	1),
('3D',	1),
('3E',	1),
('3F',	1),
('3G',	1),
('3H',	1),
('3I',	1),
('3J',	0),
('4A',	1),
('4B',	1),
('4C',	1),
('4D',	1),
('4E',	1),
('4F',	1),
('4G',	1),
('4H',	1),
('4I',	1),
('4J',	0),
('5A',	1),
('5B',	1),
('5C',	1),
('5D',	1),
('5E',	1),
('5F',	1),
('5G',	1),
('5H',	1),
('5I',	1),
('5J',	0);

CREATE TABLE IF NOT EXISTS `expences` (
`exp_id` INT(255) NOT NULL AUTO_INCREMENT,
`exp_name` VARCHAR(255) NOT NULL,
`exp_price` VARCHAR(255) NOT NULL,
`c_name` VARCHAR(3) NOT NULL,
`exp_deleted` INT(1) NOT NULL,
PRIMARY KEY (exp_id)
);

ALTER TABLE `students`
ADD FOREIGN KEY (c_name) REFERENCES `class`(c_name);

ALTER TABLE `payments`
ADD FOREIGN KEY (c_name) REFERENCES `class`(c_name);

ALTER TABLE `sp_record`
ADD FOREIGN KEY (stu_id) REFERENCES students(stu_id);
ALTER TABLE `sp_record`
ADD FOREIGN KEY (pmt_id) REFERENCES payments(pmt_id);

ALTER TABLE `expences`
ADD FOREIGN KEY (c_name) REFERENCES `class`(c_name);