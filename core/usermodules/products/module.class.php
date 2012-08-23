<?
/*
Модуль товары
*/

define("SCRIPTO_products",true);

class products {
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
		$db->addPrefix("%CROSSSALE%",$config["db"]["prefix"]."crosssale");
		$db->addPrefix("%ACTIONS%",$config["db"]["prefix"]."actions");
		$db->addPrefix("%ACTION_PRODUCTS%",$config["db"]["prefix"]."action_products");
		$db->addPrefix("%BLOCK_ACTIONS%",$config["db"]["prefix"]."block_actions");
		$db->addPrefix("%PRODUCTS%",$config["db"]["prefix"]."products");
		$db->addPrefix("%FIRMS%",$config["db"]["prefix"]."firms");
		$db->addPrefix("%COLLECTIONS%",$config["db"]["prefix"]."collections");
		$db->addPrefix("%PRODUCT_PICTURES%",$config["db"]["prefix"]."product_pictures");
		$db->addPrefix("%BLOCK_PRODUCTS%",$config["db"]["prefix"]."block_products");
		$db->addPrefix("%PRODUCT_PRODUCTS%",$config["db"]["prefix"]."product_products");
		$db->addPrefix("%PRODUCT_VARIANTS%",$config["db"]["prefix"]."product_variants");
		$db->addPrefix("%PRODUCT_TYPES%",$config["db"]["prefix"]."product_types");
		$db->addPrefix("%PRODUCT_OPTIONS%",$config["db"]["prefix"]."product_options");
	}
	
	function doInstall() {
		global $db;
		global $engine;
		$type_id=mysql_insert_id();
		if ($db->query("insert into `%blocks%` values (null,0,'Товары','',$type_id,'products',1,0,2,5,'".date('Y-m-d H:i:s')."',0".$engine->generateInsertSQL("blocks",array()).");")) {
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
	
	function getProductsByProduct($id_product,$visible=true,$num=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		$str="";
		if ($visible) {
			$str=" and %PRODUCTS%.visible=1";
		}
		$limit="";
		if ($num>0) {
			$limit=" LIMIT 0,$num";
		}
		$res=$db->query("select %PRODUCTS%.* from %PRODUCTS%,%PRODUCT_PRODUCTS% where %PRODUCT_PRODUCTS%.id_product=$id_product and %PRODUCT_PRODUCTS%.id_product2=%PRODUCTS%.id_product $str $limit");
		while ($row=$db->fetch($res)) {
			$row["picture"]=$this->getPictureFromProductID($row["id_product"]);
			$products[]=$row;
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}	
	
	function existProductInProduct($id_product,$id_product2) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product2)) return false;
		if ($db->getCount("select id_product from `%PRODUCT_PRODUCTS%` where id_product=$id_product and id_product2=$id_product2")>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function addProductToProduct($id_product,$id_product2) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product2)) return false;
		if ($this->existProductInProduct($id_product,$id_product2)) return false;
		if ($db->query("insert into `%PRODUCT_PRODUCTS%` values($id_product,$id_product2)")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getPrintPrice($price,$valute="руб") {
		if (!preg_match("/^[0-9]{1,}$/i",$price)) return $price." $valute";
		$new_price="";
		$l=strlen($price);
		for ($i=0;$i<=$l;$i++) {
			$ch=(($l-$i)/3) - floor(($l-$i)/3);
			if ($ch==0) $new_price.=" ";
			$new_price.=substr($price,$i,1);
		}
		return $new_price." $valute";
	}
	
	function deleteProductFromProduct($id_product,$id_product2) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product2)) return false;
		if ($db->query("delete from `%PRODUCT_PRODUCTS%` where id_product=$id_product and id_product2=$id_product2")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getTopProducts($number) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$number)) {
			$res=$db->query("select %PRODUCTS%.* from %PRODUCTS% ORDER BY `buy` DESC limit 0,$number");
			while ($row=$db->fetch($res)) {
				$row["picture"]=$this->getPictureFromProductID($row["id_product"]);
				$row["print_price"]=$this->getPrintPrice($row["price1"]);
				$products[]=$row;
			}
			if (isset($products)) {
				return $products;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getProductsRandomByBlock($id_block,$visible=true,$num=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		$str="";
		if ($visible) {
			$str=" and %PRODUCTS%.visible=1";
		}
		$limit="";
		if ($num>0) {
			$limit=" LIMIT 0,$num";
		}
		$res=$db->query("select %PRODUCTS%.* from %PRODUCTS%,%BLOCK_PRODUCTS% where %BLOCK_PRODUCTS%.id_block=$id_block and %BLOCK_PRODUCTS%.id_product=%PRODUCTS%.id_product $str ORDER BY RAND() $limit");
		while ($row=$db->fetch($res)) {
			$row["picture"]=$this->getPictureFromProductID($row["id_product"]);
			$row["price_print"]=$this->getPrintPrice($row["price1"]);
			$products[]=$row;
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}
	
	function getFavoriteProductsCount() {
		if (isset($_SESSION["favorites"])) {
			if (is_array($_SESSION["favorites"])) {
				$k=0;
				foreach ($_SESSION["favorites"] as $favorite) {
					if ($favorite)
						$k++;
				}
				return $k;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	function getFavoriteProducts() {
		global $db;
		if (isset($_SESSION["favorites"])) {
			if (is_array($_SESSION["favorites"])) {
				$k=0;
				$sql='';
				foreach ($_SESSION["favorites"] as $id_product=>$favorite) {
					if ($favorite) {
						if ($k==0) {
							$sql.='where `id_product`='.$id_product;
						} else {
							$sql.=' or `id_product`='.$id_product;
						}
						$k++;
					}
				}
				if ($sql!='') {
				$res=$db->query("select * from %PRODUCTS% $sql");
				while ($row=$db->fetch($res)) {
					$row["picture"]=$this->getPictureFromProductID($row["id_product"]);
					$row["price_print"]=$this->getPrintPrice($row["price1"]);
					$row["price2_print"]=$this->getPrintPrice($row["price2"]);
					$row["values"]=@unserialize($row["options_info"]);
					if (isset($row["values"]["options"]))
						$row["values"]["options"]=$this->decodeSerialize($row["values"]["options"]);
					$products[]=$row;
				}
				if (isset($products)) {
					return $products;
				} else {
					return false;
				}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function getProductsByBlock($id_block,$visible=true,$num=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		$str="";
		if ($visible) {
			$str=" and %PRODUCTS%.visible=1";
		}
		$limit="";
		if ($num>0) {
			$limit=" LIMIT 0,$num";
		}
		$res=$db->query("select %PRODUCTS%.* from %PRODUCTS%,%BLOCK_PRODUCTS% where %BLOCK_PRODUCTS%.id_block=$id_block and %BLOCK_PRODUCTS%.id_product=%PRODUCTS%.id_product $str $limit");
		while ($row=$db->fetch($res)) {
			$row["picture"]=$this->getPictureFromProductID($row["id_product"]);
			$row["price_print"]=$this->getPrintPrice($row["price1"]);
			$row["price2_print"]=$this->getPrintPrice($row["price2"]);
			$products[]=$row;
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}
	
	function getProductsByActionBlock($id_action,$visible=true,$num=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_action)) return false;
		$str="";
		if ($visible) {
			$str=" and %PRODUCTS%.visible=1";
		}
		$limit="";
		if ($num>0) {
			$limit=" LIMIT 0,$num";
		}
		$res=$db->query("select %PRODUCTS%.* from %PRODUCTS%,%ACTION_PRODUCTS% where %ACTION_PRODUCTS%.id_action=$id_action and %ACTION_PRODUCTS%.id_product=%PRODUCTS%.id_product $str $limit");
		
		while ($row=$db->fetch($res)) {
			$row["picture"]=$this->getPictureFromProductID($row["id_product"]);
			$row["price_print"]=$this->getPrintPrice($row["price1"]);
			$row["price2_print"]=$this->getPrintPrice($row["price2"]);
			$products[]=$row;
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}
	
	function getAllProducts($mode="rubrics") {
		global $db;
		$res=$db->query("select id_product,caption,code from %PRODUCTS% order by caption");

		while ($row=$db->fetch($res)) {
			if ($mode=="code") {
				$products[$row["code"]]=$row;
			} else {
				$products[]=$row;
			}
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}	
	
	function existProductInBlock($id_block,$id_product) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		if ($db->getCount("select id_product from `%BLOCK_PRODUCTS%` where id_product=$id_product and id_block=$id_block")>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function addProductToBlock($id_block,$id_product) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		if ($this->existProductInBlock($id_block,$id_product)) return false;
		if ($db->query("insert into `%BLOCK_PRODUCTS%` values($id_product,$id_block)")) {
			return true;
		} else {
			return false;
		}
	}
	
	function deleteProductFromBlock($id_block,$id_product) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		if ($db->query("delete from `%BLOCK_PRODUCTS%` where id_product=$id_product and id_block=$id_block")) {
			return true;
		} else {
			return false;
		}
	}	
	
	function deleteBlockAdmin($id_block) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		if ($db->query("delete from `%BLOCK_PRODUCTS%` where id_block=$id_block")) {
			return true;
		} else {
			return false;
		}
	}
	
	function doBlockAdmin($block,$page) {
		global $db;
		global $smarty;
		global $config;
		switch ($block["type"]["type"]) {
			case "products_random":
			case "products":
				//добавляем товары к блоку
				if (isset($_REQUEST["add_product"])) {
					$id_product=@$_REQUEST["id_product"];
					$this->addProductToBlock($block["id_block"],$id_product);
				}
				if (isset($_REQUEST["delete_product"])) {
					$id_product=@$_REQUEST["id_product"];
					$this->deleteProductFromBlock($block["id_block"],$id_product);
				}
				$block_products=$this->getProductsByBlock($block["id_block"],false,0);
				$smarty->assign("block_products",$block_products);
				$products=$this->getAllProducts();
				$smarty->assign("products",$products);
				$this->engine->clearCacheBlocks($this->thismodule["name"]);
			break;
			case "products_actions":
				if (isset($_REQUEST["id_action"])) {
					$id_act=@$_REQUEST["id_action"];
					if (preg_match("/^[0-9]{1,}$/i",$id_act)) {
						$this->deleteAllActionsFromBlock($block["id_block"]);
						$db->query("insert into `%BLOCK_ACTIONS%` values($id_act,".$block["id_block"].")");
						$this->engine->clearCacheBlocks($this->thismodule["name"]);
					}
				}
				$id_action=$this->getActionByBlock($block["id_block"]);
				$smarty->assign("id_action",$id_action);
				$block_actions=$this->getActions();
				$smarty->assign("block_actions",$block_actions);
			break;
		}
		if (is_file($config["pathes"]["templates_dir"].$this->thismodule["template_path"]."admin_block.tpl.html")) {
			$content=$smarty->fetch($this->thismodule["template_path"]."admin_block.tpl.html");
			return $content;
		} else {
			return false;
		}
	}
	
	function doBlock($block,$page,&$objects) {
		global $smarty;
		global $rubrics;
		$smarty->assign("product_block_type",$block["type"]["type"]);
		switch ($block["type"]["type"]) {
			case "products_compare":
				$favorite_count=$this->getFavoriteProductsCount();
				$smarty->assign("favorite_count",$favorite_count);
			break;
			case "products_firms":
				$firms=$this->getAllFirms();
				$smarty->assign("block_firms",$firms);
			break;
			case "products_actions":
				$action=$this->getActionArrayByBlock($block["id_block"]);
				$smarty->assign("block_action",$action);
				$block_products=$this->getProductsByActionBlock($action["id_action"],true,$block["number_objects"]);
		if (is_array($block_products))
			foreach ($block_products as $key=>$product)
				foreach ($rubrics as $position) {
					foreach ($position as $rubr) {
						if ($product["id_category"]==$rubr["id_category"]) {
							$block_products[$key]["product_url"]=$rubr["url"];
							break;break;
						}
					}
				}
				$smarty->assign("block_products",$block_products);
			break;
			case "products_types":
				$types=$this->getProductTypes();
				$smarty->assign("block_types",$types);
			break;
			case "products_leaders":
				$block_products=$this->getTopProducts($block["number_objects"]);
		if (is_array($block_products))
			foreach ($block_products as $key=>$product)
				foreach ($rubrics as $position) {
					foreach ($position as $rubr) {
						if ($product["id_category"]==$rubr["id_category"]) {
							$block_products[$key]["product_url"]=$rubr["url"];
							break;break;
						}
					}
				}
				$smarty->assign("block_products",$block_products);
			break;
			case "products_random":
			$block_products=$this->getProductsRandomByBlock($block["id_block"],true,$block["number_objects"]);
		if (is_array($block_products))
			foreach ($block_products as $key=>$product)
				foreach ($rubrics as $position) {
					foreach ($position as $rubr) {
						if ($product["id_category"]==$rubr["id_category"]) {
							$block_products[$key]["product_url"]=$rubr["url"];
							break;break;
						}
					}
				}
				$smarty->assign("block_products",$block_products);
			break;
			case "products":
			$block_products=$this->getProductsByBlock($block["id_block"],true,$block["number_objects"]);
		if (is_array($block_products))
			foreach ($block_products as $key=>$product)
				foreach ($rubrics as $position) {
					foreach ($position as $rubr) {
						if ($product["id_category"]==$rubr["id_category"]) {
							$block_products[$key]["product_url"]=$rubr["url"];
							break;break;
						}
					}
				}
				$smarty->assign("block_products",$block_products);
			break;
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
		if ($engine->checkInstallModule("products")) {
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
	
	//Поиск товаров
	function doUserSearch($str,$prefix='') {
		global $db;
		$res=$db->query("select * from `%PRODUCTS%` where `caption$prefix` LIKE '%$str%' or `code` LIKE '%$str%' or `content$prefix` LIKE '%$str%' and visible=1 order by `sort` DESC");
		while ($row=$db->fetch($res)) {
			$row["prist"]="?productID=".$row["id_product"];
			$items[]=$row;
		}
		if (isset($items)) return $items;
		return false;
	}
	
	//проверяем существование товара
	function existProduct($code) {
		global $db;
		$res=$db->query("select id_product from `%PRODUCTS%` where `code`='".sql_quote($code)."'");
		if (@mysql_num_rows($res)>0) {
			return true;
		} else {
			return false;
		}
	}
	/*добавление товаров*/
	function addProduct($date_news="",$caption="",$content="",$content_full="",$url="",$id_firm=0,$id_collection=0,$id_cat,$id_type,$price1=0,$price2=0,$price_default=0,$kolvo=0,$visible=1,$code="",$sql="",$meta='',$keywords='') {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_firm)) return false;
		if (!preg_match("/^[0-9]{1,}$/i",$id_collection)) return false;
		if (
		$db->query("
		insert into %PRODUCTS% values (null,$id_cat,$id_type,$id_firm,$id_collection,'".sql_quote($caption)."','".sql_quote($code)."','".sql_quote($content)."','".sql_quote($content_full)."','".sql_quote($date_news[2])."-".sql_quote($date_news[1])."-".sql_quote($date_news[0])."','".sql_quote($url)."',$price1,$price2,$price_default,$kolvo,0,$visible,0,0,'','".sql_quote($meta)."','".sql_quote($keywords)."'".$sql.")
		")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function getProductPictures($id_product) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
			$res=$db->query("select * from `%PRODUCT_PICTURES%` where id_product=$id_product order by `sort` DESC");
			$pictures=array();
			while ($row=$db->fetch($res)) {
				$pictures[]=$row;
			}
			return $pictures;
		}
		return false;
	}
	
	//получаем товар по идентификатору
	function getProductByID($id_product) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
				$res=$db->query("select *,DATE_FORMAT(`date`,'%d') as `date_day`,DATE_FORMAT(`date`,'%m') as `date_month`,DATE_FORMAT(`date`,'%Y') as `date_year`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date_print` from `%PRODUCTS%` where id_product=$id_product");
			$row=$db->fetch($res);
			$row["price_print"]=$this->getPrintPrice($row["price1"]);
			$row["price2_print"]=$this->getPrintPrice($row["price2"]);
			$row["pictures"]=$this->getProductPictures($id_product);
			$row["values"]=@unserialize($row["options_info"]);
			if (isset($row["values"]["options"]))
				$row["values"]["options"]=$this->decodeSerialize($row["values"]["options"]);
			return $row;
		} else {
			return false;
		}
	}
	
	//Декодировать значения
	function decodeSerialize($array) {
		if (is_array($array)) {
			foreach ($array as $key=>$arr) {
				if (is_array($arr)) {
					$array[$key]=$this->decodeSerialize($arr);
				} else {
					$array[$key]=base64_decode($arr);
				}
			}
		}
		return $array;
	}
	
	//получить общее количество товаров
	function getCountAllProducts() {
		global $db;
		global $rubrics;
		global $engine;
		global $smarty;
		
		$products=array();
		$userproducts=array();
		$res=$db->query("select `id_category`,count(`id_product`) as `cnt` from %PRODUCTS% group by `id_category`");
		while ($row=$db->fetch($res)) {
			$products[$row["id_category"]]=$row["cnt"];
		}
		$res=$db->query("select `id_category`,count(`id_product`) as `cnt` from %PRODUCTS% where `visible`=1 group by `id_category`");
		while ($row=$db->fetch($res)) {
			$userproducts[$row["id_category"]]=$row["cnt"];
		}		
		if (!isset($rubrics)) {
			$rubrics=$engine->getAllPositionsRubrics(0);
		}
		foreach ($rubrics as $key1=>$position) {
			foreach ($position as $key2=>$category) {
				if (isset($products[$category["id_category"]])) {
					$rubrics[$key1][$key2]["products"]=$products[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["products"]=0;
				}
				if (isset($userproducts[$category["id_category"]])) {	$rubrics[$key1][$key2]["userproducts"]=$userproducts[$category["id_category"]];
				} else {
					$rubrics[$key1][$key2]["userproducts"]=0;
				}
			}
		}
		$smarty->assign("rubrics",$rubrics);
		return $rubrics;
	}
	
	function filterProductsCount($filter,$in_category=false) {
		global $db;
		$sql='';
		$i=0;
		$action_products=false;
		if (is_array($filter)) {
			foreach ($filter as $key=>$value) {
				if (preg_match("/^[0-9]{1,}$/i",$value)) {
					if ($i==0) $sql.='where ';
					if ($i>0) $sql.=' and ';
					if ($key=='id_category') {
						if ($in_category) {
							$sql.="(";
							$this->generateSQLDop($value,$sql,true);
							$sql.=")";
						} else {
							$sql="`$key`=$value";
						}
					} elseif($key=='id_action') {
						$action_products=true;
						$sql.="`%ACTION_PRODUCTS%`.`id_action`=$value and `%ACTION_PRODUCTS%`.id_product=`%PRODUCTS%`.id_product";
					} else {
						$sql.="`$key`=$value";
					}
					$i++;
				}
			}
		}
		if ($action_products) {
			$d_sql=",`%ACTION_PRODUCTS%`";
		} else {
			$d_sql="";
		}
		$res=$db->query("select `%PRODUCTS%`.id_product from `%PRODUCTS%`".$d_sql." $sql");
		return @mysql_num_rows($res);
	}
	
	function filterProducts($filter,$in_category=false,$page=0,$onpage=20,$sort="date",$desc="DESC") {
		global $db;
		$sql='';
		$i=0;
		$action_products=false;
		if (is_array($filter)) {
			foreach ($filter as $key=>$value) {
				if (preg_match("/^[0-9]{1,}$/i",$value)) {
					if ($i==0) $sql.='where ';
					if ($i>0) $sql.=' and ';
					if ($key=='id_category') {
						if ($in_category) {
							$sql.="(";
							$this->generateSQLDop($value,$sql,true);
							$sql.=")";
						} else {
							$sql="`$key`=$value";
						}
					} elseif($key=='id_action') {
						$action_products=true;
						$sql.="`%ACTION_PRODUCTS%`.`id_action`=$value and `%ACTION_PRODUCTS%`.id_product=`%PRODUCTS%`.id_product";
					} else {
						$sql.="`$key`=$value";
					}
					$i++;
				}
			}
		}
//		echo $sql_dop;
		if ($action_products) {
			$d_sql=",`%ACTION_PRODUCTS%`";
		} else {
			$d_sql="";
		}
		$res=$db->query("select `%PRODUCTS%`.* from `%PRODUCTS%`".$d_sql." $sql order by $sort $desc LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$products[$row["id_product"]]=$row;
			$products[$row["id_product"]]["picture"]=$this->getPictureFromProductID($row["id_product"]);
$products[$row["id_product"]]["price_print"]=$this->getPrintPrice($products[$row["id_product"]]["price1"]);
$products[$row["id_product"]]["price2_print"]=$this->getPrintPrice($products[$row["id_product"]]["price2"]);
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}
	
	function getProductsCount($id_cat=0,$str='',$visible=1,$in_category=false) {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		if (trim($str)!='') {
			$str_sql="and (`caption` like '".sql_quote($str)."' or `code` like '".sql_quote($str)."')";
		} else {
			$str_sql='';
		}
		if ($in_category) {
		$sql_dop="and (";
		$this->generateSQLDop($id_cat,$sql_dop,true);
		$sql_dop.=")";
		} else {
		$sql_dop=" and `id_category`=$id_cat";
		}
		$res=$db->query("select id_product from `%PRODUCTS%` where 1 $sql_dop $str_sql $vis");
		return @mysql_num_rows($res);
	}
	
	function generateSQLDop($id_cat=0,&$sql_dop,$first=false) {
		global $rubrics;
		if (!preg_match("/^[0-9]{1,}$/i",$id_cat)) return false;
		if ($first) {
		$sql_dop.="id_category=$id_cat";
		} else {
		$sql_dop.=" or id_category=$id_cat";
		}
		foreach ($rubrics as $position) {
			foreach ($position as $rubric) {
				if ($rubric["id_sub_category"]==$id_cat) {
					$this->generateSQLDop($rubric["id_category"],$sql_dop,false);
				}
			}
		}
		return true;
	}
	
	function getPictureFromProductID($id_product) {
		global $db;
		$res=$db->query("select * from `%PRODUCT_PICTURES%` where id_product=$id_product order by `sort` DESC LIMIT 0,1");
		if (mysql_num_rows($res)>0) {
			return $db->fetch($res);
		} else {
			return false;
		}
	}
	
	function getProducts($id_cat=0,$visible=1,$in_category=false,$page=0,$onpage=20,$str='',$sort="date",$desc="DESC") {
		global $db;
		if ($visible) {
			$vis=" and visible=1";
		} else {
			$vis="";
		}
		if ($in_category) {
		$sql_dop="and (";
		$this->generateSQLDop($id_cat,$sql_dop,true);
		$sql_dop.=")";
		} else {
		$sql_dop=" and `id_category`=$id_cat";
		}
		if (trim($str)!='') {
			$str_sql="and (`caption` like '".sql_quote($str)."' or `code` like '".sql_quote($str)."')";
		} else {
			$str_sql='';
		}
//		echo $sql_dop;
		$res=$db->query("select * from `%PRODUCTS%` where 1 $sql_dop $str_sql $vis order by $sort $desc LIMIT ".($page*$onpage).",$onpage");
		while ($row=$db->fetch($res)) {
			$products[$row["id_product"]]=$row;
			$products[$row["id_product"]]["picture"]=$this->getPictureFromProductID($row["id_product"]);
$products[$row["id_product"]]["price_print"]=$this->getPrintPrice($products[$row["id_product"]]["price1"]);
$products[$row["id_product"]]["price2_print"]=$this->getPrintPrice($products[$row["id_product"]]["price2"]);
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}
	
	function addImageToProduct($id_image,$id_product) {
		global $db;
		global $engine;
		if (preg_match("/^[0-9]{1,}$/i",$id_product) &&
			preg_match("/^[0-9]{1,}$/i",$id_image)
		) {
			$res=$db->query("select id_image from %PRODUCT_PICTURES% where id_image=$id_image and id_product=$id_product");
			if (mysql_num_rows($res)==0) {
				$object=$engine->getImageByID($id_image);
				$res=$db->query("select main_picture from %PRODUCT_PICTURES% where  id_product=$id_product and main_picture=1");
				if (mysql_num_rows($res)==0) {
					$main_picture=1;
				} else {
					$main_picture=0;
				}
				if ($db->query("insert into %PRODUCT_PICTURES% values ($id_product,$id_image,$main_picture,'".$object["small_photo"]."','".$object["medium_photo"]."','".$object["big_photo"]."',0)")) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function addVariant($id_product,$caption="",$description="",$price=0) {
		global $db;
		if ($db->query("insert into %PRODUCT_VARIANTS% values (null,$id_product,'".sql_quote($caption)."','".sql_quote($description)."',$price)")) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	function getVariants($id_product=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
			$res=$db->query("select * from %PRODUCT_VARIANTS% where id_product=$id_product order by `caption`");
			while ($row=$db->fetch($res)) {
				$variants[]=$row;
			}
			if (isset($variants)) return $variants;
			return false;
		} else {
			return false;
		}
	}
	
	function getVariantByID($id_variant=0) {
		global $db;
		if (preg_match("/^[0-9]{1,}$/i",$id_variant)) {
			$res=$db->query("select * from %PRODUCT_VARIANTS% where id_variant=$id_variant");
			return $db->fetch($res);
		} else {
			return false;
		}
	}
	
	function getCSVLevel(&$caption) {
		$level=substr_count($caption,'!');
		$caption=str_replace('!','',$caption);
		return $level;
	}
	
	/*Функции по работе с типами товаров*/
	function addType($caption='',$sql='') {
		global $db;
		//Функция добавления нового типа товара
		if ($db->query("insert into `%PRODUCT_TYPES%` values (null,'".sql_quote($caption)."'$sql)")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getTypeByID($id_type) {
		global $db;
		$res=$db->query("select * from `%PRODUCT_TYPES%` where id_type=$id_type");
		return $db->fetch($res);
	}
	
	function getProductTypes() {
		global $db;
		$res=$db->query("select * from `%PRODUCT_TYPES%` order by `caption` ASC");
		while ($row=$db->fetch($res)) {
			$types[]=$row;
		}
		if (isset($types)) {
			return $types;
		} else {
			return false;
		}
	}
	
	function addOption($id_type,$caption,$value,$in_order=0) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_type)) return false;
		if ($in_order!=1) $in_order=0;
		if ($db->query("insert into `%PRODUCT_OPTIONS%` values (null,$id_type,'".sql_quote($caption)."','".sql_quote($value)."',$in_order)")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getOptions($id_type) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_type)) return false;
		$res=$db->query("select * from `%PRODUCT_OPTIONS%` where id_type=$id_type");
		while ($row=$db->fetch($res)) {
			$options[]=$row;
		}
		if (isset($options)) {
			return $options;
		} else {
			return false;
		}
	}
	
	function getAllOptions($id_type) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_type)) return false;
		$res=$db->query("select * from `%PRODUCT_OPTIONS%` where id_type=$id_type");
		while ($row=$db->fetch($res)) {
			if ($row["values"]!='')
				$row["values_list"]=explode(chr(13),$row["values"]);
			$options[$row["show_in_order"]][$row["id_option"]]=$row;
		}
		if (isset($options)) {
			return $options;
		} else {
			return false;
		}
	}	
	
	function getTypesList() {
		global $db;
		global $lang;
		$res=$db->query("select * from `%PRODUCT_TYPES%` order by `caption`");
		$options[0]["id"]=0;
		$options[0]["name"]=$lang['product_no'];
		while ($row=$db->fetch($res)) {
			$option["id"]=$row["id_type"];
			$option["name"]=$row["caption"];
			$options[]=$option;
		}
		if (isset($options)) {
			return $options;
		} else {
			return false;
		}
	}
	/*Конец функций по работе с типами товаров*/
	
	function generatePricingSQL($filter) {
		//функция генерации выборки для наценки
		if (is_array($filter)) {
			if (sizeof($filter)>0) {
				$where='';
					foreach ($filter as $k=>$filt) {
						if ($k==0) {
							$where.='where '.$filt;
						} else {
							$where.=' and '.$filt;
						}
					}
				return $where;
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
	
	function getCountAllProductsEx() {
		global $db;
		$res=$db->query("select count(`id_product`) as `cnt` from `%PRODUCTS%`");
		$row=$db->fetch($res);
		return $row["cnt"];
	}
	
	function existFirm($caption) {
		global $db;
		if ($db->getCount("select `id_firm` from `%FIRMS%` where LOWER(`caption`)='".sql_quote(strtolower($caption))."'")>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function addFirm($caption='',$content='',$sql='') {
		global $db;
		if ($db->query("insert into `%FIRMS%` values(null,'".sql_quote($caption)."','".sql_quote($content)."'".$sql.")")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getAllFirms() {
		global $db;
		$res=$db->query("select * from `%FIRMS%` order by `caption` ASC");
		while ($row=@$db->fetch($res)) {
			$firms[]=$row;
		}
		if (isset($firms)) return $firms;
		return false;
	}
	
	function getFirmByID($id_firm) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_firm)) return false;
		$res=$db->query("select * from `%FIRMS%` where `id_firm`=$id_firm");
		return $db->fetch($res);
	}
	
	function existCollection($caption) {
		global $db;
		if ($db->getCount("select `id_collection` from `%COLLECTIONS%` where LOWER(`caption`)='".sql_quote(strtolower($caption))."'")>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function addCollection($caption='',$id_firm) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_firm)) return false;
		if ($db->query("insert into `%COLLECTIONS%` values(null,$id_firm,'".sql_quote($caption)."')")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getAllCollections($id_firm=0) {
		global $db;
		$where_sql='';
		if (preg_match("/^[0-9]{1,}$/i",$id_firm) && $id_firm>0) {
			$where_sql=" where `id_firm`=$id_firm";
		}
		$res=$db->query("select * from `%COLLECTIONS%` $where_sql order by `caption` ASC");
		while ($row=@$db->fetch($res)) {
			$collections[]=$row;
		}
		if (isset($collections)) return $collections;
		return false;
	}
	
	function getAllCollectionsEx() {
		global $db;
		$res=$db->query("select * from `%COLLECTIONS%` order by `caption` ASC");
		while ($row=@$db->fetch($res)) {
			$collections[$row["id_firm"]][]=$row;
		}
		if (isset($collections)) return $collections;
		return false;
	}
	
	function getCollectionByID($id_collection) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_collection)) return false;
		$res=$db->query("select * from `%COLLECTIONS%` where `id_collection`=$id_collection");
		return $db->fetch($res);
	}
	
	function deleteCollections($id_firm) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_firm)) return false;
		if ($db->query("delete from `%COLLECTIONS%` where `id_firm`=$id_firm")) {
			return true;
		} else {
			return false;
		}
	}
	
	function deleteFirm($id_firm) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_firm)) return false;
		if ($db->query("delete from `%FIRMS%` where `id_firm`=$id_firm")) {
			$this->deleteCollections($id_firm);
			return true;
		} else {
			return false;
		}
	}
	
	//Работа с фирмами и коллекциями
	//Функция получения фирм и коллекций для вставки в форму добавления\редактирования товара
	function getFirmsAndCollections() {
		global $db;
		global $lang;
		$collections=$this->getAllCollectionsEx();
		$firms=$this->getAllFirms();
		$n=0;
		$values[$n]["id"]='0:0';
		$values[$n]["name"]=$lang["collections"]["not_change"];
		if (is_array($firms)) {
			foreach ($firms as $firm) {
				$n++;
				$values[$n]["id"]=$firm["id_firm"].':0';
				$values[$n]["name"]=$firm["caption"];
				if (isset($collections[$firm["id_firm"]])) {
					foreach ($collections[$firm["id_firm"]] as $collection) {
						$n++;
						$values[$n]["id"]=$firm["id_firm"].':'.$collection["id_collection"];
						$values[$n]["name"]=$firm["caption"].' / '.$collection["caption"];
					}
				}
			}
		}
		return $values;
	}
	
	function parseCollection($collection) {
		if (preg_match("/^[0-9:0-9]{1,}$/i",$collection)) {
			$item=explode(':',$collection);
			if (!isset($item[0])) {
				$item[0]=0;
			} else {
				if (!preg_match("/^[0-9]{1,}$/i",$item[0])) $item[0]=0;
			}
			if (!isset($item[1])) {
				$item[1]=0;
			} else {
				if (!preg_match("/^[0-9]{1,}$/i",$item[1])) $item[1]=0;
			}
		} else {
			$item[0]=0;
			$item[1]=0;
		}
		return $item;
	}
	
	/*управление акциями*/
	function getActionByID($id_action) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_action)) return false;
		$res=$db->query("select * from `%ACTIONS%` where `id_action`=$id_action");
		return $db->fetch($res);
	}
	
	function getActionByCategory($id_cat) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_cat)) return false;
		$res=$db->query("select * from `%ACTIONS%` where `id_category`=$id_cat");
		return $db->fetch($res);
	}
	
	function getActions() {
		global $db;
		$res=$db->query("select * from `%ACTIONS%` order by `caption`");
		while ($row=@$db->fetch($res)) {
			$actions[]=$row;
		}
		if (isset($actions)) {
			return $actions;
		} else {
			return false;
		}
	}
	
	function addAction($id_cat,$caption='',$content='',$sql='') {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_cat)) return false;
		if ($db->query("insert into `%ACTIONS%` values(null,$id_cat,'".sql_quote($caption)."','".sql_quote($content)."' $sql)")) {
			return @mysql_insert_id();
		} else {
		echo mysql_Error();
			return false;
		}
	}
	
	function existCategoryAction($id_cat) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_cat)) return false;
		if ($db->getCount("select `id_action` from `%ACTIONS%` where `id_category`=$id_cat")>0) {
			return true;
		} else {
			return false;
		}
	}
	
	function getProductsByAction($id_action) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_action)) return false;
		$res=$db->query("select `%PRODUCTS%`.* from `%PRODUCTS%`,`%ACTION_PRODUCTS%` where `%ACTION_PRODUCTS%`.`id_action`=$id_action and `%PRODUCTS%`.id_product=`%ACTION_PRODUCTS%`.id_product");
		while ($row=$db->fetch($res)) {
			$products[$row["id_product"]]=$row;
		}
		if (isset($products)) {
			return $products;
		} else {
			return false;
		}
	}
	
	function getProductActions($id_product) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		$res=$db->query("select * from `%ACTION_PRODUCTS%` where `id_product`=$id_product");
		while ($row=$db->fetch($res)) {
			$actions[$row["id_action"]]=true;
		}
		if (isset($actions)) {
			return $actions;
		} else {
			return false;
		}
	}
	
	function clearProductActions($id_product) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_product)) return false;
		if ($db->query("delete from `%ACTION_PRODUCTS%` where `id_product`=$id_product")) {
			return true;
		} else {
			return false;
		}
	}
	
	function addActionsToProduct($actions,$id_product) {
		global $db;
		if (is_array($actions)) {
			foreach ($actions as $id_action=>$action) {
				$db->query("insert into `%ACTION_PRODUCTS%` values($id_action,$id_product)");
			}
			return true;
		} else {
			return false;
		}
	}
	
	function deleteAllActionsFromBlock($id_block) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		if ($db->query("delete from `%BLOCK_ACTIONS%` where `id_block`=$id_block")) {
			return true;
		} else {
			return false;
		}
	}
	
	function getActionByBlock($id_block) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		$res=$db->query("select `id_action` from `%BLOCK_ACTIONS%` where `id_block`=$id_block");
		$row=$db->fetch($res);
		if (isset($row["id_action"])) {
			return $row["id_action"];
		} else {
			return false;
		}
	}

	function getActionArrayByBlock($id_block) {
		global $db;
		if (!preg_match("/^[0-9]{1,}$/i",$id_block)) return false;
		$res=$db->query("select `%ACTIONS%`.* from `%ACTIONS%`,`%BLOCK_ACTIONS%` where `%BLOCK_ACTIONS%`.`id_block`=$id_block and `%ACTIONS%`.id_action=`%BLOCK_ACTIONS%`.id_action");
		$row=$db->fetch($res);
		if (isset($row["id_action"])) {
			return $row;
		} else {
			return false;
		}
	}
}
?>
