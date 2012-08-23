<?
/*
Установочный файл для модуля формы
*/

$install_sql[]="CREATE TABLE `%FORMS%` (
`id_form` BIGINT NOT NULL AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`category_caption` VARCHAR( 250 ) NOT NULL ,
`caption` VARCHAR( 250 ) NOT NULL ,
`caption_admin` VARCHAR( 250 ) NOT NULL ,
`caption_mail_admin` VARCHAR( 250 ) NOT NULL ,
`caption_mail_user` VARCHAR( 250 ) NOT NULL ,
`email` VARCHAR( 250 ) NOT NULL ,
`content` LONGTEXT,
`forwardcontent` LONGTEXT,
`success_user` LONGTEXT,
`success_admin` LONGTEXT,
`date` DATE NOT NULL ,
`visible` INT NULL default 1 ,
`start_value` INT NOT NULL default 0,
PRIMARY KEY ( `id_form` ) ,
INDEX ( `caption` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%FORMS_INPUT%` (
`id_input` BIGINT NOT NULL AUTO_INCREMENT ,
`id_form` BIGINT NOT NULL,
`type_caption` VARCHAR( 250 ) NOT NULL ,
`input_type` INT NOT NULL,
`data_caption` VARCHAR( 250 ) NOT NULL ,
`data_type` BIGINT NOT NULL,
`obyaz` int not null default 0,
`sort` int not null default 0,
`caption` VARCHAR( 250 ) NOT NULL,
`error_text` VARCHAR( 250 ) NOT NULL,
`tooltip` VARCHAR( 250 ) NOT NULL,
PRIMARY KEY ( `id_input` ) ,
INDEX ( `caption` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%INPUT_VALUES%` (
`id_value` BIGINT NOT NULL AUTO_INCREMENT ,
`id_input` BIGINT NOT NULL,
`default` int not null default 1,
`caption` VARCHAR( 250 ) NOT NULL,
PRIMARY KEY ( `id_value` ) ,
INDEX ( `caption` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%FORMS_ORDERS%` (
`id_order` BIGINT NOT NULL AUTO_INCREMENT ,
`id_form` BIGINT NOT NULL,
`content` LONGTEXT,
`ip` VARCHAR( 250 ) NOT NULL,
`date` DATE NOT NULL ,
`order_number` BIGINT NOT NULL,
`unread` INT NOT NULL default 1,
`fio` VARCHAR(50) NULL,
`email` VARCHAR(50) NULL,
PRIMARY KEY ( `id_order` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%ORDER_ANSWERS%` (
`id_answer` BIGINT NOT NULL AUTO_INCREMENT ,
`id_order` BIGINT NOT NULL,
`ip` VARCHAR( 250 ) NOT NULL,
`date` DATE NOT NULL ,
`unread` INT NOT NULL default 1,
`is_admin` INT NOT NULL default 0,
`email` VARCHAR(50) NULL,
`forwardtext` LONGTEXT NULL,
`forward_date` DATE NULL ,
PRIMARY KEY ( `id_answer` )
) type=MyISAM;";
?>