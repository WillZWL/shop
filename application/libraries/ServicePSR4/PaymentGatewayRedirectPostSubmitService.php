<?php
namespace ESG\Panther\Service;

interface PaymentGatewayRedirectPostSubmitInterface
{
}

abstract class PaymentGatewayRedirectPostSubmitService extends PaymentGatewayRedirectService implements PaymentGatewayRedirectPostSubmitInterface
{
    public function __construct()
    {
        parent::__construct();
    }
}



