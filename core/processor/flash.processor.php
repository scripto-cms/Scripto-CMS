<?
global $page;
if (isset($_REQUEST["flash_id"])) {
	$this->setFullMode();
	$flash_id=@$_REQUEST["flash_id"];
	if (preg_match("/^[0-9]{1,}$/i",$flash_id)) {
		$flash=$this->getFlashByID($flash_id);
		$smarty->assign("flash",$flash);
		$this->addSubPath($flash["caption"],$page["url"]);
		$page["title"]=$flash["title"];
		$page["meta"]=$flash["meta"];
		$page["caption"]=$flash["caption"];
		$smarty->assign("object",$flash);
		if ($this->checkInstallModule("comments")) {
			$smarty->assign("comments_install",true);
			$smarty->assign("comment_type","flash");
			$smarty->assign("object_id",$flash_id);
			$cmm=new Comments();
			$cmm->doDb();
			$comments=$cmm->getCommentsByObject($flash_id,"flash");
			$smarty->assign("comments",$comments);
		}
	}
} else {
	$smarty->assign("preview_full",false);
	if ($page["main_page"]) {
	$flashes=$this->getFlashByCat($page["id_category"],true,true);
	} else {
	//страницы
	$onpage=$config["category"]["onpage_flash"];
	$max=$this->getCountFlashByCat($page["id_category"],true);
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
	$flashes=$this->getFlashByCat($page["id_category"],true,false,$pg,$onpage);
	}
	if (isset($flashes)) {
	$smarty->assign("flashes",$flashes);
	}
}
	$content["flash"]=$smarty->fetch($pth2.$c["template_client"]);
	$content["text"]=$page["content"];
?>