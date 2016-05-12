<?php

class FdSectionVo extends \BaseVo
{
    private $id;
    private $fd_id;
    private $fd_image;
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

    public function setFdId($fd_id)
    {
        if ($fd_id !== null) {
            $this->fd_id = $fd_id;
        }
    }

    public function getFdId()
    {
        return $this->fd_id;
    }

    public function setFdImage($fd_image)
    {
        if ($fd_image !== null) {
            $this->fd_image = $fd_image;
        }
    }

    public function getFdImage()
    {
        return $this->fd_image;
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
