DROP TABLE IF EXISTS `#__aapu_attribute_class` ;
DROP TABLE IF EXISTS `#__aapu_data_types` ;
DROP TABLE IF EXISTS `#__aapu_attributes` ;
DROP TABLE IF EXISTS `#__aapu_attribute_entity` ;

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
  `canceled` TINYINT(1) NULL ,
  `required` TINYINT(1) NOT NULL DEFAULT 0 ,
  `published` TINYINT(1) NOT NULL DEFAULT 1 ,
  `validator` VARCHAR(255) NULL ,
  `data_type_id` INT NOT NULL ,
  `attribute_class_id` INT(15) UNSIGNED NOT NULL ,
  `values_list` TINYTEXT NULL ,
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

INSERT INTO `#__aapu_attribute_class` (`id`, `name`, `label`, `description`) VALUES
(1, 'usr_harddata', 'Hard Data', 'Los datos agrupados dentro de esta categoría se cargan con el sistema y no pueden borrarse.');

INSERT INTO `#__aapu_data_types` (`id`, `label`, `description`, `render`) VALUES
(1, 'TEXT', 'Campo de texto simple', 'render_for_TEXT_data_type.php'),
(2, 'INT', 'Campo que contiene Numeros enteros', 'render_for_INT_data_type.php'),
(3, 'BOOLEAN', 'Campo que permite ingresar un valor lógico', 'render_for_BOOLEAN_data_type.php'),
(4, 'DATE', 'Fechas', 'render_for_DATE_data_type.php'),
(5, 'MODULE', 'Muestra módulos', 'render_for_MODULE_data_type.php'),
(6, 'SEX', 'Tipo de dato que indica el sexo de una persona', 'render_for_SEX_data_type.php'),
(7, 'DOUBLE', 'Dato decimal', 'render_for_DOUBLE_data_type.php'),
(8, 'COMBOBOX', 'Combobox', 'render_for_COMBOBOX_data_type.php'),
(9, 'LISTBOX', 'Listbox', 'render_for_LISTBOX_data_type.php'),
(10, 'MULTI_LISTBOX', 'Listbox de múltiple selección', 'render_for_MULTI_LISTBOX_data_type.php');

INSERT INTO `#__aapu_attributes` (`id`, `name`, `label`, `description`, `comments`, `from`, `to`, `canceled`, `required`, `published`, `validator`, `data_type_id`, `attribute_class_id`, `values_list`) VALUES
(1, 'sex', 'Sexo', 'Sexo del usuario', '', '2010-04-08', '0000-00-00', 0, 1, 1, 'validator_for_SEX_attributes.php', 6, 1, null),
(2, 'birthday', 'Fecha de Nacimiento', 'Fecha de Nacimiento', '', '2010-04-09', '0000-00-00', 0, 1, 1, 'validator_for_DATES_attributes.php', 4, 1, null),
(3, 'zonal', 'Zonal de Preferencia', 'Zonal de Preferencia del usuario', '', '2010-05-04', '0000-00-00', 0, 1, 1, '', 8, 1, 'SELECT v.id, v.label FROM jos_custom_properties_values v WHERE v.field_id = (SELECT f.id FROM jos_custom_properties_fields f WHERE f.name = "root_zonales") AND v.name LIKE "bue\\_%";');
