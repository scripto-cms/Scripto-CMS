<?
//Scripto CMS
/*
Разработчик Иванов Дмитрий
Начало разработки - 26.04.08
e-mail: support@scripto-cms.ru
*/
session_start();
/*ddos detector*/
/*
$time=getmicrotime();
if (isset($_SESSION["time"])) {
	if (($time-$_SESSION["time"])<0.4) {
		$_SESSION["block"]=true;
	}
}
$_SESSION["time"]=$time;

if (isset($_SESSION["block"])) {
	if ($_SESSION["block"]==true) {
		header('HTTP/1.0 503 Service Unavailable');
		echo @file_get_contents('503.html');
		flush();
		die();
	}
}
*/
/*end of ddos detector*/
if(!ini_get('zlib.output_compression')) @ob_start("ob_gzhandler"); 
header("Expires: Mon, 12 Jul 2005 12:13:13 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
} 
$time_start=getmicrotime();
define("time_start",$time_start);
define("SCRIPTO_GALLERY",true);
setlocale(LC_ALL, "ru_RU.CP1251");
//ini_set('zlib.output_compression', 'On');
//Вывод ошибок
	$err=@$_REQUEST["debug"];
		if ($err) {
			//включить вывод ошибок
		   @ini_set (display_errors,On);
		   @ini_set (error_reporting, E_ALL);
		if ($err=="phpinfo") {
			//вывести phpinfo
			phpinfo();die();
		}
		if ($err=="check") {
			//проверить технические требования
			header("Location:http://".$_SERVER["HTTP_HOST"]."/?user_module=check",true,301);
			die();
		}
		} else {
		   @ini_set (display_errors,Off);
		   @ini_set (error_reporting, E_ALL);
		}
	include("core/functions/main.lib.php");
		if (get_magic_quotes_gpc()) {
			@ini_set("magic_quotes_gpc",false);
			@ini_set("magic_quotes_runtime", false);
			@ini_set("magic_quotes_sybase", false);	
		}
	clearRequest();
	include("core/functions/a.charset.php");
//определяем пути
	//имя сайта
	$host="http://".$_SERVER["HTTP_HOST"];
	$path=pathinfo($_SERVER["PHP_SELF"]);
	 if (($path["dirname"]!="\\") && ($path["dirname"]!="/")) {
		if  (substr($path["dirname"],0,1)=="/") {
		 $prist="";
		} else {
		 $prist="/";
		}
		 $root=$_SERVER["DOCUMENT_ROOT"]."/".noSlash($path["dirname"])."/";
		 $httproot="http://".$_SERVER["HTTP_HOST"]."/".noSlash($path["dirname"])."/";
	 } else {
		 $root=$_SERVER["DOCUMENT_ROOT"].'/';
		 $httproot="http://".$_SERVER["HTTP_HOST"]."/";
	 }
	 
	 $_SESSION["scripto_root"]=$root;
	 $_SESSION["scripto_httproot"]=$httproot;
	header("HTTP/1.0 200 OK");
//загружаем конфиги
	$config_dir=$root."config/";
	if (is_file($config_dir."main.config.php")) {
		include($config_dir."main.config.php");
	} else {
		die("couldn't found main config ".$config_dir."main.config.php");
	}
	if (is_file($config_dir."images.config.php")) {
		include($config_dir."images.config.php");
	} else {
		die("couldn't found main config ".$config_dir."images.config.php");
	}
	if (is_file($config_dir."mysql.config.php")) {
		include($config_dir."mysql.config.php");
	} else {
		die("couldn't found main config ".$config_dir."mysql.config.php");
	}
	if (is_file($config_dir."pathes.config.php")) {
		include($config_dir."pathes.config.php");
	} else {
		die("couldn't found main config ".$config_dir."pathes.config.php");
	}
	if (is_file($config_dir."languages.config.php")) {
		include($config_dir."languages.config.php");
	} else {
		die("couldn't found main config ".$config_dir."languages.config.php");
	}
	if ($config["debug_mode"]) {
		//включить вывод ошибок
	   @ini_set (display_errors,On);
	   @ini_set (error_reporting, E_ALL);
	}

	if ($config["install"]==false)
		header("location: install.php");
//подгружаем обязательные классы
	include($config["classes"]["mysql"]);
	include($config["classes"]["engine"]);
//подключаемся к базе данных
	$db=new Db();
	$db->config=$config;
	$db->SQLConnect();
	$db->setPrefix();
//Инициализация прочих классов
	$engine=new Engine();
	$engine->db=$db;
	$engine->config=$config;
	$languages=$engine->getListLanguages();
	$engine->languages=$languages;
	$engine->tables=$tables;
	$settings=$engine->getSettings();
	$settings["httproot"]=$httproot;
	$settings["root"]=$root;
	$settings["day"]=date("d");
	$settings["month"]=date("m");
	$settings["year"]=date("Y");
	$settings["hour"]=date("H");
	$settings["minute"]=date("i");
	$settings["second"]=date("S");
//подключаем языковые файлы
	if (is_dir($config["pathes"]["lang_dir"].$settings["language"])) {
		if (is_file($config["pathes"]["lang_dir"].$settings["language"]."/main.lang.php")) {
include($config["pathes"]["lang_dir"].$settings["language"]."/main.lang.php");
		} else {
			die("Not found file main.lang.php");
		}
	} else {
		die("Couldn't find languages dir (".$config["pathes"]["lang_dir"].$settings["language"].")");
	}
//подключаем сторонние скрипты (Smarty и т.п.)
     require($config["classes"]["smarty"]);
	 $smarty = new Smarty;
	 $smarty->compile_check = true;
	 $smarty->debugging = false;
	 $smarty->template_dir = $config["pathes"]["templates_dir"];
	 $smarty->compile_dir = $config["smarty"]["compiledir"];
//дополняем $engine
	$engine->smarty=$smarty;
	$engine->lang=$lang;
//подключаем к смарти основные переменные
	$smarty->assign("config",$config);
	$smarty->assign("settings",$settings);
	$smarty->assign("lang",$lang);
	$smarty->assign("siteurl",$httproot);
	$smarty->assign("img",$config["http"]["images"]);
	$smarty->assign("js",$config["http"]["javascript"]);
	$smarty->assign("languages",$languages);
	$smarty->assign("user_upload",$config["pathes"]["user_files_http"]);
	$smarty->assign("user_images",$config["pathes"]["user_image_http"]);
	$smarty->assign("user_video",$config["pathes"]["user_video_http"]);
	$smarty->assign("user_music",$config["pathes"]["user_music_http"]);
	$smarty->assign("user_flash",$config["pathes"]["user_flash_http"]);
	$smarty->assign("user_thumbnails",$config["pathes"]["user_thumbnails_http"]);
	
	/*дата и время*/
	
	$curryear=$settings["year"];
	$j=0;
		for ($s=$curryear-2;$s<=$curryear+3;$s++) {
			$years[$j]=$s;
			$j++;
		}
		for ($j=1;$j<=12;$j++) $months[$j-1]=$j;
		for ($j=1;$j<=31;$j++) $days[$j-1]=$j;
			$j=0;
		for ($j=1;$j<=24;$j++) $hours[$j-1]=$j-1;
		for ($j=1;$j<=60;$j++) $minutes[$j-1]=$j-1;
	$smarty->assign("years",$years);
	$smarty->assign("months",$months);
	$smarty->assign("days",$days);
	$smarty->assign("hours",$hours);
	$smarty->assign("minutes",$minutes);
	
	$ajax=false;
	if (isset($_REQUEST["ajax"])) {
		$ajax=true;
		$smarty->assign("ajax",true);
		header('Content-Type: text/html; charset=windows-1251');
	}

	//инклудим все модули
	/*здесь тормоза*/
	$engine->modules=$engine->loadModules();
	/*конец тормозам*/
	//работаем
	$id_rubric=noSlash(trim(@$_REQUEST["id_rubric"]));
	if ($id_rubric=="admin") {
		//админка
		//Проверяем разрешен ли доступ с данного IP
		if (!checkIp($settings["ips"],getIp())) {
			header("location: $httproot");
		}
		//переменные для админа
		$smarty->assign("admin_templates",$config["templates"]["admin"]);
		$smarty->assign("admin_images",$config["http"]["admin_images"]);
		$smarty->assign("admin_icons",$config["http"]["admin_images"].'icons/');
		$smarty->assign("admin_module_icon",$config["http"]["image_modules"]);
		
		//получаем установленные модули
		$installed_modules=$engine->getInstallModulesFast();
		$smarty->assign("installed_modules",$installed_modules);
		
		//получаем информацию о пользователе
		$user=$engine->getAdmin();
		$smarty->assign("user",$user);
		$engine->user=$user;
		switch ($user["status"]) {
			case 2:
				//авторизованы
				$module=trim(@$_REQUEST["module"]);
				if ($module=="") $module="main";
					if ($ajax) {
					$main_template=$config["admin"]["white"];
					} else {
					$main_template=$config["admin"]["main"];
					}
				$page=$engine->processModule($module,"admin");
				$smarty->assign("admin_module",$module);
				$engine->assignCongratulation();
			break;
			case 1:
				//ошибка ввода логина\пароля
				$main_template=$config["admin"]["splashscreen"];
				$main_css=$config["admin"]["splashscreen_css"];
			break;
			case 0:
				//не авторизованы
				$main_template=$config["admin"]["splashscreen"];
				$main_css=$config["admin"]["splashscreen_css"];
			break;
		}
	} else {
		//пользовательский интерфейс
		//если требуется просто загрузка определенного модуля
		$user_module=false;
		if (isset($_REQUEST["user_module"])) {
			if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$_REQUEST["user_module"]))
				 $user_module=$_REQUEST["user_module"];
		}
		if ($user_module) {
			$main_template=$config["user"]["white"];
			$main_css=$config["user"]["white_css"];
			$page=$engine->processModule($user_module,"user");
		} else {
			//Обрабатываем язык
			$db->user_mode=true;
			$mainlang=$engine->getLanguage(&$id_rubric);
			$smarty->assign("mainlang",$mainlang);
			$engine->current_language=$mainlang;
			if ($languages[$mainlang]["default"]==0) {
				$engine->current_prefix='_'.$mainlang;
				$current_lang=$mainlang.'/';
				if (is_file($config["pathes"]["lang_dir"].$current_lang."/main.lang.php")) {
					include($config["pathes"]["lang_dir"].$current_lang."/main.lang.php");
				}
			} else {
				$engine->current_prefix="";
				$current_lang='';
			}
			$db->current_prefix=$engine->current_prefix;
			$smarty->assign("current_prefix",$engine->current_prefix);
			$smarty->assign("current_lang",$current_lang);
			$db->internal_number=0;
			$settings=$db->convertArrayToLang($settings);
			$smarty->assign("settings",$settings);
			//показываем рубрики
			if ($id_rubric=="") {
				//главная страница
				$page=$engine->getMainPage();
			} else {
				$page=$engine->getCategoryByIdent($id_rubric);
				if (!is_array($page)) {
					//выводим 404 ошибку
					header('HTTP/1.0 404 Not Found', true, '404');
					header('Content-Type: text/html; charset=windows-1251');
					$page=$engine->get404Page();
					if (!is_array($page))
						$page=$engine->getMainPage();
					$page["error404"]=true;
				}
			}
				if (isset($page)) 
				if (is_array($page)) {
					if (isset($page["template"]["path"])) {
				$template=$page["template"];
				$main_template=$config["templates"]["themes"].$page["template"]["path"];
		$main_css=$config["templates"]["css"].$page["template"]["tpl_theme"]."/".$page["template"]["tpl_css"];
					$smarty->assign("img_theme",$config["http"]["images"]."themes/".$template["tpl_theme"]."/");

					//проверка на iPhone
					$engine->iPhoneCheck();
					//проверка на iPad
					$engine->iPadCheck();
					
					//получаем путь к текущим рубрикам
					$path=$engine->getPath($page["id_category"]);
					foreach ($path as $pth) {
						$config["path"][]=$pth["id_category"];
					}
					
					//получаем все рубрики
					$rubrics=$engine->getAllPositionsRubrics();
					$engine->rubrics=$rubrics;
					
					//получаем адреса
					$urls=array();
					foreach ($rubrics as $position)
						foreach ($position as $r)
							$urls[$r["id_category"]]=$current_lang.$r["ident"];
					$engine->urls=$urls;
					$smarty->assign("urls",$urls);
					
					//Устанавливаем дополнительные свойства для рубрики и пути к рубрике
					$engine->getPageInfo($page);
					$path=$engine->getPathInfo($path);
					
					//устанавливаем page для основного класса
					$engine->page=$page;
					
					//получаем контент для подразделов
					$params=array();
					
					foreach ($rubrics as $pos=>$r) {
						foreach ($r as $key=>$r2) {
							//просматриваем разделы
							if ($r2["parent"]==$page["id_category"]) {
								$this_type=$rubrics[$pos][$key]["category_type"];
								$params[$pos][$this_type][$key]=$r2["id_category"];
							}
						}
					}

					$engine->getElementsByCat($rubrics,$params,1);
					$smarty->assign("rubrics",$rubrics);
					
					//получаем родителя
					if (isset($path[sizeof($path)-2]) && (sizeof($path)-2)>0)
						$smarty->assign("parent",$path[sizeof($path)-2]);
					
					//получаем модули, которые подключаются в независимости от раздела
					$static_modules=$engine->getStaticModules($page);
					
					$page["content"]=$engine->checkRegistered($page["content"]);
					$page=$engine->getContentCategory($page);
					$engine->page=$page;
					
					//получаем блоки
					//получаем блоки, которые должны быть на всех страницах
					$types=$engine->getBlockTypes();
					$blocks=array();
					$allblocks=$engine->getBlocks("allblocks",$page,$types);
					if (is_array($allblocks)) {
					$blocks=$allblocks;
					}
					unset($allblocks);
					$thispage_blocks=$engine->getBlocks("page",$page,$types);
					if (is_array($thispage_blocks)) {
					$blocks=array_merge($blocks,$thispage_blocks);
					}
					unset($thispage_blocks);
					$smarty->assign("blocks",$blocks);
					//получаем модули текущей рубрики
					$modules=$engine->getModulesByCategory($page["id_category"],$page);
					$page["modules"]=$modules;
					$page["static_modules"]=$static_modules;
					$smarty->assign("modules",$modules);
					$smarty->assign("static_modules",$static_modules);
					
					//обрабатываем текущую рубрику и получаем контент
					$content=$engine->getContentByPage($page);
					$smarty->assign("content",$content);
					
					//Устанавливаем дополнительные свойства пути
					$path=$engine->getSubPath($path);
					$real_path=$engine->getRealPath($path);
					$smarty->assign("real_path",$real_path);
					$smarty->assign("path",$path);
					
					$smarty->assign("jquery",'<script type="text/javascript" src="/images/themes/default/js/jquery-1.4.2.min.js"></script><script type="text/javascript" src="/images/themes/default/js/lightbox/js/jquery.lightbox.js"></script>');
					
					}
				} else {
					die($lang["error"]["not_found_mainpage"]);
				}
		}
	}
	if (isset($page)) {
		$smarty->assign("page",$page);
	}
$time_end=getmicrotime();
if (isset($_REQUEST["speed"])) echo "Ядро загрузилось за ".($time_end-$time_start)." секунд";	
	if (isset($_REQUEST["white"])) $smarty->assign("white",true);
	//рисуем шаблон
	if (isset($main_template)) {
		if (is_file($config["pathes"]["templates_dir"].$main_template)) {
			if (isset($main_css)) {
				$css=$smarty->fetch($main_css);
				$smarty->assign("css",$css);
			}
			$smarty->display($main_template);
		} else {
			die($lang["error"]["not_found_template"]."(".$main_template.")");
		}
	} else {
		die($lang["error"]["not_set_template"]);
	}
$time_end=getmicrotime();
if (isset($_REQUEST["speed"])) {
echo "<p>Страница с шаблоном сгенерирована за ".($time_end-$time_start)." секунд</p>";
$mem=$engine->get_memory_usage();
echo "<p>Скрипт потребляет памяти: <b>".@$mem["percent"]."</b>% (".@$mem["usage"]." из ".@$mem["limit"].")</p>";
}
?>