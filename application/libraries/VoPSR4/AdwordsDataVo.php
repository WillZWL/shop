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

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setPrice($price)
    {
        if ($price !== null) {
            $this->price = $price;
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setApiRequestResult($api_request_result)
    {
        if ($api_request_result !== null) {
            $this->api_request_result = $api_request_result;
        }
    }

    public function getApiRequestResult()
    {
        return $this->api_request_result;
    }

    public function setComment($comment)
    {
        if ($comment !== null) {
            $this->comment = $comment;
        }
    }

    public function getComment()
    {
        return $this->comment;
    }

}
