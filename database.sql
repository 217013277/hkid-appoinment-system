-- Create Table for table `Roles`
CREATE TABLE IF NOT EXISTS `Roles` (
  `Id` int NOT NULL,
  `RoleName` varchar(30) NOT NULL,
  `Description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `RoleName` (`RoleName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table `Roles`
INSERT INTO `Roles` (`Id`, `RoleName`, `Description`) VALUES
(1, 'JuniorStaff', '- Go through application process at Registration of Persons Offices\r\n- Handle applications at office\r\n- Issue new HKID card to the corresponding applicants'),
(2, 'ApprovingStaff', '- Approve applications of special cases'),
(3, 'SystemAdmin', '- Deploy security controls\r\n- Configure security setting\r\n- Perform backup\r\n- Create and manage accounts'),
(4, 'EndUser', NULL);

-- AUTO_INCREMENT for table `Roles`
ALTER TABLE `Roles`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- Create Table for table `Users`
CREATE TABLE IF NOT EXISTS `Users` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(30) NOT NULL,
  `Salt` varchar(128) NOT NULL,
  `Hash` varchar(512) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `RoleId` int,
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
  UNIQUE (`Username`, `Email`, `HKID`)
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
  `VenueId` int NOT NULL,
  `StartTime` datetime NOT NULL,
  `EndTime` datetime NOT NULL,
  `ApprovalStatus` varchar(30) NOT NULL,
  `Notified` varchar(30),
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `LastModifedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
  -- FOREIGN KEY (`UserId`) REFERENCES Users(`Id`),
  -- FOREIGN KEY (`VenueId`) REFERENCES Venues(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for table `ip`
CREATE TABLE IF NOT EXISTS `AuthLog` (
  `address` char(16) COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(30),
  `action` varchar(16) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=UTF8MB4 COLLATE=utf8mb4_bin;
