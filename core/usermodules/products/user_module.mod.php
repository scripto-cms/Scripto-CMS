<?
//Модуль товары, пользовательская часть
global $engine;
global $page;
if ($engine->checkInstallModule("basket")) {
if ($this->thismodule["shop_on"]) {
	 $lng='';
	 if (isset($engine->languages[$engine->current_language])) {
	 	if ($engine->languages[$engine->current_language]["default"]==0) {
				$lng=$engine->current_language.'/';
		}
	 }
	$smarty->assign("shop_on","yes");
	$bskt=$engine->getModule('basket');
	$bskt["basket_url"]=$lng.'/'.$bskt["basket_url"];
	$smarty->assign("bskt",$bskt);
}
}
if (isset($_REQUEST["productID"])) {
global $rubrics;
	//просмотр товара
	$productID=$_REQUEST["productID"];
	if (preg_match("/^[0-9]{1,}$/i",$productID)) {
		$product=$this->getProductByID($productID);
		$smarty->assign("product",$product);
		if ($product["id_category"]==$page["id_category"]) {
		$options=$this->getAllOptions($product["id_type"]);
		$smarty->assign("options",$options);
$db->query("update `%PRODUCTS%` set views=views+1 where id_product=".$product["id_product"]);
$engine->addSubPath($page["caption"],$page['url']);
$page["caption"]=$product["caption"];
		$recommended_products=$this->getProductsByProduct($productID,true,0);
		if (is_array($recommended_products)) {
			foreach ($recommended_products as $key=>$prod)
				foreach ($rubrics as $position) {
					foreach ($position as $rubr) {
						if ($prod["id_category"]==$rubr["id_category"]) {
							$recommended_products[$key]["product_url"]=$rubr["url"];
							$recommended_products[$key]["rand1"]=rand(150,250);
							$recommended_products[$key]["rand2"]=rand(12,18);
							break;break;
						}
					}
				}
		$smarty->assign("recommended_products",$recommended_products);
		}
		$variants=$this->getVariants($product["id_product"]);
		$smarty->assign("variants",$variants);
		$page["title"]=$product["caption"];
		$page["meta"]=$product["meta"];
		$page["keywords"]=$product["keywords"];
		}
	}
} else {
	if (isset($page["additional"]["favorite_url"])) {
		//страница сравнения товаров
		if (isset($_REQUEST["add"])) {
			if (isset($_REQUEST["id_product"])) {
				$id_product=$_REQUEST["id_product"];
				if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
					$_SESSION["favorites"][$id_product]=true;
				}
			}
		}
		if (isset($_REQUEST["remove"])) {
			if (isset($_REQUEST["id_product"])) {
				$id_product=$_REQUEST["id_product"];
				if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
					$_SESSION["favorites"][$id_product]=false;
				}
			}
		}
		$products=$this->getFavoriteProducts();
		$smarty->assign("products",$products);
		$smarty->assign("compare",true);
	} else {
	if (isset($page["additional"]["brand_page"])) {
		if (isset($_REQUEST["id_firm"])) {
			$id_firm=$_REQUEST["id_firm"];
			if (preg_match("/^[0-9]{1,}$/i",$id_firm)) {
				$firm=$this->getFirmByID($id_firm);
				$smarty->assign("firm",$firm);
				$collections=$this->getAllCollections($id_firm);
				$smarty->assign("collections",$collections);
				$page["caption"]=$firm["caption"];
				$filter["id_firm"]=$id_firm;
				if (isset($_REQUEST["id_collection"])) {
					$id_collection=@$_REQUEST["id_collection"];
					if (preg_match("/^[0-9]{1,}$/i",$id_collection)) {
						$coll=$this->getCollectionByID($id_collection);
						$smarty->assign("coll",$coll);
						$engine->addSubPath($firm["caption"],$page["url"].'?id_firm='.$firm["id_firm"]);
						$engine->addSubPath($firm["caption"],$page["url"].'?id_firm='.$firm["id_firm"]);
						$page["caption"]=$firm["caption"].' / '.$coll["caption"];
						$filter["id_collection"]=$id_collection;
					}
				}
			}
		}
	} elseif (isset($page["additional"]["actions_page"])) {
		//акционная страница
		$action=$this->getActionByCategory($page["id_category"]);
		$smarty->assign("action",$action);
		$filter["id_action"]=$action["id_action"];
	} elseif (isset($page["additional"]["type_url"])) {
		//просмотр по типу товара
			$id_type=$_REQUEST["id_type"];
			if (preg_match("/^[0-9]{1,}$/i",$id_type)) {
				$type=$this->getTypeByID($id_type);
				$smarty->assign("type",$type);
				$page["caption"]=$type["caption"];
				$filter["id_type"]=$id_type;
			}
	} else {
		$filter["id_category"]=$page["id_category"];
	}
					$filter["visible"]=1;
					$onpage=$this->thismodule["onpage"];
					if ($this->thismodule["do_subcategory"]) {
					$count=$this->filterProductsCount($filter,true);
					} else {
					$count=$this->filterProductsCount($filter);
					}
					$pages=ceil($count/$onpage);
					$pages_arr=array();
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
					if (isset($_REQUEST["sort"])) {
						$sort=$_REQUEST["sort"];
							switch ($sort) {
								case "caption":
									$srt="caption";
								break;
								case "price":
									$srt="price1";
								break;
								default:
									$srt="sort";
									$sort="sort";
							}
					} else {
						$srt="sort";
						$sort="sort";
					}
					$smarty->assign("sort",$sort);
					$dsc="";
					if (isset($_REQUEST["desc"])) {
						$desc=$_REQUEST["desc"];
						if ($desc=="yes") {
							$desc="ASC";
							$dsc="yes";
						} else {
							$desc="DESC";
						}
					} else {
						$desc="DESC";
					}
					$smarty->assign("desc",$dsc);
					if ($this->thismodule["do_subcategory"]) {
					$products=$this->filterProducts($filter,true,$pg,$onpage,$srt,$desc);
					} else {
					$products=$this->filterProducts($filter,false,$pg,$onpage,$srt,$desc);
					}
					if (is_array($pages_arr)) {
						$smarty->assign("products",$products);
						$smarty->assign("pages",$pages_arr);
						$smarty->assign("pg",$pg);
						$smarty->assign("mp",sizeof($pages_arr));
					}
	}
}
?>