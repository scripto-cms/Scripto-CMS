<?
/*
Установочный файл для модуля товары
*/
global $engine;

$install_sql[]="CREATE TABLE `%PRODUCTS%` (
`id_product` BIGINT NOT NULL AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL default 0,
`id_firm` BIGINT NOT NULL default 0 ,
`id_collection` BIGINT NOT NULL default 0,
`caption` VARCHAR( 250 ) NOT NULL ,
`code` VARCHAR( 250 ) NOT NULL ,
`content` LONGTEXT,
`content_full` LONGTEXT,
`date` DATE NOT NULL ,
`url` VARCHAR( 250 ) NOT NULL ,
`price1` FLOAT NULL ,
`price2` FLOAT NULL ,
`price_default` FLOAT NULL ,
`kolvo` BIGINT NULL ,
`views` BIGINT NULL default 0 ,
`visible` INT NULL default 1 ,
`buy` BIGINT NULL default 0 ,
`sort` BIGINT NULL default 0,
`options_info` LONGTEXT default '',
`meta` LONGTEXT,
`keywords` LONGTEXT,
PRIMARY KEY ( `id_product` )
) type=MyISAM;";
$install_sql[]="CREATE INDEX products_id_category USING BTREE ON `%PRODUCTS%` (id_category);";
$install_sql[]="CREATE INDEX products_id_product USING BTREE ON `%PRODUCTS%` (id_product);";
$install_sql[]="CREATE INDEX products_id_firm USING BTREE ON `%PRODUCTS%` (id_firm);";
$install_sql[]="CREATE INDEX products_id_collection USING BTREE ON `%PRODUCTS%` (id_collection);";
$install_sql[]="CREATE TABLE `%PRODUCT_PICTURES%` (
`id_product` BIGINT NOT NULL ,
`id_image` BIGINT NOT NULL,
`main_picture` int not null default 0,
`small_filename` VARCHAR( 250 ) NOT NULL,
`medium_filename` VARCHAR( 250 ) NOT NULL,
`big_filename` VARCHAR( 250 ) NOT NULL,
`sort` MEDIUMINT NOT NULL,
INDEX( `id_product` ) ,
INDEX( `id_image` )
) type=MyISAM;";
$install_sql[]="CREATE INDEX product_pictures_id_image USING BTREE ON `%PRODUCT_PICTURES%` (id_image);";
$install_sql[]="CREATE INDEX product_pictures_id_product USING BTREE ON `%PRODUCT_PICTURES%` (id_product);";
$install_sql[]="CREATE TABLE `%BLOCK_PRODUCTS%` (
`id_product` BIGINT NOT NULL ,
`id_block` BIGINT NOT NULL,
INDEX( `id_product` ),
INDEX( `id_block` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%ACTIONS%` (
`id_action` BIGINT NOT NULL  AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`caption` VARCHAR(255) NOT NULL,
`content` LONGTEXT,
PRIMARY KEY ( `id_action` ),
INDEX( `id_category` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%ACTION_PRODUCTS%` (
`id_action` BIGINT NOT NULL ,
`id_product` BIGINT NOT NULL ,
INDEX( `id_action` ),
INDEX( `id_product` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%BLOCK_ACTIONS%` (
`id_action` BIGINT NOT NULL ,
`id_block` BIGINT NOT NULL,
INDEX( `id_action` ),
INDEX( `id_block` )
) type=MyISAM;";
$install_sql[]="CREATE INDEX product_block_id_block USING BTREE ON `%BLOCK_PRODUCTS%` (id_block);";
$install_sql[]="CREATE TABLE `%PRODUCT_PRODUCTS%` (
`id_product` BIGINT NOT NULL ,
`id_product2` BIGINT NOT NULL,
INDEX( `id_product` ),
INDEX( `id_product2` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%PRODUCT_VARIANTS%` (
`id_variant` BIGINT NOT NULL AUTO_INCREMENT ,
`id_product` BIGINT NOT NULL,
`caption` VARCHAR( 250 ) NOT NULL ,
`content` LONGBLOB,
PRIMARY KEY ( `id_variant` ) ,
INDEX ( `caption` )
) type=MyISAM;";
$install_sql[]="CREATE INDEX product_variants_id_product USING BTREE ON `%PRODUCT_VARIANTS%` (id_variant);";
$install_sql[]="CREATE TABLE `%PRODUCT_TYPES%` (
`id_type` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NOT NULL ,
PRIMARY KEY ( `id_type` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%PRODUCT_OPTIONS%` (
`id_option` BIGINT NOT NULL AUTO_INCREMENT ,
`id_type` BIGINT NOT NULL ,
`caption` VARCHAR( 250 ) NOT NULL ,
`values` LONGTEXT NOT NULL ,
`show_in_order` INT NOT NULL default 0,
PRIMARY KEY ( `id_option` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%FIRMS%` (
`id_firm` BIGINT NOT NULL AUTO_INCREMENT,
`caption` VARCHAR( 250 ) NOT NULL,
`description` LONGTEXT NOT NULL,
PRIMARY KEY ( `id_firm` )
) type=MyISAM;";
$install_sql[]="CREATE TABLE `%COLLECTIONS%` (
`id_collection` BIGINT NOT NULL AUTO_INCREMENT,
`id_firm` BIGINT NOT NULL ,
`caption` VARCHAR( 250 ) NOT NULL,
PRIMARY KEY ( `id_collection` )
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
Добавляем раздел просмотр товаров по производителю
*/
if (!$engine->rubricExist($m->thismodule["brand_url"],0)) {
$val["brand_page"]=true;
$engine->addCategory($m_page["id_category"],"Просмотр товаров по производителю","text",$m->thismodule["brand_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0,0,0,0,0,'','',$val);
$engine->addModuleToCategory("products",mysql_insert_id());
}

/*
Добавляем раздел просмотр товаров по типу товара
*/
if (!$engine->rubricExist($m->thismodule["type_url"],0)) {
unset($val);
$val["type_url"]=true;
$engine->addCategory($m_page["id_category"],"Просмотр товаров по типу","text",$m->thismodule["type_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0,0,0,0,0,'','',$val);
$engine->addModuleToCategory("products",mysql_insert_id());
}

/*
Добавляем раздел сравнение товаров
*/
if (!$engine->rubricExist($m->thismodule["favorite_url"],0)) {
unset($val);
$val["favorite_url"]=true;
$engine->addCategory($m_page["id_category"],"Сравнение товаров","text",$m->thismodule["favorite_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0,0,1,0,0,'','',$val);
$engine->addModuleToCategory("products",mysql_insert_id());
}

$install_sql[]="insert into `%block_types%` values (null,'Лидеры продаж','products_leaders','products',0);";
$install_sql[]="insert into `%block_types%` values (null,'Сравнение товаров','products_compare','products',0);";
$install_sql[]="insert into `%block_types%` values (null,'Вывод типов товаров','products_types','products',1);";
$install_sql[]="insert into `%block_types%` values (null,'Акции','products_actions','products',1);";
$install_sql[]="insert into `%block_types%` values (null,'Вывод фирм для товаров','products_firms','products',1);";
$install_sql[]="insert into `%block_types%` values (null,'Товары на выбор (random)','products_random','products',0);";
$install_sql[]="insert into `%block_types%` values (null,'Товары на выбор','products','products',1);";
?>