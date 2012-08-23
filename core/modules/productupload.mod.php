<?
//модуль загрузки фотографий в скрипт
if (defined("SCRIPTO_GALLERY")) {
	$products=new Products();
	$products->doDB();
	$module["title"]=$lang["modules"]["upload"];
	$smarty->assign("doUpload",true);
	$secretkey_original=md5($config["secretkey"]."scripto_gallery".$settings["login"]);
	$secretkey=@$_REQUEST["secretkey"];
	$id_product=@$_REQUEST["id_product"];
if (preg_match("/^[0-9]{1,}$/i",$id_product)) {
$product=$products->getProductByID($id_product);
if (isset($_FILES['photoupload']) && $secretkey==$secretkey_original)
{
	ini_set("upload_max_filesize", "100M");
	$filename=charset_x_win(strtolower($_FILES['photoupload']['name']));
	$pos=0;
	$file_ext=getFileExt($filename, $pos);
	$error = false;
	$type=$this->getFileType($filename);
	switch ($type) {
		case "image":
			$upload_path=$config["pathes"]["user_image"];
			$caption=$filename;
		break;
		default:
		$error = '403';
		echo('HTTP/1.0 ' . $error);
		die('Error ' . $error);
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
		$id_object=$this->insertNewObject($caption,$filename,$type,$file_ext);
			$id_photo=$this->copyObject($id_object,$product["id_category"],true);
			if ($id_photo>0) {
				$products->addImageToProduct($id_photo,$id_product);
				$this->deleteObject($id_object);
			}
		}
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