components = new Array();
total = new Array();
subtotal = new Array();
imgList = new Array();

function CheckForm(fm){
	total_ele = fm.elements.length;
	for (var i = 0; i < total_ele; i++)
	{
		ele = fm.elements[i];
		if (!checkFormElement(ele))
		{
			return false;
		}
	}
	return true;
}

function CheckSetElements(obj, name_match)
{
	eles = getEle(obj, "input", "name", name_match);
	for (var i = 0; i < eles.length; i++)
	{
		ele = eles[i];
		if (!checkFormElement(ele))
		{
			return false;
		}
	}
	return true;
}

function checkFormElement(ele)
{
	elename = (ele.getAttribute('dname') == undefined)?ele.name:ele.getAttribute('dname');
	if(ele.getAttribute('selectAll') != undefined)
	{
		for (var j=0; j<ele.options.length; j++)
		{
			ele.options[j].selected = true;
		}
	}

	if(ele.getAttribute('notEmpty') != undefined)
	{
		if (isEmpty(ele.value))
		{
			alert(elename + " should not be empty");
			ele.focus();
			return false;
		}
	}

	if(ele.getAttribute('isNumber') != undefined)
	{
		if (!isNumber(ele.value))
		{
			alert(elename + " should be a number");
			ele.focus();
			return false;
		}
	}

	if(ele.getAttribute('isInteger') != undefined)
	{
		if (!isInteger(ele.value))
		{
			alert(elename + " should be an integer");
			ele.focus();
			return false;
		}
	}

	if(ele.getAttribute('isNatural') != undefined)
	{
		if (!isNatural(ele.value))
		{
			alert(elename + " should be natural");
			ele.focus();
			return false;
		}
	}

	if(ele.getAttribute('noSpecial') != undefined)
	{
		if (!noSpecial(ele.value))
		{
			alert(elename + " only accepts a-z, A-Z, 0-9, - or _");
			ele.focus();
			return false;
		}
	}

	if(ele.getAttribute('validEmail') != undefined)
	{
		if (!isValidEmail(trim(ele.value)))
		{
			alert(elename + " is an invalid e-mail address format");
			ele.focus();
			return false;
		}
	}

	if((match_ele = ele.getAttribute('match')) != null)
	{
		if (ele.form.elements[match_ele])
		{
			if (ele.form.elements[match_ele].value != ele.value)
			{
				alert(elename + " does not match " + ele.form.elements[match_ele].name);
				ele.focus();
				return false;
			}
		}
	}

	if((min = ele.getAttribute('min')) != null)
	{
		if (ele.value != "")
		{
			if (min*1==min || (min = ele.form.elements[min].value))
			{
				if (ele.value*1 < min*1)
				{
					alert(elename + " can't be smaller than " + min);
					ele.focus();
					return false;
				}
			}
		}
	}

	if((max = ele.getAttribute('max')) != null)
	{
		if (ele.value != "")
		{
			if (max*1==max || (max = ele.form.elements[max].value))
			{
				if (ele.value*1 > max*1)
				{
					alert(elename + " can't be greater than " + max);
					ele.focus();
					return false;
				}
			}
		}
	}

	if((minLen = ele.getAttribute('minLen')) != null)
	{
		if (ele.value != "")
		{
			if (ele.value.getBytes() < minLen)
			{
				alert(elename + " can't be shorter than " + minLen + " characters");
				ele.focus();
				return false;
			}
		}
	}

	if((maxLen = ele.getAttribute('maxLen')) != null)
	{
		if (ele.value != "")
		{
			if (ele.value.getBytes() > maxLen)
			{
				alert(elename + " can't be longer than " + maxLen + " characters");
				ele.focus();
				return false;
			}
		}
	}

	checkAccept(ele);

	checkImage(ele);

	if((cp_name = ele.getAttribute('unique')) != null)
	{
		if (ele.value != "")
		{
			if (components[cp_name] == undefined)
			{
				components[cp_name] = new Array();
			}
			if (components[cp_name][ele.value])
			{
				alert(elename + " is not unique");
				ele.focus();
				return false;
			}
			else
			{
				components[cp_name][ele.value] = 1;
			}
		}
	}

	if((calc_name = ele.getAttribute('total')) != null)
	{
		if (ele.value != "")
		{
			total[calc_name] = ele.value*1;
			if (subtotal[calc_name] != undefined && total[calc_name] < subtotal[calc_name])
			{
				alert(elename + " was exceeded");
				ele.focus();
				return false;
			}
		}
	}

	if((calc_name = ele.getAttribute('subtotal')) != null)
	{
		if (ele.value != "")
		{
			subtotal[calc_name] = (subtotal[calc_name] == undefined)?ele.value*1:subtotal[calc_name]+ele.value*1;
			if (total[calc_name] != undefined && total[calc_name] < subtotal[calc_name])
			{
				alert(elename + " exceeded the limitation : " + total[calc_name]);
				ele.focus();
				return false;
			}
		}
	}
	
	if((regex = ele.getAttribute('notMatchRegExI')) != null)
	{
		if (ele.value != "")
		{
			if (regExCheck(regex, ele.value, 'i'))
			{
				alert(elename + " " + ele.getAttribute('warningMsg'));
				ele.focus();
				return false;
			}
		}
	}

	if((regex = ele.getAttribute('matchRegExI')) != null)
	{
		if (ele.value != "")
		{
			if (!regExCheck(regex, ele.value, 'i'))
			{
				alert(elename + " " + ele.getAttribute('warningMsg'));
				ele.focus();
				return false;
			}
		}
		return true;
	}

	return true;
}

function checkAccept(ele)
{
	if (!isAccept(ele)){
			elename = (ele.getAttribute('dname') == undefined)?ele.name:ele.getAttribute('dname');
			ele.value = "";
			var ele2= ele.cloneNode(false);
			ele2.onChange= ele.onChange;
			ele.parentNode.replaceChild(ele2,ele);
			alert(elename + " is an invalid file format");
			return false;
	}
}

function isAccept(ele)
{
	if(ele.value != "" && (formats = ele.getAttribute('accept')) != "" && formats != null)
	{
		formats = formats.replace(/(\/|\,)/g, "|");
		regex = new RegExp("\.(" + formats + ")$", "i");
		if (ele.value.search(regex) == -1)
		{
			return false;
		}
	}
	return true;
}

function checkImage(ele)
{
	elename = (ele.getAttribute('dname') == undefined)?ele.name:ele.getAttribute('dname');
	if (!isSquare(ele)){
			ele.value = "";
			var ele2= ele.cloneNode(false);
			ele2.onChange= ele.onChange;
			ele.parentNode.replaceChild(ele2,ele);
			alert(elename + " is not square");
			return false;
	}

	if((maxWidth = ele.getAttribute('maxWidth')) != null)
	{
		if (ele.value != "")
		{
			var img_name = escape(ele.value);
			if (imgList[img_name].width > maxWidth)
			{
				ele.value = "";
				var ele2= ele.cloneNode(false);
				ele2.onChange= ele.onChange;
				ele.parentNode.replaceChild(ele2,ele);
				alert(elename + " can't be greater than width limit: " + maxWidth);
				return false;
			}
		}
	}

	if((maxHeight = ele.getAttribute('maxHeight')) != null)
	{
		if (ele.value != "")
		{
			var img_name = escape(ele.value);
			if (imgList[img_name].height > maxHeight)
			{
				ele.value = "";
				var ele2= ele.cloneNode(false);
				ele2.onChange= ele.onChange;
				ele.parentNode.replaceChild(ele2,ele);
				alert(elename + " can't be greater than height limit: " + maxHeight);
				return false;
			}
		}
	}

	if((minWidth = ele.getAttribute('minWidth')) != null)
	{
		if (ele.value != "")
		{
			var img_name = escape(ele.value);
			if (imgList[img_name].width < minWidth)
			{
				ele.value = "";
				var ele2= ele.cloneNode(false);
				ele2.onChange= ele.onChange;
				ele.parentNode.replaceChild(ele2,ele);
				alert(elename + " can't be smaller than width limit: " + minWidth);
				return false;
			}
		}
	}

	if((minHeight = ele.getAttribute('minHeight')) != null)
	{
		if (ele.value != "")
		{
			var img_name = escape(ele.value);
			if (imgList[img_name].height < minHeight)
			{
				ele.value = "";
				var ele2= ele.cloneNode(false);
				ele2.onChange= ele.onChange;
				ele.parentNode.replaceChild(ele2,ele);
				alert(elename + " can't be smaller than height limit: " + minHeight);
				return false;
			}
		}
	}
}

function isSquare(ele)
{
	var img_name = escape(ele.value);
	if(ele.value != "" && ele.getAttribute('isSquare') != undefined && imgList[img_name].width != imgList[img_name].height)
	{
		return false;
	}
	return true;
}

function isNumber(value)
{
	if (value*1 == value)
		return true;
	else
		return false;
}

function isInteger(value)
{
	if (value != "")
	{
		regex = /^[\-+]?[0-9]+$/;
		if (value.search(regex) == -1)
		{
			return false;
		}
	}
	return true;
}

function isNatural(value)
{
	if (value != "")
	{
		regex = /^[0-9]+$/;
		if (value.search(regex) == -1)
		{
			return false;
		}
	}
	return true;
}

function noSpecial(value)
{
	if (value != "")
	{
		regex = /^[a-zA-Z0-9-_]+$/;
		if (value.search(regex) == -1)
		{
			return false;
		}
	}
	return true;
}

function isEmpty(value)
{
	if (trim(value) == "")
		return true;
	else
		return false;
}

function isValidEmail(value)
{
	if (value !="") {
		regex = /^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i;
		if (value.search(regex) == -1) 
		{
			return false;
		}
	}
	return true; 
}

function trim(str, chars)
{
	return ltrim(rtrim(str, chars), chars);
}
 
function ltrim(str, chars)
{
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}
 
function rtrim(str, chars)
{
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function set_image(img)
{
	var img_name = escape(img.value);
	if (window.navigator.userAgent.indexOf("MSIE")>=1)
	{
		getPath(img);
		setTimeout(function () { set_load(img);}, 1000);
	}
	else
	{
		imgList[img_name] = new Image();
		imgList[img_name].src = getPath(img);
		setTimeout(function () { set_load(img);}, 1000);
	}
}

function set_load(img)
{
	var img_name = escape(img.value);
	if (imgList[img_name].width == 0 || imgList[img_name].height == 0)
	{
		setTimeout(function () { set_load(img);}, 1000);
	}
	else
	{
		checkImage(img);
	}
	return true;
}

function getPath(obj)
{
	if(obj)
	{
		//ie
		if (window.navigator.userAgent.indexOf("MSIE")>=1)
		{
			var img_name = escape(obj.value);
			obj.select();
			// IE get path
			var h_obj = document.getElementById(obj.name+'_h');
			h_obj.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = document.selection.createRange().text;
			imgList[img_name] = new Image();
			imgList[img_name].width = h_obj.offsetWidth;
			imgList[img_name].height = h_obj.offsetHeight;
		}
		//firefox
		else if(window.navigator.userAgent.indexOf("Firefox")>=1)
		{
			if(obj.files)
			{
			// Firefox get data
				return obj.files.item(0).getAsDataURL();
			}
			return obj.value;
		}
		return obj.value;
	}
}
	
function regExCheck(regExForTest, testSt, regExFlag)
{
	var regex = new RegExp(regExForTest, regExFlag);
	if (regex.test(testSt))
		return true;
	return false;
}

String.prototype.getBytes = function() {
	return encodeURIComponent(this).replace(/%../g, 'x').length;
}
