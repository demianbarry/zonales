TRUNCATE TABLE `#__eqzonales_banda_bak`;
TRUNCATE TABLE `#__eqzonales_eq_bak`;

INSERT INTO `#__eqzonales_eq_bak` (`id`,`nombre`,`descripcion`, `observaciones`,`user_id`, `solrquery_bq`)
	SELECT `e`.`id`, `e`.`nombre`, `e`.`descripcion`, `e`.`observaciones`, `e`.`user_id`, `e`.`solrquery_bq` FROM `#__eqzonales_eq` `e`
	ON DUPLICATE KEY UPDATE `id` = `e`.`id`, `nombre` = `e`.`nombre`, `descripcion` = `e`.`descripcion`, `observaciones` = `e`.`observaciones`, `user_id` = `e`.`user_id`, `solrquery_bq` = `e`.`solrquery_bq`;

INSERT INTO `#__eqzonales_banda_bak` (`id`,`valor`,`peso`, `jos_customproperties_value_id`,`eq_id`, `default`, `active`)
	SELECT `e`.`id`, `e`.`valor`, `e`.`peso`, `e`.`jos_customproperties_value_id`, `e`.`eq_id`, `e`.`default`, `e`.`active` FROM `#__eqzonales_banda` `e`
	ON DUPLICATE KEY UPDATE `id` = `e`.`id`, `valor` = `e`.`valor`, `peso` = `e`.`peso`, `jos_customproperties_value_id` = `e`.`jos_customproperties_value_id`, `eq_id` = `e`.`eq_id`, `default` = `e`.`default`, `active` = `e`.`active`;

DROP TABLE IF EXISTS `#__eqzonales_banda`;
DROP TABLE IF EXISTS `#__eqzonales_eq`;