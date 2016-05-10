<?php
class ProductTypeVo extends \BaseVo
{
    private $sku;
    private $type_id;
    private $status;

    protected $primary_key = ['sku', 'type_id'];
    protected $increment_field = '';

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

    public function setTypeId($type_id)
    {
        if ($type_id !== null) {
            $this->type_id = $type_id;
        }
    }

    public function getTypeId()
    {
        return $this->type_id;
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

}
