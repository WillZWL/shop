<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
</head>
<body
    onResize="SetFrameFullHeight(document.getElementById('fcart'));SetFrameFullHeight(document.getElementById('fprod'));">
<div id="main">
    <?php  $ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]); ?>
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["add_button"] ?>" class="button" onclick="Redirect('<?= site_url($this->path) ?>')">
                &nbsp;
                <input type="button" value="<?= $lang["on_hold_button"] ?>" class="button" onclick="Redirect('<?= site_url($this->path . '/on_hold') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message" . ($platform_id ? "" : 1)] ?>
            </td>
            <td align="right" style="padding-right:8px">
                <?= $lang["selling_platform"] ?>:
                <select onChange="Redirect('<?= base_url() . $this->path ?>/index/'+this.value)">
                    <option></option>
                    <?php
                    $sp_selected[$platform_id] = " SELECTED";
                    foreach ($sp_list as $obj) :
                        $id = $obj->getSellingPlatformId();
                        ?>
                        <option
                            value="<?= $id ?>"<?= $sp_selected[$id] ?>><?= $id . " - " . $obj->getName(); ?></option>
                    <?php
                    endforeach;
                    ?>

                </select>
            </td>
        </tr>
    </table>
    <?php if ($platform_id) : ?>
        <iframe name="fcart" id="fcart" src="<?= base_url() . $this->path ?>/cart/<?= $platform_id ?>" width="200"
                style="float:left;border-right:1px solid #000000;" noresize frameborder="0" marginwidth="0"
                marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
        <iframe name="fprod" id="fprod" src="<?= base_url() . $this->path ?>/prod_list/<?= $platform_id ?>"
                width="1059" style="float:left;border-left:1px solid #999999;" noresize frameborder="0" marginwidth="0"
                marginheight="0" hspace=0 vspace=0 onLoad="SetFrameFullHeight(this)"></iframe>
    <?php endif; ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>