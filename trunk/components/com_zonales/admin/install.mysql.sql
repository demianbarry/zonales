CREATE TABLE `#__zonales_menu` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `menu_id` INTEGER  NOT NULL,
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