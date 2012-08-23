<?
/*модуль заметки*/
global $page;
global $settings;

if (defined("SCRIPTO_GALLERY")) {

	$page["title"]=$lang["modules"]["notes"];
	$smarty->assign("page",$page);
	$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/organaizer/");
	$this->setAdminTitle($lang["modules"]["notes"]);

	switch ($modAction) {
		case "viewnote":
			$id_note=@$_REQUEST["id_note"];
			if (preg_match("/^[0-9]{1,}$/i",$id_note)) {
				$note=$this->getNoteByID($id_note);
				$smarty->assign("note",$note);
			}
		break;
		case "deletenote":
			$id_note=@$_REQUEST["id_note"];
			if (preg_match("/^[0-9]{1,}$/i",$id_note)) {
				if ($db->query("delete from `%notes%` where id_note=$id_note")) {
					$this->setCongratulation("",$lang["congratulation"]["note_delete"],3000);
				}
				$modAction="notes";
			}
		break;
		case "addnote":
			//Добавление заметки
			$mode=@$_REQUEST["mode"];
			$this->addPath('Органайзер','/admin?module=notes',true);
			if ($mode=="edit") {
			$this->addPath('Редактирование заметки','',false);
			} else {
			$this->addPath('Добавление заметки','',false);
			}
			$this->assignPath();
			
			if (isset($_REQUEST["id_note"])) {
				$id_note=@$_REQUEST["id_note"];
				$note=$this->getNoteByID($id_note);
			}
			
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=strip_tags(@$_REQUEST["caption"]);
				$content=$this->stripContent(@$_REQUEST["fck1"]);
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($note) {
						$caption=$note["caption"];
						$content=$note["content"];
					}
				} else {
					$caption="";
					$content="";
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField('Название заметки','Неверно заполнено название заметки',"text",$caption,$caption,"/^[^`#]{2,250}$/i","caption",1,'',array('size'=>'40','ticket'=>''));

$fck_editor1=$this->createFCKEditor("fck1",$content);
$frm->addField("Текст заметки","Не указан текст заметки","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
if (isset($_REQUEST["id_note"])) {
$id_note=$_REQUEST["id_note"];
$frm->addField("","","hidden",$id_note,$id_note,"/^[^`]{0,}$/i","id_note",1);
}

if ($mode=="edit") {
	$s_name=$lang["forms"]["notes"]["edit_note_submit_name"];
} else {
	$s_name=$lang["forms"]["notes"]["add_note_submit_name"];
}

			if (
$this->processFormData($frm,$s_name,$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_note)) {
				 	if ($db->query("update %notes% set `caption`='".sql_quote($caption)."' , `content`='".sql_quote($content)."' where id_note=$id_note")) {
						//отредактировали
				   $this->setCongratulation('',$lang["congratulation"]["note_edit"],3000);
				   $modAction="notes";
				   $this->clearPath();
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $add_id=$this->createNote($caption,$content);
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $this->setCongratulation('',$lang["congratulation"]["note_add"],3000);
				   $modAction="notes";
				   $this->clearPath();
				 }
				}
			}
		break;
		case "notes":
		
		break;
		case "view":
		
		break;
		case "createAffair":
			$delo=@$_REQUEST["delo"];
			$vazhn=@$_REQUEST["vazhn"];
			if (preg_match("/^[0-9]{1,}$/i",$vazhn) && trim($delo)!='') {
				$id_affair=$this->createAffair($delo,$vazhn);
				if ($id_affair>0) {
					$affair=$this->getAffairByID($id_affair);
					if (is_array($affair)) {
						$affair["content"]=ToUTF8($affair["content"]);
						die(json_encode($affair));
					} else {
						die('ERROR');
					}
				} else {
					die("ERROR");
				}
			} else {
				die("WRONG DATA");
			}
		break;
		case "setDelAffair":
			$id_process=@$_REQUEST["id_process"];
			if (preg_match("/^[0-9]{1,}$/i",$id_process)) {
				if ($db->query("delete from `%process%` where `id_process`=$id_process")) {
					die('DEL');
				} else {
					die('ERROR');
				}
			} else {
				die('ERROR');
			}
		break;
		case "setDoAffair":
			$id_process=@$_REQUEST["id_process"];
			if (preg_match("/^[0-9]{1,}$/i",$id_process)) {
				if ($db->query("update `%process%` set `done`=1, `date_done`='".date('Y-m-d H:i:s')."' where `id_process`=$id_process")) {
					die(date('d.m.Y H:i'));
				} else {
					die('ERROR');
				}
			} else {
				die('ERROR');
			}
		break;
		case "affairs":
			for ($x=0;$x<=4;$x++)
				$vazhn[]=$x;
			$smarty->assign("vazhn",$vazhn);
			$affairs=$this->getAllAffairs();
			$smarty->assign("affairs",$affairs);
		break;
		case "save":
			$idreminder=@$_REQUEST["idreminder"];
			$subject=@$_REQUEST["subject"];
			$date_day=@$_REQUEST["date_day"];
			$date_month=@$_REQUEST["date_month"];
			$date_year=@$_REQUEST["date_year"];
			$time_hour=@$_REQUEST["time_hour"];
			$time_minute=@$_REQUEST["time_minute"];
			$del=@$_REQUEST["del"];
			$d=0;
			$upd=0;
			if (is_array($idreminder)) {
				foreach ($idreminder as $k=>$reminder) {
					if (isset($del[$reminder])) {
						if ($db->query("delete from `%reminders%` where id_reminder=$reminder")) {
							$d++;
						}
					} else {
						if (preg_match("/^[0-9]{1,}$/i",$reminder)) {
							if (
								(preg_match("/^[0-9]{1,}$/i",$date_day[$reminder])) &&
								(preg_match("/^[0-9]{1,}$/i",$date_month[$reminder])) &&
								(preg_match("/^[0-9]{1,}$/i",$date_year[$reminder])) &&
								(preg_match("/^[0-9]{1,}$/i",$time_hour[$reminder])) &&
								(preg_match("/^[0-9]{1,}$/i",$time_minute[$reminder]))
							) {
							$upd_sql=",`show_date`='".sql_quote(@$date_year[$reminder])."-".sql_quote(@$date_month[$reminder])."-".sql_quote(@$date_day[$reminder])." ".sql_quote(@$time_hour[$reminder]).":".sql_quote(@$time_minute[$reminder]).":00'";
							if (!checkdate($date_month[$reminder],$date_day[$reminder],$date_year[$reminder]))
								$upd_sql='';
								if ($db->query("update `%reminders%` set `subject`='".sql_quote($subject[$reminder])."',`show`=0 $upd_sql where id_reminder=$reminder")) {
									$upd++;
								}
							}
					}
					}
				}
				$this->setCongratulation('',"Обновлено $upd напоминаний, удалено $d напоминаний.",5000);
			}
			$modAction="view";
		break;
		case "addreminder":
			$mode=@$_REQUEST["mode"];
			$this->addPath('Органайзер','/admin?module=notes',true);
			if ($mode=="edit") {
			$this->addPath('Редактирование напоминания','',false);
			} else {
			$this->addPath('Добавление напоминания','',false);
			}
			$this->assignPath();
			
			if (isset($_REQUEST["id_reminder"])) {
				$id_reminder=@$_REQUEST["id_reminder"];
				$reminder=$this->getReminderByID($id_reminder);
			}
			
			if (isset($_REQUEST["save"])) {
				$first=false;
				$subject=@$_REQUEST["subject"];
				$text=strip_tags(@$_REQUEST["text"]);
				$date_reminder=array();
				$date_reminder[0]=@$_REQUEST["date_reminder_day"];
				$date_reminder[1]=@$_REQUEST["date_reminder_month"];
				$date_reminder[2]=@$_REQUEST["date_reminder_year"];
				$time_reminder=array();
				$time_reminder[0]=@$_REQUEST["time_reminder_hour"];
				$time_reminder[1]=@$_REQUEST["time_reminder_minute"];
				if (isset($_REQUEST["undelete"])) {
					$undelete=1;
				} else {
					$undelete=0;
				}
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($reminder) {
						$subject=$reminder["subject"];
						$text=$reminder["content"];
						$undelete=$reminder["undelete"];
						$date_reminder=array();
						$date_reminder[0]=@$reminder["date_day"];
						$date_reminder[1]=@$reminder["date_month"];
						$date_reminder[2]=@$reminder["date_year"];
						$time_reminder=array();
						$time_reminder[0]=@$reminder["time_hour"];
						$time_reminder[1]=@$reminder["time_minute"];
					}
				} else {
					$subject="";
					$text="";
					$date_reminder=array();
					$date_reminder[0]=(int)date("d");
					$date_reminder[1]=(int)date("m");
					$date_reminder[2]=(int)date("Y");
					$time_reminder=array();
					$time_reminder[0]=(int)date("G");
					$time_reminder[1]=(int)date("i");
					$undelete=0;
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField('Тема напоминания','Неверно заполнена тема напоминания',"text",$subject,$subject,"/^[^`#]{2,250}$/i","subject",0,'Проверить позиции в поисковиках',array('size'=>'40','ticket'=>''));

$frm->addField('Текст напоминания','Неверно заполнен текст напоминания',"textarea",$text,$text,"/^[^`#]{2,200}$/i","text",1,'',array('rows'=>'40','cols'=>'10','ticket'=>'От 2 до 200 символов'));

$frm->addField('Дата напоминания','Неверно выбрана дата напоминания',"date",$date_reminder,$date_reminder,"/^[0-9]{1,}$/i","date_reminder",0,"19.01.2008");

$frm->addField('Время напоминания','Неверно выбрано время напоминания',"time",$time_reminder,$time_reminder,"/^[0-9]{1,}$/i","time_reminder",0,"19:45");

$frm->addField('Не удалять напоминание после наступления события','',"check",$undelete,$undelete,"/^[^`#]{1,}$/i","undelete",0,'');

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
if (isset($_REQUEST["id_reminder"])) {
$id_reminder=$_REQUEST["id_reminder"];
$frm->addField("","","hidden",$id_reminder,$id_reminder,"/^[^`]{0,}$/i","id_reminder",1);
}

if ($mode=="edit") {
	$s_name=$lang["forms"]["notes"]["edit_reminder_submit_name"];
} else {
	$s_name=$lang["forms"]["notes"]["add_reminder_submit_name"];
}

if (!checkdate($date_reminder[1],$date_reminder[0],$date_reminder[2]))
	$frm->addError($lang["error"]["incorrect_date"]);

			if (
$this->processFormData($frm,$s_name,$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_reminder)) {
				 	if ($db->query("update %reminders% set `subject`='".sql_quote($subject)."' , `undelete`=$undelete , content='".sql_quote($text)."' , `show_date`='".sql_quote(@$date_reminder[2])."-".sql_quote(@$date_reminder[1])."-".sql_quote(@$date_reminder[0])." ".sql_quote(@$time_reminder[0]).":".sql_quote(@$time_reminder[1]).":00' ,`show`=0 where id_reminder=$id_reminder")) {
						//отредактировали
				   $this->setCongratulation('',$lang["congratulation"]["reminder_edit"],3000);
				   $modAction="view";
				   $this->clearPath();
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
 $add_id=$this->addReminder($subject,$text,$date_reminder,$time_reminder,$undelete);
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $this->setCongratulation('',$lang["congratulation"]["reminder_add"],3000);
				   $modAction="view";
				   $this->clearPath();
				 }
				}
			}
		break;
		case "delete_reminders":
			$this->deleteNewReminders();
			die("OK");
		break;
		case "get_new":
			$reminders=$this->getReminders(true);
			$n=0;
			if (is_array($reminders)) {
			foreach ($reminders as $key=>$reminder) {
				$reminders[$n]["subject"]=ToUTF8($reminder["subject"]);
				$reminders[$n]["content"]=ToUTF8($reminder["content"]);
				$n++;
			}
			}
			$modules=$this->getInstallModulesFast();
			foreach ($modules as $key=>$module) {
				if (@$module["name"]!=false) {
				$mod=$this->includeModule($this->getModule($module["name"]));
				if (method_exists($mod,'getNewReminders')) {
					$mod_reminder=$mod->getNewReminders();
					if (is_array($mod_reminder)) {
						$reminders[$n]=$mod_reminder;
						if ($settings["module_notes"]==0) {
							unset($reminders[$n]["subject"]);
							unset($reminders[$n]["content"]);
						}
						$reminders[$n]["time"]=3000;
						$reminders[$n]["module"]=$module["name"];
						$n++;
					}
				}
				}
			}
			/*получаем текущие напоминания на сегодня*/
			$count_reminders=$this->getNowReminders();
			if ($count_reminders>0) {
				$reminders[$n]["silent"]=true;
				$reminders[$n]["module"]="notes";
				$reminders[$n]["count"]=$count_reminders;
				$n++;
			}
			$count_objects=$objects=$this->getAllObjectsCount();
			if ($count_objects>0) {
				$reminders[$n]["silent"]=true;
				$reminders[$n]["module"]="gallery";
				$reminders[$n]["count"]=$count_objects;
				$n++;
			}
			die(json_encode($reminders));
		break;
		default:
			$modAction="view";
	}
	
	if ($modAction=="view") {
		$reminders=$this->getReminders();
		$smarty->assign("reminders",$reminders);
	}
	
	if ($modAction=="notes") {
		$notes=$this->getNotes();
		$smarty->assign("notes",$notes);
	}
	
	$smarty->assign("modAction",$modAction);
}
?>