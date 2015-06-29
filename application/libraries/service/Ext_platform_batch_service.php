<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once "Base_batch_service.php";

class Ext_platform_batch_service extends Base_batch_service
{
    private $product_serivce;
    private $config_service;

    public function __construct()
    {
        parent::__construct();

        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->set_product_service(new Product_service());
    }

    public function get_product_service()
    {
        return $this->product_service;
    }

    public function set_product_service($value)
    {
        $this->product_service = $value;
    }

    public function gen_shipping_override($platform_id = 'AMUK')
    {
        $data_out =
            $this->get_product_service()->get_product_shipping_override_info($platform_id,
                'Product_shipping_override_dto');

        if (count($data_out) <= 0)
        {
            return;  // Nothing is required to do.
        }

        $out_xml = new Vo_to_xml($data_out, '');
        $out_csv = new Xml_to_csv('', APPPATH . 'data/shipping_override_xml2csv.txt', TRUE, ',');



        $file_content = $this->get_dex()->convert($out_xml, $out_csv);

        $filename = "shippingoverride_".date("YmdHis").".xls";

        $local_path = $this->get_config()->value_of('INT.SHIP_OVERRIDE.PATH.' . $platform_id);

        if (!is_dir($local_path))
        {
            $ret = mkdir($local_path, "0755");
            if($ret === FALSE)
            {
                $reason[] = 'Cannot Create Directory ' . $local_path;
            }
        }

        $full_filename = $local_path . '/' . $filename;

        if ($fhandler = fopen($full_filename, 'w'))
        {
            fwrite($fhandler, $file_content);
            fclose($fhandler);
        }
        else
        {
            $reason[] = "Failed to open file for writing";
        }

        if (count($reason))
        {
            print_r($reason);
            exit(1);
        }

        $remote_path =  $this->get_config()->value_of('INT.SHIP_OVERRIDE.REMOTE_PATH.' . $platform_id);

        if ($remote_path)
        {
            $params['remote_path'] = $remote_path;
        }

        $params['filename'] = $filename;
        $params['hostname_key'] = 'IXTENS_' . $platform_id;
        $params['local_path'] = $local_path;

        print_r($this->ftp_upload($params));
    }
}
/* End of file fulfillment_batch_service.php */
/* Location: ./system/application/libraries/service/Fulfillment_batch_service.php */