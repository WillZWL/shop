<?php
class RequestOrderCompensationDto
{
    private $compensation_id;
    private $so_no;
    private $platform_id;
    private $item_sku;
    private $prod_name;
    private $note;
    private $request_on;

    public function getCompensationId()
    {
        return $this->compensation_id;
    }

    public function setCompensationId($value)
    {
        $this->compensation_id = $value;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setSoNo($value)
    {
        $this->so_no = $value;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setItemSku($value)
    {
        $this->item_sku = $value;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote($value)
    {
        $this->note = $value;
    }

    public function getRequestOn()
    {
        return $this->request_on;
    }

    public function setRequestOn($value)
    {
        $this->request_on = $value;
    }

}
