<?php
namespace ESG\Panther\Service\Promotion;

use ESG\Panther\Service\BaseService;
use ESG\Panther\Service\ProductService;

class PromotionFactoryService extends BaseService
{
	private $_discountTypeInterface;
    private $_cart;
    private $_promotionCodeObj;
    private $valid=0;

	public function __construct(DiscountTypeInterface $_discountTypeInterface,$cartInfo,$promotionCodeObj)
    {
        $this->_discountTypeInterface=$_discountTypeInterface;
        $this->_cart=$cartInfo;
        $this->_promotionCodeObj=$promotionCodeObj;
        $this->productService=new ProductService;
    }
   //FD = Free Delivery / A = Amount / P = Percent / FI = Free Item / R = Redemption
    public function getPromotionCart()
    {   
        if($this->validatePromotionCode()){
            return $this->_discountTypeInterface->getPromotionCart();
        }
    }

    public function modifyPromotionCart()
    {
        return $this->_discountTypeInterface->modifyPromotionCart();
    }

    public function validRemoveItemPromotion()
    {
        return $this->_discountTypeInterface->validateRomoveCartItemAction();
    }

    public function cancelPromotionCart()
    {
        return $this->_discountTypeInterface->cancelPromotionCart();
    }

    public function validatePromotionCode()
    {
        $this->validRelevantProd();
        $this->validCurrencyId();
        $this->validCountryId();
        $this->validCartItemField();
        $this->validRedemption();
        return $this->valid ? "true" : "false"; 
    }

    protected function validRelevantProd()
    {
        if($this->valid && ($relevantProd = $this->_promotionCodeObj->getRelevantProd())){
            $relevantProd = @explode(",", $relevantProd);
            foreach ($this->_cart as $cartItem) {
                if(in_array($cartItem->getSku(), $relevantProd)){
                    $this->valid=1;
                    break;
                }
            }
        }
    }

    protected function validCurrencyId()
    {
        if ($this->valid && ($currencyId = $this->_promotionCodeObj->getCurrencyId())){
            $platformObj = $this->sc["PlatformBizVarService"]->get(array("selling_platform_id"=>$this->_cart->getplatformId()));
            if ($platformObj){
                $this->valid = ($currencyId == $platformObj->getPlatformCurrencyId()) ? 1 : 0 ;
            }
        }
    }

    protected function validCountryId()
    {
        if ($this->valid && ($countryId = $this->_promotionCodeObj->getCountryId())){
            if ($country_id == $this->_cart->getCountryId()){
                $this->valid = 1;
            }else{
                $this->valid = 0;
            }
        }
    }

    /*protected function validEmail()
    {
        $email = $this->_promotionCodeObj->getEmail();
        if ($this->valid && $checkEmail && $this->_cart->getEmail()){
            $checkEmail=preg_match('/^'.str_replace('%', '.*', $checkEmail).'$/', trim($this->_cart->getEmail()));
            $checkEmail ? $this->valid = 1 : $this->valid = 0 ;
        }
    }*/

    public function validRedemption()
    {
        $totalRedemption = $this->_promotionCodeObj->getTotalRedemption();
        if ($this->valid && ($totalRedemption!= -1)){
            $leftRedemption= $totalRedemption - $this->_promotionCodeObj->getNoTaken();
            $total_redemption_left ? $this->valid = 1 : $this->valid = 0 ;
        }
    }

    protected function validCartItemField()
    {
        foreach($this->_cart->items as $cartItem){
            $productObj = $this->productService->getDao()->getProductWithPrice($cartItem->getSku(), $this->_cart->getplatformId());

            if ($this->valid && ($brandId = $this->_promotionCodeObj->getBrandId())){
                $this->valid=$this->validFeild($brandId,$productObj->getBrandId());
            }
            if($this->valid && ($catId = $this->_promotionCodeObj->getCatId())){
                $this->valid=$this->validFeild($catId,$productObj->getCatId());
            }
            if($this->valid && ($subCatId = $this->_promotionCodeObj->getSubCatId())){
                $this->valid=$this->validFeild($subCatId,$productObj->getSubCatId());
            }
            if($this->valid && ($subSubCatId = $this->_promotionCodeObj->getSubSubCatId())){
                $this->valid=$this->validFeild($subSubCatId,$productObj->getSubSubCatId());
            }
        }
    }

    public function validFeild($cartItemField,$promotionCodeField){
        return $cartItemField == $promotionCodeField ? "true" :"false";
    }

}