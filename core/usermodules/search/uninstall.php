<?
/*
Файл удаления модуля поиск по сайту
*/
global $engine;
$res=$db->query("select * from %block_types% where type='search'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='search'";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='search'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$search_url=$m->thismodule["search_url"];
$search_cat=$engine->getCategoryByIdent($search_url);
if ($search_cat) {
$engine->deleteCategory($search_cat["id_category"]);
}
?>