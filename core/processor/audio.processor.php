<?
global $page;
	$smarty->assign("preview_full",false);
	if (isset($_REQUEST["id_audio"])) {
		$id_audio=@$_REQUEST["id_audio"];
		if (preg_match("/^[0-9]{1,}$/i",$id_audio)) {
			$audio=$this->getAudioByID($id_audio);
			$smarty->assign("audio",$audio);
			$this->addSubPath($audio["caption"],$page["url"]);
			$page["title"]=$audio["title"];
			$page["meta"]=$audio["meta"];
			$page["caption"]=$audio["caption"];
			if ($this->checkInstallModule("comments")) {
				$smarty->assign("comments_install",true);
				$smarty->assign("comment_type","audio");
				$smarty->assign("object_id",$id_audio);
				$cmm=new Comments();
				$cmm->doDb();
				$comments=$cmm->getCommentsByObject($id_audio,"audio");
				$smarty->assign("comments",$comments);
			}
		}
	} else {
	if ($page["main_page"]) {
	$audios=$this->getAudioByCat($page["id_category"],true,true);
	} else {
	//страницы
	$onpage=$config["category"]["onpage_tracks"];
	$max=$this->getCountAudioByCat($page["id_category"],true);
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
	$audios=$this->getAudioByCat($page["id_category"],true,false,$pg,$onpage);
	}
	if (isset($audios)) {
	$smarty->assign("audios",$audios);
	}
	}
	$content["audio"]=$smarty->fetch($pth2.$c["template_client"]);
	$content["text"]=$page["content"];
?>