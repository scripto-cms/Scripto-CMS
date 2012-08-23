<?
/*
Модуль товары
*/

define("SCRIPTO_publications",true);

class publications {
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
		$db->addPrefix("%PUBLICATIONS%",$config["db"]["prefix"]."publications");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Последние публикации','',$type_id,'lastpubl',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
	
	function getAllPublications() {
		global $db;
		$res=$db->query("select id_publication,caption,code from %PUBLICATIONS% order by caption");

		while ($row=$db->fetch($res)) {
			$products[]=$row;
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}	
	
	
	function doBlockAdmin($block,$page) {
		return "";
	}
	
	function doBlock($block,$page,&$objects) {
		global $db;
		global $smarty;
		global $rubrics;
		$objects=$this->getLastPublications($block["number_objects"]);
		if (is_array($objects)) {
			foreach ($objects as $key=>$publ)
				foreach ($rubrics as $position) {
					foreach ($position as $rubr) {
						if ($publ["id_category"]==$rubr["id_category"]) {
							$objects[$key]["page_url"]=$rubr["url"];
							break;break;
						}
					}
				}
		}
		$smarty->assign("block_objects",$objects);
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
		if ($engine->checkInstallModule("publications")) {
			return true;
		} else {
			return false;
		}
	}
	
	function doStatic() {
		global $config;
		global $settings;
		global $db;
		global $page;
		global $lang;
		global $smarty;
		global $thismodule;
		global $engine;
		
		if (is_file($this->thismodule["path"]."user_module.mod.php")) {
			include($this->thismodule["path"]."user_module.mod.php");
			//здесь получаем товары для рубрик
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
	
	/*добавление товаров*/
	function addPublication($date_news="",$caption="",$content="",$content_full="",$meta="",$keywords="",$url="",$author="",$id_cat,$visible=1,$sql='') {
		global $db;
		if (
		$db->query("
		insert %PUBLICATIONS% values (null,$id_cat,'".sql_quote($caption)."','".sql_quote($content)."','".sql_quote($content_full)."','".sql_quote($meta)."','".sql_quote($keywords)."','".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."','".sql_quote($author)."','".sql_quote($url)."',0,$visible,0".$sql.")
		")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function doUserSearch($str,$prefix='') {
		global $db;
		$res=$db->query("select * from `%PUBLICATIONS%` where `caption$prefix` LIKE '%$str%' or `content$prefix` LIKE '%$str%' and visible=1 order by `sort` DESC");
		while ($row=$db->fetch($res)) {
			$row["prist"]="?publicationID=".$row["id_publication"];
			$items[]=$row;
		}
		if (isset($items)) return $items;
		return false;
	}	
	
	function getContentPublication($publication,$folder) {
		global $config;
		global $smarty;
		global $engine;
			if (is_file($config["pathes"]["user_data"].$folder."/".$publication["id_publication"].".dat")) {
	if ($engine->current_prefix=='')
	$publication["content_full"]=@file_get_contents($config["pathes"]["user_data"].$folder."/".$publication["id_publication"].".dat");
			}
			$pages=explode("[page]",$publication["content_full"]);
			if (sizeof($pages)==1) {
				$publication["content_page"]=$publication["content_full"];
			} else {
				for ($x=0;$x<=sizeof($pages)-1;$x++) {
					$publication["pages"][]=$x;
				}
				if (isset($_REQUEST["publication_page"])) {
					$current_page=$_REQUEST["publication_page"];
					if (!preg_match("/^[0-9]{1,}$/i",$current_page)) $current_page=0;
				} else {
					$current_page=0;
				}
				if (isset($pages[$current_page])) {
					$publication["content_page"]=$pages[$current_page];
					$publication["current_page"]=$current_page;
				} else {
					$publication["content_page"]=$pages[0];
					$publication["current_page"]=0;
				}
			}
			
			return $publication;
	}	
	
	//получаем товар по идентификатору
	function getPublicationByID($id_publication) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_publication)) {
				$res=$db->query("select *,DATE_FORMAT(`date`,'%d') as `date_day`,DATE_FORMAT(`date`,'%m') as `date_month`,DATE_FORMAT(`date`,'%Y') as `date_year`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date_print` from `%PUBLICATIONS%` where id_publication=$id_publication");
			$row=$db->fetch($res);
			$row=$this->getContentPublication($row,"publications");
			return $row;
		} else {
			return false;
		}
	}
	
	
	//получить общее количество товаров
	function getCountAllPublications() {
		global $db;
		global $rubrics;
		global $engine;
		global $smarty;
		
		$products=array();
		$userproducts=array();
		$res=$db->query("select `id_category`,count(`id_publication`) as `cnt` from %PUBLICATIONS% group by `id_category`");
		while ($row=$db->fetch($res)) {
			$publ[$row["id_category"]]=$row["cnt"];
		}
		$res=$db->query("select `id_category`,count(`id_publication`) as `cnt` from %PUBLICATIONS% where `visible`=1 group by `id_category`");
		while ($row=$db->fetch($res)) {
			$userpubl[$row["id_category"]]=$row["cnt"];
		}		
		if (!isset($rubrics)) {
			$rubrics=$engine->getAllPositionsRubrics();
		}
		foreach ($rubrics as $key1=>$position) {
			foreach ($position as $key2=>$category) {
				if (isset($publ[$category["id_category"]])) {
					$rubrics[$key1][$key2]["publ"]=$publ[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["publ"]=0;
				}
				if (isset($userpubl[$category["id_category"]])) {	$rubrics[$key1][$key2]["userpubl"]=$userpubl[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["userpubl"]=0;
				}
			}
		}
		$smarty->assign("rubrics",$rubrics);
		return $rubrics;
	}
	
	function getPublicationsCount($id_cat=0,$visible=1) {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		$res=$db->query("select id_publication from `%PUBLICATIONS%` where id_category=$id_cat $vis");
		return @mysql_num_rows($res);
	}
	
	function getPublications($id_cat=0,$visible=1,$page=0,$onpage=20) {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `date_print` from `%PUBLICATIONS%` where id_category=$id_cat $vis order by `date` DESC LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$publ[$row["id_publication"]]=$row;
		}
		
		if (isset($publ)) {
			return $publ;
		} else {
			return false;
		}
	}
	
	function getLastPublications($number=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$number)) {
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `date_print` from `%PUBLICATIONS%` where visible=1 order by `date` DESC LIMIT 0,$number");
		while ($row=$db->fetch($res)) {
			$publ[$row["id_publication"]]=$row;
		}
		
		if (isset($publ)) {
			return $publ;
		} else {
			return false;
		}
		} else {
			return false;
		}
	}	
	
}
?>
