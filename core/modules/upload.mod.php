<?
//модуль загрузки фотографий в скрипт
if (defined("SCRIPTO_GALLERY")) {
	$module["title"]=$lang["modules"]["upload"];
	$smarty->assign("doUpload",true);
	$secretkey_original=md5($config["secretkey"]."scripto_gallery".$settings["login"]);
	$secretkey=@$_REQUEST["secretkey"];
if (isset($_FILES['photoupload']) && $secretkey==$secretkey_original)
{
	ini_set("upload_max_filesize", "100M");
	$filename=charset_x_win(strtolower($_FILES['photoupload']['name']));
	$pos=0;
	$file_ext=getFileExt($filename, $pos);
	if (!preg_match("/^[^а-€ј-я]{1,}$/i",$filename)) {
		$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
	}
	$error = false;
	$type=$this->getFileType($filename);
	$caption=$filename;
	switch ($type) {
		case "image":
			$upload_path=$config["pathes"]["user_image"];
		break;
		case "video":
			$upload_path=$config["pathes"]["user_video"];
		break;
		case "flash":
			$upload_path=$config["pathes"]["user_flash"];
		break;
		case "music":
			$upload_path=$config["pathes"]["user_music"];
		break;
		default:
		$error = '403';
		die('wrong_format');
	}
	if (!$error && !is_writable($upload_path)) {
		$error="402";
	}
	if ($error)
	{
		die('error');
	}
		while (is_file($upload_path.$filename)==true) {
			$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
		}
		@copy($_FILES['photoupload']['tmp_name'],$upload_path.$filename);
		$object_id=$this->insertNewObject($caption,$filename,$type,$file_ext);
		$object=$this->getObjectByID($object_id);
				 if (isset($_REQUEST["id_cat"])) {
					 	$id_cat=$_REQUEST["id_cat"];
					 	if (preg_match("/^[0-9]{1,}$/i",$id_cat)) {
							$category=$this->getCategoryByID($id_cat);
								 $id_image=$this->copyObject($object_id,$id_cat,true,$category["preview_width"],$category["preview_height"]);
								 if ($id_image>0) {
									$this->deleteObject($object_id);
									$object["id_image"]=$id_image;
								}
					 	}
					 }
		
	die(json_encode($object));
}
}
?>