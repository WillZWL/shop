<?php
include_once "SellerSetting.php";
class LatestSeller extends SellerSetting
{
    protected $appId = "MKT0016";
    protected $type = 'LA';
    protected $handle = 'LatestSeller';
    protected $limit = 10;

    public function __construct()
    {
        parent::__construct();
    }
}