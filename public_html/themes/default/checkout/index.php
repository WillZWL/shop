<?php $this->load->view('header') ?>
<!-- header -->
<div class="main-columns container">
    <div class="row"> 
    <div id="sidebar-main" class="col-md-12">
    <div id="content">
        <h1 class="page-title">Checkout</h1>
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
            <div class="panel-heading noicon">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-checkout-option">
                        Step 1: Checkout Options
                        <i class="fa fa-caret-down"></i>
                    </a>
                </h4>
            </div>
            <div class="panel-collapse collapse in" id="collapse-checkout-option">
                <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <h2>New Customer</h2>
                        <p>Guest Checkout:</p>
                        <div class="radio">
                        </div>
                        <input type="button" class="btn btn-primary" data-loading-text="Loading..." id="button-account" value="Continue">
                      </div>
                      <div class="col-sm-6">
                        <h2>Returning Customer</h2>
                        <p>I am a returning customer</p>
                        <div class="form-group">
                          <label for="input-email" class="control-label">E-Mail</label>
                          <input type="text" class="form-control" id="input-email" placeholder="E-Mail" value="" name="email">
                        </div>
                        <div class="form-group">
                          <label for="input-password" class="control-label">Password</label>
                          <input type="password" class="form-control" id="input-password" placeholder="Password" value="" name="password">
                          <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/forgotten">Forgotten Password</a></div>
                        <input type="button" class="btn btn-primary" data-loading-text="Loading..." id="button-login" value="Login">
                      </div>
                    </div>            
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading noicon">
                <h4 class="panel-title">
                    <a class="accordion-toggle" href="#collapse-payment-address">
                        Step 2: Account &amp; Billing Details
                        <i class=""></i>
                    </a>
                </h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-payment-address">
                <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <fieldset id="account">
                          <legend>Your Personal Details</legend>
                          <div style="display: none;" class="form-group">
                            <label class="control-label">Customer Group</label>
                            <div class="radio">
                              <label>
                              <input type="radio" checked="checked" value="1" name="customer_group_id" /> Default</label>
                            </div>
                          </div>
                          <div class="form-group required">
                              <label for="billingFirstName" class="control-label">First Name</label> 
                              <input type="text" class="form-control" id="billingFirstName" placeholder="First Name" value="" name="billingFirstName" />
                          </div>
                          <div class="form-group required">
                              <label for="billingLastName" class="control-label">Last Name</label> 
                              <input type="text" class="form-control" id="billingLastName" placeholder="Last Name" value="" name="billingLastName" />
                          </div>
                          <div class="form-group required">
                          <label for="billingEmail" class="control-label">E-Mail</label> 
                          <input type="text" class="form-control" id="billingEmail" placeholder="E-Mail" value="" name="billingEmail" /></div>
                          <div class="form-group required">
                              <label class="control-label">Telephone</label> 
                              <div>
                                  <input type="text" maxlength=3 class="form-control" id="billingTelCountryCode" placeholder="Country Code" value="" name="billingTelCountryCode" />
                                  <input type="text" maxlength=3 class="form-control" id="billingTelAreaCode" placeholder="Area Code" value="" name="billingTelAreaCode" />
                                  <input type="text" class="form-control" id="billingTel" placeholder="Telephone" value="" name="billingTel" />
                              </div>
                          </div>
                        </fieldset>
                        <fieldset>
                          <legend>Your Password</legend>
                          <div class="form-group">
                              <label for="password" class="control-label">Password</label> 
                              <input type="password" class="form-control" id="password" placeholder="Password" value="" name="password" /></div>
                          <div class="form-group">
                              <label for="confirmPassword" class="control-label">Password Confirm</label> 
                              <input type="password" class="form-control" id="confirmPassword" placeholder="Password Confirm" value="" name="confirmPassword" />
                          </div>
                        </fieldset>
                      </div>
                      <div class="col-sm-6">
                        <fieldset id="address" class="required">
                          <legend>Your Address</legend>
                          <div class="form-group">
                              <label for="billingCompany" class="control-label">Company</label> 
                              <input type="text" class="form-control" id="billingCompany" placeholder="Company" value="" name="billingCompany" />
                          </div>
                          <div class="form-group required">
                              <label for="billingAddress1" class="control-label">Address 1</label> 
                              <input type="text" class="form-control" id="billingAddress1" placeholder="Address 1" value="" name="billingAddress1" />
                          </div>
                          <div class="form-group">
                              <label for="billingAddress2" class="control-label">Address 2</label> 
                              <input type="text" class="form-control" id="billingAddress2" placeholder="Address 2" value="" name="billingAddress2" />
                          </div>
                          <div class="form-group required">
                              <label for="billingCity" class="control-label">City</label> 
                              <input type="text" class="form-control" id="billingCity" placeholder="City" value="" name="billingCity" />
                          </div>
                          <div class="form-group required">
                              <label for="billingPostcode" class="control-label">Post Code</label> 
                              <input type="text" class="form-control" id="billingPostcode" placeholder="Post Code" value="" name="billingPostcode" />
                          </div>
                          <div class="form-group required">
                              <label for="billingCountry" class="control-label">Country</label> 
                              <select class="form-control" id="billingCountry" name="billingCountry">
                                <option value="<?php print $billing["countryId"]?>"><?php print $billing["countryName"]?></option>
                              </select>
                          </div>
                          <div class="form-group required">
                              <label for="billingState" class="control-label">County / State</label> 
                              <select class="form-control" id="billingState" name="billingState">
                                  <option value="-1"> -- select County / State -- </option>
                                  <?php foreach ($billingStateList as $stateObj): ?>
                                    <option value="<?php print $stateObj->getCountryId();?>"><?php print $stateObj->getName();?>"</option>
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
                            <input id="button-payment-address" class="btn btn-primary" type="button" data-loading-text="Loading..." value="Continue">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading noicon">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-shipping-address">
                    Step 3: Delivery Details
                    <i class=""></i>
                </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-shipping-address">
            <div class="panel-body">
                <form class="form-horizontal">
                  <div class="radio">
                    <label>
                    <!-- <input type="radio" checked="checked" value="existing" name="shipping_address" />--> We could only ship to the billing address!</label>
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
                      <input type="button" class="btn btn-primary" data-loading-text="Loading..." id="button-shipping-address" value="Continue" />
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
                   Step 4: Payment Method
                   <i class=""></i>
                </a>
            </h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-payment-method">
            <div class="panel-body">
            
            </div>
          </div>
        </div>

      </div>
      </div>
   </div> 
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    if ($("#billingState").length == 1) {
        $('#billingState').prop('disabled', 'disabled');
        $('#billingState').parent().removeClass("required");
    }
});

// Checkout
$(document).delegate('#button-account', 'click', function() {
    var targBlock = $('a[href=\'#collapse-payment-address\']');
    activatedBlock(targBlock);
});
$(document).delegate('#button-payment-address', 'click', function() {
    var targBlock = $('a[href=\'#collapse-shipping-address\']');
    activatedBlock(targBlock);
});
$(document).delegate('#button-shipping-address', 'click', function() {
    var targBlock = $('a[href=\'#collapse-payment-method\']');
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

</script> 
<?php $this->load->view('footer') ?>