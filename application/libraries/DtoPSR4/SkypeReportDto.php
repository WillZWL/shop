<?php
class SkypeReportDto
{
    private $bill_country_id;
    private $period;
    private $number_of_orders;
    private $items_ordered;
    private $subtotal;
    private $tax;
    private $discounts;
    private $shipping;
    private $total;
    private $invoiced;
    private $refunded;

    public function setBillCountryId($bill_country_id)
    {
        $this->bill_country_id = $bill_country_id;
    }

    public function getBillCountryId()
    {
        return $this->bill_country_id;
    }

    public function setPeriod($period)
    {
        $this->period = $period;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function setNumberOfOrders($number_of_orders)
    {
        $this->number_of_orders = $number_of_orders;
    }

    public function getNumberOfOrders()
    {
        return $this->number_of_orders;
    }

    public function setItemsOrdered($items_ordered)
    {
        $this->items_ordered = $items_ordered;
    }

    public function getItemsOrdered()
    {
        return $this->items_ordered;
    }

    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }

    public function setTax($tax)
    {
        $this->tax = $tax;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function setDiscounts($discounts)
    {
        $this->discounts = $discounts;
    }

    public function getDiscounts()
    {
        return $this->discounts;
    }

    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setInvoiced($invoiced)
    {
        $this->invoiced = $invoiced;
    }

    public function getInvoiced()
    {
        return $this->invoiced;
    }

    public function setRefunded($refunded)
    {
        $this->refunded = $refunded;
    }

    public function getRefunded()
    {
        return $this->refunded;
    }

}
