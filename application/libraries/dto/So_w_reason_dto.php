<?php
include_once 'Base_dto.php';

class So_w_reason_dto extends Base_dto
{
//class variable
    protected $so_no;
    protected $biz_type;
    protected $platform_id;
    protected $status;
    protected $refund_status;
    protected $hold_status;
    protected $reason_id;
    protected $reason_display_name;
    protected $order_reason_note;
    protected $hold_reason;
    protected $refund_status_progress;

    //instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_biz_type()
    {
        return $this->biz_type;
    }

    public function set_biz_type($value)
    {
        $this->biz_type = $value;
        return $this;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function get_reason_id()
    {
        return $this->reason_id;
    }

    public function set_reason_id($value)
    {
        $this->reason_id = $value;
        return $this;
    }

    public function get_reason_display_name()
    {
        return $this->reason_display_name;
    }

    public function set_reason_display_name($value)
    {
        $this->reason_display_name = $value;
        return $this;
    }

    public function get_order_reason_note()
    {
        return $this->order_reason_note;
    }

    public function set_order_reason_note($value)
    {
        $this->order_reason_note = $value;
        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
        return $this;
    }

    public function get_refund_status()
    {
        return $this->refund_status;
    }

    public function set_refund_status($value)
    {
        $this->refund_status = $value;
        return $this;
    }

    public function get_hold_status()
    {
        return $this->hold_status;
    }

    public function set_hold_status($value)
    {
        $this->hold_status = $value;
        return $this;
    }

    public function get_hold_reason()
    {
        return $this->hold_reason;
    }

    public function set_hold_reason($value)
    {
        $this->hold_reason = $value;
        return $this;
    }

    public function get_refund_status_progress()
    {
        return $this->refund_status_progress;
    }

    public function set_refund_status_progress($value)
    {
        $this->refund_status_progress = $value;
        return $this;
    }
}

?>