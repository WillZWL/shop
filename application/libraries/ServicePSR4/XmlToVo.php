<?php
namespace ESG\Panther\Service;

class XmlToVo implements outConverter
{

    private $input;
    private $map_file;

    function XmlToVo($input = "", $map_file = "")
    {
        $this->input = $input;
        $this->map_file = $map_file;
    }

    public function outConvert()
    {

        $result = simplexml_load_string($this->input, 'SimpleXMLElement', LIBXML_NOCDATA);

        $container = "";

        if ($this->map_file != "") {
            if ($handle = fopen($this->map_file, "rb")) {
                while (!feof($handle)) {
                    $tmp = trim(fgets($handle));
                    if (substr($tmp, 0, 1) == "#")
                        continue;
                    if ($tmp != "") {
                        $container = $tmp;
                        break;
                    }
                }

                while (!feof($handle)) {
                    $tmp = trim(fgets($handle));
                    if (substr($tmp, 0, 1) == "#")
                        continue;
                    if ($tmp != "") {
                        list($rskey, $rsvalue) = @explode("\t", trim($tmp));
                        $mapping[$rskey] = $rsvalue;
                    }
                }
            } else {
                echo "mapping file not found";
                return FALSE;
            }
        }

        foreach ($result as $rscontainer => $row) {
            if ($container == "") {
                $container = $rscontainer;
            }

            $vo = FALSE;


            if (substr($container, -3) == "Dto") {
                $vo_file = APPPATH . "/libraries/dto/" . strtolower(substr($container, 0, -3)) . "Dto.php";
                $vo_class = ucwords($container);
            } elseif (substr($container, -2) == "Vo") {
                $vo_file = APPPATH . "/libraries/vo/" . strtolower(substr($container, 0, -2)) . "Vo.php";
                $vo_class = ucwords($container);
            } else {
                $vo_file = APPPATH . "/libraries/vo/" . strtolower($container) . "Vo.php";
                $vo_class = ucwords($container) . "Vo";
            }

            if (file_exists($vo_file)) {
                include_once($vo_file);
                @$vo = new $vo_class();
                if ($this->map_file != "") {
                    foreach ($mapping as $rskey => $rsvalue) {
                        if (@isset($row->$rskey)) {
                            //modified by Jack 2009-11-30
                            $tmp = explode(",", $rsvalue);
                            foreach ($tmp as $rsval) {
                                if (substr($rsval, 0, 1) != "+") {
                                    @call_user_func(array($vo, "set" . $rsval), (string)$row->$rskey);
                                } else {
                                    $field = substr($rsval, 1);
                                    $content = @call_user_func(array($vo, "get" . $field));
                                    if (trim((string)$row->$rskey) != "") {
                                        @call_user_func(array($vo, "set" . $field), $content . ", " . (string)$row->$rskey);
                                    }
                                }
                            }
                        }
                    }
                } else {

                    $class_methods = get_class_methods($vo);
                    foreach ($class_methods as $fct_name) {
                        if (substr($fct_name, 0, 3) == "set") {
                            $field = camelcase2underscore(substr($fct_name, 3));
                            @call_user_func(array($vo, $fct_name), isset($row->$field) ? (string)$row->$field : null);
                        }
                    }
                }
                $output[] = $vo;
            } else {
                return FALSE;
            }
        }

        return (object)$output;
    }

    public function setInput($value)
    {
        $this->input = $value;
    }

    public function setMapFile($value)
    {
        $this->map_file = $value;
    }
}