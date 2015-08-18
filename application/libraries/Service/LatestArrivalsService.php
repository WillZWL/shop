<?php
namespace AtomV2\Service;

class LatestArrivalsService extends ProductService
{
	private $proudctType;

    public function __construct()
    {
        parent::__construct();
        $this->proudctType = 'LA';
    }

    public function getLatestArrivalSku($where, $option)
    {
    	$where['ll.type'] = $this->proudctType;

        return $this->getLandPageSku($where, $option);
    }
}
