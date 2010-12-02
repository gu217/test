
var js_isLoad_v_login_model=1;
		
/* 字段定义开始 */
if(typeof(g_fieldDefMap)=='undefined') var g_fieldDefMap = new Array();
if(typeof(g_widgetObjMap)=='undefined') var g_widgetObjMap = new Array();

g_widgetObjMap['vHeader']='vHeader';
if(typeof(g_fieldDefMap['vHeader'])=='undefined') g_fieldDefMap['vHeader'] = new Array(); 
g_widgetObjMap['vFooter']='vFooter';
if(typeof(g_fieldDefMap['vFooter'])=='undefined') g_fieldDefMap['vFooter'] = new Array(); 
g_widgetObjMap['vLogin']='vLogin';
if(typeof(g_fieldDefMap['vLogin'])=='undefined') g_fieldDefMap['vLogin'] = new Array(); 
g_fieldDefMap['vLogin']['userName'] = new ObjFieldDef('userName','用户名','','','','3','18','','','非法用户名','^[0-9a-zA-Z]+[0-9a-zA-Z-]+$','','','',''); 
g_fieldDefMap['vLogin']['password'] = new ObjFieldDef('password','密码','','','','4','18','','','','','','','',''); 
g_fieldDefMap['vLogin']['userType'] = new ObjFieldDef('userType','用户类型','','','','','','','','','','','','',''); 
g_fieldDefMap['vLogin']['referUrl'] = new ObjFieldDef('referUrl','referurl','','','','','','','','','','','','',''); 
g_widgetObjMap['findPassword']='findPassword';
if(typeof(g_fieldDefMap['findPassword'])=='undefined') g_fieldDefMap['findPassword'] = new Array(); 
g_fieldDefMap['findPassword']['username'] = new ObjFieldDef('username','用户名','','','','','','','','1到14字符的非空字串','^[^ ]{1,14}$','','','',''); 
g_fieldDefMap['findPassword']['secCode'] = new ObjFieldDef('secCode','验证码','','','','','','','','请填写正确验证码','^[0-9]{4}$','','','',''); 
g_widgetObjMap['changePasswordWithKey']='changePasswordWithKey';
if(typeof(g_fieldDefMap['changePasswordWithKey'])=='undefined') g_fieldDefMap['changePasswordWithKey'] = new Array(); 
g_fieldDefMap['changePasswordWithKey']['password1'] = new ObjFieldDef('password1','新密码','','','','6','18','','','','','','','',''); 
g_fieldDefMap['changePasswordWithKey']['password2'] = new ObjFieldDef('password2','确认新密码','','','','6','18','','','','','','','',''); 
/* 字段定义结束 */
	
/* 用户登陆 */
function vLogin_login(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("userName", "vLogin"));
	arrFields.push(new RunParam("password", "vLogin"));
	arrFields.push(new RunParam("userType", "vLogin"));
	arrFields.push(new RunParam("referUrl", "vLogin"));
	var xmlRequest = createRequest(id, "vLogin", "login", arrFields, arrPresetFields);
	if (xmlRequest != null) {
		/* send request */
		if (xmlRequest != null) {
			var uri = null;
			if (typeof(g_ReqURI) != "undefined") {
				uri = g_ReqURI;
			} else {
				showDebug("error: unknown where to request.");
			}
			sendXmlRequest(uri, xmlRequest);
		} else {
			showDebug('failed to createRequest.');
		}
	}
	return xmlRequest;
}

/* 用户注销 */
function vLogin_logout(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("type", "vLogin"));
	var xmlRequest = createRequest(id, "vLogin", "logout", arrFields, arrPresetFields);
	if (xmlRequest != null) {
		/* send request */
		if (xmlRequest != null) {
			var uri = null;
			if (typeof(g_ReqURI) != "undefined") {
				uri = g_ReqURI;
			} else {
				showDebug("error: unknown where to request.");
			}
			sendXmlRequest(uri, xmlRequest);
		} else {
			showDebug('failed to createRequest.');
		}
	}
	return xmlRequest;
}

/* 找回密码 */
function findPassword_findPassword(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("username", "findPassword"));
	arrFields.push(new RunParam("secCode", "findPassword"));
	var xmlRequest = createRequest(id, "findPassword", "findPassword", arrFields, arrPresetFields);
	if (xmlRequest != null) {
		/* send request */
		if (xmlRequest != null) {
			var uri = null;
			if (typeof(g_ReqURI) != "undefined") {
				uri = g_ReqURI;
			} else {
				showDebug("error: unknown where to request.");
			}
			sendXmlRequest(uri, xmlRequest);
		} else {
			showDebug('failed to createRequest.');
		}
	}
	return xmlRequest;
}

/*  */
function changePasswordWithKey_changePasswordWithKey(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("password1", "changePasswordWithKey"));
	arrFields.push(new RunParam("password2", "changePasswordWithKey"));
	var xmlRequest = createRequest(id, "changePasswordWithKey", "changePasswordWithKey", arrFields, arrPresetFields);
	if (xmlRequest != null) {
		/* send request */
		if (xmlRequest != null) {
			var uri = null;
			if (typeof(g_ReqURI) != "undefined") {
				uri = g_ReqURI;
			} else {
				showDebug("error: unknown where to request.");
			}
			sendXmlRequest(uri, xmlRequest);
		} else {
			showDebug('failed to createRequest.');
		}
	}
	return xmlRequest;
}
