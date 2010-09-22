<?php

/**** 字段验证函数部分 开始 ***************/

/**** 字段验证函数部分 结束 ***************/

function SysError($msgobj)
{
	if(is_array($msgobj))
	{
		trigger_error(print_r($msgobj,true), E_USER_ERROR);
	}
	else
	{
		trigger_error(strval($msgobj), E_USER_ERROR);
	}
}

function SysDebug($msgobj,$errtype=E_USER_WARNING)
{
	if(CWDF_DEBUG_MODE)
	{
		if(is_array($msgobj))
		{
			trigger_error(print_r($msgobj, true), $errtype);
		}
		else
		{
			trigger_error(strval($msgobj), $errtype);
		}
	}
}

/**
 * 后端调试输出，需要第一个参数是一个变量
 */
function SysTrace($obj, $prefix="", $nodebug=true)
{
	if(!$nodebug||CWDF_DEBUG_MODE){
		if(is_array($obj)){
			if(strlen($prefix)>0){
				ue_echo("SysTrace [".$prefix."] : ".str_replace("\n", "", print_r($obj, true)));
			}else{
				ue_echo("SysTrace : ".str_replace("\n", "", print_r($obj, true)));
			}
		}else{
			if(strlen($prefix)>0){
				ue_echo("SysTrace [".$prefix."] : ".str_replace("\n", "", strval($obj)));
			}else{
				ue_echo("SysTrace : ".str_replace("\n", "", strval($obj)));
			}	
		}
		return;
	}
}
/**
 * 轻量级的调试输出，需要第一个参数是一个变量
 */
function SysTraceLight(&$obj, $prefix="", $nodebug=true)
{
	if(!$nodebug||CWDF_DEBUG_MODE){
		if(is_array($obj)){
			if(strlen($prefix)>0){
				ue_echo("SysTrace [".$prefix."] : ".str_replace("\n", "", print_r($obj, true)));
			}else{
				ue_echo("SysTrace : ".str_replace("\n", "", print_r($obj, true)));
			}
		}else{
			if(strlen($prefix)>0){
				ue_echo("SysTrace [".$prefix."] : ".str_replace("\n", "", strval($obj)));
			}else{
				ue_echo("SysTrace : ".str_replace("\n", "", strval($obj)));
			}	
		}
		return;
	}
}
/**
 * 黑名单词汇过滤函数
 * 依赖文件 ../cache/blachwords.php，该文件通过后台管理系统生成字典表cache，然后再tools里面执行dictcache2array.php生成
 * @return string
 */
function blackwordProcess($text){
	global $blackwords, $blackwordsreplace;
	if(is_null($text)||strlen($text)<1)return "";
	$ret = preg_replace($blackwords, $blackwordsreplace, $text);
	return $ret;
}

/**
 * 获取请求网址
 *
 * @return string
 */
function RequestUri()
{
	$uri = "http://" . CWDF_HTTPHOST . ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] : $_SERVER['REQUEST_URI']);
	return $uri;
}

/**
 * 对数组内每一个元素进行adsslashes操作
 *
 * @param array $array
 */
function Add_S(&$array)
{
	foreach ($array as $key => $value)
	{
		if (!is_array($value))
		{
			$array[$key] = addslashes($value);
		}
		else
		{
			Add_S($array[$key]);
		}
	}
}

/**
 * 获取客户端IP地址
 *
 * @return string
 */
function RemoteAddr()
{
	/*
	if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
	{
    		$onlineip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
	{
    		$onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
    		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return $onlineip;
	 */
	return $_SERVER['REMOTE_ADDR'];
}

/**
 * 跳转到指定的网址
 *
 * @param string $URL
 */
function ObHeader($URL)
{
    if (!headers_sent())
    {
        header("Location: $URL");
    }
    else
    {
        echo "<meta http-equiv='refresh' content='0;url=$URL'>";
    }
    exit;
}

function urlMergeParams($url,$params)
{
	if(is_array($params) && count($params)>0)
	{
		$urlinfo = parse_url($url);
		$getparams = $params;
		if(isset($urlinfo['query']))
		{
			parse_str($urlinfo['query'],$orgparams);
			$getparams = array_merge($orgparams,$params);
		}
		$ret = '';
		if(!empty($urlinfo['host']))
		{
			$ret = $urlinfo['scheme'].'://'.$urlinfo['host'].'/';
		}
		$ret .= $urlinfo['path'].'?'.http_build_query($getparams);
	}
	else
	{
		return $url;
	}
}

/**
 * 生成分页字符串。输入列表总数，当前页数，每页显示数，网址URL，它将来自动生成分页列表字符串。
 *
 * @param int $count		记录总数
 * @param int $page		页码，从1开始
 * @param int $per			每页显示记录数
 * @param string $url		网址，需要在这个网址上附加page=4这样的请求
 * @return string		返回分页字符串
 */
function PageStrSearch($count, $pagenum, $per, $url="",$params=array())
{ 
	if(empty($url)){
		global $page;
		$url = $page->sessions["referpage"];
	}
	$sum = ceil($count/$per);
	if ($sum == 1)
		return;
	$pageArray = PageArray($sum, $pagenum, 5, 5);
	$existQuestionChar=false;
	$urlinfo = parse_url($url);
	$getparams = $params;
	if(isset($urlinfo['query']))
	{
		parse_str($urlinfo['query'],$orgparams);
		foreach($orgparams as $key=>$value)
		{
			if($key != 'pn' && $key !='pc')
			{
				$getparams[$key] = $value;
			}
		}
	}
	if(count($getparams)==0)
	{
		$safeurl = htmlspecialchars($urlinfo['path']).'?';
	}
	else
	{
		$safeurl = $urlinfo['path'].'?'.http_build_query($getparams);
		$safeurl = htmlspecialchars($safeurl);
	}
	$safeurl = str_replace('?1=1&','?',$safeurl);
	if(CWDF_DEBUG_MODE)ue_echo("function.php PageStrSearch url[".$url."],safeurl=[".$safeurl."]");

	$pstr = '<div class="fenye"><ul>';
	if ($pagenum > 1)
	{
		if(count($getparams)==0)
			$pstr .= '<li class="li1"><a href="'.$safeurl.'pn='.($pagenum - 1).'&amp;pc='.$per.'">&lt;&lt;上一页</a></li>';
		else
			$pstr .= '<li class="li1"><a href="'.$safeurl.'&amp;pn='.($pagenum - 1).'&amp;pc='.$per.'">&lt;&lt;上一页</a></li>';
	}

	$pstr .= '<li class="pagenum">';
	foreach ($pageArray as $item)
	{
		$safeitem = htmlspecialchars($item);
		if ($pagenum == $item)
		{
			$pstr .= '<span>'.$safeitem.'</span>';
		}
		else
		{
			if(count($getparams)==0)
				$pstr .= '<a href="'.$safeurl.'pn='.$safeitem.'&amp;pc='.$per.'">'.$safeitem.'</a>';
			else
				$pstr .= '<a href="'.$safeurl.'&amp;pn='.$safeitem.'&amp;pc='.$per.'">'.$safeitem.'</a>';

		}
	}
	$pstr .= '</li>';

	if ($pagenum < $sum)
	{
		if(count($getparams)==0)
			$pstr .= '<li class="li2"><a href="'.$safeurl.'pn='.($pagenum + 1).'&amp;pc='.$per.'">下一页&gt;&gt;</a></li>';	
		else
			$pstr .= '<li class="li2"><a href="'.$safeurl.'&amp;pn='.($pagenum + 1).'&amp;pc='.$per.'">下一页&gt;&gt;</a></li>';	
	}

	$pstr .= '</ul></div>';
	return $pstr;
}
/**
 * 生成分页字符串。输入列表总数，当前页数，每页显示数，网址URL，它将来自动生成分页列表字符串。
 *
 * @param int $count		记录总数
 * @param int $page		页码，从1开始
 * @param int $per			每页显示记录数
 * @param string $url		网址，需要在这个网址上附加page=4这样的请求
 * @return string		返回分页字符串
 */
function PageStrWidget($count, $pagenum, $per, $func, $params="")
{ 
	if(empty($func)){
		return ;
	}
	$sum = ceil($count/$per);
	$pageArray = PageArray($sum, $pagenum, 5, 5);
	$existQuestionChar=false;

	if($params)
	{
		$paramArr = explode(",", $params);
		foreach($paramArr as $pValue)
		{
			$getParam .= ",'".$pValue."'";
		}
	}
	$pstr = '<div class="fenye"><ul>';
	if ($pagenum > 1)
	{
		$next = $pagenum - 1;
		$pstr .= '<li class="li1"><a href="#" onclick="'.$func.'('.$next.','.$per.$getPram.')">&lt;&lt;上一页</a></li>';
	}

	$pstr .= '<li class="pagenum">';
	foreach ($pageArray as $item)
	{
		$safeitem = htmlspecialchars($item);
		if ($pagenum == $item)
		{
			$pstr .= '<span>'.$safeitem.'</span>';
		}
		else
		{
			$pstr .= '<a href="#" onclick="'.$func.'('.$safeitem.','.$per.$getParam.')">'.$safeitem.'</a>';
		}
	}
	$pstr .= '</li>';

	if ($pagenum < $sum)
	{
		$next = $pagenum + 1;
		$pstr .= '<li class="li2"><a href="#" onclick="'.$func.'('.$next.','.$per.$getParam.')">下一页&gt;&gt;</a></li>';	
	}

	$pstr .= '</ul></div>';
	return $pstr;
}

/**
 * 生成分页字符串。输入列表总数，当前页数，每页显示数，网址URL，它将来自动生成分页列表字符串。
 *
 * @param int $count		记录总数
 * @param int $page		页码，从1开始
 * @param int $per			每页显示记录数
 * @param string $url		网址，需要在这个网址上附加page=4这样的请求
 * @return string		返回分页字符串
 */
function PageStr($count,$page,$per,$url)
{
	$pre=4;
	$next=4;

	$sum=ceil($count/$per);

	$page > $sum && $page=$sum;
	(!is_numeric($page) || $page <1) && $page=1;

	if ($sum < 1)
	{
		return ;
	}
	else
	{
		$ret='<a href="' . $url . '&pn=1">&lt;&lt; </a>';
		$ret.= <<<EOT
<script language="javascript">
function onPageKeyDown(e)
{
	e = window.event||e;
	var code = e.keyCode||e.which;
	if (code == 13)
	{
		window.location.href = "{$url}&pn="+document.getElementById("PageKeyDownText").value;
	}
}
</script>
EOT;
		$flag=0;
		for($i=$page-$pre;$i <= min($sum,$page+$next);$i++){
			if($i<1) continue;
			$ret.=$i==$page ? "&nbsp;&nbsp;<b>$page</b>&nbsp;" : " <a href='$url"."&pn=$i"."'>&nbsp;$i&nbsp;</a>";
		} 
		$ret.=" <input type='text' size='2' style='height: 16px; border:1px solid #E5E5E5;' id='PageKeyDownText' onkeydown=\"onPageKeyDown(event)\"> <a href='$url". "&pn=$sum" ."'> &gt;&gt;</a>&nbsp;&nbsp;Pages: ( $page/$sum 页 共 $count 篇 )";
		return $ret;
	}
}

/**
 * 生成分页字符串。输入列表总数，当前页数，每页显示数，网址URL，它将来自动生成分页列表字符串。
 *
 * @param int $totalPage	所有页数
 * @param int $currentPage	当前页码
 * @param int $pre			当前页前页码数
 * @param int $next			当前页后页码数
 * @return array			返回分页数组
 */
function PageArray($totalPage, $currentPage, $pre = 9, $next = 9)
{
	$ret = array();
	if ($totalPage < 1)
	{
		return $ret;
	}

	$maxPage = min($totalPage, $currentPage+$next);
	for ($i = $currentPage-$pre; $i <= $maxPage; $i++)
	{
		if ($i < 1) continue;
		$ret[] = $i;
	}
	return $ret;
}

/**
 *返回查询的记录数
 *@param int $count	总数
 *@param int $page 页数
 *@param int $perpage 每页多少条
 *@param int $offset 偏移量
 *@return string
 */
function getPageLimit($count,$page=1,$perpage=10,$offset=0){
    if(empty($perpage)){
        $perpage = 10;
    }
    if($page<=0){
        $page = 1;
        $startlimit = 0;
    }
    else{
        $startlimit = ($page-1)*$perpage;
    }
    if($startlimit>$count){
        $startlimit = ((int)($count/$perpage))*$perpage;
    }
    if($startlimit<0){
        $startlimit = 0;
    }
    if($offset>0){
        $startlimit += $offset;
    }
    return $startlimit.",".$perpage;
}
/**
 * 生成分页字符串。输入列表总数，当前页数，每页显示数，网址URL，它将来自动生成分页列表字符串。
 *
 * @param int $count		记录总数
 * @param int $page			页码，从1开始
 * @param int $per			每页显示记录数
 * @param string $url		网址，需要在这个网址上附加page=4这样的请求
 * @return string		返回分页字符串
 */
function PageStr2($count, $page, $per, $url)
{
	$sum=ceil($count/$per);

	$page > $sum && $page=$sum;
	(!is_numeric($page) || $page <1) && $page=1;

	if ($sum < 1)
	{
		return ;
	}
	else
	{
		$ret="<a href=\"" . $url . "&pn=1\">首页</a>&nbsp;";
		$ret.= <<<EOT
<script language="javascript">
function onPageKeyDown(e)
{
	e = window.event||e;
	var code = e.keyCode||e.which;
	if (code == 13)
	{
		window.location.href = "{$url}&pn="+document.getElementById("PageKeyDownText").value;
	}
}
</script>
EOT;
		$ret.= "<a href='$url"."&pn=" .($page-1)."'>上页</a>&nbsp;";
		$ret.= "<a href='$url"."&pn=".($page == $sum ? $page : ($page+1))."'>下页</a>&nbsp;";
		$ret.="<a href='$url". "&pn=$sum" ."'>尾页</a><br/>";
		$ret.= "&nbsp;<b>$page/$sum</b>&nbsp;&nbsp;&nbsp;";
		$ret.="go <input type='text' size='2' style='height: 16px; border:1px solid #E5E5E5;' id='PageKeyDownText' onkeydown=\"onPageKeyDown(event)\">";
		return $ret;
	}
}

/**
 * 判断是否是email
 *
 * @param string $email
 * @return bool
 */
function IsEmail($email)
{
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$", $email))
	{
		return false;
	}
	else
	{
		return true;
	}
}

/**
 * 判断是否为合法电话号码
 *
 * @param string $C_telephone电话号码
 * @return bool
 */
function IsTelephone($C_telephone)
{
	if (!ereg("^[+]?[0-9]+([xX-][0-9]+)*$", $C_telephone))
	{
		return false;
	}
	else
	{
		return true;
	}
} 

/**
 * 判断是否为合法邮编
 *
 * @param string $strPostCode
 * @return bool
 */
function IsPostCode($strPostCode)
{
	if (strlen($strPostCode) == 6)
	{
		if (!ereg("^[+]?[_0-9]*$",$strPostCode))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
} 

/**
 * 判断是否是URL
 *
 * @param string $url
 * @return bool
 */
function IsUrl($url)
{
	if (!ereg("^http://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$", $url))
	{
		return false;
	}
	else
	{
		return true;
	}
}

/**
 * 判断给定日期字符串是否是合法日期
 * @param string $strDate	格式形如YYYY-mm-dd
 * @return bool
 */
function IsDate($strDate)
{
	if (strlen($strDate) < 1)
	{
		return false;
	}
	$iaMonthDays = array(31,28,31,30,31,30,31,31,30,31,30,31);
	$iaDate = explode("-", $strDate);

	if (count($iaDate) != 3)
	{
		return false;
	}
	
	if (strlen($iaDate[1]) > 2 || strlen($iaDate[2]) > 2)
	{
		return false;
	}
	
	$year = intval($iaDate[0]);
	$month = intval($iaDate[1]);
	$day	= intval($iaDate[2]);

	if (!$year || !$month || !$day)
	{
		return false;
	}
	
	if ($year < 1900 || $year > 2100)
	{
		return false;
	}
	
	if ((($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0))
	{
		$iaMonthDays[1] = 29;
	}
	
	if ($month < 1 || $month > 12)
	{
		return false;
	}
	
	if ($day < 1 || $day > $iaMonthDays[$month - 1])
	{
		return false;
	}
	return true;
}

/**
 * 判断给定的时间字符串是否是合法时间
 * @param string $strTimeString	格式形如hh:nn:ss
 * @return bool
 */
function IsTime($strTimeString)
{
	if (strlen($strTimeString) < 1)
	{
		return false;
	}
	
	$tempArray = explode(":", $strTimeString);
	
	!isset($tempArray[1]) && $tempArray[1] = 0;
	!isset($tempArray[2]) && $tempArray[2] = 0;
	
	$temph = intval($tempArray[0]);
	$tempm = intval($tempArray[1]);
	$temps = intval($tempArray[2]);
/*
	if (!$temph || !$tempm || !$temps)
	{
		return false;
	}
	*/
	
	if ($temph < 0 || $temph > 23)
	{
		return false;
	}
	if ($tempm < 0 || $tempm > 59)
	{
		return false;
	}
	if ($temps < 0 || $temps > 59)
	{
		return false;
	}
	return true;
}

/**
 * 判断给定的日期时间字符串是否合法
 * @param string $strDateTime	形如 YYYY-mm-dd hh:ii:ss
 * @return bool
 */
function IsDateTime($strDateTime)
{
	if (strlen($strDateTime) < 5)
	{
		return false;
	}
	
	$tempArray = explode(" ", $strDateTime);
	$tempDate = $tempArray[0];
	if (isset($tempArray[1]))
	{
		$tempTime = $tempArray[1];
	}
	else
	{
		$tempTime = "00:00:00";
	}

	if (IsDate($tempDate) && IsTime($tempTime))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * pass密钥
 * @param string $md5password 经过md5加密的密码字符串
 */
function PasskeyEncode($md5password)
{
	return md5($md5password . CWDF_PASSKEY);
}

/**
 * 将给定的由特定符号分隔开的字符串过滤掉空白字符
 * @param string $sep	分隔字符串
 * @param string $strList	给定的字符串
 * @param string $strRet	
 */
function TrimSep($sep, $strList)
{
	$strArray = explode($sep, $strList);
	$strRet = "";
	if ($strArray)
	{
		foreach ($strArray as $keyid => $item)
		{
			$strArray[$keyid] = trim($item);
		}
		$strArray = array_unique($strArray);
		$strRet = implode($sep, $strArray);
	}
	return $strRet;
}

/**
 * 将图像转换为另给定的尺寸
 * @param string $strFile	图像文件名
 * @param int $toW, $toH	要转换为的宽和高
 * @param string $toFile	另存为的文件名，如果为空，则覆盖原文件
 */
function ImageResize($srcFile, $toW, $toH, $toFile="")
{
	if ($toFile == "")
	{
		$toFile = $srcFile;
	}
	$info = "";
	$data = GetImageSize($srcFile, $info);
	switch ($data[2])
	{
		case 1:
			if (!function_exists("imagecreatefromgif"))
			{
				echo "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！<a href='javascript:go(-1);'>返回</a>";
				exit();
			}
			$im = ImageCreateFromGIF($srcFile);
			break;
		case 2:
			if (!function_exists("imagecreatefromjpeg"))
			{
				echo "你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！<a href='javascript:go(-1);'>返回</a>";
				exit();
			}
			$im = ImageCreateFromJpeg($srcFile);   
			break;
		case 3:
			$im = ImageCreateFromPNG($srcFile);   
			break;
	}
	$srcW = ImageSX($im);
	$srcH = ImageSY($im);
	$toWH = $toW/$toH;
	$srcWH = $srcW/$srcH;
	if ($toWH <= $srcWH)
	{
		$ftoW = $toW;
		$ftoH = $ftoW*($srcH/$srcW);
	}
	else
	{
		$ftoH = $toH;
		$ftoW = $ftoH*($srcW/$srcH);
	}
	if ($srcW > $toW || $srcH > $toH)
	{
		if (function_exists("imagecreatetruecolor"))
		{
			@$ni = ImageCreateTrueColor($ftoW, $ftoH);
			if ($ni)
				ImageCopyResampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
			else
			{
				$ni=ImageCreate($ftoW,$ftoH);
				ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
			}
		}
		else
		{
			$ni=ImageCreate($ftoW,$ftoH);
			ImageCopyResized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
     		}
		if (function_exists('imagejpeg')) 
			ImageJpeg($ni, $toFile);
		else ImagePNG($ni, $toFile);
		ImageDestroy($ni);
	}
	ImageDestroy($im);
}

/**
 * 规范URI格式(非协议开头的加上 http://)
 * @param string $uri
 */
function normalizeURI($uri) {
	$trim_uri = trim($uri);
	if (empty($trim_uri)) {
		return "";
	} else if (mb_substr($trim_uri, 0, 1) === "#") {
		return $trim_uri;
	}
	$regexp = "@^\\s*(\\w+://)?([^/\\s]+)(/[^\\s]*)?\\s*$@i";
	preg_match($regexp, $uri, $matches);
	if (empty($matches[1])) {
		return "http://".$matches[2].$matches[3];
	} else {
		return $matches[1].$matches[2].$matches[3];
	}
}

/**
 * @param object $obj 需要转换成 slashed json string 的对象
 * @return string     将 ' 转成 \' 的json string
 */
function getSlashedJson($obj) {
	return mb_ereg_replace("'", "\\'", json_encode($obj));
}

/**
 * @param dnsname $usertype 用户的dns名称以及用户类型
 * @return string 用户的空间站点url
 */
function gethosturl($dnsname, $usertype)
{
	if($usertype == 4)
	{
		$ret = "http://".$dnsname.".corp.gongye360.com";
	}
	else
	{
		$ret = "http://".$dnsname.".blog.gongye360.com";
	}
	return $ret;
} 

/**
 * 生成随机数
 */
function num_rand($lenth)
{
	mt_srand((double)microtime() * 1000000);
	for ($i = 0; $i < $lenth; $i++)
	{
		$randval .= mt_rand(1, 9);
	}
	return $randval;
}
/**
 * 逃脱xml格式并且依据参数进行删除换行符操作
 */
function escapeXml($xml, $trim=false)
{
	if(!$xml||strlen($xml)<1)return $xml;
	//*
	$pattern = array("&", "<", ">", "\"", "'");
	$replace = array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;");
	$ret = str_replace($pattern, $replace, $xml);
	/*/
	$ret = str_replace("&", "&amp;", $xml);
	$ret = str_replace("<", "&lt;", $ret);
	$ret = str_replace(">", "&gt;", $ret);
	$ret = str_replace("\"", "&quot;", $ret);
	$ret = str_replace("'", "&apos;", $ret);
	/*/
	if($trim){
		$ret = str_replace("\n", "", $ret);
	}
	return $ret;
}
/**
 * 
 */
function unescapeXml($xml, $trim=false)
{
	if(!$xml||strlen($xml)<1)return $xml;
	//*
	$pattern = array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;");
	$replace = array("&", "<", ">", "\"", "'");
	$ret = str_replace($pattern, $replace, $xml);
	/*/
	$ret = str_replace("&amp;", "&", $xml);
	$ret = str_replace("&lt;", "<", $ret);
	$ret = str_replace("&gt;", ">", $ret);
	$ret = str_replace("&quot;", "\"", $ret);
	$ret = str_replace("&apos;", "'", $ret);
	/*/
	if($trim){
		$ret = str_replace("\n", "", $ret);
	}
	return $ret;
}


/**
 * 获取用户主站地址
 */
function getUserBlogUrl($dnsname,$usertype=2)
{
	if(empty($dnsname))
	{
		return "";
	}
	global $page;
	if($page->sessions["referiscom"] == "true")
	{
		if(preg_match ("/^.*test\.gongye360\.com$/i", $_SERVER['HTTP_HOST'])) {
			$extra_flag = "test";
		} else if(preg_match ("/^.*bj\.gongye360\.com$/i", $_SERVER['HTTP_HOST'])) {
			$extra_flag = "bj";
		} else {
			$extra_flag = "";
		}
		if($usertype < 4)
		{
			return "http://".$dnsname.".blog".$extra_flag.".gongye360.com";
		}
		else if($usertype == 4)
		{
			return "http://".$dnsname.".corp".$extra_flag.".gongye360.com";
		}
	}
	else
	{
		if($usertype < 4)
		{
			return "http://".$dnsname.".blog.gongye360.net".$page->sessions["refermidpath"]."/products/blog";
		}
		else if($usertype == 4)
		{
			return "http://".$dnsname.".corp.gongye360.net".$page->sessions["refermidpath"]."/products/corp";
		}
	}
	return "";
}

/**
 * 获取用户健康管理主站地址
 * @deprecated
 */
function getUserHmUrl($dnsname,$usertype=3)
{
	if(empty($dnsname))
	{
		return "";
	}
	global $page;
	if($page->sessions["referiscom"] == "true")
	{
		if(preg_match ("/^.*test\.gongye360\.com$/i", $_SERVER['HTTP_HOST'])) {
			$extra_flag = "test";
		} else if(preg_match ("/^.*bj\.gongye360\.com$/i", $_SERVER['HTTP_HOST'])) {
			$extra_flag = "bj";
		} else {
			$extra_flag = "";
		}
		return "http://".$dnsname.".".CWDF_HM_PORTAL;
	}
	else
	{
		return "http://".$dnsname.".blog.gongye360.net".$page->sessions["refermidpath"]."/products/healthmgr";
	}
}

function GetProdType($url)
{
	$urlstruct = parse_url($url);
	$host = $urlstruct["host"];
	$path = $urlstruct["path"];
	unset($urlstruct);
	$iscom = false;
	if(stristr($host, "gongye360.com"))
	{
		$iscom = true;
	}
	$extra_flag = "";
	if($iscom)
	{
		if(stristr($host,"www".$extra_flag.".gongye360."))
		{
			return 1000;
		}
		elseif(stristr($host, "zhiyao" . $extra_flag. ".gongye360.")) {
			return 1001;
		}
		elseif(stristr($host, "gongkong" . $extra_flag. ".gongye360.")) {
			return 1002;
		}
		elseif(stristr($host, "baozhuang" . $extra_flag. ".gongye360.")) {
			return 1003;
		}
		elseif(stristr($host, "yibiao" . $extra_flag. ".gongye360.")) {
			return 1004;
		}
		elseif(stristr($host, "jixie" . $extra_flag. ".gongye360.")) {
			return 1005;
		}
		elseif(stristr($host, "huagong" . $extra_flag. ".gongye360.")) {
			return 1006;
		}

		elseif(stristr($host,"passport".$extra_flag.".gongye360."))
		{
			return 2000;
		}
		elseif(stristr($host,"blog".$extra_flag.".gongye360."))
		{
			return 3000;
		}
		elseif(stristr($host,"group".$extra_flag.".gongye360."))
		{
			return 4000;
		}
		elseif(stristr($host,"ask".$extra_flag.".gongye360."))
		{
			return 5000;
		}
		elseif(stristr($host,"wiki".$extra_flag.".gongye360."))
		{
			return 6000;
		}
		elseif(stristr($host,"exam".$extra_flag.".gongye360."))
		{
			return 7000;
		}
		elseif(stristr($host,"admin".$extra_flag.".gongye360."))
		{
			return 8000;
		}
		else if(stristr($host,"corp".$extra_flag.".gongye360."))
		{
			return 9000;
		}
		else
		{
			return 10000;
		}
	}
	else
	{
		if(stristr($path,"/products/www"))
		{
			return 1000;
		}
		else if(stristr($path,"/products/passport"))
		{
			return 2000;
		}
		else if(stristr($path,"/products/blog"))
		{
			return 3000;
		}
		else if(stristr($path,"/products/group"))
		{
			return 4000;
		}
		else if(stristr($path,"/products/ask"))
		{
			return 5000;
		}
		else if(stristr($path,"/products/wiki"))
		{
			return 6000;
		}
		else if(stristr($path,"/products/sysadmin"))
		{
			return 8000;
		}
		else if(stristr($path,"/products/corp"))
		{
			return 9000;
		}
		else
		{
			return 10000;
		}
	}
}

function getIndustryIdFromProdType($prodtype)
{
	global $gIndustryIdMap;
	return !empty($gIndustryIdMap[$prodtype]) ? $gIndustryIdMap[$prodtype] : 0;

}

//根据catepath获取行业ID
function getIndustryIdFromPath($path)
{
	$pos = strpos($path, '_');
	if ($pos === false)
			return 0;
	return (int)substr($path, $pos + 1); 
}

/**
 * 获取当前时间串，到微秒
 */
function microtime_format($light=false)
{
	list($usec, $sec) = explode(" ", microtime());
	if($light)return (date("Y-m-d H:i:s", $sec) . substr($usec, 1));
	else return ((float)$usec + (float)$sec);
}

/**
 * 获取utf8真度字符长度
 */
function utf8_strlen($str) 
{
    $count = 0;
	$strlen = strlen($str);
    for($i = 0; $i < $strlen; $i++){
        $value = ord($str[$i]);
        if($value > 127) {
            if($value >= 192 && $value <= 223) $i++;
            elseif($value >= 224 && $value <= 239) $i = $i + 2;
            elseif($value >= 240 && $value <= 247) $i = $i + 3;
            else return strlen($str);
        }
        $count++;
    }
    return $count;
}

/**
 * 获取utf8字串
 * @str 来源字串
 * @ulength 子串utf8字长度
 * @start 起始字节位数
 * @endchars 结束字符 mixed, 可以是数组或单字
 */
function utf8_substr(&$str, $ulength=0, $start=0, $endchars=0){
	$substr="";
    $count = 0;//字串进位
	$strlen = strlen($str);
    for($i = $start; $i < $strlen; $i++){
		if($count>=$ulength)break; //判断是否到达长度
        $value = ord($str[$i]);
		if(is_array($endchars)){//判断是否抵达结束特殊字符
			$broken = false;
			$endCharCnt = count($endchars);
			if($endCharCnt>0){
				for($j=0;$j<$endCharCnt;$j++){
					$endchar = $endchars[$j];
					if(is_int($endchar)){//数组此项为单字符
						if($value==$endchar){
							$broken=true;
							break;	
						}
					}else if(is_string($endchar)){//字串比较
						if(utf8_string_match($endchar, $str, $i, $strlen)){
							$broken=true;
							break;	
						}
					}
				}
			}
			if($broken)break;
		}else if(is_int($endchars)&&$endchars>0){ //判断是否抵达结束特殊字符
			if($value==$endchars){
				break;	
			}
		}
       	$count++;//utf8 字串进位
        if($value > 127) {
        	if($value >= 192 && $value <= 223){
				$substr .= substr($str, $i, 2);
				$i++;
				continue;
			}elseif($value >= 224 && $value <= 239){
				$substr .= substr($str, $i, 3);
				$i = $i + 2;
				continue;
			}elseif($value >= 240 && $value <= 247){
				$substr .= substr($str, $i, 4);
				$i = $i + 3;
				continue;
            }
        }
		$substr .= substr($str, $i, 1);
    }
    return $substr;
}

/**
 * utf8字节匹配关键词，匹配成功，返回true，否则返回false
 */
function utf8_string_match(&$key, &$str, $start=0, $final=0){
	$keysize = strlen($key);
	//*//实现一：按字符比较
	if($final==0)$final=strlen($str);
	for($i=0;$i<$keysize;$i++){
		if($start+$i>=$final)return false;//结束处理
		$value1=$str[$start+$i];
		$value2=$key[$i];
		if($value1==$value2)continue;
		return false;
	}
	/*/
	//实现二：系统函数strncmp
	if(strncmp(substr($str,$start,$keysize),$key,$keysize)!=0)return false;
	//*/
	return true;
}	

/**
 * 获取文件内容，直接输出
 */
function WriteJs($jsFile, $withTag=true, $notCompress=true)
{
	print("<script type=\"text/javascript\" src=\"$jsFile\"></script>\n");
	return;
	if(is_readable($jsFile)){
		if($withTag){
			$pos = strrpos($jsFile, "/")+1;
			print("<script language=\"javascript\">\n//<!--".substr($jsFile, $pos, strlen($jsFile)-$pos)."\n");
		}
		$jsStr = file_get_contents($jsFile);
		$message = array();
		$writeStr = phpJSO_compress($jsStr, $messages, $notCompress);
		print($writeStr);
		if($withTag)print("\n//-->\n</script>\n");
		if(count($message)>0){
			//TODO 
			print("<!--TRACE:js file obfuscator failed:\n".print_r($message, true)."\n-->\n");
		}
		unset($jsStr);
		unset($writeStr);
		unset($message);
	}else{
		print("<!--WARNING:js file '$jsFile' not exist!-->\n");
	}
}

/**
 * 获取文件内容，直接输出
 */
function WriteCss($cssFile, $withTag=false)
{
	if(is_readable($cssFile)){
		$pos = strrpos($jsFile, "/")+1;
		if($withTag)print("<style type=\"text/css\">\n/*cssfile:".substr($cssFile, $pos, strlen($cssFile)-$pos)."*/\n");
		$cssStr = file_get_contents($cssFile);
		print($cssStr);
		if($withTag)print("</style>\n");
		unset($cssStr);
	}else{
		print("<!--WARNING:style file '$cssFile' not exist!-->\n");
	}
}

/**
 * 裁剪字符串，去除多个回车，进格，换行，中英文逗号，中英文空格 UTF8编码实现
 * @param text	待裁剪字符串
 * @param replace 裁剪字串的替换字串，默认为一个英文空格
 */
function stripText($text, $replace=' '){
	$strlen = strlen($text);
	if($strlen>0){
		$retstr = "";
		for($i=0;$i<$strlen;$i++){	
			$value = ord($text[$i]);
			//print($value."\n");
			if($value > 127) {
				if($value >= 192 && $value <= 223){//双字节编码
					$retstr .= substr($text, $i, 2);
					//$value1 = ord($text[$i+1]);
					//print("$value|$value1\n");
					$i++;
				}elseif($value >= 224 && $value <= 239){//三字节编码
					$value1 = ord($text[$i+1]);
					$value2 = ord($text[$i+2]);
					if(($value==239&&$value1==188&&$value2==140)||($value==227&&$value1==128&&$value2==128)){//中文'，', 中文'　' , 此处处理将连续多个视为一个
						$offset=0;
						while(true){
							$j=$i+3+$offset;
							if($j>=$strlen)break;
							$value_comp = ord($text[$j]);
							$value1_comp = ord($text[$j+1]);
							$value2_comp = ord($text[$j+2]);
							if($value_comp==$value&&$value1_comp==$value1&&$value2_comp==$value2){
								$offset = $offset+3;
							}else{
								break;
							}
						}
						if($offset>0)$i=$i+$offset;
						$retstr .= $replace;	
					}else{
						$retstr .= substr($text, $i, 3);
					}
					$i= $i+2;
				}elseif($value >= 240 && $value <= 247){//四字节编码
					$retstr .= substr($text, $i, 4);
					//$value1 = ord($text[$i+1]);
					//$value2 = ord($text[$i+2]);
					//$value3 = ord($text[$i+3]);
					//print("$value|$value1|$value2|$value3\n");
					$i= $i+3;
				}else{
					$retstr .= substr($text, $i, 1);
				}
			}else{
				if($value==10||$value==9||$value==44||$value==32){//0a换行\n 14Tab\t 44encomma 32en空格
					//处理连续多个视为一个
					$offset=0;
					while(true){
						$j=$i+1+$offset;
						if($j>=$strlen)break;
						$value1 = ord($text[$j]);
						if($value1==$value){
							$offset++;
						}else{
							break;
						}
					}
					if($offset>0){
						$i=$i+$offset;
					}
					$retstr .= $replace;	
				}else if($value<32){//ignore \r
				}else{
					$retstr .= substr($text, $i, 1);
				}
			}
		}
		return $retstr;
	}else{
		return $text;
	}
}

/**
 * html文本转正常文本
 * @param html	html文本
 */
function html2text($html){
	$search = array(
		"/\r/",					// Non-legal carriage return
		"/[\n\t]+/",				// Newlines and tabs
		'/<script[^>]*>.*?<\/script>/i',	// <script>s 
		'/<!-- .* -->/',			// Comments
		'/<font[^>]*>(.+?)<\/font>/i',	// <font> and </font>
		'/<h[123][^>]*>(.+?)<\/h[123]>/ie',	// H1 - H3
		'/<h[456][^>]*>(.+?)<\/h[456]>/ie',	// H4 - H6
		'/<p[^>]*>/i',				// <P>
		'/<br[^>]*>/i',				// <br>
		'/<b[^>]*>(.+?)<\/b>/ie',		// <b>
		'/<i[^>]*>(.+?)<\/i>/i',		// <i>
		'/(<ul[^>]*>|<\/ul>)/i',		// <ul> and </ul>
		'/(<ol[^>]*>|<\/ol>)/i',		// <ol> and </ol>
		'/<li[^>]*>/i',				// <li>
		'/<a[^>]*>(.+?)<\/a>/i',	// <a>
		'/<hr[^>]*>/i',				// <hr>
		'/(<table[^>]*>|<\/table>)/i',		// <table> and </table>
		'/(<tr[^>]*>|<\/tr>)/i',		// <tr> and </tr>
		'/<td[^>]*>(.+?)<\/td>/i',		// <td> and </td>
		'/<th[^>]*>(.+?)<\/th>/i',		// <th> and </th>
		'/<style[^>]*>.*?<\/style>/i',		//<style>s
		'/&nbsp;/i',
		'/&quot;/i',
		'/&gt;/i',
		'/&lt;/i',
		'/&amp;/i',
		'/&copy;/i',
		'/&trade;/i',
		'/&mdash;/i',
		'/&ldquo;/i',
		'/&rdquo;/i',
		'/&#8220;/',
		'/&#8221;/',
		'/&#8211;/',
		'/&#8217;/',
		'/&#38;/',
		'/&#169;/',
		'/&#8482;/',
		'/&#151;/',
		'/&#147;/',
		'/&#148;/',
		'/&#149;/',
		'/&reg;/i',
		'/&bull;/i',
		'/&[&;]+;/i'
	);

	$replace = array(
		'',                                     // Non-legal carriage return
		' ',                                    // Newlines and tabs
		'',                                     // <script>s
		'',                                     // Comments
		"\t\\1\t",                              // <font>
		"strtoupper(\"\n\n\\1\n\n\")",          // H1 - H3
		"ucwords(\"\n\n\\1\n\n\")",             // H4 - H6
		"\n\n\t",                               // <P>
		"\n",                                   // <br>
		'strtoupper("\\1")',                    // <b>
		'_\\1_',                                // <i>
		"\n\n",                                 // <ul> and </ul>
		"\n\n",                                 // <ol> and </ol>
		"\t*",                                  // <li>
		"\t\\1\t",								// <a>
		"\n-------------------------\n",        // <hr>
		"\n\n",                                 // <table> and </table>
		"\n",                                   // <tr> and </tr>
		"\t\t\\1\n",                            // <td> and </td>
		"strtoupper(\"\t\t\\1\n\")",            // <th> and </th>
		'',										// <style>s
		' ',
		'"',
		'>',
		'<',
		'&',
		'(c)',
		'(tm)',
		'-',
		'"',
		'"',
		'"',
		'"',
		'-',
		"'",
		'&',
		'(c)',
		'(tm)',
		'--',
		'"',
		'"',
		'*',
		'(R)',
		'*',
		''
	); 
	$text = trim(stripslashes($html));
	$text = preg_replace($search, $replace, $text);
	$text = strip_tags($text, '');
	$text = preg_replace("/\n\s+\n/", "\n", $text);
	$text = preg_replace("/[\n]{3,}/", "\n\n", $text);
	return $text;
}

/**
 * html文本转正常文本
 * @param html	html文本
 */ 
  function escapeHtml($html){ 
   	return html2text($html);
}

/**
 * 裁剪字符串
 */
function truncateText($string, $length=80, $etc = '...', $break_words = false, $middle = false){
	if($length == 0)return '';
	if(isset($_SERVER['LANG'])&&stripos($_SERVER['LANG'],'utf-8')!=FALSE){//UTF-8编码处理
		//$string = preg_replace('/[\s,\/]+/', ' ', substr($string, 0, $length+1));
		$strlen = strlen($string);
		if($strlen>$length){
			$pos = 0;$showlen=0;
			for(;$pos<$strlen;$pos++){
				$showlen++;
				$value = ord($string[$pos]);
				if($value > 127) {
					if($value >= 192 && $value <= 223){//双字节编码
						$pos++;
						$showlen++;
					}elseif($value >= 224 && $value <= 239){//三字节编码
						$pos = $pos + 2;
						$showlen++;
					}elseif($value >= 240 && $value <= 247){//四字节编码
						$pos = $pos + 3;
						$showlen++;
					}
				}
				if($showlen>=$length){
					$pos++;break;
				}
			}         
			$retstr = substr($string, 0, $pos);
			return $retstr.$etc;
		}else{
			return $string;   
		}
	}else{
		if (function_exists("mb_strlen")){//GBK编码处理
			if (mb_strlen($string) > $length) {
				$length -= mb_strlen($etc);
				if (!$break_words && !$middle) {
					$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
				}
				if(!$middle) {
					return mb_substr($string, 0, $length).$etc;
				} else {
					return mb_substr($string, 0, $length/2) . $etc . mb_substr($string, -$length/2);
				}
			} else {
				return $string;
			}
		} else {
			if (strlen($string) > $length) {//其他处理
				$length -= strlen($etc);
				if (!$break_words && !$middle) {
					$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
				}
				if(!$middle) {
					return substr($string, 0, $length).$etc;
				} else {
					return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
				}
			} else {
				return $string;
			}
		}
	}
}

/**
 * 裁剪html文本
 */
function truncateHtml($html, $length=80, $strip=true, $etc = '...', $break_words = false, $middle = false){
	if(strlen($html)>0){
		$text = html2text($html);
		if(strlen($text)>0){
			if($strip){
				//$text = preg_replace('!\s+!', ' ', $text);
				$text = preg_replace('/\s+/', ' ', $text);
				$text = trim($text);
			}
			return truncateText($text, $length, $etc, $break_words, $middle);
		}else{
			return $text;
		}
	}else{
		return $html;
	}
}

function EncodeQueryStr($url)
{ 
	$pos = strpos($url,"?");
	if($pos === false)
	{ 
		return $url;
	}
	$fpath = substr($url,0,$pos);
	$query = substr($url,$pos+1);
	if($query!="")
	{ 
		$qarr = array();
		parse_str($query,$qarr);
		$query = http_build_query($qarr);
		return $fpath."?".$query ;
	} 
	else
	{ 
		return $fpath ;
	}
}

function PresetWidgetGet($querystr, $tpl=null)
{
	if(!is_null($querystr)&&strlen($querystr)>0){
		//特殊字符处理
		$querystr = str_replace("&amp;", "&", $querystr);
		parse_str($querystr, $params);
		global $_GET,$page;
		if(is_array($_GET)){
			$_GET=array_merge($_GET,$params);
			if($tpl){
				$tpl->assign('_GET', $_GET);
			}else{
				if(!is_null($page)&&!is_null($page->tpl))
					$page->tpl->assign('_GET', $_GET);
			}
		}
	}
}

function htmlFilterAdvertLink($htmlstr,$advertArray)
{
	$advertArray=array("healthoo.cn","xlzpt.com","fx999.net");
	$search = array();
	$replace = array();
	foreach($advertArray as $adstr)
	{
		$adstr = str_replace('.','\\.',$adstr);
		$search[] = "/\s+(href|src)=[\"']?http:\\/\\/(\w+\.)*".$adstr."[^\s>]*[\"']?/i";
		$replace[] = "";
	}
    return preg_replace($search,$replace, $htmlstr);
}

?>
