<?
/*
Модуль товары
*/

define("SCRIPTO_basket",true);

class basket {
	var $config;
	var $db;
	var $settings;
	var $lang;
	var $smarty;
	var $thismodule;
	var $engine;
	var $page;
	
	function doDb() {
		global $db;
		global $config;
		$db->addPrefix("%ORDERS%",$config["db"]["prefix"]."orders");
		$db->addPrefix("%DELIVERY%",$config["db"]["prefix"]."delivery");
		$db->addPrefix("%PAYMENT%",$config["db"]["prefix"]."payment");
		$db->addPrefix("%ORDER_PRODUCTS%",$config["db"]["prefix"]."order_products");
		$db->addPrefix("%DISCOUNTS%",$config["db"]["prefix"]."discounts");
		$db->addPrefix("%LOGIN_DISCOUNTS%",$config["db"]["prefix"]."login_discounts");
		$db->addPrefix("%OPT%",$config["db"]["prefix"]."opt");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Корзина','',$type_id,'basket',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
			return true;
		}  else {
			return false;
		}
	}
	
	function doUninstall() {
		return true;
	}
	
	function doUpdate() {
		return true;
	}
	
	function doBlockAdmin($block,$page) {
		return "";
	}
	
	function doBlock($block,$page,&$objects) {
		global $db;
		global $smarty;
		if (isset($_SESSION["basket"])) {
			$basket=$this->calculateBasket($_SESSION["basket"]);
			$smarty->assign("basket",$basket);
		}
		$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_block.tpl.html");
		}
		return $content;
	}
	
	function checkMe() {
	//проверяем существуют ли уже таблицы модуля
		global $engine;
		if ($engine->checkInstallModule("basket")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getNewReminders() {
		global $db;
		$res=$db->query("SELECT id_order from `%ORDERS%` where `view`=1");
		$count=@mysql_num_rows($res);
		if ($count>0) {
			$reminder['subject']=ToUTF8($this->thismodule["caption"]);
			$reminder['content']=ToUTF8('У Вас '.@mysql_num_rows($res).' новых заказов в интернет магазине');
			$reminder['count']=$count;
			return $reminder;
		} else {
			return false;
		}
	}
	
	function doStatic() {
		global $config;
		global $settings;
		global $db;
		global $page;
		global $lang;
		global $smarty;
		global $thismodule;
		global $engine;
		
		if (is_file($this->thismodule["path"]."user_module.mod.php")) {
			include($this->thismodule["path"]."user_module.mod.php");
			//здесь получаем товары для рубрик
$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module.tpl.html");
		}
			return $content;
		} else {
			return "not load";
		}
	}
	
	function doAdmin() {
		global $config;
		global $settings;
		global $db;
		global $lang;
		global $smarty;
		global $thismodule;
		global $engine;
		
		if (is_file($this->thismodule["path"]."admin_module.mod.php")) {
			include($this->thismodule["path"]."admin_module.mod.php");
			$content=$smarty->fetch($this->thismodule["template_path"]."admin_module.tpl.html");
			return $content;
		} else {
			return "not load";
		}
	}
	
	function doUser() {
		global $config;
		global $settings;
		global $db;
		global $lang;
		global $smarty;
		global $thismodule;
		global $engine;
		
		if (is_file($this->thismodule["path"]."user_module.mod.php")) {
			include($this->thismodule["path"]."user_module.mod.php");
			$fname=$this->config["pathes"]["templates_dir"].$this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html";
		if (is_file($fname)) {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module".$this->engine->current_prefix.".tpl.html");
		} else {
		$content=$smarty->fetch($this->thismodule["template_path"]."user_module.tpl.html");
		}
			return $content;
		} else {
			return "not load";
		}
	}
	
	function clearAuth() {
		if (isset($_SESSION["shop_login"]) || isset($_SESSION["shop_password"])) {
			unset($_SESSION["shop_login"]);
			unset($_SESSION["shop_password"]);
		}
	}
	
	function loginExist($login) {
		global $db;
		if (preg_match("/^[a-zA-Z0-9]{2,10}$/i",$login)) {
			if ($db->getCount("select id_user from %USERS% where login='$login'")>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function addUser($login,$password,$family,$name,$otch,$city,$email,$phone1,$phone2) {
		global $db;
		global $engine;
		if ($db->query("insert into `%USERS%` values (null,'$login','".$engine->generate_admin_password($password)."','".sql_quote($family)."','".sql_quote($name)."','".sql_quote($otch)."','$email','".sql_quote($city)."','$phone1','$phone2','".date("Y-m-d H:i:s")."')")) {
			return mysql_insert_id();
		} else {
			echo mysql_error();
			return false;
		}
	}

	function addDelivery($caption,$price,$description,$sql='') {
		global $db;
		if ($db->query("insert into `%DELIVERY%` values (null,'".sql_quote($caption)."','$price','".sql_quote($description)."'".$sql.")")) {
			return mysql_insert_id();
		} else {
			echo mysql_error();
			return false;
		}
	}
	
	function addPayment($caption,$description,$sql='') {
		global $db;
		if ($db->query("insert into `%PAYMENT%` values (null,'".sql_quote($caption)."','".sql_quote($description)."'".$sql.")")) {
			return mysql_insert_id();
		} else {
			echo mysql_error();
			return false;
		}
	}
	
	function addDiscount($caption,$code,$id_type=0,$price,$sql='') {
		global $db;
		if ($db->query("insert into `%DISCOUNTS%` values (null,'".sql_quote($caption)."','".sql_quote($code)."',$id_type,$price,1".$sql.")")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
function generatePassword($minLength = 8, $maxLength = 12, $maxSymbols = 0)
{
    $symbolCount = 0;

    srand((double)microtime() * 1000003);

    for ($i = 0; $i < rand($minLength, $maxLength); $i++)
    {
        do
        {
            $char = rand(33, 126);

            $symbolCount += $isSymbol = (!in_array($char, range(48, 57)) && !in_array($char, range(65, 90)) && !in_array($char, range(97, 122)));

            if ($symbolCount <= $maxSymbols || !$isSymbol)
            {
                break;
            }
        }
        while (true);

        $passwd = sprintf('%s%c', isset($passwd) ? $passwd : NULL, $char);
    }

    return $passwd;
}	

	function getUserByLogin($login) {
		global $db;
		if (!preg_match("/^[a-zA-Z0-9]{2,10}$/i",$login)) return false;
		$res=$db->query("select * from %USERS% where login='$login'");
		return $db->fetch($res);
	}

	function getUserByID($id_user=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_user)) return false;
		$res=$db->query("select * from %USERS% where id_user=$id_user");
		$row=$db->fetch($res);
		$row["fio"]=$row["family"].' '.$row["name"].' '.$row["otch"];
		$row["permissions"]=@unserialize($row["access"]);
		return $row;
	}
	
	function getDeliveryByID($id_delivery=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_delivery)) return false;
		$res=$db->query("select * from %DELIVERY% where id_delivery=$id_delivery");
		return $db->fetch($res);
	}
	
	function emailExist($email) {
		global $db;
		if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email)) {
			if ($db->getCount("select id_user from %USERS% where email='$email'")>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getPaymentByID($id_payment=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_payment)) return false;
		$res=$db->query("select * from %PAYMENT% where id_payment=$id_payment");
		return $db->fetch($res);
	}
	
	function getDiscountByID($id_discount=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_discount)) return false;
		$res=$db->query("select * from %DISCOUNTS% where id_discount=$id_discount");
		return $db->fetch($res);
	}
	
	function mailMe($to="",$from="",$subject="",$mail_type) {
		global $smarty;
		switch ($mail_type) {
			case 0:
			//регистрация
	$mailtext=$smarty->fetch($this->thismodule["template_path"]."register.mail.tpl");
			break;
			case 1:
			//изменение
	$mailtext=$smarty->fetch($this->thismodule["template_path"]."edit.mail.tpl");
			break;
			case 2:
			//Письмо админу
	$mailtext=$smarty->fetch($this->thismodule["template_path"]."order.admin.mail.tpl");
			break;
			case 3:
			//Письмо пользователю
	$mailtext=$smarty->fetch($this->thismodule["template_path"]."order.mail.tpl");
			break;
		}
		mailHTML($to,$from,$subject,$mailtext);
		return false;
	}
	
	function getClients() {
		global $db;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `date_print` from %USERS% order by `login`");;
		while ($row=$db->fetch($res)) {
			$users[]=$row;
		}
		if (isset($users)) return $users;
		return false;
	}
	
	function getDeliviries() {
		global $db;
		$res=$db->query("select * from %DELIVERY%");;
		while ($row=$db->fetch($res)) {
			$delivery[]=$row;
		}
		if (isset($delivery)) return $delivery;
		return false;
	}
	
	function getPayments() {
		global $db;
		$res=$db->query("select * from %PAYMENT%");;
		while ($row=$db->fetch($res)) {
			$payment[]=$row;
		}
		if (isset($payment)) return $payment;
		return false;
	}
	
	function getDiscounts() {
		global $db;
		$res=$db->query("select * from %DISCOUNTS% order by `caption` ASC");
		while ($row=$db->fetch($res)) {
			$discounts[]=$row;
		}
		if (isset($discounts)) return $discounts;
		return false;
	}
	
	function doProducts() {
			$products=new Products();
			$products->doDb();
			return $products;
	}
	
	function calculateBasket($basket=false) {
		global $db;
		global $engine;
		if (is_array($basket)) {
		$price=0;
		$basketinfo=array();
		$basketinfo["itogo"]=0;
		$products=$this->doProducts();
				foreach ($basket as $id_prod=>$prod) {
				if (is_array($prod) && preg_match("/^[0-9]{1,}$/i",$id_prod)) 
				foreach ($prod as $id_var=>$prodarr) {
					if (isset($prodarr["id_variant"])) {
						//с вариантом
					$basketinfo["products"][$id_prod][$id_var]=$products->getProductByID($id_prod);
if (isset($basket[$id_prod][$id_var]["options"]))
$basketinfo["products"][$id_prod][$id_var]["options"]=$basket[$id_prod][$id_var]["options"];
					$basketinfo["products"][$id_prod][$id_var]["count"]=$prodarr["count"];
	$basketinfo["products"][$id_prod][$id_var]["variant"]=$products->getVariantByID($prodarr["id_variant"]);
	
	if ($basketinfo["products"][$id_prod][$id_var]["variant"]["price"]>0) {
	$basketinfo["products"][$id_prod][$id_var]["price1"]=$basketinfo["products"][$id_prod][$id_var]["variant"]["price"];
	}
$basketinfo["products"][$id_prod][$id_var]["price_itogo"]=$prodarr["count"]*$basketinfo["products"][$id_prod][$id_var]["price1"];
	$basketinfo["products"][$id_prod][$id_var]["price_itogo_print"]=$this->getPrintPrice($basketinfo["products"][$id_prod][$id_var]["price_itogo"]);
$basketinfo["itogo"]=$basketinfo["itogo"]+$basketinfo["products"][$id_prod][$id_var]["price_itogo"];
					} else {
						//без вариантов
	$basketinfo["products"][$id_prod][0]=$products->getProductByID($id_prod);
if (isset($basket[$id_prod][0]["options"]))
$basketinfo["products"][$id_prod][0]["options"]=$basket[$id_prod][0]["options"];	
	$basketinfo["products"][$id_prod][0]["count"]=$prodarr["count"];
	$basketinfo["products"][$id_prod][0]["variant"]=false;
	$basketinfo["products"][$id_prod][0]["price_itogo"]=$prodarr["count"]*$basketinfo["products"][$id_prod][0]["price1"];
	$basketinfo["products"][$id_prod][0]["price_itogo_print"]=$this->getPrintPrice($basketinfo["products"][$id_prod][0]["price_itogo"]);
$basketinfo["itogo"]=$basketinfo["itogo"]+$basketinfo["products"][$id_prod][0]["price_itogo"];
					}
				}
				}
				if (isset($basketinfo["products"]))
				if (is_array($basketinfo["products"])) {
					$basketinfo["count"]=sizeof($basketinfo["products"]);
					$basketinfo["itogo"]=$this->getPrintPrice((int)$basketinfo["itogo"]);
				}
				return $basketinfo;
		} else {
			return false;
		}
		
	}
	
	function getPrintPrice($price,$valute="руб") {
		if (!preg_match("/^[0-9.]{1,}$/i",$price)) return false;
		$pos=strpos($price,'.');
		$ost='';
		if ($pos!==false) {
			$length=strlen($price);
			if ($length>$pos) {
				$ost=substr($price,$pos,$length-$pos);
				$price=substr($price,0,$pos);
			}
		}
		$new_price="";
		$l=strlen($price);
		for ($i=0;$i<=$l;$i++) {
			$ch=(($l-$i)/3) - floor(($l-$i)/3);
			if ($ch==0) $new_price.=" ";
			$new_price.=substr($price,$i,1);
		}
		return $new_price."$ost $valute";
	}
	
	function addProductToOrder($id_order,$product) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_order)) return false;
		if (is_array($product["variant"])) {
			$variant=$product["variant"]["caption"];
		} else {
			$variant="";
		}
		$options_str='';
		if (isset($product["options"]))
			if (is_array($product["options"]))
				$options_str=serialize($product["options"]);
		if ($db->query("INSERT INTO `%ORDER_PRODUCTS%` values ($id_order,'".sql_quote($product["caption"])."','".sql_quote($variant)."','".sql_quote($options_str)."','".sql_quote($product["code"])."',".$product["price1"].",".$product["count"].")")) {
			return true;
		} else {
			return false;
		}
	}
	
	function clearBasket() {
		if (isset($_SESSION["basket"])) {
			unset($_SESSION["basket"]);
		}
		if (isset($_SESSION["final"])) {
			unset($_SESSION["final"]);
		}
	}
	
	function getProductsByOrder($id_order=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_order)) return false;
		$res=$db->query("select * from %ORDER_PRODUCTS% where id_order=$id_order");
		while ($row=$db->fetch($res)) {
			$row["print_price"]=$this->getPrintPrice($row["price"]);
			$row["print_itogo"]=$this->getPrintPrice($row["price"]*$row["count"]);
			if (trim($row["options_info"])!='')
				$row["options"]=unserialize($row["options_info"]);
			$products[]=$row;
		}
		if (isset($products))
				return $products;
		return false;
	}
	
	function getCountOrders($id_user=0,$str='') {
		global $db;
		$str=trim($str);
		if (!preg_match("/^[0-9]{1,}$/i",$id_user)) $id_user=0;
		if ($id_user>0) {
			$usr_str=" and `%ORDERS%`.id_user=$id_user";
		} else {
			$usr_str='';
		}
		if ($str!='') {
			$str_sql=" and (`address` LIKE '".sql_quote($str)."' or `id_order` LIKE '".sql_quote($str)."' or `coupon_code` LIKE '".sql_quote($str)."' or `coupon_caption` LIKE '".sql_quote($str)."' or `%USERS%`.`email` LIKE '".sql_quote($str)."' or `%USERS%`.`city` LIKE '".sql_quote($str)."')";
		} else {
			$str_sql='';
		}
		$res=$db->query("select id_order from `%ORDERS%`,`%USERS%` where `%ORDERS%`.id_user=`%USERS%`.id_user $usr_str $str_sql");
		return @mysql_num_rows($res);
	}
	
	function getOrdersByUser($id_user=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_user)) return false;
		$usr_str="and %ORDERS%.id_user=$id_user";
		$res=$db->query("select %USERS%.*,%ORDERS%.*,DATE_FORMAT(%ORDERS%.`date`,'%d.%m.%Y %H:%i') as `date_print` from `%ORDERS%`,`%USERS%` where %ORDERS%.`id_user`=%USERS%.`id_user` $usr_str order by %ORDERS%.`date` DESC");
		while ($row=$db->fetch($res)) {
			$row["fio"]=$row["family"].' '.$row["name"].' '.$row["otch"];
			$row["itogo_print"]=$this->getPrintPrice($row["price_itog"]);
			if ($row["coupon_price"]>0 && $row["coupon_code"]!='') {
			$row["print_coupon_itogo"]=$this->getPrintPrice($row["coupon_itog"]);
			$row["print_coupon"]=$this->getPrintPrice($row["coupon_price"]);
			}
			$orders[$row["id_order"]]=$row;
		}
		if (isset($orders)) return $orders;
		return false;
	}	
	
	function getOrders($id_user=0,$str='',$page=0,$onpage=10) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$page)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$onpage)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_user)) $id_user=0;
		if ($id_user>0) {
			$usr_str="and %ORDERS%.`id_user`=$id_user";
		} else {
			$usr_str='';
		}
		if ($str!='') {
			$str_sql=" and (%ORDERS%.`address` LIKE '".sql_quote($str)."' or %ORDERS%.`id_order` LIKE '".sql_quote($str)."' or %ORDERS%.`coupon_code` LIKE '".sql_quote($str)."' or %ORDERS%.`coupon_caption` LIKE '".sql_quote($str)."' or `%USERS%`.`email` LIKE '".sql_quote($str)."' or `%USERS%`.`city` LIKE '".sql_quote($str)."')";
		} else {
			$str_sql='';
		}
		$res=$db->query("select %USERS%.*,%ORDERS%.*,DATE_FORMAT(%ORDERS%.`date`,'%d.%m.%Y %H:%i') as `date_print` from `%ORDERS%`,`%USERS%` where %ORDERS%.`id_user`=`%USERS%`.`id_user` $usr_str $str_sql order by %ORDERS%.`date` DESC LIMIT ".($page*$onpage).", $onpage");
		while ($row=$db->fetch($res)) {
			$row["fio"]=$row["family"].' '.$row["name"].' '.$row["otch"];
			$row["itogo_print"]=$this->getPrintPrice($row["price_itog"]);
			if ($row["coupon_price"]>0 && $row["coupon_code"]!='') {
			$row["print_coupon_itogo"]=$this->getPrintPrice($row["coupon_itog"]);
			$row["print_coupon"]=$this->getPrintPrice($row["coupon_price"]);
			}
			$orders[$row["id_order"]]=$row;
		}
		if (isset($orders)) return $orders;
		return false;
	}
	
	function getOrderByID($id_order=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_order)) return false;
		$res=$db->query("select *,DATE_FORMAT(%ORDERS%.`date`,'%d.%m.%Y %H:%i') as `date_print` from %ORDERS% where id_order=$id_order");
		$row=$db->fetch($res);
		$order=$row;
		$order["print_price"]=$this->getPrintPrice($row["price_itog"]);
		if ($order["coupon_price"]>0 && $order["coupon_code"]!='') {
		$order["print_coupon_itogo"]=$this->getPrintPrice($row["coupon_itog"]);
		$order["print_coupon"]=$this->getPrintPrice($row["coupon_price"]);
		}
		if ($order["discount_itog"]>0) {
		$order["print_discount_price"]=$this->getPrintPrice($row["discount_price"]);
		$order["print_discount_itog"]=$this->getPrintPrice($row["discount_itog"]);
		}
		$order["products"]=$this->getProductsByOrder($id_order);
		if (is_array($order)) return $order;
		return false;
	}
	
	function createOrder($user,$basket,$delivery,$payment,$address,$comment,$coupon=array(),$discount=array()) {
		global $db;
		global $smarty;
		if (isset($delivery["price"])) {
		if ($delivery["price"]>0) {
			$delivery_str=$delivery["caption"]." (".$this->getPrintPrice($delivery["price"]).")";
		} else {
			$delivery_str=$delivery["caption"];
		}
		} else {
			$delivery_str='';
		}
		if (isset($payment["caption"])) {
			$payment_str=$payment["caption"];
		} else {
			$payment_str='';
		}
		$coupon_itog=0;
		if (isset($coupon["price"]) && isset($coupon["code"]) && isset($coupon["caption"]) && isset($coupon["type"]))
		 if (($coupon["price"]>0) && ($coupon["code"]!='')) {
			//купон существует
			switch($coupon["type"]) {
				case 0:
					//скидка в рублях
					$coupon_itog=$basket["final"]-$coupon["price"];
				break;
				case 1:
					//скидка в процентах
					if ($coupon["price"]<=$this->thismodule["max_percent"]) {
					$discount=round($basket["final"]*($coupon["price"]/100),2);
					$coupon_itog=$basket["final"]-$discount;
					}
				break;
			}
			if ($coupon_itog<0)
				$coupon_itog=0;
		 }
		 $p=new Products();
		 $p->doDb();
		 $discount["price"]=str_replace(',','.',$discount["price"]);
		 $discount["itog"]=str_replace(',','.',$discount["itog"]);
		if ($db->query("INSERT INTO `%ORDERS%` values (null,".$user["id_user"].",'".sql_quote($delivery_str)."','".$payment_str."','".sql_quote($comment)."','".sql_quote($address)."','".date("Y-m-d H:i:s")."',".$basket["final"].",'".sql_quote(@$coupon["caption"])."','".sql_quote(@$coupon["code"])."',".$coupon["price"].",".$coupon["type"].",$coupon_itog,'".sql_quote($discount["caption"])."',".$discount["price"].",".$discount["itog"].",1)")) {
			$id_order=mysql_insert_id();
				foreach ($basket["products"] as $var) {
					foreach ($var as $prod) {
						$this->addProductToOrder($id_order,$prod);
						$db->query("update `%PRODUCTS%` set `buy`=`buy`+".$prod["count"]." where `id_product`=".$prod["id_product"]);
					}
				}
			$this->clearBasket();
			$order=$this->getOrderById($id_order);
			$smarty->assign("order",$order);
			$smarty->assign("order_id",$order["id_order"]);
			$this->mailMe($user["email"],$this->thismodule["mailadmin"],"Заказ в интернет магазине #".$id_order,3);
			$this->mailMe($this->thismodule["mailadmin"],$user["email"],"Заказ в интернет магазине #".$id_order,2);
			return $id_order;
		} else {
		echo mysql_error();
			return false;
		}
	}
	
	function authUser($login="",$password="") {
		global $db;
		if (!preg_match("/^[a-zA-Z0-9]{2,10}$/i",$login)) return false;
		if (!preg_match("/^[a-zA-Z0-9]{6,50}$/i",$password)) return false;
		$num=$db->getCount("select * from %USERS% where login='$login' and password='$password'");
		if ($num==1) {
			return true;
		} else {
			return false;
		}
	}
	
	function existDiscount($code) {
		global $db;
		if (preg_match("/^[a-zA-Z0-9]{2,255}$/i",$code)) {
			$res=$db->query("select id_discount from `%DISCOUNTS%` where LOWER(`code`)='".strtolower($code)."'");
			if (mysql_num_rows($res)>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function existActiveDiscount($code) {
		global $db;
		if (preg_match("/^[a-zA-Z0-9]{2,255}$/i",$code)) {
			$res=$db->query("select id_discount from `%DISCOUNTS%` where LOWER(`code`)='".strtolower($code)."' and `active`=1");
			if (mysql_num_rows($res)>0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getEmptyDiscount() {
		$coupon=Array();
		$coupon["caption"]='';
		$coupon["code"]='';
		$coupon["price"]=0;
		$coupon["type"]=0;
		return $coupon;
	}
	
	function getEmptyGroupDiscount() {
		$discount=Array();
		$discount["caption"]='';
		$discount["price"]=0;
		$discount["itog"]=0;
		return $discount;
	}
	
	function getUserDiscount($code,$login,$activate=true) {
		global $db;
		global $smarty;
		if (preg_match("/^[a-zA-Z0-9]{2,255}$/i",$code) && preg_match("/^[a-zA-Z0-9]{2,255}$/i",$login)) {
			$res=$db->query("select * from `%DISCOUNTS%` where LOWER(`code`)='".strtolower($code)."' and `active`=1");
			if (mysql_num_rows($res)==1) {
				$discount=@$db->fetch($res);
				$n=$db->getCount("select * from `%LOGIN_DISCOUNTS%` where id_discount=".$discount["id_discount"]." and login='".sql_quote($login)."'");
				if ($n<$this->thismodule["sales_count"]) {
					if ($activate)
						$db->query("insert into `%LOGIN_DISCOUNTS%` values(".$discount["id_discount"].",'".sql_quote($login)."')");
					return $discount;
				} else {
					$smarty->assign("coupon_has_been_activated",true);
					return $this->getEmptyDiscount();
				}
			} else {
				return $this->getEmptyDiscount();
			}
		} else {
			return $this->getEmptyDiscount();
		}
	}
	
	function existOpt($from=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$from)) return false;
		if ($db->getCount("select `id_opt` from `%OPT%` where `from`=$from")>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function addOpt($from=0,$percent=0,$active=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$from)) return false;
		if (!preg_match("/^[0-9]{1,2}$/i",$percent)) return false;
		if ($active!=1) $active=0;
		if ($db->query("insert into `%OPT%` values (null,$from,$percent,$active)")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getAllOpts($active=0) {
		global $db;
		if ($active==1) {
			$res=$db->query("select * from `%OPT%` where `active`=1 order by `from` ASC");
		} else {
			$res=$db->query("select * from `%OPT%` order by `from` ASC");
		}
		while ($row=@$db->fetch($res)) {
			$row["print"]=$this->getPrintPrice($row["from"]);
			$opts[$row["from"]]=$row;
		}
		if (isset($opts)) return $opts;
		return false;
	}
	
}
?>
