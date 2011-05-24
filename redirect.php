<?php
/**
 * 邮件统计中的连接跳转   TODO  将文件后缀改为html(widgets/passport/process/journal.php 中连接也要改) 
 */
if(empty($_GET['u']))
	header("Location: http://www.gongye360.com/");
else 
	$url = urldecode($_GET['u']);
if(!empty($_GET['email']))
	//$email = base64_decode(urldecode($_GET['email']));
	$email = urldecode(base64_decode($_GET['email']));
else 
	$email = '';

if(empty($email) && !empty($_GET['email_co']))
{
	$email = trim($_GET['email_co']);
}
$record_id = empty($_GET['email_id']) ? 0 : (int)$_GET['email_id'];

if(empty($record_id) && !empty($_GET['id_co']))
{
	$record_id = (int)$_GET['id_co'];
}

$user_ip = empty($_SERVER['REMOTE_ADDR']) ? 0 : sprintf( '%u', ip2long( $_SERVER['REMOTE_ADDR'] ) );
$source_id = empty($_GET['source_id']) ? 0 : (int)$_GET['source_id'];
$source_type = empty($_GET['source_type']) ? 0 : (int)$_GET['source_type'];
$link_code = empty($_GET['lc']) ? '' : trim($_GET['lc']);

$url = JumpTo360($url,$record_id);
$url = URLJumpDealWith($url,$email,$record_id);
setcookie('e',base64_encode($email),time()+60*60*24*3650);

echo $url;
//header("Location: {$url}");
function URLJumpDealWith($url,$email,$record_id)
{
	$delimiter = ReturnDelimiter($url);
	switch($url)
	{
		case "http://www.gongye360.com/adlinktech/signup.html":
		case "http://www.gongye360.com/adlinktech/index.html":
		case "http://www.gongye360.com/adlinktech/agenda.html":
		case "http://www.gongye360.com/adlinktech/product.html":
		case "http://www.gongye360.com/cnim/index.html":
		case "http://www.gongye360.com/edm/index.html":
			return $url .="{$delimiter}email=".$email;
		case "http://www.gongye360.com/adlinktech/adlinktech2.html":
		case "http://www.gongye360.com/adlinktech/adlinktech.html":
			return $url .="?email=".$email."&email_id=".$record_id;
		default:
			if(!strpos($url,'gongye360.com/redirect.php'))
			return $url .="{$delimiter}email=".$email."&email_id=".$record_id;
	}
	return $url;
}
function JumpTo360($url,$record_id)
{
	global $email;
	$url_rs = preg_replace("/http:\/\/[a-z0-9]+.file.800mei.net/","http://www.gongye360.com",$url,1);
	if($url_rs == $url)
		return $url_rs;
	else
		return "http://www.gongye360.com/redirect.php?u=".urlencode($url_rs)."&email_co=".$email."&email_id={$record_id}";
}
function ReturnDelimiter($url)
{
	$arr = parse_url($url);
	if(empty($arr['query']))
		return '?';
	else 
		return '&';
}
?>
