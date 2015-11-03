<?php
namespace ESG\Panther\Service;

class CategoryService extends BaseService
{

    public function getCategoryName()
    {
        $category = $this->getDao('Category')->getCategoryName();

        foreach ($category as $cat) {
            $category_mapping[$cat['id']] = str_replace(' ', '-', parse_url_char($cat['name']));
        }

        return $category_mapping;
    }

    public function getCatUrl($cat_id)
    {
        $where = ['c1.id' => $cat_id];
        $option = ['limit' => 1];
        $obj = $this->getCategoryFullPath($where, $option);

        $url = base_url();
        switch ($obj->getLevel()) {
            case '3':
                $url .= str_replace(' ', '-', parse_url_char($obj->getTopTopName())).'/';
                $url .= str_replace(' ', '-', parse_url_char($obj->getTopName())).'/';
                $url .= str_replace(' ', '-', parse_url_char($obj->getName())).'/';
                break;
            case '2':
                $url .= str_replace(' ', '-', parse_url_char($obj->getTopName())).'/';
                $url .= str_replace(' ', '-', parse_url_char($obj->getName())).'/';
                break;
            case '1':
                $url .= str_replace(' ', '-', parse_url_char($obj->getName())).'/';
                break;
            default:
                break;
        }

        return $url;
    }

    private function getCategoryFullPath($where, $option = [])
    {
        return $this->getDao('Category')->getCategoryFullPath($where, $option);
    }

    public function getMenuListData($lang_id, $platform_id)
    {
        $data = [];
        $this->load->helper('url');
        if (!($menu_list = $this->getMenuListWithPlatformId($lang_id, $platform_id))) {
            $menu_list = $this->getMenuListWithLang($lang_id);
        }

        if ($menu_list) {
            $menulist = $menu_list["list"];
            $allcatlist = $menu_list["allcat"];
            $n_search = ["  ", " "];
            $n_replace = [" ", "-"];

            if ($menulist[1][0]) {
                $i = 0;
                foreach ($menulist[1][0] as $cat_obj) {
                    $name = str_replace($n_search, $n_replace, parse_url_char($cat_obj->getName()));
                    $data["menu"][$i]["cat_id"] = $cat_obj->getId();
                    $data["menu"][$i]["display_name"] = $cat_obj->getName();
                    $data["menu"][$i]["name"] = $name;
                    $data["menu"][$i]["link"] = "{$name}/cat/?catid={$cat_obj->getId()}";
                    if ($allcatlist[$cat_obj->getId()]) {
                        $j = 0;
                        foreach ($allcatlist[$cat_obj->getId()] as $subcat_obj) {
                            $name = str_replace($n_search, $n_replace, parse_url_char($subcat_obj->getName()));
                            $data["sub_menu"][$i][$j]["display_name"] = $subcat_obj->getName();
                            $data["sub_menu"][$i][$j]["name"] = $name;
                            $data["sub_menu"][$i][$j]["link"] = "search/?from=c&catid=" . $subcat_obj->getId();
                            $j++;
                        }
                    }
                    $i++;
                }
            }
        }

        return $data;

    }

    public function getMenuListWithPlatformId($lang_id = "", $platform_id = "")
    {
        return $this->getDao('Category')->getMenuListWithPlatformId($lang_id, $platform_id);
    }

    public function getMenuListWithLang($lang_id)
    {
        return $this->getDao('Category')->getMenuListWithLang($lang_id);
    }

    public function getListWithChildCount($level, $id = "", $classname = "CategoryCountDto")
    {
        return $this->getDao('Category')->getItemWithChildCount($level, $id, $classname);
    }

    public function getItemWithPopChildCount($level, $id = "")
    {
        return $this->getDao('Category')->getItemWithPopChildCount($level, $id, "CategoryCountDto");
    }

    public function getParent($level, $id, $classname = "ViewSubCatDto")
    {
        return $this->getDao('Category')->getParent($level, $id, $classname);
    }

    public function add($obj)
    {
        return $this->getDao('Category')->insert($obj);
    }

    public function update($obj)
    {
        return $this->getDao('Category')->update($obj);
    }

    public function loadVo()
    {
        $this->getDao('Category')->get();
    }

    public function getCatListIndex($where, $option)
    {
        $data["category_list"] = $this->getDao('Category')->getListIndex($where, $option, $this->getDao('Category')->getVoClassname());

        $data["total"] = $this->getDao('Category')->getListIndex($where, ["num_rows" => 1]);
        return $data;
    }

    public function getMenuList($where = [], $option = [])
    {
        $data["list"] = $data["allcat"] = [];
        $objlist = $this->getDao('Category')->getList($where, $option);

        if ($objlist) {
            foreach ($objlist as $obj) {
                $data["list"][$obj->getLevel()][$obj->getParentCatId()][] = $obj;
                $data["allcat"][$obj->getParentCatId()][] = $obj;
            }
        }

        return $data;
    }

    public function getDisplayList($catid, $type = "cat", $brand = "", $platform_id = "WSGB", $min_price = "", $max_price = "")
    {
        $obj = $this->getDao('Category')->get(["id" => $catid]);
        if ($obj === FALSE) {
            return FALSE;
        }

        if (empty($obj)) {
            return NULL;
        } else {
            if ($obj->getLevel() == 1) {
                if ($type == "cat") {
                    return $this->getDao('Category')->retrieveCatlistForScat($catid, $brand, $platform_id);
                } elseif ($type == "price") {
                    return $this->getDao('Category')->retrievePricelistForCat($catid, $brand, $platform_id, $min_price, $max_price);
                } else {
                    return $this->getDao('Category')->retrieveBrandlistForCat($catid, $brand, $platform_id);
                }
            } else if ($obj->getLevel() == 2) {
                if ($type == "cat") {
                    return $this->getDao('Category')->retrieveCatlistForSscat($catid, $brand, $platform_id);
                } elseif ($type == "price") {
                    return $this->getDao('Category')->retrievePricelistForScat($catid, $brand, $platform_id, $min_price, $max_price);
                } else {
                    return $this->getDao('Category')->retrieveBrandlistForScat($catid, $brand, $platform_id);
                }

            } else if ($obj->getLevel() == 3) {
                if ($type == "cat") {
                    return NULL;
                } elseif ($type == "price") {
                    return $this->getDao('Category')->retrievePricelistForSscat($catid, $brand, $platform_id, $min_price, $max_price);
                } else {
                    return $this->getDao('Category')->retrieveBrandlistForSscat($catid, $brand, $platform_id);
                }
            } else {
                return NULL;
            }
        }
    }

    public function getDisplayCatlist($catid, $data = [])
    {
        $obj = $this->getDao('Category')->get(["id" => $catid]);
        $data[$obj->getLevel()] = ["name" => $obj->getName(), "id" => $obj->getId()];
        if ($obj->getLevel() == 1) {
            return $data;
        } else {
            return $this->getDisplayCatlist($obj->getParentCatId(), $data);
        }
    }

    public function getColourList()
    {
        $list = $this->getDao('Colour')->getList();

        $ret = [];

        foreach ($list as $obj) {
            $ret[$obj->getColourId()] = $obj->getName();
        }

        return $ret;
    }

    public function getListedCat($platform_id = "")
    {
        return $this->getDao('Category')->getListedCat($platform_id);
    }

    public function getFullCatList()
    {
        return $this->getDao('Category')->getFullCatList();
    }

    public function getListedCatTree()
    {
        $sitemap = [];
        $depth = 0;

        $list = $this->getDao('Category')->getList(["level" => 1], ["result_type" => "array"]);

        $sitemap = [];
        foreach ($list AS $item) {
            $id = $item["id"];
            if ($id != $item["parent_cat_id"]) {
                $sitemap[$id] = [];
                $sitemap[$id]["name"] = $item["name"];
                $sitemap[$id]["child"] = $this->buildCategoryTree($sitemap[$id], $id);
            }
        }

        return;

        echo "<table>";

        $i = 0;
        foreach ($sitemap as $mk => $maincat) {
            if (($i % 3) == 0) echo "<tr>";

            echo "<td>";
            echo "<a href=\"cat/?catid={$mk}\">";
            echo $maincat['name'];
            echo "</a><br>";

            foreach ($maincat['child'] as $sk => $subcat)
                echo "<a href=\"cat/?from=c&catid={$sk}\">";
            echo " - " . $subcat['name'];
            echo "</a><br>";

            echo "</td>";

            if (($i % 3) == 2) echo "</tr>";

            $i++;

        }

        if (1 == 0) {
            $row = $this->getDao('Category')->getNumRows();
            $list = $this->getDao('Category')->getList();
            foreach ($list as $obj) {
                if ($obj->getLevel() == 1)
                    echo $obj->getId() . "<br>";
            }
        }

        $cat_list = $this->getDao('Category')->getCatListWithLang(get_lang_id());
        $sitemap = [];
        foreach ($cat_list AS $cat_arr) {
            $sitemap[$cat_arr["id"]] = $cat_arr["name"];
        }

        $array = $this->getDao('Category')->getListedCatTree(get_lang_id());
        $ret = [];
        foreach ($array AS $obj) {
            $ret[$obj["cat_name"]][$obj["sub_cat_name"]];
        }

        $cat_list = [];
        $cat = [];
        $sub_cat_list = [];
        $sub_cat = [];

        foreach ($array as $row) {
            $sub_sub_cat = ['sub_sub_cat_id' => $row['sub_sub_cat_id'], 'sub_sub_cat_name' => $row['sub_sub_cat_name']];


            if ($cat['cat_id'] != $row['cat_id']) {
                if (!empty($cat['cat_id'])) {
                    $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
                    array_push($sub_cat_list, $sub_cat);
                    $cat['sub_cat_list'] = $sub_cat_list;
                    $cat['brand_list'] = $this->getDao('Brand')->getListedBrandByCat($cat['cat_id']);
                    array_push($cat_list, $cat);
                }

                $sub_sub_cat_list = [];
                $sub_cat = ['sub_cat_id' => $row['sub_cat_id'], 'sub_cat_name' => $row['sub_cat_name']];
                $sub_cat_list = [];
                $cat = ['cat_id' => $row['cat_id'], 'cat_name' => $row['cat_name']];
            } else if ($sub_cat['sub_cat_id'] != $row['sub_cat_id']) {
                $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
                array_push($sub_cat_list, $sub_cat);

                $sub_sub_cat_list = [];
                $sub_cat = ['sub_cat_id' => $row['sub_cat_id'], 'sub_cat_name' => $row['sub_cat_name']];
            }

            array_push($sub_sub_cat_list, $sub_sub_cat);
        }

        $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
        array_push($sub_cat_list, $sub_cat);
        $cat['sub_cat_list'] = $sub_cat_list;
        $cat['brand_list'] = $this->getDao('Brand')->getListedBrandByCat($cat['cat_id']);
        array_push($cat_list, $cat);

        return ['cat_list' => $cat_list];
    }

    private function buildCategoryTree($me, $parentID)
    {
        $list = $this->getDao('Category')->getList(["parent_cat_id" => $parentID], ["result_type" => "array"]);

        $tempTree = NULL;
        foreach ($list AS $child) {
            if ($child['cat_id'] != $child['parent_cat_id']) {

                $tempTree[$child['id']]["name"] = $child['name'];
                $tempTree[$child['id']]["child"] = $this->buildCategoryTree($tempTree[$child['cat_id']], $id);

            }
        }

        return $tempTree;       // Return the entire child tree
    }

    public function getFavouriteCategoryList($platform_id = "WSGB")
    {
        return $this->getDao('Category')->getFavouriteCategoryList(20, $platform_id);
    }

    public function getListWithKey($where = [], $option = [])
    {
        $data = [];
        if ($objlist = $this->getList($where, $option)) {
            foreach ($objlist as $obj) {
                $data[$obj->getId()] = $obj;
            }
        }
        return $data;
    }

    public function getNameListWithIdKey($where = [], $option = [])
    {
        $option["result_type"] = "array";
        $rslist = [];
        if ($ar_list = $this->getList($where, $option)) {
            foreach ($ar_list as $rsdata) {
                $rslist[$rsdata["id"]] = $rsdata["name"];
            }
        }
        return $rslist;
    }

    public function getCatExtWithKey($where = [], $option = [])
    {
        if ($obj_list = $this->getDao('CategoryExtend')->getList($where, $option)) {
            $data = [];
            foreach ($obj_list as $obj) {
                $data[$obj->getCatId()][$obj->getLangId()] = $obj;
            }
            return $data;
        }
        return FALSE;
    }

    public function getBestSellingCat($platform = "WEBGB", $lang_id = "en")
    {
        return $this->getDao('Category')->getBestSellingCat($platform, $lang_id);
    }

    public function getCatExtDefaultWithKeyList($where = [], $option = [])
    {
        return $this->getDao('CategoryExtend')->getCatExtDefaultWithKeyList($where, $option);
    }

    public function getCatBan($where)
    {
        return $this->getDao('CategoryBanner')->get($where);
    }

    public function getCategoryBannerDao()
    {
        return $this->cat_ban_dao;
    }

    public function getCatBanList($lang_id)
    {
        return $this->getDao('CategoryBanner')->getCatBanList($lang_id);
    }

    public function insertCatBan($obj)
    {
        return $this->getDao('CategoryBanner')->insert($obj);
    }

    public function updateCatBan($obj)
    {
        return $this->getDao('CategoryBanner')->update($obj);
    }

    public function getCatContObj($where = [])
    {
        return $this->getDao('CategoryContent')->get($where);
    }

    public function getCatContList($where = [], $option = [])
    {
        $ret = [];
        $cc_list = $this->getDao('CategoryContent')->getList($where, $option);
        foreach ($cc_list AS $cc_obj) {
            $ret[$cc_obj->getLangId()] = $cc_obj;
        }

        return $ret;
    }

    public function getCatExtObj($where = [])
    {
        return $this->getDao('CategoryExtend')->get($where);
    }

    public function getCatExtList($where = [], $option = [])
    {
        return $this->getDao('CategoryExtend')->getList($where, $option);
    }

    public function getCategory($where = [])
    {
        return $this->getDao('Category')->get($where);
    }

    public function getCatFilterGridInfo($level, $where = [], $option = [])
    {
        return $this->getDao('Category')->getCatFilterGridInfo($level, $where, $option);
    }

    public function getBrandFilterGridInfo($where = [], $option = [])
    {
        return $this->getDao('Brand')->getBrandFilterGridInfo($where, $option);
    }

    public function getParentCatId($cat_id)
    {
        return $this->getDao('Category')->getParentCatId($cat_id);
    }

    public function getWarrantyCatList()
    {
        return $this->getDao('Category')->getList(["parent_cat_id" => 538], ["limit" => -1, "orderby" => "name ASC"]);
    }

    public function getCatInfoWithLang($where = [], $option = [])
    {
        return $this->getDao('Category')->getCatInfoWithLang($where, $option);
    }
}
