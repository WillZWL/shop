CREATE TABLE `order_rma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `saleorderno` text,
  `model` text,
  `reasonforreturn` text,
  `receiveddate` date DEFAULT NULL,
  `awb` text,
  `actionid` int(11) DEFAULT NULL,
  `descriptionoffault` text,
  `email` text,
  `unit` int(11) DEFAULT NULL,
  `firstname` text,
  `surname` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8