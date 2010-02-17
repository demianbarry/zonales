CREATE DATABASE IF NOT EXISTS zonales;
USE zonales;

ALTER TABLE `zonales`.`jos_users`
	ADD COLUMN `email2` VARCHAR(100)  NOT NULL AFTER `email`,
	ADD COLUMN `birthdate` date  NOT NULL AFTER `params`,
	ADD COLUMN `sex` char(1)  NOT NULL AFTER `birthdate`;

DROP TABLE IF EXISTS `zonales`.`jos_protocol_types`;
CREATE TABLE  `zonales`.`jos_protocol_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `functionname` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `zonales`.`jos_providers`;
CREATE TABLE  `zonales`.`jos_providers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `discovery_url` varchar(255) default NULL,
  `parameters` varchar(255) default NULL,
  `protocol_type_id` int(11) NOT NULL,
  `description` varchar(45) default NULL,
  `observation` varchar(45) default NULL,
  `icon_url` varchar(255) NOT NULL,
  `module` varchar(100) default NULL,
  `function` varchar(100) NOT NULL,
  `access` int(11) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `suffix` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
unique (`name`),
  KEY `fk_jos_providers_jos_protocol_type` (`protocol_type_id`),
  CONSTRAINT `fk_jos_providers_jos_protocol_type` FOREIGN KEY (`protocol_type_id`) REFERENCES `jos_protocol_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Definition of table `zonales`.`jos_alias`
--

DROP TABLE IF EXISTS `zonales`.`jos_alias`;
CREATE TABLE  `zonales`.`jos_alias` (
  `user_id` int(11) NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `association_date` date NOT NULL,
  `block` tinyint(1) NOT NULL,
  `activation` varchar(100) NOT NULL,
unique (`name`),
  PRIMARY KEY  USING BTREE (`id`),
  KEY `fk_jos_aliases_jos_providers` (`provider_id`),
  CONSTRAINT `fk_jos_aliases_jos_users` FOREIGN KEY (`user_id`) REFERENCES `jos_users`(`id`);
  CONSTRAINT `fk_jos_aliases_jos_providers` FOREIGN KEY (`provider_id`) REFERENCES `jos_providers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

LOCK TABLES `jos_protocol_types` WRITE;
INSERT INTO `zonales`.`jos_protocol_types`(name,functionname) VALUES  ('OpenID',''),
 ('Twitter OAuth',''),
 ('Facebook Connect',''),
 ('Microsoft Passport',''),
 ('Email',''),
 ('Tradicional','');
UNLOCK TABLES;



LOCK TABLES `jos_groups` WRITE;
INSERT INTO `zonales`.`jos_groups` VALUES (3,'Guest');
UNLOCK TABLES;


LOCK TABLES `jos_providers` WRITE;
INSERT INTO `zonales`.`jos_providers` VALUES  (1,'Google','https://www.google.com/accounts/o8/id',NULL,1,NULL,NULL,'images/login/google.png',NULL,'',0,'',''),
 (2,'Yahoo','me.yahoo.com',NULL,1,NULL,NULL,'images/login/yahoo.png',NULL,'',0,'',''),
 (3,'OpenID',NULL,NULL,1,'','','images/login/openid.png','mod_openid','',0,'',''),
 (4,'Zonales',NULL,NULL,6,NULL,'','images/login/zonales.png','mod_login','',3,'',''),
 (5,'ClaimID',NULL,NULL,1,NULL,NULL,'images/login/claimid.png','mod_openid','',0,'http://claimid.com/',''),
 (6,'MyOpenID',NULL,NULL,1,NULL,NULL,'images/login/myopenid.png','mod_openid','',0,'','.myopenid.com'),
 (7,'LiveJournal',NULL,NULL,1,NULL,NULL,'images/login/livejournal.png','mod_openid','',0,'','.livejournal.com'),
 (8,'Flickr',NULL,NULL,1,NULL,NULL,'images/login/flickr.png','mod_openid','',0,'www.flickr.com/',''),
 (9,'MySpace',NULL,'',1,NULL,NULL,'images/login/myspace.jpg','mod_openid','',0,'www.myspace.com/',''),
 (10,'Aol',NULL,NULL,1,NULL,NULL,'images/login/aol.png','mod_openid','',0,'openid.aol.com/',''),
 (11,'Orange','orange.fr',NULL,1,NULL,NULL,'images/login/orange.png',NULL,'',0,'','');
INSERT INTO `zonales`.`jos_providers` VALUES  (12,'Wordpress',NULL,NULL,1,NULL,NULL,'images/login/wordpress.png','mod_openid','',0,'','.wordpress.com'),
 (13,'Verisign',NULL,NULL,1,NULL,NULL,'images/login/verisign.png','mod_openid','',0,'','.pip.verisignlabs.com');
UNLOCK TABLES;
