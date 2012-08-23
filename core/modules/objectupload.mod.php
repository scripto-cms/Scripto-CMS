<?
//модуль загрузки файлов для модуля объекты
if (defined("SCRIPTO_GALLERY")) {
	$smarty->assign("doUpload",true);
if (isset($_FILES['photoupload']))
{
	ini_set("upload_max_filesize", "100M");
	$filename=charset_x_win(strtolower($_FILES['photoupload']['name']));
	$pos=0;
	$exts=array("php","php4","php5","phps");
	$file_ext=getFileExt($filename, $pos);
	$error = false;
	$upload_path=$config["pathes"]["user_files"]."files/";
	if (isset($_REQUEST["id_object"])) {
		$id_object=@$_REQUEST["id_object"];
		if (preg_match("/^[0-9]{1,}$/i",$id_object)) {
			$objects=new Objects();
			$objects->doDb();
			$object=$objects->getObjectByID($id_object,$type);
		} else {
			die("wrong_id_object");
		}
	} else {
		die("wrong_id_object");
	}
	if (in_array(strtolower($file_ext),$exts)) {
		die("wrong_format");
	}
	if (!is_dir($upload_path)) {
		die("not exist");
	}
	if (!preg_match("/^[^а-яА-Я]{1,}$/i",$filename)) {
		$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
	}
		while (is_file($upload_path.$filename)==true) {
			$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
		}
		@copy($_FILES['photoupload']['tmp_name'],$upload_path.$filename);
		if ($objects->addFile($object["id_object"],$object["id_type"],$filename)) {
			$file=$objects->getFileByID(mysql_insert_id());
			die(json_encode($file));
		} else {
			die(mysql_error());
			die("err");
		}
}
}
?>