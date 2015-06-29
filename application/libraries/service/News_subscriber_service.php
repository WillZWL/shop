<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class News_subscriber_service extends Base_service
{
    private $v_srv;
    private $e_srv;
    private $config;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/News_subscriber_dao.php");
        $this->set_dao(new News_subscriber_dao());
        include_once(APPPATH . "libraries/service/Validation_service.php");
        $this->set_v_srv(new Validation_service());
        include_once(APPPATH . "libraries/service/Event_service.php");
        $this->set_e_srv(new Event_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
    }

    public function add_subscriber($email = '')
    {
        $v_srv = $this->get_v_srv();
        $v_rules[0] = array('not_empty', 'valid_email');
        $v_srv->set_rules($v_rules);

        $v_srv->set_data('trunks@trunks.com');

        try {
            $rs = $v_srv->run();
        } catch (Exception $e) {
            return FALSE;
            //$e_srv->fire_event($func, $dto_obj);
        }

        $where = array('email' => $email);

        $sb = $this->get_dao()->get($where);

        if ($sb) {
            $sb->set_status(1);
            $this->get_dao()->update($sb);
        } else {
            $sb = new News_subscriber_vo();
            $sb->set_email($email);
            $sb->set_status(1);
            $this->get_dao()->insert($sb);
        }

        $email_dto = $this->_get_email_dto();
        $email_dto->set_event_id('news_subscription');
        $email_dto->set_tpl_id('news_subscription');
        $email_dto->set_mail_to($email);
        $email_dto->set_mail_from('lindsay@valuebasket.com ');
        $email_dto->set_replace(array('default_url' => $this->get_config()->value_of("default_url")));
        $this->get_e_srv()->fire_event($email_dto);

        return TRUE;
    }

    public function get_v_srv()
    {
        return $this->v_srv;
    }

    public function set_v_srv($vs)
    {
        $this->v_srv = $vs;
    }

    private function _get_email_dto()
    {
        include_once APPPATH . "libraries/dto/event_email_dto.php";
        return new Event_email_dto();
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_e_srv()
    {
        return $this->e_srv;
    }

    public function set_e_srv($value)
    {
        $this->e_srv = $value;
    }
}

/* End of file news_subscriber_service.php */
/* Location: ./app/libraries/service/News_subscriber_service.php */