<?php

class BundleCoreVo extends \BaseVo
{
    private $id;
    private $core_sku;
    private $bundle_no;
    private $status = '1';

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

    public function setCoreSku($core_sku)
    {
        if ($core_sku !== null) {
            $this->core_sku = $core_sku;
        }
    }

    public function getCoreSku()
    {
        return $this->core_sku;
    }

    public function setBundleNo($bundle_no)
    {
        if ($bundle_no !== null) {
            $this->bundle_no = $bundle_no;
        }
    }

    public function getBundleNo()
    {
        return $this->bundle_no;
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
