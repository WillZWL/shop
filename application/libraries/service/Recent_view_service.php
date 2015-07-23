<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Recent_view_service extends Base_service
{
    private $size = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function add($sku)
    {
        $tmp = $_SESSION["recent"];
        if (!in_array($sku, $tmp)) {
            $size = count($_SESSION["recent"]);
            if ($size >= $this->get_size()) {
                array_shift($_SESSION["recent"]);
            }
        } else {
            $key = array_keys($_SESSION["recent"], $sku);
            unset($_SESSION["recent"][$key[0]]);

        }
        $_SESSION["recent"][] = $sku;
    }

    public function get_size()
    {
        return $this->size;
    }

    public function get_recent()
    {
        $ret = $_SESSION["recent"];
        krsort($ret);
        return $ret;
    }

    public function __destroy()
    {
        unset($_SESSION["recent"]);
    }
}

?>