<?
/*
Модуль товары
*/

define("SCRIPTO_users",true);

class users {
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
		$db->addPrefix("%USERS%",$config["db"]["prefix"]."users");
		$db->addPrefix("%USER_OBJECTS%",$config["db"]["prefix"]."user_objects");
		$db->addPrefix("%GROUPS%",$config["db"]["prefix"]."groups");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Авторизация','',$type_id,'authform',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
		if ($engine->checkInstallModule("users")) {
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
	
	function getNewReminders() {
		global $db;
		$res=$db->query("SELECT id_user from `%USERS%` where `new`=1");
		$count=@mysql_num_rows($res);
		if ($count>0) {
			$reminder['subject']=ToUTF8($this->thismodule["caption"]);
			$reminder['content']=ToUTF8(@mysql_num_rows($res).' новых пользователя');
			$reminder['count']=$count;
			return $reminder;
		} else {
			return false;
		}
	}
	
	function clearAuth() {
		if (isset($_SESSION["user_login"]) || isset($_SESSION["user_password"])) {
			unset($_SESSION["user_login"]);
			unset($_SESSION["user_password"]);
			unset($_SESSION["auth"]);
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
	
	function addUser($login,$password,$family,$name,$otch,$city,$email,$phone1,$phone2,$new=1,$avatar='',$moderator=0,$id_group=0) {
		global $db;
		global $engine;
		if ($new!=0) $new=1;
		if (!preg_match("/^[0-9]{1,}$/i",$id_group)) $id_group=0;
		if ($db->query("insert into `%USERS%` values (null,'".sql_quote($avatar)."','$login','".$engine->generate_admin_password($password)."','".sql_quote($family)."','".sql_quote($name)."','".sql_quote($otch)."','$email','".sql_quote($city)."','$phone1','$phone2','".date("Y-m-d H:i:s")."',$new,$moderator,'',$id_group)")) {
			return mysql_insert_id();
		} else {
			echo mysql_error();
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
		if (mysql_num_rows($res)==1) {
		$row=$db->fetch($res);
		$row["fio"]=$row["family"].' '.$row["name"].' '.$row["otch"];
		$row["permissions"]=@unserialize($row["access"]);
		return $row;
		} else {
		return false;
		}
	}

	
	function getUserByID($id_user=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_user)) return false;
		$res=$db->query("select * from %USERS% where id_user=$id_user");
		if (mysql_num_rows($res)==1) {
		$row=$db->fetch($res);
		$row["fio"]=$row["family"].' '.$row["name"].' '.$row["otch"];
		$row["permissions"]=@unserialize($row["access"]);
		return $row;
		} else {
		return false;
		}
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
			//запрос на генерацию нового пароля
	$mailtext=$smarty->fetch($this->thismodule["template_path"]."newpassword.mail.tpl");
			break;
			case 3:
			//новый пароль для входа
	$mailtext=$smarty->fetch($this->thismodule["template_path"]."recovery.mail.tpl");
			break;
		}
		mailHTML($to,$from,$subject,$mailtext);
		return false;
	}
	
	function getAllUsers($sort=0) {
		global $db;
		$sort_sql='';
		if ($sort==0) {
			$sort_sql=' order by `login`';
		} elseif ($sort==1) {
			$sort_sql=' order by `family`,`name`,`otch`';
		}
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `date_print` from %USERS% $sort_sql");;
		while ($row=$db->fetch($res)) {
			$row["fio"]=$row["family"].' '.$row["name"].' '.$row["otch"];
			$users[]=$row;
		}
		if (isset($users)) return $users;
		return false;
	}
	
	function getCountUsers($str='',$stype=0) {
		global $db;
		$str_sql='';
		if (trim($str)!='') {
			$str_sql="where (`email` LIKE '$str' or `login` LIKE '$str' or `family` LIKE '$str' or `name` LIKE '$str' or otch LIKE '$str')";
		}
		$type_sql='';
		switch ($stype) {
			case 1:
				$type_sql=' and `new`=1';
			break;
			case 2:
				$type_sql=' and `moderator`=1';
			break;
		}
		$res=$db->query("select id_user from `%USERS%` $str_sql $type_sql");
		return @mysql_num_rows($res);
	}
	
	function getUsers($str='',$page=0,$onpage=20,$stype=0) {
		global $db;
		$str_sql='';
		if (trim($str)!='') {
			$str_sql="where (`email` LIKE '$str' or `login` LIKE '$str' or `family` LIKE '$str' or `name` LIKE '$str' or otch LIKE '$str')";
		}
		$type_sql='';
		switch ($stype) {
			case 1:
				$type_sql=' and `new`=1';
			break;
			case 2:
				$type_sql=' and `moderator`=1';
			break;
		}
		$res=$db->query("select *,DATE_FORMAT(`date`,'%d-%m-%Y') as `date_print` from %USERS% $str_sql $type_sql order by `login` LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$row["fio"]=$row["family"].' '.$row["name"].' '.$row["otch"];
			$users[]=$row;
		}
		if (isset($users)) return $users;
		return false;
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
	
	function authModerator($login="",$password="") {
		global $db;
		if (!preg_match("/^[a-zA-Z0-9]{2,10}$/i",$login)) return false;
		if (!preg_match("/^[a-zA-Z0-9]{6,50}$/i",$password)) return false;
		$res=$db->query("select * from %USERS% where login='$login' and password='$password' and `moderator`=1");
		if (mysql_num_rows($res)==1) {
			$user=$db->fetch($res);
			$user["permissions"]=@unserialize($user["access"]);
			return $user;
		} else {
			return false;
		}
	}
	
	function addObject2User($id_object,$id_user,$id_type) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_object)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_user)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_type)) return false;
		if ($db->query("insert into `%USER_OBJECTS%` values ($id_object,$id_user,$id_type,0)")) {
		 return true;
		} else {
		 return false;
		}
	}
	
	function getObjectsByUser($id_user,$visible=0,$limit=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_user)) return false;
			$limit_str='';
			if (preg_match("/^[0-9]{1,}$/i",$limit)) {
				if ($limit>0)  {
					$limit_str=' LIMIT 0,$limit';
				}
			}
			$vis_str="";
			if ($visible==1) {
				$vis_str=" and %OBJ%.visible=1";
			}
			$res=$db->query("select `%OBJ%`.*,`%USER_OBJECTS%`.sort,DATE_FORMAT(%OBJ%.`date_create`,'%d.%m.%Y %h:%i:%S') as `create_print` from `%USER_OBJECTS%`,`%OBJ%` where `%USER_OBJECTS%`.id_user=$id_user and `%USER_OBJECTS%`.id_object=`%OBJ%`.id_object $vis_str order by `%OBJ%`.date_create DESC  $limit_str");
			while ($row=$db->fetch($res)) {
				$row["my_object"]=true;
				$objects[$row["id_type"]][]=$row;
			}
			if (isset($objects)) {
				return $objects;
			} else {
				return false;
			}
	}
	
	function setCookie($login='',$password='') {
		setcookie("user_login", $login,time()+2592000,'/',@$_SERVER["HTTP_HOST"]);
		setcookie("user_password", $password,time()+2592000,'/',@$_SERVER["HTTP_HOST"]);
	}
	
	function unsetCookie($login='',$password='') {
		@setcookie("user_login", $login,time()-90000,'/',@$_SERVER["HTTP_HOST"]);
		@setcookie("user_password", $password,time()-90000,'/',@$_SERVER["HTTP_HOST"]);
	}
	
	function existGroup($groupname='') {
		global $db;
		$count=$db->getCount("select `id_group` from `%GROUPS%` where LOWER(`caption`)='".sql_quote(strtolower($groupname))."'");
		if ($count>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function addGroup($groupname='',$percent=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$percent)) return false;
		if (!preg_match("/^[0-9]{1,2}$/i",$percent)) return false;
		if ($db->query("insert into `%GROUPS%` values(null,'".sql_quote($groupname)."',$percent)")) {
			return true;
		} else {
			echo mysql_Error();
			return false;
		}
	}
	
	function getAllGroups() {
		global $db;
		$res=$db->query("select * from `%GROUPS%` group by `caption` DESC");
		while ($row=@$db->fetch($res)) {
			$groups[$row["id_group"]]=$row;
		}
		if (isset($groups)) return $groups;
		return false;
	}
	
	function getAllGroupsEx() {
		global $db;
		$res=$db->query("select * from `%GROUPS%` group by `caption` DESC");
		$group["id"]=0;
		$group["name"]="Не указано";
		$groups[]=$group;
		while ($row=@$db->fetch($res)) {
			$group["id"]=$row["id_group"];
			$group["name"]=$row["caption"];
			$groups[]=$group;
		}
		if (isset($groups)) return $groups;
		return false;
	}
	
	function getGroupByID($id_group=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_group) && $id_group>0) {
			$res=$db->query("select * from `%GROUPS%` where `id_group`=$id_group");
			return $db->fetch($res);
		} else {
			return false;
		}
	}
	
}
?>
