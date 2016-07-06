<?php
namespace ESG\Panther\Service\Promotion;

class RedemptionPromotionService extends BaseDiscountItemService implements DiscountTypeInterface
{
    private $_cart;
    private $_promotionCodeObj;
    private $_discItemList;
    private $_discount;

    public function __construct($cartInfo,$promotionCodeObj)
    {
        $this->_cart=$cartInfo;
        $this->_promotionCodeObj=$promotionCodeObj;
        parent::__construct($cartInfo,$promotionCodeObj);
    }

    public function getPromotionCart()
    {    
        $redemptionProdSkuList=@explode(",",$this->_promotionCodeObj->getRedemptionProdValue());
        $redemptionAmount=$this->_promotionCodeObj->getRedemptionAmount();
        $discountCartItem=$this->getDiscountCartItem($redemptionProdSkuList,$redemptionAmount);
        if($discountCartItem){
          $this->_cart = $discountCartItem;
          $this->_cart->setPromotionCode($this->_promotionCodeObj->getCode());
          $this->_cart->setPromotionError(null);
        }else{
          $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
        }
        return $this->_cart;
    }

    public function validateRomoveCartItemAction()
    {
        return $this->validatePromotionRemoveItem();
    }

    public function modifyPromotionCart()
    {
        return $this->_cart;
    }

    public function cancelPromotionCart()
    {     
        $cart= $this->removePromotionCart();
        $cart->setPromotionError(null);
        return $cart;
    }

}