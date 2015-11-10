<?php

namespace ESG\Panther\Service;

class AffiliateService extends BaseService
{

    public function __construct()
    {
        parent::__construct();

        /*include_once(APPPATH . "libraries/dao/Affiliate_dao.php");
        $this->set_dao(new Affiliate_dao());
        include_once(APPPATH . 'libraries/dao/So_dao.php');
        $this->set_so_dao(New So_dao());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());*/
    }

    public function addAfCookie($src)
    {
        $domain = check_domain();

        $affiliate = NULL;

        if (is_array($src) && count($src) > 0) {
            if ($src['AF']) {
                $affiliate = $src['AF'];
                switch ($src['AF']) {
                    case 'LS':
                        $af_id = 'LS';
                    case 'LSAU':
                        $af_id = 'LSAU';
                        if ($src['siteID']) {
                            $site_id = mysql_real_escape_string($src['siteID']);
                            $this->setLinkshareTrackingCookie($site_id, $domain, $af_id);
                        }
                        break;
                    case 'LSNZ':
                        $af_id = 'LSNZ';
                        if ($src['siteID']) {
                            $site_id = mysql_real_escape_string($src['siteID']);
                            $this->setLinkshareTrackingCookie($site_id, $domain, $af_id);
                        }
                        break;
                    default:
                }
            }

        } else if (!empty($src)) {
            $affiliate = $src;
        } else {
            return;
        }

        if ($affiliate) {
            setcookie("af", $affiliate, time() + (60 * 60 * 24 * 30), "/", "." . $domain);
            $_COOKIE["af"] = $affiliate;
        }

    }

    public function setLinkshareTrackingCookie($site_id, $domain, $af_id)
    {
        $siteID = $_GET["siteID"];
        $expire_time = 60 * 60 * 24 * 365 * 2;
        $time_enter = gmdate('Y-m-d/H:i:s', gmmktime());
        $valid_id = "/^[-a-zA-Z0-9._\/*]{34}$/";
        if (preg_match($valid_id, $siteID)) {
            setcookie('af', $af_id, time() + $expire_time, "/", "." . $domain);
            setcookie('af_ref', $siteID, time() + $expire_time, "/", "." . $domain);
            setcookie('LS_siteID', $siteID, time() + $expire_time, "/", "." . $domain);
            setcookie('LS_timeEntered', $time_enter, time() + $expire_time, "/", "." . $domain);
        }
    }

    public function removeAfRecord()
    {
        $domain = check_domain();
        setcookie("af", "", time() - 3600, "/", "." . $domain);
        setcookie('af_ref', "", time() - 3600, "/", "." . $domain);
    }

    public function getAfRecord()
    {
        $data = array();
        if (isset($_COOKIE["af"]) && $this->get(array("id" => $_COOKIE["af"]))) {
            if (isset($_COOKIE["af_ref"])) {
                $af_ref = $_COOKIE["af_ref"];
            }
            return array("af" => $_COOKIE["af"], "af_ref" => $af_ref);
        }
        return $data;
    }

}


