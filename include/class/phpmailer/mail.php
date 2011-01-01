<?php 
require_once dirname(__FILE__).'/class.phpmailer.php';

function DoMail($name='*',$to='*@gmail.com',$title='myEmail',$body='HaLou!',$reply='',$att='/home/1.sh')
{
	$mail = new PHPMailer;
	$mail->Body = $body;
	$mail->Subject = $title;
	$mail->AddAddress($to,$name);
	if(!empty($att))
	{
		if(is_array($att))
		{
			foreach($att as $v)		
				$mail->AddAttachment($v);
		}
		else
		{
			$mail->AddAttachment($att);
		}
	}
	if(!empty($reply))
		$mail->ReplyTo = array($reply);

	if($mail->Send())
		return true;
	else 
		return false;
}
DoMail();
?>