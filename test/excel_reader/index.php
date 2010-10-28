<?php
require_once 'Excel/reader.php';
//$config['file'] = 'per_info.txt';
define ( 'SQL_FILE', 'per_info.txt' );

$data = new Spreadsheet_Excel_Reader ( );

// Set output Encoding.
$data->setOutputEncoding ( 'UTF8' );

$file_array = glob ( '*.xls' );

echo GenUrl ( sizeof ( $file_array ), "?a=" );
$file [] = $file_array [GetCurrentFile ()];
//echo GenFormatData ( $data, GetCurrentSheet () );
GenDataByFile ( $data, $file );

function GenDataByFile($data, $file_array)
{
	$f_size = sizeof ( $file_array );
	for($j = 0; $j < $f_size; $j ++)
	{
		$data->read ( $file_array [$j] );
		$size = sizeof ( $data->sheets );
		for($i = 0; $i < $size; $i ++)
		{
			GenFormatData ( $data, $i );
		}
	}
}

function GetCurrentFile()
{
	if (isset ( $_GET ['a'] ))
		return $_GET ['a'];
	else
		return 0;
}

function GenData($data, $current_sheet)
{
	$rs_data = '';
	for($i = 1; $i <= $data->sheets [$current_sheet] ['numRows']; $i ++)
	{
		for($j = 1; $j <= $data->sheets [$current_sheet] ['numCols']; $j ++)
		{
			$rs_data .= "\"" . $data->sheets [$current_sheet] ['cells'] [$i] [$j] . "\",";
		}
		$rs_data .= "\n";
	}
	echo $data->sheets [$current_sheet] ['numCols'];
	return $rs_data;
}

function GenFormatData($data, $current_sheet)
{
	$rs_arr = array ();
	$n = 0;
	for($i = 1; $i <= $data->sheets [$current_sheet] ['numRows']; $i ++)
	{
		//for($j = 1; $j <= $data->sheets [$current_sheet] ['numCols']; $j ++)
		//{
		list ( $rs_arr [$n] ['name'], $rs_arr [$n] ['id'] ) = explode ( '                        ', $data->sheets [$current_sheet] ['cells'] [$i] [1] );
		$rs_arr [$n] ['gender'] = GetGender ( $rs_arr [$n] ['id'] );
		$rs_arr [$n] ['birthday'] = GetBirthday ( $rs_arr [$n] ['id'] );
		//}
		$n ++;
		if ($i % 50 == 0)
		{
			WriteDataToFile ( $rs_arr );
			//var_dump($rs_arr);
			//exit ();
			$n = 0;
			//$rs_arr = array ();
		}
	}
}

function GetBirthday($id)
{
	if (strlen ( $id ) == 18)
		return preg_replace ( '/^\d{6}(\d{4})(\d{2})(\d{2}).*$/', '\\1-\\2-\\3', $id );
	else
		return preg_replace ( '/^\d{6}(\d{2})(\d{2})(\d{2}).*$/', '19\\1-\\2-\\3', $id );

}

function GetGender($id)
{
	if (strlen ( $id ) == 18)
		return substr ( $id, - 2, 1 ) % 2 == 0 ? 0 : 1;
	else
		return substr ( $id, - 1 ) % 2 == 0 ? 0 : 1;
}

function GenUrl($size, $link)
{
	$str = '';
	for($i = 0; $i < $size; $i ++)
	{
		$str .= '<a href=' . $link . $i . '>' . $i . '</a>   ';
	}
	return $str;
}

function WriteDataToFile($info_array)
{
	static $file_exist = FALSE;
	if (file_exists ( SQL_FILE ))
		$file_exist = TRUE;
	else
		file_put_contents ( SQL_FILE, 'insert into per_info(`name`,`gender`,`birthday`,`id_card_num`)values' );
	$str = GenStrFromArray ( $info_array );
	if (is_writable ( SQL_FILE ))
	{
		if (! $handle = fopen ( SQL_FILE, 'a' ))
		{
			ErrorReport ( SQL_FILE . ' can not open!' );
			return false;
		}
		if (fwrite ( $handle, $str ) === FALSE)
		{
			ErrorReport ( SQL_FILE . 'can not writeable!' );
			return false;
		}
		fclose ( $handle );
		return true;
	}
	else
	{
		ErrorReport ( SQL_FILE . ' is not writeable!' );
		return false;
	}

}

function ErrorReport($msg)
{
	trigger_error ( $msg );
}

function GenStrFromArray($array)
{
	$str = '';
	$size = sizeof ( $array );
	//print_r ( $array, TRUE );
	//exit ( '@@@@@@' );
	for($i = 0; $i < $size; $i ++)
	{
		$str .= '(\'' . $array [$i] ['name'] . '\',' . '\'' . $array [$i] ['gender'] . '\',' . $array [$i] ['birthday'] . '\',' . '\'' . $array [$i] ['id'] . '\'),';
	}
	return substr ( $str, 0, - 1 );
}
?>