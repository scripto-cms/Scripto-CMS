<?
$moduleinfo["caption"]="Модуль публикации";
$moduleinfo["url"]="http://www.scripto.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["icon"]="articles.png";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/publications/";
$moduleinfo["version"]="1.0";
$moduleinfo["use_in_one_rubric"]=false;//выводить во всех разделах сайта
$moduleinfo["publications_url"]="publications";

$moduleinfo["onpage"]=10;//выводить по 10 публикаций на странице
$moduleinfo["onpage_admin"]=20;//выводить по 20 публикаций в админке

$moduleinfo["comments"]=true;

/*Настройки языковых версий*/
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["type"]="text";
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["caption"]="Заголовок публикации";
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["PUBLICATIONS"]["content"]["caption"]="Краткое содержимое публикации";
$moduleinfo["tables"]["PUBLICATIONS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["content"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["type"]="solmetra";
$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["caption"]="Полное содержимое публикации";
$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["content_full"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["type"]="textarea";
$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["caption"]="Тег meta description";
$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["meta"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["type"]="textarea";
$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["caption"]="Тег keywords";
$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PUBLICATIONS"]["keywords"]["sql_type"]="LONGTEXT NULL";
/*Конец настройки языковых версий*/
?>