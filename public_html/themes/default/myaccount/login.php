<?php $this->load->view('header') ?>
    <div id="contact-container">
        <!-- aftership widget -->
        <div id="as-root"></div><script>(function(e,t,n){var r,i=e.getElementsByTagName(t)[0];if(e.getElementById(n))return;r=e.createElement(t);r.id=n;r.src="//apps.aftership.com/all.js";i.parentNode.insertBefore(r,i)})(document,"script","aftership-jssdk")</script>
        <!-- aftership widget -->
        <div id="content">
            <div id="register">
                <div id="left_side">
                    <h5 class="side_title"><?= _('Existing Customer') ?></h5>
                    <form name="fm_login" action="checkout_login" method="post" class="form-holder">
                    <p style="color:red;"><?=$login_failed_msg ?></p>
                        <ul>
                            <li>
                                <label><?= _('Email') ?> *</label>
                                <fieldset><input type="text" name="email" dname="<?= _('Email') ?>" value="" notEmpty validEmail/></fieldset>
                            </li>
                            <li>
                                <label><?= _('Password') ?> *</label>
                                <fieldset><input type="password" name="password" /></fieldset>
                            </li>
                             <p>
                                <button style="cursor:pointer;" onclick="return SubmitLogin();" class="btn"><?= _('Log In') ?></button> |
                                <a href="<?=base_url()?>login/forget_password?back=checkout" rel="lyteframe" rev="width: 600px; height:240px; scrolling: auto;padding: 40px;" title=""><?= _('Forgotten Password') ?></a>
                            </p>
                        </ul>
                        <input type="hidden" name="posted" value="1">
                    </form>

                    <br /><br />
                    <?= _('Order shipped? Track your delivery progress by entering your tracking number below:') ?><br /><br />
                    <div data-slug="" data-size="large" data-width="220" data-counter="true" class="as-track-button" data-tracking-number="<?=$trackno?>"></div>
                </div>

                <div id="right_side">
                    <h5 class="side_title"><?= _('New Customer') ?></h5>
                    <form name="fm_client" onSubmit="return CheckForm(this)" method="post" class="form-holder">
                    <p style="color:red;"><?=$reg_failed_msg?></p>
                        <ins class="no_top_padding"><?= _('Delivery Address') ?></ins>
                        <ul>
                            <li>
                                <label><?= _('Country') ?> *</label>
                                <select name="country_id" class="styled_select" id="delivery_country" onchange="update_field_attribute(this.value)">
                                    <?php foreach ($bill_to_list as $bill_country): ?>
                                    <option value="<?=$bill_country->getCountryId();?>"><?=$bill_country->getName();?></option>
                                    <?php endforeach ?>
                                </select>
                            </li>
                            <li>
                                <label><?= _('City') ?> *</label>
                                <fieldset class="medium"><input type="text" dname="<?= _('City') ?>" name="city" notEmpty isLatin/></fieldset>
                            </li>
                            <li>
                                <label><?= _('State') ?> <span id="asterisk">*</span></label>
                                <fieldset class="medium"><input type="text" dname="<?= _('State') ?>" name="state" id="state" notEmpty isLatin/></fieldset>
                            </li>
                            <li>
                                <label><?= _('Address') ?> *</label>
                                <fieldset class="large"><input type="text" name="address_1" dname="<?= _('Address Line 1') ?>" notEmpty isLatin maxlength="40"/></fieldset><br />
                                <fieldset class="large"><input type="text" name="address_2" dname="<?= _('Address Line 2') ?>" isLatin maxlength="40"/></fieldset>
                            </li>
                            <li>
                                <label><?= _('Postal code') ?><span id="postcode_asterisk">*</span></label>
                                <fieldset class="medium"><input type="text" name="postcode" id="postcode" validPostal="country_id" dname="<?= _('Postal code') ?>" isLatin/></fieldset>
                            </li>
                        </ul>
                        <ins><?= _('Telephone') ?></ins>
                        <ul>
                            <li>
                                <label><?= _('Country Code') ?> *</label>
                                <fieldset class="very_small"><input type="text" name="tel_1" notEmpty isLatin/></fieldset>
                            </li>
                            <li>
                                <label><?= _('Area Code') ?> *</label>
                                <fieldset class="very_small"><input type="text" name="tel_2" notEmpty isLatin/></fieldset>
                            </li>
                            <li>
                                <label><?= _('Number') ?> *</label>
                                <fieldset class="medium"><input type="text" name="tel_3" notEmpty isLatin/></fieldset>
                            </li>
                        </ul>
                        <ins><?= _('Recipient Details') ?></ins>
                        <ul>
                            <li>
                                <label><?= _('Title') ?> *</label>
                                <select name="title" class="styled_select" id="recipient_title">
                                    <?php foreach ($title as $title_row): ?>
                                    <option value="<?=$title_row?>"><?=$title_row?></option>
                                    <?php endforeach ?>
                                </select>
                            </li>
                            <li>
                                <label><?= _('Surname') ?> *</label>
                                <fieldset class="medium"><input type="text" dname="<?= _('Surname') ?>" name="surname" notEmpty/></fieldset>
                            </li>
                            <li>
                                <label><?= _('First Name') ?> *</label>
                                <fieldset class="medium"><input type="text" dname="<?= _('First Name') ?>" name="forename" notEmpty/></fieldset>
                            </li>
                            <li class='clear_b'>
                                <label><?= _('Company Name') ?></label>
                                <fieldset><input type="text" dname="<?= ('Company Name') ?>" name="companyname" /></fieldset>
                            </li>
                        </ul>
                        <ins><?= _('Email Address') ?></ins>
                        <ul>
                            <li>
                                <label><?= _('Email Address') ?> *</label>
                                <fieldset><input type="text" name="email" dname="<?= _('Email Address') ?>" notEmpty validEmail/></fieldset>
                            </li>
                            <li>
                                <label><?= _('Confirm Email Address') ?> *</label>
                                <fieldset><input type="text" name="confirm_email" dname="<?= _('Confirm Email Address') ?>" validEmail notEmpty match="email"/></fieldset>
                            </li>
                        </ul>

                        <ins><?= _('Password') ?></ins>
                        <p style="color:#5b5b5b;font-size:11px;line-height: 14px;"><?= _('Password must be between 6 to 20 characters in length. We recommend that you make your password secure by including a mixture of upper and lower case characters, numbers and symbols (e.g. #, @, !)') ?></p></br>
                        <ul>
                            <li>
                                <label><?= _('New Password') ?> *</label>
                                <fieldset><input type="password" name="password" dname="<?= _('New Password') ?>" minLen="6" maxLen="20" notEmpty/></fieldset>
                            </li>
                            <li>
                                <label><?= _('Confirm Password') ?> *</label>
                                <fieldset><input type="password" name="confirm_password" dname="<?= _('Confirm Password') ?>" match="password"  onpaste="return false;"/></fieldset>
                            </li>
                            <p><button type="submit" class="border-radius-2"><?= _('Submit') ?></button></p>
                        </ul>
                        <input type="hidden" name="posted" value="1">
                        <input type="hidden" name="page" value="register">

                    </form>
                </div>
            </div>
        </div>
    </div>
    <?=$notice['js']?>
    <script>
        if(document.getElementById("delivery_country").value == 'HK' || document.getElementById("delivery_country").value == 'IE') {
            jQuery("#postcode").removeAttr("notEmpty");
            jQuery("#postcode_asterisk").html("");
        } else {
            jQuery("#postcode").attr("notEmpty", "");
            jQuery("#postcode_asterisk").html("*");
        }
        function SubmitLogin() {
            if(CheckForm(document.fm_login)) {
                document.fm_login.submit();
                return true;
            }
        }
        function update_field_attribute(country_id) {
            if ((document.getElementById("delivery_country").value != 'US')
                && (document.getElementById("delivery_country").value != 'AU')
                && (document.getElementById("delivery_country").value != 'BE')
                && (document.getElementById("delivery_country").value != 'ES')
                && (document.getElementById("delivery_country").value != 'IT')
                && (document.getElementById("delivery_country").value != 'MX')
                && (document.getElementById("delivery_country").value != 'RU'))
            {
                jQuery("#state").removeAttr("notEmpty");
                jQuery("#asterisk").html("");
            } else {
                jQuery("#state").attr("notEmpty", "");
                jQuery("#asterisk").html("*");
            }

            if(country_id == 'HK' || country_id =='IE') {
                jQuery("#postcode").removeAttr("notEmpty");
                jQuery("#postcode_asterisk").html("");
            } else {
                jQuery("#state").attr("notEmpty", "");
                jQuery("#postcode_asterisk").html("*");
            }
        }
        update_field_attribute(document.getElementById("delivery_country").value);
    </script>
<?php $this->load->view('footer') ?>