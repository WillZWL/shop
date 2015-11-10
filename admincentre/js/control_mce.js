document.write('<scr'+'ipt type="text/javascript" src="/js/tiny_mce/tiny_mce.js" ></scr'+'ipt>');
function control_mce()
{
	this.mode ="";
	this.theme ="";
	this.plugins = "";
	this.theme_advanced_buttons1 = "";
	this.theme_advanced_buttons2 = "";
	this.theme_advanced_buttons3 = "";
	this.theme_advanced_fonts = "";
	this.theme_advanced_toolbar_location = "";
	this.theme_advanced_toolbar_align = "";
	this.theme_advanced_statusbar_location = "";
	this.theme_advanced_resizing = "";
	this.template_external_list_url = "";
	this.external_link_list_url = "";
	this.external_image_list_url = "";
	this.media_external_list_url = "";
};
control_mce.prototype.init_default = function (version)
{
	this.mode = "textareas";
	this.theme ="advanced";
	this.theme_advanced_fonts = "Andale Mono=andale mono,times;"+
								"Arial=arial,helvetica,sans-serif;"+
								"Arial Black=arial black,avant garde;"+
								"Book Antiqua=book antiqua,palatino;"+
								"Comic Sans MS=comic sans ms,sans-serif;"+
								"Courier New=courier new,courier;"+
								"Georgia=georgia,palatino;"+
								"Helvetica=helvetica;"+
								"Impact=impact,chicago;"+
								"Symbol=symbol;"+
								"Tahoma=tahoma,arial,helvetica,sans-serif;"+
								"Terminal=terminal,monaco;"+
								"Times New Roman=times new roman,times;"+
								"Trebuchet MS=trebuchet ms,geneva;"+
								"Verdana=verdana,geneva;"+
								"Webdings=webdings;"+
								"Wingdings=wingdings,zapf dingbats;"+
								"細明體=細明體;"+
								"標楷體=標楷體";
	this.theme_advanced_toolbar_location = "top";
	this.theme_advanced_toolbar_align = "left";
	this.theme_advanced_statusbar_location = "bottom";
	this.theme_advanced_resizing = true;
	
	if(version == "simple" || version == '' || version == undefined)
	{
		this.plugins = "preview, searchreplace";
		this.theme_advanced_buttons1 = "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect";
		this.theme_advanced_buttons2 = "cut,copy,paste,pastetext,pasteword,removeformat,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,help,|,preview,|,forecolor,backcolor";
		this.theme_advanced_buttons3 = "";
		this.template_external_list_url = "";
		this.external_link_list_url = "";
		this.external_image_list_url = "";
		this.media_external_list_url = "";
	}
	else if (version == "full")
	{
		this.plugins = "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave";
		this.theme_advanced_buttons1 = "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect";
		this.theme_advanced_buttons2 = "cut,copy,paste,pastetext,pasteword,removeformat,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor";
		this.theme_advanced_buttons3 = "tablecontrols,|,hr,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen";
		this.theme_advanced_buttons4 = "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft";
		this.template_external_list_url = "lists/template_list.js";
		this.external_link_list_url = "lists/link_list.js";
		this.external_image_list_url = "lists/image_list.js";
		this.media_external_list_url = "lists/media_list.js";
	}
}
control_mce.prototype.load = function ()
{
	tinyMCE.init({
		// General options
		mode : this.mode,
		theme : this.theme,
		plugins : this.plugins,

		// Theme options
		theme_advanced_buttons1 : this.theme_advanced_buttons1,
		theme_advanced_buttons2 : this.theme_advanced_buttons2,
		theme_advanced_buttons3 : this.theme_advanced_buttons3,
		theme_advanced_fonts :this.theme_advanced_fonts,
		theme_advanced_toolbar_location : this.theme_advanced_toolbar_location,
		theme_advanced_toolbar_align : this.theme_advanced_toolbar_align,
		theme_advanced_statusbar_location : this.theme_advanced_statusbar_location,
		theme_advanced_resizing : this.theme_advanced_resizing,		
		
		template_external_list_url : this.template_external_list_url,
		external_link_list_url : this.external_link_list_url,
		external_image_list_url : this.external_image_list_url,
		media_external_list_url : this.media_external_list_url
		});
}
control_mce.prototype.load_default = function (version)
{
	this.init_default(version);
	this.load();
}
