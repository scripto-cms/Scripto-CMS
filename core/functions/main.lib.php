<?
//библиотека для Scripto CMS

function getImagesByCat($id_cat=0,$visible=0,$main=false,$pg=false,$onpage=0) {
	global $engine;
	$images=$engine->getImagesByCat($id_cat,$visible,$main,$pg,$onpage);
	return $images;
}

function getVideosByCat($id_cat=0,$visible=0,$main=false,$pg=false,$onpage=0) {
	global $engine;
	$images=$engine->getVideoByCat($id_cat,$visible,$main,$pg,$onpage);
	return $images;
}

/*функции , нужные программе установки*/
function apache_is_module_loaded($mod_name)
 {
 	if (function_exists("apache_get_modules")) {
    	$modules = apache_get_modules();
	} else {
		return true;
	}

    return in_array($mod_name, $modules);
 }
	function generatePassword($minLength = 8, $maxLength = 12, $maxSymbols = 0)
	{
    $symbolCount = 0;

    srand((double)microtime() * 1000003);

    for ($i = 0; $i < rand($minLength, $maxLength); $i++)
    {
        do
        {
            $char = rand(33, 126);

            $symbolCount += $isSymbol = (!in_array($char, range(48, 57)) && !in_array($char, range(65, 90)) && !in_array($char, range(97, 122)));

            if ($symbolCount <= $maxSymbols || !$isSymbol)
            {
                break;
            }
        }
        while (true);

        $passwd = sprintf('%s%c', isset($passwd) ? $passwd : NULL, $char);
    }

    return $passwd;
	} 
/*конец функций, нужных программе установки*/

//Получение MIME типов


    function get_content_type($filename) {

        $mime_types = array(

            'text/plain' => 'txt',
            'text/html' => 'html',
            'text/css' => 'css',
            'application/javascript' => 'js',
            'application/json' => 'json',
            'application/xml' => 'xml',
            'application/x-shockwave-flash' => 'swf',
            'video/x-flv' => 'flv',

            // images
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/vnd.microsoft.icon' => 'ico',
            'image/tiff' => 'tif',
            'image/svg+xml' => 'svg',

            // archives
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'rar',
            'application/x-msdownload' => 'exe',
            'application/x-msdownload' => 'msi',
            'application/vnd.ms-cab-compressed' => 'cab',

            // audio/video
            'audio/mpeg' => 'mp3',
            'video/quicktime' => 'qt',
            'video/quicktime' => 'mov',

            // adobe
            'application/pdf' => 'pdf',
            'image/vnd.adobe.photoshop' => 'psd',
            'application/postscript' => 'ai',
            'application/postscript' => 'eps',
            'application/postscript' => 'ps',

            // ms office
            'application/msword' => 'doc',
            'application/rtf' => 'rtf',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.ms-powerpoint' => 'ppt',

            // open office
            'application/vnd.oasis.opendocument.text' => 'odt',
            'application/vnd.oasis.opendocument.spreadsheet' => 'ods',
        );
		if(function_exists('mime_content_type')) {
        $ext = mime_content_type($filename);
        if (@array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        else {
            return false;
        }
		} else {
			return false;
		}
    }


//Проверка запрещен ли IP
function checkIP($allowed_ip,$myip) {
	$allowed_ip=trim($allowed_ip);
	if ($allowed_ip!='') {
	$ips=explode(chr(13),$allowed_ip);
	$myip=trim($myip);
	$myip_array=explode('.',$myip);
	foreach ($ips as $ip) {
		$ip=trim($ip);
		$ip_array=explode('.',$ip);
		$find=0;
		
		for ($x=0;$x<sizeof($ip_array);$x++) {
			if ($ip_array[$x]=='*') {
				$find++;
			} else {
				if (isset($myip_array[$x])) {
					if ($myip_array[$x]==$ip_array[$x]) {
						$find++;
					}
				}
			}
		}
		if ($find==sizeof($ip_array)) {
			return true;
		}
		unset($ip_array);
		$x=0;
	}
		return false;
	} else {
		return true;
	}
}

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

function json_safe_encode($var)
{
   return json_encode(json_fix_cyr($var));
}

function json_fix_cyr($var)
{
   if (is_array($var)) {
       $new = array();
       foreach ($var as $k => $v) {
           $new[json_fix_cyr($k)] = json_fix_cyr($v);
       }
       $var = $new;
   } elseif (is_object($var)) {
       $vars = get_class_vars(get_class($var));
       foreach ($vars as $m => $v) {
           $var->$m = json_fix_cyr($v);
       }
   } elseif (is_string($var)) {
       $var = iconv('cp1251', 'utf-8', $var);
   }
   return $var;
}
//функция конвертации из UTF8 в кодировку пользователя
function UTF8($str="",$encoding="cp1251") {
return @iconv("UTF-8",$encoding,$str);
}

function ToUTF8($str="",$encoding="cp1251") {
return @iconv($encoding,"UTF-8",$str);
}

//функция для получения расширения файла
function getFileExt($filename) {
	$len=strlen(trim($filename));
	$pos=0;
	for ($l=$len;$l>0;$l--) {
		if (isset($filename[$l])) 
		if ($filename[$l]==".") {
			$pos=$l;
			break;
		}
	}
	if ($len>$pos+1) {
	return strtolower(substr($filename,$pos+1,$len-$pos-1));
	} else {
	return "";
	}
}
//функция, удаляющая папки с подпапками
function delTree($dir) {
    $files = glob( $dir . '*', GLOB_MARK );
	if (is_array($files)) {
    foreach( $files as $file ){
        if( substr( $file, -1 ) == '/' )
            delTree( $file );
        else
            @unlink( $file );
    }
    @rmdir( $dir );
	}
}

function noSlash($str) {

if (substr($str,0,1)=="/") {
$str=substr($str,1);
}
$l=strlen($str)-1;
if (substr($str,$l,1)=="/") {
$str=substr($str,0,$l);
}
return $str;

}

function replaceSlashes($str="") {
	$search=array(
	"/",
	"\""
	);
	return str_replace($search,"",$str);
}

function clearRequest() {
	if (get_magic_quotes_gpc()) {
		foreach ($_REQUEST as $k=>$value) stripMe($_REQUEST[$k]);
		foreach ($_GET as $k=>$value) stripMe($_GET[$k]);
		foreach ($_POST as $k=>$value) stripMe($_POST[$k]);
	}
}

function stripMe(&$val) {
	if (is_array($val)) {
		foreach ($val as $k=>$v) stripMe($v);
	} else {
		$val=stripslashes($val);
	}
}

function sql_quote( $value )
{
//	set_magic_quotes_runtime(1);
//	$value = addslashes($value);

    if( get_magic_quotes_gpc() )
    {
          $value = stripslashes( $value );
    }
    //check if this function exists
    if( function_exists( "mysql_real_escape_string" ) )
    {
          $value = mysql_real_escape_string( $value );
    }
    //for PHP version < 4.3.0 use addslashes
    else
    {
          $value = addslashes( $value );
    }
    return $value;
}

function XMail( $from, $to, $subj, $text, $filename) {
    $f         = fopen($filename,"rb");
    $un        = strtoupper(uniqid(time()));
    $head      = "From: $from\n";
    $head     .= "To: $to\n";
    $head     .= "Subject: $subj\n";
    $head     .= "X-Mailer: PHPMail Tool\n";
    $head     .= "Reply-To: $from\n";
    $head     .= "Mime-Version: 1.0\n";
    $head     .= "Content-Type:multipart/mixed;";
    $head     .= "boundary=\"----------".$un."\"\n\n";
    $zag       = "------------".$un."\nContent-Type:text/html;\n";
    $zag      .= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";
    $zag      .= "------------".$un."\n";
    $zag      .= "Content-Type: application/octet-stream;";
    $zag      .= "name=\"".basename($filename)."\"\n";
    $zag      .= "Content-Transfer-Encoding:base64\n";
    $zag      .= "Content-Disposition:attachment;";
    $zag      .= "filename=\"".basename($filename)."\"\n\n";
    $zag      .= chunk_split(base64_encode(fread($f,filesize($filename))))."\n";
    
    return @mail("$to", "$subj", $zag, $head);
} 

function getIp()
    {
        global $REMOTE_ADDR;
        global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
        global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
        global $HTTP_SERVER_VARS, $HTTP_ENV_VARS;
        // Get some server/environment variables values
        if (empty($REMOTE_ADDR)) {
            if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
            }
            else if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $HTTP_SERVER_VARS['REMOTE_ADDR'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $HTTP_ENV_VARS['REMOTE_ADDR'];
            }
            else if (@getenv('REMOTE_ADDR')) {
                $REMOTE_ADDR = getenv('REMOTE_ADDR');
            }
        } // end if
        if (empty($HTTP_X_FORWARDED_FOR)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'];
            }
            else if (@getenv('HTTP_X_FORWARDED_FOR')) {
                $HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
            }
        } // end if
        if (empty($HTTP_X_FORWARDED)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $HTTP_SERVER_VARS['HTTP_X_FORWARDED'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $HTTP_ENV_VARS['HTTP_X_FORWARDED'];
            }
            else if (@getenv('HTTP_X_FORWARDED')) {
                $HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
            }
        } // end if
        if (empty($HTTP_FORWARDED_FOR)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_FORWARDED_FOR'];
            }
            else if (@getenv('HTTP_FORWARDED_FOR')) {
                $HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
            }
        } // end if
        if (empty($HTTP_FORWARDED)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $HTTP_SERVER_VARS['HTTP_FORWARDED'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $HTTP_ENV_VARS['HTTP_FORWARDED'];
            }
            else if (@getenv('HTTP_FORWARDED')) {
                $HTTP_FORWARDED = getenv('HTTP_FORWARDED');
            }
        } // end if
        if (empty($HTTP_VIA)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
                $HTTP_VIA = $_SERVER['HTTP_VIA'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
                $HTTP_VIA = $_ENV['HTTP_VIA'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_VIA'])) {
                $HTTP_VIA = $HTTP_SERVER_VARS['HTTP_VIA'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_VIA'])) {
                $HTTP_VIA = $HTTP_ENV_VARS['HTTP_VIA'];
            }
            else if (@getenv('HTTP_VIA')) {
                $HTTP_VIA = getenv('HTTP_VIA');
            }
        } // end if
        if (empty($HTTP_X_COMING_FROM)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
            }
            else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $HTTP_SERVER_VARS['HTTP_X_COMING_FROM'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $HTTP_ENV_VARS['HTTP_X_COMING_FROM'];
            }
            else if (@getenv('HTTP_X_COMING_FROM')) {
                $HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
            }
        } // end if
        if (empty($HTTP_COMING_FROM)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
            }
            else if (!empty($HTTP_COMING_FROM) && isset($HTTP_SERVER_VARS['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $HTTP_SERVER_VARS['HTTP_COMING_FROM'];
            }
            else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $HTTP_ENV_VARS['HTTP_COMING_FROM'];
            }
            else if (@getenv('HTTP_COMING_FROM')) {
                $HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
            }
        } // end if

        // Gets the default ip sent by the user
        if (!empty($REMOTE_ADDR)) {
            $direct_ip = $REMOTE_ADDR;
        }

        // Gets the proxy ip sent by the user
        $proxy_ip     = '';
        if (!empty($HTTP_X_FORWARDED_FOR)) {
            $proxy_ip = $HTTP_X_FORWARDED_FOR;
        } else if (!empty($HTTP_X_FORWARDED)) {
            $proxy_ip = $HTTP_X_FORWARDED;
        } else if (!empty($HTTP_FORWARDED_FOR)) {
            $proxy_ip = $HTTP_FORWARDED_FOR;
        } else if (!empty($HTTP_FORWARDED)) {
            $proxy_ip = $HTTP_FORWARDED;
        } else if (!empty($HTTP_VIA)) {
            $proxy_ip = $HTTP_VIA;
        } else if (!empty($HTTP_X_COMING_FROM)) {
            $proxy_ip = $HTTP_X_COMING_FROM;
        } else if (!empty($HTTP_COMING_FROM)) {
            $proxy_ip = $HTTP_COMING_FROM;
        } // end if... else if...
        // Returns the true IP if it has been found, else FALSE
        if (empty($proxy_ip)) {
            // True IP without proxy
            return $direct_ip;
        } else {
            $is_ip = ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}', $proxy_ip, $regs);
            if ($is_ip && (count($regs) > 0)) {
                // True IP behind a proxy
                return $regs[0];
            } else {
                // Can't define IP: there is a proxy but we don't have
                // information about the true IP
                return FALSE;
            }
        } // end if... else...
    } 
	
function mailHTML($to,$from,$subject,$body,$with_files=false) {
//функция отправки e-mail
	global $config;
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "From: $from\n";
	$headers .= "Reply-To: $from\n";
	$zag='';
	if (is_Array($_FILES) && (@sizeof($_FILES)>0) && ($with_files==true)) {
		$do_file=false;	
		foreach ($_FILES as $FL) {
			if (is_file($FL['tmp_name'])) {
			if ($do_file==false) {
				$headers.="content-type: multipart/mixed;";
			}
			$do_file=true;
			$f= fopen($FL['tmp_name'],"rb");
			$headers.="boundary=\"----------".$FL["name"]."\"\n\n";
$zag= "------------".$FL["name"]."\nContent-Type:text/html;\n";
$zag.= "Content-Transfer-Encoding: 8bit\n\n$body\n\n";
$zag.= "------------".$FL["name"]."\n";
$zag.= "Content-Type: application/octet-stream;";
$zag.= "name=\"".$FL["name"]."\"\n";
$zag.= "Content-Transfer-Encoding:base64\n";
$zag.= "Content-Disposition:attachment;";
$zag.= "filename=\"".$FL["name"]."\"\n\n";
$zag.= chunk_split(base64_encode(fread($f,filesize($FL['tmp_name']))))."\n";
fclose($f);
			}
		}
		if ($do_file==false) {
			$headers .= "Content-type: text/html; charset=".$config["mail"]["charset"]."\n";
			$zag=$body;
		}
	} else {
		$headers .= "Content-type: text/html; charset=".$config["mail"]["charset"]."\n";
		$zag=$body;
	}
	mail($to, $subject, $zag, $headers);
}

//сортировка разделов по убыванию
function cmp2 ($a, $b)
{
    if ($a["sort"] == $b["sort"]) return 0;
    return ($a["sort"] > $b["sort"]) ? -1 : 1;
}
?>