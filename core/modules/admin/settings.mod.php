<?
/*
модуль настройки
*/
global $db;
if (defined("SCRIPTO_GALLERY")) {

$page["title"]=$lang["modules"]["settings"];
$smarty->assign("page",$page);
$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/settings/");
$this->setAdminTitle($lang["modules"]["settings"]);
$languages=$this->getAllLanguages();

$settings=$this->getSettings();

if (isset($_REQUEST["save"])) {
$first=false;
$title=@$_REQUEST["title"];
$id_lang=@$_REQUEST["id_lang"];
$content=$this->stripContent(@$_REQUEST["fck1"]);
$titletag=@$_REQUEST["titletag"];
$metatag=@$_REQUEST["metatag"];
$keywords=@$_REQUEST["keywords"];
$small_x=@$_REQUEST["small_x"];
$small_y=@$_REQUEST["small_y"];
$medium_x=@$_REQUEST["medium_x"];
$medium_y=@$_REQUEST["medium_y"];
$url=@$_REQUEST["url"];
$mailadmin=@$_REQUEST["mailadmin"];
if (!isset($_REQUEST["set_new_password"])) {
	$set_new_password=0;
} else {
	$set_new_password=1;
}
if (!isset($_REQUEST["module_notes"])) {
	$module_notes=0;
} else {
	$module_notes=1;
}
$oldpassword=@$_REQUEST["oldpassword"];
$newpassword=@$_REQUEST["newpassword"];
$login=@$_REQUEST["login"];
$ips=@$_REQUEST["ips"];
$lang_values=@$_REQUEST["lang_values"];
} else {
$first=true;
$title=$settings["caption"];
$id_lang=$settings["language"];
$content=$settings["description"];
$titletag=$settings["title"];
$metatag=$settings["meta"];
$keywords=$settings["keywords"];
$small_x=$settings["small_x"];
$small_y=$settings["small_y"];
$medium_x=$settings["medium_x"];
$medium_y=$settings["medium_y"];
$url=$settings["url"];
$oldpassword="";
$set_new_password=0;
$login=$settings["login"];
$newpassword="";
$ips=$settings["ips"];
$module_notes=$settings["module_notes"];
$lang_values=$this->generateLangArray("settings",$settings);
$mailadmin=$settings["mailadmin"];
}

require ($config["classes"]["form"]);
$frm=new Form($smarty);

$frm->addField($lang["forms"]["settings"]["title"]["caption"],$lang["forms"]["settings"]["title"]["error"],"text",$title,$title,"/^[^`#]{2,255}$/i","title",1,$lang["forms"]["settings"]["title"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["settings"]["title"]["rules"]));

$frm->addField($lang["forms"]["settings"]["email"]["caption"],$lang["forms"]["settings"]["email"]["error"],"text",$mailadmin,$mailadmin,"/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i","mailadmin",1,$lang["forms"]["settings"]["email"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["settings"]["email"]["rules"]));

$frm->addField($lang["forms"]["settings"]["url"]["caption"],$lang["forms"]["settings"]["url"]["error"],"text",$url,$url,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","url",0,$lang["forms"]["settings"]["url"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["settings"]["url"]["rules"]));

$frm->addField("Дополнительные настройки сайта","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","s01",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["settings"]["language"]["caption"],$lang["forms"]["settings"]["language"]["error"],"hidden",$id_lang,$id_lang,"/^[a-zA-Z]{1,}$/i","id_lang",1,$lang["forms"]["settings"]["language"]["sample"],array('size'=>'30'));

$frm->addField($lang["forms"]["settings"]["module_notes"]["caption"],$lang["forms"]["settings"]["module_notes"]["error"],"check",$module_notes,$module_notes,"/^[0-9]{1}$/i","module_notes",0);

$fck_editor1=$this->createFCKEditor("fck1",$content);
$frm->addField($lang["forms"]["settings"]["content"]["caption"],$lang["forms"]["settings"]["content"]["error"],"solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$frm->addField("","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","s01",0,'',array('end'=>true));

$frm->addField("SEO оптимизация","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","seo",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["settings"]["titletag"]["caption"],$lang["forms"]["settings"]["titletag"]["error"],"text",$titletag,$titletag,"/^[^`#]{2,255}$/i","titletag",0,$lang["forms"]["settings"]["titletag"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["settings"]["titletag"]["rules"]));

$frm->addField($lang["forms"]["settings"]["metatag"]["caption"],$lang["forms"]["settings"]["metatag"]["error"],"textarea",$metatag,$metatag,"/^[^#]{1,}$/i","metatag",0,$lang["forms"]["settings"]["metatag"]["sample"],array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["settings"]["metatag"]["rules"]));

$frm->addField($lang["forms"]["settings"]["keywords"]["caption"],$lang["forms"]["settings"]["keywords"]["error"],"textarea",$keywords,$keywords,"/^[^#]{1,}$/i","keywords",0,$lang["forms"]["settings"]["keywords"]["sample"],array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["settings"]["keywords"]["rules"]));

$frm->addField("SEO оптимизация","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","seo",0,'',array('end'=>true));

$frm->addField("Настройки изображений","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","image",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["settings"]["smallx"]["caption"],$lang["forms"]["settings"]["smallx"]["error"],"text",$small_x,$small_x,"/^[^`#]{1,255}$/i","small_x",1,$lang["forms"]["settings"]["smallx"]["sample"],array('size'=>'4','ticket'=>$lang["forms"]["settings"]["smallx"]["rules"]));

$frm->addField($lang["forms"]["settings"]["smally"]["caption"],$lang["forms"]["settings"]["smally"]["error"],"text",$small_y,$small_y,"/^[^`#]{1,255}$/i","small_y",1,$lang["forms"]["settings"]["smally"]["sample"],array('size'=>'4','ticket'=>$lang["forms"]["settings"]["smally"]["rules"]));

$frm->addField($lang["forms"]["settings"]["mediumx"]["caption"],$lang["forms"]["settings"]["mediumx"]["error"],"text",$medium_x,$medium_x,"/^[^`#]{1,255}$/i","medium_x",1,$lang["forms"]["settings"]["mediumx"]["sample"],array('size'=>'4','ticket'=>$lang["forms"]["settings"]["mediumx"]["rules"]));

$frm->addField($lang["forms"]["settings"]["mediumy"]["caption"],$lang["forms"]["settings"]["mediumy"]["error"],"text",$medium_y,$medium_y,"/^[^`#]{1,255}$/i","medium_y",1,$lang["forms"]["settings"]["mediumy"]["sample"],array('size'=>'4','ticket'=>$lang["forms"]["settings"]["mediumy"]["rules"]));

$frm->addField("Настройки изображений","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","image",0,'',array('end'=>true));

$frm->addField("Ограничение доступа к административной панели","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","security",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["settings"]["ips"]["caption"],$lang["forms"]["settings"]["ips"]["error"],"textarea",$ips,$ips,"/^[^#]{6,}$/i","ips",0,$lang["forms"]["settings"]["ips"]["sample"],array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["settings"]["ips"]["rules"]));

$frm->addField("Ограничение доступа к административной панели","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","security",0,'',array('end'=>true));

if ($this->user["type"]=="root") {
$frm->addField("Смена пароля администратора","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","s02",0,'',array('hidden'=>true));

$frm->addField($lang["forms"]["settings"]["set_new_password"]["caption"],$lang["forms"]["settings"]["set_new_password"]["error"],"check",$set_new_password,$set_new_password,"/^[0-9]{1}$/i","set_new_password",1);

$frm->addField($lang["forms"]["settings"]["login"]["caption"],$lang["forms"]["settings"]["login"]["error"],"text",$login,$login,"/^[a-zA-Z0-9]{4,50}$/i","login",0,$lang["forms"]["settings"]["login"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["settings"]["login"]["rules"]));

$frm->addField($lang["forms"]["settings"]["oldpassword"]["caption"],$lang["forms"]["settings"]["oldpassword"]["error"],"password",$oldpassword,$oldpassword,"/^[a-zA-Z0-9]{4,50}$/i","oldpassword",0,$lang["forms"]["settings"]["oldpassword"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["settings"]["oldpassword"]["rules"]));

$frm->addField($lang["forms"]["settings"]["newpassword"]["caption"],$lang["forms"]["settings"]["newpassword"]["error"],"text",$newpassword,$newpassword,"/^[a-zA-Z0-9]{4,50}$/i","newpassword",0,$lang["forms"]["settings"]["newpassword"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["settings"]["newpassword"]["rules"]));

$frm->addField("Смена пароля администратора","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","s02",0,'',array('end'=>true));
}

$this->generateLangControls("settings",$lang_values,$frm);

			if ($set_new_password) {
				if (!$this->generate_admin_password($oldpassword)==$settings["pass"]) {
					$frm->addError($lang["error"]["wrong_old_password"]);
				} else {
					if (!preg_match("/^[a-zA-Z0-9]{4,50}$/i",$newpassword) && $newpassword=="")
						$frm->addError($lang["forms"]["settings"]["newpassword"]["error"]);
				}
			}

			if (
$this->processFormData($frm,$lang["forms"]["settings"]["submit_name"],$first
			)) {
				if ($this->user["type"]!="root" && $mailadmin!=$settings["mailadmin"]) {
					$mailadmin=$settings["mailadmin"];
					$this->setCongratulation('Ошибка','E-mail администратора разрешено изменять только администратору!',5000);
				}
				$db->query("
				update %settings% set 
				`caption`='".sql_quote($title)."',
				`mailadmin`='".sql_quote($mailadmin)."',
				`description`='".sql_quote($content)."',
				`title`='".sql_quote($titletag)."',
				`meta`='".sql_quote($metatag)."',
				`keywords`='".sql_quote($keywords)."',
				`small_x`=$small_x,
				`small_y`=$small_y,
				`medium_x`=$medium_x,
				`medium_y`=$medium_y,
				`url`='".sql_quote($url)."',
				`ips`='".sql_quote($ips)."',
				`module_notes`=$module_notes
				".$this->generateUpdateSQL("settings",$lang_values)."
				");
				echo mysql_error();
				if ($this->user["type"]=="root") {
				if ($set_new_password) {
				$db->query("
				update %settings% set
				`login`='$login',
				`pass`='".$this->generate_admin_password($newpassword)."'
				");
				}
				}
				$this->processFormData($frm,$lang["forms"]["settings"]["submit_name"],true);
				$this->setCongratulation('',$lang["congratulation"]["settings_save"],3000);
			}

}
?>