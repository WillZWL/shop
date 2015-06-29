<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="">
<title><?=$lang["title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url().'css/style.css'?>">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
<style type="text/css">
input.text{width:200px;}
input.button{width:80px;}
.bg0{background-color:#ddddff;}
.bg1{background-color:#eeeeff;}
select{width:300px; }
</style>
<script language="javascript" src="<?=base_url().'js/picklist.js'?>"></script>
</head>
<script type="text/javascript">
function checkBox(chk) {
    chk.checked = true;
}
</script>
<script type="text/javascript">
function checkSelectBox(left, chk) {

    if (left.options.length <= 0)
        return;
    chk.checked = true;
}
</script>

<body topmargin="0" leftmargin="0" onResize="SetFrameFullHeight(document.getElementById('report'));">
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="30" class="title"><b style="font-size:16px;color:#000000"><?=$lang["title"]?></b></td>
</tr>
<tr>
<td height="2" bgcolor="#000033"></td>
</tr>
</table>
<?php
    if($notice["img"] == "")
    {
?>

<form action="<?=base_url()?>marketing/customer_extraction/" name="fm" method="post" style="padding:0; margin:0" onSubmit="return CheckForm(this)">
<input type="hidden" name="id" value="<?=''?>">
<?php
    }
?>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
<tr>
<td height="70" style="padding-left:8px">
<b style="font-size:14px"><?=$lang["header"]?></b><br>
<?=$lang["header_message"]?><br>
</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
<tr class="header">
    <td height="20" width="250">&nbsp;&nbsp;<?=$lang["properties"]?></td>
    <td><font color="#FFFFFF">&nbsp;&nbsp;<?=$lang["assoc_value"]?></font></td>
</tr>
<tr>
    <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?=$lang["platform"]?>
        <input type="checkbox" name="plat_box" value="1" <?php echo $_POST['submit']?"checked":""; if($plat_box==1){echo "checked";} ?>>
    </td>
    <td height="20" align="left" class="value" style="padding:6px;">
        <table border="0" cellpadding="2" cellspacing="1" class="tb_list">
        <tr class="header">
            <th ><font color="#ffffff"><?=$lang["unselected_plat"]?></font></th>
            <th >&nbsp;</th>
            <th ><font color="#ffffff"><?=$lang["selected_plat"]?></font></th>
        </tr>
        <tr>
        <td><select name="full_plat_list[]" id='left' style='width:300px;height:320px' multiple='multiple'>
        <?php
        foreach($platform_in as $k=>$v)
        {
            echo '<option value=\''.$k.'\'>'.$v.'</option>';
        }
        ?>
        </select></td>
        <td align="center" valign="middle">
            <input type="button" value=">" onclick="AddOne(document.getElementById('left'),document.getElementById('right')); checkSelectBox(document.getElementById('right'), document.fm.plat_box)" class="button"><br><br>
            <input type="button" value=">>" onclick="AddAll(document.getElementById('left'),document.getElementById('right')); checkSelectBox(document.getElementById('right'), document.fm.plat_box)" class="button"><br><br><br>
            <input type="button" value="<" onclick="DelOne(document.getElementById('left'),document.getElementById('right'));" class="button"><br><br>
            <input type="button" value="<<" onclick="DelAll(document.getElementById('left'),document.getElementById('right'));" class="button"></td>
        <td>
        <select name="joined_plat_list[]" id='right' style='width:300px;height:320px' multiple='multiple'>
        <?php
        if($platform_ex){
            foreach($platform_ex as $k=>$v)
            {
                echo '<option value=\''.$k.'\'>'.$v.'</option>';
            }
        }
        ?></select>
        </td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?=$lang["purchase_period"]?>
        <input type="checkbox" name="period_box" value="1" <?php echo $_POST['submit']?"checked":""; if($period_box==1){echo "checked";} ?>>
    </td>
    <td height="20" align="left" class="value">
        <div>&nbsp;&nbsp;<b><?=$lang["start_date"]?></b></div>
        <?php $nowdate = date("Y-m-d");?>
        &nbsp;&nbsp;<input name="start_date" value='<?=$_POST['submit']?htmlspecialchars($start_date):trim($nowdate);?>' onchange="checkBox(document.fm.period_box)" notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.start_date, false, false, false, '2010-01-01'); checkBox(document.fm.period_box)" align="absmiddle"><br>
        <div>&nbsp;&nbsp;<b><?=$lang["end_date"]?></b></div>
        &nbsp;&nbsp;<input name="end_date" value='<?=$_POST['submit']?htmlspecialchars($end_date):trim($nowdate);?>' onchange="checkBox(document.fm.period_box)" notEmpty><img src="/images/cal_icon.gif" class="pointer" onclick="showcalendar(event, document.fm.end_date, false, false, false, '2010-01-01'); checkBox(document.fm.period_box)" align="absmiddle">
    </td>
</tr>
<tr>
    <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?=$lang["frequency"]?>
        <input type="checkbox" name="freq_box" value="1" <?php echo $_POST['submit']?"checked":""; if($freq_box==1){echo "checked";} ?>>
    </td>
    <td height="20" align="left" class="value">&nbsp;&nbsp;
    <input type="text" name="frequency" onchange="checkBox(document.fm.freq_box)" value="<?=$_POST['submit']?$_POST['frequency']:'0';?>" notEmpty></td>
</tr>
<tr>
    <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?=$lang["order_value"]?>
        <input type="checkbox" name="order_box" value="1" <?php echo $_POST['submit']?"checked":""; if($order_box==1){echo "checked";}?>>
    </td>
    <td height="20" align="left" class="value">&nbsp;&nbsp;
        <select name="currency" style="width:50px" >
            <option value="gbp">GBP</option>
            <option value="eur">EUR</option>
            <option value="usd">USD</option>
        </select>
        <input type="text" name="order_value" onchange="checkBox(document.fm.order_box)" value="<?=$_POST['submit']?$_POST['order_value']:'0';?>" notEmpty>
    </td>
</tr>
<tr>
    <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?=$lang["prod_cat"]?>
        <input type="checkbox" name="cat_box" value="1" <?php echo$_POST['submit']?"checked":""; if($cat_box==1){echo "checked";} ?>>
    </td>
    <td height="20" align="left" class="value" style="padding:6px;">
        <table border="0" cellpadding="2" cellspacing="1" class="tb_list">
        <tr class="header">
            <th ><font color="#ffffff"><?=$lang["unselected_cat"]?></font></th>
            <th >&nbsp;</th>
            <th ><font color="#ffffff"><?=$lang["selected_cat"]?></font></th>
        </tr>
        <tr>
        <td><select name="full_cat_list[]" id='left_cat' style='width:300px;height:320px' multiple='multiple'>
        <?php
        foreach($category_in as $k=>$v)
        {
            echo '<option value=\''.$k.'\'>'.$v.'</option>';
        }
        ?>
        </select></td>
        <td align="center" valign="middle">
            <input type="button" value=">" onclick="AddOne(document.getElementById('left_cat'),document.getElementById('right_cat')); checkSelectBox(document.getElementById('right_cat'), document.fm.cat_box)" class="button"><br><br>
            <input type="button" value=">>" onclick="AddAll(document.getElementById('left_cat'),document.getElementById('right_cat')); checkSelectBox(document.getElementById('right_cat'), document.fm.cat_box)" class="button"><br><br><br>
            <input type="button" value="<" onclick="DelOne(document.getElementById('left_cat'),document.getElementById('right_cat'));" class="button"><br><br>
            <input type="button" value="<<" onclick="DelAll(document.getElementById('left_cat'),document.getElementById('right_cat'));" class="button">
        </td>
        <td><select name="joined_cat_list[]" id='right_cat' style='width:300px;height:320px' multiple='multiple'>
        <?php
        if($cateogry_ex){
            foreach($category_ex as $k=>$v)
            {
                echo '<option value=\''.$k.'\'>'.$v.'</option>';
            }
        }
        ?></select>
        </td>
        </tr>
        </table>
    </td>
</tr>
</table>
<?php
?>
<table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
<tr>
<?php
    if($notice["img"] == "")
    {
?>

<td align="left" style="padding-left:8px;"><input type="submit" name="submit" value="<?=$lang["generate_csv"]?>" onclick="SelectAllItems(document.fm.elements['joined_cat_list[]']); SelectAllItems(document.fm.elements['joined_plat_list[]']);SelectAllItems(document.fm.elements['full_cat_list[]']); SelectAllItems(document.fm.elements['full_plat_list[]']);"></td>

</tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
<?php
        if($_POST['submit']){
?>
<iframe name="report" id="report" src="<?=$linkto?>" width="1259" noresize frameborder="0" marginwidth="0" marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
<?php
        }
    }
?>
</div>
<?=$notice["js"]?>
</body>
</html>