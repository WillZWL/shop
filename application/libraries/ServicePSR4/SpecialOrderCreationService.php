<?php
namespace ESG\Panther\Service;

class SpecialOrderCreationService extends OrderCreationService
implements CreateSoInterface, CreateSoEventInterface
{
    private $_checkoutInfoDto = null;

    public function __construct($formValue) {
        parent::__construct($formValue);
    }

    public function getBizType() {
        return "SPECIAL";
    }

    public function selfCreateClientObj() {
        $email = $this->checkoutFormData["client"]["email"];
        return $this->getService("Client")->getDao("Client")->get(["email" => $email]);
    }

    public function getCheckoutData() {
        if (!$this->_checkoutInfoDto) {
            $this->_checkoutInfoDto = new \CheckoutInfoDto;
            $this->_checkoutInfoDto->setOrderReason($this->checkoutFormData["so_extend"]["order_reason"]);
            $this->_checkoutInfoDto->setOrderNotes($this->checkoutFormData["so_extend"]["notes"]);
            $this->_checkoutInfoDto->setParentSoNo($this->checkoutFormData["parent_so_no"]);
            $this->_checkoutInfoDto->setLangId("en");
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
//do nothing
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
