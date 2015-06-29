<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Brand_cat_prod_dto extends Base_dto
{
        private $sku;
        private $name;
        private $status;
        private $quantity;
        private $website_quantity;
        private $image;
        private $colour_id;
        private $website_status;
        private $price;

        public function __construct()
        {
                parent::__construct();
        }

        public function get_colour_id()
        {
            return $this->colour_id;
        }

        public function set_colour_id($value)
        {
                $this->colour_id = $value;
        }

        public function get_sku()
        {
                return $this->sku;
        }

        public function set_sku($value)
        {
                $this->sku = $value;
        }

        public function get_name()
        {
                return $this->name;
        }

        public function set_name($value)
        {
                $this->name = $value;
        }

        public function get_status()
        {
                return $this->status;
        }

        public function set_status($value)
        {
                $this->status = $value;
        }

        public function get_quantity()
        {
                return $this->quantity;
        }

        public function set_quantity($value)
        {
                $this->quantity = $value;
        }

        public function get_website_quantity()
        {
                return $this->website_quantity;
        }

        public function set_website_quantity($value)
        {
                $this->website_quantity = $value;
        }
        public function get_image()
        {
                return $this->image;
        }

        public function set_image($value)
        {
                $this->image = $value;
        }

        public function get_website_status()
        {
                return $this->website_status;
        }

        public function set_website_status($value)
        {
                $this->website_status = $value;
        }

        public function get_price()
        {
                return $this->price;
        }

        public function set_price($value)
        {
                $this->price = $value;
        }
}

