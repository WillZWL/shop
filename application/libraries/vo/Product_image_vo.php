<?php
include_once 'Base_vo.php';

class Product_image_vo extends Base_vo
{

    //class variable
    private $id;
    private $sku;
    private $priority = '1';
    private $image;
    private $alt_text;
    private $image_saved = '1';
    private $vb_image = '';
    private $stop_sync_image = '0';
    private $vb_alt_text = '';
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "id";

    //instance method
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
        return $this;
    }

    public function get_priority()
    {
        return $this->priority;
    }

    public function set_priority($value)
    {
        $this->priority = $value;
        return $this;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($value)
    {
        $this->image = $value;
        return $this;
    }

    public function get_alt_text()
    {
        return $this->alt_text;
    }

    public function set_alt_text($value)
    {
        $this->alt_text = $value;
        return $this;
    }

    public function set_image_saved($image_saved)
    {
        $this->image_saved = $image_saved;
    }

    public function get_image_saved()
    {
        return $this->image_saved;
    }

    public function set_vb_image($vb_image)
    {
        if ($vb_image) {
            $this->vb_image = $vb_image;
        }
    }

    public function get_vb_image()
    {
        return $this->vb_image;
    }

    public function set_stop_sync_image($stop_sync_image)
    {
        //if ($stop_sync_image) {
            $this->stop_sync_image = $stop_sync_image;
        //}
    }

    public function get_stop_sync_image()
    {
        return $this->stop_sync_image;
    }

    public function set_vb_alt_text($vb_alt_text)
    {
        if ($vb_alt_text) {
            $this->vb_alt_text = $vb_alt_text;
        }
    }

    public function get_vb_alt_text()
    {
        return $this->vb_alt_text;
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