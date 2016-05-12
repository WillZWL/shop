<?php

class FdSectionCatVo extends \BaseVo
{
    private $id;
    private $fds_id;
    private $banner;
    private $image;
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

    public function setFdsId($fds_id)
    {
        if ($fds_id !== null) {
            $this->fds_id = $fds_id;
        }
    }

    public function getFdsId()
    {
        return $this->fds_id;
    }

    public function setBanner($banner)
    {
        if ($banner !== null) {
            $this->banner = $banner;
        }
    }

    public function getBanner()
    {
        return $this->banner;
    }

    public function setImage($image)
    {
        if ($image !== null) {
            $this->image = $image;
        }
    }

    public function getImage()
    {
        return $this->image;
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
