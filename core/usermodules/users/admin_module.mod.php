<?
/*
Модуль пользователи, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
$objects_install=false;
if ($engine->checkInstallModule("objects")) {
	$smarty->assign("objects_install",true);
	$objects_install=true;
}
$basket_install=false;
if ($engine->checkInstallModule("basket")) {
	$smarty->assign("basket_install",true);
	$basket_install=true;
}
$m_action=@$_REQUEST["m_action"];
switch ($m_action) {
	case "group_users":
		//Назначение пользователей для группы
		$id_group=@$_REQUEST["id_group"];
		if (preg_match("/^[0-9]{1,}$/i",$id_group)) {
			$group=$this->getGroupByID($id_group);
			$smarty->assign("group",$group);
			if (isset($_REQUEST["save"])) {
				$add=@$_REQUEST["add"];
				$db->query("update `%USERS%` set id_group=0 where `id_group`=$id_group");
				if (is_array($add)) {
					foreach ($add as $id_user=>$val) {
						if (preg_match("/^[0-9]{1,}$/i",$id_user)) {
							$db->query("update `%USERS%` set id_group=$id_group where `id_user`=$id_user");
						}
					}
				}
				$smarty->assign("saved",true);
			}
			$users=$this->getAllUsers(1);
			$smarty->assign("users",$users);
		}
	break;
	case "groups":
		//группы пользователей
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Группы пользователей','',false);
		$engine->assignPath();
		if (isset($_REQUEST["addnew"])) {
			$groupname=trim(strip_tags(@$_REQUEST["groupname"]));
			if (!$this->existGroup($groupname)) {
				if ($this->addGroup($groupname,0,0)) {
					$engine->setCongratulation('Группа создана','Группа '.$groupname.' создана!',3000);
				} else {
					$engine->setCongratulation('Ошибка','При создании новой группы произошла ошибка!',3000);
				}
			} else {
				$engine->setCongratulation('Ошибка','Группа '.$groupname.' уже существует!',3000);
			}
		}
		if (isset($_REQUEST["save"])) {
			$caption=@$_REQUEST["caption"];
			$percent=@$_REQUEST["percent"];
			$del=@$_REQUEST["del"];
			if (is_array($caption)) {
			$groups=$this->getAllGroups();
			$d=0;
			$u=0;
				foreach ($caption as $id_group=>$capt) {
					if (preg_match("/^[0-9]{1,}$/i",$id_group)) {
						if (isset($del[$id_group])) {
							if ($db->query("delete from `%GROUPS%` where id_group=$id_group")) {
								$d++;
							}
						} else {
							$do_caption=false;
							if (isset($groups[$id_group]["caption"])) {
								if ($capt!=$groups[$id_group]["caption"] && !$this->existGroup($capt)) {
									$do_caption=true;
								} else {
									if ($capt==$groups[$id_group]["caption"]) {
										$do_caption=true;
									}
								}
							} else {
								if (!$this->existGroup($capt))
									$do_caption=true;
							}
							$percent_sql='';
							if (isset($percent[$id_group])) {
								if (preg_match("/^[0-9]{1,2}$/i",$percent[$id_group])) {
									$percent_sql=",`percent`=".$percent[$id_group];
								}
							}
							if ($do_caption)
								if ($db->query("update `%GROUPS%` set `caption`='".sql_quote($capt)."' $percent_sql where `id_group`=$id_group")) {
									$u++;
								}
						}
					}
				}
			$engine->setCongratulation('',"Обновлено $u групп , удалено $d групп",5000);
			unset($groups);
			}
		}
		$groups=$this->getAllGroups();
		$smarty->assign("groups",$groups);
	break;
	case "delete":
		$id_user=@$_REQUEST["id_user"];
		if (preg_match("/^[0-9]{1,}$/i",$id_user)) {
			$db->query("DELETE from `%USERS%` where id_user=$id_user");
			$db->query("DELETE from `%USER_OBJECTS%` where id_user=$id_user");
			$engine->setCongratulation("Пользователи","Пользователь удален успешно!",5000);
		}
		$m_action="users";
	break;
	case "save":
		$iduser=@$_REQUEST["iduser"];
		$new=@$_REQUEST["new"];
		$email=@$_REQUEST["email"];
		$name=@$_REQUEST["name"];
		$family=@$_REQUEST["family"];
		$otch=@$_REQUEST["otch"];
		if (is_array($iduser)) {
		$u=0;
			foreach ($iduser as $key=>$id_user) {
				if (preg_match("/[0-9]{1,}$/i",$id_user)) {
					if (isset($new[$id_user])) {
						$new_str='`new`=1';
					} else {
						$new_str='`new`=0';
					}
					$email_str='';
					if (isset($email[$id_user])) {
						if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email[$id_user])) {
							$email_str=",`email`='".$email[$id_user]."'";
						}
					}
					$family[$id_user]=sql_quote(strip_tags($family[$id_user]));
					$name[$id_user]=sql_quote(strip_tags($name[$id_user]));
					$otch[$id_user]=sql_quote(strip_tags($otch[$id_user]));
					if ($db->query("update `%USERS%`set $new_str,`name`='".$name[$id_user]."',`family`='".$family[$id_user]."',`otch`='".$otch[$id_user]."' $email_str where id_user=$id_user")) {
						$u++;
					}
				}
			}
			$engine->setCongratulation("Пользователи","Обновлено $u пользователей!",5000);
		}
		$m_action="users";
	break;
	case "users":
		//просмотр пользователей
		
	break;
	case "register":
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);

			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_user"])) {
				$id_user=@$_REQUEST["id_user"];
				$usr=$this->getuserByID($id_user);
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
$engine->setPreview($img["small_photo"],$previewMode,'Аватар установлен');
$_SESSION["user_small"]=$img["small_photo"];
								} else {
								if ($db->query("update `%USERS%` set `avatar`='".$img["small_photo"]."' where id_user=$id_user")) {
$engine->setPreview($img["small_photo"],$previewMode,'Аватар установлен');
								}
								}
							break;
						}
					}
				}
			
			if (isset($_REQUEST["save"])) {
				$first=false;
				$login=@$_REQUEST["login"];
				$family=@$_REQUEST["family"];
				$name=@$_REQUEST["name"];
				$otch=@$_REQUEST["otch"];
				$email=@$_REQUEST["email"];
				$city=@$_REQUEST["city"];
				$phone1=@$_REQUEST["phone1"];
				$phone2=@$_REQUEST["phone2"];
				if (isset($_REQUEST["moderator"])) {
					$moderator=1;
				} else {
					$moderator=0;
				}
				if (isset($_REQUEST["change_pass"])) {
					$change_pass=1;
				} else {
					$change_pass=0;
				}
				$id_group=@$_REQUEST["id_group"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($usr) {
						$login=$usr["login"];
						$family=$usr["family"];
						$name=$usr["name"];
						$otch=$usr["otch"];
						$email=$usr["email"];
						$city=$usr["city"];
						$phone1=$usr["phone1"];
						$phone2=$usr["phone2"];
						$change_pass=0;
						$moderator=$usr["moderator"];
						$id_group=$usr["id_group"];
					}
				} else {
					$login="";
					$family="";
					$name="";
					$otch="";
					$email="";
					$city="";
					$phone1="";
					$phone2="";
					$change_pass=0;
					$moderator=0;
					$id_group=0;
				}
			}
			
			$values=$this->getAllGroupsEx();
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("Желаемый логин","Неверно заполнено поле желаемый логин","text",$login,$login,"/^[a-zA-Z0-9]{2,20}$/i","login",1,"dmitry991984",array('size'=>'40','ticket'=>"Цифры и латинские буквы, от 5 до 10 символов"));

$frm->addField("Группа пользователя","Неверно выбрана группа пользователя","list",$values,$id_group,"/^[0-9]{1,}$/i","id_group",1,"Постоянные клиенты",array('size'=>'40'));

$frm->addField("Фамилия","Неверно заполнено поле фамилия","text",$family,$family,"/^[^`#]{2,255}$/i","family",0,"Кузнецов",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Имя","Неверно заполнено поле имя","text",$name,$name,"/^[^`#]{2,255}$/i","name",1,"Валерий",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Отчество","Неверно заполнено поле отчество","text",$otch,$otch,"/^[^`#]{2,255}$/i","otch",0,"Степанович",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Город","Неверно заполнено поле город","text",$city,$city,"/^[^`#]{2,255}$/i","city",0,"Москва",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("E-mail","Неверно заполнено поле e-mail","text",$email,$email,"/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i","email",1,"valery@site.ru",array('size'=>'40','ticket'=>""));

$frm->addField("Городской телефон","Неверно заполнено поле городской телефон","text",$phone1,$phone1,"/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i","phone1",0,"8 (495) 910-44-56",array('size'=>'40','ticket'=>""));

$frm->addField("Сотовый телефон","Неверно заполнено поле сотовый телефон","text",$phone2,$phone2,"/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i","phone2",0,"8 (926) 910-44-56",array('size'=>'40','ticket'=>""));

$frm->addField('Аватар пользователя',"","caption",0,0,"/^[0-9]{1}$/i","avatar",0,'',array('hidden'=>true));
if ($mode=="edit") {
$frm->addField('Аватар','',"preview",$usr["avatar"],$usr["avatar"],"/^[0-9]{1,}$/i","min_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true));
} else {
if (isset($_SESSION["user_small"])) {
$usr["avatar"]=$_SESSION["user_small"];
} else {
$usr["avatar"]='';
}
$frm->addField('Аватар','',"preview",$usr["avatar"],$usr["avatar"],"/^[0-9]{1,}$/i","min_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true));
}
$frm->addField('Аватар пользователя',"","caption",0,0,"/^[0-9]{1}$/i","avatar",0,'',array('end'=>true));

$frm->addField("Сделать пользователя модератором","Неверно выбрано свойство сделать пользователя модератором","check",$moderator,$moderator,"/^[0-9]{1}$/i","moderator",1);

if ($mode=="edit") {
	$frm->addField("Изменить пароль","Неверно выбрано свойство смены пароля","check",$change_pass,$change_pass,"/^[0-9]{1}$/i","change_pass",1);
}

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_user"])) {
$id_user=$_REQUEST["id_user"];
$frm->addField("","","hidden",$id_user,$id_user,"/^[^`]{0,}$/i","id_user",1);
}
$smarty->assign("mode",$mode);
if ($mode=="edit") {
$b_text="Изменить";
$engine->addPath('Редактирование пользователя '.$usr["login"],'',false);
if ($login!=$usr["login"])
 if ($this->loginExist($login)) 
	$frm->addError("Пользователь с логином <b>$login</b> уже существует!");	
} else {
$b_text="Регистрация";
$engine->addPath('Регистрация нового пользователя','',false);
if ($this->loginExist($login)) 
	$frm->addError("Пользователь с логином <b>$login</b> уже существует!");
}

			if (
$engine->processFormData($frm,$b_text,$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_user)) {
				 	$pass_sql="";
				 	if ($change_pass) {
				 $password=$this->generatePassword();
				 $smarty->assign("password",$password);
					$pass_sql=",`password`='".$engine->generate_admin_password($password)."'";
					}
					if ($engine->user["type"]!="root") {
						$moderator=$usr["moderator"];
						$engine->setCongratulation('Ошибка','Назначить, либо снять права модератора может только администратор сайта',0);
					}
				 	if ($db->query("update %USERS% set `new`=0,`id_group`=$id_group,`moderator`=$moderator,`login`='".sql_quote($login)."' $pass_sql , family='".sql_quote($family)."' , `name`='".sql_quote($name)."',`otch`='".sql_quote($otch)."',city='".sql_quote($city)."',`email`='".sql_quote($email)."',`phone1`='".sql_quote($phone1)."',`phone2`='".sql_quote($phone2)."' where id_user=$id_user")) {
						//отредактировали
				//	   $modAction="view";
				   $engine->setCongratulation("Пользователи","Информация о пользователе $login отредактирована успешно!");
				   $cuser=$this->getuserByID($id_user);
				   $smarty->assign("cuser",$cuser);
				   $this->mailMe($email,$this->thismodule["mailadmin"],"Изменение информации о пользователе $login на сайте ".$settings["httproot"],1);
					$m_action="users";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $password=$this->generatePassword();
				 $smarty->assign("password",$password);
 $add_id=$this->addUser($login,$password,$family,$name,$otch,$city,$email,$phone1,$phone2,0,$usr["avatar"],$moderator,$id_group);
				 if ($add_id!=false) {
				   //добавили успешно!
				   $engine->setCongratulation("Пользователи","Пользователь $login зарегистрирован успешно!");
				   $cuser=$this->getUserByID($add_id);
				   $smarty->assign("cuser",$cuser);
				   $this->mailMe($email,$this->thismodule["mailadmin"],"Регистрация пользователя на сайте ".$settings["httproot"],0);
					$m_action="users";
				 }
				}
			}
			$engine->assignPath();
	break;
	case "permissions":
	$id_user=@$_REQUEST["id_user"];
	if (preg_match("/^[0-9]{1,}$/i",$id_user) && ($this->engine->user["type"]=='root')) {
		$usrs=new Users();
		$usrs->doDB();
		if (isset($_REQUEST["save"])) {
			$standart=@$_REQUEST["standart"];
			$additional=@$_REQUEST["additional"];
			$user_modules=array();
			if (is_array($standart))
				foreach ($standart as $key=>$st)
					$user_modules["standart"][$key]=true;
			if (is_array($additional))
				foreach ($additional as $key=>$st)
					$user_modules["additional"][$key]=true;
				$user_modules_str=serialize($user_modules);
				$db->query("update `%USERS%` set `access`='".sql_quote($user_modules_str)."' where id_user=$id_user");
				$smarty->assign("saved",true);
		}
		$usr=$usrs->getUserByID($id_user);
		$smarty->assign("usr",$usr);
		$modules=$engine->getAllModulesSettings();
		$smarty->assign("mods",$modules);
	}
	break;
	case "objects":
	$id_user=@$_REQUEST["id_user"];
	if (preg_match("/^[0-9]{1,}$/i",$id_user) && $objects_install) {
		$usr=$this->getUserByID($id_user);
		$smarty->assign("user",$usr);
		$ob=new objects();
		$ob->doDb();
		$smarty->assign("addObj2",true);
				if (isset($_REQUEST["sort_me"])) {
					//сортировка
					$del=@$_REQUEST["del"];
					$sort=@$_REQUEST["sort"];
					if (is_array($sort)) {
					$d=0;
					$u=0;
						foreach ($sort as $id_obj=>$value) {
							if (isset($del[$id_obj])) {
								$db->query("delete from `%USER_OBJECTS%` where id_object=$id_obj and id_user=$id_user");
								$d++;
							} else {
								if (preg_match("/^[0-9]{1,}$/i",$sort[$id_obj])) {
									$db->query("update `%USER_OBJECTS%` set `sort`=".$sort[$id_obj]." where id_object=$id_obj and id_user=$id_user");
									$u++;
								}
							}
						}
						$engine->setCongratulation("","Изменения сохранены (Удалено $d объектов , обновлено $u объектов)");
					}
				}
		$usr_objects=$this->getObjectsByUser($id_user);
		$smarty->assign("user_objects",$usr_objects);
				if (isset($_REQUEST["setObject"])) {
					$id_object2=@$_REQUEST["id_object2"];
					if (preg_match("/^[0-9]{1,}$/i",$id_object2)) {
						$previewMode=@$_REQUEST["previewMode"];
						switch ($previewMode) {
							case "new":
								if ($id_object2!=0) {
								$obj=$ob->getObjectByIDEx($id_object2);
								$smarty->assign("objct",$obj);
								$smarty->assign("id_object2",$id_object2);
								if ($this->addObject2User($id_object2,$id_user,$obj["id_type"])) {
									$smarty->assign("fancyTooltip","Объект успешно добавлен");
$smarty->assign("addObj2",true);
								}
								}
							break;
							default:
								if ($id_object2==0) {
										$db->query("delete from `%USER_OBJECTS%` where id_object=$previewMode and id_user=$id_user");
										$smarty->assign("fancyTooltip","Объект удален успешно");
										$smarty->assign("id_object2",$previewMode);
										$smarty->assign("deleteObj",true);
								} else {
									$obj=$ob->getObjectByIDEx($id_object2);
									$smarty->assign("objct",$obj);
									$smarty->assign("id_object2",$id_object2);
									$smarty->assign("addObj2",true);
								if ($db->query("update `%USER_OBJECTS%` set id_object=$id_object2 where id_object=$previewMode and id_user=$id_user")) {
	$engine->setPreview($obj["caption"],$previewMode,'Объект установлен успешно','html');
								}
								}
						}
						$smarty->assign("closeFancybox",true);
					}
				}
				
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
$engine->addPath('Выбор объектов для пользователя '.$usr["login"],'',false);
		$engine->assignPath();
	}
	break;
	case "dialogobject":
		if (isset($_REQUEST["id_user"]) && $objects_install) {
			$id_user=$_REQUEST["id_user"];
			if (preg_match("/^[0-9]{1,}$/i",$id_user)) {
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
				$usr=$this->getUserByID($id_user);
				$obj=new objects();
				$obj->doDb();
				$smarty->assign("user",$usr);
				$smarty->assign("id_category",false);
				if (isset($_REQUEST["id_category"])) {
					$id_category=@$_REQUEST["id_category"];
						if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
							$smarty->assign("noModuleHeader",true);
							$objects=$obj->getAllObjects($id_category,0);
							$smarty->assign("count_objects",sizeof($objects));
							$smarty->assign("objects",$objects);
							$smarty->assign("get_category",true);
							$smarty->assign("id_category",$id_category);
						}
				}
				$rubrics=$obj->getCountAllObjectsEx();
				$smarty->assign("categories",$rubrics);
			}
		}
	break;
	default:
		$m_action="users";
}
if ($m_action=="users") {
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'',false);
	$engine->assignPath();
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
		} else {
			$str_real='*';
			$str='*';
			$find=strpos($str_real,'*');
			if ($find===false) {
				$str='%'.$str_real.'%';
			} else {
				$str=str_replace('*','%',$str_real);
			}
		}
		$smarty->assign('str',$str_real);
		$smarty->assign('str_url',urlencode($str_real));
	} else {
		$str='';
	}
	if (isset($_REQUEST["stype"])) {
		if (preg_match("/^[0-9]{1,}$/i",$_REQUEST["stype"])) {
			$stype=$_REQUEST["stype"];
		} else {
			$stype=0;
		}
	} else {
		$stype=0;
	}
	$smarty->assign("stype",$stype);
	$count=$this->getCountUsers($str,$stype);
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
	$usrs=$this->getUsers($str,$pg,$this->thismodule["onpage_admin"],$stype);
	$smarty->assign("users",$usrs);
						if (isset($pages_arr)) {
							$smarty->assign("pages",$pages_arr);
							$smarty->assign("pagenumber",$pg);
						}
}
$smarty->assign("m_action",$m_action);
?>