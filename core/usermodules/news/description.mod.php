<?
$moduleinfo["caption"]="Модуль новостей";
$moduleinfo["url"]="http://www.scripto-cms.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["version"]="1.0";
$moduleinfo["icon"]="news.png";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/news/";
$moduleinfo["use_in_one_rubric"]=true;//только для одной рубрики
$moduleinfo["onpage_admin"]=20;
$moduleinfo["onpage"]=10;
$moduleinfo["comments"]=true;
$moduleinfo["news_url"]="news";

/*Настройки языковых версий*/
$moduleinfo["tables"]["NEWS"]["caption"]["type"]="text";
$moduleinfo["tables"]["NEWS"]["caption"]["caption"]="Заголовок новости";
$moduleinfo["tables"]["NEWS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["NEWS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["NEWS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["NEWS"]["content"]["caption"]="Краткое описание";
$moduleinfo["tables"]["NEWS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["NEWS"]["content"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["NEWS"]["content_full"]["type"]="solmetra";
$moduleinfo["tables"]["NEWS"]["content_full"]["caption"]="Полное описание";
$moduleinfo["tables"]["NEWS"]["content_full"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["NEWS"]["content_full"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["NEWS"]["meta"]["type"]="textarea";
$moduleinfo["tables"]["NEWS"]["meta"]["caption"]="Тег meta description";
$moduleinfo["tables"]["NEWS"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["NEWS"]["meta"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["NEWS"]["keywords"]["type"]="textarea";
$moduleinfo["tables"]["NEWS"]["keywords"]["caption"]="Тег keywords";
$moduleinfo["tables"]["NEWS"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["NEWS"]["keywords"]["sql_type"]="LONGTEXT NULL";
/*Конец настройки языковых версий*/
?>