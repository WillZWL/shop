<?php

class DomainPlatformVo extends \BaseVo
{
    private $domain;
    private $platform_id;
    private $site_name;
    private $short_name;
    private $domain_type = '1';
    private $status = '1';

    protected $primary_key = ['domain'];
    protected $increment_field = '';

    public function setDomain($domain)
    {
        if ($domain !== null) {
            $this->domain = $domain;
        }
    }

    public function getDomain()
    {
        return $this->domain;
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

    public function setSiteName($site_name)
    {
        if ($site_name !== null) {
            $this->site_name = $site_name;
        }
    }

    public function getSiteName()
    {
        return $this->site_name;
    }

    public function setShortName($short_name)
    {
        if ($short_name !== null) {
            $this->short_name = $short_name;
        }
    }

    public function getShortName()
    {
        return $this->short_name;
    }

    public function setDomainType($domain_type)
    {
        if ($domain_type !== null) {
            $this->domain_type = $domain_type;
        }
    }

    public function getDomainType()
    {
        return $this->domain_type;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
