/*
    Copyright (c) 2005-2012, Guru Sistemas and/or Gustavo Adolfo Arcila Trujillo
    All rights reserved.
    www.gurusistemas.com

    phpMyDataGrid Professional IS NOT FREE, may not be re-sold or redistributed as a single library.

    If you want to use phpMyDataGrid Professional on any of your projects, you Must purchase a license.

    You can buy the full source code or encoded version at http://www.gurusistemas.com/
    also can try the donationware version, which can be downloaded from http://www.gurusistemas.com/

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  "AS IS"  AND ANY EXPRESS  OR  IMPLIED WARRANTIES, INCLUDING,
    BUT NOT LIMITED TO,  THE IMPLIED WARRANTIES  OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT
    SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,  INDIRECT,  INCIDENTAL, SPECIAL, EXEMPLARY,  OR CONSEQUENTIAL
    DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF  USE, DATA, OR PROFITS;  OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
    OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

    For more info, samples, tips, screenshots, help, contact, support, please visit phpMyDataGrid site
    http://www.gurusistemas.com/
*/
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_esNS()
{
    return (navigator.userAgent.toLowerCase().indexOf("opera") != -1 || navigator.product == 'Gecko')
}

function DG_check(id, defval)
{
    if (!DG_isdefined(defval)) defval = '';
    if (!document.getElementById(id))
    {
        var newdgActive = document.createElement('input');
        newdgActive.type = 'hidden';
        newdgActive.name = id;
        newdgActive.id = id;
        newdgActive.value = defval;
        document.body.insertBefore(newdgActive, document.body.nextSibling)
    }
};

function ac()
{
    DG_check('DG_dgactive');
    return DG_gvv('DG_dgactive')
};

function slideAdd()
{
    if (DG_gvv('dg_nowindow' + ac()) == 0)
    {
        DG_svv("DG_posY" + ac(), DG_getY());
        window.scrollTo(0, 0)
    }
    else
    {
        if (DG_goo("addDiv" + ac()).style.display == 'block')
        {
            DG_hss("addDiv" + ac(), 'none');
            DG_hss("dgDiv" + ac(), 'block')
        }
        else
        {
            DG_hss("addDiv" + ac(), 'block');
            DG_hss("dgDiv" + ac(), 'none')
        };
        DG_svv("dg_nocenter" + ac(), 1)
    }
};

function DG_addrow()
{
    slideAdd();
    DG_Do("add")
};

function DG_editrow(intRow, code)
{
    slideAdd();
    DG_Do("edit", intRow, code)
};

function DG_viewrow(intRow, code)
{
    slideAdd();
    DG_Do("view", intRow, code)
};

function DG_deleterow(intRow, code)
{
    if (confirm(DG_gvv('DGtxtDelete' + ac()))) DG_Do("delete", intRow, code)
};

function DM_editrow(param)
{
    arrParam = param.split("::");
    DG_editrow(arrParam[0], arrParam[1])
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DM_viewrow(param)
{
    arrParam = param.split("::");
    DG_viewrow(arrParam[0], arrParam[1])
};

function DM_deleterow(param)
{
    arrParam = param.split("::");
    DG_deleterow(arrParam[0], arrParam[1])
};

function DG_centrar(DivName)
{
    if (self.innerWidth)
    {
        frameWidth = self.innerWidth;
        frameHeight = self.innerHeight
    }
    else if (document.documentElement && document.documentElement.clientWidth)
    {
        frameWidth = document.documentElement.clientWidth;
        frameHeight = document.documentElement.clientHeight
    }
    else if (document.body)
    {
        frameWidth = document.body.clientWidth;
        frameHeight = document.body.clientHeight
    };
    centroh1 = window.innerWidth / 2;
    if (isNaN(centroh1)) centroh1 = frameWidth / 2;
    centroh2 = document.getElementById(DivName).offsetWidth / 2;
    centroh = parseInt(centroh1) - parseInt(centroh2);
    centrov1 = window.innerHeight / 2;
    if (isNaN(centrov1)) centrov1 = frameHeight / 2;
    centrov2 = document.getElementById(DivName).offsetHeight / 2;
    centrov = parseInt(centrov1) - parseInt(centrov2);
    document.getElementById(DivName).style.left = centroh + "px";
    document.getElementById(DivName).style.top = centrov + "px";
    document.getElementById(DivName).style.left = centroh + "px";
    document.getElementById(DivName).style.top = centrov + "px"
};

function DG_getX()
{
    var posX = 0;
    if (window.innerHeight)
    {
        posX = window.pageXOffset
    }
    else if (document.documentElement && document.documentElement.scrollTop)
    {
        posX = document.documentElement.scrollLeft
    }
    else if (document.body)
    {
        posX = document.body.scrollLeft
    };
    return posX
};

function DG_getY()
{
    var posY = 0;
    if (window.innerHeight)
    {
        posY = window.pageYOffset
    }
    else if (document.documentElement && document.documentElement.scrollTop)
    {
        posY = document.documentElement.scrollTop
    }
    else if (document.body)
    {
        posY = document.body.scrollTop
    };
    return posY
};

function DG_setOpacity(id, val)
{
    opc = (val == 100) ? 60 : 100;
    DG_opacity(id, opc, val, 1)
};

function DG_opacity(id, opacStart, opacEnd, millisec)
{
    var speed = Math.round(millisec / 300);
    var timer = 0;
    if (opacStart > opacEnd)
    {
        for (i = opacStart; i >= opacEnd; i--)
        {
            setTimeout("DG_changeOpac(" + i + ",'" + id + "')", (timer * speed));
            timer++
        }
    }
    else if (opacStart < opacEnd)
    {
        for (i = opacStart; i <= opacEnd; i++)
        {
            setTimeout("DG_changeOpac(" + i + ",'" + id + "')", (timer * speed));
            timer++
        }
    }
};

function DG_changeOpac(opacity, id)
{
    var object = DG_goo(id).style;
    object.opacity = object.MozOpacity = object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")"
};

function DG_imposeMaxLength(objField, txtMaxLen)
{
    if (objField.value.length > txtMaxLen) objField.value = objField.value.substr(0, txtMaxLen);
    return true
};

function DG_silent_enter(e, new_id, dgvcode, allowEnter)
{
    var e = window.event || e;
    var cCode = e.keyCode ? e.keyCode : e.charCode;
    var allowEnter = (typeof (allowEnter) == 'undefined') ? false : allowEnter;
    if (cCode == 9 || (cCode == 13 && !allowEnter))
    {
        DG_goo(new_id).onblur = null;
        DG_save_field(new_id, dgvcode)
    };
    if (cCode == 27) DG_cancel_field(new_id);
    return true
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_bl_enter(e, action)
{
    var e = window.event || e;
    var charCode = e.keyCode;
    if (charCode == 13)
    {
        if (DG_isdefined(action)) eval(action);
        return false
    }
    else return true
};

function DG_bl_esc(e, action)
{
    var e = window.event || e;
    var charCode = e.keyCode;
    if (charCode == 27)
    {
        if (DG_isdefined(action)) eval(action);
        return false
    }
    else return true
};

function DG_set_page_rows()
{
    DG_hss("DGpgTable" + ac(), "block");
    DG_hss("DG_pgGetRows" + ac(), "none");
    DG_svv("DG_nrpp" + ac(), DG_gvv("DG_nrppAux" + ac()));
    DG_set_pg_rows()
};

function DG_set_pg_rows()
{
    DG_svv("DG_nrpp" + ac(), DG_gvv("DG_nrppAux" + ac()));
    DG_Do()
};

function DG_cncl_page_rows()
{
    DG_hss("DGpgTable" + ac(), "block");
    DG_hss("DG_pgGetRows" + ac(), "none")
};

function DG_act_page_rows()
{
    DG_hss("DGpgTable" + ac(), "none");
    DG_hss("DG_pgGetRows" + ac(), "block")
};

function DG_liberaCapa(id)
{
    if (!DG_isdefined(id) || typeof (id) == 'object') return;
    DG_capa = null;
    DG_setOpacity(id, 100);
    document.onselectstart = null
};

function DG_clickCapa(e, obj, id)
{
    if (!DG_esNS())
    {
        DG_capa = event.srcElement.parentElement.style;
        difX = e.offsetX;
        difY = e.offsetY
    }
    else
    {
        DG_capa = obj.parentNode;
        difX = e.layerX;
        difY = e.layerY
    };
    DG_setOpacity(id, 60);
    document.body.focus();
    document.onselectstart = function ()
    {
        return false
    };
    return false
};

function DG_select(objChk)
{
    v = objChk.value;
    if (objChk.checked) DG_goo('dg' + ac() + 'TR' + v).className = 'dgSelRow';
    else DG_goo('dg' + ac() + 'TR' + v).className = DG_gvv('dg' + ac() + 'Choc' + v)
};

function DG_mueveCapa(e)
{
    var posX = DG_getX();
    var posY = DG_getY();
    if (DG_capa != null)
    {
        if (DG_esNS())
        {
            DG_capa.style.top = (e.clientY - difY + posY) + "px";
            DG_capa.style.left = (e.clientX - difX + posX) + "px"
        }
        else
        {
            DG_capa.pixelLeft = event.clientX - difX + posX;
            DG_capa.pixelTop = event.clientY - difY + posY
        };
        return false
    }
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_chgpg(pgNumber)
{
    DG_Do("chgPage", pgNumber)
};

function DG_orderby(field, order, event, addShift)
{
    if ((DG_isdefined(addShift) && addShift == 1) || event.shiftKey == 1)
    {
        if (DG_gvv('dg_order' + ac()) != "") field = DG_gvv('dg_order' + ac()) + "," + field;
        if (DG_gvv('dg_oe' + ac()) != "") order = DG_gvv('dg_oe' + ac()) + "," + order
    };
    DG_Do("orderby", field, order)
};

function DG_ui(fldname, keyValue, imgName)
{
    DG_svv("DG_posY" + ac(), DG_getY());
    window.scrollTo(0, 0);
    DG_Do("upload", fldname, keyValue + "&imgname" + ac() + "=" + imgName)
};

function DM_orderasc(param)
{
    DG_Do("orderby", param, "ASC")
};

function DM_orderdes(param)
{
    DG_Do("orderby", param, "DESC")
};

function DM_orderasca(param)
{
    DG_orderby(param, "ASC", null, 1)
};

function DM_orderdesa(param)
{
    DG_orderby(param, "DESC", null, 1)
};

function DG_closeDiv(divName)
{
    if (!DG_isdefined(divName)) divName = "addDiv" + ac();
    window.scrollTo(0, DG_gvv("DG_posY" + ac()));
    DG_sii(divName, "")
};

function DG_closeAdd()
{
    slideAdd();
    DG_sii("addDiv" + ac(), "")
};

function DG_showSearchBox()
{
    if (DG_gvv('dg_toolbar' + ac()) == 0 || DG_gvv('dg_toolbarsearch' + ac()) == 0)
    {
        DG_opacity("DG_srchDIV" + ac(), 0, 0, 1);
        DG_hss("DG_srchDIV" + ac(), "block");
        DG_opacity("DG_srchDIV" + ac(), 0, 100, 500);
        DG_centrar("DG_srchDIV" + ac())
    }
    else
    {
        DG_Slide("DG_srchDIV" + ac(),
        {
            duration: .2
        }).swap()
    };
    DG_goo("dg_schrstr" + ac()).focus()
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_closeSearch()
{
    if (DG_gvv('dg_toolbar' + ac()) == 0 || DG_gvv('dg_toolbarsearch' + ac()) == 0)
    {
        DG_opacity("DG_srchDIV" + ac(), 100, 0, 500);
        setTimeout('DG_hss("DG_srchDIV' + ac() + '","none")', 600)
    }
};

function DG_doSearch()
{
    DG_closeSearch();
    DG_Do("search")
};

function DG_resetSearch()
{
    DG_hss("rstsearch" + ac(), "none");
    DG_closeSearch();
    DG_Do("resetsearch")
};

function DG_setsearch(campo, fldvalue)
{
    var camposearch = DG_gvv("DGcamposearch" + ac());
    var results = eval("camposearch.search(/" + campo + ":sel/gi)");
    if (results == "-1")
    {
        DG_sii("searchBox" + ac(), "<input type='text' id='dg_schrstr" + ac() + "' class='dgInput' size='35' value='" + fldvalue + "' onkeypress='return DG_bl_enter(event,\"DG_doSearch()\") && DG_bl_esc(event,\"DG_closeSearch()\")' /><input type='hidden' id='boxshr" + ac() + "' name='boxshr" + ac() + "' value='0' />")
    }
    else
    {
        DG_hss("imgsearch" + ac(), "none");
        DG_ajaxLoader(DG_gvv('DGscrName' + ac()), "ajaxDHTMLDiv" + ac(), "2&dgfs" + ac() + "=" + campo + DG_gvv('DGparams' + ac()) + "&tAjax=" + Math.random(), "searchBox" + ac(), "<input type='hidden' id='boxshr" + ac() + "' name='boxshr" + ac() + "' value='1'>" + DG_gvv('DGtxtLoading' + ac()));
        DG_checkAjaxSearch("boxshr" + ac(), fldvalue)
    }
};

function DG_doSave(fields, recno)
{
    DG_Do("save", fields, recno);
    if (DG_gvv('dg_nowindow' + ac()) == 1) slideAdd()
};

function DG_doSaveAdd(fields, recno, wae)
{
    DG_Do("saveadd", fields, recno, wae);
    if (DG_gvv('dg_nowindow' + ac()) == 1) slideAdd()
};

function DG_showExportBox()
{
    if (DG_gvv('dg_toolbar' + ac()) == 0 || DG_gvv('dg_toolbarexport' + ac()) == 0)
    {
        DG_opacity("DG_xportDIV" + ac(), 0, 0, 1);
        DG_hss("DG_xportDIV" + ac(), "block");
        DG_opacity("DG_xportDIV" + ac(), 0, 100, 500);
        DG_centrar("DG_xportDIV" + ac())
    }
    else
    {
        DG_Slide("DG_xportDIV" + ac(),
        {
            duration: .2
        }).swap()
    }
};

function DG_closeExport()
{
    if (DG_gvv('dg_toolbar' + ac()) == 0 || DG_gvv('dg_toolbarexport' + ac()) == 0)
    {
        DG_opacity("DG_xportDIV" + ac(), 100, 0, 500);
        setTimeout('DG_hss("DG_xportDIV' + ac() + '","none")', 600)
    }
};

function DG_checkAjaxSearch(id, fldvalue)
{
    var dato = DG_gvv(id);
    if (dato != 0)
    {
        DG_svv(id, dato + 1);
        if (dato == 40)
        {
            DG_sii("searchBox" + ac(), "<input type='text' id='dg_schrstr" + ac() + "' class='dgInput' size='35' value='" + fldvalue + "' onkeypress='return DG_bl_enter(event)' /><input type='hidden' id='boxshr" + ac() + "' name='boxshr" + ac() + "' value='0' />");
            DG_hss("imgsearch" + ac(), "inline")
        }
        else
        {
            setTimeout("DG_checkAjaxSearch('" + id + "','" + fldvalue + "');", 1000)
        }
    }
    else
    {
        DG_goo("imgsearch" + ac()).style.display = 'inline'
    }
};

function DG_export(destino, opcion)
{
    DG_closeExport();
    if (opcion == 'I')
    {
        var archivo_export = DG_gvv("dg_exportMagma" + ac());
        if (archivo_export.length > 0) DG_svv('DGscrName' + ac(), archivo_export);
        output = 2
    }
    else
    {
        output = 0
    };
    DG_Do("export", destino, opcion, output)
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_delete_selection()
{
    if (confirm(DG_gvv('DGtxtDeletes' + ac()))) action = 12;
    else return;
    DG_Do("delete_selection")
};

function DG_Do(action, p1, p2, p3)
{
    var theDiv = "dgDiv" + ac();
    var dgvcode = dgrtd = "";
    var p = DG_gvv('DGscrName' + ac());
    var DG_ajaxid = 1;
    var pgNumber = DG_gvv("dg_r" + ac());
    var vOrder = DG_gvv("dg_order" + ac());
    var oe = DG_gvv("dg_oe" + ac());
    var ss = DG_gvv("dg_ss" + ac());
    var schrstr = DG_gvv("dg_schrstr" + ac());
    var nocenter = DG_gvv("dg_nocenter" + ac());
    if (DG_isdefined(DG_goo("DG_exportadetails" + ac())) && DG_goo("DG_exportadetails" + ac()).type == 'select-one')
    {
        var exportDet = DG_gvv("DG_exportadetails" + ac())
    }
    else
    {
        var exportDet = (DG_isdefined(DG_goo("DG_exportadetails" + ac()))) ? DG_gcc("DG_exportadetails" + ac()) : 0
    };
    var selected_checkboxes = selected_checks();
    switch (action)
    {
        case "chgPage":
            pgNumber = p1;
            break;
        case "orderby":
            vOrder = p1;
            oe = p2;
            break;
        case "search":
            pgNumber = 0;
            if (schrstr == "") DG_hss("rstsearch" + ac(), "none");
            else DG_hss("rstsearch" + ac(), "inline");
            break;
        case "resetsearch":
            pgNumber = 0;
            schrstr = "";
            DG_svv("dg_schrstr" + ac(), "");
            break;
        case "delete":
            dgrtd = p1;
            DG_ajaxid = 3;
            dgvcode = p2;
            break;
        case "add":
            DG_ajaxid = 5;
            theDiv = "addDiv" + ac();
            if (nocenter != 1) wae = "DG_centrar('DG_addDIV" + ac() + "');";
            break;
        case "edit":
            DG_ajaxid = 5;
            theDiv = "addDiv" + ac();
            dgrtd = p1;
            dgvcode = p2;
            if (nocenter != 1) wae = "DG_centrar('DG_addDIV" + ac() + "');";
            break;
        case "view":
            DG_ajaxid = 5;
            theDiv = "addDiv" + ac();
            dgrtd = p1;
            dgvcode = "view" + p2;
            if (nocenter != 1) wae = "DG_centrar('DG_addDIV" + ac() + "');";
            break;
        case "export":
            DG_ajaxid = 7;
            dgrtd = p1;
            dgvcode = p2;
            extra = DG_gvv('DGextraParameters' + ac()).replace(/ /ig, '').split(",");
            if (extra.length > 0)
            {
                for (var n = 0; n < extra.length; n++)
                {
                    if (DG_goo(extra[n]) != null) dgvcode += (DG_goo(extra[n]).type == 'checkbox') ? "&" + extra[n] + "=" + DG_gcc(extra[n]) : "&" + extra[n] + "=" + DG_gvv(extra[n])
                }
            };
            if (p1 == 'S' && selected_checkboxes == "") return alert(DG_gvv("dg_noselect" + ac()));
            break;
        case "upload":
            DG_ajaxid = 8;
            theDiv = "uplDiv" + ac();
            dgrtd = p1;
            dgvcode = p2;
            break;
        case "save":
        case "saveadd":
            DG_ajaxid = 6;
            dgrtd = p2;
            for (field = 0; field < p1.length; field++)
            {
                if (field != 'inArray')
                {
                    var fldName = p1[field].split(":");
                    fldValue = (DG_isdefined(fldName[1]) ? fldName[1] : "X");
                    fldValue = (DG_isdefined(fldName[1]) && fldName[1] == "check") ? DG_gcc(fldName[0]) : fldValue = DG_gvv(fldName[0]);
                    if (DG_gvv('dg_tinymce' + ac()) == 1 && DG_isdefined(tinyMCE.get(fldName)))
                    {
                        fldValue = tinyMCE.get(fldName).getContent()
                    };
                    dgvcode = dgvcode + "&" + fldName[0] + "=" + encodeURIComponent(fldValue)
                }
            };
            DG_sii("addDiv" + ac(), "");
            if (action == 'saveadd') wae = "DG_addrow()";
            break;
        case "delete_selection":
            action = 12;
        default:
            DG_ajaxid = action;
            dgrtd = p1;
            if (DG_isdefined(p2)) theDiv = p2;
            break
    };
    parametersAjax = DG_ajaxid + "&dg_r" + ac() + "=" + pgNumber + "&dg_order" + ac() + "=" + vOrder + "&dg_edt" + ac() + "=" + exportDet + "&dg_oe" + ac() + "=" + oe + "&dg_ss" + ac() + "=" + ss + "&dg_schrstr" + ac() + "=" + schrstr + "&dg_nrpp" + ac() + "=" + DG_gvv('DG_nrpp' + ac()) + "&dgrtd" + ac() + "=" + dgrtd + "&dgvcode" + ac() + "=" + dgvcode + "&chksel" + ac() + "=" + selected_checkboxes + DG_gvv('DGparams' + ac()) + "&x=" + screen.width + "&y=" + screen.height + "&dg_tAjax=" + Math.random();
    if (DG_isdefined(p3))
    {
        connector = (p.indexOf("?") == -1) ? "?" : "&";
        if (p3 == 2)
        {
            window.open(p + connector + "DG_ajaxid" + ac() + "=" + parametersAjax)
        }
        else
        {
            location.href = p + connector + "DG_ajaxid" + ac() + "=" + parametersAjax
        }
    }
    else
    {
        DG_ajaxLoader(p, "ajaxDHTMLDiv" + ac(), parametersAjax, theDiv, '', wae);
        wae = ""
    };
    afterAction = DG_gvv('afterAction' + ac());
    if (afterAction != '') eval(afterAction)
};

function DG_hss(objToProcess, status)
{
    try
    {
        document.getElementById(objToProcess).style.display = status
    }
    catch (err)
    {
        if (debug) alert("HSS undefined: " + objToProcess)
    }
};

function DG_gvv(objToProcess)
{
    try
    {
        return document.getElementById(objToProcess).value
    }
    catch (err)
    {
        if (debug) alert("GVV undefined: " + objToProcess)
    }
};

function DG_goo(objToProcess)
{
    try
    {
        return document.getElementById(objToProcess)
    }
    catch (err)
    {
        if (debug) alert("GOO undefined: " + objToProcess)
    }
};

function DG_svv(objToProcess, strValue)
{
    try
    {
        document.getElementById(objToProcess).value = strValue
    }
    catch (err)
    {
        if (debug) alert("SVV undefined: " + objToProcess)
    }
};

function DG_sii(objToProcess, strValue)
{
    try
    {
        document.getElementById(objToProcess).innerHTML = strValue
    }
    catch (err)
    {
        if (debug) alert("SII undefined: " + objToProcess)
    }
};

function DG_gii(objToProcess)
{
    try
    {
        return document.getElementById(objToProcess).innerHTML
    }
    catch (err)
    {
        if (debug) alert("GII undefined: " + objToProcess)
    }
};

function DG_gcc(objToProcess)
{
    try
    {
        return (document.getElementById(objToProcess).checked) ? 1 : 0
    }
    catch (err)
    {
        if (debug) alert("GCC undefined: " + objToProcess)
    }
};

function DG_isdefined(objToTest)
{
    if (null == objToTest) return false;
    if ("undefined" == typeof (objToTest)) return false;
    return true
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_isFunction(objToTest)
{
    return (typeof (objToTest) == "function")
};

function DG_setExport(destino)
{
    if (!DG_isdefined(destino)) destino = DG_checkselected('exporta' + ac());
    alcance = DG_checkselected('exportato' + ac());
    if (alcance == -1 || destino == -1) return;
    DG_svv("DGactExport" + ac(), "DG_export('" + alcance + "','" + destino + "')")
};

function DG_defAjaxHandler()
{
    var axx = false;
    if (window.XMLHttpRequest)
    {
        axx = new XMLHttpRequest();
        if (axx.overrideMimeType)
        {
            axx.overrideMimeType('text/html')
        }
    }
    else if (window.ActiveXObject)
    {
        try
        {
            axx = new ActiveXObject("Msxml2.XMLHTTP")
        }
        catch (e)
        {
            try
            {
                axx = new ActiveXObject("Microsoft.XMLHTTP")
            }
            catch (e)
            {}
        }
    };
    if (!axx)
    {
        alert('Cannot create XMLHTTP instance');
        return false
    };
    return (axx)
};

function DG_manageAjax(x, o, programa, parametros)
{
    x.onreadystatechange = o;
    if (methodForm == 'POST')
    {
        if (parametros != '') parameters = 'DG_ajaxid' + ac() + '=' + parametros;
        else parameters = '';
        url = programa;
        // note this solution does not work......... breaks tier 2 and above
        // url = '#';                  // this allows us to use datagrid as /manage_table/tag/lookup
                                    // instead of /manage_table?tag=lookup

        x.open('POST', url, true);
        x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        x.setRequestHeader("Content-length", parameters.length);
        x.setRequestHeader("Connection", "close");
        x.send(parameters)
    }
    else
    {
        if (parametros != '') url = programa + '?DG_ajaxid' + ac() + '=' + parametros;
        else url = programa;
        x.open("GET", url, true);
        x.send(null)
    }
};

function DG_ajaxLoader(programa, id, parametros, displayid, text, whenAjaxEnd, o)
{
    imgpath = DG_gvv('DGimgpath' + ac());
    methodForm = DG_gvv('DGmethodForm' + ac());
    imgAjax = DG_gvv('DGimgAjax' + ac());
    if (!DG_isdefined(text) || text == '') text = DG_gvv('DGtxtLoading' + ac());
    if (window.innerHeight)
    {
        posY = window.pageYOffset
    }
    else if (document.documentElement && document.documentElement.scrollTop)
    {
        posY = document.documentElement.scrollTop
    }
    else if (document.body)
    {
        posY = document.body.scrollTop
    };
    elemento = DG_goo(id);
    if (DG_esNS())
    {
        elemento.style.top = parseInt(posY) + 'px'
    }
    else
    {
        elemento.style.pixelTop = parseInt(posY)
    };
    if (DG_esNS())
    {
        elemento.style.left = '0px'
    }
    else
    {
        elemento.style.pixelLeft = 0
    };
    elemento.innerHTML = "<div class='dgAjax'><img border='0' width='16' height='16' src='" + imgpath + imgAjax + "'>&nbsp;&nbsp;" + text + "&nbsp;&nbsp;&nbsp;<\/div>";
    x = DG_defAjaxHandler();
    if (x)
    {
        if (!DG_isdefined(o))
        {
            o = function ()
            {
                if (x.readyState == 4 && x.status == 200)
                {
                    DG_sii(id, '');
                    DG_SetContainerHTML(displayid, x.responseText, true);
                    if (DG_isdefined(whenAjaxEnd) && whenAjaxEnd != "") eval(whenAjaxEnd)
                }
            }
        };
        DG_manageAjax(x, o, programa, parametros)
    }
};

function DG_SetContainerHTML(id, html, processScripts)
{
    mydiv = DG_goo(id);
    mydiv.innerHTML = html;
    if (processScripts != false)
    {
        var elementos = mydiv.getElementsByTagName('script');
        for (i = 0; i < elementos.length; i++)
        {
            var elemento = elementos[i];
            nuevoScript = document.createElement('script');
            nuevoScript.text = elemento.innerHTML;
            nuevoScript.type = 'text/javascript';
            if (elemento.src != null && elemento.src.length > 0) nuevoScript.src = elemento.src;
            elemento.parentNode.replaceChild(nuevoScript, elemento)
        }
    }
};

function DG_checkAjax(id, oldvalue, idfield)
{
    if (DG_gii(id).search(/&lt;ERROR&gt;/ig) != -1)
    {
        alert(DG_gvv('DGtextErrorInline' + ac()));
        DG_sii(id, DG_gvv("DG_editvalue" + ac()));
        DG_svv("i" + ac() + id, oldvalue);
        return false
    };
    DG_checkCalcs(idfield);
    return true
};

function DG_setCheckboxes(do_check)
{
    var elts = document.getElementsByName("chk" + ac() + "sel[]");
    var elts_cnt = (typeof (elts.length) != 'undefined') ? elts.length : 0;
    if (elts_cnt)
    {
        for (var i = 0; i < elts_cnt; i++)
        {
            if (elts[i].checked) elts[i].checked = false;
            else elts[i].checked = true;
            DG_select(elts[i])
        }
    }
    else
    {
        elts.checked = do_check
    };
    return true
};

function selected_checks()
{
    var sel_checks = new Array();
    var elts_a = document.getElementsByName("chk" + ac() + "sel[]");
    var elts_cnt_a = (typeof (elts_a.length) != 'undefined') ? elts_a.length : 0;
    if (elts_cnt_a)
    {
        counter_a = 0;
        for (var i_a = 0; i_a < elts_cnt_a; i_a++)
        {
            if (elts_a[i_a].checked)
            {
                sel_checks[counter_a] = elts_a[i_a].value;
                counter_a++
            }
        }
    };
    return sel_checks
};

function DG_checkselected(ctrl)
{
    ctrl = document.getElementsByName(ctrl);
    for (i = 0; i < ctrl.length; i++) if (ctrl[i].checked) return ctrl[i].value;
    return -1
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_upload(obj)
{
    var uploadDir = obj.value;
    DG_goo('dgul' + ac()).action = DG_gvv('DGscrName' + ac());
    DG_goo('dgul' + ac()).submit()
};

function DG_addslashes(str)
{
    return str.replace(/\'/g, "\\'")
};

function DG_cancel_field(idfield)
{
    var myID = idfield.split("_AjaxDhtml");
    DG_sii(myID[0], DG_gvv("DG_editvalue" + ac()));
    DG_svv("ajaxDHTMLediting", 0);
    setTimeout("DG_goo('" + myID[0] + "').onclick = DG_goo('" + myID[0] + "').oldclick;", 500)
};

function updResult(updField, dato)
{
    DG_ajaxLoader(DG_gvv('DGscrName' + ac()), 'ajaxDHTMLDiv' + ac(), "9&dgrtd" + ac() + "=" + updField + "&dgnt" + ac() + "=" + dato + "&dgvcode" + ac() + "=" + DG_gvv('DGparams' + ac()) + "&tAjax=" + Math.random(), updField, DG_gvv('DGtxtSaving' + ac()), "updTotCal('" + updField + "')")
};

function updTotCal(campo)
{
    cNm = campo.split(".-.");
    arrRows = DG_gvv('DGCalcRows' + ac()).split(',');
    arrTot = DG_gvv("DGtotalizar" + ac()).split(",");
    lngCod = ac().length;
    cNm[0] = DG_left(cNm[0], cNm[0].length - lngCod);
    if (!(DG_gvv('DGthereisTotal' + ac()) == 0 && arrTot.DG_inArray(cNm[0]))) return;
    campo = cNm[0] + ".-." + cNm[1];
    nv = DG_gvv("c" + ac() + campo);
    var nt = 0;
    DG_svv("i" + ac() + campo, nv);
    for (var n = 0; n < arrRows.length; n++)
    {
        valCell = DG_gvv("i" + ac() + cNm[0] + ".-." + arrRows[n]);
        valCell = parseFloat(valCell);
        if (isNaN(valCell)) valCell = 0;
        nt += valCell
    };
    DG_ajaxLoader(DG_gvv('DGscrName' + ac()), 'ajaxDHTMLDiv' + ac(), '9&dgrtd' + ac() + '=' + cNm[0] + ac() + ".-.Total&dgnt" + ac() + "=" + nt + "&dgvcode" + ac() + "=" + DG_gvv('DGparams' + ac()) + "&tAjax=" + Math.random(), cNm[0] + ac() + ".-.Total", DG_gvv('DGtxtSaving' + ac()))
};
Array.prototype.DG_inArray = function (search_term)
{
    var i = this.length;
    if (i > 0)
    {
        do {
            if (this[i] === search_term) return true
        } while (i--)
    };
    return false
};
document.onmousemove = DG_mueveCapa;
document.onmouseup = DG_liberaCapa;

function DG_set_working_grid(id)
{
    if (DG_gvv("ajaxDHTMLediting") == '0') DG_svv("DG_dgactive", id)
};

function DG_D_edit(objField, dgvcode)
{
    // DG_gvv = get_vv, DG_svv = set_vv
    if (DG_gvv("ajaxDHTMLediting") == '0')
    {
        DG_svv("ajaxDHTMLediting", 1);
        var tac = DG_gvv('dg_ta_columns' + ac());
        eval("var aColumns=" + tac);
        var imgpath = DG_gvv('DGimgpath' + ac());
        var StyleAjax = DG_gvv('DGajaxStyle' + ac());
        var idField = objField.id;
        var arrFldData = idField.split(".-.");
        var idLen = ac().length;
        var fldLen = arrFldData[0].length;
        var fldData = DG_left(arrFldData[0], fldLen - idLen);
        var origidField = fldData + ".-." + arrFldData[1];
        DG_svv('DG_editRecNo' + ac(), arrFldData[1]);
        var aColumn = aColumns[fldData];
        var lenmax = (aColumn['maxlength'] == "0") ? "" : " maxlength='" + aColumn['maxlength'] + "' ";
        var rows = " rows='" + ((aColumn['fieldWidth'] == 0) ? "3" : aColumn['fieldWidth']) + "' ";
        var new_id = objField.id + "_AjaxDhtml";
        var inputtext = DG_gvv('i' + ac() + origidField);
        DG_svv("DG_editvalue" + ac(), objField.innerHTML.replace(/\n/ig, ""));
        if (new_id == "_AjaxDhtml")
        {
            DG_svv("ajaxDHTMLediting", 0);
            return
        }
        else
        {
            var classIn = " class='dgInput' ";
            var classIm = " class='dgImgLink' ";
            var thename = " id='" + new_id + "' ";
            var savefield = " DG_save_field('" + new_id + "','" + dgvcode + "') ";
            var cancelfield = " DG_cancel_field('" + new_id + "') ";
            var keypress = events = changed = "";
            elWidth = " style='width:90%' ";
            var frm = frmDate = '';
            if (aColumn["datatype"] == 'date' && DG_gvv('DGbolCalendar' + ac()) == 1)
            {
                myMask = aColumn['mask'].split(':');
                StyleAjax = "default";
                miMask = myMask[1].substr(0, 1) + myMask[2] + myMask[1].substr(1, 1) + myMask[2] + myMask[1].substr(2, 1);
                miMask = miMask.replace(/d/gi, 'dd');
                miMask = miMask.replace(/m/gi, 'mm');
                miMask = miMask.replace(/y/gi, 'yyyy');
                txtCal = DG_gvv('DGtxtCalendar' + ac());
                elWidth = " style='width:65%' ";
                frmDate = "<img src='" + imgpath + DG_gvv('DGimgCalendar' + ac()) + "' alt='" + txtCal + "' title='" + txtCal + "' onClick='viewCalendar( \"" + new_id + "\", \"" + miMask + "\", event );' class='dgImgPags' >"
            };
            if (StyleAjax == "silent")
            {
                var valEnter = 0;
                if (aColumn["datatype"] == "textarea")
                {
                    keypress = "DG_imposeMaxLength(this, " + aColumn['maxlength'] + ") && ";
                    valEnter = 1
                };
                keypress = " return " + keypress + " DG_silent_enter(event,'" + new_id + "','" + dgvcode + "'," + valEnter + ") ";
                events = " onDblClick=\"this.onblur=null;this.onkeypress=null;" + savefield + "\" onKeyPress=\"this.onblur=null;" + keypress + "\" onBlur=\"this.onKeyPress=null;" + savefield + "\" ";
                changed = " onChange=\"this.onblur=null;this.onkeypress=null;" + savefield + "\" "
            }
            else
            {
                events = " onKeyPress='return DG_bl_enter(event)' "
            };
            switch (aColumn["datatype"])
            {
                case "select":
                    frm += "<select class='dgSelectpage' " + elWidth + changed + thename + events + ">";
                    arrOptions = Array();
                    for (therow in aColumn["select"])
                    {
                        arrOptions[arrOptions.length] = aColumn["select"][therow] + "|||" + therow
                    }
                    arrOptions.sort();
                    for (n = 0; n < arrOptions.length; n++)
                    {
                        arrRow = arrOptions[n].split("|||");
                        frm += "<option value='" + arrRow[1] + "'" + ((inputtext == arrRow[1]) ? " selected='selected' " : "") + ">" + arrRow[0] + "<\/option>"
                    }
                    frm += "<\/select>";
                    break;
                case "check":
                    frm += "<input class='dgCheck' type='checkbox' " + thename + changed + events + ((inputtext == 1) ? " checked='checked' " : "") + ">";
                    break;
                case "textarea":
                    frm += "<textarea " + classIn + elWidth + thename + events + rows + "></textarea>";
                    break;
                case "password":
                    frm += "<input " + thename + classIn + " type='password' " + elWidth + events + lenmax + ">";
                    break;
                case "number":
                    frm += "<input " + thename + classIn + " type='number' " + elWidth + events + lenmax + ">" + frmDate
                    break;
                default:
                    frm += "<input " + thename + classIn + " type='text' " + elWidth + events + lenmax + ">" + frmDate
            };
            if (StyleAjax != "silent")
            {
                txtSave = DG_gvv('DGtxtSave' + ac());
                txtCancel = DG_gvv('DGtxtCancel' + ac());
                frm += "<div " + elWidth + " align='center'><img src='" + imgpath + DG_gvv('DGimgSave' + ac()) + "' alt='" + txtSave + "' title='" + txtSave + "' onClick = \"" + savefield + "\"" + classIm + ">";
                frm += "<img src='" + imgpath + DG_gvv('DGimgCancel' + ac()) + "' alt='" + txtCancel + "' title='" + txtCancel + "' onClick=\"" + cancelfield + "\"" + classIm + "></div>"
            };
            DG_sii(idField, frm);
            DG_goo(new_id).focus();
            DG_svv(new_id, unescape(inputtext));
            if (aColumn["datatype"] != "check" && aColumn["datatype"] != "select") DG_goo(new_id).select();
            objField.oldclick = objField.onclick;
            objField.onclick = null
        }
    }
};

function DG_save_field(idfield, dgvcode)
{
    eval("var aColumns= " + DG_gvv('dg_ta_columns' + ac()));
    var objFld = DG_goo(idfield);
    var idfield = idfield.replace(/_AjaxDhtml/ig, "");
    var arrFldData = idfield.split(".-.");
    var idLen = ac().length;
    var fldLen = arrFldData[0].length;
    var fldData = DG_left(arrFldData[0], fldLen - idLen);
    var origidField = fldData + ".-." + arrFldData[1];
    var column = fldData;
    var oldtext = DG_gvv("DG_editvalue" + ac());
    if (DG_gvv('DG_cFields' + ac()).indexOf('-[' + column + ']-') != -1)
    {
        var condition = DG_gvv('DG_' + column + '_C' + ac()).replace(/this./ig, "DG_goo('" + idfield + "_AjaxDhtml').");
        if (!(eval(condition))) return alert(DG_gvv('DG_' + column + '_E' + ac()))
    };
    var dbvalue = objFld.value.toString();
    if (objFld.type.indexOf('checkbox') == 0) dbvalue = (objFld.checked) ? '1' : '0';
    DG_svv("ajaxDHTMLediting", 0);
    setTimeout("DG_goo('" + idfield + "').onclick = DG_goo('" + idfield + "').oldclick;", 1000);
    if (dbvalue == (oldValue = DG_gvv('i' + ac() + origidField)))
    {
        DG_sii(idfield, oldtext);
        return
    };
    DG_sii(idfield, dbvalue);
    DG_svv('i' + ac() + origidField, dbvalue);
    DG_goo(idfield).style.color = DG_gvv('DGdgAjaxChanged' + ac());
    arrOldText = oldtext.split("<script");
    oldtext = arrOldText[0];
    idkey = arrFldData[1];
    newtext = dbvalue;
    field = arrFldData[0];
    field = DG_left(field, field.length - ac().length);
    afterOnlineEdition = DG_gvv('afterOnlineEdition' + ac());
    afterOnlineEdition = afterOnlineEdition.replace(/idkey/, "'" + idkey + "'");
    afterOnlineEdition = afterOnlineEdition.replace(/field/, "'" + field + "'");
    afterOnlineEdition = afterOnlineEdition.replace(/newtext/, "'" + newtext + "'");
    afterOnlineEdition = afterOnlineEdition.replace(/oldtext/, "'" + oldtext + "'");
    DG_ajaxLoader(DG_gvv('DGscrName' + ac()), "ajaxDHTMLDiv" + ac(), "4&dgrtd" + ac() + "=" + idfield + "&dgnt" + ac() + "=" + encodeURIComponent(dbvalue) + "&dgvcode" + ac() + "=" + dgvcode + DG_gvv('DGparams' + ac()) + "&tAjax=" + Math.random(), idfield, DG_gvv('DGtxtSaving' + ac()), afterOnlineEdition + ";DG_tmp=DG_checkAjax(\"" + idfield + "\",\"" + oldValue + "\",\"" + idfield + "\");");
    mask = aColumns[column]["mask"];
    if (DG_gvv('DGdecimalPoint' + ac()) == ".") sepMiles = "\\,";
    else sepMiles = ".";
    afterAction = DG_gvv('afterAction' + ac());
    if (afterAction != '') eval(afterAction)
};

function DG_left(str, n)
{
    if (n <= 0) return "";
    else if (n > String(str).length) return str;
    else return String(str).substring(0, n)
};

function DG_right(str, n)
{
    if (n <= 0) return "";
    else if (n > String(str).length) return str;
    else
    {
        var iLen = String(str).length;
        return String(str).substring(iLen, iLen - n)
    }
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_checkCalcs(idfield)
{
    eval("var aColumns= " + DG_gvv('dg_ta_columns' + ac()));
    var arrFldData = idfield.split(".-.");
    var column = arrFldData[0];
    var idLen = ac().length;
    var fldLen = arrFldData[0].length;
    var column = DG_left(arrFldData[0], fldLen - idLen);
    if (DG_gvv('DGthereisCalc' + ac()) == "true")
    {
        for (actualitem in aColumns)
        {
            strLeft = aColumns[actualitem]["mask"];
            if (strLeft.substring(0, 5) == 'calc:' || strLeft.substring(0, 6) == 'scalc:')
            {
                var part1 = strLeft.split("//");
                var parts = part1[0].split(":");
                var expresionToCalc = parts[1];
                var expresion = parts[1];
                var ShowCalc = false;
                expresion = expresion.replace(/\+/g, ' ');
                expresion = expresion.replace(/-/g, ' ');
                expresion = expresion.replace(/\//g, ' ');
                expresion = expresion.replace(/\*/g, ' ');
                expresion = expresion.replace(/\(/g, ' ');
                expresion = expresion.replace(/\)/g, ' ');
                expresionToCalc = expresionToCalc.replace(/\+/g, ' + ');
                expresionToCalc = expresionToCalc.replace(/-/g, ' - ');
                expresionToCalc = expresionToCalc.replace(/\//g, ' / ');
                expresionToCalc = expresionToCalc.replace(/\*/g, ' * ');
                expresionToCalc = expresionToCalc.replace(/\(/g, ' ( ');
                expresionToCalc = expresionToCalc.replace(/\)/g, ' ) ');
                var varExpresion = expresion.split(" ");
                var indice = idfield.split(".-.");
                indice[1] = indice[1].replace(/_AjaxDhtml/gi, '');
                for (var ind = 0; ind < varExpresion.length; ind++)
                {
                    var expresion = varExpresion[ind];
                    if (column == expresion) ShowCalc = true;
                    expresion = expresion.replace(/ /g, "");
                    if (expresion != '')
                    {
                        var puede = 0;
                        for (var ni in aColumns) if (aColumns[ni]["strfieldName"] == expresion) puede = 1;
                        if (puede == 1)
                        {
                            var expresiones = DG_gvv("i" + ac() + expresion + ".-." + indice[1]);
                            if (DG_gvv('DGdecimalPoint' + ac()) == '.')
                            {
                                expresiones = expresiones.replace(/\,/g, '');
                                expresiones = expresiones.replace(/\./g, '.')
                            }
                            else
                            {
                                expresiones = expresiones.replace(/\./g, '');
                                expresiones = expresiones.replace(/\,/g, '.')
                            };
                            expresiones = expresiones.replace(/ /g, '');
                            if (expresiones == "" || expresiones == " " || expresiones == "&nbsp;") expresiones = 0;
                            expresion = " " + expresion + " ";
                            expresionToCalc = expresionToCalc.replace(expresion, expresiones)
                        }
                    }
                };
                if (ShowCalc)
                {
                    try
                    {
                        arrRows = DG_gvv('DGCalcRows' + ac()).split(',');
                        dato = eval(expresionToCalc);
                        switch (DG_gvv('DGdecimals' + ac()))
                        {
                            case 1:
                                vr = 10;
                                break;
                            case 3:
                                vr = 1000;
                                break;
                            case 4:
                                vr = 10000;
                                break;
                            default:
                                vr = 100;
                                break
                        };
                        dato = Math.round(dato * vr) / vr;
                        updField = aColumns[actualitem]["strfieldName"] + ac() + ".-." + indice[1];
                        DG_goo(updField).style.color = DG_gvv('DGdgAjaxChanged' + ac());
                        arrTot = DG_gvv("DGtotalizar" + ac()).split(",");
                        if (DG_gvv('DGthereisTotal' + ac()) == 0 && arrTot.DG_inArray(column))
                        {
                            var nt = 0;
                            for (var n = 0; n < arrRows.length; n++)
                            {
                                valCell = DG_gvv("i" + ac() + column + ".-." + arrRows[n]);
                                valCell = parseFloat(valCell);
                                if (isNaN(valCell)) valCell = 0;
                                nt += valCell
                            };
                            DG_ajaxLoader(DG_gvv('DGscrName' + ac()), 'ajaxDHTMLDiv' + ac(), '9&dgrtd' + ac() + '=' + column + ".-.Total&dgnt" + ac() + "=" + nt + "&dgvcode" + ac() + "=" + DG_gvv('DGparams' + ac()) + "&tAjax=" + Math.random(), column + ac() + ".-.Total", DG_gvv('DGtxtSaving' + ac()), "updResult('" + updField + "','" + dato + "');")
                        }
                        else
                        {
                            updResult(updField, dato)
                        }
                    }
                    catch (e)
                    {
                        alert(e)
                    }
                }
            }
        }
    }
    else
    {
        arrRows = DG_gvv('DGCalcRows' + ac()).split(',');
        var indice = idfield.split(".-.");
        indice[1] = indice[1].replace(/_AjaxDhtml/gi, '');
        for (actualitem in aColumns)
        {
            updField = aColumns[actualitem]["strfieldName"] + ac() + ".-." + indice[1];
            arrTot = DG_gvv("DGtotalizar" + ac()).split(",");
            if (DG_gvv('DGthereisTotal' + ac()) == 0 && arrTot.DG_inArray(column))
            {
                var nt = 0;
                try
                {
                    for (var n = 0; n < arrRows.length; n++)
                    {
                        valCell = DG_gvv("i" + ac() + column + ".-." + arrRows[n]);
                        valCell = parseFloat(valCell);
                        if (isNaN(valCell)) valCell = 0;
                        nt += valCell
                    };
                    DG_ajaxLoader(DG_gvv('DGscrName' + ac()), 'ajaxDHTMLDiv' + ac(), '9&dgrtd' + ac() + '=' + column + ".-.Total&dgnt" + ac() + "=" + nt + "&dgvcode" + ac() + "=" + DG_gvv('DGparams' + ac()) + "&tAjax=" + Math.random(), column + ac() + ".-.Total", DG_gvv('DGtxtSaving' + ac()))
                }
                catch (e)
                {
                    alert(e)
                }
            }
        }
    }
};
var slideInUse = new Array();

function DG_Slide(objId, options)
{
    this.obj = document.getElementById(objId);
    this.duration = 1;
    this.height = parseInt(DG_getHeightFromCss(this.obj));
    if (typeof options != 'undefined')
    {
        this.options = options
    }
    else
    {
        this.options = {}
    };
    if (this.options.duration)
    {
        this.duration = this.options.duration
    };
    this.swap = function ()
    {
        if (this.obj.style.display == 'block') this.up();
        else this.down()
    };
    this.up = function ()
    {
        this.curHeight = this.height;
        this.newHeight = '1';
        if (slideInUse[objId] != true)
        {
            var finishTime = this.slide();
            window.setTimeout("DG_Slide('" + objId + "').finishup(" + this.height + ");", finishTime)
        }
    };
    this.down = function ()
    {
        this.newHeight = this.height;
        this.curHeight = '1';
        if (slideInUse[objId] != true)
        {
            this.obj.style.height = '1px';
            this.obj.style.display = 'block';
            this.slide()
        }
    };
    this.slide = function ()
    {
        slideInUse[objId] = true;
        var frames = 30 * duration;
        var tIncrement = (duration * 1000) / frames;
        tIncrement = Math.round(tIncrement);
        var sIncrement = (this.curHeight - this.newHeight) / frames;
        var frameSizes = new Array();
        for (var i = 0; i < frames; i++)
        {
            if (i < frames / 2)
            {
                frameSizes[i] = (sIncrement * (i / frames)) * 4
            }
            else
            {
                frameSizes[i] = (sIncrement * (1 - (i / frames))) * 4
            }
        };
        for (var i = 0; i < frames; i++)
        {
            this.curHeight = this.curHeight - frameSizes[i];
            window.setTimeout("document.getElementById('" + objId + "').style.height='" + Math.round(this.curHeight) + "px';", tIncrement * i)
        };
        window.setTimeout("delete(slideInUse['" + objId + "']);", tIncrement * i);
        if (this.options.onComplete)
        {
            window.setTimeout(this.options.onComplete, tIncrement * (i - 2))
        };
        return tIncrement * i
    };
    this.finishup = function (height)
    {
        this.obj.style.display = 'none';
        this.obj.style.height = height + 'px'
    };
    return this
};

function DG_getHeightFromCss(tdObj)
{
    var height = '';
    if (tdObj.currentStyle)
    {
        height = tdObj.currentStyle.height
    }
    else if (document.defaultView.getComputedStyle)
    {
        height = document.defaultView.getComputedStyle(tdObj, '').height
    };
    if (height == 'auto')
    {
        tdObj.style.height = tdObj.offsetHeight + "px";
        height = tdObj.offsetHeight
    }
    return height
};

function DG_saveParameters()
{
    var theDiv = "dgDiv" + ac();
    var dgvcode = dgrtd = wae = "";
    var p = DG_gvv('DGscrName' + ac());
    var DG_ajaxid = 1;
    var pgNumber = DG_gvv("dg_r" + ac());
    var vOrder = DG_gvv("dg_order" + ac());
    var oe = DG_gvv("dg_oe" + ac());
    var ss = DG_gvv("dg_ss" + ac());
    var schrstr = DG_gvv("dg_schrstr" + ac());
    var selected_checkboxes = selected_checks();
    parametersAjax = "dg_r" + ac() + "=" + pgNumber + "&dg_order" + ac() + "=" + vOrder + "&dg_oe" + ac() + "=" + oe + "&dg_ss" + ac() + "=" + ss + "&dg_schrstr" + ac() + "=" + schrstr + "&dg_nrpp" + ac() + "=" + DG_gvv('DG_nrpp' + ac()) + "&dgrtd" + ac() + "=" + dgrtd + "&dgvcode" + ac() + "=" + dgvcode + "&chksel" + ac() + "=" + selected_checkboxes + DG_gvv('DGparams' + ac());
    n = DG_gvv('DGscrName' + ac()).split(".");
    Set_Cookie("DG_parameters" + n[0], parametersAjax, 99999)
};

function DG_getCookie(name)
{
    var cname = name + "=";
    var dc = document.cookie;
    if (dc.length > 0)
    {
        begin = dc.indexOf(cname);
        if (begin != -1)
        {
            begin += cname.length;
            end = dc.indexOf(";", begin);
            if (end == -1) end = dc.length;
            return unescape(dc.substring(begin, end))
        }
    };
    return ""
};

function Set_Cookie(name, value, expires, path, domain, secure)
{
    var today = new Date();
    today.setTime(today.getTime());
    if (expires) expires = expires * 1000 * 60 * 60 * 24;
    var expires_date = new Date(today.getTime() + (expires));
    document.cookie = name + "=" + escape(value) + ((expires) ? ";expires=" + expires_date.toGMTString() : "") + ((path) ? ";path=" + path : "") + ((domain) ? ";domain=" + domain : "") + ((secure) ? ";secure" : "")
};

function eraseCookie(name)
{
    Set_Cookie(name, "", -1)
};

function closeColumnMenu()
{
    DG_hss(DG_gvv('DG_uac' + ac()), 'none');
    DG_hss('a' + DG_gvv('DG_uac' + ac()), 'block')
};

function viewColumnOptions(id, e, nombre, yOffset)
{
    if (id != DG_gvv('DG_uac' + ac())) closeColumnMenu();
    if (typeof (yOffset) == 'undefined') yOffset = 0;
    cal_old_mousedown = document.body.onmousedown;
    document.body.onmousedown = "";
    MM_nombreDiv = "DGdivCalendar" + ac();
    MM_mostrar(MM_nombreDiv, e, '', yOffset);
    setColData(nombre)
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

//ID 11,        "11", "111", ""
//ID 1,         "1", "11", ""
function DG_viewdetails(id, node, parameters)
{
    program = DG_gvv('DGdetails' + ac()).split(",");
    amount = program.length;
    if (amount > 1)
    {
        alert('does not hit here');
        if (!DG_isdefined(DG_goo('DG_det_' + node + '1')))
        {
            content = "";
            style = "";
            theClass = 'current';
            inner = "<ol class=\"dgTabs\">";
            for (n = 1; n <= amount; n++)
            {
                inner = inner + "<li class=\"" + theClass + "\">" + "<a id=\"DG_det_" + node + n + "a\" href=\"javascript:setGrid('DG_det_" + node + "','" + n + "', '" + amount + "')\">Details " + n + "</a></li>";
                content = content + "<div id=\"DG_det_" + node + n + "\" class=\"dgTabsContent\" " + style + "></div>";
                style = "style=\"display:none\"";
                theClass = ""
            }
            inner = inner + "</ol>";
            document.getElementById('DG_det_' + node).innerHTML = inner + content
        }
    }
    else
    {
        //alert('1');
        document.getElementById('DG_det_' + node).innerHTML = "<div id=\"DG_det_" + node + "-1\" class=\"dgTabsContent\">WE SHOULD INSERT HERE</div>"
        //document.getElementById('DG_det_' + node).innerHTML = "<div id=\"DG_det_" + node + "1\" class=\"dgTabsContent\"></div>"
    };
    DG_hss("openNode_" + node, "none");     // hide the + icon
    DG_hss("closeNode_" + node, "block");   // enable the - icon

    // calculate what needs to be shown
    wae = ((amount > 1) ? "DG_processNextGrid(" + id + ", " + node + ", '" + parameters + "', '" + program.slice(1).join(",") + "',1);" : "") + "addLineNode(" + node + ")";
    //alert(node);
    //DG_ajaxLoader(program[0], "DG_det_" + node + "1", "&dg_det_id=" + id + "&" + parameters + "&tAjax=" + Math.random(), "DG_det_" + node + "1", DG_gvv('DGtxtLoading' + ac()), wae)
    //function DG_ajaxLoader(programa, id, parametros, displayid, text, whenAjaxEnd, o)
    DG_ajaxLoader
    (
        program[0],                                 // program
        "DG_det_" + node,// + "1",                          // id       , BUG in original script
        "&dg_det_id=" + id + "&" + parameters + "&tAjax=" + Math.random(),      // parameter
        "DG_det_" + node,// + "1",                          // displayid    , BUG in original script
        DG_gvv('DGtxtLoading' + ac()),                      // text
        wae                                     // whenAjaxEnd
    )
}

function DM_viewdetails(id)
{
    arrData = id.split("::");
    DG_viewdetails(arrData[0])
};

function addLineNode(node)
{
    obj = document.getElementById("DG_det_a_" + node);
    height = parseInt(parseInt(DG_getHeightFromCss(obj.parentNode)) / 2);
    obj.innerHTML = '<div class="dg_node" style="height:' + height + 'px;"></div></div>'
}

function DG_processNextGrid(id, node, parameters, program, c)
{
    c++;
    program = program.split(",");
    amount = program.length;
    wae = (amount > 1) ? "DG_processNextGrid(" + id + ", " + node + ", '" + parameters + "', '" + program.slice(1).join(",") + "', " + c + ");" : "";
    DG_ajaxLoader(program[0], "DG_det_" + node + c, "&dg_det_id=" + id + "&" + parameters + "&tAjax=" + Math.random(), "DG_det_" + node + c, DG_gvv('DGtxtLoading' + ac()), wae)
}

function setGrid(id, tab, total)
{
    for (x = 1; x <= total; x++)
    {
        DG_hss(id + x.toString(), "none");
        DG_goo(id + x.toString() + "a").parentNode.className = ""
    }
    DG_hss(id + tab, "block");
    DG_goo(id + tab.toString() + "a").parentNode.className = "current"
}

function DG_nodeShow(id)
{
    DG_hss("openNode_" + id, "block");
    DG_hss("closeNode_" + id, "none");
    DG_sii("DG_det_a_" + id, "");
    obj = document.getElementById("DG_det_a_" + id).style.height = "auto";
    obj = document.getElementById("DG_det_a_" + id).parentNode.style.height = "auto"
};

function setColData(nombre)
{
    imgpath = DG_gvv('DGimgpath' + ac());
    var myFields = DG_gvv("dg_allowhide" + ac()).split(",");
    var strOutput = '<table id="tableCalendar">';
    var tac = DG_gvv('dg_ta_columns' + ac());
    var n = DG_gvv('DGscrName' + ac()).split(".");
    eval("var aColumns=" + tac);
    var colum = DG_getCookie("DG_columns" + n[0]);
    Set_Cookie("TMP_columns" + n[0], colum, 99999);
    var colum = (colum == null) ? "" : colum;
    var columnas = colum.split(",");
    strOutput += '<tr><td colspan="2" align="right" class="closeCell"><span>' + nombre + '</span><a href="javascript:applyColData()"><img src="' + imgpath + DG_gvv('DGimgClose' + ac()) + '" border="0"><\/a><\/td></tr>';
    for (i = 0; i < myFields.length; i++)
    {
        var aColumn = aColumns[myFields[i]];
        var nCampo = aColumn['strHeader'];
        var checked = (columnas.DG_inArray(myFields[i])) ? "" : " checked='checked' ";
        strOutput += '<tr class="rColumn"><td class="fColumns" align="left" colspan="2">' + '<label onclick="setColumn(\'' + myFields[i] + '\', DG_gcc(\'id' + myFields[i] + '\'))">' + '<input id=\'id' + myFields[i] + '\' type="checkbox" value="' + myFields[i] + '" ' + checked + '>' + nCampo + '</label><\/td><\/tr>'
    };
    strOutput += '<\/table>';
    document.getElementById("DGdivCalendar" + ac()).innerHTML = strOutput
};

function applyColData()
{
    closeCalendar();
    var n = DG_gvv('DGscrName' + ac()).split(".");
    var colum = DG_getCookie("TMP_columns" + n[0]);
    var column = DG_getCookie("DG_columns" + n[0]);
    if (colum != column) DG_Do('apply')
};

function cancelColData(dc)
{
    if (!DG_isdefined(dc))
    {
        var n = DG_gvv('DGscrName' + ac()).split(".");
        var colum = DG_getCookie("TMP_columns" + n[0]);
        Set_Cookie("DG_columns" + n[0], colum, 99999)
    };
    closeCalendar()
};

function setColumn(column, checked)
{
    var n = DG_gvv('DGscrName' + ac()).split(".");
    var columnas = DG_getCookie("DG_columns" + n[0]).split(",");
    var auxColumna = Array();
    var existe = false;
    if (columnas.length > 0 && columnas[0].length > 0)
    {
        for (var i = 0; i < columnas.length; i++)
        {
            if (column == columnas[i])
            {
                existe = true;
                if (checked == 0)
                {
                    if (!auxColumna.DG_inArray(column)) auxColumna[auxColumna.length] = column
                }
            }
            else
            {
                if (!auxColumna.DG_inArray(columnas[i])) auxColumna[auxColumna.length] = columnas[i]
            }
        }
    };
    if (!existe)
    {
        if (!auxColumna.DG_inArray(column)) auxColumna[auxColumna.length] = column
    };
    Set_Cookie("DG_columns" + n[0], auxColumna, 99999)
};
var wae = "";
var DG_capa = null;
var difX = 0;
var difY = 0;
var afterAction = '';
var debug = false;

function DG_kp_input(e)
{
    return DG_bl_enter(e, "DG_svv('aux_pg' + ac(),1)")
};

function DG_SetFavorite(FavStatus, obj, action)
{
    var myID = obj.id;
    arrID = myID.split("icn_" + ac());
    var thID = arrID[1].split(".-.");
    DG_Do(action, myID + ".-." + FavStatus, thID[0] + ac() + '.-.' + thID[1])
};

function DG_updateNested(value, target)
{
    DG_goo("dgFld" + target).disabled = true;
    DG_Do(13, target + "&DG_nestedValue=" + value, "ajaxDHTMLDiv" + ac());
    return true
};

function DG_tmpUpload(field, value, form)
{
    DG_hss("uplimg_" + form, "inline");
    DG_goo("up" + form).submit()
}

function DG_edit_uploadedImg(id)
{
    DG_hss("dgFld_file", "inline");
    DG_hss("uplstat_" + id, "none")
}

function DG_remove_uploadedImg(id, field)
{
    DG_sii("uplstat_" + id, "");
    DG_hss("dgFld_file", "inline");
    DG_svv("dgFld" + field, "delete")
}

function DG_ltrim(value)
{
    eval("var re = /\s*((\S+\s*)*)/");
    return value.replace(re, "$1")
};

function DG_rtrim(value)
{
    eval("var re = /((\s*\S+)*)\s*/");
    return value.replace(re, "$1")
};

function DG_trim(value)
{
    return DG_ltrim(DG_rtrim(value))
};