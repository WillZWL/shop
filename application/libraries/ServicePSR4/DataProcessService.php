<?php
namespace ESG\Panther\Service;

class DataProcessService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
    }

    private $file;
    private $map_file;
    private $first_line_heading;
    private $delimiter;

    private function initialize($file, $map_file, $first_line_heading , $delimiter)
    {
        if (empty($file) || empty($map_file)) {
            return FALSE;
        }
        $this->file = $file;
        $this->map_file = $map_file;
        $this->first_line_heading = $first_line_heading;
        $this->delimiter = $delimiter;
    }

    public function convert($file = '', $map_file = '', $first_line_heading = TRUE, $delimiter = ",")
    {
        if (!($this->initialize($file, $map_file, $first_line_heading, $delimiter = ","))) {
            if ($this->validFile()) {
                $map_result = $this->handleMappingFile();
                if (!empty($map_result['vo'])) {
                    $obj_list = $this->fileToObj();
                    return $obj_list;
                }
                if (!empty($map_result['tb'])) {
                    $this->fileToInterface();
                }
            }
        } else {
            return FALSE;
        }
    }


    public function import($file, $mapping_file)
    {

    }

    private function fileToObj()
    {
        $mapping_result = $this->handleMappingFile();
        $mapping_value = array_values($mapping_result['mapping']);
        $vo_name = $mapping_result['vo'];
        $voClassName = ucfirst($this->underscore2camelcase($vo_name));
        $handle = fopen($this->file, "rb");
        $rs = [];
        while (!feof($handle)) {
            $line = trim(fgets($handle));
            $line = explode($this->delimiter, $line);
            $vo = New $voClassName();
            foreach ($mapping_value as $key => $value) {
                $value = array_values($value);
                if (!empty($value[0])) {
                    $set_fct_name = $this->underscore2camelcase('set_' . $value[0]);
                    $vo->$set_fct_name(str_replace('"', '', $line[$key]));
                }
            }
            $rs[] = $vo;
        }
        return (object)$rs;
    }

    private function fileToInterface()
    {
        $mapping_result = $this->handleMappingFile();
        $mapping_value = array_values($mapping_result['mapping']);
        $tb_name = $mapping_result['tb'];

        $sql = "INSERT INTO ".$tb_name. " (";
        foreach ($mapping_value as $key => $value) {
            $sql .= $value.",";
        }
        $sql = substr($sql, 0, -1).")  VALUES ";
        $handle = fopen($this->file, "rb");
        $i = 0;
        while (!feof($handle)) {
            $line = trim(fgets($handle));
            $line = explode($this->delimiter, $line);
            if ($i < 10) {
                $sql_line .= '(';
                foreach ($line as $sql_val) {
                    $sql_line .= $sql_val.",";
                }
                $sql_line .= substr($sql_line, 0 , -1). "), ";
            }

            $i++;
        }
        $sql .= $sql_line;
        return $sql;
    }

    public function XmlToObj()
    {
        return FALSE;
    }

    public function XmlToInterface()
    {
        return FALSE;
    }

    public function ObjlistToCsv($obj_list, $mapping_file, $delimiter = ',')
    {
        if (include($mapping_file)) {
            $mapping_value = array_values($mapping);
            $mapping_key = array_keys($mapping);
            $csv_header = implode($delimiter, $mapping_value);
            $csv_header .= "\r\n";
            $csv_content = '';
            foreach ($obj_list as $obj) {
                $csv_line_arr = [];
                foreach ($mapping_key as $get_name) {
                    if (!empty($get_name)) {
                        $get_fct_name = $this->underscore2camelcase('get' . $get_name);
                        $csv_line_arr[] = "\"".$obj->$get_fct_name()."\"";
                    } else {
                        $csv_line_arr[] = " ";
                    }
                }
                $csv_content .= implode($delimiter, $csv_line_arr)."\r\n";
            }
            $csv = $csv_header. $csv_content;
            return $csv;
        } else {
            return false;
        }
    }

    public function ObjToXml()
    {
        return FALSE;
    }

    private function validFile()
    {
        $handle = fopen($this->file, "rb");
        $map_result = $this->handleMappingFile();
        $mapping = $map_result['mapping'];
        if ($this->delimiter == ',') {
            $file_header = fgetcsv($handle);
        } else {
            $first_line = trim(fgets($handle));
            $file_header = explode($this->delimiter, $first_line);
        }
        if (is_array($file_header)) {
            foreach ($file_header as $key => $value) {
                $map_value = $mapping[$key];
                $map_key = array_keys($map_value);
                if (is_numeric($map_key)) {
                    if ($map_key != $key) {
                        return false;
                    }
                }
                if (is_string($map_key)) {
                    if ($map_key != $value) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    private function handleMappingFile()
    {
        if (include($this->map_file)) {
            $mapping = $mapping_file['mapping'];
            if ($mapping_file['type'] == 'vo') {
                $result = array('vo'=>$mapping_file['store'], 'mapping'=>$mapping);
            } elseif ($mapping_file['type'] == 'tb') {
                $result = array('tb'=>$mapping_file['store'], 'mapping'=>$mapping);
            } else {
                $result = false;
            }
            return $result;
        } else {
            return false;
        }
    }

    private function underscore2camelcase($name)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
    }
}