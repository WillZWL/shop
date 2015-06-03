<link rel="stylesheet" href="/css/lytebox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/lytebox_ext.css" type="text/css" media="screen" />
<script src="/js/common.js" type="text/javascript"></script>
<script src="/js/lytebox_cv.min.js" type="text/javascript"></script>

<script>
	function SubmitLogin()
	{
		if(CheckForm(document.fm_login))
		{
			document.fm_login.submit();
			return true;
		}

		return false;
	}
</script>

<div class="p10">
    <div class="bordered mb20">
        <h2 class="section-title Rokkitt grey-title"><?=$lang_text['existing_customer']?></h2>

		<form name="fm_login" action="?<?=$back?>" method="post" class="p10 site-form" onSubmit="return SubmitLogin();">
			<p style="color:red;"><?=$login_failed_msg['notice']?></p>

            <fieldset>
                <legend><?=$lang_text['log_in']?></legend>
                <ol>
                    <li>
                        <label><?=$lang_text['email']?><sup>*</sup></label>
                        <input type="text" name="email" dname="<=$lang_text['email']?>" value="<?=$email?>" notEmpty validEmail/>
                    </li>

                    <li>
                        <label><?=$lang_text['password']?><sup>*</sup></label>
                        <input type="password" name="password" />
                    </li>  

                    <li class="m0">
                        <input type="submit" class="orange-gradient" style="cursor:pointer;" value="<?=$lang_text['log_in']?>" id=""/>
                        <a href="<?=$base_url?>forget_password?back=checkout" rel="lyteframe" rev="width: 600px; height:240px; scrolling: auto;padding: 40px;" class="p10"><?=$lang_text['forget_password']?></a>
						<input type="hidden" name="posted" value="1">
                    </li>                        
                </ol>
            </fieldset>
		</form>
    </div>

    <div class="bordered checkout-accordion">
        <h2 class="section-title Rokkitt grey-title"><?=$lang_text['new_customer']?></h2>

		<form name="fm_client" onSubmit="return CheckForm(this)" method="post" class="p10 site-form">
			<p style="color:red;"><?=$register_failed_msg['notice']?></p>

            <fieldset>
                <legend><?=$lang_text['new_customer']?></legend>
                <ol>
                    <li><strong class="Rokkitt"><?=$lang_text['delivery_address']?></strong></li>
                    <li>
                        <label><?=$lang_text['country']?><sup>*</sup></label>
                        <select name="country_id" class="styled_select" id="delivery_country" onchange="update_field_attribute(this.value)">
							<?php
								foreach ($bill_country_arr as $bill_country)
								{
                                    if($bill_country['id'] != "MX") // Temporary hide Mexico
                                    {
									   echo '<option value="' . $bill_country['id'] . '" ' . $bill_country['selected'] . '">' . $bill_country['display_name'] . '</option>';
                                    }
								}
							?>
						</select>                  
					</li>
                    <li>
                        <label><?=$lang_text['city']?><sup>*</sup></label>
                        <input type="text" dname="<?=$lang_text['city']?>" name="city" notEmpty isLatin/>
                    </li> 
                    <li>
                        <label><?=$lang_text['state']?></label>
                        <input type="text" dname="<?=$lang_text['state']?>" name="state" id="state" notEmpty isLatin/>
                    </li>          
                    <li>
						<label><?=$lang_text['address']?><sup>*</sup></label>
                        <input type="text" name="address_1" dname="<?=$lang_text['addr_line_1']?>" notEmpty isLatin/>
						<input type="hidden" name="address_2" value="" />
                    </li>
                    <li>
                        <label><?=$lang_text['postal_code']?></label>
                        <input type="text" name="postcode" id="postcode" validPostal="country_id" dname="<?=$lang_text['postal_code']?>" isLatin/>
                    </li>

                    <li><strong><?=$lang_text['phone']?></strong></li>    
                    <li class="phone-details">
						<span>
                            <label><?=$lang_text['country_code']?><sup>*</sup>:</label>
                            <input type="text" name="tel_1" notEmpty isLatin/>
                        </span>
                        <span>
                            <label><?=$lang_text['area_code']?><sup>*</sup>:</label>
                            <input type="text" name="tel_2" notEmpty isLatin/>
                        </span>
                        <span>
                            <label><?=$lang_text['number']?><sup>*</sup>:</label>
                            <input type="text" name="tel_3" notEmpty isLatin/>
                        </span>                                
                    </li>
                    <li>&nbsp;</li>

                    <li><strong class="Rokkitt"><?=$lang_text['recipient_details']?></strong></li>
                    <li>
                        <label><?=$lang_text['title']?><sup>*</sup></label>
                        <select name="title" id="recipient_title">
							<?php
								foreach ($title as $obj)
								{
									echo '<option value="' . $obj['value_EN'] . '" ' . $obj['selected'] . '>' . $obj['value'] . '</option>';
								}
							?>
                        </select>
                    </li>
                    <li>
                        <label><?=$lang_text['surname']?><sup>*</sup></label>
                        <input type="text" dname="<?=$lang_text['surname']?>" name="surname" notEmpty/>
                    </li> 
                    <li>
                        <label><?=$lang_text['first_name']?><sup>*</sup></label>
                        <input type="text" dname="<?=$lang_text['first_name']?>" name="forename" notEmpty/>
                    </li> 
                    <li>
                        <label><?=$lang_text['company_name']?></label>
                        <input type="text" dname="<?=$lang_text['company_name']?>" name="companyname" />
                    </li>
<?php 
	if ($show_client_id)
	{
?>
                    <li>
                        <label><?=$lang_text['client_id_no']?> <a href="javascript::void(0)" title="<?php print $lang_text['client_id_title']?>">[?]</a></label>
                        <input type="text" dname="<?=$lang_text['client_id_no']?>" name="client_id_no" />
                    </li>
<?php 
	}
?>
                    <li>&nbsp;</li>
                    <li><strong class="Rokkitt"><?=$lang_text['email_head']?></strong></li>
                    <li>
                        <label><?=$lang_text['email_addr']?><sup>*</sup></label>
						<input type="text" name="email" dname="<?=$lang_text['email_addr']?>" notEmpty validEmail/>
                    </li> 
                    <li>
                        <label><?=$lang_text['confirm_email']?><sup>*</sup></label>
                        <input type="text" name="confirm_email" dname="<?=$lang_text['confirm_email']?>" validEmail notEmpty match="email"/>
                    </li>      
                    <li>&nbsp;</li>

                    <li>
                        <strong class="Rokkitt"><?=$lang_text['password']?></strong>
                        <span class="tips"><?=$lang_text['password_comment']?></span>
                    </li>
                    <li>
                        <label><?=$lang_text['password']?><sup>*</sup></label>
                        <input type="password" name="password" dname="<?=$lang_text['new_password']?>" minLen="6" maxLen="20" notEmpty/>
                    </li> 
                    <li>
                        <label><?=$lang_text['confirm_password']?><sup>*</sup></label>
                        <input type="password" name="confirm_password" dname="<?=$lang_text['reenter_password']?>" match="password"  onpaste="return false;"/>
                    </li>
                    <li class="tac m0">
						<label class="clicker p10">
                            <input type="checkbox" name="" value="" id=""/>
                            <?=$lang_text['subscribe']?>
                        </label>
                        <br class="clear"/>
                        <input type="submit" class="orange-gradient" value="<?=$lang_text['submit']?>" id=""/>
						<input type="hidden" name="posted" value="1">
						<input type="hidden" name="page" value="register">
                    </li>
                </ol>
            </fieldset>
        </form>                 
    </div>    
</div>

<script>
	if(document.getElementById("delivery_country").value != 'US')
	{
		$("#state").removeAttr("notEmpty");
		$("#asterisk").html("");
	}
	else
	{
		$("#state").attr("notEmpty", "");
		$("#asterisk").html("*");
	}

	if(document.getElementById("delivery_country").value == 'HK' || document.getElementById("delivery_country").value == 'IE')
	{
		$("#postcode").removeAttr("notEmpty");
		$("#postcode_asterisk").html("");
	}
	else
	{
		$("#postcode").attr("notEmpty", "");
		$("#postcode_asterisk").html("*");
	}

	function update_field_attribute(country_id)
	{
		if(country_id != 'US')
		{
			$("#state").removeAttr("notEmpty");
			$("#asterisk").html("");
		}
		else
		{
			$("#state").attr("notEmpty", "");
			$("#asterisk").html("*");
		}

		if(country_id == 'HK' || country_id =='IE')
		{
			$("#postcode").removeAttr("notEmpty");
			$("#postcode_asterisk").html("");
		}
		else
		{
			$("#state").attr("notEmpty", "");
			$("#postcode_asterisk").html("*");
		}
	}
</script>