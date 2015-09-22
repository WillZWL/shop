<?php
namespace ESG\Panther\Service;

interface inConverter
{
    public function inConvert();
}

interface outConverter
{
    public function outConvert();
}

class DataExchangeService extends BaseService
{

    private $import;
    private $export;

    function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->config =& $CI->config;
        $this->load = $CI->load;
        $this->load->helper('string');
    }

    public function convert(inConverter $import, outConverter $export = NULL)
    {
        $this->import = $import;
        if (empty($export)) {
            return $this->import->inConvert();
        } else {
            $this->export = $export;
            $this->export->set_input($this->import->inConvert());
            return $this->export->outConvert();
        }
    }

}
// CSV to XML

class CsvToXml implements inConverter
{

    private $input;
    private $map_file;
    private $first_line_heading;
    private $delimiter;
    private $checkQuote;

    function CsvToXml($input = "", $map_file = "", $first_line_heading = TRUE, $delimiter = ",", $checkQuote = TRUE)
    {
        $CI =& get_instance();
        $this->load = $CI->load;

        $this->input = $input;
        $this->map_file = $map_file;
        $this->first_line_heading = $first_line_heading;
        $this->delimiter = $delimiter;
        $this->checkQuote = $checkQuote;

    }

    public function inConvert()
    {

        $result = array();
        $container = "data";

        $this->load->plugin('csv_parser');
        $reader = new CSVFileLineIterator($this->input);
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

    public function set_input($value)
    {
        $this->input = $value;
    }

    public function set_map_file($value)
    {
        $this->map_file = $value;
    }

    public function set_first_line_heading($value)
    {
        $this->first_line_heading = $value;
    }
}


// XML to XML
class XmlToXml implements inConverter, outConverter
{

    private $input;
    private $map_file;
    private $basetag;

    function XmlToXml($input = "", $map_file = "", $basetag = "entity")
    {
        $CI =& get_instance();
        $this->load = $CI->load;

        $this->input = $input;
        $this->map_file = $map_file;
    }

    public function inConvert()
    {
        return $this->_convert();
    }

    public function _convert()
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
        return $this->_convert();
    }

    public function set_input($value)
    {
        $this->input = $value;
    }

    public function set_map_file($value)
    {
        $this->map_file = $value;
    }
}


// VO to XML
class VoToXml implements inConverter
{

    private $input;
    private $map_file;

    function VoToXml($input = "", $map_file = "")
    {
        $CI =& get_instance();
        $this->load = $CI->load;

        $this->input = $input;
        $this->map_file = $map_file;
    }

    public function inConvert($need_cdata = true)
    {

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
                        $rscontainer = camelcase2underscore(substr($rscontainer, 0, -2));
                    }
                    //added by Jack
                    if (substr($rscontainer, -3) == "Dto") {
                        $rscontainer = camelcase2underscore(substr($rscontainer, 0, -3));
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
                        if (substr($fct_name, 0, 4) == "get") {
                            $field = camelcase2underscore(substr($fct_name, 4));
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

    public function set_input($value)
    {
        $this->input = $value;
    }

    public function set_map_file($value)
    {
        $this->map_file = $value;
    }
}


// XML to CSV
class XmlToCsv implements outConverter
{

    private $input;
    private $map_file;
    private $first_line_heading;
    private $delimiter;

    function XmlToCsv($input = "", $map_file = "", $first_line_heading = TRUE, $delimiter = ",")
    {
        $CI =& get_instance();
        $this->load = $CI->load;

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


    public function set_input($value)
    {
        $this->input = $value;
    }

    public function set_map_file($value)
    {
        $this->map_file = $value;
    }
}

// XML to XLS
class XmlToXls implements outConverter
{
    private $input;
    private $map_file;

    function XmlToXls($input = "", $map_file = "", $first_line_heading = TRUE, $sheet_map_file = "", $display_sheet_key = TRUE)
    {
        $CI =& get_instance();
        $this->load = $CI->load;

        $this->input = $input;
        $this->map_file = $map_file;
        $this->sheet_map_file = $sheet_map_file;
        $this->first_line_heading = $first_line_heading;
        $this->display_sheet_key = $display_sheet_key;
    }

    public function outConvert()
    {
        $result = simplexml_load_string($this->input, 'SimpleXMLElement', LIBXML_NOCDATA);

        include_once(BASEPATH . 'plugins/PHPExcel/IOFactory.php');
        include_once(BASEPATH . 'plugins/phpexcel_pi.php');
        $xls_writer = new PHPExcel();

        $container = "";

        if ($this->map_file != "") {
            if (is_file($this->map_file)) {
                if ($handle = fopen($this->map_file, "rb")) {
                    while (!feof($handle)) {
                        $tmp = trim(fgets($handle));
                        if (substr($tmp, 0, 1) == "#") {
                            continue;
                        }
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
                            //$heading[] =strpos((string)$rsvalue, array('"', $this->delimiter)) === FALSE ? $rsvalue : '"'.str_replace('"', '""', $rsvalue).'"';
                        }
                    }
                } else {
                    return FALSE;
                }
            } elseif (is_array($this->map_file)) {
                $container = $this->map_file["container"];
                foreach ($this->map_file["mapping"] as $rskey => $rsvalue) {
                    $mapping[] = array($rskey, $rsvalue);
                    //$heading[] =strpos((string)$rsvalue, array('"', $this->delimiter)) === FALSE ? $rsvalue : '"'.str_replace('"', '""', $rsvalue).'"';
                }
            }
        }

        if ($this->sheet_map_file != "") {
            if (is_file($this->sheet_map_file)) {
                if ($handle = fopen($this->sheet_map_file, "rb")) {
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
                            $sheet_mapping[] = array($rskey, $rsvalue, $spec_value);
                            $heading[] = strpos((string)$rsvalue, array('"', $this->delimiter)) === FALSE ? $rsvalue : '"' . str_replace('"', '""', $rsvalue) . '"';
                        }
                    }
                } else {
                    return FALSE;
                }
            } elseif (is_array($this->sheet_map_file)) {
                $sheet_key = $this->sheet_map_file["sheet_key"];
                $i = 0;
                foreach ($this->sheet_map_file["sheet_list"] as $sheet_value => $sheet_name) {
                    $sheet_mapping[$sheet_value] = array("sheet_name" => $sheet_name, "sheet_no" => $i);
                    if ($i > 0) {
                        $xls_writer->createSheet();
                    }
                    $xls_writer->setActiveSheetIndex($i)->setTitle($sheet_name);
                    $excel_row_arr[$i] = 1;
                    if ($this->first_line_heading) {
                        $excel_column = 0;
                        foreach ($mapping as $rsmap) {
                            if (!$this->display_sheet_key AND $rsmap[0] == $sheet_key) {
                                continue;
                            }
                            $xls_writer->getActiveSheet()->setCellValueByColumnAndRow($excel_column, $excel_row_arr[$i], $rsmap[1]);
                            $excel_column++;
                        }
                        $excel_row_arr[$i]++;
                    }
                    $i++;
                }
            }
        }

        $output = "";
        if (count($result)) {
            if (!isset($sheet_mapping)) {
                $excel_row = 1;
                $xls_writer->setActiveSheetIndex(0);
            }
            foreach ($result as $rscontainer => $row) {
                $line = array();
                if ($this->map_file != "") {
                    foreach ($mapping as $rsmap) {
                        $rskey = $rsmap[0];
                        $rsvalue = $rsmap[1];
                        $spec_value = $rsmap[2];
                        if ($rskey == $sheet_key) {
                            $active_sheet = $sheet_mapping[(string)$row->$rskey]["sheet_no"];
                            if (!($this->display_sheet_key)) {
                                continue;
                            }
                        }
                        if (trim($rskey) == "" && !is_null($spec_value)) {
                            $line[$rskey] = $spec_value;
                        } else {
                            if (@isset($row->$rskey)) {
                                $line[$rskey] = $row->$rskey;
                            } else {
                                $line[$rskey] = "";
                            }
                        }
                    }
                    $excel_column = 0;
                    foreach ($line AS $cell_info) {
                        if (isset($active_sheet)) {
                            $xls_writer->setActiveSheetIndex($active_sheet);
                            $xls_writer->getActiveSheet()->setCellValueByColumnAndRow($excel_column, $excel_row_arr[$active_sheet], $cell_info);
                        } else {
                            $xls_writer->getActiveSheet()->setCellValueByColumnAndRow($excel_column, $excel_row, $cell_info);
                        }
                        $excel_column++;
                    }
                    $excel_row_arr[$active_sheet]++;
                }
                /*
                else
                {
                    foreach ($row as $field=>$value)
                    {
                        $heading[$field] = preg_match('/["|\\'.$this->delimiter.']/', (string)$field) ? '"'.str_replace('"', '""', $field).'"' : $field;
                        $line[] = preg_match('/["|\\'.$this->delimiter.']/', (string)$value) ? '"'.str_replace('"', '""', $value).'"' : $value;
                    }
                }
                $output .= implode($this->delimiter, $line)."\r\n";
                */
                $excel_row++;
            }
            $xls_writer->setActiveSheetIndex(0);
            //With excel 2003 or before format
            $objWriter = IOFactory::createWriter($xls_writer, 'Excel5');
            ob_start();
            $objWriter->save('php://output');
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    public function set_input($value)
    {
        $this->input = $value;
    }

    public function set_map_file($value)
    {
        $this->map_file = $value;
    }
}

// XML to VO
class XmlToVo implements outConverter
{

    private $input;
    private $map_file;

    function XmlToVo($input = "", $map_file = "")
    {
        $CI =& get_instance();
        $this->load = $CI->load;

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
                                    $field = camelcase2underscore(substr($rsval, 1));
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

    public function set_input($value)
    {
        $this->input = $value;
    }

    public function set_map_file($value)
    {
        $this->map_file = $value;
    }
}


