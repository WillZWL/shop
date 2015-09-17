<?php
class WhConfirmShipmentDto
{
    private $shipment_id;
    private $tracking_no;
    private $courier;
    private $log_sku;
    private $trans_id;
    private $prod_name;
    private $sku;
    private $shipped_qty;
    private $ordered_qty;
    private $received_qty;
    private $supplier_id;
    private $supplier_name;
    private $detail;
    private $reason;
    private $remarks;
    private $delivery_mode;

    public function setShipmentId($shipment_id)
    {
        $this->shipment_id = $shipment_id;
    }

    public function getShipmentId()
    {
        return $this->shipment_id;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setCourier($courier)
    {
        $this->courier = $courier;
    }

    public function getCourier()
    {
        return $this->courier;
    }

    public function setLogSku($log_sku)
    {
        $this->log_sku = $log_sku;
    }

    public function getLogSku()
    {
        return $this->log_sku;
    }

    public function setTransId($trans_id)
    {
        $this->trans_id = $trans_id;
    }

    public function getTransId()
    {
        return $this->trans_id;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setShippedQty($shipped_qty)
    {
        $this->shipped_qty = $shipped_qty;
    }

    public function getShippedQty()
    {
        return $this->shipped_qty;
    }

    public function setOrderedQty($ordered_qty)
    {
        $this->ordered_qty = $ordered_qty;
    }

    public function getOrderedQty()
    {
        return $this->ordered_qty;
    }

    public function setReceivedQty($received_qty)
    {
        $this->received_qty = $received_qty;
    }

    public function getReceivedQty()
    {
        return $this->received_qty;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setSupplierName($supplier_name)
    {
        $this->supplier_name = $supplier_name;
    }

    public function getSupplierName()
    {
        return $this->supplier_name;
    }

    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function setDeliveryMode($delivery_mode)
    {
        $this->delivery_mode = $delivery_mode;
    }

    public function getDeliveryMode()
    {
        return $this->delivery_mode;
    }

}
