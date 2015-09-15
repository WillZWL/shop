<?php
class SiteDto
{
    protected $domain;
    protected $siteName;
    protected $lang;
    protected $logo;
    protected $email;
    protected $platform;
    protected $domainType;
    protected $siteStatus;
    protected $platformCountryId;
    protected $platformCurrencyId;
    protected $signPos;
    protected $decPlace;
    protected $decPoint;
    protected $thousandsSep;
    protected $sign;
    protected $roundUpNearestForPriceTable;
    protected $langId;

    public function getDomain() {
        return $this->domain;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function getSiteName() {
        return $this->siteName;
    }

    public function setSiteName($siteName) {
        $this->siteName = $siteName;
    }
    
    public function getLang() {
        return $this->lang;
    }

    public function setLang($lang) {
        $this->lang = $lang;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getPlatform() {
        return $this->platform;
    }

    public function setPlatform($platform) {
        $this->platform = $platform;
    }
    
    public function getDomainType() {
        return $this->domainType;
    }

    public function setDomainType($domainType) {
        $this->domainType = $domainType;
    }

    public function getSiteStatus() {
        return $this->siteStatus;
    }

    public function setSiteStatus($siteStatus) {
        $this->siteStatus = $siteStatus;
    }

    public function getPlatformCountryId() {
        return $this->platformCountryId;
    }

    public function setPlatformCountryId($platformCountryId) {
        $this->platformCountryId = $platformCountryId;
    }

    public function getPlatformCurrencyId() {
        return $this->platformCurrencyId;
    }

    public function setPlatformCurrencyId($platformCurrencyId) {
        $this->platformCurrencyId = $platformCurrencyId;
    }

    public function getSignPos() {
        return $this->signPos;
    }

    public function setSignPos($signPos) {
        $this->signPos = $signPos;
    }
    
    public function getDecPlace() {
        return $this->decPlace;
    }

    public function setDecPlace($decPlace) {
        $this->decPlace = $decPlace;
    }

    public function getDecPoint() {
        return $this->decPoint;
    }

    public function setDecPoint($decPoint) {
        $this->decPoint = $decPoint;
    }
    
    public function getThousandsSep() {
        return $this->thousandsSep;
    }

    public function setThousandsSep($thousandsSep) {
        $this->thousandsSep = $thousandsSep;
    }
    
    public function getSign() {
        return $this->sign;
    }

    public function setSign($sign) {
        $this->sign = $sign;
    }
    
    public function getRoundUpNearestForPriceTable() {
        return $this->roundUpNearestForPriceTable;
    }

    public function setRoundUpNearestForPriceTable($roundUpNearestForPriceTable) {
        $this->roundUpNearestForPriceTable = $roundUpNearestForPriceTable;
    }

    public function getLangId() {
        return $this->langId;
    }

    public function setLangId($langId) {
        $this->langId = $langId;
    }
}