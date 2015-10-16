<?php $this->load->view('header') ?>
<!-- header -->
<div class="main-columns container">
    <div class="row"> 
    <div id="sidebar-main" class="col-md-12">
    <div id="content">
    <form action="/Checkout/payment<?php print (($debug)?"/1":"")?>" method="POST" id="checkoutForm" name="checkoutForm">
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
                      <div class="col-sm-6">
                        <h2><?=_("New Customer")?></h2>
                        <p><?=_("Guest Checkout")?>:</p>
                        <div class="radio">
                        </div>
                        <input type="button" class="btn btn-primary" data-loading-text="<?=_("Loading...")?>" id="button-account" value="<?=_("Continue")?>">
                      </div>
                      <div class="col-sm-6">
                        <h2><?=_("Returning Customer")?></h2>
                        <p><?=_("I am a returning customer")?></p>
                        <div class="form-group">
                          <label for="input-email" class="control-label"><?=_("E-Mail")?></label>
                          <input type="text" class="form-control" id="input-email" placeholder="<?=_("E-Mail")?>" value="" name="email">
                        </div>
                        <div class="form-group">
                          <label for="input-password" class="control-label"><?=_("Password")?></label>
                          <input type="password" class="form-control" id="input-password" placeholder="<?=_("Password")?>" value="" name="password">
                          <a href=""><?=_("Forgotten Password")?></a></div>
                        <input type="button" class="btn btn-primary" data-loading-text="<?=_("Loading...")?>" id="button-login" value="Login">
                      </div>
                    </div>            
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading noicon">
                <h4 class="panel-title">
                    <a class="accordion-toggle" href="#collapse-payment-address">
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
                        <fieldset>
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
                              <label for="billingAddress1" class="control-label"><?=_("Address 1")?></label> 
                              <input type="text" class="form-control" maxlength=1024 id="billingAddress1" placeholder="<?=_("Address 1")?>" value="" name="billingAddress1" />
                          </div>
                          <div class="form-group">
                              <label for="billingAddress2" class="control-label"><?=_("Address 2")?></label> 
                              <input type="text" class="form-control" maxlength=1024 id="billingAddress2" placeholder="<?=_("Address 2")?>" value="" name="billingAddress2" />
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
                <a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-shipping-address">
                    <?=_("Step 3: Delivery Details")?>
                    <i class=""></i>
                </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-shipping-address">
            <div class="panel-body">
                <form class="form-horizontal">
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
                      <label for="input-shipping-address-1" class="col-sm-2 control-label">Address 1</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-address-1" placeholder="Address 1" value=""
                        name="address_1" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="input-shipping-address-2" class="col-sm-2 control-label">Address 2</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="input-shipping-address-2" placeholder="Address 2" value=""
                        name="address_2" />
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
                </form>
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
                <a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-payment-method">
                   <?=_("Step 4: Payment Method")?>
                   <i class=""></i>
                </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-payment-method">
            <div class="panel-body">
<?php foreach ($paymentOption as $card): ?>
                <div style="float:left;padding-right:2px;">
                    <input id="card_ay_VSA" type="radio" name="paymentCard" value="<?php print $card->getCardCode() . "%%" . $card->getCardId() . "%%" . $card->getPaymentGatewayId()?>">
                    <?php print "<img alt='" . $card->getCardName() . "' title='" . $card->getCardName() . "' src='" . $card->getCardImage() . "'/>"; ?>
                </div>
<?php endforeach ?>
                <div class="clearfix" />
                <div class="buttons">
                    <div class="pull-right">
                        <input type="hidden" name="formSalt" id='formSalt' value="<?=$formSalt;?>">
                        <input type="submit" class="btn btn-primary" data-loading-text="<?=_("Loading...")?>" id="checkoutNow" value="<?=_("Continue")?>" />
                    </div>
                </div>
            </div>
          </div>
        </div>

      </div>
      </div>
   </div> 
</div>
</div>
<script type="text/javascript">
function displayCheckoutNowButton($show)
{
    if ($show == 1)
        $("#checkoutNow").show();
    else
        $("#checkoutNow").hide();
}

$(document).ready(function() {
    if ($("#billingState option").length == 1) {
        $('#billingState').prop('disabled', 'disabled');
        $('#billingState').parent().removeClass("required");
    }

// submit the form
    $("#checkoutForm").submit(function(event) {
        standardWaitingScreen.showPleaseWait();
        displayCheckoutNowButton(0);
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
//            displayCheckoutNowButton(1);
            if (data.url.substring(0, 5) == "ERROR")
            {
                alert(data.url.substring(6, data.length));
                displayCheckoutNowButton(1);
            }
            else
                location.href = data.url;
        })
        .fail(function(data) {
            if (data.responseCode)
                console.log(data.responseCode);
            var url = data;
            location.href = data.url;
            displayCheckoutNowButton(1);
            standardWaitingScreen.hidePleaseWait();
        });
        event.preventDefault();
    });
    
    validateCheckout();
});

// Checkout
$(document).delegate('#button-account', 'click', function() {
    var targBlock = $('a[href=\'#collapse-payment-address\']');
    activatedBlock(targBlock);
});
$(document).delegate('#button-payment-address', 'click', function() {
    var targBlock = $('a[href=\'#collapse-shipping-address\']');
    if (validateBlock("collapse-payment-address"))
        activatedBlock(targBlock);
});
$(document).delegate('#button-shipping-address', 'click', function() {
    var targBlock = $('a[href=\'#collapse-payment-method\']');
    displayCheckoutNowButton(1);
    activatedBlock(targBlock);
});
function activatedBlock(obj)
{
    obj.attr("data-parent", "#accordion");
    obj.attr("data-toggle", "collapse");
//    obj.find("i").removeClass("fa fa-caret-down");
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
                        message: "<?=_("The billing address 1 is required")?>"
                    },
                    regexp: {
                        regexp: /^([ \u00c0-\u01ffa-zA-Z0-9,'\-\/#])+$/,
                        message: "<?=_("The billing address1 name can only consist of alphabetical, number")?>"
                    },
                    stringLength: {
                        min: 1,
                        max: 1024,
                        message: "<?=_("The billing address must be more than 1 and less than 1024 characters long")?>"
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
        }
    })
}
</script> 
<script type="text/javascript" src="/themes/default/asset/formvalidation/js/formValidation.min.js"></script>
<script type="text/javascript" src="/themes/default/asset/formvalidation/js/framework/bootstrap.min.js"></script>
<link href="/themes/default/asset/formvalidation/css/formValidation.min.css" rel="stylesheet" />
<?php $this->load->view('footer') ?>