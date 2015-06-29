<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Category_service extends Base_service
{
    private $brand_service;
    private $ext_dao;

    public function __construct(){
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH."libraries/dao/Category_dao.php");
        $this->set_dao(new Category_dao());
        include_once(APPPATH."libraries/dao/Category_extend_dao.php");
        $this->set_ext_dao(new Category_extend_dao());
        include_once(APPPATH."libraries/dao/Category_content_dao.php");
        $this->set_cc_dao(new Category_content_dao());
        include_once(APPPATH."libraries/service/Brand_service.php");
        $this->set_brand_service(new Brand_service());
        include_once(APPPATH."libraries/dao/Category_banner_dao.php");
        $this->set_cat_ban_dao(new Category_banner_dao());
    }

    public function get_menu_list_data($lang_id, $platform_id)
    {
        $data = array();
        $this->load->helper('url');
        if (!($menu_list = $this->get_menu_list_w_platform_id($lang_id, $platform_id)))
        {
            $menu_list = $this->get_menu_list_w_lang($lang_id);
        }

        if ($menu_list)
        {
            $menulist = $menu_list["list"];
            $allcatlist = $menu_list["allcat"];
            $n_search = array("  ", " ");
            $n_replace = array(" ", "-");

            if ($menulist[1][0])
            {
                $i = 0;
                foreach ($menulist[1][0] as $cat_obj)
                {
                    $name = str_replace($n_search, $n_replace, parse_url_char($cat_obj->get_name()));
                    $data["menu"][$i]["cat_id"] = $cat_obj->get_id();
                    $data["menu"][$i]["display_name"] = $cat_obj->get_name();
                    $data["menu"][$i]["name"] = $name;
                    $data["menu"][$i]["link"] = "{$name}/cat/?catid={$cat_obj->get_id()}";
                    //$data["menu"][$i]["link"] = "cat/?catid={$cat_obj->get_id()}";
                    if ($allcatlist[$cat_obj->get_id()])
                    {
                        $j = 0;
                        foreach ($allcatlist[$cat_obj->get_id()] as $subcat_obj)
                        {
                            $name = str_replace($n_search, $n_replace, parse_url_char($subcat_obj->get_name()));
                            $data["sub_menu"][$i][$j]["display_name"] = $subcat_obj->get_name();
                            $data["sub_menu"][$i][$j]["name"] = $name;
                            $data["sub_menu"][$i][$j]["link"] = "search/?from=c&catid=".$subcat_obj->get_id();
                            $j++;
                        }
                    }
                    $i++;
                }
            }
        }

        return $data;

    }


    public function get_list_with_child_count($level,$id="",$classname="Category_count_dto")
    {
        return $this->get_dao()->get_item_with_child_count($level,$id,$classname);
    }

    public function get_item_with_pop_child_count($level,$id="")
    {
        return $this->get_dao()->get_item_with_pop_child_count($level,$id,"Category_count_dto");
    }

    // public function get($id="")
    // {
    //  if($id == "")
    //  {
    //      $ret = $this->get_dao()->get();
    //  }
    //  else
    //  {
    //      $ret = $this->get_dao()->get(array("id"=>$id));
    //  }

    //  return $ret;
    // }

    public function get_parent($level, $id, $classname="View_sub_cat_dto")
    {
        return $this->get_dao()->get_parent($level,$id,$classname);
    }

    // public function update($obj)
    // {
    //  return $this->get_dao()->update($obj);
    // }

    public function add($obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function load_vo()
    {
        $this->get_dao()->include_vo();
    }

    public function get_cat_list_index($where,$option)
    {
        $data["category_list"] = $this->get_dao()->get_list_index($where,$option,$this->get_dao()->get_vo_classname());

        $data["total"] = $this->get_dao()->get_list_index($where,array("num_rows"=>1));
        return $data;
    }

    public function get_menu_list($where=array(), $option=array())
    {
        $data["list"] = $data["allcat"] = array();
        $objlist = $this->get_dao()->get_list($where, $option);

        if ($objlist)
        {
            foreach($objlist as $obj)
            {
                $data["list"][$obj->get_level()][$obj->get_parent_cat_id()][] = $obj;
                $data["allcat"][$obj->get_parent_cat_id()][] = $obj;
            }
        }

/*
        $objlist = $this->get_dao()->get_menu_all_cat_list(array(), array("limit"=>-1));
        if ($objlist)
        {
            foreach($objlist as $obj)
            {
                $data["allcat"][$obj->get_parent_cat_id()][] = $obj;
            }
        }
*/
        return $data;
    }

    public function get_menu_list_w_lang($lang_id)
    {
        return $this->get_dao()->get_menu_list_w_lang($lang_id);
    }

    public function get_menu_list_w_platform_id($lang_id="", $platform_id="")
    {
        return $this->get_dao()->get_menu_list_w_platform_id($lang_id, $platform_id);
    }

    public function get_display_list($catid, $type="cat",$brand="",$platform_id="WSGB",$min_price="",$max_price="")
    {
        $obj = $this->get_dao()->get(array("id"=>$catid));
        if($obj === FALSE)
        {
            return FALSE;
        }

        if(empty($obj))
        {
            return NULL;
        }
        else
        {
            if($obj->get_level() == 1)
            {
                if($type == "cat")
                {
                    return $this->get_dao()->retrieve_catlist_for_scat($catid,$brand,$platform_id);
                }
                elseif($type == "price")
                {
                    return $this->get_dao()->retrieve_pricelist_for_cat($catid,$brand,$platform_id,$min_price,$max_price);
                }
                else
                {
                    return $this->get_dao()->retrieve_brandlist_for_cat($catid,$brand,$platform_id);
                }
            }
            else if ($obj->get_level() == 2)
            {
                if($type == "cat")
                {
                    return $this->get_dao()->retrieve_catlist_for_sscat($catid,$brand,$platform_id);
                }
                elseif($type == "price")
                {
                    return $this->get_dao()->retrieve_pricelist_for_scat($catid,$brand,$platform_id,$min_price,$max_price);
                }
                else
                {
                    return $this->get_dao()->retrieve_brandlist_for_scat($catid,$brand,$platform_id);
                }

            }
            else if ($obj->get_level() == 3)
            {
                if($type == "cat")
                {
                    return NULL;
                }
                elseif($type == "price")
                {
                    return $this->get_dao()->retrieve_pricelist_for_sscat($catid,$brand,$platform_id,$min_price,$max_price);
                }
                else
                {
                    return $this->get_dao()->retrieve_brandlist_for_sscat($catid,$brand,$platform_id);
                }
            }
            else{
                return NULL;
            }
        }
    }


    public function get_display_catlist($catid,$data=array())
    {
        $obj = $this->get_dao()->get(array("id"=>$catid));
        $data[$obj->get_level()] = array("name"=>$obj->get_name(),"id"=>$obj->get_id());
        if($obj->get_level() == 1)
        {
            return $data;
        }
        else
        {
            return $this->get_display_catlist($obj->get_parent_cat_id(),$data);
        }
    }

    public function get_colour_list()
    {
        include_once APPPATH."libraries/dao/Colour_dao.php";
        $color_dao = new Colour_dao();

        $list = $color_dao->get_list();

        $ret = array();

        foreach($list as $obj)
        {
            $ret[$obj->get_id()] = $obj->get_name();
        }

        return $ret;
    }

    private function build_category_tree($me, $parentID)            // Recursive function to get all of the children...unlimited depth
    {
        $list = $this->get_dao()->get_list(array("parent_cat_id"=>$parentID), array("result_type"=>"array"));

        $tempTree = NULL;
        foreach($list AS $child)
        {
            if ( $child['cat_id'] != $child['parent_cat_id'] )
            {
                //$depth++;     // Increment depth as we are building this child's child tree

                $tempTree[$child['id']]["name"] = $child['name'];
                $tempTree[$child['id']]["child"] = $this->build_category_tree($tempTree[$child['cat_id']], $id);

                //$depth--;     // Decrement depth we're done building the child's child tree.
                //array_push($exclude, $child['id']);           // Add the item to the exclusion list
            }
        }

        return $tempTree;       // Return the entire child tree
    }

    public function get_listed_cat($platform_id = "")
    {
        return $this->get_dao()->get_listed_cat($platform_id);
    }

    public function get_full_cat_list()
    {
        return $this->get_dao()->get_full_cat_list();
    }

    //no use, old version
    public function get_listed_cat_tree()
    {
        $sitemap = array();
        $depth = 0;

        $list = $this->get_dao()->get_list(array("level"=>1), array("result_type"=>"array"));

        $sitemap = array();
        foreach($list AS $item)
        {
            // database has an item named "base", this is not required and confusing as it is a parent of itself,
            // we will filter it out and do not include in the output

            $id = $item["id"];
            if ($id != $item["parent_cat_id"])
            {
                $sitemap[$id] = array();
                $sitemap[$id]["name"] = $item["name"];
                $sitemap[$id]["child"] = $this->build_category_tree($sitemap[$id], $id);
            }
        }

        return;

        echo "<table>";

        $i = 0;
        foreach ($sitemap as $mk => $maincat)
        {
            if (($i % 3) == 0) echo "<tr>";

            echo "<td>";

            // using relative links here so that domain can be easily ported
            echo "<a href=\"cat/?catid={$mk}\">";
            echo $maincat['name'];
            echo "</a><br>";

            foreach ($maincat['child'] as $sk => $subcat)

                // using relative links here so that domain can be easily ported
                echo "<a href=\"cat/?from=c&catid={$sk}\">";
            echo " - " . $subcat['name'];
            echo "</a><br>";

            echo "</td>";

            if (($i % 3) == 2) echo "</tr>";

            $i++;

        }

        //$ret = $this->get_dao()->get_list(array("level"=>1,"orderby"=>"id"));
        if (1==0)
        {
            $row = $this->get_dao()->get_num_rows();
            $list = $this->get_dao()->get_list();
            foreach($list as $obj)
            {
                if ($obj->get_level() == 1)
                    echo $obj->get_id() . "<br>";
            }
        }

        //var_dump ($ret);
        //method 1
        $cat_list = $this->get_dao()->get_cat_list_w_lang(get_lang_id());
        $sitemap = array();
        foreach($cat_list AS $cat_arr)
        {
            $sitemap[$cat_arr["id"]] = $cat_arr["name"];
        }

        //method2
        $array = $this->get_dao()->get_listed_cat_tree(get_lang_id());
        $ret = array();
        foreach($array AS $obj)
        {
            $ret[$obj["cat_name"]][$obj["sub_cat_name"]];
        }
        $query = $this->db->last_query();
        //var_dump ($query);

        $cat_list = array();
        $cat = array();
        $sub_cat_list = array();
        $sub_cat = array();

        foreach ($array as $row)
        {
            $sub_sub_cat = array('sub_sub_cat_id' => $row['sub_sub_cat_id'],'sub_sub_cat_name' => $row['sub_sub_cat_name']);


            if ($cat['cat_id'] != $row['cat_id'])
            {
                if (!empty($cat['cat_id']))
                {
                    $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
                    array_push($sub_cat_list, $sub_cat);
                    $cat['sub_cat_list'] = $sub_cat_list;
                    $cat['brand_list'] = $this->get_brand_service()->get_listed_brand_by_cat($cat['cat_id']);
                    array_push($cat_list, $cat);
                }

                $sub_sub_cat_list = array();
                $sub_cat = array('sub_cat_id' => $row['sub_cat_id'],
                        'sub_cat_name' => $row['sub_cat_name']);
                $sub_cat_list = array();
                $cat = array('cat_id' => $row['cat_id'],
                        'cat_name' => $row['cat_name']);
            }
            else if ($sub_cat['sub_cat_id'] != $row['sub_cat_id'])
            {
                $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
                array_push($sub_cat_list, $sub_cat);

                $sub_sub_cat_list = array();
                $sub_cat = array('sub_cat_id' => $row['sub_cat_id'],
                        'sub_cat_name' => $row['sub_cat_name']);
            }

            array_push($sub_sub_cat_list, $sub_sub_cat);
        }

        $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
        array_push($sub_cat_list, $sub_cat);
        $cat['sub_cat_list'] = $sub_cat_list;
        $cat['brand_list'] = $this->get_brand_service()->get_listed_brand_by_cat($cat['cat_id']);
        array_push($cat_list, $cat);

        //var_dump ($cat_list);


        return array('cat_list' => $cat_list);
    }

    public function get_favourite_category_list($platform_id="WSGB")
    {
        return $this->get_dao()->get_favourite_category_list(20, $platform_id);
    }

    public function get_list_w_key($where=array(), $option=array())
    {
        $data = array();
        if ($objlist = $this->get_list($where, $option))
        {
            foreach ($objlist as $obj)
            {
                $data[$obj->get_id()] = $obj;
            }
        }
        return $data;
    }

    public function get_name_list_w_id_key($where=array(), $option=array())
    {
        $option["result_type"] = "array";
        $rslist = array();
        if ($ar_list = $this->get_list($where, $option))
        {
            foreach ($ar_list as $rsdata)
            {
                $rslist[$rsdata["id"]] = $rsdata["name"];
            }
        }
        return $rslist;
    }

    public function get_cat_ext_w_key($where=array(), $option=array())
    {
        if ($obj_list = $this->get_ext_dao()->get_list($where, $option))
        {
            $data = array();
            foreach ($obj_list as $obj)
            {
                $data[$obj->get_cat_id()][$obj->get_lang_id()] = $obj;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_best_selling_cat($platform="WEBGB", $lang_id="en")
    {
        return $this->get_dao()->get_best_selling_cat($platform, $lang_id);
    }

    public function get_cat_ext_default_w_key_list($where=array(), $option=array())
    {
        return $this->get_ext_dao()->get_cat_ext_default_w_key_list($where, $option);
    }

    public function set_ext_dao($dao)
    {
        $this->ext_dao = $dao;
    }

    public function get_ext_dao()
    {
        return $this->ext_dao;
    }

    public function set_cc_dao($dao)
    {
        $this->cc_dao = $dao;
    }

    public function get_cc_dao()
    {
        return $this->cc_dao;
    }

    public function set_brand_service($service)
    {
        $this->brand_service = $service;
    }

    public function get_brand_service()
    {
        return $this->brand_service;
    }

    public function set_cat_ban_dao($dao)
    {
        $this->cat_ban_dao = $dao;
    }

    public function get_cat_ban_dao()
    {
        return $this->cat_ban_dao;
    }

    public function get_cat_ban($where)
    {
        return $this->get_cat_ban_dao()->get($where);
    }

    public function get_cat_ban_list($lang_id)
    {
        return $this->get_cat_ban_dao()->get_cat_ban_list($lang_id);
    }

    public function insert_cat_ban($obj)
    {
        return $this->get_cat_ban_dao()->insert($obj);
    }

    public function update_cat_ban($obj)
    {
        return $this->get_cat_ban_dao()->update($obj);
    }

    public function get_cat_cont_obj($where=array())
    {
        return $this->get_cc_dao()->get($where);
    }

    public function get_cat_cont_list($where=array(), $option=array())
    {
        $cc_list = $this->get_cc_dao()->get_list($where, $option);
        foreach($cc_list AS $cc_obj)
        {
            $ret[$cc_obj->get_lang_id()] = $cc_obj;
        }

        return $ret;
    }

    public function get_cat_ext_obj($where=array())
    {
        return $this->get_ext_dao()->get($where);
    }

    public function get_cat_ext_list($where=array(), $option=array())
    {
        return $this->get_ext_dao()->get_list($where, $option);
    }

    public function get_category($where = array())
    {
        return $this->get_dao()->get($where);
    }

    public function get_cat_filter_grid_info($level, $where = array(), $option = array())
    {
        return $this->get_dao()->get_cat_filter_grid_info($level, $where, $option);
    }

    public function get_brand_filter_grid_info($where = array(), $option = array())
    {
        return $this->brand_service->get_brand_filter_grid_info($where, $option);
    }

    public function get_parent_cat_id($cat_id)
    {
        return $this->get_dao()->get_parent_cat_id($cat_id);
    }

    public function get_warranty_cat_list()
    {
        return $this->get_dao()->get_list(array("parent_cat_id"=>538), array("limit"=>-1, "orderby"=>"name ASC"));
    }

    public function get_cat_info_w_lang($where = array(), $option = array())
    {
        return $this->get_dao()->get_cat_info_w_lang($where, $option);
    }
}
