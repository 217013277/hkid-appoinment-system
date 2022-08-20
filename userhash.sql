CREATE TABLE `userhash` (
  `id` varchar(30) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `hash` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `userhash`
  ADD PRIMARY KEY (`id`);