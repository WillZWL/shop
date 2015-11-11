<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\RaProdCatDao;
use ESG\Panther\Service\CategoryService;

class RaProdCatService extends BaseService
{

    private $cat_srv;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new RaProdCatDao);
        $this->categoryService = new CategoryService;
    }

    public function getRaProdCatWithExtNameList($cat_id, $lang_id = "en")
    {
        $data = [];
        if ($ra_prod_cat = $this->get(["ss_cat_id" => $cat_id])) {
            for ($i = 1; $i < 9; $i++) {
                $getter = "getRcmSsCatId" . $i;
                if ($cur_ra_cat_id = $ra_prod_cat->$getter()) {
                    if ($cat_ext_obj = $this->categoryService->getCatExtDefaultWithKeyList(["c.id" => $cur_ra_cat_id, "l.id" => $lang_id], ["limit" => 1])) {
                        $data[] = $cat_ext_obj;
                    }
                }
            }
        }
        return $data;
    }
}


