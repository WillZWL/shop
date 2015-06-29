<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/calstyle.css">

<style>
    .offield:hover
    {
        cursor:pointer;
    }
</style>

<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>supply/supplier_helper/js_supplist"></script>
<script type="text/javascript" src="<?=base_url()?>js/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/warehouse/js_warehouse"></script>

<script language="javascript">
<!--
var rowIndex = <?=count($po_item_list) + 1?> ;

var addok = <?=$po_obj->get_status() == "N"?1:0?>;

function addRow(id, sku, name)
{
    if(addok == 1)
    {
        var x=document.getElementById(id).insertRow(rowIndex);

        var a=x.insertCell(0);
        var b=x.insertCell(1);
        var c0=x.insertCell(2);
        var c=x.insertCell(3);
        var d=x.insertCell(4);

        a.setAttribute("class","value");
        b.setAttribute("class","value");
        c0.setAttribute("class","value");
        c.setAttribute("class","value");
        d.setAttribute("class","value");

        var varcell = 'wh';
        var k = 0;
        for( k in warehouse)
        {
            varcell = varcell + k;
            pos = 4+k*1;
            varcell = x.insertCell(pos);
            varcell.setAttribute("class","value");
            varcell.innerHTML = '<input type="text" name="'+ warehouse[k][0].toLowerCase() +'[]" value="0" class="input">';
        }
        a.innerHTML= sku+'<input type="hidden" name="sku[]" value="'+sku+'"><input type="hidden" name="line_number[]" value=""><input type="hidden" name="modify_on[]" value=""><input type="hidden" name="create_on[]" value=""><input type="hidden" name="create_at[]" value=""><input type="hidden" name="create_by[]" value="">';
        b.innerHTML= stripslashes(name);
        c0.innerHTML= '0<input type="hidden" name="shipped_qty[]" value="0">';
        c.innerHTML= '<input type="text" name="qty[]" value="1" isInteger min=0 class="input" onKeyUp="calculatePrice();" isInteger min=1>';
        d.innerHTML= '<input type="text" name="price[]" value="0.00" isNumber min=0 class="input" onKeyUp="calculatePrice();" isNumber min=0>';
        var dpos = 5+k*1;
        var e=x.insertCell(dpos);
        e.setAttribute("class","value");
        e.innerHTML= '<input type=\'checkbox\' onClick=\'CalculatePrice()\' name="delete[]" value="1">';
        rowIndex++;
    }
    else
    {
        alert('<?=$lang["add_not_allowed"]?>');
    }
}

function calculatePrice()
{
    if(document.getElementsByName('qty[]'))
    {
        var pos = '';
        var qty = document.getElementsByName('qty[]');
        var i = qty.length;
        var price = document.getElementsByName('price[]');
        var deleteItem = document.getElementsByName('delete[]');
        var total = 0.00;
        for(var j=0;j < i; j++)
        {

            if(!deleteItem[j].checked)
            {
                total += qty[j].value * price[j].value;
            }
            pos = qty[j];

            if(qty[j].value == 0)
            {
                pos.style.backgroundColor = "#ffff00";
            }
            else
            {
                pos.style.backgroundColor = "#ffffff";
            }
        }
        document.getElementById('total').innerHTML = total.toFixed(2);
        document.getElementById('amount').value = total;
    }
}


function writeSupplier()
{
    for(var i  in supplist)
    {
        if(i == <?=$po_obj->get_supplier_id()?>)
        {
            selected = "SELECTED";
        }
        else
        {
            selected = "";
        }
        document.write('<option value="'+i+'" '+selected+ '>'+supplist[i][0]+'</option>');
    }
}

function getCurrency(id)
{
    if(id != "")
    {
        document.getElementById('scurr').innerHTML = supplist[id][1];
        document.getElementById('scurr2').innerHTML = " - "+supplist[id][1];
        document.getElementById('currency').value = supplist[id][1];
        document.getElementById('sourcing_region').value = supplist[id][2];
    }
}

function stripslashes (str) {
     return (str+'').replace(/\\(.?)/g, function (s, n1) {
        switch (n1) {
            case '\\':
                return '\\';
            case '0':
                return '\0';
            case '':
                return '';
            default:
                return n1;
        }
    });
}

function CheckQty()
{
    var pos = '';
    var objC = '';
    var tobj = '';
    var qty = document.getElementsByName('qty[]');
    var sqty = document.getElementsByName('shipped_qty[]');
    var limit = qty.length;

    for(var i =0; i < limit; i++)
    {
        pos = qty[i];
        order_qty = pos.value;
        spos = sqty[i];
        shipped_qty = spos.value;
        for(var j in warehouse)
        {

            var objC = warehouse[j][0].toLowerCase();
            var tobj = document.getElementsByName(objC+"[]");
            shipped_qty = shipped_qty * 1 + tobj[i].value * 1;


            if(shipped_qty > order_qty)
            {
                alert('<?=$lang["quantity_error"]?>');
                tobj[i].style.backgroundColor = "#ffbb77";
                return false;
            }
            else
            {
                tobj[i].style.backgroundColor = "#ffffff";
            }
        }
    }
    return true;
}

function submitTNT(tracking_no)
{
    document.tnt.QUERY.value = tracking_no;
    document.tnt.submit();
}

function submitFEDEX(tracking_no)
{
    document.fedex.tracknumbers.value = tracking_no;
    document.fedex.submit();
}

function qtyMoveHere(e)
{
    childrenNode = e.parentNode.children;
    var index = Array.prototype.indexOf.call(childrenNode, e);

    var td_number_per_row = childrenNode.length;
        //alert(childrenNode);return  false;

    //alert(index);return false;

    var dynamic_move_qty_list = document.getElementsByClassName("dynamic_move_qty");

    //alert(index);return false;
    var orderedQty = document.getElementsByClassName('order_qty');

//  alert(dynamic_move_qty_list.length);return false;

    for(var i=0; i<dynamic_move_qty_list.length; i++)
    {
        var input_td = dynamic_move_qty_list[i].parentNode;
        same_row_children_elements = input_td.parentNode.children;
        this_element_index = Array.prototype.indexOf.call(same_row_children_elements, input_td);

        if(index != this_element_index)
        {
            dynamic_move_qty_list[i].value=0;
        }
        else
        {
            var row_number = parseInt(i/td_number_per_row);
            this_row_qty = input_td.parentNode.getElementsByClassName("order_qty")[0];
            dynamic_move_qty_list[i].value = this_row_qty.value;
        }

    }
}

function tick_all()
{
    var deleteElements = document.getElementsByName('delete[]');

    if(deleteElements[0].disabled==true)
    {
        alert("Cannot be Selected");
        return false;
    }
    else
    {
        var check_all = false;
        var uncheck_all = false;

        for(var i = 0; i<deleteElements.length; i++)
        {
            if(deleteElements[i].checked != true)
            {
                check_all = true;
                break;
            }
        }

        if(check_all==true)
        {
            for(var i = 0; i<deleteElements.length; i++)
            {
                deleteElements[i].checked = true;
            }
        }
        else
        {
            for(var i = 0; i<deleteElements.length; i++)
            {
                deleteElements[i].checked = false;
            }
        }
    }
}
-->
</script>
</head>
<?php
    $status_array = $lang["status_name"];
    $dlvry_mode = $lang["dlvry_mode"];
    $shipment_status = $lang["shipment_status"];
?>
<body class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["subtitle"]?></td>
    </tr>
    <tr>
        <td height="2" bgcolor="#000033"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["subheader"]?></td>
        <td width="200" valign="top" align="right" style="padding-right:8px">&nbsp;</td>
    </tr>
</table>
<form name="fm" action="<?=base_url()?>order/supplier_order/view_ship/<?=$po_obj->get_po_number()?>" method="POST" onSubmit="CheckForm(this);" target="_parent">
<input type="hidden" name="posted" value="1">
<input type="hidden" name="currency" id="currency" value="<?=$po_obj->get_currency();?>">
<input type="hidden" name="amount" id="amount" value="<?=$po_obj->get_amount();?>">
<input type="hidden" name="sourcing_region" id="sourcing_region" value="">
<input type="hidden" name="po_number" value="<?=$po_obj->get_po_number()?>">
<table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
<tr>
    <td class="field"><?=$lang["po_number"]?></td>
    <td class="value" colspan="3"><?=$po_obj->get_po_number()?></td>
<tr>
    <td class="field fieldwidth"><?=$lang["supplier_invoice_number"]?></td>
    <td class="value valuewidth">
<?php
    if($po_obj->get_status() != "C")
    {
?>
    <input type="text" name="supplier_invoice_number" value="<?=$po_obj->get_supplier_invoice_number()?>" class="input"></td>
<?php
    }
    else
    {
?><?=$po_obj->get_supplier_invoice_number()?><input type="hidden" name="supplier_invoice_number" value="<?=$po_obj->get_supplier_invoice_number()?>"><?php
    }
    ?>


    <td class="field fieldwidth"><?=$lang["supplier"]?></td>
    <td class="value valuewidth">
    <?php
    if($po_obj->get_status() == "N")
    {
    ?>
        <select name="supplier" onChange="getCurrency(this.value);" class="input" notEmpty ><option value=""><?=$lang["please_select"]?></option><script language="javascript">writeSupplier();</script></select>
    <?php
    }
    else
    {
    ?>
        <script language="javascript">document.write(supplist[<?=$po_obj->get_supplier_id()?>][0]);</script><input type="hidden" name="supplier" value="<?=$po_obj->get_supplier_id()?>">
    <?php
    }
    ?>
    </td>
</tr>
<tr>
    <td class="field fieldwidth"><?=$lang["delivery_mode"]?></td>
    <td class="value valuewidth">
<?php
    if($po_obj->get_status() == "N")
    {
?><select name="delivery_mode" class="input" notEmpty><option value="">-- Please Select --</option><?php
        foreach($dlvry_mode as $key=>$value)
        {
?><option value="<?=$key?>" <?=$key==$po_obj->get_delivery_mode()?"SELECTED":""?>><?=$value?></option>
<?php
        }
?></select><?php
    }
    else
    {
        echo $dlvry_mode[$po_obj->get_delivery_mode()];
?>
            <input name="delivery_mode" type="hidden" value="<?=$po_obj->get_delivery_mode()?>">
<?php
    }
?></td>
    <td class="field fieldwidth"><?=$lang["status"]?></td>
    <td class="value valuewidth"><?php
    $eta = "<input name='eta' type='text' style='input' readonly notEmpty value='".date("d/m/y",strtotime($po_obj->get_eta()))."'>&nbsp;<input type='button' name='selectdate' onclick=\"displayDatePicker('eta');\" value='".$lang["select_date"]."' >";
    if($po_obj->get_status() == "N")
    {
?><select name="status"class="input"><option value="N" SELECTED><?=$status_array["N"]?></option><option value="CL"><?=$status_array["CL"]?></option></select><?php
    }
    else
    {
        echo $status_array[$po_obj->get_status()];?><input type="hidden" name="status" value="<?=$po_obj->get_status()?>"><?php
    }
    ?></td>
</tr>
<tr>
    <td class="field fieldwidth"><?=$lang["create_on"]?></td>
    <td class="value valuewidth"><?=$po_obj->get_create_on()?></td>
    <td class="field fieldwidth"><?=$lang["modify_on"]?></td>
    <td class="value valuewidth"><?=$po_obj->get_modify_on()?></td>
</tr>
<tr>
    <td class="field fieldwidth"><?=$lang["create_by"]?></td>
    <td class="value valuewidth"><?=$po_obj->get_create_by()?></td>
    <td class="field fieldwidth"><?=$lang["modify_by"]?></td>
    <td class="value valuewidth"><?=$po_obj->get_modify_by()?></td>
</tr>
<tr>
    <td class="field fieldwidth"><?=$lang["current_total"]?></td>
    <td class="value"><span id="scurr"><?=$po_obj->get_currency()?></span>&nbsp;<span id="total"><?=number_format($po_obj->get_amount(),2);?></span></td>
    <td class="field fieldwidth"><?=$lang["eta"]?></td>
    <td class="value"><?=$eta?></td>
</tr>
</table>
<table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#cccccc">
<tr>
    <td height="30" bgcolor="#333333" style="padding-left:10px;"><font style="color:#ffffff; font-weight:bold; font-size:13px;"><?=$lang["order_detail"]?></font></td>
</tr>
<table>
<table border="0" cellspacing="1" cellpadding="0" width="100%" id="order_form" class="tb_list">
<col width="60"><col><col width="80"><col width="100"><col width="100"><?php
    foreach($wh_list as $obj)
    {
        echo "<col width=\"80\">";
    }
?><col width="40">
<tr class="header2">
    <td class="offield"><?=$lang["sku"]?></td>
    <td class="offield"><?=$lang["prodname"]?></td>
    <td class="offield"><?=$lang["shipped_qty"]?></td>
    <td class="offield"><?=$lang["order_qty"]?></td>
    <td class="offield"><?=$lang["unit_price"]?>&nbsp;<span id="scurr2"> - <?=$po_obj->get_currency()?></span></td>

<?php
    foreach($wh_list as $wobj)
    {
?>
    <td class="offield" onclick="qtyMoveHere(this)"><span><?=$lang["ship_to"]." ".$wobj->get_id()?></span></td>
<?php
    }
?>
    <td class="offield" width="40"><span onclick="tick_all()"><?=$lang["delete"]?></span></td>
</tr>
<?php
    foreach($po_item_list as $obj)
    {
?>
<tr>
    <td class="value"><?=$obj->get_sku()?><input type="hidden" name="sku[]" value="<?=$obj->get_sku()?>"><input type="hidden" name="line_number[]" value="<?=$obj->get_line_number()?>"><input type="hidden" name="modify_on[]" value="<?=$obj->get_modify_on()?>"><input type="hidden" name="create_on[]" value="<?=$obj->get_create_on()?>"><input type="hidden" name="create_at[]" value="<?=$obj->get_create_at()?>"><input type="hidden" name="create_by[]" value="<?=$obj->get_create_by()?>"></td>
    <td class="value" ><?=$obj->get_name()?></td>
    <td class="value"><?=$obj->get_shipped_qty()?><input type="hidden" name="shipped_qty[]" value="<?=$obj->get_shipped_qty()?>"></td>
    <td class="value"><input class='order_qty' type="text" name="qty[]" value="<?=$obj->get_order_qty()?>" class="input" onKeyUp="calculatePrice();" isInteger min=1 <?=($obj->get_order_qty() == $obj->get_shipped_qty()?"READONLY":"")?>></td>
    <td class="value">
<?php
    if($po_obj->get_status() == "N")
    {
?>  <input type="text" name="price[]" value="<?=$obj->get_unit_price()?>" class="input" onKeyUp="calculatePrice()" isNumber min=0></td><?php
    }
    else
    {
?>  <?=$obj->get_unit_price()?><input type="hidden" name="price[]" value="<?=$obj->get_unit_price()?>"></td><?php
    }
?>
<?php
    $wh_cnt = 0;
    foreach($wh_list as $wobj)
    {
?>
    <td class="value">
    <input name="<?=strtolower($wobj->get_id())?>[]" type="text" value="0" class="input dynamic_move_qty" data=<?=$wh_cnt?> isInteger min=0 <?=$obj->get_shipped_qty() == $obj->get_order_qty()?"READONLY":""?>>
    </td>
<?php
        $wh_cnt++;
    }
?>
    <td class="value"><input type="checkbox" name="delete[]" <?=$po_obj->get_status() == "N" && $obj->get_shipped_qty() == 0?"":"DISABLED"?> onClick="calculatePrice();" value="1"></td>
</tr>
<?php
    }
?>
</table>
<table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="#333333">
<tr height="25">
<col width="20%"><col width="60%"><col width="20%">
    <td align="left" style="padding-left:10px;"><input type="button" value="<?=$lang["back"]?>" onClick="parent.location.href = '<?=$_SESSION["SOLISTPAGE"]?>';" class="button" target="_parent"></td>
    <td align="right"><font style="color:#ffffff; font-weight:bold; font-size:11px;"><?=$lang["courier"]?><select name="courier"><option value=""></option><option value="DHL">DHL</option><option value="TNT">TNT</option><option value="FEDEX">FEDEX</option><option value="UPS">UPS</option></select>&nbsp;&nbsp;<?=$lang["tracking_no"]?>&nbsp;<input type="text" name="tracking_no" value="<?=$this->input->post("tracking_no")?>"></font></td>
    <td align="right" height="30" style="padding-right:10px;"><input type="button" value="<?=$lang["submit"]?>" onClick="if(CheckForm(this.form) && CheckQty()) this.form.submit();" class="button"></td>
</tr>
</table>
</form>
<?php
    if($wh_cnt)
    {
?>
<br><br>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tb_pad">
<tr class="header">
    <td align="left" height="30" style="padding-left:10px;"><font style="color:#ffffff; font-weight:bold; font-size:13px;"><?=$lang["shipment_history"]?></font></td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="1" border="0"  class="tb_list">
<col width="100"><col width="80"><col><col width="120"><col width="100"><col width="100"><col width="60"><col width="100"><col width="100"><col width="100">
<tr>
    <td class="field" style="text-align:center;"><?=$lang["shipment_id"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["sku"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["prodname"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["create_on"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["qty_shipped"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["received_qty"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["to_location"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["tracking_no2"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["shipment_status_header"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["reason"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["remarks"]?></td>
    <td class="field" style="text-align:center;"><?=$lang["confirmed_by"]?></td>
</tr>
<?php
        $tracker_head = array(
                        "DHL"=>"<a href='http://www.dhl.co.uk/publish/gb/en/eshipping/international_air.high.html?pageToInclude=RESULTS&type=fasttrack&AWB=[:tracking_no:]' target='DHL'>[:tracking_no:]",
                        "FEDEX"=>"<a href='#' onClick='submitFEDEX(\"[:tracking_no:]\")');'>[:tracking_no:]",
                        "TNT"=>"<a href='#' onClick='submitTNT(\"[:tracking_no:]\")');'>[:tracking_no:]",
                        "UPS"=>"<a href='http://wwwapps.ups.com/WebTracking/track?HTMLVersion=5.0&loc=zh_HK&track.y=10&Requester=TRK_MOD&showMultipiece=N&trackNums=[:tracking_no:]' target='UPS'>[:tracking_no:]"
                        );

        $tracker_tail = array("DHL"=>"</a>",
                          "FEDEX"=>"</a>",
                          "TNT"=>"</a>",
                          "UPS"=>"</a>"
                          );
        foreach($shipment_info as $obj)
        {
            $shipment_id = $obj->get_sid();
            $detail = $obj->get_detail();
            $status = $obj->get_status();
            $remark = $obj->get_remark();
            $info = explode("::",$detail);
            $row = count($info);

            foreach($info as $key=>$value)
            {
                $value_arr = explode('||',$value);
                $confirm_by = $value_arr[7];
                if($obj->get_status() == 'IT')
                {
                    $confirm_by = '';
                }
                else
                {
                    $confirm_by = ($confirm_by?$confirm_by:'system').' on '.$value_arr[8];
                }
?>
<tr>
<?php
                if(!$key)
                {
?>
    <td class="value" rowspan="<?=$row?>"><a href="<?=base_url()?>order/supplier_order/gen_shipment_csv/<?=$shipment_id?>" target="csv_frame"><?=$shipment_id?></a></td>
<?php
                }
?>
    <td class="value"><?=$value_arr[0]?></td>
    <td class="value"><?=$value_arr[1]?></td>
    <td class="value"><?=$value_arr[2]?></td>
    <td class="value"><?=$value_arr[3]?></td>
    <td class="value"><?=$value_arr[4]?></td>
    <td class="value"><?=$value_arr[5]?></td>
<?php
                if(!$key)
                {
                    if($obj->get_tracking_no() == "" || $obj->get_courier() == "")
                    {
                        $track = "";
                    }
                    else
                    {
                        $thead = str_replace("[:tracking_no:]",$obj->get_tracking_no(),$tracker_head[$obj->get_courier()]);
                        $ttail = str_replace("[:tracking_no:]",$obj->get_tracking_no(),$tracker_tail[$obj->get_courier()]);
                        $track = $thead.$ttail;
                    }
?>
    <td class="value" rowspan="<?=$row?>"><?=$obj->get_courier()."<br>".$track?></td>
    <td class="value" rowspan="<?=$row?>"><?=$shipment_status[$status]?></td>
<?php
                }
?>
    <td class="value"><?=$value_arr[6]?></td>
<?php
                if(!$key){
?>
    <td class="value" rowspan="<?=$row?>"><?=$remark?></td>
    <td class="value" rowspan="<?=$row?>"><?=$confirm_by?></td>
<?php
                }
?>
</tr>
<?php

        }
    }
?>
<input type="hidden" value="1" name="posted">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tb_pad header" >
<tr>
    <td width="30">&nbsp;</td>
</tr>
</table>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?}?>
<form name='fedex' target='fedex' method='post' action='http://fedex.com/Tracking'>
<input type='hidden' name='tracknumbers' value=''>
<input type='hidden' name='language' value='english'>
<input type='hidden' name='action' value='track'>
<input type='hidden' name='ascend_header' value='1'>
<input type='hidden' name='mps' value='y'>
<input type='hidden' name='cntry_code' value='gb'>
</form>
<form name='tnt' target='tnt' method='post' action='http://cgi.tnt.co.uk/trackntrace/ConEnquiry.asp' xmlns=''>
<input type='hidden' name='QUERY' value=''>
<input type='hidden' name='TrackChoice' value='consignment'>
</form>
<iframe name="csv_frame" src="" width="0" height="0" frameborder="0" scrolling="no"></iframe>
</div>
<script language="javascript">
document.getElementById('sourcing_region').value= supplist[<?=$po_obj->get_supplier_id()?>][2];
</script>
<?=$notice["js"]?>
</body>
</html>