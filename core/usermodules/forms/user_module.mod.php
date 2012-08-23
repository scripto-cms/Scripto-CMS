<?
//Модуль формы, пользовательская часть
//получаем формы
global $page;
global $thismodule;

$form=$this->getFormByCategory($page["id_category"]);
$smarty->assign("frm",$form);
if ($form["visible"]) {
if (isset($_REQUEST["answer_mode"])) {
	$id_order=@$_REQUEST["id_order"];
	$skey=@$_REQUEST["skey"];
	if (preg_match('/^[0-9]{1,}$/i',$id_order)) {
		$order=$this->getOrderByID($id_order);
		$smarty->assign("order",$order);
		$form=$this->getFormByID($order["id_form"]);
		$smarty->assign("form",$form);
		$mode="denied";
		if (md5($form["email"].$config["secretkey"])==$skey) {
			$mode="admin";
		}
		if (md5($order["email"])==$skey) {
			$mode="user";
		}
		$smarty->assign("mode",$mode);
		$smarty->assign("answer_mode",true);
		if ($mode=='admin' || $mode=='user') {
		$answers=$this->getAnswers($id_order);
		$smarty->assign("answers",$answers);
		$engine->addSubPath('Ответ на заявку #'.$id_order,$page['url'].'/?id_order='.$order["order_number"]);
		$page["caption"]='Ответ на заявку #'.$order["order_number"];
		$search=array('%NUMBER%','%FIO%');
		if ($mode=="admin") {
		$replace=array($order["order_number"],$order["fio"]);
		} else {
		$replace=array($order["order_number"],'Администратор');
		}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$content=$engine->stripContent(@$_REQUEST["fck1"]);
			} else {
				$first=true;
				if ($mode=="admin") {
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
				$order["content"]=$forwardcontent.'<p></p><hr><font style="color:#A1A1A1;"><i>Вы писали: <br>'.$order["content"].'</i></font>';
				} else {
				if ($order["fio"]) {
				$order["content"]='Добрый день, Администратор<br><br>С Уважением, '.$order["fio"];
				} else {
				$order["content"]='';
				}
				}
				//$forwardcontent=str_replace($search,$replace,$form["forwardcontent"]);
				$content=$order["content"];
			}
			if ($mode=="admin") {
			$subject='RE: '.str_replace($search,$replace,$form["caption_mail_user"]);
			} else {
			$subject='RE: '.str_replace($search,$replace,$form["caption_mail_admin"]);
			}
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			$fck_editor1=$engine->createFCKEditor("fck1",$content);
			$frm->addField("Содержимое письма","Неверно заполнено содержимое письма","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1);
			$frm->addField("","","hidden",$id_order,$id_order,"/^[0-9]{1,}$/i","id_order",1);
			if (
$engine->processFormData($frm,"Отправить",$first
			)) {
				if ($mode=="admin") {
				if ($this->createAnswer($id_order,1,$form["email"],$content)) {
				$smarty->assign("save",true);
				$this_cat=$engine->getCategoryByID($form["id_category"]);
				$content.='<p><b>ВНИМАНИЕ</b> Данное сообщение было сгенерировано автоматизированной системой, если Вы хотите ответить на него , то пожалуйста воспользуйтесь следующей ссылкой:</p><p><a href="'.$this_cat["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($order["email"]).'" target="_blank">'.$this_cat["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($order["email"]).'</a></p>';
				mailHTML($order["email"],$form["email"],$subject,$content);
				$smarty->assign("sended",true);
				}
				} else {
				if ($this->createAnswer($id_order,0,$order["email"],$content)) {
				$smarty->assign("save",true);
		$content.='<p><b>ВНИМАНИЕ</b> Данное сообщение было сгенерировано автоматизированной системой, если Вы хотите ответить на него , то пожалуйста воспользуйтесь следующей ссылкой:</p><p><a href="'.$page["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($form["email"].$config["secretkey"]).'" target="_blank">'.$page["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($form["email"].$config["secretkey"]).'</a></p>';				
				mailHTML($form["email"],$order["email"],$subject,$content);
				$smarty->assign("sended",true);
				}
				}
			}
		}
	}
} else {
$inputs=$this->getInputsByForm($form["id_form"]);
if (isset($_REQUEST["save"])) {
	$first=false;
	$values=@$_REQUEST["values"];
	foreach ($inputs as $key=>$input) {
	if (($input["input_type"]==5) || ($input["input_type"]==6)) {
	$vals[$input["id_input"]]=$this->getValuesByInputEx($input["id_input"]);
	}
		if (($input["input_type"]==4)) {
			if (isset($values[$input["id_input"]])) {
				$values[$input["id_input"]]=1;
			} else {
				$values[$input["id_input"]]=0;
			}
		}
		if ($input["input_type"]==8) {
			$values[$input["id_input"]]=@$_REQUEST["kcaptcha"];
		}
		
		if ($input["input_type"]==9) {
			$values[$input["id_input"]][0]=@$_REQUEST["date_".$input["id_input"]."_day"];
			$values[$input["id_input"]][1]=@$_REQUEST["date_".$input["id_input"]."_month"];
			$values[$input["id_input"]][2]=@$_REQUEST["date_".$input["id_input"]."_year"];
		}
		if ($input["input_type"]==10) {
			$values[$input["id_input"]][0]=@$_REQUEST["time_".$input["id_input"]."_hour"];
			$values[$input["id_input"]][1]=@$_REQUEST["time_".$input["id_input"]."_minute"];
		}
	}
} else {
	$first=true;
	if (isset($_REQUEST["values"])) {
	$values=@$_REQUEST["values"];
	if (!is_array($values)) $values=array();
	} else {
	$values=array();
	}
	if (is_array($inputs)) {
	foreach ($inputs as $key=>$input) {
		if (!isset($values[$input["id_input"]])) {
		if (($input["input_type"]==5) || ($input["input_type"]==6)) {
			$vals2[$input["id_input"]]=$this->getValuesByInput($input["id_input"]);
			$vals[$input["id_input"]]=$this->getValuesByInputEx($input["id_input"]);
			if (is_array($vals2[$input["id_input"]]))
			foreach ($vals2[$input["id_input"]] as $v) {
				if ($v["default"]) {
					$values[$input["id_input"]]=$v["id_value"];
					break;
				}
			}
		} elseif ($input["input_type"]==9) {
			$values[$input["id_input"]][0]=(int)date("d");
			$values[$input["id_input"]][1]=(int)date("m");
			$values[$input["id_input"]][2]=(int)date("Y");
		} elseif ($input["input_type"]==10) {
			$values[$input["id_input"]][0]=(int)date("H");
			$values[$input["id_input"]][1]=(int)date("i");
		} else {
			$values[$input["id_input"]]="";
		}
		}
	}
	}
}
	require ($config["classes"]["form"]);
	$frm=new Form($smarty);
	$smarty->assign("is_file_form",true);
	$user_email='';
	$fio='';
	if (is_array($inputs))
	foreach ($inputs as $key=>$input) {
	if ($input["data_type"]==6) {
		//ФИО
		$fio=$values[$input["id_input"]];
	}
	if ($input["data_type"]==2) {
		//email
		if (trim($user_email)=='') {
			$user_email=	$values[$input["id_input"]];
		} else {
			$user_email.=';'.$values[$input["id_input"]];
		}
	}	
		if (($input["input_type"]==5) || ($input["input_type"]==6)) {
		if (isset($values[$input["id_input"]]))
	$frm->addField($input["caption"],$input["error_text"],$this->thismodule["inputs"][$input["input_type"]]["type"],$vals[$input["id_input"]],$values[$input["id_input"]],"/^[0-9]{1,}$/i","values[".$input["id_input"]."]",$input["obyaz"],$input["tooltip"],array('size'=>'30'));		
		} elseif ($input["input_type"]==7) {
	$frm->addField($input["caption"],$input["error_text"],$this->thismodule["inputs"][$input["input_type"]]["type"],"","","/^[^`#]{1,}$/i","values[".$input["id_input"]."]",0);
		} elseif ($input["input_type"]==3) {
$fck_editor[$input["id_input"]]=$engine->createFCKEditor("values[".$input["id_input"]."]",$values[$input["id_input"]]);
$frm->addField($input["caption"],$input["error_text"],$this->thismodule["inputs"][$input["input_type"]]["type"],$fck_editor[$input["id_input"]],$fck_editor[$input["id_input"]],"/^[[:print:][:allnum:]]{1,}$/i","values[".$input["id_input"]."]",$input["obyaz"],"",array('real_value'=>$values[$input["id_input"]]));
		} elseif ($input["input_type"]==9) {
	$frm->addField($input["caption"],$input["error_text"],$this->thismodule["inputs"][$input["input_type"]]["type"],$values[$input["id_input"]],$values[$input["id_input"]],$this->thismodule["types"][$input["data_type"]]["eregi"],"date_".$input["id_input"],$input["obyaz"],$input["tooltip"],array('size'=>'30'));
		if (!checkdate($values[$input["id_input"]][1],$values[$input["id_input"]][0],$values[$input["id_input"]][2])) {
			$frm->addError($input["error_text"]);
		}
		} elseif ($input["input_type"]==10) {
	$frm->addField($input["caption"],$input["error_text"],$this->thismodule["inputs"][$input["input_type"]]["type"],$values[$input["id_input"]],$values[$input["id_input"]],$this->thismodule["types"][$input["data_type"]]["eregi"],"time_".$input["id_input"],$input["obyaz"],$input["tooltip"],array('size'=>'30'));
		} elseif ($input["input_type"]==11) {
	$frm->addField($input["caption"],$input["error_text"],$this->thismodule["inputs"][$input["input_type"]]["type"],'','',"/^[0-9]{0,}$/i","files".$input["id_input"]."",$input["obyaz"],$input["tooltip"],array('size'=>'30'));
		} elseif ($input["input_type"]==8) {
$frm->addField($input["caption"],$input["error_text"],"kcaptcha","",$config["classes"]["kcaptha"],"/^[^#]{1,}$/i","kcaptcha",1,'',array('ticket'=>'Любые цифры и буквы'));
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] !=  @$values[$input["id_input"]]){
				$frm->addError($input["error_text"]);
			}
		} else {
	$frm->addField($input["caption"],$input["error_text"],$this->thismodule["inputs"][$input["input_type"]]["type"],$values[$input["id_input"]],$values[$input["id_input"]],$this->thismodule["types"][$input["data_type"]]["eregi"],"values[".$input["id_input"]."]",$input["obyaz"],$input["tooltip"],array('size'=>'30'));
		}
	}
	
	if ($engine->processFormData($frm,$lang["forms"]["send"],$first,"system/classes/module_form.html")) {
		//$engine->setCongratulation("Форма отправлена успешно!");
		unset($_SESSION['captcha_keystring']);
		$form_html=$frm->print_me();
		$smarty->assign("form_html",$form_html);
		$smarty->assign("form_send",true);
		$number=0;
		$number=$this->createOrder($form,$form_html,$fio,$user_email);
		$id_order=mysql_insert_id();
		$smarty->assign("number",$number);
		$search=array('%NUMBER%','%FIO%','%DATA%');
		$replace=array($number,$fio,$form_html);
		if (trim($form["caption_mail_admin"])!='') {
			$admin_subject=str_replace($search,$replace,$form["caption_mail_admin"]);
		} else {
			$admin_subject="[".$form["caption"]."] Сообщение #$number с сайта ".$settings["httproot"];
		}
		if (trim($form["caption_mail_user"])!='') {
			$user_subject=str_replace($search,$replace,$form["caption_mail_user"]);
		} else {
			$user_subject="[".$form["caption"]."] Сообщение #$number с сайта ".$settings["httproot"];
		}		
		$admin_text=str_replace($search,$replace,$form["success_admin"])."<br>".$form_html;
		$user_text=str_replace($search,$replace,$form["success_user"]);
		if (preg_match("/^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$/i",$user_email)) {
			$admin_from=$user_email;
		} else {
			$admin_from=$this->thismodule["mailadmin"];
		}
		$admin_text.='<p><b>ВНИМАНИЕ</b> Данное сообщение было сгенерировано автоматизированной системой, если Вы хотите ответить на него , то пожалуйста воспользуйтесь следующей ссылкой:</p><p><a href="'.$page["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($form["email"].$config["secretkey"]).'" target="_blank">'.$page["url"].'?answer_mode=yes&id_order='.$id_order.'&skey='.md5($form["email"].$config["secretkey"]).'</a></p>';
		mailHTML($form["email"],$admin_from,$admin_subject,$admin_text,true);
		mailHTML($user_email,$form["email"],$user_subject,$user_text);
	}
}
}
?>