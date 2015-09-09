CREATE TABLE IF NOT EXISTS `prefix_usergbook` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sid` INT(11) NOT NULL DEFAULT '0',
    `uid` INT(11) NOT NULL DEFAULT '0',
    `txt` TEXT NOT NULL,
    `datetime` INT(20) NULL DEFAULT NULL,
PRIMARY KEY (`id`)
)
ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `prefix_usergbook_koms` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `gbid` INT(11) NOT NULL DEFAULT '0',
    `uid` INT(11) NOT NULL DEFAULT '0',
    `txt` TEXT NOT NULL,
    `datetime` int(20) DEFAULT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `prefix_friendscheck` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid` INT(11) NOT NULL,
    `fid` INT(11) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `prefix_friends` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid` INT(11) NOT NULL,
    `fid` INT(11) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `prefix_userblock` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `uid` INT(11) NOT NULL,
    `bid` INT(11) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE=MyISAM;

UPDATE `prefix_config` SET `wert` = '145' WHERE `schl` = 'Fabreite';
UPDATE `prefix_config` SET `wert` = '145' WHERE `schl` = 'Fahohe';
UPDATE `prefix_config` SET `wert` = '117760' WHERE `schl` = 'Fasize';

ALTER TABLE `prefix_user` ADD `titelbild` varchar(100) NOT NULL DEFAULT '' AFTER `avatar`;