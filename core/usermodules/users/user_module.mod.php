<?
global $page;
if (!isset($_SESSION["user_login"]) && !isset($_SESSION["user_password"])) {
	//неавторизированы
if (isset($_COOKIE['user_login']) && isset($_COOKIE['user_password'])) {
	//проверка зарегистрированного
	if ($this->authUser($_COOKIE["user_login"],$_COOKIE["user_password"])) {
		$smarty->assign("user_login",$_COOKIE["user_login"]);
		$_SESSION["user_login"]=$_COOKIE["user_login"];
		$_SESSION["user_password"]=$_COOKIE["user_password"];
		$_SESSION["auth"]=true;
		$smarty->assign("user_login",$_SESSION["user_login"]);
		$smarty->assign("authorized",true);
	} else {
		$this->unsetCookie();
		$smarty->assign("not_authorized",true);
		$this->clearAuth();
		$smarty->assign("wrong_password",true);
	}
} else {
	$smarty->assign("not_authorized",true);
	if (isset($_REQUEST["user_login"]) && isset($_REQUEST["user_password"])) {
	$user_login=trim(@$_REQUEST["user_login"]);
	$user_password=$engine->generate_admin_password(trim(@$_REQUEST["user_password"]));
	if ($this->authUser($user_login,$user_password)) {
		$_SESSION["user_login"]=$user_login;
		$_SESSION["user_password"]=$user_password;
		$_SESSION["auth"]=true;
		if (isset($_REQUEST["remember_me"])) {
		$this->setCookie($user_login,$user_password);
		} else {
		$this->unsetCookie($user_login,$user_password);
		}
		$smarty->assign("user_login",$_SESSION["user_login"]);
		$smarty->assign("authorized",true);
	} else {
		$this->clearAuth();
		$smarty->assign("wrong_password",true);
		$smarty->assign("not_authorized",true);
	}
	}
}
} else {
	//проверка зарегистрированного
	if ($this->authUser($_SESSION["user_login"],$_SESSION["user_password"])) {
		$smarty->assign("user_login",$_SESSION["user_login"]);
		if (isset($_REQUEST["user_exit"])) {
			$smarty->assign("not_authorized",true);
			$this->unsetCookie($_SESSION["user_login"],$_SESSION["user_password"]);
			$this->clearAuth();
		} else {
			$_SESSION["auth"]=true;
			$smarty->assign("authorized",true);
		}
	} else {
		$smarty->assign("not_authorized",true);
		$this->clearAuth();
		$smarty->assign("wrong_password",true);
	}
}

switch ($page["ident"]) {
	case $this->thismodule["forgot_url"]:
		$smarty->assign("m_type","forgot");
		if (isset($_REQUEST["activatekey"])) {
			//восстановление парол€
			$smarty->assign("generate_password",true);
			$login=@$_REQUEST["login"];
			$activatekey=@$_REQUEST["activatekey"];
			$newkey=md5($login.$config["secretkey"]);
			if ($newkey==$activatekey) {
				$u=$this->getUserByLogin($login);
				if (is_array($u)) {
					//есть такой пользователь
					$u_pass=$this->generatePassword();
					$pass=$engine->generate_admin_password($u_pass);
					$db->query("update `%USERS%` set `password`='".sql_quote($pass)."' where `login`='".sql_quote($u["login"])."'");
					$smarty->Assign("u",$u);
					$smarty->Assign("u_pass",$u_pass);
					$this->mailMe($u["email"],$this->thismodule["mailadmin"],"¬осстановление парол€ на сайте ".$settings["httproot"],3);
				} else {
					$smarty->assign("incorrect_key",true);
				}
			} else {
				$smarty->assign("incorrect_key",true);
			}
		} else {
			if (isset($_REQUEST["login"])) {
				//логин указан
				$u=$this->getUserByLogin($_REQUEST["login"]);
				if (is_array($u)) {
					$email=$u["email"];
					$p=strpos($email,"@");
					if ($p!==false) {
					$l=strlen($email)-1;
					$white_mail='';
					for ($i=0;$i<$p;$i++)
						$white_mail.='*';
					$email=$white_mail.substr($email,$p,$l);
					} else {
					$email='';
					}
					$activatekey=md5($u["login"].$config["secretkey"]);
					$smarty->assign("activatekey",$activatekey);
					$smarty->assign("u",$u);
					$lang["users"]["activatesend"]=str_replace('%email%',$email,$lang["users"]["activatesend"]);
					$this->mailMe($u["email"],$this->thismodule["mailadmin"],"¬осстановление парол€ на сайте ".$settings["httproot"],2);
					$smarty->assign("lang",$lang);
					$smarty->assign("send",true);
				} else {
					$smarty->assign("login_error",true);
				}
			}
		}
	break;
	case $this->thismodule["my_url"]:
		$smarty->assign("m_type","my");
		if (@$_SESSION["auth"]==true && isset($_SESSION["user_login"])) {
			$user=$this->getUserByLogin($_SESSION["user_login"]);
			$smarty->assign("cuser",$user);
			$objects_install=false;
			if ($engine->checkInstallModule("objects")) {
				$smarty->assign("objects_install",true);
				$objects_install=true;
			}
			if ($engine->checkInstallModule("basket")) {
				$smarty->assign("basket_install",true);
				$basket_install=true;
			}
			if (isset($_REQUEST["objects"]) && $objects_install) {
				$smarty->assign("s_type","objects");
				$engine->addSubPath($lang["users"]["objects"],$page['url']);
				$page["caption"]=$lang["users"]["objects"];
				$ob=new objects();
				$ob->doDb();
				$user_objects=$this->getObjectsByUser($user["id_user"],1);
				if (is_array($user_objects)) {
				foreach ($user_objects as $id_type=>$tp) {
					$types[$id_type]=$ob->getTypeByID($id_type);
				}
				$smarty->assign("object_types",$types);
				foreach ($types as $key=>$tp) {
	$filename=$config["pathes"]["templates_dir"].$config["templates"]["user_processor"].$tp["ident"].'_short.processor.tpl';
					if (is_file($filename)) {
	$types[$key]["processor"]=$config["templates"]["user_processor"].$tp["ident"].'_short.processor.tpl';
					} else {
	$types[$key]["processor"]=$config["templates"]["user_processor"].'objects_short.processor.tpl';
					}
				}
					foreach ($user_objects as $id_type=>$tp) {
					foreach ($tp as $key=>$obj) {
	$user_objects[$id_type][$key]["values"]=$ob->transformateValues($obj,@$types[$id_type],"short");
						$user_objects[$id_type][$key]["processor"]=@$types[$id_type]["processor"];
						unset($user_objects[$id_type][$key]["value1"]);
						unset($user_objects[$id_type][$key]["value2"]);
						unset($user_objects[$id_type][$key]["value3"]);
						unset($user_objects[$id_type][$key]["value4"]);
						unset($user_objects[$id_type][$key]["value5"]);
						unset($user_objects[$id_type][$key]["value6"]);
						unset($user_objects[$id_type][$key]["value7"]);
						unset($user_objects[$id_type][$key]["value8"]);
						unset($user_objects[$id_type][$key]["value9"]);
						unset($user_objects[$id_type][$key]["value10"]);
						unset($user_objects[$id_type][$key]["list1"]);
						unset($user_objects[$id_type][$key]["list2"]);
						unset($user_objects[$id_type][$key]["list3"]);
					}
					}
				}
					$smarty->assign("user_objects",$user_objects);
			}
			if (isset($_REQUEST["basket"]) && $basket_install) {
				$smarty->assign("s_type","basket");
				$engine->addSubPath($lang["users"]["my_orders"],$page['url'].'?basket=yes');
				if (isset($_REQUEST["id_order"])) {
				$id_order=@$_REQUEST["id_order"];
				$engine->addSubPath($lang["users"]["my_orders"],$page['url'].'?basket=yes');
					if (preg_match("/^[0-9]{1,}$/i",$id_order)) {
						$page["caption"]=$lang["users"]["order_info"].' #'.$id_order;
						$ob=new Basket();
						$ob->doDb();
						$order=$ob->getOrderByID($id_order);
						$smarty->assign("order",$order);
						$smarty->assign("id_order",$id_order);
					} else {
						$page["caption"]=$lang["users"]["order_error"];
					}
				} else {
				$page["caption"]=$lang["users"]["my_orders"];
				$ob=new Basket();
				$ob->doDb();
				$orders=$ob->getOrdersByUser($user["id_user"]);
				$smarty->Assign("orders",$orders);
				}
			}
			if (isset($_REQUEST["edit"])) {
				$smarty->assign("s_type","edit");
	$engine->addSubPath($lang["users"]["my"],$page['url']);
	$page["caption"]=$lang["users"]["my"];
			//ѕровер€ем загружаетс€ ли аватар
			if (isset($_REQUEST["avatarload"])) {
			/* загрузка аватара*/
				if (isset($_FILES["avatar"])) {
				
				}
			/*конец загрузки аватара*/
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
			} else {
				$first=true;
				$login=$user["login"];
				$family=$user["family"];
				$name=$user["name"];
				$otch=$user["otch"];
				$email=$user["email"];
				$city=$user["city"];
				$phone1=$user["phone1"];
				$phone2=$user["phone2"];
			}
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			if (is_file($this->thismodule["path"]."user_form.php")) {
				include($this->thismodule["path"]."user_form.php");
			}
			if ($this->loginExist($login) && $login!=$user["login"])
				$frm->addError(str_replace('%login%',$login,$lang["users"]["user_exist"]));
			if ($this->emailExist($email) && $email!=$user["email"])
				$frm->addError(str_replace('%email%',$email,$lang["users"]["email_exist"]));
			 if (defined("SCRIPTO_forms")) {
			 	$tpl_form="system/classes/module_form.html";
			 } else {
			 	$tpl_form='';
			}
			if (
$engine->processFormData($frm,$lang["users"]["save"],$first,$tpl_form
			)) {
				if ($db->query("UPDATE `%USERS%` set `name`='".sql_quote($name)."',`family`='".sql_quote($family)."',`otch`='".sql_quote($otch)."',`email`='".sql_quote($email)."',`city`='".sql_quote($city)."',`phone1`='".sql_quote($phone1)."',`phone2`='".sql_quote($phone2)."',`login`='".sql_quote($login)."',`new`=1 where id_user=".$user["id_user"])) {
					$smarty->assign("user_updated",true);
				}
			}
			}
			if (isset($_REQUEST["changepassword"])) {
				$smarty->assign("s_type","changepassword");
	$engine->addSubPath($lang["users"]["my"],$page['url']);
	$page["caption"]=$lang["users"]["my"];
			if (isset($_REQUEST["save"])) {
				$first=false;
				$newpass=trim(@$_REQUEST["newpass"]);
				$oldpass=trim(@$_REQUEST["oldpass"]);
			} else {
				$first=true;
				$newpass='';
				$oldpass='';
			}
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
$frm->addField($lang["users"]["currentpassword"]["caption"],$lang["users"]["currentpassword"]["error"],"password",$oldpass,$oldpass,"/^[^`#]{5,25}$/i","oldpass",1,"",array('size'=>'40','ticket'=>'ќт 5 до 25 символов'));
$frm->addField($lang["users"]["newpassword2"]["caption"],$lang["users"]["newpassword2"]["error"],"text",$newpass,$newpass,"/^[^`#]{5,25}$/i","newpass",1,"",array('size'=>'40','ticket'=>'ќт 5 до 10 символов'));
$frm->addField("","","hidden",'yes','yes',"/^[^`#]{2,255}$/i","changepassword",1,"");
			if ($newpass==$oldpass) 
				$frm->addError($lang["users"]["eq_passwords"]);
			if ($engine->generate_admin_password($oldpass)!=$user["password"]) 
				$frm->addError($lang["users"]["wrong_old_password"]);
			if (defined("SCRIPTO_forms")) {
			 	$tpl_form="system/classes/module_form.html";
			 } else {
			 	$tpl_form='';
			}
			if (
$engine->processFormData($frm,$lang["users"]["change"],$first,$tpl_form
			)) {
				if ($db->query("update `%USERS%` set `password`='".sql_quote($engine->generate_admin_password($newpass))."' where id_user=".$user["id_user"])) {
					$smarty->Assign("cuser",$user);
					$email=$user["email"];
					$login=$user["login"];
					$smarty->assign("password_change",true);
					$smarty->assign("password",$newpass);
					$this->mailMe($email,$this->thismodule["mailadmin"],"»зменение информации о пользователе $login на сайте ".$settings["httproot"],1);
				}
			}
			}
		}
	break;
	case $this->thismodule["register_url"]:
	$smarty->assign("m_type","register");
	if ($this->thismodule["register"]) {
		//регистраци€ пользовател€
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
			} else {
				$first=true;
				$login="";
				$family="";
				$name="";
				$otch="";
				$email="";
				$city="";
				$phone1="";
				$phone2="";
			}
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			if (is_file($this->thismodule["path"]."user_form.php")) {
				include($this->thismodule["path"]."user_form.php");
			}
			if ($this->loginExist($login))
				$frm->addError(str_replace('%login%',$login,$lang["users"]["user_exist"]));
			if ($this->emailExist($email))
				$frm->addError(str_replace('%email%',$email,$lang["users"]["email_exist"]));
			 if (defined("SCRIPTO_forms")) {
			 	$tpl_form="system/classes/module_form.html";
			 } else {
			 	$tpl_form='';
			 }
			if (
$engine->processFormData($frm,$lang["users"]["register"],$first,$tpl_form
			)) {
				 $password=$this->generatePassword();
				 $smarty->assign("password",$password);
 $add_id=$this->addUser($login,$password,$family,$name,$otch,$city,$email,$phone1,$phone2,1,'',0);
				 if ($add_id!=false) {
				   //добавили успешно!
				   $smarty->assign("register",true);
				   $user=$this->getUserByID($add_id);
				   $smarty->assign("user",$user);
				   if ($engine->checkInstallModule("subscribe")) {
				   	$subscribe=new Subscribe();
					$subscribe->doDb();
					$subscribe->addToSubscribe($user["email"],$user["fio"]);
				   }
				   $smarty->assign("registered",true);
				   $this->mailMe($email,$this->thismodule["mailadmin"],"–егистраци€ на сайте ".$settings["httproot"],0);
				 }
			}
	} else {
		$smarty->assign("register_off",true);
	}
	break;
}
?>