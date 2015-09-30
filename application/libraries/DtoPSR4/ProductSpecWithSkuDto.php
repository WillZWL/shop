<?php
class ProductSpecWithSkuDto
{
    private $psg_func_id;
    private $ps_func_id;
    private $unit_id;
    private $text;
    private $start_value;
    private $end_value;
    private $final_value;

    public function setPsgFuncId($psg_func_id)
    {
        $this->psg_func_id = $psg_func_id;
    }

    public function getPsgFuncId()
    {
        return $this->psg_func_id;
    }

    public function setPsFuncId($ps_func_id)
    {
        $this->ps_func_id = $ps_func_id;
    }

    public function getPsFuncId()
    {
        return $this->ps_func_id;
    }

    public function setUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    public function getUnitId()
    {
        return $this->unit_id;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setStartValue($start_value)
    {
        $this->start_value = $start_value;
    }

    public function getStartValue()
    {
        return $this->start_value;
    }

    public function setEndValue($end_value)
    {
        $this->end_value = $end_value;
    }

    public function getEndValue()
    {
        return $this->end_value;
    }

    public function setFinalValue($final_value)
    {
        $this->final_value = $final_value;
    }

    public function getFinalValue()
    {
        return $this->final_value;
    }

}
