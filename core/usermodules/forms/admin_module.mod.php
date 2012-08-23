<?
/*
Модуль формы, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
$m_action=@$_REQUEST["m_action"];
			
switch ($m_action) {
	case "forward_order":
		$engine->clearPath();	
		$id_order=@$_REQUEST["id_order"];
		if (preg_match("/^[0-9]{1,}$/i",$id_order)) {
			$order=$this->getOrderByID($id_order);
			$smarty->assign("order",$order);
			$answers=$this->getAnswers($id_order);
			$smarty->assign("answers",$answers);
			$form=$this->getFormByID($order["id_form"]);
			if (isset($_REQUEST["save"])) {
				$first=false;
				$email=@$_REQUEST["email"];
				$subject=@$_REQUEST["subject"];
				$content=$engine->stripContent(@$_REQUEST["fck1"]);
			} else {
				$first=true;
				$email=$order["email"];
				$search=array('%NUMBER%','%FIO%');
				$replace=array($order["order_number"],$order["fio"]);
				if (!is_array($answers)) {
				$order["content"]=str_replace($search,$replace,$order["content"]);
				} else {
				$answer_text="";
				foreach ($answers as $answer) {
					if ($answer["is_admin"]==0) {
						$answer_text=$answer["forwardtext"];
					}
				}
				$order["content"]=str_replace($search,$replace,$answer_text);
				}
				$forwardcontent=str_replace($search,$replace,$form["forwardcontent"]);
				$subject='RE: '.str_replace($search,$replace,$form["caption_mail_user"]);
				$content=$forwardcontent.'<p></p><hr><font style="color:#A1A1A1;"><i>Вы писали: <br>'.$order["content"].'</i></font>';
			}
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			$frm->addField("E-mail получателя","Неверно заполнен e-mail получателя","text",$email,$email,"/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i","email",1,"",array('size'=>'40','ticket'=>"e-mail адрес"));
			$frm->addField("Тема письма","Неверно заполнена тема письма","text",$subject,$subject,"/^[^`]{2,255}$/i","subject",1,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));
			$fck_editor1=$engine->createFCKEditor("fck1",$content);
			$frm->addField("Содержимое письма","Неверно заполнено содержимое письма","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1);
			$frm->addField("","","hidden",$id_order,$id_order,"/^[0-9]{1,}$/i","id_order",1);
			if (
$engine->processFormData($frm,"Отправить",$first
			)) {
				if ($this->createAnswer($id_order,1,$form["email"],$content)) {
				$this_cat=$engine->getCategoryByID($form["id_category"]);
				$content.='<p><b>ВНИМАНИЕ</b> Данное сообщение было сгенерировано автоматизированной системой, если Вы хотите ответить на него , то пожалуйста воспользуйтесь следующей ссылкой:</p><p><a href="'.$this_cat["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($email).'" target="_blank">'.$this_cat["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($email).'</a></p>';
				$smarty->assign("save",true);
				mailHTML($email,$form["email"],$subject,$content);
				$db->query("update `%FORMS_ORDERS%` set unread=0 where id_order=".$order["id_order"]);
				$db->query("update `%ORDER_ANSWERS%` set unread=0 where id_order=".$order["id_order"]);
				$smarty->assign("sended",true);
				}
			}
		}
	break;
	case "save":
			$idform=@$_REQUEST["idform"];
			$vis=@$_REQUEST["vis"];
			$capt=@$_REQUEST["capt"];
			$email=@$_REQUEST["email"];
			$n=0;
			foreach ($idform as $key=>$form) {
				if (isset($vis[$key])) {
					$vis_value=1;
				} else {
					$vis_value=0;
				}
				$caption="";
				$mail="";
				if (isset($capt[$key]))
					$caption=",`caption_admin`='".sql_quote($capt[$key])."'";
				if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$email[$key]))
					$mail=",`email`='".sql_quote($email[$key])."'";				
				if ($db->query("UPDATE %FORMS% set `visible`=$vis_value $caption $mail where `id_form`=$form")) 
					$n++;
			}
			$engine->setCongratulation("","Обновлено $n форм(ы)",3000);
			$m_action="view";
	break;	
	case "add":
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
	$values=array();
	$engine->getRubricsTreeEx($values,0,0,true,"",false);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_form"])) {
				$id_form=@$_REQUEST["id_form"];
				$form=$this->getFormByID($id_form);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$caption_admin=@$_REQUEST["caption_admin"];
				$caption_mail_admin=@$_REQUEST["caption_mail_admin"];
				$caption_mail_user=@$_REQUEST["caption_mail_user"];
				$content=$engine->stripContent(@$_REQUEST["fck1"]);
				$forwardcontent=$engine->stripContent(@$_REQUEST["fck4"]);
				$success_admin=$engine->stripContent(@$_REQUEST["fck2"]);
				$success_user=$engine->stripContent(@$_REQUEST["fck3"]);
				$email=@$_REQUEST["email"];
				$id_cat=@$_REQUEST["id_cat"];
				if (isset($_REQUEST["visible"])) {
				 $visible=1;
				} else {
				 $visible=0;
				}
				$start_value=@$_REQUEST["start_value"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($form) {
						$caption=$form["caption"];
						$caption_admin=$form["caption_admin"];
						$caption_mail_admin=$form["caption_mail_admin"];
						$caption_mail_user=$form["caption_mail_user"];
						$content=$form["content"];
						$forwardcontent=$form["forwardcontent"];
						$success_admin=$form["success_admin"];
						$success_user=$form["success_user"];
						$email=$form["email"];
						$id_cat=$form["id_category"];
						$visible=$form["visible"];
						$start_value=$form["start_value"];
						$lang_values=$engine->generateLangArray("FORMS",$form);
					}
				} else {
					$caption="";
					$caption_admin="";
					$caption_mail_admin='';
					$caption_mail_user='';
					$content="";
					$forwardcontent="";
					$success_admin="";
					$success_user="";
					$email=$settings["mailadmin"];
					if (isset($_REQUEST["id_cat"])) {
						if (preg_match("/^[0-9]{1,}$/i",$_REQUEST["id_cat"])) {
							$id_cat=$_REQUEST["id_cat"];
						}
					} else {
					$id_cat=@$values[0]["id"];
					}
					$visible=0;
					$start_value="0";
					$lang_values=$engine->generateLangArray("FORMS",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
$frm->addField("Раздел, в котором будет выводиться форма","Ошибка выбора раздела для формы","list",$values,$id_cat,"/^[0-9]{1,}$/i","id_cat",1,$lang["forms"]["catalog"]["razdel"]["sample"],array('size'=>'30'));
			
$frm->addField("Название формы (пользовательское)","Неверно заполнено название формы (пользовательское)","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",0,"Заполните форму",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Название формы (административное)","Неверно заполнено название формы (административное)","text",$caption_admin,$caption_admin,"/^[^`#]{2,255}$/i","caption_admin",1,"Форма заказа",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Тема письма для администратора","Неверно заполнена тема письма для администратора","text",$caption_mail_admin,$caption_mail_admin,"/^[^`]{2,255}$/i","caption_mail_admin",0,"Поступила заявка с сайта site.ru",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Тема письма для пользователя","Неверно заполнена тема письма для пользователя","text",$caption_mail_user,$caption_mail_user,"/^[^`]{2,255}$/i","caption_mail_user",0,"Вы отправили заявку на сайт site.ru",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("E-mail , на который присылать форму","Неверно заполнен e-mail , на который присылать форму","text",$email,$email,"/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i","email",1,"info@site.ru",array('size'=>'40','ticket'=>"e-mail адрес"));

$fck_editor4=$engine->createFCKEditor("fck4",$forwardcontent);
$frm->addField("Текст, при ответе администратором","Неверно заполнен текст, при ответе администратором","solmetra",$fck_editor4,$fck_editor4,"/^[[:print:][:allnum:]]{1,}$/i","forwardcontent",1,"");

$frm->addField("Начальное значение счетчика заказов","Неверно заполнено начальное значение счетчика заказов","text",$start_value,$start_value,"/^[0-9]{1,5}$/i","start_value",0,"700",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Показывать на сайте","Неверно выбрано свойство показывать на сайте","check",$visible,$visible,"/^[0-9]{1}$/i","visible",1);

$fck_editor1=$engine->createFCKEditor("fck1",$content);
$frm->addField("Краткое описание формы","Неверно заполнено краткое описание формы","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$fck_editor2=$engine->createFCKEditor("fck2",$success_admin);
$frm->addField("Сообщение об успешной отправке формы администратору","Неверно заполнено сообщение об успешной отправке формы администратору","solmetra",$fck_editor2,$fck_editor2,"/^[[:print:][:allnum:]]{1,}$/i","success_admin",1,"");

$fck_editor3=$engine->createFCKEditor("fck3",$success_user);
$frm->addField("Сообщение об успешной отправке формы пользователю","Неверно заполнено сообщение об успешной отправке формы пользователю","solmetra",$fck_editor3,$fck_editor3,"/^[[:print:][:allnum:]]{1,}$/i","success_user",1,"");

$engine->generateLangControls("FORMS",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_form"])) {
$id_form=$_REQUEST["id_form"];
$frm->addField("","","hidden",$id_form,$id_form,"/^[^`]{0,}$/i","id_form",1);
}
if ($mode=="edit") {
	$engine->addPath('Редактирование формы','',false);
} else {
	$engine->addPath('Добавление формы','',false);
}
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_form)) {
				 $category=$engine->getCategoryByID($id_cat);
				 	if ($db->query("update %FORMS% set `caption`='".sql_quote($caption)."',`caption_admin`='".sql_quote($caption_admin)."',`caption_mail_admin`='".sql_quote($caption_mail_admin)."' ,`caption_mail_user`='".sql_quote($caption_mail_user)."', email='".sql_quote($email)."' , `content`='".sql_quote($content)."',`forwardcontent`='".sql_quote($forwardcontent)."',`success_user`='".sql_quote($success_user)."',`success_admin`='".sql_quote($success_admin)."',visible=$visible,id_category=$id_cat,`category_caption`='".sql_quote($category["caption"])."',`start_value`=$start_value ".$engine->generateUpdateSQL("FORMS",$lang_values)." where id_form=$id_form")) {
						//отредактировали
				//	   $modAction="view";
				    $engine->setCongratulation("","Форма отредактирована успешно!",3000);
					$m_action="view";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $add_id=$this->addForm($id_cat,$caption,$caption_admin,$caption_mail_admin,$caption_mail_user,$content,$forwardcontent,$success_admin,$success_user,$email,$visible,$start_value,$engine->generateInsertSQL("FORMS",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $engine->setCongratulation("","Форма создана успешно!",3000);
				   $engine->addModuleToCategory($this->thismodule["name"],$id_cat);
					$m_action="view";
				 }
			}
		}
		$engine->assignPath();
	break;
	case "set_value":
		$engine->clearPath();
		$id_input=@$_REQUEST["id_input"];
		if (preg_match("/^[0-9]{1,}$/i",$id_input)) {
			$input=$this->getInputByID($id_input);
			$smarty->assign("input",$input);
			if (isset($_REQUEST["add_value"])) {
				$caption=@$_REQUEST["caption"];
				if (isset($_REQUEST["default"])) {
					$default=1;
				} else {
					$default=0;
				}
				$this->addValue($id_input,$caption,$default);
				$smarty->assign("added",true);
			}
			if (isset($_REQUEST["save_value"])) {
				$idval=@$_REQUEST["idval"];
				$delete=@$_REQUEST["delete"];
				$default_value=@$_REQUEST["default_value"];
				$value_caption=@$_REQUEST["value_caption"];
				foreach ($idval as $key=>$val) {
					if (isset($delete[$key])) {
						$db->query("delete from `%INPUT_VALUES%` where id_value=$val");
					} else {
						$db->query("update `%INPUT_VALUES%` set `caption`='".$value_caption[$key]."' , `default`=0 where id_value=$val");
					}
				}
				$db->query("update `%INPUT_VALUES%` set `default`=1 where id_value=$default_value");
				$smarty->assign("changed",true);
			}
			$values=$this->getValuesByInput($id_input);
			$smarty->assign("values",$values);
		}
	break;
	case "addinput":
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);	
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_form"])) {
				$id_form=@$_REQUEST["id_form"];
				$form=$this->getFormByID($id_form);
				$smarty->assign("form",$form);
				$engine->addPath($form["caption_admin"],'/admin/?module=modules&modAction=settings&m_action=view_form&id_form='.$form["id_form"].'&module_name='.$this->thismodule["name"],true);	
			}
			if (isset($_REQUEST["id_input"])) {
				$id_input=@$_REQUEST["id_input"];
				$input=$this->getInputByID($id_input);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=@$_REQUEST["caption"];
				$error_text=@$_REQUEST["error_text"];
				$tooltip=@$_REQUEST["tooltip"];
				$id_type=@$_REQUEST["id_type"];
				$data_type=@$_REQUEST["data_type"];
				if (isset($_REQUEST["obyaz"])) {
				 $obyaz=1;
				} else {
				 $obyaz=0;
				}
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($input) {
						$caption=$input["caption"];
						$error_text=$input["error_text"];
						$tooltip=$input["tooltip"];
						$id_type=$input["input_type"];
						$obyaz=$input["obyaz"];
						$data_type=$input["data_type"];
						$lang_values=$engine->generateLangArray("FORMS_INPUT",$input);
					}
				} else {
					$caption="";
					$error_text="";
					$tooltip="";
					if (isset($_REQUEST["id_type"])) {
						if (preg_match("/^[0-9]{1,}$/i",$_REQUEST["id_type"])) {
							$id_type=$_REQUEST["id_type"];
						}
					} else {
					$id_type=@$this->thismodule["inputs"][0]["id"];
					}
					if (isset($_REQUEST["data_type"])) {
						if (preg_match("/^[0-9]{1,}$/i",$_REQUEST["data_type"])) {
							$data_type=$_REQUEST["data_type"];
						}
					} else {
					$data_type=@$this->thismodule["types"][0]["id"];
					}
					$obyaz=0;
					$lang_values=$engine->generateLangArray("FORMS_INPUT",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
$frm->addField("Тип элемента","Ошибка выбора типа элемента","list",$this->thismodule["inputs"],$id_type,"/^[0-9]{1,}$/i","id_type",1,"Текстовое поле",array('size'=>'30'));
			
$frm->addField("Тип данных элемента","Ошибка выбора типа данных элемента","list",$this->thismodule["types"],$data_type,"/^[0-9]{1,}$/i","data_type",1,"e-mail",array('size'=>'30'));
			
$frm->addField("Элемент является обязательным для заполнения","Неверно выбрано обязательности элемента","check",$obyaz,$obyaz,"/^[0-9]{1}$/i","obyaz",1);
			
$frm->addField("Название элемента","Неверно заполнено название элемента","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Открытие сайта",array('size'=>'40','ticket'=>"Любые буквы и цифры"));
			
$frm->addField("Текст, при неправильном вводе значения","Неверно заполнен текст при неправильном вводе значения","textarea",$error_text,$error_text,"/^[^#]{1,}$/i","error_text",0,"Элемент <название> заполнен неверно",array('rows'=>'40','cols'=>'10'));

$frm->addField("Пример правильного значения","Неверно заполнен пример правильного значения","textarea",$tooltip,$tooltip,"/^[^#]{1,}$/i","tooltip",0,"",array('rows'=>'40','cols'=>'10'));

$engine->generateLangControls("FORMS_INPUT",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_form"])) {
$id_form=$_REQUEST["id_form"];
$frm->addField("","","hidden",$id_form,$id_form,"/^[^`]{1,}$/i","id_form",1);
}
if (isset($_REQUEST["id_input"])) {
$id_input=$_REQUEST["id_input"];
$frm->addField("","","hidden",$id_input,$id_input,"/^[^`]{1,}$/i","id_input",1);
}

if ($mode=="edit") {
	$engine->addPath('Редактирование элемента управления','',false);
} else {
	$engine->addPath('Добавление элемента управления','',false);
}
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_input)) {
				 $type_caption=$this->thismodule["inputs"][$id_type]["name"];
				 $data_caption=$this->thismodule["types"][$data_type]["name"];
				 	if ($db->query("update `%FORMS_INPUT%` set `caption`='".sql_quote($caption)."' ,input_type=$id_type,data_type=$data_type,obyaz=$obyaz,error_text='".sql_quote($error_text)."', tooltip='".sql_quote($tooltip)."',`type_caption`='".sql_quote($type_caption)."',`data_caption`='".sql_quote($data_caption)."' ".$engine->generateUpdateSQL("FORMS_INPUT",$lang_values)." where id_input=$id_input")) {
						//отредактировали
				//	   $modAction="view";
				    $engine->setCongratulation("","Элемент отредактирован успешно!",3000);
					$m_action="view_form";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $type_caption=$this->thismodule["inputs"][$id_type]["name"];
				 $data_caption=$this->thismodule["types"][$data_type]["name"];
 $add_id=$this->addInput($id_form,$type_caption,$id_type,$data_caption,$data_type,$obyaz,$caption,$error_text,$tooltip,$engine->generateInsertSQL("FORMS_INPUT",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $engine->setCongratulation("","Элемент создан успешно!",3000);
				   $m_action="view_form";
				 }
			}
		}
			$engine->assignPath();
	break;
	case "delete_input":
		$id_form=@$_REQUEST["id_form"];
		$id_input=@$_REQUEST["id_input"];
		if (preg_match("/^[0-9]{1,}$/i",$id_form) && preg_match("/^[0-9]{1,}$/i",$id_input)) {
			$form=$this->getFormByID($id_form);
			$input=$this->getInputByID($id_input);
			if ($this->deleteInput($id_input)) {
			$engine->setCongratulation("","Элемент управления ".$input["caption"]." успешно удален!",5000);
			$m_action="view_form";
			}
			$smarty->assign("form",$form);
			$smarty->assign("input",$input);
		}
	break;
	case "delete":
		$id_form=@$_REQUEST["id_form"];
		if (preg_match("/^[0-9]{1,}$/i",$id_form)) {
			$form=$this->getFormByID($id_form);
			if ($this->deleteForm($id_form)) {
				$engine->setCongratulation("","Форма ".$form["caption_admin"]." успешно удалена",3000);
				$m_action="view";
			}
		}
	break;
	case "view_form":break;
	case "view_orders":
		$id_form=@$_REQUEST["id_form"];
		if (preg_match("/^[0-9]{1,}$/i",$id_form)) {
			$form=$this->getFormByID($id_form);
			$smarty->assign("form",$form);
			$engine->clearPath();
			$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
			$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
$engine->addPath('Просмотр заказов по форме '.$form["caption_admin"],'',false);
			$engine->assignPath();
		$onpage=20;
		$count=$this->getCountOrdersByForm($id_form);
		$pages=ceil($count/$onpage);
		$pages_arr=array();
			for ($x=0;$x<=$pages-1;$x++) $pages_arr[]=$x;
			if (isset($_REQUEST["p"])) {
				$pg=@$_REQUEST["p"];
			if (!preg_match("/^[0-9]{1,}$/i",$pg)) $pg=0;
			if ($pg>$pages) $pg=0;
				if ($pg<0)
					$pg=0;
				} else {
					$pg=0;
				}
			$unread_answer=$this->getUnreadAnswers();
			$orders=$this->getOrdersByForm($id_form,$pg,$onpage);
			if (is_array($orders)) {
			foreach ($orders as $key=>$ord) {
				if (isset($unread_answer[$ord["id_order"]])) {
					$orders[$key]["unread"]=1;
				}
			}
			if (is_array($pages_arr)) {
				$smarty->assign("orders",$orders);
				$smarty->assign("pages",$pages_arr);
				$smarty->assign("pg",$pg);
			}
			}
			$smarty->assign("id_form",$id_form);
		}
	break;
	case "read_orders":
		$engine->clearPath();	
		$id_form=@$_REQUEST["id_form"];
		if (preg_match("/^[0-9]{1,}$/i",$id_form)) {
			$res=$db->query("select * from `%FORMS_ORDERS%` where id_form=$id_form and unread=1 order by `order_number`");
			while ($row=$db->fetch($res)) {
				$orders[]=$row;
			}
			if (isset($orders))
				$smarty->assign("orders",$orders);
			$db->query("update `%FORMS_ORDERS%` set unread=0 where id_form=".$id_form);
		}	
	break;
	case "view_order":
		$engine->clearPath();
		$id_order=@$_REQUEST["id_order"];
		if (preg_match("/^[0-9]{1,}$/i",$id_order)) {
			$order=$this->getOrderByID($id_order);
			$smarty->assign("order",$order);
			if ($order["unread"]==1)
				$db->query("update `%FORMS_ORDERS%` set unread=0 where id_order=".$order["id_order"]);
		}
	break;
	default:
		$m_action="view";
}
if ($m_action=="view_form") {
		$id_form=@$_REQUEST["id_form"];
		if (preg_match("/^[0-9]{1,}$/i",$id_form)) {
			$form=$this->getFormByID($id_form);
			$smarty->assign("form",$form);
			$engine->clearPath();
			$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
			$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);	$engine->addPath($form["caption_admin"],'',false);
			$engine->assignPath();
			$inputs=$this->getInputsByForm($id_form);
			if (isset($_REQUEST["sort_down"])) {
				$id_input=@$_REQUEST["id_input"];
				if (preg_match("/^[0-9]{1,}$/i",$id_input)) {
				foreach ($inputs as $key=>$input) {
					if ($id_input==$input["id_input"]) {
					 if (isset($inputs[$key-1])) {
						$prom=$inputs[$key-1];
						$inputs[$key-1]=$input;
						$inputs[$key]=$prom;
					 }
					}
				}
				foreach ($inputs as $key=>$input) {
					$db->query("UPDATE `%FORMS_INPUT%` set `sort`=".$key." where id_input=".$input["id_input"]);
				}
				$engine->setCongratulation("","Элементы отсортированы",3000);
				}
			}
			if (isset($_REQUEST["sort_up"])) {
				$id_input=@$_REQUEST["id_input"];
				if (preg_match("/^[0-9]{1,}$/i",$id_input)) {
				foreach ($inputs as $key=>$input) {
					if ($id_input==$input["id_input"]) {
					 if (isset($inputs[$key+1])) {
						$prom=$inputs[$key+1];
						$inputs[$key+1]=$input;
						$inputs[$key]=$prom;
					 }
					}
				}
				foreach ($inputs as $key=>$input) {
					$db->query("UPDATE `%FORMS_INPUT%` set `sort`=".$key." where id_input=".$input["id_input"]);
				}
				$engine->setCongratulation("","Элементы отсортированы",3000);
				}
			}
			$smarty->assign("inputs",$inputs);
			$smarty->assign("inputs_count",sizeof($inputs));
		}
}
if ($m_action=="view") {
	//получаем все формы
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'',false);
	$engine->assignPath();
	$unread=$this->getCountUnreadOrders();
	$forms=$this->getForms(0);
	if (is_array($forms))
		foreach ($forms as $id_form=>$form) {
			if (isset($unread[$id_form])) {
				$forms[$id_form]["unread"]=$unread[$id_form];
			} else {
				$forms[$id_form]["unread"]=0;
			}
		}
	$smarty->assign("forms",$forms);
}
$smarty->assign("m_action",$m_action);
?>