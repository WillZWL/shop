<?php
class ReleaseOrderHistoryVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $release_reason;


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

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setReleaseReason($release_reason)
    {
        if ($release_reason !== null) {
            $this->release_reason = $release_reason;
        }
    }

    public function getReleaseReason()
    {
        return $this->release_reason;
    }

}
