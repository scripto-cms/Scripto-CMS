<?
/*
Модуль комментарии, управление
Версия модуля - 1.0
Разработчик - Иванов Дмитрий
*/
global $settings;
$m_action=@$_REQUEST["m_action"];
switch ($m_action) {
	default:
		$engine->addJS("/core/usermodules/basket/basket.js");
		$engine->assignJS();
		if (isset($_REQUEST["save"])) {
			$idcomment=@$_REQUEST["idcomment"];
			$new=@$_REQUEST["new"];
			$del=@$_REQUEST["del"];
			$d=0;
			$u=0;
			if (is_array($idcomment)) {
				foreach ($idcomment as $id_comment=>$comment) {
					if (isset($del[$id_comment])) {
						$db->query("delete from `%COMMENTS%` where id_comment=$id_comment");
						$d++;
					}
					if (isset($new[$id_comment])) {
						$db->query("update `%COMMENTS%` set `new`=0 where id_comment=$id_comment");
						$u++;
					}
				}
				$engine->setCongratulation("","Удалено $d комментариев, одобрено $u комментариев.",5000);
			}
		}
		$onpage=$this->thismodule["onpage_admin"];
		if (isset($_REQUEST["viewbyurl"])) {
		$url=strtolower(@$_REQUEST["url"]);
		$url=str_replace("www.","",$url);
		$r=str_replace("www.","",$settings["httproot"]);
		$url=str_replace($r,"",$url);
		$count=$this->getUrlCommentsCount($url);
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
		$comments=$this->getUrlComments($url,$pg,$onpage);
		$smarty->assign("comments",$comments);
		if (isset($pages_arr)) {
			$smarty->assign("pages",$pages_arr);
			$smarty->assign("pagenumber",$pg);
			$smarty->assign("url",$url);
		}
		$smarty->assign("viewbyurl",true);
		} elseif (isset($_REQUEST["viewbystr"])) {
		if (isset($_REQUEST["str"])) {
			$str_real=trim($_REQUEST["str"]);
			if (trim($str_real)!='') {
				$find=strpos($str_real,'*');
				if ($find===false) {
					$str='%'.$str_real.'%';
				} else {
					$str=str_replace('*','%',$str_real);
				}
			} else {
				$str_real='*';
				$str='*';
				$find=strpos($str_real,'*');
				if ($find===false) {
					$str='%'.$str_real.'%';
				} else {
					$str=str_replace('*','%',$str_real);
				}
			}
			$smarty->assign('str',$str_real);
			$smarty->assign('str_url',urlencode($str_real));
		} else {
			$str='';
		}
		$count=$this->getStrCommentsCount($str);
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
		$comments=$this->getStrComments($str,$pg,$onpage);
		$smarty->assign("comments",$comments);
		if (isset($pages_arr)) {
			$smarty->assign("pages",$pages_arr);
			$smarty->assign("pagenumber",$pg);
		}
		$smarty->assign("viewbystr",true);
		} else {
		$count=$this->getNewCommentsCount();
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
		$comments=$this->getNewComments($pg,$onpage);
		$smarty->assign("comments",$comments);
		if (isset($pages_arr)) {
			$smarty->assign("pages",$pages_arr);
			$smarty->assign("pagenumber",$pg);
		}
		}
	break;
}
$smarty->assign("m_action",$m_action);
?>