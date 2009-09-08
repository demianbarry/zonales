-- tablas temporarias
CREATE TABLE IF NOT EXISTS `#__zonales_menu_bak` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `menu_id` INTEGER  NOT NULL,
  `field_id` INTEGER NOT NULL,
  `value_id` INTEGER  NOT NULL,
  `title` VARCHAR(255)  NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `title_index`(`title`)
)
ENGINE = MyISAM;

CREATE TABLE IF NOT EXISTS `#__zonales_cp2tipotag_bak` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `tipo_id` INTEGER  NOT NULL,
  `field_id` INTEGER  NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `tipo_index`(`tipo_id`)
)
ENGINE = MyISAM;

CREATE TABLE IF NOT EXISTS `#__zonales_tipotag_bak` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `tipo_index`(`tipo`)
)
ENGINE = MyISAM;


-- tablas primarias
CREATE TABLE `#__zonales_menu` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `menu_id` INTEGER  NOT NULL,
  `field_id` INTEGER NOT NULL,
  `value_id` INTEGER  NOT NULL,
  `title` VARCHAR(255)  NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `title_index`(`title`)
)
ENGINE = MyISAM;

CREATE TABLE `#__zonales_cp2tipotag` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `tipo_id` INTEGER  NOT NULL,
  `field_id` INTEGER  NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `tipo_index`(`tipo_id`)
)
ENGINE = MyISAM;

CREATE TABLE `#__zonales_tipotag` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `tipo_index`(`tipo`)
)
ENGINE = MyISAM;

INSERT INTO `#__zonales_menu` (`id`,`menu_id`,`field_id`,`value_id`,`title`)
	SELECT `id`, `menu_id`, `field_id`, `value_id`, `title` FROM `#__zonales_menu_bak` `mb`
	ON DUPLICATE KEY UPDATE `id` = `mb`.`id`, `menu_id` = `mb`.`menu_id`, `field_id` = `mb`.`field_id`, `value_id` = `mb`.`value_id`, `title` = `mb`.`title`;

INSERT INTO `#__zonales_cp2tipotag` (`id`,`tipo_id`,`field_id`)
	SELECT `id`, `tipo_id`, `field_id` FROM `#__zonales_cp2tipotag_bak` `mb`
	ON DUPLICATE KEY UPDATE `id` = `mb`.`id`, `tipo_id` = `mb`.`tipo_id`, `field_id` = `mb`.`field_id`;

INSERT INTO `#__zonales_tipotag` (`id`,`tipo`)
	SELECT `id`, `tipo` FROM `#__zonales_tipotag_bak` `mb`
	ON DUPLICATE KEY UPDATE `id` = `mb`.`id`, `tipo` = `mb`.`tipo`;
