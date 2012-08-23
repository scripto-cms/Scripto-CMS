<?
/*
Модуль новости сайта, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
$m_action=@$_REQUEST["m_action"];
switch ($m_action) {
	case "quickedit":
			if (isset($_REQUEST["id_news"])) {
				$id_news=@$_REQUEST["id_news"];
				if (preg_match("/^[0-9]{1,}$/i",$id_news)) {
					$news=$this->getNewsByID($id_news);
				    if (isset($_REQUEST["fck1"])) {
		    			$content=$engine->stripContent(@$_REQUEST["fck1"]);
		    			$first=false;
				    } else {
				    	$content=$news["content_full"];
				    	$first=true;
				    }	
					$fck_editor1=$engine->createFCKEditor("fck1",$content);
					$smarty->assign("editor",$fck_editor1);
					$smarty->assign("news",$news);
					$close=@$_REQUEST["close"];
					$smarty->assign("close",$close);
					if (isset($_REQUEST["save"])) {
						if ($db->query("update %NEWS% set `content_full`='".sql_quote($content)."' where id_news=$id_news")) {
							$smarty->assign("save",true);
						}
					}
				}
			}	
	break;
	case "save":
			$id_news=@$_REQUEST["idnews"];
			$caption=@$_REQUEST["caption"];
			$author=@$_REQUEST["author"];
			$url=@$_REQUEST["url"];
			$n=0;
			foreach ($id_news as $key=>$nw) {
				if (preg_match("/^[0-9]{1,}$/i",$nw)) {
					$capt="";
					$auth="";
					$url_string="";
					if (isset($caption[$key]))
						$capt="`caption`='".sql_quote($caption[$key])."'";
					if (isset($author[$key]))
						$auth=",`author`='".sql_quote($author[$key])."'";
					if (isset($url[$key]))
						if (preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$url[$key]) || (trim($url[$key])==''))
							$url_string=",`url`='".sql_quote($url[$key])."'";
				if ($db->query("update `%NEWS%` set $capt $auth $url_string where id_news=$nw")) {
					$n++;
				}
				}
			}
			$engine->clearCacheBlocks($this->thismodule["name"]);
			$m_action="view";
			$engine->setCongratulation("","Обновлено $n новостей!",5000);
	break;
	case "add":
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_news"])) {
				$id_news=@$_REQUEST["id_news"];
				$news=$this->getNewsByID($id_news);
			}
	  		if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
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
						$img=$engine->getImageByID($id_image);
						}
						switch ($previewMode) {
							case "small":
								if ($mode!="edit") {
$engine->setPreview($img["small_photo"],$previewMode,'Малое превью установлено');
$_SESSION["news_small"]=$img["small_photo"];
								} else {
								if ($db->query("update `%NEWS%` set `small_preview`='".$img["small_photo"]."' where id_news=$id_news")) {
$engine->setPreview($img["small_photo"],$previewMode,'Малое превью установлено');
								}
								}
							break;
							case "medium":
								if ($mode!="edit") {
$engine->setPreview($img["medium_photo"],$previewMode,'Среднее превью установлено');
$_SESSION["news_middle"]=$img["medium_photo"];
								} else {
								if ($db->query("update `%NEWS%` set `middle_preview`='".$img["medium_photo"]."' where id_news=$id_news")) {
									$engine->setPreview($img["medium_photo"],$previewMode,'Среднее превью установлено');
								}
								}
							break;
						}
					}
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
						if (defined("SCRIPTO_tags")) {
							$tags=@$_REQUEST["tags"];
						}
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($news) {
						$caption=$news["caption"];
						$content=$news["content"];
						$content_full=$news["content_full"];
						$meta=$news["meta"];
						$keywords=$news["keywords"];
						$author=$news["author"];
						$url=$news["url"];
						$date_news=array();
						$date_news[0]=$news["date_day"];
						$date_news[1]=$news["date_month"];
						$date_news[2]=$news["date_year"];
						if (defined("SCRIPTO_tags")) {
	$tags=$tgs->getTags($news["id_news"],'news','text');
						}
						$lang_values=$engine->generateLangArray("NEWS",$news);
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
						if (defined("SCRIPTO_tags")) {
							$tags='';
						}
					$lang_values=$engine->generateLangArray("NEWS",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("Название новости","Неверно заполнено название новости","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Открытие сайта",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

if (defined("SCRIPTO_tags")) {
$frm->addField($lang["forms"]["catalog"]["tags"]["caption"],$lang["forms"]["catalog"]["tags"]["error"],"text",$tags,$tags,"/^[^`#]{2,255}$/i","tags",0,$lang["forms"]["catalog"]["tags"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["tags"]["rules"]));
}

$frm->addField("Дата новости","Неверно заполнена дата новости","date",$date_news,$date_news,"/^[0-9]{1,}$/i","date_news",1,"19.01.2008",array('size'=>'40','ticket'=>"Цифры и точки"));

$fck_editor1=$engine->createFCKEditor("fck1",$content);
$frm->addField("Краткое описание новости","Неверно заполнено краткое описание новости","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$fck_editor2=$engine->createFCKEditor("fck2",$content_full);
$frm->addField("Полное описание новости","Неверно заполнено полное описание новости","solmetra",$fck_editor2,$fck_editor2,"/^[[:print:][:allnum:]]{1,}$/i","content_full",1,"");

$frm->addField("Тег meta description","Неверно заполнен тег meta description","textarea",$meta,$meta,"/^[^`#]{2,255}$/i","meta",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Тег meta keywords","Неверно заполнен тег meta keywords","textarea",$keywords,$keywords,"/^[^`#]{2,255}$/i","keywords",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$engine->generateLangControls("NEWS",$lang_values,$frm);

$frm->addField('Превью для новости',"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('hidden'=>true));
if ($mode=="edit") {
$frm->addField('Малое изображение превью','',"preview",$news["small_preview"],$news["small_preview"],"/^[0-9]{1,}$/i","min_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true));
$frm->addField('Среднее изображение превью','',"preview",$news["middle_preview"],$news["middle_preview"],"/^[0-9]{1,}$/i","big_preview",0,'',array('mode'=>'medium','multiple'=>'no','fancy_show'=>true));
} else {
if (isset($_SESSION["news_small"])) {
$news["small_preview"]=$_SESSION["news_small"];
} else {
$news["small_preview"]='';
}
if (isset($_SESSION["news_middle"])) {
$news["middle_preview"]=$_SESSION["news_middle"];
} else {
$news["middle_preview"]='';
}
$frm->addField('Малое изображение превью','',"preview",$news["small_preview"],$news["small_preview"],"/^[0-9]{1,}$/i","min_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true));
$frm->addField('Среднее изображение превью','',"preview",$news["middle_preview"],$news["middle_preview"],"/^[0-9]{1,}$/i","big_preview",0,'',array('mode'=>'medium','multiple'=>'no','fancy_show'=>true));
}
$frm->addField('Превью для новости',"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('end'=>true));

$frm->addField("Источник","Неверно заполнен источник новости","text",$author,$author,"/^[^`#]{2,255}$/i","author",0,"http://www.lenta.ru",array('size'=>'40','ticket'=>"Любые буквы и цифры , включая имена и адреса сайтов"));

$frm->addField("URL для перехода на новость","Неверно заполнен URL для перехода на новость","text",$url,$url,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","url",0,"http://www.lenta.ru/18/08/2008/12.html",array('size'=>'40','ticket'=>"Адрес сайта"));

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_news"])) {
$id_news=$_REQUEST["id_news"];
$frm->addField("","","hidden",$id_news,$id_news,"/^[^`]{0,}$/i","id_news",1);
}

if ($mode=="edit") {
	$engine->addPath('Редактирование новости','',false);
} else {
	$engine->addPath('Добавление новости','',false);
}

if (checkdate($date_news[1],$date_news[0],$date_news[2])==false)
	$frm->addError("Выбранной даты не существует!");

			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_news)) {
				 	if ($db->query("update %NEWS% set `caption`='".sql_quote($caption)."' , author='".sql_quote($author)."', `meta`='".sql_quote($meta)."', `keywords`='".sql_quote($keywords)."' , `url`='".sql_quote($url)."',`date_news`='".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."',content='".sql_quote($content)."',`content_full`='".$content_full."' ".$engine->generateUpdateSQL("NEWS",$lang_values)." where id_news=$id_news")) {
						//отредактировали
				//	   $modAction="view";
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$id_news,'news');
						}
				   $engine->setCongratulation("","Новость отредактирована успешно!",3000);
				   $m_action="view";
				   $engine->clearCacheBlocks($this->thismodule["name"]);
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 if (isset($_SESSION["news_small"])) {
				 	$news_small=$_SESSION["news_small"];
					unset($_SESSION["news_small"]);
				 } else {
				 	$news_small='';
				 }
				 if (isset($_SESSION["news_middle"])) {
				 	$news_middle=$_SESSION["news_middle"];
					unset($_SESSION["news_middle"]);
				 } else {
				 	$news_middle='';
				 }
 $add_id=$this->addNews($date_news,$caption,$content,$content_full,$meta,$keywords,$url,$author,$news_small,$news_middle,$engine->generateInsertSQL("NEWS",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$add_id,'news');
						}
				   $engine->setCongratulation("","Новость создана успешно!",3000);
				   $m_action="view";
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				 }
				}
			}
			$engine->assignPath();
	break;
	case "delete":
		$id_news=@$_REQUEST["id_news"];
		if (preg_match("/^[0-9]{1,}$/i",$id_news)) {
			if ($db->query("delete from %NEWS% where id_news=$id_news")) {
				$engine->setCongratulation("","Новость удалена!",3000);
		  		if (defined("SCRIPTO_tags")) {
					$tgs=new Tags();
					$tgs->doDb();
					$tgs->deleteTags($id_news,'news');
				}
				$m_action="view";
				$engine->clearCacheBlocks($this->thismodule["name"]);
			}
		}
	break;
	default:
		$m_action="view";
}
if ($m_action=="view") {
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'',false);
	$engine->assignPath();
			$onpage=$this->thismodule["onpage_admin"];
			$count=$this->getAllNewsCount();
			$pages=ceil($count/$onpage);
			for ($x=0;$x<=$pages-1;$x++) $pages_arr[]=$x;
			if (isset($_REQUEST["p"])) {
					$pg=@$_REQUEST["p"];
					if (!preg_match("/^[0-9]{1,}$/i",$pg)) $pg=0;
					if ($pg>=$pages) $pg=0;
					if ($pg<0) $pg=0;
			} else {
				$pg=0;
			}
	$news=$this->getAllNews($pg,$onpage);
	$smarty->assign("news",$news);
	if (isset($pages_arr)) {
	$smarty->assign("pages",$pages_arr);
	$smarty->assign("pg",$pg);
	}
}
$smarty->assign("m_action",$m_action);
?>