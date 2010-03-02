ALTER TABLE `#__users`
    ADD COLUMN `email2` VARCHAR(100)  NOT NULL AFTER `email`;
    ADD COLUMN `birthdate` date  NOT NULL AFTER `params`,
    ADD COLUMN `sex` CHAR(1)  NOT NULL AFTER `birthdate`;