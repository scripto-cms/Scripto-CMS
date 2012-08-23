<?
/*
Установочный файл для модуля объекты
*/
@mkdir($config["pathes"]["user_files"]."files/");
$install_sql[]="CREATE TABLE `%TYPES%` (
`id_type` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NOT NULL ,
`ident` VARCHAR( 250 ) NOT NULL ,
`description` LONGTEXT,
`date_type` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
`use_code` INT NOT NULL ,
`do_comments` INT NOT NULL ,
`short_content` INT NOT NULL ,
`full_content` INT NOT NULL ,
`voters` INT NOT NULL ,
`small_preview` INT NOT NULL ,
`medium_preview` INT NOT NULL ,
`max_images` BIGINT NOT NULL ,
`use_gallery` INT NOT NULL default 0,
`max_videos` BIGINT NOT NULL ,
`use_videogallery` INT NOT NULL default 0,
`use_objects` INT NOT NULL default 0,
`use_files` INT NOT NULL default 0,
`add_cat` BIGINT NOT NULL default 0,
`user_add` INT NOT NULL default 0,
`download_only_for_users` INT NOT NULL default 0,
`onpage` BIGINT NOT NULL ,
`show_alphabet` INT NOT NULL ,
`caption1` VARCHAR( 250 ) NOT NULL ,
`type1` VARCHAR( 250 ) NOT NULL ,
`show_in_full1` INT NOT NULL ,
`show_in_short1` INT NOT NULL ,
`caption2` VARCHAR( 250 ) NOT NULL ,
`type2` VARCHAR( 250 ) NOT NULL ,
`show_in_full2` INT NOT NULL ,
`show_in_short2` INT NOT NULL ,
`caption3` VARCHAR( 250 ) NOT NULL ,
`type3` VARCHAR( 250 ) NOT NULL ,
`show_in_full3` INT NOT NULL ,
`show_in_short3` INT NOT NULL ,
`caption4` VARCHAR( 250 ) NOT NULL ,
`type4` VARCHAR( 250 ) NOT NULL ,
`show_in_full4` INT NOT NULL ,
`show_in_short4` INT NOT NULL ,
`caption5` VARCHAR( 250 ) NOT NULL ,
`type5` VARCHAR( 250 ) NOT NULL ,
`show_in_full5` INT NOT NULL ,
`show_in_short5` INT NOT NULL ,
`caption6` VARCHAR( 250 ) NOT NULL ,
`type6` VARCHAR( 250 ) NOT NULL ,
`show_in_full6` INT NOT NULL ,
`show_in_short6` INT NOT NULL ,
`caption7` VARCHAR( 250 ) NOT NULL ,
`type7` VARCHAR( 250 ) NOT NULL ,
`show_in_full7` INT NOT NULL ,
`show_in_short7` INT NOT NULL ,
`caption8` VARCHAR( 250 ) NOT NULL ,
`type8` VARCHAR( 250 ) NOT NULL ,
`show_in_full8` INT NOT NULL ,
`show_in_short8` INT NOT NULL ,
`caption9` VARCHAR( 250 ) NOT NULL ,
`type9` VARCHAR( 250 ) NOT NULL ,
`show_in_full9` INT NOT NULL ,
`show_in_short9` INT NOT NULL ,
`caption10` VARCHAR( 250 ) NOT NULL ,
`type10` VARCHAR( 250 ) NOT NULL ,
`show_in_full10` INT NOT NULL ,
`show_in_short10` INT NOT NULL ,
`list_caption1` VARCHAR( 250 ) NOT NULL ,
`list_value1` MEDIUMTEXT NOT NULL ,
`list_type1` VARCHAR( 250 ) NOT NULL ,
`list_show_in_full1` INT NOT NULL ,
`list_show_in_short1` INT NOT NULL ,
`list_caption2` VARCHAR( 250 ) NOT NULL ,
`list_value2` MEDIUMTEXT NOT NULL ,
`list_type2` VARCHAR( 250 ) NOT NULL ,
`list_show_in_full2` INT NOT NULL ,
`list_show_in_short2` INT NOT NULL ,
`list_caption3` VARCHAR( 250 ) NOT NULL ,
`list_value3` MEDIUMTEXT NOT NULL ,
`list_type3` VARCHAR( 250 ) NOT NULL ,
`list_show_in_full3` INT NOT NULL ,
`list_show_in_short3` INT NOT NULL ,
`view_all_text` VARCHAR( 250 ) NOT NULL ,
`add_text` VARCHAR( 250 ) NOT NULL ,
`fulllink_text` VARCHAR( 250 ) NOT NULL ,
`congratulation_text` LONGTEXT NULL default '',
`edit_text` LONGTEXT NULL default '',
PRIMARY KEY ( `id_type` ) ,
INDEX ( `caption` ),
INDEX ( `ident` )
);";
$install_sql[]="CREATE INDEX types_id_types USING BTREE ON `%TYPES%` (id_type);";
$install_sql[]="CREATE TABLE `%OBJ%` (
`id_object` BIGINT NOT NULL AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL ,
`new` INT NOT NULL default 1,
`caption` VARCHAR( 250 ) NOT NULL ,
`title` VARCHAR( 250 ) NULL ,
`meta` TEXT NULL ,
`keywords` TEXT NULL ,
`code` VARCHAR( 250 ) NOT NULL ,
`date_create` DATETIME NOT NULL,
`small_content` LONGTEXT,
`small_preview` VARCHAR( 250 ) NOT NULL ,
`middle_preview` VARCHAR( 250 ) NOT NULL ,
`value1` VARCHAR( 250 ) NOT NULL ,
`value2` VARCHAR( 250 ) NOT NULL ,
`value3` VARCHAR( 250 ) NOT NULL ,
`value4` VARCHAR( 250 ) NOT NULL ,
`value5` VARCHAR( 250 ) NOT NULL ,
`value6` VARCHAR( 250 ) NOT NULL ,
`value7` VARCHAR( 250 ) NOT NULL ,
`value8` VARCHAR( 250 ) NOT NULL ,
`value9` VARCHAR( 250 ) NOT NULL ,
`value10` VARCHAR( 250 ) NOT NULL ,
`list1` VARCHAR( 250 ) NOT NULL ,
`list2` VARCHAR( 250 ) NOT NULL ,
`list3` VARCHAR( 250 ) NOT NULL ,
`voters` BIGINT NOT NULL default 0,
`voters_ip` LONGTEXT NOT NULL default '',
`views` BIGINT NOT NULL default 0,
`visible` INT NOT NULL default 1,
PRIMARY KEY ( `id_object` ) ,
INDEX ( `caption` ),
INDEX ( `code` )
);";
$install_sql[]="CREATE INDEX types_id_types USING BTREE ON `%OBJ%` (id_object);";
$install_sql[]="CREATE TABLE `%OBJECT_PICTURES%` (
`id_object` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL ,
`id_image` BIGINT NOT NULL ,
`small_preview` VARCHAR( 250 ) NOT NULL ,
`middle_preview` VARCHAR( 250 ) NOT NULL ,
`big_preview` VARCHAR( 250 ) NOT NULL ,
`sort` BIGINT NOT NULL default 0
);";
$install_sql[]="CREATE INDEX object_pictures_id_object USING BTREE ON `%OBJECT_PICTURES%` (id_object);";
$install_sql[]="CREATE TABLE `%OBJECT_VIDEOS%` (
`id_object` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL ,
`id_video` BIGINT NOT NULL ,
`caption` VARCHAR( 250 ) NOT NULL ,
`filename` VARCHAR( 250 ) NOT NULL ,
`sort` BIGINT NOT NULL default 0
);";
$install_sql[]="CREATE INDEX object_videos_id_object USING BTREE ON `%OBJECT_VIDEOS%` (id_object);";
$install_sql[]="CREATE TABLE `%OBJECT_OBJECTS%` (
`id_object` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL ,
`id_object2` BIGINT NOT NULL ,
`sort` BIGINT NOT NULL default 0
);";
$install_sql[]="CREATE INDEX object_objects_id_object USING BTREE ON `%OBJECT_OBJECTS%` (id_object);";
$install_sql[]="CREATE TABLE `%block_object_types%` (
`id_block` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL
);";
$install_sql[]="CREATE INDEX block_object_types_id_block USING BTREE ON `%block_object_types%` (id_block);";
$install_sql[]="CREATE INDEX block_object_types_id_type USING BTREE ON `%block_object_types%` (id_type);";
$install_sql[]="CREATE TABLE `%OBJECT_FILES%` (
`id_file` BIGINT NOT NULL AUTO_INCREMENT ,
`id_object` BIGINT NOT NULL ,
`id_type` BIGINT NOT NULL ,
`filename` VARCHAR( 250 ) NOT NULL ,
`sort` BIGINT NOT NULL default 0,
`downloaded` BIGINT NOT NULL ,
`downloaded_ip` LONGTEXT NOT NULL default '',
PRIMARY KEY ( `id_file` )
);";
$install_sql[]="CREATE INDEX object_files_id_object USING BTREE ON `%OBJECT_FILES%` (id_object);";
$install_sql[]="CREATE TABLE `%OBJECT_CATEGORIES%` (
`id_type` BIGINT NOT NULL ,
`id_cat` BIGINT NOT NULL 
);";
$install_sql[]="CREATE INDEX block_object_categories_id_type USING BTREE ON `%OBJECT_CATEGORIES%` (id_type);";
$install_sql[]="CREATE INDEX block_object_categories_id_cat USING BTREE ON `%OBJECT_CATEGORIES%` (id_cat);";
$install_sql[]="insert into `%block_types%` values (null,'Добавление объектов','addobjects','objects',1);";
$install_sql[]="insert into `%block_types%` values (null,'Последние объекты','lastobjects','objects',1);";
?>