<?
/*
Файл удаления модуля комментарии
*/
$uninstall_sql[]="DELETE FROM %COMMENTS%";
$uninstall_sql[]="DROP TABLE %COMMENTS%";
$uninstall_sql[]="DELETE FROM %static_modules% where `mod_name`='comments'";
?>