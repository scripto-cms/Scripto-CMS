<?
/*
Установочный файл для модуля теги
*/
global $engine;
$install_sql[]="CREATE TABLE `%TAGS%` (
`id_tag` BIGINT NOT NULL AUTO_INCREMENT ,
`tag` VARCHAR(110),
PRIMARY KEY ( `id_tag` )
);";
$install_sql[]="CREATE TABLE `%TAG_OBJECTS%` (
`id_tag` BIGINT NOT NULL,
FOREIGN KEY (id_tag) REFERENCES %TAGS%(id_tag)
          ON UPDATE CASCADE
          ON DELETE RESTRICT,
`id_object` BIGINT NOT NULL,
`object_type` VARCHAR(255)
);";
/*
Получаем идентификатор главного раздела
*/
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
/*
Добавляем раздел корзина
*/
if (!$engine->rubricExist($m->thismodule["tag_url"],0)) {
$engine->addCategory($m_page["id_category"],"Тег","text",$m->thismodule["tag_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
$engine->addModuleToCategory("tags",mysql_insert_id());
}
$install_sql[]="insert into `%block_types%` values (null,'Облако тегов','first_test','tags',1);";
?>