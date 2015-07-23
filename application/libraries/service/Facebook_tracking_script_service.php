<?php

include_once(APPPATH . "libraries/service/Atom_tracking_script_service.php");

class Facebook_tracking_script_service extends Atom_tracking_script_service
{
    const FB_PIXEL_ID_SG = "6008135149854";
    const FB_PIXEL_ID_MY = "6008338237054";
    const FB_PIXEL_ID_NZ = "6008338238254";
    const FB_PIXEL_ID_AU = "6008338238654";
    const FB_PIXEL_ID_PH = "6008581726454";
    const FB_PIXEL_ID_IE = "6009051452424";
    const FB_PIXEL_ID_ES = "6009051450824";
    const FB_PIXEL_ID_FR = "6009051450024";
    const FB_PIXEL_ID_GB = "6009051448224";
    const FB_PIXEL_ID_FI = "6009546532624";
    const FB_PIXEL_ID_SE = "6009546542624";
    const FB_PIXEL_ID_NO = "6009546543424";
    const FB_PIXEL_ID_HK = "6010172727654";

    const FB_ACCOUNT_CURRENCY = "EUR";

    private $_af_list = array("FBMY", "FBNZ", "FBSG", "FBAU", "FBPH", "FBIE", "FBES", "FBFR", "FBGB", "FBFI", "FBSE", "FBNO", "FBHK");

    public function __construct()
    {
        parent::Atom_tracking_script_service();
    }

    public function is_registered_page($page)
    {
        if ($this->is_payment_success_page($page))
            return true;

        return false;
    }

    public function need_to_show_generic_tracking_page()
    {
        return false;
    }

    public function get_specific_code($page = array(), $var = array())
    {
        $pixel_id = $this->_get_pixel_id(PLATFORMCOUNTRYID);
        if ($pixel_id == "") {
//get the pixel id again by affiliate id
            if ($var['affiliate_name']) {
                if (strlen($var['affiliate_name']) > 2) {
                    if (substr($var['affiliate_name'], 0, 2) == "FB")
                        $pixel_id = $this->_get_pixel_id(substr($var['affiliate_name'], -2));
                }
            }
        }
        $facebook_script = "";

        if ($this->is_payment_success_page($page)) {
            if (in_array($var['affiliate_name'], $this->_af_list)
                && ($pixel_id != "")
            ) {
                $currency_id = $var['so']->get_currency_id();
//              $amount = $this->convert_amount($var['total_amount'], $currency_id, self::FB_ACCOUNT_CURRENCY);
                $amount = $var['total_amount'];

                $facebook_script = <<< facebook_script_end
<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = '{$pixel_id}';
fb_param.value = '{$amount}';
fb_param.currency = '{$currency_id}';
(function(){
var fpw = document.createElement('script');
fpw.async = true;
fpw.src = (location.protocol=='http:'?'http':'https')+'://connect.facebook.net/en_US/fp.js';
var ref = document.getElementsByTagName('script')[0];
ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id={$pixel_id}&amp;value={$amount}&amp;currency={$currency_id}" /></noscript>
facebook_script_end;
            }
        }
        return $facebook_script;
    }

    private function _get_pixel_id($countryId)
    {
        switch ($countryId) {
            case "SG":
            case "MY":
            case "NZ":
            case "AU":
            case "PH":
            case "IE":
            case "ES":
            case "FR":
            case "GB":
            case "FI":
            case "SE":
            case "NO":
            case "HK":
                return constant("Facebook_tracking_script_service::FB_PIXEL_ID_" . $countryId);
            default:
                return "";
        }
    }

    public function get_all_page_code($page = array(), $var = array())
    {
        return "";
    }
}
