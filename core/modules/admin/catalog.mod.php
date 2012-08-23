<?
/*
модуль каталог
*/
global $config;
if (defined("SCRIPTO_GALLERY")) {
	$page["title"]=$lang["modules"]["catalog"];
	$smarty->assign("page",$page);
	$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/struct/");
	$this->setAdminTitle($lang["modules"]["catalog"]);
	if ($modAction=="") $modAction="view";
	switch ($modAction) {
		case "save":
			$id_cat=@$_REQUEST["idcat"];
			$sort=@$_REQUEST["sort"];
			$visible=@$_REQUEST["visible"];
			$ident=@$_REQUEST["ident"];
			$caption=@$_REQUEST["caption"];
			$main_page=@$_REQUEST["main_page"];
			$type=@$_REQUEST["type"];
			$menu_type=@$_REQUEST["menu_type"];
			$n=0;
			$idents=$this->getAllIdents();
				foreach ($id_cat as $key=>$cat) {
					if (preg_match("/^[0-9]{1,}$/i",$cat)) {
						if (isset($visible[$key])) {
							$vis=1;
						} else {
							$vis=0;
						}
						if ($main_page==$cat) {
							$mp=",main_page=1";
							$db->query("update %categories% set main_page=0");
						} else {
							$mp="";
						}
						$ident_str='';
$ident[$key]=trim($ident[$key]);
						if (preg_match("/^[-а-яА-Яa-zA-Z0-9_\/]{2,255}$/i",$ident[$key])) {
							if (!in_array($ident[$key],$idents)) {
								$ident_str=", `ident`='".strtolower($ident[$key])."'";
							}
						}
						$capt_str='';
						if (preg_match("/^[^`#]{2,255}$/i",$caption[$key])) {
							$capt_str=", `caption`='".$caption[$key]."'";
						}
						$type_str='';
						if (preg_match("/^[a-zA-Z0-9_-]{2,255}$/i",$type[$key])) {
							$type_str=",`category_type`='".$type[$key]."'";
						}
						$menutype_str='';
						if (preg_match("/^[a-zA-Z0-9]{2,255}$/i",$menu_type[$key])) {
							$menutype_str=",`position`='".$menu_type[$key]."'";
						}
						if (!preg_match("/^[0-9]{1,}$/i",$sort[$key])) $sort[$key]=0;
						$db->query("update %categories% set `sort`=".$sort[$key]." , visible=$vis $mp $ident_str $capt_str $type_str $menutype_str where id_category=$cat");
					}
					$n++;
				}
				$this->setCongratulation('',$lang["interface"]["data_saved"],5000);
		break;
		case "add_blank":
		$modAction="view";
			if (isset($_REQUEST["id_cat"])) {
				$id_cat=@$_REQUEST["id_cat"];
				if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
					$parent=$this->getCategoryByID($id_cat);
					$position=$parent["position"];
					$id_tpl=$parent["id_tpl"];
					$content_type=$parent["category_type"];	
					$date_news=array();
					$date_news[0]=(int)date("d");
					$date_news[1]=(int)date("m");
					$date_news[2]=(int)date("Y");
					$number=@$_REQUEST["number"];
						if (!preg_match("/^[0-9]{1,2}$/i",$number)) {
							$number=1;
						} else {
							if ($number<=0) $number=1;
						}
					$k=0;
					for ($y=0;$y<=$number-1;$y++) {
						$ident=$parent["ident"].'/'.$this->generateIdent();
						$add_id=$this->addCategory($id_cat,'',$content_type,$ident,1,0,'','','','','','',$id_tpl,$position,$date_news,0,0,0,1);
						$k++;
					}
					 if ($k>0) {
					 	$this->setCongratulation('',$lang["interface"]["add_blank"],3000);
					 }
				}
			}
		break;
		case "quickedit":
			if (isset($_REQUEST["id_cat"])) {
				$id_cat=@$_REQUEST["id_cat"];
				if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
					$category=$this->getCategoryByID($id_cat);
				    if (isset($_REQUEST["fck1"])) {
		    			$content=$this->stripContent(@$_REQUEST["fck1"]);
		    			$first=false;
				    } else {
				    	$content=$category["content"];
				    	$first=true;
				    }	
					$fck_editor1=$this->createFCKEditor("fck1",$content);
					$smarty->assign("editor",$fck_editor1);
					$smarty->assign("category",$category);
					$close=@$_REQUEST["close"];
					$smarty->assign("close",$close);
					if (isset($_REQUEST["save"])) {
						$content=$this->stripContent($content);
						 if ($category["file_content"]) {
							if ($this->setContentFile($id_cat,$content)) {
								$this->updateContent($id_cat);
							}
							$content='';
						 }
						if ($db->query("update %categories% set `content`='".sql_quote($content)."' where id_category=$id_cat")) {	
							$smarty->assign("save",true);
						}
					}
				}
			}
		break;
		case "add":
			$values=array();
			$this->getRubricsTreeEx($values,0,0,true,"",true);
			$templates=$this->getTemplatesEx("site");
			$positions=$this->getMenuTypesEx();
			if ($this->checkInstallModule("users")) {
				$is_users=true;
			} else {
				$is_users=false;
			}
			$mode=@$_REQUEST["mode"];
			$this->addPath($lang["interface"]["category_module"],'/admin?module=catalog',true);
			if ($mode=="edit") {
			$this->addPath($lang["interface"]["edit_category_module"],'',false);
			} else {
			$this->addPath($lang["interface"]["add_category_module"],'',false);	
			}
			$this->assignPath();
			if (isset($_REQUEST["id_cat1"])) {
				$id_cat1=@$_REQUEST["id_cat1"];
				$category=$this->getCategoryByID($id_cat1);
			}
			//устанавливаем превью
				if (isset($_REQUEST["setPreview"])) {
					$previewMode=@$_REQUEST["previewMode"];
					$id_image=@$_REQUEST["id_image"];
					if (preg_match("/^[0-9]{1,}$/i",$id_image)) {
						if ($id_image==0) {
						$img["small_photo"]='';
						$img["medium_photo"]='';
						} else {
						$img=$this->getImageByID($id_image);
						}
						switch ($previewMode) {
							case "small":
								if ($mode!="edit") {
$this->setPreview($img["small_photo"],$previewMode,'Малое превью установлено');
$_SESSION["category_small"]=$img["small_photo"];
								} else {
								if ($db->query("update `%categories%` set preview='".$img["small_photo"]."' where id_category=$id_cat1")) {
$this->setPreview($img["small_photo"],$previewMode,'Малое превью установлено');
								}
								}
							break;
							case "medium":
								if ($mode!="edit") {
$this->setPreview($img["medium_photo"],$previewMode,'Среднее превью установлено');
$_SESSION["category_middle"]=$img["medium_photo"];
								} else {
								if ($db->query("update `%categories%` set big_preview='".$img["medium_photo"]."' where id_category=$id_cat1")) {
									$this->setPreview($img["medium_photo"],$previewMode,'Среднее превью установлено');
								}
								}
							break;
						}
					}
				}
	  		if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$title=@$_REQUEST["title"];
				$id_cat=@$_REQUEST["id_cat"];
				if (!isset($_REQUEST["visible"])) {
					$visible=0;
				} else {
					$visible=1;
				}
				if (!isset($_REQUEST["page404"])) {
					$page404=0;
				} else {
					$page404=1;
				}
				if (!isset($_REQUEST["in_navigation"])) {
					$in_navigation=0;
				} else {
					$in_navigation=1;
				}
				if (!isset($_REQUEST["is_registered"])) {
					$is_registered=0;
				} else {
					$is_registered=1;
				}
				if (!isset($_REQUEST["file_content"])) {
					$file_content=0;
				} else {
					$file_content=1;
				}
				if (defined("SCRIPTO_tags")) {
					$tags=@$_REQUEST["tags"];
				}
				$titletag=@$_REQUEST["titletag"];
				$metatag=@$_REQUEST["metatag"];
				$metakeywords=@$_REQUEST["metakeywords"];
				$ident=str_replace(" ","_",noSlash(@$_REQUEST["ident"]));
				$content=@$_REQUEST["content"];
				$rss_link=@$_REQUEST["rss_link"];
				$content_type=@$_REQUEST["content_type"];
				$content=$this->stripContent(@$_REQUEST["fck1"]);
				$subcontent=$this->stripContent(@$_REQUEST["fck2"]);
				$id_tpl=@$_REQUEST["id_tpl"];
				$position=@$_REQUEST["position"];
				if (!isset($_REQUEST["future_post"])) {
					$future_post=0;
				} else {
					$future_post=1;
				}
				$date_news=array();
				$date_news[0]=@$_REQUEST["date_news_day"];
				$date_news[1]=@$_REQUEST["date_news_month"];
				$date_news[2]=@$_REQUEST["date_news_year"];
				$preview_width=@$_REQUEST["preview_width"];
					if (!preg_match("/^[0-9]{1,4}$/i",$preview_width)) $preview_width=0;
				$preview_height=@$_REQUEST["preview_height"];
					if (!preg_match("/^[0-9]{1,4}$/i",$preview_height)) $preview_height=0;
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($category) {
						$id_cat=$category["id_sub_category"];
						if (defined("SCRIPTO_tags")) {
							$tags=$tgs->getTags($category["id_category"],'category','text');
						}
						$titletag=$category["title"];
						$metatag=$category["meta"];
						$metakeywords=$category["keywords"];
						$content=$category["content"];
						$subcontent=$category["subcontent"];
						$title=$category["caption"];
						$rss_link=$category["rss_link"];
						$content_type=$category["category_type"];
						$visible=$category["visible"];
						$page404=$category["page404"];
						$ident=$category["ident"];
						$id_tpl=$category["id_tpl"];
						$position=$category["position"];
						$future_post=$category["future_post"];
						if (trim($category["file_content"]!="")) {
							$file_content=1;
						} else {
							$file_content=0;
						}
						$date_news=array();
						$date_news[0]=$category["date_day"];
						$date_news[1]=$category["date_month"];
						$date_news[2]=$category["date_year"];
						$preview_width=$category["preview_width"];
						$preview_height=$category["preview_height"];
						$in_navigation=$category["in_navigation"];
						$is_registered=$category["is_registered"];
						$lang_values=$this->generateLangArray("categories",$category);
					} else {
						//показываем ошибку
					}
				} else {
					if (defined("SCRIPTO_tags")) {
						$tags='';
					}
					$title="";
					if (isset($_REQUEST["id_cat"])) {
					$id_cat=$_REQUEST["id_cat"];
					$parent=$this->getCategoryByID($id_cat);
					$ident=$parent["ident"].'/'.$this->generateIdent();
					$position=$parent["position"];
					$id_tpl=$parent["id_tpl"];
					$content_type=$parent["category_type"];
					if (trim($parent["file_content"])!="") {
						$file_content=1;
					} else {
						$file_content=0;
					}
					} else {
					$id_cat=0;
					$ident=$this->generateIdent();
					$position="up";
					$id_tpl=@$templates[0]["id"];
					$content_type="text";
					}
					$visible=1;
					$file_content=0;
					$titletag="";
					$metatag="";
					$metakeywords="";
					$content="";
					$subcontent="";
					$rss_link="";
					$future_post="";
					$date_news=array();
					$date_news[0]=(int)date("d");
					$date_news[1]=(int)date("m");
					$date_news[2]=(int)date("Y");
					$preview_width=0;
					$preview_height=0;
					$in_navigation=1;
					$is_registered=0;
					$page404=0;
					$lang_values=$this->generateLangArray("categories",null);
				}
			}
			$module["title"]=$lang["modules"]["add_catalog"];
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField($lang["forms"]["catalog"]["razdel"]["caption"],$lang["forms"]["catalog"]["razdel"]["error"],"list",$values,$id_cat,"/^[0-9]{1,}$/i","id_cat",1,$lang["forms"]["catalog"]["razdel"]["sample"],array('size'=>'30'));

$frm->addField($lang["forms"]["catalog"]["title"]["caption"],$lang["forms"]["catalog"]["title"]["error"],"text",$title,$title,"/^[^`#]{2,255}$/i","title",0,$lang["forms"]["catalog"]["title"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["title"]["rules"]));

if (defined("SCRIPTO_tags")) {
$frm->addField($lang["forms"]["catalog"]["tags"]["caption"],$lang["forms"]["catalog"]["tags"]["error"],"text",$tags,$tags,"/^[^`#]{2,255}$/i","tags",0,$lang["forms"]["catalog"]["tags"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["tags"]["rules"]));
}

$frm->addField($lang["forms"]["catalog"]["ident"]["caption"],$lang["forms"]["catalog"]["ident"]["error"],"text",$ident,$ident,"/^[-а-яА-Яa-zA-Z0-9_\/]{2,255}$/i","ident",1,$lang["forms"]["catalog"]["ident"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["ident"]["rules"]));


$frm->addField('Превью для раздела',"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('hidden'=>true));
if ($mode=="edit") {
$frm->addField('Малое изображение превью','',"preview",$category["preview"],$category["preview"],"/^[0-9]{1,}$/i","min_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true,'id_cat'=>$category["id_category"]));
$frm->addField('Большое изображение превью','',"preview",$category["big_preview"],$category["big_preview"],"/^[0-9]{1,}$/i","big_preview",0,'',array('mode'=>'medium','multiple'=>'no','fancy_show'=>true,'id_cat'=>$category["id_category"]));
} else {
if (isset($_SESSION["category_small"])) {
$category["preview"]=$_SESSION["category_small"];
} else {
$category["preview"]='';
}
if (isset($_SESSION["category_middle"])) {
$category["big_preview"]=$_SESSION["category_middle"];
} else {
$category["big_preview"]='';
}
if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
	$cat_idcat=$id_cat;
} else {
	$cat_idcat=false;
}
$frm->addField('Малое изображение превью','',"preview",$category["preview"],$category["preview"],"/^[0-9]{1,}$/i","min_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true,'id_cat'=>$cat_idcat));
$frm->addField('Большое изображение превью','',"preview",$category["big_preview"],$category["big_preview"],"/^[0-9]{1,}$/i","big_preview",0,'',array('mode'=>'medium','multiple'=>'no','fancy_show'=>true,'id_cat'=>$cat_idcat));
}
$frm->addField('Превью для раздела',"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('end'=>true));


$frm->addField('Контент раздела',"","caption",0,0,"/^[0-9]{1}$/i","con",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["catalog"]["file_content"]["caption"],$lang["forms"]["catalog"]["file_content"]["error"],"check",$file_content,$file_content,"/^[0-9]{1}$/i","file_content",1);

$fck_editor1=$this->createFCKEditor("fck1",$content);
$frm->addField($lang["forms"]["catalog"]["content"]["caption"],$lang["forms"]["catalog"]["content"]["error"],"solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content2",1,"");

$fck_editor2=$this->createFCKEditor("fck2",$subcontent);
$frm->addField($lang["forms"]["catalog"]["subcontent"]["caption"],$lang["forms"]["catalog"]["subcontent"]["error"],"solmetra",$fck_editor2,$fck_editor2,"/^[[:print:][:allnum:]]{1,}$/i","content2",1,"");

$frm->addField('Контент раздела',"","caption",0,0,"/^[0-9]{1}$/i","con",0,'',array('end'=>true));

$frm->addField('SEO оптимизация',"","caption",0,0,"/^[0-9]{1}$/i","seo",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["catalog"]["titletag"]["caption"],$lang["forms"]["catalog"]["titletag"]["error"],"text",$titletag,$titletag,"/^[^`#]{2,255}$/i","titletag",0,$lang["forms"]["catalog"]["titletag"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["titletag"]["rules"]));

$frm->addField($lang["forms"]["catalog"]["metatag"]["caption"],$lang["forms"]["catalog"]["metatag"]["error"],"textarea",$metatag,$metatag,"/^[^#]{1,}$/i","metatag",0,$lang["forms"]["catalog"]["metatag"]["sample"],array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["catalog"]["metatag"]["rules"]));

$frm->addField($lang["forms"]["catalog"]["metakeywords"]["caption"],$lang["forms"]["catalog"]["metakeywords"]["error"],"textarea",$metakeywords,$metakeywords,"/^[^#]{1,}$/i","metakeywords",0,$lang["forms"]["catalog"]["metakeywords"]["sample"],array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["catalog"]["metakeywords"]["rules"]));

$frm->addField('',"","caption",0,0,"/^[0-9]{1}$/i","seo",0,'',array('end'=>true));

$frm->addField('Прочее',"","caption",0,0,"/^[0-9]{1}$/i","other",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["catalog"]["rss_link"]["caption"],$lang["forms"]["catalog"]["rss_link"]["error"],"text",$rss_link,$rss_link,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","rss_link",0,$lang["forms"]["catalog"]["rss_link"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["rss_link"]["rules"]));

$frm->addField($lang["forms"]["catalog"]["future_post"]["caption"],$lang["forms"]["catalog"]["future_post"]["error"],"check",$future_post,$future_post,"/^[0-9]{1}$/i","future_post",0);

$frm->addField($lang["forms"]["catalog"]["future_date"]["caption"],$lang["forms"]["catalog"]["future_date"]["error"],"date",$date_news,$date_news,"/^[0-9]{1,}$/i","date_news",0,"19.01.2008");

$frm->addField($lang["forms"]["catalog"]["preview_width"]["caption"],$lang["forms"]["catalog"]["preview_width"]["error"],"text",$preview_width,$preview_width,"/^[0-9]{1,4}$/i","preview_width",0,$lang["forms"]["catalog"]["preview_width"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["preview_width"]["rules"]));

$frm->addField($lang["forms"]["catalog"]["preview_height"]["caption"],$lang["forms"]["catalog"]["preview_height"]["error"],"text",$preview_height,$preview_height,"/^[0-9]{1,4}$/i","preview_height",0,$lang["forms"]["catalog"]["preview_height"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["preview_height"]["rules"]));

$frm->addField('',"","caption",0,0,"/^[0-9]{1}$/i","other",0,'',array('end'=>true));

$frm->addField('Системные настройки',"","caption",0,0,"/^[0-9]{1}$/i","system",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["catalog"]["template"]["caption"],$lang["forms"]["catalog"]["template"]["error"],"list",$templates,$id_tpl,"/^[0-9]{1,}$/i","id_tpl",1,$lang["forms"]["catalog"]["template"]["sample"],array('size'=>'30'));

$frm->addField($lang["forms"]["catalog"]["position"]["caption"],$lang["forms"]["catalog"]["position"]["error"],"list",$positions,$position,"/^[a-zA-Z]{1,}$/i","position",1,$lang["forms"]["catalog"]["position"]["sample"],array('size'=>'30'));

$content_types=$this->getContentTypes();
if ($mode=="edit") {
$frm->addField($lang["forms"]["catalog"]["content_type"]["caption"],$lang["forms"]["catalog"]["content_type"]["error"],"list",$content_types,$content_type,"/^[a-zA-Z]{1,}$/i","content_type",1,$lang["forms"]["catalog"]["content_type"]["sample"],array('size'=>'30','disabled'=>false));
} else {
$frm->addField($lang["forms"]["catalog"]["content_type"]["caption"],$lang["forms"]["catalog"]["content_type"]["error"],"list",$content_types,$content_type,"/^[a-zA-Z]{1,}$/i","content_type",1,$lang["forms"]["catalog"]["content_type"]["sample"],array('size'=>'30'));
}

$frm->addField($lang["forms"]["catalog"]["visible"]["caption"],$lang["forms"]["catalog"]["visible"]["error"],"check",$visible,$visible,"/^[0-9]{1}$/i","visible",1);

$frm->addField($lang["forms"]["catalog"]["404"]["caption"],$lang["forms"]["catalog"]["404"]["error"],"check",$page404,$page404,"/^[0-9]{1}$/i","page404",1);

$frm->addField($lang["forms"]["catalog"]["in_navigation"]["caption"],$lang["forms"]["catalog"]["in_navigation"]["error"],"check",$in_navigation,$in_navigation,"/^[0-9]{1}$/i","in_navigation",1);

if ($is_users) {
$frm->addField($lang["forms"]["catalog"]["is_registered"]["caption"],$lang["forms"]["catalog"]["is_registered"]["error"],"check",$is_registered,$is_registered,"/^[0-9]{1}$/i","is_registered",1);
} else {
$frm->addField($lang["forms"]["catalog"]["is_registered"]["caption"],$lang["forms"]["catalog"]["is_registered"]["error"],"hidden",$is_registered,$is_registered,"/^[0-9]{1}$/i","is_registered",1);
}

$frm->addField('',"","caption",0,0,"/^[0-9]{1}$/i","system",0,'',array('end'=>true));

$this->generateLangControls("categories",$lang_values,$frm);

if (isset($_REQUEST["mod_name"])) {
	$mod_name=$_REQUEST["mod_name"];
	if (preg_match("/^[a-zA-Z]{1,}$/i",$mod_name)) {
		$frm->addField("","","hidden",$mod_name,$mod_name,"/^[a-zA-Z]{1,}$/i","mod_name",1);
		if ($first) {
			$ref=getenv("HTTP_REFERER");
		} else {
			$ref=@$_REQUEST["ref"];
		}
		$frm->addField("","","hidden",$ref,$ref,"/^[^`]{1,}$/i","ref",1);
		$this->clearPath();
	}
}
$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
if (isset($_REQUEST["id_cat1"])) {
$id_cat1=$_REQUEST["id_cat1"];
$frm->addField("","","hidden",$id_cat1,$id_cat1,"/^[^`]{0,}$/i","id_cat1",1);
}

if ($mode=="edit") {
	if ($ident!=$category["ident"])
		if ($this->rubricExist($ident))
			$frm->addError($lang["error"]["ident_exist"]);
} else {
if ($this->rubricExist($ident))
	$frm->addError($lang["error"]["ident_exist"]);
}

if (!checkdate($date_news[1],$date_news[0],$date_news[2]))
	$frm->addError($lang["error"]["incorrect_date"]);

			if (
$this->processFormData($frm,$lang["forms"]["catalog"]["submit_name"],$first
			)) {
				//добавляем или редактируем
				//404 ошибка
				if ($page404==1) 
					$db->query("update `%categories%` set `page404`=0");
				if ($mode=="edit") {
				 //редактируем
				 if ($file_content) {
					if ($this->setContentFile($id_cat1,$content)) {
						$this->updateContent($id_cat1);
					}
					$content='';
				 } else {
				 	$this->deleteContentFile($id_cat1,$content);
				 }
				 if (isset($id_cat1)) {
				 if ($id_cat==$id_cat1) $id_cat=$category["id_sub_category"];
				 	if ($db->query("update %categories% set caption='".sql_quote($title)."' , title='".sql_quote($titletag)."', meta='".sql_quote($metatag)."',content='".sql_quote($content)."',`subcontent`='".sql_quote($subcontent)."',visible=$visible,`page404`=$page404,`category_type`='".sql_quote($content_type)."',ident='".sql_quote($ident)."',rss_link='".sql_quote($rss_link)."',id_sub_category=$id_cat,id_tpl=$id_tpl,position='".sql_quote($position)."',`keywords`='".sql_quote($metakeywords)."',`date_post`='".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."',`future_post`=$future_post, `preview_width`=$preview_width , `preview_height`=$preview_height,`in_navigation`=$in_navigation,`is_registered`=$is_registered ".$this->generateUpdateSQL("categories",$lang_values)." where id_category=$id_cat1")) {
						//отредактировали
				//	   $modAction="view";
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$id_cat1,'category');
						} 
				   $this->setCongratulation('',$lang["congratulation"]["rubric_edit"],3000);
				   $id_cat_select=$id_cat1;
				   $modAction="view";
				   $this->clearPath();
					} else {
					
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 if ($file_content) {
				 	$cont=$content;
					$content="";
				 } 
				 if (isset($_SESSION["category_small"])) {
				 	$category_small=$_SESSION["category_small"];
					unset($_SESSION["category_small"]);
				 } else {
				 	$category_small='';
				 }
				 if (isset($_SESSION["category_middle"])) {
				 	$category_middle=$_SESSION["category_middle"];
					unset($_SESSION["category_middle"]);
				 } else {
				 	$category_middle='';
				 }
				 $add_id=$this->addCategory($id_cat,$title,$content_type,$ident,$visible,$page404,$titletag,$metatag,$metakeywords,$rss_link,$content,$subcontent,$id_tpl,$position,$date_news,$future_post,$preview_width,$preview_height,$in_navigation,$is_registered,$category_small,$category_middle,'',$lang_values);
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$add_id,'category');
						}
					   $id_cat_select=$add_id;
				if (isset($_REQUEST["mod_name"])) {
					$mod_name=$_REQUEST["mod_name"];
					if (preg_match("/^[a-zA-Z]{1,}$/i",$mod_name)) {
						$smarty->assign("mod_name",$mod_name);
						$smarty->assign("ref",$ref);
						$smarty->assign("id_cat",$add_id);
						$this->setSessionCongratulation('',$lang["congratulation"]["rubric_add"],3000);
					}
				} else {
				   $this->setCongratulation('',$lang["congratulation"]["rubric_add"],3000);
				   if ($file_content) {
				   	//контент в файл
					if ($this->setContentFile($add_id,$cont)) {
						$this->updateContent($add_id);
					}
				   }
				   $modAction="view";
				   $this->clearPath();
				  }
				 } else {
				   //показываем ошибку
				   
				 }
				
				}
			}
			if (isset($mode))
				$smarty->assign("mode",$mode);
			if (isset($id_cat1))
				$smarty->assign("id_object",$id_cat1);
		break;
		case "delete":
			//удаление
			$id_cat=@$_REQUEST["id_cat"];
			if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
				$cat=$this->getCategoryByID($id_cat);
				$id_cat_select=$cat["id_sub_category"];
				$this->deleteCategory($id_cat);
				$modAction="view";
				$this->setCongratulation('',$lang["congratulation"]["rubric_delete"],3000);
			} else {
				//выдаем ошибку
			}
		break;
	}
	if ($modAction=="view" || $modAction=="save") {
	$desc=true;
	$order="sort";
		if (isset($_REQUEST["order"])) {
			$order=$_REQUEST["order"];
			if (isset($_REQUEST["desc"])) {
			$desc=true;
			} else {
			$desc=false;
			}
			$this->setCongratulation('',$lang["interface"]["catalog_sorted"],3000);
		}
		$smarty->assign("order",$order);
		$smarty->assign("desc",$desc);
		if (isset($id_cat_select)) {
			if (preg_match("/^[0-9]{1,}$/i",$id_cat_select)) {
				$path=$this->getPath($id_cat_select);
				foreach ($path as $pth) {
					$config["path"][]=$pth["id_category"];
				}
				$this->config=$config;
			}
		}
		$categories=$this->getRubricsTree(0,0,false,'',false,$order,$desc);
		$smarty->assign("categories",$categories);
	}
}
?>