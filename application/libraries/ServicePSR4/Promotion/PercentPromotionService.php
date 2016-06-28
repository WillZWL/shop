<?php
namespace ESG\Panther\Service\Promotion;

class PercentPromotionService extends BaseDiscountLevelService implements DiscountTypeInterface
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
        $discLevelTotalAmount=$this->getDiscLevelTotalAmount();
        $discountAmount=$this->getDiscountAmount($discLevelTotalAmount);
        if($discountAmount){
        	$this->setCartItemDiscount($discountAmount);
        	$this->_cart->setPromotionCode($this->_promotionCodeObj->getCode());
        	$this->_cart->setPromotionError(null);
        	$this->_cart->setPromoDiscTotal($discountAmount);
        }else{
          $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
        }
        return $this->_cart;
    }

    

    public function validateRomoveCartItemAction()
    {
        return  $this->validatePromotionRemoveItem();
    }

    public function modifyPromotionCart()
    {
        return $this->validatePromotionRemoveItem();
    }

    public function cancelPromotionCart()
    {     
        return $this->removePromotionCode();
    }
}