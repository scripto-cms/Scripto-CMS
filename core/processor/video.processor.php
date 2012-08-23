<?
global $page;
if (isset($_REQUEST["video_id"])) {
	$this->setFullMode();
	$video_id=@$_REQUEST["video_id"];
	if (preg_match("/^[0-9]{1,}$/i",$video_id)) {
		$video=$this->getVideoByID($video_id);
		$smarty->assign("video",$video);
		$this->addSubPath($video["caption"],$page["url"]);
		$page["title"]=$video["title"];
		$page["meta"]=$video["meta"];
		$page["caption"]=$video["caption"];
		$smarty->assign("object",$video);
		if ($page["main_page"]) {
		$videos=$this->getVideoByCat($page["id_category"],true,true);
		} else {
		$videos=$this->getVideoByCat($page["id_category"],true);
		}
		$n=0;
		$videos_full=array();
		$next_video=false;
		$n_video=-1;
		foreach ($videos as $key=>$vid) {
			if ($next_video) {
				$next_video=false;
				$n_video=$key;
			}		
			if ($video["id_video"]==$vid["id_video"]) {
				$number=$n;
				$next_photo=true;
			}
			$videos_full[]=$vid["id_video"];
			$n++;
		}
		if (isset($videos[$n_video]["id_video"])) {
			$smarty->assign("next_video",$videos[$n_video]["id_video"]);
		}
		if (($number-$config["fullmode"]["on_page"])<0) {
			$min=0;
		} else {
			$min=$number-$config["fullmode"]["on_page"];
		}
		if (($number+$config["fullmode"]["on_page"]+1)>sizeof($videos)) {
			$max=sizeof($videos);
				if (($number-$config["fullmode"]["on_page"]*2)<0) {
					$min=0;
				} else {
					$min=$number-$config["fullmode"]["on_page"]*2;
				}
		} else {
			$max=$number+$config["fullmode"]["on_page"]+1;
			if ($min==0) {
				if (($number+$config["fullmode"]["on_page"]*2+1)>sizeof($videos)) {
					$max=sizeof($videos);
				} else {
					$max=$number+$config["fullmode"]["on_page"]*2+1;
				}
			}
		}
		$videos=array_slice($videos,$min,$max-$min);
		if ($this->checkInstallModule("comments")) {
			$smarty->assign("comments_install",true);
			$smarty->assign("comment_type","video");
			$smarty->assign("object_id",$video_id);
			$cmm=new Comments();
			$cmm->doDb();
			$comments=$cmm->getCommentsByObject($video_id,"video");
			$smarty->assign("comments",$comments);
		}
		$smarty->assign("videos",$videos);
		$smarty->assign("videos_full",$videos_full);
		$smarty->assign("max_videos",sizeof($videos));
	}
} else {
	$smarty->assign("preview_full",false);
	if ($page["main_page"]) {
	$videos=$this->getVideoByCat($page["id_category"],true,true);
	} else {
	//страницы
	$onpage=$config["category"]["onpage_videos"];
	$max=$this->getCountVideoByCat($page["id_category"],true);
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
	$videos=$this->getVideoByCat($page["id_category"],true,false,$pg,$onpage);
	}
	if (isset($videos)) {
	$smarty->assign("videos",$videos);
	}
}
	$content["video"]=$smarty->fetch($pth2.$c["template_client"]);
	$content["text"]=$page["content"];
?>