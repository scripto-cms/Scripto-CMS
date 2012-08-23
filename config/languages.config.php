<?
//настройка стандартных таблиц и полей, доступных в разных языковых версиях
//категории
$tables["categories"]["caption"]["type"]="text";
$tables["categories"]["caption"]["caption"]="Название раздела";
$tables["categories"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["categories"]["caption"]["sql_type"]="VARCHAR(250) NULL";
$tables["categories"]["content"]["type"]="solmetra";
$tables["categories"]["content"]["caption"]="Содержание раздела";
$tables["categories"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["categories"]["content"]["sql_type"]="LONGTEXT NULL";
$tables["categories"]["subcontent"]["type"]="solmetra";
$tables["categories"]["subcontent"]["caption"]="Дополнительное содержание раздела";
$tables["categories"]["subcontent"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["categories"]["subcontent"]["sql_type"]="LONGTEXT NULL";
$tables["categories"]["title"]["type"]="text";
$tables["categories"]["title"]["caption"]="Тег title для раздела";
$tables["categories"]["title"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["categories"]["title"]["sql_type"]="VARCHAR(250) NULL";
$tables["categories"]["meta"]["type"]="textarea";
$tables["categories"]["meta"]["caption"]="Теги meta description";
$tables["categories"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["categories"]["meta"]["sql_type"]="TEXT NULL";
$tables["categories"]["keywords"]["type"]="textarea";
$tables["categories"]["keywords"]["caption"]="Теги meta keywords";
$tables["categories"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["categories"]["keywords"]["sql_type"]="TEXT NULL";

//настройки
$tables["settings"]["caption"]["type"]="text";
$tables["settings"]["caption"]["caption"]="Название сайта";
$tables["settings"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["settings"]["caption"]["sql_type"]="VARCHAR(250) NULL";

$tables["settings"]["title"]["type"]="text";
$tables["settings"]["title"]["caption"]="Тег title";
$tables["settings"]["title"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["settings"]["title"]["sql_type"]="VARCHAR(250) NULL";

$tables["settings"]["meta"]["type"]="textarea";
$tables["settings"]["meta"]["caption"]="Тег meta";
$tables["settings"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["settings"]["meta"]["sql_type"]="TEXT NULL";

$tables["settings"]["keywords"]["type"]="textarea";
$tables["settings"]["keywords"]["caption"]="Тег keywords";
$tables["settings"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["settings"]["keywords"]["sql_type"]="TEXT NULL";

//блоки
$tables["blocks"]["caption"]["type"]="text";
$tables["blocks"]["caption"]["caption"]="Название блока";
$tables["blocks"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["blocks"]["caption"]["sql_type"]="VARCHAR(250) NULL";

$tables["blocks"]["content"]["type"]="solmetra";
$tables["blocks"]["content"]["caption"]="Содержимое блока";
$tables["blocks"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$tables["blocks"]["content"]["sql_type"]="LONGTEXT NULL";
?>