<tr>
    <td colspan="2" class="title2" style="line-height:30px;padding-bottom:4px;">
        <input type="button" value="<?= $lang["weight_cat"] ?>"
               onClick="Redirect('<?= base_url() ?>marketing/delivery_charge/index/weight')">
        <?php
        if ($delivery_type_list) {
            foreach ($delivery_type_list AS $key => $value) {
                ?>
                <input type="button" value="<?= $lang['delivery_charge'] ?>(<?= $value ?>)"
                       onClick="Redirect('<?= base_url() ?>marketing/delivery_charge/view/<?= $key ?>')">
            <?php
            }
        }
        ?>
        <?= $str ?>
    </td>
</tr>
