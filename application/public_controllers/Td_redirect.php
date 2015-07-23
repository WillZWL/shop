<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Td_redirect extends PUB_Controller
{
    public function Td_redirect()
    {
        parent::PUB_Controller();
        $this->load->library('service/affiliate_service');
    }

#   SBF #2284 Tradedoubler FR redirect script, SBF #2382 ES redirect
    public function index($country = "FR")
    {
        /********************************** /
         * Three parts to a tradedoubler redirect url
         * e.g. dev.valuebasket.com/td_redirect/index/FR/?tduid=1234&url=http://www.valuebasket.fr/fr_FR/search/search_by_ss?q=MySaga&AF=TDFR
         * 1) dev.valuebasket.com/td_redirect/index/FR/ --> redirects to respective country to set cookie
         * 2) ?tduid=1234 --> Tradedoubler will input a tduid, which we will use to set cookie
         * 3) &url=xxxxx --> This will be the destination after redirect. Set our internal affiliate ID here (SBF #3942)
         * / **********************************/

        session_start();

        # for easier debugging in dev; so that it goes to dev site
        if (substr($_SERVER['HTTP_HOST'], 0, 3) != "dev" || substr($_SERVER['HTTP_HOST'], 0, 3) != "www")
            $subdomain = "www";
        else
            $subdomain = (substr($_SERVER['HTTP_HOST'], 0, 3));

        switch ($country) {
            case "FR":
                $defaultUrl = "http://$subdomain.valuebasket.fr/fr_FR";
                $domain = "valuebasket.fr";
                break;

            case "ES":
                $defaultUrl = "http://$subdomain.valuebasket.es/es_ES";
                $domain = "valuebasket.es";
                break;

            case "IT":
                $defaultUrl = "http://$subdomain.valuebasket.com/it_IT";
                $domain = "valuebasket.com";
                break;

            case "GB":
                $defaultUrl = "http://$subdomain.valuebasket.com/en_GB";
                $domain = "valuebasket.com";
                break;

            case "PL":
                $defaultUrl = "http://$subdomain.valuebasket.pl/pl_PL";
                $domain = "valuebasket.pl";
                break;

            default:
                header("Location: http://www.valuebasket.com");
                die();
                break;
        }

        if (!empty($_GET["tduid"])) {
            // if you are not vb.es and you are trying to cookie vb.es
            // then you must jump to vb.es/td_redirect/index/ES?tduid=abcdef
            // and let vb.es redirect to http://vb.es/

            $cookieDomain = "." . $domain;
            if (stripos($_SERVER["SERVER_NAME"], $cookieDomain) === false) {
                # we pass all the original query parameters to the correct redirect country url
                $url = "http://$subdomain.$domain/en_$country/td_redirect/index/$country/?" . $_SERVER["QUERY_STRING"];
                // $url = "http://$domain/".lang_part()."/td_redirect/index/$country/?tduid=" . $_GET["tduid"];

                // var_dump($url); die();
                header("Location: " . $url);
                die();
            } else {
                $cookieDomain = "." . $domain;
                setcookie("TRADEDOUBLER", $_GET["tduid"], (time() + 3600 * 24 * 365), "/", $cookieDomain);
                // setcookie("TRADEDOUBLER", $_GET["tduid"],(time() + 10), "/", $cookieDomain);
                // If you do not use the built-in session functionality in PHP, modify
                // the following expression to work with your session handling routines.
                //
                $_SESSION["TRADEDOUBLER"] = $_GET["tduid"];
            }
            // echo "<pre>"; var_dump($_SERVER);    var_dump($cookieDomain); die();
        }

        if (empty($_GET["url"]))
            $url = $defaultUrl;
        else
            $url = urldecode(substr(strstr($_SERVER["QUERY_STRING"], "url"), 4));

        if ($_GET) {
            # add affiliate cookie in case it gets lost after redirect
            $this->affiliate_service->add_af_cookie($_GET);
        }

        header("Location: " . $url);
    }
}

