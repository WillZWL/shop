    <tr>
        <td colspan="2" class="title2" style="line-height:30px;padding-bottom:4px;">
            <input type="button" value="<?=$lang["freight_cat"]?>" onClick="Redirect('<?=base_url()?>mastercfg/freight')">
            <!--<input type="button" value="<?=$lang["weight_cat"]?>" onClick="Redirect('<?=base_url()?>mastercfg/freight/index/weight')">-->
            <?php
                if($origin_country_list)
                {
                    foreach($origin_country_list AS $key=>$value)
                    {
            ?>
                <input type="button" value="<?=$lang['freight_cost']?>(<?=$value?>)" onClick="Redirect('<?=base_url()?>mastercfg/freight/view/<?=$value?>')">
            <?php
                    }
                }
        /*
                //$cost_courier = "AMUK_Std";
                if ($courierlist)
                {
                    foreach ($courierlist as $courier)
                    {
                        $cur_type = $courier->get_type();
                        $cur_weight_type = $courier->get_weight_type();

                        if ($cur_type == 'W' && ($cur_weight_type == "B" || $cur_weight_type == "CH"))
                        {
                            $cname = $courier->get_courier_name();
                            $str .= ' <input type="button" value="'.$lang["delivery_charge"].'('.$cname.')" onClick="Redirect(\''.base_url().'mastercfg/freight/view/'.$courier->get_id().'/CH\')">';
                        }
                        if ($cur_type == 'W' && $cur_weight_type == "CH")
                        {
                            continue;
                        }
                        $display_type=($cur_type=="W")?$lang["delivery_cost"]:$lang["freight_cost"];
            ?>
                <input type="button" value="<?=$display_type?>(<?=$value?>)" onClick="Redirect('<?=base_url()?>mastercfg/freight/view/<?=$value?>')">
            <?php
                    }
                }
                */
            ?>
            <?=$str?>
        </td>
    </tr>
