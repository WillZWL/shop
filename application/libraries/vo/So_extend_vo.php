<?php
include_once 'Base_vo.php';

class So_extend_vo extends Base_vo
{

    //class variable
    private $so_no;
    private $order_reason;
    private $notes;
    private $offline_fee = '0.00';
    private $vatexempt = '0';
    private $acked;
    private $fulfilled;
    private $conv_site_id;
    private $conv_status = '0';
    private $conv_site_ref;
    private $voucher_code;
    private $voucher_detail_id;
    private $licence_key;
    private $ls_time_entered;
    private $entity_id;
    private $aftership_status;
    private $aftership_checkpoint;
    private $aftership_token;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("so_no");

    //auo increment
    private $increment_field = "";

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

    public function get_order_reason()
    {
        return $this->order_reason;
    }

    public function set_order_reason($value)
    {
        $this->order_reason = $value;
        return $this;
    }

    public function get_notes()
    {
        return $this->notes;
    }

    public function set_notes($value)
    {
        $this->notes = $value;
        return $this;
    }

    public function get_offline_fee()
    {
        return $this->offline_fee;
    }

    public function set_offline_fee($value)
    {
        $this->offline_fee = $value;
        return $this;
    }

    public function get_vatexempt()
    {
        return $this->vatexempt;
    }

    public function set_vatexempt($value)
    {
        $this->vatexempt = $value;
        return $this;
    }

    public function get_acked()
    {
        return $this->acked;
    }

    public function set_acked($value)
    {
        $this->acked = $value;
        return $this;
    }

    public function get_fulfilled()
    {
        return $this->fulfilled;
    }

    public function set_fulfilled($value)
    {
        $this->fulfilled = $value;
        return $this;
    }

    public function get_conv_site_id()
    {
        return $this->conv_site_id;
    }

    public function set_conv_site_id($value)
    {
        $this->conv_site_id = $value;
        return $this;
    }

    public function get_conv_status()
    {
        return $this->conv_status;
    }

    public function set_conv_status($value)
    {
        $this->conv_status = $value;
        return $this;
    }

    public function get_conv_site_ref()
    {
        return $this->conv_site_ref;
    }

    public function set_conv_site_ref($value)
    {
        $this->conv_site_ref = $value;
        return $this;
    }

    public function get_voucher_code()
    {
        return $this->voucher_code;
    }

    public function set_voucher_code($value)
    {
        $this->voucher_code = $value;
        return $this;
    }

    public function get_voucher_detail_id()
    {
        return $this->voucher_detail_id;
    }

    public function set_voucher_detail_id($value)
    {
        $this->voucher_detail_id = $value;
        return $this;
    }

    public function get_licence_key()
    {
        return $this->licence_key;
    }

    public function set_licence_key($value)
    {
        $this->licence_key = $value;
        return $this;
    }

    public function get_ls_time_entered()
    {
        return $this->ls_time_entered;
    }

    public function set_ls_time_entered($value)
    {
        $this->ls_time_entered = $value;
        return $this;
    }

    public function get_entity_id()
    {
        return $this->entity_id;
    }

    public function set_entity_id($value)
    {
        $this->entity_id = $value;
        return $this;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

    public function get_aftership_status()
    {
        return $this->aftership_status;
    }

    public function set_aftership_status($value)
    {
        $this->aftership_status = $value;
        return $this;
    }

    public function get_aftership_checkpoint()
    {
        return $this->aftership_checkpoint;
    }

    public function set_aftership_checkpoint($value)
    {
        $this->aftership_checkpoint = $value;
        return $this;
    }

    public function get_aftership_token()
    {
        return $this->aftership_token;
    }

    public function set_aftership_token($value)
    {
        $this->aftership_token = $value;
        return $this;
    }

}
?>