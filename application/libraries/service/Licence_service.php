<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";
include_once "Actable_service.php";

class Licence_service extends Base_service implements Actable_service
{
    private $sl_dao;
    private $so_srv;
    private $ev_dto;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Software_licence_dao.php");
        $this->set_sl_dao(new Software_licence_dao());
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
    }

    public function init()
    {
    }

    public function get_licence_w_detail($where = array(), $option = array())
    {
        return $this->get_sl_dao()->get_licence_w_detail($where, $option);
    }

    public function get_sl_dao()
    {
        return $this->sl_dao;
    }

    public function set_sl_dao($value)
    {
        $this->sl_dao = $value;
    }

    public function get_licence_detail($where = array())
    {
        return $this->get_sl_dao()->get($where);
    }

    public function get_licence_detail_list($where = array(), $option = array())
    {
        return $this->get_sl_dao()->get_list($where, $option);
    }

    public function add_licence_detail($licence_detail_obj)
    {
        return $this->get_sl_dao()->insert($licence_detail_obj);
    }

    public function update_licence_detail($licence_detail_obj)
    {
        return $this->get_sl_dao()->update($licence_detail_obj);
    }

    public function run($dto)
    {
        if ($dto) {
            $event_id = $dto->get_event_id();
            switch ($event_id) {
                case "assign_licence":
                    $this->set_ev_dto($dto);
                    $this->assign_licence();
                    break;
                default:
            }
        } else {
            return FALSE;
        }
    }

    public function assign_licence()
    {
        $this->update_soidl(array("line_no" => $this->get_ev_dto()->get_line_no(), "item_sku" => $this->get_ev_dto()->get_sku(), "licence_key" => $this->get_ev_dto()->get_licence_key()));
    }

    public function update_soidl($vars = array())
    {
        include_once(APPPATH . "helpers/object_helper.php");
        $soidl_dao = $this->get_so_srv()->get_soidl_dao();

        if (($this->soidl = $soidl_dao->get(array("so_no" => $this->get_ev_dto()->get_so_no(), "line_no" => $this->get_ev_dto()->get_line_no(), "item_sku" => $this->get_ev_dto()->get_sku(), "licence_key" => $this->get_ev_dto()->get_licence_key())))) {
            set_value($this->soidl, $vars);
            if ($soidl_dao->update($this->soidl)) {
                $this->increment_distributed_value($this->get_ev_dto()->get_sku(), $this->get_ev_dto()->get_licence_key());
            } else {
                return FALSE;
            }
        } else {
            $soidl_obj = $soidl_dao->get();
            $soidl_obj->set_so_no($this->get_ev_dto()->get_so_no());
            set_value($soidl_obj, $vars);
            if ($soidl_dao->insert($soidl_obj)) {
                $this->increment_distributed_value($this->get_ev_dto()->get_sku(), $this->get_ev_dto()->get_licence_key());
            } else {
                return FALSE;
            }
        }

    }

    public function get_so_srv()
    {
        return $this->so_srv;
    }

    public function set_so_srv($value)
    {
        $this->so_srv = $value;
    }

    public function get_ev_dto()
    {
        return $this->ev_dto;
    }

    public function set_ev_dto($value)
    {
        $this->ev_dto = $value;
    }

    public function increment_distributed_value($sku, $key, $qty = 1)
    {
        if (!empty($sku) && !empty($key)
            && $software_licence_obj = $this->get_sl_dao()->get(array("sku" => $sku, "key" => $key))
        ) {
            $where = array("sku" => $sku, "key" => $key);
            $data = array("distributed" => $software_licence_obj->get_distributed() + $qty);
            $this->get_sl_dao()->q_update($where, $data);
        }
    }

}

/* End of file voucher_service.php */
/* Location: ./app/libraries/service/Voucher_service.php */