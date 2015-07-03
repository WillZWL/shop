<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function thumbnail($image, $max_width, $max_height, $dest)
{
    list($width, $height, $type) = getimagesize($image);
    if ($height > $max_height || $width > $max_width) {
        $ratio_height = $max_height / $height;
        $ratio_width = $max_width / $width;
        ($ratio_height < $ratio_width) ? $ratio = $ratio_height : $ratio = $ratio_width;
        $new_height = floor($height * $ratio);
        $new_width = floor($width * $ratio);
    } else {
        $new_height = $height;
        $new_width = $width;
        $ratio = 1;
    }

    switch ($type) {
        case IMAGETYPE_GIF:
            $thumb = imagecreatetruecolor($new_width, $new_height);
            $source = imagecreatefromgif($image);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagegif($thumb, $dest);
            imagedestroy($thumb);
            imagedestroy($source);
            break;
        case IMAGETYPE_JPEG:
            $thumb = imagecreatetruecolor($new_width, $new_height);
            $source = imagecreatefromjpeg($image);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($thumb, $dest, 90);
            imagedestroy($thumb);
            imagedestroy($source);
            break;
        case IMAGETYPE_PNG:
            $thumb = imagecreatetruecolor($new_width, $new_height);
            $source = imagecreatefrompng($image);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagepng($thumb, $dest);
            imagedestroy($thumb);
            imagedestroy($source);
            break;
    }
}

function watermark($image, $watermark, $valign = "B", $align = "R", $padding = 5, $transparent_color = "")
{
    list($i_width, $i_height, $i_type) = getimagesize($image);
    list($w_width, $w_height, $w_type) = getimagesize($watermark);
    $transparent_color = ltrim($transparent_color, "#");
    $ar_t_color = sscanf($transparent_color, '%2x%2x%2x');

    switch ($valign) {
        case "T"://Top
            $dest_y = $padding;
            break;
        case "M"://Middle
            $dest_y = $i_height / 2 - $w_height / 2;
            break;
        default://Bottom
            $dest_y = $i_height - $w_height - $padding;
            break;
    }
    switch ($align) {
        case "L"://Left
            $dest_x = $padding;
            break;
        case "C"://Center
            $dest_x = $i_width / 2 - $w_width / 2;
            break;
        default://Right
            $dest_x = $i_width - $w_width - $padding;
            break;
    }

    if ($i_type != IMAGETYPE_GIF) {
        switch ($w_type) {
            case IMAGETYPE_GIF:
                $w_source = imagecreatefromgif($watermark);
                break;
            case IMAGETYPE_JPEG:
                $w_source = imagecreatefromjpeg($watermark);
                break;
            case IMAGETYPE_PNG:
                $w_source = imagecreatefrompng($watermark);
                break;
        }
        if ($transparent_color) {
            $t_color = imagecolorallocate($w_source, $ar_t_color[0], $ar_t_color[1], $ar_t_color[2]);
            imagecolortransparent($w_source, $t_color);
        }
    }

    switch ($i_type) {
        case IMAGETYPE_GIF:
            $i_source = new Imagick($image);
            $w_source = new Imagick($watermark);
            foreach ($i_source as $frame) {
                $frame->compositeImage($w_source, Imagick::COMPOSITE_OVER, $dest_x, $dest_y);
            }
            $i_source->writeImages($image, true);
            break;
        case IMAGETYPE_JPEG:
            $i_source = imagecreatefromjpeg($image);
            imagecopy($i_source, $w_source, $dest_x, $dest_y, 0, 0, $w_width, $w_height);
            imagejpeg($i_source, $image);
            imagedestroy($i_source);
            imagedestroy($w_source);
            break;
        case IMAGETYPE_PNG:
            $i_source = imagecreatefrompng($image);
            imagecopy($i_source, $w_source, $dest_x, $dest_y, 0, 0, $w_width, $w_height);
            imagejpeg($i_source, $image);
            imagedestroy($i_source);
            imagedestroy($w_source);
            break;
    }
}

function get_image_file($name = "", $size = "", $sku = "", $id = "")
{
    $default_name = "imageunavailable";
    $default_ext = "jpg";
    $ar_file = explode(".", $name);
    $count_name = count($ar_file);
    $ext = $ar_file[$count_name - 1];
    $ar_image_name = explode("_", $ar_file[0]);
    $image_name = $sku ? $sku : $ar_image_name[0];
    if (empty($sku) && $image_name == $ext) {
        return "";
    }
    if ($size != "") {
        $size = "_" . $size;
    }
    if ($id != "") {
        $id = "_" . $id;
    }
    $filename = $image_name . $id . $size . "." . $ext;
    $path = "../public_html/images/product/";
    $admin_path = "../admincentre/images/product/";
    $file_exist = is_file($path . $filename) || is_file($admin_path . $filename);
    $rs_file = "/images/product/" . ($file_exist ? $filename : $default_name . $size . "." . $default_ext);

    return $rs_file;
}

function get_banner_file($name = "", $ext = "")
{
    if (empty($name) && empty($ext)) {
        return "";
    }

    $filename = $name . "." . $ext;
    $path = "../public_html/images/product_banner/";
    $admin_path = "../admincentre/images/product_banner/";
    $file_exist = is_file($path . $filename) || is_file($admin_path . $filename);

    if ($file_exist) {
        $banner_filepath = "/images/product_banner/" . $filename;
        return $banner_filepath;
    } else
        return "";
}


