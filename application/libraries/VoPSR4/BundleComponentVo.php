<?php

class BundleComponentVo extends \BaseVo
{
    private $id;
    private $bundle_core_id;
    private $component_sku;

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

    public function setBundleCoreId($bundle_core_id)
    {
        if ($bundle_core_id !== null) {
            $this->bundle_core_id = $bundle_core_id;
        }
    }

    public function getBundleCoreId()
    {
        return $this->bundle_core_id;
    }

    public function setComponentSku($component_sku)
    {
        if ($component_sku !== null) {
            $this->component_sku = $component_sku;
        }
    }

    public function getComponentSku()
    {
        return $this->component_sku;
    }

}
