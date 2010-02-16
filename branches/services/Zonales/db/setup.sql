-- Creacion y/o alteracion de tablas

-- -----------------------------------------------------
-- Table `zonales`.`jos_protocol_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zonales`.`jos_protocol_types` ;

CREATE  TABLE IF NOT EXISTS `zonales`.`jos_protocol_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `zonales`.`jos_providers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zonales`.`jos_providers` ;

CREATE  TABLE IF NOT EXISTS `zonales`.`jos_providers` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `discovery_url` VARCHAR(255) NULL DEFAULT NULL ,
  `parameters` VARCHAR(255) NULL DEFAULT NULL ,
  `protocol_type_id` INT NOT NULL ,
  `description` VARCHAR(45) NULL DEFAULT NULL ,
  `observation` VARCHAR(45) NULL DEFAULT NULL ,
  `icon_url` VARCHAR(255) NOT NULL ,
  `module` VARCHAR(50) NULL DEFAULT NULL ,
  `function` VARCHAR(255) NULL DEFAULT NULL ,
  `access` INT NOT NULL,
  `prefix` VARCHAR(100) NULL DEFAULT NULL ,
  `suffix` VARCHAR(100) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_jos_providers_jos_protocol_type` (`protocol_type_id` ASC) ,
  CONSTRAINT `fk_jos_providers_jos_protocol_type`
    FOREIGN KEY (`protocol_type_id` )
    REFERENCES `zonales`.`jos_protocol_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION),
CONSTRAINT `fk_access_groups`
    FOREIGN KEY (`access` )
    REFERENCES `zonales`.`jos_groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `zonales`.`jos_alias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zonales`.`jos_alias` ;

CREATE  TABLE IF NOT EXISTS `zonales`.`jos_alias` (
  `user_id` INT NOT NULL ,
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `provider_id` INT NOT NULL ,
  `association_date` DATE NOT NULL ,
  `block` TINYINT(1) NOT NULL ,
  `activation` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_jos_aliases_jos_providers` (`provider_id` ASC) ,
  INDEX `fk_jos_aliases_jos_users` (`user_id` ASC) ,
  INDEX `fk_jos_alias_estados_alias` (`estado_id` ASC) ,
  CONSTRAINT `fk_jos_aliases_jos_providers`
    FOREIGN KEY (`provider_id` )
    REFERENCES `zonales`.`jos_providers` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_jos_aliases_jos_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `zonales`.`jos_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `jos_users`
    ADD CONSTRAINT `unique_user_email` UNIQUE INDEX `email`;
    ADD COLUMN `email2` VARCHAR(100)  NOT NULL AFTER `email`;
    ADD COLUMN `birthdate` date  NOT NULL AFTER `params`,
    ADD COLUMN `sex` CHAR(1)  NOT NULL AFTER `birthdate`;


-- carga de datos

insert into zonales.jos_protocol_types(name) values 
                ("OpenID"),
                ("Twitter OAuth"),
                ("Facebook Connect"),
                ("MyspaceID"),
                ("Windows Live ID"),
                ("Email");

insert into zonales.jos_groups(id,name) values (3,"Guest");

insert into zonales.jos_providers(name,discovery_url,protocol_type_id,icon_url,access,module,prefix,suffix) values
                ("Google","https://www.google.com/accounts/o8/id",1,"images/login/google.png",0,null,"",""),
                ("Yahoo","me.yahoo.com",1,"images/login/yahoo.png",0,null,"",""),
                ("OpenID",null,1,"images/login/openid.png",0,"mod_openid","",""),
                ("Zonales",null,7,"images/login/zonales.png",3,"mod_login","",""),
                ("ClaimID",null,1,"images/login/claimid.png",0,"mod_openid","http://claimid.com/",""),
                ("MyOpenID",null,1,"images/login/myopenid.png",0,"mod_openid","",".myopenid.com");
