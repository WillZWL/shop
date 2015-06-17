<div id="cart" class="pull-right clearfix">
    <div data-toggle="dropdown" data-loading-text="Loading..." class="heading media dropdown-toggle">
        <div class="cart-inner media-body">
            <a>
                <i class="icon-cart fa fa-shopping-cart"></i>
                <span class="text-cart">text_shopping_cart</span>
                <span id="cart-total" class="cart-total">1 item(s) - $38.00</span>
                <i class="fa fa-angle-down"></i>
            </a>
        </div>
    </div>
    <ul class="dropdown-menu content">
        <?php if ($cart['content']): ?>
            <li>
                <table class="table">
                    <?php foreach ($$cart['content'] as $sku => $item): ?>
                    <tr>
                        <td class="text-center">
                            <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=50"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/2-47x47.jpg" alt="Apple iPhone 6 128GB" title="Apple iPhone 6 128GB" /></a>
                        </td>
                        <td class="text-left">
                            <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=50">Apple iPhone 6 128GB</a>
                        </td>
                        <td class="text-right">x 1</td>
                        <td class="text-right">$38.00</td>
                        <td class="text-center"><button type="button" onclick="cart.remove('YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjUwO30=');" title="Remove" class="btn btn-primary btn-xs"><i class="fa fa-times"></i></button></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </li>
            <li>
                <div class="table-responsive">
                    <table class="table table-v4">
                        <tr>
                            <td class="text-right"><strong>Sub-Total</strong></td>
                            <td class="text-right">$30.00</td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Eco Tax (-2.00)</strong></td>
                            <td class="text-right">$2.00</td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>VAT (20%)</strong></td>
                            <td class="text-right">$6.00</td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Total</strong></td>
                            <td class="text-right">$38.00</td>
                        </tr>
                    </table>
                    <p class="text-right">
                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=checkout/cart" class="btn btn-primary"> View Cart </a>&nbsp;&nbsp;&nbsp;
                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=checkout/checkout" class="btn btn-primary"> Checkout</a>
                    </p>
                </div>
            </li>

        <?php else: ?>
            <li>
                <p class="text-center">Your shopping cart is empty!</p>
            </li>
        <?php endif ?>
    </ul>
</div>
