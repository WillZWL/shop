<?php

Class myPagination
{

    var $full_tag_style = array();
    var $first_link_style = array();
    var $last_link_style = array();
    var $next_tag_style = array();
    var $prev_tag_style = array();
    var $cur_tag_style = array();
    var $num_tag_style = array();
    var $anchor_style = '';


    function myPagination($config)
    {
        $CI =& get_instance();
        $this->config =& $CI->config;
        $this->load = $CI->load;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
    }

    function create_link_with_style()
    {
        // If our item count or per-page total is zero there is no need to continue.
        if ($this->total_rows == 0 OR $this->per_page == 0) {
            return '';
        }

        // Calculate the total number of pages
        $num_pages = ceil($this->total_rows / $this->per_page);

        // Is there only one page? Hm... nothing more to do here then.
        if ($num_pages == 1) {
            return '';
        }

        // Determine the current page number.
        $CI =& get_instance();

        if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE) {
            if ($CI->input->get($this->query_string_segment) != 0) {
                $this->cur_page = $CI->input->get($this->query_string_segment);

                // Prep the current page - no funny business!
                $this->cur_page = (int)$this->cur_page;
            }
        } else {
            if ($CI->uri->segment($this->uri_segment) != 0) {
                $this->cur_page = $CI->uri->segment($this->uri_segment);

                // Prep the current page - no funny business!
                $this->cur_page = (int)$this->cur_page;
            }
        }

        $this->num_links = (int)$this->num_links;

        if ($this->num_links < 1) {
            show_error('Your number of links must be a positive number.');
        }

        if (!is_numeric($this->cur_page)) {
            $this->cur_page = 0;
        }

        // Is the page number beyond the result range?
        // If so we show the last page
        if ($this->cur_page > $this->total_rows) {
            $this->cur_page = ($num_pages - 1) * $this->per_page;
        }

        $uri_page_number = $this->cur_page;
        $this->cur_page = floor(($this->cur_page / $this->per_page) + 1);

        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with
        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

        // Is pagination being used over GET or POST?  If get, add a per_page query
        // string. If post, add a trailing slash to the base URL if needed
        if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE) {
            $this->base_url = rtrim($this->base_url) . '&amp;' . $this->query_string_segment . '=';
        } else {
            $this->base_url = rtrim($this->base_url, '/') . '/';
        }

        // And here we go...
        $output = '';

        // Render the "First" link
        if ($this->cur_page > ($this->num_links + 1)) {
            $output .= $this->add_tag_style($this->first_tag_open, $this->first_tag_style) . '<a href="' . $this->base_url . '">' . $this->first_link . '</a>' . $this->first_tag_close;
        }

        // Render the "previous" link
        if ($this->cur_page != 1) {
            $i = $uri_page_number - $this->per_page;
            if ($i == 0) $i = '';
            $output .= $this->add_tag_style($this->prev_tag_open, $this->prev_tag_style) . '<a href="' . $this->base_url . $i . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
        }

        // Write the digit links
        for ($loop = $start - 1; $loop <= $end; $loop++) {
            $i = ($loop * $this->per_page) - $this->per_page;

            if ($i >= 0) {
                if ($this->cur_page == $loop) {
                    $output .= $this->add_tag_style($this->cur_tag_open, $this->cur_tag_style) . $loop . $this->cur_tag_close; // Current page
                } else {
                    $n = ($i == 0) ? '' : $i;
                    $output .= $this->add_tag_style($this->num_tag_open, $this->num_tag_style) . '<a href="' . $this->base_url . $n . '"' . $this->anchor_style . '>' . $loop . '</a>' . $this->num_tag_close;
                }
            }
        }

        // Render the "next" link
        if ($this->cur_page < $num_pages) {
            $output .= $this->add_tag_style($this->next_tag_open, $this->next_tag_style) . '<a href="' . $this->base_url . ($this->cur_page * $this->per_page) . '">' . $this->next_link . '</a>' . $this->next_tag_close;
        }

        // Render the "Last" link
        if (($this->cur_page + $this->num_links) < $num_pages) {
            $i = (($num_pages * $this->per_page) - $this->per_page);
            $output .= $this->add_tag_style($this->last_tag_open, $this->last_tag_style) . '<a href="' . $this->base_url . $i . '">' . $this->last_link . '</a>' . $this->last_tag_close;
        }

        // Kill double slashes.  Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $output = preg_replace("#([^:])//+#", "\\1/", $output);

        // Add the wrapper HTML if exists
        $output = $this->add_style_tag($this->full_tag_open, $this->full_tag_style) . $output . $this->full_tag_close;

        return $output;
    }

    function add_tag_style($tag, $style_tag)
    {
        $attribute = "";
        if (count($style_tag) == 0) {
            return $tag;
        } else {
            foreach ($style_tag as $key => $value) {
                if (trim($key) != "" && trim($value) != "") {
                    $style .= $key . "=\"" . $value . "\" ";
                }

                $tag = ereg_replace(">$", $style . ">", $tag);
            }

            return $tag;
        }
    }
}

