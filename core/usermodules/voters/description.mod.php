<?
$moduleinfo["caption"]="Модуль опросы";
$moduleinfo["url"]="http://www.scripto-cms.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["icon"]="vote.png";
$moduleinfo["version"]="1.0";
$moduleinfo["use_in_one_rubric"]=true;//только для одной рубрики
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/voters/";
$moduleinfo["voters_url"]="voters";

$moduleinfo["1dayvote"]=false;//удалять результаты опросов ежедневно
$moduleinfo["onpage"]=1;

/*Настройки языковых версий*/
$moduleinfo["tables"]["VOTERS"]["vopros"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["vopros"]["caption"]="Вопрос опроса";
$moduleinfo["tables"]["VOTERS"]["vopros"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["vopros"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet1"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet1"]["caption"]="Вариант ответа 1";
$moduleinfo["tables"]["VOTERS"]["otvet1"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet1"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet2"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet2"]["caption"]="Вариант ответа 2";
$moduleinfo["tables"]["VOTERS"]["otvet2"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet2"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet3"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet3"]["caption"]="Вариант ответа 3";
$moduleinfo["tables"]["VOTERS"]["otvet3"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet3"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet4"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet4"]["caption"]="Вариант ответа 4";
$moduleinfo["tables"]["VOTERS"]["otvet4"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet4"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["VOTERS"]["otvet5"]["type"]="text";
$moduleinfo["tables"]["VOTERS"]["otvet5"]["caption"]="Вариант ответа 5";
$moduleinfo["tables"]["VOTERS"]["otvet5"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["VOTERS"]["otvet5"]["sql_type"]="VARCHAR(255) NULL";

/*Конец настройки языковых версий*/
?>