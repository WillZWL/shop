<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\PaymentOptionDao;

class PaymentOptionService extends BaseService
{
//    private $_paymentOptionSetDao;

    public function __construct() {
        parent::__construct();
        $this->setDao(new PaymentOptionDao());
//        $this->setPaymentOptionSetDao(new PaymentOptionSetDao());
    }
/*
    public function setPaymentOptionSetDao($value) {
        $this->_paymentOptionSetDao = $value;
    }

    public function getPaymentOptionSetDao() {
        return $this->_paymentOptionSetDao;
    }
*/    
    public function getPaymentOptionByPlatformId($platformId, $page = "checkout") {
        $where = ["po.platform_id" => $platformId, "po.page" => $page];
        $option = ["limit" => -1, "group_by" => "poc.code"];

        $result = $this->getDao()->getPaymentOption($where, $option);
        return $result;
    }
}
