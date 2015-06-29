<?php
include_once 'Base_dto.php';

class So_ext_w_reason_dto extends Base_vo
{
//class variable
    protected $so_no;
    protected $reason_id;
    protected $reason;
    protected $reason_display_name;
    protected $require_payment;
    protected $notes;
    protected $offline_fee = '0.00';
    protected $vatexempt = '0';
    protected $acked;
    protected $fulfilled;
    protected $conv_site_id;
    protected $conv_status = '0';
    protected $conv_site_ref;
    protected $voucher_code;
    protected $voucher_detail_id;
    protected $licence_key;
    protected $ls_time_entered;
    protected $entity_id;
    protected $create_on = '0000-00-00 00:00:00';
    protected $create_at;
    protected $create_by;
    protected $modify_on;
    protected $modify_at;
    protected $modify_by;

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

    public function get_reason_id()
    {
        return $this->reason_id;
    }

    public function set_reason_id($value)
    {
        $this->reason_id = $value;
        return $this;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
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

    public function get_require_payment()
    {
        return $this->require_payment;
    }

    public function set_require_payment($value)
    {
        $this->require_payment = $value;
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

}
?>