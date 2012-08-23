<?
global $page;
if (isset($_REQUEST["photo_id"])) {
	$this->setFullMode();
	$photo_id=@$_REQUEST["photo_id"];
	if (preg_match("/^[0-9]{1,}$/i",$photo_id)) {
		$photo=$this->getImageByID($photo_id);
		$this->addSubPath($photo["caption"],$page["url"]);
		$page["title"]=$photo["title"];
		$page["meta"]=$photo["meta"];
		$page["caption"]=$photo["caption"];
		$smarty->assign("photo",$photo);
		$smarty->assign("object",$photo);
		if ($page["main_page"]) {
		$images=$this->getImagesByCat($page["id_category"],true,true);
		} else {
		$images=$this->getImagesByCat($page["id_category"],true);
		}
		$n=0;
		$images_full=array();
		$next_photo=false;
		$n_photo=-1;
		foreach ($images as $key=>$image) {
			if ($next_photo) {
				$next_photo=false;
				$n_photo=$key;
			}		
			if ($photo["id_photo"]==$image["id_photo"]) {
				$number=$n;
				$next_photo=true;
			}
			$images_full[]=$image["id_photo"];
			$n++;
		}
		if (isset($images[$n_photo]["id_photo"])) {
			$smarty->assign("next_photo",$images[$n_photo]["id_photo"]);
		}
		if (($number-$config["fullmode"]["on_page"])<0) {
			$min=0;
		} else {
			$min=$number-$config["fullmode"]["on_page"];
		}
		if (($number+$config["fullmode"]["on_page"]+1)>sizeof($images)) {
			$max=sizeof($images);
				if (($number-$config["fullmode"]["on_page"]*2)<0) {
					$min=0;
				} else {
					$min=$number-$config["fullmode"]["on_page"]*2;
				}
		} else {
			$max=$number+$config["fullmode"]["on_page"]+1;
			if ($min==0) {
				if (($number+$config["fullmode"]["on_page"]*2+1)>sizeof($images)) {
					$max=sizeof($images);
				} else {
					$max=$number+$config["fullmode"]["on_page"]*2+1;
				}
			}
		}
		$images=array_slice($images,$min,$max-$min);
		if ($this->checkInstallModule("comments")) {
			$smarty->assign("comments_install",true);
			$smarty->assign("comment_type","image");
			$smarty->assign("object_id",$photo_id);
			$cmm=new Comments();
			$cmm->doDb();
			$comments=$cmm->getCommentsByObject($photo_id,"image");
			$smarty->assign("comments",$comments);
		}
		$smarty->assign("images",$images);
		$smarty->assign("images_full",$images_full);
		$smarty->assign("max_images",sizeof($images));
	}
} else {
	$smarty->assign("preview_full",false);
	if ($page["main_page"]) {
	$images=$this->getImagesByCat($page["id_category"],true,true);
	} else {
	//страницы
	$onpage=$config["category"]["onpage_images"];
	$max=$this->getCountImagesByCat($page["id_category"],true);
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
	$images=$this->getImagesByCat($page["id_category"],true,false,$pg,$onpage);
	}
	$smarty->assign("images",$images);
}
	$content["image"]=$smarty->fetch($pth2.$c["template_client"]);
	$content["text"]=$page["content"];
?>