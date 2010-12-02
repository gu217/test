$('#userName').blur(UserNameCheck());
$('#pwd1').blur(CheckPassword1());
$('#pwd2').blur(CheckPassword2());
$('#email').blur(CheckEmail());
$('#mobile').blur(CheckMobile());
function UserNameCheck()
{
    username = $.trim($('#userName').val());
    if(username=='')
    {
        TipsChange('userName','','info');
        return 0;
    }
        
    if(username.length<2||username.length>18)
    {
        TipsChange('userName','用户名要大于等于2位，小于等于18位');
        return 1;
    }
    else if(!CheckChinese(username))
    {
        TipsChange('userName','用户名不支持中文');
        return 2;
    }
    /**
    else if(ExistUserName(username))
    {  ::TODO::
       TipsChange('userName','用户已经存在');
       return 3;
    }
    **/
    else
    {
        TipsChange('userName','','ok');
        return 4;
    }
}

function CheckPassword1()
{
    pwd1 = $('#pwd1').val();
    if(pwd1=='')
    {
        TipsChange('pwd1','','info');
        alert('OK');
        return 0;
    }
        
    if(pwd1.length<6||pwd1.length>18)
    {
        TipsChange('pwd1','密码要大于等于6位，小于等于18位');
        return -1;
    }
        TipsChange('pwd1','','ok');
        return 1;
}

function CheckPassword2()
{
    pwd1 = $('#pwd1').val();
    pwd2 = $('#pwd2').val();
    if(pwd2 == '')
    {
        TipsChange('pwd2','','info');
        return 0;
    }
        
    if(pwd1 != pw2)
    {
        TipsChange('pwd2','两次输入的密码不一致');
        return -1;
    }
   TipsChange('pwd2','','ok');
   return 1;
}
function CheckEmail()
{
    email = $.trim($('#email').val());
    if(email=='')
    {
        TipsChange('email','','info');
        return 0;
    }
    email_test = new RegExp('^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|com|gov|mil|org|edu|int)$');
    if(!email_test.test(email))
    {
        TipsChange('email','请填写有效邮箱');
        return 1;
    }
    
     TipsChange('email','','ok');
}

function CheckMobile()
{
    m = $.trim($('#mobile').val());
    if(m=='')
    {
        TipsChange('mobile','','info');
        return 0;
    }
    m_test = new RegExp('^([+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+)?$');
    if(!m_test.test(m))
    {
         TipsChange('email','手机格式不正确');
    }
    
    TipsChange('mobile','','ok');
}

function TipsChange(_id,_info,_tip)
{
	$('#'+_id).siblings('span').hide();
    var _tip = _tip || 'error';
    var _info = _info || '';
    tip_class = '.reg_'+_tip;
    if(_info!='')
        $('#'+_id).siblings.(tip_class).html(_info).show();
    else
         $('#'+_id).siblings(tip_class).show();
}

function CheckChinese(str)
{
  return /[^\u4e00-\u9fa5]/.test(str);
}
