<?
$moduleinfo["caption"]="Модуль товары";
$moduleinfo["url"]="http://www.scripto.ru";
$moduleinfo["author"]="Scripto";
$moduleinfo["description"]="";
$moduleinfo["version"]="1.0";
$moduleinfo["documentation"]="http://scripto-cms.ru/documentation/additional/products/";
$moduleinfo["use_in_one_rubric"]=false;//выводить во всех разделах сайта
$moduleinfo["icon"]="products.png";

$moduleinfo["show_price1"]=true;//выводить стоимость 1
$moduleinfo["show_price2"]=true;//выводить стоимость 2
$moduleinfo["show_count"]=false;//выводить количество товара
$moduleinfo["onpage"]=16;//выводить по 20 товаров на странице
$moduleinfo["onpage_admin"]=16;//выводить по 20 товаров на странице
$moduleinfo["shop_on"]=true;//включить функции интернет магазина
$moduleinfo["do_subcategory"]=true;//выводить товары из подкатегорий
$moduleinfo["brand_url"]="brand";//идентификатор страницы, на которой показывать товары производителей
$moduleinfo["type_url"]="type";//идентификатор страницы, на которой показывать типы товаров
$moduleinfo["favorite_url"]="favorite";//идентификатор страницы, на которой выводить сравнение товаров

//Поля для импорта CSV
$moduleinfo["csv"][0]["id"]="csv_code";
$moduleinfo["csv"][0]["caption"]="Код товара";
$moduleinfo["csv"][1]["id"]="csv_product";
$moduleinfo["csv"][1]["caption"]="Название товара";
$moduleinfo["csv"][2]["id"]="csv_price1";
$moduleinfo["csv"][2]["caption"]="Цена 1";
$moduleinfo["csv"][3]["id"]="csv_price2";
$moduleinfo["csv"][3]["caption"]="Цена 2";
$moduleinfo["csv"][4]["id"]="csv_price_default";
$moduleinfo["csv"][4]["caption"]="Закупочная стоимость";
$moduleinfo["csv"][5]["id"]="csv_description";
$moduleinfo["csv"][5]["caption"]="Краткое описание";
$moduleinfo["csv"][6]["id"]="csv_fulldescription";
$moduleinfo["csv"][6]["caption"]="Полное описание";
$moduleinfo["csv"][7]["id"]="csv_link";
$moduleinfo["csv"][7]["caption"]="Ссылка на сайт производителя товара";
$moduleinfo["csv"][8]["id"]="csv_count";
$moduleinfo["csv"][8]["caption"]="На складе (количество товара)";
$moduleinfo["csv"][9]["id"]="csv_caption";
$moduleinfo["csv"][9]["caption"]="Название раздела";

/*Настройки языковых версий*/
$moduleinfo["tables"]["PRODUCTS"]["caption"]["type"]="text";
$moduleinfo["tables"]["PRODUCTS"]["caption"]["caption"]="Название товара";
$moduleinfo["tables"]["PRODUCTS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["PRODUCTS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["PRODUCTS"]["content"]["caption"]="Краткое описание товара";
$moduleinfo["tables"]["PRODUCTS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["content"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCTS"]["content_full"]["type"]="solmetra";
$moduleinfo["tables"]["PRODUCTS"]["content_full"]["caption"]="Полное описание товара";
$moduleinfo["tables"]["PRODUCTS"]["content_full"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["content_full"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCTS"]["meta"]["type"]="textarea";
$moduleinfo["tables"]["PRODUCTS"]["meta"]["caption"]="Тег meta description";
$moduleinfo["tables"]["PRODUCTS"]["meta"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["meta"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCTS"]["keywords"]["type"]="textarea";
$moduleinfo["tables"]["PRODUCTS"]["keywords"]["caption"]="Тег keywords";
$moduleinfo["tables"]["PRODUCTS"]["keywords"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCTS"]["keywords"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["FIRMS"]["caption"]["type"]="text";
$moduleinfo["tables"]["FIRMS"]["caption"]["caption"]="Название фирмы";
$moduleinfo["tables"]["FIRMS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FIRMS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["FIRMS"]["description"]["type"]="solmetra";
$moduleinfo["tables"]["FIRMS"]["description"]["caption"]="Описание фирмы";
$moduleinfo["tables"]["FIRMS"]["description"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["FIRMS"]["description"]["sql_type"]="LONGTEXT NULL";

$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["type"]="text";
$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["caption"]="Название типа";
$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["PRODUCT_TYPES"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["ACTIONS"]["caption"]["type"]="text";
$moduleinfo["tables"]["ACTIONS"]["caption"]["caption"]="Название акции";
$moduleinfo["tables"]["ACTIONS"]["caption"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["ACTIONS"]["caption"]["sql_type"]="VARCHAR(255) NULL";

$moduleinfo["tables"]["ACTIONS"]["content"]["type"]="solmetra";
$moduleinfo["tables"]["ACTIONS"]["content"]["caption"]="Описание акции";
$moduleinfo["tables"]["ACTIONS"]["content"]["eregi"]="/^[^`#]{2,255}$/i";
$moduleinfo["tables"]["ACTIONS"]["content"]["sql_type"]="LONGTEXT NULL";
/*Конец настройки языковых версий*/
?>