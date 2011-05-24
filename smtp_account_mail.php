<?php 
require_once dirname(__FILE__).'/include/class/phpmailer/class.phpmailer.php';

function DoMail($name='*',$to='gu217@126.com',$title='myEmail',$body='HaLou!',$reply='',$att='')
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
		$username = '575401070';
		$password = 'gongyegongye';
		$host = 'qq.com';
		$title = "[2181120] 蔡伯阶您好,邀请您参加“凌华测控技术与计算机平台技术研讨会” [f839xwvr28pje5fekj4d9eq2vxuiex]";
		$title = "电磁流量计测量中的气泡噪声及其处理方法";
		$show_name = '';
		$to = 'beng360@163.com';
//		$to = 'gpc@gongye360.com';
		$content = <<<HTML
		
<table border="0" cellpadding="0" cellspacing="0" style="align:center;width:100%;">
 <tr>
	<td>如不能正常显示邮件请点击此<a href="http://4sbwen.file.800mei.net/redirect.php?u=http%3A%2F%2Fbm3c7v.file.800mei.net%2Fadlinktech%2Findex.html&amp;[param]" target="_blank">链接</a></td>
 </tr>
	<tr>
		<td>

			<table border="0" cellpadding="0" cellspacing="0" style="">
				<tr>
					<td>
						<img src="http://bm3c7v.file.800mei.net/adlinktech/images/lh_1.gif" border="none"><br />
					</td>
				</tr>
				<tr><td style="font-size:13px;border-left:1px solid gray;border-right:2px solid gray;">  　　注：不同场次的主题演讲有所不同，请点击<a href="http://4sbwen.file.800mei.net/redirect.php?u=http%3A%2F%2Fbm3c7v.file.800mei.net%2Fadlinktech%2Fagenda.html&amp;[param]" target="_blank">这里</a>查看详细信息</td></tr>

				<tr>
					<td><img src="http://bm3c7v.file.800mei.net/adlinktech/images/lh_2.gif" border="none" /></td>
				</tr>
				<tr>
					<td style="border-left:1px solid gray;border-right:2px solid gray;"><img src="http://bm3c7v.file.800mei.net/logimg.php?[param]" style="border:none;" /><a href="http://4sbwen.file.800mei.net/redirect.php?u=http%3A%2F%2Fbm3c7v.file.800mei.net%2Fadlinktech%2Fsignup.html&amp;[param]" target="_blank"><img src="http://bm3c7v.file.800mei.net/adlinktech/images/button.jpg" border="nonoe" style="padding:0px;margin:0px;display:block;" /></a>
					</td>
				</tr>
				<tr>
					<td><img src="http://bm3c7v.file.800mei.net/adlinktech/images/lh_3.gif" border="none" /></td>

				</tr>
			</table>
		</td>
	</tr>
 </table>
		
HTML;
		
//		$content = "发错了";
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
//		$mail->Host       = 'smtp.'.$host; // SMTP server
//		$mail->Host       = $host; // SMTP server
//		$mail->SMTPDebug  = FALSE;                     // enables SMTP debug information (for testing)
//		$mail->SMTPAuth   = FALSE;                  // enable SMTP authentication
//		$mail->Port       = 25;        	         // set the SMTP port for the GMAIL server | default: 25
//		$mail->Username   = $username; // SMTP account username
//		$mail->Username   = 'gpc@gongye360.com'; // SMTP account username
//		$mail->Password   = $password;        // SMTP account password
//		$content = randomLetter(1500);
		$content = <<<HTML
		钢铁企业在高炉检漏和连铸连轧控制中大量使用电磁流量计来测量冷却水。冷却水的测量信号往往与设备开启关联，任何一个误动作将会造成无法弥补的损失。测量与控制的精度和可靠性涉及到设备安全、节约能耗以及钢铁产品性能指标。因此，钢铁生产过程对电磁流量计要求具有反应迅速、灵敏度高、重复性稳定性好、可靠性高等特点。

本文讨论的就是为解决钢铁生产高炉检漏和连轧连铸中冷却水可靠测量的问题。

1 冷却水测量的一个故障特例

某钢铁公司的炼钢厂连铸冷却水测量中出现了如图1所示的故障流量曲线。流量故障变化呈脉冲规律，脉冲的幅度约为120m3/h，故障脉冲宽度大约为10～12s，周期不定。这种故障造成了系统的误报警，导致工厂生产过程中的严重事故。尽管电磁流量计具有一定的智能化故障判断功能，但由于故障是不定期发生的，很难捕捉到检测故障发生时流量计所反映的流体物化参数和噪声干扰的信息，因此很难按照的常规方法去判断出现流量显示输出为零的可能性，很难判定这种故障的起因。

传感器安装示意图如图2所示。上游是DN80管道经90°弯头后，由渐扩管再扩大至DN150管道进入。流量计上游的直管段长度不足5D，计算得到从DN80～DN150的扩大锥角β大约为40°。从现场安装情况分析，初步认为故障可能是由气泡擦过电极形成短暂时间的感应信号为零所致。也就是说，这是一种气穴现象[2]。所以，我们称这种故障为“气泡噪声”（bubblenoise）。那么气泡又是如何产生的，为什么有时候模拟型转换器看不到这种故障。

2 气泡噪声产生原因的分析

从安装情况看，本例的安装情况与电磁流量计的安装要求不符。流量计上游的弯头、扩大管，以及插入热电偶，距电极的直管段不足5D。这些都是容易在电极附近产生旋涡和不对称流速分布以及分离液体中气体形成气泡的原因。上游由小口径（DN80）以高流速（6m/s以上的平均流速），约40°的入射角流向DN150管道[3]。扩大管气泡分离如图3所示。

这种沿着管壁非顺滑的流体流动，流体的流束首先是收缩呈射流形式流动，然后再逐渐将流束扩散为轴对称的充分发展流。射流过程会形成扩大管内入口处周围的负压区域，于是在电极前要产生大量的旋涡。这样，破坏了电磁流量计测量要求即流速中心轴对称的基本条件。更严重的是由于在电极前形成负压，旋涡处可能分离气体，并慢慢聚集形成气泡。分离的气泡常常附在流速几乎为零的管壁上，流体流动容易携带气泡沿管壁移动。当气泡沿管壁移动擦过电极时，使电极上的感应信号为零，这时的测量输出和显示为零。

弯头和插入热电偶的下游也会有旋涡产生和气体分离。高温液体在旋涡产生过程中更容易汽化分离气泡，这些都是钢铁行业冷却水测量时容易遇到的现象。

分离的气泡向下游移动，擦过电极的时间受液体流动速度、管道内壁粗糙度、流量计衬里的光滑程度、电极的形状与突出衬里的高度等因素的影响长短不定。本例2台仪表反映的故障时间都在10s左右。

为了使仪表输出稳定，电磁流量计设计有阻尼时间。仪表的阻尼是在被测量流量变动时能够平滑仪表的测量值。当输入量阶跃上升到最大值，仪表测量值并不是立即从零达到最大值，而是需要一段时间。把从零到最大输出值的63%（或欧洲产品习惯定义为90%）所需要的时间定义为阻尼时间。电信号的阻尼时间实际上是一个RC阻容滤波器的时间常数，它是一个积分过程。图4为阻尼时间等效电路[4]。

图4中：Ei为阶跃输入信号幅度；Eo为积分输出信号幅度；t为阻尼时间；e=2.71828为常数；电阻电容之积RC就是阻尼时间常数；τ为阶跃脉冲信号的宽度。

当RC=τ时，输出信号达到输入信号最大值的63%；当RC=3τ时，输出信号达到输入信号最大值的95%。为了减小测量误差，则采用长阻尼时间，通常取RC=（5～7）τ。同时应该注意到，如果阻尼时间小，后面的输入信号脉冲需要再滤波，形成三角波状输出，达不到最大稳定值。但是，阻尼时间过长，会造成仪表的反应速度慢，也就是说灵敏度低，控制与调节的可靠性差。所以，在一般情况下，电磁流量计的阻尼时间设为3～6s。

气泡噪声信号波形脉冲幅度从最大100%下降到零，并维持10余秒，气泡噪声与阻尼时间的关系如图5所示。

输出幅度可用式（1）表示：

如果按一般阻尼时间设置为5s，计算信号输出会下降到约40%，即原本测量输出120m3/h，这时只能得到约50m3/h，低于工厂下限报警值，从而引起误报警。同时，由于智能电磁流量计具有空管报警并将信号输出自动置零的功能，在气泡擦过电极时，电极电阻增大，发生空管报警，仪表使测量输出保持在零值。气泡擦过电极的时间大于阻尼时间，形成多次脉冲的滤波，其滤波次数决定于气泡擦过电极的时间与阻尼时间的比。因此，该阶段的流量显示不稳定，输出存在大的纹波。

模拟型电磁流量计没有出现故障报警是由于：①模拟型电磁流量计在信号处理时具有采样电路和积分保持电路，其积分时间常数由电阻电容和积分放大器决定，通常模拟电路的时间常数比较大；智能化电磁流量计是断续采样的，依靠软件设置CPU运算进行数字滤波，阻尼时间需要设置，设置的范围很宽，从0.5～100s。通常设置的阻尼时间小于气泡噪声的脉冲宽度。②智能化电磁流量计具有空管检测功能，当电极检测到气泡即提出报警，并把空管认为是没有流量流过，自动将输出显示置于零状态。模拟型电磁流量计一般不带空管检测功能，判断不了电极出现气泡，这时也就不会把输出显示置于零。因此，似乎显得模拟型电磁流量计对气泡噪声影响不敏感。

3 问题的避免和解决方法

由以上分析可得，电磁流量计在钢铁行业冷却水测量中出现的误报警大多是由气泡擦过电极引起的。所以，首先从安装上满足仪表上游直管段长度要求，规范仪表的安装，选择远离热源的安装场所，合理使用管道流速，选用光洁度高的PFA氟塑料衬里和高纯氧化铝工业陶瓷导管。这些措施将有助于防止或减小旋涡和气体分离的发生。也就是说，改进传感器制造工艺、改善使用仪表环境条件和安装条件、采用仪表上游加装排气阀等措施，有可能避免问题的发生[5]。其次，合理地设置仪表阻尼时间和功能，也可以解决出现气泡噪声测量的误报警。阻尼时间的选择是根据流量信号中发生气泡噪声的脉冲宽度来选取。一般应取阻尼时间为气泡噪声脉冲宽度的3～5倍。如气泡噪声脉冲宽度是10s，阻尼时间应取30～50s。具体选择应根据要求的控制精度，3倍脉冲宽度控制误差在5%，5倍脉冲宽度控制精度高于1%。

加大仪表阻尼时间能有效地解决这种脉冲型气泡噪声的影响，同时也带来了反应迟钝的缺点，即当真正流量波动时，仪表反应很慢。这对要求灵敏控制的冷却水系统无疑是个难题。为了解决这个问题，智能化电磁流量计可以使用软件逻辑判断即粗大误差处理的方法[5]。在出现这种故障时，通过调整流量的不敏感时间和变化幅度限制这两个条件来判断是流量的变动，还是气泡擦过电极。如果不是气泡擦过电极的噪声，CPU按正常采样、运算和数字滤波；如果判定产生的是气泡噪声，切除测量值，维持前面的流量测量值。这样，正常流量测量期间阻尼时间仍然为3～6s。只有在有气泡噪声时，根据脉冲宽度设置的长短将不敏感时间加长，系统控制的时间也会加长。

当我们合理选择具有粗大误差抑制功能电磁流量转换器的“变化率限制值”和“不敏感时间值”时，转换器不仅能够抑制气泡噪声引起的误报警，而且在正常工作时仪表的反应速度仍然能够保持所设置的阻尼时间值。

4 试验验证

气泡噪声的研究，应该是用气泡对电磁流量传感器电极进行模拟试验，但目前尚未有这种条件。因此，我们只用电磁流量信号发生器信号的切换，进行气泡噪声的模拟，输出曲线如图6所示。

从图6可以看出，适当地选取阻尼时间和智能型电磁流量计处理气泡噪声故障的方法，对观察流量计显示与输出信号变化，判断处理气泡噪声的效果明显。切换电磁流量计标准信号源的开关，快速设置流速和零点，按需要保持信号为零的时间，模拟气泡噪声的发生和存在。改变仪表阻尼时间并设置不同的“变化率限制值”及“不敏感时间值”，测试仪表输出的变化。

结果表明，加大阻尼时间和智能化气泡噪声处理都能达到输出不发生大的变化，后者更有利于正常测量期间测量反应速度的提高[6]。

本文提出气泡噪声的解决办法，在现场运行正常，未再出现气泡故障报警。

5 结束语

本文对气泡噪声的初步探索，有助于在电磁的应用中，判断气液两相流分离气泡和进行噪声处理，充分利用现代的计算机技术提高测量的可靠性。
HTML;
		$mail->IsSMTP();
		$mail->Host       = 'smtp.'.$host; // SMTP server
		$mail->SMTPDebug  = FALSE;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = TRUE;                  // enable SMTP authentication
		$mail->Port       = 25;        	         // set the SMTP port for the GMAIL server | default: 25
		$mail->Username   = $username; // SMTP account username
		$mail->Password   = $password;        // SMTP account password
		try
		{
			$mail->SetFrom($username.'@'.$host,$show_name);
			$mail->AddAddress($to,'gpc');
			$mail->Subject = $title;
			$mail->AltBody = 'Hi';
			$mail->MsgHTML($content);
			$mail->Send();
		}
		catch (phpmailerException $e) 
		{
			var_dump($e);
			return $e->errorMessage();
		} 
		catch (Exception $e) 
		{
			var_dump($e);
			return $e->getMessage();
		}
}
echo SmtpAccountTest();
function randomLetter($count)
{
    $s = 'abcdefghijklmnopqerstuvwxyz0123456789';
    $strCount = strlen($s);
    $rs = ''; 
    while ($count--) {
        $i = rand(0, $strCount - 1); 
        $rs .= $s[$i];
    }   
    return $rs;
}
?>