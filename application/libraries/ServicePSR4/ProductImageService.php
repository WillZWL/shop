<?php

namespace ESG\Panther\Service;

class ProductImageService extends BaseService
{
    public function createNewProductImage($sku, $oldObj)
    {
        if (!$this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $newObj = new \ProductImageVo();
        $newObj->setSku($sku);
        $this->updateProductImage($newObj, $oldObj);

        return $newObj;
    }

    public function updateProductImage($newObj, $oldObj)
    {
        $newObj->setPriority((string) $oldObj->priority);
        $newObj->setVbImage((string) $oldObj->sku.'_'.(string) $oldObj->id.'.'.(string) $oldObj->image);
        $newObj->setImage((string) $oldObj->image);
        $newObj->setAltText($newObj->getSku().'_'.(string) $oldObj->id.'.'.(string) $oldObj->image);
        $newObj->setVbAltText((string) $oldObj->alt_text);
        $newObj->setStatus((string) $oldObj->status);
    }

    public function transferImages()
    {
        //get the pending images
        $image_list = $this->getDao('ProductImage')->getPendingImages();

        //var_dump($image_list);exit;
        if ($image_list !== false) {
            foreach ($image_list as $img) {
                $min_priority = $img->min_priority;
                $img_priority = $img->priority;

                $id = $img->id;

                //save VB images
                $img_size = array('l', 'm', 's');

                //get the image from VB
                $file = 'http://www.valuebasket.com/images/product/'.$img->vb_image;
                //$file = "http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg";
                $file_headers = @get_headers($file);
                if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    $file_exist = false;
                } else {
                    $file_exist = true;
                }

                if ($file_exist) {
                    $imgpath = FCPATH.'../public_html/'.$this->getService('ContextConfig')->valueOf('prod_img_path');
                    $img_local = $img->sku.'_'.$img->id.'.'.$img->image;

                    //delete old images
                    $file_old = file_exists($imgpath.$img_local);
                    if ($file_old) {
                        @unlink($imgpath.$img_local);
                    }

                    //save VB image in AtomV2
                    $image_content = file_get_contents($file);
                    //$image_content = file_get_contents("http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg");
                    if (file_put_contents($imgpath.$img_local, $image_content) === false) {
                        continue;
                    }

                    list($width, $height) = explode('x', $this->getService('ContextConfig')->valueOf('thumb_w_x_h'));
                    //thumbnail($imgpath . $sku . "_" . $new_id . "." . $pc->image, $width, $height, $imgpath . $sku . "_" . $new_id . "." . $pc->image);
                    thumbnail($imgpath.$img_local, $width, $height, $imgpath.$img_local);

                    foreach ($img_size as $size) {
                        //delete old images
                        $img_old = is_file($imgpath.$img->sku.'_'.$img->id."_{$size}.".$img->image);
                        //print "   " . $imgpath . $img->sku . "_" . $img->id . "_{$size}." . $img->image . "   ";
                        if ($img_old) {
                            @unlink($imgpath.$img->sku.'_'.$img->id."_{$size}.".$img->image);
                        }

                        list($width, $height) = explode('x', $this->getService('ContextConfig')->valueOf("thumb_{$size}_w_x_h"));
                        thumbnail($imgpath.$img->sku.'_'.$img->id.'.'.$img->image, $width, $height, $imgpath.$img->sku.'_'.$img->id."_{$size}.".$img->image);
                    }

                    //if it is the image with the minimun priority, we also save the image without the id
                    if ($min_priority == $img_priority) {
                        //delete old images
                        $file_old = file_exists($imgpath.$img->sku.'.'.$img->image);
                        if ($file_old) {
                            @unlink($imgpath.$img->sku.'.'.$img->image);
                        }

                        //save VB image in AtomV2
                        $vars = explode('_', $img->vb_image);
                        $VB_sku = $vars[0];
                        $vars2 = explode('.', $img->vb_image);
                        $ext = $vars2[count($vars2) - 1];

                        $image_content = file_get_contents('http://www.valuebasket.com/images/product/'.$VB_sku.'.'.$ext);
                        if (file_put_contents($imgpath.$img->sku.'.'.$img->image, $image_content) === false) {
                            continue;
                        }

                        list($width, $height) = explode('x', $this->getService('ContextConfig')->valueOf('thumb_w_x_h'));
                        thumbnail($imgpath.$img->sku.'.'.$img->image, $width, $height, $imgpath.$img->sku.'.'.$img->image);

                        foreach ($img_size as $size) {
                            //delete old images
                            $img_old = is_file($imgpath.$img->sku."_{$size}.".$img->image);
                            if ($img_old) {
                                @unlink($imgpath.$img->sku."_{$size}.".$img->image);
                            }

                            list($width, $height) = explode('x', $this->getService('ContextConfig')->valueOf("thumb_{$size}_w_x_h"));
                            thumbnail($imgpath.$img->sku.'.'.$img->image, $width, $height, $imgpath.$img->sku."_{$size}.".$img->image);
                        }
                    }
                }
                $pi_obj = $this->getDao('ProductImage')->get(array('id' => $id));

                //update the product_image table
                $pi_obj->setImageSaved(1);
                $pi_obj->setAltText($img_local);
                $this->getDao('ProductImage')->update($pi_obj);
            }
        }

        return count($image_list);
    }
}
