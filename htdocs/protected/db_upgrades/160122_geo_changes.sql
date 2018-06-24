

--
-- Table structure for table `v4_geo_features`
--

CREATE TABLE IF NOT EXISTS `v4_geo_features` (
  `id` int(11) NOT NULL,
  `feature_type` enum('point','route','area') NOT NULL,
  `group_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `geo_long` decimal(15,10) NOT NULL,
  `geo_lat` decimal(15,10) NOT NULL,
  `active` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `create_time` bigint(20) NOT NULL,
  `update_time` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v4_geo_groups`
--

CREATE TABLE IF NOT EXISTS `v4_geo_groups` (
  `id` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  `view_order` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v4_geo_waypoints`
--

CREATE TABLE IF NOT EXISTS `v4_geo_waypoints` (
`id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `waypoint_no` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `image` varchar(250) NOT NULL,
  `geo_long` decimal(15,10) NOT NULL,
  `geo_lat` decimal(15,10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `v4_geo_features`
--
ALTER TABLE `v4_geo_features`
 ADD PRIMARY KEY (`id`), 
 ADD KEY `geo_long` (`geo_long`), 
 ADD KEY `geo_lat` (`geo_lat`), 
 ADD KEY `group_id` (`group_id`), 
 ADD KEY `feature_type` (`feature_type`);

--
-- Indexes for table `v4_geo_groups`
--
ALTER TABLE `v4_geo_groups`
 ADD PRIMARY KEY (`id`), 
 ADD KEY `view_order` (`view_order`);

--
-- Indexes for table `v4_geo_waypoints`
--
ALTER TABLE `v4_geo_waypoints`
 ADD PRIMARY KEY (`id`), 
 ADD KEY `feature_id` (`feature_id`), 
 ADD KEY `waypoint_no` (`waypoint_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `v4_geo_features`
--
ALTER TABLE `v4_geo_features`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `v4_geo_groups`
--
ALTER TABLE `v4_geo_groups`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `v4_geo_waypoints`
--
ALTER TABLE `v4_geo_waypoints`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

