<?php

class FdSectionSubCatVo extends \BaseVo
{
    private $id;
    private $fdsc_id;
    private $left_image;
    private $bg_image;
    private $right_image;
    private $right_link;
    private $display_order = '1';

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

    public function setFdscId($fdsc_id)
    {
        if ($fdsc_id !== null) {
            $this->fdsc_id = $fdsc_id;
        }
    }

    public function getFdscId()
    {
        return $this->fdsc_id;
    }

    public function setLeftImage($left_image)
    {
        if ($left_image !== null) {
            $this->left_image = $left_image;
        }
    }

    public function getLeftImage()
    {
        return $this->left_image;
    }

    public function setBgImage($bg_image)
    {
        if ($bg_image !== null) {
            $this->bg_image = $bg_image;
        }
    }

    public function getBgImage()
    {
        return $this->bg_image;
    }

    public function setRightImage($right_image)
    {
        if ($right_image !== null) {
            $this->right_image = $right_image;
        }
    }

    public function getRightImage()
    {
        return $this->right_image;
    }

    public function setRightLink($right_link)
    {
        if ($right_link !== null) {
            $this->right_link = $right_link;
        }
    }

    public function getRightLink()
    {
        return $this->right_link;
    }

    public function setDisplayOrder($display_order)
    {
        if ($display_order !== null) {
            $this->display_order = $display_order;
        }
    }

    public function getDisplayOrder()
    {
        return $this->display_order;
    }

}
