<?php

/**
 * 把一个字符串转换为可以用HTML输出的格式
 * @param    string    $str
 * @return    string
*/
function HtmlEncode($str)
{
    if (empty($str))
        return('');

    $ar = array(
        '&'        => '&amp;',
        '<'        => '&lt;',
        '>'        => '&gt;',
        chr(9)    => '　　',
        chr(13)    => '<br />',
        chr(34)    => '&quot;',
        '  '    => '&nbsp; ',
        ' '        => '&nbsp;',
        '&nbsp;&nbsp;'    => '&nbsp; ',
    );
    $ar_search = array_keys($ar);
    $ar_replace = array_values($ar);

    $outstr = str_replace($ar_search, $ar_replace, $str);
    return $outstr;
} // end of func HtmlEncode


/**
 * 判断一个字符的某个位置的字符是否中文
 * 如果从一个中文的第二个字节开始检查，将返回FALSE
 * @param    string    $str
 * @param    int        $pos
 * @return    boolean
 */
function IsGbChar($str = '', $pos = 0)
{
    if (empty($str))
    {
        return(false);
    }
    else
    {
        //检查连续的两个字节
        $s1 = ord(substr($str, $pos, 1));
        $s2 = ord(substr($str, $pos + 1, 1));
        if ((160 < $s1) && (248 > $s1) && (160 < $s2) && (255 > $s2))
        {
            return(true);
        }
        else
        {
            return(false);
        }
    }
} // end of func IsGbChar


/**
 * Convert 15-digi pin to 18-digi
 *
 * @param    string    $pin
 * @return    string
 */
function Pin15To18($pin) {
    if (15 != strlen($pin))
        // Error, which value should I return ?
        return $pin;

    $s = substr($pin, 0, 6) . '19' . substr($pin, 6);

    $n = 0;
    for ($i = 17; 0 < $i; $i --) {
        $n += (pow(2, $i) % 11) * intval($s{17 - $i});
    }
    $n = $n % 11;
    switch ($n) {
        case    0:
            $s_last = '1';
            break;
        case    1:
            $s_last = '0';
            break;
        case    2:
            $s_last = 'X';
            break;
        default:
            $s_last = strval(12 - $n);
            break;
    }

    return $s . $s_last;
} // end of func Pin15To18


/**
 * 生成随机字符串
 * a表示包含小写字符，A表示包含大写字符，0表示包含数字
 * @param    int        $len    字符串长度
 * @param    string    $mode    模式
 * @return    string
 */
function RandomString($len, $mode)
{
    $str = '';
    if (preg_match('/[a]/', $mode))
    {
        $str .= 'abcdefghijklmnopqrstuvwxyz';
    }
    if (preg_match('/[A]/', $mode))
    {
        $str .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    if (preg_match('/[0]/', $mode))
    {
        $str .= '0123456789';
    }
    $result = '';
    $str_len = strlen($str);
    $max = 1000;
    for($i = 0;$i < $len;$i++)
    {
        $num = rand(0, $max);
        $num = $num % $str_len;
        $result .= $str[$num];
    }
    return $result;
} // end of func RandomString


/**
 * Decode a string which is MIME encoding
 *
 * @link    http://www.faqs.org/rfcs/rfc2047
 * @link    http://www.php.net/imap_utf8
 * @param    string    $str
 * @param    string    $encoding    Encoding of output string.
 * @return    string
 */
function Rfc2047Decode($str, $encoding = 'utf-8')
{
    // Find string encoding
    $ar = array();
    //preg_match_all('/=\?(.{3,13})\?[B|Q]\?([\/\d\w\=]*)\?\=/i', $str, $ar);
    preg_match_all('/=\?(.{3,13})\?([B|Q])\?([^\?]*)\?\=/i', $str, $ar);
    // 0 is all-string pattern, 1 is encoding, 2 is string to base64_decode
    $i = count($ar[0]);
	
    if (0 < $i)
    {
        // Got match, process
        for ($j = 0; $j < count($i); $j++)
        {
            $s = '';
            if ('B' == strtoupper($ar[2][$j])) {
                // Decode base64 first
                $s = base64_decode($ar[3][$j]);
            }
            elseif ('Q' == strtoupper($ar[2][$j])) {
                // quoted-printable encoding ? its format like '=0D=0A'
                $s = quoted_printable_decode($ar[3][$j]);
            }

            // Then convert string to charset ordered
            if ($encoding != strtolower($ar[1][$j]))
                $s = mb_convert_encoding($s, $encoding, $ar[1][$j]);

            // Then replace into original string
            if (!empty($s))
                $str = str_replace($ar[0][$j], $s, $str);
        }
        
        return $str;
    }
    else
    {
        // No match, return original string
        return $str;
    }
}


/**
 * Encode a string using MIME encoding method
 *
 * Usually used in mail header, attachment name etc.
 *
 * No break in string(B encoding mode instead of Q, see
 * phpmailer::EncodeHeader, line 1156), because that possible
 * break chinese chars.
 * @link    http://www.faqs.org/rfcs/rfc2047
 * @param    string    $str
 * @param    string    $encoding    Encoding of $str
 * @return    string
 */
function Rfc2047Encode($str, $encoding = 'utf-8')
{
    return "=?" . $encoding . "?B?" . base64_encode($str) . "?=";
} // end of func Rfc2047Encode


/**
 * 返回字符串的长度，一个中文字按一个单位算
 *
 * Check also: mb_strwidth()
 * @param    string    $str
 * @return    int
 */
function StrlenGb($str = '')
{
    $len = strlen($str);
    $j = 0;
    for ($i=0; $i<$len; $i++)
    {
        if (true == IsGbChar($str, $i))
        {
            $i++;
        }
        $j++;
    }
    return($j);
} // end of func StrlenGb


/**
 * 把一个用字符串2间隔的字符串，用字符串1进行连接
 * @param    string    $str    源字符串
 * @param    string    $s1        用于连接的字符串
 * @param    string    $s2        源字符串本身是用什么连接的，如果为空，则使用$s1的值
 * @param    boolean    $embody    首尾是否加上字符串2
 * @param    boolean    $istrim    是否去除字符串中的特殊字符
 * @return    string
 */
function StrReForm( $str, $s1, $s2 = '', $embody = false, $istrim = true )
{
    $ss2 = empty($s2) ? $s1 : $s2;
    $ar = explode( $ss2, $str );
    if ( true == $istrim )
    {
        for ($i=0; $i<count($ar); $i++)
        {
            $ar[$i] = trim( $ar[$i], ' \t\0\x0B' );
        }
    }
    //去除空串
    $ar1 = array();
    for ($i=0; $i<count($ar); $i++)
    {
        if ( !empty( $ar[$i] ) )
        {
            array_push( $ar1, $ar[$i] );
        }
    }
    $s = implode( $s1, $ar1 );
    if ( true == $embody )
    {
        $s = $s1 . $s . $s1;
    }
    return( $s );
} // end of func StrReForm


/**
 * Convert ucfirst format to underline_connect format
 *
 * If convert fail, return original string.
 * @param    string    $str
 * @return    string
 */
function StrUcfirst2Underline($str)
{
    $s = preg_replace('/([A-Z])/', '_\1', $str);
    $ar = explode('_', $s);
    $s = '';
    if (empty($ar))
        $s = $str;
    else
    {
        foreach ($ar as $s1)
            if (!empty($s1))
                $s .= '_' . strtolower($s1);
        $s = substr($s, 1);
    }
    return $s;
} // end of func StrUcfirst2Underline


/**
 * Convert underline_connect format to ucfirst format
 *
 * If convert fail, return ucfirst($str)
 * @param    string    $str
 * @param    boolean    $minus    Treat minus sign as splitter also.
 * @return    string
 */
function StrUnderline2Ucfirst($str, $minus = false)
{
    if ($minus)
        $str = str_replace('-', '_', $str);
    $ar = explode('_', $str);
    $s = '';
    if (empty($ar))
        $s = ucfirst($str);
    else
        foreach ($ar as $s1) {
            if ('' != $s1)
                $s .= ucfirst($s1);
        }
    return $s;
} // end of func StrUnderline2Ucfirst


/**
 * 截取子字符串，中文按长度1计算
 * 在计算截取起始位置和截取长度时，中文也是按长度1计算的
 * 比如$str='大中小'，SubstrGb($str, 1, 1) = '中';
 *
 * Obsolete, see: http://www.fwolf.com/blog/post/133
 * @link http://www.fwolf.com/blog/post/133
 * @param   string  $str
 * @param   int     $start
 * @param   int     $len
 */
function SubstrGb($str = '', $start = 0, $len = 0)
{
    $tmp = '';
    if (empty($str) || $len == 0)
    {
        return false;
    }
    $l = strlen($str);
    $j = 0;
    for ($i = 0; $i < $l; $i++)
    {
        $tmpstr = (ord($str[$i]) >= 161 && ord($str[$i]) <= 247&& ord($str[$i+1]) >= 161 && ord($str[$i+1]) <= 254)?$str[$i].$str[++$i]:$tmpstr = $str[$i];
        if ($j >= $start && $j <= ($start + $len))
        {
            $tmp .= $tmpstr;
        }
        $j++;
        if ($j == ($start + $len))
        {
            break;
        }
    }
    return $tmp;
} // end of func SubstrGb


/**
 * Get substr by display width, and ignore html tag's length
 *
 * Using mb_strimwidth()
 *
 * Attention: No consider of html complement.
 * @link http://www.fwolf.com/blog/post/133
 * @param    string    $str    Source string
 * @param    int        $len    Length
 * @param    string    $marker    If str length exceed, cut & fill with this
 * @param    int        $start    Start position
 * @param    string    $encoding    Default is utf-8
 * @return    string
 */
function SubstrIgnHtml($str, $len, $marker = '...', $start = 0, $encoding = 'utf-8') {
    $i = preg_match_all('/<[^>]*>/i', $str, $ar);
    if (0 == $i) {
        // No html in $str
        $str = htmlspecialchars_decode($str);
        $str = mb_strimwidth($str, $start, $len, $marker, $encoding);
        $str = htmlspecialchars($str);
        return $str;
    } else {
        // Have html tags, need split str into parts by html
        $ar = $ar[0];
        $ar_s = array();
        for ($i = 0; $i < count($ar); $i ++) {
            // Find sub str
            $j = strpos($str, $ar[$i]);
            // Add to new ar: before, tag
            if (0 != $j)
                $ar_s[] = substr($str, 0, $j);
            $ar_s[] = $ar[$i];
            // Trim origin str, so we start from 0 again next loop
            $str = substr($str, $j + strlen($ar[$i]));
        }

        // Loop to cut needed length
        $s_result = '';
        $i_length = $len - mb_strwidth($marker, $encoding);
        $f_tag = 0;        // In html tag ?
        $i = 0;
        while ($i < count($ar_s)) {
            $s = $ar_s[$i];
            $i ++;

            // Is it self-end html tag ?
            if (0 < preg_match('/\/\s*>/', $s)) {
                $s_result .= $s;
            } elseif (0 < preg_match('/<\s*\//', $s)) {
                // End of html tag ?
                // When len exceed, only end tag allowed
                if (0 < $f_tag) {
                    $s_result .= $s;
                    $f_tag --;
                }
            } elseif (0 < strpos($s, '>')) {
                // Begin of html tag ?
                // When len exceed, no start tag allowed
                if (0 < $i_length) {
                    $s_result .= $s;
                    $f_tag ++;
                }
            } else {
                // Real string
                $s = htmlspecialchars_decode($s);
                if (0 == $i_length) {
                    // Already got length
                    continue;
                } elseif (mb_strwidth($s, $encoding) < $i_length) {
                    // Can add to rs completely
                    $i_length -= mb_strwidth($s, $encoding);
                    $s_result .= htmlspecialchars($s);
                } else {
                    // Need cut then add to rs
                    $s_result .= htmlspecialchars(mb_strimwidth($s, 0, $i_length, '', $encoding)) . $marker;
                    $i_length = 0;
                }
            }
        }

        return $s_result;
    }
    return '';
} // end of func SubstrIgnHtml

/**
 *		分页函数
 * @param	$count			int		item total num
 * @param	$current_page	int		current page
 * @param	$per			int		item per page display
 * @param	$params			array	params array  $key=>$value
 */
function Pager($count, $current_page, $per, $url,$params=array())
{
	$sum=ceil($count/$per);
	$pages_display = 4;//当前页前后各显示几条
	if ($sum < 1)
	{
		return '';
	}
	$current_page > $sum && $current_page=$sum;
	(!is_numeric($current_page) || $current_page <1) && $current_page=1;
	$url_param_str = http_build_query($params);
	$url .= empty($url_param_str) ? '?' : '?'.$url_param_str.'&';
	$pre_page_arr = ($current_page)<$pages_display ? range(1,$current_page) : range($current_page-$pages_display+1,$current_page-1);
	if($current_page == 1)
	{
		$pre_page_arr = array();
	}
	elseif($current_page<$pages_display+1)
	{
		$pre_page_arr = range(1,$current_page-1);
	}
	else
	{
		$pre_page_arr = range($current_page-$pages_display,$current_page-1);
	}
	//当前页后面页显示的页数
	$suf_page_arr = array();
	if($current_page+$pages_display<=$sum)
	{
		$suf_page_arr = range($current_page+1,$current_page+$pages_display);
	}
	elseif($current_page+$pages_display>$sum&&$current_page!=$sum)
	{
		$suf_page_arr = range($current_page+1,$sum);
	}
	else
	{
		$suf_page_arr = array();
	}
	$pre_page_str = '';
	foreach($pre_page_arr as $v)
	{
		$pre_page_str .= "<a href='$url"."pn=" .$v."'>{$v}</a>&nbsp;";
	}
	$suf_page_str = '';
	foreach($suf_page_arr as $v)
	{
		$suf_page_str .= "<a href='$url"."pn=" .$v."'>{$v}</a>&nbsp;";
	}
	var_dump($suf_page_str);
	$ret = '';
	if($current_page!=1)
	{
		$ret = "<a href=\"" . $url . "pn=1\">首页</a>&nbsp;";
		$ret.= "<a href='$url"."pn=" .($current_page-1)."'>上页</a>&nbsp;";
	}
	else
	{
		$ret = "首页&nbsp;";
		$ret.= "上页&nbsp;";
	}
	$ret .= $pre_page_str;
	$ret.= $current_page."&nbsp;";
	$ret .= $suf_page_str;
	if($current_page!=$sum)
	{
		$ret.= "<a href='$url"."pn=".($current_page == $sum ? $current_page : ($current_page+1))."'>下页</a>&nbsp;";
		$ret.="<a href='$url". "pn=$sum" ."'>尾页</a><br/>";
	}
	else
	{
		$ret.= "下页&nbsp;";
		$ret.= "尾页&nbsp;";
	} 
	$ret.= "&nbsp;<b>$current_page/$sum</b>&nbsp;&nbsp;&nbsp;";
	$ret.="<input type='text' size='2' style='height: 16px; border:1px solid #E5E5E5;' id='PageKeyDownText' onkeydown=\"onPageKeyDown(event)\"> <input type='button' value='GO' onclick='GoPage()' />";
	$ret.= <<<EOT
<script language="javascript">
	function onPageKeyDown(e)
	{
		e = window.event||e;
		var code = e.keyCode||e.which;
		if (code == 13)
		{
			GoPage();
		}
	}
	function GoPage()
	{
		window.location.href = "{$url}pn="+document.getElementById("PageKeyDownText").value;
	}
</script>
EOT;
	return $ret;
}
?>
