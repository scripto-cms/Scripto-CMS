<?
/*
Модуль объекты, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
global $rubrics;

global $thismodule;
$m_action=@$_REQUEST["m_action"];
switch ($m_action) {
	case "categoriestype":
		$engine->clearPath();
		$id_type=@$_REQUEST["id_type"];
		if (preg_match('/^[0-9]{1,}$/i',$id_type)) {
			$type=$this->getTypeByID($id_type);
			$smarty->assign("type",$type);
			if (isset($_REQUEST["save"])) {
				$categories=@$_REQUEST["id_cat"];
				$db->query("delete from `%OBJECT_CATEGORIES%` where id_type=$id_type");
				if (is_array($categories)) {
					foreach ($categories as $key=>$cat) {
						$db->query("insert into `%OBJECT_CATEGORIES%` values ($id_type,$key)");
					}
				}
				$smarty->assign("save",true);
			}
			$type_categories=$this->getCategoriesByType($id_type);
			$categories=$engine->getRubricsTree(0,0,false,'',false,"",false);
			foreach ($categories as $key=>$cat) {
				if (is_array($type_categories)) {
					if (in_array($cat["id_category"],$type_categories)) {
						$categories[$key]["type"]=true;
					} else {
						$categories[$key]["type"]=false;
					}
				} else {
					$categories[$key]["type"]=false;
				}
			}
			$smarty->assign("categories",$categories);
		}
	break;
	case "view_types":
	
	break;
	case "view":
	
	break;
	case "dialogobject":
		if (isset($_REQUEST["id_object"])) {
			$id_object=$_REQUEST["id_object"];
			if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
				/*установка свойств*/
				$smarty->assign("doAjaxify",true);
				if (isset($_REQUEST["mode"])) {
				$mode=$_REQUEST["mode"];
				if (preg_match("/^[a-zA-Z0-9_-]{1,}$/i",$mode))
					 $smarty->assign("mode",$mode);
				}
				$smarty->assign("multiple","no");
				//откуда пришел пользователь
				if (!isset($_REQUEST["ref"])) {
					$ref=getenv("HTTP_REFERER");
					$smarty->assign("ref",$ref);
				}
				$engine->setAdminTitle('Выбор объекта');
				$page["title"]='Выбор объекта';
				if (isset($_REQUEST["get_rubrics"])) {
					$smarty->assign("get_rubrics",true);
				} else {
					$smarty->assign("get_rubrics",false);
				}
				/*конец установки свойств*/
				$engine->clearPath();
				$object=$this->getObjectByID($id_object,$type);
				$smarty->assign("object",$object);
				$smarty->assign("id_category",false);
				if (isset($_REQUEST["id_category"])) {
					$id_category=@$_REQUEST["id_category"];
						if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
							$smarty->assign("noModuleHeader",true);
							$objects=$this->getAllObjects($id_category,0);
							$smarty->assign("count_objects",sizeof($objects));
							$smarty->assign("objects",$objects);
							$smarty->assign("get_category",true);
							$smarty->assign("id_category",$id_category);
						}
				}
				$rubrics=$this->getCountAllObjectsEx();
				$smarty->assign("categories",$rubrics);
			}
		}
	break;
	case "files":
		if (isset($_REQUEST["id_object"])) {
			$id_object=$_REQUEST["id_object"];
			if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
				$upload_path=$config["pathes"]["user_files"]."files/";
				$engine->addJS("/core/usermodules/objects/files.js");
				$engine->assignJS();
				$smarty->assign("doFiles",true);
				if (isset($_REQUEST["sort_me"])) {
					//сортировка
					$del=@$_REQUEST["del"];
					$sort=@$_REQUEST["sort"];
					if (is_array($sort)) {
					$d=0;
					$u=0;
					$files=$this->getFilesByObject($id_object);
						foreach ($sort as $id_obj=>$value) {
							if (isset($del[$id_obj])) {
								if (isset($files[$id_obj])) {
									@unlink($upload_path.$files[$id_obj]["filename"]);
								}
								$db->query("delete from `%OBJECT_FILES%` where id_file=$id_obj");
								$d++;
							} else {
								if (preg_match("/^[0-9]{1,}$/i",$sort[$id_obj])) {
									$db->query("update `%OBJECT_FILES%` set `sort`=".$sort[$id_obj]." where id_file=$id_obj");
									$u++;
								}
							}
						}
						$engine->setCongratulation("","Изменения сохранены (Удалено $d файлов , обновлено $u файлов)");
					}
				}
				$object=$this->getObjectByID($id_object,$type);
				$smarty->assign("object",$object);
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
				$engine->addPath($type["caption"],'/admin/?module=modules&modAction=settings&m_action=view&module_name='.$this->thismodule["name"].'&id_category='.$object["id_category"].'&id_type='.$object["id_type"],true);
				$engine->addPath('Просмотр файлов объекта '.$object["caption"],'',false);
				$engine->assignPath();
			}
		}
	break;
	case "objectsgallery":
		if (isset($_REQUEST["id_object"])) {
			$id_object=$_REQUEST["id_object"];
			if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
				if (isset($_REQUEST["sort_me"])) {
					//сортировка
					$del=@$_REQUEST["del"];
					$sort=@$_REQUEST["sort"];
					if (is_array($sort)) {
					$d=0;
					$u=0;
						foreach ($sort as $id_obj=>$value) {
							if (isset($del[$id_obj])) {
								$db->query("delete from `%OBJECT_OBJECTS%` where id_object=$id_object and id_object2=$id_obj");
								$d++;
							} else {
								if (preg_match("/^[0-9]{1,}$/i",$sort[$id_obj])) {
									$db->query("update `%OBJECT_OBJECTS%` set `sort`=".$sort[$id_obj]." where id_object=$id_object and id_object2=$id_obj");
									$u++;
								}
							}
						}
						$engine->setCongratulation("","Изменения сохранены (Удалено $d объектов , обновлено $u объектов)");
					}
				}
				$object=$this->getObjectByID($id_object,$type);
				$smarty->assign("object",$object);
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
				$engine->addPath($type["caption"],'/admin/?module=modules&modAction=settings&m_action=view&module_name='.$this->thismodule["name"].'&id_category='.$object["id_category"].'&id_type='.$object["id_type"],true);
				$engine->addPath('Просмотр объектов объекта '.$object["caption"],'',false);
				$engine->assignPath();
				if (isset($_REQUEST["setObject"])) {
					$id_object2=@$_REQUEST["id_object2"];
					if (preg_match("/^[0-9]{1,}$/i",$id_object2)) {
						$previewMode=@$_REQUEST["previewMode"];
						switch ($previewMode) {
							case "new":
								if ($id_object2!=0) {
								$obj=$this->getObjectByIDEx($id_object2);
								$smarty->assign("objct",$obj);
								$smarty->assign("id_object2",$id_object2);
								if ($this->addObject2Object($id_object,$id_object2)) {
									$smarty->assign("fancyTooltip","Объект успешно добавлен");
								}
								$smarty->assign("addObj",true);
								}
							break;
							default:
								if ($id_object2==0) {
										$db->query("delete from `%OBJECT_OBJECTS%` where id_object=$id_object and id_object2=$previewMode");
										$smarty->assign("fancyTooltip","Объект удален успешно");
										$smarty->assign("id_object2",$previewMode);
										$smarty->assign("deleteObj",true);
								} else {
									$obj=$this->getObjectByIDEx($id_object2);
									$smarty->assign("objct",$obj);
									$smarty->assign("id_object2",$id_object2);
								if ($db->query("update `%OBJECT_OBJECTS%` set id_object2=$id_object2 where id_object2=$previewMode and id_object=$id_object")) {
	$engine->setPreview($obj["caption"],$previewMode,'Объект установлен успешно','html');
								}
								}
						}
						$smarty->assign("closeFancybox",true);
					}
				}
			}
		}
	break;
	case "videogallery":
		if (isset($_REQUEST["id_object"])) {
			$id_object=$_REQUEST["id_object"];
			if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
				if (isset($_REQUEST["sort_me"])) {
					//сортировка
					$del=@$_REQUEST["del"];
					$sort=@$_REQUEST["sort"];
					if (is_array($sort)) {
					$d=0;
					$u=0;
						foreach ($sort as $id_video=>$value) {
							if (isset($del[$id_video])) {
								$db->query("delete from `%OBJECT_VIDEOS%` where id_object=$id_object and id_video=$id_video");
								$d++;
							} else {
								if (preg_match("/^[0-9]{1,}$/i",$sort[$id_video])) {
									$db->query("update `%OBJECT_VIDEOS%` set `sort`=".$sort[$id_video]." where id_object=$id_object and id_video=$id_video");
									$u++;
								}
							}
						}
						$engine->setCongratulation("","Изменения сохранены (Удалено $d видео , обновлено $u видео)");
					}
				}
				$object=$this->getObjectByID($id_object,$type);
				$smarty->assign("object",$object);
				if (sizeof($object["videos"])>$type["max_videos"] && $type["max_videos"]>0) {
					$engine->setCongratulation("Превышен лимит видео","Вы превысили лимит видео (для данного типа - ".$type["max_videos"]."), для пользователя будут выведены первые ".$type["max_videos"]." видео.",5000);
				}
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
				$engine->addPath($type["caption"],'/admin/?module=modules&modAction=settings&m_action=view&module_name='.$this->thismodule["name"].'&id_category='.$object["id_category"].'&id_type='.$object["id_type"],true);
				$engine->addPath('Просмотр галереи видео объекта '.$object["caption"],'',false);
				$engine->assignPath();
				if (isset($_REQUEST["setVideo"])) {
					$previewMode=@$_REQUEST["previewMode"];
					$id_video=@$_REQUEST["id_video"];
					if (preg_match("/^[0-9]{1,}$/i",$id_video)) {
						if ($id_video>0) {
						$video=$engine->getVideoByID($id_video);
						$smarty->assign("id_video",$id_video);
						$smarty->assign("id_category",$object["id_category"]);
						$smarty->assign("vid",$video);
						}
						switch ($previewMode) {
							case "new":
								if (isset($video["caption"])) {
									if ($this->addVideoToObject($id_object,$id_video)) {
						$smarty->assign("fancyTooltip","Видео установлено успешно");
									} else {
						$smarty->assign("fancyTooltip","В процессе добавления видео произошла ошибка");
									}
								} else {
						$smarty->assign("fancyTooltip","Видео не добавлено, т.к. Вы его не выбрали");
								}
								$smarty->assign("addVideo",true);
								$smarty->assign("closeFancybox",true);
							break;
							default:
								if (preg_match("/^[0-9]{1,}$/i",$previewMode)) {
									if (isset($video["caption"])) {
										$db->query("update `%OBJECT_VIDEOS%` set id_video=".$video["id_video"].",`filename`='".$video["filename"]."',`caption`='".$video["caption"]."' where id_object=$id_object and id_video=$previewMode");
										$engine->setPreview('<a href="'.@$_SESSION["scripto_httproot"].'admin/?module=objects&modAction=viewvideo&id_video='.$video["id_video"].'&ajax=true" class="video">'.$video["caption"].'</a>',$previewMode,'Видео установлено успешно','html');
									} else {
										$db->query("delete from `%OBJECT_VIDEOS%` where id_object=$id_object and id_video=$previewMode");
										$smarty->assign("fancyTooltip","Видео удалено успешно");
										$smarty->assign("id_video",$previewMode);
										$smarty->assign("deleteVideo",true);
									}
								}
								$smarty->assign("closeFancybox",true);
						}
					}
				}
			}
		}
	break;	
	case "gallery":
		if (isset($_REQUEST["id_object"])) {
			$id_object=$_REQUEST["id_object"];
			if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
				if (isset($_REQUEST["sort_me"])) {
					//сортировка
					$del=@$_REQUEST["del"];
					$sort=@$_REQUEST["sort"];
					if (is_array($sort)) {
					$d=0;
					$u=0;
						foreach ($sort as $id_image=>$value) {
							if (isset($del[$id_image])) {
								$db->query("delete from `%OBJECT_PICTURES%` where id_object=$id_object and id_image=$id_image");
								$d++;
							} else {
								if (preg_match("/^[0-9]{1,}$/i",$sort[$id_image])) {
									$db->query("update `%OBJECT_PICTURES%` set `sort`=".$sort[$id_image]." where id_object=$id_object and id_image=$id_image");
									$u++;
								}
							}
						}
						$engine->setCongratulation("","Изменения сохранены (Удалено $d изображений , обновлено $u изображений)");
					}
				}
				$object=$this->getObjectByID($id_object,$type);
				$smarty->assign("object",$object);
				if (sizeof($object["images"])>$type["max_images"] && $type["max_images"]>0) {
					$engine->setCongratulation("Превышен лимит изображений","Вы превысили лимит изображений (для данного типа - ".$type["max_images"]."), для пользователя будут выведены первые ".$type["max_images"]." изображений.",5000);
				}
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
				$engine->addPath($type["caption"],'/admin/?module=modules&modAction=settings&m_action=view&module_name='.$this->thismodule["name"].'&id_category='.$object["id_category"].'&id_type='.$object["id_type"],true);
				$engine->addPath('Просмотр галереи изображений объекта '.$object["caption"],'',false);
				$engine->assignPath();
				if (isset($_REQUEST["setPreview"])) {
					$previewMode=@$_REQUEST["previewMode"];
					$id_image=@$_REQUEST["id_image"];
					if (is_array($id_image)) {
						$imgs=array();
						foreach ($id_image as $key=>$id_img) {
							if (preg_match("/^[0-9]{1,}$/i",$id_img)) {
								$image=$engine->getImageByID($id_img);
								$imgs[$key]["img_src"]=$image["small_photo"];
								$imgs[$key]["id_image"]=$id_img;
								$smarty->assign("id_category",$object["id_category"]);
								switch ($previewMode) {
								case "new":
									
if (isset($image["caption"])) {
									if ($this->addImageToObject($id_object,$id_img)) {
									if ($object["small_preview"]=='') {
								$res=$db->query("update `%OBJ%` set `small_preview`='".$image["small_photo"]."' where id_object=$id_object");
									}
									if ($object["middle_preview"]=='') {
								$res=$db->query("update `%OBJ%` set `middle_preview`='".$image["medium_photo"]."' where id_object=$id_object");
									}
						$smarty->assign("fancyTooltip","Изображение добавлено успешно");
									} else {
						$smarty->assign("fancyTooltip","В процессе добавления изображения произошла ошибка");
									}
								} else {
						$smarty->assign("fancyTooltip","Изображение не добавлено, т.к. Вы его не выбрали");
								}
								$smarty->assign("addObjects",true);
								$smarty->assign("closeFancybox",true);
									
								break;
								}
							}
						}
						$smarty->assign("imgs",$imgs);
					} else {
					if (preg_match("/^[0-9]{1,}$/i",$id_image)) {
						if ($id_image>0) {
						$image=$engine->getImageByID($id_image);
						$smarty->assign("img_src",$image["small_photo"]);
						$smarty->assign("id_image",$id_image);
						$smarty->assign("id_category",$object["id_category"]);
						}
						switch ($previewMode) {
							case "new":
								if (isset($image["caption"])) {
									if ($this->addImageToObject($id_object,$id_image)) {
									if ($object["small_preview"]=='') {
								$res=$db->query("update `%OBJ%` set `small_preview`='".$image["small_photo"]."' where id_object=$id_object");
									}
									if ($object["middle_preview"]=='') {
								$res=$db->query("update `%OBJ%` set `middle_preview`='".$image["medium_photo"]."' where id_object=$id_object");
									}
						$smarty->assign("fancyTooltip","Изображение добавлено успешно");
									} else {
						$smarty->assign("fancyTooltip","В процессе добавления изображения произошла ошибка");
									}
								} else {
						$smarty->assign("fancyTooltip","Изображение не добавлено, т.к. Вы его не выбрали");
								}
								$smarty->assign("addObject",true);
								$smarty->assign("closeFancybox",true);
							break;
							default:
								if (preg_match("/^[0-9]{1,}$/i",$previewMode)) {
									if (isset($image["caption"])) {
										$db->query("update `%OBJECT_PICTURES%` set id_image=".$image["id_photo"].",`small_preview`='".$image["small_photo"]."',`middle_preview`='".$image["medium_photo"]."',`big_preview`='".$image["big_photo"]."' where id_object=$id_object and id_image=$previewMode");
										$engine->setPreview($image["small_photo"],$previewMode,'Изображение изменено успешно');
									} else {
										$db->query("delete from `%OBJECT_PICTURES%` where id_object=$id_object and id_image=$previewMode");
										$smarty->assign("fancyTooltip","Изображение удалено успешно");
										$smarty->assign("id_image",$previewMode);
										$smarty->assign("deleteObject",true);
									}
								}
								$smarty->assign("closeFancybox",true);
						}
					}
				}
				}
			}
		}
	break;
	case "deletetype":
		if (isset($_REQUEST["id_type"])) {
			$id_type=$_REQUEST["id_type"];
			if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
				if ($this->deleteType($id_type)) {
					$engine->setCongratulation('','Тип удален успешно',5000);
				}
			}
		}
		$m_action="view_types";
	break;
	case "quickedit":
			$engine->clearPath();
			if (isset($_REQUEST["id_object"])) {
				$id_object=@$_REQUEST["id_object"];
				if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
					$object=$this->getObjectByID($id_object,$type);
				    if (isset($_REQUEST["fck1"])) {
		    			$content=$engine->stripContent(@$_REQUEST["fck1"]);
		    			$first=false;
				    } else {
				    	$content=@$object["content_full"];
				    	$first=true;
				    }	
					$fck_editor1=$engine->createFCKEditor("fck1",$content);
					$smarty->assign("editor",$fck_editor1);
					$smarty->assign("id_object",$id_object);
					$smarty->assign("object",$object);
					$close=@$_REQUEST["close"];
					$smarty->assign("close",$close);
					if (isset($_REQUEST["save"])) {
						if ($engine->setContentFileEx($id_object,$content,"objects/".$object["id_type"])) {
							$smarty->assign("save",true);
						}
					}
				}
			}
	break;
	case "save":
		$id_type=@$_REQUEST["id_type"];
		$d=0;
		$o=0;
		if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
			$idobject=@$_REQUEST["idobject"];
			$caption=@$_REQUEST["caption"];
			$vis=@$_REQUEST["vis"];
			$del=@$_REQUEST["del"];
			$new=@$_REQUEST["new"];
			if (is_array($idobject))
				foreach ($idobject as $key=>$obj) {
				if (isset($del[$key])) {
					if ($this->deleteObject($obj,$id_type)) {
				  		if (defined("SCRIPTO_tags")) {
							$tgs=new Tags();
							$tgs->doDb();
							$tgs->deleteTags($obj,'objects');
						}
						$d++;
					}
				} else {
					if (isset($vis[$key])) {
						$vis_value=1;
					} else {
						$vis_value=0;
					}
					$new_str='';
					if (isset($new[$key])) {
						$new_str=',`new`=0';
					}
					if ($db->query("UPDATE %OBJ% set `caption`='".sql_quote($caption[$key])."',`visible`=$vis_value $new_str where `id_object`=$obj")) {
						$o++;
					}
				}
			}
		}
		$engine->clearCacheBlocks($this->thismodule["name"]);
		$engine->setCongratulation('',"Информация об объектах изменена (удалено $d объектов, обновлено $o объектов)",3000);
		$m_action="view";
	break;
	case "addvalue":
	if (isset($_REQUEST["id_type"])) {
		$id_type=$_REQUEST["id_type"];
		if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
			$type=$this->getTypeByID($id_type);
			$smarty->assign("type",$type);
			$id_category=@$_REQUEST["id_category"];
			if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
				$category=$engine->getCategoryById($id_category);
				$smarty->assign("cat",$category);
				$smarty->assign("id_category",$id_category);
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);	
				$engine->addPath($type["caption"],'/admin/?module=modules&modAction=settings&m_action=view&module_name='.$this->thismodule["name"].'&id_category='.$id_category.'&id_type='.$type["id_type"],true);

				/*добавление объекта*/
				$values=array();
				$engine->getRubricsTreeEx($values,0,0,true,"",false);
			
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_object"])) {
				$id_object=@$_REQUEST["id_object"];
				$object=$this->getObjectByID($id_object,$type);
			}
	  		if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$val=@$_REQUEST["val"];
				$listval=@$_REQUEST["listval"];
					if (is_array($type["checkbox"])) {
						foreach ($type["checkbox"] as $key=>$tp) {
							if (isset($val[$key])) {
								$val[$key]=1;
							} else {
								$val[$key]=0;
							}
						}
					}
				if (defined("SCRIPTO_tags")) {
					$tags=@$_REQUEST["tags"];
				}
				$caption=@$_REQUEST["caption"];
				if ($type["use_code"]) {
				$code=@$_REQUEST["code"];
				}
				if ($type["short_content"]) {
				$content=$engine->stripContent(@$_REQUEST["fck1"]);
				}
				if ($type["full_content"]) {
				$content_full=$engine->stripContent(@$_REQUEST["fck2"]);
				}
				$id_cat=@$_REQUEST["id_cat"];
				if (isset($_REQUEST["visible"])) {
				 $visible=1;
				} else {
				 $visible=0;
				}
				$titletag=@$_REQUEST["titletag"];
				$metatag=@$_REQUEST["metatag"];
				$metakeywords=@$_REQUEST["metakeywords"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($object) {
						if (defined("SCRIPTO_tags")) {
							$tags=$tgs->getTags($object["id_object"],'objects','text');
						}
						$caption=$object["caption"];
						if ($type["short_content"]) {
							$content=$object["small_content"];
						}
						if ($type["full_content"]) {
							$content_full=@$object["content_full"];
						}
						if ($type["use_code"]) {
							$code=$object["code"];
						}
						if (isset($object["values"]["texts"]))
						if (is_array($object["values"]["texts"])) {
							foreach ($object["values"]["texts"] as $key=>$tp) {
								$val[$key]=$tp;
							}
						}	
						if (isset($object["values"]["checkbox"]))
						if (is_array($object["values"]["checkbox"])) {
							foreach ($object["values"]["checkbox"] as $key=>$tp) {
								$val[$key]=$tp;
							}
						}
						if (isset($object["values"]["lists"]))
						if (is_array($object["values"]["lists"])) {
							foreach ($object["values"]["lists"] as $key=>$tp) {
								$listval[$key]=$tp;
							}
						}
						$id_cat=@$object["id_category"];
						$visible=@$object["visible"];
						$titletag=@$object["title"];
						$metatag=@$object["meta"];
						$metakeywords=@$object["keywords"];
						$lang_values=$engine->generateLangArray("OBJ",$object);
					}
				} else {
					if (defined("SCRIPTO_tags")) {
						$tags='';
					}
					if (is_array($type["texts"])) {
						foreach ($type["texts"] as $key=>$tp) {
							$val[$key]='';
						}
					}	
					
					if (is_array($type["checkbox"])) {
						foreach ($type["checkbox"] as $key=>$tp) {
							$val[$key]=0;
						}
					}
						
					if (is_array($type["lists"])) {
						foreach ($type["lists"] as $key=>$tp) {
							$listval[$key]='';
						}
					}
					$caption="";
					if ($type["short_content"]) {
					$content="";
					}
					if ($type["full_content"]) {
					$content_full="";
					}
					if (isset($_REQUEST["id_category"])) {
						if (preg_match("/^[0-9]{1,}$/i",$_REQUEST["id_category"])) {
							$id_cat=$_REQUEST["id_category"];
						}
					} else {
					$id_cat=@$values[0]["id"];
					}
					if ($type["use_code"]) {
						$code=rand(1,10000).rand(1,10000);
					}
					$visible=1;
					$titletag='';
					$metatag='';
					$metakeywords='';
					$lang_values=$engine->generateLangArray("OBJ",null);
				}
			}
			
			$smarty->assign("is_file_form",true);
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			$frm->addField('Раздел','Ошибка выбора раздела',"list",$values,$id_cat,"/^[0-9]{1,}$/i","id_cat",1,'О компании&nbsp;>&nbsp;публикации',array('size'=>'30'));
			
			$frm->addField("Название ".$type["fulllink_text"],"Неверно заполнено название ".$type["fulllink_text"],"text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Дом в Подмосковье",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

if (defined("SCRIPTO_tags")) {
$frm->addField($lang["forms"]["catalog"]["tags"]["caption"],$lang["forms"]["catalog"]["tags"]["error"],"text",$tags,$tags,"/^[^`#]{2,255}$/i","tags",0,$lang["forms"]["catalog"]["tags"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["tags"]["rules"]));
}

			if ($type["use_code"]) {
				$frm->addField("Уникальный код ".$type["fulllink_text"],"Неверно заполнен уникальный код ".$type["fulllink_text"],"text",$code,$code,"/^[^`#]{2,255}$/i","code",1,"24545356",array('size'=>'40','ticket'=>"Любые буквы и цифры"));
			}
			if ($type["short_content"]) {
			$fck_editor1=$engine->createFCKEditor("fck1",$content);
			$frm->addField("Краткое описание ".$type["fulllink_text"],"Неверно заполнено краткое описание ".$type["fulllink_text"],"solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");
			}
			if ($type["full_content"]) {
			$fck_editor2=$engine->createFCKEditor("fck2",$content_full);
			$frm->addField("Полное описание ".$type["fulllink_text"],"Неверно заполнено полное описание ".$type["fulllink_text"],"solmetra",$fck_editor2,$fck_editor2,"/^[[:print:][:allnum:]]{1,}$/i","content_full",1,"");
			}
			/*seo*/
$frm->addField('SEO оптимизация',"","caption",0,0,"/^[0-9]{1}$/i","seo",0,'',array('hidden'=>true));

$frm->addField('Тег title','Ошибка заполнения тега title',"text",$titletag,$titletag,"/^[^`#]{2,255}$/i","titletag",0,'',array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["titletag"]["rules"]));

$frm->addField('Тег meta','Ошибка заполнения тега meta',"textarea",$metatag,$metatag,"/^[^#]{1,}$/i","metatag",0,'',array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["catalog"]["metatag"]["rules"]));

$frm->addField('Тег meta keywords','Ошибка заполнения тега meta keywords',"textarea",$metakeywords,$metakeywords,"/^[^#]{1,}$/i","metakeywords",0,'',array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["catalog"]["metakeywords"]["rules"]));

$frm->addField('',"","caption",0,0,"/^[0-9]{1}$/i","seo",0,'',array('end'=>true));

$engine->generateLangControls("OBJ",$lang_values,$frm);
			/*end of seo*/
			/*работаем с изображениями*/
			//устанавливаем превью
				if (isset($_REQUEST["setPreview"])) {
					$previewMode=@$_REQUEST["previewMode"];
					$id_image=@$_REQUEST["id_image"];
					if (preg_match("/^[0-9]{1,}$/i",$id_image)) {
						if ($id_image==0) {
						$img["small_photo"]='';
						$img["medium_photo"]='';
						} else {
						$img=$engine->getImageByID($id_image);
						}
						switch ($previewMode) {
							case "small":
								if ($mode!="edit") {
$engine->setPreview($img["small_photo"],$previewMode,'Малое превью установлено');
$_SESSION["object_small"]=$img["small_photo"];
								} else {
								if ($db->query("update `%OBJ%` set `small_preview`='".$img["small_photo"]."' where id_object=$id_object")) {
$engine->setPreview($img["small_photo"],$previewMode,'Малое превью установлено');
								}
								}
							break;
							case "medium":
								if ($mode!="edit") {
$engine->setPreview($img["medium_photo"],$previewMode,'Среднее превью установлено');
$_SESSION["object_middle"]=$img["medium_photo"];
								} else {
								if ($db->query("update `%OBJ%` set `middle_preview`='".$img["medium_photo"]."' where id_object=$id_object")) {
									$engine->setPreview($img["medium_photo"],$previewMode,'Среднее превью установлено');
								}
								}
							break;
						}
					}
				}
			if ($type["small_preview"] || $type["medium_preview"]) {
			$frm->addField('Превью для '.$type["fulllink_text"],"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('hidden'=>true));	
			if ($mode=="edit") {
			if ($type["small_preview"]) 
				$frm->addField('Малое изображение превью','',"preview",$object["small_preview"],$object["small_preview"],"/^[0-9]{1,}$/i","small_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true,'id_cat'=>$object["id_category"]));
			if ($type["medium_preview"])
				$frm->addField('Большое изображение превью','',"preview",$object["middle_preview"],$object["middle_preview"],"/^[0-9]{1,}$/i","middle_preview",0,'',array('mode'=>'medium','multiple'=>'no','fancy_show'=>true,'id_cat'=>$object["id_category"]));
			} else {
			if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
				$cat_idcat=$id_category;
			} else {
				$cat_idcat=false;
			}
			if ($type["small_preview"])  {
				if (isset($_SESSION["object_small"])) {
					$object["small_preview"]=$_SESSION["object_small"];
				} else {
					$object["small_preview"]='';
				}
				$frm->addField('Малое изображение превью','',"preview",$object["small_preview"],$object["small_preview"],"/^[0-9]{1,}$/i","small_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true,'id_cat'=>$cat_idcat));
			} else {
				$object["small_preview"]='';
			}
			if ($type["medium_preview"])  {
				if (isset($_SESSION["object_middle"])) {
					$object["middle_preview"]=$_SESSION["object_middle"];
				} else {
					$object["middle_preview"]='';
				}
				$frm->addField('Среднее изображение превью','',"preview",$object["middle_preview"],$object["middle_preview"],"/^[0-9]{1,}$/i","middle_preview",0,'',array('mode'=>'medium','multiple'=>'no','fancy_show'=>true,'id_cat'=>$cat_idcat));
			} else {
				$object["middle_preview"]='';
			}
			}
			$frm->addField('Превью для '.$type["fulllink_text"],"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('end'=>true));		
			} else {
				$object["small_preview"]='';
				$object["middle_preview"]='';
			}	
			/*конец работы с изображениями*/

			if (isset($type["texts"])) 
			if (is_array($type["texts"])) {
				foreach ($type["texts"] as $key=>$tp) {
					if (isset($this->thismodule["eregi"][$tp["type"]])) {
						$eregi=$this->thismodule["eregi"][$tp["type"]];
					} else {
						$eregi="/^[^`]{1,}$/i";
					}
					if ($tp["type"]=="textarea") {
					$frm->addField($tp["caption"],"Неверно заполнено поле ".$tp["caption"],"textarea",$val[$key],$val[$key],$eregi,"val[$key]",0,"",array('size'=>'40'));	
					} else{
					$frm->addField($tp["caption"],"Неверно заполнено поле ".$tp["caption"],"text",$val[$key],$val[$key],$eregi,"val[$key]",0,"",array('size'=>'40'));	
					}
				}
			}
			if (isset($type["lists"])) 
			if (is_array($type["lists"])) {
				foreach ($type["lists"] as $key=>$tp) {
					if (isset($this->thismodule["eregi"][$tp["type"]])) {
						$eregi=$this->thismodule["eregi"][$tp["type"]];
					} else {
						$eregi="/^[^`]{1,}$/i";
					}
					$frm->addField($tp["caption"],"Неверно выбрано значение ".$tp["caption"],"list",$tp["values"],$listval[$key],$eregi,"listval[$key]",0,"",array('size'=>'40'));	
				}
			}
			if (isset($type["checkbox"])) 
			if (is_array($type["checkbox"])) {
				foreach ($type["checkbox"] as $key=>$tp) {
					$frm->addField($tp["caption"],"Неверно заполнено поле ".$tp["caption"],"check",$val[$key],$val[$key],"/^[0-9]{0,1}$/i","val[$key]",0,"");	
				}				
			}
			
			$frm->addField('Виден на сайте','Неверно указано свойство видимости',"check",$visible,$visible,"/^[0-9]{1}$/i","visible",1);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$id_category,$id_category,"/^[^`]{0,}$/i","id_category",1);
$frm->addField("","","hidden",$id_type,$id_type,"/^[^`]{0,}$/i","id_type",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_object"])) {
$id_object=$_REQUEST["id_object"];
$frm->addField("","","hidden",$id_object,$id_object,"/^[^`]{0,}$/i","id_object",1);
}

if ($mode=="edit") {
$engine->addPath('Редактирование объекта','',false);
$btn="Сохранить";
	if ($type["use_code"] && $code!=$object["code"]) {
		if ($this->existObject($code,$id_type))
			$frm->addError('Объект с таким кодом уже существует!');
	}
} else {
$engine->addPath('Добавление объекта','',false);
$btn="Добавить";
	if ($type["use_code"] && !$first) {
		if ($this->existObject($code,$id_type))
			$frm->addError('Объект с таким кодом уже существует!');
	}
}
			if (
$engine->processFormData($frm,$btn,$first
			)) {
				
				if ($mode=="edit") {
					//редактирование
					if (!$type["short_content"]) {
						$content_sql='';
					} else {
						$content_sql=",`small_content`='".sql_quote($content)."'";
					}
					if (!$type["use_code"]) {
						$code_sql='';
					} else {
						$code_sql=",`code`='".sql_quote($code)."'";
					}
					$values_str='';
					$listvalues_str='';
					if (is_array($val)) 
						foreach($val as $k=>$v) 
							$values_str.=",`value".($k+1)."`='".sql_quote($v)."'";
					if (is_array($listval)) 
						foreach($listval as $k=>$v) 
							$listvalues_str.=",`list".($k+1)."`='".sql_quote($v)."'";							
						
					if ($db->query("update `%OBJ%` set `id_category`=$id_cat,`caption`='".sql_quote($caption)."',`visible`=$visible,`new`=0,`title`='".sql_quote($titletag)."',`meta`='".sql_quote($metatag)."',`keywords`='".sql_quote($metakeywords)."' $values_str $listvalues_str $content_sql $code_sql ".$engine->generateUpdateSQL("OBJ",$lang_values)." where id_object=$id_object")) {
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$id_object,'objects');
						}
						$engine->setCongratulation('','Объект отредактирован успешно!',3000);
						$engine->clearCacheBlocks($this->thismodule["name"]);
	$engine->addModuleToCategory($this->thismodule["name"],$id_cat);
$_REQUEST["id_category"]=$id_cat;
						if ($type["full_content"]) {
							$engine->setContentFileEx($id_object,$content_full,"objects/".$type["id_type"]);
						}
						$m_action="view";
					}
				} else {
					//добавление
					if (!$type["short_content"]) {
						$content='';
					}
					if (!$type["use_code"]) {
						$code='';
					}
					if (!$type["full_content"]) {
						$content_full='';
					}
					$add_id=$this->addObject($id_category,$type["id_type"],$caption,$titletag,$metatag,$metakeywords,$code,$object["small_preview"],$object["middle_preview"],$content,$content_full,$val,$listval,null,$visible,0,$engine->generateInsertSQL("OBJ",$lang_values));
					if ($add_id>0) {
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$add_id,'objects');
						}
						if (isset($_SESSION["object_middle"]))
							$_SESSION["object_middle"]='';
						if (isset($_SESSION["object_small"]))
							$_SESSION["object_small"]='';
						$engine->setCongratulation('','Объект добавлен успешно!',3000);
					$engine->clearCacheBlocks($this->thismodule["name"]);	$engine->addModuleToCategory($this->thismodule["name"],$id_category);
						if ($type["full_content"]) {
							$engine->setContentFileEx($add_id,$content_full,"objects/".$type["id_type"]);
						}
						$m_action="view";
					}
				}

			}				
				
				/*конец добавления объекта*/
				
				$engine->assignPath();
			}
		}
	}
	break;
	case "addtype":
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_type"])) {
				$id_type=@$_REQUEST["id_type"];
				$type=$this->getTypeByID($id_type);
			}
			$values=array();
			$engine->getRubricsTreeEx($values,0,0,true,"",false);
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$ident=@$_REQUEST["ident"];
				$content=$engine->stripContent(@$_REQUEST["fck1"]);
				if (isset($_REQUEST["use_gallery"])) {
					$use_gallery=1;
				} else {
					$use_gallery=0;
				}
				if (isset($_REQUEST["use_videogallery"])) {
					$use_videogallery=1;
				} else {
					$use_videogallery=0;
				}
				if (isset($_REQUEST["use_objects"])) {
					$use_objects=1;
				} else {
					$use_objects=0;
				}
				if (isset($_REQUEST["use_files"])) {
					$use_files=1;
				} else {
					$use_files=0;
				}
				if (isset($_REQUEST["user_add"])) {
					$user_add=1;
				} else {
					$user_add=0;
				}
				if (isset($_REQUEST["download_only_for_users"])) {
					$download_only_for_users=1;
				} else {
					$download_only_for_users=0;
				}
				if (isset($_REQUEST["alphabet"])) {
					$alphabet=1;
				} else {
					$alphabet=0;
				}
				if (isset($_REQUEST["use_code"])) {
					$use_code=1;
				} else {
					$use_code=0;
				}
				if (isset($_REQUEST["do_comments"])) {
					$do_comments=1;
				} else {
					$do_comments=0;
				}
				if (isset($_REQUEST["small_preview"])) {
					$small_preview=1;
				} else {
					$small_preview=0;
				}
				if (isset($_REQUEST["medium_preview"])) {
					$medium_preview=1;
				} else {
					$medium_preview=0;
				}	
				if (isset($_REQUEST["short_content"])) {
					$short_content=1;
				} else {
					$short_content=0;
				}
				if (isset($_REQUEST["full_content"])) {
					$full_content=1;
				} else {
					$full_content=0;
				}		
				if (isset($_REQUEST["voters"])) {
					$voters=1;
				} else {
					$voters=0;
				}
				$start_value=@$_REQUEST["start_value"];
				$caption1=@$_REQUEST["caption1"];
				$type1=@$_REQUEST["type1"];
				if (isset($_REQUEST["show_in_full1"])) {
					$show_in_full1=1;
				} else {
					$show_in_full1=0;
				}
				if (isset($_REQUEST["show_in_short1"])) {
					$show_in_short1=1;
				} else {
					$show_in_short1=0;
				}
				$caption2=@$_REQUEST["caption2"];
				$type2=@$_REQUEST["type2"];
				if (isset($_REQUEST["show_in_full2"])) {
					$show_in_full2=1;
				} else {
					$show_in_full2=0;
				}
				if (isset($_REQUEST["show_in_short2"])) {
					$show_in_short2=1;
				} else {
					$show_in_short2=0;
				}
				$caption3=@$_REQUEST["caption3"];
				$type3=@$_REQUEST["type3"];
				if (isset($_REQUEST["show_in_full3"])) {
					$show_in_full3=1;
				} else {
					$show_in_full3=0;
				}
				if (isset($_REQUEST["show_in_short3"])) {
					$show_in_short3=1;
				} else {
					$show_in_short3=0;
				}
				$caption4=@$_REQUEST["caption4"];
				$type4=@$_REQUEST["type4"];	
				if (isset($_REQUEST["show_in_full4"])) {
					$show_in_full4=1;
				} else {
					$show_in_full4=0;
				}
				if (isset($_REQUEST["show_in_short4"])) {
					$show_in_short4=1;
				} else {
					$show_in_short4=0;
				}
				$caption5=@$_REQUEST["caption5"];
				$type5=@$_REQUEST["type5"];	
				if (isset($_REQUEST["show_in_full5"])) {
					$show_in_full5=1;
				} else {
					$show_in_full5=0;
				}
				if (isset($_REQUEST["show_in_short5"])) {
					$show_in_short5=1;
				} else {
					$show_in_short5=0;
				}
				$caption6=@$_REQUEST["caption6"];
				$type6=@$_REQUEST["type6"];
				if (isset($_REQUEST["show_in_full6"])) {
					$show_in_full6=1;
				} else {
					$show_in_full6=0;
				}
				if (isset($_REQUEST["show_in_short6"])) {
					$show_in_short6=1;
				} else {
					$show_in_short6=0;
				}
				$caption7=@$_REQUEST["caption7"];
				$type7=@$_REQUEST["type7"];	
				if (isset($_REQUEST["show_in_full7"])) {
					$show_in_full7=1;
				} else {
					$show_in_full7=0;
				}
				if (isset($_REQUEST["show_in_short7"])) {
					$show_in_short7=1;
				} else {
					$show_in_short7=0;
				}
				$caption8=@$_REQUEST["caption8"];
				$type8=@$_REQUEST["type8"];
				if (isset($_REQUEST["show_in_full8"])) {
					$show_in_full8=1;
				} else {
					$show_in_full8=0;
				}
				if (isset($_REQUEST["show_in_short8"])) {
					$show_in_short8=1;
				} else {
					$show_in_short8=0;
				}
				$caption9=@$_REQUEST["caption9"];
				$type9=@$_REQUEST["type9"];	
				if (isset($_REQUEST["show_in_full9"])) {
					$show_in_full9=1;
				} else {
					$show_in_full9=0;
				}
				if (isset($_REQUEST["show_in_short9"])) {
					$show_in_short9=1;
				} else {
					$show_in_short9=0;
				}
				$caption10=@$_REQUEST["caption10"];
				$type10=@$_REQUEST["type10"];
				if (isset($_REQUEST["show_in_full10"])) {
					$show_in_full10=1;
				} else {
					$show_in_full10=0;
				}
				if (isset($_REQUEST["show_in_short10"])) {
					$show_in_short10=1;
				} else {
					$show_in_short10=0;
				}
				$list_caption1=@$_REQUEST["list_caption1"];
				$list_value1=@$_REQUEST["list_value1"];
				$list_type1=@$_REQUEST["list_type1"];
				if (isset($_REQUEST["list_show_in_full1"])) {
					$list_show_in_full1=1;
				} else {
					$list_show_in_full1=0;
				}
				if (isset($_REQUEST["list_show_in_short1"])) {
					$list_show_in_short1=1;
				} else {
					$list_show_in_short1=0;
				}
				$list_caption2=@$_REQUEST["list_caption2"];
				$list_value2=@$_REQUEST["list_value2"];
				$list_type2=@$_REQUEST["list_type2"];
				if (isset($_REQUEST["list_show_in_full2"])) {
					$list_show_in_full2=1;
				} else {
					$list_show_in_full2=0;
				}
				if (isset($_REQUEST["list_show_in_short2"])) {
					$list_show_in_short2=1;
				} else {
					$list_show_in_short2=0;
				}
				$list_caption3=@$_REQUEST["list_caption3"];
				$list_value3=@$_REQUEST["list_value3"];
				$list_type3=@$_REQUEST["list_type3"];
				if (isset($_REQUEST["list_show_in_full3"])) {
					$list_show_in_full3=1;
				} else {
					$list_show_in_full3=0;
				}
				if (isset($_REQUEST["list_show_in_short3"])) {
					$list_show_in_short3=1;
				} else {
					$list_show_in_short3=0;
				}
				$view_all_text=@$_REQUEST['view_all_text'];
				$add_text=@$_REQUEST['add_text'];	
				$fulllink_text=@$_REQUEST['fulllink_text'];
				$congratulation_text=@$_REQUEST["fck2"];
				$edit_text=@$_REQUEST["fck3"];
				$max_images=@$_REQUEST["max_images"];
				$max_videos=@$_REQUEST["max_videos"];
				$onpage=@$_REQUEST["onpage"];
				$add_cat=@$_REQUEST["add_cat"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($type) {
					$caption=$type["caption"];
					$ident=$type["ident"];
					$small_preview=$type["small_preview"];
					$medium_preview=$type["medium_preview"];
					$short_content=$type["short_content"];
					$full_content=$type["full_content"];
					$voters=$type["voters"];
					$content=$type["description"];
					$alphabet=$type["show_alphabet"];
					$use_gallery=$type["use_gallery"];
					$use_videogallery=$type["use_videogallery"];
					$use_objects=$type["use_objects"];
					$user_add=$type["user_add"];
					$use_files=$type["use_files"];
					$type1=$type["type1"];
					$caption1=$type["caption1"];
					$show_in_full1=$type["show_in_full1"];
					$show_in_short1=$type["show_in_short1"];
					$type2=$type["type2"];
					$caption2=$type["caption2"];
					$show_in_full2=$type["show_in_full2"];
					$show_in_short2=$type["show_in_short2"];
					$type3=$type["type3"];
					$caption3=$type["caption3"];
					$show_in_full3=$type["show_in_full3"];
					$show_in_short3=$type["show_in_short3"];
					$type4=$type["type4"];
					$caption4=$type["caption4"];
					$show_in_full4=$type["show_in_full4"];
					$show_in_short4=$type["show_in_short4"];
					$type5=$type["type5"];
					$caption5=$type["caption5"];
					$show_in_full5=$type["show_in_full5"];
					$show_in_short5=$type["show_in_short5"];
					$type6=$type["type6"];
					$caption6=$type["caption6"];
					$show_in_full6=$type["show_in_full6"];
					$show_in_short6=$type["show_in_short6"];
					$type7=$type["type7"];
					$caption7=$type["caption7"];
					$show_in_full7=$type["show_in_full7"];
					$show_in_short7=$type["show_in_short7"];
					$type8=$type["type8"];
					$caption8=$type["caption8"];
					$show_in_full8=$type["show_in_full8"];
					$show_in_short8=$type["show_in_short8"];
					$type9=$type["type9"];
					$caption9=$type["caption9"];
					$show_in_full9=$type["show_in_full9"];
					$show_in_short9=$type["show_in_short9"];
					$type10=$type["type10"];
					$caption10=$type["caption10"];
					$show_in_full10=$type["show_in_full10"];
					$show_in_short10=$type["show_in_short10"];
					$list_type1=$type["list_type1"];
					$list_value1=$type["list_value1"];
					$list_caption1=$type["list_caption1"];
					$list_show_in_full1=$type["list_show_in_full1"];
					$list_show_in_short1=$type["list_show_in_short1"];
					$list_type2=$type["list_type2"];
					$list_value2=$type["list_value2"];
					$list_caption2=$type["list_caption2"];
					$list_show_in_full2=$type["list_show_in_full2"];
					$list_show_in_short2=$type["list_show_in_short2"];
					$list_type3=$type["list_type3"];
					$list_value3=$type["list_value3"];
					$list_caption3=$type["list_caption3"];
					$list_show_in_full3=$type["list_show_in_full3"];
					$list_show_in_short3=$type["list_show_in_short3"];
					$view_all_text=$type["view_all_text"];
					$add_text=$type["add_text"];	
					$fulllink_text=$type["fulllink_text"];
					$max_images=$type["max_images"];
					$max_videos=$type["max_videos"];
					$use_code=$type["use_code"];
					$do_comments=$type["do_comments"];
					$onpage=$type["onpage"];
					$download_only_for_users=$type["download_only_for_users"];
					$add_cat=$type["add_cat"];
					$congratulation_text=$type["congratulation_text"];
					$edit_text=$type["edit_text"];
					$lang_values=$engine->generateLangArray("TYPES",$type);
					}
				} else {
					$caption="";
					$ident="";
					$small_preview=0;
					$medium_preview=0;
					$short_content=0;
					$full_content=0;
					$voters=0;
					$content='';
					$alphabet=0;
					$use_gallery=0;
					$use_videogallery=0;
					$use_objects=0;
					$use_files=0;
					$type1='none';
					$caption1='';
					$show_in_full1=0;
					$show_in_short1=0;
					$type2='none';
					$caption2='';
					$show_in_full2=0;
					$show_in_short2=0;
					$type3='none';
					$caption3='';
					$show_in_full3=0;
					$show_in_short3=0;
					$user_add=0;
					$type4='none';
					$caption4='';
					$show_in_full4=0;
					$show_in_short4=0;
					$type5='none';
					$caption5='';
					$show_in_full5=0;
					$show_in_short5=0;
					$type6='none';
					$caption6='';
					$show_in_full6=0;
					$show_in_short6=0;
					$type7='none';
					$caption7='';
					$show_in_full7=0;
					$show_in_short7=0;
					$type8='none';
					$caption8='';
					$show_in_full8=0;
					$show_in_short8=0;
					$type9='none';
					$caption9='';
					$show_in_full9=0;
					$show_in_short9=0;
					$type10='none';
					$caption10='';
					$show_in_full10=0;
					$show_in_short10=0;
					$list_type1='none';
					$list_value1='';
					$list_caption1='';
					$list_show_in_full1=0;
					$list_show_in_short1=0;
					$list_type2='none';
					$list_value2='';
					$list_caption2='';
					$list_show_in_full2=0;
					$list_show_in_short2=0;
					$list_type3='none';
					$list_value3='';
					$list_caption3='';
					$list_show_in_full3=0;
					$list_show_in_short3=0;
					$view_all_text='';
					$add_text='';	
					$fulllink_text='';
					$congratulation_text='';
					$edit_text='';
					$max_images=0;
					$max_videos=0;
					$use_code=0;
					$do_comments=1;
					$onpage=25;
					$download_only_for_users=0;
					$add_cat=0;
					$lang_values=$engine->generateLangArray("TYPES",null);
				}
			}

			require ($config["classes"]["form"]);
			$frm=new Form($smarty);

$frm->addField("Название типа","Неверно заполнено название типа","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Артисты",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Идентификатор типа","Неверно заполнен идентификатор типа","text",$ident,$ident,"/^[a-zA-Z0-9]{2,30}$/i","ident",1,"artists",array('size'=>'40','ticket'=>"Латинские буквы и цифры от 2 до 30 знаков"));

$frm->addField("Добавление объектов пользователями","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","userobjects",0,'',array('hidden'=>true));
$frm->addField("Разрешить добавление объектов пользователями","Неверно выбрано свойство разрешить добавление объектов пользователями","check",$user_add,$user_add,"/^[0-9]{1}$/i","user_add",1);

$frm->addField('Разместить форму добавления в разделе','Ошибка выбора раздела',"list",$values,$add_cat,"/^[0-9]{1,}$/i","add_cat",1,'Главная страница&nbsp;>&nbsp;Добавить статью',array('size'=>'30'));

$fck_editor2=$engine->createFCKEditor("fck2",$congratulation_text);
$frm->addField("Текст, при успешном добавлении объекта пользователем","Неверно указан текст при добавлении объекта пользователем","solmetra",$fck_editor2,$fck_editor2,"/^[^`#]{2,255}$/i","congratulation_text",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$fck_editor3=$engine->createFCKEditor("fck3",$edit_text);
$frm->addField("Текст, при успешном редактировании объекта пользователем","Неверно указан текст при редактировании объекта пользователем","solmetra",$fck_editor3,$fck_editor3,"/^[^`#]{2,255}$/i","edit_text",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Добавление объектов пользователями","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","userobjects",0,'',array('end'=>true));

$frm->addField("Выводить алфавитный список","Неверно выбрано свойство выводить алфавитный список","check",$alphabet,$alphabet,"/^[0-9]{1}$/i","alphabet",1);

$frm->addField("Разрешить комментарии к объектам","Неверно выбрано свойство разрешить комментарии к объектам","check",$do_comments,$do_comments,"/^[0-9]{1}$/i","do_comments",1);

$fck_editor1=$engine->createFCKEditor("fck1",$content);
$frm->addField("Описание","Неверно заполнено описание","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$frm->addField("Разрешить добавление рекомендуемых объектов","Неверно выбрано свойство разрешить добавление рекомендуемых объектов","check",$use_objects,$use_objects,"/^[0-9]{1}$/i","use_objects",1);

$frm->addField("Разрешить прикрепление файлов к объектам","Неверно выбрано свойство разрешить прикрепление файлов к объектам","check",$use_files,$use_files,"/^[0-9]{1}$/i","use_files",1);

$frm->addField("Разрешить скачивание файлов только зарегистрированным пользователям (если установлен модуль пользователи)","Неверно выбрано свойство разрешить скачивание файлов только зарегистрированным пользователям","check",$download_only_for_users,$download_only_for_users,"/^[0-9]{1}$/i","download_only_for_users",1);

$frm->addField("Настройки изображений","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","images",0,'',array('hidden'=>true));
$frm->addField("Выводить галерею изображений у объекта","Неверно выбрано свойство выводить галерею изображений у объекта","check",$use_gallery,$use_gallery,"/^[0-9]{1}$/i","use_gallery",1);
$frm->addField("Максимальное количество изображений у объекта","Неверно указано максимальное количество изображений у объекта","text",$max_images,$max_images,"/^[0-9]{1,}$/i","max_images",1,"10",array('size'=>'40','ticket'=>"Цифры, максимальное количество - 25"));
$frm->addField("Загружать маленькое изображение превью","","check",$small_preview,$small_preview,"/^[0-9]{1}$/i","small_preview",1);
$frm->addField("Загружать среднее изображение превью","","check",$medium_preview,$medium_preview,"/^[0-9]{1}$/i","medium_preview",1);
$frm->addField("Настройки изображений","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","images",0,'',array('end'=>true));

$frm->addField("Настройки видео","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","videos",0,'',array('hidden'=>true));
$frm->addField("Выводить галерею видео у объекта","Неверно выбрано свойство выводить галерею видео у объекта","check",$use_videogallery,$use_videogallery,"/^[0-9]{1}$/i","use_videogallery",1);
$frm->addField("Максимальное количество видео у объекта","Неверно указано максимальное количество видео у объекта","text",$max_videos,$max_videos,"/^[0-9]{1,}$/i","max_videos",1,"10",array('size'=>'40','ticket'=>"Цифры, максимальное количество - 25"));
$frm->addField("Настройки видео","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","videos",0,'',array('end'=>true));

$frm->addField("Разрешить голосование за объекты","","check",$voters,$voters,"/^[0-9]{1}$/i","voters",1);
$frm->addField("У каждого объекта свой уникальный код","","check",$use_code,$use_code,"/^[0-9]{1}$/i","use_code",1);
$frm->addField("Краткое содержимое","","check",$short_content,$short_content,"/^[0-9]{1}$/i","short_content",1);
$frm->addField("Полное содержимое","","check",$full_content,$full_content,"/^[0-9]{1}$/i","full_content",1);
$frm->addField("Количество выводимых объектов на странице","Неверно указано максимальное количество выводимых объектов","text",$onpage,$onpage,"/^[0-9]{1,}$/i","onpage",1,"10",array('size'=>'40','ticket'=>"Цифры"));

$frm->addField("Настройки текстовых полей","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","fields",0,'',array('hidden'=>true));

$frm->addField("Название поля 1","Ошибка ввода названия поля 1","text",$caption1,$caption1,"/^[^`]{1,}$/i","caption1",0,'',array('size'=>'30'));
$frm->addField("Тип поля 1","Ошибка выбора типа поля 1","list",$this->thismodule["types"],$type1,"/^[a-zA-Z]{1,}$/i","type1",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 1 в кратком описании","","check",$show_in_short1,$show_in_short1,"/^[0-9]{1}$/i","show_in_short1",1);
$frm->addField("Выводить поле 1 в полном описании","","check",$show_in_full1,$show_in_full1,"/^[0-9]{1}$/i","show_in_full1",1);

$frm->addField("Название поля 2","Ошибка ввода названия поля 2","text",$caption2,$caption2,"/^[^`]{1,}$/i","caption2",0,'',array('size'=>'30'));
$frm->addField("Тип поля 2","Ошибка выбора типа поля 2","list",$this->thismodule["types"],$type2,"/^[a-zA-Z]{1,}$/i","type2",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 2 в кратком описании","","check",$show_in_short2,$show_in_short2,"/^[0-9]{1}$/i","show_in_short2",1);
$frm->addField("Выводить поле 2 в полном описании","","check",$show_in_full2,$show_in_full2,"/^[0-9]{1}$/i","show_in_full2",1);

$frm->addField("Название поля 3","Ошибка ввода названия поля 3","text",$caption3,$caption3,"/^[^`]{1,}$/i","caption3",0,'',array('size'=>'30'));
$frm->addField("Тип поля 3","Ошибка выбора типа поля 3","list",$this->thismodule["types"],$type3,"/^[a-zA-Z]{1,}$/i","type3",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 3 в кратком описании","","check",$show_in_short3,$show_in_short3,"/^[0-9]{1}$/i","show_in_short3",1);
$frm->addField("Выводить поле 3 в полном описании","","check",$show_in_full3,$show_in_full3,"/^[0-9]{1}$/i","show_in_full3",1);

$frm->addField("Название поля 4","Ошибка ввода названия поля 4","text",$caption4,$caption4,"/^[^`]{1,}$/i","caption4",0,'',array('size'=>'30'));
$frm->addField("Тип поля 4","Ошибка выбора типа поля 4","list",$this->thismodule["types"],$type4,"/^[a-zA-Z]{1,}$/i","type4",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 4 в кратком описании","","check",$show_in_short4,$show_in_short4,"/^[0-9]{1}$/i","show_in_short4",1);
$frm->addField("Выводить поле 4 в полном описании","","check",$show_in_full4,$show_in_full4,"/^[0-9]{1}$/i","show_in_full4",1);

$frm->addField("Название поля 5","Ошибка ввода названия поля 5","text",$caption5,$caption5,"/^[^`]{1,}$/i","caption5",0,'',array('size'=>'30'));
$frm->addField("Тип поля 5","Ошибка выбора типа поля 5","list",$this->thismodule["types"],$type5,"/^[a-zA-Z]{1,}$/i","type5",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 5 в кратком описании","","check",$show_in_short5,$show_in_short5,"/^[0-9]{1}$/i","show_in_short5",1);
$frm->addField("Выводить поле 5 в полном описании","","check",$show_in_full5,$show_in_full5,"/^[0-9]{1}$/i","show_in_full5",1);

$frm->addField("Название поля 6","Ошибка ввода названия поля 6","text",$caption6,$caption6,"/^[^`]{1,}$/i","caption6",0,'',array('size'=>'30'));
$frm->addField("Тип поля 6","Ошибка выбора типа поля 6","list",$this->thismodule["types"],$type6,"/^[a-zA-Z]{1,}$/i","type6",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 6 в кратком описании","","check",$show_in_short6,$show_in_short6,"/^[0-9]{1}$/i","show_in_short6",1);
$frm->addField("Выводить поле 6 в полном описании","","check",$show_in_full6,$show_in_full6,"/^[0-9]{1}$/i","show_in_full6",1);

$frm->addField("Название поля 7","Ошибка ввода названия поля 7","text",$caption7,$caption7,"/^[^`]{1,}$/i","caption7",0,'',array('size'=>'30'));
$frm->addField("Тип поля 7","Ошибка выбора типа поля 7","list",$this->thismodule["types"],$type7,"/^[a-zA-Z]{1,}$/i","type7",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 7 в кратком описании","","check",$show_in_short7,$show_in_short7,"/^[0-9]{1}$/i","show_in_short7",1);
$frm->addField("Выводить поле 7 в полном описании","","check",$show_in_full7,$show_in_full7,"/^[0-9]{1}$/i","show_in_full7",1);

$frm->addField("Название поля 8","Ошибка ввода названия поля 8","text",$caption8,$caption8,"/^[^`]{1,}$/i","caption8",0,'',array('size'=>'30'));
$frm->addField("Тип поля 8","Ошибка выбора типа поля 8","list",$this->thismodule["types"],$type8,"/^[a-zA-Z]{1,}$/i","type8",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 8 в кратком описании","","check",$show_in_short8,$show_in_short8,"/^[0-9]{1}$/i","show_in_short8",1);
$frm->addField("Выводить поле 8 в полном описании","","check",$show_in_full8,$show_in_full8,"/^[0-9]{1}$/i","show_in_full8",1);

$frm->addField("Название поля 9","Ошибка ввода названия поля 9","text",$caption9,$caption9,"/^[^`]{1,}$/i","caption9",0,'',array('size'=>'30'));
$frm->addField("Тип поля 9","Ошибка выбора типа поля 9","list",$this->thismodule["types"],$type9,"/^[a-zA-Z]{1,}$/i","type9",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 9 в кратком описании","","check",$show_in_short9,$show_in_short9,"/^[0-9]{1}$/i","show_in_short9",1);
$frm->addField("Выводить поле 9 в полном описании","","check",$show_in_full9,$show_in_full9,"/^[0-9]{1}$/i","show_in_full9",1);

$frm->addField("Название поля 10","Ошибка ввода названия поля 10","text",$caption10,$caption10,"/^[^`]{1,}$/i","caption10",0,'',array('size'=>'30'));
$frm->addField("Тип поля 10","Ошибка выбора типа поля 10","list",$this->thismodule["types"],$type10,"/^[a-zA-Z]{1,}$/i","type10",1,'',array('size'=>'30'));
$frm->addField("Выводить поле 10 в кратком описании","","check",$show_in_short10,$show_in_short10,"/^[0-9]{1}$/i","show_in_short10",1);
$frm->addField("Выводить поле 10 в полном описании","","check",$show_in_full10,$show_in_full10,"/^[0-9]{1}$/i","show_in_full10",1);

$frm->addField("Настройки полей","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","fields",0,'',array('end'=>true));

$frm->addField("Настройки выпадающих списков","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","lists",0,'',array('hidden'=>true));

$frm->addField("Название списка 1","Ошибка ввода названия списка 1","text",$list_caption1,$list_caption1,"/^[^`]{1,}$/i","list_caption1",0,'',array('size'=>'30'));
$frm->addField('Значения списка 1','Неверно заполнены значения списка 1',"textarea",$list_value1,$list_value1,"/^[^#]{1,}$/i","list_value1",0,'',array('rows'=>'40','cols'=>'10','ticket'=>'По одному на строчку'));
$frm->addField("Тип списка 1","Ошибка выбора типа списка 1","list",$this->thismodule["listtypes"],$list_type1,"/^[a-zA-Z]{1,}$/i","list_type1",1,'',array('size'=>'30'));
$frm->addField("Выводить значение списка 1 в кратком описании","","check",$list_show_in_short1,$list_show_in_short1,"/^[0-9]{1}$/i","list_show_in_short1",1);
$frm->addField("Выводить значение списка 1 в полном описании","","check",$list_show_in_full1,$list_show_in_full1,"/^[0-9]{1}$/i","list_show_in_full1",1);

$frm->addField("Название списка 2","Ошибка ввода названия списка 2","text",$list_caption2,$list_caption2,"/^[^`]{1,}$/i","list_caption2",0,'',array('size'=>'30'));
$frm->addField('Значения списка 2','Неверно заполнены значения списка 2',"textarea",$list_value2,$list_value2,"/^[^#]{1,}$/i","list_value2",0,'',array('rows'=>'40','cols'=>'10','ticket'=>'По одному на строчку'));
$frm->addField("Тип списка 2","Ошибка выбора типа списка 2","list",$this->thismodule["listtypes"],$list_type2,"/^[a-zA-Z]{1,}$/i","list_type2",1,'',array('size'=>'30'));
$frm->addField("Выводить значение списка 2 в кратком описании","","check",$list_show_in_short2,$list_show_in_short2,"/^[0-9]{1}$/i","list_show_in_short2",1);
$frm->addField("Выводить значение списка 2 в полном описании","","check",$list_show_in_full2,$list_show_in_full2,"/^[0-9]{1}$/i","list_show_in_full2",1);

$frm->addField("Название списка 3","Ошибка ввода названия списка 3","text",$list_caption3,$list_caption3,"/^[^`]{1,}$/i","list_caption3",0,'',array('size'=>'30'));
$frm->addField('Значения списка 3','Неверно заполнены значения списка 3',"textarea",$list_value3,$list_value3,"/^[^#]{1,}$/i","list_value3",0,'',array('rows'=>'40','cols'=>'10','ticket'=>'По одному на строчку'));
$frm->addField("Тип списка 3","Ошибка выбора типа списка 3","list",$this->thismodule["listtypes"],$list_type3,"/^[a-zA-Z]{1,}$/i","list_type3",1,'',array('size'=>'30'));
$frm->addField("Выводить значение списка 3 в кратком описании","","check",$list_show_in_short3,$list_show_in_short3,"/^[0-9]{1}$/i","list_show_in_short3",1);
$frm->addField("Выводить значение списка 3 в полном описании","","check",$list_show_in_full3,$list_show_in_full3,"/^[0-9]{1}$/i","list_show_in_full3",1);

$frm->addField("Настройки выпадающих списков","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","lists",0,'',array('end'=>true));

$frm->addField("Настройки текстов","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","texts",0,'',array('hidden'=>true));

$frm->addField("Назад к просмотру <...>","Неверно указан текст просмотр <...>","text",$view_all_text,$view_all_text,"/^[^`#]{2,255}$/i","view_all_text",0,"артистов",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Добавить <...>","Неверно указан текст добавить <...>","text",$add_text,$add_text,"/^[^`#]{2,255}$/i","add_text",0,"Артиста",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Просмотреть описание <...>","Неверно указан текст просмотреть описание <...>","text",$fulllink_text,$fulllink_text,"/^[^`#]{2,255}$/i","fulllink_text",0,"артиста",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Настройки текстов","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","texts",0,'',array('end'=>true));

$engine->generateLangControls("TYPES",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_type"])) {
$id_type=$_REQUEST["id_type"];
$frm->addField("","","hidden",$id_type,$id_type,"/^[^`]{0,}$/i","id_type",1);
}
if ($mode=="edit") {
	$engine->addPath('Редактирование типа '.$type["caption"],'',false);
	if ($ident!=$type["ident"])
		if ($this->existType($ident))
			$frm->addError('Тип с указанным идентификатором уже существует');	
} else {
	$engine->addPath('Создание нового типа','',false);
	if ($this->existType($ident))
		$frm->addError('Тип с указанным идентификатором уже существует');
}
if ($max_images>25)
	$frm->addError("Максимальное количество изображений у объекта - 25");
if ($max_videos>25)
	$frm->addError("Максимальное количество  видео у объекта - 25");
	
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_type)) {
				 	$query="update `%TYPES%` set `caption`='".sql_quote($caption)."',`ident`='".sql_quote($ident)."',`description`='".sql_quote($content)."',`use_objects`=$use_objects,`use_files`=$use_files,`add_cat`=$add_cat,`user_add`=$user_add,`download_only_for_users`=$download_only_for_users,`short_content`=$short_content,`full_content`=$full_content,`voters`=$voters,`small_preview`=$small_preview,`medium_preview`=$medium_preview,`show_alphabet`=$alphabet,`caption1`='".sql_quote($caption1)."',`type1`='".sql_quote($type1)."',`show_in_full1`=$show_in_full1,`show_in_short1`=$show_in_short1,`caption2`='".sql_quote($caption2)."',`type2`='".sql_quote($type2)."',`show_in_full2`=$show_in_full2,`show_in_short2`=$show_in_short2,`caption3`='".sql_quote($caption3)."',`type3`='".sql_quote($type3)."',`show_in_full3`=$show_in_full3,`show_in_short3`=$show_in_short3,`caption4`='".sql_quote($caption4)."',`type4`='".sql_quote($type4)."',`show_in_full4`=$show_in_full4,`show_in_short4`=$show_in_short4,`caption5`='".sql_quote($caption5)."',`type5`='".sql_quote($type5)."',`show_in_full5`=$show_in_full5,`show_in_short5`=$show_in_short5,`caption6`='".sql_quote($caption6)."',`type6`='".sql_quote($type6)."',`show_in_full6`=$show_in_full6,`show_in_short6`=$show_in_short6,`caption7`='".sql_quote($caption7)."',`type7`='".sql_quote($type7)."',`show_in_full7`=$show_in_full7,`show_in_short7`=$show_in_short7,`caption8`='".sql_quote($caption8)."',`type8`='".sql_quote($type8)."',`show_in_full8`=$show_in_full8,`show_in_short8`=$show_in_short8,`caption9`='".sql_quote($caption9)."',`type9`='".sql_quote($type9)."',`show_in_full9`=$show_in_full9,`show_in_short9`=$show_in_short9,`caption10`='".sql_quote($caption10)."',`type10`='".sql_quote($type10)."',`show_in_full10`=$show_in_full10,`show_in_short10`=$show_in_short10,`list_caption1`='".sql_quote($list_caption1)."',`list_value1`='".sql_quote($list_value1)."',`list_type1`='".sql_quote($list_type1)."',`list_show_in_full1`=$list_show_in_full1,`list_show_in_short1`=$list_show_in_short1,`list_caption2`='".sql_quote($list_caption2)."',`list_value2`='".sql_quote($list_value2)."',`list_type2`='".sql_quote($list_type2)."',`list_show_in_full2`=$list_show_in_full2,`list_show_in_short2`=$list_show_in_short2,`list_caption3`='".sql_quote($list_caption3)."',`list_value3`='".sql_quote($list_value3)."',`list_type3`='".sql_quote($list_type3)."',`list_show_in_full3`=$list_show_in_full3,`list_show_in_short3`=$list_show_in_short3,`view_all_text`='".sql_quote($view_all_text)."',`add_text`='".sql_quote($add_text)."',`fulllink_text`='".sql_quote($fulllink_text)."',`max_images`=$max_images,`use_gallery`=$use_gallery,`max_videos`=$max_videos,`use_videogallery`=$use_videogallery,`use_code`=$use_code,`congratulation_text`='".sql_quote($congratulation_text)."',`edit_text`='".sql_quote($edit_text)."',`onpage`=$onpage, `do_comments`=$do_comments ".$engine->generateUpdateSQL("TYPES",$lang_values)." where id_type=".$type["id_type"];
				 	if ($db->query($query)) {
						//отредактировали
					if ($user_add)
						$engine->addModuleToCategory($this->thismodule["name"],$add_cat);
				    $engine->setCongratulation("","Тип объектов отредактирован успешно!",3000);
					$m_action="view_types";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $query="insert into `%TYPES%` values(null,'".sql_quote($caption)."','".sql_quote($ident)."','".sql_quote($content)."',NULL,$use_code,$do_comments,$short_content,$full_content,$voters,$small_preview,$medium_preview,$max_images,$use_gallery,$max_videos,$use_videogallery,$use_objects,$use_files,$add_cat,$user_add,$download_only_for_users,$onpage,$alphabet,'".sql_quote($caption1)."','".sql_quote($type1)."',$show_in_full1,$show_in_short1,'".sql_quote($caption2)."','".sql_quote($type2)."',$show_in_full2,$show_in_short2,'".sql_quote($caption3)."','".sql_quote($type3)."',$show_in_full3,$show_in_short3,'".sql_quote($caption4)."','".sql_quote($type4)."',$show_in_full4,$show_in_short4,'".sql_quote($caption5)."','".sql_quote($type5)."',$show_in_full5,$show_in_short5,'".sql_quote($caption6)."','".sql_quote($type6)."',$show_in_full6,$show_in_short6,'".sql_quote($caption7)."','".sql_quote($type7)."',$show_in_full7,$show_in_short7,'".sql_quote($caption8)."','".sql_quote($type8)."',$show_in_full8,$show_in_short8,'".sql_quote($caption9)."','".sql_quote($type9)."',$show_in_full9,$show_in_short9,'".sql_quote($caption10)."','".sql_quote($type10)."',$show_in_full10,$show_in_short10,'".sql_quote($list_caption1)."','".sql_quote($list_value1)."','".sql_quote($list_type1)."',$list_show_in_full1,$list_show_in_short1,'".sql_quote($list_caption2)."','".sql_quote($list_value2)."','".sql_quote($list_type2)."',$list_show_in_full2,$list_show_in_short2,'".sql_quote($list_caption3)."','".sql_quote($list_value3)."','".sql_quote($list_type3)."',$list_show_in_full3,$list_show_in_short3,'".sql_quote($view_all_text)."','".sql_quote($add_text)."','".sql_quote($fulllink_text)."','".sql_quote($congratulation_text)."','".sql_quote($edit_text)."' ".$engine->generateInsertSQL("TYPES",$lang_values).")";
				 if ($db->query($query)) {
				   //добавили успешно!
					if ($user_add)
					   $engine->addModuleToCategory($this->thismodule["name"],$add_cat);
				   $engine->setCongratulation("","Новый тип объектов добавлен успешно!",3000);
					$m_action="view_types";
				 }
			}
		}
		$engine->assignPath();
	break;
	default:
		$m_action="view_types";
}
	if ($m_action=='view_types') {
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'',false);
		$engine->assignPath();
		$types=$this->getAllTypes();
		$smarty->assign("types",$types);
	}
	if ($m_action=='view') {
		if (isset($_REQUEST["id_type"])) {
			$id_type=$_REQUEST["id_type"];
			if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
				$type=$this->getTypeByID($id_type);
				$smarty->assign("type",$type);
				$id_category=@$_REQUEST["id_category"];
				if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
					$category=$engine->getCategoryById($id_category);
					$smarty->assign("cat",$category);
					$smarty->assign("id_category",$id_category);
					$ppath=$engine->getPath($id_category);
					$smarty->assign("ppath",$ppath);	
					//проверяем поисковый запрос
					if (isset($_REQUEST["str"])) {
						$str_real=trim($_REQUEST["str"]);
						if (trim($str_real)!='') {
						$find=strpos($str_real,'*');
						if ($find===false) {
							$str='%'.$str_real.'%';
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
					$count=$this->getObjectsCount($id_category,$id_type,$str,false,false);
					$pages=ceil($count/$this->thismodule["onpage_admin"]);
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
						$objects=$this->getObjects($id_category,$id_type,$type,$str,false,false,$pg,$this->thismodule["onpage_admin"]);
						$smarty->assign("objects",$objects);
						if (isset($pages_arr)) {
							$smarty->assign("pages",$pages_arr);
							$smarty->assign("pagenumber",$pg);
						}
				} else {
					$count=$this->getCountNewObjects($id_type);
					$pages=ceil($count/$this->thismodule["onpage_admin"]);
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
						$objects=$this->getNewObjects($type,$pg,$this->thismodule["onpage_admin"]);
						$smarty->assign("objects",$objects);
						if (isset($pages_arr)) {
							$smarty->assign("pages",$pages_arr);
							$smarty->assign("pagenumber",$pg);
						}
				}
				//получаем все рубрики
				$rubrics=$this->getCountAllObjects($id_type);
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);	$engine->addPath($type["caption"],'',false);
				$engine->assignPath();
			}
		}
	}	
$smarty->assign("m_action",$m_action);
?>