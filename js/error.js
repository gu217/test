	window.onerror = function error(msg,file,line)	
	{
		//generate error message-div
		var dialog=document.createElement("div")
		dialog.className='errordialog'
		dialog.innerHTML='<div style="font-family:Courier New;font-size:12px;border:1px dashed #C3D9FF;padding:2px;background-color:#F9F9F9">'+
		'<div style="font-weight:bold;color:red;">JavaScript Error: </div><ul style="line-height:150%;"><li>Wrong:　' + 
		msg +' </li><li>Line:　' + 
		line +' </li><!--li>File:　'+
		file+
		'</li--><ul></div>';
		document.body.appendChild(dialog);
		return true;
	}
