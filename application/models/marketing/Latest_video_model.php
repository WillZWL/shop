<?php
class Latest_video_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/latest_video_service');
        $this->load->library('service/category_service');
        $this->load->library('service/selling_platform_service');
    }

    public function __autoload()
    {
        $this->latest_video_service->get_dao()->include_vo();
    }

    public function get_count($catid="", $mode="", $platform="", $type="", $src="")
    {
        return $this->latest_video_service->get_count($catid, $mode, $platform, $type, $src);

    }

    public function get_vo()
    {
        return $this->latest_video_service->get_dao()->get();
    }

    public function get_latest_video($catid="", $rank="", $v_type="", $platform="", $src="")
    {
        return $this->latest_video_service->get_latest_video($catid, $rank, $v_type, $platform, $src);
    }

    public function insert($obj)
    {
        return $this->latest_video_service->insert($obj);
    }

    public function update($obj)
    {
        return $this->latest_video_service->update($obj);
    }

    public function get_video_list($where=array(), $option=array())
    {
        return $this->latest_video_service->get_video_list($where, $option);
    }

    public function get_video_list_total($where=array(),$option = array())
    {
        $option["num_rows"] = 1;
        return $this->latest_video_service->get_video_list_total($where, $option);
    }

    public function get_list_w_name($catid="",$mode="",$l_type="LV",$v_type="",$platform="",$src="",$r_type="object")
    {
        // l_type: listing type / v_type: video type / r_type: return type
        return $this->latest_video_service->get_list_w_name($catid, $mode, $l_type, $v_type, $platform, $src, $r_type);
    }

    public function delete_bs($where=array())
    {
        return $this->latest_video_service->delete_bs($where);
    }

    public function trans_start()
    {
        $this->latest_video_service->trans_start();
    }

    public function trans_complete()
    {
        $this->latest_video_service->trans_complete();
    }

    public function get_cat_list_index($where, $option, $type="LV")
    {
        $result =  $this->latest_video_service->get_dao()->get_index_list($where,$option,$type);
        $count = $this->latest_video_service->get_dao()->get_index_list($where,array("num_rows"=>1),$type);
        return array("list"=>$result, "total"=>$count);
    }

    public function get_display_list($catid="", $type="", $src="")
    {
        return $this->latest_video_service->display_list($catid, $type, $src);
    }

    public function get_list_limit()
    {
        return $this->latest_video_service->get_limit();
    }

    public function gen_listing()
    {
        $this->latest_video_service->gen_listing();
    }

    public function get_platform_id_list($where,$option)
    {
        return $this->selling_platform_service->get_list($where, $option);
    }

    public function get_cat_list($where=array(), $option=array())
    {
        return $this->category_service->get_list($where, $option);
    }

    public function get_cat_num_rows($where=array(), $option=array())
    {
        return $this->category_service->get_num_rows($where, $option);
    }
}
?>