<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body topmargin="0" leftmargin="0" style="width:1058px;">
<div id="main" style="width:1058px;">
    <?= $notice["img"] ?>
    <?php
    if ($canedit && $this->input->get('platform') <> "") :
    ?>
    <form name="catview" action="<?= base_url() . "marketing/category/update_scpv/" ?>" method="POST"
          onSubmit="CheckForm(this);">
        <?php
        endif;
        ?>
        <div style="width:100%; background-color:#000000; height:2px;">&nbsp;</div>
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td height="70" style="padding-left:8px">
                    <b style="font-size:14px"><?= $lang["header"] . " " . $cat_obj->getName() ?></b>
                    <br><?= $lang["select_sp"] ?>
                    <select style="width:250px;" onChange="gotoPage('<?= base_url() ?>marketing/category/view_scpv/?subcat_id=<?= $this->input->get("subcat_id") ?>&platform=',this.value)">
                        <option value="">-- <?= $lang["please_select"] ?> --</option>
                        <?php
                        foreach ($sp_list as $obj) :
                            ?>
                            <option value="<?= $obj->getSellingPlatformId() ?>" <?= ($this->input->get('platform') == $obj->getSellingPlatformId() ? "SELECTED" : "") ?>><?= $obj->getSellingPlatformId() . " - " . $obj->getName(); ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </td>
                <td>
                    <a href="#"
                       OnClick="javascript:window.open('/marketing/pricing_tool_website/auto_pricing_by_platform/<?= $this->input->get('platform') ?>','','height=120,width=640');">
                        Update auto-priced SKUs
                    </a>
                </td>
            </tr>
        </table>
        <?php
        if ($this->input->get('platform') <> "") :
        ?>
        <table border="0" cellpadding="0" cellspacing="1" height="20" class="page_header" width="100%">
            <tr class="header">
                <td width="150" height="20">&nbsp;&nbsp;<?= $lang["scpv_info"] ?></td>
                <td height="20" valign="middle">&nbsp;&nbsp;<?= $lang["assoc_value"] ?></td>
                <?php
                ?></font></td>
            </tr>
            <tr>
                <td width="142" valign="top" class="field" align="right">&nbsp;&nbsp;<?= $lang["scpv_currency"] ?></td>
                <td align="left" class="value">&nbsp;&nbsp;
                    <select name="currency" <?= ($canedit ? "" : "DISABLED") ?>>
                        <?php
                        foreach ($currency_list as $obj) :
                            ?>
                            <option value="<?= $obj->getCurrencyId() ?>" <?= ($obj->getCurrencyId() == $scpv_obj->getCurrencyId() ? "SELECTED" : "") ?>><?= $obj->getCurrencyId() . " - " . $obj->getName() ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="right">
                    &nbsp;&nbsp;<?= $lang["scpv_commission"] ?></td>
                <td height="20" valign="top" class="value" align="left">&nbsp;&nbsp;
                    <input type="text" name="commission" value="<?= ($type == "insert" ? "" : number_format($scpv_obj->getPlatformCommissionPercent(), 2, '.', ' ')) ?>" <?= $canedit ? "" : "READONLY" ?> isNumber min=0>%
                </td>
            </tr>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="right">
                    &nbsp;&nbsp;<?= $lang["fixed_fee"] ?></td>
                <td height="20" valign="top" class="value" align="left">&nbsp;&nbsp;
                    <input type="text" name="fixed_fee" value="<?= ($type == "insert" ? "" : number_format($scpv_obj->getFixedFee(), 2, '.', ' ')) ?>" <?= $canedit ? "" : "READONLY" ?> isNumber min=0>
                </td>
            </tr>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="right">
                    &nbsp;&nbsp;<?= $lang["profit_margin"] ?></td>
                <td height="20" valign="top" class="value" align="left">&nbsp;&nbsp;
                <input type="text" name="profit_margin" value="<?= ($type == "insert" ? "" : number_format($scpv_obj->getProfitMargin(), 2, '.', ' ')) ?>" <?= $canedit ? "" : "READONLY" ?> isNumber min=0>%
                </td>
            </tr>
            <input type="hidden" name="dlvry_chrg" value="0">
        </table>
        <?php
        if ($canedit) :
        ?>
        <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
            <tr>
                <td align="left" style="padding-left:8px;" width="50%" style="padding-left:8px"></td>
                <td align="right" style="padding-right:8px"><input type="button"
                                                                   value="<?= ($action == "update" ? $lang["update_scpv"] : $lang["add_scpv"]) ?>"
                                                                   style="font-size:11px"
                                                                   onClick="if(CheckForm(this.form)) document.catview.submit();">
                </td>
            </tr>
        </table>
        <input type="hidden" name="type" value="<?= $action ?>">
        <input type="hidden" name="subcat_id" value="<?= $this->input->get('subcat_id') ?>">
        <input type="hidden" name="platform" value="<?= $this->input->get('platform') ?>">
        <input type="hidden" name="posted" value="1">
    </form>
<?php
        endif;
endif;
?>
</div>
<?php

if ($this->input->get('d') == 1) :
    if ($this->input->get("dtype") == "insert")
    :
        ?>
        <script language="javascript">alert('<?=$lang["add_done"]?>')</script><?php
    elseif ($this->input->get("dtype") == "update") :
    ?>
    <script language="javascript">alert('<?=$lang["update_done"]?>')</script>
<?php
    else :

    endif;
?></script><?php
endif;
?>
<?=$notice["js"]?>
</body >
</html >