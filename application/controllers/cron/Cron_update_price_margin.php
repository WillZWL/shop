<?php
class Cron_update_price_margin extends MY_Controller
{
    private $app_id="CRN0030";

    function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/price_margin_model');
        $this->load->helper('url');
    }

    // cron/cron_update_price_margin/refresh_all_platform_margin
    public function refresh_all_nonweb_platform_margin()
    {
        $email = "itsupport@eservicesgroup.net";
        $ts = date("Y-m-d H:i:s");

        $platform_where["id NOT LIKE 'WEB%'"] = NULL;
        $result = $this->price_margin_model->refresh_all_platform_margin($platform_where);

        if($result["status"])
        {
            $updatelist = $result["updatelist"];
            mail($email, "VB price_margin platforms update", "<cron_update_price_margin> price_margin refreshed for following NON-WEB platforms @ GMT+0 $ts: \n$updatelist");
        }
        else
        {
            mail($email, "VB price_margin platforms update", "<cron_update_price_margin> price_margin update failed for NON-WEB @ GMT+0 $ts");
        }
    }

    public function refresh_web_platform_margin($platform_list="")
    {
        // this function allows bulk update of few platforms together without causing memory limit or time out
        $email = "itsupport@eservicesgroup.net";
        $ts = date("Y-m-d H:i:s");

        // http://admincentre.valuebasket.com/cron/cron_update_price_margin/refresh_all_web_platform_margin/WEBHK,WEBGB
        if($platform_list)
        {
            $platform_arr = explode(",", $platform_list);

            foreach ($platform_arr as $key => $platform_id)
            {
                $platform_id = strtoupper($platform_id);
                $result = $this->price_margin_model->refresh_margin($platform_id);
                $updatelist .= "$platform_id,\n";
                echo "<pre>Refreshing $platform_id ...</pre>";
            }
            echo "DONE.";
            mail($email, "VB price_margin platforms update", "<cron_update_price_margin> price_margin refreshed for following WEB platforms @ GMT+0 $ts: \n$updatelist");
        }
        else
        {
            mail($email, "VB price_margin platforms update", "<cron_update_price_margin> update failed - no platform list.");
        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file cron_update_price_margin.php */
/* Location: ./app/controllers/cron_update_price_margin.php */
