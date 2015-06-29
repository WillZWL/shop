<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/calstyle.css">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/warehouse/js_warehouse"></script>
<script language="javascript">
<!--
function changeRowStatus(row)
{
    if(document.fm.elements["deny["+row+"]"].checked)
    {
        document.fm.elements["sbdate["+row+"]"].disabled = true;
        document.fm.elements["sbwh["+row+"]"].disabled = true;
        document.fm.elements["ritem["+row+"]"].disabled = true;
        document.fm.elements["selectdate["+row+"]"].disabled = true;
        document.fm.elements["denyitem["+row+"]"].value = 0;
    }
    else
    {
        document.fm.elements["sbdate["+row+"]"].disabled = false;
        document.fm.elements["sbwh["+row+"]"].disabled = false;
        document.fm.elements["ritem["+row+"]"].disabled = false;
        document.fm.elements["selectdate["+row+"]"].disabled = false;
        document.fm.elements["denyitem["+row+"]"].value = 1;
    }
}

function drawWHList()
{
    for(var i in warehouse)
    {
        document.write("<option value='"+warehouse[i][0]+"'>"+warehouse[i][1]+"</option>");
    }
}

function showHide(elem)
{
    var vis =  document.getElementById(elem).style.display;
    document.getElementById(elem).style.display = (vis == "block"?"none":"block");
}

function valDate(elem) {
  var dateExp = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((1[6-9]|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((1[6-9]|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((1[6-9]|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$|^00\/00\/0000$/;
  var isvalid = 1;
  if (elem.value.match(dateExp)){
    var today= new Date();
    var todayDate = today.getDate();
    var todayMonth = today.getMonth() + 1;
    var todayYear = today.getFullYear();
    var dateObj = getFieldDate(elem.value);
    if (dateObj.getFullYear() > todayYear) isvalid = 0;
    else if (dateObj.getFullYear() == todayYear && dateObj.getMonth()+1 > todayMonth) isvalid = 0;
    else if (dateObj.getFullYear() == todayYear && dateObj.getMonth()+1 == todayMonth && dateObj.getDate() > todayDate) isvalid = 0;
  } else isvalid = 0;

  if (isvalid == 0){
      x=0; targetBg = elem; timer=setInterval("fader()",100);  // alert user
      elem.value = "";
//      var today= new Date();
//      var todayDate = today.getDate();
//      var todayMonth = today.getMonth() + 1;
//      var todayYear = today.getFullYear();
//      if(todayDate<10) todayDate="0"+todayDate;
//      if(todayMonth<10) todayMonth="0"+todayMonth;
//      elem.value = todayDate + "/" + todayMonth + "/" + todayYear;
      return false;
   } else return true;
}


-->
function showCodIndicator(isCod)
{
    if (isCod)
    {
        alert("This is COD order, please pay attention!");
        list_of_td = document.getElementsByTagName("td");
        for (i=0;i<list_of_td.length;i++)
        {
            if (list_of_td[i].className == "value")
                list_of_td[i].className = "value_red";
        }
    }
}
</script>
</head>
<body onload="showCodIndicator(<?=$isCod?>)">
<div id="main" bgcolor="#ddddff">
<?=$notice["image"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
    </tr>
</table>
<!-- recent history -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
<tr>
    <td align="left" height="20" style="padding-left:10px;"><a style="font-size:12px; color:#ffffff; font-weight:bold; cursor:pointer;" onClick="showHide('p1');"><?=$lang["refund_history"]." ".$orderid?></a></td>
</tr>
</table>
<div id="p1" style="width:100%; display:block;">
<table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#cccccc">
<col width="20"><col width="150"><col width="100"><col width="250"><col width=""><col width="120"><col width="20">

<?php
    if(count($history))
    {
?>
<tr class="header">
    <td height="20">&nbsp;</td>
    <td ><?=$lang["date"]?></td>
    <td ><?=$lang["status"]?></td>
    <td ><?=$lang["reason"]?></td>
    <td ><?=$lang["notes"]?></td>
    <td ><?=$lang["process_by"]?></td>
    <td ><?=$lang["approval_status"]?></td>
    <td>&nbsp;</td>
</tr>
<?php
        $processed = 0;
        $i = 0;
        foreach($history as $obj)
        {
            if($obj->get_status() == 'N')
            {
                $cobj = clone $obj;
            }
            if($obj->get_status() != 'N')
            {
                $processed = 1;
            }
?>
<tr class="row<?=$i%2?>">
    <td>&nbsp;</td>
    <td ><?=$obj->get_create_on()?></td>
    <td ><?=$lang["ristatus"][$obj->get_status()]?></td>
    <td ><?=$lang["rcategory"][$obj->get_reason_cat()]." - ".$obj->get_description()?></td>
    <td ><?=$obj->get_notes()?></td>
    <td ><?=$obj->get_name()?></td>
    <td ><?=$lang["app_status"][$obj->get_app_status()]?></td>
    <td>&nbsp;</td>
</tr>
<?php
            $i++;
        }
    }
    else
    {
?>
<tr class="row0">
    <td align="center" colspan="7" height="20"><?=$lang["no_history"]?></td>
</tr>
<?php
    }
?>
</table>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
<tr height="20">
    <td>&nbsp;</td>
</tr>
</table>
<!-- Order information -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
<tr>
    <td align="left" height="20" style="padding-left:10px;"><a style="font-size:12px; color:#ffffff; font-weight:bold; cursor:pointer;"  onClick="showHide('p2');"><?=$lang["order_information"]?></a></td>
</tr>
</table>
<div id="p2" style="width:100%; display:block;">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
<tr>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_number"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_so_no()?></td>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_status"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$lang["so_status"][$orderobj->get_status()]?></td>
</tr>
<tr>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["platform"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$lang["so_platform"][$orderobj->get_platform_id()]?></td>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_amount"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".number_format(($orderobj->get_amount() - $orderobj->get_delivery_charge()),2)?></td>
</tr>
<?php
    if(ereg('^WS',$orderobj->get_platform_id()))
    {
?>
<tr>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["biztype"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_biz_type()?></td>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_delivery_charge"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".number_format($orderobj->get_delivery_charge(),2)?></td>
</tr>
<?php
    }

    else
    {
?>
<tr>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["platform_order_id"]?></td>
    <td width="35%"align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_platform_order_id()?></td>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_delivery_charge"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".$orderobj->get_delivery_charge()?></td>
</tr>
<?php
    }
?>
<tr>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["client_id_and_name"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_client_id()." - ".$orderobj->get_bill_name()?></td>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_total"]?></td>
    <td width="35%" align="left" class="value" style="padding-left:10px;"><?=$orderobj->get_currency_id()." ".number_format(($orderobj->get_amount()),2)?></td>
</tr>
<tr>
    <td width="15%" height="20" align="right" class="field" style="padding-right:10px;"><?=$lang["order_detail"]?></td>
    <td width="85%" colspan="3" align="left" class="value">
    <table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
    <col width="20"><col width="100"><col><col width="100"><col width="150"><col width="100"><col width="20">
    <tr class="header">
        <td height="20">&nbsp;</td>
        <td style="padding-left:4px;"><?=$lang["item_sku"]?></td>
        <td style="padding-left:4px;"><?=$lang["prod_name"]?></td>
        <td style="padding-left:4px;"><?=$lang["original_price"]?></td>
        <td style="padding-left:4px;"><?=$lang["discounted_price"]?></td>
        <td style="padding-left:4px;"><?=$lang["gst"]?></td>
        <td style="padding-left:4px;"><?=$lang["purchase_qty"]?></td>
        <td height="20">&nbsp;</td>
    </tr>
<?php
    $i = 0;
    foreach($order_item_list as $obj)
    {
?>
    <tr height="20" class="row<?=$i%2?>">
        <td></td>
        <td style="padding-left:10px;"><?=$obj->get_item_sku()?></td>
        <td style="padding-left:10px;"><?=$obj->get_name()?></td>
        <td style="padding-left:10px;"><?=number_format($obj->get_unit_price() / (1 - $obj->get_discount() / 100),2)?></td>
        <td style="padding-left:10px;"><?=$obj->get_unit_price()?></td>
        <td style="padding-left:10px;"><?=$obj->get_gst_total()?></td>
        <td style="padding-left:10px;"><?=$obj->get_qty()?></td>
        <td></td>
    </tr>
<?php
        $i++;
    }
?>
    </table>
    </td>
</tr>
</table>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
<tr height="20">
    <td>&nbsp;</td>
</tr>
</table>
<!-- setting up refund request -->
<form name="fm" method="POST" onSubmit="CheckForm(this);" >
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
<tr>
    <td align="left" height="20" style="padding-left:10px;"><font style="font-size:12px; color:#ffffff; font-weight:bold;"><?=$lang["refund_detail"]." ".$refund_obj->get_id()?></font></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
<col width="8"><col width="100"><col width="200"><col><col width="100"><col width="150"><col width="80"><col width="100"><col width="150"><col width="100"><col width="60">
<tr class="header">
    <td height="20">&nbsp;</td>
    <td style="padding-left:4px;"><?=$lang["item_sku"]?></td>
    <td style="padding-left:4px;"><?=$lang["prod_name"]?></td>
    <td style="padding-left:4px;"><?=$lang["reason_comment"]?></td>
    <td style="padding-left:4px;"><?=$lang["refund_status"]?></td>
    <td style="padding-left:4px;"><?=$lang["request_date"]?></td>
    <td style="padding-left:4px;"><?=$lang["qty"]?></td>
    <td style="padding-left:4px;"><?=$lang["item_status"]?></td>
    <td style="padding-left:4px;"><?=$lang["stock_back_date"]?></td>
    <td style="padding-left:4px;"><?=$lang["stock_back_location"]?></td>
    <td style="padding-left:4px;"><?=$lang["deny_refund"]?></td>
    <td>&nbsp;</td>
</tr>
<?php
    $i = 0;
    foreach($itemlist as $obj)
    {
?>
<tr class="row<?=$i%2?>">
    <td height="20"></td>
    <td style="padding-left:4px;"><?=$obj->get_item_sku()?><input type="hidden" name="rsku[<?=$i?>]" value="<?=$obj->get_item_sku()?>" DISABLED></td>
    <td style="padding-left:4px;"><?=$obj->get_name()?></td>
    <td style="padding-left:4px;"><?=$lang["rcategory"][$cobj->get_reason_cat()].":".$cobj->get_description()?><br><?="[".$cobj->get_name()."]".htmlspecialchars($cobj->get_notes())?></td>
    <td style="padding-left:4px;"><?=$lang["ristatus"][$obj->get_status()]?></td>
    <td style="padding-left:4px;"><?="[".$obj->get_username()."] ".$obj->get_create_on()?></td>
    <td style="padding-left:4px;"><?=$obj->get_qty()?></td>
    <td style="padding-left:4px;"><select name="ritem[<?=$obj->get_line_no()?>]" class="input"><option value="N"><?=$lang["new"]?></option><option value="U"><?=$lang["used"]?></option><option value="M"><?=$lang["missing_item"]?></option></select></td>
    <td style="padding-left:4px;"><input name="sbdate[<?=$obj->get_line_no()?>]" type="text" style="input" readonly notEmpty value="<?=date("d/m/Y")?>">&nbsp;<input type='button' name='selectdate[<?=$obj->get_line_no()?>]' onclick=displayDatePicker('sbdate[<?=$obj->get_line_no()?>]') value="<?=$lang["select_date"]?>" >
</td>
    <td style="padding-left:4px;"><select name="sbwh[<?=$obj->get_line_no()?>]" class="input"><script language="javascript">drawWHList();</script></select></td>
    <td style="padding-left:4px;"><input name="deny[<?=$obj->get_line_no()?>]" type="checkbox" onClick="changeRowStatus('<?=$obj->get_line_no()?>')"><input type="hidden" name="denyitem[<?=$obj->get_line_no()?>]" value="1"></td>
    <td ></td>
</tr>
<?php
        $i++;
    }
?>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc">
<tr>
    <td width="30%" height="20" align="right" style="padding-right:10px;" class="field"><?=$lang["refund_notes"]?></td>
    <td width="70%" align="left" style="padding-left:10px;" class="value"><textarea name="rnotes" class="input" rows="5"><?=htmlspecialchars($this->input->post('rnotes'))?></textarea></td>
</tr>
<tr height="25">
    <td width="30%" height="20" align="right" style="padding-right:10px;" class="field">&nbsp;</td>
    <td width="70%" align="left" style="padding-left:10px;" class="value">&nbsp;<input type="button" onClick="if(CheckForm(this.form)) document.fm.submit();" value="<?=$lang["submit_form"]?>">&nbsp;&nbsp&nbsp;<input type="reset" value="<?=$lang["reset_form"]?>"></td>
</tr>
</table>
<input type="hidden" name="refundid" value="<?=$refund_obj->get_id()?>">
<input type="hidden" name="posted" value="1">
</form>
<table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
<tr height="30" bgcolor="#000033">
    <td style="padding-left:10px;"><input type="button" value="<?=$lang["back_to_list"]?>" onClick="Redirect('<?=base_url()."cs/refund/logistics/?".$_SESSION["QUERY_STRING"]?>')"></td>
</tr>
<tr height="20">
    <td>&nbsp;</td>
</tr>
</table>
</div>
<?=$notice["js"]?>
</body>
</html>