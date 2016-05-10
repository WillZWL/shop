<?php
class LandpageListingVo extends \BaseVo
{
    private $id;
    private $catid = '0';
    private $platform_id = '';
    private $type = '';
    private $mode = 'M';
    private $rank = '0';
    private $selection;


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

    public function setCatid($catid)
    {
        if ($catid !== null) {
            $this->catid = $catid;
        }
    }

    public function getCatid()
    {
        return $this->catid;
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

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setMode($mode)
    {
        if ($mode !== null) {
            $this->mode = $mode;
        }
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setRank($rank)
    {
        if ($rank !== null) {
            $this->rank = $rank;
        }
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function setSelection($selection)
    {
        if ($selection !== null) {
            $this->selection = $selection;
        }
    }

    public function getSelection()
    {
        return $this->selection;
    }

}
