--
-- Install SQL Script
--
-- @version		$Id: install.sql 1439 2009-08-16 12:41:13Z mostafa.muhammad $
-- @package		Joomla
-- @subpackage	JWF
-- @copyright	Copyright (C) 2008 Mostafa Muhammad. All rights reserved.
-- @license		GNU/GPL
--


--
-- Table structure for table `#__jwf_fields`
--

DROP TABLE IF EXISTS `#__jwf_fields`;
CREATE TABLE IF NOT EXISTS `#__jwf_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `sid` int(11) unsigned NOT NULL DEFAULT '0',
  `iid` int(11) NOT NULL,
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(200) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `#__jwf_history`
--

DROP TABLE IF EXISTS `#__jwf_history`;
CREATE TABLE IF NOT EXISTS `#__jwf_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL,
  `station` longtext NOT NULL,
  `iid` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `type` varchar(200) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `#__jwf_stations`
--

DROP TABLE IF EXISTS `#__jwf_stations`;
CREATE TABLE IF NOT EXISTS `#__jwf_stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `task` text NOT NULL,
  `allocatedTime` int(11) NOT NULL,
  `group` int(11) unsigned NOT NULL DEFAULT '0',
  `fields` text NOT NULL,
  `activeHooks` longtext NOT NULL,
  `activeValidations` text NOT NULL,
  `order` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `#__jwf_steps`
--

DROP TABLE IF EXISTS `#__jwf_steps`;
CREATE TABLE IF NOT EXISTS `#__jwf_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL,
  `iid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `current` int(1) unsigned NOT NULL DEFAULT '0',
  `version` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `#__jwf_workflows`
--

DROP TABLE IF EXISTS `#__jwf_workflows`;
CREATE TABLE IF NOT EXISTS `#__jwf_workflows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `component` varchar(255) NOT NULL,
  `acl` varchar(255) NOT NULL,
  `admin_gid` int(24) NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) TYPE=InnoDB;
