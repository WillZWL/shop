<?php
namespace ESG\Panther\Service;

class PaypalUkPmgwReportService extends PaypalPmgwReportService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPmgw()
    {
        return "paypal_uk";
    }
}