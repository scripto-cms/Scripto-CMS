<?
		$root=$_SESSION["scripto_root"];
		$check_zend=true;
		$sql_dump=$root."core/sql/install.sql";
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
			$setting["safe_mode"]="Безопасный режим PHP (Safe Mode)";
			$setting["file_uploads"]="Загрузка файлов";
			$setting["magic_quotes_runtime"]="magic_quotes_runtime";
			foreach ($setting as $zn=>$set) {
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
			$smarty->assign("setups",$setup);
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
?>