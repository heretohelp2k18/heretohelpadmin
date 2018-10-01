CREATE TABLE IF NOT EXISTS `crimes` (
`id` int(11) NOT NULL,
  `crime` varchar(125) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `crimes`
--

INSERT INTO `crimes` (`id`, `crime`) VALUES
(1, 'Murder'),
(2, 'Homicide'),
(3, 'Rape'),
(4, 'Drug Related Incident (RA 9165)'),
(5, 'Physical Injuries'),
(6, 'Robbery'),
(7, 'Theft'),
(8, 'Carnapping'),
(9, 'Motornapping'),
(10, 'VTA (Vehicular Traffic Accident)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crimes`
--
ALTER TABLE `crimes`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crimes`
--
ALTER TABLE `crimes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;