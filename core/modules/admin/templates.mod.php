<?
/*
модуль блоков
*/
global $page;

if (defined("SCRIPTO_GALLERY")) {

$page["title"]=$lang["modules"]["templates"];
$smarty->assign("page",$page);
$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/templates/");
$this->setAdminTitle($lang["modules"]["templates"]);

				/*информация для генерации шаблонов*/
				$blocks=$this->getAllBlocks(false);
				$smarty->assign("blocks",$blocks);
				/*конец информации для генерации шаблонов*/

	switch ($modAction) {
		case "edit":
			$id_tpl=@$_REQUEST["id_tpl"];
			if (preg_match("/^[0-9]{1,}$/i",$id_tpl)) {
				$tpl=$this->getTemplateByID($id_tpl);
				$smarty->assign("tpl",$tpl);
				if ($tpl["tpl_type"]=="site") {
				$this->addPath('Шаблоны сайта','/admin?module=templates&modAction=view&type='.$tpl["tpl_type"],true);
				$tpl_path=$config["pathes"]["templates_dir"].$config["templates"]["themes"].$tpl["path"];
$main_css=$config["pathes"]["templates_dir"].$config["templates"]["css"].$tpl["tpl_theme"]."/".$tpl["tpl_css"];
				if (is_file($tpl_path)) {
					$is_writable=is_writable($tpl_path);
					if ($is_writable) {
						if (isset($_REQUEST["save"])) {
							$code=@$_REQUEST["code"];
							@set_magic_quotes_runtime(1);
							$fp=fopen($tpl_path,"w");
							fwrite($fp,$code);
							fclose($fp);
							@set_magic_quotes_runtime(0);
							$this->setCongratulation('',$lang["interface"]["data_save"],3000);
						}
					} else {
						$this->setCongratulation($lang["error"]["basic_error"],$lang["error"]["template_no_writable"],0);
					}
					$tpl_html=file_get_contents($tpl_path);
					$smarty->assign("is_writable",$is_writable);
					$smarty->assign("tpl_html",$tpl_html);
					$smarty->assign("tpl_path",$tpl_path);
				} else {
					$smarty->assign("file_error",true);
				}
				if (is_file($main_css)) {
					$smarty->assign("have_css",true);
					$is_writable_css=is_writable($main_css);
					if ($is_writable_css) {
						if (isset($_REQUEST["savecss"])) {
							$csscode=@$_REQUEST["csscode"];
							@set_magic_quotes_runtime(1);
							$fp=fopen($main_css,"w");
							fwrite($fp,$csscode);
							fclose($fp);
							@set_magic_quotes_runtime(0);
							$this->setCongratulation('',$lang["interface"]["data_save"],3000);
						}
					}  else {
						$this->setCongratulation($lang["error"]["basic_error"],$lang["error"]["css_no_writable"],0);
					}
					$css_html=file_get_contents($main_css);
					$smarty->assign("is_writable_css",$is_writable_css);
					$smarty->assign("css_html",$css_html);
					$smarty->assign("css_path",$main_css);
				}
				}
				if ($tpl["tpl_type"]=="block") {
				$this->addPath('Шаблоны блоков','/admin?module=templates&modAction=view&type='.$tpl["tpl_type"],true);	
				$tpl_path=$config["pathes"]["templates_dir"].$config["templates"]["themes"].$tpl["path"];
				if (is_file($tpl_path)) {	
					$is_writable=is_writable($tpl_path);
					if ($is_writable) {
						if (isset($_REQUEST["save"])) {
							$code=@$_REQUEST["code"];
							@set_magic_quotes_runtime(1);
							$fp=fopen($tpl_path,"w");
							fwrite($fp,$code);
							fclose($fp);
							@set_magic_quotes_runtime(0);
							$this->setCongratulation('',$lang["interface"]["data_save"],3000);
						}
					} else {
						$this->setCongratulation($lang["error"]["basic_error"],$lang["error"]["template_no_writable"],0);
					}
					$tpl_html=file_get_contents($tpl_path);
					$smarty->assign("is_writable",$is_writable);
					$smarty->assign("tpl_html",$tpl_html);
					$smarty->assign("tpl_path",$tpl_path);
				} else {
					$smarty->assign("file_error",true);
				}
				}		
				if (isset($tpl["tpl_caption"]))
					$this->addPath($tpl["tpl_caption"],'',false);
				$this->assignPath();
				$smarty->assign("mode",true);
				$smarty->assign("type",$tpl["tpl_type"]);
			}
		break;
		case "view":

		break;
		case "add":
$type=@$_REQUEST["type"];
$smarty->assign("type",$type);
if (isset($_REQUEST["save"])) {
$first=false;
$title=@$_REQUEST["title"];
$file=@$_REQUEST["file"];
$css=@$_REQUEST["css"];
} else {
$first=true;
$title='';
if ($type=='block') {
$file='block.tpl.html';
} else {
$file='index.tpl.html';
}
$css='style.css';
}
$this->addPath($lang["interface"]["templates_module"],'/admin?module=templates&type='.$type,true);
if ($type=="site") {
$this->addPath($lang["interface"]["add_template_site"],'',false);
} elseif ($type=="block") {
$this->addPath($lang["interface"]["add_template_block"],'',false);
}

$this->assignPath();

require ($config["classes"]["form"]);
$frm=new Form($smarty);

$frm->addField($lang["forms"]["templates"]["title"]["caption"],$lang["forms"]["templates"]["title"]["error"],"text",$title,$title,"/^[^`#]{2,255}$/i","title",1,$lang["forms"]["templates"]["title"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["templates"]["title"]["rules"]));

$frm->addField($lang["forms"]["templates"]["file"]["caption"],$lang["forms"]["templates"]["file"]["error"],"text",$file,$file,"/^[^`#]{2,255}$/i","file",1,$lang["forms"]["templates"]["file"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["templates"]["file"]["rules"]));

$frm->addField($lang["forms"]["templates"]["css"]["caption"],$lang["forms"]["templates"]["css"]["error"],"text",$css,$css,"/^[^`#]{2,255}$/i","css",0,$lang["forms"]["templates"]["css"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["templates"]["css"]["rules"]));

$frm->addField("","","hidden",$type,$type,"/^[^`]{0,}$/i","type",1);

			if (
$this->processFormData($frm,$lang["forms"]["templates"]["submit_name"],$first
			)) {
				if ($type=="site") {
					//Шаблон сайта
					if ($this->addTemplate($title,$file,$css,$type)) {
						$this->clearPath();
						$modAction="view";
						$this->setCongratulation('',$lang["congratulation"]["template_add"],5000);
						$tpl_path=$config["pathes"]["templates_dir"].$config["templates"]["themes"].$settings["theme"].'/site/';
						if (!is_writable($tpl_path)) {
							$this->setCongratulation('',$lang["error"]["not_create_tpl_site"],0);
						} else {
						 if (!is_file($tpl_path.$file)) {
							$fp=@fopen($tpl_path.$file,'w');
							@fclose($fp);
						 }
						}
					}
				}
				if ($type=="block") {
					//Шаблон блока
					if ($this->addTemplate($title,$file,$css,$type)) {
						$this->clearPath();
						$modAction="view";
						$this->setCongratulation('',$lang["congratulation"]["template_add"],5000);
						$tpl_path=$config["pathes"]["templates_dir"].$config["templates"]["themes"].$settings["theme"].'/block/';
						if (!is_writable($tpl_path)) {
							$this->setCongratulation('',$lang["error"]["not_create_tpl_block"],0);
						} else {
						 if (!is_file($tpl_path.$file)) {
							$fp=@fopen($tpl_path.$file,'w');
							@fclose($fp);
						 }
						}
					}
				}
			}
		break;
		case "delete":
			$id_tpl=@$_REQUEST["id_tpl"];
			if (preg_match('/^[0-9]{1,}$/i',$id_tpl)) {
				$db->query("delete from `%templates%` where id_tpl=$id_tpl");
				$templates=$this->getTemplates("site");
				$this->setCongratulation('',$lang["congratulation"]["template_delete"],3000);
			}
			$modAction="view";
		break;
	}
	if ($modAction=="view") {
			if (isset($_REQUEST["saveme"])) {
				$caption=@$_REQUEST["caption"];
				if (is_array($caption)) {
					foreach ($caption as $id_tpl=>$capt)
						$db->query("update `%templates%` set `tpl_caption`='".sql_quote($capt)."' where id_tpl=$id_tpl");
					$this->setCongratulation("","Изменения сохранены!",3000);
				}
			}
			$type=@$_REQUEST["type"];
			switch ($type) {
				case "site":
					//получаем шаблоны сайта
					$templates=$this->getTemplatesByType($type);
				break;
				case "block":
					//получаем шаблоны блоков
					$templates=$this->getTemplatesByType($type);
				break;
				case "module":
					//получаем шаблоны модулей
					$templates=$this->getTemplatesByType($type);
				break;
			}
			if (isset($templates)) {
				$smarty->assign("templates",$templates);
			}
			$smarty->assign("type",$type);
	}
}
?>