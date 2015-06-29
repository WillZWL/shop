<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";
include_once "Actable_service.php";

class Voucher_service extends Base_service implements Actable_service
{
    private $vd_dao;
    private $so_srv;
    private $ev_dto;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Voucher_dao.php");
        $this->set_dao(new Voucher_dao());
        include_once(APPPATH."libraries/dao/Voucher_detail_dao.php");
        $this->set_vd_dao(new Voucher_detail_dao());
        include_once(APPPATH."libraries/service/So_service.php");
        $this->set_so_srv(new So_service());
    }

    public function init()
    {
    }

    public function get_voucher_w_detail($where=array(), $option=array())
    {
        return $this->get_dao()->get_voucher_w_detail($where, $option);
    }

    public function set_vd_dao($value)
    {
        $this->vd_dao = $value;
    }

    public function get_vd_dao()
    {
        return $this->vd_dao;
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

    public function get_voucher_detail($where=array())
    {
        return $this->get_vd_dao()->get($where);
    }

    public function get_voucher_detail_list($where=array(), $option=array())
    {
        return $this->get_vd_dao()->get_list($where, $option);
    }

    public function add_voucher_detail($voucher_detail_obj)
    {
        return $this->get_vd_dao()->insert($voucher_detail_obj);
    }

    public function update_voucher_detail($voucher_detail_obj)
    {
        return $this->get_vd_dao()->update($voucher_detail_obj);
    }

    public function update_soext($vars = array())
    {
        include_once(APPPATH."helpers/object_helper.php");
        $soext_dao = $this->get_so_srv()->get_soext_dao();
        if ($this->soext || ($this->soext = $soext_dao->get(array("so_no"=>$this->get_ev_dto()->get_so_no()))))
        {
            set_value($this->soext, $vars);
            $soext_dao->update($this->soext);
        }
        else
        {
            $soext_obj = $soext_dao->get();
            set_value($soext_obj, $vars);
            $soext_obj->set_so_no($this->get_ev_dto()->get_so_no());
            $this->soext = $soext_dao->insert($soext_obj);
        }
    }

    public function increment_distributed_value($id)
    {
        if($voucher_detail_obj = $this->get_vd_dao()->get(array("id"=>$id)))
        {
            $voucher_detail_obj->set_distributed($voucher_detail_obj->get_distributed()+1);
            $this->get_vd_dao()->update($voucher_detail_obj);
        }
    }

    public function assign_voucher()
    {
        $this->update_soext(array("voucher_code"=>$this->get_ev_dto()->get_voucher_code(), "voucher_detail_id"=>$this->get_ev_dto()->get_voucher_detail_id()));
        $this->increment_distributed_value($this->get_ev_dto()->get_voucher_detail_id());
    }

    public function run($dto)
    {
        if ($dto)
        {
            $event_id = $dto->get_event_id();
            switch ($event_id)
            {
                case "assign_voucher":
                    $this->set_ev_dto($dto);
                    $this->assign_voucher();
                    break;
                default:
            }
        }
        else
        {
            return FALSE;
        }
    }

}

/* End of file voucher_service.php */
/* Location: ./app/libraries/service/Voucher_service.php */