<?php
class BundleListDto
{
    private $prod_sku;
    private $component_sku;
    private $component_order;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $components;
    private $bundle_name;

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setComponentSku($component_sku)
    {
        $this->component_sku = $component_sku;
    }

    public function getComponentSku()
    {
        return $this->component_sku;
    }

    public function setComponentOrder($component_order)
    {
        $this->component_order = $component_order;
    }

    public function getComponentOrder()
    {
        return $this->component_order;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setComponents($components)
    {
        $this->components = $components;
    }

    public function getComponents()
    {
        return $this->components;
    }

    public function setBundleName($bundle_name)
    {
        $this->bundle_name = $bundle_name;
    }

    public function getBundleName()
    {
        return $this->bundle_name;
    }

}
