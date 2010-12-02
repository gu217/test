<?php 
define('CSV_FIELDS',6);
define('STANDARD_ENCODING','UTF-8');
define('CODELIST',"ASCII,GBK,GB2312,big5,UTF-8,CP936,EUC-CN,BIG-5,EUC-TW");
header('content-type:text/html;charset=utf-8');
class CSV
{
	
	public static function ReadCsv($url)
	{
		//echo mb_detect_encoding(file_get_contents($url),array('UTF-8','GBK','GB2312'));
		//exit();
		$row = 1;
		$rs_ar = array();
		$handle = fopen($url,"r");
		while ($data = fgetcsv($handle, 1000, ",")) 
		{
		    $rs_ar[$row]['flag'] = true;
			$num = count($data);
		    if($num!=CSV_FIELDS)
		    {
		    	$rs_ar[$row]['flag'] = false;
		    	$rs_ar[$row]['str'] = "第{$row}数据错误:".implode(',',$data);
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
		unset($rs_ar[1]);
		return $rs_ar;
	}
	
	public static function CheckAndConverEncoding($str)
	{
		$str = trim($str);
		$encoding = mb_detect_encoding($str,CODELIST); //$encoding 有时为false
		echo $encoding,'---',$str,'<br />';
		if($encoding != STANDARD_ENCODING && !empty($encoding))
		{
			$str = mb_convert_encoding($str,STANDARD_ENCODING,$encoding);
			//$str = iconv($encoding,STANDARD_ENCODING,$str);
		}
		//	$str = mb_convert_encoding($str,STANDARD_ENCODING);
		return $str;	
	}
	
	public function FileCsv($url)
	{
		$file = file($url);
		foreach($file as $line)
		{
			echo $line,'<br />';
		}
	}
}

var_dump(CSV::ReadCsv('zh_utf8.csv'));
CSV::FileCsv('zh.csv');
?>
