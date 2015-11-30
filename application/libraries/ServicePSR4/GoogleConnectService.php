<?php
namespace ESG\Panther\Service;
include_once BASEPATH . "../googleAutoload.php";

class GoogleConnectService extends BaseService
{
/*
    * Credentials: https://console.developers.google.com/project -> API Project
    * If service account changed, need to add the new user in https://www.google.com/merchants/Home?a=9838040#usermanagement
*/
    private $client_id = '549591735969-0apoenv5ia6a5t7hp4hqmia2uacdi212.apps.googleusercontent.com'; //Client ID
    private $service_account_name = '549591735969-0apoenv5ia6a5t7hp4hqmia2uacdi212@developer.gserviceaccount.com'; //Email Address
    private $key_file_location = 'D://website//atomv2//GoogleAPIProjPK.p12'; //key.p12

    public function __construct() {
		if (getenv("APPLICATION_ENV") == "dev") {
            $this->debug = 1;
		}
    }

    public function listProducts($accountId, $maxresults=250, $maxpages="") {
        $ret = ["status" => FALSE, "error_message" => ""];
        if (($setupResponse = $this->_setupClientService("Google_Shopping_List_Products")) === false) {
            $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . " - Missing/Invalid Details.";
            return $ret;
        } else {
            list($client, $service) = [$setupResponse["client"], $setupResponse["service"]];
        }

        try
        {
            $productlist = [];
            $nextPageToken = "";
            $i = 1;
            do {
                // max result is 250. Cannot pass in pageToken if it's empty
                if($nextPageToken == "")
                    $optParams = ["maxResults" => $maxresults];
                else
                    $optParams = ["pageToken" => $nextPageToken, "maxResults" => $maxresults];

                $result = $service->products->listProducts($accountId, $optParams);
                if($result) {
                    if($result->kind != "content#productsListResponse") {
                        // should not come in here
                        $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ". API kind is incorrect";
                        $productlist = [];
                        break;
                    } else {
                        $nextPageToken = $result->nextPageToken;
//                        var_dump($result->getResources()); exit;
                        $productlist = array_merge($productlist, $result->getResources());
                    }
                }

                if($maxpages) {
                    if($i == $maxpages)
                        break;
                } else {
                    if (getenv("APPLICATION_ENV") == "dev") {
                        if($i == 2)
                            break;
                    }
                }
                $i++;
            } while ($nextPageToken);

            if (empty($ret["error_message"])) {
                if($productlist) {
                    $ret["status"] = TRUE;
                    $ret["data"] = $productlist;
                } else {
                    $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ". Empty Product List";
                }		
            }
        } catch(\Google_Service_Exception $e) {
            $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ". Caught exception: " . $e->getMessage();
        }

        return $ret;
    }

    private function _setupClientService($serviceName) {
        $client_id = $this->client_id;
        $service_account_name = $this->service_account_name;
        $key_file_location = $this->key_file_location;
        if ($client_id == '<YOUR_CLIENT_ID>'
            || !strlen($service_account_name)
            || !strlen($key_file_location)) {
            return false;
        }

        $client = new \Google_Client();
        $client->setApplicationName($serviceName);
        google_api_php_client_autoload("Google_Service_ShoppingContent");
        $service = new \Google_Service_ShoppingContent($client);
        
        if (isset($_SESSION['service_token'])) {
            $client->setAccessToken($_SESSION['service_token']);
        }
        $key = file_get_contents($key_file_location, true);
        $cred = new \Google_Auth_AssertionCredentials(
            $service_account_name,
            array('https://www.googleapis.com/auth/content'),
            $key
        );

        $client->setAssertionCredentials($cred);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }
        $_SESSION['service_token'] = $client->getAccessToken();
        return ["client" => $client, "service" => $service];
    }

    public function getProduct($accountId, $productId) {        
// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.
        $ret = ["status" => FALSE, "error_message" => ""];

        if (($setupResponse = $this->_setupClientService("Google_Shopping_Get_Product")) === false) {
            $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . " - Missing/Invalid Details.";
            return $ret;
        } else {
            list($client, $service) = [$setupResponse["client"], $setupResponse["service"]];
        }

        try {
//	get the product
            $result = $service->products->get($accountId, $productId);
            if ($result) {
                if ($result->kind != "content#product") {
// should not come in here
                    $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ". API kind is incorrect";
                    break;
                } else {
                    $warnings = $result->getWarnings();
                    if ($warnings) {
                        $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ". Warnings:\r\n";
                        foreach ($warnings as $warning) {
                            $ret["error_message"] .= "[domain]{$warning->getDomain()}, [reason]{$warning->getReason()}, [message]{$warning->getMessage()}\r\n";
                        }
                    } else {
                        $ret["status"] = TRUE;
                        $ret["gscDataObj"] = $result;
                        $ret["data"] = $this->_convertToStdProductObject($result);
                    }
                }
            }
        } catch(\Google_Service_Exception $e) {
            // if item not found, error message contains "(404) item not found"
            $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ", ERROR: " . $e->getMessage();
        }
        return $ret;
    }

    public function deleteProduct($accountid, $productid) {
        
        // Make sure your product ID is of the form channel:languageCode:countryCode:offerId.

        # for testing
        // $accountid = "8113126";
        // $productid = "online:en:AU:AU-18066-AA-UU"; 	

        $ret["status"] = FALSE;
        $ret["error_message"] = "";
        $ret["debug"] = $this->debug;

        $client_id = $this->client_id;
        $service_account_name = $this->service_account_name;
        $key_file_location = $this->key_file_location;

        if ($client_id == '<YOUR_CLIENT_ID>'
            || !strlen($service_account_name)
            || !strlen($key_file_location)) 
        {
            $ret["error_message"] = __LINE__ . " File: " . __FILE__ . " - Missing/Invalid Details.";
            return $ret;
        }

        $client = new \Google_Client();
        $client->setApplicationName("Google_Shopping_Delete_Product");
        $service = new \Google_Service_ShoppingContent($client);

        // /************************************************
        //   If we have an access token, we can carry on.
        //   Otherwise, we'll get one with the help of an
        //   assertion credential. In other examples the list
        //   of scopes was managed by the Client, but here
        //   we have to list them manually. We also supply
        //   the service account
        //  ************************************************/
        
        if (isset($_SESSION['service_token'])) 
        {
          $client->setAccessToken($_SESSION['service_token']);
        }

        try
        {
            $productlist = array();
            $key = file_get_contents($key_file_location, true);
            $cred = new \Google_Auth_AssertionCredentials(
                $service_account_name,
                array('https://www.googleapis.com/auth/content'),
                $key
            );

            $client->setAssertionCredentials($cred);
            if ($client->getAuth()->isAccessTokenExpired()) {
              $client->getAuth()->refreshTokenWithAssertion($cred);
            }
            $_SESSION['service_token'] = $client->getAccessToken();

            //	delete the product
            // The response for a successful delete is empty
            $result = $service->products->delete($accountid, $productid);
            if($result)
            {
                $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Something might have gone wrong with deleteproduct(). \r\n";
                $warnings = $result->modelData["warnings"];
                if($warnings)
                {
                    $ret["error_message"] .= "Warnings: \r\n";
                    foreach ($warnings as $warning) 
                    {
                        $ret["error_message"] .= "[domain]{$warning["domain"]}, [reason]{$warning["reason"]}, [message]{$warning["message"]}\r\n";
                    }
                }					
            }
            else
            {
                $ret["status"] = TRUE;
            }
        }
        catch(Exception $e)
        {
            // if item not found, error message contains "(404) item not found"
            $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Caught exception: " . $e->getMessage();
        }

        return $ret;
    }

    public function deleteProductBatch($accountid, $productidbatch) {
// 			// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.

        # for testing
        // $accountid = "8113126";
        // $productid = "online:en:AU:AU-18066-AA-UU"; 	

        $ret["status"] = FALSE;
        $ret["error_message"] = "";
        $ret["debug"] = $this->debug;

        $client_id = $this->client_id;
        $service_account_name = $this->service_account_name;
        $key_file_location = $this->key_file_location;

        if ($client_id == '<YOUR_CLIENT_ID>'
            || !strlen($service_account_name)
            || !strlen($key_file_location)) 
        {
            $ret["error_message"] = __LINE__ . " File: " . __FILE__ . " - Missing/Invalid Details.";
            return $ret;
        }

        $client = new \Google_Client();
        $client->setApplicationName("Google_Shopping_Delete_Product_Batch");
        $service = new \Google_Service_ShoppingContent($client);

        // /************************************************
        //   If we have an access token, we can carry on.
        //   Otherwise, we'll get one with the help of an
        //   assertion credential. In other examples the list
        //   of scopes was managed by the Client, but here
        //   we have to list them manually. We also supply
        //   the service account
        //  ************************************************/
        
        if (isset($_SESSION['service_token'])) 
        {
          $client->setAccessToken($_SESSION['service_token']);
        }

        try
        {
            $key = file_get_contents($key_file_location, true);
            $cred = new \Google_Auth_AssertionCredentials(
                $service_account_name,
                array('https://www.googleapis.com/auth/content'),
                $key
            );

            $client->setAssertionCredentials($cred);
            if ($client->getAuth()->isAccessTokenExpired()) {
              $client->getAuth()->refreshTokenWithAssertion($cred);
            }
            $_SESSION['service_token'] = $client->getAccessToken();

            $entries = array();
            if(isset($productidbatch))
            {
                foreach ($productidbatch as $key => $productid) 
                {
                    $entry = new \Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();
                        
                    $entry->setMethod('delete');
                    $entry->setBatchId($key);
                    $entry->setProductId($productid);
                    $entry->setMerchantId($accountid);
                    $entries[$key] = $entry;

                    $key++;
                }

                if($entries)
                {
                    $batch_request = new \Google_Service_ShoppingContent_ProductsCustomBatchRequest();						
                    $batch_request->setEntries($entries);

                    if($this->debug)
                    {
                        $optParams["dryRun"] = true;
                        $batch_responses = $service->products->custombatch($batch_request, $optParams);
                    }
                    else
                    {
                        $batch_responses = $service->products->custombatch($batch_request);
                    }

                    $errors = 0;
                    $batcherror = $ret["batch_error"] = "";
                    $successlist = array();

                    $response_entries = $batch_responses->modelData["entries"];
                    if($response_entries)
                    {
                        // as long as we get response, we treat as success, then compile a list of error SKUs below
                        $ret["status"] = TRUE;
                        foreach ($response_entries as $key => $response_entry) 
                        {
                            $batchid = $response_entry["batchId"];
                            $current_productid = $productidbatch[$batchid];

                            $response_errors = $response_entry["errors"]["errors"];
                            $response_error_code = $response_entry["errors"]["code"];
                            if(is_array($response_errors))
                            {
                                $batcherror .= "[$current_productid]=>";
                                foreach ($response_errors as $k => $arr) 
                                {
                                    $batcherror .= "[errorcode]$response_error_code], [domain]{$arr["domain"]}, [reason]{$arr["reason"]}, [message]{$arr["message"]}. \r\n";
                                }
                                $errors++;
                            }
                            else
                            {
                                $successlist[] = $current_productid;
                            }
                            if($errors)
                            {
                                $ret["batch_error"] = __LINE__ . " File: " . __FILE__ . ". $errors error(s) in batch, google error reasons below:\r\n $batcherror";
                            }

                            $ret["data"] = $successlist;
                        }
                    }
                    else
                    {
                        $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Something wrong, no response entries from google. ";
                    }
                }
                else
                {
                    // error forming $entries will not throw any Exceptions
                    $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". No entries; maybe error forming of product batch";
                }
            }
            else
            {
                // shouldn't have come in here; already processed at top of file
                $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". No product IDs batch? ";
            }

        }
        catch(Exception $e)
        {
            // if item not found, error message contains "(404) item not found"
            $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Caught exception: " . $e->getMessage();
        }

        return $ret;
    }

    public function insertProduct($accountId, \Google_Service_ShoppingContent_Product $productobj) {
        $ret = ["status" => FALSE, "error_message" => ""];
        if (($setupResponse = $this->_setupClientService("Google_Shopping_Insert_Product")) === false) {
            $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . " - Missing/Invalid Details.";
            return $ret;
        } else {
            list($client, $service) = [$setupResponse["client"], $setupResponse["service"]];
        }

        try {				
            $result = $service->products->insert($accountId, $productobj);
            $ret["status"] = TRUE;
            $warnings = $result->getWarnings();
            if($warnings) {
                $ret["warning"] = __LINE__ . " File: " . __METHOD__ . ". Warnings:\r\n";
                foreach ($warnings as $warning) 
                {
                    $ret["error_message"] .= "[domain]{$warning->getDomain()}, [reason]{$warning->getReason()}, [message]{$warning->getMessage()}\r\n";
                }
            }
        } catch(\Google_Service_Exception $e) {
            $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ". Caught exception: " . $e->getMessage();
        }

        return $ret;
    }

    public function insertProductBatch($accountid, $productbatch) {
// 			// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.

        # for testing
        // $accountid = "8113126";
        // $productid = "online:en:AU:AU-18066-AA-UU"; 	

        $ret["status"] = FALSE;
        $ret["error_message"] = "";
        $ret["debug"] = $this->debug;

        $client_id = $this->client_id;
        $service_account_name = $this->service_account_name;
        $key_file_location = $this->key_file_location;

        if ($client_id == '<YOUR_CLIENT_ID>'
            || !strlen($service_account_name)
            || !strlen($key_file_location)) 
        {
            $ret["error_message"] = __LINE__ . " File: " . __FILE__ . " - Missing/Invalid Details.";
            return $ret;
        }

        $client = new \Google_Client();
        $client->setApplicationName("Google_Shopping_Delete_Product_Batch");
        $service = new \Google_Service_ShoppingContent($client);

        // /************************************************
        //   If we have an access token, we can carry on.
        //   Otherwise, we'll get one with the help of an
        //   assertion credential. In other examples the list
        //   of scopes was managed by the Client, but here
        //   we have to list them manually. We also supply
        //   the service account
        //  ************************************************/
        
        if (isset($_SESSION['service_token'])) 
        {
          $client->setAccessToken($_SESSION['service_token']);
        }

        try
        {
            $key = file_get_contents($key_file_location, true);
            $cred = new \Google_Auth_AssertionCredentials(
                $service_account_name,
                array('https://www.googleapis.com/auth/content'),
                $key
            );

            $client->setAssertionCredentials($cred);
            if ($client->getAuth()->isAccessTokenExpired()) {
              $client->getAuth()->refreshTokenWithAssertion($cred);
            }
            $_SESSION['service_token'] = $client->getAccessToken();

            $entries = $productidlist = $productnamelist = array();
            $batchkey = 0;
            if(isset($productbatch))
            {
                foreach ($productbatch as $key => $productobj) 
                {
                    $productobj = (object)$productobj;
                    // compile a list of offerIds for checking later
                    $productidlist[$batchkey] = $productobj->offerId;
                    $productnamelist[$batchkey] = $productobj->title;
                    $GSC_product = $this->_converToGscProductObject($productobj);

                    if($GSC_product)
                    {
                        $entry = new \Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();
                            
                        $entry->setMethod('insert');
                        $entry->setBatchId($batchkey);
                        $entry->setProduct($GSC_product);
                        $entry->setMerchantId($accountid);
                        $entries[$batchkey] = $entry;

                    }
                    else
                    {
                        // if we come here, most probably whole list has wrong input object class
                        $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Unable to form GSC product object\r\n";
                        $entries = array();
                        break;
                    }

                    $batchkey++;
                }

                if($entries)
                {
                    $batch_request = new \Google_Service_ShoppingContent_ProductsCustomBatchRequest();						
                    $batch_request->setEntries($entries);

                    if($this->debug)
                    {
                        $optParams["dryRun"] = true;
                        $batch_responses = $service->products->custombatch($batch_request, $optParams);
                    }
                    else
                    {
                        $batch_responses = $service->products->custombatch($batch_request);
                    }

                    $errors = 0;
                    $batcherror = $ret["batch_error"] = array();
                    $successlist = array();
                    $response_entries = $batch_responses->modelData["entries"];
                    if($response_entries)
                    {
                        // as long as we get response, we treat as success, then compile a list of error SKUs below
                        $ret["status"] = TRUE;
                        foreach ($response_entries as $key => $response_entry) 
                        {
                            $batchid = $response_entry["batchId"];
                            $current_productid = $productidlist[$batchid];
                            $current_productname = $productnamelist[$batchid];

                            $response_errors = $response_entry["errors"]["errors"];
                            $response_error_code = $response_entry["errors"]["code"];
                            if($response_errors)
                            {
                                $batcherror[$current_productid] .= "[Name:$current_productname]\r\n";
                                foreach ($response_errors as $k => $arr) 
                                {
                                    $batcherror[$current_productid] .= "[errorcode]$response_error_code\r\n[domain]{$arr["domain"]}\r\n[reason]{$arr["reason"]}\r\n[message]{$arr["message"]}. \r\n";
                                }
                                $errors++;
                            }
                            else
                            {
                                $successlist[] = $current_productid;
                            }

                            if($errors)
                            {
                                $ret["batch_error"] = $batcherror;
                            }

                            $ret["data"] = $successlist;
                        }
                    }
                    else
                    {
                        $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Something wrong, no response entries from google. ";
                    }
                }
                else
                {
                    // error forming $entries will not throw any Exceptions
                    $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". No entries; maybe error forming of product batch";
                }
            }
            else
            {
                // shouldn't have come in here; already processed at top of file
                $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". No product batch? ";
            }

        }
        catch(Exception $e)
        {
            // if item not found, error message contains "(404) item not found"
            $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Caught exception: " . $e->getMessage();
        }

        return $ret;
    }

/**
* Converts stdClass object into Google_Service_ShoppingContent_Product object format
* Mainly used to do insert products
*/
    private function _converToGscProductObject(stdClass $productobj) {
        $GSC_product = array();
        $object_array = (array)$productobj;
        if(!$object_array)
        {
            return $GSC_product;
        }

        if(get_class($productobj) != "stdClass")
        {
            // invalid object class
            return $GSC_product;
        }

        $GSC_product = new \Google_Service_ShoppingContent_Product();

        $GSC_product->setContentLanguage($productobj->contentLanguage);
        $GSC_product->setChannel($productobj->channel); # e.g. online

        if($this->debug)
        {
            // // pingtest
            // if($productobj->offerId == "AU-12859-AA-AL")
            // 	$GSC_product->setOfferId("");
            // else
            // 	$GSC_product->setOfferId($productobj->offerId);
        }

        $GSC_product->setOfferId($productobj->offerId);

        $GSC_product->setTargetCountry($productobj->targetCountry);
        
        $imageLink = $productobj->imageLink;
        $GSC_product->setImageLink($imageLink);
        
        $title = $productobj->title;
        $GSC_product->setTitle($title);

        $description = $productobj->description;
        $GSC_product->setDescription($description);

        $GSC_product->setLink($productobj->link);

        $brand = $productobj->brand;
        $GSC_product->setBrand($brand);

        if($productobj->condition != "" && $productobj->condition != NULL)
            $GSC_product->setCondition($productobj->condition);
        else
            $GSC_product->setCondition("new");

        if($productobj->color != "" && $productobj->color != NULL)
            $GSC_product->setColor($productobj->color);

        $GSC_product->setAvailability($productobj->availability);

        $googleProductCategory = $productobj->googleProductCategory;
        $GSC_product->setGoogleProductCategory($googleProductCategory);
        $GSC_product->setProductType($productobj->productType);

        if($productobj->mpn != "" && $productobj->mpn != NULL)
            $GSC_product->setMpn($productobj->mpn);
        if($productobj->gtin != "" && $productobj->gtin != NULL)
            $GSC_product->setGtin($productobj->gtin);
        if($productobj->itemGroupId != "" && $productobj->itemGroupId != NULL)
            $GSC_product->setItemGroupId($productobj->itemGroupId);

        // price
        $GSC_price = new \Google_Service_ShoppingContent_Price();
        $GSC_price->setCurrency($productobj->price["currency"]);
        $GSC_price->setValue($productobj->price["value"]);
        $GSC_product->setPrice($GSC_price);

        $shipping_price = new \Google_Service_ShoppingContent_Price();
        if($productobj->shipping["price"])
        {
            $shipping_price->setValue($productobj->shipping["price"]);
            $shipping_price->setCurrency($productobj->shipping["currency"]);
        }

        $shipping = new \Google_Service_ShoppingContent_ProductShipping();
        $shipping->setPrice($shipping_price);
        $shipping->setCountry($productobj->shipping["country"]);
        $shipping->setService($productobj->shipping["service"]);
        $GSC_product->setShipping(array($shipping));

        if($productobj->custom_attribute)
        {
            $ca_cnt = 0;
            foreach ($productobj->custom_attribute as $key => $customattribute) 
            {
                $custom_attribute[$ca_cnt] = new \Google_Service_ShoppingContent_ProductCustomAttribute();
                $custom_attribute[$ca_cnt]->setName($customattribute["name"]);
                $custom_attribute[$ca_cnt]->setType($customattribute["type"]);
                $custom_attribute[$ca_cnt]->setValue($customattribute["value"]);

                $ca_cnt++;
            }
            $GSC_product->setCustomAttributes($custom_attribute);
        }

        // setShippingWeight
        $GSC_shipping_weight = new \Google_Service_ShoppingContent_ProductShippingWeight();
        $GSC_shipping_weight->setUnit($productobj->shippingWeight["unit"]);
        $GSC_shipping_weight->setValue($productobj->shippingWeight["value"]);
        $GSC_product->setShippingWeight($GSC_shipping_weight);


        return $GSC_product;
    }

/**
* sieve out GSC result content and compile only product-related info into std object and utf_encode values 
* Make product ready for json_encode to return
*/
    private function _convertToStdProductObject(\Google_Service_ShoppingContent_Product $object)
    {
        $product = array();
        $object_array = (array)$object;

        if(!$object_array)
        {
            return $product;
        }
        
        if(get_class($object) != "Google_Service_ShoppingContent_Product")
        {
            // invalid object class
            return $product;
        }

        $product["additionalImageLinks"] = $object->additionalImageLinks;
        $product["adult"] = $object->adult;
        $product["adwordsGrouping"] = $object->adwordsGrouping;
        $product["adwordsLabels"] = $object->adwordsLabels;
        $product["adwordsRedirect"] = $object->adwordsRedirect;
        $product["ageGroup"] = $object->ageGroup;
        $product["availability"] = $object->availability;
        $product["availabilityDate"] = $object->availabilityDate;
        $product["brand"] = $object->brand;
        $product["channel"] = $object->channel;
        $product["color"] = $object->color;
        $product["condition"] = $object->condition;
        $product["contentLanguage"] = $object->contentLanguage;
        $product["description"] = $object->description;
        $product["displayAdsId"] = $object->displayAdsId;
        $product["displayAdsLink"] = $object->displayAdsLink;
        $product["displayAdsSimilarIds"] = $object->displayAdsSimilarIds;
        $product["displayAdsTitle"] = $object->displayAdsTitle;
        $product["displayAdsValue"] = $object->displayAdsValue;
        $product["energyEfficiencyClass"] = $object->energyEfficiencyClass;
        $product["expirationDate"] = $object->expirationDate;
        $product["gender"] = $object->gender;
        $product["googleProductCategory"] = $object->googleProductCategory;
        $product["gtin"] = $object->gtin;
        $product["id"] = $object->id;
        $product["identifierExists"] = $object->identifierExists;
        $product["imageLink"] = $object->imageLink;
        $product["isBundle"] = $object->isBundle;
        $product["itemGroupId"] = $object->itemGroupId;
        $product["kind"] = $object->kind;
        $product["link"] = $object->link;
        $product["material"] = $object->material;
        $product["mobileLink"] = $object->mobileLink;
        $product["mpn"] = $object->mpn;
        $product["multipack"] = $object->multipack;
        $product["offerId"] = $object->offerId;
        $product["onlineOnly"] = $object->onlineOnly;
        $product["pattern"] = $object->pattern;
        $product["productType"] = $object->productType;
        $product["shippingLabel"] = $object->shippingLabel;
        $product["sizeSystem"] = $object->sizeSystem;
        $product["sizeType"] = $object->sizeType;
        $product["sizes"] = $object->sizes;
        $product["targetCountry"] = $object->targetCountry;
        $product["title"] = $object->title;
        $product["validatedDestinations"] = $object->validatedDestinations;
        $priceObj = (array) $object->getPrice();
        $product["price"]["value"] = $priceObj["value"];
        $product["price"]["currency"] = $priceObj["currency"];
/*
        $product["shipping"]["price"]["value"] = $modelData["shipping"][0]["price"]["value"];
        $product["shipping"]["price"]["currency"] = $modelData["shipping"][0]["price"]["currency"];
        $product["shipping"]["country"] = $modelData["shipping"][0]["country"];
        $product["shipping"]["service"] = $modelData["shipping"][0]["service"];

        $product["shipping_weight"]["value"] = $modelData["shippingWeight"]["value"];
        $product["shipping_weight"]["unit"] = $modelData["shippingWeight"]["unit"];
*/
//        $product = $this->_utfEncodeArrayValues($product);
        return (object)$product;
    }

    private function _utfEncodeArrayValues($array)
    {
        $return_array = array();
        if(is_array($array))
        {
            foreach ($array as $key => $value) 
            {
                if(is_array($value))
                {
                    $return_array[$key] = $this->_utfEncodeArrayValues($value);
                }
                else
                {
                    $return_array[$key] = utf8_encode($value);
                }
            }
        }

        return $return_array;
    }
}
