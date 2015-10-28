<?php
namespace ESG\Panther\Service;
use ESG\Panther\Dao\PaymentGatewayDao;

class PaymentGatewayService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new PaymentGatewayDao);
    }


}


