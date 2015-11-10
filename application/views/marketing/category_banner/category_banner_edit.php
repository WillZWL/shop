<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="imagetoolbar" content="no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . 'css/style.css' ?>">
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script language="javascript" src="<?= base_url() ?>/js/checkform.js"></script>
    <script language="javascript">
        function openWin(src) {
            window.open('<?=base_url()?>' + src);
        }
        function SaveChange(el) {
            el.form.submit();
        }
    </script>
</head>
<body topmargin="0" leftmargin="0">
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["banner_setup"] ?></b></td>
        </tr>
    </table>
    <form name="fm" method="POST" onSubmit="return CheckForm(this)" enctype="multipart/form-data">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="page_header">
            <tr>
                <td width="15%" class="field" align="right" style="padding-right:8px;">Please Select Language</td>
                <td width="20%" class="value">&nbsp;&nbsp;
                    <select
                        onChange='SaveChange(this);gotoPage("<?= base_url() . "marketing/category_banner/index/" ?>",this.value)'>
                        <option value="" style="padding-right:50px;"> -- Please Select --</option>
                        <?php
                        foreach ($language_list as $obj) {
                            ?>
                            <option value="<?= $obj->get_id() ?>"<?= ($obj->get_id() == $language_id ? "SELECTED" : "") ?>><?= $obj->get_name() ?></option><?php
                        }
                        ?>
                    </select>
                </td>
                <?php  if ($language_id) {
                    ?>
                    <td width="15%" class="field" align="right" style="padding-right:8px;" size='5'>Please Select
                        Country
                    </td>
                    <td width="20%" class="value" notEmpty>&nbsp;&nbsp;
                        <select name="country_list[]" multiple>
                            <?php
                            foreach ($country_list_w_lang as $obj) {
                                ?>
                                <option value="<?= $obj->get_id() ?>"SELECTED><?= $obj->get_name() ?></option><?php
                            }
                            ?>
                        </select>
                    </td>
                    <td width="15%" class="field" align="right" style="padding-right:8px;">Please Select Sub Category
                    </td>
                    <td width="15%" class="value" notEmpty>&nbsp;&nbsp;
                        <select name="sub_cat_id">
                            <?php
                            foreach ($sub_cat_list as $obj) {
                                ?>
                                <option value="<?= $obj->get_id() ?>"<?= ($obj->get_id() == $sub_cat_id ? "SELECTED" : "") ?>><?= $obj->get_name() ?></option><?php
                            }
                            ?>
                        </select>
                    </td>
                <?php
                }
                ?>
            </tr>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="1" class="tb_list">
            <?php  if ($language_id)
            {
            ?>
            <tr>
                <td width="15%" class="field" align="right" style="padding-right:8px;" rowspan='2'><b>Category
                        Banner</b><br>Dimension: 90px(W) X 87px(H)<br> Format: jpg, jpeg, gif, png<br></td>
                <td width="20%" class="value" rowspan='2'>
                    <input type="file" name="image">
                </td>
                <td class="field special" align="center">
                    <?php if ($updated_country) {
                        ?>Updated Language: <?= $updated_language->get_name();
                    }?><br>
                    <?php if ($updated_country) {
                        ?>Updated Countries:<br> <?php foreach ($updated_country AS $obj) {
                            echo $obj->get_name();
                            $i++;
                            if ($i % 5 == 0) {
                                ?><br><?php
                            }
                        }
                    }?></td>
            </tr>
            <tr>
                <td width="65%" class="value">
                    <?php
                    if ($updated_banner) {
                        $image_file = CAT_PH . $updated_banner . ".jpg";
                        if (file_exists($image_file)) {
                            ?>
                            <img src='<?= base_url() . $image_file ?>'>
                        <?php
                        }
                    }?>
                </td>
            </tr>

        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
            <tr>
                <td align="right" style="padding-right:8px">&nbsp;&nbsp;<input type="button"
                                                                               value="<?= $lang["update_banner"] ?>"
                                                                               onClick="if(CheckForm(this.form)) this.form.submit()">
                </td>
            </tr>
        </table>
        <?php
        }
        ?>
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="template" value="<?= $template_type ?>">
        <!-- <input type="hidden" name="type" id="type" value="">-->
    </form>
</div>
<?= $notice["js"] ?>
<script language="javascript" src="<?= base_url() ?>js/check_change.js"></script>
</body>
</html>