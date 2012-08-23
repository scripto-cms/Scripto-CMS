<?
/*
Файл удаления модуля пользователи
*/
global $engine;
$uninstall_sql[]="DELETE FROM `%USERS%`";
$uninstall_sql[]="DROP TABLE `%USERS%`";
$uninstall_sql[]="DELETE FROM `%GROUPS%`";
$uninstall_sql[]="DROP TABLE `%GROUPS%`";
$uninstall_sql[]="DELETE FROM `%USER_OBJECTS%`";
$uninstall_sql[]="DROP TABLE `%USER_OBJECTS%`";
$my_cat=$engine->getCategoryByIdent($m->thismodule["my_url"]);
if ($my_cat) {
$engine->deleteCategory($my_cat["id_category"]);
}
$my_register=$engine->getCategoryByIdent($m->thismodule["register_url"]);
if ($my_register) {
$engine->deleteCategory($my_register["id_category"]);
}
$my_forgot=$engine->getCategoryByIdent($m->thismodule["forgot_url"]);
if ($my_forgot) {
$engine->deleteCategory($my_forgot["id_category"]);
}
$res=$db->query("select * from %block_types% where type='users'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='users'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$uninstall_sql[]="DELETE FROM %static_modules% where `mod_name`='users'";
?>