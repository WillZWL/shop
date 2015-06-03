<script src="http://checkout.google.com/files/digital/ga_post.js" type="text/javascript"></script>
<form action="<?=base_url()?>checkout/order_confirm/google/<?=$debug?>" method="POST" onsubmit="setUrchinInputCode(pageTracker);">
	<input type="hidden" name="analyticsdata" value="">
	<input type="image" name="Google Checkout" alt="Fast checkout through Google"  
	src="http://checkout.google.com/buttons/checkout.gif?merchant_id=<?=$mid?>&w=180&h=46&style=white&variant=text&loc=en_US" height="46" width="180"/>
</form>