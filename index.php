<?php
define( 'INC_DIR', $_SERVER ['DOCUMENT_ROOT'].'/include' );
require_once INC_DIR."/func/function_360.php";
require_once INC_DIR."/func/str_func.php";
require_once INC_DIR."/func/func.php";

class MyClass
{

	public function __construct()
	{
		self::EchoHead();
	}

	public function ArrayAdd()
	{
		$arr_1 = array (1 => 'a', 2 => 'b', 3 => 'c' );
		$arr_2 = array (3 => 'd', 5 => 'e', 6 => 'f' );
		ToEcho( $arr_1+$arr_2 );
	}

	public function ArrayMultisort()
	{
		$ar = array (array ("10", 11, 100, 100, "a" ), array (1, 2, "2", 3, 1 ) );
		array_multisort( $ar [0], SORT_ASC, SORT_STRING, $ar [1], SORT_NUMERIC, SORT_DESC );
		ToEcho( $ar );
	}

	public function ArrayRange()
	{
		$arr_char = range( 'A', 'z' );
		$arr_num = range( 1, 58 );
		$arr_new = array_combine( $arr_num, $arr_char );
		$arr_new_flip = array_flip( $arr_new );
		//ToEcho(array_keys($arr_new_flip,58));
		//ToEcho(array_search(58,$arr_new_flip));
		//ToEcho($arr_new_flip+$arr_new_flip);
		shuffle( $arr_new );
		$arr_merge_recursive = array_merge_recursive( $arr_new_flip, $arr_new );
		//ToEcho(array_merge_recursive(),false);
		ToEcho( $arr_merge_recursive );
		ToEcho( array_key_exists( 57, $arr_merge_recursive ) );
	}

	/**
	 * 计算两个日期的天数差
	 *
	 * @param $data1 string
	 * @param $data2 string
	 * */
	public function DateDiff($date1 = '2010-05-11', $date2 = '2010-05-01')
	{
		$d_1 = strtotime( $date1 );
		$d_2 = strtotime( $date2 );
		$max = max( $d_1, $d_2 );
		$min = min( $d_1, $d_2 );

		echo ($max-$min)/86400; //24*60*60 = 86400
	}

	public function DomClass()
	{
		$dom = new DOMDocument( '1.0', 'utf-8' );
		$element = $dom->CreateElement( 'test', 'The Root' );
		for($i = 0;$i<10;$i++)
		{
			$child = $dom->CreateElement( 'child', 'The Child'.$i );

			$id = $dom->CreateAttribute( 'id' );
			$text = $dom->createTextNode( $i );
			$id->appendChild( $text );
			$double_id = $dom->CreateAttribute( 'd_id' );
			$d_text = $dom->CreateTextNode( $i*2 );
			$double_id->appendChild( $d_text );
			$child->appendChild( $double_id );
			$child->appendChild( $id );
			$element->appendChild( $child );
		}
		$dom->appendChild( $element );
		echo $dom->saveXML();
	}

	private static function EchoHead()
	{
		echo <<<HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title> go.com </title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="Generator" content="gu217_test">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<script src="/js/error.js"></script>
<script src="/js/x.js"></script>
</head>
<body>

HEAD;
	}

	private static function EchoEnd()
	{
		echo <<<FOOT

</body>
</html>
FOOT;
	}

	/**
	 * 正则表达式的替换
	 * */
	public function FuncEregReplace()
	{
		// ereg_replace replace with the "\\digit" math the matching part,such as \0 0\1
		$str = "From 1983-10-08 to 2010-01-01";
		$str1 = ereg_replace( "([0-9]{1,})-([0-9]{1,2})-([0-9]{1,2})", "\\1-\\2"."year", $str );
		$str2 = ereg_replace( "([0-9]{1,})-([0-9]{1,2})-([0-9]{1,2})", "\\2/\\3/\\1", $str );
		echo $str1.'<br />';
		echo $str2;
	}

	public static function JsonUsage()
	{
		$arr = array ('a' => 'a', 'b' => array ('c' => 'd' ) );
		echo '
		<script type="text/javascript">
			js=\'', json_encode( $arr ), '\';
			js=eval("("+js+")");
			document.write(js.b.c);
		</script>';
	}

	public function StrFunction()
	{
		$len = 8;
		$string = 'PHP 有支援很方便的 function 可以直接達到此功能.';
		//$string = 'AF,S F,ADFSA,DFDAS  A AGHGHGH    FFFFFHHHHHHHHHJJJJJJPPPPPPOOOOOOOOOOO';

		$string = strip_tags( $string );
		//$string = mb_strimwidth ( $string, 0, $len, '...', 'UTF-8' );
		$string = mb_substr( $string, 0, $len, 'UTF-8' );
		$string .= (mb_strlen( $string, 'UTF-8' )<$len) ? '...' : '';
		echo $string."--".strlen( $string )."<br />\r\n";
		//mb_detect_encoding mb_convert_encoding
	}

	public function StrtoTimeTest()
	{
		echo strtotime( "now" ), "\n";
		echo strtotime( "10 September 2000" ), "\n";
		echo strtotime( "+1 day" ), "\n";
		echo strtotime( "+1 week" ), "\n";
		echo strtotime( "+1 week 2 days 4 hours 2 seconds" ), "\n";
		echo strtotime( "next Thursday" ), "\n";
		echo strtotime( "last Monday" ), "\n";
		echo strtotime( 'noon' );
		echo strtotime( 'midnight' );
		echo strtotime( '10am' );
		echo strtotime( '2pm' );

	}

	function isUkWorkingDay($utDate)
	{

		$holidays [] = date( 'Y-m-d', strtotime( 'first monday january '.$year ) );
		$holidays [] = date( 'Y-m-d', $utFirstJan );
		$holidays [] = date( 'Y-m-d', strtotime( 'last friday', $utEasterSunday ) );
		$holidays [] = date( 'Y-m-d', strtotime( 'next monday', $utEasterSunday ) );
		$holidays [] = date( 'Y-m-d', strtotime( 'first monday may '.$year ) );
		$holidays [] = date( 'Y-m-d', strtotime( 'last monday june '.$year ) ); // end of may B.H.
		$holidays [] = date( 'Y-m-d', strtotime( 'last monday september '.$year ) ); // end of August B.H.
		$holidays [] = date( 'Y-m-d', strtotime( 'next monday', $xmasDay ) );
		$holidays [] = date( 'Y-m-d', strtotime( 'next monday', $xmasDay ) );
		$holidays [] = date( 'Y-m-d', strtotime( 'next tuesday', $xmasDay ) );
		$holidays [] = date( 'Y-m-d', $xmasDay );
		$holidays [] = date( 'Y-m-d', strtotime( 'next day', $xmasDay ) );
		# on 2/8/2010
		date( 'm/d/y', strtotime( 'first day' ) ); # 02/01/10
		date( 'm/d/y', strtotime( 'last day' ) ); # 02/28/10
		date( 'm/d/y', strtotime( 'last day next month' ) ); # 03/31/10
		date( 'm/d/y', strtotime( 'last day last month' ) ); # 01/31/10
		date( 'm/d/y', strtotime( '2009-12 last day' ) ); # 12/31/09 - this doesn't work if you reverse the order of the year and month
		date( 'm/d/y', strtotime( '2009-03 last day' ) ); # 03/31/09
		date( 'm/d/y', strtotime( '2009-03' ) ); # 03/01/09
		date( 'm/d/y', strtotime( 'last day of march 2009' ) ); # 03/31/09
		date( 'm/d/y', strtotime( 'last day of march' ) ); # 03/31/10
		date( "Y-m-d", strtotime( "last day next month 2009-01-31" ) )."<br>";
		date( "Y-m-d", strtotime( "2009-01-31 +1 month" ) ); //2009-03-03
		strtotime( '+0 week sun nov 2009' ); // first sunday in nov 2009
		strtotime( '+1 week sun nov 2009' ); // second sunday
		strtotime( '-1 week sun nov 2009' ); // last sunday in oct 2009
		date( 'Y-m-d', strtotime( '+7 days' ) );
		date( 'Y-m-d H:i:s', strtotime( 'midnight +1 day' ) );
		strtotime( date( 'Y-m-d', strtotime( '+1 day' ) ) );
		date( "Y-m-d", strtotime( "last day next month 2000-01-31" ) );
		//echo date('Y-m-d',strtotime('+7 days')),'<br />',date('Y-m-d H:i:s',strtotime('midnight +1 day')),'<br />',strtotime(date('Y-m-d',strtotime('+1 day')));
		//echo '<br />'.date( "Y-m-d", strtotime( "last day next month 2000-01-31" ) )."<br>";
		//echo date('Y-m-d H:i:s',strtotime('midnight +1 day'));
	}

	public function DoTest()
	{
		echo time(),'<br />',strtotime(date('Y-m-d'));
		//var_dump(strlen(0)==strlen(''));
		//ToEcho(get_headers("http://google.com/11111gu.php"),false);
		//ToEcho(get_headers("http://google.com"));
		//trigger_error("");
		//var_dump(empty($_GET['OK']));	// no notice
		//var_dump($_GET['OK']!='');	//notice
		//var_dump(is_null($_GET['OK']));  //notice
		//var_dump(count($_GET['OK']));  //notice
		$str = "谷歌1010百度";
		$str2 = "0123456789";
		echo truncateText( $str, 7 ), '<br />';
		echo truncateText( $str2, 7 ), '<br />';
		echo SubstrGb( $str, 0, 7 ), '<br />';
		echo SubstrGb( $str2, 0, 7 ), '<br />';

		ToEcho( $_SERVER );

		echo date( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( '2010-02-28 00:00:00' ) ) ), '<br />', $ip_long = sprintf( '%u', ip2long( '192.192.168.192' ) ), '<br />', $ip_long, '<br />', '<b>'.long2ip( sprintf( '%d', $ip_long ) ).'</b>', //wrong
long2ip( sprintf( '%d', 3233851584 ) ); //right

	}

	public function TryCatch()
	{ //PHP不能自动抛出异常，不知道try catch有何用
		try
		{
			goog();
			$error = 'Always throw this error';
			throw new Exception( $error );
			//$this->b();
		} catch( Exception $e )
		{
			trigger_error( '111 wrong' );
		}
	}

	public function RegularExpressions()
	{
		var_dump( 1,preg_match("/^0|104$/","004",$matches),$matches,'<br />');  // wrong==>(0|1)04
		var_dump( 2,preg_match( "/^(0|(104))$/", "004", $matches ), $matches, '<br />' ); // int(1)
		var_dump( 3,preg_match( "/^0|(104)$/", "004", $matches ), $matches, '<br />' ); // int(1)
		var_dump( 4,preg_match( "/^z|food$/", "z", $matches ), $matches, '<br />' ); // int(1)
		var_dump( 5,preg_match( "/^z|food$/", "zood", $matches ), $matches, '<br />' ); // int(1)
		var_dump( 6,preg_match( "/^(0|(104))$/", "104", $matches ), $matches, '<br />' ); // int(1)
		var_dump( 7,preg_match( "/^(0|104)$/", "004", $matches ), $matches, '<br />' ); // int(1)
		var_dump( 8,preg_match( "/^(0|104)$/", "104", $matches ), $matches, '<br />' ); // int(1)
		var_dump( 9,preg_match( "/^(0|104)$/", "004", $matches ), $matches, '<br />' ); // int(0)
		var_dump( 10,preg_match( "/^(0|104)$/", "404", $matches ), $matches, '<br />' ); // int(0)
		var_dump( 11,preg_match( "/^(0|99|100|101)$/", "101", $matches ), $matches, '<br />' ); // int(1)
	}

	public function UrlFuncTest()
	{
		$url = "http://zhiyao.gongye360.com/index.html?a=1&b=2";
		$url = "index.html";
		ToEcho( http_build_query( array ('a' => 1, 'b' => 2 ) ) ); //string(7) "a=1&b=2" // array() ""
		ToEcho( parse_url( $url ) );
		/**
		 * array(4) {
					  ["scheme"]=>
					  string(4) "http"
					  ["host"]=>
					  string(20) "zhiyao.gongye360.com"
					  ["path"]=>
					  string(11) "/index.html"
					  ["query"]=>
					  string(7) "a=1&b=2"
					}
		 *
		 */
		ToEcho( pathinfo( $url ) );
		/**
		 * array(4) {
					  ["dirname"]=>
					  string(27) "http://zhiyao.gongye360.com"
					  ["basename"]=>
					  string(18) "index.html?a=1&b=2"
					  ["extension"]=>
					  string(12) "html?a=1&b=2"
					  ["filename"]=>
					  string(5) "index"
					}
		 *
		 */
		echo GetSuffixOfUrl( $url ), '<br />';
	}
	
	public function IPCheck()
	{
		$ip1= '8.8.8.6';
		$ip2= '8.8.8.7';
		$netmask = '255.255.255.248';
		$l_ip1 = $this->Ip2Bin($ip1);
		$l_ip2 = $this->Ip2Bin($ip2);
		$l_netmask = $this->Ip2Bin($netmask);
		$s1 = $l_ip1&$l_netmask;
		$s2 = $l_ip2&$l_netmask;
		if($s1 == $s2)
			echo $ip1.'和'.$ip2.'在同一个网段,网络标志为:'.$this->Bin2Ip($s1);
		
	}

	public function Ip2Bin($ip)
	{
		if(!strpos($ip,'.'))
			return '';
		$ip_r = explode('.',$ip);
		foreach($ip_r as &$v)
		{
			$v = substr('00000000'.decbin($v),-8);
		}
		return implode('.',$ip_r);
	}
	public function Bin2Ip($bin)
	{
		if(!strpos($bin,'.'))
			return '';
		$ip_r = explode('.',$bin);
		foreach($ip_r as &$v)
		{
			$v = bindec($v);
		}
		return implode('.',$ip_r);
	}
	public function DouNiWan()
	{
		echo <<<HTML
			<div id='dou'>逗你玩!</div>
			<div id='meinv' style='display:none;'><h1>噢!</h1></div>
			<div id='love' style='display:none;'><h1>送给你!<img src="/image/001.jpg" style="height:500px;width:500px;" alt="LOVE" /></h1></div>
			<script type="text/javascript">
				$('#dou').click(function (){

				$(this).hide();
				$('#meinv').show('slown');
				setTimeout("$('#love').show('slown')",500);
				});
			</script>
HTML;
	}

	public function ChargetCode()
	{
		$text = '网页中添加百度搜索框';
		$rs =	iconv ( 'UTF-8' , 'GB2312' , $text );
		var_dump(urlencode($text));
		 echo json_encode($text);
		 $cls_path = '3_4_1847';
		 echo $cls_path = str_replace('_','\_',$cls_path);
	}

	public function  TestT()
	{
		$log['push_flag'] = 0;
		$log['push_start_time'] = '2010-11-04 00:00:00';
		$log['push_end_time'] = '2010-11-05 00:00:00';
		if($log['push_flag']!=0&&(strtotime($log['push_start_time'])>strtotime(date('Y-m-d'))||strtotime($log['push_end_time'])<strtotime(date('Y-m-d'))))
		{
			$log['push_flag'] = 0;
		}
	}

	//CSV 文件读取(待修改)
	public static function ReadCsv($url)
	{
		$row = 1;
		$rs_ar = array();
		$handle = fopen($url,"r");
		while ($data = fgetcsv($handle, 1000, ","))
		{
		    $rs_ar[$row]['flag'] = true;
			$num = count($data);
		    if($num!=FIELDS_SIZE)
		    {
		    	$rs_ar[$row]['flag'] = false;
		    	$rs_ar[$row]['str'] = "第{$row}行字段数目不正确,请检查是否漏写了分隔符:".implode(',',$data);
		    }
		    else
		    {
		    	for ($c=0; $c < $num; $c++)
			    {
			        $data[$c]=self::CheckAndConverEncoding($data[$c]);
			    }
				$rs_ar[$row]['arr'] = $data;
		    }
		    $row++;
		}
		fclose($handle);
		//unset($rs_ar[1]);
		return $rs_ar;
	}

	public function __destruct()
	{
		self::EchoEnd ();
		//var_dump(preg_match("/[\xB0-\xF7][\xA1-\xFE]/",'001中国',$matches),$matches); 检测是否含有中文
		//换行符 chr(10)  换行符使用\n时，要用双引号包括
		//echo ord("\n"); //10
		//define('CODELIST',"ASCII,GBK,GB2312,big5,UTF-8,CP936,EUC-CN,BIG-5,EUC-TW");
	}
}
$a = new MyClass ( );
$a->DoTest();
echo '<br />',md5('hzjnfm');
?>

