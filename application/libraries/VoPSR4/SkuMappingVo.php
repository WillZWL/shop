<?php
class SkuMappingVo extends \BaseVo
{
    private $id;
    private $sku;
    private $ext_sys = '';
    private $ext_sku = '';
    private $vb_sku = '';
    private $status = '1';

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

    public function setExtSys($ext_sys)
    {
        if ($ext_sys !== null) {
            $this->ext_sys = $ext_sys;
        }
    }

    public function getExtSys()
    {
        return $this->ext_sys;
    }

    public function setExtSku($ext_sku)
    {
        if ($ext_sku !== null) {
            $this->ext_sku = $ext_sku;
        }
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setVbSku($vb_sku)
    {
        if ($vb_sku !== null) {
            $this->vb_sku = $vb_sku;
        }
    }

    public function getVbSku()
    {
        return $this->vb_sku;
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
