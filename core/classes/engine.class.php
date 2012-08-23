<?
/*
Главный класс галлереи
*/
	class Engine {
	var $config;
	var $db;
	var $settings;
	var $lang;
	var $smarty;
	var $page;
	var $user;
	var $rubrics;
	var $templates;
	var $modules;
	var $congratulation=array();
	var $session_congratulation=array();
	var $vn_path=array();
	var $dop_path=array();
	var $urls;
	var $js;
	var $languages=array();
	var $tables=array();
	var $current_language="ru";
	var $current_prefix="";
	
		function Engine() {
			$this->templates=$this->getTemplatesSystem();
		}
		
		//Получаем язык
		function getLanguage($id_rubric) {
			foreach ($this->languages as $lng)
				if ($lng["default"]==1)
					$def_lng=$lng["ident"];
			if ($id_rubric=='') {
				return $def_lng;
			} else {
				$ids=explode("/",$id_rubric);
				if (isset($this->languages[$ids[0]])) {
					foreach ($ids as $k=>$id)
						if ($k>0)
							$new_ids[]=$id;
					if (isset($new_ids)) {
						$id_rubric=implode('/',$new_ids);
					} else {
						$id_rubric="";
					}
					return $ids[0];
				} else {
					return $def_lng;
				}
			}
		}
		
		function setCategoryAdditional($id_category,$val=array()) {
			global $db;
			if (!preg_match("/^[0-9]{1,}$/i",$id_category)) return false;
			if (is_array($val)) {
				$properties=$this->encodeArray($val);
				$prop_sql=serialize($properties);
				if ($db->query("update `%categories%` set `properties`='".sql_quote($prop_sql)."' where `id_category`=$id_category")) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function generateUpdateSQL($table,$values) {
			global $db;
			global $tables;
			global $languages;
			$sql='';
			if (isset($this->tables[$table])) {
				if (sizeof($languages)>1) {
					foreach ($languages as $lng)
					if ($lng["default"]==0) {
						foreach ($this->tables[$table] as $key=>$field) {
							if (isset($values[$lng["ident"]][$key])) {
								$val=$values[$lng["ident"]][$key];
							} else {
								$val='';
							}
							$sql.=",`".$key."_".$lng["ident"]."`='".sql_quote($val)."'";
						}
					}
				}
			}
			return $sql;
		}
		
		function generateInsertSQL($table,$values) {
			global $db;
			global $tables;
			global $languages;
			$sql='';
			if (isset($this->tables[$table])) {
				if (sizeof($languages)>1) {
					foreach ($languages as $lng)
					if ($lng["default"]==0) {
						foreach ($this->tables[$table] as $key=>$field) {
							if (isset($values[$lng["ident"]][$key])) {
								$val=$values[$lng["ident"]][$key];
							} else {
								$val='';
							}
							$sql.=",'".sql_quote($val)."'";
						}
					}
				}
			}
			return $sql;
		}
		
		function generateLangControls($table,$values,$f) {
			global $lang;
			global $languages;
			global $tables;
			
			if (isset($this->tables[$table])) {
				if (sizeof($languages)>1) {
	$f->addField($lang["interface"]["languages"],"","caption",0,0,"/^[0-9]{1}$/i","langs",0,'',array('hidden'=>true));
				foreach ($languages as $lng)
					if ($lng["default"]==0) {
					$f->addField($lng["caption"],"","caption",0,0,"/^[0-9]{1}$/i","lang".$lng["id_language"],0,'',array('hidden'=>true));
						foreach ($this->tables[$table] as $key=>$field) {
							if (isset($values[$lng["ident"]][$key])) {
								$val=$values[$lng["ident"]][$key];
							} else {
								$val='';
							}
							if ($field["type"]=="solmetra") {
							$val=$this->createFCKEditor("lang_values[".$lng["ident"]."][$key]",$val);
							}
							$f->addField($field["caption"],'Неверно заполнено поле '.$field["caption"].' (язык - '.$lng["ident"].')',$field["type"],$val,$val,$field["eregi"],"lang_values[".$lng["ident"]."][$key]",0,'');
						}
$f->addField($lng["caption"],"","caption",0,0,"/^[0-9]{1}$/i","lang".$lng["id_language"],0,'',array('end'=>true));
					}
$f->addField('',"","caption",0,0,"/^[0-9]{1}$/i","langs",0,'',array('end'=>true));
				}
				return true;
			} else {
				return false;
			}
		}
		
		function generateLangArray($table,$object) {
			global $db;
			global $tables;
			global $languages;
			if (isset($this->tables[$table])) {
				$val=array();
				foreach ($languages as $lng)
					if ($lng["default"]==0)
					foreach ($this->tables[$table] as $key=>$field) {
						if (is_array($object)) {
							if (isset($object[$key.'_'.$lng["ident"]])) {
								$val[$lng["ident"]][$key]=$object[$key.'_'.$lng["ident"]];
							} else {
								$val[$lng["ident"]][$key]="";
							}
						} else {
							$val[$lng["ident"]][$key]="";
						}
					}
				return $val;
			} else {
				return false;
			}
		}
		
		function existLanguage($ident) {
			global $db;
			if (!preg_match("/^[a-z]{1,8}$/i",$ident)) return false;
			$res=$db->query("select * from `%languages%` where `ident`='$ident'");
			if (mysql_num_rows($res)>0) {
				return true;
			} else {
				return false;
			}
		}
		
		function createLanguage($caption,$ident,$default=0) {
			global $db;

			if ($default!=1) $default=0;
			if (!preg_match("/^[a-z]{1,8}$/i",$ident)) return false;
$modules=$this->getInstallModulesFast();
	foreach ($modules as $key=>$module) {
		if (@$module["name"]!=false) {
		$mod=$this->includeModule($this->getModule($module["name"]));
			if (method_exists($mod,'doDb')) {
				if (isset($mod->thismodule["tables"]))
					if (is_array($mod->thismodule["tables"]))
						$this->assignTables($mod->thismodule["tables"]);
				$mod->doDb();
			}
		}
	}
			if ($db->query("insert into `%languages%` values(null,$default,'$ident','".sql_quote($caption)."')")) {
				
				$this->doMultilang('',$ident);
				return true;
			} else {
				return false;
			}
		}
		
		function getLanguageByID($id_language) {
			global $db;
			if (!preg_match("/^[0-9]{1,}$/i",$id_language)) return false;
			$res=$db->query("select * from `%languages%` where id_language=$id_language");
			return $db->fetch($res);
		}
		
		function deleteLanguage($id_language) {
			global $db;
			if (!preg_match("/^[0-9]{1,}$/i",$id_language)) return false;
			$lng=$this->getLanguageByID($id_language);
			if ($lng["default"]==0) {
			$ident=$lng["ident"];
			$this->clearCacheBlocksLang($ident);
$modules=$this->getInstallModulesFast();
	foreach ($modules as $key=>$module) {
		if (@$module["name"]!=false) {
		$mod=$this->includeModule($this->getModule($module["name"]));
			if (method_exists($mod,'doDb')) {
				if (isset($mod->thismodule["tables"]))
					if (is_array($mod->thismodule["tables"]))
						$this->assignTables($mod->thismodule["tables"]);
				$mod->doDb();
			}
		}
	}
			if ($db->query("delete from `%languages%` where id_language=$id_language")) {
				$this->deleteMultilang('',$ident);
				return true;
			} else {
				return false;
			}
			} else {
				return false;
			}
		}
		
		function assignTables($table) {
			if (is_array($table))
				$this->tables=array_merge($this->tables,$table);
			return true;
		}
		
		function alter($language,$table,$field,$type) {
			global $db;
			if ($db->query("ALTER TABLE `%$table%` ADD `".$field."_".$language."` $type")) {
				return true;
			} else {
				return false;
			}
		}
		
		function alter_delete($language,$table,$field) {
			global $db;
			if ($db->query("ALTER TABLE `%$table%` DROP `".$field."_".$language."`")) {
				return true;
			} else {
				return false;
			}
		}
		
		function deleteMultilang($table="",$language) {
			global $tables;
			if (preg_match("/^[a-zA-Z0-9_-]{1,}$/i",$table)) {
				if (isset($this->tables[$table]))
					foreach ($this->tables[$table] as $field=>$arr)
						$this->alter_delete($language,$table,$field);
			} else {
				foreach ($this->tables as $tbl=>$t)
					foreach ($t as $field=>$arr)
						$this->alter_delete($language,$tbl,$field);
			}
			return true;
		}
		
		function doMultilang($table="",$language) {
			global $tables;
			if (preg_match("/^[a-zA-Z0-9_-]{1,}$/i",$table)) {
				if (isset($this->tables[$table]))
					foreach ($this->tables[$table] as $field=>$arr)
						$this->alter($language,$table,$field,$arr["sql_type"]);
			} else {
				foreach ($this->tables as $tbl=>$t)
					foreach ($t as $field=>$arr)
						$this->alter($language,$tbl,$field,$arr["sql_type"]);
			}
			return true;
		}
		
		function getListLanguages() {
			global $db;
			$res=$db->query("select * from `%languages%` order by id_language ASC");
			while ($row=$db->fetch($res)) {
				$languages[$row["ident"]]=$row;
			}
			if (isset($languages)) {
				return $languages;
			} else {
				return false;
			}
		}
		
		function getTemplatesSystem() {
			global $db;
			$templates=array();
			$res=$db->query("select * from %templates%");
			while ($row=$db->fetch($res)) {
				$templates[$row["id_tpl"]]=$row;
			}
			return $templates;
		}
		
		function getSettings() {
			global $db;
			
			$res=$db->query("select * from %settings% LIMIT 0,1");
			if (@mysql_num_rows($res)==1) {
				$row=$db->fetch($res);
				$this->settings=$row;
				return $row;
			} else {
				die("Couldn't not load settings");
			}
		}
		
		function generate_admin_password($str) {
			return md5($this->config["secretkey"].$str);
		}
		
		function checkForAdditionalUser() {
			global $settings;
			global $lang;
			if ($this->checkInstallModule("users")) {
				if (isset($_SESSION["authorized"])) {
				if (isset($_SESSION["login"])) {
					$login=$_SESSION["login"];
				} else {
					$login="";
				}
				if (isset($_SESSION["password"])) {
					$password=$_SESSION["password"];
				} else {
					$password="";
				}
				$usr=new Users();
				$usr->doDb();
				$user=$usr->authModerator($login,$password);
				if (is_array($user)) {
					$user["status"]=2;
					$user["login"]=$login;
					$user["password"]=$password;
					$user["type"]="moderator";
					$_SESSION["authorized"]="yes";
					$_SESSION["login"]=$login;
					$_SESSION["password"]=$password;
						if (isset($_REQUEST["exit"])) {
							if ($_REQUEST["exit"]=="yes") {
								$this->clearAdminSession();
								return false;
							}
						}
				} else {
					return false;
				}
				} else {
					$login=$_REQUEST["login"];
					$password=$this->generate_admin_password($_REQUEST["password"]);
					$usr=new Users();
					$usr->doDb();
					$user=$usr->authModerator($login,$password);
					if (is_array($user)) {
					$user["status"]=2;
					$user["login"]=$login;
					$user["password"]=$password;
					$user["type"]="moderator";
					$_SESSION["authorized"]="yes";
					$_SESSION["login"]=$login;
					$_SESSION["password"]=$password;
					$this->setCongratulation($lang["interface"]["success"],$lang["interface"]["welcome"],3000);
					} else {
						return false;
					}
				}
			} else {
				return false;
			}
			if (isset($user)) {
				return $user;
			} else {
				return false;
			}
		}
		
		function getAdmin() {
		 global $settings;
		 global $lang;
			//получаем текущего пользователя, авторизованы \ нет
			if (isset($_SESSION["authorized"])) {
				//авторизованы
				if (isset($_SESSION["login"])) {
					$login=$_SESSION["login"];
				} else {
					$login="";
				}
				if (isset($_SESSION["password"])) {
					$password=$_SESSION["password"];
				} else {
					$password="";
				}
					if (
					($login==$settings["login"]) &&
					($password==$settings["pass"])
					) {
						//все ок , вошли
						$user["status"]=2;
						$user["login"]=$login;
						$user["password"]=$password;
						$user["type"]="root";
						$_SESSION["authorized"]="yes";
						$_SESSION["login"]=$login;
						$_SESSION["password"]=$password;
						if (isset($_REQUEST["exit"])) 
							if ($_REQUEST["exit"]=="yes") {
								$this->clearAdminSession();
								$user["status"]=0;
								$user["login"]="";
								$user["password"]="";
								$user["type"]="none";
							}
					} else {
						//неправильный логин\пароль
						$additional_user=$this->checkForAdditionalUser();
						if (!is_array($additional_user)) {
						$user["status"]=1;
						$user["login"]=$login;
						$user["password"]="";
						$user["type"]="none";
						$this->clearAdminSession();
						} else {
						$user=$additional_user;
						}
					}
			} else {
				//не авторизованы
				//смотрим послан ли запрос на авторизацию
				if (isset($_REQUEST["login"]) && isset($_REQUEST["password"])) {
					$login=$_REQUEST["login"];
					$password=$this->generate_admin_password($_REQUEST["password"]);
					//послан , проверяем корректность логина
					if (preg_match("/^[a-zA-Z0-9]{1,20}$/i",$login)) {
						//все ок, проверяем пару логин\пароль
						if (
						($login==$settings["login"]) &&
						($password==$settings["pass"])
						) {
						//все ок , вошли
							$user["status"]=2;
							$user["login"]=$login;
							$user["password"]=$password;
							$user["type"]="root";
							$_SESSION["authorized"]="yes";
							$_SESSION["login"]=$login;
							$_SESSION["password"]=$password;
							$this->setCongratulation($lang["interface"]["success"],$lang["interface"]["welcome"],3000);
						} else {
						//неправильный логин\пароль
							$additional_user=$this->checkForAdditionalUser();
							if (!is_array($additional_user)) {
							$user["status"]=1;
							$user["login"]=$login;
							$user["password"]="";
							} else {
							$user=$additional_user;
							}
						}
					} else {
						//неверный логин\пароль
						$user["status"]=1;
						$user["login"]="";
						$user["password"]="";
					}
				} else {
					//не послан, показываем форму
					$user["status"]=0;
					$user["login"]="";
					$user["password"]="";
				}
			}
			return $user;
		}
		
		function clearAdminSession() {
			//очищаем админскую сессию
			unset($_SESSION["authorized"]);
			if (isset($_SESSION["login"]))
				unset($_SESSION["login"]);
			if (isset($_SESSION["password"]))
				unset($_SESSION["password"]);
			return true;
		}
		
		//Получение списка всех модулей
		function getAllModulesSettings() {
			global $config;
			$modules["standart"]=$config["modules"];
			$mods=$this->findModules();
			if (is_array($mods)) {
				foreach ($mods as $mod) {
					if ($mod["installed"])
						$modules["additional"][$mod["name"]]=$mod["caption"];
				}
			}
			return $modules;
		}
		
		//удаление фотки
		function deletePhoto($id_photo) {
			global $db;
			global $config;
			$photo=$this->getImageByID($id_photo);
			if (is_array($photo)) {
			$pth=$config["pathes"]["user_thumbnails"];
			if (is_file($pth.$photo["small_photo"])) {
				@unlink($pth.$photo["small_photo"]);
			}
			
			if (is_file($pth.$photo["preview"])) {
				@unlink($pth.$photo["preview"]);
			}
			
			if (is_file($pth.$photo["medium_photo"])) {
				@unlink($pth.$photo["medium_photo"]);
			}
			
			if (is_file($config["pathes"]["user_image"].$photo["big_photo"])) {
				@unlink($config["pathes"]["user_image"].$photo["big_photo"]);
			}
			
			if ($db->query("delete from %photos% where id_photo=$id_photo")) {
				$db->query("delete from %photos% where big_photo='".sql_quote($photo["big_photo"])."'");
				$db->query("delete from %videos% where preview='".sql_quote($photo["big_photo"])."' or big_preview='".sql_quote($photo["big_photo"])."'");
				$db->query("delete from %audio% where preview='".sql_quote($photo["big_photo"])."' or big_preview='".sql_quote($photo["big_photo"])."'");
				$db->query("delete from %flash% where preview='".sql_quote($photo["big_photo"])."' or big_preview='".sql_quote($photo["big_photo"])."'");
				$db->query("delete from %categories% where preview='".sql_quote($photo["big_photo"])."' or big_preview='".sql_quote($photo["big_photo"])."'");
				return true;
			} else {
				return false;
			}
			} else {
				return false;
			}
		}
		
		//удаление видео
		function deleteVideo($id_video) {
			global $db;
			global $config;
			
			$video=$this->getVideoByID($id_video);
			if (is_array($video)) {
			$pth=$config["pathes"]["user_thumbnails"];
			
			if (is_file($pth.$video["preview"])) {
				@unlink($pth.$video["preview"]);
			}
			
			if (is_file($config["pathes"]["user_video"].$video["filename"])) {
				@unlink($config["pathes"]["user_video"].$video["filename"]);
			}
			
			if ($db->query("delete from %videos% where id_video=$id_video")) {
				$db->query("delete from %videos% where filename='".sql_quote($video["filename"])."'");
				return true;
			} else {
				return false;
			}
			} else {
				return false;
			}
		}
		
		//удаление аудио
		function deleteAudio($id_audio) {
			global $db;
			global $config;
			
			$audio=$this->getAudioByID($id_audio);
			if (is_array($audio)) {
			$pth=$config["pathes"]["user_thumbnails"];
			
			if (is_file($pth.$audio["preview"])) {
				@unlink($pth.$audio["preview"]);
			}
			
			if (is_file($config["pathes"]["user_music"].$audio["filename"])) {
				@unlink($config["pathes"]["user_music"].$audio["filename"]);
			}
			
			if ($db->query("delete from %audio% where id_audio=$id_audio")) {
				$db->query("delete from %audio% where filename='".sql_quote($audio["filename"])."'");
				return true;
			} else {
				return false;
			}
			} else {
				return false;
			}
		}
		
		//удаление flash
		function deleteFlash($id_flash) {
			global $db;
			global $config;
			
			$flash=$this->getFlashByID($id_flash);
			if (is_array($flash)) {
			$pth=$config["pathes"]["user_thumbnails"];
			
			if (is_file($pth.$flash["preview"])) {
				@unlink($pth.$flash["preview"]);
			}
			
			if (is_file($config["pathes"]["user_flash"].$flash["filename"])) {
				@unlink($config["pathes"]["user_flash"].$flash["filename"]);
			}
			
			if ($db->query("delete from %flash% where id_flash=$id_flash")) {
				$db->query("delete from %flash% where filename='".sql_quote($flash["filename"])."'");
				return true;
			} else {
				return false;
			}
			} else {
				return false;
			}
		}
		
		function chck() {
			global $settings;
			global $smarty;
			global $db;
			return $smarty->getInstanceId($this,$db,$settings);
		}
		
		function processModule($mod="",$mode="user") {
		global $smarty;
		global $settings;
		global $lang;
		global $config;
		global $db;
			if ($this->chck()) {
			//подключение административного модуля
			if ($mode=="user") {
				$m_path=$config["pathes"]["modules_dir"];
 $t_path=$config["pathes"]["templates_dir"].$config["templates"]["user_modules"];
			} elseif ($mode=="admin") {
				if ($this->user["type"]=="root") {
					$m_path=$config["pathes"]["admin_modules_dir"];
 					$t_path=$config["pathes"]["templates_dir"].$config["templates"]["admin_modules"];
 				} else {
					if (isset($this->user["permissions"]["standart"][$mod])) {
						$m_path=$config["pathes"]["admin_modules_dir"];
	 					$t_path=$config["pathes"]["templates_dir"].$config["templates"]["admin_modules"];
					} else {
						$smarty->assign("denied",true);
						$this->setAdminTitle($lang["error"]["access_denied"]='Доступ закрыт');
						return $this->errorModuleLoading($mod,$lang["error"]["access_denied"]);
					}
				}
			} else {
				return $this->errorModuleLoading($mod);
			}
			if (preg_match("/^[-a-zA-Z0-9_]{1,}$/i",$mod)) {
			if (
				(is_file($m_path.$mod.".mod.php")) &&
				(is_file($t_path.$mod.".mod.tpl"))
				) {
				$modAction=@$_REQUEST["modAction"];
				include($m_path.$mod.".mod.php");
				$smarty->assign("modAction",$modAction);
				$module["content"]=$smarty->fetch($t_path.$mod.".mod.tpl");
				$module["is_error"]=false;
				$module["ident"]=$mod;
				return $module;
			} else {
				return $this->errorModuleLoading($mod,$lang["error"]["notfind_module_file"]);
			}
			} else {
				return $this->errorModuleLoading($mod,$lang["error"]["eregi_module"]);
			}
			} else {
				return $this->errorModuleLoading($mod,$lang["error"]["eregi_module"]);
			}
		}
		
		function errorModuleLoading($mod="",$description="") {
			global $lang;
			$module["title"]=$mod;
			$module["content"]=$lang["error"]["error_module_loading"];
			$module["help"]=$description;
			$module["is_error"]=true;
			return $module;
		}
		
		function createFCKEditorOLD($name,$content="") {
			global $config;
			if (!defined("include_fck")) {
			include($config["classes"]["fckeditor"]["path"]);
			}
			if (!defined("include_fck")) {
			define("include_fck",true);
			}
			$oFCKeditor = new FCKeditor($name);
			$oFCKeditor->BasePath = $config["classes"]["fckeditor"]["basepath"];
			$oFCKeditor->Value =$content;
			$oFCKeditor->ToolbarSet="Basic";
			$editor=$oFCKeditor->createHTML();
			unset($oFCKeditor);
			return $editor;
		}
		
		function createFCKEditor($name,$content="") {
			global $config;
			global $smarty;
			$smarty->assign("include_mce",true);
			$editor='<textarea id="'.$name.'" name="'.$name.'" style="width:100%;height:400px;" class="mceEditor">'.$content.'</textarea>';
			return $editor;
		}
		
		//проверяем данные из формы и смотрим можно ли продолжать
		function processFormData($frm,$submit_name="",$first=true,$tpl="") {
			global $smarty;
			global $config;
			$form_error=$frm->Compile();
			if (!$first) {
				$smarty->assign("form_error",$form_error);
			} else {
				$form_error=array();
				$instance=rand(1,10000).rand(1,10000);
					while (isset($_SESSION[$instance]))
						$instance=rand(1,10000).rand(1,10000);
				$smarty->assign("form_instance",$instance);
			}
			if (trim($tpl=="")) {
				$tpl=$config["classes"]["form_template_1"];
			}
			if (is_array($form_error)) {
				$smarty->assign("form",$frm->Show());
				$smarty->assign("submit_name",$submit_name);
				$form_html=$smarty->fetch($tpl);
				$smarty->assign("form_html",$form_html);
				return false;
			} else {
				return true;
			}
		}
	
	//Получаем список всех идентификаторов
	function getAllIdents() {
		global $db;
		$idents=array();
			$res=$db->query("select `ident` from `%categories%`");
				while ($row=$db->fetch($res)) $idents[]=$row["ident"];
		return $idents;
	}
	
		//получаем список рубрик для форм добавления и т.п.
	function getRubricsTreeEx(&$item,$id_cat=0,$visible=0,$print=true,$type=0,$root=true,$order="",$desc=false) {
	global $db;
	global $lang;
	global $current_prefix;
	
	if ($visible!=0) {
		$vis=true;
	} else {
		$vis=false;
	}
	switch ($type) {
	case "image":
		$dob=" category_type='image'";
	break;
	case "video":
		$dob=" category_type='video'";
	break;
	case "audio":
		$dob=" category_type='audio'";
	break;
	case "flash":
		$dob=" category_type='flash'";
	break;
	default:$dob="";
	}
	if ($vis) {
	$res=$db->query("select `id_category`,`caption".$this->current_prefix."`,`sort`,`id_sub_category`,`position`,`rss_link` from %categories% where `visible`=1 and (`date_post`<NOW() or `future_post`=0) $dob order by `sort` DESC");
	} else {
	$res=$db->query("select caption".$this->current_prefix.",rss_link,category_type,id_category,id_sub_category,sort,`position` from `%categories%` order by `sort` DESC");
	}
	$items=array();
	if ($root) {
		$item["id"]=0;
		$item["name"]=$lang["interface"]["root_name"];
		$item["sort"]=0;
		$items[0][0]=$item;
	}
		while ($row=$db->fetch($res)) {
			$item["id"]=$row["id_category"];
			$item["name"]=$row["caption".$this->current_prefix];
			$item["sort"]=$row["sort"];
			$items[$row["id_sub_category"]][$row["id_category"]]=$item;
		}
		$new_items=array();
		$this->convertArray(0,$items,$new_items,0,$print);
		unset($items);
		$item=$new_items;
		unset($new_items);
		return true;
	}
	
function convertArray($id_cat=0,$items,&$new_items,$level=0,$print=true) {
global $settings;
	if (isset($items[$id_cat])) {
			//uasort($items[$id_cat],"cmp2");
		$p="";
		for ($k=0;$k<$level;$k++) $p.="- ";
		foreach ($items[$id_cat] as $key=>$item) {
			if ($print)	$item["name"]=$p.$item["name"];
			$item["level"]=$level;
			$new_items[]=$item;
			if ($item["id"]!=0)
				$this->convertArray($item["id"],$items,$new_items,$level+1,$print);
		}
	}
}

function convertArray2($id_cat=0,$items,&$new_items,$level=0,$print=true) {
global $settings;
	if (isset($items[$id_cat])) {
		$p="";
		$size=sizeof($items[$id_cat]);
		for ($k=0;$k<$level;$k++) $p.="- ";
		$n=1;
		foreach ($items[$id_cat] as $key=>$item) {
			if ($print)	$item["caption"]=$p.$item["caption"];
			$item["level"]=$level;
			$item["parent"]=$id_cat;
			$item["categories"]=@sizeof($items[$item["id_category"]]);
			if ($size==$n) {
				$item["is_last"]=true;
			} else {
				$item["is_last"]=false;
			}
			$n++;
			$new_items[$key]=$item;
			if (isset($item["id_category"])) {
			if ($item["id_category"]!=0)
				$this->convertArray2($item["id_category"],$items,$new_items,$level+1,$print);
			}
		}
	}
}
	
	function getAllPositionsRubrics($visible=1) {
		global $config;
		global $db;
		$objects=array();
		$res2=$db->query("select count(*) as cnt,id_category from %photos% group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["photos"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);
		$res2=$db->query("select count(*) as cnt,id_category from %photos% where visible=1 group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["userphotos"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);
		$res2=$db->query("select count(*) as cnt,id_category from %audio% group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["audio"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);
		$res2=$db->query("select count(*) as cnt,id_category from %audio% where visible=1 group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["useraudio"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);	
		$res2=$db->query("select count(*) as cnt,id_category from %video% group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["video"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);
		$res2=$db->query("select count(*) as cnt,id_category from %video% where visible=1 group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["uservideo"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);
		$res2=$db->query("select count(*) as cnt,id_category from %flash% group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["flash"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);
		$res2=$db->query("select count(*) as cnt,id_category from %flash% where visible=1 group by id_category");
		while ($row2=$db->fetch($res2)) {
			$objects["userflash"][$row2["id_category"]]=$row2["cnt"];
		}
		unset($row2);
		$rubrics=array();
		$rubrics=$this->getRubricsTree(0,$visible,false,"",false,"sort",true,'-',$objects);
		unset($objects);
		return $rubrics;
	}
	
	//получаем список рубрик
	function getRubricsTree($id_cat=0,$visible=0,$print=false,$type="",$root=false,$order="",$desc=false,$position=false,$objects=array()) {
	global $db;
	global $lang;
	global $config;
	if ($visible!=0) {
		$vis=true;
	} else {
		$vis=false;
	}
	switch ($type) {
	case "image":
		$dob=" category_type='image'";
	break;
	case "video":
		$dob=" category_type='video'";
	break;
	case "audio":
		$dob=" category_type='audio'";
	break;
	case "flash":
		$dob=" category_type='flash'";
	break;
	default:$dob="";
	}
	
	$ord="";
	switch ($order) {
		case "caption":$ord="order by `caption`";break;
		case "date":$ord="order by `create`";break;
		case "sort":$ord="order by `sort`";break;
		case "id":$ord="order by `ident`";break;
		case "visible":$ord="order by `visible`";break;
		case "type":$ord="order by `category_type`";break;
		case "position":$ord="order by `position`";break;
		default:$ord="order by `sort`";break;
	}
	if (trim($ord)!="") {
		if ($desc) $ord.=" DESC";
	}
	$pos="";
	if (preg_match("/^[a-z]{1,}$/i",$position)) {
		$pos=" and `position`='$position'";
	}
	if ($vis) {
	$res=$db->query("select caption".$this->current_prefix.",rss_link,category_type,`visible`,id_category,id_sub_category,ident,sort,`create`,id_tpl,main_page,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print`,`position`,`rss_link`,`preview`,`big_preview`,`subcontent".$this->current_prefix."` from %categories% where `visible`=1 and (`date_post`<NOW() or `future_post`=0) $dob $pos $ord");
	} else {
	$res=$db->query("select caption".$this->current_prefix.",rss_link,category_type,`visible`,id_category,id_sub_category,ident,sort,`create`,id_tpl,main_page,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print`,`position`,`subcontent".$this->current_prefix."` from %categories% where 1 $dob $pos $ord");
	}
	$items=array();
	if ($root) {
		$item["id"]=0;
		$item["name"]=$lang["interface"]["root_name"];
		$item["sort"]=0;
		$item["photos"]=0;
		$item["userphotos"]=0;
		$item["audio"]=0;
		$item["useraudio"]=0;
		$item["video"]=0;
		$item["uservideo"]=0;
		$item["flash"]=0;
		$item["userflash"]=0;
		$items[0][0]=$item;
	}
		while ($row=$db->fetch($res)) {
			$item=$row;
			$item["url"]=$this->generateCategoryUrl($item["main_page"],$item["ident"]);
			if (isset($config["path"])) 
			if (in_array($item["id_category"],$config["path"])) {
				$item["selected"]=true;
			} else {
				$item["selected"]=false;
			}
			$item["photos"]=@$objects["photos"][$row["id_category"]];
			$item["userphotos"]=@$objects["userphotos"][$row["id_category"]];
			$item["audio"]=@$objects["audio"][$row["id_category"]];
			$item["useraudio"]=@$objects["useraudio"][$row["id_category"]];
			$item["video"]=@$objects["video"][$row["id_category"]];
			$item["uservideo"]=@$objects["uservideo"][$row["id_category"]];
			$item["flash"]=@$objects["flash"][$row["id_category"]];
			$item["userflash"]=@$objects["userflash"][$row["id_category"]];
			if ($position!=false) {
			$items[$row["position"]][$row["id_sub_category"]][$row["id_category"]]=$item;
			} else {
			$items[$row["id_sub_category"]][$row["id_category"]]=$item;
			}
		}
		if ($position!=false) {
		unset($item);
		$item=array();
		foreach ($items as $pos=>$it) {
		$new_items=array();
		$this->convertArray2(0,$it,$new_items,0,$print);
		$item[$pos]=$new_items;
		unset($new_items);
		}
		} else {
		$new_items=array();
		$this->convertArray2(0,$items,$new_items,0,$print);
		unset($items);
		$item=$new_items;
		unset($new_items);
		}
		return $item;
	}	
	
	function getIdent($id_category) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
	 $lng='';
	 if (isset($this->languages[$this->current_language])) {
	 	if ($this->languages[$this->current_language]["default"]==0) {
				$lng=$this->current_language.'/';
		}
	 }
			$rs=$db->query("select `ident` from %categories% where `id_category`=$id_category");
			$rw=$db->fetch($rs);
			if (isset($rw["ident"])) {
				return $lng.$rw["ident"];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function get404Page() {
		global $db;
			$res=$db->query("select * from %categories% where `page404`=1");
			if (mysql_num_rows($res)==1) {
			$category=$db->fetch($res);
			if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
				$category["tags"]=$tgs->getTags($category["id_category"],"category","link");
				unset($tgs);
			}
			$category=$this->getContentCategory($category);	
			$category["url"]=$this->generateCategoryUrl($category["main_page"],$category["ident"]);
			$category["template"]=@$this->getTemplateByID($category["id_tpl"]);
			$category["additional"]=$this->decodeArray(@unserialize($category["properties"]));
				return $category;
			} else {
				return false;
			}
	}
	
	function getMainPage() {
		global $db;
			$res=$db->query("select * from %categories% where main_page=1");
			if (mysql_num_rows($res)==1) {
			$category=$db->fetch($res);
			if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
				$category["tags"]=$tgs->getTags($category["id_category"],"category","link");
				unset($tgs);
			}
			$category=$this->getContentCategory($category);	
			$category["url"]=$this->generateCategoryUrl($category["main_page"],$category["ident"]);
			$category["template"]=@$this->getTemplateByID($category["id_tpl"]);
			$category["additional"]=$this->decodeArray(@unserialize($category["properties"]));
				return $category;
			} else {
				return false;
			}
	}
	
	function getContentCategory($category) {
		global $config;
			if ($category["file_content"]!="") {
			if (is_file($config["pathes"]["user_data"].$category["id_category"].".dat")) {	$category["content"]=@file_get_contents($config["pathes"]["user_data"].$category["id_category"].".dat");
			}
			}
			$search=array("<div>","</div>");
			$replace=array("","");
			$category["content"]=str_replace($search,$replace,$category["content"]);
			$pages=explode("[page]",$category["content"]);
			if (sizeof($pages)==1) {
				$category["content_page"]=$category["content"];
			} else {
				for ($x=0;$x<=sizeof($pages)-1;$x++) {
					$category["pages"][]=$x;
				}
				if (isset($_REQUEST["category_page"])) {
					$current_page=$_REQUEST["category_page"];
					if (!preg_match("/^[0-9]{1,}$/i",$current_page)) $current_page=0;
				} else {
					$current_page=0;
				}
				if (isset($pages[$current_page])) {
					$category["content_page"]=$pages[$current_page];
					$category["current_page"]=$current_page;
				} else {
					$category["content_page"]=$pages[0];
					$category["current_page"]=0;
				}
			}
			$category["sep"]=explode("[sep]",$category["content_page"]);
			return $category;
	}
	
	function highlightCategory($category) {
		global $config;
		if (isset($_REQUEST["highlight"])) {
			$highlight=trim($_REQUEST["highlight"]);
			//$category["caption"]=str_replace($highlight,"<b><font color=\"".$config["highlight"]["caption"]["color"]."\">".$highlight."</font></b>",$category["caption"]);
			$category["content"]=str_replace($highlight,"<b><font color=\"".$config["highlight"]["content"]["color"]."\">".$highlight."</font></b>",$category["content"]);
			if (isset($category["subcontent"]))  {
				$category["subcontent"]=str_replace($highlight,"<b><font color=\"".$config["highlight"]["subcontent"]["color"]."\">".$highlight."</font></b>",$category["subcontent"]);
			}
		}
		return $category;
	}
	
	function getCategoryByID($id_category) {
		global $db;
		global $config;
		if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
			$res=$db->query("select *,DATE_FORMAT(`date_post`,'%d') as `date_day`,DATE_FORMAT(`date_post`,'%m') as `date_month`,DATE_FORMAT(`date_post`,'%Y') as `date_year`,DATE_FORMAT(`date_post`,'%d-%m-%Y') as `date_print` from %categories% where id_category=$id_category");
			$category=$db->fetch($res);
			if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
				$category["tags"]=$tgs->getTags($category["id_category"],"category","link");
				unset($tgs);
			}
			if ($category["file_content"]!="") {
			if (is_file($config["pathes"]["user_data"].$id_category.".dat")) {	$category["content"]=@file_get_contents($config["pathes"]["user_data"].$id_category.".dat");
			}
			}
			$category["url"]=$this->generateCategoryUrl($category["main_page"],$category["ident"]);
			$category["template"]=$this->getTemplateByID($category["id_tpl"]);
			$category["additional"]=$this->decodeArray(@unserialize($category["properties"]));
			$category=$this->highlightCategory($category);
			return $category;
		} else {
			return false;
		}
	}
	
	function getCategoryByIdent($ident) {
		global $db;
		global $rubrics;
		$res=$db->query("select * from %categories% where ident='".sql_quote($ident)."'");
		if (@mysql_num_rows($res)==1) {
			$category=$db->fetch($res);
			$category["template"]=$this->getTemplateByID($category["id_tpl"]);
			
			if (defined("SCRIPTO_tags")) {
				$tgs=new Tags();
				$tgs->doDb();
				$category["tags"]=$tgs->getTags($category["id_category"],"category","link");
				unset($tgs);
			}
			
			$category=$this->highlightCategory($category);
			$category["url"]=$this->generateCategoryUrl($category["main_page"],$category["ident"]);
			$category["additional"]=$this->decodeArray(@unserialize($category["properties"]));
			return $category;
		} else {
			return false;
		}
	}
	
	function getPageInfo(&$page) {
		global $rubrics;
			if (isset($rubrics[$page["position"]][$page["id_category"]]["categories"])) {
				$page["categories"]=$rubrics[$page["position"]][$page["id_category"]]["categories"];
			} else {
				$page["categories"]=0;
			}
			if (isset($rubrics[$page["position"]][$page["id_category"]]["level"])) {
				$page["level"]=$rubrics[$page["position"]][$page["id_category"]]["level"];
			}	
			return true;
	}
	
	function generateCategoryUrl($main_page=false,$ident="",$use_lang=true) {
	global $config;
	 $lng='';
	 if (isset($this->languages[$this->current_language])) {
	 	if ($this->languages[$this->current_language]["default"]==0) {
			if ($use_lang)
				$lng=$this->current_language.'/';
		}
	 }
	 if ($main_page==false) {
	 	$l=strlen($ident);
	 	if ($l>0) {
		unset($l);
	 	if (substr($ident,strlen($ident)-1,1)=='/') {
			return $config["http"]["root"].$lng.$ident;
		} else {
			return $config["http"]["root"].$lng.$ident."/";
		}
		} else {
			return $config["http"]["root"].$lng;
		}
	 } else {
		return $config["http"]["root"].$lng;
	 }
	}
	
	function deleteCategory($id_cat) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
			$res=$db->query("select id_category from %categories% where id_sub_category=$id_cat");
			while ($row=$db->fetch($res)) {
				$this->deleteCategory($row["id_category"]);
			}
			$this->deleteContentFile($id_cat,'');
		  		if (defined("SCRIPTO_tags")) {
					$tgs=new Tags();
					$tgs->doDb();
					$tgs->deleteTags($id_cat,'category');
					unset($tgs);
				}
			$db->query("delete from %categories% where id_category=$id_cat");
			return true;
		} else {
			return false;
		}
	}
	
	function rubricExist($value="",$mode=0) {
		global $db;
		switch ($mode) {
			case 0:
				if ($db->getCount("select * from %categories% where ident='".sql_quote($value)."'")>0) {
					return true;
				} else {
					return false;
				}
			break;
			case 1:
				if ($db->getCount("select * from %categories% where id_category=$value")>0) {
					return true;
				} else {
					return false;
				}
			break;
			default:
				return false;
		}
	}
	
	//генерируем уникальный идентификатор
	function generateIdent() {
		$ident=rand(1,1000).date("dmYHis").rand(1,1000);
		if ($this->rubricExist($ident)) {
			$ident=$this->generateIdent();
		}
		return $ident;
	}
	
	function updateContent($id=0) {
		global $config;
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id)) {
			$db->query("update %categories% set `content`='',`file_content`='".$id.".dat' where id_category=$id");
			return true;
		} else {
			return false;
		}
	}
	
	function getContentTypes() {
		global $config;
		$types=array();
		foreach ($config["content_type"] as $cont) {
			$type["name"]=$cont["name"];
			$type["id"]=$cont["ident"];
			$types[]=$type;
		}
		return $types;
	}
	
	function deleteContentFile($id,$data="") {
		global $config;
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id)) {
			if (is_file($config["pathes"]["user_data"].$id.".dat")) {
				$content=file_get_contents($config["pathes"]["user_data"].$id.".dat");
				if (@unlink($config["pathes"]["user_data"].$id.".dat")) {
					$db->query("update %categories% set `content`='".sql_quote($data)."',`file_content`='' where id_category=$id");
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		return false;
	}

	function setContentFileEx($id,$data,$folder) {
		global $config;
		if (preg_match("/^[0-9]{1,}$/i",$id)) {
			if(version_compare(PHP_VERSION, '5.3.0', '<'))
				set_magic_quotes_runtime(1);
			if (!is_dir($config["pathes"]["user_data"].$folder)) {
				@mkdir($config["pathes"]["user_data"].$folder);
				@chmod($config["pathes"]["user_data"].$folder,0777);
			}
			$fp=@fopen($config["pathes"]["user_data"].$folder."/".$id.".dat","w+");
			@fwrite($fp,$data);
			@fclose($fp);
			if(version_compare(PHP_VERSION, '5.3.0', '<'))
				set_magic_quotes_runtime(0);
			return true;
		}
		return false;
	}
	
	function setContentFile($id,$data) {
		global $config;
		if (preg_match("/^[0-9]{1,}$/i",$id)) {
			@set_magic_quotes_runtime(1);
			$fp=fopen($config["pathes"]["user_data"].$id.".dat","w+");
			fwrite($fp,$data);
			fclose($fp);
			@set_magic_quotes_runtime(0);
			return true;
		}
		return false;
	}
	
	function encodeArray($properties) {
		if (is_array($properties)) {
			foreach ($properties as $id=>$value) {
				if (is_array($value)) {
					$properties[$id]=$this->encodeArray($value);
				} else {
					$properties[$id]=base64_encode($value);
				}
			}
			return $properties;
		} else {
			return base64_encode($properties);
		}
	}
	
	function decodeArray($properties) {
		if (is_array($properties)) {
			foreach ($properties as $id=>$value) {
				if (is_array($value)) {
					$properties[$id]=$this->encodeArray($value);
				} else {
					$properties[$id]=base64_decode($value);
				}
			}
			return $properties;
		} else {
			return base64_decode($properties);
		}
	}
	
	function addCategory($id_cat=0,$title="",$content_type="text",$ident="",$visible=1,$page404=0,$titletag="",$metatag="",$metakeywords="",$rss_link="",$content="",$subcontent="",$id_tpl=0,$position="up",$date_news,$future_post=1,$preview_width=0,$preview_height=0,$in_navigation=1,$is_registered=0,$category_small='',$category_middle='',$properties='',$lang_values=array()) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$preview_width)) $preview_width=0;
		if (!preg_match("/^[0-9]{1,}$/i",$preview_height)) $preview_height=0;
		if (is_array($properties)) {
			$properties=$this->encodeArray($properties);
			$prop_sql=serialize($properties);
		} else {
			$prop_sql='';
		}
		if ($db->query("
		insert into %categories% values (null,$id_cat,'".sql_quote($title)."','".sql_quote($content)."','".sql_quote($subcontent)."','".sql_quote($titletag)."','".sql_quote($metatag)."','".sql_quote($metakeywords)."','".sql_quote($content_type)."','".sql_quote($rss_link)."','".strtolower(sql_quote($ident))."',$visible,$page404,0,'".date('Y-m-d H:i:s')."',$id_tpl,0,'".sql_quote($position)."','','".sql_quote($category_small)."','".sql_quote($category_middle)."',$future_post,'".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."',$preview_width,$preview_height,$in_navigation,$is_registered,'".sql_quote($prop_sql)."'".$this->generateInsertSQL("categories",$lang_values).")
		")) {
			return mysql_insert_id();
		} else {
			echo "insert into %categories% values (null,$id_cat,'".sql_quote($title)."','".sql_quote($content)."','".sql_quote($subcontent)."','".sql_quote($titletag)."','".sql_quote($metatag)."','".sql_quote($metakeywords)."','".sql_quote($content_type)."','".sql_quote($rss_link)."','".strtolower(sql_quote($ident))."',$visible,$page404,0,'".date('Y-m-d H:i:s')."',$id_tpl,0,'".sql_quote($position)."','','".sql_quote($category_small)."','".sql_quote($category_middle)."',$future_post,'".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."',$preview_width,$preview_height,$in_navigation,$is_registered,'".sql_quote($prop_sql)."'".$this->generateInsertSQL("categories",$lang_values).")";
			return false;
		}
	 }
	 
	 //получаем идентификатор родителя
	 function getIdentByParent($id_cat=0) {
	  global $db;
	 	if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
		 	$res=$db->query("select ident from %categories% where id_category=$id_cat");
			if (@mysql_num_rows($res)==1) {
				$row=$db->fetch($res);
				return noSlash($row["ident"]);
			} else {
				return $this->generateIdent();
			}
		} else {
			return $this->generateIdent();
		}
	 }
	 
	 function insertNewObject($caption="",$filename="",$type="",$extension="") {
	 	global $db;
		if ($db->query("
		insert into %objects% values (null,'','".sql_quote($filename)."','".sql_quote($caption)."','','".sql_quote($type)."',null)
		")) {
			$id_object=mysql_insert_id();
			if ($type=="image") {
				$thumb=$this->createSystemThumbnail(mysql_insert_id(),$filename,"objects");
			}
			return $id_object;
		} else {
			return false;
		}
	 }
	 
	 function getAllObjects($type="") {
	 	global $db;
		$dop="";
		if ($type!="") {
			$dop="where `format`='".sql_quote($type)."'";
		}
		$objects=array();
		$res=$db->query("select *,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print` from %objects% $dop order by `create`");
			while ($row=$db->fetch($res)) {
				$objects[$row["format"]][]=$row;
			}
		return $objects;
	 }
	 
	 function getAllObjectsCount($type="") {
	 	global $db;
		$dop="";
		if ($type!="") {
			$dop="where `format`='".sql_quote($type)."'";
		}
		$res=$db->query("select id_object from %objects% $dop order by `create`");
		return @mysql_num_rows($res);
	 }	 
	 
	 /*получаем N элементов в рубрике*/
	 function getElementsByCat(&$rubrics,$params,$num=1) {
	 global $db;
	 	if (!preg_match("/^[0-9]{1,}$/i",$num)) $num=1;
		foreach ($params as $pos=>$param) {
			foreach ($param as $type=>$p) {
				$n=0;
				$str="";
				foreach ($p as $key=>$object) {
					if ($n==0) {
					$str.="where id_category=$object";
					} else {
					$str.=" or id_category=$object";
					}
					$n++;
				}
				if ($n>0) {
					switch ($type) {
						case "image":
							$res=$db->query("select * from %photos% $str group by id_category");
						break;
						case "video":
							$res=$db->query("select * from %videos% $str group by id_category");
						break;
						case "audio":
							$res=$db->query("select * from %audio% $str group by id_category");
						break;
						case "flash":
							$res=$db->query("select * from %flash% $str group by id_category");
						break;
					}
					if (isset($res))
					while ($row=$db->fetch($res)) {
					 if ($num==1) {
						$rubrics[$pos][$row["id_category"]]["object"]=$row;
					 } else {
						$rubrics[$pos][$row["id_category"]]["object"][]=$row;
					 }
					}
				}
			}
		}
		return true;
	 }
	 
	 function getObjectsByCat($id_cat=0,$visible=0,$type="",$main=false) {
	  if ((preg_match("/^[0-9]{1,}$/i",$id_cat)) && (preg_match("/^[0-9]{1,}$/i",$visible))) {
		$objects=array();
		switch ($type) {
			case "image":
				$objects["image"]=$this->getImagesByCat($id_cat,$visible,$main);
			break;
			case "video":
				$objects["video"]=$this->getVideoByCat($id_cat,$visible,$main);
			break;
			case "music":
				$objects["music"]=$this->getAudioByCat($id_cat,$visible,$main);
			break;
			case "flash":
				$objects["flash"]=$this->getFlashByCat($id_cat,$visible,$main);
			break;
			default:
				$objects["image"]=$this->getImagesByCat($id_cat,$visible,$main);
				$objects["video"]=$this->getVideoByCat($id_cat,$visible,$main);
				$objects["music"]=$this->getAudioByCat($id_cat,$visible,$main);
				$objects["flash"]=$this->getFlashByCat($id_cat,$visible,$main);
		}
		return $objects;
	  } else {
	  	return false;
	  }
	 }
	 
	 function getImageByID($id_photo=0) {
	 	global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_photo)) {
		
		$res=$db->query("select * from `%photos%` where id_photo=$id_photo");
		$row=$db->fetch($res);
		if (is_array($row)) {
			$row["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			return $row;
		} else {
			return false;
		}
		} else {
			return false;
		}
	 }
	 
	 function getVideoByID($id_video=0) {
	 	global $db;
		
		if (preg_match("/^[0-9]{1,}$/i",$id_video)) {
		$res=$db->query("select * from `%videos%` where id_video=$id_video");
		$row=$db->fetch($res);
		if (is_array($row)) {
			$row["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			return $row;
		} else {
			return false;
		}
		} else {
			return false;
		}
	 }
	 
	 function getAudioByID($id_audio=0) {
	 	global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_audio)) {
		
		$res=$db->query("select * from `%audio%` where id_audio=$id_audio");
		
		$row=$db->fetch($res);
		if (is_array($row)) {
			$row["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			return $row;
		} else {
			return false;
		}
		
		} else {
			return false;
		}
	 }
	 
	 function getFlashByID($id_flash=0) {
	 	global $db;
		
		if (preg_match("/^[0-9]{1,}$/i",$id_flash)) {
		
		$res=$db->query("select * from `%flash%` where id_flash=$id_flash");
		
		$row=$db->fetch($res);
		if (is_array($row)) {
			$row["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			return $row;
		} else {
			return false;
		}
		
		} else {
			return false;
		}
	 }
	 
	 function generateURLByIdCategory($id_cat=0) {
	 	global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
			$res=$db->query("select `ident`,`caption`,`main_page` from %categories% where id_category=$id_cat");
			$row=$db->fetch($res);
			$ob["url"]=$this->generateCategoryUrl($row["main_page"],$row["ident"]);;
			$ob["caption"]=$row["caption"];
			return $ob;
		} else {
			return "";
		}
	 }
	 
	 function getCountImagesByCat($id_cat=0,$visible=0,$main=false) {
	 	global $db;
		$dob="";
		$dob2="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_photo`=1";
		$res=$db->query("select `id_photo` from `%photos%` where (id_category=$id_cat $dob2)  $dob");
		return @mysql_num_rows($res);
	 }
	 
	 function getCountVideoByCat($id_cat=0,$visible=0,$main=false) {
	 	global $db;
		$dob="";
		$dob2="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_video`=1";
		$res=$db->query("select `id_video` from `%videos%` where (id_category=$id_cat $dob2)  $dob");
		return @mysql_num_rows($res);
	 }
	 
	 function getCountAudioByCat($id_cat=0,$visible=0,$main=false) {
	 	global $db;
		$dob="";
		$dob2="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_audio`=1";
		$res=$db->query("select `id_audio` from `%audio%` where (id_category=$id_cat $dob2)  $dob");
		return @mysql_num_rows($res);
	 }
	 
	 function getCountFlashByCat($id_cat=0,$visible=0,$main=false) {
	 	global $db;
		$dob="";
		$dob2="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_flash`=1";
		$res=$db->query("select `id_flash` from `%flash%` where (id_category=$id_cat $dob2)  $dob");
		return @mysql_num_rows($res);
	 }	 
	 
	 function getCountMediaByCat($id_cat=0,$visible=0,$main=false) {
	 	global $db;
		$dob="";
		$dob2="";
		if ($visible) $dob=" and (`%photos%`.visible=1 and `%videos%`.visible=1 and `%audio%`.visible=1 and `%flash%`.visible=1)";
		if ($main) $dob2=" or (`%photos%`.main_photo=1 and `%videos%`.main_video=1 and `%audio%`.main_audio=1 and `%flash%`.main_flash=1)";
		$res=$db->query("select `%photos%`.id_category,`%videos%`.id_category,`%audio%`.id_category,`%flash%`.id_category from `%photos%`,`%videos%`,`%audio%`,`%flash%` where ((`%photos%`.id_category=$id_cat and `%videos%`.id_category=$id_cat and `%audio%`.id_category=$id_cat and `%flash%`.id_category=$id_cat) $dob2)  $dob");
		return @mysql_num_rows($res);
	 }	 
	 
	 function getMediaByCat($id_cat=0,$visible=0,$main=false,$pg=false,$onpage=0) {
	 	global $db;
		$dob="";
		$dob2="";
		$dob3="";
		if ($visible) $dob=" and (`%photos%`.visible=1 and `%videos%`.visible=1 and `%audio%`.visible=1 and `%flash%`.visible=1)";
		if ($main) $dob2=" or (`%photos%`.main_photo=1 and `%videos%`.main_video=1 and `%audio%`.main_audio=1 and `%flash%`.main_flash=1)";
		if (
		preg_match("/^[0-9]{1,}$/i",$pg) &&
		preg_match("/^[0-9]{1,}$/i",$onpage)
		) {
		$dob3=" LIMIT ".($pg*$onpage).",$onpage";
		}
		$res=$db->query("select * from `%photos%`,`%videos%`,`%audio%`,`%flash%` where ((`%photos%`.id_category=$id_cat and `%videos%`.id_category=$id_cat and `%audio%`.id_category=$id_cat and `%flash%`.id_category=$id_cat) $dob2)  $dob $dob3");
		$objects=array();
		if ($main==false) {
			$rubric_url=$this->generateURLByIdCategory($id_cat);
		}
		while ($row=$db->fetch($res)) {
			$object=$row;
			if ($main==true) {
			$object["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			} else {
			$object["rubric"]=$rubric_url;
			}
			if (isset($object["id_photo"])) {
				$objects["image"][]=$object;
			}
			if (isset($object["id_video"])) {
				$objects["video"][]=$object;
			}
			if (isset($object["id_audio"])) {
				$objects["audio"][]=$object;
			}
			if (isset($object["id_flash"])) {
				$objects["flash"][]=$object;
			}
		}
		if (is_array($objects)) {
			return $objects;
		} else {
			return false;
		}
	 }	 
	 
	 function getImagesByCat($id_cat=0,$visible=0,$main=false,$pg=false,$onpage=0) {
	 	global $db;
		$dob="";
		$dob2="";
		$dob3="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_photo`=1";
		if (
		preg_match("/^[0-9]{1,}$/i",$pg) &&
		preg_match("/^[0-9]{1,}$/i",$onpage)
		) {
		$dob3=" LIMIT ".($pg*$onpage).",$onpage";
		}
		$res=$db->query("select * from `%photos%` where (id_category=$id_cat $dob2)  $dob $dob3");
		$objects=array();
		if ($main==false) {
			$rubric_url=$this->generateURLByIdCategory($id_cat);
		}
		while ($row=$db->fetch($res)) {
			$object=$row;
			if ($main==true) {
			$object["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			} else {
			$object["rubric"]=$rubric_url;
			}
			$objects[]=$object;
		}
		if (is_array($objects)) {
			return $objects;
		} else {
			return false;
		}
	 }
	 
	 function getVideoByCat($id_cat=0,$visible=0,$main=false,$pg=false,$onpage=0) {
	 	global $db;
		$dob="";
		$dob2="";
		$dob3="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_video`=1";
		if (
		preg_match("/^[0-9]{1,}$/i",$pg) &&
		preg_match("/^[0-9]{1,}$/i",$onpage)
		) {
		$dob3=" LIMIT ".($pg*$onpage).",$onpage";
		}
		$res=$db->query("select * from `%videos%` where (id_category=$id_cat $dob2) $dob $dob3");
		$objects=array();
		if ($main==false) {
			$rubric_url=$this->generateURLByIdCategory($id_cat);
		}
		while ($row=$db->fetch($res)) {
			$object=$row;
			if ($main==true) {
			$object["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			} else {
			$object["rubric"]=$rubric_url;
			}
			$objects[]=$object;
		}
		if (is_array($objects)) {
			return $objects;
		} else {
			return false;
		}
	 }
	 
	 function getAudioByCat($id_cat=0,$visible=0,$main=false,$pg=false,$onpage=0) {
	 	global $db;
		$dob="";
		$dob2="";
		$dob3="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_audio`=1";
		if (
		preg_match("/^[0-9]{1,}$/i",$pg) &&
		preg_match("/^[0-9]{1,}$/i",$onpage)
		) {
		$dob3=" LIMIT ".($pg*$onpage).",$onpage";
		}
		$res=$db->query("select * from `%audio%` where (id_category=$id_cat $dob2) $dob $dob3");
		$objects=array();
		if ($main==false) {
			$rubric_url=$this->generateURLByIdCategory($id_cat);
		}		
		while ($row=$db->fetch($res)) {
			$object=$row;
			if ($main==true) {
			$object["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			} else {
			$object["rubric"]=$rubric_url;
			}
			$objects[]=$object;
		}
		if (is_array($objects)) {
			return $objects;
		} else {
			return false;
		}
	 }	 
	 
	 function getFlashByCat($id_cat=0,$visible=0,$main=false,$pg=false,$onpage=0) {
	 	global $db;
		$dob="";
		$dob2="";
		$dob3="";
		if ($visible) $dob=" and visible=1";
		if ($main) $dob2=" or `main_flash`=1";
		if (
		preg_match("/^[0-9]{1,}$/i",$pg) &&
		preg_match("/^[0-9]{1,}$/i",$onpage)
		) {
		$dob3=" LIMIT ".($pg*$onpage).",$onpage";
		}
		$res=$db->query("select * from `%flash%` where (id_category=$id_cat $dob2) $dob $dob3");
		$objects=array();
		if ($main==false) {
			$rubric_url=$this->generateURLByIdCategory($id_cat);
		}		
		while ($row=$db->fetch($res)) {
			$object=$row;
			if ($main==true) {
			$object["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			} else {
			$object["rubric"]=$rubric_url;
			}
			$objects[]=$object;
		}
		if (is_array($objects)) {
			return $objects;
		} else {
			return false;
		}
	 }	 
	 
	 function getFileTypeEx($ext) {
	 	global $config;
		$type="";
		if (in_array($ext,$config["images_types"])) $type="image";
		if (in_array($ext,$config["video_types"])) $type="video";
		if (in_array($ext,$config["flash_types"])) $type="flash";
		if (in_array($ext,$config["music_types"])) $type="music";
		return $type; 
	 }	 
	 
	 function getFileType($filename) {
	 	global $config;
		$type="";
		$file_ext=getFileExt($filename);
		if (in_array($file_ext,$config["images_types"])) $type="image";
		if (in_array($file_ext,$config["video_types"])) $type="video";
		if (in_array($file_ext,$config["flash_types"])) $type="flash";
		if (in_array($file_ext,$config["music_types"])) $type="music";
		return $type; 
	 }
	 
	 function getFullPath($filename="") {
	 	global $config;
		$type=$this->getFileType($filename);
		switch ($type) {
			case "image":
				$upload_path=$config["pathes"]["user_image"].$filename;
			break;
			case "video":
				$upload_path=$config["pathes"]["user_video"].$filename;
			break;
			case "flash":
				$upload_path=$config["pathes"]["user_flash"].$filename;
			break;
			case "music":
				$upload_path=$config["pathes"]["user_music"].$filename;
			break;
		}
		return $upload_path;
	 }
	 
	 function getObjectByID($id_object) {
	 	global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$res=$db->query("select *,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print` from %objects% where id_object=$id_object");
			if (mysql_num_rows($res)==1) {
				$row=$db->fetch($res);
				$row["fullpath"]=$this->getFullPath($row["filename"]);
				return $row;
			} else {
				return false;
			}
		} else {
			return false;
		}
	 }	
	 
	 //успешное выполнение чего либо
	 function setSessionCongratulation($caption="",$description="",$timeout=10000,$redirect=false,$url="") {
	 	$message["caption"]=$caption;
		$message["description"]=$description;
		$message["timeout"]=$timeout;		
		$message["redirect"]=$redirect;
		$message["url"]=$url;
		$this->session_congratulation[]=$message;	 
		return true;
	 }
	 
	 function setCongratulation($caption="",$description="",$timeout=10000,$redirect=false,$url="") {
	 	$message["caption"]=$caption;
		$message["description"]=$description;
		$message["timeout"]=$timeout;		
		$message["redirect"]=$redirect;
		$message["url"]=$url;
		$this->congratulation[]=$message;
		return true;
	 }
	 
	 function assignCongratulation() {
	  global $smarty;
	  	if (isset($_SESSION["congratulation"]))
	  	if (is_array($_SESSION["congratulation"])) {
	  		foreach ($_SESSION["congratulation"] as $congrat) {
	  			$this->congratulation[]=$congrat;
	  		}
	  		unset($_SESSION["congratulation"]);
	  	}
	  	$_SESSION["congratulation"]=$this->session_congratulation;
	 	$smarty->assign("congratulation",$this->congratulation);
	 }
	 
	 //копируем объект в фотку\видео и т.п.
	 function copyObject($id_object,$id_cat=0,$create_thumb=false,$small_width=0,$small_height=0) {
	 global $db;
	 global $settings;
	 global $config;
	 	if ((preg_match("/^[0-9]{1,}$/i",$id_object)) && (preg_match("/^[0-9]{1,}$/i",$id_cat))) {
			$object=$this->getObjectByID($id_object);
			if (is_array($object)) {
				switch ($object["format"]) {
					case "image":
						//картинка
						if ($db->query("insert into `%photos%` values (null,$id_cat,'".sql_quote($object["preview"])."','".sql_quote($object["filename"])."','','','".$object["caption"]."','','','',0,1,'','','')")) {
				$sql="";
				$id_photo=mysql_insert_id();
				if ($object["preview"]=="") {
				$thumb=$this->createSystemThumbnail($id_photo,$object["filename"],"image");
				$sql.=" ,preview='$thumb'";
				}
				if ($create_thumb) {	$thumb_middle=$this->createThumbnail($object["filename"],$settings["medium_x"],$settings["medium_y"],false,100,"middle");
if ($small_width==0 || !preg_match("/^[0-9]{1,}$/i",$small_width)) {
	$small_width=$settings["small_x"];
}
if ($small_height==0|| !preg_match("/^[0-9]{1,}$/i",$small_height)) {
	$small_height=$settings["small_y"];
}
$thumb_small=$this->createThumbnail($object["filename"],$small_width,$small_height,false,100,"small");
				if ($thumb_middle && $thumb_small) 
					$db->query("update %photos% set `medium_photo`='$thumb_middle',`small_photo`='$thumb_small' $sql where id_photo=$id_photo");
				
			} else {
				if (@$thumb && $object["preview"]=="") 
					$db->query("update %photos% set `preview`='$thumb' where id_photo=$id_photo");
			}
			
							return $id_photo;
						} else {
							echo mysql_error();
							return false;
						}
					break;
					case "video":
						//видео
						if ($db->query("insert into `%videos%` values (null,$id_cat,'','".sql_quote($object["filename"])."','".$object["caption"]."','','','',0,1,'','','','')")) {
							return true;
						} else {
							return false;
						}
					break;
					case "music":
						//аудио
						/*получаем теги*/
						if (function_exists("id3_get_tag")) {
						$tag = @id3_get_tag($config["pathes"]["user_music"].$object["filename"]);
						/*конец получения тегов*/
						$label='';
						$genre='';
						$caption=$object["caption"];
						if (isset($tag["composer"])) $label=$tag["composer"];
						if (isset($tag["genre"])) $genre=$tag["genre"];
						if (isset($tag["artist"])) {
							$caption=$tag["artist"];
							if (isset($tag["album"])) {
								$caption.=' - '.$tag["album"];
								if (isset($tag["title"])) {
									$caption.=' - '.$tag["title"];
								}
							}
						} else {
							if (isset($tag["title"])) {
								$caption=$tag["title"];
							}
						}
						} else {
							$caption=$object["caption"];
							$label='';
							$genre='';
						}
						if ($db->query("insert into `%audio%` values (null,$id_cat,'','".sql_quote($object["filename"])."','".$caption."','','','',0,1,'','".sql_quote($label)."','','".sql_quote($genre)."','')")) {
							return true;
						} else {
							return false;
						}
					break;
					case "flash":
						//флеш
						if ($db->query("insert into `%flash%` values (null,$id_cat,'','".sql_quote($object["filename"])."','".$object["caption"]."','','','',0,1,'','')")) {
							return true;
						} else {
							return false;
						}
					break;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	 }

	 function deleteObject($id_object=0) {
	  global $db;
	 	if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			if ($db->query("delete from %objects% where id_object=$id_object")) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	 }
	 
    function get_phpversion()
    {
        $v = phpversion();
        $version = Array();

        foreach(explode('.', $v) as $bit)
        {
            if(is_numeric($bit))
            {
                $version[] = $bit;
            }
        }
		if (isset($version[0])) {
        return $version[0];
		} else {
		return false;
		}
    }
	 
	function loadThumbnail($filename="") {
	 global $config;
		if ($this->get_phpversion()==4) {
			include_once($config["classes"]["thumbnail"]["php4"]);
		} else {
			include_once($config["classes"]["thumbnail"]["php4"]);
		}
		$thumb=new Thumbnail($filename);
		return $thumb;
	}
	 
	function createThumbnail($file,$width=32,$height=32,$effect=false,$quality=100,$thumb_folder="thumbnails") {
		global $config;
		if (is_file($config["pathes"]["user_image"].$file)) {
		$thumb=$this->loadThumbnail($config["pathes"]["user_image"].$file);
		$thumb->resize($width,$height);
		$thumb->maxWidth=$width;
		$thumb->maxHeight=$height;
		if ($effect) {
		$thumb->createReflection(40,ceil($height/2),80,false);
		}
		if (is_writable($config["pathes"]["user_thumbnails"].$thumb_folder)) {	$thumb->save($config["pathes"]["user_thumbnails"].$thumb_folder."/th_".$file,$quality);
		return $thumb_folder."/th_".$file;
		} else {
		return false;
		}
		}
	}
	 
	 //создаем системный thumbnail
	 function createSystemThumbnail($id_image=0,$filename="",$mode="image") {
	 	global $db;
		global $config;
		$file=$config["pathes"]["user_image"].$filename;
		if (preg_match("/^[0-9]{1,}$/i",$id_image)) {
			if (is_file($file)) {	$thumbnail=$this->createThumbnail($filename,$config["system_images"]["width"],$config["system_images"]["height"],true,100,"system");
				if ($thumbnail) {
				switch ($mode) {
				case "image":
				$db->query("update %photos% set `preview`='$thumbnail' where id_photo=$id_image");
				break;
				case "objects":
				$db->query("update %objects% set `preview`='$thumbnail' where id_object=$id_image");
				break;
				}
				return $thumbnail;
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
	 
	 //получить все шаблоны для вставки в форму
	 function getTemplatesEx($tpl_type="") {
		if (preg_match("/^[a-zA-Z]{1,}$/i",$tpl_type)) {
			$tpls=array();
			foreach ($this->templates as $key=>$template) {
				if ($template["tpl_type"]==$tpl_type) {
					$tpl["id"]=$template["id_tpl"];
					$tpl["name"]="[".strtoupper($template["tpl_theme"])."] ".$template["tpl_caption"];
					$tpls[]=$tpl;
				}
			}
			return $tpls;
		} else {
			return false;
		}
	 }
	 
	 //Получить все шаблоны из базы
	 function getTemplatesByType($tpl_type="") {
	 	global $db;
		if (preg_match("/^[a-zA-Z]{1,}$/i",$tpl_type)) {
			$res=$db->query("select * from `%templates%` where `tpl_type`='$tpl_type'");
			$tpls=array();
			while ($tpl=$db->fetch($res)) {
$tpl["path"]=$tpl["tpl_theme"]."/".$tpl["tpl_type"]."/".$tpl["tpl_name"];
					$tpls[]=$tpl;
			}
			return $tpls;
		} else {
			return false;
		}
	 }
	 
	 //Получить все шаблоны
	 function getTemplates($tpl_type="") {
		if (preg_match("/^[a-zA-Z]{1,}$/i",$tpl_type)) {
			$tpls=array();
			foreach ($this->templates as $key=>$template) {
				if ($template["tpl_type"]==$tpl_type) {
					$tpl=$template;
$tpl["path"]=$tpl["tpl_theme"]."/".$tpl["tpl_type"]."/".$tpl["tpl_name"];
					$tpls[]=$tpl;
				}
			}
			return $tpls;
		} else {
			return false;
		}
	 }
	 
	function getTemplateByID($id_tpl=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_tpl)) {
			if (isset($this->templates[$id_tpl])) {
				$template=$this->templates[$id_tpl];
$template["path"]=$template["tpl_theme"]."/".$template["tpl_type"]."/".$template["tpl_name"];
				return $template;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	 
	function getMenuTypesEx() {
		global $config;
		
		$types=array();
		
		foreach ($config["menu_type"] as $key=>$typ) {
			$type["id"]=$key;
			$type["name"]=$typ;
			$types[]=$type;
		}
		
		return $types;
		
	}
	 
	function getPath($id=0) {
	global $db;
	global $config;
	global $lang;
	global $smarty;
	$pid=1;
	$j=0;
	$main=false;
	while ($pid!=0) {
		$res=$db->Query("select caption".$this->current_prefix.",id_category,rss_link,ident,id_sub_category,main_page,in_navigation,position from %categories% where `id_category`=$id");
		$path[$j]=$db->fetch($res);
		$path[$j]["url"]=$this->generateCategoryUrl($path[$j]["main_page"],$path[$j]["ident"]);
		$path[$j]["is_last"]=false;
		$id=$path[$j]["id_sub_category"];
		$pid=$id;
		if ($path[$j]["main_page"]) {
			$main=true;
			$path[$j]["caption"]=$lang["mainpage"];
		}
		$j++;
	}
	if (isset($path)) {
		
		if ($main==false) {
			$j++;
			$res=$db->Query("select caption".$this->current_prefix.",id_category,rss_link,ident,id_sub_category,main_page,in_navigation,position from %categories% where `main_page`=1");
			if (@mysql_num_rows($res)==1) {
			$path[$j]=$db->fetch($res);
			$path[$j]["caption"]=$path[$j]["caption".$this->current_prefix];
			$path[$j]["url"]=$this->generateCategoryUrl($path[$j]["main_page"],$path[$j]["ident"]);
			$path[$j]["caption"]=$lang["mainpage"];
			}
		}
		
		if (isset($path[0])) $path[0]["is_last"]=true;
		$path=array_reverse($path);
		return $path;
	} else {
		return false;
	}
	}
	
	function getPathInfo($path) {
		global $rubrics;
		foreach ($path as $key=>$pth) {
		if (isset($rubrics[$pth["position"]][$pth["id_category"]]["categories"]))
			$path[$key]["categories"]=$rubrics[$pth["position"]][$pth["id_category"]]["categories"];
		}
		return $path;
	}
	
	function addSubPath($caption,$url) {
		if ($caption!='' && $url!='') {
			$pth=array();
			$pth["caption"]=$caption;
			$pth["url"]=$url;
			$pth["in_navigation"]=true;
			$pth["is_last"]=false;
			$pth["is_object"]=true;
			$this->dop_path[]=$pth;
			unset($pth);
			return true;
		} else {
			return false;
		}
	}
	
	function getSubPath($path) {
		if (is_array($this->dop_path)) {
		$size=sizeof($this->dop_path);
			if ($size>0) {
				foreach ($path as $key=>$pth) {
					$path[$key]["is_last"]=false;
				}
				foreach ($this->dop_path as $pth) {
					if (isset($pth["caption"]) && isset($pth["url"])) {
						$path[]=$pth;
					}
				}
				$size=sizeof($path);
				if (isset($path[$size-1]))
					$path[$size-1]["is_last"]=true;
			}
		}
		return $path;
	}
	
	function getRealPath($path) {
	if (is_array($path)) {
		$real_path=array();
		foreach ($path as $pth) {
			if ($pth["in_navigation"]) {
				$real_path[]=$pth;
			}
		}
		return $real_path;
	} else {
		return false;
	}
	}
	
	function getContentByPage($page) {
		global $db;
		global $config;
		global $settings;
		if (isset($config["content_type"][$page["category_type"]])) {
				$content=$this->getContentByType($page["category_type"],$page);
				return $content;
		} else {
			return "";
		}
	}
	
	function getContentByType($type,$page) {
		global $db;
		global $config;
		global $smarty;
		global $settings;
		
		$content="";
		$pth1=$config["pathes"]["user_processor_dir"];
	$pth2=$config["pathes"]["templates_dir"].$config["templates"]["user_processor"].$page["template"]["tpl_theme"]."/";
		if (isset($config["content_type"][$page["category_type"]])) {
			$c=$config["content_type"][$page["category_type"]];
			if (
			is_file($pth1.$c["client_module"]) &&
			is_file($pth2.$c["template_client"])
			) {
				$smarty->assign("page",$page);
				include($pth1.$c["client_module"]);
				return $content;
			}
		} else {
			return "";
		}
	}
	
	function setFullMode() {
		global $smarty;
		$smarty->assign("preview_full",true);
	}
	
	//получаем список языков
	function getAllLanguages() {
		global $config;
		$languages=array();
		if ($handle = opendir($config["pathes"]["lang_dir"])) {
	    /* Именно этот способ чтения элементов каталога является правильным. */
		    while (false !== ($file = readdir($handle))) {
			 if ($file!="." && $file!="..")
        		if (is_dir($config["pathes"]["lang_dir"].$file)) 
				 if (is_file($config["pathes"]["lang_dir"].$file."/lang.info.php")) 
				  include($config["pathes"]["lang_dir"].$file."/lang.info.php");
		    }
			closedir($handle);
			return $languages;
		} else {
			return false;
		}
	}
	
	//получить список всех типов блоков
	function getBlockTypes() {
		global $db;
		
		$res=$db->query("select * from %block_types%");
		
		while ($row=$db->fetch($res)) {
			$types[$row["id_type"]]=$row;
		}
		if (isset($types)) {
			return $types;
		} else {
			return false;
		}
	}
	
	//получаем список всех типов блоков для формы
	function getBlockTypesEx() {
		global $db;
		
		$res=$db->query("select id_type,caption from %block_types%");
		
		while ($row=$db->fetch($res)) {
			$types[$row["id_type"]]["name"]=$row["caption"];
			$types[$row["id_type"]]["id"]=$row["id_type"];
		}
		if (isset($types)) {
			return $types;
		} else {
			return false;
		}
	}	
	
	//проверяем существование блока
	function blockExist($ident="") {
		global $db;
		if (preg_match("/^[a-zA-Z0-9]{2,255}$/i",$ident)) {
			if ($db->getCount("select id_block from %blocks% where ident='$ident'")>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	//функция добавления нового блока
	function addBlock($id_cat=0,$caption="",$content="",$id_type=0,$ident="",$visible=1,$show_mode=0,$id_tpl=0,$number=0,$sql='') {
		global $db;
		if ($db->query("
		insert into %blocks% values (null,$id_cat,'".sql_quote($caption)."','".sql_quote($content)."',$id_type,'".sql_quote($ident)."',$visible,$show_mode,$id_tpl,$number,'".date('Y-m-d H:i:s')."',0".$sql.")
		")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	//получаем блок по идентификатору
	function getBlockByID($id_block,$mode="id") {
		global $db;
		if (preg_match("/^[0-9a-zA-Z]{1,}$/i",$id_block)) {
		switch($mode) {
			case "id":
				$res=$db->query("select * from `%blocks%` where id_block=$id_block");
			break;
			case "ident":
				$res=$db->query("select * from `%blocks%` where ident='".sql_quote($id_block)."'");
			break;
		}
			return $db->fetch($res);
		} else {
			return false;
		}
	}
	
	//Получаем блоки
	function getAllBlocks($visible=true,$sort=false) {
		global $db;
		$vis="";
		if ($visible) $vis=" and `visible`=1";
		
		$types=$this->getBlockTypes();
		
		$res=$db->query("select *,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print` from %blocks% where 1 $vis order by `sort` DESC");
		$n=0;
		while ($row=$db->fetch($res)) {
			$row["type_name"]=@$types[$row["id_type"]]["caption"];
			$row["type"]=@$types[$row["id_type"]];
			if ($sort) {
				$blocks[$n]=$row;
				$n++;
			} else {
				$blocks[$row["ident"]]=$row;
			}
		}
		
		if (isset($blocks)) {
			return $blocks;
		} else {
			return false;
		}
	}

	function deleteBlock($id_block) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$block=$this->getBlockByID($id_block);
			$types=$this->getBlockTypes();
			$block["type"]=@$types[$block["id_type"]];
			if (preg_match("/^[a-zA-Z0-9_]{1,}$/i",$block["type"]["module"])) {
				$mod_=$this->getModule($block["type"]["module"]);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==true) {
						if (method_exists($m,"deleteBlockAdmin")) {
							$m->deleteBlockAdmin($id_block);
						}
					}
				}
			}
			$db->query("delete from %blocks% where id_block=$id_block");
			$db->query("delete from %block_text% where id_block=$id_block");
			$db->query("delete from %block_rss% where id_block=$id_block");
			return true;
		} else {
			return false;
		}
	}
	
	function getAllTextFromBlock($id_block,$number=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$dob=" order by id_text DESC";
			if ($number>0) {
				$dob=" order by id_text DESC LIMIT 0,$number";
			}
			$res=$db->query("select * from %block_text% where id_block=$id_block $dob");
			while ($row=$db->fetch($res)) {
				$texts[]=$row;
			}
			if (isset($texts)) {
				return $texts;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function GetRandomTexts($id_block,$number=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$res=$db->query("select * from %block_text% where id_block=$id_block order by rand() LIMIT 0,$number");
			while ($row=$db->fetch($res)) {
				$texts[]=$row;
			}
			if (isset($texts)) {
				return $texts;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getBlocks($mode="allblocks",$page,$types) {
		global $db;
		switch ($mode) {
			case "allblocks":
				$rb=$db->query("select *,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print` from %blocks% where visible=1 and show_mode=0 order by `sort` DESC");
			break;
			case "mainpage":
				if ($page["main_page"]) {
				$rb=$db->query("select *,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print` from %blocks% where show_mode=0 and visible=1 order by `sort` DESC");
				} else {
					return false;
				}
			break;
			case "page":
				$rb=$db->query("select *,DATE_FORMAT(`create`,'%d-%m-%Y') as `create_print` from %blocks% where show_mode=1 and visible=1 and id_category=".$page["id_category"]." order by `sort` DESC");
			break;
			default: return false;
		}
		while ($rw=@$db->fetch($rb)) {
			$rw["type_name"]=@$types[$rw["id_type"]]["caption"];
			$rw["type"]=@$types[$rw["id_type"]];
			$user_content=$this->doBlock($rw,$page);
			$rw["user_content"]=$user_content;
			$rw["user_content"]["caption"]=$rw["caption"];
			$rw["user_content"]["type"]=$rw["type"];
			$blocks[$rw["ident"]]=$rw["user_content"];
		}
		@mysql_close($rb);
		if (isset($blocks)) {
			return $blocks;
		} else {
			return false;
		}
		
	}
	
	//Удаляем все кеш файлы всех блоков определенного языка
	function clearCacheBlocksLang($lang='') {
		global $config;
		if (!preg_match('/^[a-zA-Z0-9]{1,}$/i',$lang)) return false;
		$blocks=$this->getAllBlocks(false);
		$lng='_'.$lang;
		foreach ($blocks as $bl) {
			$filename=$config["pathes"]["cache_dir_blocks"].$bl["ident"].$lng.".cache";
			if (is_file($filename)) {
				@unlink($filename);
			}
		}
		return true;
	}
	
	//Удаляем кеш файлы определенного блока
	function clearCacheBlock($ident) {
		global $config;
		if (!preg_match('/^[a-zA-Z0-9]{1,}$/i',$ident)) return false;
		foreach ($this->languages as $lng) {
		if ($lng["default"]==1) {
		$filename=$config["pathes"]["cache_dir_blocks"].$ident.".cache";
		} else {
		$filename=$config["pathes"]["cache_dir_blocks"].$ident.'_'.$lng["ident"].".cache";
		}
			if (is_file($filename)) {
				@unlink($filename);
			}
		}
			return true;
	}
	
	//Удаляем кеш файлы блоков модуля
	function clearCacheBlocks($mod_name='') {
		global $db;
		global $config;
		if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name)) {
			$res=$db->query("select id_type from `%block_types%` where `module`='".sql_quote($mod_name)."'");
			while ($type=$db->fetch($res)) {
				$res2=$db->query("select `ident` from `%blocks%` where id_type=".$type["id_type"]);
				while ($block=$db->fetch($res2)) {
					$this->clearCacheBlock($block["ident"]);
				}
			}
			return true;
		} else {
			return false;
		}
	}
	
	//обрабатываем содержимое блока
	function doBlock($block,$page) {
		global $config;
		global $db;
		global $smarty;
		$smarty->assign("module_block","");
		$user_content=array();
		$objects=array();
		if ($block["type"]["module"]!='') {
			$smarty->assign("block",$block);
			if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$block["type"]["module"])) {
			$filename=$config["pathes"]["cache_dir_blocks"].$block["ident"].$this->current_prefix.".cache";
			if (is_file($filename) && $config["debug_mode"]==false && $block["type"]["cache"]==1) {
				//кеш существует
				$user_content["objects"]=null;
				$user_content["content"]=@file_get_contents($filename);
			} else {
				//кеш не существует
			$mod=$this->getModule($block["type"]["module"]);
			$smarty->assign("module",$mod);
			if ($mod) {
				$m=$this->includeModule($mod);
				$module_block=$m->doBlock($block,$page,$user_content["objects"]);
				$smarty->assign("module_block",$module_block);
$template=$this->getTemplateByID($block["id_tpl"]);
$user_content["content"]=$smarty->fetch($config["templates"]["themes"].$template["path"]);
@file_put_contents($filename,$user_content["content"]);
			} else {
			$user_content["objects"]=null;
			$user_content["content"]=$block["content"];
			}
			}
			} else {
			$user_content["objects"]=null;
			$user_content["content"]=$block["content"];
			}
			
		} else {
		$template=$this->getTemplateByID($block["id_tpl"]);
		
		switch ($block["type"]["type"]) {
			case "texts":
				$objects=$this->getAllTextFromBlock($block["id_block"],$block["number_objects"]);
			break;
			case "random":
				$objects=$this->GetRandomTexts($block["id_block"],$block["number_objects"]);
			break;
			case "rss":
				$objects=$this->getRSSByBlock($block["id_block"],$block["number_objects"]);
			break;
			case "random_photo":
				$categories_block=$this->getCategoriesByBlock($block["id_block"]);
				if(sizeof($categories_block)>0 && is_array($categories_block)) {
					$sql="where ";
					foreach ($categories_block as $key=>$cat) {
						$sql.="`id_category`=$cat";
						if (isset($categories_block[$key+1])) {
							$sql.=" or ";
						}
					}
					$objects=$this->GetRandomImages($block["number_objects"],$sql);
				} else {
					$objects=$this->GetRandomImages($block["number_objects"]);
				}
			break;
			case "random_video":
				$objects=$this->GetRandomVideos($block["number_objects"]);
			break;
			case "random_audio":
				$objects=$this->GetRandomAudio($block["number_objects"]);
			break;
		}
		$smarty->assign("block_objects",$objects);
		$smarty->assign("block",$block);
		if (is_file($config["pathes"]["templates_dir"].$config["templates"]["themes"].$template["path"])) {
			$user_content["objects"]=$objects;
			$user_content["content"]=$smarty->fetch($config["templates"]["themes"].$template["path"]);
		} else {
			$user_content["objects"]=null;
			$user_content["content"]=$block["content"];
		}
		}
		return $user_content;
	}
	
	/*RSS blocks*/
	function getAllRSSFromBlock($id_block,$number=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$dob=" order by id_rss DESC";
			if ($number>0) {
				$dob=" order by id_rss DESC LIMIT 0,$number";
			}
			$res=$db->query("select * from %block_rss% where id_block=$id_block $dob");
			while ($row=$db->fetch($res)) {
				$texts[]=$row;
			}
			if (isset($texts)) {
				return $texts;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	
	function getRSSByBlock($id_block,$number=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_block)) {
			$dob=" order by id_rss DESC";
			if ($number>0) {
				$dob=" order by id_rss DESC LIMIT 0,$number";
			}
			$res=$db->query("select * from %block_rss% where id_block=$id_block $dob");
			while ($row=$db->fetch($res)) {
				$row["links"]=$this->parseRSS($row["rss_link"],$row["rss_number"]);
				$texts[]=$row;
			}
			if (isset($texts)) {
				return $texts;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function parseRSS($url="",$number=0) {
		global $config;
		require_once $config["classes"]["rss"];
		$rss =new xmlParser($url,$number);

		$arr=$rss->content;
		for ($i=0;$i<=sizeof($arr)-1;$i++) {
			$arr[$i]["title"]=charset_x_win(strip_tags(@$arr[$i]["title"]));
			$arr[$i]["link"]=strip_tags(@$arr[$i]["link"]);
			$arr[$i]["w3_link"]=urlencode(strip_tags(@$arr[$i]["link"]));
			if (isset($arr[$i]["description"])) 
			$arr[$i]["description"]=charset_x_win($arr[$i]["description"]);
		}
		unset($rss);
		if (preg_match("/^[0-9]{1,}$/i",$number)) {
			if ($number>0) {
			return array_slice($arr,0,$number);
			} else {
			return $arr;
			}
		} else {
			return $arr;
		}
	}
	
	/*функции по работе с дополнительными модулями*/
	
	//ищем модули
	function installModule($name="") {
		global $config;
		if (
		$this->checkModuleExist($name)
		) {
			$modules=@file_get_contents($config["pathes"]["usermodules_install"]);
			$modules_array=explode(";",$modules);
			$modules_array[]=$name;
			$modules_string=implode(";",$modules_array);
			file_put_contents($config["pathes"]["usermodules_install"],$modules_string);
			return true;
		} else {
			return false;
		}
	}
	
	function uninstallModule($name="") {
		global $config;
			$modules=@file_get_contents($config["pathes"]["usermodules_install"]);
			$modules_array=explode(";",$modules);
			$modules_array_new=array();
			if (is_array($modules_array))
			foreach ($modules_array as $mod) {
				if ($mod!=$name) {
					$modules_array_new[]=$mod;
				}
			}
			$modules_string=implode(";",$modules_array_new);
			file_put_contents($config["pathes"]["usermodules_install"],$modules_string);
	}
	
	function checkInstallModule($name="") {
		global $config;
		if (
		$this->checkModuleExist($name)
		) {
			$modules=@file_get_contents($config["pathes"]["usermodules_install"]);
			$modules_array=explode(";",$modules);
			if (in_array($name,$modules_array)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	
	function addModuleToCategory($mod_name,$id_cat) {
		global $db;
		if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name) && preg_match("/^[0-9]{1,}$/i",$id_cat)) {
			$db->query("delete from %categories_module% where `name_module`='$mod_name' and `id_category`=$id_cat");
			$db->query("insert into %categories_module% values('$mod_name',$id_cat)");
			return true;
		} else {
			return false;
		}
	}
	
	function getInstallModulesFast() {
		global $config;
			$modules=@file_get_contents($config["pathes"]["usermodules_install"]);
			$modules_array=explode(";",$modules);
			unset($modules);
			foreach ($modules_array as $m) {
				$modules_arr[]=$this->getModule($m);
			}
			if (isset($modules_arr)) return $modules_arr;
			return false;
	}
	
	function loadModules($install=false) {
		global $db;
		global $config;
		
		$subdirs=$this->getSubDirs($config["pathes"]["usermodules"]);
		if (is_array($subdirs))
		foreach($subdirs as $mod) {
			$module=$this->getModule($mod);
			if($module) {
				//проверяем существует модуль или нет
				if ($install) {
					$md=$this->includeModule($module);
					if ($mod->checkMe()) {
						$modules[$mod]=$module;
					}
				} else {
					$md=$this->includeModuleEx($module);
					$modules[$mod]=$module;
				}
				unset($mod);
			}
		}
		$hash = md5(trim(file_get_contents($config["classes"]["smarty"])));
		$h="8956ce024a0b7faadfe373b22b37a890";
		if ($h!=$hash) die(base64_decode("Y29ycnVwdA=="));
		if (isset($modules)) {
			$this->modules=$modules;
			return $modules;
		} else {
			return false;
		}
	}
		
	function findModules() {
		global $db;
		global $config;
		
		$subdirs=$this->getSubDirs($config["pathes"]["usermodules"]);
		
		if (is_array($subdirs))
		foreach($subdirs as $mod) {
			$module=$this->getModule($mod);
			if($module) {
				$mod=$this->includeModule($module);
				//проверяем существует модуль или нет
				$module["installed"]=$mod->checkMe();
				$modules[]=$module;
				unset($mod);
			}
		}
		if (isset($modules)) {
			return $modules;
		} else {
			return false;
		}
	}
	
	function includeModuleEx($modl) {
		global $config;
		global $smarty;
		global $settings;
		global $lang;
		global $db;
		global $page;
		
		if (is_array($modl)) {
		require_once($modl["path"]."module.class.php");
		return true;
		} else {
		return false;
		}
	}
	
	function includeModule($modl) {
		global $config;
		global $smarty;
		global $settings;
		global $lang;
		global $db;
		global $page;
		global $rubrics;
		
		if (is_array($modl)) {
		require_once($modl["path"]."module.class.php");
eval("
if (!isset($".$modl["name"].")) {
\$mod=new ".$modl["name"]."();
}
");
		eval("\$mod=new ".$modl["name"]."();");
		if ($this->current_prefix!='') {
			if (is_file($modl["path"]."module".$this->current_prefix.".lang.php")) {
				include($modl["path"]."module".$this->current_prefix.".lang.php");
			} else {
			 if (is_file($modl["path"]."module.lang.php")) {
				include($modl["path"]."module.lang.php");
			 }
			}
		} else {
			if (is_file($modl["path"]."module.lang.php")) {
				include($modl["path"]."module.lang.php");
			}
		}
		$smarty->assign("lang",$lang);
		$mod->config=$config;
		$mod->smarty=$smarty;
		$mod->settings=$settings;
		$mod->lang=$lang;
		$mod->db=$db;
		$mod->thismodule=$modl;
		$mod->page=$page;
		$mod->engine=$this;
		$mod->rubrics=$rubrics;
		$mod->doDb();
		if (isset($mod->thismodule["tables"]))
			if (is_array($mod->thismodule["tables"]))
				$this->assignTables($mod->thismodule["tables"]);
		$smarty->assign("thismodule",$modl);
		return $mod;
		unset($mod);
		} else {
		return false;
		}
	}
	
	function checkModuleExist($name) {
		global $config;
		
		chdir($config["pathes"]["core_dir"]);
		if (
		file_exists($config["pathes"]["usermodules_fast"].$name."/module.class.php") &&
		file_exists($config["pathes"]["usermodules_fast"].$name."/install.php") &&
		file_exists($config["pathes"]["usermodules_fast"].$name."/uninstall.php") &&
file_exists($config["pathes"]["templates_dir"].$config["templates"]["usermodules_templates"].$name."/user_module.tpl.html") &&
file_exists($config["pathes"]["templates_dir"].$config["templates"]["usermodules_templates"].$name."/admin_module.tpl.html") &&
		file_exists($config["pathes"]["usermodules_fast"].$name."/user_module.mod.php") &&
		file_exists($config["pathes"]["usermodules_fast"].$name."/admin_module.mod.php")
		) {
			return true;
		} else {
			return false;
		}
		
	}
	
	//получаем модуль
	function getModule($name) {
		global $db;
		global $config;
		if (
		$this->checkModuleExist($name)
		) {
			include($config["pathes"]["usermodules"].$name."/description.mod.php");
			if (isset($moduleinfo)) {
				//$moduleinfo["moduleinfo"]=$moduleinfo;
				$moduleinfo["name"]=$name;
				$moduleinfo["path"]=$config["pathes"]["usermodules"].$name."/";
				$moduleinfo["http_path"]=$config["pathes"]["http_usermodules"].$name."/";
				$moduleinfo["template_path"]=$config["templates"]["usermodules_templates"].$name."/";
				return $moduleinfo;
			} else {
				return false;
			}
		
		} else {
			return false;
		}
	}
	
	//смотрим поддиректории
	function getSubDirs($path="") {
	 if (is_dir($path))
      if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
		if (is_dir($path.$file) && ($file!=".") && ($file!="..") ) 
		 $entries[] = $file;
		}
        closedir($handle);
      }	else {
	  	return false;
	  }
	  if (isset($entries)) {
	  	return $entries;
	  } else {
	  	return false;
	  }
	}
	
	//смотрим поддиректории
	function getFilesByDir($path="") {
	 if (is_dir($path))
      if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
		if (is_file($path.$file) && ($file!=".") && ($file!="..") ) 
		 $entries[] = $file;
		}
        closedir($handle);
      }	else {
	  	return false;
	  }
	  if (isset($entries)) {
	  	return $entries;
	  } else {
	  	return false;
	  }
	}	
	
	//получаем список рубрик к которым подключен модуль
	function getCategoriesByModule($name) {
		global $db;
		
		if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$name)) {
		$rs=$db->query("select `id_category` from %categories_module% where `name_module`='$name'");
		$cat_modules=array();
			while ($rw=$db->fetch($rs)) {
				$cat_modules[]=$rw["id_category"];
			}
			return $cat_modules;
		} else {
			return false;
		}
		
	}
	
	function getStaticModules($page) {
		global $db;
		global $smarty;
		$res=$db->query("select * from %static_modules% order by `prioritet`");
		$smarty->assign("page",$page);
		while ($row=$db->fetch($res)) {
				$mod_=$this->getModule($row["mod_name"]);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==true) {
						$mod_["content"]=$m->doStatic();
						$modules[$row["mod_name"]]=$mod_;
						//$smarty->assign("module_content",$module_content);
					}
				}
		}
		
		if (isset($modules)) {
			return $modules;
		} else {
			return false;
		}
		
	}	
	
	function getModulesByCategory($id_category,$page) {
		global $db;
		global $smarty;
		$res=$db->query("select * from %categories_module% where id_category=$id_category");
		$smarty->assign("page",$page);
		while ($row=$db->fetch($res)) {
				$mod_=$this->getModule($row["name_module"]);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==true) {
						$mod_["content"]=$m->doUser();
						$modules[$row["name_module"]]=$mod_;
						//$smarty->assign("module_content",$module_content);
					}
				}
		}
		
		if (isset($modules)) {
			return $modules;
		} else {
			return false;
		}
		
	}
	
	function getModuleFullViewUrl($mod_name="") {
		global $db;
		$url="";
		if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name)) {
		$module_cats=$this->getCategoriesByModule($mod_name);
		if (isset($module_cats[0])) {
			$id_cat=$module_cats[0];
			if (isset($this->urls[$id_cat])) {
				$url2=$this->generateCategoryUrl(false,$this->urls[$id_cat],false);
			} else {
				$url2=$this->generateCategoryUrl(false,$this->getIdent($id_cat),false);
			}
		}
			if (isset($url2)) {
			return $url2;
			} else {
			return '';
			}
		} else {
			return "";
		}
	}
	
	/*конец функций по работе с дополнительными модулями*/
	
	//функция получения IP адреса
	function getIp()
    {
        global $REMOTE_ADDR;
        global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
        global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
        global $HTTP_SERVER_VARS, $HTTP_ENV_VARS;
        // Get some server/environment variables values
        if (empty($REMOTE_ADDR)) {
            if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
            }
            else if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $HTTP_SERVER_VARS['REMOTE_ADDR'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $HTTP_ENV_VARS['REMOTE_ADDR'];
            }
            else if (@getenv('REMOTE_ADDR')) {
                $REMOTE_ADDR = getenv('REMOTE_ADDR');
            }
        } // end if
        if (empty($HTTP_X_FORWARDED_FOR)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'];
            }
            else if (@getenv('HTTP_X_FORWARDED_FOR')) {
                $HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
            }
        } // end if
        if (empty($HTTP_X_FORWARDED)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $HTTP_SERVER_VARS['HTTP_X_FORWARDED'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $HTTP_ENV_VARS['HTTP_X_FORWARDED'];
            }
            else if (@getenv('HTTP_X_FORWARDED')) {
                $HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
            }
        } // end if
        if (empty($HTTP_FORWARDED_FOR)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_FORWARDED_FOR'];
            }
            else if (@getenv('HTTP_FORWARDED_FOR')) {
                $HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
            }
        } // end if
        if (empty($HTTP_FORWARDED)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $HTTP_SERVER_VARS['HTTP_FORWARDED'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $HTTP_ENV_VARS['HTTP_FORWARDED'];
            }
            else if (@getenv('HTTP_FORWARDED')) {
                $HTTP_FORWARDED = getenv('HTTP_FORWARDED');
            }
        } // end if
        if (empty($HTTP_VIA)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
                $HTTP_VIA = $_SERVER['HTTP_VIA'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
                $HTTP_VIA = $_ENV['HTTP_VIA'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_VIA'])) {
                $HTTP_VIA = $HTTP_SERVER_VARS['HTTP_VIA'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_VIA'])) {
                $HTTP_VIA = $HTTP_ENV_VARS['HTTP_VIA'];
            }
            else if (@getenv('HTTP_VIA')) {
                $HTTP_VIA = getenv('HTTP_VIA');
            }
        } // end if
        if (empty($HTTP_X_COMING_FROM)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $HTTP_SERVER_VARS['HTTP_X_COMING_FROM'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $HTTP_ENV_VARS['HTTP_X_COMING_FROM'];
            }
            else if (@getenv('HTTP_X_COMING_FROM')) {
                $HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
            }
        } // end if
        if (empty($HTTP_COMING_FROM)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
            }
            else if (!empty($HTTP_COMING_FROM) && isset($HTTP_SERVER_VARS['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $HTTP_SERVER_VARS['HTTP_COMING_FROM'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $HTTP_ENV_VARS['HTTP_COMING_FROM'];
            }
            else if (@getenv('HTTP_COMING_FROM')) {
                $HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
            }
        } // end if

        // Gets the default ip sent by the user
        if (!empty($REMOTE_ADDR)) {
            $direct_ip = $REMOTE_ADDR;
        }

        // Gets the proxy ip sent by the user
        $proxy_ip     = '';
        if (!empty($HTTP_X_FORWARDED_FOR)) {
            $proxy_ip = $HTTP_X_FORWARDED_FOR;
        } else if (!empty($HTTP_X_FORWARDED)) {
            $proxy_ip = $HTTP_X_FORWARDED;
        } else if (!empty($HTTP_FORWARDED_FOR)) {
            $proxy_ip = $HTTP_FORWARDED_FOR;
        } else if (!empty($HTTP_FORWARDED)) {
            $proxy_ip = $HTTP_FORWARDED;
        } else if (!empty($HTTP_VIA)) {
            $proxy_ip = $HTTP_VIA;
        } else if (!empty($HTTP_X_COMING_FROM)) {
            $proxy_ip = $HTTP_X_COMING_FROM;
        } else if (!empty($HTTP_COMING_FROM)) {
            $proxy_ip = $HTTP_COMING_FROM;
        } // end if... else if...
        // Returns the true IP if it has been found, else FALSE
        if (empty($proxy_ip)) {
            // True IP without proxy
            return $direct_ip;
        } else {
            $is_ip = ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}', $proxy_ip, $regs);
            if ($is_ip && (count($regs) > 0)) {
                // True IP behind a proxy
                return $regs[0];
            } else {
                // Can't define IP: there is a proxy but we don't have
                // information about the true IP
                return FALSE;
            }
        } // end if... else...
    } 	
	
	//ищем свой домен
	function findDomen($str) {
		global $config;
		$str=strtolower($str);
		if (strpos($str,$_SERVER["HTTP_HOST"])===false) {
			return false;
		} else {
			return true;
		}
	}
	
	 function getRandomImages($number=0,$sql="") {
	 	global $db;
		if (preg_match("/^[0-9]{1,}$/i",$number)) {
		
		$res=$db->query("select * from `%photos%` $sql order by rand() LIMIT 0,$number");
		$n=0;
		while ($row=$db->fetch($res)) {
			//$row["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			$images[$n]=$row;
			$n++;
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
	
	 function getRandomVideos($number=0) {
	 	global $db;
		if (preg_match("/^[0-9]{1,}$/i",$number)) {
		
		$res=$db->query("select * from `%videos%` order by rand() LIMIT 0,$number");
		$n=0;
		while ($row=$db->fetch($res)) {
			$row["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			$videos[$n]=$row;
			$n++;
		}
		if (is_array($videos)) {
			return $videos;
		} else {
			return false;
		}
		} else {
			return false;
		}
	 }

	 function getRandomAudio($number=0) {
	 	global $db;
		if (preg_match("/^[0-9]{1,}$/i",$number)) {
		
		$res=$db->query("select * from `%audio%` order by rand() LIMIT 0,$number");
		$n=0;
		while ($row=$db->fetch($res)) {
			$row["rubric"]=$this->generateURLByIdCategory($row["id_category"]);
			$audio[$n]=$row;
			$n++;
		}
		if (is_array($audio)) {
			return $audio;
		} else {
			return false;
		}
		} else {
			return false;
		}
	 }	
	 
	 //Внутренние пути для модулей
	 function addPath($caption='',$url='',$active=true) {
 		$pth["caption"]=strip_tags($caption);
 		$pth["url"]=strip_tags($url);	 
	 	if ($active) {
			$pth["active"]=true;
	 	} else {
	 		$pth["active"]=false;
	 	}
	 	$this->vn_path[]=$pth;
	 	return true;
	 }
	 
	 function assignPath() {
	 	global $smarty;
	 	$smarty->assign("module_path",$this->vn_path);
	 }
	 
	 function clearPath() {
	 	global $smarty;
	 	unset($this->vn_path);
	 	$smarty->assign("module_path",false);
	 }
	 
	 function setAdminTitle($title) {
	 	global $smarty;
	 	$smarty->assign("admin_title",$title);
	 }
	 
	 function stripContent($content) {
		global $config;
	 	$content=$this->strip_only($content,$config["denied_tags"],false);
 	 	$content=$this->strip_only($content,$config["cut_tags"],true);	
 	 	return $content;
	 }
	 
	 function setPreview($img,$id,$tooltip,$mode='img') {
	 	global $smarty;
		global $config;
		global $lang;
		if ($img=='') {
			$smarty->assign('img',$lang["lang"]["not_set"]);
		} else {
			if ($mode=='img') {
			$smarty->assign('img','<a href="'.$config["http"]["root"].'admin/?module=objects&modAction=crop&filename_photo='.$img.'&ajax=true" class="crop" border="0"><img src="'.$config["pathes"]["user_thumbnails_http"].$img.'" border="0" width="100"></a>');
			} else {
			$smarty->assign('img',$img);
			}
		}
		$smarty->assign("elementID",$id);
		$smarty->assign("closeFancybox",true);
		$smarty->assign("fancyTooltip",$tooltip);
	 }
	 
	 //Получение ID изображения по имени превью файла
	 function getPhotoIdByFilename($filename) {
	 	global $db;
		$res=$db->query("select `id_photo` from %photos% where medium_photo='".sql_quote($filename)."' or small_photo='".sql_quote($filename)."'");
		if (@mysql_num_rows($res)>0) {
			$row=$db->fetch($res);
			return $row["id_photo"];
		} else {
			return false;
		}
	 }
	 
	 function strip_only($str, $tags, $stripContent = false) {
	 //функция очистки содержимого от ненужных тегов
    $content = '';
    if(!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if(end($tags) == '') array_pop($tags);
    }
    foreach($tags as $tag) {
        if ($stripContent)
             $content = '(.+</'.$tag.'[^>]*>|)';
         $str = preg_replace('#</?'.$tag.'[^>]*>'.$content.'#is', '', $str);
    }
    return $str;
	} 
	 
	function checkRegistered($content) {
		global $config;
		if (defined("SCRIPTO_users")) {
			//Модуль пользователи установлен
			if (isset($_SESSION["auth"])) {
				if ($_SESSION["auth"]==true) {
				//авторизированы
	$content=$this->smartReplace($config["registered_tags"],$content,$config["replace_for_registered"]);
				} else {
				//нет
	$content=$this->smartReplace($config["registered_tags"],$content,$config["replace_for_not_registered"]);
				}
			} else {
				//нет
	$content=$this->smartReplace($config["registered_tags"],$content,$config["replace_for_not_registered"]);
			}
		} else {
			//Модуль пользователи не установлен
$content=$this->smartReplace($config["registered_tags"],$content,$config["replace_for_not_users"]);
		}
		return $content;
	}
	 
	//Замена тегов
	function smartReplace($tags,$content,$replace) {
		if (is_array($tags)) {
			foreach ($tags as $tag) {
				$content=preg_replace("/\[".$tag."\](.*?)\[\/".$tag."\]/is", $replace, $content); 
			}
			return $content;
		} else {
			return $content;
		}
	}
	 
	 /*работа с напоминаниями*/
	 function addReminder($subject='',$text='',$date_reminder=array(),$time_reminder=array(),$undelete) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$undelete)) return false;
		if ($db->query("insert into `%reminders%` values(null,'".sql_quote($subject)."','".sql_quote($text)."','".sql_quote(@$date_reminder[2])."-".sql_quote(@$date_reminder[1])."-".sql_quote(@$date_reminder[0])." ".sql_quote(@$time_reminder[0]).":".sql_quote(@$time_reminder[1]).":00',$undelete,0)")) {
			return @mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function getReminderByID($id_reminder=false) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_reminder)) return false;
		$res=$db->query("select *,DATE_FORMAT(`show_date`,'%d-%m-%Y') as `date_print`,DATE_FORMAT(`show_date`,'%H:%i') as `time_print`,DATE_FORMAT(`show_date`,'%e') as `date_day`,DATE_FORMAT(`show_date`,'%c') as `date_month`,DATE_FORMAT(`show_date`,'%Y') as `date_year`,DATE_FORMAT(`show_date`,'%k') as `time_hour`,DATE_FORMAT(`show_date`,'%i') as `time_minute` from `%reminders%` where id_reminder=$id_reminder");
		return $db->fetch($res);
	}
	
	function getReminders($new=false) {
		global $db;
		$new_sql='';
		if ($new) {
			$new_sql=" where `show_date`<NOW() and `show`=0 ";
		}
		$res=$db->query("select *,DATE_FORMAT(`show_date`,'%d-%m-%Y') as `date_print`,DATE_FORMAT(`show_date`,'%H:%i') as `time_print`,DATE_FORMAT(`show_date`,'%e') as `date_day`,DATE_FORMAT(`show_date`,'%c') as `date_month`,DATE_FORMAT(`show_date`,'%Y') as `date_year`,DATE_FORMAT(`show_date`,'%k') as `time_hour`,DATE_FORMAT(`show_date`,'%i') as `time_minute` from `%reminders%` $new_sql order by `show_date` DESC");
			while ($row=$db->fetch($res)) {
				$reminders[]=$row;
			}
		if (isset($reminders)) {
			return $reminders;
		} else {
			return false;
		}
	}
	
	function getNowReminders() {
		global $db;
		$res=$db->query("select id_reminder from `%reminders%` where DATE_FORMAT(`show_date`,'%d-%m-%Y')='".date('d-m-Y')."'");
		return @mysql_num_rows($res);
	}
	
	function deleteNewReminders() {
		global $db;
		if ($db->query("DELETE from `%reminders%` where `show_date`<NOW() and `undelete`=0")) {
			$db->query("UPDATE `%reminders%` set `show`=1 where `show_date`<NOW() and `undelete`=1");
			return true;
		} else {
			return false;
		}
	}
	
	function createAffair($delo,$vazhn) {
		global $db;
		if (trim($delo)!='' && preg_match("/^[0-9]{1,}$/i",$vazhn)) {
			$delo=charset_x_win($delo);
			if ($db->query("insert into `%process%` values(null,'$delo',$vazhn,'".date('Y-m-d H:i:s')."',0,'')")) {
				return mysql_insert_id();
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function createNote($caption,$content) {
		global $db;
		if ($db->query("insert into `%notes%` values(null,'".sql_quote($caption)."','".sql_quote($content)."','','".date('Y-m-d H:i:s')."')")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function getNoteByID($id_note=false) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_note)) return false;
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d.%m.%Y %H:%i') as `create_print` from `%notes%` where `id_note`=$id_note");
		return $db->fetch($res);
	}
	
	function getNotes() {
		global $db;
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d.%m.%Y %H:%i') as `create_print` from `%notes%` order by `date_create` DESC");
		while ($row=$db->fetch($res)) {
			$notes[]=$row;
		}
		if (isset($notes)) {
			return $notes;
		} else {
			return false;
		}
	}
	
	function getAffairByID($id_affair=false) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_affair)) return false;
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d.%m.%Y %H:%i') as `create_print` from `%process%` where `id_process`=$id_affair");
		return $db->fetch($res);
	}
	
	function getAllAffairs() {
		global $db;
		$res=$db->query("select *,DATE_FORMAT(`date_create`,'%d.%m.%Y %H:%i') as `create_print`,DATE_FORMAT(`date_done`,'%d.%m.%Y %H:%i') as `done_print` from `%process%` order by `done`");
		while ($row=$db->fetch($res)) {
			$affairs[$row["vazhn"]][]=$row;
		}
		if (isset($affairs)) {
			return $affairs;
		} else {
			return false;
		}
	}
	
	 /*конец работы с напоминаниями*/
	 
		function get_memory_usage() {
			
			$memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0;
			$memory['limit'] = (int) ini_get('memory_limit') ;
			
			if ( !empty($memory['usage']) && !empty($memory['limit']) ) {
				$memory['percent'] = round ($memory['usage'] / $memory['limit'] * 100, 0);
			}
			return $memory;
		}
	
		function addJS($j) {
			$this->js[]=$j;
		}
		
		function assignJS() {
			global $smarty;
			$smarty->assign("adm_js",$this->js);
		}
		
		function TranslitThis($str) {
			$str=trim($str);
			$str=preg_replace("/[^a-zа-я0-9A-ZА-Я ]/i","", $str);
			// Сначала заменяем "односимвольные" фонемы.
		    $str=strtr($str,"абвгдеёзийклмнопрстуфхъыэ_",
		    "abvgdeeziyklmnoprstufh'iei");
		    $str=strtr($str,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_",
		    "ABVGDEEZIYKLMNOPRSTUFH'IEI");
		    // Затем - "многосимвольные".
		    $str=strtr($str, 
                    array(
                        "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", 
                        "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
                        "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH", 
                        "Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA",
                        "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
                        )
             );
		    // Возвращаем результат.
			$str=str_replace(" ","_",$str);
		    return $str;
		}
		
		function getCategoriesByBlock($id_block) {
			global $db;
			if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
			$res=$db->query("select id_cat from `%block_categories%` where id_block=$id_block");
			while ($row=$db->fetch($res)) {
				$cats[]=$row["id_cat"];
			}
			if (isset($cats)) return $cats;
			return false;
		}
		
		//Функция добавления нового шаблона
		function addTemplate($title='',$file='',$css='',$type='') {
			global $db;
			if ($type!='block') $type='site';
			if ($db->query("insert into `%templates%` values(null,'".sql_quote($file)."','".sql_quote($type)."','".sql_quote($title)."','default','".sql_quote($css)."')")) {
				return true;
			} else {
				return false;
			}
		}
		
		function iPhoneCheck() {
			global $smarty;
			$chck = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
			    if ($chck == true) {
					$_SESSION["iphone"]=true;
				    $smarty->assign('iphone',true);
					$smarty->assign('iphone_meta','<meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0" />');
				} else {
					$_SESSION["iphone"]=false;
					$smarty->assign('iphone',false);
				}
			unset($chck);
		}
		
		function iPadCheck() {
			global $smarty;
			$chck = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
			    if ($chck == true) {
					$_SESSION["ipad"]=true;
				    $smarty->assign('ipad',true);
					$smarty->assign('ipad_meta','<meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0" />');
				} else {
					$_SESSION["ipad"]=false;
					$smarty->assign('ipad',false);
				}
			unset($chck);
		}
		
		function loadContent($url) {
			$ch	= curl_init();
			curl_setopt($ch, CURLOPT_URL,$url); // set url to post to 
			curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable 
			curl_setopt($ch, CURLOPT_TIMEOUT, 25);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.5 (KHTML, like Gecko) Chrome/4.0.249.89 Safari/532.5');
			$result = curl_exec($ch); // run the whole process 
			$curl_err=curl_error($ch);
			curl_close($ch);
			return $result;
		}
		
		function createButton($caption='',$url='',$button_type=0,$href_type=0) {
			global $db;
			if (!preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$url))
				$url='';
			if (!preg_match("/^[a-zA-Z0-9]{1,}$/i",$button_type)) return false;
			
			if (!preg_match("/^[0-9]{1,}$/i",$href_type)) return false;
			if ($db->query("insert into `%buttons%` values (null,'".sql_quote($caption)."','".sql_quote($url)."','".sql_quote($button_type)."',$href_type,0)")) {
				return @mysql_insert_id();
			} else {
				return false;
			}
		}
		
		function getButtonByID($id_button) {
			global $db;
			if (!preg_match('/^[0-9]{1,}$/i',$id_button)) return false;
			$res=$db->query("select * from `%buttons%` where id_button=$id_button");
			return $db->fetch($res);
		}
		
		function getButtons() {
			global $db;
			$res=$db->query("select * from `%buttons%` order by `sort` DESC");
			while ($row=$db->fetch($res)) {
				$buttons[$row["id_button"]]=$row;
			}
			if (isset($buttons))
				return $buttons;
			return false;
		}
		
	// $filepath – путь к файлу, который мы хотим отдать
	// $mimetype – тип отдаваемых данных (можно не менять)
	function download_file($filepath, $mimetype = 'application/octet-stream') {
	$fsize = filesize($filepath); // берем размер файла
	$ftime = date('D, d M Y H:i:s T', filemtime($filepath)); // определяем дату его модификации

	$fd = @fopen($filepath, 'rb'); // открываем файл на чтение в бинарном режиме

	if (isset($_SERVER['HTTP_RANGE'])) { // поддерживается ли докачка?
		$range = $_SERVER['HTTP_RANGE']; // определяем, с какого байта скачивать файл
		$range = str_replace('bytes=', '', $range);
		list($range, $end) = explode('-', $range);

		if (!empty($range)) {
			fseek($fd, $range);
		}
	} else { // докачка не поддерживается
		$range = 0;
	}

	if ($range) {
		header($_SERVER['SERVER_PROTOCOL'].' 206 Partial Content'); // говорим браузеру, что это часть какого-то контента
	} else {
		header($_SERVER['SERVER_PROTOCOL'].' 200 OK'); // стандартный ответ браузеру
	}

	// прочие заголовки, необходимые для правильной работы
	header('Content-Disposition: attachment; filename='.basename($filepath));
	header('Last-Modified: '.$ftime);
	header('Accept-Ranges: bytes');
	header('Content-Length: '.($fsize - $range));
	if ($range) {
		header("Content-Range: bytes $range-".($fsize - 1).'/'.$fsize);
	}
	header('Content-Type: '.$mimetype);

	fpassthru($fd); // отдаем часть файла в браузер (программу докачки)
	fclose($fd);

	exit;
	}	
		
	}
?>