<?
/*
Установочный файл для модуля Новости сайта
*/
global $engine;
$install_sql[]="CREATE TABLE `%NEWS%` (
`id_news` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NOT NULL ,
`content` LONGTEXT,
`content_full` LONGTEXT,
`meta` LONGTEXT NULL,
`keywords` LONGTEXT NULL,
`date_news` DATE NOT NULL ,
`author` VARCHAR( 250 ) NOT NULL ,
`url` VARCHAR( 250 ) NOT NULL ,
`small_preview` VARCHAR(200) NULL default '',
`middle_preview` VARCHAR(200) NULL default '',
PRIMARY KEY ( `id_news` ) ,
INDEX ( `caption` )
);";
$install_sql[]="insert into `%block_types%` values (null,'Последние новости','lastnews','news',1);";
/*
Добавляем раздел новости
*/
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
if (!$engine->rubricExist($m->thismodule["news_url"],0)) {
$engine->addCategory($m_page["id_category"],"Новости","text",$m->thismodule["news_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
$engine->addModuleToCategory("news",mysql_insert_id());
}
?>