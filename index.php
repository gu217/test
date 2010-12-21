<?php
define( 'INC_DIR', $_SERVER ['DOCUMENT_ROOT'].'/include' );
require_once INC_DIR."/func/function_360.php";
require_once INC_DIR."/func/str_func.php";
require_once INC_DIR."/func/func.php";
define('FIELDS_SIZE',15);

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
	public function ForTest()
	{
		$taskid=20;
		$batchnum=1;
		$contid=1;
		$usertype=4;
		//$userindustry = "3_4,3_419_1871,3_1358_1826,3_90";
		$userindustry = "3_4";
		$industy_str = '';
		$topic_str = '';
		$industy_arr = array();
		$topic_arr = array();
		if(!strstr($userindustry,'all'))
		{
			$userindustry_arr = explode(',',$userindustry);
			foreach($userindustry_arr as $v)
			{
				$line_cnt = substr_count($v,'_');
				if($line_cnt==1)
				{
					//$industy_arr[] = $v;
					$industy_arr[] =  (int)substr($v,strrpos($v,'_')+1);
				}
				elseif($line_cnt==2)
					$topic_arr[] =  (int)substr($v,strrpos($v,'_')+1);
			}
			
			//if(!empty($industy_arr))
			//	$industy_str = "'".implode("','",$industy_arr)."'";
			$industy_str = implode(',',$industy_arr);
			$topic_str = implode(',',$topic_arr);
		}
		$usertype_where_str = '';
		if($usertype>0)
			$usertype_where_str = " AND u.user_type_id=$usertype";
				
		$industy_where_str = '';	
		if(!empty($industy_str))
		{
			$where_sql[] = "SELECT {$contid} AS contid, {$batchnum} AS batchnum, u.userid, u.user_type_id AS usertype, u.username, u.nickname, u.corp_name AS corpname, u.email, {$taskid} AS taskid
							FROM T_GTUser u, user_industry ui
							WHERE u.del_flag =0
							AND u.audit_flag =1
							AND u.userid = ui.user_id AND ui.industry_id IN({$industy_str}) {$usertype_where_str}";
		}
			
		$topic_where_str = '';
		if(!empty($topic_str))
		{
			$where_sql[] = "SELECT {$contid} AS contid, {$batchnum} AS batchnum, u.userid, u.user_type_id AS usertype, u.username, u.nickname, u.corp_name AS corpname, u.email, {$taskid} AS taskid
							FROM T_GTUser u, T_UserTopic t
							WHERE u.del_flag =0
							AND u.audit_flag =1
							AND u.userid = t.user_id AND  t.topic_id IN({$topic_str}) {$usertype_where_str}";
		}
				
		if(empty($where_sql))
		{
			$sql = "SELECT {$contid} AS contid, {$batchnum} AS batchnum, u.userid, u.user_type_id AS usertype, u.username, u.nickname, u.corp_name AS corpname, u.email, {$taskid} AS taskid
					FROM T_GTUser u
					WHERE u.del_flag = 0
					AND u.audit_flag = 1
					GROUP BY u.email
					ORDER BY u.userid";
		}
		else 
		{
			$sql = 'SELECT a.* FROM ('.implode(' UNION ',$where_sql).') AS a GROUP BY a.email ORDER BY a.userid';
		}	
		echo $sql,'<br />';
		echo $insertsql = "INSERT IGNORE INTO T_NewsLetter_Record(contid,batchnum,userid,usertype,username,nickname,corpname,email,taskid) ".$sql;
	}
	
	public function EmailTaskTest($taskid,$batchnum,$contid,$email)
	{
		if(empty($taskid)||empty($batchnum)||empty($contid)||empty($email))
			return false;
		$var_arr = array();
		if(!is_array($email))
		{
			$var_arr[] = "({$contid},{$batchnum},0,0,'','','','{$email}',{$taskid})";
		}
		else
		{
			foreach($email as $v)
			{
				if(empty($v['email']))
					continue;
				$userid = empty($v['userid']) ? 0 : (int)$v['userid'];
				$usertype = empty($v['usertype']) ? 0 : (int)$v['usertype'];
				$username = empty($v['username']) ? '' : $v['username'];
				$nickname = empty($v['nickname']) ? '' : $v['nickname'];
				$corpname = empty($v['corpname']) ? '' : $v['corpname'];
				
				$var_arr[] = "({$contid},{$batchnum},$userid,$usertype,'{$username}','{$nickname}','{$corpname}','{$v['email']}',{$taskid})";
			}
		}
		echo $values = implode(',',$var_arr);
		$a->EmailTaskTest(1,1,1,array(0=>array('email'=>'gpc@gongye360.com'),1=>array('userid'=>1,'usertype'=>4,'username'=>'username','nickname'=>'nickname','email'=>'gpc@gongye360.com')));
	}
	public function SomeKnowlegeTest()
	{
		//echo date('Y-m-d H:i:s',strtotime('08:70')); 
		//echo date('Y-m-d H:i:s',strtotime('now'));
		//var_dump(preg_match('/^\d{2}:\d{2}~\d{2}:\d{2}$/',"00:00~23:299"));
		//var_dump(strtotime('08:70')); //false
		//var_dump(preg_match('/^((\d{1,2}|\d{1,2}~\d{1,2})\,)*(\d{1,2}|\d{1,2}~\d{1,2})$/',"1,2,3~6"));
			$send_date = "1~7,6";
			if(!preg_match('/^((\d{1,2}|\d{1,2}~\d{1,2})\,)*(\d{1,2}|\d{1,2}~\d{1,2})$/',$send_date))
			{
				exit('preg');
			}
			$frequency = 2;
			$max = 0;
			if($frequency == 2||$frequency == 3)
				$max = 7;
			elseif ($frequency == 4)
				$max = 31;
			if(!empty($max))//只有当frequency为每周,每两周,每月时才检查日期合法性,其它情况此日期无效
			{
				$send_date_arr = explode(',',$send_date);
				foreach ($send_date_arr as $v)
				{
					$s_d_arr = explode('~',$v);
					switch(count($s_d_arr))
					{
						case 1:
							if($s_d_arr[0]<1||$s_d_arr[0]>$max)
							{
									var_dump($s_d_arr,$max);
									exit(1);
							}
							break;
						case 2:
							if($s_d_arr[0]<1||$s_d_arr[0]>$max||$s_d_arr[1]<1||$s_d_arr[1]>$max||$s_d_arr[0]>=$s_d_arr[1])
							{
									var_dump($s_d_arr,$max,count($s_d_arr));
									exit(2);
							}
							break;
					}
				}
			}
	}
	public static function getDays($startdate,$enddate,$senddate,$frequency)
	{
		//暂时没考虑设为1,3,5发信但时间范围内只有1，3的情况,在此考虑的都是一个完整周期
		$senddate_arr = explode(',',$senddate);
		$date_part_arr = array();
		foreach($senddate_arr as $k=>$v)
		{
			if(strrpos($v,'~')===false)
				continue;
			$temp_arr = explode('~',$v);
			$date_part_arr[] = range($temp_arr[0],$temp_arr[1]);
			unset($senddate_arr[$k]);
			unset($temp_arr);
		}
		foreach($date_part_arr as $d)
		{
			$senddate_arr = array_merge_recursive($senddate_arr,$d);
		}
		$senddate_arr = array_unique($senddate_arr);
		$size = sizeof($senddate_arr);
		switch ($frequency)
		{
			case 2://每周
				return $size;
			case 3://每两周
				return $size*2;
			case 4://每月
				return $size;
			case 5://每季度
				return $size*3;
			case 6://每半年
				return $size*6;
			case 7://每年
				return $size*12;
		}
	}
	
	public function getEmailNum($usertype,$topics)
	{
		$industy_str = '';
		$topic_str = '';
		$industy_arr = array();
		$topic_arr = array();
		if(!strstr($topics,'all'))
		{
			$userindustry_arr = explode(',',$topics);
			foreach($userindustry_arr as $v)
			{
				$line_cnt = substr_count($v,'_');
				if($line_cnt==1)//行业
					$industy_arr[] =  (int)substr($v,strrpos($v,'_')+1);
				elseif($line_cnt==2)//专题
					$topic_arr[] =  (int)substr($v,strrpos($v,'_')+1);
			}
			
			$industy_str = implode(',',$industy_arr);
			$topic_str = implode(',',$topic_arr);
		}
		$usertype_where_str = '';
		if($usertype>0)
			$usertype_where_str = " AND u.user_type_id=$usertype";
				
		$industy_where_str = '';	
		if(!empty($industy_str))
		{
			$where_sql[] = "SELECT u.email
							FROM T_GTUser u, user_industry ui
							WHERE u.del_flag =0
							AND u.audit_flag =1
							AND u.userid = ui.user_id AND ui.industry_id IN({$industy_str}) {$usertype_where_str}";
		}
			
		$topic_where_str = '';
		if(!empty($topic_str))
		{
			$where_sql[] = "SELECT u.email
							FROM T_GTUser u, T_UserTopic t
							WHERE u.del_flag =0
							AND u.audit_flag =1
							AND u.userid = t.user_id AND  t.topic_id IN({$topic_str}) {$usertype_where_str}";
		}
				
		if(empty($where_sql))
		{
			$sql = "SELECT u.email
					FROM T_GTUser u
					WHERE u.del_flag = 0
					AND u.audit_flag = 1
					";
		}
		else 
		{
			$sql = 'SELECT distinct a.email FROM ('.implode(' UNION ',$where_sql).') AS a';
		}	
		return $sql;
	}
	public function arrayList()
	{
		$industry = array(
							1=>array('name'=>'化工','code'=>'3_1358'),
							2=>array('name'=>'制药','code'=>'3_4'),
							3=>array('name'=>'物流与包装','code'=>'3_419'),
							4=>array('name'=>'仪器仪表','code'=>'3_631'),
							5=>array('name'=>'机械','code'=>'3_752'),
							6=>array('name'=>'工控','code'=>'3_90')
			);
		$corpType = array(
							1=>array('name'=>'生产商','code'=>'10'),
							1=>array('name'=>'代理商','code'=>'11'),
							1=>array('name'=>'系统集成商','code'=>'12'),
							1=>array('name'=>'高等院校','code'=>'13'),
							1=>array('name'=>'终端用户','code'=>'14'),
							1=>array('name'=>'设计院所','code'=>'15'),
							1=>array('name'=>'设备制造商','code'=>'16'),
							1=>array('name'=>'媒体出版','code'=>'132'),
							1=>array('name'=>'行业协会','code'=>'133'),
							1=>array('name'=>'政府机构','code'=>'134'),
							1=>array('name'=>'OEM厂商','code'=>'135'),
							1=>array('name'=>'会议展览','code'=>'467')
		);
	}
	/**
	 * 格式化输入文本
	 *
	 * @param string $text
	 * @return array   $format_info['flag'] //验证通过		$format_info['str'] //行数据  $format_info['arr'] //正确的数据分组
	 */
	
	public static function ParseText($text)
	{
		$format_info = array();
		$param_arr = explode("\n",$text);
		$size = count($param_arr);
		for($i=0;$i<$size;$i++)
		{
			$param_arr[$i] = trim($param_arr[$i]);
			$format_info[$i]['flag'] = false;
			$format_info[$i]['str'] = $param_arr[$i];
			if(!strpos($param_arr[$i],','))
			{
				$format_info[$i]['str'] .= ' 格式错误的行，没有分隔符';//格式错误的行，没有分隔符
				continue;
			}
			list($tem_arr['corpName'],$tem_arr['corpType'],$tem_arr['industry'],$tem_arr['corpNature'],$tem_arr['turnover_id'],$tem_arr['realName'],$tem_arr['gender'],$tem_arr['remail'],$tem_arr['mobile'],$tem_arr['tel'],$tem_arr['fax'],$tem_arr['address'],$tem_arr['zipCode'],$tem_arr['corpimage']) = explode(',',$param_arr[$i]);
			foreach ($tem_arr as &$t)
			{
				$t = trim($t);
			}
			if(!empty($tem_arr['corpimage']))
				$tem_arr['picUrl'] = str_replace('_thumb180','_thumb400',$tem_arr['corpimage']);
			
			if(count($tem_arr)!=FIELDS_SIZE)//字段与数据表字段不符
			{
				$format_info[$i]['str'] .= ' 字段数不正确,请检查有没有缺少分隔符';
				unset($tem_arr);				
				continue;
			}
			$format_info[$i]['flag'] = true;
			$format_info[$i]['arr'] = $tem_arr;
			unset($tem_arr);
		}
		return self::CheckParam($format_info);
	}
	
	/**
	 * 检查生产数组的的合法性
	 *
	 * @param array $arr
	 */
	public static function CheckParam($arr)
	{
		/** 字段检测
		$regular = array(
						0=>'/^[0-9a-zA-Z]+[0-9a-zA-Z-]+$/',//username
						1=>'/^.{6,18}$/',//password
						2=>'/^[^\s]{2,64}$/',//corpname
						3=>'/^.{2,16}$/',//realname
						4=>'/^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|com|gov|mil|org|edu|int)$/',//email
						5=>'/^([\+]?(\d){2})?1\d{10}$/'//mobile
						);
		**/
		$fields_name = array(
					0=>'用户名',
					1=>'密码',
					2=>'企业名称',
					3=>'联系人',
					4=>'Email',
					5=>'手机号'
		);
		foreach($arr as &$v)
		{
			if( !$v['flag'])
				continue;
			for($i=0;$i<6;$i++)
			{
				switch ($i)
				{
					case 0:
						$username_len = strlen($v['arr'][0]);
						if($username_len<2||$username_len>18)
						{
							$v['flag'] = false;
							$v['str'] .= " 用户名要大于等于2位，小于等于18位"; 
							unset($var['arr']);
							break 2;//退出for循环
						}
						elseif(!preg_match('/^[0-9a-zA-Z]+[0-9a-zA-Z-]+$/',$v['arr'][0]))
						{
							$v['flag'] = false;
							$v['str'] .= " 用户名由字母数字或者“-”组成"; 
							unset($var['arr']);
							break 2;//退出for循环
						}
						break;
					case 1:
						$password_len = strlen($v['arr'][1]);
						if($password_len<6||$password_len>18)
						{
							$v['flag'] = false;
							$v['str'] .= " 密码要大于等于6位，小于等于18位"; 
							unset($var['arr']);
							break 2;//退出for循环
						}
						break;
					case 2:
						$corpname_len = strlen($v['arr'][2]);
						if($corpname_len<2||$corpname_len>64)
						{
							$v['flag'] = false;
							$v['str'] .= " 企业名称要大于等于2位，小于等于30位"; 
							unset($var['arr']);
							break 2;//退出for循环
						}
						break;
					case 3:
						$realname_len = strlen($v['arr'][3]);
						if($realname_len<2||$realname_len>16)
						{
							$v['flag'] = false;
							$v['str'] .= " 联系人姓名要大于等于2位，小于等于16位"; 
							unset($var['arr']);
							break 2;//退出for循环
						}
						break;
					case 4:
						if(!preg_match('/^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|com|gov|mil|org|edu|int)$/',$v['arr'][4]))
						{
							$v['flag'] = false;
							$v['str'] .= " Email格式不正确"; 
							unset($var['arr']);
							break 2;//退出for循环
						}
						break;
					case 5:
						$mobile_len = strlen($v['arr'][5]);
						if($mobile_len<11||$mobile_len>14)
						{
							$v['flag'] = false;
							$v['str'] .= " 手机号码长度不正确"; 
							unset($var['arr']);
							break 2;//退出for循环
						}elseif(!preg_match('/^([\+]?(\d){2})?1\d{10}$/',$v['arr'][5]))
						{
							$v['flag'] = false;
							$v['str'] .= " 手机号码格式不正确"; 
							unset($var['arr']);
							break 2;//退出for循环
						}
						break;
				}
			}
		}
		return $arr;
	}
	public function MinDays($senddate,$frequency)
	{
		$senddate_arr = explode(',',$senddate);
		$date_part_arr = array();
		foreach($senddate_arr as $k=>$v)
		{
			if(strrpos($v,'~')===false)
				continue;
			$temp_arr = explode('~',$v);
			$date_part_arr[] = range($temp_arr[0],$temp_arr[1]);
			unset($senddate_arr[$k]);
			unset($temp_arr);
		}
		foreach($date_part_arr as $d)
		{
			$senddate_arr = array_merge_recursive($senddate_arr,$d);
		}
		$senddate_arr = array_unique($senddate_arr);
		if($frequency==2||$frequency==3)
			$loop = 7;
		elseif($frequency==4)
			$loop = 30;
		$size = sizeof($senddate_arr);
		if($size==1)
			return $loop;
		sort($senddate_arr,SORT_NUMERIC);
		print_r($senddate_arr);
		$min = 30;//月数
		for($i=$size-1;$i>=1;$i--)
		{
			$min = min($min,$senddate_arr[$i]-$senddate_arr[$i-1]);
		}
		$min = min($min,$senddate_arr[0]+$loop-$senddate[$size-1]);
		return $min;
	}
	public function CheckTime($sendtime)
	{
		if(empty($sendtime))
			return true;
		$time_arr = explode('~',$sendtime);
		$now = strtotime('now');
		print_r($time_arr);
		var_dump(strtotime($time_arr[1]));
		var_dump(strtotime('now'));
		var_dump(strtotime($time_arr[0]));
		if(strtotime($time_arr[1])<$now||$now<strtotime($time_arr[0]))
			return false;
		else 
			return true;
			
	}
	
	public static function str_getcsv_self($input, $delimiter=',', $enclosure='"', $escape=null, $eol=null) 
	{
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
		//var_dump(false<strtotime('now'));
		//print_r($_SERVER);
		//list($arr[1],$arr[2])=explode(',','1111');
		//var_dump(substr_count(",,,北京某某化工公司01,,",','));
	}
}
$a = new MyClass ( );
//$b = $a->CheckTime('08:50~14:40');
//var_dump($b);
var_dump($a->str_getcsv_self(',,北京某某化工公司01,'));
?>

