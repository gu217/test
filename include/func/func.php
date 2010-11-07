<?php

/**
Editor:gu217
Email:gu217@126.com
 **/
function GetGet($var, $trim = TRUE, $default = '')
{
	return GetRequest( $_GET, $var, $trim, $default );
}

function GetCookie($var, $trim = TRUE, $default = '')
{
	return GetRequest( $_COOKIE, $var, $trim, $default );
}

function GetPost($var, $trim = TRUE, $default = '')
{
	return GetRequest( $_POST, $var, $trim, $default );
}

function GetSession($var, $trim = TRUE, $default = '')
{
	$_SESSION [$var] = GetRequest( $_SESSION, $var, $trim, $default );
	return $_SESSION [$var];
}

function GetRequest(&$r, $var, $trim = TRUE, $default = null)
{
	if(isset( $r [$var] ))
	{
		$val = $r [$var];
		// Deal with special chars in parameters
		if(!get_magic_quotes_gpc())
			$val = RunAddSalshes( $val, $trim );
	}
	else
		$val = $default;
	return $val;
}

/**
 * addslashes
 *
 * @param mixed $var
 * @param Boolean $trim		trim?
 * @return mixed
 */
function RunAddSalshes($var, $trim = TRUE)
{
	if(is_array( $var ))
	{
		foreach( $var as $k=> $v )
		{
			$var [$k] = RunAddSalshes( $v, $trim );
		}
	}
	else
	{
		if($trim)
			$var = trim( addslashes( $var ) );
		else
			$var = addslashes( $var );
	}
	return $var;
}

/**
 * ADODB will addslashes auto
 *
 * @param mixed $var
 * @param Boolean $trim
 * @return mixed
 */
function RunStripSlashes($var, $trim = TRUE)
{
	if(is_array( $var ))
	{
		foreach( $var as $k=> $v )
		{
			$var [$k] = RunStripSlashes( $v, $trim );
		}
	}
	else
	{
		if($trim)
			$var = trim( stripslashes( $var ) );
		else
			$var = stripslashes( $var );
	}
	return $var;
}

/**
 * file upload
 *
 * @param string $filesource
 * @param string $filename
 * @param string $dest_dir
 * @param int $dir_type	0 'Ym' 1 'Ymd' 2 'Y',default 2 'Ym'
 * @return Boolean
 */
function FileUpload($filesource, $filename, $dest_dir, $dir_type = 0)
{
	switch($dir_type)
	{
		case 0 :
			$date_format = "Ym";
			break;
		case 1 :
			$date_format = "Ymd";
			break;
		case 2 :
			$date_format = "Y";
			break;
		default:
			$date_format = "Ym";
	}
	$foldir = date( $date_format );
	//if($dest_dir{0}=='/')//$_SERVER['DOCUMENT_ROOT']=>D:/myphp/lab.com/
	//   $dest_dir = substr($_SERVER['DOCUMENT_ROOT'],1);
	//$dest_dir = str_replace($dest_dir,'//','/');
	if(substr( $_SERVER ['DOCUMENT_ROOT'], -1 )!='/'&&$dest_dir {0}!='/')
		$dest_dir = '/'.$dest_dir;
	if(substr( $_SERVER ['DOCUMENT_ROOT'], -1 )=='/'&&$dest_dir {0}=='/')
		$dest_dir = substr( $dest_dir, 1 );
	if(!is_dir( $_SERVER ['DOCUMENT_ROOT'].$dest_dir )||!is_writeable( $_SERVER ['DOCUMENT_ROOT'].$dest_dir ))
	{
		exit( $_SERVER ['DOCUMENT_ROOT'].$dest_dir.'=>the folder is not exists or you have no permisssion to write!' );
	}
	$destination = $dest_dir.$foldir;
	if(!is_dir( $destination ))
	{
		mkdir( $destination, 0777, TRUE );
	}
	$destination .= '/'.$filename;
	move_uploaded_file( $filesource, $_SERVER ['DOCUMENT_ROOT'].$destination );
	return $destination;
}

function MicrotimeFloat()
{
	list ( $usec, $sec ) = explode( " ", microtime() );
	return ((float)$usec+(float)$sec);
}

function GetFileExtentsion($file)
{
	$pathinfo = pathinfo( $file );
	return $pathinfo ['extension'];
}

function GetSuffixOfUrl($url)
{
	$parse_url = parse_url( $url );
	if(empty( $parse_url ['path'] ))
		return '';
	$pathinfo = pathinfo( $parse_url ['path'] );
	return $pathinfo ['extension'];
}

/**
 * Get current URL
 *
 * @param Boolean $with_get_param	with the param
 * @return String
 */
function GetSelfUrl($with_get_param = TRUE)
{
	if(isset( $_SERVER ["HTTPS"] )&&'on'==$_SERVER ["HTTPS"])
		$url = 'https://';
	else
		$url = 'http://';
	
	$s_t = ($with_get_param) ? $_SERVER ['REQUEST_URI'] : $_SERVER ["SCRIPT_NAME"];
	
	$url .= $_SERVER ["HTTP_HOST"].$s_t;
	return $url;
}

/**
 * Go back
 *
 * @param init $step  -1 or -2,other is unnormal!
 */
function GoBack($step = -1)
{
	echo "<script>window.history.go($step)</script>";
	exit();
}

/**
 * URL go to URL
 *
 * @param string $url
 * @param string $prefix set default value
 * @param init $time  usually,$time=0
 */
function GoUrl($url, $prefix = '', $time = 0, $type = 2)
{
	$url = $prefix.$url;
	switch($type)
	{
		case 1 :
			sleep( $time );
			//Maybe have output before this
			@header( "Location:$url" );
			break;
		case 2 :
			echo "<meta http-equiv=refresh content='$time;url=\"$url\"'>";
			break;
		case 3 :
			echo "<script type='text/javascript'>
						function GoUrl()
						{
							window.location ='$url';
						}
						setTimeout(GoUrl(),$time*1000);
					</script>";
			break;
		default:
			;
	}
	exit();
}

/**
 * 通过给出的URL参数和网站目录获得完整的URL example: GetFormatUrl('a=cc&c=dd')
 *
 * @param array $params array('a'=>'cc','b'=>'dd')
 * @return string Get the full URL;such as: http://baidu.com/img/?a=cc&c=dd
 */
/**
function GetFormatUrl($params = '')
{

//	$params = ($params!='' && $params {0} == '?') ? $params : '?' . $params;
//	if($dir!='')
//	{
//		$dir = $dir {0} == '/' ? $dir : '/' . $dir;
//		$dir = substr ( $dir, - 1 ) == '/' ? $dir : $dir . '/';
//	}

	$dir = dirname ( __FILE__ );
	if ($dir {0} == '/')
		$dir = substr ( $dir, strrpos ( $dir, '/' ) + 1 );
	else
		$dir = substr ( $dir, strrpos ( $dir, '\\' ) + 1 );
	$params = $params == '' ? $params : '?' . $params;
	$dir = $dir == '' ? $dir : '/' . $dir . '/';
	$url = 'http';
	if (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on')
		$url .= 's://';
	else
		$url .= '://';
	$url .= $_SERVER ['HTTP_HOST'];
	$port = ($_SERVER ['SERVER_PORT'] == '80') ? '' : ':' . $_SERVER ['SERVER_PORT'];
	$url .= $port;
	if ($dir != '' && substr ( $_SERVER ['REQUEST_URI'], 0, strlen ( $dir ) ) == $dir)
		$url .= $dir . $params;
	else
		$url .= $params;
	return $url;
}
 **/
/**
 * 通过给出的参数获得完整的网站路径
 *
 * @param string $url
 * @param array $params
 * @param bool $keep_old_params
 * @return string
 */
function GetFormatUrl($url = '', $params = array(), $keep_old_params = FALSE)
{
	$url_params = array ();
	$url_param_str = '';
	if(empty( $url ))
		$url = GetSelfUrl( $keep_old_params );
	$parse_url = parse_url( $url );
	if(!empty( $parse_url ['query'] ))
	{
		$url_params = parse_str( $parse_url ['query'] );
	}
	$url_param_str = http_build_query( array_merge( $url_params, $params ) );
	if(!empty( $url_param_str ))
		return $url.'?'.$url_param_str;
	else
		return $url;
	//如果params为二维数组,url类似 http://go.com/index.php?a=bb&b%5B0%5D=1&b%5B1%5D=2&b%5B2%5D=3 单不影响解析
}

/**
 * For debug
 *
 * @param mixed $var
 * @param int $exit 0 or !0
 */
function ToEcho($var, $exit = 0)
{
	$exit = $exit ? TRUE : FALSE;
	echo '<pre>'.print_r( $var, TRUE ).'</pre>'; // 一些空的或为0的数用print_r不能很好的展现出来
	if($exit)
		exit();
}

/**
 * Get random string
 *
 * @param int $len
 * @param string $mod
 * @return str
 */
function RandomStr($len, $mod)
{
	$str = '';
	if(preg_match( '/[a]/', $mod ))
		$str .= 'abcdefghijklmnopqrstuvwxyz';
	if(preg_match( '/[A]/', $mod ))
		$str .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if(preg_match( '/[0]/', $mod ))
		$str .= '0123456789';
	$rs = '';
	$len_str = strlen( $str );
	for($i = 0;$i<$len;$i++)
	{
		$rs .= $str {rand( 1, $len_str )-1};
	}
	return $rs;
}

/**
 * Give the message
 *
 * @param string $msg
 * @param init $level 	0,Only give the tip;1,show ths error message;
 * @param $string $line		The line of error happens,such as SQL Wrong! 'Save Fail!'
 */
function Msg($msg = '', $level = 0, $line = '')
{
	header( 'Content-Type:text/html; charset=utf-8' );
	$msg = $msg=='' ? $msg : str_replace( "'", "\'", $msg ).'！';
	if($msg!=''&&$level==0) //not allow alert empty message,if $msg is empty,there will be a error in javascript
	{
		// There can filter some unnecessary messages. 
		echo "<script type='Text/Javascript'>alert('$msg');</script>";
	}
	if($level==1)
	{
		echo "<font color='red'>", $msg, '</font>';
		exit();
	}
}

function RandomZhChar($len = 2)
{
	//header('Content-Type:text/html; charset=utf-8');
	$return = array ();
	$str = "天朝万岁人命公司共和国大力";
	//将汉字转换成16进制代码的形式
	$str = substr( json_encode( $str ), 1, -1 );
	$arr = str_split( $str, 6 );
	$char_arr_keys = array_rand( $arr, $len );
	foreach( $char_arr_keys as $v )
	{
		$char_arr [] = $arr [$v];
	}
	$return ['code'] = '"'.implode( '', $char_arr ).'"'; //将穿过来的值 json_encode 对比即可
	$return ['char'] = '';
	for($i = 0;$i<$len;$i++)
	{
		echo $char_arr [$i];
		$return ['char'] .= chr( hexdec( substr( $char_arr [$i], 2 ) ) );
		$return ['char'] .= chr( hexdec( substr( $char_arr [$i], 0, 2 ) ) );
		echo iconv( 'UTF-16', 'UTF-8', $return ['char'] );
		exit();
	}
	$return ['char'] = iconv( 'UTF-16', 'UTF-8', $return ['char'] );
	var_dump( $return );

}

/**
 * 验证码的生产(包括字母型和汉字)
 * 
 * @param int $mod 		0 混合型(根据随机字符串调整) 1汉字(注:汉字的验证码只显示一些在一定范围内比较简单的字,一些复杂的字可能看不清,一些生僻字可能不好输入)
 */
function GetCheckImg($mod = 0)
{
	//验证码图片的高宽
	$width = 62;
	$height = 20;
	//生成验证码图片
	Header( "Content-type: image/PNG" );
	@session_start(); //将随机数存入session中
	

	//	$auth_code = RandomStr ( 5, 'Aa0' );
	$auth_code = RandomStr( 4, '0' );
	
	$_SESSION ['auth_code'] = $auth_code;
	$im = imagecreate( $width, $height ); //制定图片背景大小
	

	$black = ImageColorAllocate( $im, 0, 0, 0 ); //设定三种颜色
	$white = ImageColorAllocate( $im, 255, 255, 255 );
	$gray = ImageColorAllocate( $im, 200, 200, 200 );
	
	imagefill( $im, 0, 0, $gray ); //采用区域填充法，设定（0,0）
	// 将四位整数验证码绘入图片
	$_SESSION ['auth_code'] = $auth_code;
	imagestring( $im, 5, 5, 3, $auth_code, $black );
	// 用 col 颜色将字符串 s 画到 image 所代表的图像的 x，y 座标处（图像的左上角为 0, 0）。
	//如果 font 是 1，2，3，4 或 5，则使用内置字体
	for($i = 0;$i<100;$i++) //加入干扰象素
	{
		$randcolor = ImageColorallocate( $im, rand( 0, 255 ), rand( 0, 255 ), rand( 0, 255 ) );
		imagesetpixel( $im, rand( 0, $width ), rand( 0, $height ), $randcolor );
	}
	
	ImagePNG( $im );
	ImageDestroy( $im );
}

/**
 * 等长截取utf-8字符串,无乱码(sometimes isn't same length)
 *
 * @param string $string
 * @param init $start
 * @param init $length
 * @param mixed $suffix
 * @return string
 */
/**
function SubstrUtf8($string, $start, $length, $suffix = '...')
{
	$chars = $string;
	$i = 0;
	$m = 0;
	$n = 0;
	do
	{
		if (! isset ( $chars [$i] ))
			break;
		if (preg_match ( "/[0-9a-zA-Z]/", $chars [$i] ))
		{
			$m ++;
		}
		else
		{
			$n ++;
		}
		$k = $n / 3 + $m / 2;
		$l = $n / 3 + $m;
		$i ++;
	} while ( $k < $length );
	
	$str1 = mb_substr ( $string, $start, $l, 'utf-8' ); //保证不会出现乱码
	return mb_strlen ( $str1 ) >= mb_strlen ( $string ) ? $str1 : $str1 . $suffix;
}
 ***/
/**
 * substr for utf8
 *
 * @param string $str
 * @param int $start
 * @param int $len
 * @param string $marker
 * @param str $encoding
 * @return str
 */
function SubstrUtf8($str, $start = 0, $len, $marker = '...', $encoding = 'utf-8')
{
	$i = preg_match_all( '/<[^>]*>/i', $str, $ar );
	if(0==$i)
	{
		// No html in $str
		$str = htmlspecialchars_decode( $str );
		$str = mb_strimwidth( $str, $start, $len, $marker, $encoding );
		$str = htmlspecialchars( $str );
		return $str;
	}
	else
	{
		// Have html tags, need split str into parts by html
		$ar = $ar [0];
		$ar_s = array ();
		for($i = 0;$i<count( $ar );$i++)
		{
			// Find sub str
			$j = strpos( $str, $ar [$i] );
			// Add to new ar: before, tag
			if(0!=$j)
				$ar_s [] = substr( $str, 0, $j );
			$ar_s [] = $ar [$i];
			// Trim origin str, so we start from 0 again next loop
			$str = substr( $str, $j+strlen( $ar [$i] ) );
		}
		
		// Loop to cut needed length
		$s_result = '';
		$i_length = $len-mb_strwidth( $marker, $encoding );
		$f_tag = 0; // In html tag ?
		$i = 0;
		while( $i<count( $ar_s ) )
		{
			$s = $ar_s [$i];
			$i++;
			
			// Is it self-end html tag ?
			if(0<preg_match( '/\/\s*>/', $s ))
			{
				$s_result .= $s;
			}
			elseif(0<preg_match( '/<\s*\//', $s ))
			{
				// End of html tag ?
				// When len exceed, only end tag allowed
				if(0<$f_tag)
				{
					$s_result .= $s;
					$f_tag--;
				}
			}
			elseif(0<strpos( $s, '>' ))
			{
				// Begin of html tag ?
				// When len exceed, no start tag allowed
				if(0<$i_length)
				{
					$s_result .= $s;
					$f_tag++;
				}
			}
			else
			{
				// Real string
				$s = htmlspecialchars_decode( $s );
				if(0==$i_length)
				{
					// Already got length
					continue;
				}
				elseif(mb_strwidth( $s, $encoding )<$i_length)
				{
					// Can add to rs completely
					$i_length -= mb_strwidth( $s, $encoding );
					$s_result .= htmlspecialchars( $s );
				}
				else
				{
					// Need cut then add to rs
					$s_result .= htmlspecialchars( mb_strimwidth( $s, 0, $i_length, '', $encoding ) ).$marker;
					$i_length = 0;
				}
			}
		}
		
		return $s_result;
	}
	return '';
} // end of func SubstrIgnHtml


function MyTrim($var, $mod)
{
	$mod = strtoupper( $mod );
	switch($mod)
	{
		case 'B' :
			$trim = 'trim';
			break;
		case 'L' :
			$trim = 'ltrim';
			break;
		case 'R' :
			$trim = 'rtrim';
			break;
		default:
			$trim = 'trim';
	}
	if(is_array( $var ))
	{
		foreach( $var as &$v )
		{
			$v = $trim( $v );
		}
	}
	else
		$var = $trim( $var );
	return $var;
}

function GetCfg($cfg)
{
	global $config;
	if(false===strpos( $cfg, '.' ))
	{
		return ($config [$cfg]);
	}
	else
	{
		// Recoginize the dot
		$ar = explode( '.', $cfg );
		$c = $config;
		foreach( $ar as $val )
		{
			// Every dimision will go 1 level deeper
			$c = &$c [$val];
		}
		return ($c);
	}
} // end of func GetCfg


function SetCfg($cfg, $value)
{
	global $config;
	if(false===strpos( $cfg, '.' ))
	{
		$config [$cfg] = $value;
	}
	else
	{
		// Recoginize the dot
		$ar = explode( '.', $cfg );
		$c = &$config;
		$j = count( $ar )-1;
		// Every loop will go 1 level sub array
		for($i = 0;$i<$j;$i++)
		{
			// 'a.b.c', if b is not set, create it as an empty array
			if(!isset( $c [$ar [$i]] ))
				$c [$ar [$i]] = array ();
			$c = &$c [$ar [$i]];
		}
		// Set the value
		$c [$ar [$i]] = $value;
	}
} // end of func SetCfg


function Md5Password($pass)
{
	return md5( GetCfg( 'encrypted.str' ).md5( $pass ) );
}

//:THINK:  特殊字符的转换 =>҉
function UnicodeEncode($name)
{
	$name = iconv( 'UTF-8', 'UCS-2', $name );
	$len = strlen( $name );
	$str = '';
	for($i = 0;$i<$len-1;$i = $i+2)
	{
		$c = $name [$i];
		$c2 = $name [$i+1];
		if(ord( $c )>0)
		{ //两个字节的文字
			$str .= '\u'.base_convert( ord( $c ), 10, 16 ).str_pad( base_convert( ord( $c2 ), 10, 16 ), 2, 0, STR_PAD_LEFT );
		}
		else
		{
			$str .= $c2;
		}
	}
	return $str;
}

//将UNICODE编码后的内容进行解码
function UnicodeDecode($name)
{
	//转换编码，将Unicode编码转换成可以浏览的utf-8编码
	$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	preg_match_all( $pattern, $name, $matches );
	if(!empty( $matches ))
	{
		$rs_str = '';
		$len = count( $matches [0] );
		for($j = 0;$j<$len;$j++)
		{
			$str = $matches [0] [$j];
			if($j<1)
				$rs_str .= substr( $name, 0, strpos( $name, $str ) );
			else
				$rs_str .= substr( $name, strpos( $name, $matches [0] [$j-1] )+6, strpos( $name, $str )-strpos( $name, $matches [0] [$j-1] )-6 );
			if(strpos( $str, '\\u' )===0)
			{
				$code = base_convert( substr( $str, 2, 2 ), 16, 10 );
				$code2 = base_convert( substr( $str, 4 ), 16, 10 );
				$c = chr( $code ).chr( $code2 );
				$c = iconv( 'UCS-2', 'UTF-8', $c );
				$rs_str .= $c;
			}
			else
			{
				$rs_str .= $str;
			}
			if($j==$len-1)
				$rs_str .= substr( $name, strpos( $name, $str )+6 );
		}
	}
	return $rs_str;
}
?>
