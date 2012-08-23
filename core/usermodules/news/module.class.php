<?
/*
ћодуль новостей
*/

define("SCRIPTO_news",true);

class news {
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
		$db->addPrefix("%NEWS%",$config["db"]["prefix"]."news");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'ѕоследние новости','',$type_id,'lastnews',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
		$objects=$this->getLastNews($block["number_objects"]);
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
	//провер€ем существуют ли уже таблицы модул€
		global $engine;
		if ($engine->checkInstallModule("news")) {
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
	
	function doTags($obj) {
		global $db;
		if (is_array($obj)) {
		$id_object_str='';
		$n=0;
		$l=sizeof($obj);
		$url=$this->engine->getModuleFullViewUrl("news");
			foreach ($obj as $ob) {
				if ($n<$l-1) {
					$id_object_str.="`id_news`=$ob or ";
				} else {
					$id_object_str.="`id_news`=$ob";
				}
				$n++;
			}
			$res=$db->query("select * from `%NEWS%` where $id_object_str");
			while ($row=$db->fetch($res)) {
				$item["url"]=$url."?id_news=".$row["id_news"];
				$item["caption"]=$row["caption"];
				$item["description"]=$row["content"];
				$item["picture"]=$row["small_preview"];
				$items[]=$item;
			}
			if (isset($items)) return $items;
			return false;
		} else {
			return false;
		}
	}
	
	
	/*добавление новостей*/
	function addNews($date_news="",$caption="",$content="",$content_full="",$meta='',$keywords='',$url="",$author="",$news_small='',$news_middle='',$sql='') {
		global $db;
		if (
		$db->query("
		insert %NEWS% values (null,'".sql_quote($caption)."','".sql_quote($content)."','".sql_quote($content_full)."','".sql_quote($meta)."','".sql_quote($keywords)."','".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."','".sql_quote($author)."','".sql_quote($url)."','$news_small','$news_middle'".$sql.")
		")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	//получаем новость по идентификатору
	function getNewsByID($id_news) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_news)) {
			$res=$db->query("select *,DATE_FORMAT(`date_news`,'%d') as `date_day`,DATE_FORMAT(`date_news`,'%m') as `date_month`,DATE_FORMAT(`date_news`,'%Y') as `date_year`,DATE_FORMAT(`date_news`,'%d-%m-%Y') as `date_print` from `%NEWS%` where id_news=$id_news");
			$row=$db->fetch($res);
			if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
				$row["tags"]=$tgs->getTags($row["id_news"],"news","link");
				unset($tgs);
			}
			return $row;
		} else {
			return false;
		}
	}
	
	function getAllNewsCount() {
		global $db;
		global $engine;
		
		$res=$db->query("select id_news from %NEWS% order by `date_news` DESC");
		
			return @mysql_num_rows($res);
	}	
		
	//ѕолучаем новости
	function getAllNews($page=0,$onpage=20) {
		global $db;
		global $engine;
		
		$url=$engine->getModuleFullViewUrl("news");
		
		$res=$db->query("select *,DATE_FORMAT(`date_news`,'%d-%m-%Y') as `date_print`,DATE_FORMAT(`date_news`,'%c') as `month`,DATE_FORMAT(`date_news`,'%e') as `day`,DATE_FORMAT(`date_news`,'%Y') as `year` from %NEWS% order by `date_news` DESC LIMIT ".($page*$onpage).",".$onpage);
		
		while ($row=$db->fetch($res)) {
			$row["full_url"]=$url."?id_news=".$row["id_news"];
			$news[$row["id_news"]]=$row;
		}
		
		if (isset($news)) {
			return $news;
		} else {
			return false;
		}
	}
	
	//ѕолучаем последние новости
	function getLastNews($number=0) {
		global $db;
		global $engine;
		
		if (!preg_match("/^[0-9]{1,}$/i",$number)) $number=0;
		$url=$engine->getModuleFullViewUrl("news");
		$res=$db->query("select *,DATE_FORMAT(`date_news`,'%d-%m-%Y') as `date_print` from %NEWS% order by `date_news` DESC LIMIT 0,$number");
		
		while ($row=$db->fetch($res)) {
			$row["full_url"]=$url."?id_news=".$row["id_news"];
			$news[$row["id_news"]]=$row;
		}
		
		if (isset($news)) {
			return $news;
		} else {
			return false;
		}
	}
	
}
?>