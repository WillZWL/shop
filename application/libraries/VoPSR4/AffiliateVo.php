<?php
class AffiliateVo extends \BaseVo
{
    private $id;
    private $affiliate_id;
    private $platform_id;
    private $affiliate_description;
    private $ext_party = '';

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

    public function setAffiliateId($affiliate_id)
    {
        if ($affiliate_id !== null) {
            $this->affiliate_id = $affiliate_id;
        }
    }

    public function getAffiliateId()
    {
        return $this->affiliate_id;
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

    public function setAffiliateDescription($affiliate_description)
    {
        if ($affiliate_description !== null) {
            $this->affiliate_description = $affiliate_description;
        }
    }

    public function getAffiliateDescription()
    {
        return $this->affiliate_description;
    }

    public function setExtParty($ext_party)
    {
        if ($ext_party !== null) {
            $this->ext_party = $ext_party;
        }
    }

    public function getExtParty()
    {
        return $this->ext_party;
    }

}
