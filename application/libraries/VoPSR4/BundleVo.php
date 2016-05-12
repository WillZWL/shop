<?php

class BundleVo extends \BaseVo
{
    private $prod_sku;
    private $component_sku;
    private $component_order = '0';

    protected $primary_key = ['prod_sku', 'component_sku', 'component_order'];
    protected $increment_field = '';

    public function setProdSku($prod_sku)
    {
        if ($prod_sku !== null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
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

    public function setComponentOrder($component_order)
    {
        if ($component_order !== null) {
            $this->component_order = $component_order;
        }
    }

    public function getComponentOrder()
    {
        return $this->component_order;
    }

}
