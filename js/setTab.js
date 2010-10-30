//Usage demo: http://zhiyao.gongye360.com/index.html

/*切换按钮*/
function setTab(name,cursel,n)
{
	for(i=1;i<=n;i++)
	{
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con_"+name+"_"+i);
		menu.className=i==cursel?"hover":"";
		con.style.display=i==cursel?"block":"none";
	}
}
/*幻灯片*/
var snum=1; //初始化显示的图片
var best_num=4; //图片的数量
var sec=3000; //切换图片的速度
var pic_id;
function FocusSlide(v){
var i;
for(i=1;i<=4;i++){
eval("document.getElementById(\"focus_tit_"+i+"\").className=\"\"");
eval("document.getElementById(\"focus_img_"+i+"\").style.display=\"none\"");
}
eval("document.getElementById(\"focus_tit_"+v+"\").className=\"cur\"");
eval("document.getElementById(\"focus_img_"+v+"\").style.display=\"\"");
SetNum(v+1);
clearTimeout(pic_id);
pic_id = setTimeout("FocusSlide(snum)",sec);
}
function SetNum(v){
snum=v;
if(snum>best_num) snum=1;
}
pic_id = setTimeout("FocusSlide(snum)",sec);




