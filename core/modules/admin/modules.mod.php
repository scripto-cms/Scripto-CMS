<?
/*
модуль по управлению модул€ми
*/

if (defined("SCRIPTO_GALLERY")) {
	$page["title"]=$lang["modules"]["modules"];
	$smarty->assign("page",$page);
	$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/modules/");
	if ($modAction=="") $modAction="view";
	switch ($modAction) {
		case "category_module":
			$this->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
			$this->addPath($lang["interface"]["category_module_ex"],'',false);
			$this->assignPath();
			$this->setAdminTitle($lang["modules"]["modules_category"]);
			$mod_name=@$_REQUEST["module_name"];
			if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name)) {
				$mod_=$this->getModule($mod_name);
				if ($mod_) {
				if (isset($_REQUEST["m_save"])) {
					if ($mod_["use_in_one_rubric"]) {
						$id_cat=@$_REQUEST["id_cat"];
						if (preg_match("/^[0-9]{1,}$/i",$id_cat) && preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_["name"])) {
							$db->query("delete from %categories_module% where `name_module`='".$mod_["name"]."'");
							$db->query("insert into %categories_module% values('".$mod_["name"]."',$id_cat)");
$this->setCongratulation('',$lang["congratulation"]["module_category_save"],7000);
						}
					} else {
						$id_cat=@$_REQUEST["id_cat"];
						$db->query("delete from %categories_module% where `name_module`='".$mod_["name"]."'");
						if (is_array($id_cat)) {
						foreach ($id_cat as $key=>$cat) {
							if (preg_match("/^[0-9]{1,}$/i",$key)) {
								$db->query("insert into %categories_module% values('".$mod_["name"]."',$key)");
							}
						}
						}
				$this->setCongratulation('',$lang["congratulation"]["module_category_save"],7000);
					}
				} 
					$desc=true;
					$order="";
					$modules_categories=$this->getCategoriesByModule($mod_["name"]);
					$categories=$this->getRubricsTree(0,0,false,'',false,$order,$desc);
					foreach ($categories as $key=>$cat) {
						if (in_array($cat["id_category"],$modules_categories)) {
							$categories[$key]["module"]=true;
						} else {
							$categories[$key]["module"]=false;
						}
					}
					$smarty->assign("categories",$categories);
					$smarty->assign("user_module",$mod_);
				}
			}
		break;
		case "settings":
			$this->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);	
			$mod_name=@$_REQUEST["module_name"];
			if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name)) {
				if ($this->user["type"]=='root' || isset($this->user["permissions"]["additional"][$mod_name])) {
				$mod_=$this->getModule($mod_name);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==true) {
						$this->addPath($m->thismodule["caption"],'',false);
						$this->assignPath();
						$module_content=$m->doAdmin();
						$smarty->assign("module_content",$module_content);
						$smarty->assign("sub_module",$m->thismodule["name"]);
						$this->setAdminTitle($m->thismodule["caption"]);
						if (isset($m->thismodule["version"]))
							$smarty->assign("module_version",$m->thismodule["version"]);
						if (isset($m->thismodule["documentation"]))
							$smarty->assign("module_documentation",$m->thismodule["documentation"]);						
					}
					$smarty->assign("user_module",$mod_);
				}
				} else {
					$this->setAdminTitle($lang["error"]["access_denied"]='ƒоступ закрыт');
					$smarty->assign("denied",true);
				}
			}
		break;
		case "update":
			$mod_name=@$_REQUEST["module_name"];
			if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name) && $this->user["type"]=='root') {
				$mod_=$this->getModule($mod_name);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==true) {
						include($mod_["path"]."update.php");
							if (isset($update_sql)) {
							$db->query_array($update_sql,true);
							}
							$m->doUpdate();
						if (isset($m->thismodule["tables"])) {
						if (is_array($m->thismodule["tables"])) {
							$this->assignTables($m->thismodule["tables"]);
								foreach ($this->languages as $lng=>$l)
									if ($l["default"]==0)
									foreach ($m->thismodule["tables"] as $tbl=>$tabl)
										$this->doMultilang($tbl,$lng);
						}
						}
							$this->setCongratulation('',$lang["congratulation"]["module_update"],7000);
					}
				}
				$modAction="view";
			} else {
				if ($this->user["type"]!='root') {
					$modAction="view";
					$this->setCongratulation($lang["error"]["caption"],$lang["error"]["access_denied"],3000);
				}
			}
		break;
		case "uninstall":
			$mod_name=@$_REQUEST["module_name"];
			if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name) && $this->user["type"]=='root') {
				$mod_=$this->getModule($mod_name);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==true) {
						include($mod_["path"]."uninstall.php");
							if (isset($uninstall_sql)) {
							$db->query_array($uninstall_sql,true);
							}
							//echo mysql_error();
							$m->doUninstall();
							$this->uninstallModule($mod_name);
						    $this->setCongratulation('',$lang["congratulation"]["module_uninstall"],7000);
							$installed_modules=$this->getInstallModulesFast();
							$smarty->assign("installed_modules",$installed_modules);						    
					}
				}
				$modAction="view";
			} else {
				if ($this->user["type"]!='root') {
					$modAction="view";
					$this->setCongratulation($lang["error"]["caption"],$lang["error"]["access_denied"],3000);
				}
			}
		break;
		case "install":
			$mod_name=@$_REQUEST["module_name"];
			if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$mod_name) && $this->user["type"]=='root') {
				$mod_=$this->getModule($mod_name);
				if ($mod_) {
					$m=$this->includeModule($mod_);
					if ($m->checkMe()==false) {
						$install_sql=array();
						include($mod_["path"]."install.php");
						if ($db->query_array($install_sql) && $m->doInstall()) {
						$this->installModule($mod_name);
						if (isset($m->thismodule["tables"])) {
						if (is_array($m->thismodule["tables"])) {
							$this->assignTables($m->thismodule["tables"]);
								foreach ($this->languages as $lng=>$l) 
									if ($l["default"]==0)
									foreach ($m->thismodule["tables"] as $tbl=>$tabl) 
										$this->doMultilang($tbl,$lng);
						}
						}
						$this->setCongratulation('',$lang["congratulation"]["module_install"],7000);
						$installed_modules=$this->getInstallModulesFast();
						$smarty->assign("installed_modules",$installed_modules);
						} else {
						$debug_info='';
						if ($config["debug_mode"]) $debug_info=$lang["error"]["error_text"].sql_quote(htmlspecialchars(mysql_error()));
						$this->uninstallModule($mod_name);
						include($mod_["path"]."uninstall.php");
							if (isset($uninstall_sql)) {
							$db->query_array($uninstall_sql,true);
							}
							$m->doUninstall();
							$this->uninstallModule($mod_name);
						$this->setCongratulation('',$lang["error"]["module_install"].$debug_info,7000);
						}
					}
				}
				$modAction="view";
			} else {
				if ($this->user["type"]!='root') {
					$modAction="view";
					$this->setCongratulation($lang["error"]["caption"],$lang["error"]["access_denied"],3000);
				}
			}
		break;
	}
	if ($modAction=="view") {
		$this->setAdminTitle($lang["modules"]["modules"]);
		$modules=$this->findModules();
		$smarty->assign("modules",$modules);
	}
	$smarty->assign($modAction);
}
?>