<?
/*
Установочный файл для модуля публикации
*/
global $engine;
$install_sql[]="CREATE TABLE `%PUBLICATIONS%` (
`id_publication` BIGINT NOT NULL AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`caption` VARCHAR( 250 ) NOT NULL ,
`content` LONGTEXT,
`content_full` LONGTEXT,
`meta` LONGTEXT NULL,
`keywords` LONGTEXT NULL,
`date` DATE NOT NULL,
`author` VARCHAR( 250 ) NOT NULL ,
`url` VARCHAR( 250 ) NOT NULL ,
`views` BIGINT NULL default 0 ,
`visible` INT NULL default 1 ,
`sort` BIGINT NULL default 0,
PRIMARY KEY ( `id_publication` ) ,
INDEX ( `caption` )
) ENGINE=MyISAM;";
$install_sql[]="insert into `%block_types%` values (null,'Последние публикации','lastpublications','publications',1);";
/*
Добавляем раздел публикации
*/
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
if (!$engine->rubricExist($m->thismodule["publications_url"],0)) {
$engine->addCategory($m_page["id_category"],"Публикации","text",$m->thismodule["publications_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
$engine->addModuleToCategory("publications",mysql_insert_id());
}
?>