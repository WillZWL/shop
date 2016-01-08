<?php
//require_once(BASEPATH . "plugins/gshoppingcontent/GShoppingContent.php");
use ESG\Panther\Models\Marketing\ExtCategoryMappingModel;

class ExtCategoryMapping extends MY_Controller
{
    private $appId = "MKT0074";
    private $langId = "en";
    public $extCategoryMappingModel;

    public function __construct() {
        parent::__construct();
        $this->extCategoryMappingModel = new ExtCategoryMappingModel();
    }

    public function index() {
        $where = $option = array();
        $where["status"] = 1;
        $option["limit"] = -1;
        $data['cat_details_list'] = $this->extCategoryMappingModel->processCatDetail($where, $option);
        $data['country_list'] = $this->extCategoryMappingModel->getCountryList();
        $data['google_category_list'] = $this->extCategoryMappingModel->getGoogleCategoryList($_GET);

        $data["google_datafeed_account"] = $this->sc["Google"]->getShoppingAccountInfoList();
//var_dump($this->sc["Google"]->getAdwordAccountInfoList());exit;
        $this->load->view('marketing/ext-category-mapping/ext-category-mapping-index', $data);
    }

    public function getAppId() {
        return $this->appId;
    }

    public function get_google_category_existing_mapping()
    {
        $where = $option = array();
        $option['limit'] = -1;
        //var_dumP($this->db->last_query());die();
        $container = array();
        if ($s = $this->ext_category_mapping_model->getCountryGoogleCategoryMapping($where, $option)) {
            foreach ($s as $d) {
                $container[$d->get_country_id()][$d->get_category_id()] = $d->get_google_category_name();
            }
        }

        $t = array();

        if ($country_list = $this->ext_category_mapping_model->get_country_list()) {
            foreach ($country_list as $c) {
                $t[$c] = @$container[$c];
            }
        }
    }

    public function createMappingRule()
    {
        $cat_id = trim($_POST["cat_id"]);
        $sub_cat_id = trim($_POST["sub_cat_id"]);
        $sub_sub_cat_id = trim($_POST["sub_sub_cat_id"]);
        $country_id = trim($_POST["country_id"]);
        $target_google_category = trim($_POST["target_google_category"]);
        if (($cat_id == "" && $sub_cat_id == "" && $sub_sub_cat_id == "") || $country_id == "" || $target_google_category == "") {
            echo "Please check you input and try again.";
        } else {
            //always mapping to the more details category_id
            $categroy_id = $sub_sub_cat_id ? $sub_sub_cat_id : ($sub_cat_id ? $sub_cat_id : $cat_id);
            $feedback = $this->extCategoryMappingModel->createOrUpdateMapping($categroy_id, $target_google_category, $country_id);
            echo $feedback;
        }
    }

    public function createGoogleCategory()
    {
        $new_google_cat = rtrim($_POST["new_google_cat"], " > ");
        $country_list = $_POST["country_list"];
        $feedback = $this->extCategoryMappingModel->createNewGoogleCategory($new_google_cat, $country_list);
        echo $feedback;
    }

    public function accountInfo() {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($this->sc["Google"]->getAdwordAccountInfoList());
    }

    public function compaign_info()
    {

        $ad_accountId = trim($_POST['ad_accountId']);
        $user = $this->adwords_service->init_account($ad_accountId);

        $result = array();
        if (empty($ad_accountId)) {
            $result[] = array("error" => "invalid input: please select account");
            echo json_encode($result);
            exit();
        }
        $result = $this->adwords_service->compaign_info($user);
        echo json_encode($result);
    }

    public function adgroup_info()
    {
        $ad_accountId = trim($_POST['ad_accountId']);
        $user = $this->adwords_service->init_account($ad_accountId);
        $campaignId = trim($_POST['campaignId']);
        $result = array();
        if (empty($ad_accountId) || empty($campaignId)) {
            $result[] = array("error" => "invalid input: please try again");
            echo json_encode($result);
            exit();
        }
        $result = $this->adwords_service->adgroup_info($user, $campaignId);
        echo json_encode($result);
    }

    public function keyword_info()
    {
        $ad_accountId = trim($_POST['ad_accountId']);
        $user = $this->adwords_service->init_account($ad_accountId);
        $adGroupId = trim($_POST['adGroupId']);
        $result = array();
        if (empty($ad_accountId) || empty($adGroupId)) {
            $result[] = array("error" => "invalid input: please try again");
            echo json_encode($result);
            exit();
        }
        $result = $this->adwords_service->keyword_info($user, $adGroupId);
        echo json_encode($result);
    }

    public function adGroup_ad_info()
    {
        $ad_accountId = trim($_POST['ad_accountId']);
        $user = $this->adwords_service->init_account($ad_accountId);
        $adGroupId = trim($_POST['adGroupId']);
        $result = array();
        if (empty($ad_accountId) || empty($adGroupId)) {
            $result[] = array("error" => "invalid input: please try again");
            echo json_encode($result);
            exit();
        }
        $result = $this->adwords_service->adGroup_ad_info($user, $adGroupId);
        echo json_encode($result);
    }

    public function keyword_parameter_info()
    {
        $ad_accountId = trim($_POST['ad_accountId']);
        $keywordId = trim($_POST['keywordId']);
        $user = $this->adwords_service->init_account($ad_accountId);
        $adGroupId = trim($_POST['adGroupId']);
        $result = array();
        if (empty($ad_accountId) || empty($adGroupId) || empty($keywordId)) {
            $result[] = array("error" => "invalid input: please try again");
            echo json_encode($result);
            exit();
        }
        $feedback = '';

        $result = $this->adwords_service->keyword_parameter_info($user, $adGroupId, $keywordId);
        foreach ($result as $val) {
            $feedback .= "keyword ID: " . $val->criterionId . "<br>";
            $feedback .= "Attached Param: " . $val->insertionText . "<br>";
            $feedback .= "ParamIndex: " . $val->paramIndex . "<br>";
        }
        echo $feedback;
    }

    public function start_point($sku, $platform_id)
    {
        $this->adwords_service->start_point($sku, $platform_id);
    }

    public function update_ad_price($sku, $platform_id)
    {
        $this->adwords_service->update_ad_price($sku, $platform_id);
    }

    public function pause_or_resume_adGroup($sku, $platform_id, $status = 'PAUSED')
    {
        //status: PAUSED, DELETED, ENABLED
        $this->adwords_service->pause_or_resume_adGroup($sku, $platform_id, $status);
    }

    function process_data($sku, $platform_id, $test = 0)
    {
        $result = $this->adwords_service->process_data($sku, $platform_id, $test = 0);
        var_dump($result);
    }

    public function getProductItem($id = "", $country = "", $language = "")
    {
        $input = $_POST;
        if ($getproduct_result = $this->sc["GoogleShopping"]->getProduct($input)) {
            $account_id = @$_POST["account_id"];
            $country = @$_POST["country_id"];
            $language = @$_POST["language_id"];
            $sku = @$_POST["sku"];
            if ($getproduct_result["status"] == TRUE) {
                if ((array) $getproduct_result["data"]) {
                    $product = $getproduct_result["data"];
                    $sku = $product->offerId;
                    $title = $product->title;
                    $condit = $product->condition;
                    $avail = $product->availability;
                    $brand = $product->brand;
                    $gtin = $product->gtin;
                    $mpn = $product->mpn;
                    $price = $product->price["value"];
                    $currency = $product->price["currency"];
                    $google_categorys = $product->googleProductCategory;
                    $adwords_redirect = $product->adwordsRedirect;

                    $result = <<<end
                        <input type="hidden" id="item_account" value="$account_id">
                        <input type="hidden" id="item_country" value="$country">
                        <input type="hidden" id="item_language" value="$language">
                        <label class="item_label">sku</label><input class="input_box" id="item_sku" value="$sku" readonly>
                        <label class="item_label">title</label><input class="input_box" id="item_title" value="$title">
                        <label class="item_label">cond</label><input class="input_box" id="item_condi" value="$condit">
                        <label class="item_label">price</label><input class="input_box" id="item_price" value="$price">  <br>
                        <label class="item_label">brand</label><input class="input_box" id="item_brand" value="$brand">
                        <label class="item_label">avail</label><input class="input_box" id="item_valid" value="$avail">
                        <label class="item_label">gtin</label><input class="input_box" id="item_gtin" value="$gtin">
                        <label class="item_label">mpn</label><input class="input_box" id="item_mpn" value="$mpn">  <br>
                        <label class="item_label">currency</label><input class="input_box" id="item_currency" value="$currency">
                        <label class="item_label">google_cat</label><input class="input_box" id="item_google_categorys" style="width:680px" value="$google_categorys">
                        <br>
                        <label class="item_label">adwords_redirect</label><input class="input_box" id="item_adwords_redirect" style="width:680px" value="$adwords_redirect">

end;
                    echo $result;
                    die();

                } else {
                    print __LINE__ . __METHOD__ . ", No object result obtain";
                    exit;
                }
            } else {
                print $getproduct_result["error_message"];
                exit;
            }
        }
        echo "No Result Found";
    }

    public function updateProductItem()
    {
        $input = $_POST;
        $updateResult = $this->sc["GoogleShopping"]->updateProduct($input);
        if ($updateResult["status"]) {
            print $input["item_sku"] . " Updated successfully";
        } else {
            print $updateResult["error_message"];
        }
    }
/*
    public function delete_product_item($platform_id = "", $sku = "", $country_id = "", $language_id = "")
    {
        $this->google_shopping_service->delete_product_item($platform_id, $sku, $country_id, $language_id);
    }

    //confusing function of update_google_shopping_item_by_platform and cron_update_google_shopping_feed
    //

    public function gen_data_feed($platform_id = "WEBSG")
    {
        $result = $this->google_shopping_service->gen_data_feed($platform_id);
    }
*/
    public function updateGoogleShoppingItemByPlatform($platformId = "WEBSG", $sku = "")
    {
        set_time_limit(600);
        $this->sc["GoogleShopping"]->updateGoogleShoppingItemByPlatform($platformId, $sku);
    }

    public function updateGoogleShoppingItemAllPlatform()
    {
        set_time_limit(3000);
        $sellingPlatObj = $this->sc["SellingPlatform"]->getDao("SellingPlatform")->getList(["status" => 1], ["limit" => -1]);
        foreach ($sellingPlatObj as $selling) {
            $this->sc["GoogleShopping"]->updateGoogleShoppingItemByPlatform($selling->getSellingPlatformId());
        }
    }

    public function cron_update_google_shopping_feed($sku = "", $specified_platform = "")
    {
        $this->google_shopping_service->cron_update_google_shopping_feed($sku, $specified_platform);
    }

    public function getGoogleShoppingContentReport($platformId = "") {
        $this->sc["GoogleShopping"]->getGoogleShoppingContentReport($platformId);
    }

    public function update_adGroup_keyword_price_paramter($sku = "", $platform_id = "", $price = "")
    {
        $this->adwords_service->update_adGroup_keyword_price_paramter($sku, $platform_id, $price);
    }

    public function create_adGroup_by_platform_list($google_adwords_target_platform_list = "", $sku = "")
    {
        $this->adwords_service->create_adGroup_by_platform_list($google_adwords_target_platform_list, $sku);
    }

    public function update_adGroup_status_by_stock_status($sku = "", $platform_id = "", $status = "")
    {
        $this->adwords_service->update_adGroup_status_by_stock_status($sku, $platform_id, $status);
    }

    public function gsc_cache_api_exec()
    {
        // debuggin line
        $this->google_shopping_service->cache_api_exec_debug();

        // original line
        // $this->google_shopping_service->cache_api_exec();
    }

    public function ad_cache_api_exec()
    {
        $this->adwords_service->cache_api_exec();
    }

    public function _get_language_id()
    {
        return $this->langId;
    }

    public function getCountryGoogleCategoryMapping()
    {
        $country_id = $_POST["country_id"];
        $data['existsing_mapping'] = $container = $where = $option = [];
        $option['limit'] = -1;
        $where['ext_c.country_id'] = $country_id;
        if ($categoryMappingList = $this->extCategoryMappingModel->getGoogleCategoryMappingList($where, $option)) {
            foreach ($categoryMappingList as $obj) {
                $container[$obj->getCountryId()][$obj->getCategoryId()] = $obj->getGoogleCategoryName();
            }
        }

        $category_id_w_name = $where = $option = [];
        $option['limit'] = -1;
        if ($category_combination = $this->extCategoryMappingModel->getCategoryCombination($where, $option)) {
            foreach ($category_combination as $categoryList) {
                //echo $c->get_id();
                //echo preg_replace("/Base->/", '', $c->get_name());
                //echo "<br>";
                $categoryName = preg_replace("/Base->/", '', $categoryList->getName());
                $i = strpos($categoryName, '->');
                $i = $i ? $i : "100";
                $first_category_level = substr($categoryName, 0, $i); //this is category name
                $category_classification[$first_category_level][$categoryList->getId()] = $categoryName;
            }
        }

        $output = "";
        if ($category_classification) {
            foreach ($category_classification as $first_category_level => $sub_list) {
                $output .= "<div class='sub_accordion'>   <h3>{$first_category_level}</h3> <div> <p>";
                foreach ($sub_list as $category_id => $combination_category_name) {
                    //$container[$d->get_country_id()][$d->get_category_id()]
                    $google_category_name = $container[$country_id][$category_id];
                    if (!$google_category_name) {
                        $status = 'invalid_google_cat';
                    } else {
                        $status = 'valid_google_cat';
                    }
                    $output .= '<input type="text"  class="system_cat ' . $status . '" value="' . $combination_category_name . '" readonly><input type="text" class="google_cat ' . $status . '" value="' . $google_category_name . '" readonly> <br>';
                }
                $output .= "</p></div></div>";
            }
        }
        echo $output;
    }
}

?>