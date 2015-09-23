<?php
namespace ESG\Panther\Service;

include('inConverter.php');

class CsvToXml implements inConverter
{

    private $input;
    private $map_file;
    private $first_line_heading;
    private $delimiter;
    private $checkQuote;

    function CsvToXml($input = "", $map_file = "", $first_line_heading = TRUE, $delimiter = ",", $checkQuote = TRUE)
    {
        $this->input = $input;
        $this->map_file = $map_file;
        $this->first_line_heading = $first_line_heading;
        $this->delimiter = $delimiter;
        $this->checkQuote = $checkQuote;

    }

    public function inConvert()
    {

        $result = [];
        $container = "data";

        $this->load->plugin('csv_parser');
        $reader = new \CSVFileLineIterator($this->input);
        $result = csv_parse($reader, $this->delimiter, FALSE, $this->checkQuote);

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

        for ($i = 0; $i < count($result[0]); $i++) {
            $result[0][$i] = trim($result[0][$i]);

            if ($this->map_file != "") {
                if (isset($mapping[$i])) {
                    $heading[$mapping[$i]] = $i;
                } elseif (isset($mapping[$result[0][$i]])) {
                    $heading[$mapping[$result[0][$i]]] = $i;
                }
            } else {
                $heading[$i] = $this->first_line_heading ? $result[0][$i] : "Column_" . $i;
            }
        }

        $startline = $this->first_line_heading ? 1 : 0;

        $output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $output .= "<entity>\n";


        for ($i = $startline; $i < count($result); $i++) {
            $output .= "\t<{$container}>\n";
            if ($this->map_file != "") {
                foreach ($mapping as $rskey => $rsvalue) {
                    if (@isset($result[$i][$heading[$rsvalue]])) {
                        $output .= "\t\t<{$rsvalue}><![CDATA[" . strip_invalid_xml((string)$result[$i][$heading[$rsvalue]]) . "]]></{$rsvalue}>\n";
                    }
                }
            } else {
                foreach ($result[$i] as $rskey => $rsvalue) {
                    $output .= "\t<{$heading[$rskey]}><![CDATA[" . strip_invalid_xml((string)$rsvalue) . "]]></{$heading[$rskey]}>\n";
                }
            }
            $output .= "\t</{$container}>\n";
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

    public function setFirstLineHeading($value)
    {
        $this->first_line_heading = $value;
    }
}