<?
/*
ћодуль поиска по сайту
*/

define("SCRIPTO_search",true);

class search {
	var $config;
	var $db;
	var $settings;
	var $lang;
	var $smarty;
	var $thismodule;
	var $engine;
	
	function doDb() {
		global $db;
		global $config;
		//$db->addPrefix("%NEWS%",$config["db"]["prefix"]."news");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'‘орма поиска по сайту','',$type_id,'search',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
			return true;
		}  else {
			return false;
		}
	}
	
	function doUninstall() {
		return true;
	}
	
	function doUpdate() {
		return true;
	}
	
	function doBlock($block,$page,&$objects) {
		global $db;
		global $smarty;
		global $engine;
		$surl=$engine->getModuleFullViewUrl("search");
		$smarty->assign("search_url",$surl);
	$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block.tpl.html");
		}
		return $content;
	}
	
	function checkMe() {
	//провер€ем существуют ли уже таблицы модул€
		global $engine;
		if ($engine->checkInstallModule("search")) {
			return true;
		} else {
			return false;
		}
	}
	
	function doAdmin() {
		global $config;
		global $settings;
		global $db;
		global $lang;
		global $smarty;
		global $thismodule;
		global $engine;
		
		if (is_file($this->thismodule["path"]."admin_module.mod.php")) {
			include($this->thismodule["path"]."admin_module.mod.php");
			$content=$smarty->fetch($this->thismodule["template_path"]."admin_module.tpl.html");
			return $content;
		} else {
			return "not load";
		}
	}
	
	function doUser() {
		global $config;
		global $settings;
		global $db;
		global $lang;
		global $smarty;
		global $thismodule;
		global $engine;
		
		if (is_file($this->thismodule["path"]."user_module.mod.php")) {
			include($this->thismodule["path"]."user_module.mod.php");
			$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module.tpl.html");
		}
			return $content;
		} else {
			return "not load";
		}
	}
	
}
?>