<?php $this->load->view('header') ?>
<!-- header -->
<div id="review_order" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?= _('Shopping Cart') ?></h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <colgroup>
                        <col width="40">
                        <col width="250">
                        <col width="70">
                        <col width="80">
                        <col width="70">
                    </colgroup>
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
                                <?php if($item->getRedemption()=="1") {?>
                                <div style="padding-left:25px"><?=$item->getQty();?></div>
                                <?php }else{ ?>
                                <div class="input-group btn-block" style="max-width: 200px;">
                                    <input id="update-<?php print $item->getSku()?>" type="text" name="quantity[YToxOntzOjEwOiJwcm9kdWN0X2lkIjtpOjMzO30=]" value="<?php print $item->getQty()?>" size="1" class="form-control">
                                    <span class="input-group-btn">
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-primary" onclick="cart.update('<?php print $item->getSku()?>', $('#update-<?php print $item->getSku()?>').val())" data-original-title="<?= _('Update') ?>"><i class="fa fa-refresh"></i></button>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-primary" onclick="cart.remove('<?php print $item->getSku()?>');" data-original-title="<?= _('Remove') ?>"><i class="fa fa-times-circle"></i></button>
                                    </span>
                                </div>
                                <?php }?>
                            </td>
                            <td class="text-right"><?= platform_curr_format($item->getPrice()) ?></td>
                            <td class="text-right"><?= platform_curr_format($item->getPrice()*$item->getQty()) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </form>
        
        <div class="row">
             <div class="col-sm-6">
                    <div id="discount_code">
                        <p class="rokkit_24"><?=_('Promo code')?></p>
                        <form name="fm_promo" id="promo_form" action="" class="form-holder" method="post">
                            <fieldset>
                                <input type="text" placeholder="<?=_('Enter your promo code')?>" value="<?=$cartInfo->getPromotionCode()?>" name="promotion_code" dname="" notEmpty/>
                            </fieldset>
                             <?php if($cartInfo->getPromotionCode()){?>
                            <input type="hidden" name="cancel_promotion" value="1">
                            <button type="button" class="btn btn-primary cancel-promotion">
                                <?=_('Remove / Edit Code')?>
                            </button>
                            <?php }else{?>
                            <button type="button" class="btn btn-primary apply-promotion">
                                <?=_('Apply Code')?>
                            </button>
                             <?php } ?>
                            <p style="color:">
                            <?php if($cartInfo->getPromotionError()){
                                echo _('Sorry, Promotion Code Invalid. Please check if the conditions and/or minimum order amount have been met.');
                            }?>
                            </p>
                        </form>
                    </div>
                    <script>
                        $(document).ready(function(){
                            $(".apply-promotion").click(function(){
                                 $( "form#promo_form" ).submit();
                            });
                            $(".cancel-promotion").click(function(){
                                 $( "form#promo_form" ).submit();
                            });
                        });
                    </script>
             </div>
            <div class="col-sm-4 col-sm-offset-2">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="text-right"><strong><?=_('Subtotal').':' ?></strong></td>
                            <td class="text-right"><?= platform_curr_format($cartInfo->getSubtotal()+$cartInfo->getPromoDiscTotal()) ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong><?=_('Shipping').':' ?></strong></td>
                            <td class="text-right"><?= platform_curr_format($cartInfo->getDeliveryCharge()) ?></td>
                        </tr>
                        <?php if($cartInfo->getPromoDiscTotal()!=null){?>
                        <tr>
                            <td class="text-right"><strong><?=_('Promotion Discount').':' ?></strong></td>
                            <td class="text-right"> - <?= platform_curr_format($cartInfo->getPromoDiscTotal()) ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right"><strong><?= _('Total').':' ?></strong></td>
                            <td class="text-right"><?= platform_curr_format($cartInfo->getGrandTotal()) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" style="position: absolute;bottom: 65px; width:100%;">
            <div class="col-sm-6">
                <p style="font-size: 13px;padding: 0;margin: 0;line-height: 18px;">
                    <b><?= _('We only accept Bank Transfer payments for orders exceeding 2000 GBP') ?></b>
                </p>
            </div>
        </div>
        <div class="buttons">
            <div class="pull-left"><a href="/" class="btn btn-default"><?= _('Continue Shopping') ?></a></div>
            <div class="pull-right"><a href="/checkout" class="btn btn-primary"><?= _('Checkout') ?></a></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php $this->load->view('footer') ?>
