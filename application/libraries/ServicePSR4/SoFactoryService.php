<?php
namespace ESG\Panther\Service;

//use PHPMailer;
//use EventEmailDto;
//use ESG\Panther\Service\ExchangeRateService;
//use ESG\Panther\Service\EventService;
//use ESG\Panther\Service\EntityService;
//use ESG\Panther\Service\DelayedOrderService;
//use ESG\Panther\Service\ReviewFianetService;
//use ESG\Panther\Service\PdfRenderingService;
//use ESG\Panther\Service\CurrencyService;
//use ESG\Panther\Service\TemplateService;
//use ESG\Panther\Service\SubjectDomainService;
//use ESG\Panther\Service\DataExchangeService;
//use ESG\Panther\Service\ComplementaryAccService;
use ESG\Panther\Dao\SoDao;
use ESG\Panther\Service\ClientService;
use ESG\Panther\Service\ProductService;
use ESG\Panther\Service\CreateClientInterface;

class SoFactoryService extends BaseService
{
    public $injectObj = null;
    public $clientService = null;

    public function __construct($injectObj = null)
    {
        parent::__construct();
        $this->injectObj = $injectObj;
        $this->clientService = new ClientService;
        $this->productService = new ProductService;
        $this->setDao(new SoDao());
    }

    public function createSaleOrder($clientInfo = [], $orderInfo = [])
    {
//create client
        $clientObj = $this->_createClient($clientInfo);
        if ($clientObj)
        {
            $this->_createSo($clientObj, $orderInfo);
        }
        else
        {

        }
    }

    private function _createClient($clientInfo = [])
    {
        $clientObj = $this->clientService->createClient($clientInfo, $this->injectObj, true);
        return $clientObj;
    }

    private function _createSo($clientObj, $orderInfo, $parentSoNo = null)
    {
        $soObj = $this->SoService->getDao()->get();
        $soObj->setClientId($clientObj->getId());
        $soObj->setPlatformId($orderInfo->getPlatformId());
        $soObj->setAmount($orderInfo->getGrandTotal());
        $soObj->setCurrencyId($orderInfo->getCurrency());
        $soObj->setBizType($orderInfo->getBizType());
        $soObj->setDeliveryCharge($orderInfo->getDeliveryCharge());
        $soObj->setDeliveryType($orderInfo->getDeliveryType());
        if ($orderInfo->getBizType() == "ONLINE")
            $soObj->setLangId(LANG_ID);
        else {
//other are all through admincentre
            $soObj->setLangId("en");
        }
        $billName = $clientObj->getForename();
        if ($billName) {
            $billName .= " " . $clientObj->getSurname();
        } else {
            $billName = $clientObj->getSurname();
        }
        $soObj->setBillName($billName);
        $soObj->setBillCompany($clientObj->getCompanyname());
        $billAddress = $clientObj->getAddress1();
        if ($billAddress) {
            $billAddress .= "||" . ($clientObj->getAddress2()?$clientObj->getAddress2():"");
        } else {
            $billAddress = ($clientObj->getAddress2()?$clientObj->getAddress2():"");
        }
        if ($billAddress) {
            $billAddress .= "||" . ($clientObj->getAddress3()?$clientObj->getAddress3():"");
        } else {
            $billAddress = ($clientObj->getAddress3()?$clientObj->getAddress3():"");
        }
        $soObj->setBillAddress($billAddress);
        $soObj->setBillPostcode($clientObj->getPostcode());
        $soObj->setBillCity($clientObj->getCity());
        $soObj->setBillState($clientObj->getState());
        $soObj->setBillCountryId($clientObj->getCountryId());

        $soObj->setDeliveryName($clientObj->getDelName());
        $soObj->setDeliveryCompany($clientObj->getDelCompany());
        $deliveryAddress = $clientObj->getDelAddress1();
        if ($deliveryAddress) {
            $deliveryAddress .= "||" . ($clientObj->getDelAddress2()?$clientObj->getDelAddress2():"");
        } else {
            $deliveryAddress = ($clientObj->getDelAddress2()?$clientObj->getDelAddress2():"");
        }
        if ($deliveryAddress) {
            $deliveryAddress .= "||" . ($clientObj->getDelAddress3()?$clientObj->getDelAddress3():"");
        } else {
            $deliveryAddress = ($clientObj->getDelAddress3()?$clientObj->getDelAddress3():"");
        }
        $soObj->setDeliveryAddress($deliveryAddress);
        $soObj->setDeliveryPostcode($clientObj->getDelPostcode());
        $soObj->setDeliveryCity($clientObj->getDelCity());
        $soObj->setDeliveryState($clientObj->getDelState());
        
        if (isset($parentSoNo))
            $soObj->setDeliveryState($parentSoNo);
        $soObj->setStatus(0);
        $soObj->setHoldStatus(0);
        $soObj->setRefundStatus(0);
        $soObj->setCsCustomerQuery(0);
        if ($orderInfo->getOrderCreateDate())
            $soObj->setOrderCreateDate($orderInfo->getOrderCreateDate());
        else
            $soObj->setOrderCreateDate(date("Y-m-d H:i:s"));

        return $soObj;
    }
/**************************************************
**  pass CartDto into
**  you can form this DTO and pass the
***************************************************/
    public function getOrderInfoDetail(CartDto $cart)
    {
//vat, vat_percent, rate, ref_1, weight, expect_delivery_date, cost
//        $this->getCart
    }

/********************************************************
**  function to prevent cart was amended by user and in case the price is updating by BD before checkout
**  rebuildCart to find all the required value for an order
*********************************************************/
    public function rebuildCartByCartDto($orderInfo)
    {
        $newCart = new \CartDto();
        if ($orderInfo->getBizType() == "ONLINE")
            $langId = LANG_ID;
        else
            $langId = "en";
        $options = ["supplierCost" => true];
        foreach($orderInfo->items as $sku => $item)
        {
            $newProductInfo = $this->getCartItemInfo($sku, $langId, $orderInfo->getPlatformId(), $options);
            $newProductInfo->setQty($item->getQty());
            $newCart->items[$sku] = $newProductInfo;
        }
    }

    public function getCartItemInfo($sku, $lang, $platformId, $options = []) {
        $where = ["pr.platform_id" => $platformId
                , "pc.lang_id" => $lang
                , "p.sku" => $sku
                , "p.status" => 2
                , "pr.listing_status" => "L"
                , "p.website_status in ('I', 'P')" => null];
        $options["orderby"] = "pi.priority";
        $options["limit"] = 1;

        $productInfo = $this->productService->getDao()->getCartData($where, $options);
//        print $this->productService->getDao()->db->last_query();
        if ($productInfo) {
            return $productInfo;
        }
        else
        {
//out of stock, or
            $subject = "[Panther] Adding product which is not valid to the cart " . $sku . ":" . $platformId;
            $message = $this->productService->getDao()->db->last_query();
            mail($this->support_email, $subject, $message, "From: website@" . SITE_DOMAIN . "\r\n");
        }
        return false;
    }
}
