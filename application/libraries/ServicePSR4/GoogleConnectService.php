<?php
namespace ESG\Panther\Service;
include_once BASEPATH . "../googleAutoload.php";

class GoogleConnectService extends BaseService
{
/*
    * Credentials: https://console.developers.google.com/project -> API Project
    * If service account changed, need to add the new user in https://www.google.com/merchants/Home?a=9838040#usermanagement
*/
    const PRODUCT_SHOPPING_CHANNEL = "online";
//    private $clientId = '110619035985876631175'; //Client ID, no use
    private $_serviceAccountName = "account-2@ethereal-terra-114508.iam.gserviceaccount.com"; //Email Address
    private $_keyFileLocation = "/var/www/html/key/GoogleContentApi-a0754173747d.p12"; //key.p12

    public function __construct() {
		if (getenv("APPLICATION_ENV") == "dev") {
            $this->debug = 1;
            $this->_keyFileLocation = "D://website//atomv2//GoogleContentApi-a0754173747d.p12";
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
                    if ($this->debug) {
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
//        $client_id = $this->client_id;
        $_serviceAccountName = $this->_serviceAccountName;
        $_keyFileLocation = $this->_keyFileLocation;
        if (!strlen($_serviceAccountName)
            || !strlen($_keyFileLocation)) {
            return false;
        }

        $client = new \Google_Client();
        $client->setApplicationName($serviceName);
        google_api_php_client_autoload("Google_Service_ShoppingContent");
        $service = new \Google_Service_ShoppingContent($client);
        
        if (isset($_SESSION['service_token'])) {
            $client->setAccessToken($_SESSION['service_token']);
        }
        $key = file_get_contents($_keyFileLocation, true);
        $cred = new \Google_Auth_AssertionCredentials(
            $_serviceAccountName,
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
        if (($service = $this->_createService("Google_Shopping_Get_Product")) === false) {
            $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . " - Missing/Invalid Details.";
            return $ret;
        }

        try {
//	get the product
            $result = $service->products->get($accountId, $productId);
            if ($result) {
                if ($result->kind != "content#product") {
// should not come in here
                    $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . ". API kind is incorrect";
                    break;
                } else {
                    $ret["error_message"] = $this->_formWarning($result->getWarnings(), __LINE__ . " METHOD: " . __METHOD__ . ". Warnings:\r\n");

                    if (!$ret["error_message"]) {
                        $ret["status"] = TRUE;
                        $ret["gscDataObj"] = $result;
                        $ret["data"] = $this->_convertToStdProductObject($result);
                    }
                }
            }
        } catch(\Google_Service_Exception $e) {
            // if item not found, error message contains "(404) item not found"
            $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . ", ERROR: " . $e->getMessage();
        }
        return $ret;
    }

    public function deleteProduct($accountId, $productId) {        
        $ret = ["status" => FALSE, "error_message" => ""];
        if (($service = $this->_createService("Google_Shopping_Delete_Product")) === false) {
            $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . " - Missing/Invalid Details.";
            return $ret;
        }

        try {
            $result = $service->products->delete($accountId, $productId);
            if ($result) {
                $ret["error_message"] = $this->_formWarning($result->getWarnings(), __LINE__ . " METHOD: " . __METHOD__ . ". Something might have gone wrong with deleteproduct(). \r\n");
            }
        } catch(\Google_Service_Exception $e) {
            $ret["error_message"] = __LINE__ . ", METHOD: " . __METHOD__ . ", ERROR: " . $e->getMessage();
        }
        return $ret;
    }

    private function _createService($serviceName) {
        if (($setupResponse = $this->_setupClientService($serviceName)) === false) {
            return false;
        } else {
            list($client, $service) = [$setupResponse["client"], $setupResponse["service"]];
        }
        return $service;
    }

    private function _formWarning($warnings = null, $generalMessage = null) {
        $errorMessage = "";
        if ($warnings)
        {
            if ($generalMessage)
                $errorMessage .= $generalMessage;
            foreach ($warnings as $warning) {
                $errorMessage .= "[domain]{$warning->getDomain()}, [reason]{$warning->getReason()}, [message]{$warning->getMessage()}\r\n";
            }
        }
        return $errorMessage;
    }

    private function _formBatchEntries($productIdBatch) {
        $entries = [];
        if (isset($productIdBatch)) {
            $uniqueBatchId = date("his");
            foreach ($productIdBatch as $key => $productId)  {
                $entry = new \Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();
                $entry->setMethod('delete');
                $entry->setBatchId($uniqueBatchId);
                $entry->setProductId($productId->getGoogleRefId());
                $entry->setMerchantId($accountId);
                $entries[$key] = $entry;
                $key++;
            }
        }
        return $entries;
    }

    private function _sendBatchRequest($service, $entries) {
        $batchRequest = new \Google_Service_ShoppingContent_ProductsCustomBatchRequest();						
        $batchRequest->setEntries($entries);
        if ($this->debug) {
            $batchResponse = $service->products->custombatch($batchRequest, ["dryRun" => true]);
        } else {
            $batchResponse = $service->products->custombatch($batchRequest);
        }
        return $batchResponse;
    }

    public function deleteProductBatch($accountId, $productIdBatch) {
        $ret = ["status" => FALSE, "error_message" => ""];
        if (($service = $this->_createService("Google_Shopping_Delete_Product_Batch")) === false) {
            $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . " - Missing/Invalid Details.";
            return $ret;
        }
        try {
            $entries = $this->_formBatchEntries($productIdBatch);
            if($entries) {
                $batchResponse = $this->_sendBatchRequest($service, $entries);

                $numberOfErrorsInBatch = 0;
                $batcherror = $ret["batch_error"] = "";
                $successList = [];
                if ($responseEntries = $batchResponse->getEntries()) {
// as long as we get response, we treat as success, then compile a list of error SKUs below
                    $ret["status"] = TRUE;
                    foreach ($responseEntries as $key => $responseEntry) 
                    {
                        $batchId = $responseEntry->getBatchId();
                        $currentProductId = $entries[$key];
                        $responseErrors = $responseEntry->getErrors()->getErrors();
                        $responseErrorCode = (($responseEntry->getErrors()) ? $responseEntry->getErrors()->getCode() : "");
                        if(is_array($responseErrors)) {
                            $batcherror .= "[$currentProductId]=>";
                            foreach ($responseErrors as $k => $error) {
                                $batcherror .= "[errorcode]$responseErrorCode], [domain]{$error->getDomain()}, [reason]{$error->getReason()}, [message]{$error->getMessage()}. \r\n";
                            }
                            $numberOfErrorsInBatch++;
                        } else {
                            $successList[] = $currentProductId;
                        }
                        if ($numberOfErrorsInBatch) {
                            $ret["batch_error"] = __LINE__ . " METHOD: " . __METHOD__ . ". $numberOfErrorsInBatch error(s) in batch, google error reasons below:\r\n $batcherror";
                        }

                        $ret["data"] = $successList;
                    }
                } else {
                    $ret["error_message"] = __LINE__ . " File: " . __FILE__ . ". Something wrong, no response entries from google. ";
                }
            } else {
                // shouldn't have come in here; already processed at top of file
                $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . ". No product IDs batch, empty entries ";
            }
        } catch(\Google_Service_Exception $e) {
            $ret["error_message"] = __LINE__ . ", METHOD: " . __METHOD__ . ", ERROR: " . $e->getMessage();
        }
        return $ret;
    }

    public function insertProduct($accountId, \Google_Service_ShoppingContent_Product $productobj) {
        $ret = ["status" => FALSE, "error_message" => ""];
        if (($service = $this->_createService("Google_Shopping_Insert_Product")) === false) {
            $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . " - Missing/Invalid Details.";
            return $ret;
        }

        try {
            $result = $service->products->insert($accountId, $productobj);
            $ret["status"] = TRUE;
            $ret["error_message"] = $this->_formWarning($result->getWarnings(), __LINE__ . " METHOD: " . __METHOD__ . ". Warnings:\r\n");
        } catch(\Google_Service_Exception $e) {
            $ret["error_message"] = __LINE__ . " File: " . __METHOD__ . ". Caught exception: " . $e->getMessage();
        }

        return $ret;
    }

    public function testInsertProduct($accountId, $googleApiRequestObj) {
        $googleProductObj = $this->_converToGscProductObject($googleApiRequestObj);
        $result = $this->insertProduct($accountId, $googleProductObj);
        var_dump($result);
    }

    private function _formBatchRequest(&$googleApiRequestObjList = []) {
        $entries = [];
        $entryId = 0;
        $accountInfo = $this->getService("Google")->shoppingAcctInfo;
        foreach ($googleApiRequestObjList as $recordId => $googleRequest) {
            $googleProductObj = $this->_converToGscProductObject($googleRequest);
            $entry = new \Google_Service_ShoppingContent_ProductsCustomBatchRequestEntry();
            if ($googleRequest->getGoogleProductStatus() == "I") {
                $entry->setMethod("insert");
                $entry->setProduct($googleProductObj);
            } else {
                $entry->setMethod("delete");
                $entry->setProductId($googleRequest->getGoogleProductId());
                var_dump($googleRequest->getGoogleProductId());
            }
            $entry->setBatchId($recordId);
            $entry->setMerchantId($accountInfo[$googleRequest->getPlatformId()]["account_id"]);
            array_push($entries, $entry);
            $entryId++;
        }
//        var_dump($entries);exit;
        return $entries;
    }

    public function processingInsertProductBatch($batchId, &$googleApiRequestObjList = []) {
//        $batchError = "";
        $ret = ["status" => FALSE, "error_message" => ""];
        if ($entries = $this->_formBatchRequest($googleApiRequestObjList)) {
            if (($service = $this->_createService("Google_Shopping_Get_Product")) === false) {
                $ret["error_message"] = __LINE__ . " METHOD: " . __METHOD__ . " - Missing/Invalid Details.";
                return $ret;
            }
/*
            if($this->debug)
                $optParams["dryRun"] = true;
            else
*/
                $optParams = [];

            $batchRequest = new \Google_Service_ShoppingContent_ProductsCustomBatchRequest();
			$batchRequest->setEntries($entries);
            $batchResponse = $service->products->custombatch($batchRequest, $optParams);
            $entriesResponse = $batchResponse->getEntries();
            foreach ($entriesResponse as $entryResponse) { //Google_Service_ShoppingContent_ProductsCustomBatchResponseEntry
                $entryResult = "F";
                $errorMessage = "";
                $entryId = $entryResponse->getBatchId();
                if ($entryResponse->getErrors()) {
                    $errorMessage = $this->_handleBatchError($entryResponse);
                } else {
                    $entryResult = "S";
                }
                if ($entryResponse->getProduct()) {
                    if (($warning = $this->_handleWarning($entryResponse)) != "") {
                        if ($entryResult != "F")
                            $entryResult = "W";
                        $errorMessage .= $warning;
                    }
                }
                $googleApiRequestObjList[$entryId]->setResult($entryResult);
                $googleApiRequestObjList[$entryId]->setKeyMessage($errorMessage);
                $googleApiRequestObjList[$entryId]->setApiResponse($this->_getApiResponse($entryResponse));
                $ret["result"] = true;
            }
        }
        return $ret;
    }

    private function _getApiResponse($entryResponse) {
        if ($entryResponse->getProduct()) {
            $apiResponse = @http_build_query(get_object_vars($entryResponse)) . @http_build_query(get_object_vars($entryResponse->getProduct()));
        } else {
            $apiResponse = @http_build_query(get_object_vars($entryResponse));
        }
        return $apiResponse;
    }

    private function _handleWarning($entryResponse) {
        $errorMessage = "";
        $warnings = $entryResponse->getProduct()->getWarnings();
        if ($warnings) {
            $errorMessage = $this->_formWarning($warnings, __LINE__ . " METHOD: " . __METHOD__ . ". Warnings:");
        }
        return $errorMessage;
    }

    private function _handleBatchError($entryResponse) {
        $errorMessage = "";
        $responseErrors = $entryResponse->getErrors();
        $responseErrorCode = (($entryResponse->getErrors()) ? $entryResponse->getErrors()->getCode() : "");
        if ($responseErrors->getErrors()) {
            $errorMessage = $this->_formWarning($responseErrors->getErrors(), "ErrorCode:" . $responseErrorCode . ",");
        } else {
            $errorMessage = "ErrorCode:" . $responseErrorCode . "," . $responseErrors->getMessage();
        }
        return $errorMessage;
    }
/**
* Converts stdClass object into Google_Service_ShoppingContent_Product object format
* Mainly used to do insert products
*/
    private function _converToGscProductObject(\GoogleApiRequestVo $productobj) {
        $googleShoppingContentProduct = new \Google_Service_ShoppingContent_Product();
        $googleShoppingContentProduct->setContentLanguage($productobj->getContentLanguage());
        $googleShoppingContentProduct->setChannel(self::PRODUCT_SHOPPING_CHANNEL);
        $googleShoppingContentProduct->setOfferId($productobj->getGoogleProductId());
        $googleShoppingContentProduct->setTargetCountry($productobj->getTargetCountry());

        $googleShoppingContentProduct->setImageLink($productobj->getImageLink());
        $googleShoppingContentProduct->setTitle($productobj->getTitle());
        $googleShoppingContentProduct->setLink($productobj->getLink());
        $googleShoppingContentProduct->setBrand($productobj->getBrandName());

        $description = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/','', $productobj->getDescription());
        $googleShoppingContentProduct->setDescription($description);
        
        if($productobj->getCondition() != "" && $productobj->getCondition() != NULL)
            $condition = $productobj->getCondition();
        else
            $condition = "new";
        $googleShoppingContentProduct->setCondition($condition);
        if($productobj->getMpn() != "" && $productobj->getMpn() != NULL)
            $googleShoppingContentProduct->setMpn($productobj->getMpn());
        if($productobj->getGtin() != "" && $productobj->getGtin() != NULL)
            $googleShoppingContentProduct->setGtin($productobj->getGtin());
        if($productobj->getItemGroupId() != "" && $productobj->getItemGroupId() != NULL)
            $googleShoppingContentProduct->setItemGroupId($productobj->getItemGroupId());

        if ($productobj->getAvailability() == "Y")
            $availability = "in stock";
        else
            $availability = "out of stock";
        $googleShoppingContentProduct->setAvailability($availability);
        if($productobj->getColourId() != "" && $productobj->getColourId() != NULL)
            $googleShoppingContentProduct->setColor($productobj->getColourId());

        $googleShoppingContentProduct->setGoogleProductCategory($productobj->getGoogleProductCategory());
        $googleShoppingContentProduct->setProductType($productobj->getProductType());

//price
        $googleShoppingPrice = new \Google_Service_ShoppingContent_Price();
        $googleShoppingPrice->setCurrency($productobj->getCurrency());
        $googleShoppingPrice->setValue($productobj->getPrice());
        $googleShoppingContentProduct->setPrice($googleShoppingPrice);

        $googleShippingPrice = new \Google_Service_ShoppingContent_Price();
        $googleShippingPrice->setValue(0.00);
        $googleShippingPrice->setCurrency($productobj->getCurrency());

        $shipping = new \Google_Service_ShoppingContent_ProductShipping();
        $shipping->setPrice($googleShippingPrice);
        $shipping->setCountry($productobj->getTargetCountry());
        $shipping->setService("Standard");
        $googleShoppingContentProduct->setShipping(array($shipping));

        $customAttribute = [];
        $attribute=0;
        if ($productobj->getCustomAttributePromoId()) {
            $customAttribute[$attribute] = new \Google_Service_ShoppingContent_ProductCustomAttribute();
            $customAttribute[$attribute]->setName("promotion_id");
            $customAttribute[$attribute]->setType("text");
            $customAttribute[$attribute]->setValue($productobj->getCustomAttributePromoId());
            $googleShoppingContentProduct->setCustomAttributes($customAttribute);
            $attribute++;
        }

// setShippingWeight
        $googleShoppingWeight = new \Google_Service_ShoppingContent_ProductShippingWeight();
        $googleShoppingWeight->setUnit("kg");
        $googleShoppingWeight->setValue($productobj->getShippingWeightValue());
        $googleShoppingContentProduct->setShippingWeight($googleShoppingWeight);

        return $googleShoppingContentProduct;
    }

	private function utf_encode_array_values($array) {
		$return_array = array();
		if(is_array($array))
		{
			foreach ($array as $key => $value) 
			{
				if(is_array($value))
				{
					$return_array[$key] = $this->utf_encode_array_values($value);
				}
				else
				{
					if(mb_detect_encoding($value) != "UTF-8")
						$return_array[$key] = utf8_encode($value);
					else
						$return_array[$key] = $value;
				}
			}
		}

		return $return_array;
	}

    public function stdObjToString($input, $array_key = "")
    {
        $string_to_return = "";
        foreach ($input as $key => $value) {
            if (is_object($value)) {
                $string_to_return .= $this->stdObjToString($value, "    " . $array_key . ($key . "_"));
            } else if (is_array($value)) {
                foreach ($value as $second_key => $second_value) {
                    $string_to_return .= $this->stdObjToString($second_value, "    " . $array_key . ($key . "_" . $second_key . "_"));
                }
            } else {
                $string_to_return .= $array_key . $key . "=" . $value . "\n";
            }
        }
        return $string_to_return;
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
