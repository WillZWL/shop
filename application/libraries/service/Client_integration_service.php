<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_integration_service
{
    private $notification_email = "itsupport@eservicesgroup.net";

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->helper('url', 'string', 'object');
        $this->input = $CI->input;
        // $this->load->helper(array('url', 'object','string'));

        include_once(APPPATH . "libraries/dao/Interface_client_dao.php");
        $this->set_ic_dao(new Interface_client_dao());
        include_once(APPPATH . "libraries/dao/Client_dao.php");
        $this->set_client_dao(new Client_dao());
    }

    public function set_ic_dao(Base_dao $dao)
    {
        $this->ic_dao = $dao;
    }

    public function set_client_dao(Base_dao $dao)
    {
        $this->client_dao = $dao;
    }

    public function get_client_id_by_email($client_email)
    {
        $obj = get_client_vo(array("email" => $client_email));
        return $obj->get_id();
    }

    public function get_client_vo_by_id($client_id)
    {
        return get_client_vo(array("id" => $client_id));
    }

    public function create_client($batch_id)
    {
        if (!$batch_id) {
            $error_msg = 'Function create_client(): $batch_id cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $batch_error = 0;

            $interface_client_obj = $this->get_ic_dao()->get();
            $interface_client_obj->set_batch_id($batch_id);
            $interface_client_obj->set_ext_client_id($this->ext_client_id);
            $interface_client_obj->set_email($this->client_email);
            $interface_client_obj->set_password($this->password);
            $interface_client_obj->set_forename($this->client_name["forename"]);
            $interface_client_obj->set_surname($this->client_name["surname"]);
            $interface_client_obj->set_title($this->client_name["title"]);
            $interface_client_obj->set_address_1($this->client_addresss["address_1"]);
            $interface_client_obj->set_address_2($this->client_addresss["address_2"]);
            $interface_client_obj->set_address_3($this->client_addresss["address_3"]);
            $interface_client_obj->set_postcode($this->client_addresss["postcode"]);
            $interface_client_obj->set_city($this->client_addresss["city"]);
            $interface_client_obj->set_state($this->client_addresss["state"]);
            $interface_client_obj->set_country_id($this->client_addresss["country_id"]);
            $interface_client_obj->set_tel_3($this->client_tel);
            $interface_client_obj->set_mobile($this->client_mobile);
            $interface_client_obj->set_del_name($this->del_name);
            $interface_client_obj->set_del_company($this->del_company);
            $interface_client_obj->set_del_address_1($this->del_address["address_1"]);
            $interface_client_obj->set_del_address_2($this->del_address["address_2"]);
            $interface_client_obj->set_del_address_3($this->del_address["address_3"]);
            $interface_client_obj->set_del_postcode($this->del_address["postcode"]);
            $interface_client_obj->set_del_city($this->del_address["city"]);
            $interface_client_obj->set_del_state($this->del_address["state"]);
            $interface_client_obj->set_del_country_id($this->del_address["country_id"]);
            $interface_client_obj->set_del_tel_3($this->del_tel);
            $interface_client_obj->set_del_mobile($this->del_mobile);

            $interface_client_obj->set_batch_status('N');

            // insert into db interface_client
            $interface_client_vo = $this->get_ic_dao()->insert($interface_client_obj);

            if ($interface_client_vo === FALSE) {
                $error_msg = "Error Table: Interface_client\nError Msg: " . $this->get_ic_dao()->db->_error_message() . "\nError SQL:" . $this->get_ic_dao()->db->_error_message() . "\n" . $this->get_ic_dao()->db->last_query();
                $batch_error = 1;
            } else {
                return $interface_client_vo;
            }

            if ($batch_error) {
                $platform_id = $this->platform_id;
                $website = $this->website;
                $this->send_notification_email("BE", $platform_id, $website, $error_msg);
                throw new Exception($error_msg);
            }
        }
    }

    public function get_ic_dao()
    {
        return $this->ic_dao;
    }

    private function send_notification_email($pending_action, $platform_id, $website, $error_msg = "")
    {
        switch ($pending_action) {
            case "BE":
                $message = $error_msg;
                $title = "[$platform_id - $website] commit_platform_batch problems - BATCH_ERROR";
                break;
        }
        mail($this->notification_email, $title, $message);

    }

    public function get_interface_client_vo($client_trans_id, $batch_id = "")
    {
        if (!$client_trans_id) {
            $error_msg = 'Function get_interface_client_vo(): $client_trans_id cannot be empty.';
            throw new Exception($error_msg);
        } elseif ($client_trans_id && !$batch_id) {
            $interface_client_vo = $this->get_ic_dao()->get(array("trans_id" => $client_trans_id));
        } elseif ($client_trans_id && $batch_id) {
            $interface_client_vo = $this->get_ic_dao()->get(array("batch_id" => $batch_id, "trans_id" => $client_trans_id));
        }

        if ($interface_client_vo) {
            return $interface_client_vo;
        } else {
            $error_msg = 'Function get_interface_client_vo(): ' . "\nError Table: interface_client. client does not exist. \nBatch_id: $batch_id \nClient's trans_id: $client_trans_id";
            throw new Exception($error_msg);
        }

    }

    public function commit_client($batch_id, $iso_obj)
    {
        /* this function insert into actual client table */
        $c_dao = $this->get_client_dao();
        $ic_dao = $this->get_ic_dao();
        $c_vo = $c_dao->get();

        $ic_obj = $ic_dao->get(array("batch_id" => $batch_id, "trans_id" => $iso_obj->get_client_trans_id())); // trans_id

        //start_transaction
        $c_dao->trans_start();

        //client
        $c_obj = $c_dao->get(array("email" => $ic_obj->get_email()));
        if ($c_obj) {
            set_value($c_obj, $ic_obj);
            $c_ret = $c_dao->update($c_obj);
        } else {
            $c_obj = clone $c_vo;
            set_value($c_obj, $ic_obj);
            $c_ret = $c_dao->insert($c_obj);
        }

        if ($c_ret !== FALSE) {
            $commit_client["iso_batch_status"] = TRUE;
            $commit_client["failed_reason"] = "";
            $commit_client["c_dao"] = $c_dao;
            $commit_client["ic_dao"] = $ic_dao;
            $commit_client["ic_obj"] = $ic_obj;
            $commit_client["c_obj"] = $c_obj;
        } else {
            $commit_client["iso_batch_status"] = FALSE;
            $commit_client["failed_reason"] = __LINE__ . "client: " . $c_dao->db->_error_message();
            $commit_client["c_dao"] = $c_dao;
            $commit_client["ic_dao"] = $ic_dao;
            $commit_client["ic_obj"] = $ic_obj;
            $commit_client["c_obj"] = $c_obj;
        }
        return $commit_client;
    }

    public function get_client_dao()
    {
        return $this->client_dao;
    }

    public function update_interface_client($c_obj, $ic_obj)
    {
        if (!$c_obj || !$ic_obj) {
            $error_msg = 'Function update_interface_client(): $c_obj and $ic_obj cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $ic_dao = $this->get_ic_dao();
            $client_id = $c_obj->get_id();

            // ic_list contains a list of interface_client records without client_id
            // this loop is to set the client_id into all these records
            if ($ic_dao->update_client_id_to_interface($ic_obj->get_batch_id(), $ic_obj->get_email(), $client_id)) {
                $update_interface_client["iso_batch_status"] = TRUE;
                $update_interface_client["failed_reason"] = "";
                $update_interface_client["ic_dao"] = $ic_dao;
                $update_interface_client["ic_obj"] = $ic_obj;
            } else {
                $update_interface_client["iso_batch_status"] = FALSE;
                $update_interface_client["ic_dao"] = $ic_dao;
                $update_interface_client["ic_obj"] = $ic_obj;
                $update_interface_client["failed_reason"] = __LINE__ . "Interface_client: " . $ic_dao->db->_error_message();
            }

            return $update_interface_client;
        }
    }

    public function execute_client_trans($c_dao, $trans_type)
    {
        // this function sets the type of transation so info can be written into db
        if (!$c_dao || !$trans_type) {
            $error_msg = 'Function execute_client_trans(): $c_dao and $trans_type cannot be empty.';
            throw new Exception($error_msg);
        } elseif ($trans_type == "rollback") {
            $c_dao->trans_rollback();
        } elseif ($trans_type == "complete") {
            $c_dao->trans_complete();
        } else {
            $error_msg = 'Function execute_client_trans(): $trans_type not recognised.';
            throw new Exception($error_msg);
        }
    }

    public function set_platform_id($platform_id)
    {
        if (!$platform_id) {
            $error_msg = 'Function set_platform_id(): $platform_id cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $this->platform_id = strtoupper($platform_id);
        }
    }


// ================================= FILL IN CLIENT'S INFO ===================================================

    public function set_website($website)
    {
        if (!$website) {
            $error_msg = 'Function set_website(): $website cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $this->website = strtoupper($website);
        }
    }

    public function set_ext_client_id($ext_client_id = "")
    {
        # this is the client_id generated by respective platform (e.g. Qoo10)
        # if platform does not supply own client_id, use email
        $this->ext_client_id = $ext_client_id;
    }

    public function set_client_name($forename, $title = "", $surname = "")
    {
        if (!$forename) {
            $error_msg = 'Function set_client_name(): $forename cannot be empty.';
            throw new Exception($error_msg);
        }

        $client_name["title"] = $title;
        $client_name["forename"] = $forename;
        $client_name["surname"] = $surname;

        $this->client_name = $client_name;

    }

    public function set_client_tel($tel, $country_code = "", $area_code = "")
    {
        if (!$tel) {
            $error_msg = 'Function set_client_tel(): $tel cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $this->client_tel = trim($country_code . $area_code . $tel);
        }
    }

    public function set_client_mobile($mobile = "")
    {
        $this->client_mobile = $mobile;
    }

    public function set_client_email($client_email)
    {
        if (!$client_email) {
            $error_msg = 'Function set_client_email(): $client_email cannot be empty';
            throw new Exception($error_msg);
        } else {
            $this->client_email = $client_email;
        }
    }

    public function set_client_address($country_id, $postcode, $address_1, $address_2 = "", $address_3 = "", $city = "", $state = "")
    {
        if (!$country_id || !$postcode || !$address_1) {
            $error_msg = 'Function set_client_address(): $country_id, $postcode and $address_1 cannot be empty';
            throw new Exception($error_msg);
        } else {
            $client_addresss["address_1"] = $address_1;
            $client_addresss["address_2"] = $address_2;
            $client_addresss["address_3"] = $address_3;
            $client_addresss["city"] = $city;
            $client_addresss["state"] = $state;
            $client_addresss["postcode"] = $postcode;
            $client_addresss["country_id"] = $country_id;
            $this->client_addresss = $client_addresss;
        }
    }


// ================================= FILL IN DELIVERY INFO ===================================================

    public function set_password($password)
    {
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();

        if (!$password) {
            $error_msg = 'Function set_password(): $password cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $this->password = ($encrypt->encode($password));
        }
    }

    public function set_del_name($del_name)
    {
        if (!$del_name) {
            $error_msg = 'Function set_del_name(): $del_name cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $this->del_name = $del_name;
        }
    }

    public function set_del_tel($tel, $country_code = "", $area_code = "")
    {
        if (!$tel) {
            $error_msg = 'Function set_del_tel(): $tel cannot be empty.';
            throw new Exception($error_msg);
        } else {
            $this->del_tel = trim($country_code . $area_code . $tel);
        }
    }

    public function set_del_mobile($mobile = "")
    {

        $this->del_mobile = $mobile;
    }

    public function set_del_address($country_id, $postcode, $address_1, $address_2 = "", $address_3 = "", $city = "", $state = "")
    {
        if (!$country_id || !$postcode || !$address_1) {
            $error_msg = "Function set_del_address() - one of the following is empty: \n country_id: $country_id; \npostcode: $postcode; \naddress_1: $address_1.";
            throw new Exception($error_msg);
        } else {
            $del_address["address_1"] = $address_1;
            $del_address["address_2"] = $address_2;
            $del_address["address_3"] = $address_3;
            $del_address["city"] = $city;
            $del_address["state"] = $state;
            $del_address["postcode"] = $postcode;
            $del_address["country_id"] = $country_id;
            $this->del_address = $del_address;
        }
    }


// ===========================================

    public function set_del_company($company_name = "")
    {
        $this->del_company = $company_name;
    }

    // private function is_valid_contact_number($country_code="", $area_code="", $tel="")
    // {
    //  if (
    //          (!$country_code && ctype_digit(str_replace($valid_char, '', $country_code))) &&
    //          (!$area_code && ctype_digit(str_replace($valid_char, '', $area_code))) &&
    //          (!$tel && ctype_digit(str_replace($valid_char, '', $tel)))
    //      )
    //  {
    //      return FALSE;
    //  }
    //  return TRUE;
    // }

    // private function is_valid_date($date)
    // {
    //  $explode_date = explode('-', (date('d-m-Y', strtotime($date))));
    //  $check_valid = checkdate($explode_date[1], $explode_date[0], $explode_date[2]);
    //  if(!$check_valid || $explode_date[2] == 1970)
    //  {
    //      return FALSE;
    //  }
    //  return TRUE;

    // }

}

