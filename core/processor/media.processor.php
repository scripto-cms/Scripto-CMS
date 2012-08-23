<?
	$smarty->assign("preview_full",false);
	if ($page["main_page"]) {
	$media=$this->getMediaByCat($page["id_category"],true,true);
	} else {
	//страницы
	$onpage=$config["category"]["onpage_media"];
	$max=$this->getCountMediaByCat($page["id_category"],true);
	if (isset($_REQUEST["pg"])) {
		$pg=$_REQUEST["pg"];
		if (!preg_match("/^[0-9]{1,}$/i",$pg))
			$pg=0;
	} else {
		$pg=0;
	}
	if ($onpage>0) {
	$page_count=ceil($max/$onpage);
	if ($pg>$page_count) $pg=$pages;
	
	for ($j=0;$j<$page_count;$j++) 
		$pages[$j]=$j;
	if (isset($pages)) {
	$smarty->assign("pages",$pages);
	$smarty->assign("pg",$pg);
	}
	}
	$media=$this->getMediaByCat($page["id_category"],true,false,$pg,$onpage);
	}
	$smarty->assign("media",$media);
	$content["media"]=$smarty->fetch($pth2.$c["template_client"]);
	$content["text"]=$page["content"];
?>