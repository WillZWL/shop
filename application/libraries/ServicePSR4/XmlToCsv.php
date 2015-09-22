<?php
namespace ESG\Panther\Service;

include('outConverter.php');

class XmlToCsv implements outConverter
{

    private $input;
    private $map_file;
    private $first_line_heading;
    private $delimiter;

    function XmlToCsv($input = "", $map_file = "", $first_line_heading = TRUE, $delimiter = ",")
    {
        $this->input = $input;
        $this->map_file = $map_file;
        $this->first_line_heading = $first_line_heading;
        $this->delimiter = $delimiter;
    }

    public function outConvert()
    {
        $result = simplexml_load_string($this->input, 'SimpleXMLElement', LIBXML_NOCDATA);

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
                        $cur_row = fgets($handle);
                        $tmp = trim($cur_row);
                        if (substr($tmp, 0, 1) == "#") {
                            continue;
                        }
                        if ($tmp != "") {
                            list($rskey, $rsvalue, $spec_value) = @explode("\t", $cur_row);
                            $rskey = trim($rskey);
                            $rsvalue = trim($rsvalue);
                            $spec_value = trim($spec_value);
                            $mapping[] = array($rskey, $rsvalue, $spec_value);
                            $heading[] = strpos((string)$rsvalue, array('"', $this->delimiter)) === FALSE ? $rsvalue : '"' . str_replace('"', '""', $rsvalue) . '"';
                        }
                    }
                } else {
                    return FALSE;
                }
            } elseif (is_array($this->map_file)) {
                $container = $this->map_file["container"];
                foreach ($this->map_file["mapping"] as $rskey => $rsvalue) {
                    $mapping[] = array($rskey, $rsvalue);
                    $heading[] = strpos((string)$rsvalue, array('"', $this->delimiter)) === FALSE ? $rsvalue : '"' . str_replace('"', '""', $rsvalue) . '"';
                }
            }
        }
        $output = "";

        if (count($result)) {
            foreach ($result as $rscontainer => $row) {
                $line = array();
                if ($this->map_file != "") {
                    foreach ($mapping as $rsmap) {
                        $rskey = $rsmap[0];
                        $rsvalue = $rsmap[1];
                        $spec_value = $rsmap[2];
                        if (trim($rskey) == "" && !is_null($spec_value)) {
                            $line[] = preg_match('/["|\\' . $this->delimiter . ']/', $spec_value) ? '"' . str_replace('"', '""', $spec_value) . '"' : $spec_value;
                        } else {
                            if (@isset($row->$rskey)) {
                                $line[] = preg_match('/["|\\' . $this->delimiter . ']/', (string)$row->$rskey) ? '"' . str_replace('"', '""', $row->$rskey) . '"' : $row->$rskey;
                            } else {
                                $line[] = "";
                            }
                        }
                    }
                } else {
                    foreach ($row as $field => $value) {
                        $heading[$field] = preg_match('/["|\\' . $this->delimiter . ']/', (string)$field) ? '"' . str_replace('"', '""', $field) . '"' : $field;
                        $line[] = preg_match('/["|\\' . $this->delimiter . ']/', (string)$value) ? '"' . str_replace('"', '""', $value) . '"' : $value;
                    }
                }
                $output .= implode($this->delimiter, $line) . "\r\n";
            }
        }

        return ($this->first_line_heading) ? @implode($this->delimiter, $heading) . "\r\n" . $output : $output;
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