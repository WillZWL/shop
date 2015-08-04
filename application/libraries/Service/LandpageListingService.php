<?php
namespace AtomV2\Service;

abstract class LandpageListingService extends BaseService
{
    const LIMIT = 10;

    public function __construct()
    {
        parent::__construct();
        // include_once(APPPATH . "libraries/dao/Landpage_listing_dao.php");
        // $this->set_dao(new Landpage_listing_dao());
        // include_once(APPPATH . "libraries/service/Category_service.php");
        // $this->category_service = new Category_service();
        // include_once(APPPATH . "libraries/service/Selling_platform_service.php");
        // $this->selling_platform_service = new Selling_platform_service();
    }

    public function get_limit()
    {
        return self::LIMIT;
    }
}
