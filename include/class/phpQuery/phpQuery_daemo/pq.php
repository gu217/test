<?php 
/**
 * author gu217
 * version 1.0
 */
set_time_limit(0);
require_once dirname(__FILE__).'/phpQuery/phpQuery/phpQuery.php';

class PqTest
{
	public $aInfo = array(
			'url'=>'http://www.33eee.net/YSE/',
			'base_url'=>'http://www.33eee.net',
			'save_dir'=>'/home/acer/Downloads/down_girl'
	);
	private $aUrlList=array();
	public $oDb;
	
	public function __construct()
	{
		$this->oDb = mysql_connect('127.0.0.1','root','root') or exit('Can\'t connect MySQL database! \n');
		mysql_select_db('grab_info',$this->oDb);
		mysql_query("set names 'utf8'");
	}
	public function GetInfo($url)
	{
//		phpQuery::$debug=2;
		phpQuery::newDocumentFile($url,"text/html; charset=gb2312");
		foreach(pq('.list>table>tr>td>li') as $li)
		{
			echo $tmp_url = pq($li)->find('a')->attr('href');
			echo "\n";
			if(!empty($tmp_url))
				mysql_query("INSERT INTO `info_urls`(`url`,`hash`) VALUES ('http://www.33eee.net{$tmp_url}','".md5($tmp_url)."')");
		}
		print_r($this->aUrlList);
	//	system(`wget -O ~/Desktop/111.png -i -F http://www.moolenaar.net/2011_small.png`,$rs);
	}
	
	public function GatherUrls()
	{
		$urls = array();
		for($i=50;$i>0;$i--)
		{
			echo $url = "http://www.33eee.net/YSE/index_{$i}.html";
			echo "\n";
			$urls = $this->GetInfo($url);
		}
	}
	
	public function SaveUrl($urls)
	{
		if(empty($urls))
			exit("No urls \n");
		$vals = '';
		foreach($urls as &$v)
		{
			$v ="('http://www.33eee.net{$v}','".md5($v)."')";
		}
		$vals = implode(',',$urls);
		mysql_query("INSERT INTO `info_urls`(`url`,`hash`) VALUES ".$vals);
	}
	
	public function ForeachPaperImg($url)
	{
		$url = 'http://www.33eee.net/YSE/17110.html';
//		phpQuery::$debug = 1;
		phpQuery::newDocumentFile($url,"text/html; charset=gb2312");
		foreach(pq('#MyContent>a') as $img)
		{
			$img_url = pq($img)->find('img')->attr('src');
			if(!empty($img_url))
				$this->SaveImg($url,$img_url);
		}
	}
	
	public function SaveImg($url,$img_url)
	{
		$parse_url = parse_url($url);
		$parseinfo = pathinfo($parse_url['path']);
		$dir = $parseinfo['filename'];
		$d_dir = $this->aInfo['save_dir'].'/'.$dir;
		
		$img_parse_url = parse_url($img_url);
		$img_pathinfo = pathinfo($img_parse_url['path']);
		$d_img_name = $d_dir.'/'.time().rand().'.'.$img_pathinfo['extension'];
		if(!is_dir($d_dir))
			mkdir($d_dir,777);
		system("wget -O {$d_img_name} -i -F {$img_url}",$rs);
	}
	
	public function PqTest()
	{
		$url = 'http://dede.com';
//		phpQuery::$debug = 1;
		phpQuery::newDocumentFile($url);
		foreach(pq('.listbox>dl>dd>ul>li') as $list)
		{
			echo pq($list)->find('a')->attr('href'),"\n";
		}
	}
}
$a = new PqTest();
$a->ForeachPaperImg('');
//$a->GetInfo('');
//$a->PqTest();
?>
