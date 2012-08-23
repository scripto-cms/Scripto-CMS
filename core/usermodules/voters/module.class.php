<?
/*
Модуль Опросы
*/

define("SCRIPTO_voters",true);

class voters {
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
		$db->addPrefix("%VOTERS%",$config["db"]["prefix"]."voters");
		$db->addPrefix("%VOTERS_IP%",$config["db"]["prefix"]."voters_ip");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Опрос','',$type_id,'voters',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
		$smarty->assign("thismodule",$this->thismodule);
		$this->doVote();
		$voters=$this->getAllCurrentVoters($block["number_objects"]);
		$smarty->assign("voters",$voters);
		$url=$engine->getModuleFullViewUrl("voters");
		if (trim($url)!="") {
			$smarty->assign("vote_url",$url);
		}
		$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block.tpl.html");
		}
		return $content;
	}
	
	function doVote() {
		global $engine;
		global $smarty;
		if (isset($_REQUEST["id_vote"])) {
			$id_vote=$_REQUEST["id_vote"];
			$otvet=@$_REQUEST["otvet"];
			if (preg_match("/^[0-9]{1,}$/i",$id_vote) && preg_match("/^[0-9]{1,}$/i",$otvet)) {
				$ip=$engine->getIp();
				if (!$this->voted($ip,$id_vote) && !isset($_SESSION["votes"][$id_vote])) {
					$this->vote($id_vote,$otvet,$ip);
				}
			}
			$smarty->assign("show_result",$id_vote);
		}
	}
	
	function checkMe() {
	//проверяем существуют ли уже таблицы модуля
		global $engine;
		if ($engine->checkInstallModule("voters")) {
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
	
	
	//проверяем голосовали или нет
	function voted($ip,$id_vote=0) {
		global $db;
		global $engine;
		if (!preg_match("/^[0-9]{1,}$/i",$id_vote)) $id_vote=0;
		$res=$db->query("SELECT * FROM %VOTERS_IP% where ip='$ip' and `date`=DATE(NOW()) and id_vote=$id_vote");
		if (mysql_num_rows($res)>0) {
			return true;
		} else {
			return false;
		}
	}
	
	//голосуем
	function vote($id_vote=0,$otvet=0,$ip="") {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_vote) && preg_match("/^[0-9]{1,}$/i",$otvet)) {
		$res=$db->query("update %VOTERS% set `voters".$otvet."`=`voters".$otvet."`+1 , `all`=`all`+1 where id_vote=$id_vote");
			if ($res) {
				$_SESSION["votes"][$id_vote]=true;
				$db->query("INSERT INTO %VOTERS_IP% values ($id_vote,'$ip',NOW())");
				return true;
			} else {
				return false;
			}
		}
	}
	
	//получаем опрос по идентификатору
	function getVoteByID($id_vote) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_vote)) {
				$res=$db->query("select *,DATE_FORMAT(`date_add`,'%d-%m-%Y') as `date_print` from `%VOTERS%` where id_vote=$id_vote");
			return $db->fetch($res);
		} else {
			return false;
		}
	}
	
	//Получаем вопросы
	function getUserAllVoters($page=0,$onpage=10) {
		global $db;
		global $engine;
		if ($page==0) $page=1;
		$res=$db->query("select *,DATE_FORMAT(`date_add`,'%d-%m-%Y') as `date_print` from %VOTERS% order by `date_add` DESC LIMIT ".(($page-1)*$onpage).",".$onpage);
		
		while ($row=$db->fetch($res)) {
			$voters[$row["id_vote"]]=$row;
		}
		
		if (isset($voters)) {
			return $voters;
		} else {
			return false;
		}
	}
	
	function getCountVoters() {
		global $db;
		
		$res=$db->query("select count(*) as cnt from %VOTERS%");
		
		$row=$db->fetch($res);
		
		return $row["cnt"];
	}
	
	function getAllCurrentVoters($limit=2) {
		global $db;
		global $engine;
		if (!preg_match("/^[0-9]{1,}$/i",$limit)) $limit=2;
		$rs=$db->query("select *,DATE_FORMAT(`date_add`,'%d-%m-%Y') as `date_print` from %VOTERS% where current=1 order by `date_add` DESC LIMIT 0,$limit");
		
		while ($rw=$db->fetch($rs)) {
			$voters[$rw["id_vote"]]=$rw;
		}
		
		if (isset($voters)) {
			return $voters;
		} else {
			return false;
		}
	}
	
	//Получаем вопросы
	function getAllVoters() {
		global $db;
		global $engine;
		$res=$db->query("select *,DATE_FORMAT(`date_add`,'%d.%m.%Y') as `date_print` from %VOTERS% order by `date_add` DESC");
		
		while ($row=$db->fetch($res)) {
			$voters[$row["id_vote"]]=$row;
		}
		
		if (isset($voters)) {
			return $voters;
		} else {
			return false;
		}
	}
	
	/*добавление опроса*/
	function addVote($vopros="",$otvet1="",$otvet2="",$otvet3="",$otvet4="",$otvet5="",$current=0,$sql='') {
		global $db;
		if (
		$db->query("
insert %VOTERS% values (null,'".sql_quote($vopros)."','".sql_quote($otvet1)."',0,'".sql_quote($otvet2)."',0,'".sql_quote($otvet3)."',0,'".sql_quote($otvet4)."',0,'".sql_quote($otvet5)."',0,0,'".date('Y-m-d H:i:s')."',$current".$sql.")
		")) {
			return mysql_insert_id();
		} else {
			echo mysql_error();
			return false;
		}
	}
	
	//Получить все IP
	function getAllIps() {
		global $db;
		$res=$db->query("select `id_vote`,`ip` from `%VOTERS_IP%`");
		while ($row=$db->fetch($res)) {
			$ips[$row["id_vote"]][]=$row["ip"];
		}
		if (isset($ips)) return $ips;
		return false;
	}
}
?>
