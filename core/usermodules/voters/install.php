<?
/*
Установочный файл для модуля голосования
*/
global $engine;
$install_sql[]="CREATE TABLE `%VOTERS%` (
`id_vote` BIGINT NOT NULL AUTO_INCREMENT ,
`vopros` VARCHAR(255) not null default '',
`otvet1` VARCHAR(255) not null default '',
`voters1` BIGINT not null default 0,
`otvet2` VARCHAR(255) not null default '',
`voters2` BIGINT not null default 0,
`otvet3` VARCHAR(255) null,
`voters3` BIGINT null,
`otvet4` VARCHAR(255) null,
`voters4` BIGINT null,
`otvet5` VARCHAR(255) null,
`voters5` BIGINT null,
`all` BIGINT not null default 0,
`date_add` datetime,
`current` int,
PRIMARY KEY (`id_vote`)
);";
$install_sql[]="CREATE TABLE `%VOTERS_IP%` (
`id_vote` BIGINT ,
`ip` VARCHAR(255) not null default '',
`date` date
);";
$install_sql[]="insert into `%block_types%` values (null,'Опрос','voters','voters',0);";
/*
Добавляем раздел голосования
*/
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
if (!$engine->rubricExist($m->thismodule["voters_url"],0)) {
$engine->addCategory($m_page["id_category"],"Голосования","text",$m->thismodule["voters_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
$engine->addModuleToCategory("voters",mysql_insert_id());
}
?>