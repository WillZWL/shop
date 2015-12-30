<?php
namespace ESG\Panther\Service;

interface CreateSoInterface
{
    public function getBizType();
/******************************************
**  function selfCreateClientObj
**  this will return a client, some may want to create client differently
********************************************/
    public function selfCreateClientObj();
/******************************************
**  function getCheckoutData
**  return CheckoutInfoDto
********************************************/
    public function getCheckoutData();

/******************************************
**  function getCartDto
**  return CartDto
********************************************/
    public function getCartDto();
}
