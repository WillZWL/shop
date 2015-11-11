<?php 
namespace ESG\Panther\Service;


use ESG\Panther\Dao\PriceDao;
use ESG\Panther\Dao\ProductDao;
use ESG\Panther\Dao\ConfigDao;
use ESG\Panther\Service\CacheApiRequestDao;
use ESG\Panther\Service\BatchService;
use ESG\Panther\Service\AdwordsService;
use ESG\Panther\Service\GoogleShoppingService;

class ProductUpdateFollowupService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }


    public function googleShoppingUpdate($sku = "", $schedule_update = true)
    {
        if (!$prod_obj = $this->ProductDao->get(array("sku" => $sku))) {
            return false;
        }
        $latest_stock_status = $prod_obj->get_website_status();

        $displat_quantity = $prod_obj->get_display_quantity();
        $website_quantity = $prod_obj->get_website_quantity();

        if (($displat_quantity <= 0) || ($website_quantity <= 0)) {
            $availability = 'N';
        } else {
            $availability = 'Y';
        }

        //loop all website platform -- stock status detect
        $all_platform_obj_list = $this->platform_biz_var_service->get_selling_platform_list();

        foreach ($all_platform_obj_list as $platform_obj) {
            $platform_id = $platform_obj->get_id();
            //check the status first, website_status, listing_status, google_shopping status
            if (substr($platform_id, 0, 3) == "WEB") {
                if (!$this->google_shopping_service->get_shopping_api_accountId($platform_id)) {
                    continue;
                }

                //get the listing status
                if ($price_obj = $this->get_price_dao()->get(array("sku" => $sku, "platform_id" => $platform_id))) {
                    $listing_status = $price_obj->get_listing_status();
                    $latest_price = $price_obj->get_price();
                    $is_advertised = $price_obj->get_is_advertised();

                    //numbers of field determine the ad status paused/enabled
                    //product table's prod_status, can be change in product management page
                    //then is_advertised, listing_status and stock status

                    $prod_obj = $this->product_dao->get(array("sku" => $sku));
                    $prod_status = $prod_obj->get_status();

                    if (!$prod_status) {
                        $required_status = 0;
                    } elseif ($availability == 'N') {
                        $required_status = 0;
                    } elseif ($is_advertised == "N") {
                        $required_status = 0;
                    } elseif ($listing_status == "N") {
                        $required_status = 0;
                    } elseif (in_array($latest_stock_status, array("A", "O", "P"))) {
                        $required_status = 0;
                    } else {
                        $required_status = 1;
                    }


                    $stock_status = ($required_status ? "ENABLED" : "PAUSED");

                    //if don't want to advertise and this record not exists, then no need to do anything
                    if ((!$gsc_obj = $this->google_shopping_service->get_dao()->get(array("sku" => $sku, "platform_id" => $platform_id))) && ($is_advertised == "N")) {
                        continue;
                    } elseif ((!$required_status) && (!$gsc_obj = $this->google_shopping_service->get_dao()->get(array("sku" => $sku, "platform_id" => $platform_id)))) {
                        continue;
                    } elseif ((!$gsc_obj = $this->google_shopping_service->get_dao()->get(array("sku" => $sku, "platform_id" => $platform_id))) && ($is_advertised == "Y")) {

                        $gsc_obj = $this->google_shopping_service->get_dao()->get();
                        $gsc_obj->set_sku($sku);
                        $gsc_obj->set_platform_id($platform_id);
                        $gsc_obj->set_status($required_status);
                        $gsc_obj->set_price($latest_price);
                        $gsc_obj->set_api_request_result(0);
                        $this->google_shopping_service->get_dao()->insert($gsc_obj);

                        if (!$cache_api_request_vo = $this->cache_api_request_dao->get(array("api" => "GSC", "sku" => $sku, "platform_id" => $platform_id))) {
                            $cache_api_request_vo = $this->cache_api_request_dao->get();
                            $cache_api_request_vo->set_api("GSC");
                            $cache_api_request_vo->set_sku($sku);
                            $cache_api_request_vo->set_platform_id($platform_id);
                            $cache_api_request_vo->set_stock_update($stock_status);
                            $cache_api_request_vo->set_price_update($latest_price);
                            $cache_api_request_vo->set_item_create('Y');
                            $cache_api_request_vo->set_exec(0);
                            $this->cache_api_request_dao->insert($cache_api_request_vo);
                        }
                    } elseif ($gsc_obj = $this->google_shopping_service->get_dao()->get(array("sku" => $sku, "platform_id" => $platform_id))) {
                        $running_status = $gsc_obj->get_status();

                        if ($running_status != $required_status) {
                            $this->google_shopping_service->get_dao()->trans_start();

                            $gsc_obj->set_status($required_status);
                            $gsc_obj->set_api_request_result(0);
                            $this->google_shopping_service->get_dao()->update($gsc_obj);

                            //stock status update needed
                            //if no pending api request (exec = 0) for this sku, then create it, else update it.
                            if (!$cache_api_request_vo = $this->cache_api_request_dao->get(array("api" => "GSC", "sku" => $sku, "platform_id" => $platform_id, "exec" => 0))) {
                                $cache_api_request_vo = $this->cache_api_request_dao->get();
                                $cache_api_request_vo->set_api("GSC");
                                $cache_api_request_vo->set_sku($sku);
                                $cache_api_request_vo->set_platform_id($platform_id);
                                $cache_api_request_vo->set_stock_update($stock_status);
                                $cache_api_request_vo->set_price_update('N');
                                $cache_api_request_vo->set_item_create('N');
                                $cache_api_request_vo->set_exec(0);
                                if ($this->cache_api_request_dao->insert($cache_api_request_vo)) {
                                    $this->google_shopping_service->get_dao()->trans_complete();
                                } else {
                                    $this->google_shopping_service->get_dao()->trans_rollback();
                                }
                            } elseif ($cache_api_request_vo = $this->cache_api_request_dao->get(array("api" => "GSC", "sku" => $sku, "platform_id" => $platform_id, "exec" => 0))) {
                                $cache_api_request_vo->set_stock_update($stock_status);
                                if ($this->cache_api_request_dao->update($cache_api_request_vo)) {
                                    $this->google_shopping_service->get_dao()->trans_complete();
                                } else {
                                    $this->google_shopping_service->get_dao()->trans_rollback();
                                }
                            }
                        }
                    }


                    if ($gsc_obj = $this->google_shopping_service->get_dao()->get(array("sku" => $sku, "platform_id" => $platform_id))) {
                        $running_price = $gsc_obj->get_price();
                        //required_status here is to disable price update function if that product is PAUSED
                        if (($latest_price != $running_price) && $required_status) {
                            //only when the ad is enable, update the price
                            $this->google_shopping_service->get_dao()->trans_start();

                            $gsc_obj->set_price($latest_price);
                            $gsc_obj->set_api_request_result(0);

                            $this->google_shopping_service->get_dao()->update($gsc_obj);

                            if (!$cache_api_request_vo = $this->cache_api_request_dao->get(array("api" => "GSC", "sku" => $sku, "platform_id" => $platform_id, "exec" => 0))) {
                                $cache_api_request_vo = $this->cache_api_request_dao->get();
                                $cache_api_request_vo->set_api("GSC");
                                $cache_api_request_vo->set_sku($sku);
                                $cache_api_request_vo->set_platform_id($platform_id);
                                $cache_api_request_vo->set_stock_update("N");
                                $cache_api_request_vo->set_price_update($latest_price);
                                $cache_api_request_vo->set_item_create('N');
                                $cache_api_request_vo->set_exec(0);
                                if ($this->cache_api_request_dao->insert($cache_api_request_vo)) {
                                    $this->google_shopping_service->get_dao()->trans_complete();
                                } else {
                                    $this->google_shopping_service->get_dao()->trans_rollback();
                                }
                            } elseif ($cache_api_request_vo = $this->cache_api_request_dao->get(array("api" => "GSC", "sku" => $sku, "platform_id" => $platform_id, "exec" => 0))) {
                                $cache_api_request_vo->set_price_update($latest_price);
                                if ($this->cache_api_request_dao->update($cache_api_request_vo)) {
                                    $this->google_shopping_service->get_dao()->trans_complete();
                                } else {
                                    $this->google_shopping_service->get_dao()->trans_rollback();
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($schedule_update) {
            if ($config_vo = $this->config_dao->get(array("variable" => "google_shopping_api_at_job"))) {
                if (!$value = $config_vo->get_value()) {
                    $this->batch_service->schedule_php_process(1, "marketing/ext_category_mapping/gsc_cache_api_exec");
                    $config_vo->set_value(1);
                    $this->config_dao->update($config_vo);
                }
            }
        }
    }

    public function adwords_update($sku = "", $google_adwords_target_platform_list = array(), $adGroup_status = array(), $schedule_update = true)
    {
        //Parameter $adGroup_status is not more used

        //var_dump($google_adwords_target_platform_list);die();


        if (!$prod_obj = $this->product_dao->get(array("sku" => $sku))) {
            return false;
        }

        $latest_stock_status = $prod_obj->get_website_status();
        $all_platform_obj_list = $this->platform_biz_var_service->get_selling_platform_list();
        foreach ($all_platform_obj_list as $platform_obj) {
            $platform_id = $platform_obj->get_id();
            if (substr($platform_id, 0, 3) == "WEB") {
                if ($price_obj = $this->get_price_dao()->get(array("sku" => $sku, "platform_id" => $platform_id))) {
                    $listing_status = $price_obj->get_listing_status();
                    $latest_price = $price_obj->get_price();

                    //check the required status for ad: paused/enabled
                    //listing_status, stock_status and adGroup_status, all these status can be found in pricing_tool_website page.

                    $prod_obj = $this->product_dao->get(array("sku" => $sku));
                    $prod_status = $prod_obj->get_status();

                    //$required_status: 0 - paused, 2 - enabled

                    if (!$prod_status) {
                        $required_status = 0;
                    } elseif ($listing_status == "N") {
                        $required_status = 0;
                    } elseif (in_array($latest_stock_status, array("A", "O", "P"))) {
                        $required_status = 0;
                    } else {
                        $required_status = 1;
                    }

                    $stock_status = ($required_status ? "ENABLED" : "PAUSED");
                    //first check if this is the first time that want to create the ad. This is indicated by $google_adwords_target_platform_list
                    if (isset($google_adwords_target_platform_list[$platform_id])) {
                        //create ad. need to create record in cache_api_request and adwords_data
                        $this->adwords_service->get_adwords_data_dao()->trans_start();

                        //NOTICE: the way to get the dao.
                        $adwords_vo = $this->adwords_service->get_adwords_data_dao()->get();
                        $adwords_vo->set_sku($sku);
                        $adwords_vo->set_platform_id($platform_id);
                        $adwords_vo->set_status($required_status);
                        $adwords_vo->set_price($latest_price);
                        $adwords_vo->set_api_request_result(0);

                        if ($this->adwords_service->get_adwords_data_dao()->insert($adwords_vo)) {
                            //if success, create the cache_api_request record
                            $cache_api_request_vo = $this->cache_api_request_dao->get();
                            $cache_api_request_vo->set_api("AD");
                            $cache_api_request_vo->set_sku($sku);
                            $cache_api_request_vo->set_platform_id($platform_id);
                            //pass the stock status even it's to create the ad
                            $cache_api_request_vo->set_stock_update($stock_status);

                            //pass the price even it's to create the ad
                            $cache_api_request_vo->set_price_update($latest_price);
                            $cache_api_request_vo->set_item_create("Y");
                            $cache_api_request_vo->set_exec(0);

                            if ($this->cache_api_request_dao->insert($cache_api_request_vo)) {
                                $this->adwords_service->get_adwords_data_dao()->trans_complete();
                            } else {
                                $this->adwords_service->get_adwords_data_dao()->trans_rollback();
                            }
                        } else {
                            $this->adwords_service->get_adwords_data_dao()->trans_rollback();
                        }
                    } elseif ($adwords_obj = $this->adwords_service->get_adwords_data_dao()->get(array("sku" => $sku, "platform_id" => $platform_id))) {
                        $running_status = $adwords_obj->get_status();
                        $running_price = $adwords_obj->get_price();

                        //handle both the stock status and the price update at a time.
                        //update the price only the ad is enabled.
                        if (($running_status != $required_status) || (($running_price != $latest_price) && $required_status)) {
                            //first update the adwords_data record, then then cache_api_request table
                            $this->adwords_service->get_adwords_data_dao()->trans_start();
                            $adwords_obj->set_status($required_status);
                            $adwords_obj->set_price($latest_price);
                            $adwords_obj->set_api_request_result(0);
                            if ($this->adwords_service->get_adwords_data_dao()->update($adwords_obj)) {
                                //start manipulate the cache_api_request

                                //if there already a record with exact sku and platform_id value and not be executed yet, then update it;else create new one
                                if ($cache_api_request_vo = $this->cache_api_request_dao->get(array("api" => "AD", "sku" => $sku, "platform_id" => $platform_id, "exec" => 0))) {
                                    if ($running_status != $required_status) {
                                        $cache_api_request_vo->set_stock_update($stock_status);
                                    }
                                    if (($running_price != $latest_price) && $required_status) {
                                        $cache_api_request_vo->set_price_update($latest_price);
                                    }

                                    if ($this->cache_api_request_dao->update($cache_api_request_vo)) {
                                        $this->adwords_service->get_adwords_data_dao()->trans_complete();
                                    } else {
                                        $this->adwords_service->get_adwords_data_dao()->trans_rollback();
                                    }
                                } else {
                                    $cache_api_request_vo = $this->cache_api_request_dao->get();
                                    $cache_api_request_vo->set_api("AD");
                                    $cache_api_request_vo->set_sku($sku);
                                    $cache_api_request_vo->set_platform_id($platform_id);
                                    $cache_api_request_vo->set_item_create("N");
                                    $cache_api_request_vo->set_exec(0);
                                    if ($running_status != $required_status) {
                                        $cache_api_request_vo->set_stock_update($stock_status);
                                    } else {
                                        $cache_api_request_vo->set_stock_update("N");
                                    }

                                    if (($running_price != $latest_price) && $required_status) {
                                        $cache_api_request_vo->set_price_update($latest_price);
                                    } else {
                                        $cache_api_request_vo->set_price_update("N");
                                    }

                                    if ($this->cache_api_request_dao->insert($cache_api_request_vo)) {
                                        $this->adwords_service->get_adwords_data_dao()->trans_complete();
                                    } else {
                                        $this->adwords_service->get_adwords_data_dao()->trans_rollback();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($schedule_update) {
            if ($config_vo = $this->config_dao->get(array("variable" => "adwords_api_at_job"))) {
                if (!$value = $config_vo->get_value()) {
                    $this->batch_service->schedule_php_process(1, "marketing/ext_category_mapping/ad_cache_api_exec");
                    $config_vo->set_value(1);
                    $this->config_dao->update($config_vo);
                }
            }
        }
    }

    

}

?>