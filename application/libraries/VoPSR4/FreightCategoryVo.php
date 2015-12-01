<?php
class FreightCategoryVo extends \BaseVo
{
    private $id;
    private $name;
    private $weight;
    private $declared_pcent;
    private $bulk_admin_chrg;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        if ($name != null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setWeight($weight)
    {
        if ($weight != null) {
            $this->weight = $weight;
        }
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setDeclaredPcent($declared_pcent)
    {
        if ($declared_pcent != null) {
            $this->declared_pcent = $declared_pcent;
        }
    }

    public function getDeclaredPcent()
    {
        return $this->declared_pcent;
    }

    public function setBulkAdminChrg($bulk_admin_chrg)
    {
        if ($bulk_admin_chrg != null) {
            $this->bulk_admin_chrg = $bulk_admin_chrg;
        }
    }

    public function getBulkAdminChrg()
    {
        return $this->bulk_admin_chrg;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
