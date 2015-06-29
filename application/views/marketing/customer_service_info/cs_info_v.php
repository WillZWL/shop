<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?=$lang["page_title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url().'css/style.css'?>">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>/js/control_mce.js"></script>
<script>
    var a = new control_mce();
    a.load_default("simple");
</script>
<script language="javascript">
function SaveChange(el)
{
    el.form.submit();
}

function selectAll(f)
{
    var st = getEle(document.tform, "input", "name", "st_status");
    for(var key in st)
    {
        st[key].checked = true;
    }
}

function deSelectAll(f)
{
    var st = getEle(document.tform, "input", "name", "st_status");
    for(var key in st)
    {
        st[key].checked = false;
    }
}
</script>
<style>
.faq_clink{
    font-size:14px;
    line-height:25px;
    text-decoration:none;
    color:#444444;
}
.faq_qlink {
    list-style-type:none;
}
.faq_qlink a{
    font-size:18px;
    text-decoration:none;
    color:#666666;
    list-style-type:none;
}
.faq_clink a:hover{
    text-decoration:underline;
    color:#444444;
}
.faq_qlink a:hover{
    text-decoration:underline;
    color:#666666;
}
</style>
</head>

<?php
    $ar_status = array("1" => "active", "0" => "inactive");
    $ar_color = array("1" => "#009900", "0" => "#0000CC");
?>
<body topmargin="0" leftmargin="0">
    <div id="main">
        <?=$notice["img"]?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="30" class="title"><b style="font-size:16px;color:#000000"><?=$lang["page_title"]?></b></td>
                <td width="400" align="right" class="title"><input type="button" value="<?=$lang["status_manage"]?>" class="button" onclick="Redirect('<?=site_url('marketing/customer_service_info/status')?>')"></td>
            </tr>
            <tr>
                <td colspan="2" height="2" bgcolor="#000033"></td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="page_header">
            <tr>
                <td height="70" style="padding-left:8px;" align="left" valign="middle"><b style="font-size:14px;"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
            </tr>
        </table>
        <form action="" method="POST" name="tform" style="padding:0; margin:0" onSubmit="return CheckForm(this)" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?=$id?>">
            <table border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
            <col width="20%"><col width="80%">
                <tr class="header">
                    <td width="20%" align="center"><?=$lang["option"]?></td>
                    <td width="30%" align="center"><?=$lang["value"]?></td>
                </tr>
                <tr>
                    <td class="field field_text"></td>
                    <td class="value value_text">
                    <table align="center">
                    <tr>
                    <td style="border:0px">
                        <?=$lang["select_language"]?>:
                        <select onChange='gotoPage("<?=base_url()."marketing/customer_service_info/index/"?>",this.value)' ><option value=""> -- <?=$lang["please_select"]?> -- </option><?php
                            foreach($lang_list as $lang_obj)
                            {
                            ?><option value="<?=$lang_obj->get_id()?>" <?=($lang_obj->get_id()==$language_id?"SELECTED":"")?>><?=$lang_obj->get_name()?></option><?php
                            }
                            ?>
                        </select>
                    </td>
                    <td style="text-align:center;border:0px" width="50px">OR</td>
                    <td style="border:0px">
                        <?=$lang["select_country"]?>:
                        <select onChange='SaveChange(this);gotoPage("<?=base_url()."marketing/customer_service_info/index/ALL/"?>",this.value)' ><option value=""> -- <?=$lang["please_select"] ?> -- </option><?php
                            foreach($country_list as $obj)
                            {
                            ?><option value="<?=$obj->get_id()?>" <?=($obj->get_id()==$country_id?"SELECTED":"")?>><?=$obj->get_name()?></option><?php
                            }
                            ?>
                        </select>
                    </td>
                    </tr>
                    </table>
                    </td>
                </tr>
                <?if($country_id || $language_id)
                {
                ?>
                <!--
                <tr>
                    <td class="field field_text"><?=$lang["title"]?></td>
                    <td class="value value_text"><textarea class="input_format" name="title" rows="3" style="width:100%; resize:none;"><?if($csi_obj['WEBSITE']){ echo $csi_obj['WEBSITE']->get_title();}?></textarea></td>
                </tr>
                <tr>
                    <td class="field field_text"><?=$lang["content"]?></td>
                    <td class="value value_text"><textarea class="input_format" name="content" rows="3" style="width:100%; resize:none;"><?if($csi_obj['WEBSITE']){ echo $csi_obj['WEBSITE']->get_content();}?></textarea></td>
                </tr>
                <tr>
                    <td class="field field_text"><?=$lang["short_text_skype"]?></td>
                    <td class="value value_text"><textarea class="input_format" name="short_text[skype]" rows="8" style="width:100%; resize:none;"><?if($csi_obj['SKYPE']){ echo $csi_obj['SKYPE']->get_short_text();}?></textarea></td>
                </tr>
                <tr>
                    <td class="field field_text"><?=$lang["long_text_skype"]?></td>
                    <td class="value value_text"><textarea class="input_format" name="long_text[skype]" rows="8" style="width:100%; resize:none;"><?if($csi_obj['SKYPE']){ echo $csi_obj['SKYPE']->get_long_text();}?></textarea></td>
                </tr>
                -->
                <tr>
                    <td class="field field_text"><?=$lang["short_text_website"]?></td>
                    <td class="value value_text"><input class="input_format" name ="short_text[website]" value = "<?if($csi_obj['WEBSITE']){ echo $csi_obj['WEBSITE']->get_short_text();}?>" style="width:200px"></td>
                </tr>
                <!--
                <tr>
                    <td class="field field_text"><?=$lang["long_text_website"]?></td>
                    <td class="value value_text"><textarea class="input_format" name="long_text[website]" rows="8" style="width:100%; resize:none;"><?if($csi_obj['WEBSITE']){ echo $csi_obj['WEBSITE']->get_long_text();}?></textarea></td>
                </tr>
                -->
                <?php
                }
                ?>
            </table>
            <?php
            if($country_id != "" || $language_id != "")
            {
            ?>
            <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
                <tr>
                    <td align="right" style="padding-right:8px">
                        <input type="button" value="<?=$lang["update_var"]?>" style="font-size:11px" onClick="if(CheckForm(this.form)) this.form.submit();">
                    </td>
                </tr>
            </table>
            <?php
            }
            ?>
            <br>
            <input type="hidden" name="type" value="edithead">
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="lang_id" value='<?=$lang_id?>'>
        </form>
    </div>
</body>

</html>