<?php
namespace ESG\Panther\Service;

class ManualOrderCreationService extends BaseService 
implements CreateSoInterface, CreateSoEventInterface
{
    private $_checkoutFormData = null;
    private $_checkoutInfoDto = null;

    public function __construct($formValue) {
        parent::__construct();
        $this->_checkoutFormData = $formValue;
    }

    public function getBizType() {
        return "MANUAL";
    }

    public function selfCreateClientObj() {
        $email = $this->_checkoutFormData["client"]["email"];
        return $this->getService("Client")->getDao("Client")->get(["email" => $email]);
    }

    public function getCheckoutData() {
        if (!$this->_checkoutInfoDto) {
            $this->_checkoutInfoDto = new \CheckoutInfoDto;
            $this->_checkoutInfoDto->setOrderReason($this->_checkoutFormData["so_extend"]["order_reason"]);
            $this->_checkoutInfoDto->setOrderNotes($this->_checkoutFormData["so_extend"]["notes"]);
            $this->_checkoutInfoDto->setLangId("en");
            $this->_checkoutInfoDto->setPaymentGatewayId($this->_checkoutFormData["payment_gateway"]);
            $this->_checkoutInfoDto->setPayDate($this->_checkoutFormData["payment_date"]);
        }
        return $this->_checkoutInfoDto;
    }

    public function getCartDto() {
        return $this->_buildCart();
    }

    private function _buildCart() {
        $cartSessionService = new CartSessionService(TRUE);
        $skuList = [];
        if ($this->_checkoutFormData["soi"]) {
            foreach($this->_checkoutFormData["soi"] as $item) {
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
            $platformId = $this->_checkoutFormData["platformId"];
            $platformBizObj = $this->getService("PlatformBizVar")->getDao("PlatformBizVar")->get(["selling_platform_id" => $platformId]);

            $cartSessionService->manualAddItemsToCart($skuList, $platformId, $platformBizObj, $this->_checkoutFormData["delivery_charge"]);
            return $cartSessionService->getCart();
        }
        return false;
    }

    public function soBeforeInsertEvent($soObj) {
        $soObj->setTxnId($this->_checkoutFormData["txn_id"]);
    }

    public function soInsertSuccessEvent($soObj) {
//add order notes
        if ($this->_checkoutFormData["so_extend"]["notes"])
        {
            $orderNote = new \OrderNotesVo();
            $orderNote->setSoNo($soObj->getSoNo());
            $orderNote->setNote($this->_checkoutFormData["so_extend"]["notes"]);
            $orderNote->setType("S");
            $insertResult = $this->getService("So")->getDao("OrderNotes")->insert($orderNote);
            if (insertResult === FALSE) {
                $message = __METHOD__ . __LINE__ . "sql:" . $this->getService("So")->getDao("OrderNotes")->db->last_query() . ", Error:" . $this->getService("So")->getDao("OrderNotes")->db->error()["message"];
                $this->sendAlert("[Panther] Cannot add order note to special order, so_no" . $soObj->getSoNo(), $message , "oswald-alert@eservicesgroup.com");
            }
        }
    }
}
