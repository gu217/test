	window.onerror =function error(msg,file,line)	
	{
		//生成错误提示div
		var dialog=document.createElement("div")
		dialog.className='errordialog'
		dialog.innerHTML='<div style="font-family:Courier New;border:1px solid #C3D9FF;padding:2px;background-color:#C3D9FF">'+
		'<div style="font-weight:bold;color:red;">JavaScript Error: </div><ul style="line-height:150%;"><li>Wrong:　' + 
		msg +' </li><li>Line:　' + 
		line +' </li><li>File:　'+
		file+
		'</li><ul></div>';
		document.body.appendChild(dialog);
		return true;
	}
