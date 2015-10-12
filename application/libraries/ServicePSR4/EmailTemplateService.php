<?php
namespace ESG\Panther\Service;

class EmailTemplateService extends BaseService
{
    public function getVariablesInTemplate($template_string = "", $start_delimiter = "[:", $end_delimiter = ":]")
    {
        /* ======================================================================
            This function gets all the variables in a template string, usually
            encapsulated by [::]
            e.g. [:client_id:], [:so_no:]
        ====================================================================== */

        $var_with_count_arr = $var_arr = $search_var_start = $search_var_end = array();
        $count_of_var = array();

        if ($template_string && $start_delimiter && $end_delimiter) {
            if ($search_var_start = explode($start_delimiter, $template_string)) {
                unset($search_var_start[0]);    # the array before the first "[:" is unwanted
                foreach ($search_var_start as $key => $value) {
                    # any array without ":]" in should not be a variable
                    if (strpos($value, "$end_delimiter")) {
                        $search_var_end = explode("$end_delimiter", trim($value));
                        $var_arr[] = trim($search_var_end[0]); # anything after ":]" is unwanted
                    }
                }

                # count number of occurances for each variable
                if ($count_of_var = array_count_values($var_arr)) {
                    foreach ($count_of_var as $key => $value) {
                        $var_with_count_arr[] = "$key::$value";
                    }
                }
            }
        }

        return $var_with_count_arr;
    }
}
