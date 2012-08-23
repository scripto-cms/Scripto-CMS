<?
	$smarty->assign("doHeader",true);
	if (is_file($config["pathes"]["admin_modules_dir"]."crop.mod.php")) {
		@include($config["pathes"]["admin_modules_dir"]."crop.mod.php");
	}
?>