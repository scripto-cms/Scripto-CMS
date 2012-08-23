<?
/*
Модуль теги, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
if (isset($_REQUEST["save"])) {
	$del=@$_REQUEST["del"];
	if (is_array($del)) {
	$d=0;
		foreach ($del as $id_tag=>$tag) {
			$db->query("delete from `%TAGS%` where id_tag=$id_tag");
			$db->query("delete from `%TAG_OBJECTS%` where id_tag=$id_tag");
			$d++;
		}
		$engine->clearCacheBlocks($this->thismodule["name"]);
		$engine->setCongratulation('Данные сохранены','Удалено '.$d.' тегов',5000);
	}
}
$count=$this->getTagsCount();
$onpage=$this->thismodule["onpage"];
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
$tags=$this->getTagsFromBase($pg,$onpage,true);
$smarty->assign("tags",$tags);

?>