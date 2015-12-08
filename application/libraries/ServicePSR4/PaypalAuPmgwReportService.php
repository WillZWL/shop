<?php
namespace ESG\Panther\Service;

class PaypalAuPmgwReportService extends PaypalPmgwReportService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPmgw()
    {
        return "paypal_au";
    }
}
