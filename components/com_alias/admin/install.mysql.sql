ALTER TABLE `#__users`
	ADD COLUMN `email2` VARCHAR(100) AFTER `email`,
	ADD COLUMN `birthdate` date  NOT NULL AFTER `params`,
	ADD COLUMN `sex` char(1)  NOT NULL AFTER `birthdate`;

DROP TABLE IF EXISTS `#__protocol_types`;
CREATE TABLE  `#__protocol_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `function` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__providers`;
CREATE TABLE  `#__providers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `discovery_url` varchar(255) default NULL,
  `parameters` varchar(255) default NULL,
  `protocol_type_id` int(11) NOT NULL,
  `description` varchar(45) default NULL,
  `observation` varchar(45) default NULL,
  `icon_url` varchar(255) NOT NULL,
  `access` int(11) NOT NULL,
  `prefix` varchar(50) NOT NULL default "",
  `suffix` varchar(50) NOT NULL default "",
  `required_input` varchar(255) default "::",
  `apikey` varchar(255) default null,
  `secretkey` varchar(255) default null,
  PRIMARY KEY  (`id`),
unique (`name`),
  KEY `fk_#__providers_#__protocol_type` (`protocol_type_id`),
  CONSTRAINT `fk_#__providers_#__protocol_type` FOREIGN KEY (`protocol_type_id`) REFERENCES `#__protocol_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Definition of table `#__alias`
--

DROP TABLE IF EXISTS `#__alias`;
CREATE TABLE  `#__alias` (
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `association_date` date NOT NULL,
  `block` tinyint(1) NOT NULL,
  `activation` varchar(100) NOT NULL,
unique (`name`),
  PRIMARY KEY  USING BTREE (`id`),
  KEY `fk_#__aliases_#__providers` (`provider_id`),
  CONSTRAINT `fk_#__aliases_#__users` FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`);
  CONSTRAINT `fk_#__aliases_#__providers` FOREIGN KEY (`provider_id`) REFERENCES `#__providers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `#__protocol_types` WRITE;
INSERT INTO `#__protocol_types`(name,functionname) VALUES  ('OpenID',''),
 ('Twitter OAuth',''),
 ('Facebook Connect',''),
 ('Microsoft Passport',''),
 ('Email',''),
 ('Tradicional','');
UNLOCK TABLES;



LOCK TABLES `#__groups` WRITE;
INSERT INTO `#__groups` VALUES (3,'Guest');
UNLOCK TABLES;


LOCK TABLES `#__providers` WRITE;
INSERT INTO `#__providers` VALUES('name','discovery_url','parameters','protocol_type_id','description','observation','icon_url','access','prefix','suffix','required_input')
('Google','https://www.google.com/accounts/o8/id',NULL,1,NULL,NULL,'images/login/google.png',0,'','','::'),
 ('Yahoo','me.yahoo.com',NULL,1,NULL,NULL,'images/login/yahoo.png',0,'','','::'),
 ('OpenID',NULL,NULL,1,'','','images/login/openid.png',0,'','','text:username:ZONALES_PROVIDER_ENTER_ID'),
 ('Zonales',NULL,NULL,6,NULL,'','images/login/zonales.png',3,'','','text:username:ZONALES_PROVIDER_ENTER_USERNAME;password:password:ZONALES_PROVIDER_ENTER_PASSWORD'),
 ('ClaimID',NULL,NULL,1,NULL,NULL,'images/login/claimid.png',0,'http://claimid.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME'),
 ('MyOpenID',NULL,NULL,1,NULL,NULL,'images/login/myopenid.png',0,'','.myopenid.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME'),
 ('LiveJournal',NULL,NULL,1,NULL,NULL,'images/login/livejournal.png',0,'','.livejournal.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME'),
 ('Flickr',NULL,NULL,1,NULL,NULL,'images/login/flickr.png',0,'www.flickr.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME'),
 ('MySpace',NULL,'',1,NULL,NULL,'images/login/myspace.jpg',0,'www.myspace.com/','','text:username:ZONALES_PROVIDER_ENTER_PROFILE_NAME'),
 ('Aol',NULL,NULL,1,NULL,NULL,'images/login/aol.png',0,'openid.aol.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME'),
 ('Orange','orange.fr',NULL,1,NULL,NULL,'images/login/orange.png',0,'','','::'),
('Wordpress',NULL,NULL,1,NULL,NULL,'images/login/wordpress.png',0,'','.wordpress.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME'),
 ('Verisign',NULL,NULL,1,NULL,NULL,'images/login/verisign.png',0,'','.pip.verisignlabs.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME');
UNLOCK TABLES;
