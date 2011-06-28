<?php
define( 'INC_DIR', $_SERVER ['DOCUMENT_ROOT'].'/include' );
require_once INC_DIR."/func/function_360.php";
require_once INC_DIR."/func/str_func.php";
require_once INC_DIR."/func/func.php";
require_once INC_DIR."/func/grab_function.php";
require_once INC_DIR."/class/phpQuery/phpQuery.php";
define('FIELDS_SIZE',15);
define("URL_RAND_SIZE",6);
//define("REDIRECT_URL","http://".randomLetter(URL_RAND_SIZE).".file.800mei.net/redirect.php");
define("REDIRECT_URL","http://www.gongye360.com/redirect.php");
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
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.form.js"></script>
</head>
<body>
<!------- ----------->
HEAD;
	}

	private static function EchoEnd()
	{
		echo <<<FOOT
<!------- ----------->
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

	public function ImgDisInCode()
	{
		$url = '/home/acer/Pictures/Photos/trash_ie6.gif';
		$mime = mime_content_type($url);
		$data = base64_encode(file_get_contents($url));
		$src = "data:{$mime};base64,{$data}";
		echo <<<IMG
			<img src="{$src}" onload="this.height=100;this.weight=100;" />
			<img src="/image/default.gif" />
IMG;
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

	public function ChargetCode()
	{
		$text = '网页中添加百度搜索框';
		$rs =	iconv ( 'UTF-8' , 'GB2312' , $text );
		var_dump(urlencode($text));
		 echo json_encode($text);
		 $cls_path = '3_4_1847';
		 echo $cls_path = str_replace('_','\_',$cls_path);
	}

	
	public function SomeKnowlegeTest()
	{
		//echo date('Y-m-d H:i:s',strtotime('08:70')); 
		//echo date('Y-m-d H:i:s',strtotime('now'));
		//var_dump(preg_match('/^\d{2}:\d{2}~\d{2}:\d{2}$/',"00:00~23:299"));
		//var_dump(strtotime('08:70')); //false
		//var_dump(preg_match('/^((\d{1,2}|\d{1,2}~\d{1,2})\,)*(\d{1,2}|\d{1,2}~\d{1,2})$/',"1,2,3~6"));
	}
	
	
	public static function str_getcsv_self($input, $delimiter=',', $enclosure='"', $escape=null, $eol=null) 
	{
		  setlocale(LC_ALL, 'zh_CN UTF-8');
		  $temp=fopen("php://memory", "rw");
		  fwrite($temp, $input);
		  fseek($temp, 0);
		  $r=fgetcsv($temp, 4096, $delimiter, $enclosure);
		  fclose($temp);
		  return $r;
		 //fgetcsv 读取中文可能出错,要用setlocale(LC_ALL, 'nl_NL');
		// utf-8
		//setlocale(LC_ALL, 'en_US.UTF-8');
		// 简体
		//setlocale(LC_ALL, 'zh_CN');
		/**http://hi.baidu.com/qiaoyuetian/blog/item/40e4953d19dba8e43d6d9712.html
		 * 以下是常用的地区标识
		zh_CN GB2312
		en_US.UTF-8 UTF-8
		zh_TW BIG5
		zh_HK BIG5-HKSCS
		zh_TW.EUC-TW EUC-TW
		zh_TW.UTF-8 UTF-8
		zh_HK.UTF-8 UTF-8
		zh_CN.GBK GBK
			 */
	}

	
	public function __destruct()
	{
		self::EchoEnd ();
		//var_dump(preg_match("/[\xB0-\xF7][\xA1-\xFE]/",'001中国',$matches),$matches); 检测是否含有中文
		//换行符 chr(10)  换行符使用\n时，要用双引号包括
		//echo ord("\n"); //10
		//define('CODELIST',"ASCII,GBK,GB2312,big5,UTF-8,CP936,EUC-CN,BIG-5,EUC-TW");
//		var_dump(strtotime('2010-12-21 00:00:00'));
//		echo preg_replace("/[\\/\\\]+/",'/','//d//e//\\f');
//		echo $a = http_build_query(array("id_str"=>array(1,2,3)));
//		preg_match("/\d{1,3}?/","1234",$matches);
//		print_r($matches);
//		$a = "81 \n";++$a;//$a 的值不会发生改变
	}
	
	public function AddLinkForKeyWord($content='',$keywords=array())
	{
		$content = <<<CONTENT
<p>
	工业自动化控制是工业技术进步的重要方向，是机电一体化、推进两化融合的基础工作。但是，多年来一直缺少国家层面的关注和战略性安排，处于严重落后阶段，并存有重大隐患。</p>
<p>
	四是配套政策跟进，如首台套须给以足够支持、资助示范工程、试运行改进、首台套采购政策等。<br />
	&nbsp;</p>		
CONTENT;
		$keywords = array(
			array(
				'keyword'=>'洁净室',
				'link'=>'http://jiejingshi11.com'
			),
			array(
				'keyword'=>'ff',
				'link'=>'http://beng22.com'
			),
			array(
				'keyword'=>'泵',
				'link'=>'http://beng33.com'
			),
			array(
				'keyword'=>'PLC',
				'link'=>'http://gongkong.gongye360.com/plc/index.html'
			),
			array(
				'keyword'=>'ff',
				'link'=>'http://beng55.com'
			),
			array(
				'keyword'=>'ff',
				'link'=>'http://beng66.com'
			),
		);
		$i = 0;
		$link_num = 5;
		foreach($keywords as $v)
		{
			$find_keyword = str_replace(array('/'),array('\/'),$v['keyword']);
			if($i>=$link_num)
				break;
			$pos = mb_stripos($content,$v['keyword'],0);
			if($pos === FALSE)
				continue;
			if($pos<=3)
			{
				$content = preg_replace("/{$find_keyword}/i","<a href=\"{$v['link']}\" target=\"_blank\">\\0</a>",$content,1);
				$i++;
			}
			else
			{
				while(TRUE)
				{
					if($i>=$link_num)
						break 2;
					$tmp_before = mb_substr($content,0,$pos);
					$tmp_after = mb_substr($content,$pos);
					$pos1 = strripos($tmp_before,'<a ');
					$pos2 = strripos($tmp_before,'</a>');
					if($pos2>=$pos1&&strrpos($tmp_before,'>')>=strrpos($tmp_before,'<'))
					{
						$tmp_after = preg_replace("/{$find_keyword}/i","<a href=\"{$v['link']}\" title=\"{$v['keyword']}\" target=\"_blank\">\\0</a>",$tmp_after,1);
						$i++;
						$content = $tmp_before.$tmp_after;
						break;
					}
					else
					{
						$pos = mb_stripos($content,$v['keyword'],mb_strlen($tmp_before.$v['keyword']));
						if($pos === FALSE)
							break;
						else 
							continue;
					}
				}
			}
		}
		echo $content;
	}
	
	public function PqLearn()
	{
		$href="http://comp_23914.cn.chemnet.com/show/clist--.html";
		$href="http://comp_21447.cn.chemnet.com/show/pdetail--406213.html";
		$b = phpQuery::newDocumentFile($href);
//		phpQuery::newDocumentHTML("<div>");
		print_r(phpQuery::$documents);
		$a = phpQuery::newDocumentFile($href);
		$id = $a->getDocumentID();
//		array_pop(phpQuery::$documents);
		phpQuery::unloadDocuments($id);
		phpQuery::selectDocument($b->getDocumentID());
		var_dump($b->getDocumentID(),pq('body')->getDocumentID());//equal to 
		$prod['title'] = trim(pq("div#vipright_1_1>div.vipright_1s>dl>dt>h1")->text());
		echo $prod['title'];
	}
	
	public function Test()
	{
//		$content = str_replace("<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=gb2413\" \/>","<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=gb2312\" \/>",$content);
//		:TOTHINK: //双引号转义替换不成功
//		$content = str_replace('<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />','<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />',$content);
//		phpQuery::newDocument($content);
//		echo $href = pq("div#pages>a.cur")->next("a")->attr("href");
//
//		$file_content = str_replace('<meta http-equiv="Content-Type" content="text/html; charset=gb2413" />','<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />',$file_content);
//      phpQuery::newDocument($file_content,"text/html;charset=utf-8");
//		echo $detail = pq("div.right_box_bg:eq(0)>div.nr_box:eq(0)")->html();

		$href = "http://www.cnsb.cn/html/products/5751/info_show_5751864.html";
		phpQuery::newDocumentFile($href,"text/html;charset=utf-8");
//		phpQuery::newDocument($content);
//		$content = mb_convert_encoding($content,'UTF-8','GB2312');
	

	}
	
	public function CorpInfoKey($key)
	{
		switch ($key)
		{
			case '公司名称：':
				return 'corp_name';	
			case '联系人：':
				return 'contact';	
			case '联系电话：':
				return 'tel';	
			case '传真：':
				return 'fax';
			case '手　　机：':
				return 'mobile';	
			case '电子邮箱：':
				return 'email';	
			case '公司网站：':
				return 'site';	
			case '地  区：':
				return 'region';	
			case '联系地址：':
				return 'address';	
			case '邮　　编：':
				return 'zipcode';
		}
	}
	
	public function Xpath2JqueryPath($xpath = '/html/body/table[6]/tbody/tr/td[3]/table[3]',$has_tbody = TRUE)
	{
		if(empty($xpath))
			return '';
		$tmp_arr = explode("/",substr($xpath,1));
		$except_arr = array('html','body','tbody');
		foreach ($tmp_arr as $key=>&$val)
		{
			if($val=='html')
			{
				unset($tmp_arr[$key]);
				continue;				
			}
			elseif( !in_array($val,$except_arr) && preg_match("/[a-z1-9]+(\[([1-9]+)\])/",$val,$matches))
			{
				$eq = $matches[2] - 1;
				$val = preg_replace("/\[[1-9]+\]/",":eq($eq)",$val);
			}
			elseif(!in_array($val,$except_arr))
			{
				$val = $val.':eq(0)';
			}
		}
		$rs = implode('>',$tmp_arr);
		if(!$has_tbody)
			return str_replace("tbody>",'',$rs);
		return $rs;
//      echo exec("./php_test 'a' 'bb b'");
	}
	
    public static function StatisticReplace($content)
    {
    	if(empty($content))
    		return '';
//    	$content = str_replace('[URL_RAND]',randomLetter(URL_RAND_SIZE),$content);
    	$pattern = "/(<a[^>]+href\s*=\s*[\"\']?)([^\"\' ]+)([\"\']?.*?\>[\w\W]*?\<\/a\>)/i";
		return preg_replace_callback($pattern,'PregReplace',$content);
    }
    
    public static function ContentStatisticReplace($content)
    {
    	if(empty($content))
    		return '';
    	$pattern = "/(<a[^>]+href\s*=\s*[\"\']?)([^\"\' ]+)([\"\']?.*?\>[\w\W]*?\<\/a\>)/i";
		return preg_replace_callback($pattern,'PregReplaceSiteContent',$content);
    }
	
	public function CurlTest()
	{
		$post_data = array();
		$post_data['clientname'] = "test08";
		$post_data['clientpasswd'] = "test08";
		$post_data['join_batch'] = "杭州 4/28";
		$post_data['p'] = "gongye360000ii";
		$url='http://www.gongye360.com/static/?type=cnim';
		$o="";
		foreach ($post_data as $k=>$v)
		{
		   $o.= "$k=".urlencode($v)."&";
		}
		$post_data_str=substr($o,0,-1);//clientname=test08&clientpasswd=test08&submit=submit
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		//为了支持cookie
		curl_setopt($ch, CURLOPT_COOKIEJAR, './cookie.txt');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
		$result = curl_exec($ch);
		echo $result;
	}

}
		function UrlRandomReplaceOneByOne($mathes)
		{
			return randomLetter(6);
		}
$new_class = new MyClass ( );
//$new_class->Test();
//echo $a->Xpath2JqueryPath('/html/body/div[9]/div/div/div[3]/table/tbody/tr/td[2]',FALSE);
//exit();
$test = <<< HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>凌华科技：军用宽温级产品</TITLE>
<META content="text/html; charset=UTF-8" http-equiv=Content-Type>
<META name=ROBOTS content=ALL>
<META content=no-cache http-equiv=Pragma>
<META name=GENERATOR content="MSHTML 8.00.7600.16766"></HEAD>
<BODY leftMargin=0 rightMargin=0 topMargin=0>
<DIV align=center>
  <TABLE border=0 cellSpacing=0 cellPadding=0 width=720>
    <TBODY>
      <TR> 
        <TD height=8></TD>
      </TR>
      <TR> 
        <TD><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=1"><IMG src="images/e1.jpg" width="720" height="449" 
      border=0></A></TD>
      </TR>
      <TR>
        <TD><img src="images/e3.jpg" width="720" height="28"></TD>
      </TR>
    </TBODY>
  </TABLE>
<TABLE border=0 cellSpacing=0 cellPadding=0>
  <TBODY>
  <TR>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=2"><IMG src="images/e-cm720.jpg" alt="CoreModule 720" width="145" height="166" 
      border=0></A></TD>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=4"><img src="images/e-etx-pvr.jpg" alt=ETX-PVR width="143" height="166" 
      border=0></a></TD>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=6"><IMG src="images/e-express-cbr.jpg" alt=Express-CBR width="131" height="166" 
      border=0></A></TD>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=8"><IMG 
  src="images/e-ruff735.jpg" alt="Ruffsystem 735/840" width="167" height="166" 
      border=0></A></TD>
      </TR>
  <TR>
        <TD width=180 align=left><IMG src="images/4085.gif" width="116" height="30" border=0></TD>
        <TD width=180 align=left><IMG src="images/4085.gif" width="116" height="30" border=0></TD>
        <TD width=180 align=left><IMG src="images/4085.gif" width="116" height="30" border=0></TD>
        <TD width=180 align=left><IMG src="images/4075.gif" width="116" height="30" border=0></TD>
      </TR>
  <TR>
    <TD height=3></TD></TR>
  <TR>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=3"><IMG src="images/quote-cn.gif" alt="Get a Quote" width="131" height="25" 
      border=0></A></TD>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=5"><IMG src="images/quote-cn.gif" alt="Get a Quote" width="131" height="25" 
      border=0></A></TD>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=7"><IMG src="images/quote-cn.gif" alt="Get a Quote" width="131" height="25" 
      border=0></A></TD>
        <TD width=180 align=left><A target="_blank"  
      href="http://cm.adlinktech.com/login/EventAnalytics.asp?code=0511062802&no=9"><IMG src="images/quote-cn.gif" alt="Get a Quote" width="131" height="25" 
      border=0></A></TD>
      </TR>
  <TR>
    <TD height=15></TD></TR></TBODY></TABLE>
<TABLE border=0 cellSpacing=0 cellPadding=1 width=720>
  <TBODY>
  <TR>
    <TD bgColor=#808080 width="100%"></TD>
    <TD bgColor=#a80000 height=5></TD></TR>
  <TR bgColor=#ffffff>
    <TD bgColor=#e4e4e4 width="100%" align=right>
      <TABLE style="LINE-HEIGHT: 13pt; FONT-FAMILY: Arial,Verdana,Helvetica,sans-serif; FONT-SIZE: 11px;text-decoration:none" border=0 cellSpacing=0 cellPadding=8>
        <TBODY>
        <TR>
                <TD align=right> <P>如需更多信息，请与<A target="_blank"  style="text-decoration:none;"
            href="http://www.adlinktech.com/cn/contact_us/contactus.html"><B>凌华科技</B></A>联系<br>
                    Tel: 010-58858666&nbsp;&nbsp;Fax: 010-58858625<BR>
                    <IMG src="images/wheretobuy.gif" width="14" height="14" border=0 
            align=absMiddle> <A target="_blank"  style="text-decoration:none;"
            href="http://www.adlinktech.com/index.html?edm2011112-sandybridge">Web: 
                    www.adlinktech.com</A><BR>
                    <IMG 
            src="images/mail.gif" width="14" height="11" border=0 align=absMiddle> 
                    email: <A target="_blank"  style="text-decoration:none;"
            href="mailto:market@adlinktech.com">market@adlinktech.com</A></P><font face="Courier New, Courier, monospace"></font>
                  </TD></TR></TBODY></TABLE></TD>
        <TD><A target="_blank"  style="text-decoration:none;"
      href="http://www.adlinktech.com/cn/index.html"><IMG 
  src="images/adlinkTech_logo.gif" alt="ADLINK Technology Inc." width="263" height="63" 
      border=0></A></TD>
      </TR></TBODY></TABLE>
     </DIV>
	  
	  </BODY>
	  </HTML>

HTML;
//$b = $new_class->StatisticReplace($test);
$b = $new_class->ContentStatisticReplace($test);
echo $b;
//exit('test');
//$b = preg_replace_callback('/\[URL_RAND\]/','UrlRandomReplaceOneByOne',$test);
//echo $b;
//echo JumpTo360("http://afncgm.file.800mei.net/adlinktech/signup.html");
//$a->RandomHz();
function JumpTo360($url)
{
	return preg_replace("/http:\/\/[a-z0-9]+.file.800mei.net/","http://www.gongye360.com",$url,1);
}

function PregReplace($matches)
{
	if('#'==$matches[2] || stripos($matches[2],"javascript:") !== FALSE || stripos($matches[2],"mailto:")!==FALSE || stripos($matches[2],REDIRECT_URL)!==FALSE )
	{
		return $matches[1].$matches[2].$matches[3];				
	}
	$TEST = TRUE;
	if($TEST)
		return $matches[1].REDIRECT_URL."?u=".urlencode("http://www.gongye360.com/edm/laterbatch/".$matches[2]."l").'&email_co=guchuan@gongye360.com&id_co=1000'.$matches[3];
	else 
		return $matches[1].REDIRECT_URL."?u=".urlencode($matches[2]).'&amp;[param]'.$matches[3];
}

function PregReplaceSiteContent($matches)
{
	if('#'==$matches[2] || stripos($matches[2],"javascript:") !== FALSE || stripos($matches[2],"mailto:")!==FALSE || stripos($matches[2],REDIRECT_URL)!==FALSE )
	{
		return $matches[1].$matches[2].$matches[3];				
	}
	//return $matches[1].REDIRECT_URL."?u=".urlencode($matches[2]).'&amp;[param]'.$matches[3];
	return $matches[1]."<?php echo statisticUrlGen('".$matches[2]."'); ?>".$matches[3];
}

function getUnicodeFromOneUTF8($word) 
{  
  //获取其字符的内部数组表示，所以本文件应用utf-8编码！  
  if (is_array( $word))  
    $arr = $word;  
  else    
    $arr = str_split($word);   
  //此时，$arr应类似array(228, 189, 160)  
  //定义一个空字符串存储  
  $bin_str = '';  
  //转成数字再转成二进制字符串，最后联合起来。  
  foreach ($arr as $value)  
    $bin_str .= decbin(ord($value));  
  //此时，$bin_str应类似111001001011110110100000,如果是汉字"你"  
  //正则截取  
  $bin_str = preg_replace('/^.{4}(.{4}).{2}(.{6}).{2}(.{6})$/','$1$2$3', $bin_str);  
  //此时， $bin_str应类似0100111101100000,如果是汉字"你"   
//  return bindec($bin_str); //返回类似20320， 汉字"你"  
  return dechex(bindec($bin_str)); //如想返回十六进制4f60，用这句  
}

function RandZhong($size = 1)
{
	$code = '';
//	while ($size --)
//	{
//		$code .='\u'. rand(0,9).dechex(rand(0,15)).dechex(rand(0,15)).dechex(rand(0,15));
//	}
//	$code = '"'.$code.'"';
//	echo json_decode($code);
	$start = hexdec('4e00');
	$end = hexdec('9fa5');
	while($size --)
	{
		$code .= '\u'.dechex(rand($start,$end));
	}
	$code = '"'.$code.'"';
	echo json_decode($code);
}
function csubstr($string,$start,$length)
{
	$str="";
	$len=$start+$length;
	for($i=$start;$i<$len;$i++)
	{
		if(ord(substr($string,$i,1))>0xa0)
		{
			$str.=substr($string,$i,2);
			$i++;
		}
		else
			$str.=substr($string,$i,1);
	}
	
	return $str;
} 
function getArray($size = 1)
{
	$str = <<<HTML
传热设备	http://www.ccen.net/company/company_list.php?gopage=0&sortid_1=1
粉碎设备	http://www.ccen.net/company/company_list.php?gopage=0&sortid_1=2
混合设备	http://www.ccen.net/company/company_list.php?gopage=0&sortid_1=3
分离设备	http://www.ccen.net/company/company_list.php?gopage=0&sortid_1=4
压力容器	http://www.ccen.net/company/company_list.php?gopage=0&sortid_1=5
HTML;
	$tmp_arr = explode("\n",$str);
	$rs = array();
	$i = 0;
	foreach ($tmp_arr as $v)
	{
		$t_arr = array();
		$t_t = explode("	",$v);
		$t_arr["name"] = str_replace(' ','->',$t_t[0]);
		$t_arr["code"] = "";
		$t_arr["is_over"] = 'FALSE';
		$t_arr["url"] = $t_t[1];
		$rs[$i++] = $t_arr;
	}
	echo "array ( \n";
	foreach ($rs as $k=>$parent_v)
	{
		echo "	{$k} => array( \n";
		foreach ($parent_v as $key=>$v)
		{
			$dot = $key == 'url' ? '' : ',';
			if($key == 'is_over')
				echo "		'{$key}'=>{$v}{$dot}\n";
			else 
				echo "		'{$key}'=>'{$v}'{$dot}\n";
		}
		echo "		),\n";
	}
	echo ")\n";
}
//getArray();
$ttt = <<<TTHTML
      <table width="95%" align="center" cellpadding="0" cellspacing="0" style="font-size:12px; line-height:22px;">
      <tr>
        <td height="15" colspan="2"></td>
      </tr>
      <tr>
        <td height="25" colspan="2"><span style="font-size:14px; font-weight:bold; color:#3366ff">前 言</span></td>
TTHTML;
function randomClass($matches)
{
    return $matches[1] . ' class="' . randomLetter(5000) . '">';
}


function createRandomHtml($content, $tag = 'td')
{
   return preg_replace_callback("/(<{$tag}[\s]*[^>]*)>/i", 'randomClass', $content);
}
//echo "SELECT COUNT(1) FROM `grab_content_url` WHERE `hash`='".md5('http://www.cnsb.cn/html/products/5719/info_show_5719162.html')."'";exit();
//echo createRandomHtml($ttt);

function RecordError($data)
{
	$referer = empty($_SERVER['HTTP_REFERER']) ? 'referer' : $_SERVER['HTTP_REFERER'];
	if(empty($data['email']) || substr($data['email'],-1)=='7')
		error_log("{$data['email']}  => {$referer} \n", 3, "my-errors.log");
}
$data['email'] = '127';
RecordError($data);
?>

