<?php $this->load->view('header') ?>
<!-- header -->
<div id="review_order" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?= _('Shopping Cart') ?></h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><?= _('Image') ?></th>
                            <th class="text-left"><?= _('Product Name') ?></th>
                            <th class="text-left"><?= _('Quantity') ?></th>
                            <th class="text-right"><?= _('Unit Price') ?></th>
                            <th class="text-right"><?= _('Total') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartInfo->items as $sku => $item): ?>
                        <tr>
                            <td class="text-center">
                                <a href="#"><img src="<?php print $item->getImageUrl() ?>" alt="<?= $item->getNameInLang() ?>" title="<?= $item->getNameInLang() ?>" class="img-thumbnail"></a>
                            </td>
                            <td class="text-left"><a href="#"><?= $item->getNameInLang() ?></a>
                            </td>
                            <td class="text-left">
                                <div class="input-group btn-block" style="max-width: 200px;">
                                    <input id="update-<?php print $item->getSku()?>" type="text" name="quantity[YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjMzO30=]" value="<?php print $item->getQty()?>" size="1" class="form-control">
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-primary" onclick="cart.update('<?php print $item->getSku()?>', $('#update-<?php print $item->getSku()?>').val())" data-original-title="Update"><i class="fa fa-refresh"></i></button>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-primary" onclick="cart.remove('<?php print $item->getSku()?>');" data-original-title="Remove"><i class="fa fa-times-circle"></i></button>
                                    </span>
                                </div>
                            </td>
                            <td class="text-right"><?= $item->getPrice() ?></td>
                            <td class="text-right"><?= $item->getAmount() ?></td>
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
                            <td class="text-right"><strong><?=_('Subtotal:') ?></strong></td>
                            <td class="text-right"><?= $cartInfo->getGrandTotal() ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?=_('Shipping:') ?></strong></td>
                            <td class="text-right"><?= $cartInfo->getDeliveryCharge() ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?= _('Total:') ?></strong></td>
                            <td class="text-right"><?= $cartInfo->getGrandTotal() ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="buttons">
            <div class="pull-left"><a href="/" class="btn btn-default"><?= _('Continue Shopping') ?></a></div>
            <div class="pull-right"><a href="/Checkout" class="btn btn-primary"><?= _('Checkout') ?></a></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php $this->load->view('footer') ?>
