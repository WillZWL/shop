<form name="fm_client" method="post" action="<?= $action ? $action : "" ?>" onSubmit="return CheckForm(this)">
    <table cellspacing="0" cellpadding="4" width="500">
        <tbody>
        <tr>
            <td colspan="2"><font color="#666666">Please ensure that you provide a valid and active e-mail
                    address</font></td>
        </tr>
        <tr>
            <td width="140"><span class="warn">*</span> Email address:</td>
            <td>
                <?php
                if (($this->input->post("email") && $this->input->post("from")) || $action) {
                    $email = $action ? $client_obj->get_email() : $this->input->post("email");
                    ?>
                    <input name="email" dname="Email Address" type="hidden"
                           value="<?= htmlspecialchars($email) ?>"><?= $email ?>
                <?php
                } else {
                    ?>
                    <input name="email" dname="Email Address" class="text"
                           value="<?= htmlspecialchars($client_obj->get_email()) ?>" notEmpty validEmail>
                <?php
                }
                ?>
            </td>
        </tr>
        <?php
        if ($action) {
            ?>
            <tr>
                <td><span class="warn">*</span> Old password:</td>
                <td><input type="password" dname="Password" name="old_password" class="text"></td>
            </tr>
        <?php
        } else {
            ?>
            <tr>
                <td><span class="warn">*</span> Confirm Email address:</td>
                <td>
                    <input type="text" dname="Confirm email address" validEmail notEmpty match="email" class="text"
                           id="confirm_email" name="confirm_email" onpaste="return false;"/>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="2"><font color="#666666">Password must be between 6 to 20 characters in length. We recommend
                    that you
                    make your password secure by including a mixture of upper and lower case
                    characters, numbers and symbols (e.g. #, @, !)</font></td>
        </tr>
        <tr>
            <td><? if (!$action) { ?><span class="warn">*</span> <? } ?>Choose a <?= $action ? "new " : "" ?>password:
            </td>
            <td><input type="password" minLen="6" maxLen="20" <?= $action ? "" : "notEmpty " ?>class="text"
                       name="password" dname="New Password"/></td>
        </tr>
        <tr>
            <td><? if (!$action) { ?><span class="warn">*</span> <? } ?>Re-enter <?= $action ? "new " : "" ?>password:
            </td>
            <td><input type="password" match="password" class="text" dname="Re-enter password" id="confirm_password"
                       name="confirm_password" onpaste="return false;"/></td>
        </tr>
        <tr>
            <td><span class="warn">*</span> Location:</td>
            <td>
                <select name="country_id" class="text" id="country_id">
                    <?php
                    if ($bill_to_list) {
                        $bc_selected = $client_obj->get_country_id();
                        foreach ($bill_to_list as $cobj) {
                            ?>
                            <option
                                value="<?= $cobj->get_id() ?>" <?= $bc_selected == $cobj->get_id() ? "SELECTED" : "" ?>><?= $cobj->get_lang_name() ? $cobj->get_lang_name() : $cobj->get_name() ?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><span class="warn">*</span> Name:</td>
            <td>
                <? $t_selected[$client_obj->get_title()] = " SELECTED"; ?>
                <select name="title">
                    <option value="Mr"<?= $t_selected["Mr"] ?>>Mr
                    <option value="Mrs"<?= $t_selected["Mrs"] ?>>Mrs
                    <option value="Miss"<?= $t_selected["Miss"] ?>>Miss
                    <option value="Dr"<?= $t_selected["Dr"] ?>>Dr
                </select>
                <input name="forename" dname="First Name" class="text2"
                       value="<?= htmlspecialchars($client_obj->get_forename()) ?>" notEmpty>
                <input name="surname" dname="Last Name" class="text2"
                       value="<?= htmlspecialchars($client_obj->get_surname()) ?>" notEmpty>
            </td>
        </tr>
        <tr>
            <td>&nbsp; Billing Company Name:</td>
            <td>
                <input name="companyname" dname="Billing Company Name" class="text"
                       value="<?= htmlspecialchars($client_obj->get_companyname()) ?>" isLatin>
            </td>
        </tr>
        <tr>
            <td><span class="warn">*</span> Billing Address:</td>
            <td>
                <input name="address_1" dname="Address Line 1" class="text"
                       value="<?= htmlspecialchars($client_obj->get_address_1()) ?>" notEmpty isLatin>
                <input name="address_2" dname="Address Line 2" class="text"
                       value="<?= htmlspecialchars($client_obj->get_address_2()) ?>" isLatin>
            </td>
        </tr>
        <tr>
            <td><span class="warn">*</span> City/Town:</td>
            <td>
                <input name="city" dname="City" class="text" value="<?= htmlspecialchars($client_obj->get_city()) ?>"
                       notEmpty isLatin>
            </td>
        </tr>
        <tr>
            <td><span class="warn">*</span> State:</td>
            <td>
                <input name="state" dname="State" class="text" value="<?= htmlspecialchars($client_obj->get_state()) ?>"
                       notEmpty isLatin>
            </td>
        </tr>
        <tr>
            <td><span class="warn">*</span> Postal Code:</td>
            <td>
                <input name="postcode" type="text" id="del_postcode" class="text"
                       value="<?= htmlspecialchars($client_obj->get_postcode()) ?>" validPostal="country_id"
                       dname="Postal Code" isLatin/>
            </td>
        </tr>
        <tr>
            <td>Telephone number: <br/>
                <font color="#666666" style="font-size: 9px;">(Please omit brackets and dashes)</font></td>
            <td>
                <font color="#666666" style="font-size: 9px;">Country Code - Area Code - Telephone</font><br/>
                <input value="<?= htmlspecialchars($client_obj->get_tel_1()) ?>" size="5" dname="Telephone Country Code"
                       name="tel_1"/> -
                <input value="<?= htmlspecialchars($client_obj->get_tel_2()) ?>" size="3" dname="Telephone Area Code"
                       name="tel_2"/> -
                <input value="<?= htmlspecialchars($client_obj->get_tel_3()) ?>" style="width: 190px;" dname="Telephone"
                       name="tel_3"/>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="checkbox" value="1"
                                   name="subscriber"<?= $client_obj->get_subscriber() ? " CHECKED" : "" ?> /> <strong>Keep
                    me updated with ValueBasket's special offers and newsletters.<strong></strong></strong></td>
        </tr>
        <tr>
            <td>
                <table width="143" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="39" align="center" background="/images/orderformbox_23.gif"
                            style="cursor:pointer;text-align:center;"
                            onClick="if(CheckForm(document.fm_client)) document.fm_client.submit()"><font
                                color="#ffffff"><strong>Sign up and continue</strong></font></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" colspan="2">&nbsp;</td>
        </tr>
        </tbody>
    </table>

    <input type="hidden" name="posted" value="1">
    <input type="hidden" name="page" value="register">
    <input type="hidden" name="back" value="<?= $back ?>">
</form>
