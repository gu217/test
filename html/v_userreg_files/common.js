
function showDebug(msg, id, bAlert) {
	/* no debug */
}

ObjBuff = function() {
}

ObjBuff.prototype.loadResource = function(src, type) {
	if (typeof(type) != "string" || type.length < 0) {
		///showDebug("unknown resource type to load!");
		return null;
	}
	if (typeof(this.m_ResPool) != "object") {
		this.m_ResPool = new Object();
	}
	if (typeof(this.m_ResPool[type]) != "object") {
		this.m_ResPool[type] = new Object();
	}
	if (typeof(this.m_head) != "object" || this.m_head == null) {
		var _list = document.getElementsByTagName("head");
		if (_list.length <= 0) {
			///showDebug("not find head tag!");
			return null;
		}
		this.m_head = _list[0];
		if (typeof(this.m_head) != "object" || this.m_head == null) {
			///showDebug("error head!");
			return null;
		}
	}
	if (typeof(this.m_objRefPos) != "object" || this.m_objRefPos == null) {
		this.m_objRefPos = getObjById("page_css");
		if (this.m_objRefPos == null) {
			///showDebug("not find refpos to load resource!");
			return null;
		}
	}
	if (typeof(this.m_ResPool[type][src]) == "object" && this.m_ResPool[type][src] != null) {/* lookup cache */
		///showDebug("resource cached:" + src, null, false);
		return this.m_ResPool[type][src];
	}

	var _tagname;
	if (type == "js") {
		_tagname = "script";
	} else if (type == "css") {
		_tagname = "link";
	}
	var _listObj = this.m_head.getElementsByTagName(_tagname);
	var objSrc = null;
	for (var i = 0; i < _listObj.length; ++i) {/* search exists objs */
		var child = _listObj[i];
		if (typeof(child) != "object" || child == null || typeof(child.getAttribute) == "undefined") {
			continue;
		}
		if (child.getAttribute("src") == src) {
			///showDebug("resource loaded before:" + src, null, false);
			objSrc = child;
			break;
		}
	}
	if (objSrc == null) {/* create new obj */
		if (type == "js") {
			objSrc = document.createElement("script");
			objSrc.setAttribute("charset", "UTF-8");
			objSrc.setAttribute("src", src);
			objSrc.setAttribute("type", "text/javascript");
		} else if (type == "css") {
			objSrc = document.createElement("link");
			objSrc.setAttribute("href", src);
			objSrc.setAttribute("rel", "stylesheet");
			objSrc.setAttribute("type", "text/css");
		}
		this.m_objRefPos.parentNode.insertBefore(objSrc, this.m_objRefPos);
		///showDebug("resource dyna loaded:" + src, null, false);
	}
	this.m_ResPool[type][src] = objSrc;
	return this.m_ResPool[type][src];
}

ObjBuff.prototype.clearWidget = function(id) {
	if (typeof(id) != "string" || id.length <= 0) {
		///showDebug("invalid param to clearWidget!");
		return;
	}
	if (typeof(this.widget) == "object") {
		this.widget[id] = null;
	}
	if (typeof(this.namedObjPool) == "object") {
		this.namedObjPool[id] = null;
	}
}

ObjBuff.prototype.getWidgetById = function(id) {
	if (typeof(id) != "string" || id.length <= 0) {
		///showDebug("invalid param to getWidgetById!");
		return null;
	}
	if (typeof(this.widget) != "object" || typeof(this.widget[id]) != "object") {
		this.cacheWidget(id);
	}
	if (typeof(this.widget) != "object" || typeof(this.widget[id]) != "object") {
		///showDebug("failed to cacheWidget:" + id);
		return null;
	}
	return this.widget[id];
}

ObjBuff.prototype.getNamedObjPool = function(id) {
	if (typeof(id) != "string" || id.length <= 0) {
		///showDebug("invalid param to getNamedObjPool!");
		return null;
	}
	if (typeof(this.namedObjPool) != "object" || typeof(this.namedObjPool[id]) != "object") {
		this.cacheWidget(id);
	}
	if (typeof(this.namedObjPool) != "object" || typeof(this.namedObjPool[id]) != "object") {
		///showDebug("failed to cacheWidget:" + id);
		return null;
	}
	return this.namedObjPool[id];
}

ObjBuff.prototype.cacheWidget = function(id) {
	if (typeof(id) != "string" || id.length <= 0) {
		return;
	}
	if (typeof(this.namedObjPool) != "object") {
		this.namedObjPool = new Object();
	}
	if (typeof(this.widget) != "object") {
		this.widget = new Object();
	}
	this.widget[id] = getObjById(id);
	this.namedObjPool[id] = new Object();
	this.collectNamedObj(this.namedObjPool[id], this.widget[id]);
	return this.widget[id];
}

ObjBuff.prototype.collectNamedObj = function(pool, par) {
	if (par == null) {
		return;
	}
	for (var i = 0; i < par.childNodes.length; ++i) {
		var child = par.childNodes[i];
		if (child != null && typeof(child.getAttribute) != "undefined") {
			var name = child.getAttribute("name");
			if (typeof(name) == "string" && name.length > 0) {
				this.pushObj(pool, child, name);
			}
			this.collectNamedObj(pool, child);
		}
	}
}

ObjBuff.prototype.pushObj = function(pool, obj, name) {
	if (typeof(obj) != "object" || obj == null) {
		///showDebug("not a valid obj to buff!");
		return false;
	}
	if (typeof(name) != "string") {
		name = obj.getAttribute("name");
	}
	if (typeof(name) != "string" || name.length <= 0) {
		///showDebug("unknown obj's name to buff!" + xmlText(obj));
		return false;
	}
	if (typeof(pool[name]) != "object") {
		pool[name] = new Array();
	}
	pool[name].push(obj);
	return true;
}

var g_ObjBuff = new ObjBuff();

String.prototype.trim=function(){
	return this.replace(/(^\s*)|(\s*$)/g, "");
}
String.prototype.ltrim=function(){
	return this.replace(/(^\s*)/g,"");
}
String.prototype.rtrim=function(){
	return this.replace(/(\s*$)/g,"");
}

function isLeapYear(year) {
	if(year % 400 == 0) return true;
	else if(year % 100 == 0) return false;
	else if(year % 4 == 0) return true;
	else return false;
}

function parseDate(dateString,formatString){
	var regDate = /\d+/g;
	var regFormat = /[YyMmdHhiSs]+/g;
	var dateMatches = dateString.match(regDate);
	if(dateMatches==null) return null;
	var formatmatches = formatString.match(regFormat);
	if(formatmatches==null) return null;
	var retdate = new Date();
	var year=0,month=0,day=0,hour=0,minute=0,second=0;
	for(var i=0;i<dateMatches.length && i<6;i++){
	    switch(formatmatches[i].substring(0,1)){
	        case 'Y':
	        case 'y':
				year = parseInt(dateMatches[i],10);break;
	        case 'M':
	        case 'm':
	        	month = parseInt(dateMatches[i],10);break;
	        case 'd':
	        	day = parseInt(dateMatches[i],10);break;
	        case 'H':
	        case 'h':
	        	hour = parseInt(dateMatches[i],10);break;
	        case 'i':
	        	minute = parseInt(dateMatches[i],10);break;
	        case 'S':
	        case 's':
	        	second = parseInt(dateMatches[i],10);break;
	    }
	}
	if(month<1 || month>12) return null;
	if(day<1 || day>31) return null;
	if(month==2 && day>29) return null;
	if(month==2 && !isLeapYear(year) && day>28) return null;
	if((month==4 || month==6 || month==9 || month==11) && day>30) return null;
	if(year>0) retdate.setFullYear(year);
	if(month>0) retdate.setMonth(month-1);
	if(day>0) retdate.setDate(day);
	retdate.setHours(hour);
	retdate.setMinutes(minute);
	retdate.setSeconds(second);
	return retdate;
}

function showValidateMsg(msg) {
	return new MessageBox('提示信息', msg, 'info', '', '');
}

ObjFieldDef = function(name,label,type,isarray,enabalnull,minlen,maxlen,minval,maxval,match_hint,match_val,script_src,script_type,script_val,html_type) {
	this.name = name;
	this.label = label;
	this.type = ((type=='') ? 'string' : type);
	this.isarray = ((isarray=='true') ? true : false);
	this.enabalnull = ((enabalnull=='false') ? false : true);
	this.minlen = minlen;
	this.maxlen = maxlen;
	if(this.type=='int') {
		this.minInt = parseInt(minval);
		this.maxInt = parseInt(maxval);
	} else if(this.type=='float') {
		this.minFloat = parseFloat(minval);
		this.maxFloat = parseFloat(maxval);
	} if(this.type=='date') {
		this.minDate = parseDate(minval,'Y-m-d H:i:s');
		this.maxDate = parseDate(maxval,'Y-m-d H:i:s');
	} 
	this.minval = minval;
	this.maxval = maxval;
	this.match_hint = match_hint;
	this.match_val = match_val;
	this.script_src = script_src;
	this.script_type = script_type;
	this.script_val = script_val;
	this.html_type  = html_type;
}
ObjFieldDef.prototype.validate = function(inputstr){
	if(this.type=='int'){
		return this.intValidate(inputstr);
	} else if(this.type=='float') {
		return this.floatValidate(inputstr);
	} else if(this.type=='date') {
		return this.dateValidate(inputstr);
	} else if(this.type=='html' || this.html_type !='') {
		return this.htmlValidate(inputstr);
	} else {
		return this.stringValidate(inputstr);
	}
}
		
ObjFieldDef.prototype.stringValidate = function(inputstr) {
	if(inputstr=='' || typeof(inputstr) == undefined) {
		if(!this.enabalnull){
			showValidateMsg(this.label+'不能为空！');
			return false;
		}
	}
	var inputlen = inputstr.length;
	var _minlen = parseInt(this.minlen);
	var _maxlen = parseInt(this.maxlen);
	if(isNaN(_minlen)) _minlen=0;
	if(isNaN(_maxlen)) _maxlen=0;
	if(_minlen>0 && inputlen<_minlen){
		showValidateMsg(this.label+'小于最小长度：'+_minlen+' ！');
		return false;
	}
	if(_maxlen>0 && inputlen>_maxlen){
		showValidateMsg(this.label+'超过最大长度：'+_maxlen+' ！');
		return false;
	}
	if(this.match_val != '')	{
		var errmsg;
		if(this.match_hint == '') {
			errmsg = this.label+'非法输入，请输入正确的信息！';
		} else {
			errmsg = this.match_hint.replace('\$label',this.label);
		}
		var pattern = new RegExp(this.match_val);
		if(!pattern.test(inputstr)){
			showValidateMsg(errmsg);
			return false;
		}
	}
	return true;
}
	
ObjFieldDef.prototype.intValidate = function(inputstr) {
	if(inputstr=='' || typeof(inputstr) == undefined) {
		if(!this.enabalnull){
			showValidateMsg(this.label+'不能为空！');
			return false;
		}
		else
		{
			return true;
		}
	}
	var intVal = 0;
    if(inputstr!='') intVal = parseInt(inputstr);
	if(isNaN(intVal)) {
		showValidateMsg(this.label+'不是整数，请输入相应的整数 ！');
		return false;
	}
	if(!isNaN(this.minInt) && intVal < this.minInt){
		showValidateMsg(this.label+'不能小于最小值：'+this.minval+' ！');
		return false;
	}
	if(!isNaN(this.maxInt) && intVal > this.maxInt){
		showValidateMsg(this.label+'不能大于最大值：'+this.maxval+' ！');
		return false;
	}
	return true;
}

ObjFieldDef.prototype.floatValidate = function(inputstr) {
	if(inputstr=='' || typeof(inputstr) == undefined) {
		if(!this.enabalnull){
			showValidateMsg(this.label+'不能为空！');
			return false;
		}
		else
		{
			return true;
		}
	}
	var floatVal = 0;
	if(inputstr!='') floatVal = parseFloat(inputstr);
	if(isNaN(floatVal)) {
		showValidateMsg(this.label+'不是数值，请输入相应的数值 ！');
		return false;
	}
	if(!isNaN(this.minFloat) && floatVal < this.minFloat){
		showValidateMsg(this.label+'不能小于最小值：'+this.minval+' ！');
		return false;
	}
	if(!isNaN(this.maxFloat) && floatVal > this.maxFloat){
		showValidateMsg(this.label+'不能大于最大值：'+this.maxval+' ！');
		return false;
	}
	return true;
}

ObjFieldDef.prototype.dateValidate = function(inputstr) {
	if(inputstr=='' || typeof(inputstr) == undefined) {
		if(!this.enabalnull){
			showValidateMsg(this.label+'不能为空！');
			return false;
		}
		else
		{
			return true;
		}
	}
	var dateVal = parseDate(inputstr,'Y-m-d H:i:s');
	if(dateVal==null) {
		showValidateMsg(this.label+'格式不正确，请输入正确的日期！');
		return false;
	}
	if(this.minDate != null && dateVal < this.minDate){
		showValidateMsg(this.label+'不能小于最小值：'+this.minval+' ！');
		return false;
	}
	if(this.maxDate != null && dateVal > this.maxDate){
		showValidateMsg(this.label+'不能大于最大值：'+this.maxval+' ！');
		return false;
	}
	return true;
}

ObjFieldDef.prototype.htmlValidate = function(inputstr) {
	if(inputstr=='' || typeof(inputstr) == undefined) {
		if(!this.enabalnull){
			showValidateMsg(this.label+'不能为空！');
			return false;
		}
		else
		{
			return true;
		}
	}
	var inputlen = inputstr.length;
	var _minlen = parseInt(this.minlen);
	var _maxlen = parseInt(this.maxlen);
	if(isNaN(_minlen)) _minlen=0;
	if(isNaN(_maxlen)) _maxlen=0;
	if(_minlen>0 && inputlen<_minlen){
		showValidateMsg(this.label+'小于最小长度：'+_minlen+' ！');
		return false;
	}
	if(_maxlen>0 && inputlen>_maxlen){
		showValidateMsg(this.label+'超过最大长度：'+_maxlen+' ！');
		return false;
	}
	if(this.html_type != '')	{
		return this.testHtml(inputstr);
	}
	return true;
}

ObjFieldDef.prototype.testHtml = function(inputstr) {
	var allowTagArr = new Array();
	var noAllowTagArr = new Array();
	if(this.html_type == "review" || this.html_type == "reply")
	{
		allowTagArr = Array("a","strong","em","u","span","img","br","p","h1","strong","b","font");
	}
	if(this.html_type == "blog")
	{
		noAllowTagArr = Array("script","style","meta","body","iframe");
	}
	if(this.html_type == "wiki" || this.html_type == "ask" || this.html_type == "group")
	{
		noAllowTagArr = Array("script","style","meta","body","iframe");
	}
	//没有验证条件直接退出
	if((allowTagArr.length==0) && (noAllowTagArr.length == 0))	return true;
	var haveTagArr = new Array();
	var noAllowTag = new Array();
	var tag = /<[\/]?([0-9a-z!]*)\s*?[^<|>]*?/gi;
	var rr;
	var i=-1;
	
	//获取所有的不重复标签名
	while((rr = tag.exec(inputstr))!=null) {
		var jnum = 0;
		if(rr[1]!="") {
			var getValue;
			for(var j=0;j<=haveTagArr.length;j++) {
				getValue = rr[1].toLowerCase();
				if(haveTagArr[j] != getValue) {
					jnum++;
				}
			}
			if((jnum == haveTagArr.length+1) && (jnum>0)) {
				i++;
				haveTagArr[i] = getValue;
			}
		}
	}
	var haveTagLength = haveTagArr.length;
	//检查不允许存在的指定的标签
	var noAllowTagLength = noAllowTagArr.length;
	if(noAllowTagLength>0)	{
		for(var i=0;i<haveTagLength;i++) {
			for(var j=0;j<noAllowTagLength;j++)	{
				if(haveTagArr[i]==noAllowTagArr[j])	{
					showValidateMsg(this.label+"存在不允许的标签"+noAllowTagArr[j]+"，请检查源码或删除后用纯文本重新录入");
					return false;
				}
			}
		}
		return true;
	}
	//查询不符合条件的标签
	var allowTagLength = allowTagArr.length;
	var allownum = -1;
	for(var i=0;i<haveTagLength;i++) {
		var jnum = 0;
		for(var j=0;j<allowTagLength;j++) {
			if(haveTagArr[i]!=allowTagArr[j]) {
				jnum++;
			}
		}
		if(jnum == allowTagLength) {
			allownum++;
			noAllowTag[allownum] = haveTagArr[i];
		}
	}
	if(noAllowTag == "") return true;
	else {
		showValidateMsg(this.label+"存在不允许的标签"+noAllowTag+"，请检查源码或删除后用纯文本重新录入!");
		return false;
	}
	return true;
}


var g_ReqURI = "/common/call.php"; 
if(document.location.host.indexOf('kangq.net')>=0) g_ReqURI="common/call.php";
var g_arrEditor = new Array();

function getObjById(id) {
	if ((typeof(id) != "string") || (id == "")) {
		///showDebug("id not defined");
		return null;
	}
	var widget = document.getElementById(id);
	if (widget == null) {
		///showDebug("not find object:" + id);
	}
	return widget;
}

function getBody() {
	return document.getElementsByTagName("body")[0];
}

function calYTail() {
	return getBody().offsetHeight;
}

RunParam = function(name, ns, value) {
	this.name = name;
	this.ns = ns;
	this.value = value;
}

function _getElementText(node) {
	if (typeof(node) == "undefined" || node == null) {
		return null;
	}
	var text = "";
	if (typeof(node.childNodes) != "undefined") {
		for (var i = 0; i < node.childNodes.length; ++i) {
			var textChild = _getElementText(node.childNodes.item(i));
			if (textChild != null) {
				text += textChild;
			}
		}
	}
	if (typeof(node.nodeValue) != "undefined" && node.nodeValue != null) {
		text += node.nodeValue;
	}
	return text;
}

function parseXml(text) {
	var doc = null;
	if (window.ActiveXObject) {// code for IE
		doc = new ActiveXObject("Microsoft.XMLDOM");
		doc.async = "false";
		if (!doc.loadXML(text)) {
			///showDebug("err: parseXml in ie failed, use ajax xmlParse:\n" + text);
			doc = xmlParse(text);
		}
	} else {// code for Mozilla, Firefox, Opera, etc.
		var parser = new DOMParser();
		doc = parser.parseFromString(text,"text/xml");
	}
	///showDebug("parseXml result:\n" + xmlText(doc));
	return doc;
}

function clearDebug(id) {
	if (typeof(id) == "undefined") {
		id = "showDebug";
	}
	var obj = document.getElementById(id);
	if (obj != null) {
		var list = obj.getElementsByTagName("div");
		for (var i = 0; i < list.length; ++i) {
			if (list[i].className == "debug_content") {
				obj = list[i];
				obj.innerHTML = "";
				break;
			}
		}
	}
}

/* 在context中取名称为item的对象 */
function _getItems(id, item) {
	if (typeof(item) != "string" || item.length <= 0) {
		///showDebug("param error: unknown item name!");
		return null;
	}
	var namedObjPool = g_ObjBuff.getNamedObjPool(id);
	if (namedObjPool != null) {
		if (typeof(namedObjPool[item]) == "object") {
			return namedObjPool[item];
		} else if (typeof(namedObjPool[item + "[]"]) == "object") {
			return namedObjPool[item + "[]"];
		}
	}
	return null;
}

/* 在id所指对象中取名称为item的对象 */
function getWidgetItems(id, item) {
	return _getItems(id, item);
}
/*
function getWidgetItems_old(id, item) {
	if (typeof(item) != "string" || item.length <= 0) {
		///showDebug("param error: unknown item name!");
		return null;
	}
	var obj = getObjById(id);
	if (obj == null) {
		return null;
	}
	var context = new ExprContext(obj);
	return _getItems(context, item);
}
*/

/* 在id所指对象中取名称为item的第一个对象 */
function getWidgetItem(id, item) {
	var list = getWidgetItems(id, item);
	if (list != null && list.length >= 1) {
		return list[0];
	} else {
		return null;
	}
}

function addSelectOption(widgetId, item, value, txt, active) {
	try {
		var objSelect = getWidgetItem(widgetId, item);
		if (objSelect == null) {
			///showDebug("not find item:(" + widgetId + ", " + item + ")");
			return false;
		} else {
			if (objSelect.nodeName.toLowerCase() != "select") {
				///showDebug("item is not select:" + item);
				return false;
			} else {
				var objOption = document.createElement("option");
				objOption.value = value;
				if (typeof(txt) == "string") {
					objOption.text = txt;
				} else {
					objOption.text = "";
				}
				try {// standard
					objSelect.add(objOption, null);
				} catch (e1) {// IE
					objSelect.add(objOption);
				}
				if (typeof(active) != "undefined" && active) {
					objSelect.selectedIndex = objSelect.length - 1;
				}
				return true;
			}
		}
	} catch (e) {
		///showDebug("exception:" + e);
		return false;
	}
}

function mergeDOM(domA, domB) {
	if (domA == undefined || domB == undefined || domA == null || domB == null) {
		return domA;
	}
  var nameRoot = domA.documentElement.nodeName;
	if (typeof(nameRoot) == "string" && nameRoot.length > 0) {
		var eleB = domB.documentElement;
		if (eleB != undefined
				&& eleB.nodeName != undefined
				&& eleB.nodeName == nameRoot) {
			var destRoot = domA.documentElement;
			for (var i = 0; i < eleB.childNodes.length; ++i) {
				destRoot.appendChild(eleB.childNodes[i]);
			}
		}
	}
	return domA;
}

/* 加载script */
function LoadScript(url) {
	document.write('<script type="text/javascript" src="' + url + '" onerror="alert(\'Error loading \' + this.src);"><\/script>');
}

function getNavigatorCore() {
	var sCore = /msie/.test(navigator.userAgent.toLowerCase()) ? 'ie' : 'gecko' ;
	return sCore;
}

function getEditorContent(inst) {
	if (!LoadCkeditorJS()) {
		return null;
	}
	if (typeof(inst) == "string") { 
		var ckInstance = CKEDITOR.instances[inst];
		var content = ckInstance.getData();
		content = cleanEditorContent(content);
		return content;
	} else {
		///showDebug('no fckeditor instance to getContent.');
		return null;
	}
}

function cleanEditorContent(htmlstr)
{
	var html = htmlstr;
	html = html.replace( /<\/?html>/gi, '');
	html = html.replace(/<\/?body>/gi, ''); 
	html = html.replace( /<meta[^>]*>/gi, '');
	// Remove HEAD	
	html = html.replace( /<head[^>]*>[\S\s]*?<\/head>/gim, '');
	// Remove Script
	html = html.replace( /<script[^>]*>[\S\s]*?<\/script>/gim, '');
	html = html.replace( /<noscript[^>]*>[\S\s]*?<\/noscript>/gim, '');
	// Remove style	
	//html = html.replace( /<style[^>]*>[\S\s]*?<\/style>/gim, '');
	// Remove form	
	html = html.replace( /<form[^>]*>[\S\s]*?<\/form>/gim, '');
	// Remove iframe
	html = html.replace( /<\/?iframe[^>]*>/gi, '');
	// Remove frame
	html = html.replace( /<\/?frame[^>]*>/gi, '');
	// Remove noframes
	html = html.replace( /<noframes[^>]*>[\S\s]*?<\/noframes>/gim, '');
	// Remove frameset
	html = html.replace( /<frameset[^>]*>[\S\s]*?<\/frameset>/gim, '');
	// Remove link
	html = html.replace( /<\/?link[^>]*>/gi, '');
	// Remove object
	html = html.replace( /<object[^>]*>[\S\s]*?<\/object>/gim, '');
	return html;
}

function setEditorContent(inst) {
	var dest = document.getElementById(inst);
	if (dest == null) {
		///showDebug('not find dest to setContent.');
		return;
	}
	var content = getEditorContent(inst);
	if (content != null) { 
		dest.value = content;
	}
}

function setEditorToolbar(inst, type) {

}

function watchFckeditor(inst) {
	if (typeof(g_arrEditor) == "undefined") {
		g_arrEditor = new Array();
	}
	if (typeof(inst) != "string") {
		return;
	}
	for (var i = 0; i < g_arrEditor.length; ++i) {
		if (g_arrEditor[i] == inst) {
			return;
		}
	}
	g_arrEditor.push(inst);
}

/* 加载fckeditor相关脚本 */
function LoadCkeditorJS() {
	if (typeof(CKEDITOR) == "undefined") {
	/*
		var sCore = getNavigatorCore();
		LoadScript('common/fckeditor/editor/js/fckeditorcode_' + sCore + '.js');
		if (typeof(InitializeAPI) == "undefined") {
			///showDebug("not find InitializeAPI for fckeditor.");
			return false;
		}
		InitializeAPI();
		if (typeof(FCKeditorAPI) == "undefined") {
			///showDebug("failed to InitializeAPI.");
			return false;
		}
		*/
		return false;
	}
	return true;
}

/* 重载 window.setTimeout,以便传递参数 */
var __setTimeout = window.setTimeout;
window.setTimeout = function(fRef, nDelay) {
	if(typeof(fRef) == 'function'){
		var argu = Array.prototype.slice.call(arguments, 2);
		var f = (function(){ fRef.apply(null, argu); }); 
		return __setTimeout(f, nDelay);
	} else {
		return __setTimeout(fRef, nDelay);
	}
}

/* 按名字选取子孙 */
function getDescendantByName(base, name) {
	var arrObjs = [];
	
}

/* 按名字取值 */
function getValueByName(base, name) {
}

/* 取元素的值 */
function getValueOfObj(obj) {
	if ((typeof(obj) != "object")
		|| (obj == null)
		|| (typeof(obj.tagName) != "string")) {
		return null;
	}
	var tag = obj.tagName.toUpperCase();
	if (tag == "INPUT") {
		var type = obj.type.toUpperCase();
		if (type == "RADIO" || type == "CHECKBOX") {
			if (!obj.checked) {
				return null;
			}
		}
		return obj.value;
	} else if (tag == "SELECT" && obj.multiple) { 
			var  vals = new Array();
			for(var i=0;i<obj.options.length;i++)
			{
				if(obj.options[i].selected) vals.push(obj.options[i].value);
			}
			if(vals.length == 0) return null;
			else if(vals.length == 1) return vals[0];
			else return vals;
			
			
	}else {
		if (typeof(obj.value) != "undefined")
			return obj.value;
		else {
			///showDebug("unknown tag to getValue:" + obj.nodeName);
			return null;
		}
	}
}

/* 查找field是否在arr中 */
function inArray(arr, field) {
	if ((typeof(field) == "string") && (field.length > 0)
		&& (typeof(arr) == "object")) {
		for (var i = 0; i < arr.length; ++i) {
			if (arr[i].name == field) {
				return arr[i];
			}
		}
	}
	return;
}

/* 收集请求信息 */
function createRequest(id, widgetName, action, arrFields, arrPresetFields) {
	try {
		try {
			for (var i = 0; i < g_arrEditor.length; ++i) {
				setEditorContent(g_arrEditor[i]);
			}
		} catch (e) {
			///showDebug('exception in setEditorContent:\n' + e);
		}

		//var widget = getObjById(id);
		var widget = g_ObjBuff.cacheWidget(id);
		if (widget == null) {
			///showDebug("not find obj to createRequest:" + id);
			return null;
		}

		var docRequest = new XDocument();
		var root = docRequest.createElement("request");
		root.setAttribute("referer", escape(document.URL));
		root.setAttribute("referfrom", escape(document.referrer));
		/*
		root.setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
		root.setAttribute("xsi:noNamespaceSchemaLocation", "common/xsd/request.xsd");
		*/
		docRequest.appendChild(root);

		var widget_name = null;
		if ((typeof(widgetName) != "string") || (widgetName.length <= 0)) {
			var widget_class = widget.className;
			if ((typeof(widget_class) == "string") && (widget_class.length > 0)) {
				var arrClassParts = widget_class.split(" ");
				if (arrClassParts.length > 0) {
					widget_name = arrClassParts[0];
				} else {
					///showDebug("widget class error:" + widget_class);
				}
			}
		} else {
			widget_name = widgetName;
		}
		if ((typeof(widget_name) != "string") || (widget_name.length <= 0)) {
			///showDebug("widget name invalid.");
			return null;
		}

		var eleAction = docRequest.createElement("action");
		eleAction.setAttribute("name", action);
		eleAction.setAttribute("widgetName", widget_name);
		eleAction.setAttribute("sourceWidget", widget.id);
		root.appendChild(eleAction);

		if ((typeof(arrFields) == "object") && (arrFields != null) && (arrFields.length > 0)) {/* 取参数 */
			var namedObjPool = g_ObjBuff.getNamedObjPool(id);
			//var context = new ExprContext(widget);

			var _t1 = new Date();
			for (var i = 0; i < arrFields.length; ++i) {
				var infoField = arrFields[i];
				var fldObj = null;
				if(typeof(g_fieldDefMap[widgetName]) =='object') fldObj=g_fieldDefMap[widgetName][infoField.name];
				if(fldObj == null) {
					var fldobjName = g_widgetObjMap[widgetName];
					if(fldobjName!=null && g_fieldDefMap[fldobjName]!=null && typeof(g_fieldDefMap[fldobjName]) =='object') fldObj=g_fieldDefMap[fldobjName][infoField.name];
				}
				var bNeedValidate = false;
				if (fldObj!=null && typeof(fldObj) =='object') bNeedValidate = true;
				var value = null;
				var bIsJson = false;
				var list = getWidgetItems(id, infoField.name);
				if (list != null && list.length > 0) {
					var arrValue = new Array();
					for (var j = 0; j < list.length; ++j) {
						var objItem = list[j];
						v = getValueOfObj(objItem);
						if (v != null) {
							if(typeof(v) =='string') v=v.trim();
							if(typeof(v) =='object' && v.length)
							{
								for(var j=0;j<v.length;j++)
								{
									arrValue.push(v[j]);
								}
							}
							else
							{
								if (bNeedValidate) {
									var bIsValid = fldObj.validate(v);
									if (!bIsValid) {
										objItem.focus();
										return null;
									}
								}
								arrValue.push(v);
							}
						}
					}
					if (arrValue.length > 0) {
						if (arrValue.length == 1) {
							value = arrValue[0];
						} else {
							value = JSON.stringify(arrValue);
							bIsJson = true;
						}
					}
				}
				if (value == null) {
					if (bNeedValidate) {
						var bIsValid = fldObj.validate('');
						if (!bIsValid) {
							if (list != null && list.length > 0) {
								list[0].focus();
							}
							return null;
						}
					}
				} else {
					var eleParam = docRequest.createElement("param");
					eleParam.setAttribute("name", infoField.name);
					if (bIsJson) {
						eleParam.setAttribute("format", "json");
					}
					eleParam.appendChild(docRequest.createTextNode(value));
					eleAction.appendChild(eleParam);
				}
			}
			var _t2 = new Date();
			var _dur = _t2.getTime() - _t1.getTime();
			///showDebug("debug: cost " + _dur + " ms to collect data!");
		}
		if ((typeof(arrPresetFields) == "object") && (arrPresetFields != null)) {
			for (var key in arrPresetFields) {
				if (!arrPresetFields.hasOwnProperty(key)) {
					continue;
				}
				var infoField = arrPresetFields[key];
				if (typeof(infoField) != 'object') {
					continue;
				}
				var fldobjName = g_widgetObjMap[widgetName];
				var fldObj = null;
				if(fldobjName!=null && g_fieldDefMap[fldobjName]!=null && typeof(g_fieldDefMap[fldobjName]) =='object') fldObj=g_fieldDefMap[fldobjName][infoField.name];
				var bNeedValidate = false;
				if (fldObj!=null && typeof(fldObj) =='object') bNeedValidate = true;
				
				var value = infoField.value;
				if (bNeedValidate) {
					var bIsValid = fldObj.validate(value);
					if (!bIsValid) {
						///showDebug('value of preset param not validate, probably devlop error:' + funcCheck);
						return null;
					}
				}
				var eleParam = docRequest.createElement("param");
				eleParam.setAttribute("name", key);
				eleParam.appendChild(docRequest.createTextNode(value));
				eleAction.appendChild(eleParam);
			}
		}

		return docRequest;
	} catch (e) {
		///showDebug("exception:" + e);
		return null;
	}
}



/* 准备初始化widget的请求 */
function prepareInitWidget(id, widgetName, arrPresetFields) {
	var xmlRequest = null;
	if (typeof(widgetName) != "undefined") {
		xmlRequest = createRequest(id, widgetName, "init", null,arrPresetFields);
	} else {
		xmlRequest = createRequest(id, null, "init", null, arrPresetFields);
	}
	return xmlRequest;
}

/**
 * 通用的ajax动态请求，所有keys从当前网页中使用对象name获取
 * @param id 网页id
 * @param widget 部件名称
 * @param action 部件行为名称
 * @param keys array类型，字串数组
 */
function customDynamicRequest(id, widget, action, keys) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	for(var i=0;i<keys.length;i++){
		arrFields.push(new RunParam(keys[i], widget));
	}
	var xmlRequest = createRequest(id, widget, action, arrFields, arrPresetFields);
	if (xmlRequest != null) {
		/* send request */
		if (xmlRequest != null) {
			var uri = null;
			if (typeof(g_ReqURI) != "undefined") {
				uri = g_ReqURI;
			} else {
				///showDebug("error: unknown where to request.");
			}
			sendXmlRequest(uri, xmlRequest);
		} else {
			///showDebug('failed to createRequest.');
		}
	}
	return xmlRequest;
}

/**
 * 通用的ajax静态请求，需要自己在value中赋值
 * @param id 网页id
 * @param widget 部件名称
 * @param action 部件行为名称
 * @param keys array类型，字串数组
 * @param values array类型，字串值
 */
function customStaticRequest(id, widget, action, keys, values) {
	var arrFields = new Array();
	var arrPresetFields = new Object();
	for(var i=0;i<keys.length;i++){
		arrPresetFields[keys[i]] = new RunParam(keys[i], widget, values[i]);
	}
	var xmlRequest = createRequest(id, widget, action, arrFields, arrPresetFields);
	if (xmlRequest != null) {
		/* send request */
		if (xmlRequest != null) {
			var uri = null;
			if (typeof(g_ReqURI) != "undefined") {
				uri = g_ReqURI;
			} else {
				///showDebug("error: unknown where to request.");
			}
			sendXmlRequest(uri, xmlRequest);
		} else {
			///showDebug('failed to createRequest.');
		}
	}
	return xmlRequest;
}

/* 初始化widget */
function initWidget(id, widgetName,arrPresetFields) {
	var xmlRequest = prepareInitWidget(id, widgetName,arrPresetFields);
	/* send request */
	if (xmlRequest != null) {
		var uri = g_ReqURI;
		try {
			sendXmlRequest(uri, xmlRequest);
		} catch (e) {
			///showDebug("exception in sendXMLRequest:\n" + e);
		}
	} else {
		///showDebug('failed to createRequest.');
	}
}

function safeSendRequest(xmlRequest) {
	if (xmlRequest != null) {
		var uri = g_ReqURI;
		try {
			sendXmlRequest(uri, xmlRequest);
		} catch (e) {
			///showDebug("exception in sendXMLRequest:\n" + e);
		}
	} else {
		///showDebug('failed to createRequest.');
	}
}

function safeInitRequest(xmlRequest) {
	if (xmlRequest != null) {
		var uri = g_ReqURI;
		try {
			initXmlRequest(uri, xmlRequest);
		} catch (e) {
			///showDebug("exception in sendXMLRequest:\n" + e);
		}
	} else {
		///showDebug('failed to createRequest.');
	}
}

/**
 * 创建通讯对象
 */
function createAjaxRequest() {
	var xmlhttp = null;
	if(window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch(e) {
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHttp");
			} catch(e2) {
			}
		}
	}
	if (xmlhttp == null) {
		alert("您的浏览器不支持XMLHttpRequest,请使用 Mozilla Firefox 或 IE6.0 以上版本");
	}
	return xmlhttp;
}

/**
 * 将有参数的函数封装为无参数的函数
 */
function processFunction(strFunc){
	if(typeof(strFunc) == 'function'){
		var args=new Array();
    	for(var i=1;i<arguments.length;i++)args.push(arguments[i]);
		return strFunc.apply(window, args);
	}
}

/**
 * 向uri发送xmlRequest
 * @param uri 服务器cgi地址
 * @param requestdata 提交的数据
 * @param localFunc 注册的函数
 * @param contentType optoinal ex:
 *        'text/plain;charset=UTF-8' defult
 *        'text/xml;charset=utf-8'
 *        'application/x-www-form-urlencoded;charset=gb2312'
 */
function ajaxRequest(uri, requestdata, localFunc, contentType) {
	if (uri == null || requestdata == null || typeof(localFunc)!='function') {
		alert('Please input uri and Request XML.');
		return;
	}
	var xmlhttp = createAjaxRequest();
    if(contentType==null)contentType="text/plain;charset=utf-8";//设类型默认值
    contentType=contentType.toLowerCase();//转小写
	if (xmlhttp) {
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4) {
				if (xmlhttp.status != 200) {
					alert("Error to get XML data:\n" + xmlhttp.statusText);
				} else {
				    var docResps = null;
				    if(contentType.indexOf("text/xml")>=0){//xml返回
                        docResps = xmlhttp.responseXML;
					}else{//其他一律文本返回
                        docResps = xmlhttp.responseText;
					}
					if (docResps!=null) {
						processFunction(localFunc, docResps);
					}
				}
			} else if (xmlhttp.readyState == 3) {
			}
		};
		xmlhttp.open("POST", uri, true);
		xmlhttp.setRequestHeader("Content-Type", contentType);//使得xmlhttp.responseText存在值
		//xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//返回响应数据需要单独处理
		xmlhttp.send(requestdata);
	}
}

/**
 * 重载 window.setInterval,以便传递参数，使得可以带参数执行此方法
 */
var __setInterval= window.setInterval;
window.setInterval = function(strFunc, nDelay) {
	if(typeof(strFunc) == 'function'){
		var argu = Array.prototype.slice.call(arguments, 2);
		if(argu!=null&&(typeof(argu)=='object'||typeof(argu)=='array')&&argu.length>0){
			var f = (function(){ strFunc.apply(null, argu); });
			return __setInterval(f, nDelay);
		}
	} 
	return __setInterval(strFunc, nDelay);
}

/* 收集请求信息 */
function createRequest_old(id, widgetName, action, arrFields, arrPresetFields) {
	try {
		try {
			for (var i = 0; i < g_arrEditor.length; ++i) {
				setEditorContent(g_arrEditor[i]);
			}
		} catch (e) {
			///showDebug('exception in setEditorContent:\n' + e);
		}

		var widget = getObjById(id);
		if (widget == null) {
			///showDebug("not find obj to createRequest:" + id);
			return null;
		}

		var docRequest = new XDocument();
		var root = docRequest.createElement("request");
		root.setAttribute("referer", document.URL);
		/*
		root.setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
		root.setAttribute("xsi:noNamespaceSchemaLocation", "common/xsd/request.xsd");
		*/
		docRequest.appendChild(root);

		var widget_name = null;
		if ((typeof(widgetName) != "string") || (widgetName.length <= 0)) {
			var widget_class = widget.className;
			if ((typeof(widget_class) == "string") && (widget_class.length > 0)) {
				var arrClassParts = widget_class.split(" ");
				if (arrClassParts.length > 0) {
					widget_name = arrClassParts[0];
				} else {
					///showDebug("widget class error:" + widget_class);
				}
			}
		} else {
			widget_name = widgetName;
		}
		if ((typeof(widget_name) != "string") || (widget_name.length <= 0)) {
			///showDebug("widget name invalid.");
			return null;
		}

		var eleAction = docRequest.createElement("action");
		eleAction.setAttribute("name", action);
		eleAction.setAttribute("widgetName", widget_name);
		eleAction.setAttribute("sourceWidget", widget.id);
		root.appendChild(eleAction);

		if ((typeof(arrFields) == "object") && (arrFields != null)
			&& (arrFields.length > 0)) {/* 取参数 */
			var context = new ExprContext(widget);
			var xpath_Data = xpathParse("./descendant::*[@name and string-length(@name)>0]");
			var nodeset = xpath_Data.evaluate(context).nodeSetValue();
			if (nodeset.length > 0) {
				var dataReq = new Object();
				for (var i = 0; i < nodeset.length; ++i) {
					var fieldObj = nodeset[i];
					var field_name = fieldObj.name;
					if (typeof(field_name) == "undefined") {
						continue;
					}
					var nArrayPos = field_name.indexOf("[]");
					if (nArrayPos >= 0) {
						field_name = field_name.substring(0, nArrayPos);
					}
					var infoField = inArray(arrFields, field_name);
					if (typeof(infoField) != 'object') {/* 非本操作所需参数 */
						continue;
					}
					var value = getValueOfObj(fieldObj);
					if (value == null) {
						continue;
					}

					var funcCheck = infoField.ns + "_validate_" + infoField.name;
					var bFuncDefined = eval("typeof(" + funcCheck + ") == 'function';");
					if (!bFuncDefined) {
						///showDebug('Warning: field not defined in model, validate skipped:' + funcCheck);
					} else {
						var bIsValid = eval(funcCheck + "(value);");
						if (!bIsValid) {
							fieldObj.focus();
							return null;
						}
					}


					if (nArrayPos < 0) {
						dataReq[field_name] = value;
					} else {
						if (typeof(dataReq[field_name]) == "undefined") {
							dataReq[field_name] = new Array();
						}
						dataReq[field_name].push(value);
					}
				}

				for (field_name in dataReq) {
					if (!dataReq.hasOwnProperty(field_name)) {
						continue;
					}
					var value = dataReq[field_name];
					var eleParam = docRequest.createElement("param");
					eleParam.setAttribute("name", field_name);
					if (typeof(value) == "object" || typeof(value) == "array") {
						eleParam.setAttribute("format", "json");
						value = JSON.stringify(value);
						/*value = Object.toJSON(value);*/
					}
					eleParam.appendChild(docRequest.createTextNode(value));
					eleAction.appendChild(eleParam);
				}
			}
		}
		if ((typeof(arrPresetFields) == "object") && (arrPresetFields != null)) {
			for (var key in arrPresetFields) {
				if (!arrPresetFields.hasOwnProperty(key)) {
					continue;
				}
				var infoField = arrPresetFields[key];
				if (typeof(infoField) != 'object') {
					continue;
				}
				var value = infoField.value;
				var funcCheck = infoField.ns + '_validate_' + infoField.name;
				var bFuncDefined = eval("typeof(" + funcCheck + ") == 'function';");
				if (!bFuncDefined) {
					///showDebug('Warning: field not defined in model, validate skipped:' + funcCheck);
				} else {
					var bIsValid = eval(funcCheck + "(value);");
					if (!bIsValid) {
						///showDebug('value of preset param not validate, probably devlop error:' + funcCheck);
						return null;
					}
				}
				var eleParam = docRequest.createElement("param");
				eleParam.setAttribute("name", key);
				eleParam.appendChild(docRequest.createTextNode(value));
				eleAction.appendChild(eleParam);
			}
		}

		return docRequest;
	} catch (e) {
		///showDebug("exception:" + e);
		return null;
	}
}

function testMatch(match, value, hint, regopt) {
	if (typeof(match) != "string") {
		///showDebug("no match rule");
		return false;
	}
	if (typeof(value) != "string") {
		if (typeof(value) == "undefined" || value != null) {
			///showDebug("wrong value type:" + typeof(value));
			if (typeof(hint) == "string" && hint.length > 0) {
				alert(hint)
			}
			return false;
		}
	}

	try {
		var pattern;
		if (typeof(regopt) != "string" || regopt.length <= 0) {
			pattern = new RegExp(match);
		} else {
			pattern = new RegExp(match, regopt);
		}	
		///showDebug("regexp:" + pattern.toString());
		var bMatch = (value != null) ? pattern.test(value) : pattern.test("");
		if (bMatch) {
			return true;
		} else {
			if (typeof(hint) == "string" && hint.length > 0) {
				alert(hint);
			} else {
				///showDebug(value + " not match:(" + match + ")");
			}
		}
	} catch (e) {
		///showDebug("exception:" + e);
	}
	return false;
}

function checkMatch(id, match, name, hint) {
	var widget = getObjById(id);
	if (widget == null) {
		return false;
	}

	if (typeof(match) != "string") {
		///showDebug("no match rule");
		return false;
	}
	if (typeof(name) != "undefined") {
		///showDebug("no name to test");
		return false;
	}
	var value = getValueByName(widget, name);
	if (typeof(value) != "string") {
		///showDebug("wrong value type");
		return false;
	}

	return testMatch(match, value, hint);
}

/* 调用被注册的函数 */
function callRegFunc(id, arrParams) {
	var regFuncId = null;
	var action = arrParams['action'];
	if (typeof(action) == 'string'
		&& typeof(g_arrConnFuncs[id]) == 'object') {
		regFuncId = g_arrConnFuncs[id][action];
	}
	if (typeof(regFuncId) != 'string' || regFuncId.length <= 0) {
		///showDebug('warning: action(' + action + ') not connected to other action, operation skipped.');
		return;
	}

	var sStatement = g_arrRegFuncs[regFuncId];
	if (typeof(sStatement) != 'string') {
		///showDebug('err: not find registered func:' + regFuncId);
		return;
	}
	try {
		///showDebug("connected action:\n" + action + "->" + regFuncId + "->" + sStatement);
		return eval(sStatement);
	} catch (e) {
		///showDebug(e);
	}
}

/* 取全局参数 */
function getGlobalParam(id, action, paramName) {
	try {
		return g_arrParams[id][action][paramName];
	} catch (e) {
		///showDebug("exception when getParam:" + e);
		return null;
	}
}

/* 取目标id,首先从全局参数表中取tid,如果没找到,使用id */
function getTargetId(id, arrParams) {
	var tid = null;
	var action = arrParams['action'];
	if (typeof(action) == 'string') {
		tid = getGlobalParam(id, action, 'tid');
		if (tid == null) {
			tid = id;
		}
	}
	if (typeof(tid) != 'string' || tid.length <= 0) {
		return null;
	} else {
		return tid;
	}
}

/* 隐藏部件 */
function hideWidget(id, arrParams) {
	var tid = getTargetId(id, arrParams);
	if (tid == null) {
		///showDebug('err: not find tid.');
		return;
	}
	var widget = getObjById(tid);
	if (typeof(widget) == 'object' && widget != null) {
		widget.style.display = "none";
	} else {
		///showDebug('not find obj:' + tid);
	}
}

/* 显示部件 */
function showWidget(id, arrParams) {
	var tid = getTargetId(id, arrParams);
	if (tid == null) {
		///showDebug('err: not find tid.');
		return;
	}
	var widget = getObjById(tid);
	if (typeof(widget) == 'object' && widget != null) {
		widget.style.display = "block";
	} else {
		///showDebug('not find obj:' + tid);
	}
}

/* 创建通讯对象 */
function createXMLHttpRequest() {
	var xmlhttp = null;
	if(window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch(e) {
			try {
				xmlhttp = new ActiveXObject("Msxml2.XMLHttp");
			} catch(e2) {
			}
		}
	}
	
	if (xmlhttp == null) {
		alert("您的浏览器不支持XMLHttpRequest,请使用 Mozilla Firefox 或 IE6.0 以上版本");
	}
	return xmlhttp;
}

/* 同步从uri取xml */
function getXML(uri) {
	if (uri == null) {
		alert('Please input uri to get.');
		return null;
	}
	var xmlhttp = createXMLHttpRequest();
	if (xmlhttp) {
		xmlhttp.open("POST", uri, false);
		xmlhttp.send(null);
		while (true) {
			if (xmlhttp.readyState == 4) {
				if (xmlhttp.status == 200) {
					var xml = xmlhttp.responseXML;
					if (xml != null) {
						return xml;
					}
				}
				return null;
			}
		}
	}
}

/* 在页面中id为debug的元素内显示调试信息 */
function getResponseText(xmlhttp) {
	var html = null;
	var xml = xmlhttp.responseXML;
	if (xml == null) {
		html = "not xml:\n" + xmlhttp.responseText;
	} else {
		html = xmlText(xml);
		if (html.length < 3) {
			html = "error xml, responseText:\n" + xmlhttp.responseText;
		} else {
			html = "xml:\n" + html + "\ntext\n" + xmlhttp.responseText;
		}
	}
	return html;
}

/* 向uri发送xmlRequest */
function sendXmlRequest(uri, xmlRequest) {
	if (uri == null || xmlRequest == null) {
		alert('Please input uri and Request XML.');
		return;
	}
	///showDebug(xmlText(xmlRequest), 'debugRequest');
	var xmlhttp = createXMLHttpRequest();

	if (xmlhttp) {
		var statusbar = new StatusBar("加载中...");
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4) {
				statusbar.close();
				if (xmlhttp.status != 200) {
					alert("Error to get XML data:\n" + xmlhttp.statusText);
					///showDebug("req uri:" + uri);
				} else {
					///showDebug(getResponseText(xmlhttp), "debugResponse");
					var docResps = xmlhttp.responseXML;
					if (docResps != null) {
						processResponses(docResps);
					}
				}
			} else if (xmlhttp.readyState == 3) {
				/* ///showDebug(xmlhttp.getAllResponseHeaders(), "debugResponse"); */
			}
		};
		xmlhttp.open("POST", uri, true);
		xmlhttp.setRequestHeader("Content-Type", "text/xml");
		var data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
			+ "<!DOCTYPE request SYSTEM \"dtd/request.dtd\">\n"
			+ xmlText(xmlRequest);
		xmlhttp.send(data);
	}
}
/* 初始化网页向uri发送xmlRequest */
function initXmlRequest(uri, xmlRequest) {
	if (uri == null || xmlRequest == null) {
		alert('Please input uri and Request XML.');
		return;
	}
	///showDebug(xmlText(xmlRequest), 'debugRequest');
	var xmlhttp = createXMLHttpRequest();

	if (xmlhttp) {
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4) {
				if (xmlhttp.status != 200) {
					alert("Error to get XML data:\n" + xmlhttp.statusText);
					///showDebug("req uri:" + uri);
				} else {
					///showDebug(getResponseText(xmlhttp), "debugResponse");
					var docResps = xmlhttp.responseXML;
					if (docResps != null) {
						processResponses(docResps);
					}
				}
			} else if (xmlhttp.readyState == 3) {
				/* ///showDebug(xmlhttp.getAllResponseHeaders(), "debugResponse"); */
			}
		};
		xmlhttp.open("POST", uri, true);
		xmlhttp.setRequestHeader("Content-Type", "text/xml");
		var data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
			+ "<!DOCTYPE request SYSTEM \"dtd/request.dtd\">\n"
			+ xmlText(xmlRequest);
		xmlhttp.send(data);
	}
}

/* 将widget转换成页面代码 */
function getResult(widget) {
	if (widget == null) {
		return null;
	}

	var xsl_uri = widget.getAttribute("xsl");
	if ((typeof(xsl_uri) != "string") || (xsl_uri.length <= 0)) {
		///showDebug("widget no xsl attribute.");
	}

	var xsl = getXML(xsl_uri);
	var result = null;
	if (xsl != null) {
		result = xsltProcess(widget, xsl); 
	} else {
		result = xmlText(widget);
	}

	var eleTemp = document.createElement("div");
	eleTemp.innerHTML = result;
	return eleTemp;
}

function getElementInnerText(ele) {
	var sTotal = "";
	var list = ele.childNodes;
	for (i = 0; i < list.length; ++i) {
		var child;
		if (typeof(list.item) != "undefined") {
			child = list.item(i);
		} else {
			child = list[i];
		}
		if (typeof(child.nodeValue) != "undefined") {
			sTotal += child.nodeValue;
		} else if (typeof(child.data) != "undefined") {
			sTotal += child.data;
		}
	}
	return sTotal;
}

HtmlBuff = function() {
	this.eleTemp = document.createElement("div");
}

HtmlBuff.prototype.getOuterElement = function() {
	return this.eleTemp;
}

HtmlBuff.prototype.getHtml = function() {
	return this.eleTemp.innerHTML;
}

HtmlBuff.prototype.addChild = function(widget) {
	loadResource(widget);
	var src = getElementInnerText(widget);
	if (window.ActiveXObject) {
	/* code to fix IE bug */
	/* (innerHTML in ie will ignore script in the src, so we run the script manually) */
		runScript(src);
	}
	this.eleTemp.innerHTML += src;
}

/* 将widget转换成页面代码(第二版, 内部直接包含 html 代码) */
function getResult_v2(widget) {
	loadResource(widget);
	var eleTemp = document.createElement("div");
	var src = getElementInnerText(widget);
	if (window.ActiveXObject) {
	/* code to fix IE bug */
	/* (innerHTML in ie will ignore script in the src, so we run the script manually) */
		runScript(src);
	}
	eleTemp.innerHTML = src;
	return eleTemp;
}

function processResponses(docResps) {
	// process /responses/response/*
	var root = docResps.documentElement;
	if (typeof(root) != "object" || root == null) {
		///showDebug("wrong responses:" + xmlText(docResps));
		return;
	}
	var cntResp = 0;
	if (typeof(root.nodeName) == "string" && root.nodeName == "responses") {
		for (var i = 0; i < root.childNodes.length; ++i) {
			var response = root.childNodes[i];
			if (typeof(response) != "object" || response == null || response.nodeName != "response") {
				continue;
			}
			++cntResp;
			for (var j = 0; j < response.childNodes.length; ++j) {
				var eleResp = response.childNodes[j];
				processResponse(eleResp);
			}
		}
	}
	if (cntResp <= 0) {
		///showDebug('no action.');
		return;
	}
}

function processResponse(eleCmd) {
	var cmd = eleCmd.nodeName;
	if (cmd == null) {
		return;
	}

	if (cmd == "replaceWidget") {
		cmdReplaceWidget(eleCmd);
	} else if (cmd == "messageBox") {
		cmdMessageBox(eleCmd, null);
	} else if (cmd == "insertBefore") {
		cmdInsertBefore(eleCmd);
	} else if (cmd == "deleteWidget") {
		cmdDeleteWidget(eleCmd);
	} else if (cmd == "redirect") {
		cmdRedirect(eleCmd);
	} else if (cmd == "messageTo") {
		cmdMessageTo(eleCmd);
	} else if (cmd == "debugInfo") {
		cmdDebugInfo(eleCmd);
	} else if (cmd == "setChildWidget" || cmd == "setChild") {
		cmdSetChildWidget(eleCmd);
	} else if (cmd == "setEvent") {
		cmdSetEvent(eleCmd);
	} else if (cmd == "refresh") {
		cmdRefresh(eleCmd);
	} else if (cmd == "back") {
		cmdBack(eleCmd);
	} else if (cmd == "evalJs") {
		cmdEvalJs(eleCmd);
	} else if (cmd == "popWidget") {
		cmdPopWidget(eleCmd);
	} else if (cmd == "closePopWidget") {
		cmdClosePopWidget(eleCmd);
	} else if (cmd == "showInfo") {
		cmdShowInfo(eleCmd);
	}
}

function cmdDebugInfo(eleCmd) {
	var lc_errno = null, lc_errmsg = null, lc_errfile = null, lc_errline = null;
	for (var i = 0; i < eleCmd.childNodes.length; ++i) {
		var child = eleCmd.childNodes.item(i);
		var toRun = "lc_" + child.tagName + " = getElementInnerText(child);";
		eval(toRun);
	}
	var sDebugInfo = lc_errno + ": " + lc_errfile + ":" + lc_errline + "\n" + lc_errmsg;
	///showDebug(sDebugInfo, 'debugInfo');
}

function cmdShowInfo(eleCmd) {
	var objId = eleCmd.getAttribute("destWidget");
	if (objId == null) {
		///showDebug("miss target to showInfo");
		return;
	}
	var obj = getObjById(objId);
	if (obj == null) {
		///showDebug("not find obj to showInfo:" + objId);
		return;
	}
	var txt = getElementInnerText(eleCmd);
	obj.innerHTML = txt;
}

function cmdRedirect(eleCmd) {
	var url = eleCmd.getAttribute("url");
	if ((typeof(url) != "string") || url.length <= 0) {
		///showDebug("action error:\n" + xmlText(eleCmd));
		return;
	}
	if(url.indexOf('login.html')>0){
		if(url.indexOf('?')>0){
			if(url.indexOf('referfrom=')<0)	url=url+'&referfrom='+escape(document.URL);
		}
		else {
			url=url+'?referfrom='+escape(document.URL);
		}
	}
	window.location = url;
}

function cmdMessageBox(eleCmd, sPlusContent) {
	var listTitle = eleCmd.getElementsByTagName("title");
	var bPop = false;
	var sTitle = "";
	for (var i = 0; i < listTitle.length; ++i) {
		bPop = true;
		sTitle += getElementInnerText(listTitle[i]);
	}

	var listContent = eleCmd.getElementsByTagName("content");
	var sContent = "";
	for (var i = 0; i < listContent.length; ++i) {
		bPop = true;
		sContent += getElementInnerText(listContent[i]);
	}
	if (typeof(sPlusContent) == "string" && sContent.length > 0) {
		sContent += "<br/>" + sPlusContent;
	}
	if (!bPop) {
		return;
	}

	var boxType = eleCmd.getAttribute("type");
	if (boxType == null) {
		boxType = "info";
	}
	var sOnOk = null, sOnCancel = null;
	for (var i = 0; i < eleCmd.childNodes.length; ++i) {
		var child = eleCmd.childNodes.item(i);
		if (child.nodeName == "onok") {
			sOnOk = getElementInnerText(child);
		} else if (child.nodeName == "oncancel") {
			sOnCancel = getElementInnerText(child);
		}
	}
	var onok = null, oncancel = null;
	if (sOnOk != null) {
		onok = function() { eval(sOnOk); };
	}
	if (sOnCancel != null) {
		oncancel = function() { eval(sOnCancel); };
	}
	/*KMessageBox.ShowInfo("", sTitle, sContent);*/
	new MessageBox(sTitle, sContent, boxType, onok, oncancel);
}

function cmdMessageTo(eleCmd) {
	var sContent = null;
	var nTimeout = parseInt(eleCmd.getAttribute("timeout"));
	var sUrl = eleCmd.getAttribute("url");
	if (!isNaN(nTimeout) && (typeof(sUrl) == "string")) {
		sContent = nTimeout / 1000 + "秒后跳转";
	}
	cmdMessageBox(eleCmd, sContent);
	if (!isNaN(nTimeout)) {
		window.setTimeout(cmdRedirect, nTimeout, eleCmd);
	} else {
		cmdRedirect(eleCmd);
	}
}

function __refresh() {
	location.reload();
}

function cmdRefresh(eleCmd) {
	var sContent = null;
	var nTimeout = parseInt(eleCmd.getAttribute("timeout"));
	var sUrl = eleCmd.getAttribute("url");
	if (!isNaN(nTimeout) && (typeof(sUrl) == "string")) {
		sContent = nTimeout / 1000 + "秒后刷新页面";
	}
	cmdMessageBox(eleCmd, sContent);
	if (!isNaN(nTimeout)) {
		window.setTimeout(__refresh, nTimeout);
	} else {
		__refresh();
	}
}

function cmdBack(eleCmd) {
	var sContent = null;
	var defaulturl = eleCmd.getAttribute("defaulturl");
	var backurl = document.referrer+''; 
	if(backurl=='' || backurl=='null' || backurl=='undefined')
	{ 
		backurl = defaulturl+'';
	}
	cmdMessageBox(eleCmd, null);
	if(backurl=='' || backurl=='null' || backurl=='undefined') 
	{
		new MessageBox('提示信息', '无法返回到上一页，请手动回到上一页！', 'info', null, null);
		return;
	}
	if(backurl.indexOf('?nc=1')>0 || backurl.indexOf('&nc=1')>0)
	{
		backurl = backurl;
	} 
	else if(backurl.indexOf('?')>0)
	{ 
		backurl  = backurl + '&nc=1';
	}
	else
	{
		backurl  = backurl + '?nc=1';	
	}
	window.location = backurl;
}

function cmdDeleteWidget(eleCmd) {
	//alert("cmd not implement yet:" + xmlText(eleCmd));
	var refid = eleCmd.getAttribute("destWidget");
	if ((typeof(refid) == "string") && (refid.length > 0)) {
		var obj = getObjById(refid);
		if (obj != null) {
			var objParent = obj.parentNode;
			if (objParent != null) {
				objParent.removeChild(obj);
				return;
			}
		}
	}
	/////showDebug("some err to deleteWidget:" + xmlText(eleCmd));
}

function searchObj(objContext, IdOrType) {
	var obj = document.getElementById(IdOrType);
	if (obj == null) {
		var context = new ExprContext(objContext);
		var list = searchNodes(context, "./descendant::*[starts-with(@id, '" + IdOrType + "')]");
		if ((list != null) && (list.length > 0)) {
			obj = list[0];
		}
	}
	if (obj == null) {
		///showDebug("failed to searchObj:" + IdOrType);
	}
	return obj;
}

function cmdInsertBefore(eleCmd) {
	var parent = eleCmd.getAttribute("parent");
	var destWidget = eleCmd.getAttribute("destWidget");
	if (((parent == null) || (parent.length <= 0))
		&& ((destWidget == null) || (destWidget.length <= 0))) {
		///showDebug("response err: no parent and destWidget.");
		return;
	}

	var objParent = null;
	var objRef = null;
	if (parent == "$parent") {
		if (destWidget == null || destWidget.charAt(0) == '$') {
			///showDebug("response err: parent and destWidget should not both be var.");
			return;
		}
		objRef = searchObj(document, destWidget);
		if (objRef != null) {
			objParent = objRef.parentNode;
		}
		if (objRef == null || objParent == null) {
			///showDebug("response err: operate pos error.");
			return;
		}
	} else {
		objParent = searchObj(document, parent);
		if (objParent == null) {
			///showDebug("not find parent:" + parent);
			return;
		}
		if (destWidget == "$first") {
			objRef = objParent.firstChild;
		} else if (destWidget == "$last") {
			objRef = null;
		} else {
			objRef = searchObj(document, destWidget);
			if (objRef == null) {
				///showDebug("not find refobj, add at tail:" + destWidget);
			}
		}
	}

	var _buff = new HtmlBuff();
	for (var i = 0; i < eleCmd.childNodes.length; ++i) {
		var widget = eleCmd.childNodes.item(i);
		if (widget.nodeName == "widget" || widget.nodeName == "html") {
			_buff.addChild(widget);
		}
	}
	var list = _buff.getOuterElement().childNodes;
	for (var j = 0; j < list.length; ++j) {
		objParent.insertBefore(list.item(j), objRef);
	}
}

function replaceWidget(objNew, objOld) {
	if (objOld != null) {
		var objParent = objOld.parentNode;
		if (objParent != null) {
			/*objParent.replaceChild(objNew, objOld);*/
			var list = objNew.childNodes;
			for (var i = 0; i < list.length; ++i) {
				objParent.insertBefore(list.item(i), objOld);
			}
			objParent.removeChild(objOld);
		}
	}
}

function searchNodes(context, sXPath) {
	try {
		var xpath_wtype = new xpathParse(sXPath);
		var list = xpath_wtype.evaluate(context).nodeSetValue();
		return list;
	} catch (e) {
		///showDebug(e);
		return null;
	}
}

function cmdReplaceWidget(eleCmd) {
	var refid = eleCmd.getAttribute("destWidget");
	var reftype = eleCmd.getAttribute("destType");
	if (refid == null && reftype == null) {
		alert("response err: no destWidget and destType");
		return;
	}

	var _buff = new HtmlBuff();
	for (var i = 0; i < eleCmd.childNodes.length; ++i) {
		var child = eleCmd.childNodes.item(i);
		if (child.nodeName == "widget" || child.nodeName == "html") {
			_buff.addChild(child);
		}
	}
	var objNew = _buff.getOuterElement();

	if (refid != null && refid.length > 0) {
		var objRef = document.getElementById(refid);
		replaceWidget(objNew, objRef);
	}
	if (reftype != null && reftype.length > 0) {
		var context = new ExprContext(document);
		var list = searchNodes(context, "//*[@name='" + reftype + "']");
		if (list != null) {
			for (var i = 0; i < list.length; ++i) {
				var objRef = list[i];
				replaceWidget(objNew, objRef);
			}
		}
	}
}

function cmdSetChildWidget(eleCmd) {
	var parentId = eleCmd.getAttribute("parent");
	if (parentId == null) {
		///showDebug("err: not find parent id to setChildWidget");
		return;
	}
	var parent = document.getElementById(parentId);
	if (parent == null) {
		///showDebug("err: not find parent to setChildWidget:" + parentId);
		return;
	}

	var _buff = new HtmlBuff();
	for (var i = 0; i < eleCmd.childNodes.length; ++i) {
		var child = eleCmd.childNodes.item(i);
		if (typeof(child.nodeName) == "string"
			&& (child.nodeName == "widget" || child.nodeName == "html")) {
			_buff.addChild(child);
		}
	}

	if ((parent != null) && (parent.innerHTML != undefined)) {
		var _html = _buff.getHtml();
		if (typeof(_html) == "string" && _html.length > 0) {
			parent.innerHTML = _html;
		} else {
			parent.innerHTML = "";
			///showDebug("empty child, clear parent.");
		}
	}
}

function cmdSetEvent(eleCmd) {
	var targetId = eleCmd.getAttribute("target");
	if (targetId == null) {
		///showDebug("err: no targetId to setEvent.");
		return;
	}
	var target = document.getElementById(targetId);
	if (target == null) {
		///showDebug("err: not find target to setEvent:" + targetId);
		return;
	}

	for (var i = 0; i < eleCmd.childNodes.length; ++i) {
		try {
			var child = eleCmd.childNodes.item(i);
			if (child.nodeName == "event") {
				var eventName = child.getAttribute("name");
				if ((typeof(eventName) == 'string') && (eventName.length > 0)) {
					var script = "";
					for (var j = 0; j < child.childNodes.length; ++j) {
						script += getElementInnerText(child.childNodes.item(j));
					}
					var toRun = "target." + eventName + " = function() {\n"
						+ script
						+ ";\n}";
					///showDebug("setEvent:\n" + toRun);
					eval(toRun);
				}
			}
		} catch (e) {
			///showDebug("exception in setEvent:\n" + e);
		}
	}
}

function cmdEvalJs(eleCmd) {
	var script = getElementInnerText(eleCmd);
	if (script.length > 0) {
		try {
			eval(script);
		} catch (e) {
			///showDebug("exception in evalJs:\n" + e + "\n" + script);
		}
	}
}

function cmdPopWidget(eleCmd) {
	var parent = null;
	var parentId = eleCmd.getAttribute("parent");
	if (parentId != null) {
		parent = document.getElementById(parentId);
	}
	var _title = eleCmd.getAttribute("title");
	if (_title == null) {
		_title = "";
	}
	var _left = eleCmd.getAttribute("left");
	var _top = eleCmd.getAttribute("top");
	var _width = eleCmd.getAttribute("width");
	var _height = eleCmd.getAttribute("height");
	var _style = new Style(_left, _top, _width, _height);

	var _buff = new HtmlBuff();
	var _idPop = null;
	for (var i = 0; i < eleCmd.childNodes.length; ++i) {
		var child = eleCmd.childNodes.item(i);
		if (child.nodeName == "widget" || child.nodeName == "html") {
			if (_idPop == null) {
				_idPop = child.getAttribute("id");
				if (typeof(_idPop) != "string") {
					_idPop = "pop_";
				} else {
					_idPop = "pop_" + _idPop;
				}
			}
			_buff.addChild(child);
		}
	}

	/*
	if ((parent != null) && (parent.innerHTML != undefined)
		&& (objNew != null)) {
		parent.innerHTML = objNew.innerHTML;
	}
	*/
	var _content = '<div id="' + _idPop + '">' + _buff.getHtml() + '</div>';
	new PopWindow(_title, _content, "popwindow", null, null, _style);
}

function _closePopWidgetByWidgetId(widgetId) {
	var _idPop = "pop_" + widgetId;
	var objWidget = getObjById(_idPop);
	if (objWidget == null) {
		///showDebug("not find obj:" + widgetId);
		return false;
	}
	try {
		var pattern = new RegExp("^messagebox_\\d+$");
		var objPop = objWidget.parentNode.parentNode.parentNode;
		if (typeof(objPop.id) == "string" && objPop.id.length > 0
			&& pattern.test(objPop.id)) {
			var objParent = objPop.parentNode;
			objParent.removeChild(objPop);
			return true;
		} else {
			///showDebug("pop id error!" + objPop.id);
			return false;
		}
	} catch (e) {
		///showDebug("exception:" + e);
	}
}

function cmdClosePopWidget(eleCmd) {
	var widgetId = eleCmd.getAttribute("destWidget");
	if (widgetId == null) {
		///showDebug("response miss destWidget!");
		return false;
	}
	return _closePopWidgetByWidgetId(widgetId);
}

/* 加载部件资源 */
function loadResource(widget) {
	var widgetName = widget.getAttribute("name");
	if (typeof(widgetName) == "string" && widgetName.length > 0) {
		var widgetCss = "widgets/css/" + widgetName + ".css";
		loadCss(widgetCss);
	}
	var widgetJs = widget.getAttribute("js");
	if (typeof(widgetJs) == "string" && widgetJs.length > 0) {
		loadJs(widgetJs);
	}
}

/* 加载样式表 */
function loadCss(widgetCss) {
	if (typeof(g_ObjBuff) == "object" && g_ObjBuff != null) {
		return g_ObjBuff.loadResource(widgetCss, "css");
	}
	///showDebug("warning: not find ObjBuff, use slow mode!");
	try {
		var objRefPos = getObjById("page_css");
		if (objRefPos == null) {
			///showDebug("not find refpos");
			return null;
		}
		var context = new ExprContext(document);
		var xpath_Css = xpathParse("/HTML/HEAD/LINK[@href='" + widgetCss + "']");
		var list = xpath_Css.evaluate(context).nodeSetValue();
		var objWidgetCss = null;
		if (list.length > 0) {
			objWidgetCss = list[0];
			objRefPos.parentNode.removeChild(objWidgetCss);
			objRefPos.parentNode.insertBefore(objWidgetCss, objRefPos);
		} else {
			objWidgetCss = document.createElement("link");
			objWidgetCss.setAttribute("href", widgetCss);
			objWidgetCss.setAttribute("rel", "stylesheet");
			objWidgetCss.setAttribute("type", "text/css");
			objRefPos.parentNode.insertBefore(objWidgetCss, objRefPos);
		}
		return objWidgetCss;
	} catch (e) {
		///showDebug("failed to loadCss:" + widgetCss + "\n" + e);
		return null;
	}
}

/* 加载js */
function loadJs(widgetJs) {
	var modelstr = widgetJs.substring(widgetJs.lastIndexOf('/')+1,widgetJs.lastIndexOf('.js'));
	if(modelstr!='') var loadvarname='js_isLoad_'+modelstr.replace('.','_');
	eval('var hasloaded=typeof('+loadvarname+')!=\'undefined\';');
	if(hasloaded) {
		///showDebug(widgetJs+"has loaded");
		return null;
	}
	if (typeof(g_ObjBuff) == "object" && g_ObjBuff != null) {
		return g_ObjBuff.loadResource(widgetJs, "js");
	}
	///showDebug("warning: not find ObjBuff, use slow mode!");
	try {
		var objRefPos = getObjById("page_css");
		if (objRefPos == null) {
			///showDebug("not find refpos");
			return null;
		}
		var context = new ExprContext(document);
		var xpath_Css = xpathParse("/HTML/HEAD/SCRIPT[@src='" + widgetJs + "']");
		var list = xpath_Css.evaluate(context).nodeSetValue();
		var objWidgetJs = null;
		if (list.length > 0) {
			objWidgetJs = list[0];
			objRefPos.parentNode.removeChild(objWidgetJs);
			objRefPos.parentNode.insertBefore(objWidgetJs, objRefPos);
		} else {
			objWidgetJs = document.createElement("script");
			objWidgetJs.setAttribute("charset", "UTF-8");
			objWidgetJs.setAttribute("src", widgetJs);
			objWidgetJs.setAttribute("type", "text/javascript");
			objRefPos.parentNode.insertBefore(objWidgetJs, objRefPos);
		}
		return objWidgetJs;
	} catch (e) {
		///showDebug("failed to loadJs:" + widgetJs + "\n" + e);
		return null;
	}
}

function setTemplate(tplhref) {
	if (typeof(tplhref) != "string" || tplhref.length <= 0) {
		///showDebug("invalid skin.");
		return false;
	}
	try {
		var objTemplate = getObjById("template_css");
		if (objTemplate == null) {
			///showDebug("not find skin obj");
			return false;
		}
		objTemplate.setAttribute("href", tplhref);
		///showDebug("setTemplate done:" + tplhref);
	} catch (e) {
		///showDebug("failed to setTemplate:" + tplhref + "\n" + e);
		return false;
	}
	return true;
}

function initMenu(widgetId, jsonMenu) {
	var menudata = JSON.parse(jsonMenu);
	if (menudata != null) {
		///showDebug("received menu data:\n" + JSON.stringify(menudata));
	} else {
		///showDebug("received empty menudata");
	}

	if (true) {
		var menutype = "flatmenu";
		if (menutype == "flatmenu") {
			var widget = getObjById(widgetId);
			if (widget == null) {
				///showDebug("not find widget:" + widgetId);
				return false;
			}

			widget.innerHTML = "";
			var menuRoot = document.createElement("div");
			menuRoot.className = "flatmenu";
			widget.appendChild(menuRoot);
			var treeRoot = new MenuTree(null, null, 0);
			createFlatMenu(menudata, menuRoot, treeRoot, 1);
			treeRoot.collapseMenu();
		} else if (menutype == "jimmenu") {
			createJimMenu(menudata);
		} else if (menutype == "sdmenu") {
			var eleMenu = createSDMenu(menudata);
			var widget = getObjById(widgetId);
			if (widget == null) {
				///showDebug("not find widget:" + widgetId);
				return false;
			}
			widget.innerHTML = eleMenu.innerHTML;
			myMenu = new SDMenu(widgetId);
			myMenu.init();
			myMenu.speed = 20;
			myMenu.expandAll();
		}
		///showDebug("initMenu success.");
	}
	return true;
}

// <script ..><!-- script --></script>
// pos      p1p3         p4 p2
function getScript(src) {
	var script = "";
	if (typeof(src) == "string" && src.length > 0) {
		var srcLow = src.toLowerCase();
		var pos = 0;
		var posPrev = -1;
		while (true) {
			posPrev = pos;
			pos = srcLow.indexOf("<script", posPrev);
			if (pos < 0) {
				break;
			}
			var p1 = srcLow.indexOf(">", pos);
			if (p1 < 0) {
				break;
			}
			var p2 = srcLow.indexOf("</script>", p1);
			if (p2 < 0) {
				break;
			}
			var p3 = srcLow.indexOf("<!--", p1 + 1);
			if (p3 < 0 || p3 > p2) {
				p3 = p1 + 1;
			} else {
				p3 += "<!--".length;
			}
			var p4 = srcLow.indexOf("-->", p3);
			if (p4 < 0 || p4 > p2) {
				p4 = p2;
			}
			var scriptOne = src.substring(p3, p4);
			script += scriptOne;

			pos = p2 + "</script>".length;
		}
	}
	return script;
}

/* get script from src, then eval it */
function runScript(src) {
	var script = getScript(src);
	///showDebug("runScript:" + script);
	try {
		eval(script);
	} catch (e) {
		///showDebug("exception in runScript:\n" + e);
	}
}

/*
function extractScript(ele) {
	var script = "";
	list = ele.getElementsByTagName("script");
	if (list != null) {
		for (var i = 0; i < list.length; ++i) {
			var scriptOne = getElementInnerText(list[i]);
			if (typeof(scriptOne) != "string") {
				continue;
			}
			var p1 = scriptOne.indexOf("<!--");
			if (p1 < 0) {
				p1 = 0;
			} else {
				p1 += "<!--".length;
			}
			var p2 = scriptOne.indexOf("-->", p1);
			if (p2 < 0) {
				p2 = scriptOne.length;
			}
			script += scriptOne.substring(p1, p2) + "\n";
		}
	}
	return script;
}
*/

/* Get object from widget */
function getWidgetObject(widget,objName,objTag)
{
	var widgetObj = document.getElementById(widget);
	if(typeof(widgetObj) != 'object')
	{
		var findObjs = document.getElementsByName(objName);
		if(findObjs.length>0) return findObjs[0];
		else return null;
	}
	var childObjs = widgetObj.getElementsByTagName(objTag);
	for(var i=0; i<childObjs.length;i++)
	{
		var childObj = childObjs[i];
		var childName = childObj.getAttribute('name');
		if(childName == objName)
		{
			return childObj;
		}
	}
	return null;
}

/* Get object from widget */
function getWidgetObjectById(widget,objId,objTag)
{
	var widgetObj = document.getElementById(widget);
	if(typeof(widgetObj) != 'object')
	{
		return document.getElementById(objId);
	}
	if(objTag == null || objTag == '')
	{
		return document.getElementById(objId);
	}
	var childObjs = widgetObj.getElementsByTagName(objTag);
	for(var i=0; i<childObjs.length;i++)
	{
		var childObj = childObjs[i];
		var childId = childObj.getAttribute('id');
		if(childId == objId)
		{
			return childObj;
		}
	}
	return null;
}

