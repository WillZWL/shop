<?php
namespace ESG\Panther\Service\Promotion;

class FreeDeliveryPromotionService implements DiscountTypeInterface
{
    private $_cart;
    private $_promotionCodeObj;
    private $_discItemList;
    private $_discount;

    public function __construct($cartInfo,$promotionCodeObj)
    {
        $this->_cart=$cartInfo;
        $this->_promotionCodeObj=$promotionCodeObj;
    }

    public function getPromotionCart()
    {
        $totalAmount=$this->_cart->getSubtotal();
        if ($totalAmount > $this->_promotionCodeObj->getOverAmount()){
        	$this->_cart->setPromotionCode($this->_promotionCodeObj->getCode());
        	$this->_cart->setPromotionError(null);
        	$this->_cart->setPromoDiscTotal($this->_cart->getDeliveryCharge());
        }else{
          $this->_cart->setPromotionCode(null);
          $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
          $this->_cart->setPromoDiscTotal(null);
        }
        return $this->_cart;
    }

    public function modifyPromotionCart()
    {
        $totalAmount=$this->_cart->getSubtotal();
        if ($totalAmount < $this->_promotionCodeObj->getOverAmount()){
            $this->_cart->setPromotionCode(null);
            $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
            $this->_cart->setPromoDiscTotal(null);
            return $this->_cart;
        }
    }

    public function getDiscountAmount()
    {
        $totalAmount=$this->_cart->getSubtotal();
        if ($totalAmount > $this->_promotionCodeObj->getOverAmount()){
        	return $this->_cart->getDeliveryCharge();
        }
    }

    public function validateRomoveCartItemAction()
    {
        $totalAmount=$this->_cart->getSubtotal();
        if ($totalAmount < $this->_promotionCodeObj->getOverAmount()){
            $this->_cart->setPromotionCode(null);
            $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
            $this->_cart->setPromoDiscTotal(null);
            return $this->_cart;
        }
    }

    public function cancelPromotionCart()
    {     
          $this->_cart->setPromotionCode(null);
          $this->_cart->setPromotionError(null);
          $this->_cart->setPromoDiscTotal(null);
          return $this->_cart;
    }

}