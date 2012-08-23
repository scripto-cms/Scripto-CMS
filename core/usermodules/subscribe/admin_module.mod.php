<?
/*
Модуль рассылки, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/

$m_action=@$_REQUEST["m_action"];

switch ($m_action) {
	case "exporttxt":
		header('Content-Disposition: attachment; filename=mailbase_'.date(dmyhi).'.txt');
		header('Last-Modified: '.$ftime);
		$allemails=$this->getAllEmails();
		foreach ($allemails as $email=>$name) {
			echo $email.'|'.$name."\n";
		}
		die();
	break;
	case "importtxt":
		ini_set("upload_max_filesize", "100M");
		$filename=charset_x_win(strtolower($_FILES['txt_file']['name']));
		$pos=0;
		$add=0;
		$file_ext=getFileExt($filename, $pos);
		if ($file_ext!='txt') {
			die('wrong_format');
		} else {
			$emails_txt=file_get_contents($_FILES['txt_file']['tmp_name']);
			$emails_array=explode($this->thismodule["delimiter"],$emails_txt);
			if (is_array($emails_array)) {
				foreach ($emails_array as $em) {
					$e=explode("|",$em);
					$email='';
					$name='';
					if (is_array($e)) {
						$email=@$e[0];
						if (isset($e[1]))
							$name=$e[1];
					} else {
						$email=$e;
					}
					$email=trim(str_replace(chr(13),'',$email));
					$name=trim(str_replace(chr(13),'',$name));
					if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email)) {
						$this->addToSubscribe($email,$name);
						$add++;
					}
				}
			}
		}
		die("ok");
	break;
	case "importfromusers":
		if ($engine->checkInstallModule("users")) {
			$smarty->assign("users_install",true);
			$u=new Users();
			$u->doDB();
			$users=$u->getAllUsers();
			$add=0;
			foreach ($users as $user) {
				if ($this->addToSubscribe($user["email"],trim($user["fio"]))) {
					$add++;
				}
			}
			$engine->setCongratulation('','В рассылку было добавлено '.$add.' e-mail',3000);
		} else {
			$engine->setCongratulation('Ошибка','Модуль пользователи не установлен!',3000);
		}
		$m_action="import";
	case "import":
		$smarty->assign("doFiles",true);
		$engine->addJS("/core/usermodules/subscribe/txt.js");
		$engine->assignJS();
		if ($engine->checkInstallModule("users")) {
			$smarty->assign("users_install",true);
		}
	break;
	case "save":
		$idemail=@$_REQUEST["idemail"];
		$name=@$_REQUEST["name"];
		$del=@$_REQUEST["del"];
		$u=0;
		$d=0;
		foreach ($idemail as $id_email=>$mail) {
			if (isset($del[$id_email])) {
				$db->query("delete from `%email%` where id_email=$id_email");
				$d++;
			} else {
				if (isset($name[$id_email]))
				if (trim($name[$id_email])!='') {
					if ($db->query("update `%email%` set `name`='".$name[$id_email]."' where id_email=$id_email"))
						$u++;
				}
			}
		}
		$engine->setCongratulation('',"Обновлено $u e-mail адресов, удалено $d e-mail адресов",3000);
		$m_action="view";
	break;
	case "get_count":
		$count=$this->getCountEmails('');
		echo $count;
		die();
	break;
	case "do_subscribe":
		$id_archive=@$_REQUEST["id_archive"];
		if (preg_match("/^[0-9]{1,}$/i",$id_archive)) {
			$archive=$this->getArchiveByID($id_archive);
			$p=@$_REQUEST["p"];
			$onpage=@$_REQUEST["onpage"];
			if (preg_match("/^[0-9]{1,}$/i",$p) && preg_match("/^[0-9]{1,}$/i",$onpage)) {
				$emails=$this->getEmails('',$p,$onpage);
				foreach ($emails as $email) {
					if ($email["name"]!='') {
						$description=str_replace('%FIO%',$email["name"],$archive["description"]);
					} else {
						$description=str_replace('%FIO%','Подписчик',$archive["description"]);
					}
					if (mailHTML($email["email"],$archive["backmail"],$archive["caption"],$description,false)) {
						die("mailerror");
					}
				}
				die("ok");
			} else {
				die("error2");
			}
		} else {
			die("error1");
		}
	break;
	case "add_subscribe":
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&m_action=subscribes&module_name='.$this->thismodule["name"],true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_archive"])) {
				$id_archive=@$_REQUEST["id_archive"];
				$archive=$this->getArchiveByID($id_archive);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=trim(@$_REQUEST["caption"]);
				$backmail=trim(@$_REQUEST["backmail"]);
				$content=@$_REQUEST["fck1"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($archive) {
						$caption=$archive["caption"];
						$backmail=$archive["backmail"];
						$content=$archive["description"];
					}
				} else {
					$caption="";
					$backmail=$settings["mailadmin"];
					$content="";
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
			$frm->addField("Тема рассылки","Неверно заполнена тема рассылки","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Открытие сайта",array('size'=>'40','ticket'=>"Любые буквы и цифры"));
			$frm->addField("Обратный адрес для рассылки","Неверно заполнен обратный адрес для рассылки","text",$backmail,$backmail,"/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i","backmail",1,"info@site.ru",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

			$fck_editor1=$engine->createFCKEditor("fck1",$content);
			$frm->addField("Текст рассылки","Неверно заполнен текст рассылки","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

			$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
			$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
			if (isset($_REQUEST["id_archive"])) {
				$id_archive=$_REQUEST["id_archive"];
				$frm->addField("","","hidden",$id_archive,$id_archive,"/^[^`]{0,}$/i","id_archive",1);
			}

			if ($mode=="edit") {
				$engine->addPath('Редактирование рассылки','',false);
			} else {
				$engine->addPath('Добавление рассылки','',false);
			}
			$engine->assignPath();
			if (
			$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
					if ($db->query("update `%archive%` set `caption`='".sql_quote($caption)."',`backmail`='".$backmail."',`description`='".sql_quote($content)."' where id_archive=$id_archive")) {
						$engine->setCongratulation('','Рассылка успешно отредактирована!',3000);
					} else {
						$engine->setCongratulation('Ошибка','В процессе редактирования рассылки произошла ошибка!',3000);
					}
				} else {
					if ($this->createArchive($caption,$backmail,$content)) {
						$engine->setCongratulation('','Рассылка успешно создана!',3000);
					} else {
						$engine->setCongratulation('Ошибка','В процессе создания рассылки произошла ошибка!',3000);
					}
				}
				$m_action="subscribes";
			}
		$engine->assignPath();
	break;
	case "subscribes":
		
	break;
	default:
		$m_action="view";
}
if ($m_action=="subscribes") {
	$engine->addJS("/core/usermodules/subscribe/subscribe.js");
	$engine->assignJS();
	if (isset($_REQUEST["delete"])) {
		if (isset($_REQUEST["id_archive"])) {
			$id_archive=$_REQUEST["id_archive"];
			if (preg_match("/^[0-9]{1,}$/i",$id_archive)) {
				if ($db->query("delete from `%archive%` where id_archive=$id_archive")) {
					$engine->setCongratulation('','Рассылка удалена успешно!',3000);
				}
			}
		}
	}
	$count=$this->getCountArchives();
	$pages=ceil($count/$this->thismodule["onpage_archives"]);
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
	$archives=$this->getArchives($pg,$this->thismodule["onpage_archives"]);
	$smarty->assign("archives",$archives);
	if (isset($pages_arr)) {
		$smarty->assign("pages",$pages_arr);
		$smarty->assign("pagenumber",$pg);
		$smarty->assign("count",$count);
	}
}
if ($m_action=="view") {
	$engine->addJS("/core/usermodules/subscribe/subscribe.js");
	$engine->assignJS();
	if (isset($_REQUEST["add"])) {
		if (isset($_REQUEST["email"])) {
			$email=$_REQUEST["email"];
			if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email)) {
				if ($this->addToSubscribe($email,'')) {
					$engine->setCongratulation('','E-mail успешно добавлен в базу!',3000);
				} else {
					$engine->setCongratulation('Ошибка','E-mail уже существует в базе!',3000);
				}
			} else {
				$engine->setCongratulation('Ошибка','Вы указали не e-mail!',3000);
			}
		}
	}
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
	$count=$this->getCountEmails($str);
	$pages=ceil($count/$this->thismodule["onpage"]);
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
	$emails=$this->getEmails($str,$pg,$this->thismodule["onpage"]);
	$smarty->assign("emails",$emails);
	if (isset($pages_arr)) {
		$smarty->assign("pages",$pages_arr);
		$smarty->assign("pagenumber",$pg);
		$smarty->assign("count",$count);
	}
}
$smarty->assign("m_action",$m_action);
?>