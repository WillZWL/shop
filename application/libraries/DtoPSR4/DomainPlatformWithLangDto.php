<?php
class DomainPlatformWithLangDto
{
    private $domain;
    private $platform_id;
    private $site_name;
    private $short_name;
    private $domain_type;
    private $status = "1";
    private $platform_country_id;
    private $language_id;
    private $platform_currency_id;
    private $type;
    private $create_on = "0000-00-00 00:00:00";
    private $create_at = "127.0.0.1";
    private $create_by;
    private $modify_on;
    private $modify_at = "127.0.0.1";
    private $modify_by;

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSiteName($site_name)
    {
        $this->site_name = $site_name;
    }

    public function getSiteName()
    {
        return $this->site_name;
    }

    public function setShortName($short_name)
    {
        $this->short_name = $short_name;
    }

    public function getShortName()
    {
        return $this->short_name;
    }

    public function setDomainType($domain_type)
    {
        $this->domain_type = $domain_type;
    }

    public function getDomainType()
    {
        return $this->domain_type;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setPlatformCurrencyId($platform_currency_id)
    {
        $this->platform_currency_id = $platform_currency_id;
    }

    public function getPlatformCurrencyId()
    {
        return $this->platform_currency_id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

}
