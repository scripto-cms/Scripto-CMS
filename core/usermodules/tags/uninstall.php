<?
/*
Файл удаления модуля теги
*/
global $engine;
$res=$db->query("select * from %block_types% where type='cloudtags'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %TAGS%";
$uninstall_sql[]="DROP TABLE %TAGS%";
$uninstall_sql[]="DELETE FROM %TAG_OBJECTS%";
$uninstall_sql[]="DROP TABLE %TAG_OBJECTS%";
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='cloudtags'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$tag_url=$m->thismodule["tag_url"];
$tag_cat=$engine->getCategoryByIdent($tag_url);
if ($tag_cat) {
$engine->deleteCategory($tag_cat["id_category"]);
}
?>