<?
/*
Модуль новости сайта, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
if ($engine->checkInstallModule("products")) {
$m_action=@$_REQUEST["m_action"];
if ($engine->checkInstallModule("users")) {
	$smarty->assign("users_install",true);

switch ($m_action) {
	case "top":
		$engine->clearPath();
$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('50 самых продаваемых товаров','',false);
		$engine->assignPath();
		$p=new Products();
		$p->doDb();
		$products=$p->getTopProducts(50);
		$smarty->assign("products",$products);
	break;
	case "add_discount":
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
$engine->addPath('Скидочные купоны','/admin/?module=modules&modAction=settings&m_action=discounts&module_name='.$this->thismodule["name"],true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			$values=$this->thismodule["dicount_type"];
			if (isset($_REQUEST["id_discount"])) {
				$id_discount=@$_REQUEST["id_discount"];
				$discount=$this->getDiscountByID($id_discount);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=trim(strip_tags(@$_REQUEST["caption"]));
				$code=trim(@$_REQUEST["code"]);
				$id_type=@$_REQUEST["id_type"];
				$price=@$_REQUEST["price"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($discount) {
						$caption=@$discount["caption"];
						$code=@$discount["code"];
						$id_type=@$discount["type"];
						$price=@$discount["price"];
						$lang_values=$engine->generateLangArray("DISCOUNTS",$discount);
					}
				} else {
						$caption="";
						$code='';
						$id_type=@$values[0]["id"];
						$price=0;
						$lang_values=$engine->generateLangArray("DISCOUNTS",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("Название","Неверно заполнено поле название купона","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"",array('size'=>'40'));

$frm->addField("Код купона (англ.)","Неверно заполнен код купона","text",$code,$code,"/^[a-zA-Z0-9]{2,255}$/i","code",1,"HK834S",array('size'=>'40','ticket'=>'Разрешены только латинские буквы, либо цифры, от 2 до 255 символов'));

$frm->addField("Тип купона","Неверно выбран тип купона","list",$values,$id_type,"/^[0-9]{1}$/i","id_type",0,"",array('size'=>'40'));

$frm->addField("Скидка","Неверно заполнена скидка","text",$price,$price,"/^[0-9]{1,5}$/i","price",1,"",array('size'=>'40'));

$engine->generateLangControls("DISCOUNTS",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_discount"])) {
$id_discount=$_REQUEST["id_discount"];
$frm->addField("","","hidden",$id_discount,$id_discount,"/^[^`]{0,}$/i","id_discount",1);
}
$smarty->assign("mode",$mode);

if ($mode=="edit") {
	$engine->addPath('Редактирование скидочного купона','',false);
	$m_button="Редактировать";
	if ($code!='')
	if ($code!=$discount["code"])
		if ($this->existDiscount($code))
			$frm->addError("Купон с кодом $code уже существует!");
} else {
	$engine->addPath('Добавление скидочного купона','',false);
	$m_button="Добавить";
	if ($code!='')
	if ($this->existDiscount($code))
		$frm->addError("Купон с кодом $code уже существует!");
}

if ($id_type==1 && $price>$this->thismodule["max_percent"])
	$frm->addError("Максимальная скидка по купону не может быть более 50%");

			if (
$engine->processFormData($frm,$m_button,$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_discount)) {
				 	if ($db->query("update %DISCOUNTS% set `caption`='".sql_quote($caption)."' , `code`='".sql_quote($code)."',`type`=$id_type, `price`=$price ".$engine->generateUpdateSQL("DISCOUNTS",$lang_values)." where id_discount=$id_discount")) {
						//отредактировали
				      $engine->setCongratulation("Интернет магазин","Информация о скидочном купоне отредактирована успешно!",5000);
					  $m_action="discounts";
					}
				 }
				} else {
				 //добавляем
 $add_id=$this->addDiscount($caption,$code,$id_type,$price,$engine->generateInsertSQL("DISCOUNTS",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				    $engine->setCongratulation("Интернет магазин","Скидочный купон добавлен успешно!",5000);
					$m_action="discounts";
				 }
				}
			}
		$engine->assignPath();
	break;
	case "delete_discount":
		$id_discount=@$_REQUEST["id_discount"];
		if (preg_match("/^[0-9]{1,}$/i",$id_discount)) {
			$db->query("DELETE from `%DISCOUNTS%` where id_discount=$id_discount");
			$engine->setCongratulation("Интернет магазин","Скидочный купон удален успешно!",5000);
		}
		$m_action="discounts";
	case "discounts":
		
	break;
	case "opt":
		$engine->clearPath();
$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Оптовые скидки','',false);
		$engine->assignPath();
		if (isset($_REQUEST["addnew"])) {
			//Добавляем новую скидку оптовую
			$from=trim(strip_tags(@$_REQUEST["from"]));
			$percent=trim(strip_tags(@$_REQUEST["percent"]));
			if (preg_match("/^[0-9]{1,2}$/i",$percent) && preg_match("/^[0-9]{1,}$/i",$from)) {
				if (!$this->existOpt($from)) {
					if ($this->addOpt($from,$percent,1)) {
						$engine->setCongratulation('',"Скидка с порогом $from добавлена!",3000);
					} else {
						$engine->setCongratulation('Ошибка',"При добавлении скидки произошла ошибка!",3000);
					}
				} else {
					$engine->setCongratulation('Ошибка',"Скидка с заданным порогом $from уже существует!",5000);
				}
			}
		}
		if (isset($_REQUEST["save"])) {
			$percent=@$_REQUEST["percent"];
			$active=@$_REQUEST["active"];
			if (is_array($percent)) {
				$u=0;
				foreach ($percent as $id_opt=>$op) {
			 	 if (preg_match("/^[0-9]{1,}$/i",$id_opt)) {
					if (!preg_match("/^[0-9]{1,2}$/i",$op)) {
						$op=0;
					}
					if (isset($active[$id_opt])) {
						$act=1;
					} else {
						$act=0;
					}
					if ($db->query("update `%OPT%` set `percent`=$op, `active`=$act where `id_opt`=$id_opt")) {
						$u++;
					}
				 }
				}
				$engine->setCongratulation('',"Обновлено $u скидок!",3000);
			}
		}
		if (isset($_REQUEST["delete"]) && !isset($_REQUEST["save"])) {
			$id_opt=@$_REQUEST["id_opt"];
			if (preg_match("/^[0-9]{1,}$/i",$id_opt)) {
				if ($db->query("delete from `%OPT%` where `id_opt`=$id_opt")) {
					$engine->setCongratulation('','Скидка удалена!',3000);
				}
			}
		}
		$opts=$this->getAllOpts(0);
		$smarty->assign("opts",$opts);
	break;
	case "add_payment":
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
$engine->addPath('Способы оплаты','/admin/?module=modules&modAction=settings&m_action=payment&module_name='.$this->thismodule["name"],true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_payment"])) {
				$id_payment=@$_REQUEST["id_payment"];
				$payment=$this->getpaymentByID($id_payment);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$description=@$_REQUEST["description"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($payment) {
						$caption=@$payment["caption"];
						$description=@$payment["description"];
						$lang_values=$engine->generateLangArray("PAYMENT",$payment);
					}
				} else {
						$caption="";
						$description="";
						$lang_values=$engine->generateLangArray("PAYMENT",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("Название","Неверно заполнено поле название","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"",array('size'=>'40'));

$frm->addField("Описание","Неверно заполнено поле описание","textarea",$description,$description,"/^[^`#]{2,255}$/i","description",0,"",array('size'=>'40'));

$engine->generateLangControls("PAYMENT",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_payment"])) {
$id_payment=$_REQUEST["id_payment"];
$frm->addField("","","hidden",$id_payment,$id_payment,"/^[^`]{0,}$/i","id_payment",1);
}
$smarty->assign("mode",$mode);

if ($mode=="edit") {
	$engine->addPath('Редактирование способа оплаты','',false);
	$m_button="Редактировать";
} else {
	$engine->addPath('Добавление способа оплаты','',false);
	$m_button="Добавить";
}
			if (
$engine->processFormData($frm,$m_button,$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_payment)) {
				 	if ($db->query("update %PAYMENT% set `caption`='".sql_quote($caption)."' , `description`='".sql_quote($description)."' ".$engine->generateUpdateSQL("PAYMENT",$lang_values)." where id_payment=$id_payment")) {
						//отредактировали
				   $engine->setCongratulation("Интернет магазин","Информация о способе оплаты отредактирована успешно!",5000);
					$m_action="payment";
					}
				 }
				} else {
				 //добавляем
 $add_id=$this->addPayment($caption,$description,$engine->generateInsertSQL("PAYMENT",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $engine->setCongratulation("Интернет магазин","Новый способ оплаты добавлен успешно!",5000);
					$m_action="payment";
				 }
				}
			}
		$engine->assignPath();
	break;
	case "delete_payment":
		$id_payment=@$_REQUEST["id_payment"];
		if (preg_match("/^[0-9]{1,}$/i",$id_payment)) {
			$db->query("DELETE from `%PAYMENT%` where id_payment=$id_payment");
			$engine->setCongratulation("Интернет магазин","Способ оплаты удален успешно!",5000);
		}
		$m_action="payment";
	case "payment":
		//просмотр способов оплаты

	break;
	case "view_order":
		$id_order=@$_REQUEST["id_order"];
		if (preg_match("/^[0-9]{1,}$/i",$id_order)) {
			$order=$this->getOrderByID($id_order);
			$smarty->assign("order",$order);
			$usr=new Users();
			$usr->doDb();
			$user=$usr->getUserByID($order["id_user"]);
			$smarty->assign("user",$user);
			$db->query("update `%ORDERS%` set `view`=0 where id_order=".$order["id_order"]);
		}
	break;
	case "orders":

	break;
	case "add_delivery":
		$engine->clearPath();
$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
$engine->addPath('Способы доставки','/admin/?module=modules&modAction=settings&m_action=delivery&module_name='.$this->thismodule["name"],true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_delivery"])) {
				$id_delivery=@$_REQUEST["id_delivery"];
				$delivery=$this->getDeliveryByID($id_delivery);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$price=@$_REQUEST["price"];
				$description=@$_REQUEST["description"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($delivery) {
						$caption=@$delivery["caption"];
						$price=@$delivery["price"];
						$description=@$delivery["description"];
						$lang_values=$engine->generateLangArray("DELIVERY",$delivery);
					}
				} else {
						$caption="";
						$price=0;
						$description="";
						$lang_values=$engine->generateLangArray("DELIVERY",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("Название","Неверно заполнено поле название","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"",array('size'=>'40','ticket'=>""));

$frm->addField("Стоимость","Неверно заполнено поле стоимость","text",$price,$price,"/^[0-9]{1,25}$/i","price",0,"",array('size'=>'40'));

$frm->addField("Описание","Неверно заполнено поле описание","textarea",$description,$description,"/^[^`#]{2,255}$/i","description",0,"",array('size'=>'40'));

$engine->generateLangControls("DELIVERY",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_delivery"])) {
$id_delivery=$_REQUEST["id_delivery"];
$frm->addField("","","hidden",$id_delivery,$id_delivery,"/^[^`]{0,}$/i","id_delivery",1);
}
$smarty->assign("mode",$mode);
if ($mode=="edit") {
	$engine->addPath('Редактирование способа доставки','',false);
	$m_button="Редактировать";
} else {
	$engine->addPath('Добавление способа доставки','',false);
	$m_button="Добавить";
}
			if (
$engine->processFormData($frm,$m_button,$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_delivery)) {
				 	if ($db->query("update %DELIVERY% set `caption`='".sql_quote($caption)."' , `description`='".sql_quote($description)."',`price`=$price ".$engine->generateUpdateSQL("DELIVERY",$lang_values)." where id_delivery=$id_delivery")) {
						//отредактировали
				//	   $modAction="view";
				   $engine->setCongratulation("Интернет магазин","Информация о способе доставки отредактирована успешно!",5000);
					$m_action="delivery";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
 $add_id=$this->addDelivery($caption,$price,$description,$engine->generateInsertSQL("DELIVERY",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $engine->setCongratulation("Интернет магазин","Новый способ доставки добавлен успешно!",5000);
					$m_action="delivery";
				 }
				}
			}
			$engine->assignPath();
	break;
	case "delete_delivery":
		$id_delivery=@$_REQUEST["id_delivery"];
		if (preg_match("/^[0-9]{1,}$/i",$id_delivery)) {
			$db->query("DELETE from `%DELIVERY%` where id_delivery=$id_delivery");
			$engine->setCongratulation("Интернет магазин","Способ доставки удален успешно!",5000);
		}
		$m_action="delivery";
	case "delivery":
		//просмотр спообов доставки

	break;
	default:
		$m_action="orders";
}
if ($m_action=="discounts") {
		$engine->clearPath();
$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Скидочные купоны','',false);
		$engine->assignPath();
		if (isset($_REQUEST["savediscounts"])) {
			$iddisc=@$_REQUEST["iddisc"];
			$active=@$_REQUEST["active"];
			foreach ($iddisc as $id_disc) {
			 if (is_array($active)) {
				if (isset($active[$id_disc])) {
					$db->query("update `%DISCOUNTS%` set `active`=1 where `id_discount`=$id_disc");
				} else {
					$db->query("update `%DISCOUNTS%` set `active`=0 where `id_discount`=$id_disc");
				}
			 } else {
			 	$db->query("update `%DISCOUNTS%` set `active`=0");
			 }
			}
			$engine->setCongratulation("Интернет магазин","Данные сохранены",3000);
		}
		$discount=$this->getDiscounts();
		$smarty->assign("discount",$discount);
}
if ($m_action=="delivery") {
		$engine->clearPath();
$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Способы доставки','',false);
		$engine->assignPath();
		$delivery=$this->getDeliviries();
		$smarty->assign("delivery",$delivery);
}
if ($m_action=="payment") {
		$engine->clearPath();
$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Способы оплаты','',false);
		$engine->assignPath();
		$payment=$this->getPayments();
		$smarty->assign("payment",$payment);
}
if ($m_action=="orders") {
		//Просмотр заказов
		$engine->clearPath();
$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		
		
		$usr=new Users();
		$usr->doDb();
		$users=$usr->getAllUsers();
		$smarty->Assign("users",$users);
		if (isset($_REQUEST["id_user"])) {
			$id_user=$_REQUEST["id_user"];
			if (!preg_match("/^[0-9]{1,}$/i",$id_user)) $id_user=0;
			if ($id_user>0) {
			$smarty->assign("with_user",true);
			$cuser=$usr->getUserByID($id_user);
			$smarty->assign("id_user",$cuser["id_user"]);
			$engine->addPath('Заказы','/admin/?module=modules&modAction=settings&module_name=basket&m_action=orders',true);
			$engine->addPath('Просмотр заказов пользователя '.$cuser["fio"],'',false);
			} else {
			$engine->addPath('Заказы','',false);
			}
		} else {
			$id_user=0;
			$engine->addPath('Заказы','',false);
		}
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
		$engine->assignPath();
					$count=$this->getCountOrders($id_user,$str);
					$pages=ceil($count/$this->thismodule["onpage_orders"]);
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
						if (isset($pages_arr)) {
							$smarty->assign("pages",$pages_arr);
							$smarty->assign("pagenumber",$pg);
						}
	$orders=$this->getOrders($id_user,$str,$pg,$this->thismodule["onpage_orders"]);
	$smarty->assign("orders",$orders);
}
$smarty->assign("m_action",$m_action);
} else {
$smarty->assign("users_not_install",true);
}
} else {
$smarty->assign("products_not_install",true);
}
?>