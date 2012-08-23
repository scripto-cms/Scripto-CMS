<?
//Управление объектами CMS
if (defined("SCRIPTO_GALLERY")) {
	$page["title"]=$lang["modules"]["objects"];
	$smarty->assign("module_documentation","http://scripto-cms.ru/documentation/standart/gallery/");
	$this->setAdminTitle($lang["modules"]["objects"]);
	switch ($modAction) {
		case "crop":
			if (is_file($config["pathes"]["admin_modules_dir"]."crop.mod.php")) {
				@include($config["pathes"]["admin_modules_dir"]."crop.mod.php");
			}
		break;
		case "changepreview":
			$multiple=@$_REQUEST["multiple"];
			if ($multiple!='yes') $multiple='no';
			$smarty->assign("multiple",$multiple);
			//откуда пришел пользователь
			if (!isset($_REQUEST["ref"])) {
				$ref=getenv("HTTP_REFERER");
				$smarty->assign("ref",$ref);
			}
			//получаем модификатор, если он нужен
			if (isset($_REQUEST["mode"])) {
				$mode=$_REQUEST["mode"];
				if (preg_match("/^[a-zA-Z0-9_-]{1,}$/i",$mode))
					 $smarty->assign("mode",$mode);
			}
			if (isset($_REQUEST["type"])) {
				$type=$_REQUEST["type"];
				$smarty->assign("type",$type);
			} else {
				$type='';
			}			
			if ($type=="video") {
			$this->setAdminTitle($lang["modules"]["changepreview_video"]);
			$page["title"]=$lang["modules"]["changepreview_video"];
			} else {
			$this->setAdminTitle($lang["modules"]["changepreview"]);
			$page["title"]=$lang["modules"]["changepreview"];
			}
			if (isset($_REQUEST["get_rubrics"])) {
				$smarty->assign("get_rubrics",true);
			} else {
				$smarty->assign("get_rubrics",false);
			}
			$secretkey=md5($config["secretkey"]."scripto_gallery".$settings["login"]);
			$smarty->assign("secretkey",$secretkey);
			//получаем категории
			$obj=array();
			if ($type=="video") {
				$res2=$db->query("select count(*) as cnt,id_category from %video% group by id_category");
					while ($row2=$db->fetch($res2)) {
						$obj["video"][$row2["id_category"]]=$row2["cnt"];
					}
			} else {
				$res2=$db->query("select count(*) as cnt,id_category from %photos% group by id_category");
					while ($row2=$db->fetch($res2)) {
						$obj["photos"][$row2["id_category"]]=$row2["cnt"];
					}
			}
			unset($row2);
			$smarty->assign("id_category",false);
			if (isset($_REQUEST["id_category"])) {
				$id_category=@$_REQUEST["id_category"];
				if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
					if ($type=="video") {
						$objects=$this->getObjectsByCat($id_category,0,"video");
						$smarty->assign("count_objects",sizeof($objects["video"]));
					} else {
						$objects=$this->getObjectsByCat($id_category,0,"image");
						$smarty->assign("count_objects",sizeof($objects["image"]));
					}
					$smarty->assign("objects",$objects);
					$smarty->assign("get_category",true);
					$smarty->assign("id_category",$id_category);
				}
			}
			$categories=$this->getRubricsTree(0,0,false,'',false,"",true,false,$obj);
			$smarty->assign("categories",$categories);
			$smarty->assign("doUpload",true);
			$smarty->assign("doAjaxify",true);
		break;
		case "photo_object":
			$id_object=@$_REQUEST["id_object"];
			if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$mode=@$_REQUEST["mode"];
			$save=@$_REQUEST["save"];
			if ($save=="yes") {
				//сохраняем
				$small_preview=sql_quote(@$_REQUEST["small_preview"]);
				$middle_preview=sql_quote(@$_REQUEST["middle_preview"]);
				switch ($mode) {
					case "video":
						$db->query("update %videos% set `preview`='$small_preview', `big_preview`='$middle_preview' where id_video=$id_object");
					break;
					case "music":
						$db->query("update %audio% set `preview`='$small_preview', `big_preview`='$middle_preview' where id_audio=$id_object");
					break;
					case "flash":
						$db->query("update %flash% set `preview`='$small_preview', `big_preview`='$middle_preview' where id_flash=$id_object");
					break;
					case "category":
						$db->query("update %categories% set `preview`='$small_preview', `big_preview`='$middle_preview' where id_category=$id_object");
					break;
				}
			}
			$objects=$this->getAllObjects("image");
				switch ($mode) {
					case "video":
						$object=$this->getVideoByID($id_object);
					break;
					case "music":
						$object=$this->getAudioByID($id_object);
					break;
					case "flash":
						$object=$this->getFlashByID($id_object);
					break;
					case "category":
						$object=$this->getCategoryByID($id_object);
					break;
				}
			$smarty->assign("objects",$objects);
			$smarty->assign("object",$object);
			$smarty->assign("mode",$mode);
			$smarty->assign("id_object",$id_object);
			}
		break;
		case "edit_object":
			$values=array();
			$this->getRubricsTreeEx($values,0,0,true,"",true);
			$id_object=@$_REQUEST["id_object"];
			if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$mode=@$_REQUEST["mode"];
				/*изменение превью*/
				if (isset($_REQUEST["setPreview"])) {
					$previewMode=@$_REQUEST["previewMode"];
					$id_image=@$_REQUEST["id_image"];
					if (preg_match("/^[0-9]{1,}$/i",$id_image)) {
						if ($id_image==0) {
						$img["small_photo"]='';
						$img["medium_photo"]='';
						} else {
						$img=$this->getImageByID($id_image);
						}
						$obj='';
						$id_obj='';
						switch ($mode) {
							case "video":
								$obj="%videos%";
								$id_obj="id_video";
							break;
							case "music":
								$obj="%audio%";
								$id_obj="id_audio";
							break;
							case "flash":
								$obj="%flash%";
								$id_obj="id_flash";
							break;
						}
						switch ($previewMode) {
							case "small":
								if (trim($obj)!='' && trim($id_obj)!='')
								if ($db->query("update `$obj` set preview='".$img["small_photo"]."' where $id_obj=$id_object")) {
									$this->setPreview($img["small_photo"],$previewMode,'Малое превью установлено');
								}
							break;
							case "medium":
								if ($db->query("update `$obj` set big_preview='".$img["medium_photo"]."' where $id_obj=$id_object")) {
									$this->setPreview($img["medium_photo"],$previewMode,'Среднее превью установлено');
								}
							break;
						}
					}
				}
				/*конец изменения превью*/
				switch ($mode) {
					case "photo":
						$object=$this->getImageByID($id_object);
					break;
					case "video":
						$object=$this->getVideoByID($id_object);
					break;
					case "music":
						$object=$this->getAudioByID($id_object);
					break;
					case "flash":
						$object=$this->getFlashByID($id_object);
					break;
				}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$title=@$_REQUEST["title"];
				$id_cat=@$_REQUEST["id_cat"];
				if (!isset($_REQUEST["visible"])) {
					$visible=0;
				} else {
					$visible=1;
				}
				$titletag=@$_REQUEST["titletag"];
				$metatag=@$_REQUEST["metatag"];
				$content=@$_REQUEST["fck1"];
				$uniq=@$_REQUEST["uniq"];
				if (isset($_REQUEST["main"])) {
					$main=1;
				} else {
					$main=0;
				}
				switch ($mode) {
					case "photo":

					break;
					case "video":
						$company=@$_REQUEST["company"];
						$prodolzhitelnost=@$_REQUEST["prodolzhitelnost"];
						$external_url=@$_REQUEST["external_url"];
					break;
					case "music":
						$genre=@$_REQUEST["genre"];
						$label=@$_REQUEST["label"];
						$prodolzhitelnost=@$_REQUEST["prodolzhitelnost"];
						$external_url=@$_REQUEST["external_url"];
					break;
					case "flash":
						$external_url=@$_REQUEST["external_url"];
					break;
				}
			} else {
				$first=true;
				$id_cat=$object["id_category"];
				$title=$object["caption"];
				$titletag=$object["title"];
				$metatag=$object["meta"];
				$content=$object["description"];
				$visible=$object["visible"];
				$uniq=@$_REQUEST["uniq"];
				
				switch ($mode) {
					case "photo":
						$main=$object["main_photo"];
					break;
					case "video":
						$main=$object["main_video"];
						$company=$object["company"];
						$prodolzhitelnost=$object["prodolzhitelnost"];
						$external_url=$object["external_url"];
					break;
					case "music":
						$main=$object["main_audio"];
						$genre=$object["genre"];
						$label=$object["label"];
						$prodolzhitelnost=$object["prodolzhitelnost"];
						$external_url=$object["external_url"];
					break;
					case "flash":
						$main=$object["main_flash"];
						$external_url=$object["external_url"];
					break;
				}
			}
				$this->clearPath();
				$this->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$this->addPath($page["title"],'/admin/?module=objects&modAction=edit&id_cat='.$id_cat,true);
				switch ($mode) {
					case "photo":
						$this->addPath('Редактирование изображения '.$title,'',false);
					break;
					case "video":
						$this->addPath('Редактирование видео '.$title,'',false);
					break;
					case "music":
						$this->addPath('Редактирование аудио '.$title,'',false);
					break;
					case "flash":
						$this->addPath('Редактирование флешролика '.$title,'',false);
					break;
				}				
				
				$this->assignPath();							
			$module["title"]=$lang["modules"]["edit_object"];
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);

$frm->addField($lang["forms"]["object"]["razdel"]["caption"],$lang["forms"]["object"]["razdel"]["error"],"list",$values,$id_cat,"/^[0-9]{1,}$/i","id_cat",1,$lang["forms"]["object"]["razdel"]["sample"],array('size'=>'30'));

$frm->addField($lang["forms"]["object"]["title"]["caption"],$lang["forms"]["object"]["title"]["error"],"text",$title,$title,"/^[^`#]{2,255}$/i","title",1,$lang["forms"]["object"]["title"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object"]["title"]["rules"]));

$frm->addField($lang["forms"]["object"]["visible"]["caption"],$lang["forms"]["object"]["visible"]["error"],"check",$visible,$visible,"/^[0-9]{1}$/i","visible",1);

$frm->addField($lang["forms"]["object"]["main"]["caption"],$lang["forms"]["object"]["main"]["error"],"check",$main,$main,"/^[0-9]{1,}$/i","main",1);

if ($mode=="video" || $mode=="music" || $mode=="flash") {
$frm->addField('Превью для объекта',"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('hidden'=>true));	

$frm->addField('Малое изображение превью','',"preview",$object["preview"],$object["preview"],"/^[0-9]{0,}$/i","min_preview",0,'',array('mode'=>'small','multiple'=>'no','fancy_show'=>true,'id_cat'=>$id_cat));
$frm->addField('Большое изображение превью','',"preview",$object["big_preview"],$object["big_preview"],"/^[0-9]{0,}$/i","big_preview",0,'',array('mode'=>'medium','multiple'=>'no','fancy_show'=>true,'id_cat'=>$id_cat));

$frm->addField('Превью для объекта',"","caption",0,0,"/^[0-9]{1}$/i","preview",0,'',array('end'=>true));
}

$fck_editor1=$this->createFCKEditor("fck1",$content);
$frm->addField($lang["forms"]["object"]["content"]["caption"],$lang["forms"]["object"]["content"]["error"],"solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content2",1,"");

$frm->addField($lang["forms"]["object"]["titletag"]["caption"],$lang["forms"]["object"]["titletag"]["error"],"text",$titletag,$titletag,"/^[^`#]{2,255}$/i","titletag",0,$lang["forms"]["object"]["titletag"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object"]["titletag"]["rules"]));

$frm->addField($lang["forms"]["object"]["metatag"]["caption"],$lang["forms"]["object"]["metatag"]["error"],"textarea",$metatag,$metatag,"/^[^#]{1,}$/i","metatag",0,$lang["forms"]["object"]["metatag"]["sample"],array('rows'=>'40','cols'=>'10','ticket'=>$lang["forms"]["object"]["metatag"]["rules"]));

				switch ($mode) {
					case "photo":

					break;
					case "video":

$frm->addField($lang["forms"]["object_video"]["company"]["caption"],$lang["forms"]["object_video"]["company"]["error"],"text",$company,$company,"/^[^`#]{2,255}$/i","company",0,$lang["forms"]["object_video"]["company"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object_video"]["company"]["rules"]));

$frm->addField($lang["forms"]["object_video"]["prodolzhitelnost"]["caption"],$lang["forms"]["object_video"]["prodolzhitelnost"]["error"],"text",$prodolzhitelnost,$prodolzhitelnost,"/^[^`#]{2,255}$/i","prodolzhitelnost",0,$lang["forms"]["object_video"]["prodolzhitelnost"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object_video"]["prodolzhitelnost"]["rules"]));

$frm->addField($lang["forms"]["object"]["external_url"]["caption"],$lang["forms"]["object"]["external_url"]["error"],"text",$external_url,$external_url,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","external_url",0,$lang["forms"]["object"]["external_url"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object"]["external_url"]["rules"]));
					break;
					case "music":
$frm->addField($lang["forms"]["object_audio"]["genre"]["caption"],$lang["forms"]["object_audio"]["genre"]["error"],"text",$genre,$genre,"/^[^`#]{2,255}$/i","genre",0,$lang["forms"]["object_audio"]["genre"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object_audio"]["genre"]["rules"]));

$frm->addField($lang["forms"]["object_audio"]["label"]["caption"],$lang["forms"]["object_audio"]["label"]["error"],"text",$label,$label,"/^[^`#]{2,255}$/i","label",0,$lang["forms"]["object_audio"]["label"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object_audio"]["label"]["rules"]));

$frm->addField($lang["forms"]["object_audio"]["prodolzhitelnost"]["caption"],$lang["forms"]["object_audio"]["prodolzhitelnost"]["error"],"text",$prodolzhitelnost,$prodolzhitelnost,"/^[^`#]{2,255}$/i","prodolzhitelnost",0,$lang["forms"]["object_audio"]["prodolzhitelnost"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object_audio"]["prodolzhitelnost"]["rules"]));

$frm->addField($lang["forms"]["object"]["external_url"]["caption"],$lang["forms"]["object"]["external_url"]["error"],"text",$external_url,$external_url,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","external_url",0,$lang["forms"]["object"]["external_url"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object"]["external_url"]["rules"]));
					break;
					case "flash":
$frm->addField($lang["forms"]["object"]["external_url"]["caption"],$lang["forms"]["object"]["external_url"]["error"],"text",$external_url,$external_url,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","external_url",0,$lang["forms"]["object"]["external_url"]["sample"],array('size'=>'40','ticket'=>$lang["forms"]["object"]["external_url"]["rules"]));
					break;
				}

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
if (isset($_REQUEST["id_cat1"])) {
$id_cat1=$_REQUEST["id_cat1"];
$frm->addField("","","hidden",$id_cat,$id_cat,"/^[^`]{0,}$/i","id_cat",1);
$frm->addField("","","hidden",$uniq,$uniq,"/^[^`]{0,}$/i","uniq",1);
}
			if (
$this->processFormData($frm,$lang["forms"]["object"]["submit_name"],$first
			)) {
				//добавляем или редактируем
				$sql="";
				$id_ob="";
				$main_obj="";
				$dob="";
				switch ($mode) {
					case "photo":
					$sql="%photos%";
					$id_ob="id_photo";
					$main_obj="main_photo";
					break;
					case "video":
					$sql="%videos%";
					$id_ob="id_video";
					$main_obj="main_video";
$dob=",`company`='".sql_quote($company)."',`prodolzhitelnost`='".sql_quote($prodolzhitelnost)."',`external_url`='".sql_quote($external_url)."'";
					break;
					case "music":
					$sql="%audio%";
					$id_ob="id_audio";
					$main_obj="main_audio";
					$dob=",`genre`='".sql_quote($genre)."',`label`='".sql_quote($label)."',`prodolzhitelnost`='".sql_quote($prodolzhitelnost)."',`external_url`='".sql_quote($external_url)."'";
					break;
					case "flash":
					$sql="%flash%";
					$id_ob="id_flash";
					$main_obj="main_flash";
$dob=",`external_url`='".sql_quote($external_url)."'";
					break;
					default:
				}
				 //редактируем
				 if ($sql!="" && $id_ob!="") {
				 	if ($db->query("update $sql set caption='".sql_quote($title)."', title='".sql_quote($titletag)."',meta='".sql_quote($metatag)."',description='".sql_quote($content)."',`visible`=$visible,id_category=$id_cat,$main_obj=$main $dob where $id_ob=$id_object")) {
						//отредактировали
					   $modAction="edit";
					   $this->setCongratulation('','Данные сохранены',5000);
				   $smarty->assign("saved",true);
				   $smarty->assign("uniq",$uniq);
				   $smarty->assign("id_cat",$id_cat);
					switch ($mode) {
						case "photo":
							$object=$this->getImageByID($id_object);
						break;
						case "video":
							$object=$this->getVideoByID($id_object);
						break;
						case "music":
							$object=$this->getAudioByID($id_object);
						break;
						case "flash":
							$object=$this->getFlashByID($id_object);
						break;
					}
				$smarty->assign("object",$object);
					}
				 }
				
				}
				}
				   $smarty->assign("id_object",$id_object);
				   $smarty->assign("mode",$mode);
		break;
		case "change":
			$photos=@$_REQUEST["photos"];
			$videos=@$_REQUEST["videos"];
			$audio=@$_REQUEST["audio"];
			$flash=@$_REQUEST["flash"];
			$do_visible=@$_REQUEST["do_visible"];
			$do_main=@$_REQUEST["do_main"];
			$do_thumbnails=@$_REQUEST["do_thumbnails"];
			$do_title=@$_REQUEST["do_title"];
			$title_value=@$_REQUEST["title_value"];
			$visible_value=@$_REQUEST["visible_value"];
			$main_value=@$_REQUEST["main_value"];
			$id_cat=@$_REQUEST["id_cat"];
			$obj=array();
			$vis="";
			$main="";
			
					if ($do_visible) {
						if ($visible_value==1) {
							$vis=" ,`visible`=1";
						} else {
							$vis=" ,`visible`=0";
						}
					}
					if ($do_title) {
						$title=" ,`title`='".charset_x_win(sql_quote($title_value))."'";
					} else {
						$title="";
					}

			if (is_array($photos))
			foreach ($photos as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($do_main) {
						if ($main_value==1) {
							$main=" ,`main_photo`=1";
						} else {
							$main=" ,`main_photo`=0";
						}
					}
					if ($db->query("update %photos% set `id_photo`=$key $vis $main $title where id_photo=$key")) {
					$obj[]=$object;
					}
					}
				}
			}
			
			if (is_array($videos))
			foreach ($videos as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($do_main) {
						if ($main_value==1) {
							$main=" ,`main_video`=1";
						} else {
							$main=" ,`main_video`=0";
						}
					}
					if ($db->query("update %videos% set `id_video`=$key $vis $main $title where id_video=$key")) {
					$obj[]=$object;
					}
					}
				}
			}
			
			if (is_array($audio))
			foreach ($audio as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($do_main) {
						if ($main_value==1) {
							$main=" ,`main_audio`=1";
						} else {
							$main=" ,`main_audio`=0";
						}
					}
					if ($db->query("update %audio% set `id_audio`=$key $vis $main $title where id_audio=$key")) {
					$obj[]=$object;
					}
					}
				}
			}
			
			if (is_array($flash))
			foreach ($flash as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($do_main) {
						if ($main_value==1) {
							$main=" ,`main_flash`=1";
						} else {
							$main=" ,`main_flash`=0";
						}
					}
					if ($db->query("update %flash% set `id_flash`=$key $vis $main $title where id_flash=$key")) {
					$obj[]=$object;
					}
					}
				}
			}
			$smarty->assign("objects",$obj);
			$smarty->assign("objects_count",sizeof($obj));
			$smarty->assign("id_cat",$id_cat);
			$smarty->assign("do_visible",$do_visible);
			$smarty->assign("do_main",$do_main);
			$smarty->assign("do_title",$do_title);
			
		break;
		case "move_id_cat":
			$photos=@$_REQUEST["photos"];
			$videos=@$_REQUEST["videos"];
			$audio=@$_REQUEST["audio"];
			$flash=@$_REQUEST["flash"];
			$id_cat=@$_REQUEST["id_cat"];
			$obj=array();
			
			if ($this->rubricExist($id_cat,1)) {

			if (is_array($photos))
			foreach ($photos as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($db->query("update %photos% set id_category=$id_cat where id_photo=$key")) {
					$obj=$object;
					}
					}
				}
			}
			
			if (is_array($videos))
			foreach ($videos as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($db->query("update %videos% set id_category=$id_cat where id_video=$key")) {
					$obj=$object;
					}
					}
				}
			}
			
			if (is_array($audio))
			foreach ($audio as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($db->query("update %audio% set id_category=$id_cat where id_audio=$key")) {
					$obj=$object;
					}
					}
				}
			}
			
			if (is_array($flash))
			foreach ($flash as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($db->query("update %flash% set id_category=$id_cat where id_flash=$key")) {
					$obj=$object;
					}
					}
				}
			}
			$smarty->assign("objects",$obj);
			$smarty->assign("objects_count",sizeof($obj));
			$smarty->assign("id_cat",$id_cat);
			}
			
		break;
		case "geturl":
			$url=@$_REQUEST["url"];
			if (preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$url)) {
				$file_ext=get_content_type($url);
				if ($file_ext==false) {
					$pos=0;
					$file_ext=getFileExt(strtolower($url), $pos);
				}
				$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
				$type=$this->getFileTypeEx($file_ext);
				$caption=$filename;
				switch ($type) {
				case "image":
					$upload_path=$config["pathes"]["user_image"];
				break;
				case "video":
					$upload_path=$config["pathes"]["user_video"];
				break;
				case "flash":
					$upload_path=$config["pathes"]["user_flash"];
				break;
				case "music":
					$upload_path=$config["pathes"]["user_music"];
				break;
				default:
					die('wrong_format');
				}
				while (is_file($upload_path.$filename)==true) {
				$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
				}
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_TIMEOUT, 300);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
				$st = curl_exec($ch);
				curl_close($ch);
				if($st=='')
				{
				    $errors= error_get_last();
				    echo "COPY ERROR: ".$errors['type'];
					echo "<br />\n".$errors['message'];
				    die("error_download");
				} else {
					$hfile=@fopen( $upload_path.$filename, 'w' );
					@fwrite($hfile, $st);
					@fclose($hfile);
					$object_id=$this->insertNewObject($caption,$filename,$type,$file_ext);
					$object=$this->getObjectByID($object_id);
					 if (isset($_REQUEST["id_cat"])) {
					 	$id_cat=$_REQUEST["id_cat"];
					 	if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
							$category=$this->getCategoryByID($id_cat);
								 $id_image=$this->copyObject($object_id,$id_cat,true,$category["preview_width"],$category["preview_height"]);
								 if ($id_image>0) {
									$this->deleteObject($object_id);
									$object["id_image"]=$id_image;
								}
					 	}
					 }
					die(json_encode($object));
				}
			} else {
				die("url_error");
			}
		break;
		case "delete_objects":
			$photos=@$_REQUEST["photos"];
			$videos=@$_REQUEST["videos"];
			$audio=@$_REQUEST["audio"];
			$flash=@$_REQUEST["flash"];
			$modtype=@$_REQUEST["modType"];
			$obj=array();
			
			if (is_array($photos))
			foreach ($photos as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($this->deletePhoto($key)) {
					$obj=$object;
					}
					}
				}
			}
			
			if (is_array($videos))
			foreach ($videos as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($this->deleteVideo($key)) {
					$obj=$object;
					}
					}
				}
			}
			
			if (is_array($audio))
			foreach ($audio as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($this->deleteAudio($key)) {
					$obj=$object;
					}
					}
				}
			}
			
			if (is_array($flash))
			foreach ($flash as $key=>$object) {
				if (preg_match("/^[0-9]{1,}$/i",$key)) {
					if ($object==1) {
					if ($this->deleteFlash($key)) {
					$obj=$object;
					}
					}
				}
			}
			$smarty->assign("modType",$modtype);
			$smarty->assign("objects",$obj);
			$smarty->assign("objects_count",sizeof($obj));
		break;
		case "delete":
			$objects=@$_REQUEST["objects"];
			$modtype=@$_REQUEST["modType"];
				$obj=array();
				foreach ($objects as $key=>$object) {
					if (preg_match("/^[0-9]{1,}$/i",$key)) {
						$ob=$this->getObjectById($key);
//						if (is_file($ob["fullpath"])) {
						if ($object==1) {
						 if ($config["pathes"]["user_thumbnails"].$ob["preview"]) {
						 @unlink($config["pathes"]["user_thumbnails"].$ob["preview"]);
						 }						
						 @unlink($ob["fullpath"]);
						 if ($this->deleteObject($key)) {
							$obj[]=$object;
						 }
						}
	//					}
					}
				}
				$smarty->assign("objects",$obj);
				$smarty->assign("modType",$modtype);
				$smarty->assign("objects_count",sizeof($obj));
				die();
		break;
		case "ftp":
		@set_time_limit(0);
			$files=$this->getFilesByDir($config["pathes"]["user_upload"]);
			if (isset($_REQUEST["go"])) {
				$smarty->assign("go",true);
				if (is_array($files)) {
				$files_new=array();
				foreach ($files as $filename) {
				$pos=0;
				$file_ext=getFileExt($filename, $pos);
				$type=$this->getFileType($filename);
				switch ($type) {
					case "image":
						$upload_path=$config["pathes"]["user_image"];
						$caption=$filename;
					break;
					case "video":
						$upload_path=$config["pathes"]["user_video"];
						$caption=$filename;
					break;
					case "flash":
						$upload_path=$config["pathes"]["user_flash"];
						$caption=$filename;
					break;
					case "music":
						$upload_path=$config["pathes"]["user_music"];
						$caption=$filename;
					break;
				}
				if (!preg_match("/^[a-zA-Z0-9-_]{1,}$/i",$filename)) {
					$fname=rand(0,10000).rand(0,10000).rand(0,10000).rand(0,10000).rand(0,10000).rand(0,10000).'.'.$file_ext;
				} else {
					$fname=$filename;
				}
				if (!is_file($upload_path.$fname)) {
				if (@copy($config["pathes"]["user_upload"].$filename,$upload_path.$fname)) {
				$this->insertNewObject($caption,$fname,$type,$file_ext);
				unlink($config["pathes"]["user_upload"].$filename);
				$files_new[]=$filename;
				}
				} else {
					$fname=rand(0,10000)."_".$fname;
					while (is_file($upload_path.$fname)) {
						$fname=rand(0,10000)."_".$filename;
					}
				if (@copy($config["pathes"]["user_upload"].$filename,$upload_path.$fname)) {
				$this->insertNewObject($caption,$fname,$type,$file_ext);
				unlink($config["pathes"]["user_upload"].$filename);
				$files_new[]=$fname;
				}
				}
				}
				}
				if (isset($files_new)) {
				$smarty->assign("files",$files_new);
				$smarty->assign("files_count",sizeof($files_new));
				}
				$this->setCongratulation("Загрузка с фтп","Файлы, загруженные на фтп скопированы в галерею",5000);
				$modAction="seenew";
			} else {
				$smarty->assign("go",false);
				$smarty->assign("files",$files);
			}
		break;
		case "edit":

		break;
		case "viewvideo":
			$this->clearPath();
			$id_video=@$_REQUEST["id_video"];
			if (preg_match("/^[0-9]{1,}$/i",$id_video)) {
				$smarty->assign("swf_object",true);
				$video=$this->getVideoByID($id_video);
				$smarty->assign("video",$video);
			}
		break;
		case "viewaudio":
			$this->clearPath();
			$id_audio=@$_REQUEST["id_audio"];
			if (preg_match("/^[0-9]{1,}$/i",$id_audio)) {
				$smarty->assign("swf_object",true);
				$audio=$this->getAudioByID($id_audio);
				$smarty->assign("audio",$audio);
			}
		break;
		case "viewflash":
			$this->clearPath();
			$id_flash=@$_REQUEST["id_flash"];
			if (preg_match("/^[0-9]{1,}$/i",$id_flash)) {
				$smarty->assign("swf_object",true);
				$flash=$this->getFlashByID($id_flash);
				$smarty->assign("flash",$flash);
			}
		break;
		case "seenew":

		break;
		case "move":
			$objects=@$_REQUEST["objects"];
			$id_cat=@$_REQUEST["id_cat"];
			$modtype=@$_REQUEST["modType"];
			if (isset($_REQUEST["create_thumbnails"])) {
				$create_thumb=true;
			} else {
				$create_thumb=false;
			}
			if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
				$category=$this->getCategoryByID($id_cat);
				$obj=array();
				if (is_array($objects))
				foreach ($objects as $key=>$object) {
					if (preg_match("/^[0-9]{1,}$/i",$key)) {
						if ($object==1) {
						 if ($this->copyObject($key,$id_cat,$create_thumb,$category["preview_width"],$category["preview_height"])) {
							$obj[]=$object;
							if (isset($_REQUEST["delete_files"])) {
								$this->deleteObject($key);
							}
						 }
						}
					}
				}
				$smarty->assign("category",$category);
				$smarty->assign("objects",$obj);
				$smarty->assign("modType",$modtype);
				$smarty->assign("objects_count",sizeof($obj));
				$smarty->assign("create_thumbnails",$create_thumb);
			}
			die();
		break;
		case "upload":
			//показываем форму загрузки файлов
			$secretkey=md5($config["secretkey"]."scripto_gallery".$settings["login"]);
			$smarty->assign("secretkey",$secretkey);
			$smarty->assign("doUpload",true);
		break;
		default:
			$modAction="seenew";
	}
	if ($modAction=="seenew") {
		//показать несортированное
		$modType=@$_REQUEST["modType"];
		 if (!in_array($modType,$config["types"])) {
		 	$modType="";
		 }
		$objects=$this->getAllObjects($modType);
		$categories=$this->getRubricsTree(0,0,false,'',$modType,"sort",true);
		$smarty->assign("categories",$categories);
		$smarty->assign("objects",$objects);
		//показываем форму загрузки файлов
		$secretkey=md5($config["secretkey"]."scripto_gallery".$settings["login"]);
		$smarty->assign("secretkey",$secretkey);
		$smarty->assign("doUpload",true);
		$smarty->Assign("modType",$modType);
	}
	if ($modAction=="edit") {
			if (isset($_REQUEST["id_cat"])) {
				$id_cat=@$_REQUEST["id_cat"];
				if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
					$this_cat=$this->getCategoryByID($id_cat);
					$smarty->assign("this_cat",$this_cat);
					if ($this_cat["main_page"]) {
					$objects=$this->getObjectsByCat($id_cat,0,"",true);
					} else {
					$objects=$this->getObjectsByCat($id_cat);
					}
					$smarty->assign("objects",$objects);
					$path=$this->getPath($id_cat);
					foreach ($path as $pth) {
						$config["path"][]=$pth["id_category"];
					}
					$this->config=$config;
				}
			} else {
				$rubric=$this->getMainPage();
				if (is_array($rubric)) {
					$smarty->assign("this_cat",$rubric);
					$objects=$this->getObjectsByCat($rubric["id_category"],0,"sort",true);
					$smarty->assign("objects",$objects);
				}
			}
			$modType=@$_REQUEST["modType"];
			 if (!in_array($modType,$config["types"])) {
			 	$modType="";
			 }
			$categories=$this->getRubricsTree(0,0,false,'',$modType,"",true);
			$smarty->assign("categories",$categories);
			$smarty->assign("modType",$modType);
	}
}
$smarty->assign("page",$page);
?>
