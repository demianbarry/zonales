-- IF NOT EXISTS(
--	SELECT * FROM information_schema.COLUMNS
--	WHERE COLUMN_NAME='birthdate' AND TABLE_NAME='#__users'
--	)
--	THEN
--		ALTER TABLE `#__users`
--                    ADD COLUMN `email2` VARCHAR(100) AFTER `email`,
--                    ADD COLUMN `birthdate` date  NOT NULL AFTER `params`,
--                    ADD COLUMN `sex` char(1)  NOT NULL AFTER `birthdate`;

-- END IF;

DROP TABLE IF EXISTS `#__alias`;
DROP TABLE IF EXISTS `#__providers`;
DROP TABLE IF EXISTS `#__protocol_types`;

CREATE TABLE  `#__protocol_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `function` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
  CONSTRAINT `fk_#__aliases_#__providers` FOREIGN KEY (`provider_id`) REFERENCES `#__providers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `#__protocol_types` WRITE;
INSERT INTO `#__protocol_types`(name,function) VALUES  ('OpenID','openid'),
 ('Twitter OAuth','twitteroauth'),
 ('Facebook Connect','facebookconnect'),
 ('Microsoft Passport','liveid'),
 ('Email',''),
 ('Tradicional','tradicional');
UNLOCK TABLES;



LOCK TABLES `#__groups` WRITE;
REPLACE INTO `#__groups` set id=3, name="Guest";
UNLOCK TABLES;


LOCK TABLES `#__providers` WRITE;
INSERT INTO `#__providers` VALUES  (1,'Google','https://www.google.com/accounts/o8/id',NULL,1,NULL,NULL,'images/login/google.png',0,'','',':::',NULL,NULL,NULL,1),
 (2,'Yahoo','me.yahoo.com',NULL,1,NULL,NULL,'images/login/yahoo.png',0,'','',':::',NULL,NULL,NULL,1),
 (3,'OpenID',NULL,NULL,1,NULL,NULL,'images/login/openid.png',0,'','','text:username:ZONALES_PROVIDER_ENTER_ID:',NULL,NULL,NULL,1),
 (4,'Zonales',NULL,NULL,6,NULL,NULL,'images/login/zonales.png',3,'','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:/password:password:ZONALES_PROVIDER_ENTER_PASSWORD:',NULL,NULL,NULL,1),
 (5,'ClaimID',NULL,NULL,1,NULL,NULL,'images/login/claimid.png',0,'http://claimid.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1),
 (6,'MyOpenID',NULL,NULL,1,NULL,NULL,'images/login/myopenid.png',0,'','.myopenid.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1),
 (7,'LiveJournal',NULL,NULL,1,NULL,NULL,'images/login/livejournal.png',0,'','.livejournal.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1),
 (8,'Flickr',NULL,NULL,1,NULL,NULL,'images/login/flickr.png',0,'www.flickr.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1);
INSERT INTO `#__providers` VALUES  (9,'MySpace',NULL,NULL,1,NULL,NULL,'images/login/myspace.jpg',0,'www.myspace.com/','','text:username:ZONALES_PROVIDER_ENTER_PROFILE_NAME:',NULL,NULL,NULL,1),
 (10,'Aol',NULL,NULL,1,NULL,NULL,'images/login/aol.png',0,'openid.aol.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1),
 (11,'Orange','orange.fr',NULL,1,NULL,NULL,'images/login/orange.png',0,'','',':::',NULL,NULL,NULL,1),
 (12,'Wordpress',NULL,NULL,1,NULL,NULL,'images/login/wordpress.png',0,'','.wordpress.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1),
 (13,'Verisign',NULL,NULL,1,NULL,NULL,'images/login/verisign.png',0,'','.pip.verisignlabs.com','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1),
 (14,'Facebook',NULL,NULL,3,NULL,NULL,'images/login/facebook.png',0,'','','button:clickme:ZONALES_PROVIDER_CONNECT:FB.Connect.requireSession(); return false;','cambiarme','cambiarme','next,session',1),
 (15,'Twitter',NULL,NULL,2,NULL,NULL,'images/login/twitter.jpg',0,'','',':::','cambiarme','cambiarme','oauth_token',1);
INSERT INTO `#__providers` VALUES  (16,'Microsoft_OpenID',NULL,NULL,1,NULL,NULL,'images/login/liveid.png',0,'http://openid.live-int.com/','','text:username:ZONALES_PROVIDER_ENTER_USERNAME:',NULL,NULL,NULL,1),
 (17,'Microsoft','http://login.live.com/wlogin.srf','alg=wsignin1.0',5,NULL,NULL,'images/login/passport.gif',0,'','',':::','cambiarme','cambiarme','action',1);
UNLOCK TABLES;
