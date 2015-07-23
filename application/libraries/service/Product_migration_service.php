<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Product_migration_service extends Base_service
{
    private $pc_dao;
    private $serv;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Product_migrate_dao.php");
        $this->set_dao(new Product_migrate_dao());
        include_once(APPPATH . "libraries/dao/Product_content_dao.php");
        $this->set_pc_dao(new Product_content_dao());
        include_once(APPPATH . "libraries/dao/Category_dao.php");
        $this->set_cat_dao(new Category_dao());
        include_once(APPPATH . "libraries/dao/Sourcing_list_dao.php");
        $this->set_sl_dao(new Sourcing_list_dao());
        include_once(APPPATH . 'libraries/service/Class_factory_service.php');
        $this->set_class_factory_service(new Class_factory_service());
    }

    public function set_cat_dao(Base_dao $dao)
    {
        $this->cat_dao = $dao;
    }

    public function set_sl_dao(Base_dao $dao)
    {
        $this->sl_dao = $dao;
    }

    public function set_class_factory_service($serv)
    {
        $this->class_factory_service = $serv;
    }

    public function get_pc_dao()
    {
        return $this->pc_dao;
    }

    public function set_pc_dao(Base_dao $dao)
    {
        $this->pc_dao = $dao;
    }

    public function get_class_factory_service()
    {
        return $this->class_factory_service;
    }

    public function get_cat_dao()
    {
        return $this->cat_dao;
    }

    public function get_sl_dao()
    {
        return $this->sl_dao;
    }

    public function migrate_image()
    {
        echo "start time ";
        echo date("H:i:s");
        echo '<br>';
        set_time_limit(3000);
        $array = $this->get_dao()->get_migrate_images();
        $IMG_PH = '/var/www/html/valuebasket.com/admincentre/images/product/test/';
        $img_size_list = array(200, 70, 40);
        $img_size_char_list = array('_l', '_m', '_s');

        $count = 0;

        foreach ($array as $row) {
//if ($count++ > 10) break;

            $file = $row['file'];
            $sku = $row['id'];
            $search_file = "/var/www/html/valuebasket.com/ftp/$file";
            $search_file2 = "/var/www/html/valuebasket.com/old_image/products/$file";
            $real_file = '';


            if (is_file($search_file)) {
                $real_file = $search_file;
            } else if (is_file($search_file2)) {
                $real_file = $search_file2;
            } else {
                echo "$sku HAS NO IMAGE!!!! <BR>";
                continue;
            }

            //$ext = substr($res["file_ext"], 1);


            $ext = strtolower(substr(strrchr($real_file, '.'), 1));
            $dest_file = $IMG_PH . $sku . '.' . $ext;
            if (!copy($real_file, $dest_file)) {
                echo "$sku copy image fail! <BR>";
                continue;
            }

            $pic_info = getimagesize($dest_file);
            $size = 40;
            $mid_char = 'x';

            if ($pic_info[0] >= 400) {
                $size = 400;
            } else if ($pic_info[0] >= 200) {
                $size = 200;
            } else if ($pic_info[0] >= 70) {
                $size = 70;
            }

            watermark($IMG_PH . $sku . "." . $ext, "/var/www/html/valuebasket.com/admincentre/images/watermark{$size}{$mid_char}{$size}.png", "M", "C", "", "#000000");

            $i = 0;

            foreach ($img_size_list as $img_size) {
                if ($size > $img_size) {
                    thumbnail($IMG_PH . $sku . "." . $ext, $img_size_list[$i], $img_size_list[$i], $IMG_PH . $sku . $img_size_char_list[$i] . '.' . $ext);
                }

                $i++;
            }

            if ($size < 400) {
                @unlink($IMG_PH . $sku . "." . $ext);
            }

            $this->get_dao()->update_image($sku, $ext);


            //list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
            //thumbnail(IMG_PH.$sku.".".$ext, $width, $height, IMG_PH.$sku.".".$ext);
            /*foreach ($img_size as $size)
            {
                list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
                thumbnail(IMG_PH.$sku.".".$ext, $width, $height, IMG_PH.$sku."_{$size}.".$ext);
            }*/

        }

        echo 'end time ';
        echo date("H:i:s");

    }
}



