<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.js"></script>

<script>
/*function isValidDate(inputDate)
{
    var result = inputDate.search(/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])/);
    return (result >= 0) ? true : false;
}

function checkFormInput()
{
    var start_date = document.getElementById("start_date").value;
    var end_date = document.getElementById("end_date").value;
    var so_number = document.getElementById("so_number").value;
    var message = "";
    
    if ((so_number == "")
        && ((start_date == "") || (end_date == "")))
        message += "You need to put a order number or a date range!\n";
    else if (so_number == "")
    {
        if (!isValidDate(start_date))
            message += "Not a valid start date!\n";
        if (!isValidDate(end_date))
            message += "Not a valid end date!\n";
    }
    if (message != "")
    {
        alert(message);
        return false;
    }
    else
        return true;
}
*/


$(function(){   
    $(".button_edit").click(function(e){
        e.preventDefault();
        $("#flag_type").val("update");
        var this_email_id = $(this).attr("data");
        var this_email_box = $("#e_"+this_email_id);
        var this_form = $("#f_"+this_email_id);
        this_email_box.toggleClass("readonly_box"); 
        

        var this_original_email = this_email_box.attr("data");
        //var this_original_email = this_email_box.val();
        
        var readonly_attr = $(this).attr('readonly');
 
        if(this_email_box.hasClass("readonly_box"))
        {
            this_email_box.attr("readonly", true);
            $(this).text("Edit");
            var edited_email = $("#e_"+this_email_id).val().trim();
            if(edited_email !== this_original_email)
            {
                this_form.submit();
            }
        }
        else
        {
            this_email_box.removeAttr("readonly");
            $(this).text("Update");
        }
    });
    
    $(".button_delete").click(function(e){
        e.preventDefault();
        var this_email_id = $(this).attr("data");
        $("#flag_type_"+this_email_id).val("delete");
        var this_form = $("#f_"+this_email_id);
        if(confirm('Do you want to remove this email?'))
        {
            this_form.submit();
        }
        else
        {
            return false;
        }
        
    });
    
    $("#add_email").click(function(e){
        e.preventDefault();
        var new_email = $("#new_email").val().trim();
        if(new_email == '')
        {
            return false;
        }
        else
        {
            document.forms['fm'].submit();
        }
    });
    
    $("tr:not('#last_row', '#header_row')").mouseenter(function(){
        $(this).addClass('highlight');
        $(this).find('button').css({
            visibility: 'visible'
        });
    }).mouseleave(function(){
        $(this).removeClass('highlight');
        $(this).find('button').css({
            visibility: 'hidden'
        });
    });
    
});
</script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
<style type="text/css">
.contentRow {
    height: 20px;
    background-color: #FFCCFF;
}
.tableField {
    background-color : #666666;
    color: #FFFFFF;
    font-size: 12px;
    font-weight: bold;
}
.readonly_box{
    border:none;
    background-color:transparent;
}
</style>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
    <td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);"><?=$lang["title"]?></b></td>
</tr>
<tr>
    <td height="2" bgcolor="#000033"></td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
<tr height="70" id='header_row'>
    <td align="left" style="padding-left:8px;">
        <b style="font-size: 14px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br>
    </td>
    <td align="right">
<form name="fm" action="<?=base_url()."order/email_referral_list_management/index"?>" method="post">
    <table border="0" cellpadding="3" cellspacing="0" width="400" style="line-height:8px;">
    <col width="140"><col width="160"><col width="40">
    <tr id='header_row'>
        <td align='right'><b><?=$lang["add_new_email"]?>: </b></td>
        <td><input name="new_email" id="new_email" style="width:150px;"></td>
        <td rowspan="2" align="center"><input type="submit" id='add_email' value="Add" style="width:50px;"><input type="hidden" name="add" value="1"> &nbsp; </td>
    </tr>
    </table>
</form>
    
    </td>
</tr>
<tr>
    <td height="2" bgcolor="#000033" colspan="3"></td>
</tr>
</table>
<?=$notice["js"]?>
<table cellpadding='3' cellspacing='0' border='1' width="100%" style="border-collapse:collapse;">
<?php
$total_number_of_records = "";
//print heading
        print "<tr bgcolor='#000000'>";
        //print "<td height='20' width='20' class='tableField'>&nbsp;</td>";
        print "<td class='tableField'>" . $lang['client_id'] . "</td>";
        print "<td class='tableField'>" . $lang['Email'] . "</td>";
        print "<td class='tableField'>" . $lang['client_name'] . "</td>";
        print "<td class='tableField'>" . $lang['ip_address'] . "</td>";
        print "<td class='tableField'>" . $lang['address'] . "</td>";
        print "<td class='tableField'>" . $lang['postal_code'] . "</td>";
        //print "<td class='tableField' align='center'>" . $lang['edit'] . "</td>";
        //print "<td class='tableField' align='center'>" . $lang['delete'] . "</td>";
        print "<td class='tableField' align='center'> </td>";
        print "</tr>";
//content
        $order_number = 1;
        $current_so = "";
        $rowcount = 0;
    
//print heading
        //var_dump($email_referral_list);die();
        foreach($email_referral_list as $per_email_info)
        {
            $row_style = "row".$rowcount%2;
?>          
            <form id="f_<?=$per_email_info->get_id()?>" action="<?=base_url()."order/email_referral_list_management/index/{$per_email_info->get_id()}"?>"  method="post">
            <tr class="<?=$row_style?>" name = "row<?=$rowcount?>">
            <td><?=$per_email_info->get_client_id()?></td>
            <td><input name = 'email' class='readonly_box' id='<?='e_'.$per_email_info->get_id()?>' type='text' value="<?=$per_email_info->get_email()?>" readonly style="width:200px" data="<?=$per_email_info->get_email()?>"></td>
            <td><?=$per_email_info->get_surname().' '.$per_email_info->get_forename()?></td>
            <td><?=$per_email_info->get_create_at()?></td>
            <!-- <td><?=$per_email_info->get_address_1().','.$per_email_info->get_address_2().','.$per_email_info->get_address_3()?></td> -->
            <td><?=$per_email_info->get_address()?></td>
            <td><?=$per_email_info->get_postcode()?></td>
            <td style='width:120px;text-align:center' >
            <button class="button_edit" style='visibility:hidden' data='<?=$per_email_info->get_id()?>' >Edit</button>
            <button class="button_delete" style='visibility:hidden' data='<?=$per_email_info->get_id()?>' >Delete</button>
            </td>

            <input id='flag_type_<?=$per_email_info->get_id()?>' type="hidden" name="post" value="update">
            </tr>
            </form>
<?          
            $rowcount++;
        }
?>
<tr id="last_row">
    <td colspan='8'>
        <form action="<?=base_url()."order/email_referral_list_management/export_csv"?>" method='POST'>
        <button>Generate List</button>
        </form>
    </td>
</tr>
</table>

<?=$this->pagination_service->create_links_with_style()?>
</div>
</div>

</body>
</html>
