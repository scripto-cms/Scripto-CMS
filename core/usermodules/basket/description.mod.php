<?
global $engine;
global $settings;

$moduleinfo["caption"]="Модуль интернет магазин";
$moduleinfo["url"]="http://www.scripto.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/shop/";
$moduleinfo["version"]="1.0";
$moduleinfo["use_in_one_rubric"]=false;//выводить во всех разделах сайта
$moduleinfo["icon"]="shop.png";

$moduleinfo["mailadmin"]=$settings["mailadmin"];//выводить по 20 товаров на странице
$moduleinfo["basket_url"]="basket";
$moduleinfo["order_url"]="order";
$moduleinfo["onpage_orders"]=25;
$moduleinfo["mode"]="max";//max - считать бОльшую скидку

$moduleinfo["sales_count"]=1;//количество продаж по одному купону на один логин
$moduleinfo["max_percent"]=50;//максимальная скидка по одному купону

$moduleinfo["dicount_type"][0]["id"]=0;
$moduleinfo["dicount_type"][0]["name"]="Скидка в рублях";
$moduleinfo["dicount_type"][1]["id"]=1;
$moduleinfo["dicount_type"][1]["name"]="Скидка в процентах";

/*Настройки языковых версий*/
$moduleinfo["tables"]["DELIVERY"]["caption"]["type"]="text";
$moduleinfo["tables"]["DELIVERY"]["caption"]["caption"]="Название";
$moduleinfo["tables"]["DELIVERY"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["DELIVERY"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["DELIVERY"]["description"]["type"]="textarea";
$moduleinfo["tables"]["DELIVERY"]["description"]["caption"]="Описание";
$moduleinfo["tables"]["DELIVERY"]["description"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["DELIVERY"]["description"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PAYMENT"]["caption"]["type"]="text";
$moduleinfo["tables"]["PAYMENT"]["caption"]["caption"]="Название";
$moduleinfo["tables"]["PAYMENT"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PAYMENT"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["PAYMENT"]["description"]["type"]="textarea";
$moduleinfo["tables"]["PAYMENT"]["description"]["caption"]="Описание";
$moduleinfo["tables"]["PAYMENT"]["description"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PAYMENT"]["description"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["DISCOUNTS"]["caption"]["type"]="text";
$moduleinfo["tables"]["DISCOUNTS"]["caption"]["caption"]="Название";
$moduleinfo["tables"]["DISCOUNTS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["DISCOUNTS"]["caption"]["sql_type"]="VARCHAR(255) NULL";
/*Конец настройки языковых версий*/
?>