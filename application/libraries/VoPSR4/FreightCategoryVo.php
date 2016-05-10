<?php
class FreightCategoryVo extends \BaseVo
{
    private $id;
    private $name;
    private $weight;
    private $declared_pcent;
    private $bulk_admin_chrg;
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

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setWeight($weight)
    {
        if ($weight !== null) {
            $this->weight = $weight;
        }
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setDeclaredPcent($declared_pcent)
    {
        if ($declared_pcent !== null) {
            $this->declared_pcent = $declared_pcent;
        }
    }

    public function getDeclaredPcent()
    {
        return $this->declared_pcent;
    }

    public function setBulkAdminChrg($bulk_admin_chrg)
    {
        if ($bulk_admin_chrg !== null) {
            $this->bulk_admin_chrg = $bulk_admin_chrg;
        }
    }

    public function getBulkAdminChrg()
    {
        return $this->bulk_admin_chrg;
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
