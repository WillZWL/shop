<?php
namespace ESG\Panther\Service;

class ManualOrderCreationService extends OrderCreationService
implements CreateSoInterface, CreateSoEventInterface
{
    private $_checkoutInfoDto = null;

    public function __construct($formValue) {
        parent::__construct($formValue);
    }

    public function getBizType() {
        return "MANUAL";
    }

    public function selfCreateClientObj() {
        return false;
/*
        $email = $this->checkoutFormData["client"]["email"];
        return $this->getService("Client")->getDao("Client")->get(["email" => $email]);
*/
    }

    public function getCheckoutData() {
        if (!$this->_checkoutInfoDto) {
            $this->_checkoutInfoDto = new \CheckoutInfoDto;
            $this->_checkoutInfoDto->setOrderReason($this->checkoutFormData["so_extend"]["order_reason"]);
            $this->_checkoutInfoDto->setOrderNotes($this->checkoutFormData["so_extend"]["notes"]);
            $this->_checkoutInfoDto->setLangId("en");
            $this->_checkoutInfoDto->setPaymentGatewayId($this->checkoutFormData["payment_gateway"]);
            $this->_checkoutInfoDto->setPayDate($this->checkoutFormData["payment_date"]);
            $this->_checkoutInfoDto->setEmail($this->checkoutFormData["client"]["email"]);
            $this->_checkoutInfoDto->setBillPassword($this->checkoutFormData["client"]["password"]);
            $this->_checkoutInfoDto->setBillCountry($this->checkoutFormData["client"]["country_id"]);
            $this->_checkoutInfoDto->setBillCompany($this->checkoutFormData["client"]["companyname"]);
            $this->_checkoutInfoDto->setTitle($this->checkoutFormData["client"]["title"]);
            $this->_checkoutInfoDto->setBillFirstName($this->checkoutFormData["client"]["forename"]);
            $this->_checkoutInfoDto->setBillLastName($this->checkoutFormData["client"]["surname"]);
            $this->_checkoutInfoDto->setBillAddress1($this->checkoutFormData["client"]["address_1"]);
            $this->_checkoutInfoDto->setBillAddress2($this->checkoutFormData["client"]["address_2"]);
            $this->_checkoutInfoDto->setBillCity($this->checkoutFormData["client"]["city"]);
            $this->_checkoutInfoDto->setBillPostal($this->checkoutFormData["client"]["postcode"]);
            $this->_checkoutInfoDto->setBillState($this->checkoutFormData["client"]["state"]);
            $this->_checkoutInfoDto->setBillTelCountryCode($this->checkoutFormData["client"]["tel_1"]);
            $this->_checkoutInfoDto->setBillTelAreaCode($this->checkoutFormData["client"]["tel_2"]);
            $this->_checkoutInfoDto->setBillTelNumber($this->checkoutFormData["client"]["tel_3"]);

            $this->_checkoutInfoDto->setMobile($this->checkoutFormData["client"]["mtel_1"] . $this->checkoutFormData["client"]["mtel_2"] . $this->checkoutFormData["client"]["mtel_3"]);

            $this->_checkoutInfoDto->setShipCountry($this->checkoutFormData["client"]["country_id"]);
            $this->_checkoutInfoDto->setShipCompany($this->checkoutFormData["client"]["companyname"]);
            $this->_checkoutInfoDto->setShipFirstName($this->checkoutFormData["client"]["forename"]);
            $this->_checkoutInfoDto->setShipLastName($this->checkoutFormData["client"]["surname"]);
            $this->_checkoutInfoDto->setShipAddress1($this->checkoutFormData["client"]["address_1"]);
            $this->_checkoutInfoDto->setShipAddress2($this->checkoutFormData["client"]["address_2"]);
            $this->_checkoutInfoDto->setShipCity($this->checkoutFormData["client"]["city"]);
            $this->_checkoutInfoDto->setShipPostal($this->checkoutFormData["client"]["postcode"]);
            $this->_checkoutInfoDto->setShipState($this->checkoutFormData["client"]["state"]);
            $this->_checkoutInfoDto->setShipTelCountryCode($this->checkoutFormData["client"]["tel_1"]);
            $this->_checkoutInfoDto->setShipTelAreaCode($this->checkoutFormData["client"]["tel_2"]);
            $this->_checkoutInfoDto->setShipTelNumber($this->checkoutFormData["client"]["tel_3"]);
        }
        return $this->_checkoutInfoDto;
    }

    public function getCartDto() {
        return $this->_buildCart();
    }

    private function _buildCart() {
        $cartSessionService = new CartSessionService(TRUE);
        $skuList = [];
        if ($this->checkoutFormData["soi"]) {
            foreach($this->checkoutFormData["soi"] as $item) {
                if ($item["sku"]) {
                    $unitPrice = $item["price"];
                    $qty = $item["qty"];
                    if (isset($skuList[$item["sku"]])) {
                        $qty += $skuList[$item["sku"]]["qty"];
                        $unitPrice = ($unitPrice + $skuList[$item["sku"]]["unitPrice"]) / 2;
                    }
                    $skuList[$item["sku"]] = ["qty" => $item["qty"], "name" => $item["name"], "unitPrice" => $item["price"]];
                }
            }
            $platformId = $this->checkoutFormData["platformId"];
            $platformBizObj = $this->getService("PlatformBizVar")->getDao("PlatformBizVar")->get(["selling_platform_id" => $platformId]);

            $cartSessionService->manualAddItemsToCart($skuList, $platformId, $platformBizObj, $this->checkoutFormData["delivery_charge"], TRUE);
            return $cartSessionService->getCart();
        }
        return false;
    }

    public function soBeforeInsertEvent($soObj) {
        $soObj->setTxnId($this->checkoutFormData["txn_id"]);
    }

    public function soInsertSuccessEvent($soObj) {
//add order notes
        if ($this->checkoutFormData["so_extend"]["notes"])
        {
            $orderNote = new \OrderNotesVo();
            $orderNote->setSoNo($soObj->getSoNo());
            $orderNote->setNote($this->checkoutFormData["so_extend"]["notes"]);
            $orderNote->setType("S");
            $insertResult = $this->getService("So")->getDao("OrderNotes")->insert($orderNote);
            if (insertResult === FALSE) {
                $message = __METHOD__ . __LINE__ . "sql:" . $this->getService("So")->getDao("OrderNotes")->db->last_query() . ", Error:" . $this->getService("So")->getDao("OrderNotes")->db->error()["message"];
                $this->sendAlert("[Panther] Cannot add order note to special order, so_no" . $soObj->getSoNo(), $message , "oswald-alert@eservicesgroup.com");
            }
        }
    }
}
