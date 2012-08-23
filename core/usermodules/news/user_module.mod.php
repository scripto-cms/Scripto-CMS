<?
//ћодуль новостей, пользовательска€ часть
global $page;
$full_view=false;
if (isset($_REQUEST["id_news"])) {
	$id_news=$_REQUEST["id_news"];
	if (preg_match("/^[0-9]{1,}$/i",$id_news)) {
		$news=$this->getNewsById($id_news);
		$smarty->assign("news",$news);
		$engine->addSubPath($news["caption"],$page["url"]);
		$page["caption"]=$news["caption"];
		$full_view=true;
		if ($engine->checkInstallModule("comments") && $this->thismodule["comments"]) {
			$smarty->assign("comments_install",true);
			$smarty->assign("comment_type","news");
			$smarty->assign("object_id",$id_news);
			$cmm=new Comments();
			$cmm->doDb();
			$comments=$cmm->getCommentsByObject($id_news,"news");
			$smarty->assign("comments",$comments);
		}
		$page["title"]=$news["caption"];
		$page["meta"]=$news["meta"];
		$page["keywords"]=$news["keywords"];
	}
} 

if ($full_view==false) {
			$onpage=$this->thismodule["onpage"];
			$count=$this->getAllNewsCount();
			$pages=ceil($count/$onpage);
			for ($x=0;$x<=$pages-1;$x++) $pages_arr[]=$x;
			if (isset($_REQUEST["p"])) {
					$pg=@$_REQUEST["p"];
					if (!preg_match("/^[0-9]{1,}$/i",$pg)) $pg=0;
					if ($pg>=$pages) $pg=0;
					if ($pg<0) $pg=0;
			} else {
				$pg=0;
			}
	$news=$this->getAllNews($pg,$onpage);
	$smarty->assign("news",$news);
	if (isset($pages_arr)) {
	$smarty->assign("pages",$pages_arr);
	$smarty->assign("pg",$pg);
	}
}
$smarty->assign("full_view",$full_view);
?>