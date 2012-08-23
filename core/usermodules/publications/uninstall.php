<?
/*
Файл удаления модуля публикации
*/
global $engine;
$uninstall_sql[]="DELETE FROM %PUBLICATIONS%";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='publications'";
$uninstall_sql[]="DROP TABLE %PUBLICATIONS%";
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='lastpublications'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$publications_url=$m->thismodule["publications_url"];
$publications_cat=$engine->getCategoryByIdent($publications_url);
if ($publications_cat) {
$engine->deleteCategory($publications_cat["id_category"]);
}
?>