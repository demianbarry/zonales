CREATE TABLE IF NOT EXISTS `#__aard_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ad_name` varchar(255) NOT NULL,
  `ad_img1` varchar(255) NOT NULL DEFAULT '',
  `ad_img1small` varchar(255) NOT NULL DEFAULT '',
  `ad_img2` varchar(255) NOT NULL DEFAULT '',
  `ad_img2small` varchar(255) NOT NULL DEFAULT '',
  `ad_desc` varchar(2000) NOT NULL,
  `ad_state` text NOT NULL,
  `ad_price` int(10) NOT NULL,
  `ad_location` varchar(100) NOT NULL,
  `ad_latitude` varchar(100) NOT NULL,
  `ad_longitude` varchar(100) NOT NULL,
  `ad_post` varchar(10) NOT NULL,
  `ad_delivery` varchar(100) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_tel` varchar(30) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_address` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_expiration` date NOT NULL,
  `published` tinyint(1) NOT NULL,
  `impressions` int(11) NOT NULL DEFAULT 0,
  `emailed` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__aard_ads_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `small` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__aard_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_img` varchar(255) NOT NULL,
  `cat_desc` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__aard_email` (
  `id` smallint(1) NOT NULL DEFAULT '1',
  `subject` varchar(255) NOT NULL,
  `fromname` varchar(255) NOT NULL,
  `fromemail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__aard_email` (`id`, `subject`, `fromname`, `fromemail`) VALUES
(1, 'Ad Expiration', 'Admin', 'email@example.com');

INSERT INTO `#__aard_cats` (`id`, `cat_name`, `cat_img`, `cat_desc`, `ordering`, `published`) VALUES
(1, 'Equipment', '', 'This is the section for all other items', 0, 1),
(2, 'Vehicles', '', 'This is the section for vehicles of all types', 0, 1),
(3, 'Electronics', '', 'This is the category for electronic items', 0, 1);

CREATE TABLE IF NOT EXISTS `#__aard_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `days_shown` int(3) NOT NULL,
  `prune` int(11) NOT NULL DEFAULT '60',
  `font_color` varchar(7) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT '&pound;',
  `distance` smallint(1) NOT NULL DEFAULT '0',
  `ad_state_font` varchar(7) NOT NULL,
  `ad_detail_font` varchar(7) NOT NULL,
  `access` smallint(1) NOT NULL DEFAULT '1',
  `map` smallint(1) NOT NULL DEFAULT '0',
  `emailusers` int(1) NOT NULL DEFAULT '0',
  `catimg` int(1) NOT NULL DEFAULT '1',
  `default_image_width` int(3) NOT NULL DEFAULT '500',
  `default_image_height` int(3) NOT NULL DEFAULT '500',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `#__aard_config` (`id`, `days_shown`, `prune`, `font_color`, `currency`, `distance`, `ad_state_font`, `ad_detail_font`, `access`, `map`, `emailusers`, `catimg`) VALUES
(1, 7, 60, '#0c3a6d', '$;', 0, '#0c3a6d', '#0c3a6d', 1, 1, 0, 1);

