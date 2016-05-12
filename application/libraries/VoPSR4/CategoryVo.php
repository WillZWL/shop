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
    private $stop_sync_priority = '0';
    private $bundle_discount = '0.00';
    private $min_display_qty = '0';
    private $sponsored = '0';
    private $status = '1';
    private $hidden = '0';

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

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setParentCatId($parent_cat_id)
    {
        if ($parent_cat_id !== null) {
            $this->parent_cat_id = $parent_cat_id;
        }
    }

    public function getParentCatId()
    {
        return $this->parent_cat_id;
    }

    public function setLevel($level)
    {
        if ($level !== null) {
            $this->level = $level;
        }
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setAddColourName($add_colour_name)
    {
        if ($add_colour_name !== null) {
            $this->add_colour_name = $add_colour_name;
        }
    }

    public function getAddColourName()
    {
        return $this->add_colour_name;
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

    public function setStopSyncPriority($stop_sync_priority)
    {
        if ($stop_sync_priority !== null) {
            $this->stop_sync_priority = $stop_sync_priority;
        }
    }

    public function getStopSyncPriority()
    {
        return $this->stop_sync_priority;
    }

    public function setBundleDiscount($bundle_discount)
    {
        if ($bundle_discount !== null) {
            $this->bundle_discount = $bundle_discount;
        }
    }

    public function getBundleDiscount()
    {
        return $this->bundle_discount;
    }

    public function setMinDisplayQty($min_display_qty)
    {
        if ($min_display_qty !== null) {
            $this->min_display_qty = $min_display_qty;
        }
    }

    public function getMinDisplayQty()
    {
        return $this->min_display_qty;
    }

    public function setSponsored($sponsored)
    {
        if ($sponsored !== null) {
            $this->sponsored = $sponsored;
        }
    }

    public function getSponsored()
    {
        return $this->sponsored;
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

    public function setHidden($hidden)
    {
        if ($hidden !== null) {
            $this->hidden = $hidden;
        }
    }

    public function getHidden()
    {
        return $this->hidden;
    }

}
