
-- Create Table for table `Users`
CREATE TABLE IF NOT EXISTS `Users` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(100) NOT NULL,
  `Salt` varchar(128) NOT NULL,
  `Hash` varchar(512) NOT NULL,
  `NameEnglish` varchar(100) NOT NULL,
  `NameChinese` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `gender` varchar(12) NOT NULL,
  `address` varchar(100) NOT NULL,
  `DateOfBirth` datetime NOT NULL,
  `PlaceOfBirth` varchar(100) NOT NULL,
  `Occupation` varchar(100) NOT NULL,
  `HKID` varchar(30) NOT NULL,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `LastModifedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE (`Email`, `HKID`)
  -- FOREIGN KEY (RoleId) REFERENCES Roles(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for table `Venues`
CREATE TABLE IF NOT EXISTS `Venues` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `VenueName` varchar(30) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE (`VenueName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `Venues`
INSERT INTO `Venues` (`Id`, `VenueName`) VALUES
(1, 'Hong Kong Island'),
(2, 'East Kowloon'),
(3, 'West Kowloon'),
(4, 'Tsuen Wan'),
(5, 'Sha Tin'),
(6, 'Sheung Shui'),
(7, 'Tuen Mun'),
(8, 'Yuen Long'),
(9, 'Tseung Kwan O');

-- AUTO_INCREMENT for table `Venues`
ALTER TABLE `Venues`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

-- Create Table for table `Appointments`
CREATE TABLE IF NOT EXISTS `Appointments` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `Location` int NOT NULL,
  `DateTime` datetime NOT NULL,
  `ApprovalStatus` varchar(30) NOT NULL,
  `Notified` varchar(30),
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `LastModifedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
  -- FOREIGN KEY (`UserId`) REFERENCES Users(`Id`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for table `ip`
CREATE TABLE IF NOT EXISTS `AuthLog` (
  `address` char(16) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(100),
  `action` varchar(16) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=UTF8MB4 COLLATE=utf8mb4_bin;
