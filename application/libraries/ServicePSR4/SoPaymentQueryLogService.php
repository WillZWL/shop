<?php
namespace ESG\Panther\Service;
use ESG\Panther\Dao\SoPaymentQueryLogDao;

class SoPaymentQueryLogService extends BaseService
{
    public function __construct() {
        parent::__construct();
        $this->setDao(new SoPaymentQueryLogDao());
    }

    public function add_log($so_no, $type, $text) {
        $this->addLog($so_no, $type, $text);
    }

    public function addLog($so_no, $type, $text) {
        $vo = $this->get();
        $vo->setSoNo($so_no);
        $vo->setTextType($type);
        $vo->setText($text);
        $this->getDao()->insert($vo);
    }
}
