<?php
namespace ESG\Panther\Service\Promotion;

class FreeItemPromotionService extends BaseDiscountItemService implements DiscountTypeInterface
{
    private $_cart;
    private $_promotionCodeObj;

    public function __construct($cartInfo,$promotionCodeObj)
    {
        $this->_cart=$cartInfo;
        $this->_promotionCodeObj=$promotionCodeObj;
        parent::__construct($cartInfo,$promotionCodeObj);
    }

    public function modifyPromotionCart()
    {
        $totalAmount=$this->_cart->getSubtotal();
        if ($totalAmount < $this->_promotionCodeObj->getOverAmount()){
            $this->_cart = $this->removePromotionCart();
            $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
        }
        return $this->_cart;
    }

    public function getPromotionCart()
    {   
    	$totalAmount=$this->_cart->getSubtotal();
        if ($totalAmount > $this->_promotionCodeObj->getOverAmount()){
            $freeItemSkuList=@explode(",",$this->_promotionCodeObj->getFreeItemSku());
            $discountCartItem=$this->getDiscountCartItem($freeItemSkuList);
            if($discountCartItem){
                $this->_cart=$discountCartItem;
                $this->_cart->setPromotionCode($this->_promotionCodeObj->getCode());
                $this->_cart->setPromotionError(null);
            }else{
                $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
            }
        }else{
            $this->_cart=$this->removePromotionCart();
            $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
        }
        return $this->_cart;
    }

    public function validateRomoveCartItemAction()
    {
        $overAmount=$this->_promotionCodeObj->getOverAmount();
        return $this->validatePromotionRemoveItem($overAmount);
    }

    public function cancelPromotionCart()
    {     
        $cart= $this->removePromotionCart();
        $cart->setPromotionError(null);
        return $cart;
    }
}