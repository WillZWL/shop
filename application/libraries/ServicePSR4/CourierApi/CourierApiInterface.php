<?php
namespace ESG\Panther\Service\CourierApi;

interface CourierApiInterface
{

    public function getCourierId();
	/******************************************
	**  function getCourierId
	**  this will return courier id
	********************************************/
	public function getCourierDataType();
	/******************************************
	**  function getCourierDataType
	**  this will return courier POST type:xml, json
	********************************************/
    public function getCourierRequestData($action,$requestData);
	/******************************************
	**  function getCourierRequestData
	**  return courier post data to add order
	********************************************/
	public function getCourierReturnData($action,$courier_return_result);
	/******************************************
	**  function getCourierReturnData
	**  return  Courier return data
	********************************************/
   
}