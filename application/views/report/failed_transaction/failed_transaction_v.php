<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main" style="width:auto;">
<?=$notice["img"]?>
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="120"><col width="90"><col width="120"><col width="120"><col width="120"><col width="80"><col width="80"><col width="120"><col>
    <tr class="header">
        <td height="20"><a href="#" onClick="SortCol(document.fm, 'so.order_create_date', '<?=$xsort["so.order_create_date"]?>')"><?=$lang["order_create_date"]?> <?=$sortimg["so.order_create_date"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'so.platform_id', '<?=$xsort["so.platform_id"]?>')"><?=$lang["platform_id"]?> <?=$sortimg["so.platform_id"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'so.bill_country_id', '<?=$xsort["so.bill_country_id"]?>')"><?=$lang["billing_country"]?> <?=$sortimg["so.bill_country_id"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'so.delivery_country_id', '<?=$xsort["so.delivery_country_id"]?>')"><?=$lang["delivery_country"]?> <?=$sortimg["so.delivery_country_id"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'ip.country_code', '<?=$xsort["ip.country_code"]?>')"><?=$lang["country_by_ip"]?> <?=$sortimg["ip.country_code"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'so.so_no', '<?=$xsort["so.so_no"]?>')"><?=$lang["order_no"]?> <?=$sortimg["so.so_no"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'so.amount', '<?=$xsort["so.amount"]?>')"><?=$lang["amount"]?> <?=$sortimg["so.amount"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'sops.card_id', '<?=$xsort["sops.card_id"]?>')"><?=$lang["payment_type"]?> <?=$sortimg["sops.card_id"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'sops.remark', '<?=$xsort["sops.remark"]?>')"><?=$lang["mb_failure_response"]?> <?=$sortimg["sops.remark"]?></a></td>
    </tr>
<?php
    $i=0;
    if ($objlist)
    {
        foreach ($objlist as $obj)
        {
?>

    <tr class="row<?=$i%2?>">
        <td height="20"><?=$obj->get_order_create_date()?></td>
        <td><?=$obj->get_platform_id()?></td>
        <td><?=$obj->get_bill_country_id()?></td>
        <td><?=$obj->get_delivery_country_id()?></td>
        <td><?=$obj->get_country_by_ip()?></td>
        <td><?=$obj->get_so_no()?></td>
        <td><?=$obj->get_amount()?></td>
        <td><?=$obj->get_card_type()?></td>
        <td><?=$obj->get_fail_reason()?></td>
    </tr>
<?php
            $i++;
        }
    }
?>
</table>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
<input type="hidden" name="search" value="1">
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>