<?
/*
Установочный файл для модуля пользователи
*/
global $engine;

$install_sql[]="CREATE TABLE `%USERS%` (
`id_user` BIGINT NOT NULL AUTO_INCREMENT ,
`avatar` VARCHAR( 250 ) NULL ,
`login` VARCHAR( 250 ) NOT NULL ,
`password` VARCHAR( 250 ) NOT NULL ,
`family` VARCHAR( 250 ) NOT NULL ,
`name` VARCHAR( 250 ) NOT NULL ,
`otch` VARCHAR( 250 ) NOT NULL ,
`email` VARCHAR( 250 ) NOT NULL ,
`city` VARCHAR( 250 ) NOT NULL ,
`phone1` VARCHAR( 250 ) NOT NULL ,
`phone2` VARCHAR( 250 ) NULL ,
`date` DATETIME NOT NULL ,
`new` INT NOT NULL default 0 ,
`moderator` INT NOT NULL default 0,
`access` LONGTEXT NULL default '',
`id_group` INT NOT NULL default 0 ,
PRIMARY KEY ( `id_user` )
) ENGINE=MyISAM;";
/*
Получаем идентификатор главного раздела
*/
$m_page=$engine->getMainpage();
$date_category=array();
$date_category[0]=(int)date("d");
$date_category[1]=(int)date("m");
$date_category[2]=(int)date("Y");
/*
Добавляем раздел личный кабинет
*/
if (!$engine->rubricExist($m->thismodule["my_url"],0)) {
$engine->addCategory($m_page["id_category"],"Личный кабинет","text",$m->thismodule["my_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
}

/*
Добавляем раздел регистрация
*/
if (!$engine->rubricExist($m->thismodule["register_url"],0)) {
$engine->addCategory($m_page["id_category"],"Регистрация","text",$m->thismodule["register_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
}

/*
Добавляем раздел забыли пароль
*/
if (!$engine->rubricExist($m->thismodule["forgot_url"],0)) {
$engine->addCategory($m_page["id_category"],"Восстановление пароля","text",$m->thismodule["forgot_url"],0,0,'','','','','','',$m_page["id_tpl"],$m_page["position"],$date_category,0);
}

$install_sql[]="CREATE TABLE `%USER_OBJECTS%` (
`id_object` BIGINT NOT NULL ,
`id_user` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL ,
`sort` BIGINT NOT NULL default 0
);";

$install_sql[]="CREATE TABLE `%GROUPS%` (
`id_group` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NULL ,
`percent` INT NOT NULL default 0,
PRIMARY KEY ( `id_group` )
) ENGINE=MyISAM;";

$install_sql[]="INSERT INTO `%static_modules%` values('users',0)";
$install_sql[]="insert into `%block_types%` values (null,'Форма авторизации','users','users',0);";
?>