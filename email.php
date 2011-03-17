<?php 
require_once dirname(__FILE__).'/include/class/phpmailer/class.phpmailer.php';

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
//DoMail();
function SmtpAccountTest()
{
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$mail->Host       = 'smtp.hexun.com'; // SMTP server
		$mail->SMTPDebug  = FALSE;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = TRUE;                  // enable SMTP authentication
		$mail->Port       = 25;        	         // set the SMTP port for the GMAIL server | default: 25
		$mail->Username   = 'fisvjtk78'; // SMTP account username
		$mail->Password   = 'gjgwnpqtvum';        // SMTP account password
		try
		{
			$mail->SetFrom('fisvjtk78@hexun.com','test');
			$mail->AddAddress('gpc@gongye360.com','gpc');
			$mail->Subject = 'Hello';
			$mail->AltBody = 'Hi';
			$mail->MsgHTML('OK');
			$mail->Send();
		}
		catch (phpmailerException $e) 
		{
			//发送失败 继续发送(retrysend)
			return $e->errorMessage();
		} 
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
}
echo SmtpAccountTest();
?>