<?php
    if (!$this->input->get("hidden"))
    {
        if ($all_trial && $all_virtual)
        {
            $require_client_detail = 0;
            $require_star = "";
            $js_card_func = "HideCard";
        }
        else
        {
            $require_client_detail = 1;
            $require_star = "*";
            $js_card_func = "ChangeCard";
        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="<?=base_url()?>css/style_facebook.css" rel="stylesheet" type="text/css" />
<script src="<?=base_url()?>js/jquery.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/main.js" type="text/javascript"></script>
<script type="text/javascript">LyteboxProps = {confirmClose: true}</script>
<script src="<?=base_url()?>js/lytebox_cv.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>checkout_facebook/js_credit_card" type="text/javascript"></script>
<script src="<?=base_url()?>js/checkform.js?lang=<?=get_lang_id()?>" type="text/javascript"></script>
<?$this->xajax->printJavascript();?>
<script src="/js/jquery.showLoading.min.js" type="text/javascript"></script>
<link href="/css/showLoading.css" rel="stylesheet" type="text/css" />
<script language="javascript">
createCallBack(['country_id', 'del_state', 'del_postcode']);
<!--
function NextPage()
{
    if (CheckForm(document.fm_pmgw))
    {
        document.getElementById('div_del_info_contact_detail').style.display = 'none';
        document.getElementById('div_payment_method_billing_info').style.display = 'block';

        DeliveryAddrAsBillingAddr();
    }
    return false;
}

function DeliveryAddrAsBillingAddr()
{
    var fm = document.fm_pmgw;

    if($("#billaddr").is(":checked"))
    {
        $("#forename").removeAttr('notEmpty');
        $("#surname").removeAttr('notEmpty');
        <?php
            if ($require_client_detail)
            {
        ?>
        $("#address_1").removeAttr('notEmpty');
        $("#city").removeAttr('notEmpty');
        $("#postcode").removeAttr('validPostal');
        <?php
            }
        ?>
        <?=$js_card_func?>(fm.del_country_id.value, document.getElementById('rs_card'));
        ChangeCardNoRadio(fm.del_country_id.value, window.parent.document.getElementById('rs_card'));
    }
    else
    {
        fm.forename.setAttribute('notEmpty', true);
        fm.surname.setAttribute('notEmpty', true);
        <?php
            if ($require_client_detail)
            {
        ?>
        fm.address_1.setAttribute('notEmpty', true);
        fm.city.setAttribute('notEmpty', true);
        fm.postcode.setAttribute('validPostal', 'country_id');
        <?php
            }
        ?>
        <?=$js_card_func?>(fm.country_id.value, document.getElementById('rs_card'));
        ChangeCardNoRadio(fm.country_id.value, window.parent.document.getElementById('rs_card'));
    }
}


function ChgDelCountry(ele)
{
    fm = ele.form;
    if (fm.billaddr.checked)
    {
        fm.country_id.value = ele.value;
    }
    fm.action='<?=base_url()?>checkout_facebook';
    fm.target='_parent';
    SetDelivery(fm);
    ChgStateLength(ele.value, fm.del_state);
    fm.submit();
}

function SetDelivery(fm)
{
    try
    {
        del_eles = parent.document.fm_delivery.delivery;
        if (del_eles)
        {
            if (del_eles.length == undefined)
            {
                del_eles.checked = true;
                del = del_eles.value;
            }
            else
            {
                for (var i=0; i<del_eles.length; i++)
                {
                    if (del_eles[i].checked)
                    {
                        del = del_eles[i].value;
                    }
                }
            }
            fm.delivery.value = del;
        }
    }
    catch(err){};
}

function ChgStateLength(country_id, state_obj)
{
    switch (country_id)
    {
        case "US":
        case "CA":
            state_obj.setAttribute('maxLength', 2);
            state_obj.value = state_obj.value.substr(0, 2);
            break;
        case "MX":
            state_obj.setAttribute('maxLength', 5);
            state_obj.value = state_obj.value.substr(0, 5);
            break;
        default:
            $("#"+state_obj.id).removeAttr('maxLength');
    }
}

function PreValidateForm(form)
{
    var target = ($("#del_state") ? "#del_state" : "#state");
    if ($(target)[0].tagName == 'SELECT')
    {
        var target_selected = target + ' :selected';

        if ($(target_selected).val() == '')
        {
            alert('Please select a state');
            return false;
        }
    }

    return true;
}
-->
</script>
</head>

<body topmargin='0' leftmargin='0' marginheight='0' marginwidth='0'>
    <form name="fm_pmgw" id="fm_pmgw" method="post" target="lbIframe">
        <div id="div_del_info_contact_detail">
            <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
                <col width="56%"><col><col width="40%">

                <tr>
                    <td colspan="3" height="30px"><font size="2"><strong>Delivery Information &amp; Contact Details</strong></font></td>
                </tr>
                <tr>
                    <td rowspan="2" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="1">
                            <col><col width="5"><col>

                            <tr>
                                <td colspan="3">Country*</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <select name="del_country_id" id="del_country_id" class="pmgw_input" onChange="if (this.value != '') { ChgDelCountry(this); }">
                                        <option value="">Select country</option>
                                        <?php
                                            $dc_selected = $_SESSION["POSTFORM"]["del_country_id"]?$_SESSION["POSTFORM"]["del_country_id"]:$thiscountry;
                                            foreach($sell_to_list as $cobj)
                                            {
                                        ?>
                                                <option value="<?=$cobj->get_id()?>" <?=$dc_selected == $cobj->get_id()?"SELECTED":""?>><?=$cobj->get_lang_name()?$cobj->get_lang_name():$cobj->get_name()?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>First Name*</td>
                                <td></td>
                                <td>Surname*</td>
                            </tr>
                            <tr>
                                <td><input type="text" class="pmgw_input" dname="First Name" notEmpty value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_first_name"])?>" id="del_first_name" name="del_first_name" /></td>
                                <td></td>
                                <td><input type="text" class="pmgw_input" dname="Surname" notEmpty value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_last_name"])?>" id="del_last_name" name="del_last_name" /></td>
                            </tr>

                            <tr>
                                <td colspan="3">Company Name</td>
                            </tr>
                            <tr>
                                <td colspan="3"><input type="text" class="pmgw_input" onblur="checkImmediate(this)" isLatin dname="Company Name" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_company"])?>" id="del_company" name="del_company" /></td>
                            </tr>

                            <tr>
                                <td colspan="3">Address*</td>
                            </tr>
                            <tr>
                                <td colspan="3"><input type="text" class="pmgw_input" onblur="checkImmediate(this)" isLatin dname="Address" <?=$require_client_detail ? "notEmpty" :"" ?> value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_address_1"])?>" id="del_address_1" name="del_address_1" maxLen="35" /></td>
                            </tr>

                            <tr>
                                <td colspan="3">Address Line 2</td>
                            </tr>
                            <tr>
                                <td colspan="3"><input type="text" class="pmgw_input" onblur="checkImmediate(this)" isLatin dname="Address Line 2" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_address_2"])?>" id="del_address_2" name="del_address_2" maxLen="35" /></td>
                            </tr>

                            <tr>
                                <td>City*</td>
                                <td></td>
                                <td>State*</td>
                            </tr>
                            <tr>
                                <td><input type="text" class="pmgw_input" onblur="checkImmediate(this)" isLatin dname="City" <?=$require_client_detail ? "notEmpty" :"" ?> value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_city"])?>" id="del_city" name="del_city" /></td>
                                <td></td>
                                <td>
                                    <div id="div_del_state">
                                        <input name="del_state" type="text" id="del_state" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_state"])?>" dname="State" isLatin onblur="checkImmediate(this);xajax.call('_check_surcharge', { parameters: [xajax.getFormValues('fm_pmgw'), document.fm_pmgw.del_surcharge.value, parent.document.getElementById('input_total').value], callback: x_CallBack.del_state});"/>
                                    </div>
                                    <span id="span_st_surcharge" style="color:#FF0000;"></span>
                                </td>
                            </tr>

                            <tr>
                                <td>Postal Code*</td>
                                <td></td>
                                <td>Telephone*</td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="del_postcode" type="text" class="pmgw_input" id="del_postcode" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["del_postcode"])?>" <?=$require_client_detail ? 'validPostal="del_country_id"' :"" ?> dname="Postal Code" isLatin onblur="checkImmediate(this);xajax.call('_check_surcharge', { parameters: [xajax.getFormValues('fm_pmgw'), document.fm_pmgw.del_surcharge.value, parent.document.getElementById('input_total').value], callback: x_CallBack.del_postcode});"/>
                                    <span id="span_pc_surcharge" style="color:#FF0000;"></span>
                                </td>
                                <td></td>
                                <td><input name="tel_3" type="text" class="pmgw_input" id="tel_3" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["tel_3"])?>" <?=$require_client_detail ? "notEmpty" :"" ?> dname="Telephone" /></td>
                            </tr>
                        </table>
                    </td>

                    <td rowspan="2"></td>

                    <td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
                            <tr>
                                <td>Email address*</td>
                            </tr>
                            <tr>
                                <td><input name="email" type="text" id="email" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["email"])?>" notEmpty validEmail dname="Email Address" /></td>
                            </tr>

                            <tr>
                                <td>Confirm email address</td>
                            </tr>
                            <tr>
                                <td><input type="text" class="pmgw_input" dname="Confirm email address" validEmail notEmpty match="email" id="confirm_email" name="confirm_email" onpaste="return false;" /></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="lbl_surcharge_cnt" style="color:#FF0000;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" name="del_surcharge" value="<?=$surcharge?>">
                                    <span id="lbl_surcharge"></span>
                                    <span style="color:#c90509; font-weight:bold;" id="span_surcharge"><?=$surcharge*1?platform_curr_format(PLATFORMID, $surcharge):""?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span id="span_total_plus_surcharge" style="font-weight: bold; color: rgb(0, 175, 240); font-size: 14px;"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td valign="bottom" align="right">
                        <input type="image" src="<?=base_url()?>images/btn_continue_<?=get_lang_id()?>.png" height="34" onClick="NextPage(); return false;" />
                    </td>
                </tr>
            </table>
        </div>

        <div id="div_payment_method_billing_info" style="display:none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <col><col width="5"><col>

                <tr>
                    <td colspan="3" height="30px"><font size="2"><strong>Payment Method &amp; Billing Information</strong></font></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
                            <col><col width="5"><col>

                            <tr>
                                <td colspan="3">
                                    <input type="checkbox" name="billaddr" id="billaddr" onclick="DeliveryAddrAsBillingAddr()" value="1"<?=$_SESSION["POSTFORM"]["billaddr"]?" CHECKED":""?> />&nbsp;
                                    <strong>Use delivery address as billing address</strong>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3">Country*</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <select name="country_id" id="country_id" class="pmgw_input" onChange="if (this.value != '') { <?=$js_card_func?>(this.value, document.getElementById('rs_card')); ChangeCardNoRadio(this.value, window.parent.document.getElementById('rs_card')); xajax.call('_check_state', { parameters: [this.value], callback: x_CallBack.country_id}); }">
                                        <option value="">Select country</option>
                                        <?php
                                            $bc_selected = $_SESSION["POSTFORM"]["country_id"]?$_SESSION["POSTFORM"]["country_id"]:$thiscountry;
                                            foreach($bill_to_list as $cobj)
                                            {
                                        ?>
                                            <option value="<?=$cobj->get_id()?>" <?=$bc_selected == $cobj->get_id()?"SELECTED":""?>><?=$cobj->get_lang_name()?$cobj->get_lang_name():$cobj->get_name()?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>First Name*</td>
                                <td></td>
                                <td>Surname*</td>
                            <tr>
                                <td><input name="forename" type="text" id="forename" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["forename"])?>" dname="First Name" /></td>
                                <td></td>
                                <td><input name="surname" type="text" id="surname" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["surname"])?>" dname="Surname" /></td>
                            </tr>

                            <tr>
                                <td colspan="3">Company Name</td>
                            </tr>
                            <tr>
                                <td colspan="3"><input name="companyname" type="text" id="companyname" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["companyname"])?>" dname="Company Name" /></td>
                            </tr>

                            <tr>
                                <td colspan="3">Address*</td>
                            </tr>
                            <tr>
                                <td colspan="3"><input name="address_1" type="text" id="address_1" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["address_1"])?>" dname="Address" maxLen="35"/></td>
                            </tr>

                            <tr>
                                <td colspan="3">Address Line 2</td>
                            </tr>
                            <tr>
                                <td colspan="3"><input name="address_2" type="text" id="address_2" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["address_2"])?>" maxLen="35"/></td>
                            </tr>

                            <tr>
                                <td>City*</td>
                                <td></td>
                                <td>State</td>
                            </tr>
                            <tr>
                                <td><input name="city" type="text" id="city" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["city"])?>" dname="City" /></td>
                                <td></td>
                                <td>
                                    <div id="div_state">
                                        <input name="state" type="text" id="state" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["State"])?>" />
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>Postal Code</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><input name="postcode" type="text" id="postcode" class="pmgw_input" value="<?=htmlspecialchars($_SESSION["POSTFORM"]["postcode"])?>" dname="Postal Code" /></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>

                    <td rowspan="2" background="/images/02category_56.gif" style="background-repeat:repeat">&nbsp;</td>

                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                            <tr>
                                <td><font size="2"><strong>Select payment method</strong></font></td>
                            </tr>
                            <tr>
                                <td height="5"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" id="rs_card">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td valign="bottom" align="right">
                        <input onClick="if(PreValidateForm(document.fm_pmgw) && CheckForm(document.fm_pmgw)){document.getElementById('a_check').onclick();} return false;" type="image" src="<?=base_url()?>images/btn_continue_<?=get_lang_id()?>.png" height="34" /><a id="a_check" href="<?=base_url()?>checkout_facebook/psform?hidden=1<?=$this->input->get("debug")?"&debug=1":""?>" rel="lyteframe" rev="width:<?=$pmgw_frame_width?>px; height:<?=$pmgw_frame_height?>px; scrolling: auto;padding: 40px;"></a>
                    </td>
                </tr>
            </table>
        </div>

        <input type="hidden" name="delivery" value="">
        <input type="hidden" name="p_enc" value="<?=$p_enc?>">
        <input type="hidden" name="all_trial" value="<?=$all_trial?>">
        <input type="hidden" name="all_virtual" value="<?=$all_virtual?>">
    </form>

    <script>
        var pmgw_fm = document.fm_pmgw;

        <?=$js_card_func?>(pmgw_fm.del_country_id.value, document.getElementById('rs_card'));
        ChangeCardNoRadio(pmgw_fm.del_country_id.value, window.parent.document.getElementById('rs_card'));

        xajax_check_state(pmgw_fm.country_id.value, '', pmgw_fm.state.value);
        xajax_check_state(pmgw_fm.del_country_id.value, 'del', pmgw_fm.del_state.value);
        xajax_check_surcharge(xajax.getFormValues('fm_pmgw'), pmgw_fm.del_surcharge.value, parent.document.getElementById('input_total').value);
        <?php
            if ($_SESSION["POSTFORM"]["payment_methods"])
            {
        ?>
            for (i=0; i<document.fm_pmgw.payment_methods.length; i++)
            {
                ele = document.fm_pmgw.payment_methods[i];
                if (ele.value == '<?=$_SESSION["POSTFORM"]["payment_methods"]?>')
                {
                    ele.checked = true;
                }
            }
        <?php
            }
        ?>
    </script>
</body>
</html>
<?php
    }
    else
    {
?>
<html>
<head>
<script src="<?=base_url()?>js/jquery.js" type="text/javascript"></script>
</head>
<body>
    <div align="center" width="100%">You will be redirected to the payment gateway website to complete your purchase... <img src="/images/loading.gif"></div>
    <div id="div_pmgw" name="div_pmgw" style="display:none"></div>
    <script language="javascript">
        var fm = parent.frames['psform'].document.fm_pmgw;
        var action = "<?=base_url()?>checkout_facebook/process_checkout/";
        var methods = fm.payment_methods;

        if (methods)
        {
            if (methods.length == undefined)
            {
                methods.checked = true;
                card_code = methods.value;
            }
            else
            {
                for (var i=0; i<methods.length; i++)
                {
                    if (methods[i].checked)
                    {
                        card_code = methods[i].value;
                    }
                }
            }
            if (card_code == 'mb_EPY')
            {
                fm.target = '_parent';
            }

            action += card_code;
            <?php
                if ($this->input->get("debug"))
                {
            ?>
                action += "/1";
            <?php
                }
            ?>
            fm.action = action;
            if (parent.frames['psform'].CheckForm(fm))
            {
                parent.frames['psform'].SetDelivery(fm);
                //fix for safari
                obj = parent.frames['psform'].$("#fm_pmgw").clone();
                $("#div_pmgw").append(obj);

                if($.browser.safari)
                {
                    $('form').removeAttr('target');
                    cur_fm = document.fm_pmgw;
                    if (cur_fm.country_id != undefined)
                    {
                        cur_fm.country_id.value = fm.country_id.value;
                    }
                    if (cur_fm.state != undefined)
                    {
                        cur_fm.state.value = fm.state.value;
                    }
                    cur_fm.del_country_id.value = fm.del_country_id.value;
                    cur_fm.del_state.value = fm.del_state.value;
                    cur_fm.submit();
                }
                else if($.browser.msie)
                {
                    fm.submit();
                }
                else
                {
                    document.fm_pmgw.submit();
                }
            }
            else
            {
                parent.frames['psform'].myLytebox.end();
            }
        }
        else
        {
            parent.frames['psform'].myLytebox.end();
        }
    </script>
</body>
</html>
<?php
    }
?>