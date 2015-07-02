<div id="cart" class="pull-right clearfix">
    <div data-toggle="dropdown" data-loading-text="Loading..." class="heading media dropdown-toggle">
        <div class="cart-inner media-body">
            <a>
                <i class="icon-cart fa fa-shopping-cart"></i>
                <span class="text-cart">text_shopping_cart</span>
                <span id="cart-total" class="cart-total"><?= count($cart_info['item']) ?> item(s) - <?= $cart_info['total_amount'] ?></span>
                <i class="fa fa-angle-down"></i>
            </a>
        </div>
    </div>
    <ul class="dropdown-menu content">
        <?php if ($cart_info['item']): ?>
            <li>
                <table class="table">
                    <?php foreach ($cart_info['item'] as $sku => $item): ?>
                    <tr>
                        <td class="text-center">
                            <a href="#"><img src="/themes/default/asset/image/demo/2-47x47.jpg" alt="<?= $item->get_prod_name() ?>" title="<?= $item->get_prod_name() ?>" /></a>
                        </td>
                        <td class="text-left">
                            <a href="#"><?= $item->get_prod_name() ?></a>
                        </td>
                        <td class="text-right">x 1</td>
                        <td class="text-right"><?= $item->get_price() ?></td>
                        <td class="text-center"><button type="button" onclick="cart.remove('YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjUwO30=');" title="Remove" class="btn btn-primary btn-xs"><i class="fa fa-times"></i></button></td>
                    </tr>
                    <?php endforeach ?>
                </table>
            </li>
            <li>
                <div class="table-responsive">
                    <table class="table table-v4">
                        <tr>
                            <td class="text-right"><strong>Total</strong></td>
                            <td class="text-right"><?= $cart_info['total_amount'] ?></td>
                        </tr>
                    </table>
                    <p class="text-right">
                        <a href="/review_order" class="btn btn-primary"> View Cart </a>&nbsp;&nbsp;&nbsp;
                        <a href="/checkout" class="btn btn-primary"> Checkout</a>
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
