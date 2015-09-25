<?php
namespace ESG\Panther\Service;

interface PaymentGatewayRedirectServiceInterface
{
}

abstract class PaymentGatewayRedirectService extends PmgwVoucher implements PaymentGatewayRedirectServiceInterface
{
    public function __construct()
    {
        parent::__construct();
    }
}



