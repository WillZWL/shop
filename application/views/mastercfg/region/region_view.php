<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . 'css/style.css' ?>">
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <style type="text/css">
        input.text {
            width: 200px;
        }

        input.button {
            width: 80px;
        }

        .bg0 {
            background-color: #ddddff;
        }

        .bg1 {
            background-color: #eeeeff;
        }

        select {
            width: 300px;
        }
    </style>
    <?php
    if ($editable) {
        ?>
        <script language="javascript" src="<?= base_url() . 'js/picklist.js' ?>"></script>
    <?php
    }
    ?>
</head>

<body topmargin="0" leftmargin="0">
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>

            <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["title"] ?></b></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>

    <?php
    if ($editable && $notice["img"] == "")
    {
    ?>
    <form action="<?=base_url()?>mastercfg/region/view/<?=$id?>" name="editform" method="post" style="padding:0; margin:0" onSubmit="return CheckForm(this)">
        <input type="hidden" name="id" value="<?php echo $region_obj->getId(); ?>">
        <?php
        }
        ?>
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">

            <tr>
                <td height="70" style="padding-left:8px">
                    <b style="font-size:14px"><?= $lang["header"] ?> - <?= $region_obj->getRegionName() ?></b><br>
                    <?= $lang["header_message"] ?><br>
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
            <tr class="header">
                <td height="20" width="250">&nbsp;&nbsp;<?= $lang["region_prop"] ?></td>
                <td><font color="#FFFFFF">&nbsp;&nbsp;<?= $lang["assoc_value"] ?></td>
            </tr>
            <tr>
                <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?= $lang["region_id"] ?></td>
                <td height="20" align="left" class="value">&nbsp;&nbsp;<?= $region_obj->getId() ?></td>
            </tr>
            <tr>
                <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?= $lang["region_name"] ?></td>
                <td height="20" align="left" class="value">&nbsp;&nbsp;<input type="text" name="region_name"
                                                                              value="<?= $region_obj->getRegionName() ?>" <?= (!$editable ? "readonly" : "") ?>
                                                                              notEmpty></td>
            </tr>
            <tr>
                <td height="20" width="250" class="field" align="right">&nbsp;&nbsp;<?= $lang["region_type"] ?></td>
                <td height="20" align="left" class="value">&nbsp;&nbsp;
                    <select name="region_type" style="width:200px;" <?= ($editable ? "" : "DISABLED") ?>>
                        <option
                            value="C" <?= ($region_obj->getType() == "C" ? "SELECTED" : "") ?>><?= $lang["courier"] ?></option>
                        <option
                            value="S" <?= ($region_obj->getType() == "S" ? "SELECTED" : "") ?>><?= $lang["sourcing"] ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td height="20" width="250" class="field" align="right">
                    &nbsp;&nbsp;<?= $lang["country_in_region"] ?></td>
                <td height="20" aalign="left" class="value" style="padding:6px;">
                    <table border="0" cellpadding="2" cellspacing="1" class="tb_list">
                        <tr class="header">
                            <?php if ($editable) { ?>
                                <th><font color="#ffffff"><?= (!$editable ? "&nbsp;" : $lang["not_in_region"]) ?></font>
                                </th>
                                <th>&nbsp;</th>
                            <?php } ?>
                            <th><font color="#ffffff"><?= $lang["in_region"] ?></font></th>
                        </tr>
                        <tr>
                            <?php  if ($editable) { ?>
                                <td><select name="countrylist" id='left' style='width:150px; height:300px;'
                                            multiple='multiple'><?php
                                        foreach ($country_ex as $key => $value) {
                                            echo '<option value=\'' . $key . '\'>' . $value . '</option>';
                                        }
                                        ?></select></td>
                                <td align="centre" valign="middle"><input type="button" value=">"
                                                                          onclick="AddOne(document.getElementById('left'),document.getElementById('right'));"
                                                                          class="button"><br><br><input type="button"
                                                                                                        value=">>"
                                                                                                        onclick="AddAll(document.getElementById('left'),document.getElementById('right'));"
                                                                                                        class="button"><br><br><br><input
                                        type="button" value="<"
                                        onclick="DelOne(document.getElementById('left'),document.getElementById('right'));"
                                        class="button"><br><br><input type="button" value="<<"
                                                                      onclick="DelAll(document.getElementById('left'),document.getElementById('right'));"
                                                                      class="button"></td>
                            <?php  } ?>
                            <td><select name="country[]" id='right' style='width:150px; height:300px;'
                                        multiple='multiple' <?= !$editable ? "disabled" : "" ?>><?php
                                    foreach ($country_in as $key => $value) {
                                        echo '<option value=\'' . $key . '\'>' . $value . '</option>';
                                    }
                                    ?></select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php
        if ($editable && $notice["img"] == "")
        {
        ?>
        <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
            <tr>
                <td align="left" style="padding-left:8px;"><input type="button" value="<?= $lang["back_to_main"] ?>"
                                                                  onClick="Redirect('<?= base_url() . "mastercfg/region/" ?>')">
                </td>
                <td align="right" style="padding-right:8px"><input type="button" value="<?= $lang["update_region"] ?>"
                                                                   onclick="SelectAllItems(document.editform.elements['country[]']); if(CheckForm(this.form)) this.form.submit();"
                                                                   style="font-size:11px"></td>
            </tr>
        </table>
        <input type="hidden" name="posted" value="1">
    </form>
<?php
}
if ($updated) {
    ?>
    <script language="javascript">
        alert("<?=$lang['update_successful']?>");
    </script>
<?php
}

?>
</div>
<?= $notice["js"] ?>
</body>
</html>