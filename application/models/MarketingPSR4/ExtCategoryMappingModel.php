<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\CategoryService;
use ESG\Panther\Service\ExternalCategoryService;

class ExtCategoryMappingModel extends \CI_Model
{
    public $categoryService = null;
    public $externalCategoryService = null;

    public function __construct() {
        parent::__construct();
        if (!$this->categoryService)
            $this->categoryService = new CategoryService();
        if (!$this->externalCategoryService)
            $this->externalCategoryService = new ExternalCategoryService();
/*
        $this->load->library('service/ext_category_mapping_service');
        $this->load->library('service/category_service');
        $this->load->library('service/external_category_service');
        $this->load->library('service/platform_biz_var_service');
*/
    }

    public function index() {
//        printf("This is Model: %s", "Nero");
    }

    public function processCatDetail($where, $option) {
        $catList = $this->getCategoryList($where, $option);
        $arr_1 = $arr_2 = $arr_3 = [];
        foreach ($catList as $catObj) {
            if ($catObj->getLevel() == 1) {
                $arr_1[] = $catObj;
            }

            if ($catObj->getLevel() == 2) {
                $arr_2[] = $catObj;
            }

            if ($catObj->getLevel() == 3) {
                $arr_3[] = $catObj;
            }
        }
        return [$arr_1, $arr_2, $arr_3];
    }

    public function getCategoryList($where = array(), $option = array()) {
        return $this->categoryService->getDao("Category")->getList($where, $option);
    }

    public function getCountryList()
    {
        return $this->categoryService->getDao("SellingPlatform")->getWebsiteCountryList();
    }

    public function getGoogleCategoryList($requestGet)
    {
        $option = ["limit" => -1];
        $where = ["ext_party" => "GOOGLEBASE"];
        if (!empty($requestGet)) {
            if (isset($requestGet["gcat"])) {
                $gCatName = $requestGet["gcat"];
                if ($requestGet["gcat_wildtype"] == "begin")
                    $where["ext_name LIKE '$gCatName%'"] = null;
                elseif ($requestGet["gcat_wildtype"] == "end")
                    $where["ext_name LIKE '%$gCatName'"] = null;
                else
                    $where["ext_name LIKE '%$gCatName%'"] = null;
            }
            if (isset($requestGet["gcat_country"]))
                $where["country_id"] = $requestGet["gcat_country"];
        }

        return $this->externalCategoryService->getDao("ExternalCategory")->getList($where, $option);
    }


    public function create_or_update_mapping($categroy_id, $target_google_category, $country_id)
    {
        $where = $option = $where_2 = $option_2 = array();
        $where["ext_party"] = "GOOGLEBASE";
        $where["country_id"] = $country_id;
        $where["category_id"] = $categroy_id;

        $where_2["ext_party"] = "GOOGLEBASE";
        $where_2["country_id"] = $country_id;
        $where_2["category_id"] = $categroy_id;
        $where_2["ext_id"] = $target_google_category;
        $where_2['`status`'] = 1;


        if ($category_vo = $this->ext_category_mapping_service->get_dao()->get($where_2)) {
            return "Rule Already Exists.";
        } elseif ($category_vo = $this->ext_category_mapping_service->get_dao()->get($where)) {
            $category_vo->set_ext_id($target_google_category);
            $category_vo->set_status(1);
            if ($this->ext_category_mapping_service->update($category_vo)) {
                return "New Mapping Rule Update Successfully.";
            } else {
                return "Rule Exists, but Update fail. Please Contact technical staffs for help";
            }
        } else {
            $category_vo = $this->ext_category_mapping_service->get_dao()->get();
            $category_vo->set_ext_party("GOOGLEBASE");
            $category_vo->set_category_id($categroy_id);
            $category_vo->set_ext_id($target_google_category);
            $category_vo->set_country_id($country_id);
            $category_vo->set_status(1);
            if ($this->ext_category_mapping_service->insert($category_vo)) {
                return "New Mapping Rule Update Successfully.";
            } else {
                return "Update Fail. Please Contact technical staffs for help";
            }
        }
    }

    public function create_new_google_category($new_google_cat, $country_list)
    {

        $feedback = $feedback_success = $feedback_fail = $feedback_waring = "";

        if (strlen($new_google_cat) > 15) {
            $new_google_cat_short = substr($new_google_cat, 0, 7) . '...' . substr($new_google_cat, -5);
        } else {
            $new_google_cat_short = $new_google_cat;
        }
        $new_google_cat_short .= "<strong> &nbsp;&nbsp;  </strong>";

        foreach ($country_list as $country_id) {
            if ($google_category_vo = $this->external_category_service->get(array("country_id" => $country_id, "ext_name" => $new_google_cat))) {
                $feedback_waring .= "$new_google_cat_short already exists in country $country_id<br>";
            } else {
                $google_category_vo = $this->external_category_service->get_dao()->get();
                $google_category_vo->set_ext_party("GOOGLEBASE");
                $google_category_vo->set_level(1);
                $google_category_vo->set_ext_name($new_google_cat);
                $platform_biz_var_vo = $this->platform_biz_var_service->get_dao()->get(array("platform_country_id" => $country_id));
                $lang_id = $platform_biz_var_vo->get_language_id();
                $google_category_vo->set_lang_id($lang_id);
                $google_category_vo->set_country_id($country_id);
                $google_category_vo->set_status(1);
                if ($this->external_category_service->get_dao()->insert($google_category_vo)) {
                    $feedback_success .= "$new_google_cat_short success add to $country_id<br>";
                } else {
                    $feedback_fail .= "$new_google_cat_short CANNOT be create to $country_id<br>";
                }
            }
        }
        if ($feedback_success != "") {
            $feedback .= "<strong style='width:20px;display:block;'>Success</strong>" . $feedback_success;
        }
        if ($feedback_fail != "") {
            $feedback .= "<strong style='width:20px;display:block;'>Fail</strong>" . $feedback_fail;
        }
        if ($feedback_waring != "") {
            $feedback .= "<strong style='width:20px;display:block;'>Warning</strong>" . $feedback_waring;
        }
        return $feedback;
    }

    function get_google_category_mapping_list($where = array(), $option = array())
    {
        return $this->ext_category_mapping_service->get_google_category_mapping_list($where, $option);
    }

    function get_category_combination($where = array(), $option = array())
    {
        return $this->ext_category_mapping_service->get_category_combination($where, $option);
    }

}

?>