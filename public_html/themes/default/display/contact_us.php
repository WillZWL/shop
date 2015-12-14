<script language="JavaScript">
<!--
	function isValidEmailAddress(emailAddress) {
		var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		return pattern.test(emailAddress);
	}

	function SubmitEnquiry(form)
	{
		var url = '[enquiry_process_url;noerr;]';
		form.action = url;
		form.subject.value = arr_data[form.enquiry_type.value]['question'][form.question.value];
		form.submit();
	}

</script>


<div id="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			Having an issue? There are a number of ways you can get in touch with our experienced Sales and Customer Service teams.
		</div>
	</div>
	<form name="fm_enquiry" class="form-horizontal" method="post" onsubmit="if (CheckForm(this)) {SubmitEnquiry(this);} return false;" target="enquiry_result_iframe" enctype="multipart/form-data">
		<div class="row">
			<fieldset class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h3>Contact Form</h3>
				<div class="form-group-contact required">
				  <label class="col-sm-2 control-label" for="input-name">Name</label>
				  <div class="col-sm-10" style="padding-bottom:4px;">
					<input type="text" name="fullname" value="" id="input-name" class="form-control" />
				  </div>
				</div>
				<div class="form-group-contact required">
				  <label class="col-sm-2 control-label" for="input-email">E-Mail Address</label>
				  <div class="col-sm-10" style="padding-bottom:4px;">
					<input type="text" name="email" value="" id="input-email" class="form-control" />
				  </div>
				</div>
				<div class="form-group-contact norequired">
				  <label class="col-sm-2 control-label" for="input-phone">Phone Number</label>
				  <div class="col-sm-10" style="padding-bottom:4px;">
					<input name="phone" id="input-phone" class="form-control"></input>
				  </div>
				</div>
				<div class="form-group-contact required">
				  <label class="col-sm-2 control-label" for="select-country">Country</label>
				  <div class="col-sm-10" style="padding-bottom:4px;">
					<select id="select-country" name="country" class="form-control">
						<option value="">Select A Country</option>
						<option value="200">Australia</option>
						<option value="201">Finland</option>
						<option value="202">Great Britain</option>
						<option value="203">Hong Kong</option>
						<option value="204">Ireland</option>
						<option value="205">Malaysia</option>
						<option value="206">Malta</option>
						<option value="207">New Zealand</option>
						<option value="208">Singapore</option>
						<option value="209">Switzerland</option>
						<option value="210">United States</option>
					</select>
				  </div>
				</div>
				<div class="form-group-contact required">
				  <label class="col-sm-2 control-label" for="input-order">Order Number</label>
				  <div class="col-sm-10" style="padding-bottom:4px;">
					<input name="order"  id="input-order" class="form-control"></input>
				  </div>
				</div>
				<div class="form-group-contact required">
				  <label class="col-sm-2 control-label" for="select-question">Your Question</label>
				  <div class="col-sm-10" style="padding-bottom:4px;">
					<select id="select-question" class="form-control" name="question">
						<option value="">Select A Question</option>
						<option value="60">"My Account" page says "Order Held"</option>
						<option value="61">"My Account" page says "Order Shipped", but I think it's lost</option>
						<option value="62">I want to cancel my order</option>
						<option value="63">I haven't received my refund</option>
						<option value="65">My delivery is missing item(s)</option>
						<option value="66">I want to change the address for my order delivery</option>
						<option value="67">I want to have express delivery for my order</option>
						<option value="75">"My Account" page still says "Order Processing"</option>
						<option value="110">I like to enquire on my Cash On Delivery order (Singapore residents only)</option>
					</select>
				  </div>
				</div>
				<div class="form-group-contact required">
				  <label class="col-sm-2 control-label" for="input-phone">Message</label>
				  <div class="col-sm-10" style="padding-bottom:4px;">
					<textarea name="message" rows="10" id="input-message" class="form-control"></textarea>
				  </div>
				</div>
				<div class="buttons">
				  <div class="pull-right" style="margin-top:10px;">
					<input class="btn btn-primary" type="submit" value="Submit Enquiry" />
				  </div>
				</div>
			</fieldset>
		</div>
    </form>
</div>