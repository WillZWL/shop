<?php
namespace ESG\Panther\Service\Promotion;

use ESG\Panther\Service\ProductService;

class BaseDiscountLevelService 
{	
	private $_promotionCodeObj;
	private $_remainQty;
	private $_cart;

	public function __construct($cartInfo,$promotionCodeObj)
    {
        $this->_promotionCodeObj=$promotionCodeObj;
        $this->_cart=$cartInfo;
        $this->getRemainQty();
        $this->productService=new ProductService();
    }

    function getDiscLevelTotalAmount(){
    	foreach($this->_cart->items as $cartItem){
    		$productObj = $this->productService->getDao()->getProductWithPrice($cartItem->getSku(), $this->_cart->getplatformId());
    		$totalAmount +=$this->getDiscLevelAmount($cartItem,$productObj);
    	}
    	return $totalAmount;
    }

	public function getDiscLevelAmount($cartItem,$productObj)
	{
    	$discLevelValueArr=@explode(",", $this->_promotionCodeObj->getDiscLevelValue());
    	switch ($this->_promotionCodeObj->getDiscLevel()) {
			case 'PD':
				if(in_array($cartItem->getSku(), $discLevelValueArr)){
					$itemAmount = $this->calculateDiscLevelAmount($cartItem);
    			}
				break;
			case 'CAT':
				if($productObj->getCatId==$discLevelValueArr[0]){
					$itemAmount = $this->calculateDiscLevelAmount($cartItem);
				}
				break;
			case 'SCAT':
				if($productObj->getSubCatId==$discLevelValueArr[1]){
					$itemAmount = $this->calculateDiscLevelAmount($cartItem);
				}
				break;
			case 'SSCAT':
				if($productObj->getSubSubCatId==$discLevelValueArr[2]){
					$itemAmount = $this->calculateDiscLevelAmount($cartItem);
				}
				break;
			case 'BN':
				if($productObj->getBrandId==$discLevelValueArr[0]){
					$itemAmount = $this->calculateDiscLevelAmount($cartItem);
				}
				break;
			default:
				$itemAmount = $cartItem->getAmount();
				break;
		}
		return $itemAmount;
    }

    public function calculateDiscLevelAmount($cartItem)
	{
		$itemAmount = 0;
		$redemption=$this->_promotionCodeObj->getRedemption();
		$qty=$cartItem->getQty();
		
		if ($redemption!= -1){
			$qty > $this->_remainQty ? $qty=$this->_remainQty : "";
			$this->_remainQty -= $qty;
		}
		$itemAmount = $cartItem->getPrice()*$qty;
		return $itemAmount;
	}

    public function setCartItemDiscount($discountAmount)
    {
		$countQty=0;
  		foreach($this->_cart->items as $cartItem){
  			if($cartItem->getAmount() > 0){
  				$countQty +=$cartItem->getQty();
  			}
  		}
  		$unitDiscount=number_format(($discountAmount/$countQty),2,'.', '');
  		//set discount by item qty
  		foreach($this->_cart->items as $key=>$cartItem){
  			if($cartItem->getAmount() > 0){
  				$ItemDiscount=$cartItem->getQty()*$unitDiscount;
  				$cartItem->setPromoDiscAmt($ItemDiscount);
  				$totalDiscount +=$ItemDiscount;
  				$this->_cart->items[$key]=$cartItem;
  			}
  		}
  		//set order first item add remain discount value
    
  		if($discountAmount!=$totalDiscount){

        $remainDiscount=$discountAmount-$totalDiscount;
  			$i=1;
  			foreach($this->_cart->items as $key=>$cartItem){
	  			if($cartItem->getAmount() > 0 && $i==1){
            $cartItem->setPromoDiscAmt($cartItem->getPromoDiscAmt()+$remainDiscount);
	  				$i++;
	  				$this->_cart->items[$key]=$cartItem;
	  				break;
	  			}
  			}
  		}
	}

	public function validatePromotionRemoveItem()
    {
        $discLevelTotalAmount=$this->getDiscLevelTotalAmount();
        $discountAmount=$this->getDiscountAmount($discLevelTotalAmount);
        if($discountAmount){
          $this->setCartItemDiscount($discountAmount);
          $this->_cart->setPromotionCode($this->_promotionCodeObj->getCode());
          $this->_cart->setPromotionError(null);
          $this->_cart->setPromoDiscTotal($discountAmount);
        }else{
          $this->setCartItemDiscount(null);
          $this->_cart->setPromotionCode(null);
          $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
          $this->_cart->setPromoDiscTotal(null);
        }
         return $this->_cart;
    }

    public function getRemainQty()
	{
    	if(!$this->_remainQty){
    		$this->_remainQty=$this->_promotionCodeObj->getTotalRedemption() - $this->_promotionCodeObj->getNoTaken();
    	} 
    }

    public function getDiscountAmount($discLevelTotalAmount)
    {
		if ($discLevelTotalAmount > 0){
			$classType=substr(get_called_class(), strrpos(get_called_class(), '\\') + 1);
			for ($i=1; $i<5; $i++){
				$func = "getOverAmount".$i;
				$discFunc = "getDiscount".$i;
				$discValue = $this->_promotionCodeObj->$func();
				$discount = $this->_promotionCodeObj->$discFunc();
				if ($discount > 0  && ($discLevelTotalAmount - $discValue) >0 ){
					if($classType == "PercentPromotionService"){
						$discountAmount=number_format($discLevelTotalAmount * $discount/100, 2, '.', '');
					}else{
						$discountAmount=$discount;
					}
				}
			}
      		return $discountAmount;
		}   
   }

   public function removePromotionCode()
   {
   		$this->_cart->setPromotionCode(null);
        $this->_cart->setPromotionError(null);
        $this->_cart->setSubTotal($this->_cart->getSubTotal()+$this->_cart->getPromoDiscTotal());
        $this->_cart->setPromoDiscTotal(null);
        foreach($this->_cart->items as $sku =>$cartItem){
        	$cartItem->setAmount($cartItem->getAmount()+$cartItem->getPromoDiscAmt());
            $cartItem->setPromoDiscAmt(null);
            $this->_cart->items[$sku]=$cartItem;
        }
        return $this->_cart;
   }

}