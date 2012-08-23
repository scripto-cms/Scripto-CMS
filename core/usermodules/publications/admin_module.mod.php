<?
/*
Модуль новости сайта, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
$m_action=@$_REQUEST["m_action"];
			
switch ($m_action) {
	case "quickedit":
			if (isset($_REQUEST["id_publication"])) {
				$id_publication=@$_REQUEST["id_publication"];
				if (preg_match("/^[0-9]{1,}$/i",$id_publication)) {
					$publication=$this->getPublicationByID($id_publication);		
				    if (isset($_REQUEST["fck1"])) {
		    			$content=$engine->stripContent(@$_REQUEST["fck1"]);
		    			$first=false;
				    } else {
				    	$content=$publication["content_full"];
				    	$first=true;
				    }	
					$fck_editor1=$engine->createFCKEditor("fck1",$content);
					$smarty->assign("editor",$fck_editor1);
					$smarty->assign("id_publication",$id_publication);
					$smarty->assign("publication",$publication);
					$close=@$_REQUEST["close"];
					$smarty->assign("close",$close);
					if (isset($_REQUEST["save"])) {
						if ($engine->setContentFileEx($id_publication,$content,"publications")) {
							$smarty->assign("save",true);
						}
					}
				}
			}	
	break;
	case "add":
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$values=array();
	$engine->getRubricsTreeEx($values,0,0,true,"",false);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_publication"])) {
				$id_publication=@$_REQUEST["id_publication"];
				$publication=$this->getPublicationByID($id_publication);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$content=$engine->stripContent(@$_REQUEST["fck1"]);
				$content_full=$engine->stripContent(@$_REQUEST["fck2"]);
				$meta=strip_tags($_REQUEST["meta"]);
				$keywords=strip_tags($_REQUEST["keywords"]);
				$date_news=array();
				$date_news[0]=@$_REQUEST["date_news_day"];
				$date_news[1]=@$_REQUEST["date_news_month"];
				$date_news[2]=@$_REQUEST["date_news_year"];
				$author=@$_REQUEST["author"];
				$url=@$_REQUEST["url"];
				$id_cat=@$_REQUEST["id_category"];
				if (isset($_REQUEST["visible"])) {
				 $visible=1;
				} else {
				 $visible=0;
				}
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($publication) {
						$caption=$publication["caption"];
						$content=$publication["content"];
						$content_full=$publication["content_full"];
						$meta=$publication["meta"];
						$keywords=$publication["keywords"];
						$author=$publication["author"];
						$url=$publication["url"];
						$date_news=array();
						$date_news[0]=$publication["date_day"];
						$date_news[1]=$publication["date_month"];
						$date_news[2]=$publication["date_year"];
						$id_cat=@$publication["id_category"];
						$visible=@$publication["visible"];
					$lang_values=$engine->generateLangArray("PUBLICATIONS",$publication);
					}
				} else {
					$caption="";
					$content="";
					$content_full="";
					$meta="";
					$keywords="";
					$author="";
					$url="";
					$date_news=array();
					$date_news[0]=(int)date("d");
					$date_news[1]=(int)date("m");
					$date_news[2]=(int)date("Y");
					if (isset($_REQUEST["id_category"])) {
						if (preg_match("/^[0-9]{1,}$/i",$_REQUEST["id_category"])) {
							$id_cat=$_REQUEST["id_category"];
						}
					} else {
					$id_cat=@$values[0]["id"];
					}
					$visible=1;
					$lang_values=$engine->generateLangArray("PUBLICATIONS",null);
				}
			}
			$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&id_category='.$id_cat.'&module_name='.$this->thismodule["name"],true);
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
$frm->addField('Раздел','Неправильно выбран раздел',"list",$values,$id_cat,"/^[0-9]{1,}$/i","id_category",1,$lang["forms"]["catalog"]["razdel"]["sample"],array('size'=>'30'));
			
$frm->addField("Название публикации","Неверно заполнено название публикации","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Открытие сайта",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Дата создания публикации","Неверно заполнена дата создания публикации","date",$date_news,$date_news,"/^[0-9]{1,}$/i","date_news",1,"19.01.2008",array('size'=>'40','ticket'=>"Цифры и точки"));

$fck_editor1=$engine->createFCKEditor("fck1",$content);
$frm->addField("Краткое описание публикации","Неверно заполнено краткое описание публикации","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$fck_editor2=$engine->createFCKEditor("fck2",$content_full);
$frm->addField("Полное описание публикации","Неверно заполнено полное описание публикации","solmetra",$fck_editor2,$fck_editor2,"/^[[:print:][:allnum:]]{1,}$/i","content_full",1,"");

$frm->addField("Тег meta description","Неверно заполнен тег meta description","textarea",$meta,$meta,"/^[^`#]{2,255}$/i","meta",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Тег meta keywords","Неверно заполнен тег meta keywords","textarea",$keywords,$keywords,"/^[^`#]{2,255}$/i","keywords",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$engine->generateLangControls("PUBLICATIONS",$lang_values,$frm);

$frm->addField("Автор","Неверно заполнено поле автор","text",$author,$author,"/^[^`#]{2,255}$/i","author",0,"Pentax",array('size'=>'40','ticket'=>"Любые буквы и цифры , включая имена и адреса сайтов"));

$frm->addField("Сайт автора","Неверно заполнен URL автора","text",$url,$url,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","url",0,"http://www.lenta.ru/18/08/2008/12.html",array('size'=>'40','ticket'=>"Адрес сайта"));


$frm->addField($lang["forms"]["publications"]["visible"]["caption"],$lang["forms"]["publications"]["visible"]["error"],"check",$visible,$visible,"/^[0-9]{1}$/i","visible",1);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_publication"])) {
$id_publication=$_REQUEST["id_publication"];
$frm->addField("","","hidden",$id_publication,$id_publication,"/^[^`]{0,}$/i","id_publication",1);
}

if ($mode=="edit") {
	$engine->addPath('Редактирование публикации','',false);
} else {
	$engine->addPath('Добавление публикации','',false);
}

if (checkdate($date_news[1],$date_news[0],$date_news[2])==false)
	$frm->addError("Выбранной даты не существует!");
	
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_publication)) {
				 	if ($db->query("update %PUBLICATIONS% set `caption`='".sql_quote($caption)."' , author='".sql_quote($author)."' , `meta`='".sql_quote($meta)."' , `keywords`='".sql_quote($keywords)."' , `url`='".sql_quote($url)."',`date`='".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."',content='".sql_quote($content)."',`content_full`='',visible=$visible,id_category=$id_cat ".$engine->generateUpdateSQL("PUBLICATIONS",$lang_values)." where id_publication=$id_publication")) {
						//отредактировали
				//	   $modAction="view";
				$engine->setContentFileEx($id_publication,$content_full,"publications");
				   $engine->setCongratulation("","Публикация отредактирована успешно!",3000);
					$engine->clearCacheBlocks($this->thismodule["name"]);
				   $m_action="view";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
 $add_id=$this->addPublication($date_news,$caption,$content,'',$meta,$keywords,$url,$author,$id_cat,$visible,$engine->generateInsertSQL("PUBLICATIONS",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
	$engine->setContentFileEx($add_id,$content_full,"publications");
	$engine->clearCacheBlocks($this->thismodule["name"]);
	$engine->addModuleToCategory($this->thismodule["name"],$id_cat);
				   $engine->setCongratulation("","Публикация добавлена успешно!",3000);
				   $m_action="view";
				 }
				}
			}
			$engine->assignPath();
	break;
	case "delete":
		$id_publication=@$_REQUEST["id_publication"];
		if (preg_match("/^[0-9]{1,}$/i",$id_publication)) {
			$publication=$this->getpublicationByID($id_publication);
			if ($db->query("delete from %PUBLICATIONS% where id_publication=$id_publication")) {
				$engine->clearCacheBlocks($this->thismodule["name"]);
				$engine->setCongratulation("","Публикация удалена!",5000);
			}
		}
		$m_action="view";
	break;
	case "save":
		$id_category=@$_REQUEST["id_category"];
		if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
			$idpubl=@$_REQUEST["idpubl"];
			$vis=@$_REQUEST["vis"];
			$capt=@$_REQUEST["capt"];
			$auth=@$_REQUEST["auth"];
			$del=@$_REQUEST["del"];
			$n=0;
			$d=0;
			foreach ($idpubl as $key=>$publ) {
			if (isset($del[$key])) {
				if ($db->query("DELETE FROM %PUBLICATIONS% where `id_publication`=$publ")) 
					$d++;
			} else {
				if (isset($vis[$key])) {
					$vis_value=1;
				} else {
					$vis_value=0;
				}
				$caption="";
				$author="";
				if (isset($capt[$key]))
					$caption=",`caption`='".sql_quote($capt[$key])."'";
				if (isset($auth[$key]))
					$author=",`author`='".sql_quote($auth[$key])."'";				
				if ($db->query("UPDATE %PUBLICATIONS% set `visible`=$vis_value $caption $author where `id_publication`=$publ")) 
					$n++;
			}
			}
		}
			$engine->clearCacheBlocks($this->thismodule["name"]);
			$engine->setCongratulation("","Удалено $d публикации(ий), обновлено $n публикации(ий)",3000);
			$m_action="view";
	break;	
	default:
		$m_action="view";
}
if ($m_action=="view") {
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'',false);
	$engine->assignPath();
		//получаем все рубрики
		$rubrics=$this->getCountAllpublications();
		if (isset($_REQUEST["view_publications"])) $smarty->assign("view_publications",true);
		if (isset($_REQUEST["id_category"])) {
			$id_category=$_REQUEST["id_category"];
			if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
					$onpage=$onpage=$this->thismodule["onpage_admin"];
					$count=$this->getpublicationsCount($id_category,true);
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
					$publications=$this->getpublications($id_category,false,$pg,$onpage);
					$smarty->assign("publications",$publications);
					if (isset($pages_arr)) {
					$smarty->assign("pages",$pages_arr);
					$smarty->assign("pg",$pg);
					}
					$smarty->assign("id_category",$id_category);
					$category=$engine->getCategoryByID($id_category);
					$smarty->assign("category",$category);
					$ppath=$engine->getPath($id_category);
					$smarty->assign("ppath",$ppath);					
			}
		}
		$smarty->assign("lang",$lang);
}
$smarty->assign("m_action",$m_action);
?>