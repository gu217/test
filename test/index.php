<?php
require_once 'include/db.php';
require_once 'include/smarty.php';
require_once 'include/simple_html_dom.php';
require_once 'include/func/request.php';
require_once 'include/debug.php';
require_once 'pager.php';
class DbDo extends MyDb{
	public $oDb;
	public $oTpl;
	//public $tplDir = '/var/www/go/tpl/';
	public $sTplDir;
	public function __construct()
	{
		$this->sTplDir = GetCfg('tpl.dir');
		$this->oDb = $this->ConDb();
		$this->IsDebug(FALSE);
		//smarty
		$this->oTpl = new MySmarty();
		$this->oTpl->SetTplDir($this->sTplDir);
		echo '__construct',"<br /><img src='/sex_girl/106.jpg' style=\"float:right;display:none;\" />",'<br />';
	}
	public function IsDebug($yes)
	{
		$this->oDb->debug = $yes;
	}
	//dispaly output for debugging
	public function pre($output)
	{
		if(is_array($output))
		$output = '<pre>'.print_r($output,TRUE).'<pre/>';
		echo $output;
		unset($ouput);
		exit();
		}
	//display output with style of html
	public function FormatEcho($output)
	{
		header("Content-Type: text/html;charset=utf8");
		if(is_array($output))
		{
			$title = 'Array--'.sizeof($output);
			//print_r(mixed [.$return=false]);need clare
			$output = '<pre>'.print_r($output,true).'<pre/>';
		}
		else
		{
			$title = substr($output,0,10);
		}
		$css =<<<CSSS
<style type="text/css">
body{
	padding:0px;
	margin:0px;
	text-align:center;
	}
#content
{
	text-align:left;
	font-family:"Courier new";
	font-size:16px;
	margin:5px 5px;
	}
</style>		
CSSS;
		$head =<<<HEAD
<html>		
<head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html;charset='utf-8'">
<script type="text/javascript" src=""></script>
$css
</head>	
HEAD;
		$body =<<<BODY
		
<body>
<div id="content">
<pre>
$output
</pre>
</div>
</body>
</html>
BODY;
		echo $head.$body;
		unset($head,$body,$output);
		}
	public function GetRegion()
	{
		//ADODB_FETCH_NUM
		//ADODB_FETCH_ASSOC
		//ADODB_FETCH_DEFAULT
		$this->oDb->SetFetchMode(ADODB_FETCH_ASSOC);
		$sql = "select code,title from region";
		$rs = $this->oDb->Execute($sql);
		$ar = $rs->GetArray();
		$this->FormatEcho($ar);
		}
	public function SmartyTest()
	{
		//$this->IsDebug(TRUE);
		//$this->oTpl->clear_cache('index.tpl');
		$this->oTpl->caching = TRUE;
		$this->oTpl->assign ( 'title', 'My Ubuntu10.04LTS OS' );
		$this->oTpl->clear_assign('time');
		$this->oTpl->assign('time',time());
		$this->oTpl->display ( 'index.tpl');
	}
	public function PagerTest()
	{
		$this->oDb->SetFetchMode(ADODB_FETCH_ASSOC);
		$sql = "select code,title from region";
		$rs = $this->oDb->Execute($sql);
		var_dump(GetGet('page'));
		var_dump($_GET['page']);
		$pager = new Pager(5,$rs->RowCount(),$_GET['page'],5,'?page=',3);
		$page_info = $pager->ShowSubPage().$pager->GetGotoHtml();
		$sql .=" limit ".$pager->sLimit['start'].','.$pager->sLimit['length'];
		$rs = $this->oDb->Execute($sql);
		$ar = $rs->GetArray();
		$this->oTpl->assign_by_ref('inf',$ar);
		$this->oTpl->assign_by_ref('pages',$page_info);
		$this->oTpl->display('index.tpl');
	}	
	public function AboutCurl()
	{
		$url = "http://mydiscuz.com/index.php";
		$ch =curl_init( $url );
		
		$this->FormatEcho ( curl_getinfo ( $ch ) );
	}
	
	/**
	 *Chose number for lottery 
	 * :TODO: Save all the 
	 */
	public function DoubleColorBall()
	{
		$lottery = '';
		$blue = range(1,16);
		foreach($blue as &$v)
		{
			$v=$v<10?('0'.$v):$v;
		}
		$red = range(1,33);
		foreach($red as &$v)
		{
			$v=$v<10?('0'.$v):$v;
		}
		
		$red_ball_numbers_keys = array_rand($red,6);
		foreach($red_ball_numbers_keys as $v)
		{
			$lottery .= ' '.$red[$v];
		}
		$lottery = '<font color="red">'.substr($lottery,1).'</font>-<font color="blue">'.$blue[array_rand($blue)].'</font>';
		$this->FormatEcho($lottery);
		$this->FormatEcho($red_ball_numbers_keys);
	}
	public function	 GetLotteryData($year='2010',$start=1,$end=5)
	{
		//$files=file_get_contents('http://www.cpyjy.com/affiche/1_10015.html');
		//$files = htmlspecialchars($files);
		//$matches = preg_match_all('',$files
		//$this->Pre($files);
		$range =range($start,$end);
		foreach($range as &$v)
		{
			$v =substr(('00'.$v),-3);
		}
		$length = $end-$start+1;
		if($length<0)
			exit("Wrong \$start or \$end!");
		for($i=0;$i<$length;$i++)
		{
			$ord = substr($year,-2).$range[$i];
			$html = file_get_html("http://www.cpyjy.com/affiche/1_{$ord}.html");
			$redballs = $html->find('.ball_red');
			$TheOrd[$ord]['red'] = array();
			$rednum = 0;
			foreach($redballs as $v)
			{
				$rednum++;
				$TheOrd[$ord]['red'][$rednum] = $v->innertext;
			}
			$blueball = $html->find('.ball_blue');
			foreach($blueball as $v)
			{
				$TheOrd[$ord]['blue']=$v->innertext;
				}
		}
		//$this->FormatEcho($redballs);
		print_r($TheOrd);
		}
	public function SimpleHtmlDom()
	{
		$html = file_get_html('http://mydiscuz.com/index.php');
		$readhtml = $html->find('a');
		foreach($readhtml as $v)
		{
			//echo $v->src.'<br />';
			echo "$v->href".'<br />';
			}
		echo "++++++++++++++++++++++++<br />";
		echo file_get_html('http://mydiscuz.com/index.php')->plaintext;
		}
	public function GetArticle()
	{
		//Get articles from other web site,'simple_html_dom.php' is a better way,i think;
		$url = "http://dede.com/a/PHP/2010/0211/1.html";
		$html = file_get_html($url);
		$title = $html->find("div.title",1)->plaintext;
		$info = $html->find("div.intro",0)->innerntext;
		$content = $html->find("div.content",0)->innertext;
		//var_dump($content);
		//exit();
		echo "=================title=======================<br />";
		echo "$title <br />===========main info================<br />";
		echo "$info <br />=============article================<br />";
		echo "$content <br />==========================<br />";
		}
		
	public function ReadDir()
	{
		//$dir ="/media/ToLearn_/";// it's no priv!
		$dir = "/media/";
		//is_dir($dir)&&exit('It\'s dir!');
		$dir = realpath($dir);
		//var_dump($dir);
		if(!$dir)
			exit('Dir Wrong!');
		if(!is_readable($dir))
			exit('The Dir is unreadle!');
		$dir_list = scandir($dir);
		$this->pre($dir_list);
		}
	public function PlayJson()
	{
		//header('Content-Type:text/html; charset=utf8');
		$arr = array(
		'info' =>'你是傻B吗?',
		'yes' =>'你很诚实,你的确是傻B,恭喜您成为超级傻B!',
		'no' =>'虽然你的嘴很硬,但你逃避不了你是傻B这个事实!'
		);
		echo json_encode($arr);
		}
	public function Unicode16ToUtf8($str = '\u867d\u7136\u4f60')
	{
		header('Content-Type:text/html; charset=unicode');
		if(strlen($str)%6!=0)
		{
			exit('There is a error!');
			}
			else
			{
				$u16 = split('\u',$str);
				$len = count($u16);
				//print_r($u16);
				$rs = '';
				for($i=1;$i<$len;$i++)
				{
					$c = '';
					$c.=chr(hexdec(substr($u16[$i],2)));
					$c.=chr(hexdec(substr($u16[$i],0,2)));
					$rs .= iconv('UTF-16','UTF-8',$c);
					}
					/*** use json_encode(); get word like  "\u867d\u7136\u4f60"
					$www = '文本字段上的普通索引只能加快对出现在字段内容最前面的字符串';
					$string = iconv('UTF-8','UTF-16',$www);
					$arr = str_split($string,4);
					var_dump(implode('\u',$arr));
					****/
					exit($rs);
				}
		}
		public function TTTT()
		{
			//echo count('abc'); --->1
			//echo strrev('abcdef');//翻转字符串
			//echo date("Y-m-d H:i:s",strtotime('+1 weeks 2 days 1 hours 30 minutes 20 seconds'));//日期函数
			//var_dump(file_get_contents('http://hebgc.com'));//取得网页内容
			$arr = array('james', 'tom', 'symfony');
			//echo implode($arr,',');join alias implode
			//×××××××××××××××获得网址中文件的后缀
			$dir = 'http://www.sina.com.cn/abc/de/fg.php?id=1';
			$parseurl = parse_url($dir);
			$explode = explode('.',basename($parseurl['path']));
			echo '<br />',$explode[1],'<br />';
			//×××××××××××××××获得网址中文件的后缀
			//×××××××计算日期天数差2007-2-5 ~ 2007-3-6 
			echo (strtotime('2010-05-06 00:00:00')-strtotime('2010-05-05 00:00:00'))/(24*60*60);
			
			//×××××××计算日期天数差2007-2-5 ~ 2007-3-6 
			//********局部变量于全局变量
			$man = 1;
			function subcall()
			{
				//echo $man;//Notice: Undefined variable: man in /var/www/go/index.php on line 430
				echo $man*10;//Notice: Undefined variable: man in /var/www/go/index.php on line 431 0
				//开启Notice错误很重要,上面一条语句虽然错误仍会输出"0"
			}
			subcall();
		}
		/***
		 * 遍历目录
		 * 
		 * @param $dir
		 * */
		public function GetTree($dir='')
		{
			$dir = !isset($dir)||empty($dir)?'/var/www/go/':$dir;
			if(!is_dir($dir))
				exit($dir.' not a dir !');
			$dir_file = opendir($dir);
			if(!$dir_file)
				exit('No privilege!');
			else
				echo $dir;
			echo '<br /><b>',$dir,'</b>';
			while(($dir_list = readdir($dir_file))!==false)
			{
				if($dir_list!='.'&&$dir_list!='..'&&is_dir($dir_list))
				{
					echo '<br /><b>',$dir_list,'</b>';
				}
				else
				{
					echo '<br />  ',$dir_list;
				}
			}
		}
		public function __destruct()
		{
			echo '<br />','__destruct';
		}

		/**
		 * 
		 * 
		 * :TODO:截取字符串合理方法
		 * */
		public function StrSplit()
		{
			header("Content-Type:text/html;charset='utf-8'");
			$str1='中华人民共和国万岁,毛主席万岁!';
			$str2='0123456789qazwsxedcrfvtgb';
			echo mb_substr($str1,0,12),'<br />';
			echo mb_substr($str2,0,10),'<br />';
		}
		/**
		 * 获得网址中文件名的后缀
		 * 
		 * */
		public function GetFileExtenionFromUrl()
		{
			$url = 'http://www.sina.com.cn/abc/de/fg.php?id=1&class=article';
			$parse_url = parse_url($url);
			/***parse_url ***
			 * Array
			(
				[scheme] => http
				[host] => www.sina.com.cn
				[path] => /abc/de/fg.php
				[query] =>  id=1&class=article
			)
			 * 
			 **/
			$pathinfo = pathinfo($parse_url['path']);
			echo $pathinfo['extension'];
			/** pathinfo ***
			 Array
			(
				[dirname] => /abc/de
				[basename] => fg.php
				[extension] => php
				[filename] => fg
			)
			**/
			//print_r($parse_url);
		}
		
		public function StripAndUcfirst($str='A_B_C')
		{
			//$part_ar = explode('_',$str);
			/***
			foreach($part_ar as &$v)//对数组产生变化需要&,引用值
				$v = ucfirst($v);
			***/
			//$part_ar = array_map('ucfirst',explode('_',$str));//array 使用系统函数
			//比较好的一种解法
			//echo '<br  />',implode('',array_map('ucfirst',explode('_',$str)));
			//另外一种解法
			echo '<br />',str_replace(' ','',ucwords(str_replace('_',' ',$str)));
		}
		
		public function Arr12Arr2()
		{
			$arr1 = array (
							'0' => array ('fid' => 1, 'tid' => 1, 'name' =>'Name1' ),
							'1' => array ('fid' => 1, 'tid' => 2 , 'name' =>'Name2' ),
							'2' => array ('fid' => 1, 'tid' => 5 , 'name' =>'Name3' ),
							'3' => array ('fid' => 1, 'tid' => 7 , 'name' =>'Name4' ),
							'4' => array ('fid' => 3, 'tid' => 9, 'name' =>'Name5' ) 
							);
			$ar2 = array_chunk($arr1,4);
			$this->pre($ar2);
		}
		
		public function GetWebUrlInfo()
		{
			echo $_GET['a'];
			echo '<br />',$_SERVER['PHP_SELF'];
			echo '<br />',$_SERVER['REQUEST_URI'];
			$this->pre($_SERVER);
			/**
			 * Array
			(
				[HTTP_HOST] => go.com
				[HTTP_USER_AGENT] => Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3
				[HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,/*;q=0.8
				[HTTP_ACCEPT_LANGUAGE] => en-us,en;q=0.5
				[HTTP_ACCEPT_ENCODING] => gzip,deflate
				[HTTP_ACCEPT_CHARSET] => ISO-8859-1,utf-8;q=0.7,*;q=0.7
				[HTTP_KEEP_ALIVE] => 115
				[HTTP_CONNECTION] => keep-alive
				[HTTP_COOKIE] => __utma=133755288.1666793299.1273224319.1273224319.1273224319.1; __utmz=133755288.1273224319.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmc=133755288
				[HTTP_CACHE_CONTROL] => max-age=0
				[PATH] => /usr/local/bin:/usr/bin:/bin
				[SERVER_SIGNATURE] => 
			Apache/2.2.12 (Ubuntu) Server at go.com Port 80

				[SERVER_SOFTWARE] => Apache/2.2.12 (Ubuntu)
				[SERVER_NAME] => go.com
				[SERVER_ADDR] => 127.0.0.1
				[SERVER_PORT] => 80
				[REMOTE_ADDR] => 127.0.0.1
				[DOCUMENT_ROOT] => /var/www/go
				[SERVER_ADMIN] => webmaster@localhost
				[SCRIPT_FILENAME] => /var/www/go/index.php
				[REMOTE_PORT] => 46632
				[GATEWAY_INTERFACE] => CGI/1.1
				[SERVER_PROTOCOL] => HTTP/1.1
				[REQUEST_METHOD] => GET
				[QUERY_STRING] => 
				[REQUEST_URI] => /
				[SCRIPT_NAME] => /index.php
				[PHP_SELF] => /index.php
				[REQUEST_TIME] => 1273292739
				[argv] => Array
					(
					)

				[argc] => 0
			)
			 * */
		}
		/**
		* @package BugFree
		* 不尽人意  中英文混排不能等长
		*
		*/
		public function sysSubStr($String,$Length,$Append = false)
		{
			if (strlen($String) <= $Length )
			{
				return $String;
			}
			else
			{
				$I = 0;
				while ($I < $Length)
				{
					$StringTMP = substr($String,$I,1);
					if ( ord($StringTMP) >=224 )
					{
						$StringTMP = substr($String,$I,3);
						$I = $I + 3;
					}
					elseif( ord($StringTMP) >=192 )
					{
						$StringTMP = substr($String,$I,2);
						$I = $I + 2;
					}
					else
					{
						$I = $I + 1;
					}
					$StringLast[] = $StringTMP;
				}
				$StringLast = implode("",$StringLast);
				if($Append)
				{
					$StringLast .= "...";
				}
				return $StringLast;
			}
		}
		
		// 说明：截取中文字符串
		// 整理：http://www.CodeBit.cn
		public function mysubstr($str, $start, $len) 
		{
			$tmpstr = "";
			$strlen = $start + $len;
			for($i = 0; $i < $strlen; $i++) 
			{
				if(ord(substr($str, $i, 1)) > 0xa0) 
				{
					$tmpstr .= substr($str, $i, 2);
					$i++;
				} 
				else
				$tmpstr .= substr($str, $i, 1);
			}
			return $tmpstr;
		}
		
		public function MyScanDir($dir)
		{
			 $files = array();
			 if ( $handle = opendir($dir) ) 
			 {
				 while ( ($file = readdir($handle)) !== false ) 
				 {
					 if ( $file != ".." && $file != "." ) {
						 if ( is_dir($dir . "/" . $file) ) {
							 $files[$file] = $this->MyScanDir($dir . "/" . $file);
						 }else {
							 $files[] = $file;
						 }
					 }
				 }
				 closedir($handle);
				 return $files;
			 }
			 exit('Cant\'t open '.$dir);
		}
		public function AboutCookie()
		{
			session_start();
			$this->FormatEcho(print_r($_COOKIE,true));
			//date 
			echo date('Y-m-d',strtotime('+300 days',strtotime('1983-11-12')));
			//$token=token_get_all("session_start();\$this->FormatEcho(print_r(\$_COOKIE,true));echo date('Y-m-d',strtotime('+300 days',strtotime('1983-11-12')));");
			//$this->FormatEcho($token);
			/***
			$s = opendir('/var/www/go/');
			$files = '';
			while(false!==($dir=readdir($s)))
			{
				$files.='<br />'.$dir.'<br />';
				
				}
			closedir($s);
			* 				$this->FormatEcho($files);
			****/

				$dirs = scandir('/var/www/go/');
				$this->FormatEcho($dirs);
		}
		
		public function MyAlbum()
		{
				$dir = opendir('/var/www/go/sex_girl/');
				//var_dump($dir->read());
				$n = 0;
				while(($img=readdir($dir))!==false)
				{
					echo "<br /><img src='/sex_girl/$img' style=\"float:right;\" />";
					//if((++$n)%5==0)
					//break;
				}
				closedir($dir);
		}
		
		public function GetLottery($arr=array())
		{
			$size = sizeof($arr);
			if($size ==0)
			{
				$num = mt_rand(1,33);
				$arr[] = $num>9?$num:'0'.$num;
				return $this->GetLottery($arr);
			}
			else if($size ==6)
			{
				sort($arr);
				for($i=0;$i<6;$i++)
				{
						$arr[$i] = '   <font color=\'red\'>'.$arr[$i].'</font>';
				}
				$num = mt_rand(1,16);
				$num = $num>9?$num:'0'.$num;
				$arr[]=' - <font color=\'blue\'>'.$num.'</font>';
				$rs = '';
				foreach($arr as $v)
				{
					$rs .=$v;
					}
				
				return $rs;
			}
			else
			{
				$num = mt_rand(1,33);
				$num = $num>9?$num:'0'.$num;
				foreach($arr as $k=>$v)
				{
					if($num==$v)
						return $this->GetLottery($arr);
				}
				$arr[] = $num;
				return $this->GetLottery($arr);
			}
			}
	public function GetMoreLottery($n=1)
	{
		for($i=0;$i<$n;$i++)
		{
			if($i>0)
				echo '<br />';
			echo $this->GetLottery();
		}
	}
	public function ArrElementDel()
	{
		$arr = array(0,1,2,3,4,5,6,7,8,9);
		debug_zval_dump($arr);
		unset($arr[1]);
		debug_zval_dump($arr);
		//var_dump($arr[1]);
		$qq_arr = array(301718968,474456512,875354109,1142144476,249216194,540352321,417430135);
		foreach($qq_arr as $k=>$v)
		{
			echo '<br />';
			echo $k,'===>',$k==6?($v%17):($v%34);
			echo '<br />';
			echo "========='{$qq_arr['0']}'===========";
		}
		
	}
	/**
	 * --
-- Table structure for table `auth_group`
--

CREATE TABLE IF NOT EXISTS `auth_group` (
  `uuid` char(36) NOT NULL,
  `code` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `subm_by` char(36) NOT NULL,
  `subm_time` datetime NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uuid`),
  UNIQUE KEY `idx_auth_group_1` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_group`
--

**/
	public function GetDataFromDataBase()
	{
		$this->oDb->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $this->oDb->Execute('select * from ent_prof order by uuid desc limit 10,-1');
		print_r($rs->GetArray());
		debug_zval_dump($rs->GetArray());
	}
	
	public function MyTestInterview()
	{
		var_dump(get_magic_quotes_gpc());
		}
	public function ArrayFunctions()
	{
		$qq_arr0 = array(301718968,474456512,875354109,1142144476,249216194,540352321,417430135);
		$qq_arr1 = array_slice($qq_arr0,3,3);
		$qq_arr2 = array_merge($qq_arr0,$qq_arr1);
		//array_merge_recursive() An array of values resulted from merging the arguments together. (嵌套嵌入数组)
		//array_splice() 替换数组  array_splice — Remove a portion of the array and replace it with something else 
		$this->FormatEcho($qq_arr1);
		$this->FormatEcho($qq_arr2);
		$arr = $qq_arr0;
		debug_zval_dump($arr);
		var_dump(function_exists('mcrypt_cbc'));
		phpinfo();
	}
	public function AboutRegular()
	{
		var_dump(preg_match('/^(\d{1,2}|1\d{1,2}|25[0-5])(\.(\d{1,2}|1\d{1,2}|25[0-4])){3}$/','192.168.254.23'));
		var_dump(preg_match('/^(19\d{2}|200\d|2010)-(1[0-2]|0\d)-([0-2]\d|3[0,1])$/','2008-09-30'));
		echo <<<SCRIPT
		<div id='div'> div </div>
		<script type="text/javascript">
		var he=/^(\d{1,2}|1\d{1,2}|25[0-5])(\.(\d{1,2}|1\d{1,2}|25[0-4])){3}$/;
		if(he.test('192.168.259.23'))
			document.write('OK');
		else
			document.write('False');
		function $(id)
		{
				return document.getElementById(id);
		}
		alert($('div').innerHTML);
		</script>
SCRIPT;
	}
	public function Operator()
	{
		$a = 10|6;
		var_dump($a);
	}
	
	public function Excel()
	{
		$file = "1.xls";
		$e = new COM("Excel.application");
	}
}	
$a =new DbDo();
//$a->GetWebUrlInfo();
//$a->SmartyTest();
//$a->MyAlbum();
$a->Excel();

?>
