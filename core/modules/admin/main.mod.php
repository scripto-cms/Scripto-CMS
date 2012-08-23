<?
/*
Модуль получения общей информации, новостей компании и статистики
*/
if (defined("SCRIPTO_GALLERY")) {

$page["title"]=$lang["modules"]["main"];
$smarty->assign("page",$page);
$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/mainwindow/");
$this->setAdminTitle($lang["modules"]["main"]);

$modAction=@$_REQUEST["modAction"];
switch ($modAction) {
	case "requirements":
		$this->addPath($lang["interface"]["main"],'/admin',true);
		$this->addPath($lang["interface"]["requirements"],'',false);
		$this->assignPath();
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
	break;
	case "edit":
		$this->addPath($lang["interface"]["main"],'/admin',true);
		$this->addPath($lang["interface"]["edit_main_buttons"],'',false);	
		$this->assignPath();
		if (isset($_REQUEST["add"])) {
			//добавляем
			$caption=@$_REQUEST["caption"];
			$url=@$_REQUEST["url"];
			$button_type=@$_REQUEST["button_type"];
			$href_type=@$_REQUEST["href_type"];
			if (!preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$url))
				$url='';
			if ($href_type>=0 && preg_match("/^[a-zA-Z0-9]{1,}$/i",$button_type)) {
				$button_id=$this->createButton($caption,$url,$button_type,$href_type);
				if ($button_id>0) {
					$this->setCongratulation('',$lang["congratulation"]["button_add"],3000);
				}
			}
		}
		if (isset($_REQUEST["save"])) {
			//обновление
			$idbutton=@$_REQUEST["idbutton"];
			$caption=@$_REQUEST["caption"];
			$url=@$_REQUEST["url"];
			$button_type=@$_REQUEST["button_type"];
			$href_type=@$_REQUEST["href_type"];
			$sort=@$_REQUEST["sort"];
			$del=@$_REQUEST["del"];
			if (is_array($idbutton)) {
			$d=0;
			$u=0;
				foreach ($idbutton as $id_button) {
					if (isset($del[$id_button])) {
						$db->query("delete from `%buttons%` where id_button=$id_button");
						$d++;
					} else {
						if (!preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$url[$id_button]))	{
							$url_str='';
						} else {
							$url_str=", `url`='".sql_quote(@$url[$id_button])."'";
						}
						if (preg_match("/^[0-9]{1,}/i",$href_type[$id_button])) {
							$href_str=", `open_type`=".$href_type[$id_button];
						} else {
							$href_str='';
						}
						if (preg_match("/^[a-zA-Z0-9]{1,}/i",$button_type[$id_button])) {
							$button_str=", `type`='".sql_quote($button_type[$id_button])."'";
						} else {
							$button_str='';
						}
						if (preg_match("/^[0-9]{1,}/i",$sort[$id_button])) {
							$sort_str=", `sort`=".$sort[$id_button];
						} else {
							$sort_str='';
						}
						$db->query("update `%buttons%` set `caption`='".@$caption[$id_button]."' $url_str $href_str $button_str $sort_str where id_button=$id_button");
						$u++;
					}
				}
				$this->setCongratulation('',"Обновлено $u кнопок, удалено $d кнопок",5000);
			}
		}
		$buttons=$this->getButtons();
		$smarty->assign("buttons",$buttons);
	break;
	default:
		$buttons=$this->getButtons();
		$smarty->assign("buttons",$buttons);

		/*получаем новости Scripto CMS*/
		$news=@$this->loadContent('http://scripto-cms.ru/news.htm');
		$smarty->assign("scripto_news",$news);
		/*конец получения новостей Scripto CMS*/
}
$smarty->assign("modAction",$modAction);
}
?>