<?php
define ( 'INC_DIR', $_SERVER ['DOCUMENT_ROOT'] . '/include' );
require_once INC_DIR . "/func/function_360.php"; 
require_once INC_DIR . "/func/str_func.php";
require_once INC_DIR . "/func/func.php";

class MyClass
{
	public static $_start = 1;
	public static $_end = 1;

	public function __construct()
	{
		$this->EchoHead ();
	}

	public function ArrayAdd()
	{
		$arr_1 = array(1=>'a',2=>'b',3=>'c');
		$arr_2 = array(3=>'d',5=>'e',6=>'f');
		ToEcho($arr_1+$arr_2);
	}
	public function ArrayMultisort()
	{
		$ar = array (array ("10", 11, 100, 100, "a" ), array (1, 2, "2", 3, 1 ) );
		array_multisort ( $ar [0], SORT_ASC, SORT_STRING, $ar [1], SORT_NUMERIC, SORT_DESC );
		ToEcho ( $ar );
	}

	public function ArrayRange()
	{
		$arr_char = range ( 'A', 'z' );
		$arr_num = range ( 1, 58 );
		$arr_new = array_combine ( $arr_num, $arr_char );
		$arr_new_flip = array_flip ( $arr_new );
		//ToEcho(array_keys($arr_new_flip,58));
		//ToEcho(array_search(58,$arr_new_flip));
		//ToEcho($arr_new_flip+$arr_new_flip);
		shuffle ( $arr_new );
		$arr_merge_recursive = array_merge_recursive ( $arr_new_flip, $arr_new );
		//ToEcho(array_merge_recursive(),false);
		ToEcho ( $arr_merge_recursive );
		ToEcho ( array_key_exists ( 57, $arr_merge_recursive ) );
	}

	/**
	 * 计算两个日期的天数差
	 * 
	 * @param $data1 string 
	 * @param $data2 string
	 * */
	public function DateDiff($date1 = '2010-05-11', $date2 = '2010-05-01')
	{
		$d_1 = strtotime ( $date1 );
		$d_2 = strtotime ( $date2 );
		$max = max ( $d_1, $d_2 );
		$min = min ( $d_1, $d_2 );
		echo ($max - $min) / (24 * 60 * 60);
	}

	public function DomClass()
	{
		$dom = new DOMDocument ( '1.0', 'utf-8' );
		$element = $dom->CreateElement ( 'test', 'The Root' );
		for($i = 0; $i < 10; $i ++)
		{
			$child = $dom->CreateElement ( 'child', 'The Child' . $i );
			
			$id = $dom->CreateAttribute ( 'id' );
			$text = $dom->createTextNode ( $i );
			$id->appendChild ( $text );
			$double_id = $dom->CreateAttribute ( 'd_id' );
			$d_text = $dom->CreateTextNode ( $i * 2 );
			$double_id->appendChild ( $d_text );
			$child->appendChild ( $double_id );
			$child->appendChild ( $id );
			$element->appendChild ( $child );
		}
		$dom->appendChild ( $element );
		echo $dom->saveXML ();
	}

	public function EchoHead()
	{
		self::$_start = self::$_start * (- 1);
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
<script type="text/javascript" src="/js/error.js"></script>
</head>
<body>

HEAD;
	}

	public function EchoEnd()
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
		$str1 = ereg_replace ( "([0-9]{1,})-([0-9]{1,2})-([0-9]{1,2})", "\\1-\\2" . "year", $str );
		$str2 = ereg_replace ( "([0-9]{1,})-([0-9]{1,2})-([0-9]{1,2})", "\\2/\\3/\\1", $str );
		echo $str1 . '<br />';
		echo $str2;
	}

	public function StrFunction()
	{
		$len = 8;
		$string = 'PHP 有支援很方便的 function 可以直接達到此功能.';
		//$string = 'AF,S F,ADFSA,DFDAS  A AGHGHGH    FFFFFHHHHHHHHHJJJJJJPPPPPPOOOOOOOOOOO';
		
		$string = strip_tags ( $string );
		//$string = mb_strimwidth ( $string, 0, $len, '...', 'UTF-8' );
		$string = mb_substr ( $string, 0, $len, 'UTF-8' );
		$string .= (mb_strlen ( $string, 'UTF-8' ) < $len) ? '...' : '';
		echo $string . "--" . strlen ( $string ) . "<br />\r\n";
	}

	public function DoTest()
	{
		//echo time(),'<br />',strtotime(date('Y-m-d'));
		//var_dump(strlen(0)==strlen(''));
		//ToEcho(get_headers("http://google.com/11111gu.php"),false);
		//ToEcho(get_headers("http://google.com"));
		//trigger_error("");
		//var_dump(empty($_GET['OK']));	// no notice
		//var_dump($_GET['OK']!='');	//notice
		//var_dump(is_null($_GET['OK']));  //notice
		//var_dump(count($_GET['OK']));  //notice
		//ToEcho(time(),true);
		$str = "谷歌1010百度";
		$str2 = "0123456789";
		echo truncateText ( $str, 7 ), '<br />';
		echo truncateText ( $str2, 7 ), '<br />';
		echo SubstrGb ( $str, 0, 7 ), '<br />';
		echo SubstrGb ( $str2, 0, 7 ), '<br />';
		$arr = array ('000' );
		echo $str . ( string ) $arr;
		var_dump ( ( string ) array ('000' ) );
		$arr [] ['a'] = 'aa';
		$arr [] ['b'] = 'bb';
		ToEcho ( $arr );
		ToEcho ( $_SERVER );
		
		echo date ( 'Y-m-d H:i:s', strtotime ( '+1 day', strtotime ( '2010-02-28 00:00:00' ) ) ), '<br />', $ip_long = sprintf ( '%u', ip2long ( '192.192.168.192' ) ), '<br />', $ip_long, '<br />', '<b>' . long2ip ( sprintf ( '%d', $ip_long ) ) . '</b>', //wrong
long2ip ( sprintf ( '%d', 3233851584 ) ); //right

	}

	public function TryCatch()
	{
		try
		{
			goog();
			$error = 'Always throw this error';
			throw new Exception ( $error );
			//$this->b();
		} catch ( Exception $e )
		{
			trigger_error('111 wrong');
		}
	}

	public function PassReference($arr)
	{
		//		cann't pass value by reference
		$arr [1] = '11';
	}

	public function RegularExpressions()
	{
		// var_dump(preg_match("/^0|104$/","004"));  // wrong==>(0|1)04
		var_dump(preg_match("/^(0|(104))$/","004",$matches),$matches,'<br />');	// int(1)
		var_dump(preg_match("/^0|(104)$/","004",$matches),$matches,'<br />');	// int(1)
		var_dump(preg_match("/^z|food$/","z",$matches),$matches,'<br />');	// int(1)
		var_dump(preg_match("/^z|food$/","zood",$matches),$matches,'<br />');	// int(1)
		var_dump(preg_match("/^(0|(104))$/","104",$matches),$matches,'<br />');	// int(1)
		var_dump(preg_match("/^(0|104)$/","004",$matches),$matches,'<br />');	// int(1)
		var_dump(preg_match("/^(0|104)$/","104",$matches),$matches,'<br />');	// int(1)
		var_dump(preg_match("/^(0|104)$/","004",$matches),$matches,'<br />');		// int(0)
		var_dump(preg_match("/^(0|104)$/","404",$matches),$matches,'<br />');		// int(0)
		var_dump(preg_match("/^(0|99|100|101)$/","101",$matches),$matches,'<br />');		// int(0)
		
	}
	
	public function UrlFuncTest()
	{
		$url = "http://zhiyao.gongye360.com/index.html?a=1&b=2";
		var_dump(http_build_query(array('a'=>1,'b'=>2)));//string(7) "a=1&b=2" // array() ""
		var_dump(parse_url($url));
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
		var_dump(pathinfo($url));
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
		echo self::GetSuffixOfUrl($url);
	}
	
	public static function GetSuffixOfUrl($url)
	{
		$parse_url = parse_url($url);
		$pathinfo = pathinfo($parse_url['path']);
		return $pathinfo['extension'];
	}
	public function __destruct()
	{
		$this->EchoEnd ();
	}
	
}

//echo Pager(100,empty($_GET['pn'])?1:$_GET['pn'],5,'index.php',array('a'=>1,'b'=>2));
//ToEcho(range(1,1));
echo '<a href="'.GetFormatUrl('',array('a'=>'bb','b'=>array(1,2,3))).'" >'.GetFormatUrl('',array('a'=>'bb','b'=>array(1,2,3))).'</a>';
if(!empty($_GET))
	print_r($_GET);
$a = new MyClass ( );
//$a->TryCatch();
//$a->ArrayAdd();
//$a->RegularExpressions();
//$a->UrlFuncTest();
//
/** cann't pass value by reference
$arr[0] = '00';
$arr[1] = '00';
PassReference($arr);
var_dump($arr);
 ***/
?>
