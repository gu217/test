<?php
include "function_gongye360.php";
include "str_func.php";
function ToEcho($var,$quit=false)
{
	echo '<pre>',var_dump($var),'</pre>';
	if($quit)
	{
		exit();
	}
} 
class MyClass
{
	public static $_start = 1;
	public static $_end = 1;
	public function __construct()
	{
		$this->EchoHead();
	}
	public function EchoHead()
	{
		self::$_start = self::$_start*(-1);
		echo <<<HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title> go.com </title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="Generator" content="EditPlus">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<script type="text/javascript" src="/js/error.js"></script>
</head>
<body>

HEAD;
	}
	public function Test()
	{
		echo date('Y-m-d H:i:s',strtotime('+1 day',strtotime('2010-02-28 00:00:00'))),
		'<br />',
		$ip_long = sprintf('%u',ip2long('192.192.168.192')),
		'<br />',
		$ip_long,
		'<br />',
		'<b>'.long2ip(sprintf('%d',$ip_long)).'</b>',//wrong
		long2ip(sprintf('%d',3233851584))//right
		;
	}
	
	public function ArrayRange()
	{
		$arr_char = range('A','z');
		$arr_num = range(1,58);
		$arr_new = array_combine($arr_num,$arr_char);
		$arr_new_flip = array_flip($arr_new);
		//ToEcho(array_keys($arr_new_flip,58));
		//ToEcho(array_search(58,$arr_new_flip));
		//ToEcho($arr_new_flip+$arr_new_flip);
		shuffle($arr_new);
		$arr_merge_recursive = array_merge_recursive($arr_new_flip,$arr_new);
		//ToEcho(array_merge_recursive(),false);
		ToEcho($arr_merge_recursive);
		ToEcho(array_key_exists(57,$arr_merge_recursive));
	}
	public function ArrayMultisort()
	{
		$ar = array(
			   array("10", 11, 100, 100, "a"),
			   array(   1,  2, "2",   3,   1)
			  );
		array_multisort($ar[0], SORT_ASC, SORT_STRING,
						$ar[1], SORT_NUMERIC, SORT_DESC);
		ToEcho($ar);
	}
	
	public function TryCatch()
	{
		try {
			$error = 'Always throw this error';
			throw new Exception($error);
			// Code following an exception is not executed.
			//echo 'Never executed';

		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "()()\n";
		}
		// Continue execution
		//echo 'Hello World';
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
       echo truncateText($str,7),'<br />';
       echo truncateText($str2,7),'<br />';
       echo SubstrGb($str,0,7),'<br />';
       echo SubstrGb($str2,0,7),'<br />';
       $arr = array('000');
       echo $str.(string)$arr;
       var_dump((string)array('000'));
       $arr[]['a'] = 'aa';
       $arr[]['b'] = 'bb';
       ToEcho($arr);

	}

	public function DomClass()
	{
		$dom = new DOMDocument('1.0','utf-8');
		$element = $dom->CreateElement('test','The Root');
		for($i=0;$i<10;$i++)
		{
			$child=$dom->CreateElement('child','The Child'.$i);
			
			$id = $dom->CreateAttribute('id');
			$text = $dom->createTextNode($i);
			$id->appendChild($text);
			$double_id = $dom->CreateAttribute('d_id');
			$d_text = $dom->CreateTextNode($i*2);
			$double_id->appendChild($d_text);
			$child->appendChild($double_id);
			$child->appendChild($id);
			$element->appendChild($child);
		}
		$dom->appendChild($element);
		echo $dom->saveXML();
	}
	public function EchoEnd()
	{
		self::$_end = self::$_end*(-1);
		echo <<<FOOT

</body>
</html>
FOOT;
	}
	public function __destruct()
	{
		$this->EchoEnd();
	}
}
$a = new MyClass;
$a->DoTest();
?>
