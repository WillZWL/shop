<?php
class SiteConfigVo extends \BaseVo
{
    private $id;
    private $domain;
    private $site_name = '';
    private $lang = '';
    private $logo = '';
    private $email = '';
    private $platform;
    private $domain_type = '1';
    private $api_implemented = '0';
    private $status = '1';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setLang($lang)
    {
        if ($lang !== null) {
            $this->lang = $lang;
        }
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLogo($logo)
    {
        if ($logo !== null) {
            $this->logo = $logo;
        }
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setEmail($email)
    {
        if ($email !== null) {
            $this->email = $email;
        }
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPlatform($platform)
    {
        if ($platform !== null) {
            $this->platform = $platform;
        }
    }

    public function getPlatform()
    {
        return $this->platform;
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

    public function setApiImplemented($api_implemented)
    {
        if ($api_implemented !== null) {
            $this->api_implemented = $api_implemented;
        }
    }

    public function getApiImplemented()
    {
        return $this->api_implemented;
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
