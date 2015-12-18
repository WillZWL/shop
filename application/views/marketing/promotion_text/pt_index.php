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
        function changeRightFrame(platform_type, language, platform_id, sku) {
            document.getElementById('right').src = '<?=base_url()?>marketing/promotion_text/view_right/' + platform_type + '/' + language + '/' + platform_id + '/' + sku;
        }
        function changeRightFrame2(platform_type) {
            document.getElementById('right').src = '<?=base_url()?>marketing/promotion_text/view_right/' + platform_type;
        }
        function changeRightFrame3(platform_type, language) {
            document.getElementById('right').src = '<?=base_url()?>marketing/promotion_text/view_right/' + platform_type + '/' + language;
        }
        function changeRightFrame4(platform_type, language, platform_id) {
            document.getElementById('right').src = '<?=base_url()?>marketing/promotion_text/view_right/' + platform_type + '/' + language + '/' + platform_id;
        }

        function changeLeftFrame(lang, platform) {
            alert(lang, platform);
            document.getElementById('left').src = '<?=base_url()?>marketing/promotion_text/view_left/' + lang + '/' + platform;
        }
    </script>
</head>
<body topmargin="0" leftmargin="0"
      onResize="SetFrameFullHeight(document.getElementById('left'));SetFrameFullHeight(document.getElementById('right'));">
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
        </tr>
        <tr class="header">
            <td height="2"></td>
            <td height="2"></td>
        </tr>
    </table>
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
    </table>
    <?php
    if ($display) {
        ?>
        <iframe src="<?= base_url() ?>marketing/promotion_text/view_left/<?= $lang_id ?>/<?= $platform_id ?>" noresize
                frameborder="0" name="left" id="left" marginwidth="0" marginheight="0" hspace="0" vspace="0" width="200"
                height="380" style="float:left; border-right:1px solid #000000;"
                onLoad="SetFrameFullHeight(this)"></iframe>
        <iframe src="" noresize frameborder="0" name="right" id="right" marginwidth="0" marginheight="0" hspace="0"
                vspace="0" width="84%" height="380" style="float:left; " onLoad="SetFrameFullHeight(this)"></iframe>
    <?php
    }
    ?>
    </script>
    </
    div >
    <?=$notice["js"]?>
    < / body >
    < / html >
