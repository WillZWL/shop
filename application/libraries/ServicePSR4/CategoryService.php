<?php
namespace AtomV2\Service;

use AtomV2\Dao\CategoryDao;
use AtomV2\Dao\ColourDao;
use AtomV2\Dao\CategoryExtendDao;
use AtomV2\Dao\CategoryContentDao;
use AtomV2\Dao\CategoryBannerDao;
use AtomV2\Service\BrandService;

class CategoryService extends BaseService
{
    private $brand_service;
    private $ext_dao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new CategoryDao);
        $this->setCategoryExtendDao(new CategoryExtendDao);
        $this->setCategoryContentDao(new CategoryContentDao);
        $this->setColourDao(new ColourDao);
        $this->setCategoryBannerDao(new CategoryBannerDao);
        $this->brandService = new BrandService;
    }

    public function setColourDao($value)
    {
        $this->colourDao = $value;
    }

    public function getColourDao()
    {
        return $this->colourDao;
    }

    public function setCategoryContentDao($dao)
    {
        $this->cc_dao = $dao;
    }

    public function setCategoryBannerDao($dao)
    {
        $this->cat_ban_dao = $dao;
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
            $n_search = array("  ", " ");
            $n_replace = array(" ", "-");

            if ($menulist[1][0]) {
                $i = 0;
                foreach ($menulist[1][0] as $cat_obj) {
                    $name = str_replace($n_search, $n_replace, parse_url_char($cat_obj->get_name()));
                    $data["menu"][$i]["cat_id"] = $cat_obj->getId();
                    $data["menu"][$i]["display_name"] = $cat_obj->get_name();
                    $data["menu"][$i]["name"] = $name;
                    $data["menu"][$i]["link"] = "{$name}/cat/?catid={$cat_obj->getId()}";
                    if ($allcatlist[$cat_obj->getId()]) {
                        $j = 0;
                        foreach ($allcatlist[$cat_obj->getId()] as $subcat_obj) {
                            $name = str_replace($n_search, $n_replace, parse_url_char($subcat_obj->get_name()));
                            $data["sub_menu"][$i][$j]["display_name"] = $subcat_obj->get_name();
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
        return $this->getDao()->getMenuListWithPlatformId($lang_id, $platform_id);
    }

    public function getMenuListWithLang($lang_id)
    {
        return $this->getDao()->getMenuListWithLang($lang_id);
    }

    public function getListWithChildCount($level, $id = "", $classname = "Category_count_dto")
    {
        return $this->getDao()->getItemWithChildCount($level, $id, $classname);
    }

    public function getItemWithPopChildCount($level, $id = "")
    {
        return $this->getDao()->getItemWithPopChildCount($level, $id, "Category_count_dto");
    }

    public function getParent($level, $id, $classname = "View_sub_cat_dto")
    {
        return $this->getDao()->getParent($level, $id, $classname);
    }

    public function add($obj)
    {
        return $this->getDao()->insert($obj);
    }

    public function load_vo()
    {
        $this->getDao()->include_vo();
    }

    public function getCatListIndex($where, $option)
    {
        $data["category_list"] = $this->getDao()->getListIndex($where, $option, $this->getDao()->getVoClassname());

        $data["total"] = $this->getDao()->getListIndex($where, array("num_rows" => 1));
        return $data;
    }

    public function getMenuList($where = [], $option = [])
    {
        $data["list"] = $data["allcat"] = [];
        $objlist = $this->getDao()->getList($where, $option);

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
        $obj = $this->getDao()->get(["id" => $catid]);
        if ($obj === FALSE) {
            return FALSE;
        }

        if (empty($obj)) {
            return NULL;
        } else {
            if ($obj->getLevel() == 1) {
                if ($type == "cat") {
                    return $this->getDao()->retrieveCatlistForScat($catid, $brand, $platform_id);
                } elseif ($type == "price") {
                    return $this->getDao()->retrievePricelistForCat($catid, $brand, $platform_id, $min_price, $max_price);
                } else {
                    return $this->getDao()->retrieveBrandlistForCat($catid, $brand, $platform_id);
                }
            } else if ($obj->getLevel() == 2) {
                if ($type == "cat") {
                    return $this->getDao()->retrieveCatlistForSscat($catid, $brand, $platform_id);
                } elseif ($type == "price") {
                    return $this->getDao()->retrievePricelistForScat($catid, $brand, $platform_id, $min_price, $max_price);
                } else {
                    return $this->getDao()->retrieveBrandlistForScat($catid, $brand, $platform_id);
                }

            } else if ($obj->getLevel() == 3) {
                if ($type == "cat") {
                    return NULL;
                } elseif ($type == "price") {
                    return $this->getDao()->retrievePricelistForSscat($catid, $brand, $platform_id, $min_price, $max_price);
                } else {
                    return $this->getDao()->retrieveBrandlistForSscat($catid, $brand, $platform_id);
                }
            } else {
                return NULL;
            }
        }
    }

    public function getDisplayCatlist($catid, $data = [])
    {
        $obj = $this->getDao()->get(array("id" => $catid));
        $data[$obj->getLevel()] = array("name" => $obj->get_name(), "id" => $obj->getId());
        if ($obj->getLevel() == 1) {
            return $data;
        } else {
            return $this->getDisplayCatlist($obj->getParentCatId(), $data);
        }
    }

    public function getColourList()
    {
        $list = $this->getColourDao()->getList();

        $ret = [];

        foreach ($list as $obj) {
            $ret[$obj->getColourId()] = $obj->getName();
        }

        return $ret;
    }

    public function getListedCat($platform_id = "")
    {
        return $this->getDao()->getListedCat($platform_id);
    }

    public function getFullCatList()
    {
        return $this->getDao()->getFullCatList();
    }

    public function getListedCatTree()
    {
        $sitemap = [];
        $depth = 0;

        $list = $this->getDao()->getList(array("level" => 1), array("result_type" => "array"));

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
            $row = $this->getDao()->getNumRows();
            $list = $this->getDao()->getList();
            foreach ($list as $obj) {
                if ($obj->getLevel() == 1)
                    echo $obj->getId() . "<br>";
            }
        }

        $cat_list = $this->getDao()->getCatListWithLang(get_lang_id());
        $sitemap = [];
        foreach ($cat_list AS $cat_arr) {
            $sitemap[$cat_arr["id"]] = $cat_arr["name"];
        }

        $array = $this->getDao()->getListedCatTree(get_lang_id());
        $ret = [];
        foreach ($array AS $obj) {
            $ret[$obj["cat_name"]][$obj["sub_cat_name"]];
        }

        $cat_list = [];
        $cat = [];
        $sub_cat_list = [];
        $sub_cat = [];

        foreach ($array as $row) {
            $sub_sub_cat = array('sub_sub_cat_id' => $row['sub_sub_cat_id'], 'sub_sub_cat_name' => $row['sub_sub_cat_name']);


            if ($cat['cat_id'] != $row['cat_id']) {
                if (!empty($cat['cat_id'])) {
                    $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
                    array_push($sub_cat_list, $sub_cat);
                    $cat['sub_cat_list'] = $sub_cat_list;
                    $cat['brand_list'] = $this->brandService->getListedBrandByCat($cat['cat_id']);
                    array_push($cat_list, $cat);
                }

                $sub_sub_cat_list = [];
                $sub_cat = array('sub_cat_id' => $row['sub_cat_id'],
                    'sub_cat_name' => $row['sub_cat_name']);
                $sub_cat_list = [];
                $cat = array('cat_id' => $row['cat_id'],
                    'cat_name' => $row['cat_name']);
            } else if ($sub_cat['sub_cat_id'] != $row['sub_cat_id']) {
                $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
                array_push($sub_cat_list, $sub_cat);

                $sub_sub_cat_list = [];
                $sub_cat = array('sub_cat_id' => $row['sub_cat_id'],
                    'sub_cat_name' => $row['sub_cat_name']);
            }

            array_push($sub_sub_cat_list, $sub_sub_cat);
        }

        $sub_cat['sub_sub_cat_list'] = $sub_sub_cat_list;
        array_push($sub_cat_list, $sub_cat);
        $cat['sub_cat_list'] = $sub_cat_list;
        $cat['brand_list'] = $this->brandService->getListedBrandByCat($cat['cat_id']);
        array_push($cat_list, $cat);

        return array('cat_list' => $cat_list);
    }

    private function buildCategoryTree($me, $parentID)
    {
        $list = $this->getDao()->getList(array("parent_cat_id" => $parentID), array("result_type" => "array"));

        $tempTree = NULL;
        foreach ($list AS $child) {
            if ($child['cat_id'] != $child['parent_cat_id']) {
                //$depth++;     // Increment depth as we are building this child's child tree

                $tempTree[$child['id']]["name"] = $child['name'];
                $tempTree[$child['id']]["child"] = $this->buildCategoryTree($tempTree[$child['cat_id']], $id);

                //$depth--;     // Decrement depth we're done building the child's child tree.
                //array_push($exclude, $child['id']);           // Add the item to the exclusion list
            }
        }

        return $tempTree;       // Return the entire child tree
    }

    public function getFavouriteCategoryList($platform_id = "WSGB")
    {
        return $this->getDao()->getFavouriteCategoryList(20, $platform_id);
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
        if ($obj_list = $this->getCategoryExtendDao()->getList($where, $option)) {
            $data = [];
            foreach ($obj_list as $obj) {
                $data[$obj->get_cat_id()][$obj->get_lang_id()] = $obj;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCategoryExtendDao()
    {
        return $this->ext_dao;
    }

    public function setCategoryExtendDao($dao)
    {
        $this->ext_dao = $dao;
    }

    public function getBestSellingCat($platform = "WEBGB", $lang_id = "en")
    {
        return $this->getDao()->getBestSellingCat($platform, $lang_id);
    }

    public function getCatExtDefaultWithKeyList($where = [], $option = [])
    {
        return $this->getCategoryExtendDao()->getCatExtDefaultWithKeyList($where, $option);
    }

    public function getCatBan($where)
    {
        return $this->getCategoryBannerDao()->get($where);
    }

    public function getCategoryBannerDao()
    {
        return $this->cat_ban_dao;
    }

    public function getCatBanList($lang_id)
    {
        return $this->getCategoryBannerDao()->getCatBanList($lang_id);
    }

    public function insertCatBan($obj)
    {
        return $this->getCategoryBannerDao()->insert($obj);
    }

    public function updateCatBan($obj)
    {
        return $this->getCategoryBannerDao()->update($obj);
    }

    public function getCatContObj($where = [])
    {
        return $this->getCategoryContentDao()->get($where);
    }

    public function getCategoryContentDao()
    {
        return $this->cc_dao;
    }

    public function getCatContList($where = [], $option = [])
    {
        $cc_list = $this->getCategoryContentDao()->getList($where, $option);
        foreach ($cc_list AS $cc_obj) {
            $ret[$cc_obj->get_lang_id()] = $cc_obj;
        }

        return $ret;
    }

    public function getCatExtObj($where = [])
    {
        return $this->getCategoryExtendDao()->get($where);
    }

    public function getCatExtList($where = [], $option = [])
    {
        return $this->getCategoryExtendDao()->getList($where, $option);
    }

    public function getCategory($where = [])
    {
        return $this->getDao()->get($where);
    }

    public function getCatFilterGridInfo($level, $where = [], $option = [])
    {
        return $this->getDao()->getCatFilterGridInfo($level, $where, $option);
    }

    public function getBrandFilterGridInfo($where = [], $option = [])
    {
        return $this->brand_service->getBrandFilterGridInfo($where, $option);
    }

    public function getParentCatId($cat_id)
    {
        return $this->getDao()->getParentCatId($cat_id);
    }

    public function getWarrantyCatList()
    {
        return $this->getDao()->getList(array("parent_cat_id" => 538), array("limit" => -1, "orderby" => "name ASC"));
    }

    public function getCatInfoWithLang($where = [], $option = [])
    {
        return $this->getDao()->getCatInfoWithLang($where, $option);
    }
}
