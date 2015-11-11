<?php
class AffiliateVo extends \BaseVo
{
    private $id;
    private $platform_id;
    private $affiliate_description;
    private $ext_party;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on = '';
    private $modify_at = '127.0.0.1';
    private $modify_by;

    private $primary_key = ['id'];
    private $increment_field = '';

    public function setId($id)
    {
        if ($id) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setAffiliateDescription($affiliate_description)
    {
        if ($affiliate_description) {
            $this->affiliate_description = $affiliate_description;
        }
    }

    public function getAffiliateDescription()
    {
        return $this->affiliate_description;
    }

    public function setExtParty($ext_party)
    {
        if ($ext_party) {
            $this->ext_party = $ext_party;
        }
    }

    public function getExtParty()
    {
        return $this->ext_party;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by) {
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
