<?
/*
Модуль новостей
*/

if ($this->checkInstallModule("tags")) {
define("SCRIPTO_tags",true);
}

class tags {
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
		$db->addPrefix("%TAGS%",$config["db"]["prefix"]."tags");
		$db->addPrefix("%TAG_OBJECTS%",$config["db"]["prefix"]."tag_objects");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Облако тегов','',$type_id,'cloudtags',1,0,2,30,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
		global $settings;
		$objects=$this->getTagsFromBase(0,$block["number_objects"]);
		$count=0;
		if (is_array($objects)) {
		foreach ($objects as $obj) {
			$count=$count+$obj["cnt"];
		}
		foreach ($objects as $key=>$obj) {
			$fontsize=ceil($this->thismodule["koef"]*round(($obj["cnt"]/$count)*100));
			if ($fontsize<$this->thismodule["minimum_fontsize"]) {
				$fontsize=$this->thismodule["minimum_fontsize"];
			}
			if ($fontsize>$this->thismodule["maximum_fontsize"]) {
				$fontsize=$this->thismodule["maximum_fontsize"];
			}
			$objects[$key]["fontsize"]=$fontsize;
			$objects[$key]["url"]=$settings["httproot"].$this->thismodule["tag_url"].'?t='.urlencode($obj["tag"]);
		}
		$this->kshuffle($objects);
		$smarty->assign("block_objects",$objects);
		}
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
		if ($engine->checkInstallModule("tags")) {
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
	
	//функция сортировки элементов массива в произвольном порядке
	function kshuffle(&$array) {
    if(!is_array($array) || empty($array)) {
        return false;
    }
    $tmp = array();
    foreach($array as $key => $value) {
        $tmp[] = array('k' => $key, 'v' => $value);
    }
    shuffle($tmp);
    $array = array();
    foreach($tmp as $entry) {
        $array[$entry['k']] = $entry['v'];
    }
    return true;
	}
	
	//Функция добавления нового тега с учетом существования старого
	function addTag($tag,$id_object,$object_type='') {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		$tag=trim($tag);
		$t=$this->getTag($tag);
		if ($t==false) {
			$id_tag=$this->insertTag($tag);
		} else {
			$id_tag=$t;
		}
		if ($db->query("INSERT INTO `%TAG_OBJECTS%` values($id_tag,$id_object,'".sql_quote($object_type)."')")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getTag($tag) {
		global $db;
		$res=$db->query("select * from `%TAGS%` where `tag`='".sql_quote($tag)."'");
		if (@mysql_num_rows($res)==1) {
			$tag=$db->fetch($res);
			return $tag["id_tag"];
		} else {
			return false;
		}
	}
	
	function insertTag($tag) {
		global $db;
		if (trim($tag)=='') return false;
		if ($db->query("insert into `%TAGS%` values (null,'".sql_quote($tag)."')")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	//функция удаления тегов
	function deleteTags($id_object,$object_type) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		if ($db->query("delete from `%TAG_OBJECTS%` where `id_object`=$id_object and `object_type`='$object_type'")) {
			return true;
		} else {
			return false;
		}
	}
	
	//функция добавления тегов из строки
	function addTags($str,$id_object,$object_type) {
		global $db;
		global $engine;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		if ($this->deleteTags($id_object,$object_type)) {
			$tags=explode(",",$str);
			if (is_array($tags)) {
			$old_tags=array();
				foreach ($tags as $tag) {
					$tag=trim($tag);
					if (!in_array($tag,$old_tags)) {
						$this->addTag($tag,$id_object,$object_type);
						$old_tags[]=$tag;
					}
				}
			}
			$engine->clearCacheBlocks("tags");
			return true;
		} else {
			return false;
		}
	}
	
	//Получаем строку тегов для объекта
	function getTags($id_object,$object_type,$mode="text") {
		global $db;
		global $engine;
		
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		$res=$db->query("select `%TAGS%`.* from `%TAGS%`,`%TAG_OBJECTS%` where `%TAG_OBJECTS%`.id_object=$id_object and `%TAG_OBJECTS%`.object_type='".sql_quote($object_type)."' and `%TAGS%`.id_tag=`%TAG_OBJECTS%`.id_tag");
		$str='';
		while ($row=$db->fetch($res)) {
			$tags[]=$row["tag"];
		}
		if (isset($tags)) {
			if (is_array($tags)) {
			$n=1;
			$l=sizeof($tags);
				foreach ($tags as $tag) {
				switch ($mode) {
				case "link":
					$str.='&nbsp;<a href="/'. $engine->modules["tags"]["tag_url"].'?t='.urlencode($tag).'">'.$tag.'</a>&nbsp;';
				break;
				case "text":
					$str.=$tag;
				break;
				}
					if ($n<$l) {
						$str.=',';
					}
					$n++;
				}
			}
		}
		return $str;
	}
	
	function getTagsCount() {
		global $db;
		$res=$db->query("select * from `%TAGS%`");
		return @mysql_num_rows($res);
	}
	
	function getCountAllTags() {
		global $db;
		$res=$db->query("SELECT count(*) as `cnt` from `%TAG_OBJECTS%`");
		$row=$db->fetch($res);
		return $row["cnt"];
	}
	
	//Получить теги для админки
	function getTagsFromBase($page=0,$onpage=40,$use_count=false) {
		global $db;
		if ($use_count) {
			$count=$this->getCountAllTags();
		} else {
			$count=0;
		}
		$res=$db->query("SELECT `%TAGS%`.*,count(*) as `cnt` FROM `%TAGS%` LEFT JOIN `%TAG_OBJECTS%` on `%TAG_OBJECTS%`.id_tag=`%TAGS%`.id_tag group by `%TAGS%`.`id_tag` order by `cnt`  DESC LIMIT ".($page*$onpage).",".$onpage);
		while ($row=$db->fetch($res)) {
			if ($count>0) {
				$row["percent"]=round(($row["cnt"]/$count)*100,2);
			}
			$tags[]=$row;
		}
		if (isset($tags)) {
			return $tags;
		} else {
			return false;
		}
	}
	
	
	//Получение тегов из пользовательской части
	function getTagsCountByTag($tag) {
		global $db;
		$res=$db->query("SELECT `%TAG_OBJECTS%`.`id_tag`,count(*) as `cnt` FROM `%TAG_OBJECTS%`,`%TAGS%` where `%TAGS%`.id_tag=`%TAG_OBJECTS%`.id_tag and `%TAGS%`.tag='".sql_quote($tag)."'  group by `%TAGS%`.id_tag");
		$row=$db->fetch($res);
		return $row;
	}
	
	function getTagsByTag($page,$onpage=30,$id_tag) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_tag)) {
			$res=$db->query("select * from `%TAG_OBJECTS%` where id_tag=$id_tag LIMIT ".($page*$onpage).",".$onpage);
			while ($row=$db->fetch($res)) {
				$tags[$row["object_type"]][]=$row;
			}
			if (isset($tags)) {
				return $tags;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>