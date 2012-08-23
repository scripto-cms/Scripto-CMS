<?
/*
Установочный файл для модуля поиск по сайту
*/
global $engine;
$install_sql[]="insert into `%block_types%` values (null,'Поиск по сайту','search','search',0);";
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
/*
Добавляем раздел поиск по сайту
*/
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
if (!$engine->rubricExist($m->thismodule["search_url"],0)) {
$engine->addCategory($m_page["id_category"],"Поиск по сайту","text",$m->thismodule["search_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
$engine->addModuleToCategory("search",mysql_insert_id());
}
?>