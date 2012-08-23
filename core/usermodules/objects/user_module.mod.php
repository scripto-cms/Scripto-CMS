<?
//Модуль объекты, пользовательская часть
global $page;
if (isset($_REQUEST["delete"])) {
	if (isset($_REQUEST["id_object"])) {
		$id_object=@$_REQUEST["id_object"];
		$id_image=@$_REQUEST["id_image"];
		if (preg_match('/^[0-9]{1,}$/i',$id_object) && preg_match('/^[0-9]{1,}$/i',$id_image)) {
			if ($db->query("delete from `%OBJECT_PICTURES%` where id_object=$id_object and id_image=$id_image")) {
				die("ok");
			}
		}
	}
	die("error");
}
if (isset($_REQUEST["add"])) {
	if (isset($_REQUEST["id_type"])) {
		$id_type=@$_REQUEST["id_type"];
		if (preg_match('/^[0-9]{1,}$/i',$id_type)) {
			$type=$this->getTypeByID($id_type);
			$smarty->assign("type",$type);
			$smarty->assign("thismodule",$this->thismodule);
			$engine->addSubPath($lang["objects"]["add"].$type["add_text"],'');
			$page["caption"]=$lang["objects"]["add"].$type["add_text"];
			$smarty->assign("addMode",true);
			if ($type["user_add"]) {
			$values=array();
			$db->user_mode=false;
			$engine->getRubricsTreeEx($values,0,1,true,"",false);
			$type_categories=$this->getCategoriesByType($id_type);
			$db->user_mode=true;
			if (is_array($type_categories)) {
			$need_categories=array();
			foreach ($values as $cat) {
				if (in_array($cat["id"],$type_categories)) {
					$need_categories[]=$cat;
				}
			}
			/*показываем форму добавления*/
				if (is_file($this->thismodule["path"]."user_add_form.mod.php")) {
					@include($this->thismodule["path"]."user_add_form.mod.php");
				}
			/*конец показа формы добавления*/
			} else {
				$smarty->assign("categories_error",true);
			}
			} else {
				$smarty->assign("user_add_error",true);
			}
		}
	}
}
if (isset($_REQUEST["id_file"])) {
	$id_file=$_REQUEST["id_file"];
	if (preg_match("/^[0-9]{1,}$/i",$id_file)) {
		$file=$this->getFileByID($id_file);
		$my_ip=getIp();
		$downloaded_ip=explode(';',$file["downloaded_ip"]);
		if (!in_array($my_ip,$downloaded_ip)) {
			$downloaded_ip[]=$my_ip;
			$downloaded_str=implode(';',$downloaded_ip);
			$db->query("update `%OBJECT_FILES%` set downloaded=downloaded+1,`downloaded_ip`='$downloaded_str' where id_file=".$file["id_file"]);
		}
		$upload_path=$config["pathes"]["user_files"]."files/";
		$engine->download_file($upload_path.$file["filename"]);
	}
	die();
}
if (isset($_REQUEST["id_object"])) {
	//просмотр объекта
	$id_object=$_REQUEST["id_object"];
	if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
		$type=false;
		if ($engine->checkInstallModule("users")) {
			$smarty->assign("users_install",true);
			$users_install=true;
		}
		$object=$this->getObjectById($id_object,$type,"full",1);
		if ($engine->checkInstallModule("comments") && $type["do_comments"]) {
			$smarty->assign("comments_install",true);
			$smarty->assign("comment_type","objects");
			$smarty->assign("object_id",$id_object);
			$cmm=new Comments();
			$cmm->doDb();
			$comments=$cmm->getCommentsByObject($id_object,"objects");
			$smarty->assign("comments",$comments);
		}
		if (isset($_REQUEST["voted"])) {
			/*голосование за объект*/
			$vote=@$_REQUEST["vote"];
			if (preg_match("/^[0-9]{1,}$/i",$vote)) {
				$my_ip=getIp();
				$do_update=false;
				if (trim($object["voters_ip"])=='') {
					$do_update=true;
					$voters_ip=array($my_ip);
				} else {
					$voters_ip=explode(';',$object["voters_ip"]);
					if (!in_array($my_ip,$voters_ip)) {
						$do_update=true;
						$voters_ip[]=$my_ip;
					}
				}
				if ($do_update) {
					$vote=$object["voters"]+$vote;
					$voters_str=implode(";",$voters_ip);
					if ($db->query("update `%OBJ%` set `voters`=$vote, `voters_ip`='$voters_str' where id_object=".$object["id_object"])) {
						die($lang["vote"]["vote_success"]);
					} else {
						die($lang["vote"]["vote_error"]);
					}
				} else {
					die($lang["vote"]["already_vote"]);
				}
			} else {
				die('ERROR');
			}
		}
$filename=$config["pathes"]["templates_dir"].$config["templates"]["user_processor"].$type["ident"].'_full.processor.tpl';
		if (is_file($filename)) {
	$type["processor"]=$config["templates"]["user_processor"].$type["ident"].'_full.processor.tpl';
		} else {
	$type["processor"]=$config["templates"]["user_processor"].'objects_full.processor.tpl';
		}
		$object["processor"]=$type["processor"];
		$smarty->assign("object",$object);
		$smarty->assign("type",$type);
		$db->query("update `%OBJ%` set `views`=`views`+1 where id_object=$id_object");
		$engine->addSubPath($object["caption"],$page['url'].'/?id_object='.$object["id_object"]);
$page["caption"]=$object["caption"];
if (trim($object["title"])!='') $page["title"]=$object["title"];
if (trim($object["meta"])!='') $page["meta"]=$object["meta"];
if (trim($object["keywords"])!='') $page["keywords"]=$object["keywords"];
	}
	$smarty->assign("full_view",true);
} else {
	//получаем список объектов
	//проверяем поисковый запрос
	if (isset($_REQUEST["str"])) {
		$str_real=trim($_REQUEST["str"]);
		if (trim($str_real)!='') {
			$find=strpos($str_real,'*');
			if ($find===false) {
				$str=$str_real.'%';
			} else {
				$str=str_replace('*','%',$str_real);
			}
			$smarty->assign('str',$str_real);
			$smarty->assign('str_url',urlencode($str_real));
		} else {
			$str='';
		}
	} else {
		$str='';
	}
	$types=$this->getTypesByCat($page["id_category"],true);
	$smarty->assign("types",$types);
	$onpage=1;
	$show_alphabet=false;
	if (is_array($types))
	foreach ($types as $key=>$type) {
		if ($type["onpage"]>$onpage) $onpage=$type["onpage"];
		$filename=$config["pathes"]["templates_dir"].$config["templates"]["user_processor"].$type["ident"].'_short.processor.tpl';
		if (is_file($filename)) {
			$types[$key]["processor"]=$config["templates"]["user_processor"].$type["ident"].'_short.processor.tpl';
		} else {
			$types[$key]["processor"]=$config["templates"]["user_processor"].'objects_short.processor.tpl';
		}
		if ($type["show_alphabet"]) {
			$show_alphabet=true;
		}
	}
	if ($show_alphabet) {
		//получаем алфавит
		$rus_alphabet=array();
		$eng_alphabet=array();
		$digits=array();
		for ($x=ord('А');$x<=ord('Я');$x++) $rus_alphabet[]=strtoupper(chr($x));
		for ($x=ord('a');$x<=ord('z');$x++) $eng_alphabet[]=strtoupper(chr($x));
		for ($x=0;$x<=9;$x++) $digits[]=$x;
		$smarty->assign("rus_alphabet",$rus_alphabet);
		$smarty->assign("eng_alphabet",$eng_alphabet);
		$smarty->assign("digits",$digits);
	}
	$smarty->assign("show_alphabet",$show_alphabet);
	$count=$this->getObjectsCountEx($page["id_category"],$str,true,true);
	if ($count>0) {
	$pages=ceil($count/$onpage);
		for ($x=0;$x<=$pages-1;$x++) $pages_arr[]=$x;
			if (isset($_REQUEST["p"])) {
				$pg=@$_REQUEST["p"];
				if (!preg_match("/^[0-9]{1,}$/i",$pg)) $pg=0;
					if ($pg>=$pages) $pg=0;
						if ($pg<0)
							$pg=0;
			} else {
				$pg=0;
			}
			if ($pg>0) {
				$smarty->assign("prev",$pg-1);
			}
			if ($pg<$pages && $pages>1) {
				$smarty->assign("next",$pg+1);
			}
					$objects=$this->getObjectsEx($page["id_category"],$str,true,true,$pg,$onpage);
					if (is_array($objects)) {
					foreach ($objects as $key=>$obj) {
						$objects[$key]["values"]=$this->transformateValues($obj,$types[$obj["id_type"]],"short");
						$objects[$key]["processor"]=$types[$obj["id_type"]]["processor"];
						unset($objects[$key]["value1"]);
						unset($objects[$key]["value2"]);
						unset($objects[$key]["value3"]);
						unset($objects[$key]["value4"]);
						unset($objects[$key]["value5"]);
						unset($objects[$key]["value6"]);
						unset($objects[$key]["value7"]);
						unset($objects[$key]["value8"]);
						unset($objects[$key]["value9"]);
						unset($objects[$key]["value10"]);
						unset($objects[$key]["list1"]);
						unset($objects[$key]["list2"]);
						unset($objects[$key]["list3"]);
					}
					$smarty->assign("objects",$objects);
					if (isset($pages_arr)) {
						$smarty->assign("pages",$pages_arr);
						$smarty->assign("pages_count",sizeof($pages_arr));
						$smarty->assign("pagenumber",$pg);
						$smarty->assign("mp",sizeof($pages_arr));
					}
					}
	}
	$smarty->assign("full_view",false);
}
?>