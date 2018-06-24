

CREATE TABLE IF NOT EXISTS `v4_advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `customer` varchar(128) NOT NULL,
  `image_filename` varchar(256) NOT NULL,
  `image_title` varchar(256) NOT NULL,
  `target_url` varchar(256) NOT NULL,
  `is_active` int(1) NOT NULL,
  `from_time` int(11) NOT NULL,
  `to_time` int(11) NOT NULL,
  `times_shown` int(11) NOT NULL,
  `times_clicked` int(11) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `v4_advertisements`
--

INSERT INTO `v4_advertisements` (`id`, `title`, `customer`, `image_filename`, `image_title`, `target_url`, `is_active`, `from_time`, `to_time`, `times_shown`, `times_clicked`, `notes`) VALUES
(2, 'Ρόκκος', 'Ρόκκος', '/volax4/uploads/ads/rockos.jpg', '', 'http://www.tinos360.gr/rokos/index.html', 1, -3600, 1577833200, 3, 1, ''),
(3, 'Του καρόλου τα παιδιά...', 'Κάρολος', '/volax4/uploads/ads/karolos.jpg', '', 'http://www.tinos360.gr/volax/index.html', 1, -3600, 1893452400, 6, 1, ''),
(4, 'Μαραθιά 1', 'Μάραθος', '/volax4/uploads/images_dv/dolphins.gif', 'Η μαραθιά που μαράθηκε...', 'http://www.e-tinos.gr/marathia/index.php', 1, 1356994800, 1451516400, 15, 1, '');

