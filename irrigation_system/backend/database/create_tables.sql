Να εκτελεστούν πρώτα με την σειρά αυτήν στο phpmyadmin

CREATE TABLE IF NOT EXISTS `farmer` (
`id` int NOT NULL AUTO_INCREMENT,
`farmer_name` varchar(40) NOT NULL,
`farmer_email` varchar(40) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `crop` (
`id` int NOT NULL AUTO_INCREMENT,
`farmer_id` int NOT NULL,
`crop_kind` varchar(40) NOT NULL,
`crop_place` varchar(40) NOT NULL,
`roots_number` int NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`farmer_id`) REFERENCES `farmer` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `data_table` (
`id` int NOT NULL AUTO_INCREMENT,
`crop_id` int NOT NULL,
`current_month` int NOT NULL,
`air_temp` float NOT NULL,
`air_humidity` float NOT NULL,
`soil_moisture` int NOT NULL,
`sun` int NOT NULL,
`liters` int NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`crop_id`) REFERENCES `crop` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `success` (
`id` int NOT NULL AUTO_INCREMENT,
`crop_id` int NOT NULL,
`point` int NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`crop_id`) REFERENCES `crop` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `advice` (
`id` int NOT NULL AUTO_INCREMENT,
`crop_id` int NOT NULL,
`litersPerRoot` int NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`crop_id`) REFERENCES `crop` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

