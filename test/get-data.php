<?php
/**
 * 从数据库库中读出数据并保存到文件中
 */
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 't-2008-zbb';
$lang = 'utf8';
$file = '';
$con = mysql_connect($host,$username,$password);
mysql_select_db($database,$con);
mysql_query("set names '$lang'");
$sql = "select title,pin from per_prof";
$rs = mysql_query( $sql );
$file = '';
while($row=mysql_fetch_array($rs,MYSQL_ASSOC))
{
	if(strlen($row['pin'])==18 or strlen($row['pin'])==15)
	$file .= $row['title'].'==>'.$row['pin']."\n";
}
file_put_contents('data.log',$file);
?>
