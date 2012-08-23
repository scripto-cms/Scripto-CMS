<?
/*
Установочный файл для модуля комментарии
*/

$install_sql[]="CREATE TABLE `%COMMENTS%` (
`id_comment` BIGINT NOT NULL AUTO_INCREMENT ,
`id_sub_comment` BIGINT NOT NULL ,
`id_object` BIGINT NOT NULL ,
`type` VARCHAR( 250 ) NOT NULL ,
`new` INT NOT NULL default 1,
`nickname` VARCHAR( 250 ) NOT NULL ,
`e-mail` VARCHAR( 250 ) NOT NULL ,
`url` VARCHAR( 250 ) NOT NULL ,
`uniq_key` VARCHAR( 250 ) NOT NULL ,
`content` LONGTEXT,
`date` TIMESTAMP NOT NULL default current_timestamp,
PRIMARY KEY ( `id_comment` )
);";
$install_sql[]="INSERT INTO `%static_modules%` values('comments',1)";
?>