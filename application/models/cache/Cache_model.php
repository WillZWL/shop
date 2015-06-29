<?php
class Cache_model extends CI_Model
{
    function load_cache($function_name = '', $params = array())
    {
        $callable = array($this, $function_name);
        if (is_callable($callable))
        {
            return call_user_func_array($callable, array($params));
        }
        else
        {
            return NULL;
        }
    }

    function write_cache($function_name = '', $params = array())
    {
        $callable = array($this, $function_name);
        if (is_callable($callable))
        {
            return call_user_func_array($callable, array($params));
        }
        else
        {
            return NULL;
        }
    }
}