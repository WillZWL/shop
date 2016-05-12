<?php

class CategoryVarVo extends \BaseVo
{
    private $cat_id;
    private $budget_pcent;
    private $status = '1';

    protected $primary_key = ['cat_id'];
    protected $increment_field = '';

    public function setCatId($cat_id)
    {
        if ($cat_id !== null) {
            $this->cat_id = $cat_id;
        }
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setBudgetPcent($budget_pcent)
    {
        if ($budget_pcent !== null) {
            $this->budget_pcent = $budget_pcent;
        }
    }

    public function getBudgetPcent()
    {
        return $this->budget_pcent;
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
