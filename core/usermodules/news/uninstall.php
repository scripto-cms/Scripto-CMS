<?
/*
Файл удаления модуля Новости сайта
*/
global $engine;
$res=$db->query("select * from %block_types% where type='lastnews'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %NEWS%";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='news'";
$uninstall_sql[]="DROP TABLE %NEWS%";
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='lastnews'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$news_url=$m->thismodule["news_url"];
$news_cat=$engine->getCategoryByIdent($news_url);
if ($news_cat) {
$engine->deleteCategory($news_cat["id_category"]);
}
?>