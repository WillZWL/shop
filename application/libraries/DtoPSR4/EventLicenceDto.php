<?php
class EventLicenceDto
{
    private $event_id;
    private $so_no;
    private $line_no;
    private $sku;
    private $licence_key;

    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
    }

    public function getEventId()
    {
        return $this->event_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setLineNo($line_no)
    {
        $this->line_no = $line_no;
    }

    public function getLineNo()
    {
        return $this->line_no;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setLicenceKey($licence_key)
    {
        $this->licence_key = $licence_key;
    }

    public function getLicenceKey()
    {
        return $this->licence_key;
    }

}
