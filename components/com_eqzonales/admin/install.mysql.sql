SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `services`.`eq`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__eqzonales_eq` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre del Ecualizador.' ,
  `descripcion` VARCHAR(512) NULL COMMENT 'Descripción del Ecualizador. Preferentemente que objetivos tematicos tiene.' ,
  `observaciones` VARCHAR(512) NULL COMMENT 'Cualquier tipo de observaciones.' ,
  `user_id` INT NULL COMMENT 'Usuario Joomla al cual pertenece este ecualizador.' ,
  `solrquery_bq` TINYTEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Ecualizadores de Usuario.';


-- -----------------------------------------------------
-- Table `services`.`eq_banda`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `#__eqzonales_banda` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `valor` VARCHAR(255) NOT NULL COMMENT 'Campo desnormalizado con el valor a mostrar en la banda del ecualizador gráfico. Se pude hacer igual al nombre del tag (value) con el que se relaciona.' ,
  `peso` INT(11) NOT NULL DEFAULT 0 COMMENT 'Peso asignado por el usuario a este tag (value).' ,
  `jos_customproperties_value_id` INT NOT NULL COMMENT 'tag (value) asociado en la tabla jos_customproperties_values' ,
  `eq_id` INT NOT NULL COMMENT 'Ecualizador al que pertenece este atributo (o banda)' ,
  `default` TINYINT(1) NOT NULL DEFAULT false ,
  `active` TINYINT(1) NOT NULL DEFAULT false ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_eq_atributos_eq1` (`eq_id` ASC) ,
  UNIQUE INDEX `uq_atributo_eq` (`jos_customproperties_value_id` ASC, `eq_id` ASC) ,
  CONSTRAINT `fk_eq_atributos_eq1`
    FOREIGN KEY (`eq_id` )
    REFERENCES `#__eqzonales_eq` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Bandas que componen el ecualizador.';

CREATE TABLE IF NOT EXISTS `#__eqzonales_eq_bak` LIKE `#__eqzonales_eq`;
CREATE TABLE IF NOT EXISTS `#__eqzonales_banda_bak` LIKE `#__eqzonales_banda`;

INSERT INTO `#__eqzonales_eq` (`id`,`nombre`,`descripcion`, `observaciones`,`user_id`, `solrquery_bq`)
	SELECT `e`.`id`, `e`.`nombre`, `e`.`descripcion`, `e`.`observaciones`, `e`.`user_id`, `e`.`solrquery_bq` FROM `#__eqzonales_eq_bak` `e`
	ON DUPLICATE KEY UPDATE `id` = `e`.`id`, `nombre` = `e`.`nombre`, `descripcion` = `e`.`descripcion`, `observaciones` = `e`.`observaciones`, `user_id` = `e`.`user_id`, `solrquery_bq` = `e`.`solrquery_bq`;

INSERT INTO `#__eqzonales_banda` (`id`,`valor`,`peso`, `jos_customproperties_value_id`,`eq_id`, `default`, `active`)
	SELECT `e`.`id`, `e`.`valor`, `e`.`peso`, `e`.`jos_customproperties_value_id`, `e`.`eq_id`, `e`.`default`, `e`.`active` FROM `#__eqzonales_banda_bak` `e`
	ON DUPLICATE KEY UPDATE `id` = `e`.`id`, `valor` = `e`.`valor`, `peso` = `e`.`peso`, `jos_customproperties_value_id` = `e`.`jos_customproperties_value_id`, `eq_id` = `e`.`eq_id`, `default` = `e`.`default`, `active` = `e`.`active`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;