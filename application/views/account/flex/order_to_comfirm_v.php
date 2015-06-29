<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
<script>
    function  cancel_check(e)
    {
        var elements = document.getElementsByName(e.name);
        for(var i = 0; i< elements.length; i++)
        {
            if(elements[i].checked == true)
            {
                elements[i].checked = false;
                return false;
            }
        }
    }
</script>
</head>
<body>
<div id="main">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td height="30" width="400" align="right" class="title">
            <input type="button" style="width: 160px" value="Search Shipped Orders" class="button" onclick="Redirect('<?=base_url()?>account/flex/get_rakuten_shipped_order')">
            <input type="button" style="width: 160px" value="Approve Shipped Orders" class="button" onclick="Redirect('<?=base_url()?>account/flex/get_rakuten_shipped_order_list')">
        </td>
    </tr>
</table>
<form name="fm" method="post">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <colgroup>
        <col width="20"><col width="50"><col width="200"><col width="100"><col width="250"><col width="60"><col width="120"><col width="120"><col width="120"><col width="20">
    </colgroup>
    <tr class="header">
        <td height="20" width="20px"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["order_id"]?> <?=$sortimg["so_no"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'platform_order_id', '<?=$xsort["platform_order_id"]?>')"><?=$lang["platform_order_id"]?> <?=$sortimg["platform_order_id"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'platform_id', '<?=$xsort["platform_id"]?>')"><?=$lang["platform_id"]?> <?=$sortimg["platform_id"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'txn_id', '<?=$xsort["txn_id"]?>')"><?=$lang["gateway_txn_id"]?> <?=$sortimg["txn_id"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'currency_id', '<?=$xsort["currency_id"]?>')"><?=$lang["currency_id"]?> <?=$sortimg["currency_id"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'amount', '<?=$xsort["amount"]?>')"><?=$lang["order_amount"]?> <?=$sortimg["amount"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'order_create_date', '<?=$xsort["order_create_date"]?>')"><?=$lang["order_create_date"]?> <?=$sortimg["order_create_date"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'dispatch_date', '<?=$xsort["dispatch_date"]?>')"><?=$lang["dispatch_date"]?> <?=$sortimg["dispatch_date"]?></a></td>
        <td><input type="checkbox" name="chkall" value="1" onclick="checkall(document.fm, this, 1);"></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
<?php
    if (!empty($obj_list))
    {
        foreach ($obj_list as $orderobj) {
?>
    <tr class="row1">
        <td height="20" width="20px"></td>
        <td><a href="<?=base_url()?>cs/quick_search/view/<?=$orderobj->get_so_no()?>" target="_blank"><?=$orderobj->get_so_no()?></a></td>
        <td><?=$orderobj->get_platform_order_id()?></td>
        <td><?=$orderobj->get_platform_id()?></td>
        <td><?=$orderobj->get_txn_id()?></td>
        <td><?=$orderobj->get_currency_id()?></td>
        <td><?=$orderobj->get_currency_id()?> <?=$orderobj->get_amount()?></td>
        <td><?=$orderobj->get_order_create_date()?></td>
        <td><?=$orderobj->get_dispatch_date()?></td>
        <td>
            <input type="checkbox" name="check[<?=$orderobj->get_so_no()?>]" value="<?=$orderobj->get_so_no()?>">
        </td>
    </tr>
<?php
        }
    }
?>
</table>
<input type="submit" value="approve" class="button" name="comfirm" />
<input type="submit" value="cancel" class="button" name="comfirm" />
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>