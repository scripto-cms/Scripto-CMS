<?
//Установочный файл Scripto CMS
	session_start();
	include("core/functions/main.lib.php");
	$check_zend=false;//проверять Зенд или нет
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
	 $root=str_replace('//','/',$root);
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
	if ($config["install"]==true) die("Программа уже установлена, рекомендуется удалить данный файл.");
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
	//включить вывод ошибок
    @ini_set (display_errors,On);
    @ini_set (error_reporting, E_ALL);
	$critical="<b>Критическая ошибка!</b>";
	if (!is_file($config["classes"]["smarty"]))
		die($critical." Не найдена библиотека Smarty (".$config["classes"]["smarty"].")");
    require($config["classes"]["smarty"]);
	$smarty = new Smarty;
	$smarty->compile_check = true;
	$smarty->debugging = false;
	$smarty->template_dir = $config["pathes"]["templates_dir"];
	if (!is_writable($config["smarty"]["compiledir"])) 
		die($critical." Папка ".$config["smarty"]["compiledir"]." не доступна для записи");
	$smarty->compile_dir = $config["smarty"]["compiledir"];
	$engine->smarty=$smarty;
	$sql_dump=$root."core/sql/install.sql";
	$_SESSION["do_step"]=0;
	if (isset($_REQUEST["step"])) {
		$step=@$_REQUEST["step"];
	} else {
		$_SESSION["step"]=0;
		$step=0;
	}
	$main_template=$config["admin"]["install"];
	if (isset($_REQUEST["save"])) {
		//работаем
		switch ($step) {
			case 0:
				if (isset($_REQUEST["agree"])) {
					$step=1;
					$_SESSION["step"]=1;
					$_SESSION["do_step"]=1;
					header("location: install.php?step=1");
				} else {
					$smarty->assign('do_agree',true);
				}
			break;
			case 1:
					$step=2;
					$_SESSION["step"]=2;
					$_SESSION["do_step"]=2;
					header("location: install.php?step=2");
			break;
			case 2:
				$mailadmin=strip_tags(trim(@$_REQUEST["mailadmin"]));
				$dbprefix=strip_tags(trim(@$_REQUEST["dbprefix"]));
				$dbhost=strip_tags(trim(@$_REQUEST["dbhost"]));
				$dbuser=strip_tags(trim(@$_REQUEST["dbuser"]));
				$dbpassword=strip_tags(trim(@$_REQUEST["dbpassword"]));
				$dbname=strip_tags(trim(@$_REQUEST["dbname"]));
				$smarty->assign("mailadmin",$mailadmin);
				$smarty->assign("dbprefix",$dbprefix);
				$smarty->assign("dbhost",$dbhost);
				$smarty->assign("dbuser",$dbuser);
				$smarty->assign("dbpassword",$dbpassword);
				$smarty->assign("dbname",$dbname);
				if (!$link = @mysql_connect($dbhost,$dbuser,$dbpassword)) {
					$smarty->assign("connect_error",true);
				} else {
					 if (!@mysql_select_db($dbname,$link)) {
					 	$smarty->assign("db_error",true);
					 } else {
						if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$mailadmin)) {
						/*процесс установки*/
						//Генерируем секретный ключ
						$secretkey=rand(100000,9999999999);
						//Логин администратора по умолчанию
						$admin_login="admin";
						$admin_password=generatePassword();
						$smarty->assign("admin_login",$admin_login);
						$smarty->assign("admin_password",$admin_password);
						$md5_password=md5($secretkey.$admin_password);
						$sql=@file_get_contents($sql_dump);
						$search=array("<admin_login>","<admin_password>","%prefix%","<mailadmin>");
						$replace=array($admin_login,$md5_password,$dbprefix,$mailadmin);
						$sql=str_replace($search,$replace,$sql);
						unset($search);
						unset($replace);
						$queries=explode(";",$sql);
						if (is_array($queries)) {
						$mysql_error=false;
						foreach ($queries as $query) {
							if (trim($query)!='') {
							if (!mysql_query(trim($query))) {
								$mysql_error=true;
								$mysql_errors[]=mysql_error();
								echo $query.'<br>';
							}
							}
						}
						$smarty->assign("mysql_error",$mysql_error);
						if ($mysql_error==false) {
						$mysql_config=@file_get_contents($config_dir."mysql.config.php");
					$search=array("<prefix>","<host>","<user>","<password>","<dbname>");
						$replace=array($dbprefix,$dbhost,$dbuser,$dbpassword,$dbname);
						$mysql_config=str_replace($search,$replace,$mysql_config);
						unset($search);
						unset($replace);
						@file_put_contents($config_dir."mysql.config.php",$mysql_config);
						unset($mysql_config);
						$main_config=@file_get_contents($config_dir."main.config.php");
						$search=array("<secret_key>","\$config[\"install\"]=false");
						$replace=array($secretkey,"\$config[\"install\"]=true");
						$main_config=str_replace($search,$replace,$main_config);
						unset($search);
						unset($replace);
						@file_put_contents($config_dir."main.config.php",$main_config);
						unset($main_config);
						$subject="Благодарим Вас за установку Scripto CMS";
						$text="Уважаемый пользователь!<br>Благодарим Вас за установку Scripto CMS на сайт $httproot.<br>Ваши доступы к административной части:<br>Адрес: <a href=\"".$httproot."admin\" target=\"blank\">".$httproot."admin</a><br>Логин администратора: $admin_login <br>Пароль администратора: $admin_password<br>С Уважением, команда разработчиков Scripto CMS.";
						mailHTML($mailadmin,"support@scripto-cms.ru",$subject,$text);
						$smarty->assign("installed",true);
						//$_SESSION["step"]=0;
						} else {
							$smarty->assign("mysql_error",true);
							$smarty->assign("mysql_errors",$mysql_errors);
						}
						} else {
							$smarty->assign("mail_error",true);
						}
						} else {
							$smarty->assign("mysql_error",true);
						}
					 }
				}
			break;
		}
	}
	switch ($step) {
		case 0:
			$smarty->assign("step_title","Шаг 1");
			$smarty->assign("step",$step);
		break;
		case 1:
			$smarty->assign("step_title","Шаг 2");
			$smarty->assign("step",$step);
			$report=array();
			$rep["lib"]="php";
			$rep["value"]="PHP версии 5.x + ";
			if (phpversion() >= '5.0') {
				$rep["install"]=true;
			} else {
				$rep["install"]=false;
			}
			$report[]=$rep;
			unset($rep);
			$rep["lib"]="mysql";
			$rep["value"]="MySQL 4.x +";
			if (function_exists('mysql_connect')) {
				$mysql_str=mysql_get_client_info();
				if (strpos($mysql_str,'5.')===false && strpos($mysql_str,'4.')===false) {
					$rep["install"]=false;
				} else {
					$rep["install"]=true;
				}
			} else {
				$rep["install"]=false;
			}
			$report[]=$rep;
			unset($rep);
			$extensions["curl"]='Библиотека <a href="http://ru2.php.net/manual/en/book.curl.php" target="_blank">CURL</a>';
			$extensions["xml"]='Библиотека <a href="http://ru2.php.net/manual/en/book.xml.php" target="_blank">XML</a>';
			$extensions["gd"]='Библиотека <a href="http://ru2.php.net/manual/en/book.image.php" target="_blank">GD</a>';
			if ($check_zend)
			$extensions["Zend Optimizer"]='<a href="http://zend.com" target="_blank">Zend Optimizer</a>';
			$extensions["mbstring"]='Библиотека <a href="http://ru2.php.net/manual/en/book.mbstring.php" target="_blank">MBString</a>';
			$extensions["json"]='Библиотека <a
 href="http://ru2.php.net/manual/en/book.json.php" target="_blank">JSON</a>';
			$extensions["iconv"]='Библиотека <a href="http://ru2.php.net/manual/en/book.iconv.php" target="_blank">Iconv</a>'; 
			foreach ($extensions as $lib=>$value) {
				$rep["lib"]=$lib;
				$rep["value"]=$value;
					if (extension_loaded($lib)) {
						$rep["install"]=true;
					} else {
						$rep["install"]=false;
					}
				$report[]=$rep;
			}
			$rep["lib"]="mod_rewrite";
			$rep["value"]="Apache mod_rewrite";
			if (apache_is_module_loaded("mod_rewrite")) {
				$rep["install"]=true;
			} else {
				$rep["install"]=false;
			}
			$report[]=$rep;
			unset($rep);
			$smarty->assign("report",$report);
			$setup=array();
			$settings["safe_mode"]="Безопасный режим PHP (Safe Mode)";
			$settings["file_uploads"]="Загрузка файлов";
			$settings["magic_quotes_runtime"]="magic_quotes_runtime";
			foreach ($settings as $zn=>$set) {
				$rep["lib"]=$zn;
				$rep["value"]=$set;
					if (ini_get($zn)) {
						$rep["install"]=true;
					} else {
						$rep["install"]=false;
					}
				$setup[]=$rep;
			}
			$rep["lib"]="register_globals";
			$rep["value"]="Register Globals выключено";
			if (ini_get("register_globals")) {
				$rep["install"]=false;
			} else {
				$rep["install"]=true;
			}
			$setup[]=$rep;
			unset($rep);
			$rep["lib"]="allow_url_fopen";
			$rep["value"]="Открытие удаленных файлов выключено";
			if (ini_get("allow_url_fopen")) {
				$rep["install"]=false;
			} else {
				$rep["install"]=true;
			}
			$setup[]=$rep;
			unset($rep);
			$smarty->assign("setup",$setup);
			$files[]=$root."core/usermodules/modules.install";
			$files[]=$root."config/main.config.php";
			$files[]=$root."config/mysql.config.php";
			$files[]=$root."templates/system/compile/";
			$files[]=$root."cache/";
			$files[]=$root."cache/blocks/";
			$files[]=$root."upload/tiny_mce/";
			$files[]=$root."upload/tiny_mce/images/";
			$files[]=$root."upload/tiny_mce/files/";
			$files[]=$root."upload/data/";
			$files[]=$root."upload/flash/";
			$files[]=$root."upload/images/";
			$files[]=$root."upload/music/";
			$files[]=$root."upload/new/";
			$files[]=$root."upload/thumbnails/middle/";
			$files[]=$root."upload/thumbnails/small/";
			$files[]=$root."upload/thumbnails/system/";
			$files[]=$root."upload/videos/";
			$fs=array();
			foreach ($files as $file) {
				$rep["lib"]=$file;
				$rep["value"]=$file;
					if (@is_writable($file)) {
						$rep["install"]=true;
					} else {
						$rep["install"]=false;
					}
				$fs[]=$rep;
			}
			unset($rep);
			$rep["lib"]=$sql_dump;
			$rep["value"]=$sql_dump;
			if (is_file($sql_dump) && is_readable($sql_dump)) {
				$rep["install"]=true;
			} else {
				$rep["install"]=false;
			}
			$fs[]=$rep;
			unset($rep);
			$smarty->assign("files",$fs);
			$install_error=false;
			foreach ($report as $set)
				if ($set["install"]==false)
					$install_error=true;
			foreach ($fs as $set)
				if ($set["install"]==false)
					$install_error=true;
			$smarty->assign("install_error",$install_error);
			if ($install_error==false) {
//				$_SESSION["step"]=1;
			}
		break;
		case 2:
			$smarty->assign("step_title","Шаг 3");
			$smarty->assign("step",$step);
		break;
	}
	$smarty->assign("step",$step+1);
	$smarty->display($main_template);
?>
