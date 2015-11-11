<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Validation_service extends Base_service
{

    private $rules;
    private $data;
    private $not_exists_in;
    private $exists_in;

    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        if ($ruleslist = $this->get_rules()) {
            if ($data = $this->get_data()) {
                foreach ($ruleslist as $field => $rules) {
                    if ($rules) {
                        if (is_object($data)) {
                            $func = "get_" . $field;
                            $value = $data->$func();
                        } else {
                            $value = $data[$field];
                        }

                        $rules = is_array($rules) ? $rules : (array)$rules;
                        foreach ($rules as $rule) {
                            list($func, $cvalue) = @explode("=", $rule);
                            if (!$this->$func($value, $cvalue)) {
                                throw new Exception($value . " is_failed_in_rule " . $rule . " for field " . $field . "; ");
                                return FALSE;
                            }
                        }
                    }
                }
            }
        }
        return TRUE;
    }

// Validation Functions

    public function get_rules()
    {
        return $this->rules;
    }

    public function set_rules($rules)
    {
        $this->rules = is_array($rules) ? $rules : (array)$rules;
    }

    public function get_data()
    {
        return $this->data;
    }

    public function set_data($data)
    {
        $this->data = (is_array($data) || is_object($data)) ? $data : (array)$data;
    }

    public function not_empty($str)
    {
        return (strlen(trim($str)) == 0) ? FALSE : TRUE;
    }

    public function is_set($str)
    {
        return isset($str);
    }

    public function valid_email($str)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    public function match($str, $field)
    {
        $data = $this->get_data();

        if (is_object($data)) {
            $func = "get_" . $field;
            $value = $data->$func();
        } else {
            $value = $data[$field];
        }

        return ($str !== $value) ? FALSE : TRUE;
    }

    public function min_len($str, $val)
    {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }

        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) < $val) ? FALSE : TRUE;
        }

        return (strlen($str) < $val) ? FALSE : TRUE;
    }

    public function max_len($str, $val)
    {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }

        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) > $val) ? FALSE : TRUE;
        }

        return (strlen($str) > $val) ? FALSE : TRUE;
    }

    public function min($str, $val)
    {
        if (!is_numeric($val)) {
            $data = $this->get_data();
            if (is_object($data)) {
                $func = "get_" . $field;
                $value = $data->$func();
            } else {
                $value = $data[$field];
            }
        }
        if ($str * 1 < $val * 1)
            return FALSE;
        else
            return TRUE;
    }

    public function max($str, $val)
    {
        if (!is_numeric($val)) {
            $data = $this->get_data();
            if (is_object($data)) {
                $func = "get_" . $field;
                $value = $data->$func();
            } else {
                $value = $data[$field];
            }
        }

        if ($str * 1 > $val * 1)
            return FALSE;
        else
            return TRUE;
    }

    public function len($str, $val)
    {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }

        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) != $val) ? FALSE : TRUE;
        }

        return (strlen($str) != $val) ? FALSE : TRUE;
    }

    public function is_integer($str)
    {
        return (bool)preg_match('/^[\-+]?[0-9]+$/', $str);
    }

    public function is_natural($str)
    {
        return (bool)preg_match('/^[0-9]+$/', $str);
    }

    public function is_number($str)
    {
        return (!is_numeric($str)) ? FALSE : TRUE;
    }

// Getter / Setter

    public function exists_in_vo($str, $val)
    {
        list($name, $column) = explode("->", $val);
        $path = APPPATH . "libraries/dao/" . strtolower($name) . "_dao.php";
        if (file_exists($path)) {
            include_once($path);
            $classname = ucfirst($name) . "_dao";
            $strclass = new $classname();
            return $strclass->get(array($column => str)) ? TRUE : FALSE;
        }
        return FALSE;
    }

    public function check_field($input, $greater = 0, $val = '15', $delimiter = ',', $skip_first_line = TRUE, $skip_empty_line = TRUE)
    {
        $checking = TRUE;
        include_once(BASEPATH . 'plugins/csv_parser_pi.php');
        $reader = new CSVFileLineIterator($input);
        $result = csv_parse($reader, $delimiter, $skip_first_line);
        for ($i = 0; $i < count($result); $i++) {
            include(APPPATH . "helpers/string_helper.php");
            if (count($result[$i]) == 1 && trim(strip_invalid_xml($result[$i][0])) == "" && $skip_empty_line) {
                array_splice($result, $i, 1);
                continue;
            }
            if ($greater == 0) {
                if (count($result[$i]) <> $val) {
                    $checking = FALSE;
                }
            } elseif ($greater == 1) {
                if (count($result[$i]) > $val) {
                    $checking = FALSE;
                }
            }
        }
        if ($checking == TRUE) {
            return $result;
        } else {
            return FALSE;
        }
    }

    public function exists_in($str, $val)
    {
        $exists_in = $this->get_exists_in();
        return $exists_in[$val][$str] ? TRUE : FALSE;
    }

    public function get_exists_in()
    {
        return $this->exists_in;
    }

    public function set_exists_in($value)
    {
        $this->exists_in = $value;
    }

    public function not_exists_in($str, $val)
    {
        $not_exists_in = $this->get_not_exists_in();
        return $not_exists_in[$val][$str] ? FALSE : TRUE;
    }

    public function get_not_exists_in()
    {
        return $this->not_exists_in;
    }

    public function set_not_exists_in($value)
    {
        $this->not_exists_in = $value;
    }
}



