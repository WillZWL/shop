<?php
class RmaFcVo extends \BaseVo
{
    private $cid;
    private $rma_fc;

    protected $primary_key = ['cid'];
    protected $increment_field = '';

    public function setCid($cid)
    {
        if ($cid !== null) {
            $this->cid = $cid;
        }
    }

    public function getCid()
    {
        return $this->cid;
    }

    public function setRmaFc($rma_fc)
    {
        if ($rma_fc !== null) {
            $this->rma_fc = $rma_fc;
        }
    }

    public function getRmaFc()
    {
        return $this->rma_fc;
    }

}
