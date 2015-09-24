<?php
namespace ESG\Panther\Service;

class SoPriorityScoreService extends BaseService
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
    }


    public function hitMarginRule($so_no, $biz_type = 'ONLINE', $number_of_days = 0, $is_highlight_rule = false, $profit_margin_rule = null)
    {
        $margin_score = [];
        $margin_threshold = [];
        $margin_threshold_highlight = 0;
        $order_profit_margin = 0;

        # we perform a query only if profit margin was not supplied from method caller
        if ($profit_margin_rule === null) {
            $profit_margin_rule = $this->getDao('So')->getProfitMargin($so_no);
            $profit_margin_rule = $profit_margin_rule["order_margin"];
        }

        /* prepare data for profit margin checking */
        if ($profit_margin_rule !== null) {
            $order_profit_margin = $profit_margin_rule * 100;
            if (!defined('PRIORITY_SCORE_MARGIN_WEBSITE')) {
                $margin_threshold_highlight_obj = $this->getDao('SubjectDomain')->get(["subject" => "PRIORITY_THRESHOLD.MARGIN.COLOR"]);
                if ($margin_threshold_highlight_obj) {
                    SoPriorityScoreService::$margin_threshold_highlight = $margin_threshold_highlight_obj->getValue();
                }

                for ($i = 0; $i < sizeof(SoPriorityScoreService::$margin_score); $i++) {
                    $margin_score_obj = $this->getDao('SubjectDomain')->get(["subject" => "PRIORITY_SCORE.MARGIN.WEBSITE.LEVEL" . ($i + 1)]);
                    $margin_and_threshold = explode("||", $margin_score_obj->getValue());
                    SoPriorityScoreService::$margin_score[$i] = $margin_and_threshold[0];
                    SoPriorityScoreService::$margin_threshold[$i] = $margin_and_threshold[1];
                }
                if (($margin_score_obj) && ($margin_threshold_highlight_obj)) {
                    DEFINE('PRIORITY_SCORE_MARGIN_WEBSITE', 1);
                }
            }
            for ($i = 0; $i < sizeof(SoPriorityScoreService::$margin_score); $i++) {
                $margin_score[$i] = SoPriorityScoreService::$margin_score[$i];
                $margin_threshold[$i] = SoPriorityScoreService::$margin_threshold[$i];
            }
            $margin_threshold_highlight = SoPriorityScoreService::$margin_threshold_highlight;
        }

//for margin only, return 0 if requirement not meet
        if (!$is_highlight_rule) {
            if (($biz_type == "ONLINE") || ($biz_type == "MOBILE") || ($biz_type == "OFFLINE")) {
                for ($i = 0; $i < sizeof(SoPriorityScoreService::$margin_score); $i++) {
                    if ($order_profit_margin > $margin_threshold[$i]) {
                        return $margin_score[$i];
                    }
                }
            }
            return 0;
        }

//for highlight
//Level 2 Color rule
        if ($is_highlight_rule) {
            if (($order_profit_margin > self::MARGIN_COLOR_LEVEL_2) && ($order_profit_margin <= $margin_threshold_highlight) && ($biz_type != 'SPECIAL')) {
                if ($number_of_days > self::MARGIN_NUMBER_OF_DAYS) {
                    return self::HIGHLIGHT_LEVEL_2;
                }
            }
        }

        if (($order_profit_margin > $margin_threshold_highlight)
            && ($biz_type != 'SPECIAL')
        ) {
            if ($is_highlight_rule) {
//Level 1 Color rule
                if ((($number_of_days > 0) && ($number_of_days > self::MARGIN_NUMBER_OF_DAYS)
                        && ($number_of_days < self::MARGIN_NUMBER_OF_DAYS_UPPER))
                    || ($number_of_days <= self::MARGIN_NUMBER_OF_DAYS)
                )
                    return self::HIGHLIGHT_LEVEL_1;
            } else {
                if (($number_of_days > 0) && ($number_of_days > self::MARGIN_NUMBER_OF_DAYS)
                    && ($number_of_days < self::MARGIN_NUMBER_OF_DAYS_UPPER)
                )
                    return $margin_score;
            }
        }

        return 0;
    }

    public function getPriorityScore($so_no)
    {
        return $this->getDao('SoPriorityScore')->get(["so_no" => $so_no]);
    }

    public function getPriorityScoreHistoryList($where = [], $option = [])
    {
        return $this->getDao('SoPriorityScoreHistory')->getList($where, $option);
    }

    public function insertSops($so_no, $priority_score)
    {
        $vo = $this->getDao('SoPriorityScore')->get();
        $vo->setSoNo($so_no);
        $vo->setScore($priority_score);
        $vo->setStatus(1);
        $this->getDao('SoPriorityScore')->insert($vo);
    }

    public function updateSops($so_no, $priority_score)
    {
        $vo = $this->getDao('SoPriorityScore')->get();
        $dao = clone $vo;
        $dao->setSoNo($so_no);
        $dao->setScore($priority_score);
        $dao->setStatus(1);
        $this->getDao('SoPriorityScore')->setCreate($dao);
        $this->getDao('SoPriorityScore')->update($dao);
    }
}


