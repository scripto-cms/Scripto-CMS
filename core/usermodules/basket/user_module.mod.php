<?
if ($engine->checkInstallModule("products") && $engine->checkInstallModule("users")) {
global $page;
$m_action=@$_REQUEST["m_action"];
$type="";
				 $usr=new Users();
				 $usr->doDb();
switch ($page["ident"]) {
	case $this->thismodule["basket_url"]:
		//корзина
		$type="basket";
		switch ($m_action) {
			case "add":
				//добавление в корзину
				$products=@$_REQUEST["products"];
				$variants=@$_REQUEST["variants"];
				$options=@$_REQUEST["options"];
				$caption=@$_REQUEST["caption"];
				if (is_array($products)) {
				foreach ($products as $id_prod=>$prod) {
				$prodarr=array();
				if (isset($variants[$id_prod])) {
					foreach ($variants[$id_prod] as $id_variant=>$var) {
					if ($var) {
						if (isset($_SESSION["basket"][$id_prod][$id_variant])) {
						$_SESSION["basket"][$id_prod][$id_variant]["count"]++;
						} else {
						$prodarr["count"]=1;
						$prodarr["id_product"]=$id_prod;
						$prodarr["id_variant"]=$id_variant;
						$_SESSION["basket"][$id_prod][$id_variant]=$prodarr;
						if (is_array($options))
							foreach ($options as $id_option=>$option) 
								if (!isset($_SESSION["basket"][$id_prod][$id_variant]["options"][$id_option][$option])) 
								if (isset($caption[$id_option]))	$_SESSION["basket"][$id_prod][$id_variant]["options"][$id_option][$option]=$caption[$id_option];
							
						}
					}
					}
				} else {
				if ($prod) {
				 if (isset($_SESSION["basket"][$id_prod][0])) {
						$_SESSION["basket"][$id_prod][0]["count"]++;
				 } else {
						$prodarr["count"]=1;
						$prodarr["id_product"]=$id_prod;
						$_SESSION["basket"][$id_prod][0]=$prodarr;
				 }
						if (is_array($options))
							foreach ($options as $id_option=>$option) 
								if (!isset($_SESSION["basket"][$id_prod][0]["options"][$id_option][$option])) 
								if (isset($caption[$id_option]))	$_SESSION["basket"][$id_prod][0]["options"][$id_option][$option]=$caption[$id_option];
				}
				}
				}
				}
			break;
			case "save":
				$id_product=@$_REQUEST["id_product"];
				$id_variant=@$_REQUEST["id_variant"];
				$count=@$_REQUEST["count"];
				$del=@$_REQUEST["del"];
				foreach ($id_product as $k=>$prod) {
					if (isset($id_variant[$k])) {
						$id_var=$id_variant[$k];
					} else {
						$id_var=0;
					}
					if (isset($del[$k])) {
						unset($_SESSION["basket"][$prod][$id_var]);
						if (isset($_SESSION["basket"][$prod]))
						if (sizeof($_SESSION["basket"][$prod])==0) 
							unset($_SESSION["basket"][$prod]);
					} else {
						$_SESSION["basket"][$prod][$id_var]["count"]=$count[$k];
					}
				}
				
			break;
		}
		$opts=$this->getAllOpts(1);
		$smarty->assign("opts",$opts);
		if (isset($_SESSION["basket"])) {
			$basket=$this->calculateBasket($_SESSION["basket"]);
			$smarty->assign("basket",$basket);
		}
		/*определяем url для добавления товаров к сравнению*/
		 $lng='';
		 if (isset($engine->languages[$engine->current_language])) {
	 		if ($engine->languages[$engine->current_language]["default"]==0) {
				$lng=$engine->current_language.'/';
			}
		 }
		$prod=$engine->getModule('products');
		$prod["favorite_url"]=$lng.'/'.$prod["favorite_url"];
		$smarty->assign("favorite_url",$prod["favorite_url"]);
	break;
	case $this->thismodule["order_url"]:
		//оформление заказа
		$type="order";
		switch ($m_action) {
			case "step2":
			//регистрация и оформление заказа
		if (isset($_SESSION["basket"])) {
			$basket=$this->calculateBasket($_SESSION["basket"]);
			$smarty->assign("basket",$basket);
			$mode=@$_REQUEST["mode"];
			if ($mode=="auth") {
			if ($usr->authUser(@$_SESSION["user_login"],@$_SESSION["user_password"])) {
				$user=$usr->getUserByLogin($_SESSION["user_login"]);
				$smarty->assign("user",$user);
			} else {
				$mode="new";
			}
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$login=strip_tags(trim(@$_REQUEST["login"]));
				$family=strip_tags(trim(@$_REQUEST["family"]));
				$name=strip_tags(trim(@$_REQUEST["name"]));
				$otch=strip_tags(trim(@$_REQUEST["otch"]));
				$email=@$_REQUEST["email"];
				$city=strip_tags(trim(@$_REQUEST["city"]));
				$phone1=strip_tags(trim(@$_REQUEST["phone1"]));
				$phone2=strip_tags(trim(@$_REQUEST["phone2"]));
				$id_delivery=@$_REQUEST["id_delivery"];
				$id_payment=@$_REQUEST["id_payment"];
				$address=strip_tags(trim(@$_REQUEST["address"]));
				$comment=strip_tags(trim(@$_REQUEST["comment"]));
				$coupon=trim(strip_tags(@$_REQUEST["coupon"]));
			} else {
				$first=true;
				if ($mode=="auth") {
					if ($user) {
						$login=$user["login"];
						$family=$user["family"];
						$name=$user["name"];
						$otch=$user["otch"];
						$email=$user["email"];
						$city=$user["city"];
						$phone1=$user["phone1"];
						$phone2=$user["phone2"];
						$id_delivery="";
						$id_payment="";
						$address="";
						$comment="";
						$coupon="";
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
					$id_delivery="";
					$id_payment="";
					$address="";
					$comment="";
					$coupon="";
				}
			}
			$deliveries=$this->getDeliviries();
			if (is_array($deliveries)) {
			$del_array=array();
			foreach ($deliveries as $del) {
				$d=array();
				$d["name"]=$del["caption"]." (".$del["price"]." руб.)";
				$d["id"]=$del["id_delivery"];
				$d["description"]=$del["description"];
				$del_array[]=$d;
				unset($d);
			}
			}
			
			$payments=$this->getPayments();
			if (is_array($payments)) {
			$paym_array=array();
			foreach ($payments as $paym) {
				$d=array();
				$d["name"]=$paym["caption"];
				$d["id"]=$paym["id_payment"];
				$d["description"]=$paym["description"];
				$paym_array[]=$d;
				unset($d);
			}
			}
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
if ($mode=="new") {
$frm->addField($lang["users"]["login"]["caption"],$lang["users"]["login"]["error"],"text",$login,$login,"/^[a-zA-Z0-9]{5,}$/i","login",1,"dmitry991984",array('size'=>'40','ticket'=>"Цифры и латинские буквы, от 5 до 10 символов"));
}

$frm->addField($lang["users"]["family"]["caption"],$lang["users"]["family"]["error"],"text",$family,$family,"/^[^`#]{2,255}$/i","family",0,$lang["users"]["family"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["name"]["caption"],$lang["users"]["name"]["error"],"text",$name,$name,"/^[^`#]{2,255}$/i","name",1,$lang["users"]["name"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["otch"]["caption"],$lang["users"]["otch"]["error"],"text",$otch,$otch,"/^[^`#]{2,255}$/i","otch",0,$lang["users"]["otch"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["city"]["caption"],$lang["users"]["city"]["error"],"text",$city,$city,"/^[^`#]{2,255}$/i","city",0,$lang["users"]["city"]["sample"],array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField($lang["users"]["email"]["caption"],$lang["users"]["email"]["error"],"text",$email,$email,"/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i","email",1,$lang["users"]["email"]["sample"],array('size'=>'40','ticket'=>""));

$frm->addField($lang["users"]["phone1"]["caption"],$lang["users"]["phone1"]["error"],"text",$phone1,$phone1,"/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i","phone1",0,$lang["users"]["phone1"]["sample"],array('size'=>'40','ticket'=>""));

$frm->addField($lang["users"]["phone2"]["caption"],$lang["users"]["phone2"]["error"],"text",$phone2,$phone2,"/^[+]?[0-9]?[ -]?[(]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[)]?[- .]?[0-9]{3}[- .]?[0-9]{2,4}[- .]?[0-9]{2,4}$/i","phone2",1,$lang["users"]["phone2"]["sample"],array('size'=>'40','ticket'=>""));

$frm->addField($lang["basket"]["enter_kupon_code"],$lang["basket"]["enter_kupon_error"],"text",$coupon,$coupon,"/^[a-zA-Z0-9]{2,255}$/i","coupon",0,'',array('size'=>'40'));

$frm->addField($lang["users"]["address"],$lang["users"]["address_error"],"textarea","",$address,"/^[^#]{1,}$/i","address",1,'');

if (is_array($deliveries)) {

$frm->addField($lang["users"]["choosedelivery"]["caption"],$lang["users"]["choosedelivery"]["error"],"optionbutton",$del_array,$id_delivery,"/^[0-9]{1,}$/i","id_delivery",1,"",array('size'=>'40','ticket'=>""));

}

if (is_array($payments)) {

$frm->addField($lang["users"]["choosepayment"]["caption"],$lang["users"]["choosepayment"]["error"],"optionbutton",$paym_array,$id_payment,"/^[0-9]{1,}$/i","id_payment",1,"",array('size'=>'40','ticket'=>""));

}

$frm->addField($lang["users"]["ordercomment"]["caption"],$lang["users"]["ordercomment"]["error"],"textarea","",$comment,"/^[^#]{1,}$/i","comment",0,'');

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$smarty->assign("mode",$mode);
if ($mode=="new") {
 if ($this->loginExist($login)) 
	$frm->addError(str_replace('%login%',$login,$lang["users"]["existlogin"]["caption"]));
if ($this->emailExist($email))
	$frm->addError(str_replace('%email%',$email,$lang["users"]["email_exist"]));
}
if ($coupon!='')
	if (!$this->existActiveDiscount($coupon))
		$frm->addError($lang["basket"]["enter_kupon_error"]);
			 if (defined("SCRIPTO_forms")) {
			 	$tpl_form="system/classes/module_form.html";
			 } else {
			 	$tpl_form='';
			 }
			if (
$engine->processFormData($frm,$lang["users"]["next"],$first,$tpl_form
			)) {
				$smarty->assign("ordered",true);
				if ($mode=="new") {
				 $password=$this->generatePassword();
				 $smarty->assign("password",$password);
 $add_id=$usr->addUser($login,$password,$family,$name,$otch,$city,$email,$phone1,$phone2,1,'');
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $user=$this->getUserByID($add_id);
				   $smarty->assign("user",$user);
				   if ($engine->checkInstallModule("subscribe")) {
				   	$subscribe=new Subscribe();
					$subscribe->doDb();
					$subscribe->addToSubscribe($user["email"],$user["fio"]);
				   }
				   $this->mailMe($email,$this->thismodule["mailadmin"],"Регистрация в интернет магазине",0);
					if (is_array($user)) {
						$_SESSION["user_login"]=$user["login"];
						$_SESSION["user_password"]=$user["password"];
					}
				 }
				} else {
					$db->query("update %USERS% set family='".sql_quote($family)."' , `name`='".sql_quote($name)."',`otch`='".sql_quote($otch)."',city='".sql_quote($city)."',`email`='".sql_quote($email)."',`phone1`='".sql_quote($phone1)."',`phone2`='".sql_quote($phone2)."' where id_user=".$user["id_user"]);
				}
				if ($id_delivery>0) {
				$delivery=$this->getDeliveryByID($id_delivery);
				}
				if ($id_payment>0) {
				$payment=$this->getPaymentByID($id_payment);
				}
				if (isset($delivery["price"])) {
				$final=$delivery["price"];
				} else {
				$final=0;
				}
				if (is_array($basket["products"])) {
					foreach ($basket["products"] as $var) {
					foreach ($var as $prod) {
						$final=$final+$prod["price1"]*$prod["count"];
					}
					}
				}
				$basket["final"]=$final;
			if (!isset($delivery)) $delivery=array();
			if (!isset($payment)) $payment=array();
			//Считаем скидки
			if ($this->existActiveDiscount($coupon)) {
				//купон существует
				$coup=$this->getUserDiscount($coupon,$user["login"],true);
			} else {
				$coup=$this->getEmptyDiscount();
			}
			$discount=$this->getEmptyGroupDiscount();
			if ($this->thismodule["mode"]=="max") {
				$max=0;
				if ($coup["price"]>0) {
					switch($coup["type"]) {
						case 0:
						//скидка в рублях
							$max=$coup["price"];
						break;
						case 1:
						//скидка в процентах
						if ($coup["price"]<=$this->thismodule["max_percent"]) {
							$disc=round($basket["final"]*($coup["price"]/100),2);
							$max=$disc;
						}
						break;
					}
				}
				if ($user["id_group"]>0) {
					$group=$usr->getGroupByID($user["id_group"]);
					$value=round($basket["final"]*($group["percent"]/100),2);
					if ($value>$max) {
						$max=$value;
						unset($coup);
						$coup=$this->getEmptyDiscount();
						$discount["caption"]=$lang["basket"]["discount_value"].$group["percent"].'%';
						$discount["itog"]=$basket["final"]-$value;
						if ($discount["itog"]<0)
							$discount["itog"]=0;
						$discount["price"]=$value;
					}
				}
				//вычисляем оптовую скидку
				$opts=$this->getAllOpts(1);
				$id_opt=0;
				if (is_array($opts))
				foreach ($opts as $op) {
					if ($basket["final"]>$op["from"]) {
						$id_opt=$op["from"];
					}
				}
				if ($id_opt>0 && isset($opts[$id_opt])) {
					$val=round($basket["final"]*($opts[$id_opt]["percent"]/100),2);
					if ($val>$max) {
						$max=$val;
						$coup=$this->getEmptyDiscount();
						$discount["caption"]=$lang["basket"]["opt_value"].$opts[$id_opt]["percent"].'%';
						$discount["itog"]=$basket["final"]-$val;
						if ($discount["itog"]<0)
							$discount["itog"]=0;
						$discount["price"]=$val;
					}
				}
			}
			$order=$this->createOrder($user,$basket,$delivery,$payment,$address,$comment,$coup,$discount);
			}
		}
			break;
			default:
			if (!isset($_SESSION["user_login"]) && !isset($_SESSION["user_password"])) {
				//неавторизированы
				$smarty->assign("not_authorized",true);
				$user_type=@$_REQUEST["user_type"];
				if ($user_type=="new") {
					header("location:".$page["url"]."?m_action=step2&mode=new");
				} elseif ($user_type=="auth") {
					$login=trim(@$_REQUEST["login"]);
					$password=$engine->generate_admin_password(trim(@$_REQUEST["password"]));
					if ($usr->authUser($login,$password)) {
						header("location:".$page["url"]."?m_action=step2&mode=auth");
						$_SESSION["user_login"]=$login;
						$_SESSION["user_password"]=$password;
						$_SESSION["auth"]=true;
					} else {
						$usr->clearAuth();
						$smarty->assign("wrong_password",true);
					}
				}
			} else {
				//проверка зарегистрированного
				if ($usr->authUser($_SESSION["user_login"],$_SESSION["user_password"])) {
					header("location:".$page["url"]."?m_action=step2&mode=auth");
				} else {
					$smarty->assign("not_authorized",true);
					$usr->clearAuth();
					$smarty->assign("wrong_password",true);
				}
			}
		}
	$smarty->assign("m_action",$m_action);
	break;
}
$smarty->assign("m_type",$type);
$smarty->assign("order_url",$this->thismodule["order_url"]);
} else {
$smarty->assign("not_install_products",true);
}
?>