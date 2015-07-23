<?php

class Courier_import extends MY_Controller
{
    private $app_id = "SUP0017";
    private $lang_id = "en";
    private $error = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('supply/courier_import_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/context_config_service');
        $this->load->library('dao/integrated_order_fulfillment_dao');
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        define('DATAPATH', $this->context_config_service->value_of("data_path"));
        $this->this_year = date('Y');
        $this->this_month = date('M');

        if ($this->input->post('posted')) {
            $result = $this->import_data_process();

            if ($result === FALSE) {
                $_SESSION["NOTICE"] = "Problem with import: {$this->error}";
            } else {
                if ($content = file_get_contents(DATAPATH . "import/recommended_courier/{$this->this_year}/{$this->this_month}/processed/courier_import_record_{$this->timestamp}.csv")) {
                    header("Content-type: text/csv");
                    header("Cache-Control: no-store, no-cache");
                    header("Content-Disposition: attachment; filename=\"record_{$this->timestamp}.csv\"");
                    echo $content;
                    die();
                } else {
                    $_SESSION["NOTICE"] = "Cannot retrieve courier_import_record_{$this->timestamp}.csv";
                }
            }
            $data["notice"] = notice($lang);
        }

        $this->load->view('supply/courier_import/index', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    private function import_data_process($upload_file_field_name = "")
    {
        if (!$upload_file_field_name)
            $upload_file_field_name = "courier_process_file";

        $upload_file = $_FILES[$upload_file_field_name]["name"];
        $main_upload_path = DATAPATH . "/import/recommended_courier";
        $config['upload_path'] = $main_upload_path . "/" . $this->this_year . "/" . $this->this_month;
        $config['allowed_types'] = 'csv';
//firefox will put "" to the mime type, so remove it
        $_FILES[$upload_file_field_name]["type"] = trim($_FILES[$upload_file_field_name]["type"], '"');
        if (strpos($_FILES[$upload_file_field_name]["type"], "csv")
            || $_FILES[$upload_file_field_name]["type"] == "application/x-octet-stream"
            || $_FILES[$upload_file_field_name]["type"] == "application/x-download"
        ) {
            // sometimes firefox made it into "vnd.csv"
            $_FILES[$upload_file_field_name]["type"] = 'application/csv';
        }
        $this->timestamp = date('Ymd_His');
        $config['file_name'] = $this->timestamp;
        $config['overwrite'] = FALSE;
        $config['is_image'] = FALSE;
        $this->load->library('upload', $config);

        # check if folder exists; if not, create
        $this->create_folder($main_upload_path);

        if (!empty($upload_file)) {
            if ($this->upload->do_upload($upload_file_field_name)) {
                $res = $this->upload->data();

                $result = $this->import_courier_data($res["full_path"]);
                return $result;
            } else {
                if ($this->upload->error_msg) {
                    $this->error = "\n" . implode("\n", $this->upload->error_msg);
                } else {
                    $this->error = "\n" . __LINE__ . " cannot upload file";
                }
            }
        } else {
            $this->error = "\n" . __LINE__ . " Upload file is empty";
        }

        return FALSE;

    }

    protected function create_folder($upload_path)
    {
        if (!file_exists($upload_path . "/" . $this->this_year)) {
            mkdir($upload_path . "/" . $this->this_year, 0775, true);
        }
        if (!file_exists($upload_path . "/" . $this->this_year . "/" . $this->this_month)) {
            mkdir($upload_path . "/" . $this->this_year . "/" . $this->this_month, 0775);
            mkdir($upload_path . "/" . $this->this_year . "/" . $this->this_month . "/processed", 0775);
            // mkdir($upload_path . "/" . $this_year . "/" . $this->this_month . "/processed_with_error", 0775);
        }
    }

    private function import_courier_data($filepath)
    {
        if (!file_exists($filepath)) {
            $this->error = "courier_import.php " . __LINE__ . " No file exists for $filepath";
            return FALSE;
        } else {
            if ($fp = fopen($filepath, 'r')) {
                $i = 0;
                $iof_dao = $this->integrated_order_fulfillment_dao;

                // we record each so_no's success status in a file
                $fp_record = fopen(DATAPATH . "import/recommended_courier/{$this->this_year}/{$this->this_month}/processed/courier_import_record_{$this->timestamp}.csv", 'w');
                fwrite($fp_record, "so_no, sku, rec_courier, processing_remarks\r\n");      #headers
                while (($content = fgetcsv($fp)) !== FALSE) {
                    $so_no = $content[0];
                    $rec_courier = $content[1]; // recommended courier

                    # row is blank, skip
                    if (empty($so_no) && empty($rec_courier))
                        continue;

                    if ($rec_courier == null || $rec_courier == "") {
                        // if no courier input, no point to continue
                        $record_string = "$so_no, , $rec_courier, N.A.\r\n";
                        fwrite($fp_record, $record_string);
                        continue;
                    }

                    if (!($iof_obj_list = $iof_dao->get_list(array("so_no" => $so_no)))) {
                        $record_string = "$so_no, , $rec_courier, so_no does not exist\r\n";
                        fwrite($fp_record, $record_string);
                    } else {
                        foreach ($iof_obj_list as $key => $iof_obj) {
                            $original_rec_courier = $iof_obj->get_rec_courier();
                            if (!$original_rec_courier) {
                                $iof_obj->set_rec_courier($rec_courier);
                                if ($iof_dao->update($iof_obj) === false) {
                                    $dberror = __LINE__ . "courier_import.php db error " . $iof_dao->db->_error_message();
                                    $record_string = "$so_no, {$iof_obj->get_sku()}, $rec_courier, " . str_replace(',', ' ', $dberror) . "\r\n";
                                    fwrite($fp_record, $record_string);
                                } else {
                                    // totally no problem
                                    $record_string = "$so_no, {$iof_obj->get_sku()}, $rec_courier, success\r\n";
                                    fwrite($fp_record, $record_string);
                                }
                            } else {
                                // if rec_courier has been filled, check if same as current uploaded
                                if ($original_rec_courier == $rec_courier) {
                                    // same as before; no updates
                                    $record_string = "$so_no, {$iof_obj->get_sku()}, $rec_courier, same courier has been recorded before\r\n";
                                    fwrite($fp_record, $record_string);
                                } else {
                                    $iof_obj->set_rec_courier($rec_courier);
                                    if ($iof_dao->update($iof_obj) === false) {
                                        $dberror = __LINE__ . "courier_import.php db error " . $iof_dao->db->_error_message();
                                        $record_string = "$so_no, {$iof_obj->get_sku()}, $rec_courier, " . str_replace(',', ' ', $dberror) . "\r\n";
                                        fwrite($fp_record, $record_string);
                                    } else {
                                        // inform user recommended courier changed
                                        $record_string = "$so_no, {$iof_obj->get_sku()}, $rec_courier, courier updated from $original_rec_courier\r\n";
                                        fwrite($fp_record, $record_string);
                                    }
                                }
                            }
                        }
                    }

                    $i++;
                }
                fwrite($fp_record, "Total order# processed, $i\r\n");
                fclose($fp_record);
                fclose($fp);
                return TRUE;
            } else {
                $this->error = "courier_import.php " . __LINE__ . " Could not get content for $filepath";
                return FALSE;
            }
        }
    }
}
