function Redirect(url)
{
	document.location.href = url;
}

function ChgBg(ele, color)
{
	ele.style.background = color;
}

function SortCol(f, sort, order)
{
	f.sort.value = sort;
	f.order.value = order;
	if (CheckForm(f))
	{
		f.submit();
	}
}

function Expand(ele)
{
	sele = ele.getElementsByTagName("select");
	if (ele.style.display == "none")
	{
		ele.style.display = "";
		for (i=0; i<sele.length; i++)
		{
			sele[i].style.display = ""
		}
	}
	else
	{
		ele.style.display = "none";
		for (i=0; i<sele.length; i++)
		{
			sele[i].style.display = "none"
		}
	}
}

function MakeScroll(ele)
{
	posY = ele.offsetTop;

	wHeight = f_clientHeight();
	ele.style.height = wHeight - posY;
}

function f_clientWidth()
{
	return f_filterResults (
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
function f_clientHeight()
{
	return f_filterResults (
		window.innerHeight ? window.innerHeight : 0,
		document.documentElement ? document.documentElement.clientHeight : 0,
		document.body ? document.body.clientHeight : 0
	);
}
function f_scrollLeft()
{
	return f_filterResults (
		window.pageXOffset ? window.pageXOffset : 0,
		document.documentElement ? document.documentElement.scrollLeft : 0,
		document.body ? document.body.scrollLeft : 0
	);
}
function f_scrollTop()
{
	return f_filterResults (
		window.pageYOffset ? window.pageYOffset : 0,
		document.documentElement ? document.documentElement.scrollTop : 0,
		document.body ? document.body.scrollTop : 0
	);
}
function f_filterResults(n_win, n_docel, n_body)
{
	var n_result = n_win ? n_win : 0;
	if (n_docel && (!n_result || (n_result > n_docel)))
		n_result = n_docel;
	return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}

function gotoPage(url,value)
{
	if(value != '')
	{
		document.location.href = url+value;
	}
}

function autoIframe(frameId)
{
	try{
		frame = document.getElementById(frameId);
		innerDoc = (notIE = frame.contentDocument) ? frame.contentDocument : frame.contentWindow.document;
		objToResize = (notIE) ? frame : frame.style ;
		objToResize.height = (notIE)?innerDoc.body.offsetHeight:innerDoc.body.scrollHeight;
	}
	catch(err){
		window.status = err.message;
	}
}

function SetFrameHeight(iframeObj, compareObj)
{
	if (iframeObj.contentDocument)
	{
		if (iframeObj.contentDocument.body.offsetHeight)
		{
			iframeObj.height = iframeObj.contentDocument.getElementById('main')?iframeObj.contentDocument.getElementById('main').offsetHeight:iframeObj.contentDocument.body.offsetHeight;
		}
	}
	else if (document.frames[iframeObj.name].document && document.frames[iframeObj.name].document.body.scrollHeight)
	{
		iframeObj.height = document.frames[iframeObj.name].document.getElementById('main')?document.frames[iframeObj.name].document.getElementById('main').scrollHeight:document.frames[iframeObj.name].document.body.scrollHeight;
	}
	if (compareObj)
	{
		if (compareObj.height<iframeObj.height)
		{
			compareObj.height = iframeObj.height;
		}
		else
		{
			iframeObj.height = compareObj.height;
		}
	}
}

function SetFrameFullHeight(iframeObj)
{
	var old_height = (navigator.userAgent.indexOf("Firefox")!=-1)?document.body.scrollHeight:document.body.offsetHeight;
	var adj_height = GetTop(iframeObj);
	var new_height = old_height - adj_height;
	if (new_height)
	{
		iframeObj.height = new_height - ((document.all)?4:0);
	}
}

function GetLeft(ele)
{
	if (ele.offsetParent)
		return ele.offsetLeft + GetLeft(ele.offsetParent);
	else
		return ele.offsetLeft;
}

function GetTop(ele)
{
	var v_top = 0;
	while(ele.offsetParent)
	{
		v_top += ele.offsetTop;
		ele = ele.offsetParent
	}
	return v_top;
}

function checkall(form, checkbox, notmark, prefix, checkall)
{
	var checkall = checkall ? checkall : 'chkall';
	var prefix = prefix ? prefix : 'check';
	for(var i = 0; i < form.elements.length; i++)
	{
		var e = form.elements[i];
		if(e.name && e.name != checkall && (!prefix || (prefix && e.name.match(prefix))))
		{
			if (checkbox)
			{
				e.checked = checkbox.checked;
			}
			else
			{
				e.checked = form.elements[checkall].checked;
			}
			if (!notmark)
			{
				Marked(e);
			}
		}
	}
}

function Marked(ele)
{
	chked = ele.checked;
	if (chked)
	{
		AddClassName(ele.parentNode.parentNode, "marked", true);
	}
	else
	{
		RemoveClassName(ele.parentNode.parentNode, "marked");
	}
}

function AddClassName(objElement, strClass, blnMayAlreadyExist)
{
	if (objElement.className)
	{
		var arrList = objElement.className.split(' ');
		if (blnMayAlreadyExist)
		{
			var strClassUpper = strClass.toUpperCase();
			for (var i = 0; i < arrList.length; i++)
			{
				if (arrList[i].toUpperCase() == strClassUpper)
				{
					arrList.splice(i, 1);
					i--;
				}
			}
		}
		arrList[arrList.length] = strClass;
		objElement.className = arrList.join(' ');
	}
	else
	{
		objElement.className = strClass;
	}
}

function RemoveClassName(objElement, strClass)
{
	if (objElement.className)
	{
		var arrList = objElement.className.split(' ');
		var strClassUpper = strClass.toUpperCase();
		for (var i = 0; i < arrList.length; i++)
		{
			if (arrList[i].toUpperCase() == strClassUpper)
			{
				arrList.splice(i, 1);
				i--;
			}
		}
		objElement.className = arrList.join(' ');
	}
}

function AddGroupClassName(gname, strClass)
{
	objlist = document.getElementsByName(gname);
	for (i=0; i<objlist.length; i++)
	{
		AddClassName(objlist[i], strClass)
	}
}

function RemoveGroupClassName(gname, strClass)
{
	objlist = document.getElementsByName(gname);
	for (i=0; i<objlist.length; i++)
	{
		RemoveClassName(objlist[i], strClass)
	}
}

Number.prototype.toFixed=function (d)
{
	var s=this+"";
	if(!d)
	{
		d=0;
	}
	if(s.indexOf(".")==-1)
	{
		s+=".";
	}
	s+=new Array(d+1).join("0");
	if(new RegExp("^(-|\\+)?(\\d+(\\.\\d{0,"+(d+1)+"})?)\\d*$").test(s))
	{
		var s="0"+RegExp.$2,pm=RegExp.$1,a=RegExp.$3.length,b=true;
		if(a==d+2)
		{
			a=s.match(/\d/g);
			if(parseInt(a[a.length-1])>4)
			{
				for(var i=a.length-2;i>=0;i--)
				{
					a[i]=parseInt(a[i])+1;
					if(a[i]==10)
					{
						a[i]=0;
						b=i!=1;
					}
					else
					{
						break;
					}
				}
			}
			s=a.join("").replace(new RegExp("(\\d+)(\\d{"+d+"})\\d$"),"$1.$2");
		}
		if(b)
		{
			s=s.substr(1);
		}
		return (pm+s).replace(/\.$/,"");
	}
	return this+"";
};

function getEle(obj, obj_type, obj_att, condition)
{
	var all = obj.getElementsByTagName(obj_type);
	var res = [];
	condition = new RegExp(condition);
	for (var i=0; i<all.length; i++)
	{
		var obj = all[i];
		if (obj.nodeType==1 && obj[obj_att]!=undefined && obj[obj_att].match(condition))res.push(obj);
	}
	return res;
};


var pop;
function Pop(theURL, name) {
	if (name)
	{
		winname = name
	}
	else
	{
		winname = "pop"
	}

	var features = "scrollbars=yes,resizable=yes,width=1280,height=600";
	pop = window.open(theURL, winname, features);
	if ((document.window != null) && (!pop.opener))
	{
		pop.opener = document.window;
	}
	pop.focus();
	miWin = true;
}

function closeWin(){
	window.opener = top;
	window.close();
}

function w(str)
{
	document.write(str);
}

function ChkChg(obj, val)
{
	if (obj.value == val)
	{
		RemoveClassName(obj, "changed");
		return false;
	}
	else
	{
		AddClassName(obj, "changed");
		return true;
	}
}

String.prototype.getBytes = function()
{
	return encodeURIComponent(this).replace(/%../g, 'x').length;
}

function fetch_params(tg)
{
	tg = tg?tg:window.location.href;
	var params = new Array( );
	var regex = /[\?&]([^=]+)=([^=\?&]{0,})/g;
	while((results = regex.exec(tg)) != null)
	{
		if (results[1] != "")
		{
			params[results[1]]=results[2];
		}
	}
	
	return params;
}

function createAjaxObject() {
	try
	{
		request = new XMLHttpRequest();
	}
	catch (trymicrosoft)
	{
		try
		{
			request = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (othermicrosoft)
		{
			try
			{
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (failed)
			{
				request = false;
			}
		}
	}

	return request;
}
