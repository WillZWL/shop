<?php
class SoidProdnameDto
{
    private $gst_total;
    private $profit;
    private $profit_raw;
    private $margin;
    private $margin_raw;
    private $name;
    private $image;
    private $status;
    private $amount;
    private $discount;
    private $unit_price;
    private $qty;
    private $so_no;
    private $line_no;
    private $item_sku;
    private $sh_no;
    private $tracking_no;
    private $dispatch_date;

    public function setGstTotal($gst_total)
    {
        $this->gst_total = $gst_total;
    }

    public function getGstTotal()
    {
        return $this->gst_total;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfitRaw($profit_raw)
    {
        $this->profit_raw = $profit_raw;
    }

    public function getProfitRaw()
    {
        return $this->profit_raw;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMarginRaw($margin_raw)
    {
        $this->margin_raw = $margin_raw;
    }

    public function getMarginRaw()
    {
        return $this->margin_raw;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setUnitPrice($unit_price)
    {
        $this->unit_price = $unit_price;
    }

    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
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

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setShNo($sh_no)
    {
        $this->sh_no = $sh_no;
    }

    public function getShNo()
    {
        return $this->sh_no;
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

}
