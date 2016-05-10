<?php
class PaymentOptionVo extends \BaseVo
{
    private $id;
    private $platform_id = '';
    private $page;
    private $set_id;


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

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPage($page)
    {
        if ($page !== null) {
            $this->page = $page;
        }
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setSetId($set_id)
    {
        if ($set_id !== null) {
            $this->set_id = $set_id;
        }
    }

    public function getSetId()
    {
        return $this->set_id;
    }

}
