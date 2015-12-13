<?php
include_once "SellerSetting.php";
class BestSeller extends SellerSetting
{
    protected $appId = "MKT0014";
    protected $type = 'BS';
    protected $handle = 'BestSeller';
    protected $limit = 10;

    public function __construct()
    {
        parent::__construct();
    }
}
