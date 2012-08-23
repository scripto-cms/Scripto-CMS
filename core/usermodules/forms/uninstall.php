<?
/*
Файл удаления модуля формы
*/
$uninstall_sql[]="DELETE FROM %FORMS%";
$uninstall_sql[]="DROP TABLE %FORMS%";
$uninstall_sql[]="DELETE FROM %FORMS_INPUT%";
$uninstall_sql[]="DROP TABLE %FORMS_INPUT%";
$uninstall_sql[]="DELETE FROM %INPUT_VALUES%";
$uninstall_sql[]="DROP TABLE %INPUT_VALUES%";
$uninstall_sql[]="DELETE FROM %FORMS_ORDERS%";
$uninstall_sql[]="DROP TABLE %FORMS_ORDERS%";
$uninstall_sql[]="DELETE FROM %ORDER_ANSWERS%";
$uninstall_sql[]="DROP TABLE %ORDER_ANSWERS%";
$uninstall_sql[]="DELETE FROM %categories_module% where `name_module`='forms'";
?>