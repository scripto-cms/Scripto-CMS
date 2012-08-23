<?
//Модуль комментариев, пользовательская часть
global $page;
if (isset($_SESSION["auth"]) && isset($_SESSION["user_login"])) {
	if ($_SESSION["auth"]) {
		$smarty->assign("user_login",$_SESSION["user_login"]);
	}
}
if (isset($_REQUEST["submit_comment"])) {
	$comment_type=@$_REQUEST["comment_type"];
	if (preg_match("/^[a-zA-Z0-9]{1,}$/i",$comment_type)) {
		$comment_object=@$_REQUEST["comment_object"];
		if (preg_match("/^[0-9]{1,}$/i",$comment_object)) {
			//добавляем комментарий
			if (isset($_SESSION["auth"]) && isset($_SESSION["user_login"])) {
				if ($_SESSION["auth"]) {
					$nickname=$_SESSION["user_login"];
				} else {
					$nickname=@$_REQUEST["nickname"];
				}
			} else {
				$nickname=@$_REQUEST["nickname"];
			}
			$comment=strip_tags(@$_REQUEST["comment"]);
			$smarty->assign("comment_text",$comment);
			$kcaptha=@$_REQUEST["kcaptha"];
			if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] !=  @$kcaptha){
				$smarty->assign("kcaptha_error",true);
			} else {
			if ($this->addComment($comment_type,$comment_object,$nickname,$comment,1)) {
				$smarty->assign("comment_add",true);
			}
			}
		}
	}
}
?>