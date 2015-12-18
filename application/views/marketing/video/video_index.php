<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <script src="<?= base_url() ?>/marketing/category/js_catlist" type="text/javascript"></script>
    <script src="<?= base_url() ?>js/common.js" type="text/javascript"></script>
    <script language="javascript">
        function SaveChange(el) {
            el.form.submit();
        }
        function changeRightFrame(sku, lang, country) {
            document.getElementById('right').src = '<?=base_url()?>marketing/video/view_right/' + sku + '/' + lang + '/' + country;
        }
        function changeLeftFrame(lang) {
            document.getElementById('left').src = '<?=base_url()?>marketing/video/view_left/<?=platform?>/?lang=' + lang;
            //document.getElementById('left').src = '<?=base_url()?>marketing/video/view_left/<?=$platform?>/?lang='+lang;
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
            <col width="16%">
            <col width="30%">
            <col>
            <tr>
                <td colspan=2 height="70" style="padding-left:8px"><b
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
                <td width="15%" class="value" align="right"
                    style="padding-right:8px;"><?= $lang["select_language"] ?></td>
                <td width="15%" class="value">
                    <select id="language"
                            onChange='SaveChange(this);gotoPage("<?= base_url() . "marketing/video/main/" ?>",this.value)'>
                        <option value="" style="padding-right:50px;"> -- Please Select --</option>
                        <?php
                        foreach ($language_list as $obj) {
                            ?>
                            <option value="<?= $obj->get_id() ?>"<?= ($obj->get_id() == $language_id ? "SELECTED" : "") ?>><?= $obj->get_name() ?></option><?php
                        }
                        ?>
                    </select>
                </td>
                <td class="value" width="2%"><?= "or" ?></td>
                <td width="15%" class="value" align="right" style="padding-right:8px;"
                    size='5'><?= $lang["select_country"] ?></td>
                <td width="15%" class="value">
                    <select
                        onChange='SaveChange(this);gotoPage("<?= base_url() . "marketing/video/main/ALL/" ?>",this.value)'>
                        <option value="" style="padding-right:50px;"> -- Please Select --</option>
                        <?php
                        foreach ($country_list as $country_obj) {
                            ?>
                            <option
                            value="<?= $country_obj->get_id() ?>" <?= ($country_obj->get_id() == $country_id ? "SELECTED" : "") ?>><?= $country_obj->get_name() ?></option><?php
                        }
                        ?>
                    </select>
                    <!--
        <select id="country" name="country[]" multiple size="7" onChange='SaveChange(this);'>
            <?php
                    foreach ($country_list_w_lang as $obj) {
                        ?><option value="<?= $obj->get_id() ?>"<?php
                        if ($this->input->post('country')) {
                            foreach ($this->input->post('country') AS $country) {
                                if ($country == $obj->get_id()) {
                                    echo "SELECTED";
                                }
                            }
                        }
                        ?>><?= $obj->get_name() ?></option><?php
                    }
                    ?>
        </select>
     -->
                </td>
                <td width="40%" class="value"></td>
            </tr>
        </table>
    </form>
    <?php
    if ($display) {
        ?>
        <iframe src="<?= base_url() ?>marketing/video/view_left/<?= $lang_id ?>/<?= $country_id ?>" noresize
                frameborder="0" name="left" id="left" marginwidth="0" marginheight="0" hspace="0" vspace="0" width="200"
                height="380" style="float:left; border-right:1px solid #000000;"
                onLoad="SetFrameFullHeight(this)"></iframe>
        <iframe src="" noresize frameborder="0" name="right" id="right" marginwidth="0" marginheight="0" hspace="0"
                vspace="0" width="1059" height="380" style="float:left; " onLoad="SetFrameFullHeight(this)"></iframe>
    <?php
    }
    ?>
    </script>
    </
    div >
    <?=$notice["js"]?>
    < / body >
    < / html >
