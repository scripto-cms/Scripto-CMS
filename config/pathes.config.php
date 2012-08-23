<?
//Настройки путей к классам, библиотекам и т.п.
	//путь к "движку"
	$config["pathes"]["core_dir"]=$root."core/";
	//путь к классам
	$config["pathes"]["classes_dir"]=$config["pathes"]["core_dir"]."classes/";
	//путь к внешним скриптам
	$config["pathes"]["external_dir"]=$config["pathes"]["core_dir"]."external/";
	//путь к функциям
	$config["pathes"]["functions_dir"]=$config["pathes"]["core_dir"]."functions/";
	//путь к папке javascript
	$config["pathes"]["javascript_dir"]=$config["pathes"]["core_dir"]."javascript/";
	//путь к SQL
	$config["pathes"]["sql_dir"]=$config["pathes"]["core_dir"]."sql/";
	//путь к языковым файлам
	$config["pathes"]["lang_dir"]=$root."languages/";
	//путь к шаблонам
	$config["pathes"]["templates_dir"]=$root."templates/";
	$config["pathes"]["templates_dir_fast"]="templates/";
	//путь к модулям
	$config["pathes"]["modules_dir"]=$config["pathes"]["core_dir"]."modules/";
	//путь к админским модулям
	$config["pathes"]["admin_modules_dir"]=$config["pathes"]["core_dir"]."modules/admin/";
	//путь к файлам-обработчикам контента [admin]
	$config["pathes"]["admin_processor_dir"]=$config["pathes"]["core_dir"]."processor/admin/";
	$config["pathes"]["cache_dir"]=$root."cache/";
	$config["pathes"]["cache_dir_modules"]=$config["pathes"]["cache_dir"]."modules/";
	$config["pathes"]["cache_dir_blocks"]=$config["pathes"]["cache_dir"]."blocks/";

	//путь к дополнительным модулям
	$config["pathes"]["usermodules"]=$config["pathes"]["core_dir"]."usermodules/";
	$config["pathes"]["usermodules_install"]=$config["pathes"]["core_dir"]."usermodules/modules.install";
	$config["pathes"]["usermodules_static"]=$config["pathes"]["core_dir"]."usermodules/modules.static";
	$config["pathes"]["http_usermodules"]=$httproot."core/usermodules/";
	
	//путь к файлам-обработчикам контента [user]
	$config["pathes"]["user_processor_dir"]=$config["pathes"]["core_dir"]."processor/";
	$config["pathes"]["usermodules_fast"]="usermodules/";
	
	//настройки классов
	$config["classes"]["mysql"]=$config["pathes"]["classes_dir"]."db.class.php";
	$config["classes"]["engine"]=$config["pathes"]["classes_dir"]."engine.class.php";
	$config["classes"]["form"]=$config["pathes"]["classes_dir"]."form.class.php";
	$config["classes"]["form_template_1"]="system/classes/form_template_1.tpl.html";

	$config["classes"]["fckeditor"]["path"]=$config["pathes"]["javascript_dir"]."fckeditor/fckeditor.php";
	$config["classes"]["fckeditor"]["basepath"]=$httproot."core/javascript/fckeditor/";
	$config["classes"]["kcaptha"]=$httproot."core/external/kcaptcha/index.php";
	
	//настройки Smarty
	$config["classes"]["smarty"]=$config["pathes"]["external_dir"]."smarty/libs/smarty.class.php";
	$config["classes"]["thumbnail"]["php4"]=$config["pathes"]["external_dir"]."thumbnails/php4/thumbnail.inc.php";
	$config["classes"]["thumbnail"]["php5"]=$config["pathes"]["external_dir"]."thumbnails/php5/thumbnail.inc.php";
	$config["smarty"]["compiledir"]=$config["pathes"]["templates_dir"]."system/compile";
	
	//настройки RSS
	$config["classes"]["rss"]=$config["pathes"]["classes_dir"]."rss.class.php";
	
	//путь к пользовательским файлам
	$config["pathes"]["user_files"]=$root."upload/";
	$config["pathes"]["user_image"]=$config["pathes"]["user_files"]."images/";
	$config["pathes"]["user_video"]=$config["pathes"]["user_files"]."videos/";
	$config["pathes"]["user_music"]=$config["pathes"]["user_files"]."music/";
	$config["pathes"]["user_flash"]=$config["pathes"]["user_files"]."flash/";
	$config["pathes"]["user_data"]=$config["pathes"]["user_files"]."data/";
	$config["pathes"]["user_thumbnails"]=$config["pathes"]["user_files"]."thumbnails/";
	$config["pathes"]["tiny_mce"]=$config["pathes"]["user_files"]."tiny_mce/";
	$config["pathes"]["user_files_http"]=$httproot."upload/";
	$config["pathes"]["user_image_http"]=$config["pathes"]["user_files_http"]."images/";
	$config["pathes"]["user_video_http"]=$config["pathes"]["user_files_http"]."videos/";
	$config["pathes"]["user_music_http"]=$config["pathes"]["user_files_http"]."music/";
	$config["pathes"]["user_flash_http"]=$config["pathes"]["user_files_http"]."flash/";
	$config["pathes"]["user_data_http"]=$config["pathes"]["user_files_http"]."data/";
	$config["pathes"]["user_thumbnails_http"]=$config["pathes"]["user_files_http"]."thumbnails/";
	$config["pathes"]["user_upload"]=$root."upload/new/";
	$config["pathes"]["user_upload_http"]=$httproot."upload/new/";
	$config["pathes"]["tiny_mce_http"]=$config["pathes"]["user_files_http"]."tiny_mce/";
	
	//настройки шаблонов
	$config["templates"]["admin"]="admin/";
	$config["templates"]["user"]="system/frontend/";
	$config["templates"]["themes"]="themes/";
	$config["templates"]["css"]="css/";
	$config["templates"]["admin_modules"]=$config["templates"]["admin"]."modules/";
	$config["templates"]["user_modules"]="system/modules/";
	$config["templates"]["admin_processor"]=$config["templates"]["admin"]."processor/";
	$config["templates"]["user_processor"]="system/processor/";	
	$config["templates"]["usermodules_templates"]=$config["templates"]["admin"]."usermodules/";
	
	//настройки путей с http стороны
	//путь к картинкам
	$config["http"]["root"]=$httproot;
	$config["http"]["images"]=$httproot."images/";
	$config["http"]["admin_images"]=$config["http"]["images"]."admin/";
	$config["http"]["image_modules"]=$config["http"]["admin_images"].'modules/';
	$config["http"]["javascript"]=$httproot."core/javascript/";
	
	//пути к шаблонам админки
	$config["admin"]["splashscreen"]=$config["templates"]["admin"]."enter.tpl.html";
	$config["admin"]["splashscreen_css"]=$config["templates"]["css"]."admin_spashscreen.css";
	$config["admin"]["main"]=$config["templates"]["admin"]."admin.tpl.html";
	$config["admin"]["main_css"]=$config["templates"]["css"]."admin_main.css";
	$config["admin"]["white"]=$config["templates"]["admin"]."white.tpl.html";
	$config["admin"]["white_css"]=$config["templates"]["css"]."white_main.css";	
	
	//путь к шаблону инсталлятора
	$config["admin"]["install"]=$config["templates"]["admin"]."install.tpl.html";	
	
	//пользовательские шаблоны
	$config["user"]["white"]=$config["templates"]["user"]."white.tpl.html";
	$config["user"]["white_css"]=$config["templates"]["css"]."white_main.css";
	
	//путь к функциям админки
	$config["pathes"]["image_dialog"]=$httproot.'admin/?module=objects&modAction=changepreview&ajax=true';
?>