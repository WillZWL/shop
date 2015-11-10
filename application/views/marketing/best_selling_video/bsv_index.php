<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <script src="<?= base_url() ?>/marketing/product/js_catlist" type="text/javascript"></script>
    <script src="<?= base_url() ?>js/common.js" type="text/javascript"></script>
    <script language="javascript">
        function SaveChange(el) {
            el.form.submit();
        }
    </script>
</head>
<body topmargin="0" leftmargin="0"
      onResize="SetFrameFullHeight(document.getElementById('left'));SetFrameFullHeight(document.getElementById('right'));">
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["subtitle"] ?></b></td>
        </tr>
        <tr class="header">
            <td height="2"></td>
        </tr>
    </table>
    <form action="" method="POST" name="form" style="padding:0; margin:0" onSubmit="return CheckForm(this)"
          enctype="multipart/form-data">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <!-- <col width="15%"><col width="35%"><col width="50%"> -->
            <col width="15%">
            <col width="15%">
            <col width="15%">
            <col width="15%">
            <col width="40%">
            <tr>
                <td colspan=4 height="70" style="padding-left:8px"><b
                        style="font-size:14px"><?= $lang["title"] ?></b><br><?= $lang["subheader"] ?></td>
                <td align="right">
                    <table align="right">
                        <tr>
                            <td align="left"><?= $lang["header_algo"] ?></td>
                        </tr>
                    </table>
                </td>

            </tr>
            <tr>
                <td class="field field_text"><?= $lang["select_platform"] ?></td>
                <td colspan=4 class="value value_text">&nbsp;&nbsp;
                    <select
                        onChange='SaveChange(this);gotoPage("<?= base_url() . "marketing/best_selling_video/main/?catid=" . $this->input->get('catid') . "&level=" . $this->input->get('level') . "&type=" . $this->input->get('type') . "&src=" . $this->input->get('src') . "&platform=" ?>",this.value)'>
                        <option value=""> -- Please select --</option><?php
                        foreach ($platform_id_list as $obj) {
                            ?>
                            <option
                            value="<?= $obj->get_id() ?>" <?= ($obj->get_id() == $platform ? "SELECTED" : "") ?>><?= $obj->get_id() ?></option><?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field field_text"><?= $lang["select_src"] ?></td>
                <td colspan=4 class="value value_text">&nbsp;&nbsp;
                    <select
                        onChange='SaveChange(this);gotoPage("<?= base_url() . "marketing/best_selling_video/main/?catid=" . $this->input->get('catid') . "&level=" . $this->input->get('level') . "&platform=" . $this->input->get('platform') . "&type=" . $this->input->get('type') . "&src=" ?>",this.value)' <?= $this->input->get('platform') ? "" : "disabled" ?>>
                        <option value=""> -- Please select --</option>
                        <option value="Y" <?= ('Y' == $src ? "SELECTED" : "") ?>><?= "YouTube" ?></option>
                        <option value="V" <?= ('V' == $src ? "SELECTED" : "") ?>><?= "Vzaar" ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field field_text"><?= $lang["select_video"] ?></td>
                <td colspan=4 class="value value_text">&nbsp;&nbsp;
                    <select
                        onChange='SaveChange(this);gotoPage("<?= base_url() . "marketing/best_selling_video/main/?catid=" . $this->input->get('catid') . "&level=" . $this->input->get('level') . "&platform=" . $this->input->get('platform') . "&src=" . $this->input->get('src') . "&type=" ?>",this.value)' <?= $this->input->get('src') ? "" : "disabled" ?>>
                        <option value=""> -- Please select --</option>
                        <option value="G" <?= ('G' == $type ? "SELECTED" : "") ?>><?= $lang["guide"] ?></option>
                        <option value="R" <?= ('R' == $type ? "SELECTED" : "") ?>><?= $lang["review"] ?></option>
                    </select>
                </td>
            </tr>
        </table>
    </form>
    <?php  if ($display) {
        ?>
        <iframe
            src="<?= base_url() ?>marketing/best_selling_video/view_left/?cat=<?= $catid ?>&level=<?= $level ?>&platform=<?= $platform ?>&type=<?= $type ?>&src=<?= $src ?>"
            noresize frameborder="0" name="left" id="left" marginwidth="0" marginheight="0" hspace="0" vspace="0"
            width="300" height="380" style="float:left; border-right:1px solid #000000;"
            onLoad="SetFrameFullHeight(this)"></iframe>
        <iframe
            src="<?= base_url() ?>marketing/best_selling_video/view_right/<?= $catid ?>/<?= $platform ?>/<?= $type ?>/<?= $src ?>"
            noresize frameborder="0" name="right" id="right" marginwidth="0" marginheight="0" hspace="0" vspace="0"
            width="959" height="380" style="float:left; " onLoad="SetFrameFullHeight(this)"></iframe>
    <?php
    }
    ?>
    </script>
    </
    div >
    <?=$notice["js"]?>
    < / body >
    < / html >
