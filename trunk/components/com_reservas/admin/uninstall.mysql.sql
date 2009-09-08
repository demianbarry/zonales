TRUNCATE TABLE `#__zonales_menu_bak`;
TRUNCATE TABLE `#__zonales_cp2tipotag_bak`;
TRUNCATE TABLE `#__zonales_tipotag_bak`;

INSERT INTO `#__zonales_menu_bak` (`id`,`menu_id`,`field_id`, `value_id`,`title`)
	SELECT `m`.`id`, `m`.`menu_id`, `m`.`field_id`, `m`.`value_id`, `m`.`title` FROM `#__zonales_menu` `m`
	ON DUPLICATE KEY UPDATE `id` = `m`.`id`, `menu_id` = `m`.`menu_id`, `field_id` = `m`.`field_id`, `value_id` = `m`.`value_id`, `title` = `m`.`title`;

INSERT INTO `#__zonales_cp2tipotag_bak` (`id`,`tipo_id`,`field_id`)
	SELECT `id`, `tipo_id`, `field_id` FROM `#__zonales_cp2tipotag` `mb`
	ON DUPLICATE KEY UPDATE `id` = `mb`.`id`, `tipo_id` = `mb`.`tipo_id`, `field_id` = `mb`.`field_id`;

INSERT INTO `#__zonales_tipotag_bak` (`id`,`tipo`)
	SELECT `id`, `tipo` FROM `#__zonales_tipotag` `mb`
	ON DUPLICATE KEY UPDATE `id` = `mb`.`id`, `tipo` = `mb`.`tipo`;

DROP TABLE IF EXISTS `#__zonales_menu`;
DROP TABLE IF EXISTS `#__zonales_cp2tipotag`;
DROP TABLE IF EXISTS `#__zonales_tipotag`;