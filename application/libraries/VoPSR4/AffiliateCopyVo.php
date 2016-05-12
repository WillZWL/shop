<?php

class AffiliateCopyVo extends \BaseVo
{
    private $id;
    private $platform_id;
    private $affiliate_description;
    private $ext_party;

    protected $primary_key = ['id'];
    protected $increment_field = '';

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
