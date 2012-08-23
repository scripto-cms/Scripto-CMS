<?
/*
Файл удаления модуля Рассылки
*/
$res=$db->query("select * from %block_types% where type='subscribe'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %email%";
$uninstall_sql[]="DELETE FROM %archive%";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='subscribe'";
$uninstall_sql[]="DROP TABLE %email%";
$uninstall_sql[]="DROP TABLE %archive%";
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='subscribe'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
?>