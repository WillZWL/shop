<?php
class AdwordsDataVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform_id;
    private $status = '1';
    private $price = '0.00';
    private $api_request_result = '1';
    private $comment = '';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setApiRequestResult($api_request_result)
    {
        $this->api_request_result = $api_request_result;
    }

    public function getApiRequestResult()
    {
        return $this->api_request_result;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }
}
