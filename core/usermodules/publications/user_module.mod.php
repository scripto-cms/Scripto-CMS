<?
global $page;
if (isset($_REQUEST["publicationID"])) {
	//просмотр товара
	$publicationID=$_REQUEST["publicationID"];
	if (preg_match("/^[0-9]{1,}$/i",$publicationID)) {
		$publication=$this->getPublicationByID($publicationID);
		$smarty->assign("publication",$publication);
		$page["title"]=$publication["caption"];
		$page["meta"]=$publication["meta"];
		$page["keywords"]=$publication["keywords"];
		if ($engine->checkInstallModule("comments") && $this->thismodule["comments"]) {
			$smarty->assign("comments_install",true);
			$smarty->assign("comment_type","publications");
			$smarty->assign("object_id",$publicationID);
			$cmm=new Comments();
			$cmm->doDb();
			$comments=$cmm->getCommentsByObject($publicationID,"publications");
			$smarty->assign("comments",$comments);
		}
	}
} else {
	//просмотр товаров
					//$rubrics=$this->getCountAllProducts();
					$onpage=$this->thismodule["onpage"];
					$count=$this->getPublicationsCount($page["id_category"],true);
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
					$publications=$this->getPublications($page["id_category"],true,$pg,$onpage);
					if (is_array($pages_arr)) {
						$smarty->assign("publications",$publications);
						$smarty->assign("pages",$pages_arr);
						$smarty->assign("pg",$pg);
					}
}
?>