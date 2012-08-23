<?
if (defined("SCRIPTO_GALLERY")) {
	//$this->includeModule("documentation");
	$documentation=new Documentation();
	$documentation->doDB();
	$secretkey_original=md5($config["secretkey"]."scripto_gallery".$settings["login"]);
	$secretkey=@$_REQUEST["secretkey"];
	$id_category=@$_REQUEST["id_category"];
	$smarty->assign("doUpload",true);
if (isset($_FILES['photoupload']) && $secretkey==$secretkey_original && preg_match("/^[0-9]{1,}$/i",$id_category)) {
	ini_set("upload_max_filesize", "100M");
	$filename=charset_x_win(strtolower($_FILES['photoupload']['name']));
	//$filename=iconv('cp1251', 'utf-8', $filename);
	$pos=0;
	$exts=array("php","php4","php5","phps");
	$file_ext=getFileExt($filename, $pos);
	$error = false;
	$upload_path=$config["pathes"]["user_files"]."documentation/";
	if (in_array(strtolower($file_ext),$exts)) {
		$error="404";
	}
	if (!$error && !is_writable($upload_path)) {
		$error="402";
	}
	if ($error)
	{
		echo('HTTP/1.0 ' . $error);
		die('Error ' . $error);
	}
	if (!is_file($upload_path.$filename)) {	@copy($_FILES['photoupload']['tmp_name'],$upload_path.$filename);
		$documentation->addDocumentation($filename,$id_category);
	} else {
		$error = '401';
		echo('HTTP/1.0 ' . $error);
		die('Error ' . $error);
	}
	sleep(1);
	die('Upload Successfull');
}
}
?>