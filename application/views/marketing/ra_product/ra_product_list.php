<html>
    <head>
        <link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
    </head>

    <body>
        <div id="main">
            <form action="<?=base_url()."marketing/ra_product/update/"?>" method="POST">
                <input type="hidden" name="sku" value="<?=$sku?>">
                <input type="hidden" name="posted" value="1">

                <table width="100%" cellpadding="0" cellspacing="0" class="tb_main">
                    <tr>
                        <td>
                            <table width="100%" cellpadding="2" cellspacing="0" class="tb_pad">
                                <?php
                                    for($i = 1; $i <21; $i++)
                                    {
                                        $func = "get_rcm_group_id_".$i;
                                        $this_group_id = $ra_product_obj->$func();
                                ?>
                                        <tr>
                                            <td width="140" align="right" class="field" style="padding-right:8px;"><?=$lang["ra_product"]?><?=$i?></td>
                                            <td align="left" class="value" width="900">
                                                <select id="rprod<?=$i?>" name="rprod<?=$i?>" >
                                                    <option><?=$lang["not_select"]?></option>
                                                <?php
                                                    $selected = "";
                                                    foreach($ra_group_list as $ra_group_obj)
                                                    {
                                                        if ($ra_group_obj->get_group_id() == $this_group_id)
                                                            $selected = "selected='selected'";
                                                        else
                                                            $selected = "";
                                                ?>
                                                    <option value="<?=$ra_group_obj->get_group_id()?>" <?=$selected?> ><?=$ra_group_obj->get_group_name()?></option>
                                                <?php
                                                    }
                                                ?>
                                                </select>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                ?>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
                                <tr>
                                    <td colspan="2" height="40"></td>
                                    <td colspan="2" align="right" style="padding-right:8px;">
                                        <input type="submit" value="<?=$lang["update_record"]?>">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </form>
        <div>

        <script>
            if (parent.frames["bundle"])
            {
                //parent.frames["bundle"].window.location.href="<?=base_url()?>marketing/bundle/add/<?=$sku?>/?"+Math.random();
            }
        </script>
    </body>
</html>