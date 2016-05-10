<?php
class VersionVo extends \BaseVo
{
    private $id;
    private $version_id;
    private $desc;
    private $status = 'A';

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

    public function setVersionId($version_id)
    {
        if ($version_id != null) {
            $this->version_id = $version_id;
        }
    }

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setDesc($desc)
    {
        if ($desc != null) {
            $this->desc = $desc;
        }
    }

    public function getDesc()
    {
        return $this->desc;
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




}
