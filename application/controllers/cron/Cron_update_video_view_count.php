<?php

class Cron_update_video_view_count extends MY_Controller
{
    const BATCH_REQUEST_MAX_OPERATION = 50;

    function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/video_model');
        $this->load->model('integration/batch_model');
        $this->load->library('service/batch_youtube_video_service');
    }

    function index($debug = 0)
    {
        $func = "youtube_video";
        $batch_vo = $this->batch_model->batch_service->get_dao()->get();

        $video_list = $this->video_model->get_list(array("status" => "1"));

        $vid = array();
        foreach ($video_list as $obj) {
            if (!in_array($obj->get_ref_id(), $vid)) {
                $vid[] = $obj->get_ref_id();
            }
        }

        if ($vid) {
            foreach ($vid as $key => $val) {
                $video_ids[] = $vid[$key];

                if (($key + 1) % self::BATCH_REQUEST_MAX_OPERATION == 0 || ($key + 1) % count($vid) == 0) {
                    $batch_obj = clone $batch_vo;
                    $batch_obj->set_func_name($func);
                    $batch_obj->set_status("N");
                    $batch_obj->set_listed(1);
                    $batch_obj->set_remark("youtube_video_" . date("YmdHi"));
                    $this->batch_model->batch_service->get_dao()->insert($batch_obj);

                    $entires = $this->batch_model->batch_youtube_video_service->query_by_video_ids($video_ids);
                    $batch_obj->set_status("P");
                    $this->batch_model->batch_service->get_dao()->update($batch_obj);

                    if ($this->_update_view_count($entires, $batch_obj->get_id())) {
                        $batch_obj->set_status("BE");
                        $batch_obj->set_end_time(date("Y:m:d H:i:s"));
                        $this->batch_model->batch_service->get_dao()->update($batch_obj);
                    } else {
                        $batch_obj->set_status("C");
                        $batch_obj->set_end_time(date("Y:m:d H:i:s"));
                        $this->batch_model->batch_service->get_dao()->update($batch_obj);
                    }
                    //print '<pre>' . print_r($entires, true) . '</pre>';
                    $video_ids = array();
                }
            }
        }
    }

    private function _update_view_count($entires, $batch_id)
    {
        $yt_video_vo = $this->batch_youtube_video_service->get_yt_dao()->get();

        foreach ($entires as $video) {
            $v_list = $this->video_model->get_list(array("ref_id" => $video->id));
            foreach ($v_list as $obj) {
                $obj->set_view_count($video->statistics['viewCount']);
                if (!$this->video_model->update($obj)) {
                    $yt_video_obj = clone($yt_video_vo);
                    $yt_video_obj->set_batch_id($batch_id);
                    $yt_video_obj->set_sku($obj->get_sku());
                    $yt_video_obj->set_ref_id($video->id);
                    $yt_video_obj->set_view_count($video->statistics['viewCount']);
                    $yt_video_obj->set_batch_status('F');
                    $yt_video_obj->set_failed_reason($this->db->_error_message());

                    $this->batch_youtube_video_service->get_yt_dao()->insert($yt_video_obj);

                    return false;
                } else {
                    $yt_video_obj = clone($yt_video_vo);
                    $yt_video_obj->set_batch_id($batch_id);
                    $yt_video_obj->set_sku($obj->get_sku());
                    $yt_video_obj->set_ref_id($video->id);
                    $yt_video_obj->set_view_count($video->statistics['viewCount']);
                    $yt_video_obj->set_batch_status('S');

                    if (!$this->batch_youtube_video_service->get_yt_dao()->insert($yt_video_obj)) {
                        return false;
                    }
                }
            }
        }
    }
}



