<?php
namespace ESG\Panther\Models\Website;

use ESG\Panther\Service\Promotion\PromotionFactoryService;
use ESG\Panther\Dao\PromotionCodeDao;

class PromotionFactoryModel extends \CI_Model
{   
    private $promotionServiceArr=array(
            "A"=> \ESG\Panther\Service\Promotion\AmountPromotionService::class,
            "FD"=> \ESG\Panther\Service\Promotion\FreeDeliveryPromotionService::class,
            "P"=> \ESG\Panther\Service\Promotion\PercentPromotionService::class,
            "FI"=> \ESG\Panther\Service\Promotion\FreeItemPromotionService::class,
            "R"=> \ESG\Panther\Service\Promotion\RedemptionPromotionService::class,
        );
    private $_promotionFactoryService;

    public function __construct() {
        parent::__construct();
        $this->promotionCodeDao=new PromotionCodeDao();
    }

    public function initPromotionFactoryService($cartInfo,$promotionCode){
        $promotionCodeObj=$this->getPromotionCodeObj($promotionCode);
        if($cartInfo && $promotionCodeObj){
            $promotionType=$promotionCodeObj->getDiscType();
            $discountTypeInterface=new $this->promotionServiceArr[$promotionType]($cartInfo,$promotionCodeObj);
            $this->_promotionFactoryService=new PromotionFactoryService($discountTypeInterface,$cartInfo,$promotionCodeObj);
            return true;
        }else{
            return false;
        }
    }

    public function getPromotionCart()
    {
        $promotionCart=$this->_promotionFactoryService->getPromotionCart();
        return $promotionCart;
    }

    public function modifyPromotionCart()
    {
         return $this->_promotionFactoryService->modifyPromotionCart();
    }

    public function cancelPromotionCart()
    {
        return $this->_promotionFactoryService->cancelPromotionCart();
    }

    public function validRemoveItemPromotion()
    {
        return $this->_promotionFactoryService->validRemoveItemPromotion();
    }

    protected function getPromotionCodeObj($promotionCode)
    {
        $currentDate = date("Y-m-d");
        $option = ["limit" => 1, "orderby" => "create_on DESC"];

        if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $promotionCode)) {
            $email = mysql_real_escape_string($promotionCode);
            $whereStr = "(code='" . $email . "' OR '" . $email . "' LIKE email)";
            $where[$whereStr] = NULL;
        } else {
            $where["code"] = $promotionCode;
        }
        $where["status"] = 1;
        $where["(ISNULL(expire_date) OR expire_date >= '" . $currentDate . "')"] = null;
        return $this->promotionCodeDao->getList($where, $option);
    }

}
