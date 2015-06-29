<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Pagination_service extends Base_service
{

    var $full_tag_style = array();
    var $open_tag_style = array();
    var $first_tag_style = array();
    var $last_tag_style = array();
    var $next_tag_style = array();
    var $prev_tag_style = array();
    var $cur_tag_style = array();
    var $num_tag_style = array();
    var $anchor_style = '';
    var $show_count_tag = FALSE;
    var $count_tag_open = '<div align="right" class="count_tag">';
    var $count_tag_close = '</div>';
    var $count_tag_msg = 'Total number of record(s)';
    var $msg_br = FALSE;
    var $row_per_page = "";

    function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->config =& $CI->config;
        $this->load = $CI->load;
        $CI->load->library('pagination');
        $this->pagination = $CI->pagination;

        //Modify by Tommy, set default value;
        // $this->pagination->full_tag_open = "<table class='page' align='right' cellpadding=0 cellspacing=0 border=0><tr><td><table cellpadding=0 cellspacing=1 border=0><tr>";
        // $this->pagination->full_tag_close = "</tr></table></td></tr></table>";
        // $this->pagination->first_tag_open = "<td>";
        // $this->pagination->first_tag_close = "</td>";
        // $this->pagination->last_tag_open = "<td>";
        // $this->pagination->first_tag_close = "</td>";
        // $this->pagination->last_tag_close = "</td>";
        // $this->pagination->next_tag_open = "<td>";
        // $this->pagination->next_tag_close = "</td>";
        // $this->pagination->prev_tag_open = "<td>";
        // $this->pagination->prev_tag_close = "</td>";
        // $this->pagination->num_tag_open = "<td>";
        // $this->pagination->num_tag_close = "</td>";
        // $this->pagination->cur_tag_open = "<td class='current'>";
        // $this->pagination->cur_tag_close = "</td>";
        // $this->pagination->first_link = htmlspecialchars("|<");
        // $this->pagination->last_link = htmlspecialchars(">|");
        // $this->pagination->next_link = htmlspecialchars("> ");
        // $this->pagination->prev_link = htmlspecialchars("<");
        // $this->pagination->num_links = 2;


    }


    function set_anchor_style($style)
    {
        $this->anchor_style = $style;
    }

    function set_count_tag_open($tag)
    {
        $this->count_tag_open = $tag;

    }

    function set_count_tag_close($tag)
    {
        $this->count_tag_close = $tag;

    }

    function set_row_per_page($value)
    {
        if(!is_numeric($value))
        {
            $this->row_per_page = "";
        }
        else
        {
            $this->row_per_page = $value;
        }
    }

    function set_show_count_tag($value)
    {
        $this->show_count_tag = $value;
    }

    function get_num_records_per_page($aid="")
    {
        //not yet implemented, return 20 as default
        //if($aid == "")
        if($this->row_per_page == "")
        {
            $ret =  20;
        }
        else
        {
            $ret = $this->row_per_page;
        }
        return $ret;
    }

    function initialize($config_array = array(), $style_array = array())
    {
        if(count($style_array) > 0 ){
            foreach($style_array as $key=>$val)
            {
                if(isset($this->$key) && trim($key) != ""  && is_array($val))
                {
                    $this->$key = $val;
                }
            }
        }

        if (count($config_array) > 0)
        {
            foreach ($config_array as $key => $val)
            {
                if (isset($this->pagination->$key))
                {
                    $this->pagination->$key = $val;
                }
            }
        }
    }

        function add_tag_style($tag, $style_tag){
                $attribute = "";
                if(count($style_tag) == 0)
                {
                        return $tag;
                }
                else
                {
            $style = "";
            if(is_array($style_tag))
            {
                            foreach($style_tag as $key=>$value)
                            {
                                    if(trim($key) != "" && trim($value) != "")
                                    {
                                            $style .= " ".$key."=\"".$value."\" ";
                                    }
                                    $tag = ereg_replace(">$"," ".$style.">",$tag);
                            }
            }
                        return $tag;
                }
        }

    function create_links_with_style()
    {
        // If our item count or per-page total is zero there is no need to continue.
        if ($this->pagination->total_rows == 0 OR $this->pagination->per_page == 0)
        {
           return '';
        }

        // Calculate the total number of pages
        $num_pages = ceil($this->pagination->total_rows / $this->pagination->per_page);

        // Is there only one page? Hm... nothing more to do here then.
/*      if ($num_pages == 1)
        {
            return '';
        }
*/
        // Determine the current page number.
        $CI =& get_instance();

        if ($CI->config->item('enable_query_strings') === TRUE OR $this->pagination->page_query_string === TRUE)
        {
            if ($CI->input->get($this->pagination->query_string_segment) != 0)
            {
                $this->pagination->cur_page = $CI->input->get($this->pagination->query_string_segment);

                // Prep the current page - no funny business!
                $this->pagination->cur_page = (int) $this->pagination->cur_page;
            }
        }
        else
        {
            if ($CI->uri->segment($this->pagination->uri_segment) != 0)
            {
                $this->pagination->cur_page = $CI->uri->segment($this->pagination->uri_segment);

                // Prep the current page - no funny business!
                $this->pagination->cur_page = (int) $this->pagination->cur_page;
            }
        }

        $this->pagination->num_links = (int)$this->pagination->num_links;

        if ($this->pagination->num_links < 1)
        {
            show_error('Your number of links must be a positive number.');
        }

        if ( ! is_numeric($this->pagination->cur_page))
        {
            $this->pagination->cur_page = 0;
        }

        // Is the page number beyond the result range?
        // If so we show the last page
        $not_exceed = 1;

        if ($this->pagination->cur_page > $this->pagination->total_rows)
        {
            $this->pagination->cur_page = ($num_pages - 1) * $this->pagination->per_page;
            $not_exceed = 0;
        }

        $uri_page_number = $this->pagination->cur_page;
        $this->pagination->cur_page = floor(($this->pagination->cur_page/$this->pagination->per_page) + 1);

        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with
        $start = (($this->pagination->cur_page - $this->pagination->num_links) > 0) ? $this->pagination->cur_page - ($this->pagination->num_links - 1) : 1;
        $end   = (($this->pagination->cur_page + $this->pagination->num_links) < $num_pages) ? $this->pagination->cur_page + $this->pagination->num_links : $num_pages;

        // Is pagination being used over GET or POST?  If get, add a per_page query
        // string. If post, add a trailing slash to the base URL if needed
        if ($CI->config->item('enable_query_strings') === TRUE OR $this->pagination->page_query_string === TRUE)
        {
            $this->pagination->base_url = preg_replace('/[?|&]per_page=\d{0,}/', "", $this->pagination->base_url);
            if(strpos($this->pagination->base_url,'?') !== FALSE)
            {
                $connector = '&amp;';
            }
            else
            {
                $connector = '?';
            }
            $this->pagination->base_url = rtrim($this->pagination->base_url).$connector.$this->pagination->query_string_segment.'=';
        }
        else
        {
            $this->pagination->base_url = rtrim($this->pagination->base_url, '/') .'/';
        }

        // And here we go...
        $output = '';

        // Render the "First" link
        if  ($this->pagination->cur_page > ($this->pagination->num_links + 1) || !$not_exceed)
        {
            $output .= $this->add_tag_style($this->pagination->first_tag_open, $this->first_tag_style).'<A HRef="'.$this->pagination->base_url.'">'.$this->pagination->first_link.'</a>'.$this->pagination->first_tag_close;
        }

        // Render the "previous" link
        if  ($this->pagination->cur_page != 1 && $not_exceed)
        {
            $i = $uri_page_number - $this->pagination->per_page;
            if ($i == 0) $i = '';
            $output .= $this->add_tag_style($this->pagination->prev_tag_open, $this->prev_tag_style).'<a href="'.$this->pagination->base_url.$i.'">'.$this->pagination->prev_link.'</a>'.$this->pagination->prev_tag_close;
        }

        // Write the digit links
        if ($this->pagination->num_links < 2)
        {
            if ($not_exceed)
            {
                $output .= $this->add_tag_style($this->pagination->cur_tag_open,$this->cur_tag_style).$this->pagination->cur_page.$this->pagination->cur_tag_close;
            }
            else
            {
                $output .= $this->add_tag_style($this->pagination->num_tag_open,$this->num_tag_style).'<a href="'.$this->pagination->base_url.(($this->pagination->cur_page* $this->pagination->per_page) - $this->pagination->per_page).'" '.$this->anchor_style.'>'.$this->pagination->cur_page.'</a>'.$this->pagination->num_tag_close;
            }
        }
        else
        {
            for ($loop = $start -1; $loop <= $end; $loop++)
            {
                $i = ($loop * $this->pagination->per_page) - $this->pagination->per_page;

                if ($i >= 0)
                {
                    if ($this->pagination->cur_page == $loop && $not_exceed)
                    {
                        $output .= $this->add_tag_style($this->pagination->cur_tag_open,$this->cur_tag_style).$loop.$this->pagination->cur_tag_close; // Current page
                    }
                    else
                    {
                        $n = ($i == 0) ? '' : $i;
                        $output .= $this->add_tag_style($this->pagination->num_tag_open,$this->num_tag_style).'<a href="'.$this->pagination->base_url.$n.'" '.$this->anchor_style.'>'.$loop.'</a>'.$this->pagination->num_tag_close;
                    }
                }
            }
        }

        // Render the "next" link
        if ($this->pagination->cur_page < $num_pages)
        {
            $output .= $this->add_tag_style($this->pagination->next_tag_open,$this->next_tag_style).'<a href="'.$this->pagination->base_url.($this->pagination->cur_page * $this->pagination->per_page).'">'.$this->pagination->next_link.'</a>'.$this->pagination->next_tag_close;
        }

        // Render the "Last" link
        if (($this->pagination->cur_page + $this->pagination->num_links) < $num_pages)
        {
            $i = (($num_pages * $this->pagination->per_page) - $this->pagination->per_page);
            $output .= $this->add_tag_style($this->pagination->last_tag_open,$this->last_tag_style).'<a href="'.$this->pagination->base_url.$i.'">'.$this->pagination->last_link.'</a>'.$this->pagination->last_tag_close;
        }

        // Kill double slashes.  Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $output = preg_replace("#([^:])//+#", "\\1/", $output);

        // Add the wrapper HTML if exists
        $output = $this->add_tag_style($this->pagination->full_tag_open,$this->full_tag_style).$output.$this->pagination->full_tag_close;

        //Add the total number of row Notice
        if ($this->show_count_tag)
        {
            if ($this->msg_br)
            {
                $output = $output."<br><br><p style='padding-top:0px;'>".$this->count_tag_open."&nbsp;".$this->count_tag_msg.": ".$this->pagination->total_rows." &nbsp; ".$this->count_tag_close."</p>";
            }
            else
            {
                $output = $this->count_tag_open."&nbsp;".$this->count_tag_msg.": ".$this->pagination->total_rows." &nbsp; ".$this->count_tag_close.$output;
            }
        }

        return $output;
    }
}
