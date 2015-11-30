<?php
class CategoryVo extends \BaseVo
{
    private $id;
    private $name;
    private $description;
    private $parent_cat_id = '0';
    private $level = '1';
    private $add_colour_name = '1';
    private $priority = '9';
    private $bundle_discount = '0.00';
    private $min_display_qty = '0';
    private $sponsored = '0';
    private $status = '1';
    private $hidden = '0';
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

    public function setDescription($description)
    {
        if ($description != null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setParentCatId($parent_cat_id)
    {
        if ($parent_cat_id != null) {
            $this->parent_cat_id = $parent_cat_id;
        }
    }

    public function getParentCatId()
    {
        return $this->parent_cat_id;
    }

    public function setLevel($level)
    {
        if ($level != null) {
            $this->level = $level;
        }
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setAddColourName($add_colour_name)
    {
        if ($add_colour_name != null) {
            $this->add_colour_name = $add_colour_name;
        }
    }

    public function getAddColourName()
    {
        return $this->add_colour_name;
    }

    public function setPriority($priority)
    {
        if ($priority != null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setBundleDiscount($bundle_discount)
    {
        if ($bundle_discount != null) {
            $this->bundle_discount = $bundle_discount;
        }
    }

    public function getBundleDiscount()
    {
        return $this->bundle_discount;
    }

    public function setMinDisplayQty($min_display_qty)
    {
        if ($min_display_qty != null) {
            $this->min_display_qty = $min_display_qty;
        }
    }

    public function getMinDisplayQty()
    {
        return $this->min_display_qty;
    }

    public function setSponsored($sponsored)
    {
        if ($sponsored != null) {
            $this->sponsored = $sponsored;
        }
    }

    public function getSponsored()
    {
        return $this->sponsored;
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

    public function setHidden($hidden)
    {
        if ($hidden != null) {
            $this->hidden = $hidden;
        }
    }

    public function getHidden()
    {
        return $this->hidden;
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
