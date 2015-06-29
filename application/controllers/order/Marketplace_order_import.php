<?php
class Marketplace_order_import extends MY_Controller
{

    private $app_id="ORD0030";
    private $lang_id="en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/marketplace_order_import_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/user_service');
        $this->load->library('service/pagination_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('encrypt');
    }

    public function index($marketplace="")
    {
        $platform_id_list = array();
        if($marketplace)
        {
            $platform_list = $this->platform_biz_var_service->get_list_w_platform_name(array("s.type"=>$marketplace));
            if($platform_list)
            {


                $data["platform_action_html"] = $this->get_marketplace_html($marketplace, $platform_list);
            }
            else
            {
                $data["error_message"] = "No platform_ids found for this marketplace.";
            }
        }

        $data["platform_id_list"] = $platform_id_list;
        // include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["selected_marketplace"] = $marketplace;
        $data["marketplace_list"] = array("qoo10", "rakuten", "fnac");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);

        //$this->load->view('order/credit_check/off_credit_check_index_v', $data);
        $this->load->view('order/marketplace_order_import/index_v', $data);
    }


    private function get_marketplace_html($marketplace, $platform_list)
    {
        $html = '';
        if($platform_list)
        {
            foreach ($platform_list as $key => $obj)
            {
                $platformid_option .= <<<html
                    <option value='{$obj->get_selling_platform_id()}' >{$obj->get_selling_platform_id()}</option>
html;

                $platformcountryid_option .= <<<html
                    <option value='{$obj->get_platform_country_id()}' >{$obj->get_platform_country_id()}</option>
html;
            }

        }

        $base_url = base_url();
        if(strtoupper($marketplace) == "RAKUTEN")
        {
            $nowdate = date("Y-m-d");
            $html = <<<html
                    <tr>
                        <td>Choose Country</td>
                        <td>
                            <select id="country">
                                $platformcountryid_option
                            </select>
                        </td>
                    <tr>
                    <tr>
                        <td colspan="2">
                            <table style='border-collapse: collapse; width:100%; ' cellpadding="8x">
                                <colgroup><col width="10%"><col width="50%"><col width="40%"></colgroup>
                                <tr class="border_bottom">
                                    <td colspan='3'>Choose your actions to run below <br><td>
                                </tr>
                                <tr class="border_bottom">
                                    <td> 1 </td>
                                    <td>Run Default Orders Import</td>
                                    <td>
                                        Orders ending on <input id="nowDate" type="text" value="$nowdate" size="23"><br>
                                        and <input id="numberDays" type="text" value="7" size="2"> days ago from above date. <br>
                                        <input type="button" onclick="runRakuten('default')" value="Run Default">
                                    </td>
                                </tr>
                                <tr class="border_bottom">
                                    <td> 2 </td>
                                    <td>Import Single Order</td>
                                    <td>
                                        Rakuten Order Number <input id="orderNumber" type="text" value="" size="23"><br>
                                        <input type="button" onclick="runRakuten('import_single')" value="Import Single">
                                    </td>
                                </tr>
                                <tr class="border_bottom">
                                    <td> 3 </td>
                                    <td>Get Orders' Info (Report)</td>
                                    <td>
                                        Orders ending on <input id="info_nowDate" type="text" value="$nowdate" size="23"><br>
                                        and <input id="info_numberDays" type="text" value="7" size="2"> days ago from above date<br>
                                        <input type="button" onclick="runRakuten('get_orders_list')" value="Get List">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <tr>
html;
        }
        elseif(strtoupper($marketplace) == "QOO10")
        {
            $html = <<<html
                    <tr>
                        <td>Choose Country</td>
                        <td>
                            <select id="country">
                                $platformcountryid_option
                            </select>
                        </td>
                    <tr>
                    <tr>
                        <td colspan="2">
                            <table style='border-collapse: collapse; width:100%; ' cellpadding="8x">
                                <colgroup><col width="10%"><col width="50%"><col width="40%"></colgroup>
                                <tr class="border_bottom">
                                    <td colspan='3'>Choose your actions to run below <br><td>
                                </tr>
                                <tr class="border_bottom">
                                    <td> 1 </td>
                                    <td>Run Default Orders Import</td>
                                    <td>
                                        <input type="button" onclick="runQoo10('default')" value="Run Default">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <tr>
html;
        }
        elseif(strtoupper($marketplace) == "FNAC")
        {
            $curr_time = mktime();
            $start_time = date("Y-m-d", $curr_time - 3600 * 24 * 14);
            $end_time = date("Y-m-d", $curr_time + 24 * 60 * 60);

            $html = <<<html
                    <tr>
                        <td>Choose Country</td>
                        <td>
                            <select id="country">
                                $platformcountryid_option
                            </select>
                        </td>
                    <tr>
                    <tr>
                        <td colspan="2">
                            <table style='border-collapse: collapse; width:100%; ' cellpadding="8x">
                                <colgroup><col width="10%"><col width="50%"><col width="40%"></colgroup>
                                <tr class="border_bottom">
                                    <td colspan='3'>Choose your actions to run below <br><td>
                                </tr>
                                <tr class="border_bottom">
                                    <td> 1 </td>
                                    <td>Run Default Orders Import</td>
                                    <td>
                                        Orders From <input id="startDate" type="text" value="$start_time" size="23"><br>
                                        To <input id="endDate" type="text" value="$end_time" size="23"> <br>
                                        <input type="button" onclick="runFNAC('default')" value="Run Default">
                                    </td>
                                </tr>
                                <tr class="border_bottom">
                                    <td> 1 </td>
                                    <td>Acknowledge orders</td>
                                    <td>
                                        <input type="button" onclick="runFNAC('acknowledge')" value="Acknowledge">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <tr>
html;
        }


        return $html;
    }

    public function _get_app_id(){
        return $this->app_id;
    }

    public function _get_lang_id(){
        return $this->lang_id;
    }
}

/* End of file marketplace_order_import.php */
/* Location: ./system/application/controllers/order/marketplace_order_import.php */
