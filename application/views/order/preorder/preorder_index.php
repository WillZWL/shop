<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
</table>
<form name="fm" method="post">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <col width=70%></col><col width=30%></col>
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message".($platform_id?"":1)]?></td>
        <td align="right" style="padding-right:8px">
            <?=$lang["action"]?>: 
            <select style="width:150px" name="order_action" id="order_action">
                <option value="delay_email"><?=$lang["action_send_delay_email"]?></option>
            </select>
        </td>
    </tr>
</table>
<?php
    $column_width = "<col width='20'><col width='80'><col width='100'><col><col width='30'><col width='80'><col width='80'><col width='130'><col width='20'>";
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
<?=$column_width?>
    <tr class="header">
        <td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["so_number"]?> <?=$sortimg["so_no"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'sku', '<?=$xsort["sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["sku"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'prod_name', '<?=$xsort["prod_name"]?>')"><?=$lang["produt_name"]?> <?=$sortimg["prod_name"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'qty', '<?=$xsort["qty"]?>')"><?=$lang["qty"]?> <?=$sortimg["qty"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'expect_delivery_date', '<?=$xsort["expect_delivery_date"]?>')"><?=$lang["edd"]?> <?=$sortimg["expect_delivery_date"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'expected_delivery_date', '<?=$xsort["expected_delivery_date"]?>')"><?=$lang["current_edd"]?> <?=$sortimg["expected_delivery_date"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'create_on', '<?=$xsort["create_on"]?>')"><?=$lang["order_date"]?> <?=$sortimg["create_on"]?></a></td>
<!--        <td style="white-space:nowrap"><?=$lang["action"]?></td>    -->
        <td title="<?=$lang["check_all"]?>"><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm, this, 1);"></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td>&nbsp;</td>
        <td><input name="so_no" class="input" value="<?=htmlspecialchars($this->input->get_post("so_no"))?>"></td>
        <td><input name="prod_sku" class="input" value="<?=htmlspecialchars($this->input->get_post("prod_sku"))?>"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
<!--        <td>&nbsp;</td> -->
        <td align="center"><input type="submit" name="searchsubmit" value="" onClick="checkall(document.fm, this, 0);" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<input type="hidden" name="sort" value='<?=$this->input->get_post("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get_post("order")?>'>
<input type="hidden" name="search" value="1">
<?php
    $i = 0;
    foreach ($preorder_list as $preorder)
    {
        if ($preorder->get_multiple_items_count() > 1)
            $style = "value_red";
        else
            $style = "row" . ($i%2);
        
        print "<tr class='" . $style . "'>";
            print "<td>&nbsp;</td>";
            print "<td>" . $preorder->get_so_no() . "</td>";
            print "<td>" . $preorder->get_prod_sku() . "</td>";
            print "<td>" . $preorder->get_prod_name() . "</td>";
            print "<td>" . $preorder->get_qty() . "</td>";
            print "<td>" . $preorder->get_expect_delivery_date() . "</td>";
            print "<td>" . $preorder->get_current_expected_delivery_date() . "</td>";
            print "<td>" . $preorder->get_create_on() . "</td>";
//          print "<td>&nbsp;</td>";
            print "<td><input type='checkbox' name='check[" . $preorder->get_so_no() . "]' value=" . $preorder->get_so_no() . "></td>";
        print "</tr>";
        $i++;
    }
?>
    <tr>
        <td colspan='9' style="text-align:right"><input type='submit' value='submit'></td>
    </tr>
</table>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>