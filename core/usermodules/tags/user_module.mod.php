<?
//Модуль теги, пользовательская часть
global $page;
global $rubrics;

//получение тега
if (isset($_REQUEST["t"])) {
	$tag=urldecode($_REQUEST["t"]);
	$smarty->assign("tag",$tag);
	//получаем список объектов
$info=$this->getTagsCountByTag($tag);
$count=$info["cnt"];
$id_tag=$info["id_tag"];

$onpage=$this->thismodule["onpage_user"];
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
					if (isset($pages_arr)) {
					$smarty->assign("pages",$pages_arr);
					$smarty->assign("pagenumber",$pg);
					}
$tags=$this->getTagsByTag($pg,$onpage,$id_tag);
if (is_array($tags)) {
$rs=array();
$objects=array();
	foreach ($tags as $type=>$tag_object) {
		 unset($rs);
		 foreach ($tag_object as $t) {
			 $rs[]=$t["id_object"];
		 }
		if ($type=="category") {
		 if (isset($rs))
		 	foreach ($rubrics as $pos)
				foreach ($pos as $rubr) {
					if (in_array($rubr["id_category"],$rs)) {
						$obj["caption"]=$rubr["caption"];
						$obj["description"]=$rubr["subcontent"];
						$obj["url"]=$rubr["url"];
						$obj["picture"]=$rubr["preview"];
						$objects[]=$obj;
					}
				}
		} else {
			$mod=$engine->includeModule($engine->getModule($type));
			if (method_exists($mod,'doTags')) {
				if (isset($rs)) {
					$objects_new=$mod->doTags($rs);
					if (is_array($objects_new))
						$objects=array_merge($objects,$objects_new);
				}
			}
		}
	}
	if (isset($objects)) {
		$smarty->assign("objects",$objects);
		$smarty->assign("tags",$tags);
	}
}
}
?>