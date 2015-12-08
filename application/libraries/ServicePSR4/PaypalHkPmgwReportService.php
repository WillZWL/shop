<?php
namespace ESG\Panther\Service;

class PaypalHkPmgwReportService extends PaypalPmgwReportService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPmgw()
    {
        return "paypal_hk";
    }
}
