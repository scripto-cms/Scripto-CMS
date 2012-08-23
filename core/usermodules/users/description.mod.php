<?
global $settings;
$moduleinfo["caption"]="ћодуль пользователи";
$moduleinfo["url"]="http://www.scripto.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["version"]="1.0";
$moduleinfo["use_in_all_rubrics"]=true;
$moduleinfo["icon"]="users.png";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/users/";

$moduleinfo["mailadmin"]=$settings["mailadmin"];
$moduleinfo["my_url"]="my";
$moduleinfo["register_url"]="register";
$moduleinfo["register"]=true;//включить или выключить регистрацию (true - включить)
$moduleinfo["forgot_url"]="forgot";
$moduleinfo["onpage_admin"]=15;

$moduleinfo["width"]=100;//ширина изображени€ аватарки пользовател€
$moduleinfo["height"]=100;//высота изображени€ аватарки пользовател€
?>