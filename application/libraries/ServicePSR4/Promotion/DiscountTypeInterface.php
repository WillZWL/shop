<?php
namespace ESG\Panther\Service\Promotion;

interface DiscountTypeInterface {

    public function getPromotionCart();
	/******************************************
	**  function getPromotionCart
	**  return  cartDto
	********************************************/
	public function modifyPromotionCart();
	/******************************************
	**  function modifyPromotionCart
	**  return  cartDto
	********************************************/
	public function validateRomoveCartItemAction();
	/******************************************
	**  function validateRomoveCartItemAction
	**  this will return cartDto
	********************************************/
	public function cancelPromotionCart();
	/******************************************
	**  function cancelPromotionCart
	**  this will return cartDto
	********************************************/
}
