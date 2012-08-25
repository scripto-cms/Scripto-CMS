CREATE TABLE `%prefix%categories` (
`id_category` BIGINT NOT NULL AUTO_INCREMENT ,
`id_sub_category` BIGINT DEFAULT '0' NOT NULL ,
`caption` VARCHAR( 250 ) NOT NULL ,
`content` LONGTEXT,
`subcontent` LONGTEXT,
`title` VARCHAR( 250 ) ,
`meta` TEXT,
`keywords` TEXT,
`category_type` VARCHAR( 250 ) NOT NULL ,
`rss_link` VARCHAR( 250 ) ,
`ident` VARCHAR( 250 ) NOT NULL ,
`visible` INT NOT NULL default 1,
`page404` INT NOT NULL default 0,
`sort` BIGINT NOT NULL default 1,
`create` DATETIME NOT NULL,
`id_tpl` BIGINT DEFAULT '0' NOT NULL ,
`main_page` INT NOT NULL default 0,
`position` VARCHAR( 250 ) default 'up',
`file_content` VARCHAR( 250 ) NOT NULL default '',
`preview` VARCHAR( 255 ) NOT NULL ,
`big_preview` VARCHAR( 255 ) NULL ,
`future_post` INT NOT NULL default 0,
`date_post` DATE NOT NULL,
`preview_width` BIGINT NOT NULL ,
`preview_height` BIGINT NOT NULL ,
`in_navigation` INT NOT NULL default 1,
`is_registered` INT NOT NULL default 0,
`properties` LONGTEXT,
PRIMARY KEY ( `id_category` ) ,
FULLTEXT ( `ident` ),
FULLTEXT ( `caption` )
) ENGINE=MyISAM;

CREATE INDEX category_id_category USING BTREE ON `%prefix%categories` (id_category);
CREATE INDEX category_id_sub_category USING BTREE ON `%prefix%categories` (id_sub_category);

INSERT into `%prefix%categories` values(null,0,'Главная страница','Благодарим Вас за установку Scripto CMS','','','','','text','','main',1,0,0,CURRENT_TIMESTAMP,1,1,'up',0,'','',0,'',0,0,1,0,'');

INSERT into `%prefix%categories` values(null,1,'О компании','','','','','','text','','about',1,0,0,CURRENT_TIMESTAMP,1,0,'up',0,'','',0,'',0,0,1,0,'');
INSERT into `%prefix%categories` values(null,1,'Партнеры','','','','','','text','','partners',1,0,0,CURRENT_TIMESTAMP,1,0,'up',0,'','',0,'',0,0,1,0,'');
INSERT into `%prefix%categories` values(null,1,'Контакты','','','','','','text','','contacts',1,0,0,CURRENT_TIMESTAMP,1,0,'up',0,'','',0,'',0,0,1,0,'');

INSERT into `%prefix%categories` values(null,1,'404 ошибка','','','','','','text','','404',0,1,0,CURRENT_TIMESTAMP,1,0,'up',0,'','',0,'',0,0,1,0,'');

CREATE TABLE `%prefix%photos` (
`id_photo` BIGINT NOT NULL  AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`preview` VARCHAR( 255 ) NOT NULL ,
`big_photo` VARCHAR( 255 ) NOT NULL ,
`medium_photo` VARCHAR( 255 ) NOT NULL ,
`small_photo` VARCHAR( 255 ) NOT NULL ,
`caption` VARCHAR( 255 ) NOT NULL ,
`description` MEDIUMTEXT,
`title` VARCHAR( 255 ) ,
`meta` TEXT,
`main_photo` INT NOT NULL DEFAULT 0,
`visible` INT NOT NULL default 0,
`big_preview` VARCHAR( 255 ) NULL ,
`medium_info` VARCHAR( 255 ) NULL ,
`small_info` VARCHAR( 255 ) NULL ,
PRIMARY KEY ( `id_photo` ) ,
INDEX ( `caption` )
) ENGINE=MyISAM;

CREATE INDEX photos_id_category USING BTREE ON `%prefix%photos` (id_category);
CREATE INDEX photos_id_photo USING BTREE ON `%prefix%photos` (id_photo);

CREATE TABLE `%prefix%settings` (
`caption` VARCHAR( 250 ) NOT NULL ,
`mailadmin` VARCHAR( 250 ) NOT NULL ,
`theme` VARCHAR( 250 ) NOT NULL ,
`language` VARCHAR( 250 ) NOT NULL ,
`description` MEDIUMTEXT,
`title` VARCHAR( 250 ) ,
`meta` TEXT,
`keywords` TEXT,
`small_x` BIGINT DEFAULT '100' NOT NULL ,
`small_y` BIGINT DEFAULT '100' NOT NULL ,
`medium_x` BIGINT DEFAULT '450' NOT NULL ,
`medium_y` BIGINT DEFAULT '450' NOT NULL ,
`login` VARCHAR( 250 ) NOT NULL ,
`pass` VARCHAR( 250 ) NOT NULL ,
`url` VARCHAR (250) NOT NULL ,
`cache_on` INT NOT NULL default 0,
`memcache_on` INT NOT NULL default 0,
`memcache_server` VARCHAR( 15 ) NOT NULL ,
`memcache_port` VARCHAR( 10 ) NOT NULL ,
`ips` MEDIUMTEXT null default '',
`servercode` VARCHAR( 200 ) NOT NULL default '',
`module_notes` INT NOT NULL default 1
) ENGINE=MyISAM;


CREATE TABLE `%prefix%videos` (
`id_video` BIGINT NOT NULL  AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`preview` VARCHAR( 255 ) NOT NULL ,
`filename` VARCHAR( 255 ) NOT NULL ,
`caption` VARCHAR( 255 ) NOT NULL ,
`description` MEDIUMTEXT,
`title` VARCHAR( 255 ) ,
`meta` TEXT,
`main_video` INT NOT NULL DEFAULT 0,
`visible` INT NOT NULL default 0,
`big_preview` VARCHAR( 255 ) NULL ,
`company` VARCHAR( 255 ) NULL ,
`prodolzhitelnost` VARCHAR( 255 ) NULL ,
`external_url` VARCHAR( 255 ) NULL ,
PRIMARY KEY ( `id_video` ) ,
INDEX ( `caption` )
) ENGINE=MyISAM;

CREATE INDEX videos_id_category USING BTREE ON `%prefix%videos` (id_category);
CREATE INDEX videos_id_video USING BTREE ON `%prefix%videos` (id_video);

CREATE TABLE `%prefix%audio` (
`id_audio` BIGINT NOT NULL  AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`preview` VARCHAR( 255 ) NOT NULL ,
`filename` VARCHAR( 255 ) NOT NULL ,
`caption` VARCHAR( 255 ) NOT NULL ,
`description` MEDIUMTEXT,
`title` VARCHAR( 255 ) ,
`meta` TEXT,
`main_audio` INT NOT NULL DEFAULT 0,
`visible` INT NOT NULL default 0,
`big_preview` VARCHAR( 255 ) NULL ,
`label` VARCHAR( 255 ) NULL ,
`prodolzhitelnost` VARCHAR( 255 ) NULL ,
`genre` VARCHAR( 255 ) NULL ,
`external_url` VARCHAR( 255 ) NULL ,
PRIMARY KEY ( `id_audio` ) ,
INDEX ( `caption` )
) ENGINE=MyISAM;

CREATE INDEX audio_id_category USING BTREE ON `%prefix%audio` (id_category);
CREATE INDEX audio_id_audio USING BTREE ON `%prefix%audio` (id_audio);

CREATE TABLE `%prefix%flash` (
`id_flash` BIGINT NOT NULL  AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL ,
`preview` VARCHAR( 255 ) NOT NULL ,
`filename` VARCHAR( 255 ) NOT NULL ,
`caption` VARCHAR( 255 ) NOT NULL ,
`description` MEDIUMTEXT,
`title` VARCHAR( 255 ) ,
`meta` TEXT,
`main_flash` INT NOT NULL DEFAULT 0,
`visible` INT NOT NULL default 0,
`big_preview` VARCHAR( 255 ) NULL ,
`external_url` VARCHAR( 255 ) NULL ,
PRIMARY KEY ( `id_flash` ) ,
INDEX ( `caption` )
) ENGINE=MyISAM;

CREATE INDEX flash_id_category USING BTREE ON `%prefix%flash` (id_category);
CREATE INDEX flash_id_flash USING BTREE ON `%prefix%flash` (id_flash);

CREATE TABLE `%prefix%objects` (
`id_object` BIGINT NOT NULL AUTO_INCREMENT,
`preview` VARCHAR( 255 ) NOT NULL ,
`filename` VARCHAR( 255 ) NOT NULL ,
`caption` VARCHAR( 255 ) NOT NULL ,
`description` VARCHAR( 255 ) NOT NULL ,
`format` VARCHAR( 255 ) NOT NULL ,
`create` TIMESTAMP NOT NULL,
PRIMARY KEY ( `id_object` ) ,
INDEX ( `caption` )
) ENGINE=MyISAM;

CREATE INDEX objects_id_object USING BTREE ON `%prefix%objects` (id_object);

INSERT INTO `%prefix%settings` ( `caption`, `mailadmin` , `theme` , `language` , `description` , `title` , `meta` ,`keywords` , `small_x` , `small_y` , `medium_x` , `medium_y` , `login` , `pass`,`ips`,`servercode`,`module_notes`)
VALUES (
'Scripto CMS','<mailadmin>', 'default', 'ru', '','' , '','', '110', '110', '650', '650' , '<admin_login>' , '<admin_password>','','',1
);


CREATE TABLE `%prefix%templates` (
`id_tpl` BIGINT NOT NULL AUTO_INCREMENT,
`tpl_name` VARCHAR( 255 ) NOT NULL ,
`tpl_type` VARCHAR( 255 ) NOT NULL ,
`tpl_caption` VARCHAR(255) NOT NULL,
`tpl_theme` VARCHAR(255) NOT NULL default 'default',
`tpl_css` VARCHAR(255) NOT NULL default 'style.css',
PRIMARY KEY ( `id_tpl` )
) ENGINE=MyISAM;

CREATE INDEX templates_id_template USING BTREE ON `%prefix%templates` (id_tpl);

INSERT INTO `%prefix%templates` ( `id_tpl` , `tpl_name` , `tpl_type` , `tpl_caption` , `tpl_theme` , `tpl_css`)
VALUES (
null, 'index.tpl.html', 'site', 'Шаблон главной страницы', 'default' ,'style.css'
);

INSERT INTO `%prefix%templates` ( `id_tpl` , `tpl_name` , `tpl_type` , `tpl_caption` , `tpl_theme`, `tpl_css` )
VALUES (
null, 'standart.tpl.html', 'block', 'Стандартный шаблон блока', 'default',''
);

CREATE TABLE `%prefix%blocks` (
`id_block` BIGINT NOT NULL AUTO_INCREMENT ,
`id_category` BIGINT NOT NULL default 0 ,
`caption` VARCHAR( 250 ) NOT NULL ,
`content` LONGTEXT,
`id_type` BIGINT NOT NULL ,
`ident` VARCHAR( 250 ) NOT NULL ,
`visible` INT NOT NULL default 1,
`show_mode` INT NOT NULL default 1,
`id_tpl` BIGINT DEFAULT '0' NOT NULL ,
`number_objects` BIGINT NOT NULL default 0,
`create` DATETIME NOT NULL,
`sort` BIGINT NOT NULL default 0,
PRIMARY KEY ( `id_block` ) ,
FULLTEXT ( `caption` )
) ENGINE=MyISAM;

CREATE INDEX blocks_id_block USING BTREE ON `%prefix%blocks` (id_block);
CREATE INDEX blocks_id_category USING BTREE ON `%prefix%blocks` (id_category);
CREATE INDEX blocks_show_mode USING BTREE ON `%prefix%blocks` (show_mode);

CREATE TABLE `%prefix%block_types` (
`id_type` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NOT NULL ,
`type` VARCHAR( 250 ) NOT NULL ,
`module` VARCHAR ( 250 ) NULL default '',
`cache` INT NOT NULL default 1,
PRIMARY KEY ( `id_type` ) ,
FULLTEXT ( `caption` )
) ENGINE=MyISAM;

insert into `%prefix%block_types` values(null,'Текстовая информация','text','',0);
insert into `%prefix%block_types` values(null,'Текстовые блоки','texts','',0);
insert into `%prefix%block_types` values(null,'Текстовые блоки (random)','random','',0);
insert into `%prefix%block_types` values(null,'Случайное фото','random_photo','',0);

CREATE INDEX blocktypes_id_type USING BTREE ON `%prefix%block_types` (id_type);

CREATE TABLE `%prefix%block_categories` (
`id_block` BIGINT NOT NULL ,
`id_cat` BIGINT NOT NULL
) ENGINE=MyISAM;

CREATE TABLE `%prefix%block_text` (
`id_text` BIGINT NOT NULL AUTO_INCREMENT ,
`id_block` BIGINT NOT NULL default 0 ,
`caption` VARCHAR( 250 ) NOT NULL ,
`content` MEDIUMTEXT,
PRIMARY KEY ( `id_text` ) ,
INDEX ( `caption` )
) ENGINE=MyISAM;

CREATE TABLE `%prefix%block_rss` (
`id_rss` BIGINT NOT NULL AUTO_INCREMENT ,
`id_block` BIGINT NOT NULL default 0 ,
`rss_caption` VARCHAR( 250 ) NOT NULL default '',
`rss_link` VARCHAR( 250 ) NOT NULL default '',
`rss_number` MEDIUMINT NOT NULL default 10,
PRIMARY KEY ( `id_rss` ) ,
INDEX ( `rss_caption` )
) ENGINE=MyISAM;

CREATE TABLE `%prefix%categories_modules` (
`name_module` VARCHAR(255) NOT NULL ,
`id_category` BIGINT NOT NULL ,
FULLTEXT ( `name_module` )
) ENGINE=MyISAM;

CREATE INDEX modules_id_category USING BTREE ON `%prefix%categories_modules` (id_category);

CREATE TABLE `%prefix%static_modules` (
`mod_name` VARCHAR( 255 ) NOT NULL,
`prioritet` INT NOT NULL default 1,
FULLTEXT ( `mod_name` )
) ENGINE=MyISAM;

CREATE TABLE `%prefix%reminders` (
`id_reminder` BIGINT NOT NULL AUTO_INCREMENT ,
`subject` VARCHAR( 250 ) NOT NULL ,
`content` MEDIUMTEXT,
`show_date` DATETIME NOT NULL,
`undelete` INT NOT NULL default 0,
`show` INT NOT NULL default 0,
PRIMARY KEY ( `id_reminder` ),
FULLTEXT ( `subject` )
) ENGINE=MyISAM;

CREATE TABLE `%prefix%notes` (
`id_note` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR( 250 ) NOT NULL ,
`content` MEDIUMTEXT,
`password` VARCHAR( 250 ) NOT NULL ,
`date_create` DATETIME NOT NULL,
PRIMARY KEY ( `id_note` ),
FULLTEXT ( `caption` )
) ENGINE=MyISAM;

CREATE TABLE `%prefix%process` (
`id_process` BIGINT NOT NULL AUTO_INCREMENT ,
`content` VARCHAR(110),
`vazhn` SMALLINT NULL default 0,
`date_create` DATETIME NOT NULL,
`done` INT NOT NULL DEFAULT 0,
`date_done` DATETIME NULL,
PRIMARY KEY ( `id_process` )
) ENGINE=MyISAM;

CREATE TABLE `%prefix%buttons` (
`id_button` BIGINT NOT NULL AUTO_INCREMENT ,
`caption` VARCHAR(100),
`url` VARCHAR(255),
`type` VARCHAR(50) NOT NULL DEFAULT '',
`open_type` INT NOT NULL DEFAULT 0,
`sort` BIGINT NOT NULL DEFAULT 0,
PRIMARY KEY ( `id_button` )
) ENGINE=MyISAM;

CREATE TABLE `%prefix%languages` (
`id_language` BIGINT NOT NULL AUTO_INCREMENT ,
`default` INT NOT NULL default 1,
`ident` VARCHAR( 25 ) NOT NULL,
`caption` VARCHAR( 250 ) NOT NULL,
PRIMARY KEY ( `id_language` )
) ENGINE=MyISAM;

CREATE INDEX languages_id_language USING BTREE ON `%prefix%languages` (id_language);

insert into `%prefix%languages` values(null,1,'ru','Русский язык');