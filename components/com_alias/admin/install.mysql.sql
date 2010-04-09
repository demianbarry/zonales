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
  `prefix` varchar(50) NOT NULL,
  `suffix` varchar(50) NOT NULL,
  `required_input` varchar(255) default NULL,
  `apikey` varchar(255) default NULL,
  `secretkey` varchar(255) default NULL,
  `callback_parameters` varchar(255) default NULL,
  `enabled` tinyint(1) not null default 1,
  PRIMARY KEY  (`id`),
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
INSERT INTO `#__protocol_types`(name,functionname) VALUES  ('OpenID','openid'),
 ('Twitter OAuth','twitterauth'),
 ('Facebook Connect','facebookconnect'),
 ('Microsoft Passport','liveid'),
 ('Email',''),
 ('Tradicional','tradicional');
UNLOCK TABLES;



LOCK TABLES `#__groups` WRITE;
INSERT INTO `#__groups` VALUES (3,'Guest');
UNLOCK TABLES;


LOCK TABLES `#__providers` WRITE;
INSERT INTO `#__providers` VALUES  (1,'Google','https://www.google.com/accounts/o8/id',NULL,1,NULL,NULL,'images/login/google.png',NULL,'',0,'','',':::',1,4,NULL,NULL,NULL),
 (2,'Yahoo','me.yahoo.com',NULL,1,NULL,NULL,'images/login/yahoo.png',NULL,'',0,'','',':::',2,2,NULL,NULL,NULL),
 (3,'OpenID',NULL,NULL,1,NULL,NULL,'images/login/openid.png','mod_openid','',0,'','','text:username:ZONALES_PROVIDER_ENTER_ID:',3,0,NULL,NULL,NULL),
 (4,'Zonales',NULL,NULL,6,NULL,NULL,'images/login/zonales.png','mod_login','',3,'','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:/password:password:ZONALES_PROVIDER_ENTER_PASSWORD:',4,3,NULL,NULL,NULL),
 (5,'ClaimID',NULL,NULL,1,NULL,NULL,'images/login/claimid.png','mod_openid','',0,'http://claimid.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',5,0,NULL,NULL,NULL),
 (6,'MyOpenID',NULL,NULL,1,NULL,NULL,'images/login/myopenid.png','mod_openid','',0,'','.myopenid.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',6,1,NULL,NULL,NULL),
 (7,'LiveJournal',NULL,NULL,1,NULL,NULL,'images/login/livejournal.png','mod_openid','',0,'','.livejournal.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',7,0,NULL,NULL,NULL);
INSERT INTO `#__providers` VALUES  (8,'Flickr',NULL,NULL,1,NULL,NULL,'images/login/flickr.png','mod_openid','',0,'www.flickr.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',8,0,NULL,NULL,NULL),
 (9,'MySpace',NULL,NULL,1,NULL,NULL,'images/login/myspace.jpg','mod_openid','',0,'www.myspace.com/','','text:username:ZONALES_PROVIDER_ENTER_PROFILE_NAME:',9,0,NULL,NULL,NULL),
 (10,'Aol',NULL,NULL,1,NULL,NULL,'images/login/aol.png','mod_openid','',0,'openid.aol.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',10,0,NULL,NULL,NULL),
 (11,'Orange','orange.fr',NULL,1,NULL,NULL,'images/login/orange.png',NULL,'',0,'','',':::',11,0,NULL,NULL,NULL),
 (12,'Wordpress',NULL,NULL,1,NULL,NULL,'images/login/wordpress.png','mod_openid','',0,'','.wordpress.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',12,0,NULL,NULL,NULL),
 (13,'Verisign',NULL,NULL,1,NULL,NULL,'images/login/verisign.png','mod_openid','',0,'','.pip.verisignlabs.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',13,0,NULL,NULL,NULL),
 (14,'Facebook',NULL,NULL,3,NULL,NULL,'images/login/facebook.png',NULL,'',0,'','','button:clickme:ZONALES_PROVIDER_CONNECT:FB.Connect.requireSession(); return false;',0,23,'cambiarme','cambiarme','next,session');
INSERT INTO `#__providers` VALUES  (15,'Twitter',NULL,NULL,2,NULL,NULL,'images/login/twitter.jpg',NULL,'',0,'','',':::',0,0,'cambiarme','cambiarme','oauth_token'),
 (16,'Microsoft_OpenID',NULL,NULL,1,NULL,NULL,'images/login/liveid.png',NULL,'',0,'http://openid.live-int.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',0,0,NULL,NULL,NULL),
 (17,'Microsoft','http://login.live.com/wlogin.srf','alg=wsignin1.0',5,NULL,NULL,'images/login/passport.gif',NULL,'',0,'','',':::',0,0,'cambiarme','cambiarme','action');
UNLOCK TABLES;
