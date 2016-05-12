<?php

class CompetitorMapVo extends \BaseVo
{
    private $id;
    private $ext_sku = '';
    private $competitor_id;
    private $status;
    private $match = '1';
    private $last_price;
    private $now_price;
    private $product_url;
    private $note_1;
    private $note_2;
    private $comp_stock_status;
    private $comp_ship_charge;
    private $reprice_min_margin = '9.00';
    private $reprice_value = '0.00';
    private $sourcefile_timestamp = '0000-00-00 00:00:00';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setExtSku($ext_sku)
    {
        if ($ext_sku !== null) {
            $this->ext_sku = $ext_sku;
        }
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setCompetitorId($competitor_id)
    {
        if ($competitor_id !== null) {
            $this->competitor_id = $competitor_id;
        }
    }

    public function getCompetitorId()
    {
        return $this->competitor_id;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setMatch($match)
    {
        if ($match !== null) {
            $this->match = $match;
        }
    }

    public function getMatch()
    {
        return $this->match;
    }

    public function setLastPrice($last_price)
    {
        if ($last_price !== null) {
            $this->last_price = $last_price;
        }
    }

    public function getLastPrice()
    {
        return $this->last_price;
    }

    public function setNowPrice($now_price)
    {
        if ($now_price !== null) {
            $this->now_price = $now_price;
        }
    }

    public function getNowPrice()
    {
        return $this->now_price;
    }

    public function setProductUrl($product_url)
    {
        if ($product_url !== null) {
            $this->product_url = $product_url;
        }
    }

    public function getProductUrl()
    {
        return $this->product_url;
    }

    public function setNote1($note_1)
    {
        if ($note_1 !== null) {
            $this->note_1 = $note_1;
        }
    }

    public function getNote1()
    {
        return $this->note_1;
    }

    public function setNote2($note_2)
    {
        if ($note_2 !== null) {
            $this->note_2 = $note_2;
        }
    }

    public function getNote2()
    {
        return $this->note_2;
    }

    public function setCompStockStatus($comp_stock_status)
    {
        if ($comp_stock_status !== null) {
            $this->comp_stock_status = $comp_stock_status;
        }
    }

    public function getCompStockStatus()
    {
        return $this->comp_stock_status;
    }

    public function setCompShipCharge($comp_ship_charge)
    {
        if ($comp_ship_charge !== null) {
            $this->comp_ship_charge = $comp_ship_charge;
        }
    }

    public function getCompShipCharge()
    {
        return $this->comp_ship_charge;
    }

    public function setRepriceMinMargin($reprice_min_margin)
    {
        if ($reprice_min_margin !== null) {
            $this->reprice_min_margin = $reprice_min_margin;
        }
    }

    public function getRepriceMinMargin()
    {
        return $this->reprice_min_margin;
    }

    public function setRepriceValue($reprice_value)
    {
        if ($reprice_value !== null) {
            $this->reprice_value = $reprice_value;
        }
    }

    public function getRepriceValue()
    {
        return $this->reprice_value;
    }

    public function setSourcefileTimestamp($sourcefile_timestamp)
    {
        if ($sourcefile_timestamp !== null) {
            $this->sourcefile_timestamp = $sourcefile_timestamp;
        }
    }

    public function getSourcefileTimestamp()
    {
        return $this->sourcefile_timestamp;
    }

}
