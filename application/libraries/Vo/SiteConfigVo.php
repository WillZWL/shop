<?php

namespace AtomV2\Vo;

class SiteConfigVo extends BaseVo
{
    private $id;
    private $domain;
    private $site_name;
    private $logo;
    private $email;
    private $domain_type;
    private $status;

    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    private $primary_key = array("id");

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getSiteName()
    {
        return $this->site_name;
    }

    public function setSiteName($site_name)
    {
        $this->site_name = $site_name;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getDomainType()
    {
        return $this->domain_type;
    }

    public function setDomainType($domain_type)
    {
        $this->domain_type = $domain_type;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
