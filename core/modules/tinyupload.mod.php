<?
if (defined("SCRIPTO_GALLERY")) {
$mode=@$_REQUEST["mode"];
if (isset($_FILES['image']))
{

	ini_set("upload_max_filesize", "100M");
	$filename=charset_x_win(strtolower($_FILES['image']['name']));
	$pos=0;
	$file_ext=getFileExt($filename, $pos);
	$exts=array("php","php4","php5","phps");
	if (in_array(strtolower($file_ext),$exts)) {
		die("wrong_format");
	}
	if (!preg_match("/^[^à-ÿÀ-ß]{1,}$/i",$filename)) {
		$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
	}
	$error = false;
	$type=$this->getFileType($filename);
	$caption=$filename;
	switch ($type) {
		case "image":
			$upload_path=$config["pathes"]["tiny_mce"];
			while (is_file($upload_path.$filename)==true) {
				$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
			}
			@copy($_FILES['image']['tmp_name'],$upload_path.$filename);
			die($config["pathes"]["tiny_mce_http"].$filename);
		break;
		default:
			if ($mode=="file") {
				$upload_path=$config["pathes"]["tiny_mce"];
				while (is_file($upload_path.$filename)==true) {
					$filename=rand(1,10000000).rand(1,10000000).rand(1,10000000).'.'.$file_ext;
				}
				@copy($_FILES['image']['tmp_name'],$upload_path.$filename);
				die($config["pathes"]["tiny_mce_http"].$filename);
			} else {
				die("wrong_format");
			}
	}
}
}
die("error");
?>