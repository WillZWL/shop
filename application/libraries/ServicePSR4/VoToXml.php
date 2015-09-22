<?php
namespace ESG\Panther\Service;

include('inConverter.php');

class VoToXml implements inConverter
{

    private $input;
    private $map_file;

    public function VoToXml($input = "", $map_file = "")
    {
        $this->input = $input;
        $this->map_file = $map_file;
    }

    public function inConvert($need_cdata = true)
    {
        print_r($this->input);
        $container = "";

        if ($this->map_file != "") {
            if (is_file($this->map_file)) {
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
                    return FALSE;
                }
            } elseif (is_array($this->map_file)) {
                $container = $this->map_file["container"];
                $mapping = $this->map_file["mapping"];
            }
        }

        if ($need_cdata) {
            $cdata_open = "<![CDATA[";
            $cdata_end = "]]>";
        } else {
            $cdata_open = "";
            $cdata_end = "";
        }

        $output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $output .= "<entity>\n";

        if (get_class_methods($this->input))
            $result[] = $this->input;
        else
            $result = $this->input;

        if ($result) {
            foreach ($result as $rsdata) {
                if ($container == "") {
                    $rscontainer = strtolower(get_class($rsdata));
                    if (substr($rscontainer, -2) == "Vo") {
                        $rscontainer = substr($rscontainer, 0, -2);
                    }
                    //added by Jack
                    if (substr($rscontainer, -3) == "Dto") {
                        $rscontainer = substr($rscontainer, 0, -3);
                    }
                    $container = $rscontainer;
                }
                $output .= "\t<{$container}>\n";
                if ($this->map_file != "") {
                    foreach ($mapping as $rskey => $rsvalue) {
                        list($p_key, $sub_key) = @explode("->", $rskey);
                        $data = @call_user_func(array($rsdata, "get" . $p_key));
                        if ($sub_key) {
                            $output .= "\t\t<{$rsvalue}>" . $cdata_open . strip_invalid_xml((string)$data->$sub_key) . $cdata_end . "</{$rsvalue}>\n";
                        } else {
                            $output .= "\t\t<{$rsvalue}>" . $cdata_open . strip_invalid_xml((string)$data) . $cdata_end . "</{$rsvalue}>\n";
                        }
                    }
                } else {
                    $class_methods = get_class_methods($rsdata);
                    foreach ($class_methods as $fct_name) {
                        if (substr($fct_name, 0, 3) == "get") {
                            $field = camelcase2underscore(substr($fct_name, 3));
                            $rsvalue = call_user_func(array($rsdata, $fct_name));
                            $output .= "\t\t<{$field}>" . $cdata_open . strip_invalid_xml((string)$rsvalue) . $cdata_end . "</{$field}>\n";
                        }
                    }
                }
                $output .= "\t</{$container}>\n";
            }
        }

        $output .= "</entity>\n";
        return $output;
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