<?php
namespace ESG\Panther\Service\Promotion;

use ESG\Panther\Service\ProductService;

class BaseDiscountItemService 
{	
	private $_promotionCodeObj;
	private $_cart;

	public function __construct($cartInfo,$promotionCodeObj)
    {
        $this->_promotionCodeObj=$promotionCodeObj;
        $this->_cart=$cartInfo;
        $this->productService=new ProductService();
    }

    public function getDiscountCartItem($redemptionProdSkuList,$redemptionAmount=null)
    {
    	$cartItemArr=null;
        foreach($redemptionProdSkuList as $sku) {
            if($sku){
            	$productDetails = $this->getPromotionCartItemInfo($sku);
	            if($productDetails){
	              $productDetails->setQty("1");
	              $productDetails->setRedemption("1");
	              $cartItemArr[$sku] = $productDetails;
	              $discountAmount += $productDetails->getPrice();
	              $discountQty += $productDetails->getQty();
	            }
            }
        }

        if($discountAmount){
			$discountAmount = $redemptionAmount ? $discountAmount - $redemptionAmount :$discountAmount;
			$unitDiscount=number_format(($discountAmount/$discountQty),2,'.', '');
            if($redemptionAmount){
                $unitPrice=number_format(($redemptionAmount/$discountQty),2,'.', '');
            }else{
                $unitPrice="0";
            }
			foreach($cartItemArr as $sku => $product){
                $itemSubTotal = $unitPrice * $product->getQty();
                $ItemDiscount=$product->getQty()*$unitDiscount;
                $totalAmount += $itemSubTotal;
                $totalItems += $product->getQty();
                $product->setPrice($unitPrice);
                $product->setAmount($itemSubTotal);
				//$product->setPromoDiscAmt($ItemDiscount);
				$this->_cart->items[$sku]=$product;
			}
			//$this->_cart->setPromoDiscTotal($discountAmount);
            $this->_cart->setTotalNumberOfItems($this->_cart->getTotalNumberOfItems()+$totalItems);
            $this->_cart->setSubtotal($this->_cart->getSubtotal()+$totalAmount);
            return $this->_cart;
        }
    }

    public function getPromotionCartItemInfo($sku)
    {
        $where = ["pr.platform_id" => $this->_cart->getplatformId()
                , "pc.lang_id" => $this->_cart->getLanguageId()
                , "p.sku" => $sku
                , "p.status" => 2
                , "pr.listing_status" => "L"
                , "p.website_status in ('I', 'P')" => null];
        $options["limit"] = 1;
        $cartItem=$this->productService->getDao()->getCartDataDetail($where,$options);
        return $cartItem;
    }

    public function validatePromotionRemoveItem($overAmount=null)
    {	
    	$itemsNums=null;$cartAmount=null;$result=false;
		if($this->_cart->getPromotionCode() && $this->_cart->items){
			foreach($this->_cart->items as $sku => $cartItem){
			  if($cartItem->getRedemption() != "1"){
			   	$itemsNums++;
			   	$cartAmount +=$cartItem->getAmount();
			  }
			}
			if($overAmount){
				$result = $cartAmount-$overAmount > 0 ? "true" :"false";
			}else{
				$result = $itemsNums ? "true" :"false";
			}

			if($result=="false"){
	            foreach($this->_cart->items as $sku => $cartItem){
	                if($cartItem->getRedemption() =="1"){
	                    unset($this->_cart->items[$sku]);
	                    $this->_cart->setPromotionError($this->_promotionCodeObj->getCode());
	                    $this->_cart->setPromotionCode(null);
	                }
	            }
        	}
		}
		return $this->_cart;
    }

    public function removePromotionCart()
    {   
    	$this->_cart->setPromotionCode(null);  
        //$this->_cart->setPromoDiscTotal(null);
        foreach($this->_cart->items as $sku => $cartItem){
            if($cartItem->getRedemption()=="1"){
                unset($this->_cart->items[$sku]);
                $totalAmount += $cartItem->getAmount();
                $totalItems += $cartItem->getQty();
            }
        }
        if($this->_cart){
            $totalNumberOfItems=$this->_cart->getTotalNumberOfItems();
            $this->_cart->setTotalNumberOfItems($totalNumberOfItems-$totalItems);
            $this->_cart->setSubtotal($this->_cart->getSubtotal()-$totalAmount);
        }
        return $this->_cart;
    }
}