<?
/*
Файл удаления модуля объекты
*/
@delTree($config["pathes"]["user_files"]."files/");
$res=$db->query("select * from %block_types% where type='lastobjects'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
$uninstall_sql[]="DELETE FROM %OBJ%";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='objects'";
$uninstall_sql[]="DROP TABLE %OBJ%";
$uninstall_sql[]="DELETE FROM %TYPES%";
$uninstall_sql[]="DROP TABLE %TYPES%";
$uninstall_sql[]="DELETE FROM %block_types% WHERE type='lastobjects'";
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$res=$db->query("select * from %block_types% where type='addobjects'");
$row=$db->fetch($res);
$id_type=$row["id_type"];
if ($id_type>0) {
$uninstall_sql[]="DELETE FROM %blocks% where id_type=$id_type";
}
$uninstall_sql[]="DELETE FROM %OBJECT_PICTURES%";
$uninstall_sql[]="DROP TABLE %OBJECT_PICTURES%";
$uninstall_sql[]="DELETE FROM %OBJECT_VIDEOS%";
$uninstall_sql[]="DROP TABLE %OBJECT_VIDEOS%";
$uninstall_sql[]="DELETE FROM %OBJECT_OBJECTS%";
$uninstall_sql[]="DROP TABLE %OBJECT_OBJECTS%";
$uninstall_sql[]="DELETE FROM %OBJECT_FILES%";
$uninstall_sql[]="DROP TABLE %OBJECT_FILES%";
$uninstall_sql[]="DELETE FROM %block_object_types%";
$uninstall_sql[]="DROP TABLE %block_object_types%";
$uninstall_sql[]="DELETE FROM %OBJECT_CATEGORIES%";
$uninstall_sql[]="DROP TABLE %OBJECT_CATEGORIES%";
?>