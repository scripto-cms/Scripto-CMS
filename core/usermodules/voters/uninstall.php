<?
/*
Файл удаления модуля голосования
*/
global $engine;
$res=$db->query("select * from %block_types% where type='voters'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %VOTERS%";
$uninstall_sql[]="DELETE FROM %VOTERS_IP%";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='voters'";
$uninstall_sql[]="DROP TABLE %VOTERS%";
$uninstall_sql[]="DROP TABLE %VOTERS_IP%";
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='voters'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$voters_url=$m->thismodule["voters_url"];
$voters_cat=$engine->getCategoryByIdent($voters_url);
if ($voters_cat) {
$engine->deleteCategory($voters_cat["id_category"]);
}
?>