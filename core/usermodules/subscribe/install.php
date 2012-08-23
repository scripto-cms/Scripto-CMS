<?
/*
Установочный файл для модуля рассылки
*/

$install_sql[]="CREATE TABLE `%email%` (
`id_email` BIGINT NOT NULL AUTO_INCREMENT ,
`email` VARCHAR(255) not null default '',
`name` VARCHAR(255) null default '',
PRIMARY KEY (`id_email`)
);";
$install_sql[]="CREATE TABLE `%archive%` (
`id_archive` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR(255) not null default '',
`backmail` VARCHAR(50) not null default '',
`description` LONGTEXT null default '',
`date` TIMESTAMP not null default CURRENT_TIMESTAMP,
PRIMARY KEY (`id_archive`)
);";
$install_sql[]="insert into `%block_types%` values (null,'Рассылки','subscribe','subscribe',0);";
?>