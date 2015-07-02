<?php $this->load->view('/default/header') ?>
<!-- header -->
<div id="sidebar-main" class="col-md-12">
    <div id="content">
        <h1 class="page-title">Shopping Cart&nbsp;(<?= $cart_info['total_weight'] ?>)</h1>
        <form action="/checkout" method="post" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center">Image</td>
                            <td class="text-left">Product Name</td>
                            <td class="text-left">Quantity</td>
                            <td class="text-right">Unit Price</td>
                            <td class="text-right">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_info['item'] as $sku => $item): ?>
                        <tr>
                            <td class="text-center">
                                <a href="#"><img src="/themes/default/asset/image/demo/2-47x47.jpg" alt="<?= $item->get_prod_name() ?>" title="<?= $item->get_prod_name() ?>" class="img-thumbnail"></a>
                            </td>
                            <td class="text-left"><a href="#"><?= $item->get_prod_name() ?></a>
                            </td>
                            <td class="text-left">
                                <div class="input-group btn-block" style="max-width: 200px;">
                                    <input type="text" name="quantity[YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjMzO30=]" value="1" size="1" class="form-control">
                                    <span class="input-group-btn">
                                        <button type="submit" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Update"><i class="fa fa-refresh"></i></button>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-primary" onclick="cart.remove('YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjMzO30=');" data-original-title="Remove"><i class="fa fa-times-circle"></i></button>
                                    </span>
                                </div>
                            </td>
                            <td class="text-right"><?= $item->get_price() ?></td>
                            <td class="text-right"><?= $item->get_price() ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-8">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="text-right"><strong>Total:</strong></td>
                            <td class="text-right"><?= $cart_info['total_amount'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttons">
            <div class="pull-left"><a href="/" class="btn btn-default">Continue Shopping</a></div>
            <div class="pull-right"><a href="/checkout" class="btn btn-primary">Checkout</a></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php $this->load->view('/default/footer') ?>