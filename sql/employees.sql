
--
-- Database: `assignment2` and php web application user
drop database assignment2;

CREATE DATABASE assignment2;
GRANT USAGE ON *.* TO 'appuser'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON assignment2.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;

USE assignment2;
--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `salary` int(10) NOT NULL,
  `birthdate` date NOT NULL,
  `filename` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `address`, `salary`, `birthdate` ,`filename`) VALUES
(1, 'Roland Mendel', 'C/ Araquil, 67, Madrid', 5000, '1990-11-20', 'green_field.jpg'),
(2, 'Victoria Ashworth', '35 King George, London', 6500, '1985-04-12', 'mont_blanc.jpg'),
(3, 'Martin Blank', '25, Rue Lauriston, Paris', 8000, '1975-10-12', 'mountain.jpg');

