<?
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
				}
			}
			$kcaptcha=@$_REQUEST["kcaptcha"];
			$titletag='';
			$metatag='';
			$metakeywords='';
			
			$smarty->assign("is_file_form",true);
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			$frm->addField($lang["objects"]["rubric"]["caption"],$lang["objects"]["rubric"]["error"],"list",$need_categories,$id_cat,"/^[0-9]{1,}$/i","id_cat",1,$lang["objects"]["rubric"]["sample"],array('size'=>'30'));
			
			$frm->addField($lang["objects"]["caption"]["caption"].$type["fulllink_text"],$lang["objects"]["caption"]["error"].$type["fulllink_text"],"text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,$lang["objects"]["caption"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

			if (defined("SCRIPTO_tags")) {
	$frm->addField($lang["forms"]["catalog"]["tags"]["caption"],$lang["forms"]["catalog"]["tags"]["error"],"text",$tags,$tags,"/^[^`#]{2,255}$/i","tags",0,$lang["forms"]["catalog"]["tags"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["catalog"]["tags"]["rules"]));
			}
			if ($type["use_code"]) {
				$frm->addField($lang["objects"]["cd"]["caption"].$type["fulllink_text"],$lang["objects"]["cd"]["error"].$type["fulllink_text"],"text",$code,$code,"/^[^`#]{2,255}$/i","code",1,"24545356",array('size'=>'40','ticket'=>"Любые буквы и цифры"));
			}
			if ($type["short_content"]) {
			$fck_editor1=$engine->createFCKEditor("fck1",$content);
			$frm->addField($lang["objects"]["short_content"]["caption"].$type["fulllink_text"],$lang["objects"]["short_content"]["error"].$type["fulllink_text"],"solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");
			}
			if ($type["full_content"]) {
			$fck_editor2=$engine->createFCKEditor("fck2",$content_full);
			$frm->addField($lang["objects"]["content"]["caption"].$type["fulllink_text"],$lang["objects"]["content"]["error"].$type["fulllink_text"],"solmetra",$fck_editor2,$fck_editor2,"/^[[:print:][:allnum:]]{1,}$/i","content_full",1,"");
			}


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
					$frm->addField($tp["caption"],$lang["objects"]["form"]["error2"].$tp["caption"],"list",$tp["values"],$listval[$key],$eregi,"listval[$key]",0,"",array('size'=>'40'));	
				}
			}
			if (isset($type["checkbox"])) 
			if (is_array($type["checkbox"])) {
				foreach ($type["checkbox"] as $key=>$tp) {
					$frm->addField($tp["caption"],$lang["objects"]["form"]["error3"].$tp["caption"],"check",$val[$key],$val[$key],"/^[0-9]{0,1}$/i","val[$key]",0,"");	
				}
			}

$frm->addField($lang["objects"]["captcha"]["caption"],$lang["objects"]["captcha"]["error"],"kcaptcha","",$config["classes"]["kcaptha"],"/^[^#]{1,}$/i","kcaptcha",1,'',array('ticket'=>'Любые цифры и буквы'));
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] !=  @$kcaptcha){
				$frm->addError($lang["objects"]["captcha"]["error"]);
			}
			
$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$id_category,$id_category,"/^[^`]{0,}$/i","id_category",1);
$frm->addField("","","hidden",$id_type,$id_type,"/^[^`]{0,}$/i","id_type",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_object"])) {
$id_object=$_REQUEST["id_object"];
$frm->addField("","","hidden",$id_object,$id_object,"/^[^`]{0,}$/i","id_object",1);
}

if ($mode=="edit") {
$engine->addPath($lang["objects"]["add_object_text"],'',false);
$btn=$lang["objects"]["save_object"];
	if ($type["use_code"] && $code!=$object["code"]) {
		if ($this->existObject($code,$id_type))
			$frm->addError($lang["objects"]["object_exist"]);
	}
} else {
$engine->addPath($lang["objects"]["save_object_text"],'',false);
$btn=$lang["objects"]["add_object"];
	if ($type["use_code"] && !$first) {
		if ($this->existObject($code,$id_type))
			$frm->addError($lang["objects"]["object_exist"]);
	}
}

			 if (defined("SCRIPTO_forms")) {
			 	$tpl_form="system/classes/module_form.html";
			 } else {
			 	$tpl_form='';
			}
			if (
$engine->processFormData($frm,$btn,$first,$tpl_form
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
						
					if ($db->query("update `%OBJ%` set `id_category`=$id_cat,`caption`='".sql_quote($caption)."',`new`=1,`visible`=0 $values_str $listvalues_str $content_sql $code_sql where id_object=$id_object")) {
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$id_object,'objects');
						}
						if ($type["full_content"]) {
							$engine->setContentFileEx($id_object,$content_full,"objects/".$type["id_type"]);
						}
						$object=$this->getObjectByID($id_object,$type);
						$smarty->assign("object",$object);
						$smarty->assign("add",true);
						$smarty->assign("edit",true);
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
					$add_id=$this->addObject($id_cat,$type["id_type"],$caption,$titletag,$metatag,$metakeywords,$code,'','',$content,$content_full,$val,$listval,null,0,1,$engine->generateInsertSQL("OBJ",$lang_values));
					if ($add_id>0) {
				  		if (defined("SCRIPTO_tags")) {
							$tgs->addTags($tags,$add_id,'objects');
						}
						if ($engine->checkInstallModule("users")) {
							//модуль пользователи установлен
							if (($_SESSION["auth"]==true) && (@$_SESSION["user_login"]!='')) {
								$u=new Users();
								$u->doDb();
								$usr=$u->getUserByLogin($_SESSION["user_login"]);
								$id_user=$usr["id_user"];
								$u->addObject2User($add_id,$id_user,$type["id_type"]);
								unset($u);
							}
						}
						if ($type["full_content"]) {
							$engine->setContentFileEx($add_id,$content_full,"objects/".$type["id_type"]);
						}
						$object=$this->getObjectByID($add_id,$type);
						$smarty->assign("object",$object);
						$smarty->assign("add",true);
					}
				}

			}
?>