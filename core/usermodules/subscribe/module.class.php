<?
/*
Модуль Опросы
*/

define("SCRIPTO_subscribe",true);

class subscribe {
	var $config;
	var $db;
	var $settings;
	var $lang;
	var $smarty;
	var $thismodule;
	var $engine;
	var $page;
	
	function doDb() {
		global $db;
		global $config;
		$db->addPrefix("%email%",$config["db"]["prefix"]."email");
		$db->addPrefix("%archive%",$config["db"]["prefix"]."email_archive");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Подписаться на рассылку','',$type_id,'subscribe',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
		global $thismodule;
		global $engine;
		global $lang;
		if (isset($_REQUEST["addnew"])) {
			if (isset($_REQUEST["email"])) {
				$email=$_REQUEST["email"];
				if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email)) {
					$name='';
					if (isset($_REQUEST["name"])) {
						$name=$_REQUEST["name"];
					}
					if ($this->addToSubscribe($email,$name)) {
						$smarty->assign("email_add",true);
					} else {
						$smarty->assign("email_exist",true);
					}
				} else {
					$smarty->assign("email_error",true);
				}
			}
		}
		$smarty->assign("thismodule",$this->thismodule);
		$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block.tpl.html");
		}
		return $content;
	}
	
	function checkMe() {
	//проверяем существуют ли уже таблицы модуля
		global $engine;
		if ($engine->checkInstallModule("subscribe")) {
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
	
	function addToSubscribe($email,$name='') {
		global $db;
		if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email)) {
			if (!$this->emailExist($email)) {
				if ($db->query("insert into `%email%` values(null,'$email','".sql_quote($name)."')")) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function emailExist($email) {
		global $db;
		if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email)) {
			$count=$db->getCount("select * from `%email%` where `email`='".sql_quote($email)."'");
			if ($count>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getCountEmails($str='') {
		global $db;
		$str_sql='';
		if (trim($str)!='') {
			$str_sql="where `email` LIKE '$str' or `name` LIKE '$str'";
		}
		$res=$db->query("select id_email from `%email%` $str_sql");
		return @mysql_num_rows($res);
	}
	
	function getEmails($str='',$page=0,$onpage=20) {
		global $db;
		$str_sql='';
		if (trim($str)!='') {
			$str_sql="where `email` LIKE '$str' or `name` LIKE '$str'";
		}
		$res=$db->query("select * from %email% $str_sql order by `email` LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$emails[]=$row;
		}
		if (isset($emails)) return $emails;
		return false;
	}
	
	function getAllEmails() {
		global $db;
		$res=$db->query("select * from `%email%` order by `email`");
		while ($row=$db->fetch($res)) {
			$emails[$row["email"]]=$row["name"];
		}
		if (isset($emails)) return $emails;
		return false;
	}
	
	function createArchive($caption='',$backmail='',$description='') {
		global $db;
		if ($db->query("insert into `%archive%` values (null,'".sql_quote($caption)."','".sql_quote($backmail)."','".sql_quote($description)."',null)")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getCountArchives() {
		global $db;
		$res=$db->query("select id_archive from `%archive%`");
		return @mysql_num_rows($res);
	}
	
	function getArchives($page=0,$onpage=20) {
		global $db;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d.%m.%Y') as `date_print` from %archive% order by `date` desc LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$archives[]=$row;
		}
		if (isset($archives)) return $archives;
		return false;
	}
	
	function getArchiveByID($id_archive) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_archive)) {
			$res=$db->query("select * from `%archive%` where id_archive=$id_archive");
			return @$db->fetch($res);
		} else {
			return false;
		}
	}
}
?>
