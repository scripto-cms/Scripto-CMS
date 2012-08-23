<?
/*
Модуль новостей
*/

define("SCRIPTO_comments",true);

class comments {
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
		$db->addPrefix("%COMMENTS%",$config["db"]["prefix"]."comments");
	}
	
	function doInstall() {
		global $db;
		return true;
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
		//$block["number_objects"]
		$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block.tpl.html");
		}
		return $content;
	}
	
	function doStatic() {
		global $config;
		global $settings;
		global $db;
		global $lang;
		global $smarty;
		global $thismodule;
		global $engine;
		
		if (is_file($this->thismodule["path"]."user_module.mod.php")) {
			include($this->thismodule["path"]."user_module.mod.php");
		} else {
			return "not load";
		}
	}
	
	function showAddForm() {
		$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module.tpl.html");
		}
		return $content;
	}
	
	function checkMe() {
	//проверяем существуют ли уже таблицы модуля
		global $engine;
		if ($engine->checkInstallModule("comments")) {
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
	
	function getNewReminders() {
		global $db;
		$res=$db->query("SELECT id_comment from `%COMMENTS%` where `new`=1");
		$count=@mysql_num_rows($res);
		if ($count>0) {
			$reminder['subject']=ToUTF8($this->thismodule["caption"]);
			$reminder['content']=ToUTF8('У Вас '.@mysql_num_rows($res).' новых комментаря(ев)');
			$reminder['count']=$count;
			return $reminder;
		} else {
			return false;
		}
	}
	
	function addComment($type,$id_object,$nickname,$comment,$new=1) {
		global $db;
		global $smarty;
		if ($new!=1) $new=0;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		if (!preg_match("/^[a-zA-Z0-9]{1,}$/i",$type)) return false;
		$url=strtolower($_SERVER["REQUEST_URI"]);
		$uniq_key=md5($comment);
		if ($this->existComment($uniq_key)) return false;
		@include($this->thismodule["path"]."parse.class.php");
		$parse=new ParseFilter();
		$comment=$parse->BB_Parse($comment,false);
		if ($db->query("insert into `%COMMENTS%` values (null,0,$id_object,'$type',$new,'".sql_quote($nickname)."','','".sql_quote($url)."','".$uniq_key."','".sql_quote($comment)."',null)")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function existComment($uniq_key) {
		global $db;
		$res=$db->query("select * from `%COMMENTS%` where `uniq_key`='".$uniq_key."'");
		if (mysql_num_rows($res)>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function getCommentsByObject($id_object,$type) {
		global $db;
		global $smarty;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		if (!preg_match("/^[a-zA-Z0-9]{1,}$/i",$type)) return false;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%COMMENTS%` where id_object=$id_object and `type`='$type' order by `date` DESC");
		while ($row=$db->fetch($res)) {
			$comments[]=$row;
		}
		if (isset($comments)) {
			return $comments;
		} else {
			return false;
		}
	}
	
	function getNewCommentsCount() {
		global $db;
		$res=$db->query("select `id_comment` from `%COMMENTS%` where `new`=1");
		return @mysql_num_rows($res);
	}
	
	function getNewComments($page,$onpage) {
		global $db;
		global $smarty;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%COMMENTS%` where `new`=1 order by `date` DESC LIMIT ".($page*$onpage).",".$onpage);
		while ($row=$db->fetch($res)) {
			$comments[]=$row;
		}
		if (isset($comments)) {
			return $comments;
		} else {
			return false;
		}
	}
	
	function getUrlCommentsCount($url='') {
		global $db;
		$res=$db->query("select `id_comment` from `%COMMENTS%` where `url` LIKE '%".sql_quote($url)."'");
		return @mysql_num_rows($res);
	}
	
	function getUrlComments($url='',$page,$onpage) {
		global $db;
		global $smarty;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%COMMENTS%` where `url` LIKE '%".sql_quote($url)."' order by `date` DESC LIMIT ".($page*$onpage).",".$onpage);
		while ($row=$db->fetch($res)) {
			$comments[]=$row;
		}
		if (isset($comments)) {
			return $comments;
		} else {
			return false;
		}
	}
	
	function getStrCommentsCount($str='') {
		global $db;
		$res=$db->query("select `id_comment` from `%COMMENTS%` where `content` LIKE '".sql_quote($str)."' or `nickname` LIKE '".sql_quote($str)."'");
		return @mysql_num_rows($res);
	}
	
	function getStrComments($str='',$page,$onpage) {
		global $db;
		global $smarty;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%COMMENTS%` where  `content` LIKE '".sql_quote($str)."' or `nickname` LIKE '".sql_quote($str)."' order by `date` DESC LIMIT ".($page*$onpage).",".$onpage);
		while ($row=$db->fetch($res)) {
			$comments[]=$row;
		}
		if (isset($comments)) {
			return $comments;
		} else {
			return false;
		}
	}
}
?>