<?
global $engine;

$install_sql[]="CREATE TABLE `%ORDERS%` (
`id_order` BIGINT NOT NULL AUTO_INCREMENT ,
`id_user` BIGINT NOT NULL ,
`delivery` VARCHAR(255) NOT NULL ,
`payment` VARCHAR(255) NOT NULL ,
`comment` LONGTEXT,
`address` LONGTEXT,
`date` DATETIME NOT NULL ,
`price_itog` DECIMAL NOT NULL ,
`coupon_caption` VARCHAR(255) NULL default '' ,
`coupon_code` VARCHAR(255) NULL default '' ,
`coupon_price` DECIMAL NULL default 0 ,
`coupon_type` INT NULL default 0 ,
`coupon_itog` DECIMAL NULL default 0 ,
`discount_caption` VARCHAR(255) NULL default '' ,
`discount_price` DECIMAL NULL default 0 ,
`discount_itog` DECIMAL NULL default 0 ,
`view` INT NOT NULL ,
PRIMARY KEY ( `id_order` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%DELIVERY%` (
`id_delivery` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NOT NULL ,
`price` DECIMAL NOT NULL ,
`description` LONGTEXT,
PRIMARY KEY ( `id_delivery` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%PAYMENT%` (
`id_payment` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NOT NULL ,
`description` LONGTEXT,
PRIMARY KEY ( `id_payment` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%ORDER_PRODUCTS%` (
`id_order` BIGINT NOT NULL ,
`caption` VARCHAR( 250 ) NOT NULL ,
`variant` VARCHAR( 250 ) NOT NULL ,
`options_info` LONGTEXT NOT NULL ,
`code` VARCHAR( 250 ) NOT NULL ,
`price` BIGINT NOT NULL default 0,
`count` BIGINT NOT NULL 
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%DISCOUNTS%` (
`id_discount` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR(255) NOT NULL ,
`code` VARCHAR(255) NOT NULL ,
`type` INT NOT NULL ,
`price` DECIMAL NOT NULL,
`active` INT NOT NULL ,
PRIMARY KEY ( `id_discount` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%LOGIN_DISCOUNTS%` (
`id_discount` BIGINT NOT NULL ,
`login` VARCHAR(255) NOT NULL
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%OPT%` (
`id_opt` BIGINT NOT NULL AUTO_INCREMENT ,
`from` DECIMAL NOT NULL ,
`percent` INT NOT NULL default 0,
`active` INT NOT NULL ,
PRIMARY KEY ( `id_opt` )
) type=MyISAM;";
/*
Получаем идентификатор главного раздела
*/
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
/*
Добавляем раздел корзина
*/
if (!$engine->rubricExist($m->thismodule["basket_url"],0)) {
$engine->addCategory($m_page["id_category"],"Корзина","text",$m->thismodule["basket_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
$engine->addModuleToCategory("basket",mysql_insert_id());
}
/*
Добавляем раздел оформление заказа
*/
if (!$engine->rubricExist($m->thismodule["order_url"],0)) {
$engine->addCategory($m_page["id_category"],"Оформление заказа","text",$m->thismodule["order_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
$engine->addModuleToCategory("basket",mysql_insert_id());
}
$install_sql[]="insert into `%block_types%` values (null,'Корзина','basket','basket',0);";
?>