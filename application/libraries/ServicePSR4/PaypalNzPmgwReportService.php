<?php
namespace ESG\Panther\Service;

class PaypalNzPmgwReportService extends PaypalPmgwReportService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPmgw()
    {
        return "paypal_nz";
    }
}