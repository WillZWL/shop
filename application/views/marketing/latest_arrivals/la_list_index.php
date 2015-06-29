<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["subtitle"]?></td>
        <td width="400" align="right" class="title">&nbsp;</td>
    </tr>
    <tr>
        <td height="2" bgcolor="#000033"></td>
        <td height="2" bgcolor="#000033"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["subheader"]?><br><br><?=$lang["category_found"]?><b><?=$total?></b></td>

        <td align="right">
            <table align="right"><tr><td align="left"><?=$lang["header_algo"]?></td></tr></table>
        </td>
    </tr>
</table>
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#000000" width="100%" class="tb_list">
    <col width="20"><col width="200"><col><col width="150"><col width="100"><col width="150"><col width="26">
    <tr class="header">
        <td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td><a href="#" onClick="SortCol(document.fm, 'name', '<?=$xsort["name"]?>')"><?=$lang["category_name"]?> <?=$sortimg["name"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'description', '<?=$xsort["description"]?>')"><?=$lang["category_desc"]?> <?=$sortimg["description"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'level', '<?=$xsort["level"]?>')"><?=$lang["category_level"]?> <?=$sortimg["level"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["category_status"]?> <?=$sortimg["status"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'cnt', '<?=$xsort["cnt"]?>')"><?=$lang["manual"]?> <?=$sortimg["cnt"]?></a></td>
        <td></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="name" class="input" value="<?=htmlspecialchars($this->input->get("name"))?>"></td>
        <td><input name="description" class="input" value="<?=htmlspecialchars($this->input->get("description"))?>"></td>
        <td><select name="level" class="input"><option value=""><?=$lang["please_select"]?></option><?php
            for($i=1; $i<= 3; $i++)
            {
            ?><option value="<?=$i?>" <?=($i == $this->input->get('level')?"SELECTED":"")?>><?=$lang["type".$i]?></option><?php
            }
        ?></select></td>
        <td><select name="status" class="input"><option value=""><?=$lang["please_select"]?></option><?php
            for($j=0; $j<2; $j++)
            {
            ?><option value="<?=$j?>" <?=($j == $this->input->get('status') && $this->input->get('status')!="")?"SELECTED":""?>><?=$lang["status".$j]?></option><?php
            }
        ?></select></td>
        <td><select name="manual" class="input"><option value=""><?=$lang["please_select"]?></option><option value="Y" <?=($this->input->get("manual") == "Y"?"SELECTED":"")?>><?=$lang["Y"]?></option><option value="N" <?=($this->input->get("manual") == "N"?"SELECTED":"")?>><?=$lang["N"]?></option></select></td>
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<?php
    if (!empty($list))
    {
        if($total > 0)
        {
            foreach ($list as $category)
            {
                $cur_color = $row_color[$i%2];
                $link = base_url()."marketing/latest_arrivals/main/?catid=".$category->get_id()."&level=".$category->get_level();
?>

    <tr class="row<?=i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick="Redirect('<?=$link?>')">
        <td height="20">&nbsp;</td>
        <td><?=$category->get_name()?></td>
        <td><?php
            $output = strip_tags($category->get_description());
            $cnt = strlen($output);
            if($cnt < 100)
            {
                echo stripslashes($output);
            }
            else
            {
                echo stripslashes(substr($output,100))." ... ";
            }
        ?></td>
        <td><?=$lang["type".$category->get_level()]?></td>
        <td><?=$lang["status".$category->get_status()]?></td>
        <td><?=$lang[($category->get_cnt()?"Y":"N")]?></td>
        <td align="center">&nbsp;</td>
    </tr>
<?php
                $i++;
            }
        }
        else
        {
?>
        <tr bgcolor="<?=$row_color[0]?>">
            <td colspan="6" align="center"><?=$lang["category_not_found"]?></td>
        </tr>
<?php
        }
    }
?>
</table>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>