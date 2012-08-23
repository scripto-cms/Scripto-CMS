<?
/*
Модуль объекты
*/

define("SCRIPTO_mod_objects",true);

class objects {
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
		$db->addPrefix("%TYPES%",$config["db"]["prefix"]."types");
		$db->addPrefix("%OBJ%",$config["db"]["prefix"]."obj");
		$db->addPrefix("%OBJECT_PICTURES%",$config["db"]["prefix"]."obj_pictures");
		$db->addPrefix("%OBJECT_VIDEOS%",$config["db"]["prefix"]."obj_videos");
		$db->addPrefix("%OBJECT_FILES%",$config["db"]["prefix"]."obj_files");
		$db->addPrefix("%OBJECT_OBJECTS%",$config["db"]["prefix"]."obj_object_objects");
	$db->addPrefix("%OBJECT_CATEGORIES%",$config["db"]["prefix"]."obj_object_categories");
	$db->addPrefix("%block_object_types%",$config["db"]["prefix"]."block_object_types");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Последние объекты','',$type_id,'lastobjects',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
		$block_types=$this->getTypesByBlock($block["id_block"]);
		switch ($block["type"]["type"]) {
		case "lastobjects":
			$objects=$this->getLastObjects($block["number_objects"],$block_types);
			$smarty->assign("block_objects",$objects);
		break;
		case "addobjects":
			$types=array();
			foreach ($block_types as $typ) {
				$types[$typ]=$this->getTypeByID($typ);
			}
			$smarty->assign("block_types",$types);
		break;
		}
		$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block.tpl.html");
		}
		return $content;
	}
	
	function doBlockAdmin($block,$page) {
		global $db;
		global $smarty;
		global $config;
		global $engine;
		
		switch ($block["type"]["type"]) {
			case "addobjects":
			case "lastobjects":
				//добавляем типы объектов к блоку
				if (isset($_REQUEST["save"])) {
					$type=@$_REQUEST["type"];
					if (is_array($type)) {
						$db->query("delete from `%block_object_types%` where id_block=".$block["id_block"]);
						foreach ($type as $id_type=>$tp) {
							$db->query("insert into `%block_object_types%` values(".$block["id_block"].",$id_type)");
						}
						$smarty->assign("save",true);
						$engine->clearCacheBlock($block["ident"]);
					}
				}
				$all_types=$this->getAllTypes();
				$block_types=$this->getTypesByBlock($block["id_block"]);
				if (is_array($block_types)) {
				if (is_array($all_types))
					foreach ($all_types as $key=>$type) 
						if (in_array($type["id_type"],$block_types))
							$all_types[$key]["checked"]=true;
				} else {
				if (is_array($all_types))
					foreach ($all_types as $key=>$type)
						$all_types[$key]["checked"]=false;
				}
				$smarty->assign("all_types",$all_types);
				$smarty->assign("block",$block);
			break;
		}
		if (is_file($config["pathes"]["templates_dir"].$this->thismodule["template_path"]."admin_block.tpl.html")) {
			$content=$smarty->fetch($this->thismodule["template_path"]."admin_block.tpl.html");
			return $content;
		} else {
			return false;
		}
	}
	
	function checkMe() {
	//проверяем существуют ли уже таблицы модуля
		global $engine;
		if ($engine->checkInstallModule("objects")) {
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
			foreach ($obj as $ob) {
				if ($n<$l-1) {
					$id_object_str.="`id_object`=$ob or ";
				} else {
					$id_object_str.="`id_object`=$ob";
				}
				$n++;
			}
			$res=$db->query("select * from `%OBJ%` where visible=1 and ($id_object_str)");
			while ($row=$db->fetch($res)) {
				$item["url"]=$this->settings['httproot'].$this->engine->urls[$row["id_category"]]."?id_object=".$row["id_object"];
				$item["caption"]=$row["caption"];
				$item["description"]=$row["small_content"];
				$item["picture"]=$row["small_preview"];
				$items[]=$item;
			}
			if (isset($items)) return $items;
			return false;
		} else {
			return false;
		}
	}
	
	function getTypesByBlock($id_block) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$res=$db->query("select `%TYPES%`.id_type from `%TYPES%`,`%block_object_types%` where `%block_object_types%`.id_block=$id_block and `%block_object_types%`.id_type=`%TYPES%`.id_type");
			while ($row=$db->fetch($res)) {
				$types[]=$row["id_type"];
			}
			if (isset($types)) {
				return $types;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	//Поиск объектов
	function doUserSearch($str,$prefix='') {
		global $db;
		$res=$db->query("select * from `%OBJ%` where `caption$prefix` LIKE '%$str%' or `code` LIKE '%$str%' or `small_content$prefix` LIKE '%$str%' and visible=1");
		while ($row=$db->fetch($res)) {
			$row["prist"]="?id_object=".$row["id_object"];
			$items[]=$row;
		}
		if (isset($items)) return $items;
		return false;
	}
	
	function existType($ident) {
		global $db;
		if (preg_match("/^[a-zA-Z0-9]{1,30}$/i",$ident)) {
			$res=$db->query("select `ident` from `%TYPES%` where ident='$ident'");
			if (mysql_num_rows($res)>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getTypeByID($id_type) {
		global $db;
		global $lang;
		if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
			$res=$db->query("select *,DATE_FORMAT(`date_type`,'%d-%m-%Y') as `create_print` from `%TYPES%` where id_type=$id_type");
			$type=$db->fetch($res);
			$k=0;
			for ($n=0;$n<10;$n++) {
				$k=$n+1;
				if (isset($type["type".$k]))
				if ($type["type".$k]!='none') {
					if ($type["type".$k]=='checkbox') {
						$type["checkbox"][$n]["caption"]=$type["caption".$k];
						$type["checkbox"][$n]["type"]=$type["type".$k];
						$type["checkbox"][$n]["show_in_full"]=$type["show_in_full".$k];
						$type["checkbox"][$n]["show_in_short"]=$type["show_in_short".$k];
					} else {
						$type["texts"][$n]["caption"]=$type["caption".$k];
						$type["texts"][$n]["type"]=$type["type".$k];
						$type["texts"][$n]["show_in_full"]=$type["show_in_full".$k];
						$type["texts"][$n]["show_in_short"]=$type["show_in_short".$k];
					}
				}
			}
			for ($n=0;$n<3;$n++) {
				$k=$n+1;
				$values=array();
				if (isset($type["list_type".$k]))
				if ($type["list_type".$k]!='none') {
					$type["lists"][$n]["caption"]=$type["list_caption".$k];
					$values=explode("\n",$type["list_value".$k]);
						foreach ($values as $key=>$val) {
							$value["id"]=trim($val);
							$value["name"]=trim($val);
							$vals[$key]=$value;
						}
					if (isset($vals)) {
						$type["lists"][$n]["values"]=$vals;
					} else {
						$type["lists"][$n]["values"]=array('id'=>'no','name'=>$lang["error"]["not_set"]);
					}
					unset($value);
					unset($vals);
					unset($values);
					$type["lists"][$n]["type"]=$type["list_type".$k];
					$type["lists"][$n]["show_in_full"]=$type["list_show_in_full".$k];
					$type["lists"][$n]["show_in_short"]=$type["list_show_in_short".$k];
				}
			}
			if (!isset($type["texts"])) $type["texts"]=false;
			if (!isset($type["checkbox"])) $type["checkbox"]=false;	
			if (!isset($type["lists"])) $type["lists"]=false;
			return $type;
		} else {
			return false;
		}
	}
	
	function getNewReminders() {
		global $db;
		$res=$db->query("SELECT id_object from `%OBJ%` where `new`=1");
		$count=@mysql_num_rows($res);
		if ($count>0) {
			$reminder['subject']=ToUTF8($this->thismodule["caption"]);
			$reminder['content']=ToUTF8('У Вас '.@mysql_num_rows($res).' добавленных объекта(ов)');
			$reminder['count']=$count;
			return $reminder;
		} else {
			return false;
		}
	}
	
	function getCountNewObjects($id_type) {
		global $db;
		if (!preg_match('/^[0-9]{1,}$/i',$id_type)) return 0;
		$res=$db->query("select count(*) as `cnt` from `%OBJ%` where `new`=1 and `id_type`=$id_type");
		$row=$db->fetch($res);
		return $row["cnt"];
	}
	
	function getCountNewObjectsByType() {
		global $db;
		$res=$db->query("select `id_type`,count(*) as `cnt` from `%OBJ%` where `new`=1 group by `id_type`");
		while ($row=$db->fetch($res)) {
			$count[$row["id_type"]]=$row["cnt"];
		}
		if (isset($count)) return $count;
		return false;
	}
	
	function getAllTypes() {
		global $db;
		$count=$this->getCountNewObjectsByType();
		$res=$db->query("select *,DATE_FORMAT(`date_type`,'%d-%m-%Y') as `create_print` from `%TYPES%` order by `caption`");
		while ($row=$db->fetch($res)) {
			if (isset($count[$row["id_type"]])) {
				$row["new"]=$count[$row["id_type"]];
			} else {
				$row["new"]=0;
			}
			$types[]=$row;
		}
		if (isset($types)) return $types;
		return false;
	}

	function getVideosByObjects($id_objects) {
		global $db;
		if (is_array($id_objects)) {
			$n=0;
			$sql='';
			foreach ($id_objects as $id_object) {
				if ($n==0) {
					$sql.="`%OBJECT_VIDEOS%`.id_object=$id_object";
				} else {
					$sql.=" or `%OBJECT_VIDEOS%`.id_object=$id_object";
				}
				$n++;
			}
			$res=$db->query("select `%video%`.*,`%OBJECT_VIDEOS%`.id_type,`%OBJECT_VIDEOS%`.id_object,`%OBJECT_VIDEOS%`.sort from `%OBJECT_VIDEOS%`,`%video%` where ($sql) and `%OBJECT_VIDEOS%`.id_video=`%video%`.id_video order by `%OBJECT_VIDEOS%`.sort DESC");
			while ($row=$db->fetch($res)) {
				$videos[$row["id_object"]][]=$row;
			}
			if (isset($videos)) {
				return $videos;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getVideosByObject($id_object,$limit=0) {
		global $db;
		if(preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$limit_str='';
			if (preg_match("/^[0-9]{1,}$/i",$limit)) {
				if ($limit>0)  {
					$limit_str=" LIMIT 0,$limit";
				}
			}
			$res=$db->query("select `%video%`.*,`%OBJECT_VIDEOS%`.id_type,`%OBJECT_VIDEOS%`.id_object,`%OBJECT_VIDEOS%`.sort from `%OBJECT_VIDEOS%`,`%video%` where `%OBJECT_VIDEOS%`.id_object=$id_object and `%OBJECT_VIDEOS%`.id_video=`%video%`.id_video order by `%OBJECT_VIDEOS%`.sort DESC $limit_str");
			while ($row=$db->fetch($res)) {
				$videos[]=$row;
			}
			if (isset($videos)) {
				return $videos;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function getObjectsByObject($id_object,$visible=0,$limit=0) {
		global $db;
		if(preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$limit_str='';
			if (preg_match("/^[0-9]{1,}$/i",$limit)) {
				if ($limit>0)  {
					$limit_str=' LIMIT 0,$limit';
				}
			}
			$vis_str="";
			if ($visible==1) {
				$vis_str=" and %OBJ%.visible=1";
			}
			$res=$db->query("select `%OBJ%`.*,`%OBJECT_OBJECTS%`.sort,DATE_FORMAT(%OBJ%.`date_create`,'%d.%m.%Y %h:%i:%S') as `create_print` from `%OBJECT_OBJECTS%`,`%OBJ%` where `%OBJECT_OBJECTS%`.id_object=$id_object and `%OBJECT_OBJECTS%`.id_object2=`%OBJ%`.id_object $vis_str order by `%OBJECT_OBJECTS%`.sort DESC $limit_str");
			while ($row=$db->fetch($res)) {
				$objects[$row["id_type"]][]=$row;
			}
			if (isset($objects)) {
				return $objects;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getImagesByObjects($id_objects) {
		global $db;
		if (is_array($id_objects)) {
			$n=0;
			$sql='';
			foreach ($id_objects as $id_object) {
				if ($n==0) {
					$sql.="`%OBJECT_PICTURES%`.id_object=$id_object";
				} else {
					$sql.=" or `%OBJECT_PICTURES%`.id_object=$id_object";
				}
				$n++;
			}
			$res=$db->query("select `%photos%`.*,`%OBJECT_PICTURES%`.* from `%OBJECT_PICTURES%`,`%photos%` where ($sql) and `%OBJECT_PICTURES%`.id_image=`%photos%`.id_photo order by `%OBJECT_PICTURES%`.sort DESC");
			while ($row=$db->fetch($res)) {
				$images[$row["id_object"]][]=$row;
			}
			if (isset($images)) {
				return $images;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getImagesByObject($id_object,$limit=0) {
		global $db;
		if(preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$limit_str='';
			if (preg_match("/^[0-9]{1,}$/i",$limit)) {
				if ($limit>0)  {
					$limit_str=" LIMIT 0,$limit";
				}
			}
			$res=$db->query("select `%photos%`.*,`%OBJECT_PICTURES%`.* from `%OBJECT_PICTURES%`,`%photos%` where `%OBJECT_PICTURES%`.id_object=$id_object and `%OBJECT_PICTURES%`.id_image=`%photos%`.id_photo order by `%OBJECT_PICTURES%`.sort DESC $limit_str");
			while ($row=$db->fetch($res)) {
				$images[]=$row;
			}
			if (isset($images)) {
				return $images;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function addObject2Object($id_object,$id_object2) {
		global $db;
		global $engine;
		if(preg_match("/^[0-9]{1,}$/i",$id_object) && preg_match("/^[0-9]{1,}$/i",$id_object2)) {
			$object=$this->getObjectByIDEx($id_object);
			$object2=$this->getObjectByIDEx($id_object2);
			if ($db->query("insert into `%OBJECT_OBJECTS%` values($id_object,".$object["id_type"].",$id_object2,0)")) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	
	function addVideoToObject($id_object,$id_video) {
		global $db;
		global $engine;
		if(preg_match("/^[0-9]{1,}$/i",$id_object) && preg_match("/^[0-9]{1,}$/i",$id_video)) {
			$object=$this->getObjectByIDEx($id_object);
			$video=$engine->getVideoByID($id_video);
			if ($db->query("insert into `%OBJECT_VIDEOS%` values($id_object,".$object["id_type"].",$id_video,'".$video["caption"]."','".$video["filename"]."',0)")) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function addImageToObject($id_object,$id_image) {
		global $db;
		global $engine;
		if(preg_match("/^[0-9]{1,}$/i",$id_object) && preg_match("/^[0-9]{1,}$/i",$id_image)) {
			$object=$this->getObjectByIDEx($id_object);
			$image=$engine->getImageByID($id_image);
			if ($db->query("insert into `%OBJECT_PICTURES%` values($id_object,".$object["id_type"].",$id_image,'".$image["small_photo"]."','".$image["medium_photo"]."','".$image["big_photo"]."',0)")) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getCountAllObjectsEx() {
		global $db;
		global $rubrics;
		global $engine;
		global $smarty;
		$objects=array();
		$userobjects=array();
		$res=$db->query("select `id_category`,count(`id_object`) as `cnt` from %OBJ% group by `id_category`");
		while ($row=$db->fetch($res)) {
			$objects[$row["id_category"]]=$row["cnt"];
		}
		$res=$db->query("select `id_category`,count(`id_object`) as `cnt` from %OBJ% where `visible`=1 group by `id_category`");
		while ($row=$db->fetch($res)) {
			$userobjects[$row["id_category"]]=$row["cnt"];
		}
		if (!isset($rubrics)) {
			$rubrics=$engine->getAllPositionsRubrics(0);
		}
		foreach ($rubrics as $key1=>$position) {
			foreach ($position as $key2=>$category) {
				if (isset($objects[$category["id_category"]])) {
					$rubrics[$key1][$key2]["objects"]=$objects[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["objects"]=0;
				}
				if (isset($userobjects[$category["id_category"]])) {	$rubrics[$key1][$key2]["userobjects"]=$userobjects[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["userobjects"]=0;
				}
			}
		}
		$smarty->assign("rubrics",$rubrics);
		return $rubrics;
	}
	
	//Получить общее количество объектов
	function getCountAllObjects($id_type) {
		global $db;
		global $rubrics;
		global $engine;
		global $smarty;
		if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
		$objects=array();
		$userobjects=array();
		$res=$db->query("select `id_category`,count(`id_object`) as `cnt` from %OBJ% where id_type=$id_type group by `id_category`");
		while ($row=$db->fetch($res)) {
			$objects[$row["id_category"]]=$row["cnt"];
		}
		$res=$db->query("select `id_category`,count(`id_object`) as `cnt` from %OBJ% where `visible`=1 and id_type=$id_type group by `id_category`");
		while ($row=$db->fetch($res)) {
			$userobjects[$row["id_category"]]=$row["cnt"];
		}		
		if (!isset($rubrics)) {
			$rubrics=$engine->getAllPositionsRubrics(0);
		}
		foreach ($rubrics as $key1=>$position) {
			foreach ($position as $key2=>$category) {
				if (isset($objects[$category["id_category"]])) {
					$rubrics[$key1][$key2]["objects"]=$objects[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["objects"]=0;
				}
				if (isset($userobjects[$category["id_category"]])) {	$rubrics[$key1][$key2]["userobjects"]=$userobjects[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["userobjects"]=0;
				}
			}
		}
		$smarty->assign("rubrics",$rubrics);
		return $rubrics;
		}
	}
	
	function deleteType($id_type=0) {
		global $db;
		global $config;
		global $engine;
		if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
			$type=$this->getTypeByID($id_type);
			if (isset($type["caption"])) {
			if ($db->query("delete from `%OBJ%` where id_type=$id_type")) {
				if ($db->query("delete from `%OBJECT_VIDEOS%` where id_type=$id_type")) {
				if ($db->query("delete from `%OBJECT_PICTURES%` where id_type=$id_type")) {}
				if ($db->query("delete from `%TYPES%` where id_type=$id_type")) {
					$db->query("delete from `%OBJECT_OBJECTS%` where id_type=$id_type");
					$db->query("delete from `%block_object_types%` where id_type=$id_type");
					delTree($config["pathes"]["user_data"].'objects/'.$id_type);
	$filename=$config["pathes"]["templates_dir"].$config["templates"]["user_processor"].$type["ident"].'_short.processor.tpl';
					if (is_file($filename)) {
						if (!@unlink($filename))
							$engine->setCongratulation('Ошибка удаления файла!','Внимание! Файл '.$type["ident"].'_short.processor.tpl не удалось удалить!',0);
					}
	$filename=$config["pathes"]["templates_dir"].$config["templates"]["user_processor"].$type["ident"].'_full.processor.tpl';
					if (is_file($filename)) {
						if (!@unlink($filename))
							$engine->setCongratulation('Ошибка удаления файла!','Внимание! Файл '.$type["ident"].'_full.processor.tpl не удалось удалить!',0);
					}
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
			} else {
				echo mysql_error();
				return false;
			}
		} else {
			return false;
		}
	}
	
	function deleteObject($id_object=0,$id_type=false) {
		global $db;
		global $config;
		if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
		if (!preg_match("/^[0-9]{1,}$/i",$id_type)) {
			$object=$this->getObjectByID($id_object);
			$id_type=$object["id_type"];
		}
		if ($db->query("DELETE from `%OBJECT_OBJECTS%` where id_object=$id_object")) {
		if ($db->query("DELETE from `%OBJECT_VIDEOS%` where id_object=$id_object")) {
			if ($db->query("DELETE from `%OBJECT_PICTURES%` where id_object=$id_object")) {
				if ($db->query("DELETE from `%OBJ%` where id_object=$id_object")) {
					@unlink($config["pathes"]["user_data"].'objects/'.$id_type.'/'.$id_object.'.dat');
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
		} else {
			return false;
		}
		} else {
			return false;
		}
	}
	
	function existObject($code='',$id_type) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_type)) return false;
		$res=$db->query("select `id_object` from `%OBJ%` where id_type=$id_type and `code`='".sql_quote($code)."'");
		if (@mysql_num_rows($res)>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function addObject($id_category=0,$id_type=0,$caption,$title='',$meta='',$keywords='',$code,$small_preview='',$middle_preview='',$content='',$content_full='',$val,$listval,$images,$visible,$new=0,$sqlt='') {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$visible)) return false;
		$values=array();
		$values_str='';
		$n=0;
		if (is_array($val)) 
			foreach($val as $k=>$v) 
				$values[$k]=sql_quote($v);
		while ($n<10) {
			if (!isset($values[$n]))
				$values[$n]="";
			$values_str.=",'".$values[$n]."'";
			$n++;
		}
		$listvalues=array();
		$listvalues_str='';
		$n=0;
		if (is_array($listval)) 
			foreach($listval as $k=>$v) 
				$listvalues[$k]=sql_quote($v);
		while ($n<3) {
			if (!isset($listvalues[$n]))
				$listvalues[$n]="";
			$listvalues_str.=",'".$listvalues[$n]."'";
			$n++;			
		}
		if ($new!=0) $new=1;
		if ($db->query("insert into `%OBJ%` values(null,$id_category,$id_type,$new,'".sql_quote($caption)."','".sql_quote($title)."','".sql_quote($meta)."','".sql_quote($keywords)."','".sql_quote($code)."','".date('Y-m-d H:i:s')."','".sql_quote($content)."','".sql_quote($small_preview)."','".sql_quote($middle_preview)."'".$values_str.$listvalues_str.",0,'',0,$visible".$sqlt.")")) {
			return mysql_insert_id();
		} else {
		echo mysql_Error();
			return false;
		}
	}

	function getObjectsCountEx($id_cat=0,$str='',$visible=1,$in_category=false) {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		if (trim($str)!='') {
			$str_sql="and `caption` like '%".sql_quote($str)."%'";
		} else {
			$str_sql='';
		}
		if ($in_category) {
		$sql_dop=" and (";
		$this->generateSQLDop($id_cat,$sql_dop,true);
		$sql_dop.=")";
		} else {
		$sql_dop=" and `id_category`=$id_cat";
		}
		$res=$db->query("select id_object from `%OBJ%` where  1 $sql_dop $vis $str_sql");
		return @mysql_num_rows($res);
	}


	function getObjectsCount($id_cat=0,$id_type=0,$str='',$visible=1,$in_category=false) {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		if (trim($str)!='') {
			$str_sql="and `caption` like '%".sql_quote($str)."%'";
		} else {
			$str_sql='';
		}	
		if ($in_category) {
		$sql_dop=" and (";
		$this->generateSQLDop($id_cat,$sql_dop,true);
		$sql_dop.=")";
		} else {
		$sql_dop=" and `id_category`=$id_cat";
		}
		if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
			$res=$db->query("select id_object from `%OBJ%` where  1 $sql_dop $vis $str_sql and id_type=$id_type");
			return @mysql_num_rows($res);		
		} else {
			return false;
		}
	}
	
	function generateSQLDop($id_cat=0,&$sql_dop,$first=false) {
		global $rubrics;
		if (!preg_match("/^[0-9]{1,}$/i",$id_cat)) return false;
		if ($first) {
		$sql_dop.="`id_category`=$id_cat";
		} else {
		$sql_dop.=" or `id_category`=$id_cat";
		}
		foreach ($rubrics as $position) {
			foreach ($position as $rubric) {
				if ($rubric["id_sub_category"]==$id_cat) {
					$this->generateSQLDop($rubric["id_category"],$sql_dop,false);
				}
			}
		}
		return true;
	}
	
	function getTypesByObject($id_object) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$res=$db->query("select `id_type` from `%OBJECT_OBJECTS%` where `id_object`=$id_object group by `id_type`");
			while ($row=$db->fetch($res)) {
				$types[$row["id_type"]]=$this->getTypeByID($row["id_type"]);
			}
			if (isset($types)) {
				return $types;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	
	function getTypesByCat($id_cat=false,$in_category=false) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
		if ($in_category) {
		$sql_dop="(";
		$this->generateSQLDop($id_cat,$sql_dop,true);
		$sql_dop.=")";
		} else {
		$sql_dop="`id_category`=$id_cat";
		}		
			$res=$db->query("select `id_type` from `%OBJ%` where $sql_dop group by `id_type`");
			while ($row=$db->fetch($res)) {
				$types[$row["id_type"]]=$this->getTypeByID($row["id_type"]);
			}
			if (isset($types)) {
				return $types;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getLastObjects($count=2,$types) {
		global $db;
		if ($count>0) {
			$typ_sql="";
			if (is_array($types)) {
				$typ_sql.=" and (";
				$n=0;
				foreach ($types as $id_type=>$type) {
					if ($n>0) $typ_sql.=" or ";
					$typ_sql.="`%OBJ%`.id_type=$type";
					$n++;
				}
				$typ_sql.=")";
			}
			$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%OBJ%` where `visible`=1 $typ_sql order by `date_create` DESC LIMIT 0,$count");
			while ($row=$db->fetch($res)) {
				$types[$row["id_type"]]=true;
				$objects[$row["id_object"]]=$row;
			}
			if (is_array($types))
			foreach ($types as $id_type=>$type) {
				$types[$id_type]=$this->getTypeByID($id_type);
			}
			if (isset($objects))
			if (is_array($objects))
			foreach ($objects as $id_object=>$object)
				$objects[$id_object]["type"]=$types[$object["id_type"]];
			if (isset($objects)) {
				return $objects;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getObjectsEx($id_cat=0,$str='',$visible=1,$in_category=false,$page=0,$onpage=20) {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		if (trim($str)!='') {
			$str_sql="and `caption` like '%".sql_quote($str)."%'";
		} else {
			$str_sql='';
		}	
		if ($in_category) {
		$sql_dop="and (";
		$this->generateSQLDop($id_cat,$sql_dop,true);
		$sql_dop.=")";
		} else {
		$sql_dop=" and `id_category`=$id_cat";
		}
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%OBJ%` where 1 $sql_dop $vis $str_sql order by `date_create` DESC LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {	
			$objects[$row["id_object"]]=$row;
		}
		if (isset($objects)) {
			return $objects;
		} else {
			return false;
		}
	}

	function getAllObjects($id_cat=0,$visible=1) {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		$sql_dop=" and `id_category`=$id_cat";
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d.%m.%Y %h:%i:%S') as `create_print` from `%OBJ%` where 1 $sql_dop $vis order by `caption`");
		while ($row=$db->fetch($res)) {
			$objects[$row["id_object"]]=$row;
		}
		if (isset($objects)) {
			return $objects;
		} else {
			return false;
		}
	}
	

	function getNewObjects($type,$page=0,$onpage=20) {
		global $db;
		if (is_array($type)) {
		$id_type=$type["id_type"];
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d.%m.%Y %h:%i:%S') as `create_print` from `%OBJ%` where id_type=$id_type and `new`=1 order by `date_create` DESC LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$row["values"]=$this->transformateValues($row,$type);
			unset($row["value1"]);
			unset($row["value2"]);
			unset($row["value3"]);
			unset($row["value4"]);
			unset($row["value5"]);
			unset($row["value6"]);
			unset($row["value7"]);
			unset($row["value8"]);
			unset($row["value9"]);
			unset($row["value10"]);
			unset($row["list1"]);
			unset($row["list2"]);
			unset($row["list3"]);
			$objects[$row["id_object"]]=$row;
		}
		if (isset($objects)) {
			return $objects;
		} else {
			return false;
		}
		} else {
			return false;
		}
	}	
	
	function getObjects($id_cat=0,$id_type=0,$type,$str='',$visible=1,$in_category=false,$page=0,$onpage=20) {
		global $db;
		if (is_array($type)) {
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		if (trim($str)!='') {
			$str_sql="and `caption` like '%".sql_quote($str)."%'";
		} else {
			$str_sql='';
		}	
		if ($in_category) {
		$sql_dop="and (";
		$this->generateSQLDop($id_cat,$sql_dop,true);
		$sql_dop.=")";
		} else {
		$sql_dop=" and `id_category`=$id_cat";
		}
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d.%m.%Y %h:%i:%S') as `create_print` from `%OBJ%` where 1 $sql_dop $vis $str_sql and id_type=$id_type order by `date_create` DESC LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$row["values"]=$this->transformateValues($row,$type);
			unset($row["value1"]);
			unset($row["value2"]);
			unset($row["value3"]);
			unset($row["value4"]);
			unset($row["value5"]);
			unset($row["value6"]);
			unset($row["value7"]);
			unset($row["value8"]);
			unset($row["value9"]);
			unset($row["value10"]);
			unset($row["list1"]);
			unset($row["list2"]);
			unset($row["list3"]);
			$objects[$row["id_object"]]=$row;
		}
		if (isset($objects)) {
			return $objects;
		} else {
			return false;
		}
		} else {
			return false;
		}
	}

	function getContentObject($object,$folder) {
		global $config;
		global $smarty;
		global $engine;
			if (is_file($config["pathes"]["user_data"].$folder."/".$object["id_object"].".dat")) {	
			if ($engine->current_prefix=='')	$object["content_full"]=@file_get_contents($config["pathes"]["user_data"].$folder."/".$object["id_object"].".dat");
if (!isset($object["content_full"])) $object["content_full"]='';
$object["content_full"]=$engine->checkRegistered($object["content_full"]);
			} else {
				return $object;
			}
			$pages=explode("[page]",$object["content_full"]);
			if (sizeof($pages)==1) {
				$object["content_page"]=$object["content_full"];
			} else {
				for ($x=0;$x<=sizeof($pages)-1;$x++) {
					$object["pages"][]=$x;
				}
				if (isset($_REQUEST["object_page"])) {
					$current_page=$_REQUEST["object_page"];
					if (!preg_match("/^[0-9]{1,}$/i",$current_page)) $current_page=0;
				} else {
					$current_page=0;
				}
				if (isset($pages[$current_page])) {
					$object["content_page"]=$pages[$current_page];
					$object["current_page"]=$current_page;
				} else {
					$object["content_page"]=$pages[0];
					$object["current_page"]=0;
				}
			}
			
			return $object;
	}
	
	function getObjectByID($id_object=false,&$type,$mode="admin",$visible=0) {
		global $db;
		global $config;
		global $smarty;
		if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%OBJ%` where id_object=$id_object");
			$row=$db->fetch($res);
			if (!is_array($type))
				$type=$this->getTypeByID($row["id_type"]);
				if ($visible!=0) $visible=1;
				if ($visible==1) {
					$max_images=$type["max_images"];
					$max_videos=$type["max_videos"];
				} else {
					$max_images=0;
					$max_videos=0;
				}
			/*получение данных о том голосовали или нет*/
			if (trim($row["voters_ip"])!='') {
				$voters_ip=explode(';',$row["voters_ip"]);
				$my_ip=getIp();
				if (in_array($my_ip,$voters_ip)) {
					$row["may_voters"]=false;
				} else {
					$row["may_voters"]=true;
				}
				if (sizeof($voters_ip)>0) {
					$row["voters_count"]=sizeof($voters_ip);
					$row["vote"]=round($row["voters"]/$row["voters_count"]);
				}
				unset($voters_ip);
			} else {
				$row["may_voters"]=true;
			}
			if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
				$row["tags"]=$tgs->getTags($row["id_object"],"objects","link");
				unset($tgs);
			}
			/*конец получения данных о том голосовали или нет*/
			if ($type["use_files"])
				$row["files"]=$this->getFilesByObject($row["id_object"]);
			$row["values"]=$this->transformateValues($row,$type,$mode);
			if ($type["use_gallery"])
				$row["images"]=$this->getImagesByObject($id_object,$max_images);
			if ($type["use_videogallery"])
				$row["videos"]=$this->getVideosByObject($id_object,$max_videos);
			if ($type["use_objects"]) {
				$row["objects"]=$this->getObjectsByObject($id_object,$visible);
				$types=array();
				$id_objects=array();
				if (is_array($row["objects"]))
				foreach ($row["objects"] as $id_type=>$tp) {
					$types[$id_type]=$this->getTypeByID($id_type);
					foreach ($tp as $key=>$obj) {
						$id_objects[]=$obj["id_object"];
					}
				}
				/*получаем фото и видео для рекомендуемых объектов*/
				$images=$this->getImagesByObjects($id_objects);
				$videos=$this->getVideosByObjects($id_objects);
				/*конец получения фото и видео для рекомендуемых объектов*/
				$smarty->assign("object_types",$types);
				foreach ($types as $key=>$tp) {
	$filename=$config["pathes"]["templates_dir"].$config["templates"]["user_processor"].$tp["ident"].'_short.processor.tpl';
					if (is_file($filename)) {
	$types[$key]["processor"]=$config["templates"]["user_processor"].$tp["ident"].'_short.processor.tpl';
					} else {
	$types[$key]["processor"]=$config["templates"]["user_processor"].'objects_short.processor.tpl';
					}
				}
					if (is_array($row["objects"]))
					foreach ($row["objects"] as $id_type=>$tp) {
					foreach ($tp as $key=>$obj) {
	$row["objects"][$id_type][$key]["values"]=$this->transformateValues($obj,@$types[$id_type],"short");
						$row["objects"][$id_type][$key]["processor"]=@$types[$id_type]["processor"];
						if (isset($images[$obj["id_object"]]))
	$row["objects"][$id_type][$key]["images"]=$images[$obj["id_object"]];
						if (isset($videos[$obj["id_object"]]))
	$row["objects"][$id_type][$key]["videos"]=$videos[$obj["id_object"]];
						unset($row["objects"][$id_type][$key]["value1"]);
						unset($row["objects"][$id_type][$key]["value2"]);
						unset($row["objects"][$id_type][$key]["value3"]);
						unset($row["objects"][$id_type][$key]["value4"]);
						unset($row["objects"][$id_type][$key]["value5"]);
						unset($row["objects"][$id_type][$key]["value6"]);
						unset($row["objects"][$id_type][$key]["value7"]);
						unset($row["objects"][$id_type][$key]["value8"]);
						unset($row["objects"][$id_type][$key]["value9"]);
						unset($row["objects"][$id_type][$key]["value10"]);
						unset($row["objects"][$id_type][$key]["list1"]);
						unset($row["objects"][$id_type][$key]["list2"]);
						unset($row["objects"][$id_type][$key]["list3"]);
					}
					}
			}
			$row=$this->getContentObject($row,"objects/".$row["id_type"]);
			unset($row["value1"]);
			unset($row["value2"]);
			unset($row["value3"]);
			unset($row["value4"]);
			unset($row["value5"]);
			unset($row["value6"]);
			unset($row["value7"]);
			unset($row["value8"]);
			unset($row["value9"]);
			unset($row["value10"]);
			unset($row["list1"]);
			unset($row["list2"]);
			unset($row["list3"]);
			return $row;
		} else {
			return false;
		}
	}
	
	function getObjectByIDEx($id_object=false) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d-%m-%Y %h:%i:%S') as `create_print` from `%OBJ%` where id_object=$id_object");
			$row=$db->fetch($res);
			$row=$this->getContentObject($row,"objects/".$row["id_type"]);
			return $row;
		} else {
			return false;
		}
	}	
	
	function transformateValues($row,$type,$mode="admin") {
		if (is_array($type)) {
		$values=array();
		switch($mode) {
			case "admin":
				if (is_array($type["texts"]))
					foreach ($type["texts"] as $key=>$tp) 
						$values["texts"][$key]=$row["value".($key+1)];
				if (is_array($type["checkbox"]))
					foreach ($type["checkbox"] as $key=>$tp) 
						$values["checkbox"][$key]=$row["value".($key+1)];
				if (is_array($type["lists"]))
					foreach ($type["lists"] as $key=>$tp) 
						$values["lists"][$key]=$row["list".($key+1)];
			break;
			case "full":
				if (is_array($type["texts"]))
					foreach ($type["texts"] as $key=>$tp) {
						if ($tp["show_in_full"] && $tp["type"]!='none') {
							$values["texts"][$key]=$tp;
							$values["texts"][$key]["value"]=$row["value".($key+1)];
						}
					}
				if (is_array($type["checkbox"]))
					foreach ($type["checkbox"] as $key=>$tp)  {
						if ($tp["show_in_full"] && $tp["type"]!='none') {
							$values["checkbox"][$key]=$tp;
							$values["checkbox"][$key]["value"]=$row["value".($key+1)];
						}
					}
				if (is_array($type["lists"]))
					foreach ($type["lists"] as $key=>$tp) {
						if ($tp["show_in_full"] && $tp["type"]!='none') {
							$values["lists"][$key]=$tp;
							$values["lists"][$key]["value"]=$row["list".($key+1)];
							unset($values["lists"][$key]["values"]);
						}
					}
			break;
			case "short":
				if (is_array($type["texts"]))
					foreach ($type["texts"] as $key=>$tp) {
						if ($tp["show_in_short"] && $tp["type"]!='none') {
							$values["texts"][$key]=$tp;
							$values["texts"][$key]["value"]=$row["value".($key+1)];
						}
					}
				if (is_array($type["checkbox"]))
					foreach ($type["checkbox"] as $key=>$tp)  {
						if ($tp["show_in_short"] && $tp["type"]!='none') {
							$values["checkbox"][$key]=$tp;
							$values["checkbox"][$key]["value"]=$row["value".($key+1)];
						}
					}
				if (is_array($type["lists"]))
					foreach ($type["lists"] as $key=>$tp) {
						if ($tp["show_in_short"] && $tp["type"]!='none') {
							$values["lists"][$key]=$tp;
							$values["lists"][$key]["value"]=$row["list".($key+1)];
							unset($values["lists"][$key]["values"]);
						}
					}
			break;
		}
			return $values;
		} else {
			return false;
		}
	}
	
	function addFile($id_object,$id_type,$filename) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_type)) return false;
		if ($db->query("INSERT into `%OBJECT_FILES%` values (NULL,$id_object,$id_type,'".sql_quote($filename)."',0,0,'')")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getFileByID($id_file) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_file)) return false;
		$res=$db->query("select * from `%OBJECT_FILES%` where id_file=$id_file");
		return $db->fetch($res);
	}
	
function convertFileSize($b,$p = null) {
    /**
     *
     * @author Martin Sweeny
     * @version 2010.0617
     *
     * returns formatted number of bytes.
     * two parameters: the bytes and the precision (optional).
     * if no precision is set, function will determine clean
     * result automatically.
     *
     **/
    $units = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");
    $c=0;
    if(!$p && $p !== 0) {
        foreach($units as $k => $u) {
            if(($b / pow(1024,$k)) >= 1) {
                $r["bytes"] = $b / pow(1024,$k);
                $r["units"] = $u;
                $c++;
            }
        }
        return number_format($r["bytes"],2) . " " . $r["units"];
    } else {
        return number_format($b / pow(1024,$p)) . " " . $units[$p];
    }
}	
	
	function getFilesByObject($id_object) {
		global $db;
		global $config;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		$res=$db->query("select * from `%OBJECT_FILES%` where id_object=$id_object order by `sort` DESC");
		while ($row=$db->fetch($res)) {
			$fname=$config["pathes"]["user_files"]."files/".$row["filename"];
			if (is_file($fname)) {
			$row["size"]=$this->convertFileSize(filesize($fname));
			}
			$files[$row["id_file"]]=$row;
		}
		if (isset($files)) return $files;
		return false;
	}
	
	function getCategoriesByType($id_type) {
		global $db;
		if (!preg_match('/^[0-9]{1,}$/i',$id_type)) return false;
		$res=$db->query("select * from `%OBJECT_CATEGORIES%` where id_type=$id_type");
		while ($row=$db->fetch($res)) {
			$categories[]=$row['id_cat'];
		}
		if (isset($categories)) {
			return $categories;
		} else {
			return false;
		}
	}
	
}
?>