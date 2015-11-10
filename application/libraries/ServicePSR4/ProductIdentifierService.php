<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductIdentifierDao;

class ProductIdentifierService extends BaseService
{

	public function __construct()
    {
        parent::__construct();
	}
	
	public function get($where=array())
	{
		return $this->getDao()->get($where);
	}

	public function getProdGrpCdBySku ($sku)
	{
		$prod_grp_cd = "";
		if ($sku != null && $sku != "")
		{
			$identifier = explode("-", $sku);
			if (count($identifier) == 3)
				$prod_grp_cd = $identifier[0];
		}

		return $prod_grp_cd;
	}
}