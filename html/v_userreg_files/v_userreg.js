
var js_isLoad_v_userreg_model=1;
		
/* 字段定义开始 */
if(typeof(g_fieldDefMap)=='undefined') var g_fieldDefMap = new Array();
if(typeof(g_widgetObjMap)=='undefined') var g_widgetObjMap = new Array();

g_widgetObjMap['vCommonUserReg']='vCommonUserReg';
if(typeof(g_fieldDefMap['vCommonUserReg'])=='undefined') g_fieldDefMap['vCommonUserReg'] = new Array(); 
g_fieldDefMap['vCommonUserReg']['userName'] = new ObjFieldDef('userName','用户名','','','','2','18','','','非法用户名','^[0-9a-zA-Z]+[0-9a-zA-Z-]+$','','js','alert(\'test\'); return false;',''); 
g_fieldDefMap['vCommonUserReg']['passWord1'] = new ObjFieldDef('passWord1','密码','','','','6','18','','','','','','','',''); 
g_fieldDefMap['vCommonUserReg']['passWord2'] = new ObjFieldDef('passWord2','确定密码','','','','6','18','','','','','','','',''); 
g_fieldDefMap['vCommonUserReg']['level_id'] = new ObjFieldDef('level_id','级别','','','','','','','','','','','','',''); 
g_fieldDefMap['vCommonUserReg']['email'] = new ObjFieldDef('email','邮箱','','','','','','','','请填写有效邮箱','^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|com|gov|mil|org|edu|int)$','','','',''); 
g_fieldDefMap['vCommonUserReg']['realName'] = new ObjFieldDef('realName','真实姓名','','','','','','','','真实姓名不能为空','^[^ ]+$','','','',''); 
g_fieldDefMap['vCommonUserReg']['industry'] = new ObjFieldDef('industry','行业','','','','','','','','','','','','',''); 
g_fieldDefMap['vCommonUserReg']['mobile'] = new ObjFieldDef('mobile','手机','','','','','','','','手机格式不正确','^([+]{0,1}(\\d){1,3}[ ]?([-]?((\\d)|[ ]){1,12})+)?$','','','',''); 
g_fieldDefMap['vCommonUserReg']['secCode'] = new ObjFieldDef('secCode','验证码','','','','','','','','请填写正确验证码','^[0-9]{4}$','','','',''); 
g_fieldDefMap['vCommonUserReg']['agree'] = new ObjFieldDef('agree','同意网络协议','','','','','','','','必须同意工业360网络服务协议','^1$','','','',''); 
g_widgetObjMap['vCorpUserReg']='vCorpUserReg';
if(typeof(g_fieldDefMap['vCorpUserReg'])=='undefined') g_fieldDefMap['vCorpUserReg'] = new Array(); 
g_fieldDefMap['vCorpUserReg']['userName'] = new ObjFieldDef('userName','用户名','','','','2','18','','','非法用户名','^[0-9a-zA-Z]+[0-9a-zA-Z-]+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['passWord1'] = new ObjFieldDef('passWord1','密码','','','','6','18','','','','','','','',''); 
g_fieldDefMap['vCorpUserReg']['passWord2'] = new ObjFieldDef('passWord2','确定密码','','','','6','18','','','','','','','',''); 
g_fieldDefMap['vCorpUserReg']['email'] = new ObjFieldDef('email','邮箱','','','','','','','','请填写有效邮箱','^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|com|gov|mil|org|edu|int)$','','','',''); 
g_fieldDefMap['vCorpUserReg']['realName'] = new ObjFieldDef('realName','真实姓名','','','','','','','','联系人不能为空','[^ ]+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['gender'] = new ObjFieldDef('gender','性别','','','','','','','','性别不正确','^1|0$','','','',''); 
g_fieldDefMap['vCorpUserReg']['tel'] = new ObjFieldDef('tel','固定电话','','','','','','','','固定电话格式不正确','^([+]?[0-9]{0,5}[-| |+]?[0-9]{7,8}[-| |+]?[0-9]{0,5})?$','','','',''); 
g_fieldDefMap['vCorpUserReg']['mobile'] = new ObjFieldDef('mobile','手机','','','','','','','','手机格式不正确','^([+]{0,1}(\\d){1,3}[ ]?([-]?((\\d)|[ ]){1,12})+)?$','','','',''); 
g_fieldDefMap['vCorpUserReg']['fax'] = new ObjFieldDef('fax','传真','','','','','','','','传真格式不正确','^([+]?[0-9]{0,5}[-| |+]?[0-9]{7,8}[-| |+]?[0-9]{0,5})?$','','','',''); 
g_fieldDefMap['vCorpUserReg']['corpName'] = new ObjFieldDef('corpName','企业名称','','','','','','','','企业名称不能为空','[^ ]+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['corpType'] = new ObjFieldDef('corpType','企业类型','','','','','','','','','','','','',''); 
g_fieldDefMap['vCorpUserReg']['industry'] = new ObjFieldDef('industry','所属行业','','','','','','','','','','','','',''); 
g_fieldDefMap['vCorpUserReg']['corpNature'] = new ObjFieldDef('corpNature','企业性质','','','','','','','','请选择企业性质','^\\d+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['turnover_id'] = new ObjFieldDef('turnover_id','营业额','','','','','','','','营业额不能为空','^[0-9]+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['areaCode'] = new ObjFieldDef('areaCode','地区','','','','','','','','地区不能为空','^[^0](\\d|_)+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['address'] = new ObjFieldDef('address','地址','','','','','','','','地址不能为空','[^ ]+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['zipCode'] = new ObjFieldDef('zipCode','邮编','','','','','','','','邮编不能为空','^[^ ]+$','','','',''); 
g_fieldDefMap['vCorpUserReg']['secCode'] = new ObjFieldDef('secCode','验证码','','','','','','','','请填写正确验证码','^[0-9]{4}$','','','',''); 
g_fieldDefMap['vCorpUserReg']['agree'] = new ObjFieldDef('agree','同意网络协议','','','','','','','','必须同意工业360网络服务协议','^1$','','','',''); 
g_widgetObjMap['userActive']='userActive';
if(typeof(g_fieldDefMap['userActive'])=='undefined') g_fieldDefMap['userActive'] = new Array(); 
g_widgetObjMap['userSuccess']='userSuccess';
if(typeof(g_fieldDefMap['userSuccess'])=='undefined') g_fieldDefMap['userSuccess'] = new Array(); 
/* 字段定义结束 */
	
/* 检验用户名 */
function vCommonUserReg_checkUserName(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("userName", "vCommonUserReg"));
	arrFields.push(new RunParam("usertype", "vCommonUserReg"));
	var xmlRequest = createRequest(id, "vCommonUserReg", "checkUserName", arrFields, arrPresetFields);
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

/* 注册用户 */
function vCommonUserReg_commonUserReg(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("userName", "vCommonUserReg"));
	arrFields.push(new RunParam("passWord1", "vCommonUserReg"));
	arrFields.push(new RunParam("passWord2", "vCommonUserReg"));
	arrFields.push(new RunParam("realName", "vCommonUserReg"));
	arrFields.push(new RunParam("level_id", "vCommonUserReg"));
	arrFields.push(new RunParam("email", "vCommonUserReg"));
	arrFields.push(new RunParam("industry", "vCommonUserReg"));
	arrFields.push(new RunParam("mobile", "vCommonUserReg"));
	arrFields.push(new RunParam("secCode", "vCommonUserReg"));
	arrFields.push(new RunParam("agree", "vCommonUserReg"));
	var xmlRequest = createRequest(id, "vCommonUserReg", "commonUserReg", arrFields, arrPresetFields);
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

/* 检验用户名 */
function vCorpUserReg_checkUserName(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("userName", "vCorpUserReg"));
	var xmlRequest = createRequest(id, "vCorpUserReg", "checkUserName", arrFields, arrPresetFields);
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

/* 注册用户 */
function vCorpUserReg_corpUserReg(id) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	arrFields.push(new RunParam("userName", "vCorpUserReg"));
	arrFields.push(new RunParam("passWord1", "vCorpUserReg"));
	arrFields.push(new RunParam("passWord2", "vCorpUserReg"));
	arrFields.push(new RunParam("corpName", "vCorpUserReg"));
	arrFields.push(new RunParam("corpType", "vCorpUserReg"));
	arrFields.push(new RunParam("industry", "vCorpUserReg"));
	arrFields.push(new RunParam("corpNature", "vCorpUserReg"));
	arrFields.push(new RunParam("turnover_id", "vCorpUserReg"));
	arrFields.push(new RunParam("realName", "vCorpUserReg"));
	arrFields.push(new RunParam("gender", "vCorpUserReg"));
	arrFields.push(new RunParam("email", "vCorpUserReg"));
	arrFields.push(new RunParam("mobile", "vCorpUserReg"));
	arrFields.push(new RunParam("tel", "vCorpUserReg"));
	arrFields.push(new RunParam("fax", "vCorpUserReg"));
	arrFields.push(new RunParam("areaCode", "vCorpUserReg"));
	arrFields.push(new RunParam("address", "vCorpUserReg"));
	arrFields.push(new RunParam("zipCode", "vCorpUserReg"));
	arrFields.push(new RunParam("agree", "vCorpUserReg"));
	arrFields.push(new RunParam("secCode", "vCorpUserReg"));
	var xmlRequest = createRequest(id, "vCorpUserReg", "corpUserReg", arrFields, arrPresetFields);
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
