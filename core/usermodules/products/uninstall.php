<?
/*
Файл удаления модуля товары
*/
global $engine;
$uninstall_sql[]="DELETE FROM %PRODUCTS%";
$uninstall_sql[]="DELETE FROM %PRODUCT_PICTURES%";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='products'";
$uninstall_sql[]="DELETE FROM %FIRMS%";
$uninstall_sql[]="DELETE FROM %COLLECTIONS%";
$uninstall_sql[]="DELETE FROM %ACTIONS%";
$uninstall_sql[]="DELETE FROM %ACTION_PRODUCTS%";
$uninstall_sql[]="DELETE FROM %BLOCK_ACTIONS%";
$uninstall_sql[]="DROP TABLE %PRODUCTS%";
$uninstall_sql[]="DROP TABLE %PRODUCT_PICTURES%";
$uninstall_sql[]="DROP TABLE %FIRMS%";
$uninstall_sql[]="DROP TABLE %COLLECTIONS%";
$uninstall_sql[]="DROP TABLE %ACTIONS%";
$uninstall_sql[]="DROP TABLE %ACTION_PRODUCTS%";
$uninstall_sql[]="DROP TABLE %BLOCK_ACTIONS%";
$uninstall_sql[]="DELETE FROM %BLOCK_PRODUCTS%";
$uninstall_sql[]="DROP TABLE %BLOCK_PRODUCTS%";
$uninstall_sql[]="DROP TABLE %PRODUCT_PRODUCTS%";
$uninstall_sql[]="DELETE FROM %PRODUCT_PRODUCTS%";
$uninstall_sql[]="DROP TABLE %PRODUCT_VARIANTS%";
$uninstall_sql[]="DELETE FROM %PRODUCT_VARIANTS%";
$uninstall_sql[]="DELETE FROM %PRODUCT_TYPES%";
$uninstall_sql[]="DROP TABLE %PRODUCT_TYPES%";
$uninstall_sql[]="DELETE FROM %PRODUCT_OPTIONS%";
$uninstall_sql[]="DROP TABLE %PRODUCT_OPTIONS%";
$res=$db->query("select * from %block_types% where type='products'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='products'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
unset($res);
unset($row);

$brand_url=$m->thismodule["brand_url"];
$brand_cat=$engine->getCategoryByIdent($brand_url);
if ($brand_cat) {
$engine->deleteCategory($brand_cat["id_category"]);
}

$type_url=$m->thismodule["type_url"];
$type_cat=$engine->getCategoryByIdent($type_url);
if ($type_cat) {
$engine->deleteCategory($type_cat["id_category"]);
}

$favorite_url=$m->thismodule["favorite_url"];
$favorite_cat=$engine->getCategoryByIdent($favorite_url);
if ($favorite_cat) {
$engine->deleteCategory($favorite_cat["id_category"]);
}

$res=$db->query("select * from %block_types% where type='products_random'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='products_random'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}

$res=$db->query("select * from %block_types% where type='products_actions'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='products_actions'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}

$res=$db->query("select * from %block_types% where type='products_leaders'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='products_leaders'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}

$res=$db->query("select * from %block_types% where type='products_types'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='products_types'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}

$res=$db->query("select * from %block_types% where type='products_firms'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='products_firms'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}

$res=$db->query("select * from %block_types% where type='products_compare'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='products_compare'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
?>