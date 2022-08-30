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
  `HKID` varchar(256) NOT NULL,
  `Verified` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `LastModifedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE (`Email`)
  -- FOREIGN KEY (RoleId) REFERENCES Roles(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for table `Appointments`
CREATE TABLE IF NOT EXISTS `Appointments` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `User` varchar(100) NOT NULL,
  `Location` varchar(30) NOT NULL,
  `DateTime` datetime NOT NULL,
  `Approved` tinyint(1) NOT NULL DEFAULT 0,
  `Notified` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `LastModifedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
  -- FOREIGN KEY (`UserId`) REFERENCES Users(`Id`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for table `ip`
CREATE TABLE IF NOT EXISTS `AuthLog` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `address` char(16) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(100),
  `action` varchar(16) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8MB4 COLLATE=utf8mb4_bin;

-- Create Table for table `otp`
CREATE TABLE IF NOT EXISTS `OTP` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100),
  `otp` varchar(16) NOT NULL,
  `Used` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `LastModifedAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8MB4 COLLATE=utf8mb4_bin;

