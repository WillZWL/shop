<?php

include_once "Base_service.php";

class So_priority_score_service extends Base_service
{
    const HIGHLIGHT_LEVEL_1 = 1;
    const HIGHLIGHT_LEVEL_2 = 2;

    const MARGIN_NUMBER_OF_DAYS = 5;
    const MARGIN_NUMBER_OF_DAYS_UPPER = 30;
//  const MARGIN_COLOR_LEVEL_1 = 25; //this is store in the subject_domain (PRIORITY_THRESHOLD.MARGIN.WEBSITE)
    const MARGIN_COLOR_LEVEL_2 = 20;
//  const MAXIMUM_MARGIN_LEVEL = 4;
    public static $margin_threshold_highlight = 0;
    public static $margin_score = array(0, 0, 0, 0);
    public static $margin_threshold = array(0, 0, 0, 0);

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/So_priority_score_dao.php");
        $this->set_dao(new So_priority_score_dao());
        include_once(APPPATH."libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH."libraries/dao/So_priority_score_history_dao.php");
        $this->set_sops_history_dao(new So_priority_score_history_dao());
        include_once APPPATH."libraries/service/Subject_domain_service.php";
        $this->set_sub_domain_srv(new Subject_domain_service());
    }

    public function hit_margin_rule($so_no, $biz_type = 'ONLINE', $number_of_days = 0, $is_highlight_rule = false, $profit_margin_rule = null)
    {
        $margin_score = array();
        $margin_threshold = array();
        $margin_threshold_highlight = 0;
        $order_profit_margin = 0;

        # we perform a query only if profit margin was not supplied from method caller
        if ($profit_margin_rule === null)
        {
            $profit_margin_rule = $this->get_so_dao()->get_profit_margin($so_no);
            $profit_margin_rule = $profit_margin_rule["order_margin"];
        }

        /* prepare data for profit margin checking */
        if ($profit_margin_rule !== null)
        {
            // $order_profit_margin = $profit_margin_rule["order_margin"] * 100;
            $order_profit_margin = $profit_margin_rule * 100;
            if (!defined('PRIORITY_SCORE_MARGIN_WEBSITE'))
            {
                $margin_threshold_highlight_obj = $this->get_sub_domain_srv()->get(array("subject"=>"PRIORITY_THRESHOLD.MARGIN.COLOR"));
                if ($margin_threshold_highlight_obj)
                {
                    So_priority_score_service::$margin_threshold_highlight = $margin_threshold_highlight_obj->get_value();
                }

                for($i=0;$i<sizeof(So_priority_score_service::$margin_score);$i++)
                {
                    $margin_score_obj = $this->get_sub_domain_srv()->get(array("subject"=>"PRIORITY_SCORE.MARGIN.WEBSITE.LEVEL" . ($i + 1)));
                    $margin_and_threshold = explode("||", $margin_score_obj->get_value());
                    So_priority_score_service::$margin_score[$i] = $margin_and_threshold[0];
                    So_priority_score_service::$margin_threshold[$i] = $margin_and_threshold[1];
                }
                if (($margin_score_obj) && ($margin_threshold_highlight_obj))
                {
                    DEFINE('PRIORITY_SCORE_MARGIN_WEBSITE', 1);
                }
            }
            for($i=0;$i<sizeof(So_priority_score_service::$margin_score);$i++)
            {
                $margin_score[$i] = So_priority_score_service::$margin_score[$i];
                $margin_threshold[$i] = So_priority_score_service::$margin_threshold[$i];
            }
            $margin_threshold_highlight = So_priority_score_service::$margin_threshold_highlight;
        }

//for margin only, return 0 if requirement not meet
        if (!$is_highlight_rule)
        {
            if (($biz_type == "ONLINE") || ($biz_type == "MOBILE") || ($biz_type == "OFFLINE"))
            {
                for($i=0;$i<sizeof(So_priority_score_service::$margin_score);$i++)
                {
                    if ($order_profit_margin > $margin_threshold[$i])
                    {
                        return $margin_score[$i];
                    }
                }
            }
            return 0;
        }

//for highlight
//Level 2 Color rule
        if ($is_highlight_rule)
        {
            if (($order_profit_margin > self::MARGIN_COLOR_LEVEL_2) && ($order_profit_margin <= $margin_threshold_highlight) && ($biz_type != 'SPECIAL'))
            {
                if ($number_of_days > self::MARGIN_NUMBER_OF_DAYS)
                {
                    return self::HIGHLIGHT_LEVEL_2;
                }
            }
        }

        if (($order_profit_margin > $margin_threshold_highlight)
            && ($biz_type != 'SPECIAL'))
        {
            if ($is_highlight_rule)
            {
//Level 1 Color rule
                if ((($number_of_days > 0) && ($number_of_days > self::MARGIN_NUMBER_OF_DAYS)
                    && ($number_of_days < self::MARGIN_NUMBER_OF_DAYS_UPPER))
                    || ($number_of_days <= self::MARGIN_NUMBER_OF_DAYS))
                    return self::HIGHLIGHT_LEVEL_1;
            }
            else
            {
                if (($number_of_days > 0) && ($number_of_days > self::MARGIN_NUMBER_OF_DAYS)
                    && ($number_of_days < self::MARGIN_NUMBER_OF_DAYS_UPPER))
                    return $margin_score;
            }
        }

        return 0;
    }

    public function get_priority_score($so_no)
    {
        return $this->get_dao()->get(array("so_no"=>$so_no));
    }

    public function get_priority_score_history_list($where = array(), $option = array())
    {
        return $this->get_sops_history_dao()->get_list($where, $option);
    }

    public function get_sops_history_dao()
    {
        return $this->sops_history_dao;
    }

    public function set_sops_history_dao(Base_dao $dao)
    {
        $this->sops_history_dao = $dao;
    }

    public function insert_sops($so_no, $priority_score)
    {
        $vo = $this->get_dao()->get();
        $vo->set_so_no($so_no);
        $vo->set_score($priority_score);
        $vo->set_status(1);
        $this->get_dao()->insert($vo);
    }

    public function update_sops($so_no, $priority_score)
    {
        $vo = $this->get_dao()->get();
        $dao = clone $vo;
        $dao->set_so_no($so_no);
        $dao->set_score($priority_score);
        $dao->set_status(1);
        $this->get_dao()->set_create($dao);
        $this->get_dao()->update($dao);
    }

    public function set_so_dao($dao)
    {
        $this->so_dao = $dao;
        return $dao;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_sub_domain_srv($srv)
    {
        $this->sub_domain_srv = $srv;
        return $srv;
    }

    public function get_sub_domain_srv()
    {
        return $this->sub_domain_srv;
    }
}

/* End of file so_priority_score_service.php */
/* Location: ./app/libraries/service/So_priority_score_service.php */