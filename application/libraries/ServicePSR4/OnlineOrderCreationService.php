<?php
namespace ESG\Panther\Service;

class OnlineOrderCreationService extends BaseService implements CreateSoInterface
{
    private $_checkoutFormData = null;
    private $_checkoutInfoDto = null;

    public function __construct($formValue) {
        parent::__construct();
        $this->_checkoutFormData = $formValue;
    }

    public function getBizType() {
        return "ONLINE";
    }

    public function selfCreateClientObj() {
        return false;
    }

    public function getCheckoutData() {
        if (!$this->_checkoutInfoDto) {
            $this->_checkoutInfoDto = new \CheckoutInfoDto;
            setValuePsrArray($this->_checkoutInfoDto, $this->_checkoutFormData);
        }
        return $this->_checkoutInfoDto;
    }

    public function getCartDto() {
        $cart = $this->getService("CartSession")->getCart();
        $cart->setBizType($this->getBizType());
        return $cart;
    }
}

