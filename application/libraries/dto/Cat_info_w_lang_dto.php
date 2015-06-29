<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Cat_info_w_lang_dto extends Base_dto
{
    private $cat_id;
    private $level;
    private $lang_id;
    private $name;
    private $description;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_cat_id()
    {
        return $this->cat_id;
    }

    public function set_cat_id($value)
    {
        $this->cat_id = $value;
    }

    public function get_level()
    {
        return $this->level;
    }

    public function set_level($value)
    {
        $this->level = $value;
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function set_lang_id($value)
    {
        $this->lang_id = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_description()
    {
        return $this->description;
    }

    public function set_description($value)
    {
        $this->description = $value;
    }
}

?>