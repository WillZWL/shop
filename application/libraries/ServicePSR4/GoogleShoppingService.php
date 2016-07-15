<?php
namespace ESG\Panther\Service;

class GoogleShoppingService extends BaseService
{
    const NUMBER_OF_PRODUCTS_IN_BATCH = 250;
    private $_reportEmail = ["WEBFR" => "bd@numeristock.fr"
                            , "WEBGB" => "bd@digitaldiscount.co.uk"
                            , "WEBIT" => "bd@nuovadigitale.it"
                            , "WEBES" => "bd@buholoco.es"
                            , "WEBAU" => "bd@aheaddigital.com.au"
                            , "WEBNL" => "bd@9digital.nl"
                            , "WEBBE" => "bd@numeristock.fr"
                            , "WEBPL" => "bd@numeristock.fr"];
    private $_technicalEmail = "oswald-alert@eservicesgroup.com";

    public function __construct()
    {
        parent::__construct();
    }

    public function getShoppingApiAccountId($platformId) {
		if (getenv("APPLICATION_ENV") == "dev") {
//			return false;
//			return "11073443"; # test account
		}
		$this->setEmailList($platformId);
        $accountInfo = $this->getService("Google")->shoppingAcctInfo;
        if (array_key_exists($platformId, $accountInfo))
            return $accountInfo[$platformId]["account_id"];
        return "";
    }

    private function _prepareFormRequestToGoogle($language, $country, $sku) {
        $googleRefId = $country . '-' . $sku;
        return ["productId" => "online:$language:$country:$googleRefId"];
    }

    public function getProduct($input) {
        if (isset($input["country_id"]))
            $country = $input["country_id"];
        if (isset($input["language_id"]))
            $language = $input["language_id"];
        if (isset($input["sku"]))
            $sku = $input["sku"];

        if ($country && $language && $sku) {
            $accountId = $this->getShoppingApiAccountId("WEB" . $country);
            $requestData = $this->_prepareFormRequestToGoogle($language, $country, $sku);
            $googleConnect = $this->getService("GoogleConnect");
            return $googleConnect->getProduct($accountId, $requestData["productId"]);
        }
        return false;
    }

    public function updateProduct($input) {
        $sku = substr($input["item_sku"], 3);
        $categoryMappingObj = $this->getService("CategoryMapping")->getDao("CategoryMapping")->get(["ext_party" => "GOOGLEBASE", "category_mapping_id" => $sku, "country_id" => $input["item_country"]]);
        if($categoryMappingObj) {
//update title from database
            $categoryMappingObj->setProductName($input["item_title"]);
            if ($this->getService("CategoryMapping")->getDao("CategoryMapping")->update($categoryMappingObj)) {
                $requestData = $this->_prepareFormRequestToGoogle($input["item_language"], $input["item_country"], $sku);
                $accountId = $this->getShoppingApiAccountId("WEB" . $input["item_country"]);
                $googleConnect = $this->getService("GoogleConnect");
//get the product from google to modify
                $getProductResult = $googleConnect->getProduct($accountId, $requestData["productId"]);
                if ($getProductResult["status"] == TRUE) {
                    $getProductResult["gscDataObj"]->setTitle($input["item_title"]);                  
                    return $googleConnect->insertProduct($accountId, $getProductResult["gscDataObj"]);
                }
            }
        }
        return false;
    }
/*
    private function _convertArrayToString($array = array())
    {
        $return_str = "";

        if ($array) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $return_str .= $this->_convertArrayToString($value);
                } else {
                    $return_str .= "[\"$key\"] => $value \r\n";
                }
            }
        }

        return $return_str;
    }

    private function GSC_error_handler_record($comment, $sku, $platform_id)
    {
        // if due to following reasons, update price.is_advertised
        if (strpos($comment, "Insufficient Identifier") != FALSE || strpos($comment, "Missing detail desc") != FALSE) {
            $price_obj = $this->get_price_dao()->get(array("sku" => $sku, "platform_id" => $platform_id));
            if ($price_obj) {
                $price_obj->set_is_advertised("N");
                $this->get_price_dao()->update($price_obj);
            }
        }

        // update google api request
        $this->api_request_result_update($sku, $platform_id, 0, $comment);
    }


    # 2015-03-12 - this function seems to be not in use

    public function get_GSC_product($sku, $platform_id)
    {
        $where = array();
        $where["pr.sku"] = $sku;
        $gs_obj_list = $this->gen_data_feed($platform_id, $shopping_api = TRUE, $where);

        $ret["status"] = false;
        if ($gs_obj_list) {
            foreach ($gs_obj_list as $gs_obj) {
                $product = $product_encoded = array();
                $needed_identifier = array();
                $ret["status"] = false;

                $title = $gs_obj->get_prod_name();
                $description = $gs_obj->get_detail_desc();
                $description = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $description);
                $description = str_replace("\r", " ", $description);
                $description = str_replace("\n", " ", $description);

                if (!$description) {
                    $ret["error_message"] = "Missing detail desc - PLA unlisted ";
                    $ret["product"] = array();
                    return $ret;
                }

                if (strlen($description) > 5000) {
                    $ret["error_message"] = "Detail Desc more than 5000 char ";
                    $ret["product"] = array();
                    return $ret;
                }
                $google_product_category = $gs_obj->get_google_product_category();
                $product_type = $gs_obj->get_product_type();
                $product_url = $gs_obj->get_product_url();
                $image_url = $gs_obj->get_image_url();
                $condition = $gs_obj->get_condition();
                $availability = $gs_obj->get_availability();
                if ($availability == "out of stock") {
                    $availability = "in stock";
                }

                $price_w_curr = $gs_obj->get_price_w_curr();

                $brand_name = $gs_obj->get_brand_name();
                $mpn = $gs_obj->get_mpn();
                $upc = $gs_obj->get_upc();
                $ean = $gs_obj->get_ean();
                $gtin = $upc ? $upc : $ean;     # gtin has been set to "" for all platforms

                // Since GTIN is empty, brand && mpn cannot be empty, else, don't bother sending to google
                if ($brand_name != "" && $brand_name != NULL) {
                    $needed_identifier[] = $brand_name;
                }
                if ($mpn != "" && $mpn != NULL) {
                    $needed_identifier[] = $mpn;
                }
                if (count($needed_identifier) < 2) {
                    $ret["error_message"] = "Insufficient Identifier [mpn | brand] - PLA unlisted";
                    $ret["product"] = array();
                    return $ret;
                }

                $item_group_id = $gs_obj->get_item_group_id();
                $colour_name = $gs_obj->get_colour_name();
                $shipping = $gs_obj->get_shipping();
                $prod_weight = $gs_obj->get_prod_weight();


                $price = $gs_obj->get_price();
                $currency = $gs_obj->get_platform_currency_id();
                $platform_country_id = $gs_obj->get_platform_country_id();

                // $product["contentLanguage"] = $lang_id;
                $product["contentLanguage"] = $gs_obj->get_language_id();
                $product["channel"] = "online";

                $google_ref_id = $platform_country_id . '-' . $sku;
                $product["offerId"] = $google_ref_id;
                $product["targetCountry"] = $platform_country_id;
                $product["imageLink"] = $image_url;
                $product["title"] = $title;
                $product["description"] = $description;
                $product["link"] = $product_url;
                $product["brand"] = $brand_name;
                $product["condition"] = $condition;
                $product["color"] = $colour_name;
                $product["availability"] = $availability;
                $product["googleProductCategory"] = $google_product_category;
                $product["productType"] = $product_type;

                $product["price"]["currency"] = $currency;
                $product["price"]["value"] = $price;

                $product["shippingWeight"]["unit"] = "kg";
                $product["shippingWeight"]["value"] = $prod_weight;

                $product["mpn"] = $mpn;


                if (($google_ref_id == "FR-16060-AA-WH") || ($google_ref_id == "FR-16060-AA-BK")) {
                    $extra_track_parameter = "&source={ifdyn:dyn}{ifpe:pe}{ifpla:pla}&dyprodid=$google_ref_id";

                    $product["adwordsRedirect"] = $product_url . $extra_track_parameter;
                }

                // Set GTIN empty for all platforms
                $product["gtin"] = "";
                $product["itemGroupId"] = $item_group_id;

                $region = '';
                $shippingPrice = '0.00';
                $service = 'Standard';

                $product["shipping"]["country"] = $platform_country_id;
                $product["shipping"]["region"] = $region;
                $product["shipping"]["price"] = $shippingPrice;
                $product["shipping"]["currency"] = $currency;
                $product["shipping"]["service"] = $service;

                // $product->addShipping($platform_country_id,$region,$shippingPrice,$currency,$service);
                $product_encoded = $this->utf_encode_array_values($product);

                $ret["status"] = TRUE;
                $ret["product"] = $product_encoded;
                return $ret;
            }
        } else {
            $ret["error_message"] = "Cannot retrieve product info for GSC product assemble";
        }

        return $ret;
    }

    private function utf_encode_array_values($array)
    {
        $return_array = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $return_array[$key] = $this->utf_encode_array_values($value);
                } else {
                    if (mb_detect_encoding($value) != "UTF-8")
                        $return_array[$key] = utf8_encode($value);
                    else
                        $return_array[$key] = $value;
                }
            }
        }

        return $return_array;
    }

    public function mail_result($content, $subject)
    {
        $mail_content = "";
        if (is_array($content)) {
            foreach ($content as $key => $val) {
                $mail_content .= $key . ":" . $val;
            }
            $mail_content = wordwrap($mail_content, 70, "\r\n");
        } else {
            $mail_content = $content;
        }

        $emailList = $this->getEmailList();
        $emailCcList = $this->getEmailCcList();


        $to = $cc = "";

        $to = implode(',', $emailList);
        $cc = implode(',', $emailCcList);

        if ($cc) {
            $header = "Cc: " . $cc . "\r\n";
            mail($to, $subject, $mail_content, $header);
        } else {
            mail($to, $subject, $mail_content);
        }
    }

    function getEmailList()
    {
        return $this->emailList;
    }

    function setEmailList($platformId)
    {
        $emailList = array();
        switch ($platformId) {
            case "WEBFR":
                $emailList[] = "google_shopping_FR@eservicesgroup.com";
                break;
            case "WEBES":
                $emailList[] = "google_shopping_ES@eservicesgroup.com";
                break;
            case "WEBIT":
                $emailList[] = "google_shopping_IT@eservicesgroup.com";
                break;
            case "WEBGB":
                $emailList[] = "google_shopping_GB@eservicesgroup.com";
                break;
            case "WEBCH":
                $emailList[] = "google_shopping_CH@eservicesgroup.com";
                break;
            case "WEBAU":
                $emailList[] = "google_shopping_AU@eservicesgroup.com";
                break;
            case "WEBPL":
                $emailList[] = "google_shopping_PL@eservicesgroup.com";
                break;
        }

        $emailList[] = "google-eu@valuebasket.com";
        $emailList[] = "itsupport@eservicesgroup.net";
        $this->emailList = $emailList;
    }

    function getEmailCcList()
    {
        return $this->emailCcList;
    }

    public function cron_update_google_shopping_feed($sku = "", $specified_platform = "")
    {
        $platform_biz_obj_list = $this->platform_biz_var_service->get_selling_platform_list();

        if ($specified_platform) {
            $this->updateGoogleShoppingItemByPlatform($specified_platform, $sku);
        } else {
            foreach ($platform_biz_obj_list as $obj) {
                $platform_id = $obj->get_id();
                $this->updateGoogleShoppingItemByPlatform($platform_id, $sku);
            }
        }
    }
*/
    public function updateGoogleShoppingItemByPlatform($platformId = "", $sku = "") {
        $this->updateGoogleShoppingItem($platformId, $sku);
    }
/***************************************************************
**  updateGoogleShoppingItem 
**  This function will insert record into pending_google_api_request
**  Another process(CronUpdatePriceMargin/processGoogleApiRequest) = GoogleShoppingService->sendBatchRequestToGoogle will get all the data inside and send to google 
****************************************************************/
    public function updateGoogleShoppingItem($platformId = "", $sku = "") {
        $this->getService("PendingGoogleApiRequest")->getDao("PendingGoogleApiRequest")->insertGoogleShoppingDataForBatch($platformId, $sku);
        $this->getService("GoogleConnect")->deleteAllProductFromPlatform($platformId);
    }

/**************************************************
**  Get a batch ID and mark the record with a batch ID and transfer to other to process to keep pending table slim
***************************************************/
    public function sendBatchRequestToGoogle() {
        error_log(__METHOD__ . ":" . __LINE__ . ", Memory:" . memory_get_usage());
        $this->getService("GoogleRequestBatch")->getDao("PendingGoogleApiRequest")->db->save_queries = false;
        $hasRequest = $this->getService("GoogleRequestBatch")->getDao("PendingGoogleApiRequest")->getList([], ["limit" => 1]);
        if ($hasRequest) {
            if (($batchObj = $this->getService("GoogleRequestBatch")->getNewBatch(GoogleService::FUNC_GOOGLE_CONTENT_API)) !== false) {
                $batchId = $batchObj->getId();
                $this->getService("GoogleRequestBatch")->getDao('GoogleRequestBatch')->db->trans_begin();
//insert
                $insertResult = $this->_sendBatchRequestToGoogleStep1($batchId);
//delete
                $deleteResult = $this->_sendBatchRequestToGoogleStep2();

                if ($insertResult && $deleteResult) {
                    $transCompleteResult = $this->getService("GoogleRequestBatch")->getDao('GoogleRequestBatch')->db->trans_commit();
                    if ($transCompleteResult) {
//we do the update of the google api request table after the transaction lock
//update
                        $updateResult = $this->_sendBatchRequestToGoogleStep3($batchId);
                        if ($updateResult !== false)
                            $this->processBatchByBatchId($batchId);
                    }
                } else {
                    $this->getService("GoogleRequestBatch")->getDao('GoogleRequestBatch')->db->trans_rollback();
                }
                $this->getService("GoogleRequestBatch")->setBatchStatus($batchObj, date("Y-m-d H:i:s"));
            } else {
                $this->sendAlert("[Panther] Cannot get a new batch to send google API", "error:" . $this->getService("GoogleRequestBatch")->getDao('GoogleRequestBatch')->db->error()["message"]);
            }
        }
        error_log(__METHOD__ . ":" . __LINE__ . ", Memory:" . memory_get_usage());
    }

    public function processBatchByBatchId($batchId, $reprocess = false) {
//        error_log(__METHOD__ . ":" . __LINE__ . ", Memory:" . memory_get_usage());
        $where["request_batch_id"] = $batchId;
        if ($reprocess) {
            $where["`result` in ('N', 'F')"] = null;
        } else {
            $where["result"] = "N";
        }
        $processingList = $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->getList($where, ["limit" => -1]);
        if (($processingList) && !is_array($processingList))
            $processingList = [$processingList];
        $googleConnect = $this->getService("GoogleConnect");
        if ($processingList) {
            $i = 0; //will be the entry ID
            do {
                $reOrderList = [];
                for($j=$i;$j<sizeof($processingList);$j++) {
                    $reOrderList[$i] = $processingList[$j];
                    $i++;
                    if (($i%self::NUMBER_OF_PRODUCTS_IN_BATCH) == 0)
                        break;
                }
                $googleConnect->processingInsertDeleteProductBatch($reOrderList);
                foreach($reOrderList as $key => $googleApiRequestObj) {
                    $updateResult = $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->update($googleApiRequestObj);
                    if ($updateResult === false) {
                        $this->_sendAlert("[Panther] cannot save google api request result", $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->db->last_query() . ", error:". $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->db->error()["message"]);
                    }
                }
            } while ($i<sizeof($processingList));
            unset($reOrderList, $processingList);
            $updateToPriceResult = $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->updateBatchRequestToPrice($batchId);
            if ($updateToPriceResult === false) {
                $this->_sendAlert("[Panther] cannot update price", $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->db->last_query() . ", error:". $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->db->error()["message"]);
            }
            
            $this->sendRequestResultToUser($batchId);
        } else {
            $this->_sendAlert("[Panther] batch has no data, batch_id:" . $batchId, $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->db->last_query() . ", error:". $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->db->error()["message"]);
        }
    }

    private function _sendEmailToUser($platformId, $content, $batchId) {
        if (array_key_exists($platformId, $this->_reportEmail)) {
            $email = $this->_reportEmail[$platformId];
            $subject = "[Panther] Google Content API alert: batchId:" . $batchId;
            mail($email, $subject, $content, "From: admin@digitaldiscount.co.uk\r\nMIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\n");
        } else {
            mail($this->_technicalEmail, "[Panther] Google Shopping API alert email not exist on platformId:" . $platformId, "", "From: admin@digitaldiscount.co.uk\r\n");
        }
//        print $platformId;
//        print $content;
    }

    public function sendRequestResultToUser($batchId) {
        $where = ["request_batch_id" => $batchId, "result in ('F', 'W')" => null];
//        $where = ["request_batch_id" => $batchId, "result in ('S')" => null];
        $apiResultList = $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->getGoogleApiRequestByBatch($where, ["limit" => -1, "orderby" => "platform_id"]);
//        print $this->getService("GoogleApiRequest")->getDao("GoogleApiRequest")->db->last_query();
        $lastPlatformId = "";
        $emailContent = "";
//        var_dump($apiResultList);
        foreach($apiResultList as $googleApiRequest) {
            if ($googleApiRequest->getPlatformId() != $lastPlatformId) {
                if ($emailContent != "") {
                    $emailContent .= "</table>";
//                    print __LINE__ . $googleApiRequest->getPlatformId();
                    $this->_sendEmailToUser($lastPlatformId, $emailContent, $batchId);
                    $emailContent = "";
                }
            }
            if ($emailContent == "") {
                $emailContent .= "<table border='1' cellpadding='5'>";
                $emailContent .= "<tr><td>SKU</td><td>Google Product Status</td><td>result</td><td>Message</td></tr>";
            }
            $emailContent .= "<tr><td>" . $googleApiRequest->getSku() . "</td><td align='center'>" . $googleApiRequest->getGoogleProductStatus() . "</td><td align='center'>" . $googleApiRequest->getResult() . "</td><td>" . $googleApiRequest->getKeyMessage() . "</td></tr>";
            $lastPlatformId = $googleApiRequest->getPlatformId();
        }
        if ($emailContent != "") {
            if ($emailContent != "")
                $emailContent .= "</table>";
//            print __LINE__ . $googleApiRequest->getPlatformId();
            $this->_sendEmailToUser($lastPlatformId, $emailContent, $batchId);
        }
    }

/*******************************************************************
**  Step1, insert data into google_api_request
**
********************************************************************/
    public function _sendBatchRequestToGoogleStep1($batchId) {
        $result = $this->getService("GoogleApiRequest")->getDao('GoogleApiRequest')->cloneGoogleApiRequestDataWithBatchId($batchId);
        if ($result === false) {
            $this->_sendAlert("[Panther] Cannot insert data to google_api_request table, " . __METHOD__ . __LINE__, "error:" . $this->getService("GoogleApiRequest")->getDao('GoogleApiRequest')->db->error()["message"]);
        }
        return $result;
    }

/*******************************************************************
**  Step2, delete the data from google_api_request
**  this step is inside a transaction with step1 to prevent someone is updating this table
********************************************************************/
    public function _sendBatchRequestToGoogleStep2() {
        $result = $this->getService("PendingGoogleApiRequest")->getDao('PendingGoogleApiRequest')->clearPendingGoogleApiRequest();
        if ($result === false) {
            $this->_sendAlert("[Panther] Cannot clear data from google_api_request table, " . __METHOD__ . __LINE__, "error:" . $this->getService("PendingGoogleApiRequest")->getDao('PendingGoogleApiRequest')->db->error()["message"]);
        }
        return $result;
    }

/*******************************************************************
**  Step3, update google_product_status
**  this step is after the transaction
********************************************************************/
    public function _sendBatchRequestToGoogleStep3($batchId) {
        $result = $this->getService("GoogleApiRequest")->getDao('GoogleApiRequest')->updateGoogleApiRequestFields($batchId);
        if ($result === false) {
            $this->_sendAlert("[Panther] Cannot clear data from google_api_request table, " . __METHOD__ . __LINE__, "error:" . $this->getService("GoogleApiRequest")->getDao('GoogleApiRequest')->db->error()["message"]);
        }
        return $result;
    }

    public function getGoogleShoppingContentReport($platformId = "") {
        if (!$platformId) {
            return true;
        } else {
            $accountId = $this->getShoppingApiAccountId($platformId);
            $googleConnect = $this->getService("GoogleConnect");
            $requestData["maxresults"] = self::NUMBER_OF_PRODUCTS_IN_BATCH;
            $productFeedResult = $googleConnect->listProducts($accountId, $requestData["maxresults"]);

            if ($productFeedResult["status"] == TRUE) {
                if ($productFeed = $productFeedResult["data"]) {
                    foreach ($productFeed as $product) {
                        $result = $this->processItemData($product, $platform_id);
                        $report .= $result;
                    }
                }

                $header = "SKU| title| product_url| image_url| target_country| brand| condition| color| availability| google_category| product_type| price| currency| MPN\n";
                $filename = 'googlebase_product_feed_' . $platform_id . '_' . date('Ymdhis') . '.csv';
                header("Content-type: text/csv");
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $header . $report;
            } else {
                $error_message = __LINE__ . " google_shopping_service.php, \r\n{$productFeedResult["error_message"]}";
                $this->mail_result($result, "content for google shopping error");
                // for front end
                $error_message = str_replace("\r\n", "<br><br>", $error_message);
                echo $error_message;
            }
        }
    }

    function processItemData($product, $platform_id = "")
    {
        $temp = array();

        $target_country = $product->targetCountry;

        $id = $product->offerId;
        $title = $product->title;
        $product_url = $product->link;
        $image_url = $product->imageLink;
        $brand = $product->brand;
        $condition = $product->condition;
        $color = $product->color;
        $vail = $product->availability;
        $google_cat = $product->googleProductCategory;
        $product_type = $product->productType;
        $price = $product->price->value;
        $price_unit = $product->price->currency;
        $mpn = $product->mpn;

        $temp[] = $id;
        $temp[] = $title;
        $temp[] = $product_url;
        $temp[] = $image_url;
        $temp[] = $target_country;
        $temp[] = $brand;
        $temp[] = $condition;
        $temp[] = $color;
        $temp[] = $vail;
        $temp[] = $google_cat;
        $temp[] = $product_type;
        $temp[] = $price;
        $temp[] = $price_unit;
        $temp[] = $mpn;

        $result = implode('|', $temp);

        return $result . "\n";
    }

    function GSC_error_handler($e, $sku, $platform_id)
    {
        $comment = "";
        foreach ($e->errors->getErrors() as $error) {
            $result[] = "SKU: " . $sku . "\r\n" .
                "Platform: " . $platform_id . "\r\n" .
                "Code: " . $error->getCode() . "\r\n" .
                "Domain: " . $error->getDomain() . "\r\n" .
                'Location: ' . $error->getLocation() . "\r\n" .
                "Internal Reason: " . $error->getInternalReason() . "\r\n";

            $comment = $error->getInternalReason();
        }


        $this->api_request_result_update($sku, $platform_id, 0, $comment);
        $this->mail_result($result, "ERROR: Google Shopping");
    }

    private function _sendAlert($subject, $message) {
        print $subject;
        print $message;
        $this->sendAlert($subject, $message, $this->_technicalEmail);
    }
}
