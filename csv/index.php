<?php 
define('CSV_FIELDS',6);
define('STANDARD_ENCODING','UTF-8');
define('CODELIST',"ASCII,GBK,GB2312,big5,UTF-8,CP936,EUC-CN,BIG-5,EUC-TW");
header('content-type:text/html;charset=utf-8');
class CSV
{
	//读取CSV文件
	public static function ReadCsv($url)
	{
		$rs_arr = array();
		$str_file = trim(file_get_contents($url)); //去掉前后的空行
		$str_file = self::CheckAndConverEncoding($str_file);//字符转换
		$tmp_arr = explode("\n",$str_file);
		foreach($tmp_arr as $k=>$v)
		{
			if(empty(trim($v)))
				continue;//去掉中间的空行
			$tmp_arr_2 = explode(',',$v);
			if(count($tmp_arr_2)!= CSV_FIELDS)
			{
				$rs_arr[$k]['flag'] = false;
				$rs_arr[$k]['str'] = $v.' 字段数不正确';
				continue;
			}
			else
			{
				unset($tmp_arr_2);
				$rs_arr[$k]['flag'] = true;
				list($rs_arr[$k]['arr']['username'],$rs_arr[$k]['arr']['password'],$rs_arr[$k]['arr']['corpname'],$rs_arr[$k]['arr']['realname'],$rs_arr[$k]['arr']['email'],$rs_arr[$k]['arr']['mobile']) = explode(',',$v);
			}
		}
		unset($tmp_arr,$v);
		return $rs_arr;
	}
	
	public static function CheckAndConverEncoding($str)
	{
		$encoding = mb_detect_encoding($str,CODELIST); //$encoding 有时为false(编码检测失败)
		if($encoding != STANDARD_ENCODING && !empty($encoding))
		{
			$str = mb_convert_encoding($str,STANDARD_ENCODING,$encoding);
		}
		return $str;	
	}
	public static function FileReadCsv()
	{
		$lines = file('http://www.example.com/');
		// 在数组中循环，显示 HTML 的源文件并加上行号。
		foreach ($lines as $line_num => $line) 
		{
			echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
		}
		// 另一个例子将 web 页面读入字符串。参见 file_get_contents()。
		$html = implode('', file ('http://www.example.com/'));
	}
}

$rs = CSV::ReadCsv('zh_utf8.csv');
print_r($rs);
//CSV::FileCsv('zh.csv');
?>
