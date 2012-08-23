<?
//Настройки доступа к БД
$config["db"]["prefix"]="<prefix>";
$config["db"]["host"]="<host>";
$config["db"]["user"]="<user>";
$config["db"]["password"]="<password>";
$config["db"]["dbname"]="<dbname>";

//Использовать SET NAMES (помогает при проблемах с кодировкой в БД)
$config["charset"]["use_charset"]=1;//1-да , 0 - нет
$config["charset"]["sql"]="cp1251";//кодировка, в которую преобразовывать
?>