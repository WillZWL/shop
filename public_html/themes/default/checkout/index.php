<?php $this->load->view('header') ?>
<!-- header -->
<div class="main-columns container">
    <div class="row">
    <div id="sidebar-main" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?=_("Checkout")?></h1>
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
            <div class="panel-heading noicon">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-checkout-option">
                        <?=_("Step 1: Checkout Options")?>
                        <i class="fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>
            <div class="panel-collapse collapse in" id="collapse-checkout-option">
                <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-6" id="newCustomerBlock">
                        <h2><?=_("New Customer")?></h2>
                        <p><?=_("Guest Checkout")?>:</p>
                        <div class="radio">
                        </div>
                        <input type="button" class="btn btn-primary" data-loading-text="<?=_("Loading...")?>" id="button-account" value="<?=_("Continue")?>">
                      </div>
                      <div class="col-sm-6">
                        <form action="/checkout/login" method="post" id="loginForm" name="loginForm">
                            <h2><?=_("Returning Customer")?></h2>
                            <p><?=_("I am a returning customer")?></p>
                            <div class="form-group">
                              <label for="loginEmail" class="control-label"><?=_("E-Mail")?></label>
                              <input type="text" class="form-control" id="loginEmail" placeholder="<?=_("E-Mail")?>" value="" name="loginEmail">
                            </div>
                            <div id="loginPasswordBlock" class="form-group">
                              <label for="loginPassword" class="control-label"><?=_("Password")?></label>
                              <input type="password" class="form-control" id="loginPassword" placeholder="<?=_("Password")?>" value="" name="loginPassword">
                              <a href="/login/forget-password?back=checkout"><?=_("Forgotten Password")?></a></div>
                            <input type="submit" class="btn btn-primary" data-loading-text="<?=_("Loading...")?>" id="loginButton" value="<?=_("Login")?>">
                            <input type="button" class="btn btn-primary hidden" data-loading-text="<?=_("Loading...")?>" id="loggedInButton" value="<?=_("You have logged in, please click here to continue")?>">
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="/checkout/payment<?php print (($debug)?"/1":"")?>" method="POST" id="checkoutForm" name="checkoutForm">
        <div class="panel panel-default">
            <div class="panel-heading noicon">
                <h4 class="panel-title">
                    <a id="payment-address-header" class="accordion-toggle" href="#collapse-payment-address">
                        <?=_("Step 2: Account &amp; Billing Details")?>
                        <i class=""></i>
                    </a>
                </h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-payment-address">
                <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <fieldset id="account">
                          <legend><?=_("Your Personal Details")?></legend>
                          <div class="form-group required">
                              <label for="billingFirstName" class="control-label"><?=_("First Name")?></label>
                              <input type="text" class="form-control" maxlength=50 id="billingFirstName" placeholder="<?=_("First Name")?>" value="" name="billingFirstName" />
                          </div>
                          <div class="form-group required">
                              <label for="billingLastName" class="control-label"><?=_("Last Name")?></label>
                              <input type="text" class="form-control" maxlength=50 id="billingLastName" placeholder="<?=_("Last Name")?>" value="" name="billingLastName" />
                          </div>
                          <div class="form-group required">
                          <label for="billingEmail" class="control-label"><?=_("E-Mail")?></label>
                          <input type="text" class="form-control" id="billingEmail" placeholder="<?=_("E-Mail")?>" value="" name="billingEmail" /></div>
                          <div class="form-group required">
                              <label class="control-label"><?=_("Telephone")?></label>
                              <div>
                                  <input type="text" maxlength=3 class="form-control" id="billingTelCountryCode" placeholder="<?=_("Country Code")?>" value="" name="billingTelCountryCode" />
                                  <input type="text" maxlength=3 class="form-control" id="billingTelAreaCode" placeholder="<?=_("Area Code")?>" value="" name="billingTelAreaCode" />
                                  <input type="text" maxlength=32 class="form-control" id="billingTelNumber" placeholder="<?=_("Telephone")?>" value="" name="billingTelNumber" />
                              </div>
                          </div>
                        </fieldset>
                        <fieldset id="passwordSection">
                          <legend><?=_("Your Password")?></legend>
                          <div class="form-group">
                              <label for="billingPassword" class="control-label"><?=_("Password")?></label>
                              <input type="password" class="form-control" id="billingPassword" placeholder="<?=_("Password")?>" value="" name="billingPassword" /></div>
                          <div class="form-group">
                              <label for="billingConfirmPassword" class="control-label"><?=_("Password Confirm")?></label>
                              <input type="password" class="form-control" id="billingConfirmPassword" placeholder="<?=_("Password Confirm")?>" value="" name="billingConfirmPassword" />
                          </div>
                        </fieldset>
                      </div>
                      <div class="col-sm-6">
                        <fieldset id="address" class="required">
                          <legend><?=_("Your Address")?></legend>
                          <div class="form-group">
                              <label for="billingCompany" class="control-label"><?=_("Company")?></label>
                              <input type="text" class="form-control" maxlength=50 id="billingCompany" placeholder="<?=_("Company")?>" value="" name="billingCompany" />
                          </div>
                          <div class="form-group required">
                              <label for="billingAddress1" class="control-label"><?=_("Address Line 1")?></label>
                              <input type="text" class="form-control" maxlength=1024 id="billingAddress1" placeholder="<?=_("Address Line 1")?>" value="" name="billingAddress1" />
                          </div>
                          <div class="form-group">
                              <label for="billingAddress2" class="control-label"><?=_("Address Line 2")?></label>
                              <input type="text" class="form-control" maxlength=1024 id="billingAddress2" placeholder="<?=_("Address Line 2")?>" value="" name="billingAddress2" />
                          </div>
                          <div class="form-group required">
                              <label for="billingCity" class="control-label"><?=_("City")?></label>
                              <input type="text" class="form-control" id="billingCity" placeholder="<?=_("City")?>" value="" name="billingCity" />
                          </div>
                          <div class="form-group required">
                              <label for="billingPostal" class="control-label"><?=_("Post Code")?></label>
                              <input type="text" class="form-control" id="billingPostal" placeholder="<?=_("Post Code")?>" value="" name="billingPostal" />
                          </div>
                          <div class="form-group required">
                              <label for="billingCountry" class="control-label"><?=_("Country")?></label>
                              <select class="form-control" id="billingCountry" name="billingCountry">
                                <option value="<?php print $billing["countryId"]?>"><?php print $billing["countryName"]?></option>
                              </select>
                          </div>
                          <div class="form-group required">
                              <label for="billingState" class="control-label"><?=_("County / State")?></label>
                              <select class="form-control" id="billingState" name="billingState">
                                  <option value=""> -- <?=_("select County / State")?> -- </option>
                                  <?php foreach ($billingStateList as $stateObj): ?>
                                    <option value="<?php print $stateObj->getStateId();?>"><?php print $stateObj->getName();?></option>
                                  <?php endforeach ?>
                              </select>
                          </div>
<!--
                          <div class="form-group required">
                            <label>
                                <input type="checkbox" checked="checked" value="1" name="shipping_address">
                                    My delivery and billing addresses are the same.
                            </label>
                          </div>
-->
                        </fieldset>
                      </div>
                    </div>
                    <div class="buttons">
                        <div class="pull-right">
                            <input id="button-payment-address" class="btn btn-primary" type="button" data-loading-text="<?=_("Loading...")?>" value="<?=_("Continue")?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading noicon">
            <h4 class="panel-title">
                <a id="shipping-address-header" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-shipping-address">
                    <?=_("Step 3: Delivery Details")?>
                    <i class=""></i>
                </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-shipping-address">
            <div class="panel-body">
                  <div class="radio">
                    <label>
                    <!-- <input type="radio" checked="checked" value="existing" name="shipping_address" />--> <?=_("We could only ship to the billing address!")?></label>
                  </div>
                  <div class="radio">
                    <label>
                    <!-- <input disabled type="radio" value="new" name="shipping_address" /> I want to use a new address</label> -->
                  </div>
                  <br />
                  <div style="display: none;" id="shipping-new">
                    <div class="form-group required">
                      <label for="input-shipping-firstname" class="col-sm-2 control-label">First Name</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-firstname" placeholder="First Name" value=""
                        name="firstname" />
                      </div>
                    </div>
                    <div class="form-group required">
                      <label for="input-shipping-lastname" class="col-sm-2 control-label">Last Name</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-lastname" placeholder="Last Name" value=""
                        name="lastname" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="input-shipping-company" class="col-sm-2 control-label">Company</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-company" placeholder="Company" value="" name="company" />
                      </div>
                    </div>
                    <div class="form-group required">
                      <label for="input-shipping-address-1" class="col-sm-2 control-label">Address Line 1</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-address-1" placeholder="Address Line 1" value="" name="address_1" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="input-shipping-address-2" class="col-sm-2 control-label">Address Line 2</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-address-2" placeholder="Address Line 2" value="" name="address_2" />
                      </div>
                    </div>
                    <div class="form-group required">
                      <label for="input-shipping-city" class="col-sm-2 control-label">City</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-city" placeholder="City" value="" name="city" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="input-shipping-postcode" class="col-sm-2 control-label">Post Code</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-postcode" placeholder="Post Code" value="B33 8TH"
                        name="postcode" />
                      </div>
                    </div>
                    <div class="form-group required">
                      <label for="input-shipping-country" class="col-sm-2 control-label">Country</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="input-shipping-country" name="country_id">
                          <option value="">--- Please Select ---</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group required">
                      <label for="input-shipping-zone" class="col-sm-2 control-label">Region / State</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="input-shipping-zone" name="zone_id">
                          <option value="">--- Please Select ---</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="buttons clearfix">
                    <div class="pull-right">
                      <input type="button" class="btn btn-primary" data-loading-text="<?=_("Loading...")?>" id="button-shipping-address" value="<?=_("Continue")?>" />
                    </div>
                  </div>
            </div>
          </div>
        </div>
<!--
        <div class="panel panel-default">
          <div class="panel-heading noicon">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-shipping-address">
                   Step 4: Delivery Method
                   <i class=""></i>
                </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-shipping-method">
            <div class="panel-body"></div>
          </div>
        </div>
-->
        <div class="panel panel-default">
          <div class="panel-heading noicon">
            <h4 class="panel-title">
                <a id="payment-method-header" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-payment-method">
                   <?=_("Step 4: Payment Method")?>
                   <i class=""></i>
                </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-payment-method">
            <div class="panel-body">
                <div class="form-group required">
<?php foreach ($paymentOption as $card): ?>
                <div style="float:left;padding-right:2px;">
                    <input id="<?=$card->getCardCode()?>" type="radio" name="paymentCard" value="<?php print $card->getCardCode() . "%%" . $card->getCardId() . "%%" . $card->getPaymentGatewayId()?>">
                    <?php print "<img alt='" . $card->getCardName() . "' title='" . $card->getCardName() . "' src='" . $card->getCardImage() . "'/>"; ?>
                </div>
<?php endforeach ?>
<?php if (!$paymentOption): ?>
<?php print _("Please contact our CS!")?>
<?php endif; ?>
                <div class="clearfix" />
                <div class="buttons">
                    <div class="pull-right">
                        <input type="hidden" name="formSalt" id='formSalt' value="<?=$formSalt;?>">
                        <input type="hidden" name="cybersourceFingerprint" id='cybersourceFingerprint' value="<?=$cybersourceFingerprint;?>">
                        <input type="submit" class="btn btn-primary" data-loading-text="<?=_("Loading...")?>" name="checkoutNow" id="checkoutNow" value="<?=_("Continue")?>" />
                    </div>
                </div>
                </div>
            </div>
          </div>
        </div>
        </form>
      </div>
      </div>
   </div>
</div>
</div>
<script type="text/javascript">
function displayButton(id, show)
{
    if (show == 1)
    {
        $("#" + id).show();
        $("#" + id).attr("disabled", false);
        $("#" + id).removeClass("disabled");
    }
    else
        $("#" + id).hide();
}

function displayCheckoutNowButton(show)
{
    displayButton("checkoutNow", show);
}

function displayLoginButton(show)
{
    displayButton("loginButton", show);
}

$(document).ready(function() {
    if ($("#billingState option").length == 1) {
        $('#billingState').prop("disabled", "disabled");
        $('#billingState').parent().removeClass("required");
    }
    $("#loggedInButton").hide();
//not allow to toggle block by skipping step
    $("#payment-method-header").attr("data-toggle", "");
    $("#payment-address-header").attr("data-toggle", "");
    $("#shipping-address-header").attr("data-toggle", "");
    validateCheckout();
    validateLogin();
<?php if ($client): ?>
    populateClientData(<?=$client?>);
<?php endif; ?>
});

function activatePaymentBlock()
{
    var targetBlock = $('a[href=\'#collapse-payment-address\']');
    activatedBlock(targetBlock, $("#collapse-checkout-option"));
}

// Checkout
$(document).delegate('#button-account', 'click', function() {
    activatePaymentBlock();
});
$(document).delegate('#loggedInButton', 'click', function() {
    activatePaymentBlock();
});
$(document).delegate('#button-payment-address', 'click', function() {
    var targetBlock = $('a[href=\'#collapse-shipping-address\']');
    if (validateBlock("collapse-payment-address"))
        activatedBlock(targetBlock, $("#collapse-payment-address"));
});
$(document).delegate('#button-shipping-address', 'click', function() {
    var targetBlock = $('a[href=\'#collapse-payment-method\']');
    displayCheckoutNowButton(1);
    activatedBlock(targetBlock, $("#collapse-shipping-address"));
});

function checkRemoteSurcharge()
{
    standardWaitingScreen.showPleaseWait();
    var postData = {billingPostal: $("#billingPostal").val(), billingCountry: $("#billingCountry").val()};
    var formURL = "/checkout/check-delivery-charge";
    $.ajax({
        type        : "POST",
        url         : formURL,
        data        : postData,
        dataType    : "json",
        encode      : true
    })
    .done(function(data) {
        standardWaitingScreen.hidePleaseWait();
        if (data.responseCode < 0) {
            if (data.error) {
                alert(data.error);
            }
        } else {
            if (data.surcharge > 0) {
                alert("<?php print _("Please note: remote surcharge has been applied to your basket!") ?>");
            }
            updateBasketIcon(data.newTotalAmount);
        }
    })
    .fail(function(data) {
        if (data.responseCode)
            console.log(data.responseCode);
        if (data.error)
            alert(data.error);
        standardWaitingScreen.hidePleaseWait();
    });
}

function activatedBlock(obj, hideBlock)
{
    if (obj.attr("id") == "payment-method-header") {
        checkRemoteSurcharge();
    }
    $(".accordion-toggle").addClass("collapsed");
    $(".in").removeClass("in");
    obj.attr("data-parent", "#accordion");
    obj.attr("data-toggle", "collapse");
    hideBlock.removeClass("in");
    obj.find("i").addClass("fa fa-caret-down");
    obj.trigger('click');
}

$('input[name=\'shipping_address\']').on('change', function() {
    if (this.value == 'new') {
        $('#shipping-existing').hide();
        $('#shipping-new').show();
    } else {
        $('#shipping-existing').show();
        $('#shipping-new').hide();
    }
});

function validateBlock(blockId)
{
    var fv = $("#checkoutForm").data("formValidation") // FormValidation instance
    $checkoutformBlock = $("#" + blockId);

    // Validate the container
    fv.validateContainer($checkoutformBlock);

    var isValidStep = fv.isValidContainer($checkoutformBlock);
    if (isValidStep === false || isValidStep === null)
    {
        $fieldLists = fv.getInvalidFields();
        $fieldLists.each(function() {
            $(this).focus();
            return false;
        });
        return false;
    }

    return true;
}

function loginSuccessful(email)
{
    $("#billingEmail").val(email);
    $("#loginEmail").val(email);
    $("#billingEmail").attr("disabled", true);
    $("#loginEmail").attr("disabled", true);
    $("#loginPasswordBlock").hide();
    $("#loginPassword").attr("disabled", true);
    $("#passwordSection").hide();
    activatePaymentBlock($("#collapse-checkout-option"));
    $("#newCustomerBlock").hide();
    $("#loggedInButton").show();
    $("#loginButton").hide();
}

function populateClientData(data)
{
    if (data.Forename)
        $("#billingFirstName").val(data.Forename);
    if (data.Surname)
        $("#billingLastName").val(data.Surname);
    if (data.Companyname)
        $("#billingCompany").val(data.Companyname);
    if (data.Address1)
        $("#billingAddress1").val(data.Address1);
    if (data.Address2)
        $("#billingAddress2").val(data.Address2);
    if (data.Postcode)
        $("#billingPostal").val(data.Postcode);
    if (data.City)
        $("#billingCity").val(data.City);
    if (data.State)
        $("#billingState").val(data.State);
    if (data.Tel1)
        $("#billingTelCountryCode").val(data.Tel1);
    if (data.Tel2)
        $("#billingTelAreaCode").val(data.Tel2);
    if (data.Tel3)
        $("#billingTelNumber").val(data.Tel3);

    if (data.Email) {
        loginSuccessful(data.Email);
    }
}

function validateLogin()
{
    $("#loginForm").formValidation({
        framework: "bootstrap",
        icon: {
            valid: "glyphicon",
            invalid: "glyphicon",
            validating: "glyphicon glyphicon-refresh"
        },
        excluded: ":disabled",
        live: "submitted", /*enabled, submitted, disabled*/
        fields: {
            loginEmail: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The email is required")?>"
                    },
                    emailAddress: {
                        message: "<?=_("The value is not a valid email address")?>"
                    }
                }
            },
            loginPassword: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("Please input a password")?>"
                    }
                }
            }
        }
    })
    .on("success.form.fv", function(event)
    {
        event.preventDefault();
        standardWaitingScreen.showPleaseWait();
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            type        : "POST",
            url         : formURL,
            data        : postData,
            dataType    : "json",
            encode      : true
        })
        .done(function(data) {
            standardWaitingScreen.hidePleaseWait();
            if (data.responseCode < 0) {
//                console.log(data.responseCode);
                if (data.error) {
                    alert(data.error);
                }
            } else {
                populateClientData(data);
            }
            displayLoginButton(1);
        })
        .fail(function(data) {
            if (data.responseCode)
                console.log(data.responseCode);
            if (data.error)
                alert(data.error);
            standardWaitingScreen.hidePleaseWait();
            displayLoginButton(1);
        });
    });
}

function poBoxValidate(value, validator) {
    <?php if (!$checkPoBoxLimit): ?>
    return true;
    <?php endif; ?>
    var poboxReg = new RegExp('\\bP(ost|ostal)?([ \.]*O(ffice)?)?([ \.]*Box)?\\b', 'i');
    if (value.match(poboxReg)) {
        return false;
    }
    return true;
}

function validateCheckout()
{
    $("#checkoutForm").formValidation({
        framework: "bootstrap",
        icon: {
            valid: "glyphicon",
            invalid: "glyphicon",
            validating: "glyphicon glyphicon-refresh"
        },
        excluded: ":disabled",
        live: "submitted", /*enabled, submitted, disabled*/
        fields: {
            billingFirstName: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The billing first name is required")?>"
                    },
                    stringLength: {
                        min: 1,
                        max: 50,
                        message: "<?=_("The billing first name must be more than 1 and less than 50 characters long")?>"
                    },
                    regexp: {
                        regexp: /^([ \u00c0-\u01ffa-zA-Z0-9'\-])+$/,
                        message: "<?=_("The billing first name can only consist of alphabetical, number")?>"
                    }
                }
            },
            billingLastName: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The billing last name is required") ?>"
                    },
                    stringLength: {
                        min: 1,
                        max: 50,
                        message: "<?=_("The billing last name must be more than 1 and less than 50 characters long")?>"
                    },
                    regexp: {
                        regexp: /^([ \u00c0-\u01ffa-zA-Z0-9'\-])+$/,
                        message: "<?=_("The billing last name can only consist of alphabetical, number")?>"
                    }
                }
            },
            billingCompany: {
                row: ".form-group",
                validators: {
                    stringLength: {
                        max: 50,
                        message: "<?=_("The billing company name cannot be longer than 50 characters")?>"
                    },
                    regexp: {
                        regexp: /^([ \u00c0-\u01ffa-zA-Z0-9'\-])+$/,
                        message: "<?=_("The billing company name can only consist of alphabetical, number")?>"
                    }
                }
            },
            billingAddress1: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The billing Address Line 1 is required")?>"
                    },
                    regexp: {
                        regexp: /^([ \u00c0-\u01ffa-zA-Z0-9,'\-\/#])+$/,
                        message: "<?=_("The billing address1 name can only consist of alphabetical, number")?>"
                    },
                    stringLength: {
                        min: 1,
                        max: 1024,
                        message: "<?=_("The billing address must be more than 1 and less than 1024 characters long")?>"
                    },
                    callback: {
                        message: "<?=_("POBox Address is not allowed")?>",
                        callback: function (value, validator, $field) {
                                return poBoxValidate(value, validator);
                        }
                    }
                }
            },
            billingAddress2: {
                row: ".form-group",
                validators: {
                    regexp: {
                        regexp: /^([ \u00c0-\u01ffa-zA-Z0-9,'\-\/#])+$/,
                        message: "<?=_("The billing address2 name can only consist of alphabetical, number")?>"
                    },
                    stringLength: {
                        min: 1,
                        max: 1024,
                        message: "<?=_("The billing address must be more than 1 and less than 1024 characters long")?>"
                    },
                    callback: {
                        message: "<?=_("POBox Address is not allowed")?>",
                        callback: function (value, validator, $field) {
                                return poBoxValidate(value, validator);
                        }
                    }
                }
            },
            billingCity: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The billing city is required")?>"
                    },
                    stringLength: {
                        min: 1,
                        max: 80,
                        message: "<?=_("The billing city must be more than 1 and less than 128 characters long")?>"
                    },
                    regexp: {
                        regexp: /^([ \u00c0-\u01ffa-zA-Z0-9'\-])+$/,
                        message: "<?=_("The billing city can only consist of alphabetical, number")?>"
                    }
                }
            },
            billingPostal: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The Zip/Postal Code is required")?>"
                    },
                    zipCode: {
                        country: "billingCountry",
                        message: "<?=_("The value is not valid %s Zip/Postal Code")?>"
                    }
                }
            },
            billingTelNumber: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The billing telephone number is required")?>"
                    },
                    regexp: {
                        regexp: /^\+?(\(?\+?(\s*)?\d{1,3}\)?\s)?\(?\d{3}\)?[\s\d.-]{1,20}\d*$/,
                        message: "<?=_("The billing telephone is not valid")?>"
                    }
                }
            },
            billingEmail: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("The email is required")?>"
                    },
                    stringLength: {
                        min: 3,
                        max: 256,
                        message: "<?=_("Email must be more than 3 and less than 256 characters long")?>"
                    },
                    emailAddress: {
                        message: "<?=_("The value is not a valid email address")?>"
                    }
                }
            },
            billingConfirmPassword: {
                row: ".form-group",
                validators: {
                    identical: {
                        field: "billingPassword",
                        message: "<?=_("The password and its confirm are not the same")?>"
                    }
                }
            },
<?php if ($billingStateList): ?>
            billingState: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("Please Select a state")?>"
                    },
                }
            },
<?php endif; ?>
            paymentCard: {
                row: ".form-group",
                validators: {
                    notEmpty: {
                        message: "<?=_("Please select a card")?>"
                    },
                }
            },
        }
    })
    .on("success.form.fv", function(event)
    {
        var errorMessage = "";
        event.preventDefault();
        standardWaitingScreen.showPleaseWait();
//        displayCheckoutNowButton(0);
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
        $.ajax({
            type        : "POST",
            url         : formURL,
            data        : postData,
            dataType    : "json",
            encode      : true
        })
        .done(function(data) {
            standardWaitingScreen.hidePleaseWait();
            displayCheckoutNowButton(1);
            if (data.hasOwnProperty("error") && (data.error < 0)) {
                errorMessage = "";
                if (data.hasOwnProperty("validInput") && !data.validInput) {
                    jQuery.each(data.errorMessage, function(i, val) {
                        errorMessage += i + ":" + val + "\n";
                    });
                } else if (data.hasOwnProperty("siteDown") && data.siteDown) {
//handle site down here, can redirect to 2nd payment option
                    errorMessage = data.errorMessage;
                }
                else if (data.hasOwnProperty("errorMessage")) {
                    errorMessage = data.errorMessage;
                }
                if (errorMessage != "")
                    alert(errorMessage);
            }
            else
                location.href = data.url;
        })
        .fail(function(data) {
            displayCheckoutNowButton(1);
            standardWaitingScreen.hidePleaseWait();
            alert("<?=_("Unknown error: Please contact our CS!")?>");
        });
    });
}
</script>
<script type="text/javascript" src="/themes/default/asset/formvalidation/js/formValidation.min.js"></script>
<script type="text/javascript" src="/themes/default/asset/formvalidation/js/framework/bootstrap.min.js"></script>
<link href="/themes/default/asset/formvalidation/css/formValidation.min.css" rel="stylesheet" />

<p style="background:url(https://h.online-metrix.net/fp/clear.png?org_id=<?=$cybersourceFingerprintId;?>&session_id=<?=$cybersourceFingerprintLabel;?>&m=1)"></p>
<img src="https://h.online-metrix.net/fp/clear.png?org_id=<?=$cybersourceFingerprintId;?>&session_id=<?=$cybersourceFingerprintLabel;?>&m=2" alt="">
<object type="application/x-shockwave-flash" data="https://h.online-metrix.net/fp/fp.swf?org_id=<?=$cybersourceFingerprintLabel;?>&session_id=<?=$cybersourceFingerprintLabel;?>" width="1" height="1"id="thm_fp"><param name="movie" value="https://h.online-metrix.net/fp/fp.swf?org_id=<?=$cybersourceFingerprintId;?>&session_id=<?=$cybersourceFingerprintLabel;?>" />
<div></div>
</object>
<script src="https://h.online-metrix.net/fp/check.js?org_id=<?=$cybersourceFingerprintLabel;?>&session_id=<?=$cybersourceFingerprintLabel;?>" type="text/javascript">
</script>
<?php $this->load->view('footer') ?>