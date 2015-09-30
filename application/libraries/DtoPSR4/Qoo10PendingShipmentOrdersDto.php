<?php
class Qoo10PendingShipmentOrdersDto
{
    private $so_no;
    private $platform_order_id;
    private $txn_id;
    private $ext_item_cd;
    private $item_count;
    private $courier_id;
    private $courier_name;
    private $tracking_no;
    private $dispatch_date;
    private $platform_country_id;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setPlatformOrderId($platform_order_id)
    {
        $this->platform_order_id = $platform_order_id;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setTxnId($txn_id)
    {
        $this->txn_id = $txn_id;
    }

    public function getTxnId()
    {
        return $this->txn_id;
    }

    public function setExtItemCd($ext_item_cd)
    {
        $this->ext_item_cd = $ext_item_cd;
    }

    public function getExtItemCd()
    {
        return $this->ext_item_cd;
    }

    public function setItemCount($item_count)
    {
        $this->item_count = $item_count;
    }

    public function getItemCount()
    {
        return $this->item_count;
    }

    public function setCourierId($courier_id)
    {
        $this->courier_id = $courier_id;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCourierName($courier_name)
    {
        $this->courier_name = $courier_name;
    }

    public function getCourierName()
    {
        return $this->courier_name;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setDispatchDate($dispatch_date)
    {
        $this->dispatch_date = $dispatch_date;
    }

    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

}
