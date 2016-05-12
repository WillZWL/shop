<?php

class FdItemVo extends \BaseVo
{
    private $fdssc_id;
    private $sku;
    private $order = '1';

    protected $primary_key = ['fdssc_id', 'sku'];
    protected $increment_field = '';

    public function setFdsscId($fdssc_id)
    {
        if ($fdssc_id !== null) {
            $this->fdssc_id = $fdssc_id;
        }
    }

    public function getFdsscId()
    {
        return $this->fdssc_id;
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

    public function setOrder($order)
    {
        if ($order !== null) {
            $this->order = $order;
        }
    }

    public function getOrder()
    {
        return $this->order;
    }

}
