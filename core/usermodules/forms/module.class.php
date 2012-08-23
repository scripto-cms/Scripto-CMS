<?
/*
ћодуль формы
*/

define("SCRIPTO_forms",true);

class forms {
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
		$db->addPrefix("%FORMS%",$config["db"]["prefix"]."forms");
		$db->addPrefix("%FORMS_INPUT%",$config["db"]["prefix"]."forms_input");
		$db->addPrefix("%FORMS_ORDERS%",$config["db"]["prefix"]."forms_orders");
		$db->addPrefix("%INPUT_VALUES%",$config["db"]["prefix"]."input_values");
		$db->addPrefix("%ORDER_ANSWERS%",$config["db"]["prefix"]."order_answers");
	}
	
	function doInstall() {
		global $db;
		return true;
	}
	
	function doUninstall() {
		return true;
	}
	
	function doUpdate() {
		return true;
	}
	
	function doBlock($block,$page,&$objects) {
		return "";
	}
	
	function checkMe() {
	//провер€ем существуют ли уже таблицы модул€
		global $engine;
		if ($engine->checkInstallModule("forms")) {
			return true;
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
			//здесь получаем товары дл€ рубрик
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
	
	function getFormByCategory($id_cat=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_cat)) return false;
		$res=$db->query("select * from `%FORMS%` where `visible`=1 and id_category=$id_cat order by `caption`");
		return $db->fetch($res);
	}
	
	function getForms($visible=1) {
		global $db;
		if ($visible) {
			$vis="and `visible`=1";
		} else {
			$vis="";
		}
		$res=$db->query("select * from `%FORMS%` where 1 $vis order by `caption`");
			while ($row=$db->fetch($res)) {
				$forms[$row["id_form"]]=$row;
			}
		if (isset($forms))
			return $forms;
		return false;
	}
	
	function getFormByID($id_form) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_form)) return false;
		$res=$db->query("select * from `%FORMS%` where id_form=$id_form");
		return $db->fetch($res);
	}
	
	function addForm($id_cat=0,$caption,$caption_admin,$caption_mail_admin='',$caption_mail_user='',$content,$forwardcontent,$success_admin='',$success_user='',$email='',$visible=0,$start_value=0,$sql='') {
		global $db;
		global $engine;
		if (!preg_match("/^[0-9]{1,}$/i",$id_cat)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$visible)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$start_value)) return false;
		$category=$engine->getCategoryByID($id_cat);
		if ($db->query("insert into `%FORMS%` values (null,$id_cat,'".sql_quote($category["caption"])."','".sql_quote($caption)."','".sql_quote($caption_admin)."','".sql_quote($caption_mail_admin)."','".sql_quote($caption_mail_user)."','".sql_quote($email)."','".sql_quote($content)."','".sql_quote($forwardcontent)."','".sql_quote($success_user)."','".sql_quote($success_admin)."','".date('Y-m-d H:i:s')."',$visible,$start_value".$sql.")")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function getInputByID($id_input) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_input)) return false;
		$res=$db->query("select * from `%FORMS_INPUT%` where id_input=$id_input");
		return $db->fetch($res);
	}
	
	function addInput($id_form=0,$type_caption,$input_type,$data_caption,$data_type,$obyaz,$caption,$error_text,$tooltip,$sql='') {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_form)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$input_type)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$data_type)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$obyaz)) return false;
		if ($db->query("insert into `%FORMS_INPUT%` values (null,$id_form,'".sql_quote($type_caption)."',$input_type,'".sql_quote($data_caption)."',$data_type,$obyaz,0,'".sql_quote($caption)."','".sql_quote($error_text)."','".sql_quote($tooltip)."'".$sql.")")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}	
	
	function getInputsByForm($id_form) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_form)) return false;
		$res=$db->query("select * from `%FORMS_INPUT%` where id_form=$id_form order by `sort` ASC");
		$k=0;
		while ($row=$db->fetch($res)) {
			$inputs[$k]=$row;
			$k++;
		}
		if (isset($inputs)) return $inputs;
		return false;
	}
	
	function setSort($id_input,$inputs,$mode="") {
		global $db;
		if ($mode=="down") {
		$x=1;
		$key_default=sizeof($inputs);
			foreach ($inputs as $key=>$input) {
				if ($id_input==$input["id_input"]) {
					//нашли нужный элемент,запоминаем его номер
					$key_default=$key;
					$sort_value=$inputs[$key+1]["sort"]-1;
					$db->query("update `%FORMS_INPUT%` set sort=$sort_value where id_input=".$input["id_input"]);
				}
				if ($key>$key_default && $key!=$key_default+1) {
				$x++;
					$db->query("update `%FORMS_INPUT%` set sort=sort-$x where id_input=".$input["id_input"]);
				}
			}
		}
		if ($mode=="up") {
		$x=1;
		$key_default=sizeof($inputs);
			foreach ($inputs as $key=>$input) {
				if ($id_input==$input["id_input"]) {
					//нашли нужный элемент,запоминаем его номер
					$key_default=$key;
					$sort_value=$inputs[$key+1]["sort"]-1;
					$db->query("update `%FORMS_INPUT%` set sort=$sort_value where id_input=".$input["id_input"]);
				}
				if ($key>$key_default && $key!=$key_default+1) {
				$x++;
					$db->query("update `%FORMS_INPUT%` set sort=sort-$x where id_input=".$input["id_input"]);
				}
			}
		}
		return true;
	}
	
	function addValue($id_input,$caption,$default) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_input)) return false;
		if ($db->getCount("select * from `%INPUT_VALUES%` where id_input=$id_input and `default`=1")==0) {
			$default=1;
		}
		if ($default==1) {
			$db->query("update `%INPUT_VALUES%` set `default`=0 where id_input=$id_input");
		}
		if ($db->query("insert into `%INPUT_VALUES%` values(null,$id_input,$default,'".sql_quote($caption)."')")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getValuesByInput($id_input) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_input)) return false;
		$res=$db->query("select * from `%INPUT_VALUES%` where id_input=$id_input order by `caption`");
		while ($row=$db->fetch($res)) {
			$values[]=$row;
		}
		if (isset($values)) return $values;
		return false;
	}
	
	function getValuesByInputEx($id_input) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_input)) return false;
		$res=$db->query("select * from `%INPUT_VALUES%` where id_input=$id_input order by `caption`");
		while ($row=$db->fetch($res)) {
			$value["id"]=$row["id_value"];
			$value["name"]=$row["caption"];
			$values[]=$value;
		}
		if (isset($values)) return $values;
		return false;
	}	
	
	function deleteInput($id_input) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_input)) return false;
		$db->query("delete from `%INPUT_VALUES%` where id_input=$id_input");
		if ($db->query("delete from `%FORMS_INPUT%` where id_input=$id_input")) {
		 return true;
		} else {
		 return false;
		}
	}
	
	function deleteForm($id_form) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_form)) return false;
		$inputs=$this->getInputsByForm($id_form);
			if (is_array($inputs)) {
				foreach ($inputs as $input) {
					$this->deleteInput($input["id_input"]);
				}
			}
		$db->query("delete from `%FORMS_ORDERS%` where id_form=$id_form");
		if ($db->query("delete from `%FORMS%` where id_form=$id_form")) {
			return true;
		}
	}
	
	function createOrder($frm,$text,$fio='',$user_email='') {
		global $db;
		if (isset($frm["id_form"])) {
		$id_form=$frm["id_form"];
		if (!preg_match("/^[0-9]{1,}$/i",$id_form)) return false;
		$start_value=@$frm["start_value"];
		if (!preg_match("/^[0-9]{1,}$/i",$start_value)) $start_value=0;
		$order_count=$start_value + $db->getCount("select id_order from `%FORMS_ORDERS%` where id_form=$id_form");
		$order_count++;
		if ($db->query("insert into `%FORMS_ORDERS%` values(null,$id_form,'".sql_quote($text)."','".getIp()."','".date('Y-m-d')."',".$order_count.",1,'".sql_quote($fio)."','".sql_quote($user_email)."')")) {
			return $order_count;
		} else {
			echo mysql_error();
			return false;
		}
		}
	}
	
	function getOrdersByForm($id_form,$page=0,$onpage=20) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_form)) return false;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `create_print` from `%FORMS_ORDERS%` where id_form=$id_form order by `order_number` DESC LIMIT ".$page*$onpage.",".$onpage);
			while ($row=$db->fetch($res)) {
				$orders[]=$row;
			}
		if (isset($orders)) {
			return $orders;
		} else {
			return false;
		}
	}
	
	function getCountOrdersByForm($id_form) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_form)) return false;
		return $db->getCount("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `create_print` from `%FORMS_ORDERS%` where id_form=$id_form order by `date`");
	}
	
	function getOrderByID($id_order) {
		global $db;
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `create_print` from `%FORMS_ORDERS%` where id_order=$id_order");
		return $db->fetch($res);
	}
	
	function getNewReminders() {
		global $db;
		$res=$db->query("SELECT id_order from `%FORMS_ORDERS%` where unread=1");
		$count=@mysql_num_rows($res);
		if ($count>0) {
			$reminder['subject']=ToUTF8($this->thismodule["caption"]);
			$reminder['content']=ToUTF8('” ¬ас '.@mysql_num_rows($res).' новых за€вок по формам');
			$reminder['count']=$count;
			return $reminder;
		} else {
			return false;
		}
	}
	
	function getCountUnreadOrders() {
		global $db;
		$res=$db->query("SELECT id_form,count(`unread`) as `unread_count` from `%FORMS_ORDERS%` where unread=1 group by id_form");
		$forms=array();
			while ($row=$db->fetch($res)) {
				$forms[$row["id_form"]]=$row["unread_count"];
			}
		return $forms;
	}
	
	function getAnswers($id_order) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_order)) return false;
		$res=$db->query("SELECT *,DATE_FORMAT(`date`,'%d-%m-%Y') as `create_print`,DATE_FORMAT(`forward_date`,'%d-%m-%Y') as `forward_print` from `%ORDER_ANSWERS%` where id_order=$id_order order by `date`");
		while ($row=$db->fetch($res)) {
			$answers[]=$row;
		}
		if (isset($answers)) return $answers;
		return false;
	}
	function createAnswer($id_order,$is_admin,$from_email,$content) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_order)) return false;
		if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$from_email)) return false;
		if ($is_admin!=1) $is_admin=0;
		$ip=getIp();
		if ($is_admin==1) {
			$unread=0;
		} else {
			$unread=1;
		}
		if ($db->query("insert into `%ORDER_ANSWERS%` values (null,$id_order,'$ip','".date('Y-m-d')."',$unread,$is_admin,'$from_email','".sql_quote($content)."','".date('Y-m-d')."')")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function getUnreadAnswers() {
		global $db;
		$res=$db->query("select `id_order` from `%ORDER_ANSWERS%` where `unread`=1 and `is_admin`=0 group by `id_order`");
		while ($row=$db->fetch($res)) {
			$unread[$row["id_order"]]=$row;
		}
		if (isset($unread)) {
			return $unread;
		} else {
			return false;
		}
	}
}
?>
