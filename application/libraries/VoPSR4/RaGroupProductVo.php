<?php
class RaGroupProductVo extends \BaseVo
{
    private $ra_group_id;
    private $sku;
    private $priority = '1';
    private $build_bundle;

    protected $primary_key = ['ra_group_id', 'sku'];
    protected $increment_field = '';

    public function setRaGroupId($ra_group_id)
    {
        if ($ra_group_id !== null) {
            $this->ra_group_id = $ra_group_id;
        }
    }

    public function getRaGroupId()
    {
        return $this->ra_group_id;
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

    public function setPriority($priority)
    {
        if ($priority !== null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setBuildBundle($build_bundle)
    {
        if ($build_bundle !== null) {
            $this->build_bundle = $build_bundle;
        }
    }

    public function getBuildBundle()
    {
        return $this->build_bundle;
    }

}
