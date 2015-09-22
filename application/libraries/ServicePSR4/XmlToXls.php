<?php
namespace ESG\Panther\Service;

include('outConverter.php');

class XmlToXls implements outConverter
{
    private $input;
    private $map_file;

    function XmlToXls($input = "", $map_file = "", $first_line_heading = TRUE, $sheet_map_file = "", $display_sheet_key = TRUE)
    {
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
        $xls_writer = new \PHPExcel();

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

    public function setInput($value)
    {
        $this->input = $value;
    }

    public function setMapFile($value)
    {
        $this->map_file = $value;
    }
}