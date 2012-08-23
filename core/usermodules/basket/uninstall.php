<?
/*
Файл удаления модуля интернет магазин
*/
global $engine;
$uninstall_sql[]="DELETE FROM `%ORDERS%`";
$uninstall_sql[]="DROP TABLE `%ORDERS%`";
$uninstall_sql[]="DELETE FROM `%OPT%`";
$uninstall_sql[]="DROP TABLE `%OPT%`";
$uninstall_sql[]="DELETE FROM `%DISCOUNTS%`";
$uninstall_sql[]="DROP TABLE `%DISCOUNTS%`";
$uninstall_sql[]="DELETE FROM `%LOGIN_DISCOUNTS%`";
$uninstall_sql[]="DROP TABLE `%LOGIN_DISCOUNTS%`";
$uninstall_sql[]="DELETE FROM `%DELIVERY%`";
$uninstall_sql[]="DROP TABLE `%DELIVERY%`";
$uninstall_sql[]="DELETE FROM `%PAYMENT%`";
$uninstall_sql[]="DROP TABLE `%PAYMENT%`";
$uninstall_sql[]="DELETE FROM `%ORDER_PRODUCTS%`";
$uninstall_sql[]="DROP TABLE `%ORDER_PRODUCTS%`";
$basket_url=$m->thismodule["basket_url"];
$basket_cat=$engine->getCategoryByIdent($basket_url);
if ($basket_cat) {
$engine->deleteCategory($basket_cat["id_category"]);
}
$order_url=$m->thismodule["order_url"];
$order_cat=$engine->getCategoryByIdent($order_url);
if ($order_cat) {
$engine->deleteCategory($order_cat["id_category"]);
}
$res=$db->query("select * from %block_types% where type='basket'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='basket'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$res=$db->query("DELETE from %categories_modules% where name_module='basket'");
?>