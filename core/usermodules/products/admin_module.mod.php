<?
/*
Модуль новости сайта, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
$m_action=@$_REQUEST["m_action"];
switch ($m_action) {
	/*crosssale*/
	case "recommended":
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Настройки рекомендуемых товаров','',false);
		$types=$this->getProductTypes();
		$smarty->assign("types",$types);
	break;
	/*end of crosssale*/
	case "action_products":
		$engine->clearPath();
		$id_action=@$_REQUEST["id_action"];
		if (preg_match("/^[0-9]{1,}$/i",$id_action)) {
			$engine->clearCacheBlocks($this->thismodule["name"]);
			$action=$this->getActionByID($id_action);
			$smarty->assign("action",$action);
			if (isset($_REQUEST["del"])) {
				$del=@$_REQUEST["del"];
				if (is_array($del)) {
					foreach ($del as $id_product=>$d) {
						$db->query("delete from `%ACTION_PRODUCTS%` where `id_product`=$id_product and `id_action`=$id_action");
					}
					$smarty->assign("delete",true);
				}
			}
			$products=$this->getProductsByAction($id_action);
			$smarty->assign("products",$products);
		}
	break;
	case "collections":
		$engine->clearPath();
		$id_firm=@$_REQUEST["id_firm"];
		if (preg_match("/^[0-9]{1,}$/i",$id_firm)) {
			$firm=$this->getFirmByID($id_firm);
			$smarty->assign("firm",$firm);
			if (isset($_REQUEST["add"])) {
				$caption=trim(strip_tags(@$_REQUEST["caption"]));
				if (!$this->existCollection($caption)) {
					if ($this->addCollection($caption,$id_firm)) {
						$smarty->assign("added",true);
					}
				} else {
					$smarty->assign("exist_collection",true);
					$smarty->assign("caption",$caption);
				}
			}
			if (isset($_REQUEST["save"])) {
				$caption=@$_REQUEST["caption"];
				$del=@$_REQUEST["del"];
				$d=0;
				$u=0;
				if (is_array($caption)) {
					foreach ($caption as $id_collection=>$capt) {
						if (isset($del[$id_collection])) {
							if ($db->query("delete from `%COLLECTIONS%` where id_collection=$id_collection"))
								$d++;
						} else {
							if ($db->query("update `%COLLECTIONS%` set `caption`='".sql_quote($capt)."' where `id_collection`=$id_collection"))
								$u++;
						}
					}
					$smarty->assign("saved",true);
					$smarty->assign("mess","Удалено $d коллекций, обновлено $u коллекций");
				}
			}
			$collections=$this->getAllCollections($id_firm);
			$smarty->assign("collections",$collections);
		}
	break;
	case "edit_type":
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Типы товаров','/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"].'&m_action=types',true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_type"])) {
				$id_type=@$_REQUEST["id_type"];
				$type=$this->getTypeByID($id_type);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=trim(@$_REQUEST["caption"]);
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($type) {
						$caption=$type["caption"];
						$lang_values=$engine->generateLangArray("PRODUCT_TYPES",$type);
					}
				} else {
					$caption="";
					$lang_values=$engine->generateLangArray("PRODUCT_TYPES",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("Название типа","Неверно заполнено название типа","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Ноутбуки",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$engine->generateLangControls("PRODUCT_TYPES",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_type"])) {
$id_type=$_REQUEST["id_type"];
$frm->addField("","","hidden",$id_type,$id_type,"/^[^`]{0,}$/i","id_type",1);
}

if ($mode=="edit") {
$engine->addPath('Редактирование типа '.$type["caption"],'',false);
} else {
$engine->addPath('Добавление типа ','',false);
}
$engine->assignPath();
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_type)) {
				 	if ($db->query("update %PRODUCT_TYPES% set `caption`='".sql_quote($caption)."'  ".$engine->generateUpdateSQL("PRODUCT_TYPES",$lang_values)." where `id_type`=$id_type")) {
						//отредактировали
				//	   $modAction="view";
				   $engine->setCongratulation("","Тип отредактирован успешно!",3000);
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				   $m_action="types";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $add_id=$this->addType($caption,$engine->generateInsertSQL("PRODUCT_TYPES",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				    $engine->setCongratulation("","Тип добавлен успешно!",3000);
				    $engine->clearCacheBlocks($this->thismodule["name"]);
				    $m_action="types";
				 }
				}
			}
		$engine->assignPath();
	break;
	case "add_action":
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Акции и спецпредложения','/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"].'&m_action=actions',true);
		$values=array();
		$engine->getRubricsTreeEx($values,0,0,true,"",false);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_action"])) {
				$id_action=@$_REQUEST["id_action"];
				$action=$this->getActionByID($id_action);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=trim(@$_REQUEST["caption"]);
				$content=@$_REQUEST["fck1"];
				$id_category=@$_REQUEST["id_category"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($action) {
						$caption=$action["caption"];
						$content=$action["content"];
						$id_category=$action["id_category"];
						$lang_values=$engine->generateLangArray("ACTIONS",$action);
					}
				} else {
					$caption="";
					$content="";
					$id_category=@$values[0]["id"];
					$lang_values=$engine->generateLangArray("ACTIONS",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
$frm->addField($lang["forms"]["catalog"]["razdel"]["caption"],$lang["forms"]["catalog"]["razdel"]["error"],"list",$values,$id_category,"/^[0-9]{1,}$/i","id_category",1,$lang["forms"]["catalog"]["razdel"]["sample"],array('size'=>'30'));

$frm->addField("Название акции","Неверно заполнено название акции","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Apple",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$fck_editor1=$engine->createFCKEditor("fck1",$content);
$frm->addField("Описание акции","Неверно заполнено описание акции","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$engine->generateLangControls("ACTIONS",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_action"])) {
$id_action=$_REQUEST["id_action"];
$frm->addField("","","hidden",$id_action,$id_action,"/^[^`]{0,}$/i","id_action",1);
}

if ($mode=="edit") {
$engine->addPath('Редактирование акции '.$action["caption"],'',false);
if ($id_category!=$action["id_category"])
	if ($this->existCategoryAction($id_category))
		$frm->addError('В выбранном разделе уже выводится акция');
} else {
if ($this->existCategoryAction($id_category))
	$frm->addError('В выбранном разделе уже выводится акция');
$engine->addPath('Добавление акции','',false);
}
$engine->assignPath();
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				 $cat=$engine->getCategoryByID($id_category);
				 $val=$cat["additional"];
				 $val["actions_page"]=true;
				 $engine->setCategoryAdditional($id_category,$val);
				 $engine->addModuleToCategory($this->thismodule["name"],$id_category);
				if ($mode=="edit") {
				 //редактируем
				 
				 if ($id_category!=$action["id_category"]) {
					 $cat=$engine->getCategoryByID($action["id_category"]);
					 if (isset($cat["additional"]["actions_page"])) {
					 	 unset($val);
						 $val=$cat["additional"];
						 unset($val["actions_page"]);
						 $engine->setCategoryAdditional($action["id_category"],$val);
					 }
				 }
				 if (isset($id_action)) {
				 	if ($db->query("update `%ACTIONS%` set `id_category`=$id_category,`caption`='".sql_quote($caption)."' , `content`='".sql_quote($content)."' ".$engine->generateUpdateSQL("ACTIONS",$lang_values)." where `id_action`=$id_action")) {
						//отредактировали
				//	   $modAction="view";
				   $engine->setCongratulation("","Акция отредактирована успешно!",3000);
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				   $m_action="actions";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $add_id=$this->addAction($id_category,$caption,$content,$engine->generateInsertSQL("ACTIONS",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $engine->setCongratulation("","Акция добавлена успешно!",3000);
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				   $m_action="actions";
				 }
				}
			}
		$engine->assignPath();
	break;
	case "add_firm":
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Фирмы и коллекции','/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"].'&m_action=firms',true);
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_firm"])) {
				$id_firm=@$_REQUEST["id_firm"];
				$firm=$this->getFirmByID($id_firm);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=trim(@$_REQUEST["caption"]);
				$content=@$_REQUEST["fck1"];
				$lang_values=@$_REQUEST["lang_values"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($firm) {
						$caption=$firm["caption"];
						$content=$firm["description"];
						$lang_values=$engine->generateLangArray("FIRMS",$firm);
					}
				} else {
					$caption="";
					$content="";
					$lang_values=$engine->generateLangArray("FIRMS",null);
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
$frm->addField("Название фирмы","Неверно заполнено название фирмы","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Apple",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$fck_editor1=$engine->createFCKEditor("fck1",$content);
$frm->addField("Описание фирмы","Неверно заполнено описание фирмы","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$engine->generateLangControls("FIRMS",$lang_values,$frm);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_firm"])) {
$id_firm=$_REQUEST["id_firm"];
$frm->addField("","","hidden",$id_firm,$id_firm,"/^[^`]{0,}$/i","id_firm",1);
}

if ($mode=="edit") {
$engine->addPath('Редактирование фирмы '.$firm["caption"],'',false);
	if ($firm["caption"]!=$caption)
		if ($this->existFirm($caption))
			$frm->addError("Фирма $caption уже существует");
} else {
$engine->addPath('Добавление фирмы','',false);
		if ($this->existFirm($caption))
			$frm->addError("Фирма $caption уже существует");
}
$engine->assignPath();
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_firm)) {
				 	if ($db->query("update %FIRMS% set `caption`='".sql_quote($caption)."' , `description`='".sql_quote($content)."' ".$engine->generateUpdateSQL("FIRMS",$lang_values)." where `id_firm`=$id_firm")) {
						//отредактировали
				//	   $modAction="view";
				   $engine->setCongratulation("","Фирма отредактирована успешно!",3000);
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				   $m_action="firms";
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
				 $add_id=$this->addFirm($caption,$content,$engine->generateInsertSQL("FIRMS",$lang_values));
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $engine->setCongratulation("","Фирма добавлена успешно!",3000);
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				   $m_action="firms";
				 }
				}
			}
		$engine->assignPath();
	break;
	case "pricing":
		if (isset($_REQUEST["do_pricing"])) {
			//наценка в действии
			$mode=@$_REQUEST["mode"];
			$err=false;
			$err_text='';
			$filter=array();
			$price_value=@$_REQUEST["price_value"];
			if (!preg_match('/^[0-9]{1,}$/i',$price_value)) {
				$err_text='Величина наценки должна быть числом';
				$err=true;
			} else {
				$price_mode=@$_REQUEST["price_mode"];
				$math_mode=@$_REQUEST["math_mode"];
				if ($price_mode!=0) $price_mode=1;
				if ($math_mode!=0) $math_mode=1;
			}
			$product_type=@$_REQUEST["product_type"];
			switch  ($product_type) {
				case 0:
					
				break;
				case 1:
					$firm=@$_REQUEST["firm"];
					$item=$this->parseCollection($firm);
					if ($item[0]>0) {
						$filter[]="`id_firm`=".$item[0];
					}
					if ($item[1]>0) {
						$filter[]="`id_collection`=".$item[1];
					}
				break;
				case 2:
					$id_type=@$_REQUEST["id_type"];
					if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
						$filter[]="`id_type`=".$id_type;
					}
				break;
				case 3:
					$id_cat=@$_REQUEST["id_cat"];
					if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
						$rubrics=$this->getCountAllProducts();
						if (isset($_REQUEST["do_subcategories"])) {
							$sql_dop="(";
							$this->generateSQLDop($id_cat,$sql_dop,true);
							$sql_dop.=")";
							$filter[]=$sql_dop;
						} else {
							$filter[]="`id_category`=".$id_cat;
						}
					}
				break;
				default:
					$err=true;
					$err_text='Неверно задан тип экспорта';
			}
			//Определяем объекты наценки
			if ($err==false) {
			//Устанавливаем наценки
			//генерируем выборку mysql
			$where=$this->generatePricingSQL($filter);
			if ($mode>=0 && $mode<3) {
				if (isset($_REQUEST["old_price_set"])) {
					$db->query("update `%PRODUCTS%` set `price2`=`price1` $where");
				}
				$filter[]="`price_default`>0";
				$price1='`price1`=`price1`';
				if (isset($_REQUEST["current_price"])) {
					$def_name="price1";
				} else {
					$def_name="price_default";
				}
				switch ($price_mode) {
					case 0:
						if ($math_mode==0) {
							$price1="`price1`=`$def_name` + (`$def_name`*".str_Replace(',','.',($price_value/100)).")";
							$price2="`price_default`=`$def_name` + (`$def_name`*".str_Replace(',','.',($price_value/100)).")";
						} else {
							$price1="`price1`=`$def_name` - (`$def_name`*".str_Replace(',','.',($price_value/100)).")";
							$price2="`price_default`=`$def_name` - (`$def_name`*".str_Replace(',','.',($price_value/100)).")";
						}
					break;
					case 1:
						if ($math_mode==0) {
							$price1="`price1`=`$def_name` + $price_value";
							$price2="`price_default`=`$def_name` + $price_value";
						} else {
							$price1="`price1`=`$def_name` - $price_value";
							$price2="`price_default`=`$def_name` - $price_value";
						}
					break;
				}
				$engine->clearCacheBlocks($this->thismodule["name"]);
			}
			$where=$this->generatePricingSQL($filter);
			switch ($mode) {
				case 0:
					$db->query("update `%PRODUCTS%` set $price1 $where");
					$engine->setCongratulation('','Розничные цены изменены',3000);
				break;
				case 1:
					$db->query("update `%PRODUCTS%` set $price2 $where");
					$engine->setCongratulation('','Закупочные цены изменены',3000);
				break;
				case 2:
					$db->query("update `%PRODUCTS%` set $price2 $where");
					$db->query("update `%PRODUCTS%` set $price1 $where");
					$engine->setCongratulation('','Розничные и закупочные цены изменены',3000);
				break;
				case 3:
					//Возвращаем старые цены
					$filter[]="`price2`>0";
					$where=$this->generatePricingSQL($filter);
					$db->query("update `%PRODUCTS%` set `price1`=`price2` $where");
					$db->query("update `%PRODUCTS%` set `price2`=0 $where");
					$engine->setCongratulation('','Розничные цены изменены',3000);
				break;
				case 4:
					$db->query("update `%PRODUCTS%` set `price2`=0 $where");
					$engine->setCongratulation('','Старые цены обнулены',3000);
				break;
			}
			} else {
				$engine->setCongratulation('Ошибка',$err_text,5000);
			}
		}
		$firms=$this->getFirmsAndCollections();
		$smarty->assign("firms",$firms);
		$values=array();
		$engine->getRubricsTreeEx($values,0,0,true,"",false);
		$types=$this->getTypesList();
		$smarty->assign("rubrs",$values);
		$smarty->assign("types",$types);
	break;
	case "firms":
	
	break;
	case "set_options":
		$engine->clearPath();
		$id_product=@$_REQUEST["id_product"];
		if (preg_match('/^[0-9]{1,}$/i',$id_product)) {
			$product=$this->getProductByID($id_product);
			$smarty->assign("product",$product);
			$options=$this->getAllOptions($product["id_type"]);
			$smarty->assign("options",$options);
			if (isset($options[0])) {
			if (isset($_REQUEST["save"])) {
				$first=false;
				$values=@$_REQUEST["values"];
			} else {
				$first=true;
				if (isset($product["values"]["id_type"])) {
					if ($product["values"]["id_type"]==$product["id_type"]) {
						$use_mode=1;
					} else {
						$use_mode=0;
					}
				} else {
					$use_mode=0;
				}
				if ($use_mode==0) {
					foreach ($options[0] as $id_option=>$option) {
						$values[$id_option]='';
					}
				} else {
					foreach ($product["values"]["options"] as $id_option=>$option) {
						$values[$id_option]=$option["value"];
					}
				}
			}
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
			
			foreach ($options[0] as $id_option=>$option) {
			if (!isset($values[$id_option])) $values[$id_option]='';
			if (!isset($option["values_list"])) {
			$frm->addField($option["caption"],"Неверно заполнено поле ".$option["caption"],"text",$values[$id_option],$values[$id_option],"/^[^`#]{1,255}$/i","values[$id_option]",1,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));
			} else {
			if (is_array($option["values_list"])) {
			$vals=array();
			foreach ($option["values_list"] as $key=>$value) {
				$vals[$key]["id"]=trim($value);
				$vals[$key]["name"]=trim($value);
			}
			$frm->addField($option["caption"],'Неверно выбрано поле  '.$option["caption"],"list",$vals,$values[$id_option],"/^[^`#]{1,255}$/i","values[$id_option]",1,'',array('size'=>'30'));
			unset($vals);
			} else {
			$frm->addField($option["caption"],"Неверно заполнено поле ".$option["caption"],"text",$values[$id_option],$values[$id_option],"/^[^`#]{2,255}$/i","values[$id_option]",1,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));
			}
			}
			}
			
		$frm->addField("","","hidden",$id_product,$id_product,"/^[0-9]{1,}$/i","id_product",1);
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//Генерация массива
				$product_massive["id_type"]=$product["id_type"];
				foreach ($options[0] as $id_option=>$option) {
					$v["caption"]=base64_encode($option["caption"]);
					$v["value"]=base64_encode($values[$id_option]);
					$product_massive["options"][$id_option]=$v;
				}
				$product_massive_str=serialize($product_massive);
				if ($db->query("update `%PRODUCTS%` set `options_info`='".$product_massive_str."' where `id_product`=$id_product")) {
					$smarty->assign("saved",true);
					$smarty->assign("closeFancybox",true);
				}
			}
			}
		}
	break;
	case "options":
		$engine->clearPath();
		if (isset($_REQUEST["id_type"])) {
			$id_type=@$_REQUEST["id_type"];
			if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
				$type=$this->getTypeByID($id_type);
				$smarty->assign("type",$type);
				if (isset($_REQUEST["add"])) {
					$caption=@$_REQUEST["caption"];
					$value=@$_REQUEST["value"];
					if (isset($_REQUEST["in_order"])) {
						$in_order=1;
					} else {
						$in_order=0;
					}
					if ($this->addOption($id_type,$caption,$value,$in_order)) {
						$smarty->assign("added",true);
					}
				}
				if (isset($_REQUEST["save"])) {
					$caption=@$_REQUEST["caption"];
					$value=@$_REQUEST["value"];
					$inorder=@$_REQUEST["inorder"];
					$del=@$_REQUEST["del"];
					if (is_array($caption)) {
						$u=0;
						$d=0;
						foreach ($caption as $id_option=>$capt) {
							if (isset($del[$id_option])) {
								$db->query("delete from `%PRODUCT_OPTIONS%` where id_option=$id_option");
								$d++;
							} else {
								if (isset($value[$id_option])) {
								if (isset($inorder[$id_option])) {
									$in_order=1;
								} else {
									$in_order=0;
								}
								$db->query("update `%PRODUCT_OPTIONS%` set `caption`='".sql_quote($capt)."', `values`='".sql_quote($value[$id_option])."',`show_in_order`=$in_order where id_option=$id_option");
								$u++;
								}
							}
						}
						$smarty->assign("saved",true);
						$smarty->assign("mess","Обновлено $u характеристик, удалено $d характеристик.");
					}
				}
			}
			$options=$this->getOptions($id_type);
			$smarty->assign("options",$options);
		}
	break;
	case "add_type":
		//добавить новый тип товара
		if (isset($_REQUEST["caption"])) {
			$caption=UTF8(@$_REQUEST["caption"]);
			$lang_values=array();
			if ($this->addType($caption,$engine->generateInsertSQL("PRODUCT_TYPES",$lang_values))) {
				$type=$this->getTypeByID(mysql_insert_id());
				$smarty->assign("type",$type);
				$type["caption"]=ToUTF8($type["caption"]);
				$engine->clearCacheBlocks($this->thismodule["name"]);
				die(json_encode($type));
			} else {
				die("ERROR");
			}
		} else {
			die("ERROR");
		}
	break;
	case "types":
		
	break;
	case "gallery":
		if (isset($_REQUEST["id_product"])) {
			$id_product=$_REQUEST["id_product"];
			if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
				if (isset($_REQUEST["sort_me"])) {
					//сортировка
					$del=@$_REQUEST["del"];
					$sort=@$_REQUEST["sort"];
					if (is_array($sort)) {
					$d=0;
					$u=0;
						foreach ($sort as $id_image=>$value) {
							if (isset($del[$id_image])) {
								$db->query("delete from `%PRODUCT_PICTURES%` where id_product=$id_product and id_image=$id_image");
								$d++;
							} else {
								if (preg_match("/^[0-9]{1,}$/i",$sort[$id_image])) {
									$db->query("update `%PRODUCT_PICTURES%` set `sort`=".$sort[$id_image]." where id_product=$id_product and id_image=$id_image");
									$u++;
								}
							}
						}
						$engine->setCongratulation("","Изменения сохранены (Удалено $d изображений , обновлено $u изображений)");
					}
				}
				$product=$this->getProductByID($id_product);
				$smarty->assign("product",$product);
				$engine->clearPath();
				$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
				$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&id_category='.$product["id_category"].'&module_name='.$this->thismodule["name"],true);
				$engine->addPath('Просмотр галереи изображений товара '.$product["caption"],'',false);
				$engine->assignPath();
				if (isset($_REQUEST["setPreview"])) {
					$previewMode=@$_REQUEST["previewMode"];
					$id_image=@$_REQUEST["id_image"];
					if (is_array($id_image)) {
						$imgs=array();
						foreach ($id_image as $key=>$id_img) {
							if (preg_match("/^[0-9]{1,}$/i",$id_img)) {
								$image=$engine->getImageByID($id_img);
								$imgs[$key]["img_src"]=$image["small_photo"];
								$imgs[$key]["id_image"]=$id_img;
								$smarty->assign("id_category",$product["id_category"]);
								switch ($previewMode) {
								case "new":
								if (isset($image["caption"])) {
									if ($this->addImageToProduct($id_img,$id_product)) {
						$smarty->assign("fancyTooltip","Изображение добавлено успешно");
									} else {
						$smarty->assign("fancyTooltip","В процессе добавления изображения произошла ошибка");
									}
								} else {
						$smarty->assign("fancyTooltip","Изображение не добавлено, т.к. Вы его не выбрали");
								}
								$smarty->assign("addObjects",true);
								$smarty->assign("closeFancybox",true);
								break;
								}
							}
						}
						$smarty->assign("imgs",$imgs);
					} else {
					if (preg_match("/^[0-9]{1,}$/i",$id_image)) {
						if ($id_image>0) {
						$image=$engine->getImageByID($id_image);
						$smarty->assign("img_src",$image["small_photo"]);
						$smarty->assign("id_image",$id_image);
						$smarty->assign("id_category",$product["id_category"]);
						}
						switch ($previewMode) {
							case "new":
								if (isset($image["caption"])) {
									if ($this->addImageToProduct($id_image,$id_product)) {
						$smarty->assign("fancyTooltip","Изображение добавлено успешно");
									} else {
						$smarty->assign("fancyTooltip","В процессе добавления изображения произошла ошибка");
									}
								} else {
						$smarty->assign("fancyTooltip","Изображение не добавлено, т.к. Вы его не выбрали");
								}
								$smarty->assign("addObject",true);
								$smarty->assign("closeFancybox",true);
							break;
							default:
								if (preg_match("/^[0-9]{1,}$/i",$previewMode)) {
									if (isset($image["caption"])) {
										$db->query("update `%PRODUCT_PICTURES%` set id_image=".$image["id_photo"].",`small_filename`='".$image["small_photo"]."',`medium_filename`='".$image["medium_photo"]."',`big_filename`='".$image["big_photo"]."' where id_product=$id_product and id_image=$previewMode");
										$engine->setPreview($image["small_photo"],$previewMode,'Изображение изменено успешно');
									} else {
										$db->query("delete from `%PRODUCT_PICTURES%` where id_product=$id_product and id_image=$previewMode");
										$smarty->assign("fancyTooltip","Изображение удалено успешно");
										$smarty->assign("id_image",$previewMode);
										$smarty->assign("deleteObject",true);
									}
								}
								$smarty->assign("closeFancybox",true);
						}
					}
				}
				}
			}
		}
	break;
	case "actions":
		//акции
		
	break;
	case "upload_csv":
		ini_set("upload_max_filesize", "100M");
		$filename=charset_x_win(strtolower($_FILES['csv_file']['name']));
		$pos=0;
		$file_ext=getFileExt($filename, $pos);
		if ($file_ext!='csv') {
			die('wrong_format');
		} else {
			if (is_file($config["pathes"]["user_files"]."products.csv")) {
				@unlink($config["pathes"]["user_files"]."products.csv");
			}
			if (@copy($_FILES['csv_file']['tmp_name'],$config["pathes"]["user_files"]."products.csv")) {
				die('ok');
			} else {
				die("not_copy");
			}
		}
		die('');
	break;
	case "read_csv":
		if (is_file($config["pathes"]["user_files"]."products.csv")) {
			$id_cat=@$_REQUEST["id_cat"];
			$csv=@$_REQUEST["csv"];
			$sheet=@$_REQUEST["sheet"];
			if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
				if (is_array($csv) && is_array($sheet)) {
					//Создаем рубрики
					$row = 1;
					if (($handle = fopen($config["pathes"]["user_files"]."products.csv", "r")) !== FALSE) {
		$rubrs=$engine->getRubricsTree(0);
		$products=$this->getAllProducts("code");
		$date_news=array();
		$date_news[0]=(int)date("d");
		$date_news[1]=(int)date("m");
		$date_news[2]=(int)date("Y");
					    while (($data = fgetcsv($handle, 2000,chr(13))) !== FALSE) {
						//определение переменных для csv
						$code='';
						$caption='';
						$product_caption='';
						$price1=0;
						$price2=0;
						$price_default=0;
						$short_content='';
						$full_content='';
						$img_url='';
						$sklad=0;
						$link='';
						$lang_values=array();
				        $row++;
						if (isset($data[0])) {
							$item=explode(';',$data[0]);
							foreach ($sheet as $key_sheet=>$sh) {
							if (isset($csv[$key_sheet]) && isset($item[$sh])) {
							$item[$sh]=trim($item[$sh]);
								switch ($csv[$key_sheet]) {
									case 'csv_code':
										$code=str_replace('0000000','',strip_tags(trim($item[$sh])));
										$code=str_replace('000000','',$code);
										$code=str_replace('00000','',$code);
										$code=str_replace('0000','',$code);
									break;
									case 'csv_caption':
										$caption=charset_x_win(strip_tags(str_replace(',','.',$item[$sh])));
									break;
									case 'csv_product':
										$product_caption=charset_x_win(strip_tags($item[$sh]));
									break;
									case 'csv_proizv':
										$proizv=charset_x_win(trim($item[$sh]));
									break;
									case 'csv_price1':
										$item[$sh]=str_replace(' ','',$item[$sh]);
										if (is_numeric($item[$sh]) || is_float($item[$sh])) 
											$price1=$item[$sh];
									break;
									case 'csv_price2':
										$item[$sh]=str_replace(' ','',$item[$sh]);
										if (is_numeric($item[$sh]) || is_float($item[$sh]))
											$price2=$item[$sh];
									break;
									case 'csv_price_default':
										$item[$sh]=str_replace(' ','',$item[$sh]);
										if (is_numeric($item[$sh]) || is_float($item[$sh])) 
											$price_default=$item[$sh];
									break;
									case 'csv_description':
										$short_content=charset_x_win($item[$sh]);
									break;
									case 'csv_fulldescription':
										$full_content=charset_x_win($item[$sh]);
									break;
									case 'csv_count':
										$sklad=$item[$sh];
									break;
									case 'csv_link':
										if (preg_match("/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i",$item[$sh]))
										$link=$item[$sh];
									break;
								}
							}
							}
						}
						//Данные получены, проверяем рубрика это или товар
						$level[0]=$id_cat;
						if (substr($caption,0,1)=='!') {
							//рубрика
							//получаем уровень рубрики
							$lev=$this->getCSVLevel($caption);
							if ($lev>0) {
								$parent=$lev-1;
							} else {
								$parent=0;
								$lev=0;
							}
							//проверяем существование нужной категории
							if (isset($level[$parent])) {
								//ищем родителя
								$found=false;
								foreach ($rubrs as $id_pos=>$rubr) {
										if ($rubr["id_category"]==$level[$parent]) {
								$ident=$rubr["ident"].'/'.$engine->translitThis($caption);
											$n_rubric=$rubr;
										}
										if ($rubr["id_sub_category"]==$level[$parent])
											if ($rubr["caption"]==$caption) {
												$found=true;
												$n_rubric=$rubr;
												break;
											}
								}
								if (!$found) {
								if (preg_match("/^[0-9]{1,}$/i",$level[$parent])) {
									if ($engine->addCategory(
									$level[$parent],$caption,$n_rubric["category_type"],$ident,1,0,'','','','',$full_content,$short_content,$n_rubric["id_tpl"],$n_rubric["position"],$date_news
									)) {
										$level[$lev]=mysql_insert_id();
$engine->addModuleToCategory($this->thismodule["name"],$level[$lev]);
										$n_rubric["caption"]=$caption;
										$n_rubric["ident"]=$ident;
										$n_rubric["id_category"]=$level[$lev];
	$rubrs[$level[$lev]]=$n_rubric;
									}
								} else {
									die("error");
								}
								} else {
								//найден раздел
								$level[$lev]=$n_rubric["id_category"];
								}
							}
						} else {
							//товар
							//Обновляются только те товары, которые с кодом.
							$do_update=false;
							if (!empty($code)) {
								if (isset($products[$code])) {
									$do_update=true;
								} else {
									$do_update=false;
								}
							} else {
								$do_update=false;
							}
							if ($do_update) {
								//обновление
								if (isset($lev))
								if (trim($code)!='')
								$db->query("update `%PRODUCTS%` set caption='".sql_quote($product_caption)."',`price1`=$price1, `price2`=$price2,`price_default`=$price_default, `kolvo`=$sklad , `id_category`=".$level[$lev].",`url`='".sql_quote($link)."' where id_product=".$products[$code]["id_product"]);
							} else {
								if (isset($lev))
								if (trim($code)!='') {
								$this->addProduct(
$date_news,$product_caption,$short_content,$full_content,$link,0,0,$level[$lev],0,$price1,$price2,$price_default,$sklad,1,$code,$engine->generateInsertSQL("PRODUCTS",$lang_values)
								);
								}
							}
						}
						}
					   $engine->clearCacheBlocks($this->thismodule["name"]);
					   //Все ок
						die("ok");
				    } else {
						die("error");
					}
					fclose($handle);
				} else {
					die("error");
				}
			} else {
				die("error");
			}
		} else {
			die("not_file");
		}
	break;
	case "csv":
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
	$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Загрузка товаров из CSV','',false);
		$engine->assignPath();
		$engine->addJS("/core/usermodules/products/csv.js");
		$engine->assignJS();
		$smarty->assign("doFiles",true);
		$values=array();
		$engine->getRubricsTreeEx($values,0,0,true,"",false);
		$smarty->assign("rubrs",$values);
		$smarty->assign("csv",$this->thismodule["csv"]);
		$sheets=array();
			for ($x=0;$x<=20;$x++)
				$sheets[$x]="Ячейка".($x+1);
		$smarty->assign("sheets",$sheets);
	break;
	case "product_products":
	$id_product=@$_REQUEST["id_product"];
	if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
	$product=$this->getProductByID($id_product);
	$smarty->assign("product",$product);
				if (isset($_REQUEST["add_product"])) {
					$id_product2=@$_REQUEST["id_product2"];
					$this->addProductToProduct($id_product,$id_product2);
				}
				if (isset($_REQUEST["delete_product"])) {
					$id_product2=@$_REQUEST["id_product2"];
					$this->deleteProductFromProduct($id_product,$id_product2);
				}
				$me_products=$this->getProductsByProduct($id_product,false,0);
				$smarty->assign("me_products",$me_products);
				$products=$this->getAllProducts();
				$smarty->assign("products",$products);
	}
	break;
	case "quickadd":
		$id_category=@$_REQUEST["id_category"];
		$lang_values=array();
		if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
			$number=@$_REQUEST["number"];
			if ($number>0 && $number<=99) {
				$date_news=array();
				$date_news[0]=(int)date("d");
				$date_news[1]=(int)date("m");
				$date_news[2]=(int)date("Y");
				for ($x=0;$x<$number;$x++) {
					$this->addProduct($date_news,'','','','',0,0,$id_category,0,0,0,0,1,1,'',$engine->generateInsertSQL("PRODUCTS",$lang_values));
				}
				$engine->clearCacheBlocks($this->thismodule["name"]);
				$engine->addModuleToCategory($this->thismodule["name"],$id_category);
				$engine->setCongratulation('','Было создано '.$number.' объектов',3000);
			}
		}
		$m_action="view";
	break;
	case "quickedit_firm":
		$engine->clearPath();
		$id_firm=@$_REQUEST["id_firm"];
			if (preg_match("/^[0-9]{1,}$/i",$id_firm)) {
					$firm=$this->getFirmByID($id_firm);
				    if (isset($_REQUEST["fck1"])) {
		    			$content=$engine->stripContent(@$_REQUEST["fck1"]);
		    			$first=false;
				    } else {
				    	$content=$firm["description"];
				    	$first=true;
				    }	
					$fck_editor1=$engine->createFCKEditor("fck1",$content);
					$smarty->assign("editor",$fck_editor1);
					$smarty->assign("firm",$firm);
					if (isset($_REQUEST["save"])) {
						if ($db->query("update `%FIRMS%` set `description`='".sql_quote($content)."' where id_firm=$id_firm")) {
							$smarty->assign("save",true);
							$smarty->assign("close",true);
						}
					}
			}
	break;
	case "quickedit":
		$engine->clearPath();
		$id_product=@$_REQUEST["id_product"];
			if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
					$product=$this->getProductByID($id_product);	
				    if (isset($_REQUEST["fck1"])) {
		    			$content=$engine->stripContent(@$_REQUEST["fck1"]);
		    			$first=false;
				    } else {
				    	$content=$product["content_full"];
				    	$first=true;
				    }	
					$fck_editor1=$engine->createFCKEditor("fck1",$content);
					$smarty->assign("editor",$fck_editor1);
					$smarty->assign("product",$product);
					if (isset($_REQUEST["save"])) {
						if ($db->query("update `%PRODUCTS%` set `content_full`='".sql_quote($content)."' where id_product=$id_product")) {
							$smarty->assign("save",true);
						}
					}
			}
	break;
	case "add":
	$values=array();
	$engine->getRubricsTreeEx($values,0,0,true,"",false);
	$types=$this->getTypesList();
	$engine->clearPath();
	$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
			
			$mode=@$_REQUEST["mode"];
			$modAction=@$_REQUEST["modAction"];
			if (isset($_REQUEST["id_product"])) {
				$id_product=@$_REQUEST["id_product"];
				$product=$this->getProductByID($id_product);
			}
			if (isset($_REQUEST["save"])) {
				$first=false;
				$caption=trim(@$_REQUEST["caption"]);
				$content=@$_REQUEST["fck1"];
				$content_full=@$_REQUEST["fck2"];
				$date_news=array();
				$date_news[0]=@$_REQUEST["date_news_day"];
				$date_news[1]=@$_REQUEST["date_news_month"];
				$date_news[2]=@$_REQUEST["date_news_year"];
				$url=@$_REQUEST["url"];
				$kolvo=@$_REQUEST["kolvo"];
				$price1=@$_REQUEST["price1"];
				$price2=@$_REQUEST["price2"];
				$price_default=@$_REQUEST["price_default"];
				$id_cat=@$_REQUEST["id_category"];
				$id_type=@$_REQUEST["id_type"];
				$code=@$_REQUEST["code"];
				$meta=@$_REQUEST["meta"];
				$keywords=@$_REQUEST["keywords"];
				if (isset($_REQUEST["visible"])) {
				 $visible=1;
				} else {
				 $visible=0;
				}
				$lang_values=@$_REQUEST["lang_values"];
				$collection_id=@$_REQUEST["collection_id"];
				$product_actions=@$_REQUEST["actions"];
			} else {
				$first=true;
				if ($mode=="edit") {
					if ($product) {
						$caption=$product["caption"];
						$content=$product["content"];
						$content_full=$product["content_full"];
						$url=$product["url"];
						$date_news=array();
						$date_news[0]=$product["date_day"];
						$date_news[1]=$product["date_month"];
						$date_news[2]=$product["date_year"];
						$kolvo=@$product["kolvo"];
						$price1=@$product["price1"];
						$price2=@$product["price2"];
						$price_default=@$product["price_default"];
						$id_cat=@$product["id_category"];
						$id_type=@$product["id_type"];
						$visible=@$product["visible"];
						$code=@$product["code"];
						$meta=@$product["meta"];
						$keywords=@$product["keywords"];
						$lang_values=$engine->generateLangArray("PRODUCTS",$product);
						$collection_id=@$product["id_firm"].':'.$product["id_collection"];
						$product_actions=$this->getProductActions($product["id_product"]);
					}
				} else {
					$caption="";
					$content="";
					$content_full="";
					$author="";
					$url="";
					$date_news=array();
					$date_news[0]=(int)date("d");
					$date_news[1]=(int)date("m");
					$date_news[2]=(int)date("Y");
					$kolvo=1;
					$price1=0;
					$price2=0;
					$price_default=0;
					$code="";
					$meta="";
					$keywords="";
					if (isset($_REQUEST["id_category"])) {
						if (preg_match("/^[0-9]{1,}$/i",$_REQUEST["id_category"])) {
							$id_cat=$_REQUEST["id_category"];
						}
					} else {
					$id_cat=@$values[0]["id"];
					}
					$id_type=0;
					$visible=1;
					$lang_values=$engine->generateLangArray("PRODUCTS",null);
					$collection_id='0:0';
					$product_actions=array();
				}
			}

			$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"].'&id_category='.$id_cat,true);
			
			require ($config["classes"]["form"]);
			$frm=new Form($smarty);
$frm->addField($lang["forms"]["catalog"]["razdel"]["caption"],$lang["forms"]["catalog"]["razdel"]["error"],"list",$values,$id_cat,"/^[0-9]{1,}$/i","id_category",1,$lang["forms"]["catalog"]["razdel"]["sample"],array('size'=>'30'));

$frm->addField('Тип товара','Неверно выбран тип товара',"list",$types,$id_type,"/^[0-9]{1,}$/i","id_type",1,'Телевизоры',array('size'=>'30'));
			
$frm->addField("Название товара","Неверно заполнено название товара","text",$caption,$caption,"/^[^`#]{2,255}$/i","caption",1,"Ноутбук ASUS",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Код товара","Неверно заполнен код товара","text",$code,$code,"/^[^`#]{2,255}$/i","code",0,"VZX-98944",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Дата создания товара","Неверно заполнена дата создания товара","date",$date_news,$date_news,"/^[0-9]{1,}$/i","date_news",1,"19.01.2008",array('size'=>'40','ticket'=>"Цифры и точки"));

$frm->addField("Описание товара","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","productcontent",0,'',array('hidden'=>true));

$fck_editor2=$engine->createFCKEditor("fck2",$content_full);
$frm->addField("Полное описание товара","Неверно заполнено полное описание товара","solmetra",$fck_editor2,$fck_editor2,"/^[[:print:][:allnum:]]{1,}$/i","content_full",1,"");

$fck_editor1=$engine->createFCKEditor("fck1",$content);
$frm->addField("Краткое описание товара","Неверно заполнено краткое описание товара","solmetra",$fck_editor1,$fck_editor1,"/^[[:print:][:allnum:]]{1,}$/i","content",1,"");

$frm->addField("Описание товара","","caption","","","/^[^a-zA-Z0-9]{2,10}$/i","productcontent",0,'',array('end'=>true));

if ($this->thismodule["show_price1"]) {
$frm->addField("Стоимость","Неверно заполнено поле стоимость","text",$price1,$price1,"/^[0-9]{1,}$/i","price1",0,"1000",array('size'=>'5','ticket'=>"Цифры"));
$frm->addField("Закупочная стоимость","Неверно заполнено поле закупочная стоимость","text",$price_default,$price_default,"/^[0-9]{1,}$/i","price_default",0,"1000",array('size'=>'5','ticket'=>"Цифры"));
} else {
$frm->addField("Стоимость","Неверно заполнено поле стоимость","hidden",$price1,$price1,"/^[0-9]{1,}$/i","price1",0,"1000",array('size'=>'5','ticket'=>"Цифры"));
$frm->addField("Закупочная стоимость","Неверно заполнено поле закупочная стоимость","hidden",$price_default,$price_default,"/^[0-9]{1,}$/i","price_default",0,"1000",array('size'=>'5','ticket'=>"Цифры"));
}

if ($this->thismodule["show_price2"]) {
$frm->addField("Старая цена","Неверно заполнено поле старая цена","text",$price2,$price2,"/^[0-9]{1,}$/i","price2",0,"1000",array('size'=>'5','ticket'=>"Цифры"));
} else {
$frm->addField("Старая цена","Неверно заполнено поле старая цена","hidden",$price2,$price2,"/^[0-9]{1,}$/i","price2",0,"1000",array('size'=>'5','ticket'=>"Цифры"));
}
if ($this->thismodule["show_count"]) {
$frm->addField("Количество","Неверно заполнено поле количество","text",$kolvo,$kolvo,"/^[0-9]{1,}$/i","kolvo",0,"12",array('size'=>'5','ticket'=>"Цифры"));
} else {
$frm->addField("Количество","Неверно заполнено поле количество","hidden",$kolvo,$kolvo,"/^[0-9]{1,}$/i","kolvo",0,"12",array('size'=>'5','ticket'=>"Цифры"));
}

$collections=$this->getFirmsAndCollections();
$frm->addField('Фирма / Коллекция товара','Неверно выбрана фирма / коллекция товара',"list",$collections,$collection_id,"/^[0-9:0-9]{1,}$/i","collection_id",1,'Apple / MacBook',array('size'=>'30'));

$frm->addField("Сайт производителя","Неверно заполнен URL производителя","text",$url,$url,"/^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]/i","url",0,"http://www.lenta.ru/18/08/2008/12.html",array('size'=>'40','ticket'=>"Адрес сайта"));

$actions=$this->getActions();

if (is_Array($actions)) {
$frm->addField('Товар учавствует в акциях',"","caption",0,0,"/^[0-9]{1}$/i","acts",0,'',array('hidden'=>true));



foreach ($actions as $id_action=>$action) {
if (isset($product_actions[$action["id_action"]])) {
	$v=true;
} else {
	$v=false;
}
$frm->addField($action["caption"],"Неверно выбрано свойство ".$action["caption"],"check",$v,$v,"/^[^`#]{1,255}$/i","actions[".$action["id_action"]."]",0);
}

$frm->addField('Товар учавствует в акциях',"","caption",0,0,"/^[0-9]{1}$/i","acts",0,'',array('end'=>true));

}


$frm->addField("Тег meta description","Неверно заполнен тег meta description","textarea",$meta,$meta,"/^[^`#]{2,255}$/i","meta",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$frm->addField("Тег meta keywords","Неверно заполнен тег meta keywords","textarea",$keywords,$keywords,"/^[^`#]{2,255}$/i","keywords",0,"",array('size'=>'40','ticket'=>"Любые буквы и цифры"));

$engine->generateLangControls("PRODUCTS",$lang_values,$frm);

$frm->addField($lang["forms"]["products"]["visible"]["caption"],$lang["forms"]["products"]["visible"]["error"],"check",$visible,$visible,"/^[0-9]{1}$/i","visible",1);

$frm->addField("","","hidden",$mode,$mode,"/^[^`]{0,}$/i","mode",1);
$frm->addField("","","hidden",$modAction,$modAction,"/^[^`]{0,}$/i","modAction",1);
if (isset($_REQUEST["id_product"])) {
$id_product=$_REQUEST["id_product"];
$frm->addField("","","hidden",$id_product,$id_product,"/^[^`]{0,}$/i","id_product",1);
}

if (checkdate($date_news[1],$date_news[0],$date_news[2])==false)
	$frm->addError("Выбранной даты не существует!");
if ($mode=="edit") {
$engine->addPath('Редактирование товара','',false);
if ($code!=$product["code"])
if (trim($code)!='')
 if ($this->existProduct($code))
	$frm->addError("Товар с кодом <b>$code</b> существует!");
} else {
$engine->addPath('Добавление товара','',false);
if (trim($code)!='')
 if ($this->existProduct($code))
	$frm->addError("Товар с кодом <b>$code</b> существует!");
}
$engine->assignPath();
			if (
$engine->processFormData($frm,"Сохранить",$first
			)) {
				//добавляем или редактируем
				$item=$this->parseCollection($collection_id);
				if ($mode=="edit") {
				 //редактируем
				 if (isset($id_product)) {
				 	if (!preg_match("/^[0-9.]{1,}$/i",$price1)) $price1=0;
				 	if (!preg_match("/^[0-9.]{1,}$/i",$price2)) $price2=0;
				 	if (!preg_match("/^[0-9.]{1,}$/i",$price_default)) $price_default=0;
				 	if ($db->query("update %PRODUCTS% set `caption`='".sql_quote($caption)."' , `url`='".sql_quote($url)."',`date`='".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."',content='".sql_quote($content)."',`content_full`='".sql_quote($content_full)."',visible=$visible,price1=$price1,price2=$price2,price_default=$price_default,kolvo=$kolvo,code='".sql_quote($code)."',id_category=$id_cat,`id_type`=$id_type,`id_firm`=".$item[0].",`id_collection`=".$item[1].", `meta`='".sql_quote($meta)."', `keywords`='".sql_quote($keywords)."' ".$engine->generateUpdateSQL("PRODUCTS",$lang_values)." where id_product=$id_product")) {
						//отредактировали
				//	   $modAction="view";
				   $engine->setCongratulation("","Товар отредактирован успешно!",3000);
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				   $m_action="view";
	   				//Удаляем акции
					$this->clearProductActions($product["id_product"]);
					$this->addActionsToProduct($product_actions,$product["id_product"]);
					}
				 } else {
				 	//показываем ошибку
				 }
				} else {
				 //добавляем
 $add_id=$this->addProduct($date_news,$caption,$content,$content_full,$url,$item[0],$item[1],$id_cat,$id_type,$price1,$price2,$price_default,$kolvo,$visible,$code,$engine->generateInsertSQL("PRODUCTS",$lang_values),$meta,$keywords);
				 if ($add_id!=false) {
				   //добавили успешно!
				//   $modAction="view";
				   $engine->setCongratulation("","Товар добавлен успешно!",3000);
				   $engine->addModuleToCategory($this->thismodule["name"],$id_cat);
				   $engine->clearCacheBlocks($this->thismodule["name"]);
				   $this->addActionsToProduct($product_actions,$add_id);
				   $m_action="view";
				 }
				}
			}
	break;
	case "set_main":
		$id_product=@$_REQUEST["id_product"];
		$id_image=@$_REQUEST["id_image"];
		if (preg_match("/^[0-9]{1,}$/i",$id_product) &&
			preg_match("/^[0-9]{1,}$/i",$id_image)
		) {
			$db->query("update `%PRODUCT_PICTURES%` set main_picture=0 where id_product=$id_product");
			$db->query("update `%PRODUCT_PICTURES%` set main_picture=1 where id_product=$id_product and id_image=$id_image");
			$product=$this->getProductByID($id_product);
			$smarty->assign("product",$product);
			$smarty->assign("id_product",$id_product);
		}
	break;
	case "upload_pictures":
	$secretkey=md5($config["secretkey"]."scripto_gallery".$settings["login"]);;
	$id_product=@$_REQUEST["id_product"];
	if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
	$smarty->assign("id_product",$id_product);
	$product=$this->getProductByID($id_product);
	$smarty->assign("prod",$product);
	$smarty->assign("secretkey",$secretkey);
	}
	$smarty->assign("doUpload",true);
	break;
	case "delete":
		$id_product=@$_REQUEST["id_product"];
		if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
			$product=$this->getProductByID($id_product);
			if ($db->query("delete from %PRODUCTS% where id_product=$id_product")) {
				$db->query("delete from %PRODUCT_PICTURES% where id_product=$id_product");
				$engine->setCongratulation("Товар удален!");
				$smarty->assign("id_category",$product["id_category"]);
				$engine->clearCacheBlocks($this->thismodule["name"]);
			}
		}
	break;
	case "save":
		$id_category=@$_REQUEST["id_category"];
		if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
			$idprod=@$_REQUEST["idprod"];
			$caption=@$_REQUEST["caption"];
			$price=@$_REQUEST["price"];
			$code=@$_REQUEST["code"];
			$vis=@$_REQUEST["vis"];
			$sort=@$_REQUEST["sort"];
			$del=@$_REQUEST["del"];
			foreach ($idprod as $key=>$prod) {
			if (isset($del[$key])) {
				$db->query("DELETE FROM %PRODUCTS% where `id_product`=$prod");
			} else {
				if (isset($vis[$key])) {
					$vis_value=1;
				} else {
					$vis_value=0;
				}
				$db->query("UPDATE %PRODUCTS% set `caption`='".sql_quote($caption[$key])."',`visible`=$vis_value,`code`='".sql_quote($code[$key])."',price1=".$price[$key].",`sort`=".$sort[$key]." where `id_product`=$prod");
			}
			}
		}
		$engine->clearCacheBlocks($this->thismodule["name"]);
		$engine->setCongratulation('','Информация о товарах изменена',3000);
		$m_action="view";
	break;
	default: $m_action="view";
}
if ($m_action=="types") {
		//типы товаров
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Просмотр типов товаров','',false);
		$engine->assignPath();
		$engine->addJS("/core/usermodules/products/types.js");
		$engine->assignJS();
		if (isset($_REQUEST["saved"])) {
			$caption=@$_REQUEST["caption"];
			$del=@$_REQUEST["del"];
			$d=0;
			$u=0;
			if (is_array($caption)) {
				foreach ($caption as $id_type=>$capt) {
					if (isset($del[$id_type])) {
						$db->query("delete from `%PRODUCT_TYPES%` where id_type=$id_type");
						$db->query("delete from `%PRODUCT_OPTIONS%` where id_type=$id_type");
						$d++;
					} else {
						$db->query("update `%PRODUCT_TYPES%` set `caption`='".sql_quote($capt)."' where id_type=$id_type");
						$u++;
					}
				}
			}
			$engine->clearCacheBlocks($this->thismodule["name"]);
			$engine->setCongratulation('Данные сохранены','Обновлено '.$u.' типов, удалено '.$d.' типов.',3000);
		}
		$types=$this->getProductTypes();
		$smarty->assign("types",$types);
}
if ($m_action=="actions") {
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Акции и спецпредложения','',false);
		$engine->assignPath();
		if (isset($_REQUEST["idaction"])) {
			$idaction=@$_REQUEST["idaction"];
			$caption=@$_REQUEST["caption"];
			$del=@$_REQUEST["del"];
			$d=0;
			$u=0;
			foreach ($idaction as $id_action=>$action) {
				if (isset($del[$id_action])) {
					if ($db->query("delete from `%ACTIONS%` where `id_action`=".$id_action)) {
						$db->query("delete from `%ACTION_PRODUCTS%` where `id_action`=".$id_action);
						$d++;
					}
				} else {
					if (isset($caption[$id_action]))
					 if ($db->query("update `%ACTIONS%` set `caption`='".sql_quote($caption[$id_action])."' where `id_action`=".$id_action)) {
						$u++;
					 }
				}
			}
			$engine->setCongratulation('Данные сохранены',"Обновлено $u акций, удалено $d акций",3000);
		}
		$actions=$this->getActions();
		$smarty->assign("actions",$actions);
}
if ($m_action=="pricing") {
	//Работа с наценками
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Управление наценками','',false);
		$engine->assignPath();
}
if ($m_action=="firms") {
	//Работа с фирмами
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'/admin/?module=modules&modAction=settings&module_name='.$this->thismodule["name"],true);
		$engine->addPath('Фирмы и коллекции','',false);
		$engine->assignPath();
		if (isset($_REQUEST["add"])) {
			$firmcaption=@$_REQUEST["firmcaption"];
			if (!$this->existFirm($firmcaption)) {
				$lang_values=array();
				if ($this->addFirm($firmcaption,'',$engine->generateInsertSQL("FIRMS",$lang_values))) {
					$engine->setCongratulation('',"Фирма $firmcaption добавлена",3000);
				 	$engine->clearCacheBlocks($this->thismodule["name"]);
				} else {
					$engine->setCongratulation('Ошибка',"При добавлении фирмы $firmcaption произошла ошибка",3000);
				}
			} else {
				$engine->setCongratulation('Ошибка',"Фирма уже $firmcaption уже создана",3000);
			}
		}
		if (isset($_REQUEST["save"])) {
			$idfirm=@$_REQUEST["idfirm"];
			$caption=@$_REQUEST["caption"];
			$del=@$_REQUEST["del"];
			if (is_array($idfirm)) {
				$d=0;
				$u=0;
				foreach ($idfirm as $id_firm=>$firm) {
					if (isset($del[$id_firm])) {
						if ($db->query("delete from `%FIRMS%` where `id_firm`=$id_firm")) {
							$this->deleteCollections($id_firm);
							$d++;
						}
					} else {
						if ($db->query("update `%FIRMS%` set `caption`='".sql_quote($caption[$id_firm])."' where `id_firm`=$id_firm")) {
							$u++;
						}
					}
				}
				$engine->clearCacheBlocks($this->thismodule["name"]);
				$engine->setCongratulation('',"Обновлено $u фирм, удалено $d фирм",3000);
			}
		}
		$firms=$this->getAllFirms();
		$smarty->assign("firms",$firms);
}
if ($m_action=="view") {
		//получаем все рубрики
		$engine->clearPath();
		$engine->addPath($lang["interface"]["rule_module"],'/admin?module=modules',true);
		$engine->addPath($this->thismodule["caption"],'',false);
		$engine->assignPath();
		$rubrics=$this->getCountAllProducts();
		if (isset($_REQUEST["id_category"])) {
			$id_category=$_REQUEST["id_category"];
			if (preg_match("/^[0-9]{1,}$/i",$id_category)) {
					$onpage=40;
					if (isset($_REQUEST["str"])) {
						$str_real=trim($_REQUEST["str"]);
						if (trim($str_real)!='') {
						$find=strpos($str_real,'*');
						if ($find===false) {
							$str='%'.$str_real.'%';
						} else {
							$str=str_replace('*','%',$str_real);
						}
						$smarty->assign('str',$str_real);
						$smarty->assign('str_url',urlencode($str_real));
						} else {
							$str='';
						}
					} else {
						$str='';
					}
					$count=$this->getProductsCount($id_category,$str,false,false);
					$pages=ceil($count/$onpage);
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
					$products=$this->getProducts($id_category,false,false,$pg,$onpage,$str);
					$smarty->assign("products",$products);
					$smarty->assign("id_category",$id_category);
					$category=$engine->getCategoryByID($id_category);
					$smarty->assign("category",$category);
					$ppath=$engine->getPath($id_category);
					$smarty->assign("ppath",$ppath);
					if (isset($pages_arr)) {
					$smarty->assign("pages",$pages_arr);
					$smarty->assign("pagenumber",$pg);
					}
			}
		} else {
			$count_products=$this->getCountAllProductsEx();
			$smarty->assign("count",$count_products);
		}
}

$smarty->assign("m_action",$m_action);
?>