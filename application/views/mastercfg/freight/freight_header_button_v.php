<tr>
    <td colspan="2" class="title2" style="line-height:30px;padding-bottom:4px;">
        <input type="button" value="<?= $lang["freight_cat"] ?>" onClick="Redirect('<?= base_url() ?>mastercfg/freight')">
        <?php
            if ($origin_country_list) :
                foreach ($origin_country_list AS $obj) :
                    ?>
                    <input type="button" value="<?= $lang['freight_cost'] ?>(<?= $obj->getCountryId() ?>)"
                           onClick="Redirect('<?= base_url() ?>mastercfg/freight/view/<?= $obj->getCountryId() ?>')">
                <?php
                endforeach;
            endif;
        ?>
    </td>
</tr>
