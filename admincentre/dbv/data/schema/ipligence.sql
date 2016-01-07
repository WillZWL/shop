CREATE TABLE `ipligence` (
  `ip_from` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `ip_to` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `country_code` varchar(10) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `continent_code` varchar(10) NOT NULL,
  `continent_name` varchar(255) NOT NULL,
  PRIMARY KEY (`ip_to`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1