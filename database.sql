CREATE TABLE IF NOT EXISTS `Roles` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `RoleName` varchar(30) NOT NULL,
  `Description` varchar(256),
  PRIMARY KEY (`id`),
  UNIQUE (`RoleName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ALTER TABLE `Roles`
--   ADD PRIMARY KEY (`id`),
--   ADD UNIQUE (`RoleName`);

CREATE TABLE IF NOT EXISTS `Users` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(30) NOT NULL,
  `Salt` varchar(128) NOT NULL,
  `Hash` varchar(512) NOT NULL,
  `RoleId` int,
  `NameEnglish` varchar(256) NOT NULL,
  `NameChinese` varchar(256) COLLATE utf8_general_ci NOT NULL,
  `gender` varchar(30) NOT NULL,
  `address` varchar(256) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `PlaceOfBirth` varchar(100) NOT NULL,
  `Occupation` varchar(100) NOT NULL,
  `HKID` varchar(30) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE (`Username`,`HKID`),
  FOREIGN KEY (RoleId) REFERENCES Roles(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ALTER TABLE `Users`
--   ADD PRIMARY KEY (`id`),
--   ADD UNIQUE (`Username`,`HKID`),
--   ADD FOREIGN KEY (RoleId) REFERENCES Roles(`Id`);

CREATE TABLE IF NOT EXISTS `Venues` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `VenueName` varchar(30) NOT NULL,
  `VenueAddress` varchar(256),
  PRIMARY KEY (`Id`),
  UNIQUE (`VenueName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `Appointments` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `VenueId` int NOT NULL,
  `Date` date NOT NULL,
  `Time` int NOT NULL,
  `Approved` varchar(30) NOT NULL,
  `Notified` varchar(30),
  PRIMARY KEY (`Id`),
  FOREIGN KEY (`UserId`) REFERENCES Users(`Id`),
  FOREIGN KEY (`VenueId`) REFERENCES Venues(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

