
CREATE  TABLE IF NOT EXISTS `#__aapu_attribute_class` (
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(90) NOT NULL ,
  `label` VARCHAR(90) NOT NULL ,
  `description` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM;

CREATE  TABLE IF NOT EXISTS `#__aapu_data_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NOT NULL ,
  `description` VARCHAR(255) NULL ,
  `render` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM;

CREATE  TABLE IF NOT EXISTS `#__aapu_attributes` (
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(90) NOT NULL ,
  `label` VARCHAR(90) NOT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `comments` VARCHAR(255) NULL DEFAULT NULL ,
  `from` DATE NOT NULL ,
  `to` DATE NULL DEFAULT '0000-00-00' ,
  `canceled` CHAR(1) NULL DEFAULT 'F' ,
  `required` CHAR(1) NOT NULL DEFAULT 'F' ,
  `validator` VARCHAR(255) NULL ,
  `data_type_id` INT NOT NULL ,
  `attribute_class_id` INT(15) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_attributes_data_types` (`data_type_id` ASC) ,
  INDEX `fk_attributes_attribute_class1` (`attribute_class_id` ASC) )
ENGINE = MyISAM;

CREATE  TABLE IF NOT EXISTS `#__aapu_attribute_entity` (
  `id` INT(15) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `value` VARCHAR(255) NULL DEFAULT NULL ,
  `value_int` INT(11) NULL DEFAULT NULL ,
  `value_double` DOUBLE NULL DEFAULT NULL ,
  `value_date` DATE NULL DEFAULT NULL ,
  `value_boolean` CHAR(1) NULL DEFAULT NULL ,
  `object_id` INT(15) UNSIGNED NULL DEFAULT NULL ,
  `object_type` VARCHAR(15) NULL DEFAULT NULL ,
  `object_name` VARCHAR(45) NULL DEFAULT NULL ,
  `attribute_id` INT(15) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_attribute_entity_attributes1` (`attribute_id` ASC) )
ENGINE = MyISAM;
