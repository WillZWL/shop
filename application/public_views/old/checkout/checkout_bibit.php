<form method="get" action="<?=base_url()?>checkout/order_confirm/bibit/<?=$debug?>">
	<input type="hidden" name="delivery" value="<?=$cur_delivery?>">
	<input type="hidden" name="review" value="">
	<input type="image" src="/images/checkout/cameraco.gif" onClick="this.form.review.value=document.fm_cart.review.value;this.form.submit();" value="Checkout">
</form>
