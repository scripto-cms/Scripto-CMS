<?
/*
модуль блоков
*/
global $page;

if (defined("SCRIPTO_GALLERY")) {

	$page["title"]=$lang["modules"]["blocks"];
	$smarty->assign("page",$page);
	$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/blocks/");
	$this->setAdminTitle($lang["modules"]["blocks"]);
	
if ($modAction=="") $modAction="view";

	switch ($modAction) {
		case "module_block":
			$mod_name=@$_REQUEST["mod_name"];
			$id_block=@$_REQUEST["id_block"];
			if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$block=$this->getBlockByID($id_block);
			$types=$this->getBlockTypes();
			$block["type"]=$types[$block["id_type"]];
			$smarty->assign("block",$block);
			if (preg_match("/^[a-zA-Z0-9_]{1,}$/i",$mod_name)) {
				$mod_=$this->getModule($mod_name);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==true) {
						if (method_exists($m,"doBlockAdmin")) {
							$block_content=$m->doBlockAdmin($block,$page);
							$smarty->assign("block_content",$block_content);
						}
					}
					$smarty->assign("user_module",$mod_);
				}
			}
			}
		break;
		case "quickedit":
			if (isset($_REQUEST["id_block"])) {
				$id_block=@$_REQUEST["id_block"];
				if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
					$block=$this->getBlockByID($id_block);
				    if (isset($_REQUEST["fck1"])) {
		    			$content=@$_REQUEST["fck1"];
		    			$first=false;
				    } else {
				    	$content=$block["content"];
				    	$first=true;
				    }	
					$fck_editor1=$this->createFCKEditor("fck1",$content);
					$smarty->assign("editor",$fck_editor1);
					$smarty->assign("block",$block);
					$close=@$_REQUEST["close"];
					$smarty->assign("close",$close);
					if (isset($_REQUEST["save"])) {
						$content=$this->stripContent($content);
						if ($db->query("update %blocks% set `content`='".sql_quote($content)."' where id_block=$id_block")) {
							$smarty->assign("save",true);
						}
					}
				}
			}
		break;
		case "save":
			$id_block=@$_REQUEST["idblock"];
			$visible=@$_REQUEST["visible"];
			$caption=@$_REQUEST["caption"];
			$blocktype=@$_REQUEST["blocktype"];
			$ident=@$_REQUEST["ident"];
			$numbers=@$_REQUEST["numbers"];
			$sort=@$_REQUEST["sort"];
			$n=0;
			$idents=array();
				$res=$db->query("select `ident` from `%blocks%`");
					while ($row=$db->fetch($res)) $idents[]=$row["ident"];
				foreach ($id_block as $key=>$cat) {
					if (preg_match("/^[0-9]{1,}$/i",$cat)) {
						if (isset($visible[$key])) {
							$vis=1;
						} else {
							$vis=0;
						}
						$ident_str='';
						if (preg_match("/^[-а-яА-Яa-zA-Z0-9_\/]{2,255}$/i",$ident[$key])) {
							if (!in_array($ident[$key],$idents)) {
								$ident_str=", `ident`='".$ident[$key]."'";
							}
						}
						$number_str='';
						if (preg_match("/^[0-9]{1,3}$/i",$numbers[$key])) {
							$number_str=", `number_objects`='".$numbers[$key]."'";
						}
						$blocktype_str='';
						if (preg_match("/^[0-9]{1,3}$/i",$blocktype[$key])) {
							$blocktype_str=", `id_type`='".$blocktype[$key]."'";
						}
						$capt_str='';
						if (preg_match("/^[^`#]{2,255}$/i",$caption[$key])) {
							$capt_str=", `caption`='".sql_quote($caption[$key])."'";
						}
						$sort_str='';
						if (preg_match("/^[0-9]{1,}$/i",$sort[$key])) {
							$sort_str=", `sort`='".sql_quote($sort[$key])."'";
						}
						$db->query("update %blocks% set visible=$vis $ident_str $capt_str $number_str $blocktype_str $sort_str where id_block=$cat");
					}
					$n++;
				}
			$modAction="view";
			$this->setCongratulation("","Информация о блоках обновлена!",5000);
		break;
		case "rss_block":
			$mode=@$_REQUEST["mode"];
			if (trim($mode)=="") $mode="view";
			
			$id_block=@$_REQUEST["id_block"];
			if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$block=$this->getBlockByID($id_block);
			$smarty->assign("block",$block);
			switch ($mode) {
				case "add":
					$rss_caption=@$_REQUEST["rss_caption"];
					$rss_link=@$_REQUEST["rss_link"];
					if (preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$rss_link)) {
					$smarty->assign("add_rss",true);
					$db->query("insert into %block_rss% values (null,$id_block,'".sql_quote($rss_caption)."','".sql_quote($rss_link)."',10)");
					} else {
						if ((trim($rss_link)!='') || (trim($rss_caption)!='')) {
							$smarty->assign("error_rss",true);
						}
					}
					$mode="view";
				break;
				case "save":
					$id_rss=@$_REQUEST["idrss"];
					$rss_caption=@$_REQUEST["rss_caption"];
					$rss_link=@$_REQUEST["rss_link"];
					$rss_number=@$_REQUEST["rss_number"];
					$delete=@$_REQUEST["delete"];
					$n=0;
					$del=0;
						foreach ($id_rss as $key=>$cat) {
							if (preg_match("/^[0-9]{1,}$/i",$cat)) {
								if (isset($rss_caption[$key])) {
									$capt=$rss_caption[$key];
								} else {
									$capt="";
								}
								if (preg_match("/^[0-9]{1,}$/i",$rss_number[$key])) {
									$numb=" , `rss_number`=".$rss_number[$key];
								} else {
									$numb="";
								}
								if (isset($rss_link[$key])) {
									$link=", `rss_link`='".sql_quote($rss_link[$key])."'";
									if (!preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$rss_link[$key])) $link="";
								} else {
									$link="";
								}
								if (isset($delete[$key])) {
								$db->query("delete from %block_rss% where id_rss=$cat");
								$del++;
								} else {
								$n++;
								$db->query("update %block_rss% set `rss_caption`='".sql_quote($capt)."' $link $numb where id_rss=$cat");
								}
							}
						}
						$smarty->assign("update_rss",true);
						$smarty->assign("n",$n);
						$smarty->assign("del",$del);
					$mode="view";
				break;
			}
			if ($mode=="view" || $mode=="save") {
					$rss=$this->getAllRSSFromBlock($id_block);
					$smarty->assign("rss",$rss);
			}
			} else {
				//показываем ошибку
			}
		break;
		case "random_photo":
			$id_block=@$_REQUEST["id_block"];
			if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
				if (isset($_REQUEST["m_save"])) {
					$db->query("delete from `%block_categories%` where id_block=$id_block");
					$id_cat=@$_REQUEST["id_cat"];
					if (is_array($id_cat)) {
						foreach ($id_cat as $key=>$ct) {
							$db->query("insert into `%block_categories%` values($id_block,$key)");
						}
					}
					$smarty->assign("save",true);
				}
				$block=$this->getBlockByID($id_block);
				$smarty->assign("block",$block);
				$blocks_categories=$this->getCategoriesByBlock($id_block);
				$categories=$this->getRubricsTree(0,0,false,'',false);
				if (is_array($blocks_categories)) {
					foreach ($categories as $key=>$cat) {
						if (in_array($cat["id_category"],$blocks_categories)) {
							$categories[$key]["category_exist"]=true;
						} else {
							$categories[$key]["category_exist"]=false;
						}
					}
				}
				$smarty->assign("categories",$categories);
			}
		break;
		case "text_block":
			$mode=@$_REQUEST["mode"];
			if (trim($mode)=="") $mode="view";
			
			$id_block=@$_REQUEST["id_block"];
			if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$block=$this->getBlockByID($id_block);
			$smarty->assign("block",$block);
			switch ($mode) {
				case "add":
					$caption=@$_REQUEST["caption"];
					$content=@$_REQUEST["content"];
					$db->query("insert into %block_text% values (null,$id_block,'".sql_quote($caption)."','".sql_quote($content)."')");
					$smarty->assign("add_block",true);
					$mode="view";
				break;
				case "save":
					$id_text=@$_REQUEST["idtext"];
					$caption=@$_REQUEST["text"];
					$content=@$_REQUEST["content"];
					$delete=@$_REQUEST["delete"];
					$n=0;
					$del=0;
						foreach ($id_text as $key=>$cat) {
							if (preg_match("/^[0-9]{1,}$/i",$cat)) {
								if (isset($caption[$key])) {
									$capt=$caption[$key];
								} else {
									$capt="";
								}
								if (isset($content[$key])) {
									$cont=$content[$key];
								} else {
									$cont="";
								}
								if (isset($delete[$key])) {
								$del++;
								$db->query("delete from %block_text% where id_text=$cat");
								} else {
								$n++;
								$db->query("update %block_text% set `caption`='".sql_quote($capt)."' , `content`='".sql_quote($cont)."' where id_text=$cat");
								}
							}
						}
					$smarty->assign("update_block",true);
					$smarty->assign("n",$n);
					$smarty->assign("del",$del);
					$mode="view";
				break;
			}
			if ($mode=="view" || $mode=="save") {
					$texts=$this->getAllTextFromBlock($id_block);
					$smarty->assign("texts",$texts);
			}
			} else {
				//показываем ошибку
			}
		break;
		case "add":
			$values=array();
			$this->getRubricsTreeEx($values,0,0,true,"",false);
			$templates=$this->getTemplatesEx("block");
			$types=$this->getBlockTypesEx();
			$mode=@$_REQUEST["mode"];
			$this->addPath('Менеджер блоков','/admin?module=blocks',true);
			if ($mode=="edit") {
			$this->addPath('Редактирование блока','',false);
			} else {
			$this->addPath('Добавление нового блока','',false);	
			}
			$this->assignPath();
			if (isset($_REQUEST["id_block"])) {
				$id_block=@$_REQUEST["id_block"];
				$block=$this->getBlockByID($id_block);
			}
			
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$id_cat=@$_REQUEST["id_cat"];
				if (!isset($_REQUEST["visible"])) {
					$visible=0;
				} else {
					$visible=1;
				}
				$ident=strip_tags(@$_REQUEST["ident"]);
				$content=$this->stripContent(@$_REQUEST["fck1"]);
				$block_type=@$_REQUEST["block_type"];
				$show_mode=@$_REQUEST["show_mode"];
				$id_tpl=@$_REQUEST["id_tpl"];
				$number=@$_REQUEST["number"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($block) {
						$id_cat=$block["id_category"];
						$caption=$block["caption"];
						$ident=$block["ident"];
						$content=$block["content"];
						$visible=$block["visible"];
						$block_type=$block["id_type"];
						$show_mode=$block["show_mode"];
						$number=$block["number_objects"];
						$id_tpl=$block["id_tpl"];
						$lang_values=$this->generateLangArray("blocks",$block);
					}
				} else {
					$caption="";
					$id_cat=0;
					$ident="";
					$visible=1;
					$content="";
					$id_tpl=@$templates[0]["id"];
					$block_type=@$types[0]["id"];
					$show_mode=@$config["block_show_mode"][0]["id"];
					$number=2;
					$lang_values=$this->generateLangArray("blocks",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
$frm->addField("Основные настройки блока","","caption","","","/^[a-zA-Z0-9]{2,10}$/i","s01",0);
			
$frm->addField($lang["forms"]["block"]["title"]["caption"],$lang["forms"]["block"]["title"]["error"],"text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",0,$lang["forms"]["block"]["title"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["block"]["title"]["rules"]));

$frm->addField($lang["forms"]["block"]["ident"]["caption"],$lang["forms"]["block"]["ident"]["error"],"text",$ident,$ident,"/^[a-zA-Z0-9]{2,255}$/i","ident",1,$lang["forms"]["block"]["ident"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["block"]["ident"]["rules"]));

$frm->addField($lang["forms"]["block"]["template"]["caption"],$lang["forms"]["block"]["template"]["error"],"list",$templates,$id_tpl,"/^[0-9]{1,}$/i","id_tpl",1,$lang["forms"]["block"]["template"]["sample"],array('size'=>'30'));

$frm->addField($lang["forms"]["block"]["type"]["caption"],$lang["forms"]["block"]["type"]["error"],"list",$types,$block_type,"/^[0-9]{1,}$/i","block_type",1,$lang["forms"]["block"]["type"]["sample"],array('size'=>'30'));

$frm->addField("Опции вывода блока","","caption","","","/^[a-zA-Z0-9]{2,10}$/i","s01",0);

$frm->addField($lang["forms"]["block"]["show_mode"]["caption"],$lang["forms"]["block"]["show_mode"]["error"],"list",@$config["block_show_mode"],$show_mode,"/^[0-9]{1,}$/i","show_mode",1,$lang["forms"]["block"]["show_mode"]["sample"],array('size'=>'30'));

$frm->addField($lang["forms"]["block"]["razdel"]["caption"],$lang["forms"]["block"]["razdel"]["error"],"list",$values,$id_cat,"/^[0-9]{1,}$/i","id_cat",1,$lang["forms"]["block"]["razdel"]["sample"],array('size'=>'30','ticket'=>$lang["forms"]["block"]["razdel"]["rules"]));

$frm->addField("Прочее","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","other",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["block"]["visible"]["caption"],$lang["forms"]["block"]["visible"]["error"],"check",$visible,$visible,"/^[0-9]{1}$/i","visible",1);

$frm->addField($lang["forms"]["block"]["number_objects"]["caption"],$lang["forms"]["block"]["number_objects"]["error"],"text",$number,$number,"/^[0-9]{1,4}$/i","number",1,$lang["forms"]["block"]["number_objects"]["sample"],array('size'=>'4','ticket'=>$lang["forms"]["block"]["number_objects"]["rules"]));

$fck_editor1=$this->createFCKEditor("fck1",$content);
$frm->addField($lang["forms"]["block"]["content"]["caption"],$lang["forms"]["block"]["content"]["error"],"solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$frm->addField("","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","other",0,'',array('end'=>true));

$this->generateLangControls("blocks",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
if (isset($_REQUEST["id_block"])) {
$id_block=$_REQUEST["id_block"];
$frm->addField("","","hidden",$id_block,$id_block,"/^[^`]{0,}$/i","id_block",1);
}

if ($mode=="edit") {
	$s_name=$lang["forms"]["block"]["edit_submit_name"];
	if ($ident!=$block["ident"])
		if ($this->rubricExist($ident))
			$frm->addError($lang["error"]["ident_exist2"]);
} else {
$s_name=$lang["forms"]["block"]["submit_name"];
if ($this->blockExist($ident))
	$frm->addError($lang["error"]["ident_exist2"]);
}

			if (
$this->processFormData($frm,$s_name,$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_block)) {
				 	if ($db->query("update %blocks% set `caption`='".sql_quote($caption)."' , `id_category`=$id_cat , `visible`=$visible , `ident`='".sql_quote($ident)."' , content='".sql_quote($content)."' , `id_type`=$block_type , `show_mode`=$show_mode , id_tpl=$id_tpl , `number_objects` = $number ".$this->generateUpdateSQL("blocks",$lang_values)."  where id_block=$id_block")) {
						//отредактировали
				//	   $modAction="view";
				   $this->setCongratulation('',$lang["congratulation"]["block_edit"],3000);
				   $modAction="view";
				   $this->clearPath();
				   $this->clearCacheBlock($ident);
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
 $add_id=$this->addBlock($id_cat,$caption,$content,$block_type,$ident,$visible,$show_mode,$id_tpl,$number,$this->generateInsertSQL("blocks",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $this->setCongratulation('',$lang["congratulation"]["block_add"],3000);
				   $modAction="view";
				   $this->clearPath();
				 }
				}
			}
			
		break;
		case "delete":
			//удаление
			$id_block=@$_REQUEST["id_block"];
			if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
				$this->deleteBlock($id_block);
				$modAction="view";
				$this->setCongratulation('',$lang["congratulation"]["block_delete"],3000);
			} else {
				//выдаем ошибку
			}
		break;
	}
			if ($modAction=="view") {
				$types=$this->getBlockTypesEx();
				$smarty->assign("block_types",$types);
				$blocks=$this->getAllBlocks(false,true);
				$smarty->assign("blocks",$blocks);
			}
}
?>