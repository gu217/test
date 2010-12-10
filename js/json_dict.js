/**
 * 多级分类器脚本
 * @author 		Bai_Jianping<XChinux@163.com>
 * @copyright 
 * @name 		json_cate.php
 * @version 		1.0
 * @todo 		多级分类器脚本
 * @final 		2007-06-06
 */

/**
 * 全局数组
 */
if(json_dict instanceof Array == false){
var json_dict = new Array();
}

/**
 * 主要的根据配置生成多级选择的函数
 * 
 * @param string dicttype 字典类型
 * @param string selid    选择框Id
 */
function GenJsonDictSelect(dicttype, selid)
{
	var elem_select = document.getElementById(selid);
	if (elem_select == null || elem_select == undefined)
	{
		bNull = true;
		elem_select = document.createElement("select");
	}
	for (dataid in json_dict[dicttype])
	{
		if (isNaN(parseInt(dataid)))
		{
			continue;
		}
		elem_option = document.createElement("option");
		elem_option.setAttribute("value", json_dict[dicttype][dataid]["dataid"]);
		elem_option.appendChild(document.createTextNode(json_dict[dicttype][dataid]["dataname"]));
		elem_select.appendChild(elem_option);
	}
}

/**
 * 初始化多级选择
 * @param string dicttype 字典类型
 * @param string selid	选择框ID
 * @param string selvalue	初始化选择
 */
function JsonDictSelInit(dicttype, selid, selvalue)
{
	if (typeof(json_dict[dicttype]) == "undefined")
    {
		return;
    }
	GenJsonDictSelect(dicttype, selid);
	if (selvalue.length == 0)
	{
		return;
	}
	selelement = document.getElementById(selid);
	selelement.value = selvalue;
}

