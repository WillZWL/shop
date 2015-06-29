<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<style>
#stock_out_all:hover
{
    cursor:pointer;
}
#stock_out_all
{
    ont-weight:bold;
    color:red;
}

</style>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script>
function stock_out_all()
{
    var radioElements = document.getElementsByClassName("action_type");
    for(var i=0;i<radioElements.length;i++)
    {
        thisSet = radioElements[i];
        if(thisSet.value=='I')
        {
            thisSet.checked = false;
        }
        else
        {
            thisSet.checked = true;
        }
    }

    var invElements = document.getElementsByClassName('inventory_qty');
    var invInputElements = document.getElementsByClassName('adjust_inv_qty');

    for(var i=0;i<invElements.length;i++)
    {
        invQty = parseInt(invElements[i].innerHTML);
        if(isNaN(invQty))
        {
            alert("Invalid Inventory!!");
            return false;
        }
        else
        {
            invInputElements[i].value = invQty;
        }

    }

    var invChechBoxElements = document.getElementsByClassName("inv_check_box");
    for(var i=0;i<invChechBoxElements.length;i++)
    {
        invChechBoxElements[i].checked = true;
        Marked(invChechBoxElements[i]);
    }
}
</script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
    $ar_type = array("F"=>$lang["freight_cat"], "W"=>$lang["weight_cat"]);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('supply/inventory_adjustment/')?>')"></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%" class="tb_list">
    <col width="20"><col><col width="180"><col width="240"><col width="120"><col width="120"><col width="240"><col width="100">
    <tr class="add_header">
        <td height="20"></td>
        <td title="<?=$lang["product"]?>"><?=$lang["product"]?></td>
        <td title="<?=$lang["sku"]?>"><?=$lang["sku"]?></td>
        <td title="<?=$lang["warehouse"]?>"><?=$lang["warehouse"]?></td>
        <td title="<?=$lang["goods_in_transit"]?>"><?=$lang["git"]?></td>
        <td title="<?=$lang["inventory"]?>"><?=$lang["inventory"]?></td>
        <td title="<?=$lang["inventory_adjust"]?>" align="center"><?=$lang["inventory_adjust"]?></td>
        <td></td>
    </tr>
<form name="fm_add" method="post" onSubmit="return CheckForm(this)">
    <tr class="add_row">
        <td rowspan="2"></td>
        <td></td>
        <?php
            if ($this->input->post("cmd") == "add")
            {
        ?>
        <td><input name="sku" dname="SKU" class="input" value="<?=htmlspecialchars($this->input->post("sku"))?>" notEmpty maxLen="15"></td>
        <td>
            <select name="to_location" class="input" notEmpty>
                <option value="">
            <?php
                if ($whlist)
                {
                    foreach ($whlist as $wh_obj)
                    {
                        $cur_id = $wh_obj->get_id();
                        $cur_name = $wh_obj->get_name();
                        $ar_wh[$cur_id] = $cur_name;
                        $w_selected[$this->input->post("to_location")]=" SELECTED";
            ?>
                <option value="<?=$cur_id?>"<?=$w_selected[$cur_id]?>><?=$cur_name?>
            <?php
                    }
                }
            ?>
            </select>
        </td>
        <td></td>
        <td></td>
        <td><?=$lang["in"]?> <input name="qty" dname="IN" class="int_input" value="<?=htmlspecialchars($this->input->post("qty"))?>" notEmpty isInteger min="0"></td>
        <?php
            }
            else
            {
        ?>
        <td><input name="sku" dname="SKU" class="input" notEmpty maxLen="15"></td>
        <td>
            <select name="to_location" class="input" notEmpty>
                <option value="">
            <?php
                if ($whlist)
                {
                    foreach ($whlist as $wh_obj)
                    {
                        $cur_id = $wh_obj->get_id();
                        $cur_name = $wh_obj->get_name();
                        $ar_wh[$cur_id] = $cur_name;
            ?>
                <option value="<?=$cur_id?>"><?=$cur_name?>
            <?php
                    }
                }
            ?>
            </select>
        </td>
        <td></td>
        <td></td>
        <td><?=$lang["in"]?> <input name="qty" dname="IN" class="int_input" notEmpty isInteger min="0"></td>
        <?php
            }
        ?>
        <td align="center" rowspan="2"><input type="submit" value="<?=$lang["add"]?>"></td>
    </tr>
    <tr class="add_row">
        <td align="right">
            <?=$lang["reason"]?>:
        </td>
        <td colspan="5">
            <textarea name="reason" rows="2" class="input"><?=$this->input->post("cmd") == "add"?htmlspecialchars($this->input->post("reason")):""?></textarea>
        </td>
    </tr>
    <tr class="empty_row">
        <td colspan="8"><hr></hr></td>
    </tr>
    <input type="hidden" name="posted" value="1">
    <input type="hidden" name="cmd" value="add">
</form>
<form name="fm" method="get">
    <tr class="header">
        <td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td title="<?=$lang["product"]?>"><a href="#" onClick="SortCol(document.fm, 'prod_name', '<?=$xsort["prod_name"]?>')"><?=$lang["product"]?> <?=$sortimg["prod_name"]?></a></td>
        <td title="<?=$lang["sku"]?>"><a href="#" onClick="SortCol(document.fm, 'prod_sku', '<?=$xsort["prod_sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["prod_sku"]?></a></td>
        <td title="<?=$lang["warehouse"]?>"><a href="#" onClick="SortCol(document.fm, 'warehouse_id', '<?=$xsort["warehouse_id"]?>')"><?=$lang["warehouse"]?> <?=$sortimg["warehouse_id"]?></a></td>
        <td title="<?=$lang["goods_in_transit"]?>"><a href="#" onClick="SortCol(document.fm, 'git', '<?=$xsort["git"]?>')"><?=$lang["git"]?> <?=$sortimg["git"]?></a></td>
        <td title="<?=$lang["inventory"]?>"><a href="#" onClick="SortCol(document.fm, 'inventory', '<?=$xsort["inventory"]?>')"><?=$lang["inventory"]?> <?=$sortimg["inventory"]?></a></td>
        <td title="<?=$lang["inventory_adjust"]?>" align="center"><?=$lang["inventory_adjust"]?></td>
        <td><span id="stock_out_all" onclick="stock_out_all()" >Stock Out All</span></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="prod_name" dname="PRODUCT" class="input" value="<?=$this->input->get("prod_name")?>"></td>
        <td><input name="prod_sku" dname="SKU" class="input" value="<?=$this->input->get("prod_sku")?>" maxLen="15"></td>
        <td>
            <select name="warehouse_id" class="input">
                <option value="">
            <?php
                if ($whlist)
                {
                    foreach ($whlist as $wh_obj)
                    {
                        $cur_id = $wh_obj->get_id();
                        $cur_name = $wh_obj->get_name();
                        $ar_wh[$cur_id] = $cur_name;
                        $w_selected[$this->input->get("warehouse_id")]=" SELECTED";
            ?>
                <option value="<?=$cur_id?>"<?=$w_selected[$cur_id]?>><?=$cur_name?>
            <?php
                    }
                }
            ?>
            </select>
        </td>
        <td><input name="git" class="int_input" value="<?=htmlspecialchars($this->input->get("git"))?>" isInteger min="0"></td>
        <td><input name="inventory" class="int_input" value="<?=htmlspecialchars($this->input->get("inventory"))?>" isInteger min="0"></td>
        <td></td>
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
<input type="hidden" name="search" value='1'>
</form>
<?php
    $i=0;
    if ((array)$objlist)
    {
?>
<form name="fm_edit" method="post">
<?php
    if($objlist)
    {
        foreach ($objlist as $obj)
        {
            $cur_wh = $obj->get_warehouse_id();
            $cur_sku = $obj->get_prod_sku();
?>

    <tr class="row<?=$i%2?>" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')">
        <td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
        <td><?=$obj->get_prod_name()?></td>
        <td><?=$obj->get_prod_sku()?></td>
        <td><?=$ar_wh[$cur_wh]?></td>
        <td><?=$obj->get_git()?></td>
        <td class="inventory_qty"><?=$obj->get_inventory()?></td>
        <td><input type="radio" class="action_type" name="adj[<?=$cur_sku?>][<?=$cur_wh?>]" value="I" CHECKED><?=$lang["in"]?>  &nbsp; <input type="radio" class="action_type" name="adj[<?=$cur_sku?>][<?=$cur_wh?>]" value="O"><?=$lang["out"]?>  <input name="qty[<?=$cur_sku?>][<?=$cur_wh?>]" dname="QTY" class="int_input adjust_inv_qty" value="<?=isset($_POST["qty"][$cur_sku][$cur_wh])?htmlspecialchars($_POST["qty"][$cur_sku][$cur_wh]):""?>" notEmpty isInteger min="0"></td>
        <td align="center"><input type='checkbox' class="inv_check_box" name='check[<?=$cur_sku?>][<?=$cur_wh?>]' value='\[<?=$cur_sku?>\]\[<?=$cur_wh?>\]' onClick='Marked(this);'></td>
    </tr>
<?php
            $i++;
        }
    }
?>
    <tr class="tb_brow">
        <td></td>
        <td align="right">
            <?=$lang["reason"]?>:
        </td>
        <td colspan="5">
            <textarea name="reason" rows="2" class="input"><?=$this->input->post("cmd") != "add"?htmlspecialchars($this->input->post("reason")):""?></textarea>
        </td>
        <td><input type="button" value="<?=$lang["submit"]?>" onClick="if(CheckSubmit(this.form)){this.form.submit();}"></td>
    </tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
<?php
    }
?>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
<script>
function CheckSubmit(f)
{
    check_eles = getEle(document, "input", "name", "check");
    count=false;
    for (i=0; i<check_eles.length; i++)
    {
        check_ele = check_eles[i];
        if (check_ele.checked)
        {
            pattern = check_ele.value;
            if (!CheckSetElements(check_ele.parentNode.parentNode, pattern))
            {
                return false;
            }
            count=true;
        }
    }
    return count;
}
</script>
</body>
</html>