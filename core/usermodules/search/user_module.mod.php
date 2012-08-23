<?
//Модуль поиск по сайту, пользовательская часть
global $engine;
$cprefix=$engine->current_prefix;
$str=trim(strip_tags(sql_quote(@$_REQUEST["str"])));
if ($str!='') {
$items=array();
$res=$db->query("select * from %categories% where (LOWER(`caption$cprefix`) LIKE '%$str%' or LOWER(`content$cprefix`) LIKE '%$str%') and `visible`=1");
	while ($row=$db->fetch($res)) {
		$item=$row;
		//$item["url"]=$engine->generateCategoryUrl($item["main_page"],$item["ident"]);
		$items[]=$item;
	}
$smarty->assign("items",$items);
$smarty->assign("count",sizeof($items));
$dop_objects=array();
/*поиск по модулям*/
$modules=$engine->getInstallModulesFast();
	foreach ($modules as $key=>$module) {
		if (@$module["name"]!=false) {
		$mod=$engine->includeModule($engine->getModule($module["name"]));
			if (method_exists($mod,'doUserSearch')) {
				$mod_items=$mod->doUserSearch($str,$cprefix);
				if (is_array($mod_items)) {
					$dop_objects=array_merge($dop_objects,$mod_items);
				}
			}
		}
	}
$smarty->assign("dop_objects",$dop_objects);
$smarty->assign("count_objects",sizeof($dop_objects));
/*поиск по модулям*/
}
$url=$engine->getModuleFullViewUrl("search");
$smarty->assign("search_url",$url);
$smarty->assign("str",$str);
?>
