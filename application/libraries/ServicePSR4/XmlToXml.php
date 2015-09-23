<?php
namespace ESG\Panther\Service;

include('inConverter.php');
include('outConverter.php');

class XmlToXml implements inConverter, outConverter
{

    private $input;
    private $map_file;
    private $basetag;

    function XmlToXml($input = "", $map_file = "", $basetag = "entity")
    {
        $this->input = $input;
        $this->map_file = $map_file;
    }

    public function inConvert()
    {
        return $this->convert();
    }

    public function convert()
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
            } else
                return FALSE;
        }

        $output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $output .= "<entity>\n";


        foreach ($result as $rscontainer => $row) {
            if ($container == "")
                $container = $rscontainer;
            $output .= "\t<{$container}>\n";
            if ($this->map_file != "") {
                foreach ($mapping as $rskey => $rsvalue) {
                    if (@isset($row->$rskey)) {
                        $output .= "\t\t<{$rsvalue}><![CDATA[" . strip_invalid_xml((string)$row->$rskey) . "]]></{$rsvalue}>\n";
                    }
                }
            } else {
                foreach ($row as $field => $value) {
                    $output .= "\t\t<{$field}><![CDATA[" . strip_invalid_xml((string)$value) . "]]></{$field}>\n";
                }
            }
            $output .= "\t</{$container}>\n";
        }

        $output .= "</entity>\n";
        return $output;
    }

    public function outConvert()
    {
        return $this->convert();
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