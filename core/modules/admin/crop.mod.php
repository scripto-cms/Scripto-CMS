<?
			/*crop изображений*/
			$smarty->assign("do_jCrop",true);
			if (isset($_REQUEST["id_photo"])) {
			$id_photo=@$_REQUEST["id_photo"];
			} else {
				if (isset($_REQUEST["filename_photo"])) {
					$filename_photo=$_REQUEST["filename_photo"];
					$id_photo=$this->getPhotoIdByFilename($filename_photo);
				}
			}
			if (preg_match("/^[0-9]{1,}$/i",$id_photo)) {
				$image=$this->getImageByID($id_photo);
				if ($image["id_category"] && is_file($config["pathes"]["user_image"].$image["big_photo"])) {
				$realsize=getimagesize($config["pathes"]["user_image"].$image["big_photo"]);
				//вырезаем
				if (isset($_REQUEST["doCrop"])) {
					$width=@$_REQUEST["width"];
					$height=@$_REQUEST["height"];
					$x1=@$_REQUEST["x"];
					$y1=@$_REQUEST["y"];
					$x2=@$_REQUEST["x2"];
					$y2=@$_REQUEST["y2"];
					$w=@$_REQUEST["w"];
					$h=@$_REQUEST["h"];
					$type=@$_REQUEST["previewlist"];
					$str='';
						if ($type=="medium") {
							$filename=$image["medium_photo"];
							$str="`medium_info`='$x1|$y1|$x2|$y2|$w|$h|$width|$height'";
						}
						if ($type=="small") {
							$filename=$image["small_photo"];
							$str="`small_info`='$x1|$y1|$x2|$y2|$w|$h|$width|$height'";	
						}
					if (preg_match("/^[0-9]{1,}$/i",$width) && preg_match("/^[0-9]{1,}$/i",$height)) {
						//работаем
						$thumb=$this->loadThumbnail($config["pathes"]["user_image"].$image["big_photo"]);
						$thumb->crop($x1,$y1,$w,$h);
						if ($w>$width && $h>$height) {
							$thumb->resize($width,$height);
						}
							if (is_writable($config["pathes"]["user_thumbnails"].$filename)) {	
								$thumb->save($config["pathes"]["user_thumbnails"].$filename,100);
								if ($db->query("update `%photos%` set $str where id_photo=".$image["id_photo"])) {
									$smarty->assign("cropSave",true);
									$image=$this->getImageByID($id_photo);
									$smarty->assign("image",$image);
								}
							}
					}
				}
					$cat=$this->getCategoryByID($image["id_category"]);
					$smarty->assign("cat",$cat);
					$do_width=true;
					if (isset($_REQUEST["width"])) {
						$width=$_REQUEST["width"];
						if (!preg_match("/^[0-9]{1,}$/i",$width) || $width==0) {
							$do_width=true;
						} else {
							$do_width=false;
						}
					}
					$do_height=true;
					if (isset($_REQUEST["height"])) {
						$height=$_REQUEST["height"];
						if (!preg_match("/^[0-9]{1,}$/i",$height) || $height==0) {
							$do_height=true;
						} else {
							$do_height=false;
						}
					}
					if ($do_width) {
						if ($cat["preview_width"]>0) {
							$width=$cat["preview_width"];
						} else {
							$width=$settings["small_x"];
						}
					}
					if ($do_height) {
						if ($cat["preview_height"]>0) {
							$height=$cat["preview_height"];
						} else {
							$height=$settings["small_y"];
						}
					}
					if ($width==0) {
						$width=$height;
					}
					if ($height==0) {
						$height=$width;
					}
					if ($image["small_info"]!='') {
						$small_info=explode("|",$image["small_info"]);
						$smarty->assign("small_info",$small_info);
					}
					if ($image["medium_info"]!='') {
						$medium_info=explode("|",$image["medium_info"]);
						$smarty->assign("medium_info",$medium_info);
					}
					$smarty->assign("width",$width);
					$smarty->assign("height",$height);
				}
				if (is_array($image)) {
				$smarty->assign("image",$image);
					if (is_file($config["pathes"]["user_image"].$image["big_photo"])) {
						$smarty->assign("size",@getimagesize($config["pathes"]["user_image"].$image["big_photo"]));
					}
				}
			}
?>